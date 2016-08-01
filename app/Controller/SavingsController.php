<?php
// This is saving controller. This controller will have all saving related information.
App::uses('CakeEmail', 'Network/Email');
class SavingsController extends AppController {
		// List of models which are used in the saving controller 
		var $uses = array('User','Organization','Region','Branch','Market','Kendra','Customer','Loan','Saving','Idproof','LogRecord','Country', 'LoanStatus','SavingsTransaction', 'LoanTransaction', 'Plan', 'Account','IncomeExpenditure','ExtraAmount');
	
	// This is a blank index function for safe navigation
    public function index() {
	   	
    }
	
    // VSSU add a saving plan function start
	public function add_savings_plan(){
		$this->layout = 'panel_layout';
		$this->set('title', 'Create Savings Plan');
		
		
		if ($this->request->is('post')) {
			
			$saving['saving']['min_amount']=$this->request->data['min_amount'];
			$saving['saving']['saving_type']=$this->request->data['Plan']['saving_type'];
			$saving['saving']['interval_day']=$this->request->data['interval_day'];
			$saving['saving']['interest_rate']=$this->request->data['interest_rate'];
			$saving['saving']['saving_period']=$this->request->data['saving_period'];
            $saving['saving']['interest_type']=$this->request->data['Plan']['interest_type'];
            
			$saving['prematurity']['before12fixed']=$this->request->data['pre_mat_b12_fixed'];
			$saving['prematurity']['before12percentage']=$this->request->data['pre_mat_b12_percentage'];
			$saving['prematurity']['after12percentage']=$this->request->data['pre_mat_a12_percentage'];
			$this->request->data['Plan']['plan_name'] = $this->request->data['plan_name'];
			$this->request->data['Plan']['plan_value'] = json_encode($saving);
			$this->request->data['Plan']['plan_type'] = 1;
			$this->request->data['Plan']['organization_id'] = $this->Auth->user('organization_id');
			$this->request->data['Plan']['modified_on'] = date("Y-m-d H:i:s");
			//pr($this->request->data);die;
			$this->Plan->save($this->request->data);
			$this->Session->setFlash(__('The plan has been created'));
			$this->redirect('/create_saving_plan');
		} 
	}
	// VSSU add a saving plan function end
    
    
    // VSSU add a saving plan function start
	public function create_account($cust_id=''){
		$this->layout = 'panel_layout';
		$this->set('title', 'Create Savings Account');
		$this->set('cust_id',$cust_id);
        $customer_data = $this->Customer->find('first',array('conditions'=>array('Customer.id'=>$cust_id),'recursive'=>-1));
        $this->set('customer_data', $customer_data);
        $plan_data = $this->Plan->find('list',array('fields'=>array('plan_value','plan_name'),'conditions'=>array('Plan.plan_type'=>1,'Plan.status'=>1)));
        $this->set('plan_data', $plan_data);
               
        
		if ($this->request->is('post')) {
			$current_date_time=date("Y-m-d H:i:s");
            
            $plan_array = json_decode($this->request->data['Saving']['plan'],true);
            
			
            $this->request->data['Saving']['currency_id'] = 1;
            $this->request->data['Saving']['created_on'] = $current_date_time;
            $this->request->data['Saving']['modified_on'] = $current_date_time;
            $this->request->data['Saving']['user_id'] = $customer_data['Customer']['user_id'];
            $this->request->data['Saving']['organization_id'] = $customer_data['Customer']['organization_id'];
            $this->request->data['Saving']['region_id'] = $customer_data['Customer']['region_id'];
            $this->request->data['Saving']['branch_id'] = $customer_data['Customer']['branch_id'];
            $this->request->data['Saving']['market_id'] = $customer_data['Customer']['market_id'];
            $this->request->data['Saving']['kendra_id'] = $customer_data['Customer']['kendra_id'];
            $this->request->data['Saving']['customer_id'] = $customer_data['Customer']['id'];
            $this->request->data['Saving']['customer_id'] = $customer_data['Customer']['id'];
            $this->request->data['Saving']['current_balance'] = $this->request->data['Saving']['savings_amount'];	
            $inerest_data = $this->calculate_maturity_amount($this->request->data['Saving']['savings_amount'],$this->request->data['Saving']['savings_term'],$this->request->data['Saving']['interest_rate'],$this->request->data['Saving']['saving_type']);
            //pr($inerest_data); die;
			$this->request->data['Saving']['maturity_amount'] = round($inerest_data['total_amount']);
            $term = $this->request->data['Saving']['savings_term'];
            $this->request->data['Saving']['maturity_date'] = date('Y-m-d', strtotime("+$term months", strtotime($this->request->data['Saving']['savings_date'])));
            
            
            $this->request->data['Account']['customer_id']=$customer_data['Customer']['id'];
            $this->request->data['Account']['organization_id']=$customer_data['Customer']['organization_id'];
            $this->request->data['Account']['region_id']=$customer_data['Customer']['region_id'];
            $this->request->data['Account']['branch_id']=$customer_data['Customer']['branch_id'];
            $this->request->data['Account']['market_id']=$customer_data['Customer']['market_id'];
            $this->request->data['Account']['kendra_id']=$customer_data['Customer']['kendra_id'];
            $this->request->data['Account']['user_id'] = $customer_data['Customer']['user_id'];
            $this->request->data['Account']['account_type']='SAVING_'.$this->request->data['Saving']['saving_type'];
            //$this->request->data['Account']['account_number']=$this->request->data['Saving']['account_number'];
            $this->request->data['Account']['opening_balance']=$this->request->data['Saving']['savings_amount'];
            $this->request->data['Account']['created_on']=$current_date_time;
            $this->request->data['Account']['modified_on']=$current_date_time;
            $this->request->data['Account']['plan_amount']=$plan_array['saving']['min_amount'];
            $this->request->data['Account']['interest_rate']=$this->request->data['Saving']['interest_rate'];
            $this->request->data['Account']['exces_interest']=$this->request->data['Saving']['interest_rate']-3;
			
            //pr($inerest_data);
            //pr($this->request->data);die;
			$this->Account->save($this->request->data);
			$this->request->data['Saving']['account_id']=$this->Account->getLastInsertId();
 
			if($this->Saving->save($this->request->data)){
			     
                 $saving_id=$this->Saving->getLastInsertId();
                $transaction_data =!empty($this->request->data['Saving']['savings_date'])?date("Y-m-d",strtotime($this->request->data['Saving']['savings_date'])):date("Y-m-d");
                $saving_amount = $this->request->data['Saving']['savings_amount'];
                // Saving Saving Transaction Data
    		   $this->request->data['SavingsTransaction']['account_id']=$this->request->data['Saving']['account_id'];
    		   $this->request->data['SavingsTransaction']['saving_id']=$saving_id;
    		   $this->request->data['SavingsTransaction']['transaction_on']=$transaction_data;
    		   $this->request->data['SavingsTransaction']['amount']=$saving_amount;
    		   $this->request->data['SavingsTransaction']['transaction_type']='CREDIT';
    		   $this->request->data['SavingsTransaction']['balance']=$this->request->data['Saving']['savings_amount'];
    		   $this->request->data['SavingsTransaction']['customer_id']=$customer_data['Customer']['id'];
    		   $this->request->data['SavingsTransaction']['organization_id']=$customer_data['Customer']['organization_id'];
    		   $this->request->data['SavingsTransaction']['region_id']=$customer_data['Customer']['region_id'];
    		   $this->request->data['SavingsTransaction']['branch_id']=$customer_data['Customer']['branch_id'];
    		   $this->request->data['SavingsTransaction']['market_id']=$customer_data['Customer']['market_id'];
               $this->request->data['SavingsTransaction']['kendra_id']=$customer_data['Customer']['kendra_id'];
    		   $this->request->data['SavingsTransaction']['created_on']=$current_date_time;
    		   $this->request->data['SavingsTransaction']['user_id']=$this->Auth->user('id');
    		   $this->SavingsTransaction->save($this->request->data);
    		   //Saving Income Expenditure Data 
    		   $this->request->data['IncomeExpenditure']['account_ledger_id']=1;
               $this->request->data['IncomeExpenditure']['account_id']=$this->request->data['Saving']['account_id'];
    		   $this->request->data['IncomeExpenditure']['credit_amount']=$saving_amount;
    		   $this->request->data['IncomeExpenditure']['transaction_date']=$transaction_data;
    		   $this->request->data['IncomeExpenditure']['balance']=$this->request->data['Saving']['current_balance'];
    		   $this->request->data['IncomeExpenditure']['organization_id']=$customer_data['Customer']['organization_id'];
    		   $this->request->data['IncomeExpenditure']['region_id']=$customer_data['Customer']['region_id'];
    		   $this->request->data['IncomeExpenditure']['branch_id']=$customer_data['Customer']['branch_id'];
    		   $this->request->data['IncomeExpenditure']['market_id']=$customer_data['Customer']['market_id'];
    		   $this->request->data['IncomeExpenditure']['created_on']=$current_date_time;
    		   $this->request->data['IncomeExpenditure']['user_id']=$this->Auth->user('id');
    		   $this->IncomeExpenditure->save($this->request->data);
    		   //Saving Extra Income Data
    		   if($saving_amount>$plan_array['saving']['min_amount']){
    			   $this->request->data['ExtraAmount']['account_id']=$this->request->data['Saving']['account_id'];
    			   $this->request->data['ExtraAmount']['customer_id']=$customer_data['Customer']['id'];
    			   $this->request->data['ExtraAmount']['amount']=$saving_amount-$plan_array['saving']['min_amount'];
    			   $this->request->data['ExtraAmount']['paid_on']=$transaction_data;
    			   $this->request->data['ExtraAmount']['interest_rate']=$this->request->data['Account']['exces_interest'];
    			   $this->request->data['ExtraAmount']['created_on']=$current_date_time;
    			   $this->request->data['ExtraAmount']['modified_on']=$current_date_time;
    			   $this->ExtraAmount->save($this->request->data);
    		   }
             
			     $this->Session->setFlash(__('The Saving Account has been created'));
			     $this->redirect('/create_saving/'.$cust_id);
			}else {
    			$this->Session->setFlash(__('The Saving Account could not be created. Please try again.'));
    		}
			
		} 
	}
	// VSSU add a saving plan function end
    
