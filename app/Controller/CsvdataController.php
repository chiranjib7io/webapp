<?php
// This is CSV data controller. This controller is for upload data via CSV. 
App::uses('CakeEmail', 'Network/Email');
class CsvdataController extends AppController {
	// List of models which are used in the csv data controller
		var $uses = array('User','Organization','Region','Branch','Kendra','Customer','Loan','Saving','Idproof','LogRecord','Country', 'Setting', 'Fee', 'UserType','LoanTransaction', 'SavingsTransaction', 'UploadReport');
	
	// If there is any wrong navigation
	public function index(){
		
	}
	
	// Data Uploaded via CSV Function START
	public function upload_csv_data(){
		$this->layout = 'panel_layout';
		$this->set('title', 'Upload CSV Data');
		// Save all the fees setting value of the organization
        $org_fee_data= $this->get_organization_settings_fees($this->Auth->user('organization_id'));
		$application_fee=$org_fee_data['Fee']['LAF'];
		$late_fine=$org_fee_data['Fee']['LFINE'];
		$loan_interest_rate=$org_fee_data['Fee']['LIR'];
		$saving_interest_rate=$org_fee_data['Fee']['SIR'];
		$processing_fee=$org_fee_data['Fee']['PROF'];
		$security_fee=$org_fee_data['Fee']['SEC'];
		$admission_fee=$org_fee_data['Fee']['ADMF'];
		$riskfund_fee=$org_fee_data['Fee']['RSKF'];
		$currency=$org_fee_data['Setting']['CUR']; 
        if($this->Auth->user('user_type_id')==2){
            $branch_list = $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.status'=>1, 'Branch.organization_id'=>$this->Auth->user('organization_id'))));
            $kendra_list = array();
            $branch_data = array();
        }else{
            $branch_list = $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.status'=>1, 'Branch.id'=>$this->Auth->user('branch_id'))));
            $kendra_list = $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.status'=>1,'Kendra.user_id'=>$this->Auth->user('id'))));
            
            $branch_data = $this->Branch->find('first',array(
            'conditions'=>array('Branch.id'=>$this->Auth->user('branch_id'),'Branch.status'=>1)
            ));    
        }
		$this->set('branch_list', $branch_list);
		$this->set('kendra_list', $kendra_list);
		$this->set('branch_data', $branch_data);
		 if ($this->request->is('post')) {	 
			// Main Values
			$organization_id=$this->Auth->user('organization_id');
			$user_id=$this->Auth->user('id');
			$upload_date=date('Y-m-d');
			$upload_report_type='CSV';
			$branch_id=$this->request->data['UploadReport']['branch_id'];
			$kendra_id =$this->request->data['UploadReport']['kendra_id'];
			$file_name=$this->Session->read('Auth.User.id').'_'.$branch_id.'_'.$kendra_id.'_'.time().'_'.$_FILES['upl']['name'];
			$filename = WWW_ROOT."upload/uploadRawReport/".$file_name;
			
			/* Start uploaded file to the server */
			if(move_uploaded_file($_FILES['upl']['tmp_name'],$filename )){
			  $this->request->data['UploadReport']['user_id']=$user_id;
			  $this->request->data['UploadReport']['organization_id']=$organization_id;
			  $this->request->data['UploadReport']['branch_id']=$branch_id;
			  $this->request->data['UploadReport']['kendra_id']=$kendra_id ;
			  $this->request->data['UploadReport']['file_name']=$file_name;
			  $this->request->data['UploadReport']['created_on']=$upload_date;
			  $this->request->data['UploadReport']['modified_on']=$upload_date;  
			  if ($this->UploadReport->save($this->request->data)) {
				$upload_flag=1;
				$lastId = $this->UploadReport->getLastInsertId();
				/* save message to session */
				$this->Session->setFlash('File uploaded successfully.');
			  } else {
				/* save message to session */
				$this->Session->setFlash('There was a problem uploading file. Please try again.');
			  }
			}
			/* End Uploaded File move to the server*/
			
			// CSV Upload Process Start
			// If file can move successfully to the server then csv process will start
			if($upload_flag==1){
				$file = fopen("$filename","r"); 
				$j=0;
				$insertflag=0;
				while(! feof($file)){
					$cust_arr=array();
					$r1=fgets($file);
					$len=sizeof($r1);
					$str1= str_replace(",","|",$r1);
					$str2=str_replace(";",",",$str1);
					$r2=explode("|",$str2);
					$temp = sizeof($r2);
					if($temp == 12){
						// Explore The CSV File Start
						$cust_fname=$r2[0];
						$cust_lname=$r2[1];
						$principal_amount=$r2[2];
						$interest_rate=$r2[3];
						$repay_amount=$r2[4];
						$number_of_period=$r2[5];
						$interest_type=$r2[6];
						$period_type=$r2[7];
						$current_installment_number=$r2[8];
						$installment_date=$r2[9];
						$total_due_amount=$r2[10];
						$total_saving_amount=$r2[11];
						// Explore the CSV File End
					}
					// Create Customer Array Start
					$this->request->data['Customer']['user_id'] = $user_id;
					$this->request->data['Customer']['cust_fname'] = $cust_fname;
					$this->request->data['Customer']['cust_lname'] = $cust_lname;
					$this->request->data['Customer']['created_on'] = $upload_date;  
					$this->request->data['Customer']['modified_on'] = $upload_date;  
					$this->request->data['Customer']['branch_id'] = $branch_id;
					$this->request->data['Customer']['kendra_id'] = $kendra_id;
					$this->request->data['Customer']['organization_id'] = $organization_id;
					$this->request->data['Customer']['cust_sex'] = 'Female';
					$this->request->data['Customer']['upload_type'] = $upload_report_type;
					// Create Customer Array End
					if($insertflag!=0){ 
						// Create Customer in the database Start
						$this->Customer->clear();
						$this->Customer->create();
						$this->Customer->save($this->request->data);
						$last_insert_Customer=$this->Customer->getLastInsertId();
						$this->request->data['Idproof']['customer_id'] = $last_insert_Customer;
						$this->Idproof->clear();
						$this->Idproof->create();
						$this->Idproof->save($this->request->data);
						// Create Customer in the database End
						
						// Create Loan Array Start
						$count_loan = $this->Loan->find('count');
						$loan_no = 'L-'.$organization_id.'-'.$last_insert_Customer.'-'.($count_loan + 1);
						$loan_rate= $repay_amount/ $number_of_period;
						$cust_arr[$last_insert_Customer]=$loan_rate;
						if($period_type == 'WEEK'){
							$interval_date= 7;
						} else {
							$interval_date= 30;
						}
						$total_back_day=($current_installment_number-1)*$interval_date;
						$exp_loan_date=($current_installment_number)*$interval_date;
						$loan_date= date( "Y-m-d", strtotime( "$installment_date -$exp_loan_date day" ) );
						$loan_dateout= date( "Y-m-d", strtotime( "$installment_date -$exp_loan_date day" ) );
						$loan_repay_start= date( "Y-m-d", strtotime( "$installment_date -$total_back_day day" ) );
						$paid_installment_no=$number_of_period-($total_due_amount/$loan_rate);
						$this->request->data['Loan']['created_on'] =$upload_date;
						$this->request->data['Loan']['loan_number'] = $loan_no;
						$this->request->data['Loan']['user_id'] = $user_id;
						$this->request->data['Loan']['customer_id'] = $last_insert_Customer;
						$this->request->data['Loan']['loan_status_id'] = 3;
						$this->request->data['Loan']['loan_issued'] = 1;
						$this->request->data['Loan']['loan_principal'] = $principal_amount;
						$this->request->data['Loan']['loan_interest'] = $interest_rate;
						$this->request->data['Loan']['loan_repay_total'] = $repay_amount;
						$this->request->data['Loan']['currency'] = 'INR';
						$this->request->data['Loan']['loan_period'] = $number_of_period;
						$this->request->data['Loan']['loan_period_unit'] = $period_type;
						$this->request->data['Loan']['loan_type'] = $interest_type;
						$this->request->data['Loan']['loan_date'] = $loan_date;
						$this->request->data['Loan']['loan_dateout'] = $loan_dateout;
						$this->request->data['Loan']['loan_rate'] = $loan_rate;
						$this->request->data['Loan']['loan_repay_start'] = $loan_repay_start;
						$this->request->data['Loan']['admission_fee'] = $admission_fee;
						$this->request->data['Loan']['processing_fee'] = $principal_amount*$processing_fee/100;
						$this->request->data['Loan']['riskfund_fee'] =  $principal_amount*$riskfund_fee/100;
						$this->request->data['Loan']['security_fee'] =  $principal_amount*$security_fee/100;
						$this->request->data['Loan']['currency'] = $currency;
						$this->request->data['Loan']['loan_fee'] = $application_fee;
						$this->request->data['Loan']['branch_id'] = $branch_id;
						$this->request->data['Loan']['kendra_id'] = $kendra_id;
						$this->request->data['Loan']['organization_id'] = $organization_id;
						$this->request->data['Loan']['upload_type'] = $upload_report_type;
						// Loan Create Array End
					 
						// Saving Create Array Start
						$this->request->data['Saving']['branch_id'] = $branch_id;
						$this->request->data['Saving']['kendra_id'] = $kendra_id;
						$this->request->data['Saving']['organization_id'] = $organization_id;
						$this->request->data['Saving']['currency_id'] = 1;
						$this->request->data['Saving']['savings_date'] = $upload_date;
						$this->request->data['Saving']['created_on'] = $upload_date;
						$this->request->data['Saving']['modified_on'] = $upload_date;
						$this->request->data['Saving']['user_id'] = $user_id;
						$this->request->data['Saving']['savings_amount'] = $total_saving_amount;
						$this->request->data['Saving']['upload_type'] = $upload_report_type;
						
						// Loan Saving to the database
						$this->Loan->clear();
						$this->Loan->create();
						$this->Loan->save($this->request->data);
						
						// Create Loan Instalment Start
						$loan_id=$this->Loan->getLastInsertId();
						// Function for create instalment
						$this->create_loan_transaction_csv($loan_id,$loan_repay_start);
						// Create Loan Instalment End
						
						// Loan Transaction Payment based on overdue Start
							for($insta_no=1; $insta_no<=$paid_installment_no; $insta_no++){
								$due_on = $loan_repay_start;
								$this->loan_amount_collection($kendra_id,$due_on,$insta_no,$cust_arr);
								$loan_repay_start= date( "Y-m-d", strtotime( "$loan_repay_start +$interval_date day" ) );
							}
						// Loan Transaction Payment based on overdue End
					
						// Saving Data Save to the databaseStart
							$this->request->data['Saving']['customer_id'] = $last_insert_Customer;
							$this->Saving->clear();
							$this->Saving->create();
							$this->Saving->save($this->request->data);
						// Saving Data Save to the database End
						
						// Saving Transaction Save to the database Start
							$due_on =  $upload_date;
							$cust_arr_save [$last_insert_Customer]=$total_saving_amount;
							$this->savings_amount_collection_csv($kendra_id,$due_on,$cust_arr_save);
						// Saving Transaction Save to the database End
					}
					$insertflag++;
				}
                    $this->Session->setFlash('Data Uploaded.');
                    
				}else {
        			$this->Session->setFlash('There was a problem uploading file. Please try again.');
        		}
			}
			// CSV Upload Process End	    
	}
	// Data Uploaded via CSV Function END
	
