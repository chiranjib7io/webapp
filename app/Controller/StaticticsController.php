<?php
// This is statistics controller. All the statistical calculation done in this controller.  
App::uses('CakeEmail', 'Network/Email');
class StaticticsController extends AppController {
	// List of models which are used in the statistics controller 
	var $uses = array('User','Organization','Region','Branch','Kendra','Customer','Loan','Saving','Idproof','LogRecord','Country', 'Setting', 'Fee','Market');

	// This is a blank index function for safe navigation
    public function index() {
		
    }
	
	// Number of customer of a organization calculation function start
	public function no_of_customer(){
       $this->set('option_val', '1');
       $start_date=date("Y-m-d", strtotime("-7 days"));
	   $end_date= date ("Y-m-d");
       if ($this->request->is('post')) {
			$option_val=$this->request->data['User']['selectdate'];
			$this->set('option_val', $option_val);
			if($option_val==1){
				$start_date=date("Y-m-d", strtotime("-7 days"));
				$end_date= date ("Y-m-d");
			}
			if($option_val==2){
				$start_date=date("Y-m-d", strtotime("-14 days"));
				$end_date= date("Y-m-d", strtotime("-7 days"));
			}
			if($option_val==3){
				$start_date=date("Y-m-d", strtotime("-30 days"));
				$end_date= date ("Y-m-d");
			}
			if($option_val==4){
				$postarray=$this->request->data;
				$daterange=explode('-', $this->request->data['datefilter']);
				$postarray['start_date']= trim($daterange[0]);
				$postarray['end_date']= trim($daterange[1]);
				$send_date['start_date']=$postarray['start_date'];
				$send_date['end_date']=$postarray['end_date'];
				$this->set('send_date', $send_date);
				$start_date=date("Y-m-d", strtotime($postarray['start_date']));
				$end_date=date("Y-m-d", strtotime($postarray['end_date']));
			}
		}
		$send_date['date_diff']= $this->date_differ($start_date,$end_date);
		$send_date['start_date']=$start_date;
		$send_date['end_date']=$end_date;
		$this->set('send_date', $send_date); 
		$this->set('title', 'No. of Customer Statistics');  // This is used for Title for every page
		$this->layout = 'panel_layout';
		$no_of_cust_array=array();
		// Data which are using in all the parts
		$user_data= $this->User->find('all',array('conditions'=>array('User.id'=>$this->Auth->user('id'))));
        $branch_list= $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.organization_id'=>$this->Auth->user('organization_id'))));
		$organization_id=$this->Auth->user('organization_id');  // Organization Id
		$total_customer=$this->Customer->find('count',array('conditions'=>array(
			'Customer.organization_id'=>$organization_id,
			'Customer.status'=>1,
			'Customer.created_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\''))); // Calculate Total Customer
		// New Customer Calculation
		$new_cust = $this->Customer->find('count', array(
			'conditions' => array(
				  'Customer.organization_id'=>$organization_id,
				  'Customer.status'=>1,
				  'Customer.created_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\''
			)
		));
		// Avarage Loan Amount
		$avarage_loan_amount_array=$this->Loan->find('all',array(
			'fields'=>array(
				'AVG(Loan.loan_principal) as avg_loan'
			),
			'conditions'=>array(
				'Loan.organization_id'=>$organization_id,
				'Loan.loan_status_id'=>3,
				'Loan.status'=>1,
				'Loan.created_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\''
		)));
		$avarage_loan_amount=round($avarage_loan_amount_array[0][0]['avg_loan'], 2);
		$customer_left=$this->Customer->find('count',array('conditions'=>array('Customer.organization_id'=>$organization_id, 'Customer.status'=>0,'Customer.created_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'' ))); // Calculate Customer Left
		// Find Maximum Duration of a Customer
		$min_customer_array=$this->Customer->find('all',array(
			'fields'=>array(
			'MIN(Customer.created_on) as min_cust'
		),
			'conditions'=>array(
				'Customer.organization_id'=>$organization_id,
				'Customer.status'=>1,
                'Customer.created_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\''
		)));
		$date1=$min_customer_array[0][0]['min_cust'];
		$diff = abs(strtotime($end_date) - strtotime($date1));
		$max_duration_days = floor($diff / (60*60*24));
		// Highest Loan Amount
		$max_loan_amount_array=$this->Loan->find('all',array(
			'fields'=>array(
				'MAX(Loan.loan_principal) as max_loan'
			),
			'conditions'=>array(
				'Loan.organization_id'=>$organization_id,
				'Loan.loan_status_id'=>3,
				'Loan.status'=>1,
                'Loan.created_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\''
		)));
		$max_loan_amount=round($max_loan_amount_array[0][0]['max_loan'], 2);
		// Lowest Loan Amount
		$min_loan_amount_array=$this->Loan->find('all',array(
			'fields'=>array(
				'MIN(Loan.loan_principal) as min_loan'
			),
			'conditions'=>array(
				'Loan.organization_id'=>$organization_id,
				'Loan.loan_status_id'=>3,
				'Loan.status'=>1,
                'Loan.created_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\''
		)));
		$min_loan_amount=round($min_loan_amount_array[0][0]['min_loan'], 2);
		$total_male_customer=$this->Customer->find('count',array('conditions'=>array('Customer.organization_id'=>$organization_id, 'Customer.cust_sex'=>'Male','Customer.created_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\''))); // Calculate Total Male Customer
		$total_female_customer=$this->Customer->find('count',array('conditions'=>array('Customer.organization_id'=>$organization_id, 'Customer.cust_sex'=>'Female','Customer.created_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\''))); // Calculate Total Female Customer
		// Minimum DOB
		$min_customer_dob_array=$this->Customer->find('all',array(
			'fields'=>array(
				'MAX(Customer.cust_dob) as max_age'
			),
			'conditions'=>array(
				'Customer.organization_id'=>$organization_id,
				'Customer.status'=>1
		)));
		$min_customer_dob=$min_customer_dob_array[0][0]['max_age'];
		// Maximum DOB
		$max_customer_dob_array=$this->Customer->find('all',array(
			'fields'=>array(
				'MIN(Customer.cust_dob) as min_age'
			),
			'conditions'=>array(
				'Customer.organization_id'=>$organization_id,
				'Customer.status'=>1,
                'Customer.created_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\''                
		)));
		$max_customer_dob=$max_customer_dob_array[0][0]['min_age'];
		if($min_customer_dob == '0000-00-00' || $max_customer_dob== '0000-00-00'){
			$min_customer_dob= 'No Data Avalable';
			$max_customer_dob= 'No Data Avalable';
			$max_age= 'No Data Avalable';
			$max_age_days= 'No Data Avalable';
			$min_age= 'No Data Avalable';
			$min_age_days= 'No Data Avalable';
			$avarage_age= 'No Data Avalable';
			$avarage_days= 'No Data Avalable';
		} else {
			$max_then = DateTime::createFromFormat("Y-m-d", $max_customer_dob);
			$max_diff = $max_then->diff(new DateTime());
			$max_age= $max_diff->format("%y year %m month %d day\n");
			$max_age_days= $max_diff->days;
			$min_then = DateTime::createFromFormat("Y-m-d", $min_customer_dob);
			$min_diff = $min_then->diff(new DateTime());
			$min_age= $min_diff->format("%y year %m month %d day\n");
			$min_age_days= $min_diff->days;
			$avarage_days=round((($max_age_days+$min_age_days)/2),0);
			$avg_years = floor($avarage_days / 365);
			$avg_months = floor(($avarage_days - $avg_years * 365) / 30);
			$avg_days = floor($avarage_days - $avg_years * 365 - $avg_months*30);
			$avarage_age="$avg_years year $avg_months month $avg_days day";	
		}
		$no_of_cust_array['total_customer']=$total_customer;
		$no_of_cust_array['new_customer']=$new_cust;
		$no_of_cust_array['avarage_loan']=$avarage_loan_amount;
		$no_of_cust_array['customer_left']=$customer_left;
		$no_of_cust_array['max_customer_duration']=$max_duration_days;
		$no_of_cust_array['max_loan_amount']=$max_loan_amount;
		$no_of_cust_array['min_loan_amount']=$min_loan_amount;
		$no_of_cust_array['total_male_customer']=$total_male_customer;
		$no_of_cust_array['total_female_customer']=$total_female_customer;
		$no_of_cust_array['max_dob']=$max_customer_dob;
		$no_of_cust_array['min_dob']=$min_customer_dob;
		$no_of_cust_array['max_age']=$max_age;
		$no_of_cust_array['max_age_days']=$max_age_days;
		$no_of_cust_array['min_age']=$min_age;
		$no_of_cust_array['min_age_days']=$min_age_days;
		$no_of_cust_array['avarage_age']=$avarage_age;
		$no_of_cust_array['avarage_days']=$avarage_days;
        $this->Customer->unBindModel(array(
		'belongsTo' => array(
                'Organization',
                'Region',
                'User',
                'Country',
               ),
		'hasOne' => array(
                'Savings',
               ),
		'hasMany' => array(
                'Loan',
				'Idproof',
				'SavingsTransaction',
				'Order'
               ),
				));
        $customers_data = $this->Customer->find('all',array('conditions'=>array('Customer.status'=>1,'Customer.created_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'')));
    	$this->set('customers_data',$customers_data); 
        $this->set('no_of_cust_array', $no_of_cust_array);
        $this->layout = 'panel_layout';
	}
	// Number of customer calculation function end
	
	// Loan Officers complete statistics function start
	public function stat_loan_officers()   {
        $this->set('title', 'Loan Officer List');  // This is used for Title for every page
		$this->layout = 'panel_layout';
        $this->User->unBindModel(array(
		'belongsTo' => array(
                'Organization',
                'Region',
                'UserType',
                'Country'
               ),
		'hasOne' => array(
                'Savings',
               ),
		'hasMany' => array(
                'Loan',
				'Order',
                'Kendra',
                'Customer'
               ),
				));
        $officer_data = $this->User->find('all',array('conditions'=>array('User.status'=>1,'User.user_type_id'=>5)));
        $lo_officer_array = array();
        $start_date=date("Y-m-d", strtotime("-7 days"));
		$end_date= date ("Y-m-d");
        $lo_number = 0;
        $avg_loan_per_cust=0;
        $total_cust= 0;
        $total_new_cust= 0;
        $total_collection = 0;
        foreach($officer_data as $k=>$lo_row)
        {
           $lo_number++;
           $lo_officer_array[$k] = $lo_row;
           $max_date = $this->LoanTransaction->find('all',array(
			'fields'=>array(
				'MAX(LoanTransaction.insta_paid_on) as max_date'
			),
			'conditions'=>array(
				'LoanTransaction.insta_paid_on !='=> '0000-00-00',
			     'Loan.loan_status_id'=>3,
				 'Loan.organization_id'=>$lo_row['User']['organization_id']
			),
			'joins' =>
						  array(
							array(
								'table' => 'loans',
								'alias' => 'Loan',
								'type' => 'inner',
								'foreignKey' => true,
								'conditions'=> array('Loan.id = LoanTransaction.loan_id')
				)),
		));
		$last_update_date=$max_date[0][0]['max_date'];
        $officer_kendra_no = $this->Kendra->find('count',
			array(
				'conditions'=>
					array(
						'Kendra.status'=>1,
						'Kendra.user_id'=>$lo_row['User']['id']
						)
		));
        $lo_officer_array[$k]['kendra_no'] = $officer_kendra_no;
		$cust_no = $this->Customer->find('count',
			array(
				'conditions'=>
					array(
						'Customer.status'=>1,
						'Customer.user_id'=>$lo_row['User']['id']
						)
		));
        $lo_officer_array[$k]['customer_no'] = $cust_no;
        $total_cust = $total_cust+$lo_officer_array[$k]['customer_no'];
        $new_cust = $this->Customer->find('count', array(
			'conditions' => array(
				  'Customer.user_id'=>$lo_row['User']['id'],
				  'Customer.status'=>1,
				  'date(Customer.created_on) BETWEEN \''.$start_date.'\' AND \''.$end_date .'\''
			)
		));
        $lo_officer_array[$k]['new_customer_no'] = $new_cust;
        $total_new_cust = $total_new_cust+$lo_officer_array[$k]['new_customer_no'];
        $total_loan = $this->Loan->find('all', array('fields' => array('SUM(Loan.loan_principal) as total_loan'),
			'conditions' => array(
			'Loan.user_id' => $lo_row['User']['id'],
			'Loan.loan_status_id' => 3,
			'Loan.status' => 1)));
        $lo_officer_array[$k]['total_loan'] = $total_loan[0][0]['total_loan'];
        $avg_loan_per_cust = $avg_loan_per_cust+(($cust_no>0)?($total_loan[0][0]['total_loan']/$cust_no):0);
		
        
        
        
 	$loan_realise = $this->LoanTransaction->find('all',array(
			'fields'=>array(
				'(SUM(LoanTransaction.insta_principal_paid)+SUM(LoanTransaction.insta_interest_paid)+SUM(LoanTransaction.overdue_paid)+SUM(LoanTransaction.prepayment)) as realized_amount',
			),
			'conditions'=>array(
				
				 'Loan.user_id'=>$lo_row['User']['id'],
				 'Loan.loan_status_id'=>3,
				 'LoanTransaction.insta_paid_on <'=>$last_update_date,
                 'LoanTransaction.insta_interest_paid >'=>0,
				),
			
				'joins' =>
						  array(
							array(
								'table' => 'loans',
								'alias' => 'Loan',
								'type' => 'inner',
								'foreignKey' => true,
								'conditions'=> array('Loan.id = LoanTransaction.loan_id')
							 )
						)
				
		));
        
        $loan_realisable = $this->LoanTransaction->find('all',array(
			'fields'=>array(
				'SUM(LoanTransaction.total_installment) as realizable_amount',
                'SUM(LoanTransaction.insta_principal_due) as realizable_principal_amount',
                'SUM(LoanTransaction.insta_interest_due) as realizable_interest_amount'
			),
			'conditions'=>array(
				
				 'Loan.user_id'=>$lo_row['User']['id'],
				 'Loan.loan_status_id'=>3,
				 'LoanTransaction.insta_due_on <='=>$last_update_date,
                 
				),
			
				'joins' =>
						  array(
							array(
								'table' => 'loans',
								'alias' => 'Loan',
								'type' => 'inner',
								'foreignKey' => true,
								'conditions'=> array('Loan.id = LoanTransaction.loan_id')
							 )
						)
				
		));
       
		$realizable_amount=$loan_realisable[0][0]['realizable_amount'];
		$realized_amount=$loan_realise[0][0]['realized_amount'];
		$percentage_paid=($realizable_amount>0)?round(($realized_amount/$realizable_amount * 100), 2):0;
        
        $lo_officer_array[$k]['loan_in_market'] = $total_loan[0][0]['total_loan']-$loan_realise[0][0]['realized_amount']-$loan_realisable[0][0]['realizable_interest_amount'];	
		$lo_officer_array[$k]['collection_percentage'] = $percentage_paid;   
		$lo_officer_array[$k]['loan_summary'] = array_merge($loan_realisable[0][0],$loan_realise[0][0]);
		$total_collection = $total_collection + $realized_amount;
		} 
    	$this->set('lo_officer_array',$lo_officer_array);
        $lo_officer_summary = array();
        $lo_officer_summary['no_of_officer'] = $lo_number;
        $lo_officer_summary['avg_loan_per_officer'] = round(($avg_loan_per_cust/$lo_number),2);
        $lo_officer_summary['avg_customer_per_officer'] = round(($total_cust/$lo_number),0);
        $lo_officer_summary['avg_new_customer_per_officer'] = round(($total_new_cust/$lo_number),0);
        $lo_officer_summary['avg_collection_per_officer'] = round(($total_collection/$lo_number),2);
        $this->set('lo_officer_summary',$lo_officer_summary);
    }
    // Loan Officers complete statistics function end
	
    // This is  the calculation of the paid percentage statistics function start
	public function stat_paid_percentage(){
		$user_id=$this->Auth->user('id');
			$user_type_id=$this->Auth->user('user_type_id');
			$organization_id=$this->Auth->user('organization_id');
			$start_date=date("Y-m-d", strtotime("-7 days"));
			$max_date = $this->LoanTransaction->find('all',array(
					'fields'=>array(
						'MAX(LoanTransaction.insta_paid_on) as max_date'
					),
					'conditions'=>array(
						'LoanTransaction.order_id'=> 0,
						'LoanTransaction.insta_paid_on !='=> '0000-00-00',
						 'Loan.loan_status_id'=>3,
						 'Loan.organization_id'=>$organization_id
					),
					'joins' =>
								  array(
									array(
										'table' => 'loans',
										'alias' => 'Loan',
										'type' => 'inner',
										'foreignKey' => true,
										'conditions'=> array('Loan.id = LoanTransaction.loan_id')
					)),
				));
			$final_date=$max_date[0][0]['max_date'];
			$this->set('update_on',$final_date); // Last updated date in the database
			$end_date= $final_date;
			$this->set('option_val', '1');
			$select_date=date("m-d-Y", strtotime($final_date));
			$send_date['start_date']=$select_date;
			$send_date['end_date']=$select_date;
		if($this->request->is('post')) {
			$option_val=$this->request->data['User']['selectdate'];
			$this->set('option_val', $option_val);
			if($option_val==1){
				$start_date=date("Y-m-d", strtotime("-7 days"));
				$end_date= $final_date;
			}
			if($option_val==2){
				$start_date=date("Y-m-d", strtotime("-14 days"));
				$end_date= date("Y-m-d", strtotime("-7 days"));
			}
			if($option_val==3){
				$start_date=date("Y-m-d", strtotime("-30 days"));
				$end_date= $final_date;
			}
			if($option_val==4){
				$postarray=$this->request->data;
				$daterange=explode('-', $this->request->data['datefilter']);
				$postarray['start_date']= trim($daterange[0]);
				$postarray['end_date']= trim($daterange[1]);
				$send_date['start_date']=$postarray['start_date'];
				$send_date['end_date']=$postarray['end_date'];
				$this->set('send_date', $send_date);
				$start_date=date("Y-m-d", strtotime($postarray['start_date']));
				$end_date=date("Y-m-d", strtotime($postarray['end_date']));
			}
		}
		$send_date['date_diff']= $this->date_differ($start_date,$end_date);
		$send_date['start_date']=$start_date;
		$send_date['end_date']=$end_date;
		$this->set('send_date', $send_date);
		$this->set('title', 'Paid Percentage');  // This is used for Title for every page
		$this->layout = 'panel_layout';
		$paid_percentage_array=array();
		// Data which are using in all the parts
		$user_data= $this->User->find('all',array('conditions'=>array('User.id'=>$this->Auth->user('id'))));
        $branch_list= $this->Branch->find('all',array('conditions'=>array('Branch.organization_id'=>$this->Auth->user('organization_id'))));
		$organization_id=$this->Auth->user('organization_id');  // Organization Id
		// Calculate Loan Overdue Amount of the Organization
		$loan_overdue = $this->LoanTransaction->find('all',array(
			'fields'=>array(
				'SUM(LoanTransaction.total_installment) as realizable_amount',
				'(SUM(LoanTransaction.insta_principal_paid)+SUM(LoanTransaction.insta_interest_paid)) as realized_amount',
				
			),
			'conditions'=>array(
				'Loan.organization_id'=>$organization_id,
				'LoanTransaction.insta_due_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'',
				'LoanTransaction.order_id'=> 0,
				'Loan.loan_status_id'=>3,
			),
			'joins' =>
					  array(
							array(
								'table' => 'loans',
								'alias' => 'Loan',
								'type' => 'inner',
								'foreignKey' => true,
								'conditions'=> array('Loan.id = LoanTransaction.loan_id')
							 )
						)
		));
		$loan_overdue_duration_id = $this->LoanTransaction->find('all',array(
			'fields'=>array(
				'MIN(LoanTransaction.insta_due_on) as mindate',
				'MAX(LoanTransaction.insta_due_on) as maxdate'
			),
			'conditions'=>array(
				'Loan.organization_id'=>$organization_id,
				'LoanTransaction.insta_due_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'',
				'LoanTransaction.order_id'=> 0,
				'LoanTransaction.insta_paid_on'=> '0000-00-00',
				'Loan.loan_status_id'=>3,
			),
			'joins' =>
					  array(
							array(
								'table' => 'loans',
								'alias' => 'Loan',
								'type' => 'inner',
								'foreignKey' => true,
								'conditions'=> array('Loan.id = LoanTransaction.loan_id')
							 )
						)
		));
        $loan_overdue_number = $this->LoanTransaction->find('all',array(
			'fields'=>array(
				'COUNT(LoanTransaction.id) as overdue_no',
				'COUNT(distinct(LoanTransaction.customer_id)) as overdue_cust_no'
			),
			'conditions'=>array(
				'Loan.organization_id'=>$organization_id,
				'LoanTransaction.insta_due_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'',
				'LoanTransaction.order_id'=> 0,
				'LoanTransaction.insta_principal_paid'=> 0,
				'Loan.loan_status_id'=>3,
			),
			'joins' =>
					array(
						array(
							'table' => 'loans',
							'alias' => 'Loan',
							'type' => 'inner',
							'foreignKey' => true,
							'conditions'=> array('Loan.id = LoanTransaction.loan_id')
						 )
					)
		));
		$total_overdue_no=$loan_overdue_number[0][0]['overdue_no'];
        $total_over_due_user=$loan_overdue_number[0][0]['overdue_cust_no'];
		$datemin=$loan_overdue_duration_id[0][0]['mindate'];
		$datemax=$loan_overdue_duration_id[0][0]['maxdate'];
       	if($datemin == '' || $datemax== ''){
       	    $diffmin = 0;
            $diffmax = 0;
            $min_duration_days = 'Not Available';
            $max_duration_days = 'Not Available';
            
        }else{
            $diffmin = abs(strtotime($end_date) - strtotime($datemin));
    		$diffmax = abs(strtotime($end_date) - strtotime($datemax));
    		$min_duration_days = floor($diffmin / (60*60*24));
    		$max_duration_days = floor($diffmax / (60*60*24));
        }
		$total_realizable=	$loan_overdue[0][0]['realizable_amount'];
		$total_reliazed=$loan_overdue[0][0]['realized_amount'];
		$total_overdue_amount=	$total_realizable- $total_reliazed;
		// Add value into the array
		$paid_percentage_array['total_overdue_no']=$total_overdue_no;
		$paid_percentage_array['total_overdue_amount']=$total_overdue_amount;
		$paid_percentage_array['longest_drop_days']=$min_duration_days;
		$paid_percentage_array['total_overdue_user']=$total_over_due_user;
        $paid_percentage_array['best_wrost_data'] = $this->best_details();
		$paid_percentage_array['branch_list']=$branch_list;
		$this->set('paid_percentage_array',$paid_percentage_array);
	}
    // This is  the calculation of the paid percentage statistics function end
	
	// Statistics for realizable and realized data function start
    public function stat_realize_realizable()
    {
        $this->layout = 'panel_layout';
        $this->set('title', 'Realize and Realizable amount');
        $summary_data = array();
        $user_id=$this->Auth->user('id');
		$user_type_id=$this->Auth->user('user_type_id');
		$organization_id=$this->Auth->user('organization_id');
		$start_date=date("Y-m-d", strtotime("-7 days"));
		$max_date = $this->LoanTransaction->find('all',array(
			'fields'=>array(
				'MAX(LoanTransaction.insta_paid_on) as max_date'
			),
			'conditions'=>array(
				'LoanTransaction.order_id'=> 0,
				'LoanTransaction.insta_paid_on !='=> '0000-00-00',
				 'Loan.loan_status_id'=>3,
				 'Loan.organization_id'=>$organization_id
			),
			'joins' =>
			  array(
				array(
					'table' => 'loans',
					'alias' => 'Loan',
					'type' => 'inner',
					'foreignKey' => true,
					'conditions'=> array('Loan.id = LoanTransaction.loan_id')
			)),
		));
		$final_date=$max_date[0][0]['max_date'];
		$this->set('update_on',$final_date); // Last Updated date in database
		$end_date= $final_date;
		$this->set('option_val', '1');
		$select_date=date("m-d-Y", strtotime($final_date));
		$send_date['start_date']=$select_date;
		$send_date['end_date']=$select_date;
		// User Values after post
		if ($this->request->is('post')) {
			$option_val=$this->request->data['User']['selectdate'];
			$this->set('option_val', $option_val);
			if($option_val==1){
				$start_date=date("Y-m-d", strtotime("-7 days"));
				$end_date= $final_date;
				//$end_date= date ("Y-m-d");
			}
			if($option_val==2){
				$start_date=date("Y-m-d", strtotime("-14 days"));
				$end_date= date("Y-m-d", strtotime("-7 days"));
			}
			if($option_val==3){
				$start_date=date("Y-m-d", strtotime("-30 days"));
				$end_date= $final_date;
			}
			if($option_val==4){
				$postarray=$this->request->data;
				$daterange=explode('-', $this->request->data['datefilter']);
				$postarray['start_date']= trim($daterange[0]);
				$postarray['end_date']= trim($daterange[1]);
				$send_date['start_date']=$postarray['start_date'];
				$send_date['end_date']=$postarray['end_date'];
				$this->set('send_date', $send_date);
				$start_date=date("Y-m-d", strtotime($postarray['start_date']));
				$end_date=date("Y-m-d", strtotime($postarray['end_date']));
			} 
		}
		$send_date['date_diff']= $this->date_differ($start_date,$end_date);
		$send_date['start_date']=$start_date;
		$send_date['end_date']=$end_date;
		$this->set('send_date', $send_date);
        $loan_overdue = $this->LoanTransaction->find('all',array(
			'fields'=>array(
				'SUM(LoanTransaction.total_installment) as realizable_amount',
				'(SUM(LoanTransaction.insta_principal_paid)+SUM(LoanTransaction.insta_interest_paid)) as realized_amount',						
			),
			'conditions'=>array(
				 'Loan.organization_id'=>$organization_id,
				 'LoanTransaction.order_id'=> 0,
				 'Loan.loan_status_id'=>3,
				 'LoanTransaction.insta_due_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'',
				),
				'joins' =>
					array(
						array(
							'table' => 'loans',
							'alias' => 'Loan',
							'type' => 'inner',
							'foreignKey' => true,
							'conditions'=> array('Loan.id = LoanTransaction.loan_id')
						 )
					)
		));	
        $due_loan = $this->LoanTransaction->find('all',array(
			'fields'=>array(
				'SUM(LoanTransaction.insta_principal_due) as due_balance'
			),
			'conditions'=>array(
				'LoanTransaction.insta_principal_paid'=> 0,
				'LoanTransaction.order_id'=> 0,
				 'Loan.loan_status_id'=>3,
				  'Loan.organization_id'=>$organization_id
			),
			'joins' =>
				array(
					array(
						'table' => 'loans',
						'alias' => 'Loan',
						'type' => 'inner',
						'foreignKey' => true,
						'conditions'=> array('Loan.id = LoanTransaction.loan_id')
				)),
		));
        $new_loan_application = $this->Loan->find('all', array(
			'fields' => array(
				  'COUNT(Loan.id) as no_of_loan',
				  'SUM(Loan.loan_principal) as total_loan_principal'  
			),
			'conditions' => array(
				  'Loan.organization_id'=>$organization_id,
				  'Loan.loan_status_id'=>2,
				  'Loan.loan_dateout BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'',
				  
			)
		));
        $approved_loan = $this->Loan->find('all', array(
			'fields' => array(
				  'COUNT(Loan.id) as no_of_loan',
				  'SUM(Loan.loan_principal) as total_loan_principal'  
			),
			'conditions' => array(
				  'Loan.organization_id'=>$organization_id,
				  'Loan.loan_status_id'=>2,
				  'Loan.loan_dateout BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'',
				  
			)
		));
		$disbursed_loan = $this->Loan->find('all', array(
			'fields' => array(
				  'COUNT(Loan.id) as no_of_loan',
				  'SUM(Loan.loan_principal) as total_loan_principal'  
			),
			'conditions' => array(
				  'Loan.organization_id'=>$organization_id,
				  'Loan.loan_status_id'=>3,
				  'Loan.loan_dateout BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'',
				  
			)
		));
		$closed_loan = $this->Loan->find('all', array(
			'fields' => array(
				  'COUNT(Loan.id) as no_of_loan',
				  'SUM(Loan.loan_principal) as total_loan_principal'  
			),
			'conditions' => array(
				  'Loan.organization_id'=>$organization_id,
				  'Loan.loan_status_id'=>6,
				  'Loan.loan_dateout BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'',
				  
			)
        ));
        $loan_payment_list = $this->LoanTransaction->find('all',array(
    		'fields'=>array(
    			'LoanTransaction.insta_due_on',
    			'LoanTransaction.insta_paid_on',
    			'SUM(LoanTransaction.total_installment) as total_installment',
    			'SUM(LoanTransaction.insta_principal_paid) as total_principal_paid',
    			'SUM(LoanTransaction.insta_interest_paid) as total_interest_paid'
    		),
    		'conditions'=>array(
    			'Loan.organization_id'=>$organization_id,
    			'Loan.loan_status_id'=>3,
    			'LoanTransaction.order_id'=> 0,
    			'LoanTransaction.insta_due_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\''
    		),
    		'joins' =>
    			  array(
    				array(
    					'table' => 'loans',
    					'alias' => 'Loan',
    					'type' => 'inner',
    					'foreignKey' => true,
    					'conditions'=> array('Loan.id = LoanTransaction.loan_id')
    				)),
    		'group'=>'LoanTransaction.insta_due_on'
    	));
         $summary_data['realize_amt'] = $loan_overdue[0][0]['realized_amount'];
         $summary_data['realizable_amt'] = $loan_overdue[0][0]['realizable_amount'];
         $summary_data['total_loan_in_mkt'] = $due_loan[0][0]['due_balance'];
         $summary_data['new_loan_application'] = $new_loan_application[0][0];
         $summary_data['approved_loan'] = $approved_loan[0][0];
         $summary_data['disbursed_loan'] = $disbursed_loan[0][0];
         $summary_data['closed_loan'] = $closed_loan[0][0];
         $summary_data['best_wrost_data'] = $this->best_details();
         $summary_data['loan_details'] = $loan_payment_list;
         $this->set('summary_data', $summary_data);
    }
	// Statistics for realizable and realized data function end
	
	// Statistics for Overdue function start (PAR 30, PAR 60 and PAR 90 also calculated here)
	public function stat_overdue(){
        $this->layout = 'collection_layout';
		$this->set('title', 'Overdue Summary');
        $user_type_id=$this->Auth->user('user_type_id');
		$organization_id=$this->Auth->user('organization_id');
        $max_date = $this->LoanTransaction->find('all',array(
				'fields'=>array(
					'MAX(LoanTransaction.insta_paid_on) as max_date'
				),
				'conditions'=>array(
					'LoanTransaction.order_id'=> 0,
					'LoanTransaction.insta_paid_on !='=> '0000-00-00',
					 'Loan.loan_status_id'=>3,
					 'Loan.organization_id'=>$organization_id
				),
				'joins' =>
				  array(
					array(
						'table' => 'loans',
						'alias' => 'Loan',
						'type' => 'inner',
						'foreignKey' => true,
						'conditions'=> array('Loan.id = LoanTransaction.loan_id')
				)),
			));
		$final_date=$max_date[0][0]['max_date'];
		$this->set('update_on',$final_date); // Last updated date in database
        $thirty_days_start=date('Y-m-d', strtotime($final_date.'-30 days'));
        $sixty_days_start=date('Y-m-d', strtotime($final_date.'-60 days'));
        $ninty_days_start=date('Y-m-d', strtotime($final_date.'-90 days'));
        $this->LoanTransaction->bindModel(array(
            'belongsTo' => array(
                'Loan' => array(
					'foreignKey' => 'loan_id',
					'type'=>'INNER',
					'conditions' => array('Loan.id = LoanTransaction.loan_id')
				),
                'Branch' => array(
					'foreignKey' => 'branch_id',
					'type'=>'INNER',
					'conditions' => array('Branch.id = LoanTransaction.branch_id')
				),
                'Kendra' => array(
					'foreignKey' => 'kendra_id',
					'type'=>'INNER',
					'conditions' => array('Kendra.id = LoanTransaction.kendra_id')
				),
                'Customer' => array(
					'foreignKey' => 'customer_id',
					'type'=>'INNER',
					'conditions' => array('Customer.id = LoanTransaction.customer_id')
				)
            )
        ));
        $loan_overdue_all = $this->LoanTransaction->find('all', 
			array(
				'fields' => array('Customer.cust_fname', 'Customer.cust_lname','Branch.branch_name','Kendra.kendra_name','LoanTransaction.total_installment','LoanTransaction.insta_due_on'),
				'conditions' => array(
					'Loan.loan_status_id'=>3,
					'Loan.organization_id'=>$organization_id,
					'LoanTransaction.insta_principal_paid' => 0,
					'LoanTransaction.insta_due_on <=' => $final_date),
				'order'=>array('LoanTransaction.insta_due_on DESC' )
					
			));
        $this->LoanTransaction->bindModel(array(
            'belongsTo' => array(
                'Loan' => array(
					'foreignKey' => 'loan_id',
					'type'=>'INNER',
					'conditions' => array('Loan.id = LoanTransaction.loan_id')
				),
                'Branch' => array(
					'foreignKey' => 'branch_id',
					'type'=>'INNER',
					'conditions' => array('Branch.id = LoanTransaction.branch_id')
				),
                'Kendra' => array(
					'foreignKey' => 'kendra_id',
					'type'=>'INNER',
					'conditions' => array('Kendra.id = LoanTransaction.kendra_id')
				),
                'Customer' => array(
					'foreignKey' => 'customer_id',
					'type'=>'INNER',
					'conditions' => array('Customer.id = LoanTransaction.customer_id')
				)
			)
        ));
        $loan_overdue_thirty = $this->LoanTransaction->find('all', 
			array(
				'fields' => array('Customer.cust_fname', 'Customer.cust_lname','Branch.branch_name','Kendra.kendra_name','LoanTransaction.total_installment','LoanTransaction.insta_due_on'),
				'conditions' => array(
					'Loan.loan_status_id'=>3,
					'Loan.organization_id'=>$organization_id,
					'LoanTransaction.insta_principal_paid' => 0,
					'LoanTransaction.insta_due_on BETWEEN \''.$sixty_days_start.'\' AND \''.$thirty_days_start .'\''
				),
				'order'=>array('LoanTransaction.insta_due_on DESC' )	
			)
		);
        $this->LoanTransaction->bindModel(array(
            'belongsTo' => array(
                'Loan' => array(
					'foreignKey' => 'loan_id',
					'type'=>'INNER',
					'conditions' => array('Loan.id = LoanTransaction.loan_id')
				),
                'Branch' => array(
					'foreignKey' => 'branch_id',
					'type'=>'INNER',
					'conditions' => array('Branch.id = LoanTransaction.branch_id')
				),
                'Kendra' => array(
					'foreignKey' => 'kendra_id',
					'type'=>'INNER',
					'conditions' => array('Kendra.id = LoanTransaction.kendra_id')
				),
                'Customer' => array(
					'foreignKey' => 'customer_id',
					'type'=>'INNER',
					'conditions' => array('Customer.id = LoanTransaction.customer_id')
				)
            )
        ));
        $loan_overdue_sixty = $this->LoanTransaction->find('all', 
			array(
				'fields' => array('Customer.cust_fname', 'Customer.cust_lname','Branch.branch_name','Kendra.kendra_name','LoanTransaction.total_installment','LoanTransaction.insta_due_on'),
				'conditions' => array(
					'Loan.loan_status_id'=>3,
					'Loan.organization_id'=>$organization_id,
					'LoanTransaction.insta_principal_paid' => 0,
					'LoanTransaction.insta_due_on BETWEEN \''.$ninty_days_start.'\' AND \''.$sixty_days_start .'\''
				),
				'order'=>array('LoanTransaction.insta_due_on DESC' )	
			)
        );
        $this->LoanTransaction->bindModel(array(
            'belongsTo' => array(
                'Loan' => array(
					'foreignKey' => 'loan_id',
					'type'=>'INNER',
					'conditions' => array('Loan.id = LoanTransaction.loan_id')
                ),
                'Branch' => array(
					'foreignKey' => 'branch_id',
					'type'=>'INNER',
					'conditions' => array('Branch.id = LoanTransaction.branch_id')
				),
                'Kendra' => array(
					'foreignKey' => 'kendra_id',
					'type'=>'INNER',
					'conditions' => array('Kendra.id = LoanTransaction.kendra_id')
				),
                'Customer' => array(
					'foreignKey' => 'customer_id',
					'type'=>'INNER',
					'conditions' => array('Customer.id = LoanTransaction.customer_id')
				)
            )
        ));
        $loan_overdue_ninety = $this->LoanTransaction->find('all', 
			array(
				'fields' => array('Customer.cust_fname', 'Customer.cust_lname','Branch.branch_name','Kendra.kendra_name','LoanTransaction.total_installment','LoanTransaction.insta_due_on'),
				'conditions' => array(
					'Loan.loan_status_id'=>3,
					'Loan.organization_id'=>$organization_id,
					'LoanTransaction.insta_principal_paid' => 0,
					'LoanTransaction.insta_due_on <=' => $ninty_days_start
				),
				'order'=>array('LoanTransaction.insta_due_on DESC' )
			)
        );
        $this->set('loan_overdue_all',$loan_overdue_all); // All overdue
        $this->set('loan_overdue_thirty',$loan_overdue_thirty); // PAR 30
        $this->set('loan_overdue_sixty',$loan_overdue_sixty); // PAR 60
        $this->set('loan_overdue_ninety',$loan_overdue_ninety); // PAR 90
	}
	// Statistics for Overdue function end
	
	// Saving List Market Wise function start
	public function saving_account_list(){
		$this->set('title', 'Saving Account List');
		$this->layout = 'panel_layout';
		$this->set('post_val', 0);
		$market_list = $this->Market->find('list',array('fields'=>array('id','market_name'),'conditions'=>array('Market.status'=>1),'recursive'=>-1));
        $this->set('market_list', $market_list);
		if ($this->request->is('post')) {
			$market_id=$this->request->data['Saving']['market_id'];
			$market_id=1;
			$saving_account_data= $this->Saving->find('all',array('conditions'=>array('Saving.market_id'=>$market_id)));
			$this->set('saving_account_data', $saving_account_data);
			//pr($saving_account_data); die;
			//pr($this->request->data); die;
		}
	}
	// Saving List Market Wise function end
}
// End of Statistics controller
?>