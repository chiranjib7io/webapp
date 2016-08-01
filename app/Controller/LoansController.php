<?php
// This is a loan controller. All the functions related to a loan is mentioned here. 
App::uses('CakeEmail', 'Network/Email');
class LoansController extends AppController {
	// List of models which are used in the loan controller 
	var $uses = array('User','Organization','Region','Branch','Market','Kendra','Customer','Loan','LoanTransaction','Account','LoanStatus','Saving','Idproof','LogRecord','Country','IncomeExpenditure');
	
	// This is a blank index function for safe navigation
    public function index() {		
    }
	
    
    // Create a Loan function start
    public function create_loan($cust_id='') {
		$this->layout = 'panel_layout';
        $this->set('title', 'Create Loan');
        $org_data= $this->get_organization_settings_fees($this->Auth->user('organization_id'));
        $user_data= $this->User->find('all',array('conditions'=>array('User.id'=>$this->Auth->user('id'))));
        $branch_list= $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.organization_id'=>$this->Auth->user('organization_id'))));
        $cust_data= $this->Customer->find('first',array('conditions'=>array('Customer.id'=>$cust_id)));
        $plan_data = $this->Plan->find('list',array('fields'=>array('plan_value','plan_name'),'conditions'=>array('Plan.plan_type'=>2,'Plan.status'=>1)));
        $this->set('plan_data', $plan_data);

        $this->set('org_data', $org_data);
        $this->set('user_data', $user_data);
        $this->set('branch_list', $branch_list);
        $this->set('cust_data', $cust_data);
        
        if ($this->request->is('post')) {
			//pr($this->request->data);die;
            $this->request->data['Loan']['created_on'] = date("Y-m-d H:i:s");
            $this->request->data['Loan']['user_id'] = $this->Auth->user('id');
            $gurantor = $this->request->data['guranter'];
            $guranter_arr = array();
            foreach($gurantor['guranter_name'] as $k=>$v){
                $guranter_arr[$k]['guranter_name'] = $v;
                $guranter_arr[$k]['guranter_account_no'] = $gurantor['guranter_account_no'][$k];
                $guranter_arr[$k]['guranter_amount'] = $gurantor['guranter_amount'][$k];
            }
            $this->request->data['Loan']['guranter']=json_encode($guranter_arr);
            
            $this->request->data['Account']['customer_id']=$this->request->data['Loan']['customer_id'];
            $this->request->data['Account']['organization_id']=$this->request->data['Loan']['organization_id'];
            $this->request->data['Account']['region_id']=$this->request->data['Loan']['region_id'];
            $this->request->data['Account']['branch_id']=$this->request->data['Loan']['branch_id'];
            $this->request->data['Account']['market_id']=$this->request->data['Loan']['market_id'];
            $this->request->data['Account']['kendra_id']=($this->request->data['Loan']['kendra_id']>0)?$this->request->data['Loan']['kendra_id']:0;
            $this->request->data['Account']['user_id'] = $this->Auth->user('id');
            $this->request->data['Account']['account_type']='LOAN';
            //$this->request->data['Account']['account_number']=$this->request->data['Saving']['account_number'];
            $this->request->data['Account']['opening_overdraft_balance']=$this->request->data['Loan']['loan_principal'];
            $this->request->data['Account']['created_on']=date("Y-m-d H:i:s");
            $this->request->data['Account']['modified_on']=date("Y-m-d H:i:s");
            $this->request->data['Account']['plan_amount']=$this->request->data['Loan']['loan_rate'];
            $this->request->data['Account']['interest_rate']=$this->request->data['Loan']['loan_interest'];
            $this->request->data['Account']['exces_interest']=$this->request->data['Loan']['loan_interest'];
            
            $this->Account->save($this->request->data);
			$this->request->data['Loan']['account_id']=$this->Account->getLastInsertId();
            $this->request->data['Loan']['kendra_id'] = $this->request->data['Account']['kendra_id'];
            $this->request->data['Loan']['current_outstanding']=$this->request->data['Loan']['loan_repay_total'];
            
			$this->Loan->create();
			if ($this->Loan->save($this->request->data)) {
				//$last_insert_loan=$this->Loan->getLastInsertId();
				$this->Session->setFlash(__('The Loan has been created'));
				$this->redirect('/customer_details/'.$cust_id);
			} else {
				$this->Session->setFlash(__('The Loan could not be created. Please, try again.'));
			}	
        }
    }
	// Create a Loan function end
    
    public function ajax_guranter_row(){
        $this->layout = 'ajax';
    }
    
    
    // Create Loan Plan Function Start
	public function add_loan_plan(){
		$this->layout = 'panel_layout';
		$this->set('title', 'Create Loan Plan');
        $loan_fees_data= $this->Fee->find('all',array('conditions'=>array('Fee.organization_id'=>$this->Auth->user('organization_id'))));
		$this->set('loan_fees_data', $loan_fees_data);

		if ($this->request->is('post')) {
			//pr($this->request->data);die;
			$loan['min_amount']=$this->request->data['min_amount'];
			$loan['loan_type']=$this->request->data['Plan']['loan_type'];
			$loan['interest_type']=$this->request->data['Plan']['interest_type'];
			//$loan['loan_period_type']=$this->request->data['Plan']['loan_period_type'];
			$loan['loan_risk_type']=$this->request->data['Plan']['loan_risk_type'];
			//$loan['interval_day']=$this->request->data['interval_day'];
			$loan['interest_rate']=$this->request->data['interest_rate'];
			//$loan['loan_period']=$this->request->data['loan_period'];
            $loan['min_save_per']=$this->request->data['min_save_per'];
            $loan['overdue_period_interest']=$this->request->data['overdue_period_interest'];
			
			$this->request->data['Plan']['plan_name'] = $this->request->data['plan_name'];
			$this->request->data['Plan']['plan_value'] = json_encode($loan);
			$this->request->data['Plan']['plan_type'] = 2;
			$this->request->data['Plan']['organization_id'] = $this->Auth->user('organization_id');
			$this->request->data['Plan']['modified_on'] = date("Y-m-d H:i:s");
			
			//pr($this->request->data);die;
			$this->Plan->save($this->request->data);
            
            // For General Settings
            $feedata=$this->request->data['Fee'];
			foreach ($feedata as $keyf=> $valf){
				$dataf['Fee']['id'] =$keyf;
				$dataf['Fee']['fee_value'] = $valf;
				$this->Fee->clear();
				$this->Fee->save($dataf);
			}
            
			$this->Session->setFlash(__('The plan has been saved'));
			$this->redirect('/create_loan_plan');
		} // Post End 
	}
	// Create Loan Plan Function End
    
	// Select Customer for Add Loan function Start
	public function loan_create(){
		$this->layout = 'panel_layout';
		$this->set('title', 'Create Loan');
        if($this->Auth->user('user_type_id')==2){
            $market_list = $this->Market->find('list',array('fields'=>array('id','market_name'),'conditions'=>array('Market.organization_id'=>$this->Auth->user('organization_id'))));
        }
        if($this->Auth->user('user_type_id')==5){
            $market_list = $this->Market->find('list',array('fields'=>array('id','market_name'),'conditions'=>array('Market.user_id'=>$this->Auth->user('id'))));
        }
        $this->set('market_list',$market_list);
		if ($this->request->is('post')) {
			//pr($this->request->data); die;
			if(!empty($this->request->data['Loan']['customer_id'])){
				$customer_id=$this->request->data['Loan']['customer_id'];
				$this->redirect('/create_loan/'.$customer_id);
			} else {
				$this->redirect('/loan_create/');
			}
			
		}
	}
	// Select Customer for Add Loan function End
	
