<?php
// This is organization controller. All the function related with Organization is written here.
App::uses('CakeEmail', 'Network/Email');
class OrganizationsController extends AppController {
	// List of models which are used in the organization controller 
	var $uses = array('User','Organization','Region','Branch','Kendra','Customer','Loan','Saving','Idproof','LogRecord','Country', 'Setting', 'Fee','AccountLedger','AccountLedgerGroup');
	
	// This is a blank index function for safe navigation
    public function index() {
        
    }
    
    
    
	// Edit an organization function start
    public function edit($orgid=''){
		$this->layout = 'panel_layout';
		$this->set('title', 'Update Organization');
		$orgid=$this->Auth->user('organization_id');
		$this->Organization->unBindModel(array(
		      'belongsTo'=> array('Country'),
		      'hasMany' => array('Region', 'Branch', 'Kendra', 'Customer', 'Fee', 'Setting', 'Loan', 'User'),
		));
        $org_data = $this->Organization->findById($orgid);
        $this->set('org_data', $org_data);
        if ($this->request->is(array('post', 'put'))) {
            $this->request->data['Organization']['modified_on'] = date("Y-m-d");
            $this->Organization->id = $orgid;
            if ($this->Organization->save($this->request->data)) {
                $this->Session->setFlash(__('Your Organization has been updated.'));
                return $this->redirect('/organization_edit/'.$orgid);
            }
            $this->Flash->error(__('Unable to update your Organization.'));
        }
        if (!$this->request->data) {
            $this->request->data = $org_data;
        }
	}
	// Edit an organization function end
    
    public function daily_income_expenditure(){
        $this->layout = 'panel_layout';
        $this->set('title', 'Daily Income Expenditure');
        $bm_list = $this->Branch->find('list', array('fields' => array('id','branch_name'), 'conditions' => array('Branch.organization_id' => $this->Auth->user('organization_id'),'Branch.status'=>1)));
        $this->set('bm_list', $bm_list);
        
        
        if ($this->request->is(array('post', 'put'))) {
            //pr($this->request->data);die;
            $branch_data = $this->Branch->findById($this->request->data['branch_id']);
            
            $data_arr['IncomeExpenditure']['account_ledger_id']=$this->request->data['account_ledger_id'];
            $this->AccountLedger->bindModel(array(
        			'belongsTo' => array(
        				'AccountLedgerGroup' => array(
        					'foreignKey' => 'account_ledger_group_id',
        					'conditions' => array('AccountLedgerGroup.id = AccountLedger.account_ledger_group_id')
        				)
        			)
        		));
            $ledger_data = $this->AccountLedger->findById($this->request->data['account_ledger_id'],array('AccountLedgerGroup.id'));
            //pr($ledger_data);die;
            $data_arr['IncomeExpenditure']['account_ledger_group_id'] = $ledger_data['AccountLedgerGroup']['id'];
            
            if($this->request->data['amount_type']==0){
                $data_arr['IncomeExpenditure']['debit_amount']=$this->request->data['amount'];
            }else{
                $data_arr['IncomeExpenditure']['credit_amount']=$this->request->data['amount'];
            }
            
            $data_arr['IncomeExpenditure']['transaction_date']=date("Y-m-d",strtotime($this->request->data['date']));
            $data_arr['IncomeExpenditure']['organization_id']=$branch_data['Branch']['organization_id'];
            $data_arr['IncomeExpenditure']['region_id']=$branch_data['Branch']['region_id'];
            $data_arr['IncomeExpenditure']['branch_id']=$this->request->data['branch_id'];
            $data_arr['IncomeExpenditure']['user_id']=($this->request->data['user_id'])?$this->request->data['user_id']:$this->Auth->user('id');
            $data_arr['IncomeExpenditure']['created_on']=date("Y-m-d H:i:s");
            
            $this->IncomeExpenditure->save($data_arr);
            $this->redirect('/organizations/daily_income_expenditure/');
        }
        
    }
    
    public function ajax_ledger_list($typ_id){
        $this->layout='ajax';
        $ldgr_list = $this->AccountLedger->find('list', array('fields' => array('id','ledger_name'), 'conditions' => array('AccountLedger.account_type'=>$typ_id,'AccountLedger.organization_id' => $this->Auth->user('organization_id'),'AccountLedger.custom_entry'=>1,'AccountLedger.status'=>1)));
        $this->set('ldgr_list', $ldgr_list);
    }
    public function ajax_credit_officer_list($brnch_id){
        $this->layout='ajax';
        
        $this->User->virtualFields['full_name'] = "CONCAT(User.first_name, ' ', User.last_name)";
        $usr_list = $this->User->find('list', array('fields' => array('id','full_name'), 'conditions' => array('User.branch_id'=>$brnch_id,'User.organization_id' => $this->Auth->user('organization_id'),'User.user_type_id'=>5,'User.status'=>1)));
        $this->set('usr_list', $usr_list);
    }
    
    public function income_expense_name_list (){
        $this->layout = 'panel_layout';
        $this->set('title', 'Income and Expenditure Name List');
        $this->AccountLedger->bindModel(array(
        			'belongsTo' => array(
        				'AccountLedgerGroup' => array(
        					'foreignKey' => 'account_ledger_group_id',
        					'type'=>'LEFT',
        					'conditions' => array('AccountLedgerGroup.id = AccountLedger.account_ledger_group_id')
        				)
        			)
        		));
        $ldgr_list = $this->AccountLedger->find('all', array('conditions' => array('AccountLedger.organization_id' => $this->Auth->user('organization_id'))));
        //pr($ldgr_list);die;
        $this->set('ldgr_list', $ldgr_list);
    }
    public function save_ledger($id=''){
        $this->layout = 'panel_layout';
        $this->set('title', 'Save Ledger Name');
        $this->set('ldgr_id', $id);
        $ledger_groups = $this->AccountLedgerGroup->find('list',array('fields'=>array('id','ledger_group_name'),'conditions'=>array('AccountLedgerGroup.status'=>1)));
        $this->set('ledger_groups',$ledger_groups);
        if ($this->request->is(array('post', 'put'))) {
            //pr($this->request->data);die;
            $this->request->data['AccountLedger']['organization_id'] = $this->Auth->user('organization_id');
            
            
            $this->AccountLedger->save($this->request->data);
            $this->Session->setFlash(__('The Information has been updated'));
            $this->redirect('/organizations/save_ledger/'.$this->request->data['AccountLedger']['id']);
        }

        if($id!=''){
            $ldgr_data = $this->AccountLedger->find('first', array('conditions' => array('AccountLedger.id' => $id,'AccountLedger.organization_id' => $this->Auth->user('organization_id'))));
            $this->request->data = $ldgr_data;
        }
    }
    
    
}
// END organization controller
?>