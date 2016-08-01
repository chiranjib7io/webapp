<?php
// This is organization controller. All the function related with Organization is written here.
App::uses('CakeEmail', 'Network/Email');
class ReportsController extends AppController {
	// List of models which are used in the organization controller 
	var $uses = array('User','Organization','Region','Branch','Kendra','Customer','Loan','Saving','Idproof','LogRecord','Country', 'Setting', 'Fee','AccountLedger');
	
	// This is a blank index function for safe navigation
    public function index() {
        
    }
    
    public function receipt_payment_report(){
        $this->layout = 'panel_layout';
        $this->set('title', 'Receipt Payment Report');
        
        $start_date=date("Y-m-d", strtotime("-7 days"));
		// Calculate last updated date in the database
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
		$final_date=($max_date[0][0]['max_date'])?$max_date[0][0]['max_date']:date("Y-m-d");
		$this->set('update_on',$final_date);
		$start_date=date("d-m-Y",strtotime("$final_date -7 days"));
		$this->set('option_val', 1);
        
		$end_date=date("Y-m-d", strtotime($final_date));
		$send_date['start_date']=$start_date;
		$send_date['end_date']=$end_date;
        $this->set('send_date', $send_date);
        
        $exp_ldgr_list = $this->AccountLedger->find('list', array('fields' => array('id','ledger_name'), 'conditions' => array('AccountLedger.account_type'=>0,'AccountLedger.organization_id' => $this->Auth->user('organization_id'),'AccountLedger.status'=>1)));
        $income_ldgr_list = $this->AccountLedger->find('list', array('fields' => array('id','ledger_name'), 'conditions' => array('AccountLedger.account_type'=>1,'AccountLedger.organization_id' => $this->Auth->user('organization_id'),'AccountLedger.status'=>1)));
        $this->set('exp_list', $exp_ldgr_list);
        
        $this->set('org_id', $this->Auth->user('organization_id'));
        
        if ($this->request->is('post')) {
            
            //pr($this->request->data);die;
            //*********************** Date Calculation start ***************************//
            $option_val=$this->request->data['selectdate'];
            $this->set('option_val', $option_val);
			if($option_val==1){
				
				$end_date= $this->request->data['datefilter3'];
                $start_date=date("d-m-Y",strtotime("$end_date -7 days"));
				//$option_name='Daily';
                $option_name='Weekly';
			}
			if($option_val==3){
                $daterange=explode('-', $this->request->data['datefilter']);
				$start_date=$daterange[1].'-'.$daterange[0].'-01';
                if(date("m-Y")==$daterange[0].'-'.$daterange[1]){
                    $end_date = date("Y-m-d");
                }else{
                    $max_day = cal_days_in_month(CAL_GREGORIAN, $daterange[0], $daterange[1]);
                    $end_date = $daterange[1].'-'.$daterange[0].'-'.$max_day;
                }
				$option_name='Monthly';
			}
			if($option_val==4){
				$daterange=explode('-', $this->request->data['datefilter2']);
				$end_date=$daterange[1];
				//$option_name='Yearly';
                $option_name='Monthly';
                $income_ldgr_list = $this->AccountLedger->find('list', array('fields' => array('id','ledger_name'), 'conditions' => array('AccountLedger.account_type'=>1,'AccountLedger.organization_id' => $this->Auth->user('organization_id'),'AccountLedger.status'=>1,'AccountLedger.id !='=>16)));
			} 
		 
            $send_date['date_diff']= $this->date_differ($start_date,$end_date);
            $send_date['option_val']=$option_val;
            $send_date['option_name']=$option_name;
            $send_date['start_date']=$start_date;
            $send_date['end_date']=$end_date;
            $send_date['post_data']=$this->request->data;
            $this->set('send_date', $send_date);
            
            //*********************** Date Calculation End ***************************//
        }
        $this->set('income_list', $income_ldgr_list);
    }
    