	// Load customer names based on market id via ajax function start
    public function ajax_customer_list_loan($mid='')
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
        $this->set('loan_cust_list', $loan_cust_list);
    }
	// Load customer names based on market id via ajax function end
    
    // Bulk Loan release Details function start
    public function loan_release($acct_id=''){
        $this->layout = 'panel_layout';
		$this->set('title', 'Loan Release');
        $loan_data= $this->Loan->find('first',array('conditions'=>array('Loan.account_id'=>$acct_id,'Loan.loan_status_id'=>1,'Loan.status'=>1)));
        $this->set('loan_data',$loan_data);
        $loan_id = $loan_data['Loan']['id'];
        $loan_status= $this->LoanStatus->find('list',array('fields'=>array('id','status_name'),'conditions'=>array('LoanStatus.status'=>1)));
        $this->set('loan_status', $loan_status);
        if ($this->request->is('post')) {
            $trans = 0;
            $this->request->data['Loan']['id'] = $loan_id;
            $this->request->data['Loan']['modified_on'] = date("Y-m-d H:i:s");
            if($this->request->data['Loan']['loan_status_id']==3){
                $this->request->data['Loan']['loan_dateout']=date("Y-m-d");
                $this->request->data['Loan']['loan_repay_start']=$this->request->data['insta_start'];
                $this->request->data['Loan']['loan_issued'] = 1;
                $period_unit =  strtolower($loan_data['Loan']['loan_period_unit']).'s';
                $this->request->data['Loan']['maturity_date'] = date("Y-m-d",strtotime('+'.$loan_data['Loan']['loan_period'].' '.$period_unit, strtotime($this->request->data['Loan']['loan_dateout'])));
                $trans = 1;
                
                    //Saving Income Expenditure Data 
            	   $exp_data['IncomeExpenditure']['account_ledger_id']=6;
                   $exp_data['IncomeExpenditure']['account_id']=$acct_id;
            	   $exp_data['IncomeExpenditure']['debit_amount']=$loan_data['Loan']['loan_principal'];
            	   $exp_data['IncomeExpenditure']['transaction_date']=date("Y-m-d");
            	   $exp_data['IncomeExpenditure']['balance']=$loan_data['Loan']['loan_principal'];
            	   $exp_data['IncomeExpenditure']['organization_id']=$loan_data['Loan']['organization_id'];
            	   $exp_data['IncomeExpenditure']['region_id']=$loan_data['Loan']['region_id'];
            	   $exp_data['IncomeExpenditure']['branch_id']=$loan_data['Loan']['branch_id'];
            	   $exp_data['IncomeExpenditure']['market_id']=$loan_data['Loan']['market_id'];
            	   $exp_data['IncomeExpenditure']['created_on']=date("Y-m-d H:i:s");
            	   $exp_data['IncomeExpenditure']['user_id']=$loan_data['Loan']['user_id'];
                   $this->IncomeExpenditure->clear();
            	   $this->IncomeExpenditure->save($exp_data);
                   
                   //Saving Income Expenditure Data 
                   if($loan_data['Loan']['security_fee']>0){
                       $exp_data = array();
                	   $exp_data['IncomeExpenditure']['account_ledger_id']=11;
                       $exp_data['IncomeExpenditure']['account_id']=$acct_id;
                	   $exp_data['IncomeExpenditure']['credit_amount']=$loan_data['Loan']['security_fee'];
                	   $exp_data['IncomeExpenditure']['transaction_date']=date("Y-m-d");
                	   $exp_data['IncomeExpenditure']['balance']=$loan_data['Loan']['security_fee'];
                	   $exp_data['IncomeExpenditure']['organization_id']=$loan_data['Loan']['organization_id'];
                	   $exp_data['IncomeExpenditure']['region_id']=$loan_data['Loan']['region_id'];
                	   $exp_data['IncomeExpenditure']['branch_id']=$loan_data['Loan']['branch_id'];
                	   $exp_data['IncomeExpenditure']['market_id']=$loan_data['Loan']['market_id'];
                	   $exp_data['IncomeExpenditure']['created_on']=date("Y-m-d H:i:s");
                	   $exp_data['IncomeExpenditure']['user_id']=$loan_data['Loan']['user_id'];
                       $this->IncomeExpenditure->clear();
                	   $this->IncomeExpenditure->save($exp_data);
                   }
                   
                   //Saving Income Expenditure Data 
                   if($loan_data['Loan']['processing_fee']>0){
                       $exp_data = array();
                	   $exp_data['IncomeExpenditure']['account_ledger_id']=8;
                       $exp_data['IncomeExpenditure']['account_id']=$acct_id;
                	   $exp_data['IncomeExpenditure']['credit_amount']=$loan_data['Loan']['processing_fee'];
                	   $exp_data['IncomeExpenditure']['transaction_date']=date("Y-m-d");
                	   $exp_data['IncomeExpenditure']['balance']=$loan_data['Loan']['processing_fee'];
                	   $exp_data['IncomeExpenditure']['organization_id']=$loan_data['Loan']['organization_id'];
                	   $exp_data['IncomeExpenditure']['region_id']=$loan_data['Loan']['region_id'];
                	   $exp_data['IncomeExpenditure']['branch_id']=$loan_data['Loan']['branch_id'];
                	   $exp_data['IncomeExpenditure']['market_id']=$loan_data['Loan']['market_id'];
                	   $exp_data['IncomeExpenditure']['created_on']=date("Y-m-d H:i:s");
                	   $exp_data['IncomeExpenditure']['user_id']=$loan_data['Loan']['user_id'];
                       $this->IncomeExpenditure->clear();
                	   $this->IncomeExpenditure->save($exp_data);
                   }
                   
                   //Saving Income Expenditure Data 
                   if($loan_data['Loan']['admission_fee']>0){
                       $exp_data = array();
                	   $exp_data['IncomeExpenditure']['account_ledger_id']=9;
                       $exp_data['IncomeExpenditure']['account_id']=$acct_id;
                	   $exp_data['IncomeExpenditure']['credit_amount']=$loan_data['Loan']['admission_fee'];
                	   $exp_data['IncomeExpenditure']['transaction_date']=date("Y-m-d");
                	   $exp_data['IncomeExpenditure']['balance']=$loan_data['Loan']['admission_fee'];
                	   $exp_data['IncomeExpenditure']['organization_id']=$loan_data['Loan']['organization_id'];
                	   $exp_data['IncomeExpenditure']['region_id']=$loan_data['Loan']['region_id'];
                	   $exp_data['IncomeExpenditure']['branch_id']=$loan_data['Loan']['branch_id'];
                	   $exp_data['IncomeExpenditure']['market_id']=$loan_data['Loan']['market_id'];
                	   $exp_data['IncomeExpenditure']['created_on']=date("Y-m-d H:i:s");
                	   $exp_data['IncomeExpenditure']['user_id']=$loan_data['Loan']['user_id'];
                       $this->IncomeExpenditure->clear();
                	   $this->IncomeExpenditure->save($exp_data);
                   }
                
            }
            //pr($this->request->data);die;
            
			$this->Loan->clear();
			if ($this->Loan->save($this->request->data)) {
                if($trans==1){ 
                    $this->create_loan_transaction($loan_id,$this->request->data['insta_start']);
                }
			} else {
				$this->Session->setFlash('The Loan could not be released. Please, try again.');
			}
        } // Post if end
    } // function end
    // Bulk Loan release Details function end
    
    // Bulk Loan release function start
    public function bulk_loan_release(){
        $this->layout = 'panel_layout';
		$this->set('title', 'Bulk Loan Release');
        $organization_id=$this->Auth->user('organization_id');
        if ($this->request->is('post')) {
			$kid = $this->request->data['Loan']['kendra_id'];	
            $bid = $this->request->data['Loan']['branch_id'];		
        }else{
            $bid = $this->Auth->user('branch_id');
        }
        if($this->Auth->user('user_type_id')==2){
            $branch_list = $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.status'=>1, 'Branch.organization_id'=>$this->Auth->user('organization_id'))));
            $kendra_list = $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.status'=>1,'Kendra.branch_id'=>$bid)));
        }else{
            $branch_list = $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.status'=>1, 'Branch.id'=>$bid)));
            $kendra_list = $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.status'=>1,'Kendra.user_id'=>$this->Auth->user('id'),'Kendra.branch_id'=>$bid)));
        }
        $loan_data= $this->Loan->find('all',array('conditions'=>array('Loan.organization_id'=>$organization_id,'Loan.loan_status_id'=>1,'Loan.status'=>1)));
        $this->set('loan_data',$loan_data);
        //pr($loan_data); die;
        $this->set('branch_list', $branch_list);
        $this->set('kendra_list', $kendra_list);
        
        if ($this->request->is('post')) {
            $kendra_id = $this->request->data['Loan']['kendra_id'];
            $this->redirect('/bulk_release_details/'.$kendra_id);
        }
    }
    
    //pending loans
    public function pending_loan()
    {
         $this->layout = 'panel_layout';
        	$this->set('title', 'Pending Loan');
            $loan_data=$this->Loan->find("all",array("conditions"=>array("Loan.loan_status_id"=>1,"Loan.user_id"=>$this->Auth->user('id'))));
            $this->set("loan_data",$loan_data) ;    
    }
    //end pendign loans
    //loand overdue open
    public function loan_overdue()
    {
           $this->layout = 'panel_layout';
    }
    
    //loand overdue close
	// Bulk Loan release function end
    
    // Loan Details based on a particular loan function start
    public function details($loan_id='') {
		$this->layout = 'panel_layout';
        $this->set('title', 'Loan details');
        if($loan_id==''){
           $this->redirect(array('action' => 'details/')); 
        }

        $loan_data = $this->get_loan_snapshot_data($loan_id);
        $loan_trans = $this->get_loan_transaction_data($loan_data['Loan']['account_id'],5);
        $loan_status= $this->LoanStatus->find('list',array('fields'=>array('id','status_name'),'conditions'=>array('LoanStatus.status'=>1,'LoanStatus.id >'=>$loan_data['Loan']['loan_status_id'])));
        
        //pr($loan_data);die;
        $this->set('loan_data', $loan_data);
        $this->set('loan_status', $loan_status);
        $this->set('loan_trans',$loan_trans);
        if ($this->request->is('post')) {
            $trans = 0;
       	    $this->request->data['Loan']['modified_on'] = date("Y-m-d H:i:s");
            if($this->request->data['Loan']['loan_status_id']==3){
                // If the loan is disbursed then the code goes here
                $this->request->data['Loan']['loan_dateout']=date("Y-m-d");
                $this->request->data['Loan']['loan_repay_start']=$this->request->data['insta_start'];
                $this->request->data['Loan']['loan_issued'] = 1;
                $period_unit =  strtolower($loan_data['Loan']['loan_period_unit']).'s';
                $this->request->data['Loan']['maturity_date'] = date("Y-m-d",strtotime('+'.$loan_data['Loan']['loan_period'].' '.$period_unit, strtotime($this->request->data['Loan']['loan_dateout'])));
                $trans = 1;
            }
            //pr($this->request->data);die;
            
           	if ($this->Loan->save($this->request->data)) {
           	    if($trans==1){ 
                    $this->create_loan_transaction($loan_id,$this->request->data['insta_start']);
                }
      	    	$this->Session->setFlash('The Loan has been updated');
				$this->redirect(array('action' => 'details/'.$loan_id));
       	    } else {
				$this->Session->setFlash('The Loan could not be updated. Please, try again.');
			}	
                
        }
            
        
    }
	 // Loan Details based on a particular loan function end
     
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
            //pr($this->request->data);die;
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
            
            //$trans_list = $this->LoanTransaction->find('all',array('conditions'=> array('year(LoanTransaction.insta_paid_on)'=>date("Y"),'month(LoanTransaction.insta_paid_on)'=>date("m"))));
            $trans_list = $this->IncomeExpenditure->find('all',array('conditions'=> array('IncomeExpenditure.account_ledger_id'=>2,'year(IncomeExpenditure.transaction_date)'=>$year,'month(IncomeExpenditure.transaction_date)'=>$month)));
            //pr($trans_list);die;
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
            
            //$trans_list = $this->LoanTransaction->find('all',array('conditions'=> array('year(LoanTransaction.insta_paid_on)'=>date("Y"),'month(LoanTransaction.insta_paid_on)'=>date("m"))));
            $trans_list = $this->IncomeExpenditure->find('all',array('conditions'=> array('IncomeExpenditure.account_ledger_id'=>2,'year(IncomeExpenditure.transaction_date)'=>date("Y"),'month(IncomeExpenditure.transaction_date)'=>date("m"))));
            //pr($trans_list);die;
        }
        $this->set('month',$month);
        $this->set('year',$year);
        $this->set('trans_list',$trans_list);
        $this->set('data_list',$data_list);
        $this->set('market_id', $market_id);
        $this->set('kendra_list', $kendra_list);
    }
   
   public function ajax_group_list_of_market($market_id=''){
        $this->layout = 'ajax';
        if($market_id==''){
            echo '0';
        }else{
            $kendra_list= $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.market_id'=>$market_id),'order'=>array('Kendra.kendra_name asc')));
            $this->set('kendra_list', $kendra_list);
            //pr($kendra_list);die;
        }
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
       //echo $_POST['value'];  
    }
    // Daily Collection Saving in AJAX Function End 
    
    // Market loan details of a branch function start
    public function market_loan_details($market_id='')
    {
        $this->layout = 'panel_layout';
        $this->set('title', 'Market Loan Details');
        // Import Number helper
        App::import('Helper', 'Number');
        $Number = new NumberHelper(new View(null));
        $summary_data = array();
        $user_id=$this->Auth->user('id');
		$user_type_id=$this->Auth->user('user_type_id');
        if($user_type_id==2){
           $branch_list= $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.organization_id'=>$this->Auth->user('organization_id')))); 
           $market_list = array();
        }else{
            $branch_list= $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.id'=>$this->Auth->user('branch_id')))); 
            $market_list = $this->Market->find('list',array('fields'=>array('id','market_name'),'conditions'=>array('Market.branch_id'=>$this->Auth->user('branch_id')))); 
        }
        $this->set('branch_list', $branch_list);
        $this->set('market_list', $market_list);
        
        if($market_id=='' && !empty($this->request->data['User']['market_id'])){
            $market_id = $this->request->data['User']['market_id'];
        }
        
		$market_data= $this->Market->find('first',array('conditions'=>array('Market.id'=>$market_id)));
		$loanArray=array();
        $organizationArray = array();
        $regionArray = array();
        $branchArray = array();
        $creditOfficerArray = array();
        //pr($market_data);die;
		 if(!empty($market_data['Market'])){
			 $organizationArray=$market_data['Organization'];
             $regionArray=$market_data['Region'];
			 $branchArray=$market_data['Branch'];
			 $creditOfficerArray=$market_data['User'];
             $marketArray = $market_data['Market'];
		 }
		$start_date=date("Y-m-d", strtotime("-7 days"));
		// Calculate last updated date in the database
		$max_date = $this->LoanTransaction->find('all',array(
				'fields'=>array(
					'MAX(LoanTransaction.insta_paid_on) as max_date'
				),
				'conditions'=>array(
					
					'LoanTransaction.insta_paid_on !='=> '0000-00-00',
					 'Loan.loan_status_id'=>3,
					 'Loan.market_id'=>$market_id
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
		$this->set('update_on',$final_date);
		$start_date=date("Y-m-d", strtotime("$final_date -7 days"));
		$end_date= $final_date;
		$option_val=1;
		$option_name='Current week';
		$this->set('option_val', $option_val);
		$select_date=date("m-d-Y", strtotime($final_date));
		$send_date['start_date']=$select_date;
		$send_date['end_date']=$select_date;
        
        $this->set('send_date', $send_date);
        
		// User Values after post
		 if ($this->request->is('post')) {
            $branch_id = $this->request->data['User']['branch_id'];	
            $market_id = $this->request->data['User']['market_id'];
			$option_val=$this->request->data['User']['selectdate'];
            
            $market_list = $this->Market->find('list',array('fields'=>array('id','market_name'),'conditions'=>array('Market.branch_id'=>$branch_id))); 
            $this->set('market_list', $market_list);
            
			$this->set('option_val', $option_val);
			if($option_val==1){
				$start_date=date("Y-m-d", strtotime("$final_date -7 days"));
				$end_date= $final_date;
				$option_name='Current week';
			}
			if($option_val==2){
				$start_date=date("Y-m-d", strtotime("$final_date -14 days"));
				$end_date= date("Y-m-d", strtotime("$final_date -7 days"));
				$option_name='Last week';
			}
			if($option_val==3){
				$start_date=date("Y-m-d", strtotime("$final_date -30 days"));
				$end_date= $final_date;
				$option_name='Current Month';
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
				$option_name='Choose Date';
			} 
		 //}
        $send_date['date_diff']= $this->date_differ($start_date,$end_date);
        $send_date['option_val']=$option_val;
        $send_date['option_name']=$option_name;
        $send_date['start_date']=$start_date;
        $send_date['end_date']=$end_date;
        $this->set('send_date', $send_date);
            
         $loan_collection = $this->market_overdue_details($market_id,$start_date,$end_date);
         
         $due_loan = $this->LoanTransaction->find('all',array(
					'fields'=>array(
                        'COUNT(distinct(LoanTransaction.loan_id)) as no_of_loan',
						'SUM(LoanTransaction.insta_principal_due) as due_balance',
                        '(SUM(LoanTransaction.insta_principal_paid)+SUM(LoanTransaction.insta_interest_paid)+SUM(LoanTransaction.overdue_paid)+SUM(LoanTransaction.prepayment)) as paid_balance'
					),
					'conditions'=>array(
						 'Loan.loan_status_id'=>3,
						  'Loan.market_id'=>$market_id
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
								  'Loan.market_id'=>$market_id,
								  'Loan.loan_status_id'=>1,
                                  'Loan.loan_date BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'',
							)
						));
        $approved_loan = $this->Loan->find('all', array(
                            'fields' => array(
								  'COUNT(Loan.id) as no_of_loan',
                                  'SUM(Loan.loan_principal) as total_loan_principal'  
							),
							'conditions' => array(
								  'Loan.market_id'=>$market_id,
								  'Loan.loan_status_id'=>2,
                                  'Date(Loan.approved_date) BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'',
							)
						));
      $disbursed_loan = $this->Loan->find('all', array(
                            'fields' => array(
								  'COUNT(Loan.id) as no_of_loan',
                                  'SUM(Loan.loan_principal) as total_loan_principal'  
							),
							'conditions' => array(
								  'Loan.market_id'=>$market_id,
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
								  'Loan.market_id'=>$market_id,
								  'Loan.loan_status_id'=>6,
                                  'Date(Loan.closing_date) BETWEEN \''.$start_date.'\' AND \''.$end_date .'\'',
							)
        ));
        $total_loan_ever = $this->Loan->find('all', array(
                                    'fields' => array(
        								  'COUNT(Loan.id) as no_of_loan',
                                          'SUM(Loan.loan_principal) as total_loan_principal'  
        							),
                                    'conditions' => array(
                                        'Loan.market_id'=>$market_id,
                                        array(
                                            'OR' => array(
                                                array('Loan.loan_status_id'=>3),
                                                array('Loan.loan_status_id'=>6)
                                                )
                                            )
                                    )
                            ));
        $total_saving = $this->Saving->find('all', array(
                                    'fields' => array(
        								  'COUNT(Saving.id) as no_of_saving',
                                          'SUM(Saving.current_balance) as total_saving_balance'  
        							),
                                    'conditions' => array(
                                        'Saving.market_id'=>$market_id,
                                        'Saving.status'=>1,
                                    )
                            ));
        
         $summary_data['realize_amt'] = $loan_collection[0][0]['insta_realise'];
         $summary_data['realizable_amt'] = $loan_collection[0][0]['insta_realisable'];
         $summary_data['loan_amount_in_mkt'] = $due_loan[0][0]['due_balance']-$due_loan[0][0]['paid_balance']-$loan_collection[0][0]['realizable_interest_amount'];
         $summary_data['total_loan_in_mkt'] = $due_loan[0][0]['no_of_loan'];
         $summary_data['overdue_amount'] = $loan_collection[0][0]['insta_realisable']-$loan_collection[0][0]['insta_realise'];
		 $summary_data['percentage_paid']	=($summary_data['realizable_amt']==0)?100:round(($summary_data['realize_amt']/$summary_data['realizable_amt'] * 100), 2);
         $summary_data['new_loan_application'] = $new_loan_application[0][0];
         $summary_data['approved_loan'] = $approved_loan[0][0];
         $summary_data['disbursed_loan'] = $disbursed_loan[0][0];
         $summary_data['closed_loan'] = $closed_loan[0][0];
         $summary_data['total_loan_ever'] = $total_loan_ever[0][0];
         $summary_data['total_saving'] = $total_saving[0][0];
         $customer_no = $this->Customer->find('count', array(
			'conditions' => array(
				  'Customer.market_id'=>$market_id,
				  'Customer.status'=>1  
			)
		));
        
       $branchLoanSummary['organization_details']=$organizationArray;
	   $branchLoanSummary['officer_details']=$creditOfficerArray;
	   $branchLoanSummary['branch_details']=$branchArray;
       $branchLoanSummary['market_details']=$marketArray;
       $branchLoanSummary['total_group']= $this->Kendra->find('count', array('conditions' => array('Kendra.market_id'=>$market_id,'Kendra.status'=>1) ));
	   
	   $branchLoanSummary['total_customer']=$customer_no;
       $this->set('branchLoanSummary', $branchLoanSummary);
       //pr($branchLoanSummary);
//       pr($summary_data);
//       die;
       $this->set('summary_data', $summary_data);
       }
    } 
     
     
    // Kendra wise loan collection list function start
    public function kendra_loan_collections($kendra_id='',$due_date){
        $this->layout = 'collection_layout';
		$this->set('title', 'Kendra Loan Collection');
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
            'conditions'=>array('Loan.kendra_id'=>$kendra_id,'LoanTransaction.insta_due_on'=>$due_date,'LoanTransaction.is_due'=>'1','Loan.loan_status_id'=>3,'Loan.status'=>1),
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
        unset($kendra_data['Customer']);
        unset($kendra_data['Loan']);
        $kendra_data['Transaction'] = $loan_payment_data;
        $this->set('kendra_data', $kendra_data);
        if ($this->request->is('post')) {
            //pr($this->request->data);die;
            $kendra_id = $this->request->data['LoanTransaction']['kendra_id'];
            $transaction_date = $this->request->data['LoanTransaction']['insta_due_on']; // Date of instalment
            $insta_no = $this->request->data['LoanTransaction']['insta_no']; 
            $cust_arr = $this->request->data['cust_arr'];  // List of customers who paid instalment
            $cust_amt = $this->request->data['cust_val'];
            $cust_acct_id = $this->request->data['cust_acct'];
            
            foreach($cust_arr as $cid=>$v){
                $account_id = $cust_acct_id[$cid];
                $repay_amount = $cust_amt[$cid];
                $this->loan_installment_collection($account_id,$repay_amount,$transaction_date);
            }

            $this->redirect('/kendra_loan_details/'.$kendra_id);
        }
    }
	// Kendra wise loan collection list function end
    public function market_loan_collection($market_id='',$due_date){
        $this->layout = 'collection_layout';
		$this->set('title', 'Market Loan Collection');
        $this->set('due_date', $due_date);
        $market_data = $this->market_details($market_id);
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
            'conditions'=>array('Loan.market_id'=>$market_id,'LoanTransaction.insta_due_on'=>$due_date,'LoanTransaction.is_due'=>'1','Loan.loan_status_id'=>3,'Loan.status'=>1),
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
        unset($market_data['Customer']);
        unset($market_data['Loan']);
        $market_data['Transaction'] = $loan_payment_data;
        $this->set('market_data', $market_data);
        if ($this->request->is('post')) {
            //pr($this->request->data);die;
            $market_id = $this->request->data['LoanTransaction']['market_id'];
            $transaction_date = $this->request->data['LoanTransaction']['insta_due_on']; // Date of instalment
            $insta_no = $this->request->data['LoanTransaction']['insta_no']; 
            $cust_arr = $this->request->data['cust_arr'];  // List of customers who paid instalment
            $cust_amt = $this->request->data['cust_val'];
            $cust_acct_id = $this->request->data['cust_acct'];
            
            foreach($cust_arr as $cid=>$v){
                $account_id = $cust_acct_id[$cid];
                $repay_amount = $cust_amt[$cid];
                
                $this->loan_installment_collection($account_id,$repay_amount,$transaction_date);
                    
                
            }
            
            $this->redirect('/bulk_loan_collections/');
        }
    }
	
	// Kendra wise bulk loan collection function start
    public function bulk_loan_collections(){
        $this->layout = 'panel_layout';
		$this->set('title', 'Bulk Loan Collection');
        
        $market_list = $this->Market->find('list',array('fields'=>array('id','market_name'),'conditions'=>array('Market.organization_id'=>$this->Auth->user('organization_id'))));
        $this->set('market_list',$market_list);
        $this->set('kendra_list',array());
        
        
        if ($this->request->is('post')) {
            $market_id = $this->request->data['Loan']['market_id'];
            $kendra_id = !empty($this->request->data['Loan']['kendra_id'])?$this->request->data['Loan']['kendra_id']:'';
            $due_on = date("Y-m-d",strtotime($this->request->data['Loan']['insta_due_on']));
            if(!empty($kendra_id)){
                $this->redirect('kendra_loan_collections/'.$kendra_id.'/'.$due_on);
            }else{
                $this->redirect('market_loan_collection/'.$market_id.'/'.$due_on);
            }
                        
        }
    }
	// Kendra wise bulk loan collection function end
    
    // Load kendra list in ajax based on a branch function start
	public function ajaxKendraListByMarket($id){
    		$data = $this->Kendra->find('list', array('fields' => array('id', 'kendra_name'),'conditions'=> array('Kendra.market_id'=>$id,'status'=>1)));
    		$this->set('kendraList', $data);	
            $this->set('id', $id);
    		$this->layout = 'ajax';
    }
    
    
    
    
    
    
    
    
    
    
     
     
     
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
	// Load kendra list in ajax based on a branch function start
	public function ajaxKendraList($id){
    		$data = $this->Kendra->find('list', array('fields' => array('id', 'kendra_name'),'conditions'=> array('Kendra.branch_id'=>$id,'status'=>1)));
    		$this->set('kendraList', $data);	
            $this->set('id', $id);
    		$this->layout = 'ajax';
    }
	// Load kendra list in ajax based on a branch function end
	
	// Load loan officer list in ajax based on a branch function start
     public function ajaxLoanOfficerList($branch_id){
            $data= $this->User->find('list',array('fields'=>array('id','first_name'),'conditions'=>array('User.user_type_id'=>5,'User.status'=>1,'User.branch_id'=>$branch_id )));
    		$this->set('loanOfficerList', $data);	
            $this->set('branch_id', $branch_id);
    		$this->layout = 'ajax';
    }   
	// Load loan officer list in ajax based on a branch function end
	
    // Load loan officer list in ajax based on a branch function start
     public function ajaxMarketList($branch_id){
            $data= $this->Market->find('list',array('fields'=>array('id','market_name'),'conditions'=>array('Market.status'=>1,'Market.branch_id'=>$branch_id )));
    		$this->set('marketList', $data);	
            $this->set('branch_id', $branch_id);
    		$this->layout = 'ajax';
    }   
	// Load loan officer list in ajax based on a branch function end
    
    
    // Create a Loan function start
    public function add($cust_id='') {
		$this->layout = 'panel_layout';
        $this->set('title', 'Create Loan');
        $org_data= $this->get_organization_settings_fees($this->Auth->user('organization_id'));
        $user_data= $this->User->find('all',array('conditions'=>array('User.id'=>$this->Auth->user('id'))));
        $branch_list= $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.organization_id'=>$this->Auth->user('organization_id'))));
        $cust_data= $this->Customer->find('first',array('conditions'=>array('Customer.id'=>$cust_id)));
        $count_loan = $this->Loan->find('count');
        $loan_no = 'L-'.$this->Auth->user('organization_id').'-'.$cust_id.'-'.($count_loan + 1);
        $this->set('org_data', $org_data);
        $this->set('user_data', $user_data);
        $this->set('branch_list', $branch_list);
        $this->set('cust_data', $cust_data);
        $this->set('loan_no', $loan_no);
        if ($this->request->is('post')) {
			$this->request->data['Loan']['created_on'] = date("Y-m-d H:i:s");
			$this->request->data['Loan']['loan_number'] = $loan_no;
            $this->request->data['Loan']['user_id'] = $this->Auth->user('id');
			$this->Loan->create();
			if ($this->Loan->save($this->request->data)) {
				$last_insert_loan=$this->Loan->getLastInsertId();
				$this->Session->setFlash(__('The Loan has been created'));
				$this->redirect('/customer_details/'.$cust_id);
			} else {
				$this->Session->setFlash(__('The Loan could not be created. Please, try again.'));
			}	
        }
    }
	// Create a Loan function end

    // Loan Details based on a particular loan function start
    public function details1($loan_id='') {
		$this->layout = 'panel_layout';
        $this->set('title', 'Loan details');
        if($loan_id==''){
           $this->redirect(array('action' => 'details/')); 
        }
        $loan_data= $this->Loan->find('first',array('conditions'=>array('Loan.id'=>$loan_id)));
        $loan_status= $this->LoanStatus->find('list',array('fields'=>array('id','status_name'),'conditions'=>array('LoanStatus.status'=>1,'LoanStatus.id >'=>$loan_data['Loan']['loan_status_id'])));
        $loan_overdue = $this->LoanTransaction->find('all',array('fields'=>array('SUM(LoanTransaction.total_installment) as total_overdue','COUNT(LoanTransaction.id) as overdue_no'),'conditions'=>array('LoanTransaction.loan_id'=>$loan_id,'LoanTransaction.insta_due_on <='=>date("Y-m-d"),'LoanTransaction.insta_principal_paid'=>0)));
        $loan_summary = $this->loan_summary($loan_id);
        $this->set('loan_overdue', $loan_overdue);
        $this->set('loan_data', $loan_data);
        $this->set('loan_status', $loan_status);
        $this->set('loan_summary', $loan_summary);
        if ($this->request->is('post')) {
            $trans = 0;
			$this->request->data['Loan']['modified_on'] = date("Y-m-d H:i:s",strtotime($this->request->data['insta_start']));
            if($this->request->data['Loan']['loan_status_id']==3){
                $this->request->data['Loan']['loan_dateout']=date("Y-m-d");
                $this->request->data['Loan']['modified_on'] = date("Y-m-d H:i:s");
                $this->request->data['Loan']['loan_repay_start']=$this->request->data['insta_start'];
                $this->request->data['Loan']['loan_issued'] = 1;
                $trans = 1;
            }
			if ($this->Loan->save($this->request->data)) {
                if($trans==1){ 
                    $this->create_loan_transaction($loan_id,$this->request->data['insta_start']);
                }
				$this->Session->setFlash('The Loan has been updated');
				$this->redirect(array('action' => 'details/'.$loan_id));
			} else {
				$this->Session->setFlash('The Loan could not be updated. Please, try again.');
			}	
        }
    }
	 // Loan Details based on a particular loan function end
    
	// Loan Details based on a particular kendra function start
    public function kendra_loan_details($kid='')
    {
		App::import('Helper', 'Number');
        $Number = new NumberHelper(new View(null));
        $this->layout = 'panel_layout';
        $this->set('title', 'Kendra Loan details');
        $show_val=0;
        $organization_id =$this->Auth->user('organization_id');
		$cust_data = array();
        $table_data = array();
        if ($this->request->is('post')) {
			$kid = $this->request->data['Loan']['kendra_id'];	
            $bid = $this->request->data['Loan']['branch_id'];		
        }else{
            $bid = $this->Auth->user('branch_id');
        }
        if($this->Auth->user('user_type_id')==2){
            $branch_list = $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.status'=>1, 'Branch.organization_id'=>$this->Auth->user('organization_id'))));
            $kendra_list = $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.status'=>1,'Kendra.branch_id'=>$bid)));
        }else{
            $branch_list = $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.status'=>1, 'Branch.id'=>$bid)));
            $kendra_list = $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.status'=>1,'Kendra.user_id'=>$this->Auth->user('id'),'Kendra.branch_id'=>$bid)));
        }
        $this->set('branch_list', $branch_list);
        $this->set('kendra_list', $kendra_list);
        if($kid!=''){
		$show_val=1;
		// The customer List Start
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
        $loan_data = $this->Loan->find('all',array('conditions'=>array('Loan.status'=>1,'Loan.loan_status_id'=>3 ,'Loan.kendra_id'=>$kid)));
 		// Find last updated date on database
		$max_date = $this->LoanTransaction->find('all',array(
				'fields'=>array(
					'MAX(LoanTransaction.insta_paid_on) as max_date'
				),
				'conditions'=>array(
					
					'LoanTransaction.insta_paid_on !='=> '0000-00-00',
					 'Loan.loan_status_id'=>3,
					 'Loan.organization_id'=>$this->Auth->user('organization_id')
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
        $final_date=$max_date[0][0]['max_date'];     // Last updated on database variable in date format
		if(!empty($loan_data)){
            foreach($loan_data as $k2=>$loan_row){
			     $loan_count = $this->Loan->find('first', 
                        array(
                            
                            'conditions' => array(
                                'Loan.loan_status_id'=>3,
                                'Loan.id'=>$loan_row['Loan']['id'],
                                ),
                            'recursive'=>-1
                        )
                        );
                $table_data[$k2][0] = $k2 + 1;
                $table_data[$k2][1] = $loan_row['Customer']['cust_fname'].' '.$loan_row['Customer']['cust_lname'];
                if(!empty($loan_count['Loan']['loan_principal'])){
					// Calculate loan overdue
                        
						// Calculate total number of instalment paid
                        $trans_id=$this->LoanTransaction->find("first", array('fields'=>array('max(LoanTransaction.id) as trans_id'),'conditions'=>array('LoanTransaction.loan_id'=>$loan_row['Loan']['id'],'LoanTransaction.insta_interest_paid >'=>0), "recursive"=> -1));
                        $trans_id_data = $this->LoanTransaction->findById($trans_id[0]['trans_id']);
                        //pr($trans_id_data);die;
                        $trans_paid_arr = $trans_id_data['LoanTransaction'];
        
                        
                        $table_data[$k2][2] = (!empty($loan_count['Loan']['loan_principal']))?$Number->currency($loan_count['Loan']['loan_principal'],'',array('places'=>0)):'No Active loan';
                        $table_data[$k2][3] = intval(($loan_count['Loan']['loan_repay_total']-$trans_paid_arr['current_outstanding'])/$loan_count['Loan']['loan_rate']);
                        $table_data[$k2][4] = $trans_paid_arr['insta_paid_on'];
                        $table_data[$k2][5] = round(($trans_paid_arr['overdue_principal']+$trans_paid_arr['overdue_interest'])/$loan_count['Loan']['loan_rate']);
                }else{
                        $table_data[$k2][2] = 'No Active loan';
                        $table_data[$k2][3] = '0';
                        $table_data[$k2][4] = '-';
                        $table_data[$k2][5] = '0';
                }
                
                $customer_link=$this->base.'/customer_edit/'. $loan_row['Customer']['id'];
				$edit_link='<a href="'. $customer_link .'"> Edit </a>';
				$customer_url= $this->base.'/customer_details/'.$loan_row['Customer']['id'];
				$customer_view_link='/customer_details/'. $loan_row['Customer']['id'];
				$view_link='<a target="_BLANK" href="'. $customer_url .'"> View </a>';
                $table_data[$k2][6] = $view_link;
                $table_data[$k2][7] = $edit_link;
            }
        }        
        $summary_data['table_val']=$this->prepare_json($table_data);
		$this->set('customers_data',$summary_data); 
		// The Customer List End
        $kendra_data = $this->kendra_details($kid); // Save all data of a  kendra
        $branch_id = $bid;
        $kendra_id = $kid;
        }else{
            $kendra_data = array();
            $loan_payment_list = array();
            $branch_id = $this->Auth->user('branch_id');
            $kendra_id = '';
        }
        $max_date = $this->LoanTransaction->find('all',array(
					'fields'=>array(
						'MAX(LoanTransaction.insta_paid_on) as max_date'
					),
					'conditions'=>array(
						
						'LoanTransaction.insta_paid_on !='=> '0000-00-00',
                        'Loan.kendra_id'=>$kid,
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
                $loan_overdue = $this->LoanTransaction->find('all',array(
					'fields'=>array(
						'SUM(LoanTransaction.total_installment) as realizable_amount',
                        '(SUM(LoanTransaction.insta_principal_paid)+SUM(LoanTransaction.insta_interest_paid)) as realized_amount',
                        'SUM(LoanTransaction.insta_principal_due) as principal_due',	
                        'SUM(LoanTransaction.insta_principal_paid) as principal_paid'						
					),
					'conditions'=>array(
						 'Loan.organization_id'=>$organization_id,
                         
                         'Loan.kendra_id'=>$kid,
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
		
		// Number of new application of loan
         $new_loan_application = $this->Loan->find('all', array(
                             'fields' => array(
								  'COUNT(Loan.id) as no_of_loan',
                                  'SUM(Loan.loan_principal) as total_loan_principal'  
							),
							'conditions' => array(
								  'Loan.organization_id'=>$organization_id,
								  'Loan.loan_status_id'=>1,
                                  'Loan.kendra_id'=>$kid,
							)
						));
		// Number of approved loan
        $approved_loan = $this->Loan->find('all', array(
                            'fields' => array(
								  'COUNT(Loan.id) as no_of_loan',
                                  'SUM(Loan.loan_principal) as total_loan_principal'  
							),
							'conditions' => array(
								  'Loan.organization_id'=>$organization_id,
								  'Loan.loan_status_id'=>2,
                                  'Loan.kendra_id'=>$kid,
							)
						));
		// Number of loan disbursed
		$disbursed_loan = $this->Loan->find('all', array(
                            'fields' => array(
								  'COUNT(Loan.id) as no_of_loan',
                                  'SUM(Loan.loan_principal) as total_loan_principal'  
							),
							'conditions' => array(
								  'Loan.organization_id'=>$organization_id,
								  'Loan.loan_status_id'=>3,
                                  'Loan.kendra_id'=>$kid,
							)
						));
		// Number of loan closed
		$closed_loan = $this->Loan->find('all', array(
                            'fields' => array(
								  'COUNT(Loan.id) as no_of_loan',
                                  'SUM(Loan.loan_principal) as total_loan_principal'  
							),
							'conditions' => array(
								  'Loan.organization_id'=>$organization_id,
								  'Loan.loan_status_id'=>6,
                                  'Loan.kendra_id'=>$kid,					  
							)
        ));
        $summary_data['realize_amt'] = $loan_overdue[0][0]['realized_amount'];
        $summary_data['realizable_amt'] = $loan_overdue[0][0]['realizable_amount'];
        $summary_data['total_loan_in_mkt'] = $loan_overdue[0][0]['principal_due']-$loan_overdue[0][0]['principal_paid'];
        $summary_data['new_loan_application'] = $new_loan_application[0][0];
        $summary_data['approved_loan'] = $approved_loan[0][0];
        $summary_data['disbursed_loan'] = $disbursed_loan[0][0];
        $summary_data['closed_loan'] = $closed_loan[0][0];
        $this->set('branch_id', $branch_id);
        $this->set('kendra_id', $kendra_id);
        $this->set('kendra_data', $kendra_data);
        $this->set('final_date', $final_date);
        $this->set('summary_data', $summary_data);
        $this->set('show_val', $show_val);
    }
    // Loan Details based on a particular kendra function end
    
    // Edit an user function start
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
	// Edit an user function end
	
	// Loan details of a branch function start
	public function branch_loan_details($branch_id='')
    {
        $this->layout = 'panel_layout';
        $this->set('title', 'Branch Loan details');
        if($branch_id==''){
           $this->redirect(array('action' => 'branch_loan_details/')); 
        }
        if($this->Auth->user('user_type_id')==2){
            $branch_list = $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.status'=>1, 'Branch.organization_id'=>$this->Auth->user('organization_id'))));
            $kendra_list = $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.status'=>1)));
        }else{
            $branch_list = $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.status'=>1, 'Branch.id'=>$this->Auth->user('branch_id'))));
            $kendra_list = $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.status'=>1,'Kendra.user_id'=>$this->Auth->user('id'))));
        }
        $this->set('branch_list', $branch_list);
        $this->set('kendra_list', $kendra_list);
        $branch_data = $this->branch_details($branch_id);
        $this->set('branch_data', $branch_data);
        $loan_payment_list = $this->LoanTransaction->find('all',array(
            'fields'=>array('LoanTransaction.insta_due_on','LoanTransaction.insta_paid_on','SUM(LoanTransaction.total_installment) as total_installment','SUM(LoanTransaction.insta_principal_paid) as total_principal_paid','SUM(LoanTransaction.insta_interest_paid) as total_interest_paid'),
            'conditions'=>array('Loan.branch_id'=>$branch_id,'Loan.loan_status_id'=>3,'Loan.status'=>1),
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
    }
	// Loan details of a branch function end
	
	// Loan Officer Wise Loan Collection function START
	public function loan_officer_details($user_id=''){
		$this->layout = 'panel_layout';
		$this->set('title', 'Loan Officer Details');
        if ($user_id!='') {
			$this->request->data['Loan']['user_id'] = $user_id;		
        }
        if($this->Auth->user('user_type_id')==2){
            $branch_list = $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.status'=>1, 'Branch.organization_id'=>$this->Auth->user('organization_id'))));
            $lo_list = array();
        }else{
            $branch_list = array();
            $lo_list= $this->User->find('list',array('fields'=>array('id','first_name'),'conditions'=>array('User.organization_id'=>$this->Auth->user('organization_id'), 'User.user_type_id'=>5,'User.status'=>1,'User.branch_id'=>$this->Auth->user('branch_id') )));
		    $this->set('lo_list', $lo_list);
        }
        if ($this->request->is('post')) {
            $branch_id = !empty($this->request->data['Loan']['branch_id'])?$this->request->data['Loan']['branch_id']:$this->Auth->user('branch_id');
			$user_id = $this->request->data['Loan']['user_id'];
            $lo_list= $this->User->find('list',array('fields'=>array('id','first_name'),'conditions'=>array('User.organization_id'=>$this->Auth->user('organization_id'), 'User.user_type_id'=>5,'User.status'=>1,'User.branch_id'=>$branch_id )));
        }
        $this->set('branch_list', $branch_list);            
        $this->set('lo_list', $lo_list);
        $max_date = $this->LoanTransaction->find('all',array(
			'fields'=>array(
				'MAX(LoanTransaction.insta_paid_on) as max_date'
			),
			'conditions'=>array(
				
				'LoanTransaction.insta_paid_on !='=> '0000-00-00',
				'Loan.user_id'=>$user_id,
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
			)),
		));
		$final_date=$max_date[0][0]['max_date'];
        
		$loanOfficerSummary=$this->app_loan_officer_details($user_id);
        $final_date = date('Y-m-d');
        $this->set('final_date', $final_date); 
		$this->set('loan_officer_summery', $loanOfficerSummary);
	}
	// Loan Officer Wise Loan Collection function END
	
	// Collection of a single instalment of a loan function START
    public function single_loan_collection($trans_id=''){
        $this->layout = 'collection_layout';
		$this->set('title', 'Single Loan Collection');
        $trans_data= $this->LoanTransaction->find('first',array('conditions'=>array('LoanTransaction.id'=>$trans_id)));
        $loan_id = $trans_data['LoanTransaction']['loan_id'];
        $loan_data= $this->Loan->find('first',array('conditions'=>array('Loan.id'=>$loan_id)));
        $loan_status= $this->LoanStatus->find('list',array('fields'=>array('id','status_name'),'conditions'=>array('LoanStatus.status'=>1)));
        $loan_overdue = $this->LoanTransaction->find('all',array('fields'=>array('SUM(LoanTransaction.total_installment) as total_overdue','COUNT(LoanTransaction.id) as overdue_no'),'conditions'=>array('LoanTransaction.loan_id'=>$loan_id,'LoanTransaction.insta_due_on <='=>date("Y-m-d"),'LoanTransaction.insta_principal_paid'=>0)));
        $loan_summary = $this->loan_summary($loan_id);
        $this->set('loan_overdue', $loan_overdue);
        $this->set('loan_data', $loan_data);
        $this->set('loan_status', $loan_status);
        $this->set('loan_summary', $loan_summary);
        $this->set('trans_data', $trans_data);
        if ($this->request->is('post')) {
            $kendra_id = $this->request->data['LoanTransaction']['kendra_id'];
            $due_on = $this->request->data['LoanTransaction']['insta_due_on'];
            $insta_no = $this->request->data['LoanTransaction']['insta_no'];
            $cust_arr = $this->request->data['cust_arr'];
            $this->loan_amount_collection($kendra_id,$due_on,$insta_no,$cust_arr);
            $this->redirect('/loan_details/'.$loan_summary['loan_id']);
        }
    }
    // Collection of a single instalment of a loan function END
	
	// Kendra wise loan collection list function start
    public function kendra_loan_collection($kendra_id='',$due_date){
        $this->layout = 'collection_layout';
		$this->set('title', 'Kendra Loan Collection');
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
        unset($kendra_data['Customer']);
        unset($kendra_data['Loan']);
        $kendra_data['Transaction'] = $loan_payment_data;
        $this->set('kendra_data', $kendra_data);
        if ($this->request->is('post')) {
            $kendra_id = $this->request->data['LoanTransaction']['kendra_id'];
            $due_on = $this->request->data['LoanTransaction']['insta_due_on']; // Date of instalment
            $insta_no = $this->request->data['LoanTransaction']['insta_no']; 
            $cust_arr = $this->request->data['cust_arr'];  // List of customers who paid instalment
            $this->loan_amount_collection($kendra_id,$due_on,$insta_no,$cust_arr);
            $this->redirect('/kendra_loan_details/'.$kendra_id);
        }
    }
	// Kendra wise loan collection list function end
	
	// Kendra wise bulk loan collection function start
    public function bulk_loan_collection(){
        $this->layout = 'panel_layout';
		$this->set('title', 'Bulk Loan Collection');
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
            $kendra_id = $this->request->data['Loan']['kendra_id'];
            $due_on = date("Y-m-d",strtotime($this->request->data['Loan']['insta_due_on']));
            $this->redirect('/kendra_loan_collection/'.$kendra_id.'/'.$due_on);
        }
    }
	// Kendra wise bulk loan collection function end
	
	// Load due date list of a kendra in ajax function start 
    public function ajax_duedate_list($kid)
    {
        $this->layout = 'ajax';
        $loan_payment_list = $this->LoanTransaction->find('list',array(
            'fields'=>array('LoanTransaction.insta_due_on','LoanTransaction.insta_due_on'),
            'conditions'=>array('Loan.kendra_id'=>$kid,'Loan.loan_status_id'=>3,'Loan.status'=>1,'LoanTransaction.insta_principal_paid'=>0),
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
    }
    // Load due date list of a kendra in ajax function end
	
	// Loan prepayment function start
    public function loan_prepayment(){
        $this->layout = 'panel_layout';
		$this->set('title', 'Loan Prepayment');
        if($this->Auth->user('user_type_id')==2){
            $market_list = $this->Market->find('list',array('fields'=>array('id','market_name'),'conditions'=>array('Market.organization_id'=>$this->Auth->user('organization_id'))));
        }
        if($this->Auth->user('user_type_id')==5){
            $market_list = $this->Market->find('list',array('fields'=>array('id','market_name'),'conditions'=>array('Market.user_id'=>$this->Auth->user('id'))));
        }
        $this->set('market_list',$market_list);
        if ($this->request->is('post')) {
            $loan_id = $this->request->data['loan_id'];
            $kendra_id = $this->request->data['Loan']['kendra_id'];
            $payment_on = date("Y-m-d",strtotime($this->request->data['payment_date']));
            $cust_id = $this->request->data['Loan']['customer_id'];
            $loan_trans_list = $this->LoanTransaction->find('all',array(
            'conditions'=>array('Loan.id'=>$loan_id,'LoanTransaction.loan_id'=>$loan_id,'LoanTransaction.insta_principal_paid'=>0),
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
            foreach($loan_trans_list as $trans_row){
                $cust_arr[$cust_id] = $trans_row['LoanTransaction']['total_installment'];
                $due_date = $trans_row['LoanTransaction']['insta_due_on'];
                $insta_no = $trans_row['LoanTransaction']['insta_no'];
                $this->loan_amount_collection($kendra_id,$due_date,$insta_no,$cust_arr);
            }
            $this->redirect('/loan_prepayment/');
        }
    }
	// Loan prepayment function end
	
	// Load customer names in list of a kendra via ajax function start
    public function ajax_customer_list($kid='')
    {
        $this->layout = 'ajax';
		// Creating a virtual field of full name
        $this->Customer->virtualFields = array(
                'full_name' => "CONCAT(Customer.cust_fname, ' ', Customer.cust_lname)"
            );
        $loan_cust_list = $this->Customer->find('list',array(
            'fields'=>array('Customer.id','Customer.full_name'),
            'conditions'=>array('Customer.market_id'=>$kid,'Customer.status'=>1)
            ));
        $this->set('loan_cust_list', $loan_cust_list);
    }
	// Load customer names in list of a kendra via ajax function end
	
	// Load prepayment amount of a customer in ajax function start
    public function ajax_prepayment_amount($cid)
    {
		App::import('Helper', 'Number');
        $Number = new NumberHelper(new View(null));
        $this->layout = 'ajax';
        $this->autoRender = false;
        $res_data = array();
        $loan_data = $this->Loan->find('first',array(
            'conditions'=>array('Loan.customer_id'=>$cid,'Loan.loan_status_id'=>3,'Loan.status'=>1)
            ));
        if(!empty($loan_data)){
            $lsummery = $this->loan_summary($loan_data['Loan']['id']);
            $res_data = $lsummery;
            $res_data['cust_name'] = $loan_data['Customer']['cust_fname'].' '.$loan_data['Customer']['cust_lname'];
			$res_data['total_realized']= $Number ->currency($lsummery['total_realized'], '',array('places'=>0));
			$res_data['total_overdue']= $Number ->currency($lsummery['total_overdue'], '',array('places'=>0));
            $res_data['loan_principal'] =$Number ->currency($loan_data['Loan']['loan_principal'], '',array('places'=>0));
            $res_data['repay_total'] = $Number ->currency($loan_data['Loan']['loan_repay_total'], '',array('places'=>0));
            $res_data['prepayment_amount'] = $Number ->currency($loan_data['Loan']['loan_repay_total']-$lsummery['total_realized'], '',array('places'=>0));
        }else{
            $res_data['loan_id'] = 0;
            $res_data['total_overdue'] = 0;
            $res_data['overdue_no'] = 0;
            $res_data['last_paid_date'] = 0;
            $res_data['total_installment_no'] = 0;
            $res_data['paid_installment'] = 0;
            $res_data['installment_amount'] = 0;
            $res_data['loan_due_balance'] = 0;
            $res_data['loan_principal'] = 0;
            $res_data['paid_amount'] = 0;
            $res_data['loan_dateout'] = 0;
            $res_data['loan_date'] = 0;
            $res_data['loan_number'] =0;
            $res_data['loan_purpose'] = 0;
            $res_data['loan_interest'] =0;
            $res_data['loan_repay_total'] = 0;
            $res_data['currency'] = 0;
            $res_data['loan_period_unit'] = 0;
            $res_data['loan_type'] = 0;
            $res_data['total_realiable'] = 0;
            $res_data['total_realized'] = 0;
            $res_data['percentage_paid'] = 0;
            $res_data['cust_name'] = 0;
            $res_data['repay_total'] = 0;
            $res_data['prepayment_amount'] = 0;
        }
        echo json_encode($res_data); // Send data in json format
    }
	// Load prepayment amount of a customer in ajax function end
    
	// Loan overdue payment function start
    public function loan_overdue_payment(){
        $this->layout = 'panel_layout';
		$this->set('title', 'Loan Overdue Payment');
        if($this->Auth->user('user_type_id')==2){
            $kendra_list = $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.organization_id'=>$this->Auth->user('organization_id'))));
        }
        if($this->Auth->user('user_type_id')==5){
            $kendra_list = $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.user_id'=>$this->Auth->user('id'))));
        }
        $this->set('kendra_list',$kendra_list);     
        if ($this->request->is('post')) {
            $loan_id = $this->request->data['loan_id'];
            $kendra_id = $this->request->data['Loan']['kendra_id'];
            $payment_on = date("Y-m-d",strtotime($this->request->data['payment_date']));
            $cust_id = $this->request->data['Loan']['customer_id'];
            $loan_overdue_list = $this->LoanTransaction->find('all',array('conditions'=>array('LoanTransaction.loan_id'=>$loan_id,'LoanTransaction.insta_due_on <='=>date("Y-m-d"), 'LoanTransaction.insta_principal_paid'=> 0)));
            foreach($loan_overdue_list as $trans_row){
                $cust_arr[$cust_id] = $trans_row['LoanTransaction']['total_installment'];
                $due_date = $trans_row['LoanTransaction']['insta_due_on'];
                $insta_no = $trans_row['LoanTransaction']['insta_no'];
                $this->loan_amount_collection($kendra_id,$due_date,$insta_no,$cust_arr);
            }
            $this->redirect('/loan_overdue_payment/');
        }
    }
	// Loan overdue payment function end
    
	// Bulk Loan release function start
    public function bulk_loan_release1(){ // This is the old function. Updated function in the top
        $this->layout = 'panel_layout';
		$this->set('title', 'Bulk Loan Release');
        if ($this->request->is('post')) {
			$kid = $this->request->data['Loan']['kendra_id'];	
            $bid = $this->request->data['Loan']['branch_id'];		
        }else{
            $bid = $this->Auth->user('branch_id');
        }
        if($this->Auth->user('user_type_id')==2){
            $branch_list = $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.status'=>1, 'Branch.organization_id'=>$this->Auth->user('organization_id'))));
            $kendra_list = $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.status'=>1,'Kendra.branch_id'=>$bid)));
        }else{
            $branch_list = $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.status'=>1, 'Branch.id'=>$bid)));
            $kendra_list = $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.status'=>1,'Kendra.user_id'=>$this->Auth->user('id'),'Kendra.branch_id'=>$bid)));
        }
        $this->set('branch_list', $branch_list);
        $this->set('kendra_list', $kendra_list);
        if ($this->request->is('post')) {
            $kendra_id = $this->request->data['Loan']['kendra_id'];
            $this->redirect('/bulk_release_details/'.$kendra_id);
        }
    }
	// Bulk Loan release function end
	
	// Bulk Loan release Details function start
    public function bulk_release_details($kid=''){
        $this->layout = 'collection_layout';
		$this->set('title', 'Bulk Release');
        $loan_data= $this->Loan->find('all',array('conditions'=>array('Loan.kendra_id'=>$kid,'Loan.loan_status_id'=>1,'Loan.status'=>1)));
        $this->set('loan_data',$loan_data);
        $kendra_data = $this->kendra_details($kid);
        unset($kendra_data['Customer']);
        unset($kendra_data['Loan']);
        $this->set('kendra_data', $kendra_data);
        $loan_status= $this->LoanStatus->find('list',array('fields'=>array('id','status_name'),'conditions'=>array('LoanStatus.status'=>1)));
        $this->set('loan_status', $loan_status);
        if ($this->request->is('post')) {
            if(isset($this->request->data['loan_arr']) && !empty($this->request->data['loan_arr'])){
                $loanarr = $this->request->data['loan_arr'];
                foreach($loanarr as $loan_id => $principal){
                    $trans = 0;
                    $this->request->data['Loan']['id'] = $loan_id;
                    $this->request->data['Loan']['modified_on'] = date("Y-m-d H:i:s");
                    if($this->request->data['Loan']['loan_status_id']==3){
                        $this->request->data['Loan']['loan_dateout']=date("Y-m-d");
                        $this->request->data['Loan']['loan_repay_start']=$this->request->data['insta_start'];
                        $this->request->data['Loan']['loan_issued'] = 1;
                        $trans = 1;
                    }
        			$this->Loan->clear();
        			if ($this->Loan->save($this->request->data)) {
                        if($trans==1){ 
                            $this->create_loan_transaction($loan_id,$this->request->data['insta_start']);
                        }
        			} else {
        				$this->Session->setFlash('The Loan could not be released. Please, try again.');
        			}
                } // Foreach end
                $this->Session->setFlash('The Loan has been Released');
		          $this->redirect(array('action' => 'bulk_loan_release/'));
            } // loan_arr if end
        } // Post if end
    } // function end
    // Bulk Loan release Details function end
	
	// Security deposit return of a customer function start
    public function security_deposite_return($mid='',$cid='')
    {
        $this->layout = 'panel_layout';
		$this->set('title', 'Security Deposite Return');
        $this->set('market_id', $mid);
        $this->set('customer_id', $cid);
        if($this->Auth->user('user_type_id')==2){
            $market_list = $this->Market->find('list',array('fields'=>array('id','market_name'),'conditions'=>array('Market.organization_id'=>$this->Auth->user('organization_id'))));
            if($mid=='')
                $loans_list = $this->Loan->find('all',array('conditions'=>array('Loan.loan_status_id'=>6,'Loan.is_security_fee_returned'=>0,'Loan.organization_id'=>$this->Auth->user('organization_id'))));
            else
                $loans_list = $this->Loan->find('all',array('conditions'=>array('Loan.loan_status_id'=>6,'Loan.is_security_fee_returned'=>0,'Loan.market_id'=>$mid)));
        }
        if($this->Auth->user('user_type_id')==5){
            $market_list = $this->Market->find('list',array('fields'=>array('id','market_name'),'conditions'=>array('Market.user_id'=>$this->Auth->user('id'))));
            if($mid=='')
                $loans_list = $this->Loan->find('all',array('conditions'=>array('Loan.loan_status_id'=>6,'Loan.is_security_fee_returned'=>0,'Loan.user_id'=>$this->Auth->user('id'))));
            else
                $loans_list = $this->Loan->find('all',array('conditions'=>array('Loan.loan_status_id'=>6,'Loan.is_security_fee_returned'=>0,'Loan.market_id'=>$mid)));
        }
        $this->set('market_list',$market_list);
        $cust_list = array();
        $fees_arr = array();
        foreach($loans_list as $loan_row){
            $cust_list[$loan_row['Customer']['id']] = $loan_row['Customer']['cust_fname'].' '.$loan_row['Customer']['cust_lname'];
            $fees_arr [$loan_row['Customer']['id']] = $loan_row['Loan']['security_fee'];
        }
        $this->set('cust_list',$cust_list);
        $this->set('fees_arr',$fees_arr);
        if ($this->request->is('post')) {
            $this->Loan->id = $this->request->data['Loan']['loan_id'];
            $this->Loan->saveField('is_security_fee_returned',1);
            
            // insert in income Expenditure
           $loan_data = $this->Loan->findById($this->request->data['Loan']['loan_id']); 
           $exp_data['IncomeExpenditure']['account_ledger_id']=4;
           $exp_data['IncomeExpenditure']['account_id']=$loan_data['Loan']['account_id'];
    	   $exp_data['IncomeExpenditure']['debit_amount']=$loan_data['Loan']['security_fee'];
    	   $exp_data['IncomeExpenditure']['transaction_date']=date("Y-m-d");
    	   $exp_data['IncomeExpenditure']['balance']=$loan_data['Loan']['security_fee'];
    	   $exp_data['IncomeExpenditure']['organization_id']=$loan_data['Loan']['organization_id'];
    	   $exp_data['IncomeExpenditure']['region_id']=$loan_data['Loan']['region_id'];
    	   $exp_data['IncomeExpenditure']['branch_id']=$loan_data['Loan']['branch_id'];
    	   $exp_data['IncomeExpenditure']['market_id']=$loan_data['Loan']['market_id'];
    	   $exp_data['IncomeExpenditure']['created_on']=date("Y-m-d H:i:s");
    	   $exp_data['IncomeExpenditure']['user_id']=$loan_data['Loan']['user_id'];
           $this->IncomeExpenditure->clear();
    	   $this->IncomeExpenditure->save($exp_data);
            
            
            $this->Session->setFlash('Security fees refunded');
            $this->redirect('/security_deposite_return/');
        }
    }
	// Security deposit return of a customer function end
	
	// Security Fee customer list load in ajax function start
    public function ajax_security_fee_customer_list($mid=0){
        $this->layout = 'ajax';
        $loans_list = $this->Loan->find('all',array('conditions'=>array('Loan.loan_status_id'=>6,'Loan.is_security_fee_returned'=>0,'Loan.market_id'=>$mid)));
        //pr($loans_list);die;
        $loan_cust_list = array();
        foreach($loans_list as $loan_row){
            $loan_cust_list[$loan_row['Customer']['id']] = $loan_row['Customer']['cust_fname'].' '.$loan_row['Customer']['cust_lname'];
        }
        $this->set('loan_cust_list', $loan_cust_list);
    }
	// Security Fee customer list load in ajax function start
    
	
	// Security Fee amount load in ajax function start 
    public function ajax_security_fee_amount($cid=0){
        $this->layout = 'ajax';
        $this->autoRender = false;
        $res_arr=array('loan_id'=>0,"fees"=>0);
        if($cid>0){
            $loans = $this->Loan->find('first',array('conditions'=>array('Loan.loan_status_id'=>6,'Loan.is_security_fee_returned'=>0,'Loan.customer_id'=>$cid)));
            if($loans['Loan']['security_fee']>0){
                $res_arr['fees'] = $loans['Loan']['security_fee'];
                $res_arr['loan_id'] = $loans['Loan']['id'];
            }
        }
        echo json_encode($res_arr); // return in json file format
    }
	// Security Fee amount load in ajax function start
}
// End of Loan controller
?>