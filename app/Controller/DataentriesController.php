<?php
// This is customer controller. All the functions related with customer is listed here.
App::uses('CakeEmail', 'Network/Email');
class DataentriesController extends AppController {
	// List of models which are used in the customer controller
	var $uses = array('User','Organization','Region','Branch','Kendra','Customer','Loan','Saving','Idproof','LogRecord','Country', 'LoanStatus','SavingsTransaction', 'LoanTransaction','Market');
    
    public $components = array('Paginator');

	
	
	// Create new customer function start
	 public function save_customer($emp_id=''){
		$this->layout = 'panel_layout';
		$this->set('title', 'Manage Customer');
        $org_data= $this->get_organization_settings_fees($this->Auth->user('organization_id'));

        $branch_list= $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.organization_id'=>$this->Auth->user('organization_id'))));
        $kendra_list = $this->Kendra->find('list', array('fields' => array('id', 'kendra_name'),'conditions'=> array('Kendra.organization_id'=>$this->Auth->user('organization_id'))));
        $market_list = $this->Market->find('list', array('fields' => array('id', 'market_name'),'conditions'=> array('Market.organization_id'=>$this->Auth->user('organization_id')),'order'=>array('Market.market_name asc')));
        $user_list = $this->User->find('list', array('fields' => array('id', 'fullname'),'conditions'=> array('User.organization_id'=>$this->Auth->user('organization_id'),'User.user_type_id'=>5),'order'=>array('User.fullname asc')));
        array_unshift($kendra_list, "Not Applicable");
        $this->set('market_list',$market_list);
        $this->set('kendra_list',$kendra_list);
        $this->set('user_list',$user_list);
        
		$identity_type=$this->id_proof_name();
		$this->set('identity_type', $identity_type);
        $this->set('emp_id',$emp_id);
        $emp_data = array();
        if ($emp_id != '') {
            $emp_data = $this->Customer->findById($emp_id);
            if (!$this->request->data) {
                $this->request->data = $emp_data;
            }
        }
		$this->set('emp_data',$emp_data);
		if ($this->request->is('post')) {
		  //pr($this->request->data);die;
		    //IMAGE UPLOAD SECTION START
           if (!empty($_FILES['customer_image']['name'])) {
                    //    $_FILES['customer_image']['name'];
                        $file = $_FILES['customer_image'];
                        $ext = substr(strtolower(strrchr($file['name'], '.')), 1);
                        $arr_ext = array('jpg', 'jpeg', 'gif','png');
                        $image_name="CUSTOMER_".rand(1,1000000000).'_'.$file['name'];
                        if (in_array($ext, $arr_ext)) {
                            move_uploaded_file($file['tmp_name'], WWW_ROOT . 'customerImages/' . $image_name);
                            //prepare the filename for database entry
                            $this->request->data['Customer']['customer_image'] = $image_name;
                                }
                         }
        //END IMAGE UPLOAD SECTION
          
          
          
		  //pr($this->request->data);//die;
            $this->request->data['Customer']['cust_dob'] = !empty($this->request->data['Customer']['cust_dob'])?$this->request->data['Customer']['cust_dob']:'0000-00-00 00:00:00';
            if ($emp_id != '') {
                $this->request->data['Customer']['modified_on'] = date("Y-m-d H:i:s");
                
                $this->Customer->id = $emp_id;
            } else {
                $this->request->data['Customer']['organization_id'] = $this->Auth->user('organization_id');
                $this->request->data['Customer']['created_on'] = date("Y-m-d H:i:s");
                $this->Customer->create();
            }
            
            //prepare idproof data
            if(!empty($this->request->data['idproof'])){
                $idproof = $this->request->data['idproof'];
                $idproof_arr = array();
                foreach($idproof['id_proof_no'] as $k=>$v){
                    $idproof_arr[$k]['id_proof_no'] = $v;
                    $idproof_arr[$k]['id_proof_type'] = $idproof['id_proof_type'][$k];
                    
                }
                $this->request->data['Customer']['id_proof']=json_encode($idproof_arr);
            }
            // end idproof data prepare
			$mkt_data = $this->Market->findById($this->request->data['Customer']['market_id']);
			$this->request->data['Customer']['organization_id']=$mkt_data['Market']['organization_id'];
            $this->request->data['Customer']['branch_id']=$mkt_data['Market']['branch_id'];
            $this->request->data['Customer']['region_id']=$mkt_data['Market']['region_id'];
			//pr($this->request->data);die;
            
			if ($this->Customer->save($this->request->data)) {
			     $last_insert_user=$this->User->getLastInsertId();
                if ($emp_id == ''){
    				                    
                    $this->Session->setFlash(__('The Customer has been Created'));
                    $emp_id = $last_insert_user;
    				
               }else{
                    $this->Session->setFlash(__('The Customer has been Saved'));
                    
               }
               
               return $this->redirect('/dataentries/save_customer/'.$emp_id);
                
			} else {
				$this->Session->setFlash(__('The Customer could not be created. Please, try again.'));
			}
            
		}
    }
	