    public function co_receipt_payment_report($start_date=''){
        $this->layout = 'panel_layout';
        $this->set('title', 'Credit officer wise Receipt Payment Report');
        if($start_date=='')
            $start_date=date("Y-m-d");
		// Calculate last updated date in the database
		
		$send_date['start_date']=$start_date;
        $this->set('send_date', $send_date);
        
        $exp_ldgr_list = $this->AccountLedger->find('list', array('fields' => array('id','ledger_name'), 'conditions' => array('AccountLedger.account_type'=>0,'AccountLedger.organization_id' => $this->Auth->user('organization_id'),'AccountLedger.status'=>1)));
        $income_ldgr_list = $this->AccountLedger->find('list', array('fields' => array('id','ledger_name'), 'conditions' => array('AccountLedger.account_type'=>1,'AccountLedger.organization_id' => $this->Auth->user('organization_id'),'AccountLedger.id !='=>16,'AccountLedger.status'=>1)));
        $co_list = $this->User->find('all', array('conditions' => array('User.user_type_id'=>5,'User.organization_id' => $this->Auth->user('organization_id'),'User.status'=>1)));
        $this->set('exp_list', $exp_ldgr_list);
        $this->set('income_list', $income_ldgr_list);
        $this->set('co_list', $co_list);
        $this->set('org_id', $this->Auth->user('organization_id'));
        
        if ($this->request->is('post')) {
            
            //pr($this->request->data);die;
            //*********************** Date start ***************************//
            $start_date= $this->request->data['datefilter'];
            $send_date['start_date']=$start_date;
            $this->set('send_date', $send_date);
            //*********************** Date End ***************************//
        }
        
    }
    
    public function co_general_report($user_id=''){
        $this->layout = 'panel_layout';
        $this->set('title', 'Credit officer Report');
        if ($user_id!='') {
			$this->request->data['Loan']['user_id'] = $user_id;		
        }
        $branch_list = $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.status'=>1, 'Branch.organization_id'=>$this->Auth->user('organization_id'))));
        $lo_list = array();
        
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
        
		$loanOfficerSummary=$this->credit_officer_general_report($user_id);
        $final_date = (!empty($final_date))?$final_date:date('Y-m-d');
        $this->set('final_date', $final_date); 
		$this->set('loan_officer_summery', $loanOfficerSummary);
    }
    
    // Load loan officer list in ajax based on a branch function start
     public function ajaxLoanOfficerList($branch_id){
            $data= $this->User->find('list',array('fields'=>array('id','first_name'),'conditions'=>array('User.user_type_id'=>5,'User.status'=>1,'User.branch_id'=>$branch_id )));
    		$this->set('loanOfficerList', $data);	
            $this->set('branch_id', $branch_id);
    		$this->layout = 'ajax';
    }   
	// Load loan officer list in ajax based on a branch function end
    
    public function profit_loss_report($start_date=''){
        $this->layout = 'panel_layout';
        $this->set('title', 'Profit and Loss Report');
        if($start_date=='')
            $start_date=date("m-Y");
		// Calculate last updated date in the database
		
		$send_date['start_date']=$start_date;
        $this->set('send_date', $send_date);
        
        $exp_ldgr_list = $this->AccountLedger->find('list', array('fields' => array('id','ledger_name'), 'conditions' => array('AccountLedger.account_type'=>0,'AccountLedger.organization_id' => $this->Auth->user('organization_id'),'AccountLedger.status'=>1,'AccountLedger.is_pl_report'=>1)));
        $income_ldgr_list = $this->AccountLedger->find('list', array('fields' => array('id','ledger_name'), 'conditions' => array('AccountLedger.account_type'=>1,'AccountLedger.organization_id' => $this->Auth->user('organization_id'),'AccountLedger.is_pl_report'=>1,'AccountLedger.status'=>1)));
        
        $this->set('exp_list', $exp_ldgr_list);
        $this->set('income_list', $income_ldgr_list);
        $this->set('org_id', $this->Auth->user('organization_id'));
        
        if ($this->request->is('post')) {
            
            //pr($this->request->data);die;
            //*********************** Date start ***************************//
            $start_date= $this->request->data['datefilter'];
            $send_date['start_date']=$start_date;
            $this->set('send_date', $send_date);
            //*********************** Date End ***************************//
        }
    }
    
     //maturity repport
    public function maturity_report()
    {
        $this->layout="panel_layout";
        if ($this->request->is('post'))
        {
         
         $startdate= $this->data['fdate'];
         $enddate=date("Y-m-d",strtotime("$startdate +7 days"));
         
    $loan_data= $this->Loan->find('all',array("conditions"=>array("Loan.user_id"=>$this->Auth->user('id'),"Loan.loan_status_id"=>3,"Loan.maturity_date >="=>$startdate,"Loan.maturity_date <="=>$enddate)));
     $loan_data=$this->set("loan_data",$loan_data);      
        }
        
    }
     //end maturity report
}
// END organization controller
?>