	// Saving amount collection via CSV function start
	 public function savings_amount_collection_csv($kid, $due_date, $cust_arr){
        $user_id = $this->Auth->user('id');
        foreach ($cust_arr as $cust_id => $repay_total) {
            $this->Loan->unBindModel(array('hasMany' => array('LoanTransaction')));
            $saving_data = $this->Saving->find('first', array('conditions' => array(
                    'Saving.customer_id' => $cust_id,
                    'Saving.kendra_id' => $kid,
                    'Saving.status' => 1)));
            $organization_id = $saving_data['Organization']['id'];
            $region_id = $saving_data['Region']['id'];
            $branch_id = $saving_data['Branch']['id'];
            $kendra_id = $saving_data['Kendra']['id'];
            $savings_amt = $repay_total;
            $savings_arr['Saving']['id'] = $saving_data['Saving']['id'];
            $savings_arr['Saving']['current_balance'] = $saving_data['Saving']['current_balance'] +
                $savings_amt;
            $savings_arr['Saving']['modified_on'] = date('Y-m-d H:i:s');
            $savings_arr['Saving']['user_id'] = $user_id;
            $savings_arr['SavingsTransaction']['saving_id'] = $saving_data['Saving']['id'];
            $savings_arr['SavingsTransaction']['transaction_on'] = $due_date;
            $savings_arr['SavingsTransaction']['amount'] = $savings_amt;
            $savings_arr['SavingsTransaction']['transaction_type'] = 'Deposite';
            $savings_arr['SavingsTransaction']['balance'] = $savings_arr['Saving']['current_balance'];
            $savings_arr['SavingsTransaction']['customer_id'] = $cust_id;
            $savings_arr['SavingsTransaction']['organization_id'] = $organization_id;
            $savings_arr['SavingsTransaction']['branch_id'] = $branch_id;
            $savings_arr['SavingsTransaction']['kendra_id'] = $kendra_id;
            $savings_arr['SavingsTransaction']['created_on'] = date("Y-m-d");
            $savings_arr['SavingsTransaction']['user_id'] = $this->Auth->user('id');
			$savings_arr['SavingsTransaction']['upload_type'] = 'CSV';
            if ($savings_amt != 0 || $savings_amt != '') {
                $this->Saving->clear();
                $this->SavingsTransaction->clear();
                $this->Saving->save($savings_arr);
                $this->SavingsTransaction->save($savings_arr);
            }
        }
    }
	// Saving amount collection via CSV function end
    