	public function ajax_idproof_row(){
        $this->layout = 'ajax';
        $identity_type=$this->id_proof_name();
		$this->set('identity_type', $identity_type);
    }
	
public function loan_payment()
{
  
  $this->layout = 'panel_layout';
  
  $bm_list = $this->Branch->find('list', array('fields' => array('id','branch_name'), 'conditions' => array('Branch.organization_id' => $this->Auth->user('organization_id'))));
  $this->set('bm_list', $bm_list);
  $this->set('creditOfficers',$this->User->find('list',array('fields'=>array('id','fullname'),'conditions'=>array('User.user_type_id'=>5,'AND'=>array('User.organization_id'=>$this->Auth->user('organization_id'))))));
  if($this->request->is('post'))
  {
    //pr($this->request->data);die;
    $region_id=$this->Branch->findById($this->request->data['User']['branch_id'],array('Branch.region_id'));
  
    
    $user_id=$this->User->findById($this->request->data['User']['credit_officer_id'],array('User.id'));
  
    if($this->request->data['cust_type']==0){
        $customer_info=array();
        $customer_info['Customer']['branch_id'] = $this->request->data['User']['branch_id'];
        $customer_info['Customer']['region_id']=$region_id['Branch']['region_id'];
        $customer_info['Customer']['user_id']=$user_id['User']['id'];
        $customer_info['Customer']['market_id'] = $this->request->data['Market']['market_id'];
        if(!empty($this->request->data['group_id']))
        {
            $customer_info['Customer']['kendra_id'] = $this->request->data['group_id'];
             $accout_info['Account']['kendra_id'] = $this->request->data['group_id'];
             $loan_info['Loan']['kendra_id']=$this->request->data['group_id'];
             $saccout_info['Account']['kendra_id'] = $this->request->data['group_id'];
             $saving_info['Saving']['kendra_id'] = $this->request->data['group_id'];
             $strans['SavingsTransaction']['kendra_id'] = $this->request->data['group_id'];
        }
        else
        {
          $customer_info['Customer']['kendra_id'] = "0";  
           $accout_info['Account']['kendra_id'] ="0";
           $loan_info['Loan']['kendra_id']="0";
           $saccout_info['Account']['kendra_id'] = "0";
           $saving_info['Saving']['kendra_id'] = "0";
           $strans['SavingsTransaction']['kendra_id'] = "0";
        }
        $customer_info['Customer']['created_on'] = date("Y-m-d H:i:s");
        $customer_info['Customer']['modified_on'] = date("Y-m-d H:i:s");
       
        $customer_info['Customer']['organization_id']=$this->Auth->user('organization_id');
        $customer_info['Customer']['cust_fname'] = $this->request->data['User']['cust_fname'];
        $customer_info['Customer']['guardian_name'] = $this->request->data['User']['guardian_name'];
        $customer_info['Customer']['guardian_reletion_type'] =   $this->request->data['User']['guardian_reletion_type'];
        $this->Customer->create();
        $this->Customer->save($customer_info);
        $customer_id= $this->Customer->getLastInsertID();
    }else{
        $customer_id= $this->request->data['customer_id'];
    }
    if(!empty($customer_id) && !empty($this->request->data['Saving']['account_no'])) // For Saving Account
    {
        $principal=$this->request->data['Saving']['currentBalance'];
      
      $saccout_info=array();
      $saccout_info['Account']['customer_id'] =$customer_id;
      $saccout_info['Account']['organization_id']=$this->Auth->user('organization_id');
      $saccout_info['Account']['region_id']=  $region_id['Branch']['region_id'];
      $saccout_info['Account']['branch_id']=$this->request->data['User']['branch_id'];
      $saccout_info['Account']['market_id']=   $this->request->data['Market']['market_id']; 
      $saccout_info['Account']['user_id']=$user_id['User']['id'];
      $saccout_info['Account']['account_type']='SAVING_'.$this->request->data['Saving']['Savings_Type'];
      $saccout_info['Account']['account_number']=$this->request->data['Saving']['account_no'];
      $saccout_info['Account']['interest_rate']=$this->request->data['Saving']['intRate'];
      $saccout_info['Account']['opening_overdraft_balance']=$principal;
      $saccout_info['Account']['created_on']=date("Y-m-d H:i:s");
      $saccout_info['Account']['modified_on']=date("Y-m-d H:i:s");
      $saccout_info['Account']['plan_amount']=$this->request->data['Saving']['minDepostitAnmt'];
      $saccout_info['Account']['exces_interest']=$this->request->data['Saving']['intRate']-3;
      $saccout_info['Account']['upload_by']=$this->Auth->user('id');
      $saccout_info['Account']['upload_type']='DATAENTRY';
      $this->Account->create();
        if($this->Account->save($saccout_info))
        {
            $sacct_id=$this->Account->getLastInsertID();
            $saving_info=array();
            $temp_arr = array('Daily'=>1,'Weekly'=>7,'Monthly'=>30,'Fixed'=>0,'MIS'=>0);
            $saving_info['Saving']['created_on'] = date("Y-m-d H:i:s");
            $saving_info['Saving']['modified_on'] = date("Y-m-d H:i:s");
            $saving_info['Saving']['customer_id']=$customer_id;
            $saving_info['Saving']['account_id']=$sacct_id;
            $saving_info['Saving']['currency_id'] = 1;
            $saving_info['Saving']['interest_rate']=$this->request->data['Saving']['intRate']; 
          
            $saving_info['Saving']['maturity_date']=date('Y-m-d',strtotime($this->request->data['maturityDate'])); 
            $saving_info['Saving']['interest_type']=$this->request->data['Saving']['interest_type']; 
            $saving_info['Saving']['organization_id']=$this->Auth->user('organization_id');
            $saving_info['Saving']['region_id']=  $region_id['Branch']['region_id'];
            $saving_info['Saving']['branch_id']=$this->request->data['User']['branch_id'];
            $saving_info['Saving']['market_id']=   $this->request->data['Market']['market_id']; 
            $saving_info['Saving']['user_id']=$user_id['User']['id'];
            $saving_info['Saving']['current_balance'] =$principal;
            $saving_info['Saving']['deposit_interval'] = $temp_arr[$this->request->data['Saving']['Savings_Type']];
            $saving_info['Saving']['min_deposit_amount'] = $this->request->data['Saving']['minDepostitAnmt'];
            $saving_info['Saving']['savings_amount'] =$principal;
            $inerest_data = $this->calculate_maturity_amount($this->request->data['Saving']['minDepostitAnmt'],$this->request->data['Saving']['term'],$this->request->data['Saving']['intRate'],$this->request->data['Saving']['Savings_Type']);
            $saving_info['Saving']['maturity_amount'] = round($inerest_data['total_amount']);
            $saving_info['Saving']['savings_term'] = $this->request->data['Saving']['term'];
            $term = $this->request->data['User']['term'];
            $saving_info['Saving']['maturity_date'] =date('Y-m-d', strtotime( $this->request->data['maturityDate']));
            $saving_info['Saving']['savings_date'] = date('Y-m-d', strtotime("-$term weeks", strtotime($this->request->data['maturityDate'])));
              if($this->Saving->Save($saving_info)){
                        $saving_id=$this->Saving->getLastInsertId();
                        
                        // Saving Saving Transaction Data
            		   $strans['SavingsTransaction']['account_id']=$sacct_id;
            		   $strans['SavingsTransaction']['saving_id']=$saving_id;
            		   $strans['SavingsTransaction']['transaction_on']=$this->request->data['saving_lastInstDate'];
            		   $strans['SavingsTransaction']['amount']=$principal;
            		   $strans['SavingsTransaction']['transaction_type']='CREDIT';
            		   $strans['SavingsTransaction']['balance']=$principal;
            		   $strans['SavingsTransaction']['customer_id']=$customer_id;
            		   $strans['SavingsTransaction']['organization_id']=$this->Auth->user('organization_id');
            		   $strans['SavingsTransaction']['region_id']=$region_id['Branch']['region_id'];
                       $strans['SavingsTransaction']['branch_id']=$this->request->data['User']['branch_id'];
            		   $strans['SavingsTransaction']['market_id']=$this->request->data['Market']['market_id']; 
            		   $strans['SavingsTransaction']['created_on']=date("Y-m-d H:i:s");
            		   $strans['SavingsTransaction']['user_id']=$this->Auth->user('id');
                      
            		   $this->SavingsTransaction->save($strans);
            		   //Saving Income Expenditure Data 
            		   $expn['IncomeExpenditure']['account_id']=$sacct_id;
                       $expn['IncomeExpenditure']['account_ledger_id']=1;
            		   $expn['IncomeExpenditure']['credit_amount']=$principal;
            		   $expn['IncomeExpenditure']['transaction_date']=$this->request->data['saving_lastInstDate'];
            		   $expn['IncomeExpenditure']['balance']=$principal;
            		   $expn['IncomeExpenditure']['organization_id']=$this->Auth->user('organization_id');
            		   $expn['IncomeExpenditure']['region_id']=$region_id['Branch']['region_id'];
            		   $expn['IncomeExpenditure']['branch_id']=$this->request->data['User']['branch_id'];
            		   $expn['IncomeExpenditure']['market_id']=$this->request->data['Market']['market_id']; 
            		   $expn['IncomeExpenditure']['created_on']=date("Y-m-d H:i:s");
            		   $expn['IncomeExpenditure']['user_id']=$user_id['User']['id'];
                      
            		   $this->IncomeExpenditure->save($expn);
                        
                         $this->Session->setFlash('New Saving Account Has Been Saved Successfully', 'default', array(), 'form1');
                    }
                    
                    
       
        }
      
      
      
        
    } // Savings End
    
    if($customer_id) // Loan Start
    {
      
      
                    $loantype = $this->request->data['User']['inerest_type']; 
                    $amount = $this->request->data['User']['amount']; 
                    $interest = $this->request->data['User']['rateInt']; 
                    $instal = $this->request->data['User']['term']; 
                    $intervaltype = $this->request->data['repay_interval_days'];
                    if($loantype=='Fixed'){
                        $repaytotal = round(($amount+($amount*$interest/100)));
        				$rate = $repaytotal / $instal;
                    }else{
                        
                        if($intervaltype=='WEEK'){
                            $interval = 52;
                        }else{
                            $interval = 12;
                        }
                        $r = $interest/$interval/100;
                        
                        $rate = round($amount*$r*(pow((1+$r),$instal))/(pow((1+$r),$instal)-1));
                        $repaytotal = round($rate*$instal);
        				
                    }
                
      
      $accout_info=array();
      $accout_info['Account']['customer_id'] =$customer_id;
      $accout_info['Account']['organization_id']=$this->Auth->user('organization_id');
      $accout_info['Account']['region_id']=  $region_id['Branch']['region_id'];
      $accout_info['Account']['branch_id']=$this->request->data['User']['branch_id'];
      $accout_info['Account']['market_id']=   $this->request->data['Market']['market_id']; 
      $accout_info['Account']['user_id']=$user_id['User']['id'];
      $accout_info['Account']['account_type']='LOAN';
      $accout_info['Account']['account_number']=$this->request->data['User']['account_no'];
      $accout_info['Account']['interest_rate']=$this->request->data['User']['rateInt'];
      $accout_info['Account']['opening_overdraft_balance']=$amount ;
      $accout_info['Account']['created_on']=date("Y-m-d H:i:s");
      $accout_info['Account']['modified_on']=date("Y-m-d H:i:s");
      $accout_info['Account']['plan_amount']=$rate;
      $accout_info['Account']['interest_rate']=$interest;
      $accout_info['Account']['exces_interest']=0;
      $accout_info['Account']['upload_by']=$this->Auth->user('id');
      $accout_info['Account']['upload_type']='DATAENTRY';
      $this->Account->create();
      if($this->Account->save($accout_info))
      {
        $account_id=$this->Account->getLastInsertID();
        $loan_info=array();
        $loan_info['Loan']['created_on'] = date("Y-m-d H:i:s");
        $loan_info['Loan']['customer_id']=$customer_id;
        $loan_info['Loan']['account_id']=$account_id;
        $loan_info['Loan']['loan_number']="";
        $loan_info['Loan']['loan_principal']=$this->request->data['User']['amount']; 
        $loan_info['Loan']['loan_interest']=$this->request->data['User']['rateInt']; 
        $loan_info['Loan']['loan_rate']=$rate;
        $loan_info['Loan']['loan_status_id']=3;
        $loan_info['Loan']['loan_issued']=1;
        $loan_info['Loan']['loan_period']=$instal;
        $loan_info['Loan']['loan_period_unit']=$intervaltype;
        $loan_info['Loan']['currency']='INR';
        $loan_info['Loan']['loan_repay_total']=$repaytotal;
        $loan_info['Loan']['loan_type']=$this->request->data['User']['loan_type']; 
        $loan_info['Loan']['interest_type']=$this->request->data['User']['inerest_type']; 
        $loan_info['Loan']['organization_id']=$this->Auth->user('organization_id');
        $loan_info['Loan']['region_id']=  $region_id['Branch']['region_id'];
        $loan_info['Loan']['branch_id']=$this->request->data['User']['branch_id'];
        $loan_info['Loan']['market_id']=   $this->request->data['Market']['market_id']; 
        $loan_info['Loan']['user_id']=$user_id['User']['id'];
        
        
         // Back Calculation for date start
         $day_no = ($intervaltype=='WEEK')?7:30;
                $paid_amount = $repaytotal-$this->request->data['User']['currentloan_balance'];
                $installment_paid_no = intval($paid_amount/$rate);
                $installment_partial_paid_no = 0;
                if(($paid_amount%$rate)==0){
                    $back_days = $installment_paid_no*$day_no;
                }else{
                    $back_days = ($installment_paid_no+1)*$day_no;
                    $installment_partial_paid_no = $installment_paid_no+1;
                }
            
                $loan_start_date = $this->request->data['linstdate'];
                $loan_maturity_date = date("Y-m-d",strtotime($loan_start_date."+".($instal*$day_no)." days"));
                $insta_start = date("Y-m-d",strtotime($loan_start_date."+$day_no days"));
                // Back Calculation for date end
                
                $loan_info['Loan']['loan_date']=$loan_start_date;
                $loan_info['Loan']['loan_dateout']=$loan_start_date;
                $loan_info['Loan']['maturity_date']=$loan_maturity_date;
                $loan_info['Loan']['loan_repay_start']=$insta_start;
                  if($this->Loan->save($loan_info)){
                    $loan_id=$this->Loan->getLastInsertId();
                    
                    $this->create_loan_transaction($loan_id,$insta_start);
                    $transaction_date = $loan_start_date;
                    $tmp_no = $installment_paid_no;
                    while($tmp_no>0){
                        $transaction_date = date("Y-m-d",strtotime($transaction_date."+$day_no days"));
                        $this->loan_installment_collection($account_id,$rate,$transaction_date);
                        $tmp_no--;
                    }
                    if($installment_partial_paid_no>0){
                        $amt = $paid_amount-($installment_paid_no*$rate);
                        $transaction_date = date("Y-m-d",strtotime($transaction_date."+$day_no days"));
                        $this->loan_installment_collection($account_id,$amt,$transaction_date);
                    }
                    
                }
        
        $this->Session->setFlash('New Customer Has Been Saved Successfully', 'default', array(), 'form1');
    }
      
      
      
        
    } // If Customer Id end
        
    } // Is Post End
  
  
}

public function saving_payment()
{
  
  $this->layout = 'panel_layout';
  
  $bm_list = $this->Branch->find('list', array('fields' => array('id','branch_name'), 'conditions' => array('Branch.organization_id' => $this->Auth->user('organization_id'))));
  $this->set('bm_list', $bm_list);
  $this->set('creditOfficers',$this->User->find('list',array('fields'=>array('id','fullname'),'conditions'=>array('User.user_type_id'=>5,'AND'=>array('User.organization_id'=>$this->Auth->user('organization_id'))))));
  if($this->request->is('post'))
  {
    //pr($this->request->data);die;
    $region_id=$this->Branch->findById($this->request->data['branch_id'],array('Branch.region_id'));
    $user_id=$this->User->findById($this->request->data['credit_officer_id'],array('User.id'));
    
    if(!empty($this->request->data['group_id']))
    {
        $customer_info['Customer']['kendra_id'] = $this->request->data['group_id'];
         $accout_info['Account']['kendra_id'] = $this->request->data['group_id'];
         $saving_info['Saving']['account_id']=$this->request->data['group_id'];
    }
    else
    {
      $customer_info['Customer']['kendra_id'] = "0";  
       $accout_info['Account']['kendra_id'] ="0";
       $saving_info['Saving']['kendra_id']="0";
    }
    
    if($this->request->data['cust_type']==0){
        $customer_info=array();
        $customer_info['Customer']['branch_id'] = $this->request->data['branch_id'];
        $customer_info['Customer']['region_id']=$region_id['Branch']['region_id'];
        $customer_info['Customer']['user_id']=$user_id['User']['id'];
        $customer_info['Customer']['market_id'] = $this->request->data['Market']['market_id'];
        $customer_info['Customer']['created_on'] = date("Y-m-d H:i:s");
        $customer_info['Customer']['modified_on'] = date("Y-m-d H:i:s");
    
        $customer_info['Customer']['organization_id']=$this->Auth->user('organization_id');
        $customer_info['Customer']['cust_fname'] = $this->request->data['cust_fname'];
        $customer_info['Customer']['guardian_name'] = $this->request->data['guardian_name'];
        $customer_info['Customer']['guardian_reletion_type'] =   $this->request->data['guardian_reletion_type'];
        $this->Customer->create();
        $this->Customer->save($customer_info);
        $customer_id= $this->Customer->getLastInsertID();
    }else{
        $customer_id= $this->request->data['customer_id'];
    }
    if($customer_id)
    {
        $principal=$this->request->data['currentBalance'];
      
      $accout_info=array();
      $accout_info['Account']['customer_id'] =$customer_id;
      $accout_info['Account']['organization_id']=$this->Auth->user('organization_id');
      $accout_info['Account']['region_id']=  $region_id['Branch']['region_id'];
      $accout_info['Account']['branch_id']=$this->request->data['branch_id'];
      $accout_info['Account']['market_id']=   $this->request->data['Market']['market_id']; 
      $accout_info['Account']['user_id']=$user_id['User']['id'];
      $accout_info['Account']['account_type']='SAVING_'.$this->request->data['Savings_Type'];
      $accout_info['Account']['account_number']=$this->request->data['account_no'];
      $accout_info['Account']['interest_rate']=$this->request->data['intRate'];
      $accout_info['Account']['opening_balance']=$principal;
      $accout_info['Account']['created_on']=date("Y-m-d H:i:s");
      $accout_info['Account']['modified_on']=date("Y-m-d H:i:s");
      $accout_info['Account']['plan_amount']=$this->request->data['minDepostitAnmt'];
      $accout_info['Account']['exces_interest']=$this->request->data['intRate']-3;
      $accout_info['Account']['upload_by']=$this->Auth->user('id');
      $accout_info['Account']['upload_type']='DATAENTRY';
      $this->Account->create();
            if($this->Account->save($accout_info))
            {
                $acct_id=$this->Account->getLastInsertID();
                $saving_info=array();
                $temp_arr = array('Daily'=>1,'Weekly'=>7,'Monthly'=>30,'Fixed'=>0,'MIS'=>0);
                $saving_info['Saving']['created_on'] = date("Y-m-d H:i:s");
                $saving_info['Saving']['modified_on'] = date("Y-m-d H:i:s");
                $saving_info['Saving']['customer_id']=$customer_id;
                $saving_info['Saving']['account_id']=$acct_id;
                $saving_info['Saving']['currency_id'] = 1;
                $saving_info['Saving']['interest_rate']=$this->request->data['intRate']; 
              
                $saving_info['Saving']['maturity_date']=date('Y-m-d',strtotime($this->request->data['maturityDate'])); 
                $saving_info['Saving']['interest_type']=$this->request->data['interest_type']; 
                $saving_info['Saving']['organization_id']=$this->Auth->user('organization_id');
                $saving_info['Saving']['region_id']=  $region_id['Branch']['region_id'];
                $saving_info['Saving']['branch_id']=$this->request->data['branch_id'];
                $saving_info['Saving']['market_id']=   $this->request->data['Market']['market_id']; 
                $saving_info['Saving']['user_id']=$user_id['User']['id'];
                $saving_info['Saving']['current_balance'] =$principal;
                $saving_info['Saving']['deposit_interval'] = $temp_arr[$this->request->data['Savings_Type']];
                $saving_info['Saving']['min_deposit_amount'] = $this->request->data['minDepostitAnmt'];
                $saving_info['Saving']['savings_amount'] =$principal;
                $inerest_data = $this->calculate_maturity_amount($this->request->data['minDepostitAnmt'],$this->request->data['term'],$this->request->data['intRate'],$this->request->data['Savings_Type']);
                $saving_info['Saving']['maturity_amount'] = round($inerest_data['total_amount']);
                $saving_info['Saving']['savings_term'] = $this->request->data['term'];
                $term = $this->request->data['term'];
                $saving_info['Saving']['maturity_date'] =date('Y-m-d', strtotime( $this->request->data['maturityDate']));
                $saving_info['Saving']['savings_date'] = date('Y-m-d', strtotime("-$term weeks", strtotime($this->request->data['maturityDate'])));
                  if($this->Saving->Save($saving_info)){
                            $saving_id=$this->Saving->getLastInsertId();
                            
                            // Saving Saving Transaction Data
                		   $strans['SavingsTransaction']['account_id']=$acct_id;
                		   $strans['SavingsTransaction']['saving_id']=$saving_id;
                		   $strans['SavingsTransaction']['transaction_on']=$this->request->data['lastInstDate'];
                		   $strans['SavingsTransaction']['amount']=$principal;
                		   $strans['SavingsTransaction']['transaction_type']='CREDIT';
                		   $strans['SavingsTransaction']['balance']=$principal;
                		   $strans['SavingsTransaction']['customer_id']=$customer_id;
                		   $strans['SavingsTransaction']['organization_id']=$this->Auth->user('organization_id');
                		   $strans['SavingsTransaction']['region_id']=$region_id['Branch']['region_id'];
                           $strans['SavingsTransaction']['branch_id']=$this->request->data['branch_id'];
                		   $strans['SavingsTransaction']['market_id']=$this->request->data['Market']['market_id']; 
                		   $strans['SavingsTransaction']['created_on']=date("Y-m-d H:i:s");
                		   $strans['SavingsTransaction']['user_id']=$this->Auth->user('id');
                          
                		   $this->SavingsTransaction->save($strans);
                		   //Saving Income Expenditure Data 
                		   $expn['IncomeExpenditure']['account_id']=$acct_id;
                           $expn['IncomeExpenditure']['account_ledger_id']=1;
                		   $expn['IncomeExpenditure']['credit_amount']=$principal;
                		   $expn['IncomeExpenditure']['transaction_date']=$this->request->data['lastInstDate'];
                		   $expn['IncomeExpenditure']['balance']=$principal;
                		   $expn['IncomeExpenditure']['organization_id']=$this->Auth->user('organization_id');
                		   $expn['IncomeExpenditure']['region_id']=$region_id['Branch']['region_id'];
                		   $expn['IncomeExpenditure']['branch_id']=$this->request->data['branch_id'];
                		   $expn['IncomeExpenditure']['market_id']=$this->request->data['Market']['market_id']; 
                		   $expn['IncomeExpenditure']['created_on']=date("Y-m-d H:i:s");
                		   $expn['IncomeExpenditure']['user_id']=$user_id['User']['id'];
                          
                		   $this->IncomeExpenditure->save($expn);
                            
                             $this->Session->setFlash('New Saving Account Has Been Saved Successfully', 'default', array(), 'form1');
                        }
                        
                        
           
            }
      
      
      
        
    }
        
    }
  
  
}



public function ajax_market_list($branch_id=null){
  $this->layout = 'ajax';
     $market_list= $this->Market->find("list",array('fields'=>array('id','market_name'),'conditions'=>array('Market.branch_id'=>$branch_id)));
     $this->set('market_list',$market_list);
  
}
public function ajax_group_list($market_id=null){
    
        $this->layout = 'ajax';
     $GroupOrKendra= $this->Kendra->find("list",array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.market_id'=>$market_id)));
     $this->set('GroupOrKendra',$GroupOrKendra);
 
}

    public function bulk_entry()
    {
        $this->layout = 'panel_layout';
        $this->set('title', 'Bulk Entry');
        $branch_list= $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.organization_id'=>$this->Auth->user('organization_id'))));
        $this->set('branch_list', $branch_list);
        $user_list = $this->User->find('list', array('fields' => array('id', 'fullname'),'conditions'=> array('User.organization_id'=>$this->Auth->user('organization_id'),'User.user_type_id'=>5),'order'=>array('User.fullname asc')));
        $this->set('user_list', $user_list);
        
        if ($this->request->is(array('post', 'put'))) {
            //pr($this->request->data);die;
            $cust_arr = array();
            $mkt_data = $this->Market->findById($this->request->data['market_id']);
            $princ_arr = $this->request->data['principal'];
            
            
            foreach($princ_arr as $k=>$principal){
                $cust_arr['Customer']['user_id'] = $this->request->data['user_id'];
    			$cust_arr['Customer']['created_on'] = date("Y-m-d H:i:s");
    			$cust_arr['Customer']['modified_on'] = date("Y-m-d H:i:s");
    			$cust_arr['Customer']['branch_id'] = $this->request->data['branch_id'];
                $cust_arr['Customer']['market_id'] = $this->request->data['market_id'];
    			$cust_arr['Customer']['region_id'] = $mkt_data['Market']['region_id'];
    			$cust_arr['Customer']['organization_id'] = $this->Auth->user('organization_id');
                $cust_arr['Customer']['kendra_id'] = ($this->request->data['kendra_id']>0)?$this->request->data['kendra_id']:0;
    			$cust_arr['Customer']['cust_fname'] = $this->request->data['first_name'][$k];
                $cust_arr['Customer']['cust_lname'] = $this->request->data['last_name'][$k];
                $cust_arr['Customer']['cust_sex'] = $this->request->data['sex'][$k];
                $this->Customer->clear();
    			$this->Customer->create();
    			if ($this->Customer->save($cust_arr)) {
                    $last_insert_Customer=$this->Customer->getLastInsertId();
                    
                    // Loan Calculation Start
                    $loantype = $this->request->data['interest_type'][$k];
                    $amount = $principal;
                    $interest = $this->request->data['interest_rate'][$k];
                    $instal = $this->request->data['term'][$k];
                    $intervaltype = $this->request->data['repay_interval_days'][$k];
                    if($loantype=='Fixed'){
                        $repaytotal = round(($amount+($amount*$interest/100)));
        				$rate = $repaytotal / $instal;
                    }else{
                        
                        if($intervaltype=='WEEK'){
                            $interval = 52;
                        }else{
                            $interval = 12;
                        }
                        $r = $interest/$interval/100;
                        
                        $rate = round($amount*$r*(pow((1+$r),$instal))/(pow((1+$r),$instal)-1));
                        $repaytotal = round($rate*$instal);
        				
                    }
                    // Loan Calculation End
                    $acct_arr['Account']['customer_id']=$last_insert_Customer;
                    $acct_arr['Account']['organization_id']=$this->Auth->user('organization_id');
                    $acct_arr['Account']['region_id']=$mkt_data['Market']['region_id'];
                    $acct_arr['Account']['branch_id']=$this->request->data['branch_id'];
                    $acct_arr['Account']['market_id']=$this->request->data['market_id'];
                    $acct_arr['Account']['kendra_id']=$cust_arr['Customer']['kendra_id'];
                    $acct_arr['Account']['user_id'] = $cust_arr['Customer']['user_id'];
                    $acct_arr['Account']['account_type']='LOAN';
                    $acct_arr['Account']['account_number']=$this->request->data['account_no'][$k];
                    $acct_arr['Account']['opening_overdraft_balance']=$principal;
                    $acct_arr['Account']['created_on']=date("Y-m-d H:i:s");
                    $acct_arr['Account']['modified_on']=date("Y-m-d H:i:s");
                    $acct_arr['Account']['plan_amount']=$rate;
                    $acct_arr['Account']['interest_rate']=$this->request->data['interest_rate'][$k];
                    $acct_arr['Account']['exces_interest']=0;
                    $acct_arr['Account']['upload_by']=$this->Auth->user('id');
                    $acct_arr['Account']['upload_type']='DATAENTRY';
                    $this->Account->clear();
                    if($this->Account->save($acct_arr)){
                        $acct_id=$this->Account->getLastInsertId();
                        
                        $loan_arr['Loan']['created_on'] = date("Y-m-d H:i:s");
                        $loan_arr['Loan']['user_id'] = $cust_arr['Customer']['user_id'];
                        $loan_arr['Loan']['account_id'] = $acct_id;
                        $loan_arr['Loan']['customer_id']=$last_insert_Customer;
                        $loan_arr['Loan']['organization_id']=$this->Auth->user('organization_id');
                        $loan_arr['Loan']['region_id']=$mkt_data['Market']['region_id'];
                        $loan_arr['Loan']['branch_id']=$this->request->data['branch_id'];
                        $loan_arr['Loan']['market_id']=$this->request->data['market_id'];
                        $loan_arr['Loan']['kendra_id']=$cust_arr['Customer']['kendra_id'];
                        $loan_arr['Loan']['loan_principal']=$principal;
                        $loan_arr['Loan']['loan_interest']=$this->request->data['interest_rate'][$k];
                        $loan_arr['Loan']['loan_rate']=$rate;
                        $loan_arr['Loan']['loan_period']=$this->request->data['term'][$k];
                        $loan_arr['Loan']['loan_period_unit']=$intervaltype;
                        $loan_arr['Loan']['currency']='INR';
                        $loan_arr['Loan']['loan_repay_total']=$repaytotal;
                        $loan_arr['Loan']['interest_type']=$this->request->data['interest_type'][$k];
                        $loan_arr['Loan']['loan_status_id']=3;
                        $loan_arr['Loan']['loan_issued']=1;
                        
                        // Back Calculation for date start
                        $day_no = ($intervaltype=='WEEK')?7:30;
                        $paid_amount = $repaytotal-$this->request->data['currentloan_balance'][$k];
                        $installment_paid_no = intval($paid_amount/$rate);
                        $installment_partial_paid_no = 0;
                        if(($paid_amount%$rate)==0){
                            $back_days = $installment_paid_no*$day_no;
                        }else{
                            $back_days = ($installment_paid_no+1)*$day_no;
                            $installment_partial_paid_no = $installment_paid_no+1;
                        }
                    
                        //$loan_start_date = date("Y-m-d",strtotime($this->request->data['last_installment_date'][$k]."-".$back_days." days"));
                        $loan_start_date = $this->request->data['last_installment_date'][$k];
                        $loan_maturity_date = date("Y-m-d",strtotime($loan_start_date."+".($instal*$day_no)." days"));
                        $insta_start = date("Y-m-d",strtotime($loan_start_date."+$day_no days"));
                        // Back Calculation for date end
                        
                        $loan_arr['Loan']['loan_date']=$loan_start_date;
                        $loan_arr['Loan']['loan_dateout']=$loan_start_date;
                        $loan_arr['Loan']['maturity_date']=$loan_maturity_date;
                        $loan_arr['Loan']['loan_repay_start']=$insta_start;
                        
                        $this->Loan->clear();
                        if($this->Loan->save($loan_arr)){
                            $loan_id=$this->Loan->getLastInsertId();
                            
                            $this->create_loan_transaction($loan_id,$insta_start);
                            $transaction_date = $loan_start_date;
                            $tmp_no = $installment_paid_no;
                            while($tmp_no>0){
                                $transaction_date = date("Y-m-d",strtotime($transaction_date."+$day_no days"));
                                if(strtotime($transaction_date)>strtotime(date("Y-m-d"))){
                                    $transaction_date = date("Y-m-d");
                                }
                                $this->loan_installment_collection($acct_id,$rate,$transaction_date);
                                $tmp_no--;
                            }
                            if($installment_partial_paid_no>0){
                                $amt = $paid_amount-($installment_paid_no*$rate);
                                $transaction_date = date("Y-m-d",strtotime($transaction_date."+$day_no days"));
                                if(strtotime($transaction_date)>strtotime(date("Y-m-d"))){
                                    $transaction_date = date("Y-m-d");
                                }
                                $this->loan_installment_collection($acct_id,$amt,$transaction_date);
                            }
                            
                        } // Loan Save End
                        
                    } // Account Save End
        			
                } // Customer Save End
            
                
           } // Foreach End
            
        } // ispost end
        
    }
    
    
    public function ajax_market_list_of_branch($branch_id=''){
        $this->layout = 'ajax';
        if($branch_id==''){
            echo '0';
        }else{
            $market_list= $this->Market->find('list',array('fields'=>array('id','market_name'),'conditions'=>array('Market.branch_id'=>$branch_id),'order'=>array('Market.market_name asc')));
            $this->set('market_list', $market_list);
        }
    }
    public function ajax_group_list_of_market($market_id=''){
        $this->layout = 'ajax';
        if($market_id==''){
            echo '0';
        }else{
            $kendra_list= $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.market_id'=>$market_id),'order'=>array('Kendra.kendra_name asc')));
            $this->set('kendra_list', $kendra_list);
        }
    }
    
    public function ajax_get_customer_list_by_market($flag=0,$mkt_id){
        $this->layout = 'ajax';
        $this->set('flag',$flag);
        if($flag==0){
            $this->set('cust_list', array());
        }else{
            $cust_list = $this->Customer->find('list', array('fields' => array('id', 'fullname'),'conditions'=> array('Customer.organization_id'=>$this->Auth->user('organization_id'),'Customer.market_id'=>$mkt_id),'order'=>array('Customer.fullname asc')));
            $this->set('cust_list', $cust_list);
        }
        //pr($cust_list);echo 'mkt'; die;
    }
    public function ajax_get_customer_list_by_kendra($flag=0,$k_id){
        $this->layout = 'ajax';
        $this->set('flag',$flag);
        if($flag==0){
            $this->set('cust_list', array());
        }else{
            $cust_list = $this->Customer->find('list', array('fields' => array('id', 'fullname'),'conditions'=> array('Customer.organization_id'=>$this->Auth->user('organization_id'),'Customer.kendra_id'=>$k_id),'order'=>array('Customer.fullname asc')));
            $this->set('cust_list', $cust_list);
        }
        //pr($cust_list);echo 'kendra'; die;
    }
    
    
    public function loan_upload_from_file(){
        $this->layout = 'panel_layout';
        $this->set('title', 'File Upload');
        
        $branch_list= $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.organization_id'=>$this->Auth->user('organization_id'))));
        $this->set('branch_list', $branch_list);
        $user_list = $this->User->find('list', array('fields' => array('id', 'fullname'),'conditions'=> array('User.organization_id'=>$this->Auth->user('organization_id'),'User.user_type_id'=>5),'order'=>array('User.fullname asc')));
        $this->set('user_list', $user_list);
        
        
        if ($this->request->is(array('post', 'put'))) {
            //pr($this->request->data);die;
            $cust_arr = array();
            $mkt_data = $this->Market->findById($this->request->data['market_id']);
            
            
            $file_name = time().'_'.$_FILES['filename']['name'];
            $filename = WWW_ROOT."uploadRawReport/".$file_name;
            move_uploaded_file($_FILES['filename']['tmp_name'],$filename );
            $myfile = fopen($filename, "r") or die("Unable to open file!");
            $json_str = fread($myfile,filesize($filename));
            fclose($myfile);
            
            $data_arr = json_decode($json_str,true);
            //pr($data_arr);
            foreach($data_arr as $k=>$row){
                
                
                //pr($row);die;
                $cust_arr['Customer']['user_id'] = $this->request->data['user_id'];
    			$cust_arr['Customer']['created_on'] = date("Y-m-d H:i:s");
    			$cust_arr['Customer']['modified_on'] = date("Y-m-d H:i:s");
    			$cust_arr['Customer']['branch_id'] = $this->request->data['branch_id'];
                $cust_arr['Customer']['market_id'] = $this->request->data['market_id'];
    			$cust_arr['Customer']['region_id'] = $mkt_data['Market']['region_id'];
    			$cust_arr['Customer']['organization_id'] = $mkt_data['Market']['organization_id'];
                $cust_arr['Customer']['kendra_id'] = ($this->request->data['kendra_id']>0)?$this->request->data['kendra_id']:0;
    			$cust_arr['Customer']['cust_fname'] = $row['cust_fname'];
                $cust_arr['Customer']['cust_lname'] = $row['cust_lname'];
                $cust_arr['Customer']['cust_sex'] = $row['cust_sex'];
                $this->Customer->clear();
    			$this->Customer->create();
    			if ($this->Customer->save($cust_arr)) {
                    $last_insert_Customer=$this->Customer->getLastInsertId();
                    $principal = $row['loan_principal'];
                    // Loan Calculation Start
                    $loantype = $row['interest_type'];
                    $amount = $principal;
                    $interest = $row['loan_interest'];
                    $instal = $row['loan_period'];
                    $intervaltype = $row['loan_period_unit'];
                    if($loantype=='Fixed' || $loantype=='FIXED'){
                        $repaytotal = round(($amount+($amount*$interest/100)));
        				$rate = $repaytotal / $instal;
                    }else{
                        
                        if($intervaltype=='WEEK'){
                            $interval = 52;
                        }else{
                            $interval = 12;
                        }
                        $r = $interest/$interval/100;
                        
                        $rate = round($amount*$r*(pow((1+$r),$instal))/(pow((1+$r),$instal)-1));
                        $repaytotal = round($rate*$instal);
        				
                    }
                    // Loan Calculation End
                    $acct_arr['Account']['customer_id']=$last_insert_Customer;
                    $acct_arr['Account']['organization_id']=$mkt_data['Market']['organization_id'];
                    $acct_arr['Account']['region_id']=$mkt_data['Market']['region_id'];
                    $acct_arr['Account']['branch_id']=$this->request->data['branch_id'];
                    $acct_arr['Account']['market_id']=$this->request->data['market_id'];
                    $acct_arr['Account']['kendra_id']=$cust_arr['Customer']['kendra_id'];
                    $acct_arr['Account']['user_id'] = $cust_arr['Customer']['user_id'];
                    $acct_arr['Account']['account_type']='LOAN';
                    $acct_arr['Account']['account_number']=$row['account_number'];
                    $acct_arr['Account']['opening_overdraft_balance']=$principal;
                    $acct_arr['Account']['created_on']=date("Y-m-d H:i:s");
                    $acct_arr['Account']['modified_on']=date("Y-m-d H:i:s");
                    $acct_arr['Account']['plan_amount']=$rate;
                    $acct_arr['Account']['interest_rate']=$row['loan_interest'];
                    $acct_arr['Account']['exces_interest']=0;
                    $acct_arr['Account']['upload_by']=$this->Auth->user('id');
                    $acct_arr['Account']['upload_type']='DATAENTRY_FILE';
                    $this->Account->clear();
                    if($this->Account->save($acct_arr)){
                        $acct_id=$this->Account->getLastInsertId();
                        
                        $loan_arr['Loan']['created_on'] = date("Y-m-d H:i:s");
                        $loan_arr['Loan']['user_id'] = $cust_arr['Customer']['user_id'];
                        $loan_arr['Loan']['account_id'] = $acct_id;
                        $loan_arr['Loan']['customer_id']=$last_insert_Customer;
                        $loan_arr['Loan']['organization_id']=$mkt_data['Market']['organization_id'];
                        $loan_arr['Loan']['region_id']=$mkt_data['Market']['region_id'];
                        $loan_arr['Loan']['branch_id']=$this->request->data['branch_id'];
                        $loan_arr['Loan']['market_id']=$this->request->data['market_id'];
                        $loan_arr['Loan']['kendra_id']=$cust_arr['Customer']['kendra_id'];
                        $loan_arr['Loan']['loan_principal']=$principal;
                        $loan_arr['Loan']['loan_interest']=$row['loan_interest'];
                        $loan_arr['Loan']['loan_rate']=$rate;
                        $loan_arr['Loan']['loan_period']=$row['loan_period'];
                        $loan_arr['Loan']['loan_period_unit']=$intervaltype;
                        $loan_arr['Loan']['currency']='INR';
                        $loan_arr['Loan']['loan_repay_total']=$repaytotal;
                        $loan_arr['Loan']['interest_type']=$row['interest_type'];
                        $loan_arr['Loan']['loan_status_id']=3;
                        $loan_arr['Loan']['loan_issued']=1;
                        
                        // Back Calculation for date start
                        $day_no = ($intervaltype=='WEEK')?7:30;
                        $paid_amount = $repaytotal-$row['currentloan_balance'];
                        $installment_paid_no = intval($paid_amount/$rate);
                        $installment_partial_paid_no = 0;
                        if(($paid_amount%$rate)==0){
                            $back_days = $installment_paid_no*$day_no;
                        }else{
                            $back_days = ($installment_paid_no+1)*$day_no;
                            $installment_partial_paid_no = $installment_paid_no+1;
                        }
                    
                        //$loan_start_date = date("Y-m-d",strtotime($this->request->data['last_installment_date'][$k]."-".$back_days." days"));
                        $loan_start_date = $row['loan_dateout'];
                        $loan_maturity_date = date("Y-m-d",strtotime($loan_start_date."+".($instal*$day_no)." days"));
                        $insta_start = date("Y-m-d",strtotime($loan_start_date."+$day_no days"));
                        // Back Calculation for date end
                        
                        $loan_arr['Loan']['loan_date']=$loan_start_date;
                        $loan_arr['Loan']['loan_dateout']=$loan_start_date;
                        $loan_arr['Loan']['maturity_date']=$loan_maturity_date;
                        $loan_arr['Loan']['loan_repay_start']=$insta_start;
                        
                        $this->Loan->clear();
                        if($this->Loan->save($loan_arr)){
                            $loan_id=$this->Loan->getLastInsertId();
                            
                            $this->create_loan_transaction($loan_id,$insta_start);
                            $transaction_date = $loan_start_date;
                            $tmp_no = $installment_paid_no;
                            while($tmp_no>0){
                                $transaction_date = date("Y-m-d",strtotime($transaction_date."+$day_no days"));
                                if(strtotime($transaction_date)>strtotime(date("Y-m-d"))){
                                    $transaction_date = date("Y-m-d");
                                }
                                $this->loan_installment_collection($acct_id,$rate,$transaction_date);
                                $tmp_no--;
                            }
                            if($installment_partial_paid_no>0){
                                $amt = $paid_amount-($installment_paid_no*$rate);
                                $transaction_date = date("Y-m-d",strtotime($transaction_date."+$day_no days"));
                                if(strtotime($transaction_date)>strtotime(date("Y-m-d"))){
                                    $transaction_date = date("Y-m-d");
                                }
                                $this->loan_installment_collection($acct_id,$amt,$transaction_date);
                            }
                            
                        } // Loan Save End
                        
                    } // Account Save End
        			
                } // Customer Save End
            
                
           } // Foreach End
            
        } // ispost end
        
        
    }
    
    public function bulk_savings_entry(){
        $this->layout = 'panel_layout';
        $this->set('title', 'Bulk Entry');
        
        $branch_list= $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.organization_id'=>$this->Auth->user('organization_id'))));
        $this->set('branch_list', $branch_list);
        $user_list = $this->User->find('list', array('fields' => array('id', 'fullname'),'conditions'=> array('User.organization_id'=>$this->Auth->user('organization_id'),'User.user_type_id'=>5),'order'=>array('User.fullname asc')));
        $this->set('user_list', $user_list);
        
        
        if ($this->request->is(array('post', 'put'))) {
            //pr($this->request->data);die;
            
            $cust_arr = array();
            $mkt_data = $this->Market->findById($this->request->data['market_id']);
            $princ_arr = $this->request->data['current_balance'];
            
            foreach($princ_arr as $k=>$principal){
                $cust_arr['Customer']['user_id'] = $this->request->data['user_id'];
    			$cust_arr['Customer']['created_on'] = date("Y-m-d H:i:s");
    			$cust_arr['Customer']['modified_on'] = date("Y-m-d H:i:s");
    			$cust_arr['Customer']['branch_id'] = $this->request->data['branch_id'];
                $cust_arr['Customer']['market_id'] = $this->request->data['market_id'];
    			$cust_arr['Customer']['region_id'] = $mkt_data['Market']['region_id'];
    			$cust_arr['Customer']['organization_id'] = $this->Auth->user('organization_id');
                $cust_arr['Customer']['kendra_id'] = ($this->request->data['kendra_id']>0)?$this->request->data['kendra_id']:0;
    			$cust_arr['Customer']['cust_fname'] = $this->request->data['first_name'][$k];
                $cust_arr['Customer']['cust_lname'] = $this->request->data['last_name'][$k];
                $cust_arr['Customer']['cust_sex'] = $this->request->data['sex'][$k];
                $this->Customer->clear();
    			$this->Customer->create();
    			if ($this->Customer->save($cust_arr)) {
                    $last_insert_Customer=$this->Customer->getLastInsertId();
                    
                    $acct_arr['Account']['customer_id']=$last_insert_Customer;
                    $acct_arr['Account']['organization_id']=$this->Auth->user('organization_id');
                    $acct_arr['Account']['region_id']=$mkt_data['Market']['region_id'];
                    $acct_arr['Account']['branch_id']=$this->request->data['branch_id'];
                    $acct_arr['Account']['market_id']=$this->request->data['market_id'];
                    $acct_arr['Account']['kendra_id']=$cust_arr['Customer']['kendra_id'];
                    $acct_arr['Account']['user_id'] = $cust_arr['Customer']['user_id'];
                    $acct_arr['Account']['account_type']='SAVING_'.$this->request->data['saving_type'][$k];
                    $acct_arr['Account']['account_number']=$this->request->data['account_no'][$k];
                    $acct_arr['Account']['opening_balance']=$principal;
                    $acct_arr['Account']['created_on']=date("Y-m-d H:i:s");
                    $acct_arr['Account']['modified_on']=date("Y-m-d H:i:s");
                    $acct_arr['Account']['plan_amount']=$this->request->data['minimum_deposit'][$k];
                    $acct_arr['Account']['interest_rate']=$this->request->data['interest_rate'][$k];
                    $acct_arr['Account']['exces_interest']=$this->request->data['interest_rate'][$k]-3;
                    $acct_arr['Account']['upload_by']=$this->Auth->user('id');
                    $acct_arr['Account']['upload_type']='DATAENTRY';
                    $this->Account->clear();
                    if($this->Account->save($acct_arr)){
                        $acct_id=$this->Account->getLastInsertId();
                        $temp_arr = array('Daily'=>1,'Weekly'=>7,'Monthly'=>30,'Fixed'=>0,'MIS'=>0);
                        $saving_arr['Saving']['account_id']=$acct_id;
                        $saving_arr['Saving']['currency_id'] = 1;
                        $saving_arr['Saving']['created_on'] = date("Y-m-d H:i:s");
                        $saving_arr['Saving']['modified_on'] = date("Y-m-d H:i:s");
                        $saving_arr['Saving']['user_id'] =$cust_arr['Customer']['user_id'];
                        $saving_arr['Saving']['organization_id'] = $this->Auth->user('organization_id');
                        $saving_arr['Saving']['region_id'] = $mkt_data['Market']['region_id'];
                        $saving_arr['Saving']['branch_id'] = $this->request->data['branch_id'];
                        $saving_arr['Saving']['market_id'] = $this->request->data['market_id'];
                        $saving_arr['Saving']['kendra_id'] = $cust_arr['Customer']['kendra_id'];
                        $saving_arr['Saving']['customer_id'] = $last_insert_Customer;
                        $saving_arr['Saving']['interest_rate'] = $this->request->data['interest_rate'][$k];
                        $saving_arr['Saving']['interest_type'] = $this->request->data['interest_type'][$k];
                        $saving_arr['Saving']['current_balance'] = $principal;	
                        $saving_arr['Saving']['deposit_interval'] = $temp_arr[$this->request->data['saving_type'][$k]];
                        $saving_arr['Saving']['min_deposit_amount'] = $this->request->data['minimum_deposit'][$k];
                        $saving_arr['Saving']['savings_amount'] = $principal;
                        $inerest_data = $this->calculate_maturity_amount($this->request->data['minimum_deposit'][$k],$this->request->data['term'][$k],$this->request->data['interest_rate'][$k],$this->request->data['saving_type'][$k]);

            			$saving_arr['Saving']['maturity_amount'] = round($inerest_data['total_amount']);
                        $saving_arr['Saving']['savings_term'] = $this->request->data['term'][$k];
                        $term = $this->request->data['term'][$k];
                        $saving_arr['Saving']['maturity_date'] = $this->request->data['maturity_date'][$k];
                        $saving_arr['Saving']['savings_date'] = date('Y-m-d', strtotime("-$term weeks", strtotime($this->request->data['maturity_date'][$k])));
                        
                        $this->Saving->clear();
                        if($this->Saving->Save($saving_arr)){
                            $saving_id=$this->Saving->getLastInsertId();
                            
                            // Saving Saving Transaction Data
                		   $strans['SavingsTransaction']['account_id']=$acct_id;
                		   $strans['SavingsTransaction']['saving_id']=$saving_id;
                		   $strans['SavingsTransaction']['transaction_on']=$this->request->data['last_installment_date'][$k];
                		   $strans['SavingsTransaction']['amount']=$principal;
                		   $strans['SavingsTransaction']['transaction_type']='CREDIT';
                		   $strans['SavingsTransaction']['balance']=$principal;
                		   $strans['SavingsTransaction']['customer_id']=$last_insert_Customer;
                		   $strans['SavingsTransaction']['organization_id']=$this->Auth->user('organization_id');
                		   $strans['SavingsTransaction']['region_id']=$mkt_data['Market']['region_id'];
             		       $strans['SavingsTransaction']['branch_id']=$this->request->data['branch_id'];
                		   $strans['SavingsTransaction']['market_id']=$this->request->data['market_id'];
                           $strans['SavingsTransaction']['kendra_id']=$cust_arr['Customer']['kendra_id'];
                		   $strans['SavingsTransaction']['created_on']=date("Y-m-d H:i:s");
                		   $strans['SavingsTransaction']['user_id']=$this->Auth->user('id');
                           $this->SavingsTransaction->clear();
                		   $this->SavingsTransaction->save($strans);
                		   //Saving Income Expenditure Data 
                		   $expn['IncomeExpenditure']['account_ledger_id']=1;
                           $expn['IncomeExpenditure']['account_id']=$acct_id;
                		   $expn['IncomeExpenditure']['credit_amount']=$principal;
                		   $expn['IncomeExpenditure']['transaction_date']=$this->request->data['last_installment_date'][$k];
                		   $expn['IncomeExpenditure']['balance']=$principal;
                		   $expn['IncomeExpenditure']['organization_id']=$this->Auth->user('organization_id');
                		   $expn['IncomeExpenditure']['region_id']=$mkt_data['Market']['region_id'];
                		   $expn['IncomeExpenditure']['branch_id']=$this->request->data['branch_id'];
                		   $expn['IncomeExpenditure']['market_id']=$this->request->data['market_id'];
                           
                		   $expn['IncomeExpenditure']['created_on']=date("Y-m-d H:i:s");
                		   $expn['IncomeExpenditure']['user_id']=$cust_arr['Customer']['user_id'];
                           $this->IncomeExpenditure->clear();
                		   $this->IncomeExpenditure->save($expn);
                            
                            
                        } // Loan Save End
                        
                    } // Account Save End
        			
                } // Customer Save End
            
                
           } // Foreach End
            
            
        } // is post end
        
    }
    
    
     function daily_saving_deposit($market_id=''){
      $month=date('m');
      $year=date('Y');
        $this->layout = 'panel_layout';
	$this->set('title', 'Bulk Savings Daily Collection');
        $bm_list = $this->Branch->find('list', array('fields' => array('id','branch_name'), 'conditions' => array('Branch.organization_id' => $this->Auth->user('organization_id'))));
        $this->set('bm_list', $bm_list);
        $market_list=array();
        $data_list = array();
        $trans_list = array();
        $market_id=0;
        $GroupOrKendra=array();
        $group_id=0;
        if ($this->request->is('post')) {
            $date = $this->data['date'];
            $arr = explode('-',$date);
            $month = $arr[0];
            $year = $arr[1];
            $branch_id=$this->data['User']['branch_id'];
            $market_list= $this->Market->find("list",array('fields'=>array('id','market_name'),'conditions'=>array('Market.branch_id'=>$branch_id)));
            $market_id = $this->request->data['Market']['market_id'];
            $GroupOrKendra= $this->Kendra->find("list",array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.market_id'=>$market_id)));
            $group_id=(!empty($this->data['User']['group_id']))? $this->data['User']['group_id']:0;
        }
        $this->set('market_id',$market_id);
        $this->set('group_id',$group_id);
         $this->set('market_list',$market_list);
          $this->set('GroupOrKendra',$GroupOrKendra);
         $this->set('month',$month);
            $this->set('year',$year);
        if($market_id!=''){
            $this->Account->unBindModel(array('belongsTo' => array(
                'User',
                'Organization',
                'Market',
                'Region'),
                'hasOne'=>array('Loan')));
             if(!empty($this->data['group_id']))
            {
                
            $data_list = $this->Account->find('all',array('fields'=>array('Branch.branch_name','Saving.current_balance','Account.account_number','Account.id','Saving.id','Customer.cust_fname','Customer.cust_lname'),
                    'conditions'=>array('Account.status'=>1,'Account.kendra_id'=>$this->data['group_id'],'OR'=>array(array('Account.account_type'=>'SAVING_Daily'),array('Account.account_type'=>'SAVING_Weekly'),array('Account.account_type'=>'SAVING_Monthly'))),
                    ));    
            }
            else
            {
         $data_list = $this->Account->find('all',array('fields'=>array('Branch.branch_name','Saving.current_balance','Account.account_number','Account.id','Saving.id','Customer.cust_fname','Customer.cust_lname'),
                    'conditions'=>array('Account.status'=>1,'Account.market_id'=>$market_id,'OR'=>array(array('Account.account_type'=>'SAVING_Daily'),array('Account.account_type'=>'SAVING_Weekly'),array('Account.account_type'=>'SAVING_Monthly'))),
                    ));       
            }
            $trans_list = $this->SavingsTransaction->find('all',
                    array('fields'=>array('SavingsTransaction.account_id','SavingsTransaction.transaction_on','SavingsTransaction.amount'),
                'conditions'=> array('year(SavingsTransaction.transaction_on)'=>$year,'month(SavingsTransaction.transaction_on)'=>$month)));
        }
        $this->set('trans_list',$trans_list);
        $this->set('data_list',$data_list);
        $this->set('market_id', $market_id);
    }
     
    // Daily Collection Saving in AJAX Function Start
    function ajax_save_savings_transaction(){
       $this->layout = 'ajax'; 
       $this->autoRender = false;
       if ($this->request->is('post')){
		   $account_id=$this->request->data['account_id'];
		   $saving_amount=$this->request->data['value'];
		   $transaction_data=$this->request->data['transaction_on'];
           $this->saving_amount_collection($account_id,$saving_amount,$transaction_data); 
	   } 
    }
    
    function daily_loan_collection($market_id=''){
        $this->layout = 'panel_layout';
		$this->set('title', 'Bulk Loan Collection');
        $market_list = $this->Market->find('list',array('fields'=>array('id','market_name'),'conditions'=>array('Market.status'=>1),'recursive'=>-1));
        $this->set('market_list', $market_list);
        $data_list = array();
        $trans_list = array();
        $kendra_list = array();
        $month = date("m");
        $year = date("Y");
        if ($this->request->is('post')) {
            $market_id = $this->request->data['Loan']['market_id'];
            $kendra_id = (!empty($this->request->data['kendra_id']))?$this->request->data['kendra_id']:0;
            $date = $this->request->data['Loan']['date'];
            $arr = explode('-',$date);
            $month = $arr[0];
            $year = $arr[1];
            $kendra_list = $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.status'=>1,'Kendra.market_id'=>$market_id),'recursive'=>-1));
        }
        if(!empty($kendra_id)){
            $this->Account->unBindModel(array('belongsTo' => array(
                'User',
                'Organization',
                'Market',
                'Region'),
                'hasOne'=>array('Saving')));

            $data_list = $this->Account->find('all',array('fields'=>array('Branch.branch_name','Loan.loan_principal','Loan.loan_repay_total','Account.account_number','Account.id','Loan.id','Customer.cust_fname','Customer.cust_lname'),
                    'conditions'=>array('Account.status'=>1,'Account.kendra_id'=>$kendra_id,'Account.account_type'=>'LOAN','Loan.loan_status_id'=>3),
                    ));
            $trans_list = $this->IncomeExpenditure->find('all',array('conditions'=> array('IncomeExpenditure.account_ledger_id'=>2,'year(IncomeExpenditure.transaction_date)'=>$year,'month(IncomeExpenditure.transaction_date)'=>$month)));
        }
        if($market_id!='' && empty($kendra_id)){
            $this->Account->unBindModel(array('belongsTo' => array(
                'User',
                'Organization',
                'Market',
                'Region'),
                'hasOne'=>array('Saving')));
            $data_list = $this->Account->find('all',array('fields'=>array('Branch.branch_name','Loan.loan_principal','Loan.loan_repay_total','Account.account_number','Account.id','Loan.id','Customer.cust_fname','Customer.cust_lname'),
                    'conditions'=>array('Account.status'=>1,'Account.market_id'=>$market_id,'Account.account_type'=>'LOAN','Loan.loan_status_id'=>3),
                    ));
            $trans_list = $this->IncomeExpenditure->find('all',array('conditions'=> array('IncomeExpenditure.account_ledger_id'=>2,'year(IncomeExpenditure.transaction_date)'=>date("Y"),'month(IncomeExpenditure.transaction_date)'=>date("m"))));
        }
        $this->set('month',$month);
        $this->set('year',$year);
        $this->set('trans_list',$trans_list);
        $this->set('data_list',$data_list);
        $this->set('market_id', $market_id);
        $this->set('kendra_list', $kendra_list);
    }
    // Daily Collection loan in AJAX Function Start
    function ajax_save_loan_transaction(){
       $this->layout = 'ajax'; 
       $this->autoRender = false;
       if ($this->request->is('post')){
		   $account_id=$this->request->data['account_id'];
		   $repay_amount=$this->request->data['value'];
		   $transaction_date=$this->request->data['transaction_on'];
		   echo $this->loan_installment_collection($account_id,$repay_amount,$transaction_date);
	   } 
    }
    // Daily Collection Saving in AJAX Function End 
    
    // Amount Collection view start
    public function single_amount_collection($account_no=''){
        $this->layout = 'panel_layout';
		$this->set('title', 'Collection Page');
        $account_data = array();
        $loan_data[0][0] = array();
        if ($this->request->is('post')) {
			$account_no=$this->request->data['Account']['account_number'];
		} // IF Post End
        if($account_no!=''){
            $this->request->data['Account']['account_number'] = $account_no;
            $account_data = $this->Account->find('first',array(
                                    'conditions'=>array('Account.account_number'=>$account_no),
                                ));
            if(!empty($account_data)){
                if(!empty($account_data['Loan']['id'])){
                    $loan_data = $this->LoanTransaction->find('all',array('fields'=>array('sum(LoanTransaction.insta_principal_paid + LoanTransaction.insta_interest_paid) as amount_paid','sum(LoanTransaction.insta_principal_paid) as principal_paid','sum(LoanTransaction.insta_interest_paid) as interest_paid'),'conditions'=>array('LoanTransaction.loan_id'=>$account_data['Loan']['id'])));
                    $insta_amount = $account_data['Loan']['loan_rate'];
                }
                if(!empty($account_data['Saving']['min_deposit_amount'])){
                    $insta_amount = $account_data['Saving']['min_deposit_amount'];
                }
                
                $this->request->data['Account']['amount'] = $insta_amount;
            }else{
                $this->Session->setFlash(__('Account number not exist. Please check!'));
            }
        }
        $this->set('loan_data',$loan_data[0][0]);
        $this->set('account_data',$account_data);
    }
    // Amount Collection view End
    
    public function ajax_save_collection_amount(){
        $this->layout = 'ajax';
        $this->autoRender = false;
        $balance = 0;
        if($this->request->data['Account']['account_type']=='Loan'){
            $account_id=$this->request->data['Account']['account_id'];
            $repay_amount=$this->request->data['Account']['amount'];
		    $transaction_date=$this->request->data['date'];
            $notes=$this->request->data['note'];
            $fine=$this->request->data['Account']['fine'];
		      //echo $account_id.'-'.$repay_amount.'-'.$transaction_date.'-'.$fine;die;
		    $balance = $this->loan_installment_collection($account_id,$repay_amount,$transaction_date,$notes,$fine);
        }
        if($this->request->data['Account']['account_type']=='Saving'){
            $account_id=$this->request->data['Account']['account_id'];
            $repay_amount=$this->request->data['Account']['amount'];
		    $transaction_date=$this->request->data['date'];
            $notes=$this->request->data['note'];
            $fine=$this->request->data['Account']['fine'];
		   
		    $balance = $this->saving_amount_collection($account_id,$repay_amount,$transaction_date,$notes,$fine);
        }
        echo $balance;
    }
    
    public function collection($cust_id=''){
        $this->layout = 'ajax';
        $this->autoRender = false;
        
    }
    
}
// Customer Controller END here
?>