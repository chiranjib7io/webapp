<?php
/*
Users Controller perform the login and loagout functionalities
with Dashboard of all type of users.
Also contain the Forgot password, new user registration and change password.
*/
App::uses('CakeEmail', 'Network/Email');
App::import('Controller', 'Loans');
class UsersController extends AppController {
	// List of models which are used in the organization controller 
	var $uses = array('User','Organization','Region','Branch','Market','Kendra','Customer','Loan','Saving','Idproof','LogRecord','Country', 'Setting', 'Fee', 'UserType', 'Region','Market');
	
	// Login with email id instant of username in auth controller
	public $components = array(
    'Auth' => array(
        'authenticate' => array(
            'Form' => array(
                'fields' => array('username' => 'email')
            )
        )
    ),'RequestHandler'
	);
	
	// Pagination in Cakephp
	public $paginate = array(
        'limit' => 25,
        'conditions' => array('status' => '1'),
    	'order' => array('User.username' => 'asc' ) 
    );
	
	// Tell auth controller which are the functions can use without login
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('login','add','forgot'); 
    }
	
	// Dashboard Function Start
    public function index() {
        if($this->Auth->user('user_type_id')==5){
			$this->redirect(array('action' => 'lo_index')); // Redirect to this function if the login user type is loan officer
        } 
        if($this->Auth->user('user_type_id')==6){
			$this->redirect(array('action' => 'single_amount_collection','controller'=>'dataentries')); // Redirect to this function if the login user type is loan officer
        }  
		$this->layout = 'panel_layout';
		$this->set('title', 'Dashbord');
		// Call Values for the Dashboard from the session
		$user_id=$this->Auth->user('id'); // Login user's user id
		$user_type_id=$this->Auth->user('user_type_id'); // Login user's user type
		$organization_id=$this->Auth->user('organization_id'); // Login user is from which organization
		$branch_id=$this->Auth->user('branch_id'); // Login user is from which branch
		

        $max_date = $this->LoanTransaction->find('all',array(
			'fields'=>array(
				'MAX(LoanTransaction.insta_paid_on) as max_date'
			),
			'conditions'=>array(
				
				'LoanTransaction.insta_paid_on !='=> '0000-00-00',
				 'Loan.loan_status_id'=>3,
				 'Loan.organization_id'=>$organization_id
			),
            'joins' => array(
                    array(
                        'table' => 'loans',
                        'alias' => 'Loan',
                        'type' => 'inner',
                        'foreignKey' => false,
                        'conditions'=> array('LoanTransaction.loan_id = Loan.id')
                    ))
		));
		$final_date=$max_date[0][0]['max_date'];  // Last updated date in the database
		$start_date=date("Y-m-d", strtotime("-7 days", strtotime($final_date)));
		$this->set('update_on',$final_date);
        $end_date= $final_date;
		$this->set('option_val', '1');
		$select_date=date("m-d-Y", strtotime($final_date));
		$send_date['start_date']=$select_date;
		$send_date['end_date']=$select_date;
        
        if ($this->request->is('post')) {
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
        
        $user_info=array();
		$user_info['user_id']=$user_id;
		$user_info['user_type_id']=$user_type_id;
		$user_info['organization_id']=$organization_id;
		$user_info['branch_id']=$branch_id;
		$user_info['start_date']=$start_date;
		$user_info['end_date']=$end_date;
		$send_date['date_diff']= $this->date_differ($start_date,$end_date);
		$final_array=$this->dashboard_extra($user_info);
		$this->set('send_date', $send_date);            
		$this->set('dashboard_array', $final_array);
		

    }
	// Dashboard Function End
    
    // Dashboard Extra Function Start
	public function dashboard_extra($user_info){
		// Static Variable Declaration
		$branch_data_full=array();
		$kendra_data_full=array();
		$total_bm=0;
		$total_lo=0;
		$total_branch=0;
		$total_kendra=0;
		$total_customer=0;
		$total_loan_in_market=0;
		$overdue_no=0;
		$realizable_amount=0;
		$realized_amount=0;
		$percentage_paid=0;
		$new_loan=0;
		$loan_details=0;
		$loan_collection=array();
		// Dynamic Variable Declaration
		$user_id=$user_info['user_id'];
		$organization_id=$user_info['organization_id'];
		$branch_id=$user_info['branch_id'];
		$user_type_id=$user_info['user_type_id'];
		$start_date=$user_info['start_date'];
		$end_date=$user_info['end_date'];

		// Organization Admin Dashboard Start
		if($user_info['user_type_id']== 2){
			$total_bm= $this->User->find('count',array('conditions'=>array('User.organization_id'=>$organization_id, 'User.user_type_id'=>4)));
			$total_lo= $this->User->find('count',array('conditions'=>array('User.organization_id'=>$organization_id, 'User.user_type_id'=>5)));
			$total_branch=$this->Branch->find('count',array('conditions'=>array('Branch.organization_id'=>$organization_id,'Branch.status'=>1)));
			$total_kendra=$this->Kendra->find('count',array('conditions'=>array('Kendra.organization_id'=>$organization_id,'Kendra.status'=>1)));
			$total_customer= $this->Customer->find('count',array('conditions'=>array('Customer.organization_id'=>$organization_id,'Customer.status'=>1)));
			if($total_customer != 0) {
			 
                         
             
				$due_loan = $this->LoanTransaction->find('all',array(
					'fields'=>array(
						'SUM(Loan.loan_principal) as total_loan',
                        'SUM(LoanTransaction.insta_principal_paid) as total_paid',
					),
					'conditions'=>array(
						'Loan.loan_status_id'=>3,
						'Loan.organization_id'=>$organization_id
					),
                    'joins' => array(
                        array(
                            'table' => 'loans',
                            'alias' => 'Loan',
                            'type' => 'inner',
                            'foreignKey' => false,
                            'conditions'=> array('LoanTransaction.loan_id = Loan.id')
                        )
                    )
				));
				$total_loan_in_market=$due_loan[0][0]['total_loan']-$due_loan[0][0]['total_paid'];
                
                
				$loan_overdue = $this->LoanTransaction->find('all',array(
                    'fields'=>array(
						'SUM(LoanTransaction.total_installment) as realizable_amount',
                        '(SUM(LoanTransaction.insta_principal_paid)+SUM(LoanTransaction.insta_interest_paid)) as realized_amount',
						
					),
					'conditions'=>array(
						'Loan.organization_id'=>$organization_id,
						'LoanTransaction.insta_due_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'',
						
                        'Loan.loan_status_id'=>3,
					),
                    'joins' => array(
                        array(
                            'table' => 'loans',
                            'alias' => 'Loan',
                            'type' => 'inner',
                            'foreignKey' => false,
                            'conditions'=> array('LoanTransaction.loan_id = Loan.id')
                        )
                    )
				));
                
              $overdueno = $this->LoanTransaction->find('all',array(
            			'fields'=>array(
            				'COUNT(LoanTransaction.id) as overdue_no'
            			),
            			'conditions'=>array(
            				
            				'LoanTransaction.insta_paid_on !='=> '0000-00-00',
            				 'Loan.loan_status_id'=>3,
            				 'Loan.organization_id'=>$organization_id,
                             'LoanTransaction.insta_due_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'',
            			),
                        'joins' => array(
                                array(
                                    'table' => 'loans',
                                    'alias' => 'Loan',
                                    'type' => 'inner',
                                    'foreignKey' => false,
                                    'conditions'=> array('LoanTransaction.loan_id = Loan.id')
                                ))
            		));  
                            
                
                
                $loan_collection = $this->LoanTransaction->find('all',array(
					'fields'=>array(
						'SUM(LoanTransaction.total_installment) as realizable_amount',
                        '(SUM(LoanTransaction.insta_principal_paid)+SUM(LoanTransaction.insta_interest_paid)) as realized_amount',
						
					),
					'conditions'=>array(
						'Loan.organization_id'=>$organization_id,
						'Loan.loan_status_id'=>3,
						
						'LoanTransaction.insta_due_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'' 
					),
                    'joins' => array(
                        array(
                            'table' => 'loans',
                            'alias' => 'Loan',
                            'type' => 'inner',
                            'foreignKey' => false,
                            'conditions'=> array('LoanTransaction.loan_id = Loan.id')
                        )
                    )
				));
				$new_cust = $this->Loan->find('count', array(
					'conditions' => array(
						  'Loan.organization_id'=>$organization_id,
						  'Loan.loan_status_id'=>3,
						  'Loan.loan_dateout BETWEEN \''.$start_date.'\' AND \''.$end_date .'\''
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
						
						'LoanTransaction.insta_due_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\''
					),
                    'joins' => array(
                        array(
                            'table' => 'loans',
                            'alias' => 'Loan',
                            'type' => 'inner',
                            'foreignKey' => false,
                            'conditions'=> array('LoanTransaction.loan_id = Loan.id')
                        )
                    ),
					
					'group'=>'LoanTransaction.insta_due_on'
				));
                $total_overdue=$loan_overdue[0][0]['realizable_amount']-$loan_overdue[0][0]['realized_amount'];
				
				$realizable_amount=$loan_overdue[0][0]['realizable_amount'];
				$realized_amount=$loan_overdue[0][0]['realized_amount'];
				$percentage_paid=round(($realized_amount/$realizable_amount * 100), 2);
				$new_loan=$new_cust;
                $loan_details = $loan_payment_list;
                // Branch Wise Details (Table Form) Start
                $branch_list = $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.status'=>1, 'Branch.organization_id'=>$organization_id)));
				foreach($branch_list as $bkey=>$dbranch) {
				    $bdata = $this->dashboard_branch_table($bkey,$start_date,$end_date);
                    if(!empty($bdata)){
                       unset($bdata['Kendra']);
					   $branch_data_full[$bkey]= $bdata;
                    }
				}
			}
		}
		// Organization Dashboard End
		
		// Branch Manager Dashboard Start
        if($user_info['user_type_id']== 4){
            
            
			$total_bm= $this->User->find('count',array(
                                    'conditions'=>array('User.branch_id'=>$branch_id, 'User.user_type_id'=>4)));
            
            
			$total_lo=$this->User->find('count',array(
                                    'conditions'=>array('User.branch_id'=>$branch_id, 'User.user_type_id'=>5)));
			$total_kendra=0;
            
            
			$total_customer= $this->Customer->find('count',array('conditions'=>array('Customer.status'=>1,'Customer.branch_id'=>$branch_id)));
			
            if($total_customer != 0) {
			 
                
				$due_loan = $this->LoanTransaction->find('all',array(
					'fields'=>array(
						'SUM(Loan.loan_principal) as total_loan',
                        'SUM(LoanTransaction.insta_principal_paid) as total_paid',
					),
					'conditions'=>array(
						'Loan.loan_status_id'=>3,
						'Loan.branch_id'=>$branch_id
					),
                    'joins' => array(
                        array(
                            'table' => 'loans',
                            'alias' => 'Loan',
                            'type' => 'inner',
                            'foreignKey' => false,
                            'conditions'=> array('LoanTransaction.loan_id = Loan.id')
                        )
                    )
				));
				$total_loan_in_market=$due_loan[0][0]['total_loan']-$due_loan[0][0]['total_paid'];
                
                
				$loan_overdue = $this->LoanTransaction->find('all',array(
                    'fields'=>array(
						'SUM(LoanTransaction.total_installment) as realizable_amount',
                        '(SUM(LoanTransaction.insta_principal_paid)+SUM(LoanTransaction.insta_interest_paid)) as realized_amount'
					),
					'conditions'=>array(
						'Loan.branch_id'=>$branch_id,
						'LoanTransaction.insta_due_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'',
				
                        'Loan.loan_status_id'=>3,
					),
                    'joins' => array(
                        array(
                            'table' => 'loans',
                            'alias' => 'Loan',
                            'type' => 'inner',
                            'foreignKey' => false,
                            'conditions'=> array('LoanTransaction.loan_id = Loan.id')
                        )
                    )
				));
                
               
               $overdueno = $this->LoanTransaction->find('all',array(
            			'fields'=>array(
            				'COUNT(LoanTransaction.id) as overdue_no'
            			),
            			'conditions'=>array(
            				
            				'LoanTransaction.insta_paid_on'=> '0000-00-00',
            				 'Loan.loan_status_id'=>3,
            				 'Loan.organization_id'=>$organization_id,
                             'LoanTransaction.insta_due_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'',
            			),
                        'joins' => array(
                                array(
                                    'table' => 'loans',
                                    'alias' => 'Loan',
                                    'type' => 'inner',
                                    'foreignKey' => false,
                                    'conditions'=> array('LoanTransaction.loan_id = Loan.id')
                                ))
            		)); 
				                
                
                $loan_collection = $this->LoanTransaction->find('all',array(
					'fields'=>array(
						'SUM(LoanTransaction.total_installment) as realizable_amount',
                        '(SUM(LoanTransaction.insta_principal_paid)+SUM(LoanTransaction.insta_interest_paid)) as realized_amount',
						
					),
					'conditions'=>array(
						'Loan.branch_id'=>$branch_id,
						'Loan.loan_status_id'=>3,
						
						'LoanTransaction.insta_due_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'' 
					),
                    'joins' =>array(
    					array(
    						'table' => 'loans',
    						'alias' => 'Loan',
    						'type' => 'inner',
    						'foreignKey' => true,
    						'conditions'=> array('Loan.id = LoanTransaction.loan_id')
    					 )
    				)
					
						
				));
                
				$new_cust = $this->Loan->find('count', array(
					'conditions' => array(
						  'Loan.branch_id'=>$branch_id,
						  'Loan.loan_status_id'=>3,
						  'Loan.loan_dateout BETWEEN \''.$start_date.'\' AND \''.$end_date .'\''
					),
                    'joins' =>array(
    					array(
    						'table' => 'loans',
    						'alias' => 'Loan',
    						'type' => 'inner',
    						'foreignKey' => true,
    						'conditions'=> array('Loan.id = LoanTransaction.loan_id')
    					 )
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
						'Loan.branch_id'=>$branch_id,
						'Loan.loan_status_id'=>3,
						
						'LoanTransaction.insta_due_on BETWEEN \''.$start_date.'\' AND \''.$end_date .'\''
					),
					'joins' =>array(
    					array(
    						'table' => 'loans',
    						'alias' => 'Loan',
    						'type' => 'inner',
    						'foreignKey' => true,
    						'conditions'=> array('Loan.id = LoanTransaction.loan_id')
    					 )
    				),
					'group'=>'LoanTransaction.insta_due_on'
				));
                $total_overdue=$loan_overdue[0][0]['realizable_amount']-$loan_overdue[0][0]['realized_amount'];
				
				$realizable_amount=$loan_overdue[0][0]['realizable_amount'];
				$realized_amount=$loan_overdue[0][0]['realized_amount'];
				$percentage_paid=round(($realized_amount/$realizable_amount * 100), 2);
				$new_loan=$new_cust;
                $loan_details = $loan_payment_list;
                // Branch Wise Details (Table Form) Start
                $branch_list = $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.status'=>1, 'Branch.organization_id'=>$organization_id)));
				foreach($branch_list as $bkey=>$dbranch) {
					$branch_data_full[$bkey]=$this->dashboard_branch_table($bkey,$start_date,$end_date);
				}
			}
		}
		//Branch Manager Dashboard End
		$final_array=array();
		$final_array['call_array_val']=$user_info;
		$final_array['total_bm']=$total_bm;
		$final_array['total_lo']=$total_lo;
		$final_array['total_branch']=$total_branch;
		$final_array['total_kendra']=$total_kendra;
		$final_array['total_customer']=$total_customer;
		$final_array['total_loan_in_market']=$total_loan_in_market;
        $final_array['overdue_no']=$overdueno[0][0]['overdue_no']; 
		$final_array['overdue_amt']=$total_overdue;
		$final_array['realizable_amount']=($realizable_amount==''? 0 : $realizable_amount);
		$final_array['realized_amount']=($realized_amount==''? 0 : $realized_amount);
		$final_array['percentage_paid']=$percentage_paid;
		$final_array['new_loan']=$new_loan;
		$final_array['loan_details']=$loan_details;
		$final_array['branch_table_data']=$branch_data_full;
		$final_array['kendra_table_data']=$kendra_data_full;
		$final_array['loan_collection']=$loan_collection;
		return $final_array;
	}
	//Dashboard Extra Function End
	
    // Loan Officer Dashboard Function Start
    public function lo_index() {
		$this->layout = 'panel_layout';
		$this->set('title', 'Loan Officer Dashbord');
		// Call Value for the Dashboard from session
		$user_id=$this->Auth->user('id');
		$user_type_id=$this->Auth->user('user_type_id');
		$organization_id=$this->Auth->user('organization_id');
		$branch_id=$this->Auth->user('branch_id');
		$start_date= date("Y-m-d");
		$select_date=date("Y-m-d");
		$send_date['start_date']=$select_date;
		// User Values after post
		if ($this->request->is('post')) {
			$postarray=$this->request->data;
			$send_date['start_date']=$postarray['start_date'];
			$this->set('send_date', $send_date);
			$start_date=date("Y-m-d", strtotime($postarray['start_date']));
		}
		 // This is the user information array
		$user_info=array();
		$user_info['user_id']=$user_id;
		$user_info['user_type_id']=$user_type_id;
		$user_info['organization_id']=$organization_id;
		$user_info['branch_id']=$branch_id;
		$user_info['start_date']=$start_date;
		$send_date['date_diff']= 1;
		$final_array=$this->lo_dashboard_extra($user_info);
		$this->set('send_date', $send_date);            
		$this->set('dashboard_array', $final_array);
    }
	// Loan Officer Dashboard Function End
	
    // Loan Officer Dashboard Extra Function Start
	public function lo_dashboard_extra($user_info){
		// Static Variable Declaration
		$branch_data_full=array();
		$kendra_data_full=array();
		$total_bm=0;
		$total_lo=0;
		$total_branch=0;
		$total_kendra=0;
		$total_customer=0;
		$total_loan_in_market=0;
		$overdue_no=0;
		$realizable_amount=0;
		$realized_amount=0;
		$percentage_paid=0;
		$new_loan=0;
		$loan_details=0;
		$loan_collection=array();
		// Session Variable Declaration
		$user_id=$user_info['user_id'];
		$organization_id=$user_info['organization_id'];
		$user_type_id=$user_info['user_type_id'];
		$start_date=$user_info['start_date'];
		// Loan Officer Dashboard Start
		$user_data= $this->User->find('first',array('conditions'=>array('User.id'=>$user_id)));
		$total_kendra=$this->Kendra->find('count',array('conditions'=>array('Kendra.user_id'=>$user_id)));
		$total_customer=$this->Customer->find('count',array('conditions'=>array('Customer.user_id'=>$user_id)));
		if($total_customer != 0) {
			$due_loan = $this->LoanTransaction->find('all',array(
				'fields'=>array(
					'SUM(LoanTransaction.insta_principal_due) as due_balance'
			),
			'conditions'=>array(
				'LoanTransaction.insta_principal_paid'=> 0,
				
				 'Loan.loan_status_id'=>3,
				  'Loan.user_id'=>$user_id
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
			$total_loan_in_market=$due_loan[0][0]['due_balance'];
			$loan_collection = $this->LoanTransaction->find('all',array(
				'fields'=>array(
					'SUM(LoanTransaction.total_installment) as realizable_amount',
					'(SUM(LoanTransaction.insta_principal_paid)+SUM(LoanTransaction.insta_interest_paid)) as realized_amount',
					'Market.*'
				),
				'conditions'=>array(
					'Loan.user_id'=>$user_id,
					
					'Loan.loan_status_id'=>3,
					'LoanTransaction.insta_due_on'=>$start_date, 
				),
                'group'=>'`LoanTransaction`.`market_id`',
				'joins' =>array(
					array(
						'table' => 'loans',
						'alias' => 'Loan',
						'type' => 'inner',
						'foreignKey' => true,
						'conditions'=> array('Loan.id = LoanTransaction.loan_id')
					 ),
					 array(
						'table' => 'markets',
						'alias' => 'Market',
						'type' => 'inner',
						'foreignKey' => true,
						'conditions'=> array('Market.id = LoanTransaction.market_id')
					 )
				)
			));
            $loan_overdue = $this->LoanTransaction->find('all',array(
				'fields'=>array(
					'SUM(LoanTransaction.total_installment) as realizable_amount',
					'(SUM(LoanTransaction.insta_principal_paid)+SUM(LoanTransaction.insta_interest_paid)) as realized_amount',
				),
				'conditions'=>array(
				 'Loan.user_id'=>$user_id,
				 
				 'Loan.loan_status_id'=>3,
				 'LoanTransaction.insta_due_on'=>$start_date, 
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
            $loan_overdue_no = $this->LoanTransaction->find('all',array(
                'fields'=>array(
					'COUNT(LoanTransaction.id) as overdue_no'
				),
				'conditions'=>array(
					'Loan.user_id'=>$user_id,
					'LoanTransaction.insta_due_on'=>$start_date, 
					
					'LoanTransaction.insta_principal_paid'=> 0,
					'Loan.loan_status_id'=>3,
				),
				'joins' => array(
					array(
						'table' => 'loans',
						'alias' => 'Loan',
						'type' => 'inner',
						'foreignKey' => true,
						'conditions'=> array('Loan.id = LoanTransaction.loan_id')
					)
				)
			));
			$new_cust = $this->Loan->find('count', array(
				'conditions' => array(
					  'Loan.user_id'=>$user_id,
					  'Loan.loan_status_id'=>3,
					  'Loan.loan_dateout'=> $start_date
				)
			));
			$total_overdue=$loan_overdue[0][0]['realizable_amount']-$loan_overdue[0][0]['realized_amount'];
			$overdue_no=$loan_overdue_no[0][0]['overdue_no'];
			$realizable_amount=$loan_overdue[0][0]['realizable_amount'];
			$realized_amount=$loan_overdue[0][0]['realized_amount'];
			$percentage_paid=($realizable_amount>0)?round(($realized_amount/$realizable_amount * 100), 2):0;
			$new_loan=$new_cust;
		}
		$final_array=array();
		$final_array['call_array_val']=$user_info;
		$final_array['total_bm']=$total_bm;
		$final_array['total_lo']=$total_lo;
		$final_array['total_branch']=$total_branch;
		$final_array['total_kendra']=$total_kendra;
		$final_array['total_customer']=$total_customer;
		$final_array['total_loan_in_market']=$total_loan_in_market;
		$final_array['overdue_no']=$overdue_no;
		$final_array['realizable_amount']=($realizable_amount==''? 0 : $realizable_amount);
		$final_array['realized_amount']=($realized_amount==''? 0 : $realized_amount);
		$final_array['percentage_paid']=$percentage_paid;
		$final_array['new_loan']=$new_loan;
		$final_array['loan_collection']=$loan_collection;
		return $final_array;
	}
	//Loan Officer Dashboard Extra Function End
    
	

    // User login function start
	public function login() {
		//if already logged-in, redirect
		if($this->Session->check('Auth.User')){
		  if($this->Auth->user('user_type_id')==5)
			$this->redirect(array('action' => 'lo_index'));
        elseif($this->Auth->user('user_type_id')==6) 
			$this->redirect(array('action' => 'daily_loan_collection','controller'=>'dataentries'));
          else	
            $this->redirect(array('action' => 'index'));	
		}
		$this->layout = 'login';
		// if we get the post information, try to authenticate
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$data['LogRecord']['device_id']=$this->request->clientIp();
				$data['LogRecord']['user_id']=$this->Auth->user('id');
				$data['LogRecord']['device_type']=$this->detectDevice();
				$data['LogRecord']['start_time']=date("Y-m-d H:i:s");
				
				$this->LogRecord->save($data);
				$last_log_record=$this->LogRecord->getLastInsertId();
				$this->Session->write('LogRecord.id', $last_log_record);             
				$this->Session->setFlash(__('Welcome, '. $this->Auth->user('username')));
				$this->redirect($this->Auth->redirectUrl());
			} else {
				$this->Session->setFlash(__('Invalid username or password'));
			}
		} 
	}
	 // User login function end 
	 
    //user logout function start
	public function logout() {
		$end_time=date("Y-m-d h:i:sa");
		$logrecordid = $this->Session->read('LogRecord.id');
        $data['LogRecord']['id']=$logrecordid;
		$data['LogRecord']['end_time']=date("Y-m-d H:i:s");
        $data['LogRecord']['log_out']=1;		
		$this->LogRecord->save($data);
		$this->Session->destroy();
		$this->redirect($this->Auth->logout());
	}
	//user logout function end
	
	//user list function start
	public function user_list() {
		$this->paginate = array(
			'limit' => 6,
			'order' => array('User.username' => 'asc' )
		);
		$users = $this->paginate('User');
		$this->set(compact('users'));
    }
	//user list function end
	
	// User Registration function start
    public function add() { 
		$countrylist = $this->Country->find('list', array('fields' => array('id', 'name')));
		$this->set('countryList', $countrylist); // List of Countries
		$this->layout = 'register';
        if ($this->request->is('post')) {
            //pr($this->request->data);die;
			$this->request->data['User']['created'] = date("Y-m-d");
			$this->request->data['User']['modified'] = date("Y-m-d");
			$this->User->create();
			if ($this->User->save($this->request->data)) {
                $this->request->data['Organization']['user_id'] = $last_insert_user;
				if ($this->Organization->save($this->request->data)) {
					$last_insert_organizer=$this->Organization->getLastInsertId();
					$this->User->id = $last_insert_user;
					$this->User->saveField('organization_id', $last_insert_organizer);
					// Add Region Start
					$data['Region']['region_name']= 'Main Region';
					$data['Region']['region_details']= 'Main Region';
					$data['Region']['user_id']= $last_insert_user;
					$data['Region']['organization_id']=$last_insert_organizer;
					$data['Region']['created_on']=date("Y-m-d");
					$data['Region']['modified_on']= date("Y-m-d");
					$this->Region->save($data);
					// Add Region End
					// Organization Loan Type Array Start	
					$datasetting=array(
						'MINLP'=>'Minimum Loan Principal',
						'MAXLP'=>'Maximum Loan Principal',
						'CUR'=>'Currency Abbreviation',
						'MINSB'=>'Minimum Savings Balance',
						'LT'=>'Loan Type',
						'LPT'=>'Loan Period Type',
						'MNLPD'=>'Minimum Loan Period',
						'MXLPD'=>'Maximum Loan Period'
					);
					// Organization Loan Type Array end
					foreach($datasetting as $k=>$v)	{
								$data['Setting']['set_name'] = $v;
								$data['Setting']['set_short'] =$k;
								$data['Setting']['set_value'] = '';
								$data['Setting']['organization_id'] = $last_insert_organizer;
								$this->Setting->clear();
								$this->Setting->save($data);
					}
					// Organization Loan Fees setting Array Start
					$datafees=array(
							'LAF'=>'Loan Application Fee',
							'LFINE'=>'Loan Fine',
							'LIR'=>'Loan Interest Rate',
							'SIR'=>'Savings Interest Rate',
							'PROF'=>'Processing Fee',
							'SEC'=>'Security Deposite Rate'
					);
					// Organization Loan Fees setting Array end
					foreach($datafees as $kf=>$vf)	{
								$data['Fee']['fee_name'] = $vf;
								$data['Fee']['fee_short'] =$kf;
								$data['Fee']['fee_value'] = 0;
								$data['Fee']['organization_id'] = $last_insert_organizer;
								$this->Fee->clear();
								$this->Fee->save($data);
					}
				}
				$this->Session->setFlash(__('The user has been created'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be created. Please, try again.'));
			}	
        }
    }
	// User Registration function end
	
    //user edit function start
    public function edit($id = null) {
		if (!$id) {
			$this->Session->setFlash('Please provide a user id');
			$this->redirect(array('action'=>'index'));
		}
		$user = $this->User->findById($id);
		if (!$user) {
			$this->Session->setFlash('Invalid User ID Provided');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->User->id = $id;
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been updated'));
				$this->redirect(array('action' => 'edit', $id));
			}else{
				$this->Session->setFlash(__('Unable to update your user.'));
			}
		}
		if (!$this->request->data) {
			$this->request->data = $user;
		}
    }
	//user edit function end
	
    //User delete function start
    public function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash('Please provide a user id');
			$this->redirect(array('action'=>'index'));
		}
        $this->User->id = $id;
        if (!$this->User->exists()) {
            $this->Session->setFlash('Invalid user id provided');
			$this->redirect(array('action'=>'index'));
        }
        if ($this->User->saveField('status', 0)) {
            $this->Session->setFlash(__('User deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('User was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
	//User delete function end

	// Site Admin Login Function start
	public function admin_login() {
		//if already logged-in, redirect
		if($this->Session->check('Auth.User')){
			$this->redirect(array('action' => 'index'));		
		}
		// if we get the post information, try to authenticate
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->Session->setFlash(__('Welcome, '. $this->Auth->user('username')));
				$this->redirect($this->Auth->redirectUrl());
			} else {
				$this->Session->setFlash(__('Invalid username or password'));
			}
		} 
	}
	// Site Admin Login Function end
	
	//User forgot password function start
	public function forgot() {
		$this->layout = 'forgot';
		// if we get the post information, try to authenticate
		if ($this->request->is('post')) {
			$emailCount = $this->User->find('first', array(
				'conditions' => array('User.email' =>$this->request->data['User']['email'])
			));
			if(!empty($emailCount)){
				// Update Password Field
				$id=$emailCount['User']['id'];
				$this->User->id=$id;
				$this->User->saveField("password","123456");
				$dbEmail=$emailCount['User']['email'];
				// Email Send
				$this->Email->from = 'no-reply@microfinanceapp.com';
				$this->Email->to = $dbEmail;
				$this->set('heading', 'You Login Password');
				$this->set('content', "Your Updated Password is: 123456");
				$this->Email->subject = 'Forgot Password';
				$this->Email->layout = 'report_msg';
				$this->Email->template = 'text_template';
				$this->Email->additionalParams="-f$dbEmail";
				$this->Email->sendAs = 'html';
				try {
					if ($this->Email->send()) {
						$this->Session->setFlash(__('Password Send to your Email ID'));
						return true;
					} else {
						return false;
					}
				}
				catch (phpmailerException $e) {
					return false;
				}
				catch (exception $e) {
					return false;
				}
			} else {
				$this->Session->setFlash(__('Invalid Email ID'));
			}
		} 
	}
	//User forgot password function end
	
	// User Edit Function start
	public function user_edit() {
		$this->set('title', 'Edit Profile');  // This is used for Title for every page
		$this->layout = 'panel_layout';
		$id= $this->Session->read('Auth.User.id');
		if (!$id) {
			$this->Session->setFlash('Please provide a user id');
			$this->redirect(array('action'=>'index'));
		}
		$user = $this->User->findById($id);
		if (!$user) {
			$this->Session->setFlash('Invalid User ID Provided');
			$this->redirect(array('action'=>'index'));
		}
		$this->set('data',$user);
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->User->id = $id;
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been updated'));
				$this->redirect(array('action' => 'edit', $id));
			}else{
				$this->Session->setFlash(__('Unable to update your user.'));
			}
		}
		if (!$this->request->data) {
			$this->request->data = $user;
		}
    }
	// User Edit Function end
	
	// User Picture Upload function start
	public function upload_pic() {
		$this->set('title', 'Upload Profile Picture');  // This is used for Title for every page
		$this->layout = 'panel_layout';
		$id= $this->Session->read('Auth.User.id');
		 if (!$id) {
			$this->Session->setFlash('Please provide a user id');
			$this->redirect(array('action'=>'index'));
		}
		$user = $this->User->findById($id);
		if (!$user) {
			$this->Session->setFlash('Invalid User ID Provided');
			$this->redirect(array('action'=>'index'));
		}
		$this->set('data',$user);
		if ($this->request->is('post') || $this->request->is('put')) {
			$file_name=$this->Session->read('Auth.User.id').'_profilepic_'.time().'_'.$_FILES['upl']['name'];
			$filename = WWW_ROOT."upload/profilePic/".$file_name; 
			if(move_uploaded_file($_FILES['upl']['tmp_name'],$filename )){
				$this->request->data['User']['image']=$file_name;
				$this->request->data['User']['modified']=date("Y-m-d");
				$this->Session->write('Auth.User.image', $file_name);
				$this->request->data['User']['id']=$this->Session->read('Auth.User.id');
				if ($this->User->save($this->request->data)) {
					 /* save message to session */
					$this->Session->setFlash('Picture uploaded successfuly.');
					/* redirect */
					$this->redirect(array('action' => 'upload_pic'));
				} else {
					/* save message to session */
					$this->Session->setFlash('There was a problem uploading image. Please try again.');
				}
			}
		}
		if (!$this->request->data) {
			$this->request->data = $user;
		}
    }
	// User Picture Upload function end	
	
	// Create Employee function start
	public function employee($emp_id=''){
		$this->layout = 'panel_layout';
		$this->set('title', 'Create Employee');
		$ut_list = $this->UserType->find('list', array('fields' => array('id', 'user_type'), 'conditions'=> array('id !='=>1)));
		$this->set('ut_list', $ut_list);
		$identity_type=$this->id_proof_name();
		$this->set('identity_type', $identity_type);
		$countrylist = $this->Country->find('list', array('fields' => array('id', 'name')));
		$this->set('countryList', $countrylist);
        $this->set('emp_id',$emp_id);
        
        if ($emp_id != '') {
            $emp_data = $this->User->findById($emp_id);
            if (!$this->request->data) {
                $this->request->data = $emp_data;
                unset($this->request->data['User']['password']);
            }
        }
		
		if ($this->request->is('post')) {
		  //pr($this->request->data);die;
          $is_exist=0;
          if ($emp_id == '') {
            $is_exist = $this->User->find('count',array('conditions'=>array('User.email'=>$this->request->data['User']['email'])));
          }
          if($is_exist==0){
            
            if ($emp_id != '') {
                $this->request->data['User']['modified'] = date("Y-m-d H:i:s");
                if(empty($this->request->data['User']['password'])){
                    unset($this->request->data['User']['password']);
                }
                $this->User->id = $emp_id;
            } else {
                $this->request->data['User']['organization_id'] = $this->Auth->user('organization_id');
                $this->request->data['User']['created'] = date("Y-m-d H:i:s");
                $this->User->create();
            }
            
            //prepare idproof data
            if(!empty($this->request->data['idproof'])){
                $idproof = $this->request->data['idproof'];
                $idproof_arr = array();
                foreach($idproof['id_proof_no'] as $k=>$v){
                    $idproof_arr[$k]['id_proof_no'] = $v;
                    $idproof_arr[$k]['id_proof_type'] = $idproof['id_proof_type'][$k];
                    
                }
                $this->request->data['User']['id_proof']=json_encode($idproof_arr);
            }
            // end idproof data prepare
			
			$this->request->data['User']['username'] = $this->request->data['User']['first_name'];
			
            
			if ($this->User->save($this->request->data)) {
			     
                if ($emp_id == ''){
    				$last_insert_user=$this->User->getLastInsertId();
    				
                    $username=$this->request->data['User']['username'];
                    $password = $this->request->data['User']['password'];
        			$dbEmail=$this->request->data['User']['email'];
                    
                    $this->Session->setFlash(__('The user has been Created'));
    				//$this->request->data['Idproof']['user_id'] = $last_insert_user;
    				//$this->Idproof->save($this->request->data);
    				// Email Send
    				$this->Email->from = 'no-reply@microfinanceapp.com';
    				$this->Email->to = $dbEmail;
    				$this->set('heading', 'Login Details');
    				$this->set('content', "Your email id is: $dbEmail and password is: $password");
    				$this->Email->subject = 'Your Username and Password';
    				$this->Email->layout = 'report_msg';
    				$this->Email->template = 'text_template';
    				$this->Email->additionalParams="-f$dbEmail";
    				$this->Email->sendAs = 'html';
    				try {
    					if ($this->Email->send()) {
    						
    						$this->redirect(array('action' => 'employee'));
    						return true;
    					} else {
    						return false;
    					}
    				}
    				catch (phpmailerException $e) {
    					return false;
    
    				}
    				catch (exception $e) {
    					return false;
    				}
               }else{
                    $this->Session->setFlash(__('The user has been Saved'));
                    
               }
                
			} else {
				$this->Session->setFlash(__('The user could not be created. Please, try again.'));
			}
            } //is exist if end	
            else{
                $this->Session->setFlash(__('The user with same email already exist.'));
                
            }
		}
	}
	// Create Employee function end
    
    public function ajax_idproof_row(){
        $this->layout = 'ajax';
        $identity_type=$this->id_proof_name();
		$this->set('identity_type', $identity_type);
    }

	// Change Password function START
	public function change_password(){
		$this->layout = 'panel_layout';
        $this->set('title', 'Change Password');
		if ($this->request->is('post')) {
			$new_password=$this->request->data['txtnewPassword'];
			$data['User']['id']=$this->Auth->user('id');
			$data['User']['password']=$new_password;
			if ($this->User->save($data)) {
				$this->Session->setFlash(__('The user password has been change'));
				$this->redirect(array('action' => 'change_password'));
			} else {
				$this->Session->setFlash(__('The user password uable to change. Please, try again.'));
			}	
			
		}
	}
	// Change Password function END
    
    
    // Show all the Region lists function start
    public function employee_list(){
        $this->layout = 'panel_layout';
        $this->set('title', 'Employee List');
        $organisation_id = $this->Auth->User('organization_id');
        //$this->User->unBindModel(array('hasMany' => array('Branch'),'belongsTo'=>array('Organization')));
        $employee_data = $this->User->find('all',array('conditions'=>array('User.organization_id'=>$organisation_id,'User.user_type_id !='=>1,'User.user_type_id !='=>2,'User.status'=>1)));
        //pr($employee_data);die;
        $this->set('employee_data', $employee_data);
        
    }
	// Show all the Region lists function end
    
    
    
    
    
    
    
    
    
}
// End of User controller
?>