	// Call kendra list in the ajax function start
    public function ajax_kendra_list($bid=''){
        $this->layout = 'ajax';
        
        $branch_data = $this->Branch->find('first',array(
            'conditions'=>array('Branch.id'=>$bid,'Branch.status'=>1)
            ));
        $kendra_list = $this->Kendra->find('list',array(
            'fields'=>array('Kendra.id','Kendra.kendra_name'),
            'conditions'=>array('Kendra.branch_id'=>$bid,'Kendra.status'=>1)
            ));
        $this->set('kendra_list', $kendra_list);
        $this->set('branch_data', $branch_data);
    }
	// Call kendra list in the ajax function end
	
	// Create loan transaction for the CSV data function start
	public function create_loan_transaction_csv($loan_id = '', $start_date) {
        $loan_data = $this->Loan->find('first', array('conditions' => array('Loan.id' =>
                    $loan_id)));
        $loan_principal = $loan_data['Loan']['loan_principal'];
        $loan_interest = $loan_data['Loan']['loan_interest'];
        $inst_amount = $loan_data['Loan']['loan_rate'];
        $repaytotal_amount = $loan_data['Loan']['loan_repay_total'];
        $currency = $loan_data['Loan']['currency'];
        $period_unit = $loan_data['Loan']['loan_period_unit'];
        $loan_period = $loan_data['Loan']['loan_period'];
        $loan_type = $loan_data['Loan']['loan_type'];
        $loan_no = $loan_data['Loan']['loan_number'];
        $loan_dateout = $loan_data['Loan']['loan_dateout'];

        //Create the EMI amount
        $e = 1;
        $temp_principal = $loan_principal;
        $start_date = date("Y-m-d", strtotime($start_date));
        while ($e <= $loan_period) {
            if ($loan_type == 'FIXED') {

                $intr = round($loan_principal * $loan_interest / 100);
                $instal_principal = round($loan_principal / $loan_period);
                $instal_interest = round($intr / $loan_period);
            } else {
                if ($period_unit == 'WEEK') {
                    $l = 1 / 52;
                    $i = $loan_interest / 100 * $l;
                    $instal_interest = $temp_principal * $i;
                    $instal_principal = round($inst_amount - $instal_interest);
                    $temp_principal = $temp_principal - $instal_principal;
                } else {
                    $l = 1 / 12;
                    $i = $loan_interest / 100 * $l;
                    $instal_interest = round($temp_principal * $i);
                    $instal_principal = round($inst_amount - $instal_interest);
                    $temp_principal = $temp_principal - $instal_principal;
                }
            }
            //Insert into LoanTransaction
            $trans_data['LoanTransaction']['loan_id'] = $loan_data['Loan']['id'];
            $trans_data['LoanTransaction']['insta_no'] = $e;
            $trans_data['LoanTransaction']['insta_due_on'] = $start_date;
            $trans_data['LoanTransaction']['total_installment'] = $loan_data['Loan']['loan_rate'];
            $trans_data['LoanTransaction']['insta_principal_due'] = $instal_principal;
            $trans_data['LoanTransaction']['insta_interest_due'] = $instal_interest;
            $trans_data['LoanTransaction']['customer_id'] = $loan_data['Loan']['customer_id'];
            $trans_data['LoanTransaction']['organization_id'] = $loan_data['Loan']['organization_id'];
            $trans_data['LoanTransaction']['region_id'] = $loan_data['Loan']['region_id'];
            $trans_data['LoanTransaction']['branch_id'] = $loan_data['Loan']['branch_id'];
            $trans_data['LoanTransaction']['kendra_id'] = $loan_data['Loan']['kendra_id'];
            $trans_data['LoanTransaction']['created_on'] = date("Y-m-d H:i:s");
			$trans_data['LoanTransaction']['upload_type'] = 'CSV';
            $this->LoanTransaction->clear();
            $this->LoanTransaction->save($trans_data);
            if ($period_unit == 'WEEK') {
                $date = strtotime($start_date);
                $date = strtotime("+1 Week", $date);
            } else {
                $date = strtotime($start_date);
                $date = strtotime("+1 month", $date);
            }
            $start_date = date("Y-m-d", $date);
            // Loan transaction end
            $e++;
        }
    }
	// Create loan transaction for the CSV data function end
}
// CSV data controller end
?>