	// Select Customer for Add Saving function Start
	public function saving_create(){
		$this->layout = 'panel_layout';
		$this->set('title', 'Create Saving');
        if($this->Auth->user('user_type_id')==2){
            $market_list = $this->Market->find('list',array('fields'=>array('id','market_name'),'conditions'=>array('Market.organization_id'=>$this->Auth->user('organization_id'))));
        }
        if($this->Auth->user('user_type_id')==5){
            $market_list = $this->Market->find('list',array('fields'=>array('id','market_name'),'conditions'=>array('Market.user_id'=>$this->Auth->user('id'))));
        }
        $this->set('market_list',$market_list);
		if ($this->request->is('post')) {
			//pr($this->request->data); die;
			if(!empty($this->request->data['customer_id'])){
				$customer_id=$this->request->data['customer_id'];
				$this->redirect('/create_saving/'.$customer_id);
			} else {
				$this->redirect('/saving_create/');
			}
		}
	}
	// Select Customer for Add Saving function End
	
	// Saving customer names based on market id via ajax function start
    public function ajax_customer_list_saving($mid='')
    {
        $this->layout = 'ajax';
		// Creating a virtual field of full name
        $this->Customer->virtualFields = array(
                'full_name' => "CONCAT(Customer.cust_fname, ' ', Customer.cust_lname)"
            );
        $loan_cust_list = $this->Customer->find('list',array(
            'fields'=>array('Customer.id','Customer.full_name'),
            'conditions'=>array('Customer.market_id'=>$mid,'Customer.status'=>1)
            ));
		//pr($loan_cust_list); die;
        $this->set('loan_cust_list', $loan_cust_list);
    }
	// Saving customer names based on market id via ajax function end
	
	// Load plan function start
    function ajax_load_plan(){
        $this->layout = 'ajax';
        $plan_data = json_decode($_POST['data'],true);
        $this->set('plan_data',$plan_data);
        //pr($plan_data);die;
    }
	// Load plan function end
/* 
    function daily_saving_deposit($market_id=''){
        $this->layout = 'panel_layout';
		$this->set('title', 'Bulk Savings Daily Collection');
        
        $market_list = $this->Market->find('list',array('fields'=>array('id','market_name'),'conditions'=>array('Market.status'=>1),'recursive'=>-1));
        $this->set('market_list', $market_list);
        $data_list = array();
        $trans_list = array();
        if ($this->request->is('post')) {
            $market_id = $this->request->data['Saving']['market_id'];
        }
        if($market_id!=''){
            $this->Account->unBindModel(array('belongsTo' => array(
                'User',
                'Organization',
                'Market',
                'Region'),
                'hasOne'=>array('Loan')));

            $data_list = $this->Account->find('all',array('fields'=>array('Branch.branch_name','Saving.current_balance','Account.account_number','Account.id','Saving.id','Customer.cust_fname','Customer.cust_lname'),
                    'conditions'=>array('Account.status'=>1,'Account.market_id'=>$market_id,'OR'=>array(array('Account.account_type'=>'SAVING_Daily'),array('Account.account_type'=>'SAVING_Weekly'),array('Account.account_type'=>'SAVING_Monthly'))),
                    ));
            
            $trans_list = $this->SavingsTransaction->find('all',array('fields'=>array('SavingsTransaction.account_id','SavingsTransaction.transaction_on','SavingsTransaction.amount'),'conditions'=> array('year(SavingsTransaction.transaction_on)'=>date("Y"),'month(SavingsTransaction.transaction_on)'=>date("m"))));
            
            //pr($data_list);die;
        }
        $this->set('trans_list',$trans_list);
        $this->set('data_list',$data_list);
        $this->set('market_id', $market_id);
    }
*/
    
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
       //echo $_POST['value'];  
    }
    // Daily Collection Saving in AJAX Function End
    
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
    
    // Plan List Function Start
	function plan_list(){
		$organisation_id = $this->Auth->User('organization_id');
		$this->set('title', 'Plan List');
		$this->layout = 'panel_layout';
		$plan_list=$this->Plan->find('all',array('conditions'=>array('Plan.status'=>1, 'Plan.organization_id'=>$organisation_id)));
		$this->set('plan_list',$plan_list); 
	}
	// Plan List Function End
    
    // Edit Saving Plan Function Start
	public function edit_saving_plan($plan_id){
		$this->layout = 'panel_layout';
		$this->set('title', 'Edit Savings Plan');
		$plan_details=$this->Plan->find('first',array('conditions'=>array('Plan.status'=>1, 'Plan.id'=>$plan_id)));
		$this->set('plan_details',$plan_details);
		
		if ($this->request->is('post')) {
			//pr($this->request->data);die;
			$saving['saving']['min_amount']=$this->request->data['min_amount'];
			$saving['saving']['saving_type']=$this->request->data['Plan']['saving_type'];
			$saving['saving']['interval_day']=$this->request->data['interval_day'];
			$saving['saving']['interest_rate']=$this->request->data['interest_rate'];
			$saving['saving']['saving_period']=$this->request->data['saving_period'];
            $saving['saving']['interest_type']=$this->request->data['Plan']['interest_type'];
            
			$saving['prematurity']['before12fixed']=$this->request->data['pre_mat_b12_fixed'];
			$saving['prematurity']['before12percentage']=$this->request->data['pre_mat_b12_percentage'];
			$saving['prematurity']['after12percentage']=$this->request->data['pre_mat_a12_percentage'];
			
			$this->request->data['Plan']['id'] = $this->request->data['plan_id'];
			$this->request->data['Plan']['plan_name'] = $this->request->data['plan_name'];
			$this->request->data['Plan']['plan_value'] = json_encode($saving);
			$this->request->data['Plan']['plan_type'] = 1;
			$this->request->data['Plan']['organization_id'] = $this->Auth->user('organization_id');
			$this->request->data['Plan']['modified_on'] = date("Y-m-d H:i:s");
			//pr($this->request->data);die;
			$this->Plan->save($this->request->data);
			$this->Session->setFlash(__('The plan has been updated'));
			$this->redirect('/plan_list');
		} 
	}
	// Edit Saving Plan Function End
    
    // Edit Loan Plan Function Start
	public function edit_loan_plan($plan_id){
		$this->layout = 'panel_layout';
		$this->set('title', 'Edit Loan Plan');
		$plan_details=$this->Plan->find('first',array('conditions'=>array('Plan.status'=>1, 'Plan.id'=>$plan_id)));
		$this->set('plan_details',$plan_details);

		if ($this->request->is('post')) {
			$loan['min_amount']=$this->request->data['min_amount'];
			$loan['loan_type']=$this->request->data['Plan']['loan_type'];
			$loan['interest_type']=$this->request->data['Plan']['interest_type'];
			$loan['loan_risk_type']=$this->request->data['Plan']['loan_risk_type'];
			$loan['interest_rate']=$this->request->data['interest_rate'];
            $loan['min_save_per']=$this->request->data['min_save_per'];
            $loan['overdue_period_interest']=$this->request->data['overdue_period_interest'];
			
			$this->request->data['Plan']['id'] = $this->request->data['plan_id'];
			$this->request->data['Plan']['plan_name'] = $this->request->data['plan_name'];
			$this->request->data['Plan']['plan_value'] = json_encode($loan);
			$this->request->data['Plan']['plan_type'] = 2;
			$this->request->data['Plan']['organization_id'] = $this->Auth->user('organization_id');
			$this->request->data['Plan']['modified_on'] = date("Y-m-d H:i:s");
			
			//pr($this->request->data);die;
			$this->Plan->save($this->request->data);
			$this->Session->setFlash(__('The plan has been updated'));
			$this->redirect('/plan_list');
		} 
	}
	// Edit Loan Plan Function End
    
	// Delete Plan Function Start
	function delete_plan($plan_id){
		$this->request->data['Plan']['id'] = $plan_id;
		$this->request->data['Plan']['status'] = 2;
		$this->Plan->save($this->request->data);
		$this->Session->setFlash(__('The plan has been deleted'));
		$this->redirect('/plan_list');
	}
	// Delete Plan Function End
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
	// Bulk Saving collection function start
	public function bulk_saving_collection(){
		$this->layout = 'panel_layout';
		$this->set('title', 'Bulk Saving Collection');
        if($this->Auth->user('user_type_id')==2){
            $kendra_list = $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.organization_id'=>$this->Auth->user('organization_id'))));
        }
        if($this->Auth->user('user_type_id')==5){
            $kendra_list = $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.user_id'=>$this->Auth->user('id'))));
        }
        $this->set('kendra_list',$kendra_list);
        $loan_payment_list = array();
        $loan_payment_list = $this->LoanTransaction->find('list',array(
            'fields'=>array('LoanTransaction.insta_due_on','LoanTransaction.insta_due_on'),
            'conditions'=>array('Loan.kendra_id'=>key($kendra_list),'Loan.loan_status_id'=>3,'Loan.status'=>1,'LoanTransaction.insta_principal_paid'=>0),
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
        $this->set('loan_payment_list', $loan_payment_list);
        if ($this->request->is('post')) {
            $kendra_id = $this->request->data['Savings']['kendra_id'];
            $due_on = date("Y-m-d",strtotime($this->request->data['Savings']['insta_due_on']));
            $this->redirect('/kendra_saving_collection/'.$kendra_id.'/'.$due_on);
        }
    }
	// Bulk Saving collection function end
	
	// Kendra wise saving collection function start
    public function kendra_saving_collection($kendra_id='',$due_date){
        $this->layout = 'collection_layout';
		$this->set('title', 'Kendra Saving Collection');
        $this->set('due_date', $due_date);
        $kendra_data = $this->kendra_details($kendra_id);
        $this->LoanTransaction->bindModel(array(
            'belongsTo' => array(
                'Customer' => array(
            			'className'    	=> 'Customer',
            			'foriegnKey'	=> 'customer_id'
            		)
              )
              ),
            false
        );
        $loan_payment_data = $this->LoanTransaction->find('all',array(
            'conditions'=>array('Loan.kendra_id'=>$kendra_id,'LoanTransaction.insta_due_on'=>$due_date,'LoanTransaction.insta_paid_on'=>'0000-00-00','Loan.loan_status_id'=>3,'Loan.status'=>1),
            'joins' =>
                  array(
                    array(
                        'table' => 'loans',
                        'alias' => 'Loan',
                        'type' => 'inner',
                        'foreignKey' => true,
                        'conditions'=> array('Loan.id = LoanTransaction.loan_id')
                    ))
            
            ));
		$loan_payment_data=$kendra_data['Customer'];
        unset($kendra_data['Customer']);
        unset($kendra_data['Loan']);
        $kendra_data['Transaction'] = $loan_payment_data;
        $this->set('kendra_data', $kendra_data);
        if ($this->request->is('post')) {
            $kendra_id = $this->request->data['LoanTransaction']['kendra_id'];
            $due_on = $this->request->data['LoanTransaction']['insta_due_on'];
            $cust_arr = $this->request->data['cust_arr'];
            $this->savings_amount_collection($kendra_id,$due_on,$cust_arr);
            $this->redirect('/bulk_saving_collection');
        }
    }
	// Kendra wise saving collection function end
	
	// Saving return of a customer function start
	public function savings_return($kid='',$cid='')
    {
        $this->layout = 'panel_layout';
		$this->set('title', 'Savings Withdrawn');
        $this->set('kendra_id', $kid);
        $this->set('customer_id', $cid);
        $this->Saving->unBindModel(array(
        'hasMany' => array('SavingsTransaction'),
        'belongsTo' => array('Currency','Organization','Region','Branch','Kendra')
        ));
        if($this->Auth->user('user_type_id')==2){
            $kendra_list = $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.organization_id'=>$this->Auth->user('organization_id'))));
            if($kid=='')
                $savings_list = $this->Saving->find('all',array('conditions'=>array('Saving.status'=>1, 'Saving.organization_id'=>$this->Auth->user('organization_id'))));
            else
                $savings_list = $this->Saving->find('all',array('conditions'=>array('Saving.status'=>1, 'Saving.kendra_id'=>$kid)));
        }
        if($this->Auth->user('user_type_id')==5){
            $kendra_list = $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.user_id'=>$this->Auth->user('id'))));
            if($kid=='')
                $savings_list = $this->Saving->find('all',array('conditions'=>array('Saving.status'=>1, 'Saving.user_id'=>$this->Auth->user('id'))));
            else
                $savings_list = $this->Saving->find('all',array('conditions'=>array('Saving.status'=>1, 'Saving.kendra_id'=>$kid)));
        }
        $this->set('kendra_list',$kendra_list);
		$cust_list = array();
        $fees_arr = array();
        foreach($savings_list as $loan_row){
            $cust_list[$loan_row['Customer']['id']] = $loan_row['Customer']['cust_fname'].' '.$loan_row['Customer']['cust_lname'];
            $fees_arr [$loan_row['Customer']['id']] = $loan_row['Saving']['current_balance'];
        }
        $this->set('cust_list',$cust_list);
        $this->set('fees_arr',$fees_arr);
        if ($this->request->is('post')) {
			$kendra_id= $this->request->data['Saving']['kendra_id'];
			$customer_id= $this->request->data['Saving']['customer_id'];
			$saving_amount= $this->request->data['Saving']['savings_amount'];
			$saving_data = $this->Saving->find('first',array('conditions'=>array('Saving.status'=>1, 'Saving.kendra_id'=>$kendra_id, 'Saving.customer_id'=>$customer_id)));
			$savings_amt=$saving_amount;
			$user_id=$this->Auth->user('id');
			$recipt_date=date('Y-m-d H:i:s');
			$organization_id = $saving_data['Organization']['id'];
			$region_id = $saving_data['Region']['id'];
			$branch_id = $saving_data['Branch']['id'];
			$kendra_id=$saving_data['Kendra']['id'];
			$cust_id=$customer_id;
			$savings_amt=$saving_amount;
			// Savings Table Updation
			$savings_arr['Saving']['id']= $saving_data['Saving']['id'];
			$savings_arr['Saving']['current_balance']= $saving_data['Saving']['current_balance']-$savings_amt;
			$savings_arr['Saving']['modified_on']= $recipt_date;
			$savings_arr['Saving']['user_id']= $user_id;
			// Saving Transaction Table
            $savings_arr['SavingsTransaction']['saving_id'] = $saving_data['Saving']['id'];
            $savings_arr['SavingsTransaction']['transaction_on'] = $recipt_date;
            $savings_arr['SavingsTransaction']['amount'] = $savings_amt;
            $savings_arr['SavingsTransaction']['transaction_type'] = 'Withdrawn';
            $savings_arr['SavingsTransaction']['balance'] = $savings_arr['Saving']['current_balance'];
            $savings_arr['SavingsTransaction']['customer_id'] = $cust_id;
            $savings_arr['SavingsTransaction']['organization_id'] = $organization_id;
            $savings_arr['SavingsTransaction']['branch_id'] = $branch_id;
            $savings_arr['SavingsTransaction']['kendra_id'] = $kendra_id;
            $savings_arr['SavingsTransaction']['created_on'] = $recipt_date;
            $savings_arr['SavingsTransaction']['user_id'] = $this->Auth->user('id');
            $this->Saving->clear();
			$this->SavingsTransaction->clear();
			$this->Saving->save($savings_arr);
			$this->SavingsTransaction->save($savings_arr);
            $this->Session->setFlash('Saving Amount Withdrawn');
            $this->redirect('/savings_return/');
        }
    }
	// Saving return of a customer function end
	
	// List of customers of security fee will be given function start
	public function ajax_security_fee_customer_list($kid=0){
        $this->layout = 'ajax';
        $loans_list = $this->Saving->find('all',array('conditions'=>array('Saving.status'=>1, 'Saving.kendra_id'=>$kid)));
        $loan_cust_list = array();
        foreach($loans_list as $loan_row){
            $loan_cust_list[$loan_row['Customer']['id']] = $loan_row['Customer']['cust_fname'].' '.$loan_row['Customer']['cust_lname'];
        }
        $this->set('loan_cust_list', $loan_cust_list);
    }
	// List of customers of security fee will be given function end
	
	// Amount of security fee of a customer will be given function start
    public function ajax_security_fee_amount($cid=0){
        $this->layout = 'ajax';
        $this->autoRender = false;
        $res_arr=array('loan_id'=>0,"fees"=>0);
        if($cid>0){
            $loans = $this->Saving->find('first',array('conditions'=>array('Saving.status'=>1, 'Saving.customer_id'=>$cid)));
			if($loans['Saving']['current_balance']>0){
                $res_arr['fees'] = $loans['Saving']['current_balance'];
                $res_arr['saving_id'] = $loans['Saving']['id'];
            }
        }
        echo json_encode($res_arr); // Send the amount in JSON format
    }
	// Amount of security fee of a customer will be given function end
	
	
}
// END of Saving Controller
?>