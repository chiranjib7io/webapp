<?php
// This is setting controller. This controller will set the organizational setting and edit.
App::uses('CakeEmail', 'Network/Email');
class SettingsController extends AppController {
	// List of models which are used in the setting controller 
	var $uses = array('User','Organization','Region','Branch','Kendra','Customer','Loan','Saving','Idproof','LogRecord','Country', 'Setting', 'Fee');
	
	// This is a blank index function for safe navigation
    public function index() {
		
    }
	
	// Set all the loan fees function start
	public function fees() {
		$this->set('title', 'Loan Details Fees');  // This is used for Title for every page
		$this->layout = 'panel_layout';
		$loan_setting_data= $this->Setting->find('all',array('conditions'=>array('Setting.organization_id'=>$this->Auth->user('organization_id'))));
		$this->set('loan_setting_data', $loan_setting_data);
		$loan_fees_data= $this->Fee->find('all',array('conditions'=>array('Fee.organization_id'=>$this->Auth->user('organization_id'))));
		$this->set('loan_fees_data', $loan_fees_data);
		if ($this->request->is('post')) {
			// Setting Controller Data Save
			$settingdata=$this->request->data['Setting'];
			 foreach ($settingdata as $keys=> $vals){
				$datas['Setting']['id'] =$keys;
				$datas['Setting']['set_value'] = $vals;
				$this->Setting->clear();
				$this->Setting->save($datas);
			}
			// Fees Controller Data Save
			$feedata=$this->request->data['Fee'];
			foreach ($feedata as $keyf=> $valf){
				$dataf['Fee']['id'] =$keyf;
				$dataf['Fee']['fee_value'] = $valf;
				$this->Fee->clear();
				$this->Fee->save($dataf);
			}
			// Redirect After Save the Data
			 $this->redirect(array('action' => 'fees'));
		}
		// Set all the loan fees function end
    }

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
    
	// Set all collection value function start
	public function collection_settings() {
		$this->set('title', 'Collection Settings');  // This is used for Title for every page
		$this->layout = 'panel_layout';
        $branch_list = $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.status'=>1, 'Branch.organization_id'=>$this->Auth->user('organization_id'))));
        $kendra_list = array();
        $this->set('branch_list', $branch_list);
        $this->set('kendra_list', $kendra_list);
        if ($this->request->is('post')) {
           $kid = $this->request->data['kendra_id'];
           $change_date = $this->request->data['payment_change_date'];           
           $loan_data = $this->Loan->find('all',array(
            'conditions'=>array('Loan.kendra_id'=>$kid,'Loan.loan_status_id'=>3,'Loan.status'=>1),
           ));
            foreach($loan_data as $loan_row){   // Loan Loop Start
                $start_date = $change_date;
                $loan_payment_list = $this->LoanTransaction->find('all',array(
                    'fields'=>array('LoanTransaction.*'),
                    'conditions'=>array('LoanTransaction.loan_id'=>$loan_row['Loan']['id'],'LoanTransaction.insta_due_on >='=>$change_date),
                    ));
                if(!empty($loan_payment_list)){
                    foreach($loan_payment_list as $trans_row)  {  // Transaction Loop Start
                        $date = strtotime($start_date);
                        if($loan_row['Loan']['loan_period_unit']=='WEEK'){
                            $date = strtotime("+1 Week", $date);
                        } // WEEK Block End
                        else{
                            $date = strtotime("+1 month", $date);
                        } // MONTH Block END
                        if($trans_row['LoanTransaction']['insta_due_on'] != $start_date){
                            $trans_data['LoanTransaction']['insta_due_on'] = $start_date;
                            $trans_data['LoanTransaction']['id'] = $trans_row['LoanTransaction']['id'];
                            $trans_data['LoanTransaction']['modified_on'] = date("Y-m-d H:i:s");
                            $this->LoanTransaction->clear();
                            $this->LoanTransaction->save($trans_data);
                        }
                        $start_date = date("Y-m-d", $date);
                    }  // Transaction Loop End
                } // IF end   
            } // Loan Loop End 
            $this->redirect('/kendra_loan_details/'.$kid);
        } // POST IF End
    }
	// Set all collection value function end
    
	// Load kendra list of a branch in ajax function start
    public function ajax_kendra_list($bid='')
    {
        $this->layout = 'ajax';
        $kendra_list = $this->Kendra->find('list',array(
            'fields'=>array('Kendra.id','Kendra.kendra_name'),
            'conditions'=>array('Kendra.branch_id'=>$bid,'Kendra.status'=>1)
            ));
        $this->set('kendra_list', $kendra_list);
    }
	// Load kendra list of a branch in ajax function start
    
    // Set all collection value function start
	public function customer_collection_settings($cust_id='') {
		$this->set('title', 'Collection Settings');  // This is used for Title for every page
		$this->layout = 'panel_layout';
        
        $cust_data = $this->Customer->find('first',array('conditions'=>array('Customer.id'=>$cust_id)));
        //pr($cust_data);die;
        $branch_list = $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.status'=>1, 'Branch.organization_id'=>$this->Auth->user('organization_id'))));
        $market_list = array($cust_data['Customer']['market_id']=>$cust_data['Market']['market_name']);
        $cust_list = array($cust_data['Customer']['id']=>$cust_data['Customer']['fullname']);
        $this->set('cust_data', $cust_data);
        $this->set('branch_list', $branch_list);
        $this->set('market_list', $market_list);
        $this->set('cust_list', $cust_list);
        if ($this->request->is('post')) {
            //pr($this->request->data);die;
           $mid = $this->request->data['market_id'];
           $cid = $this->request->data['customer_id'];
           $bid = $this->request->data['branch_id'];
           $change_date = $this->request->data['payment_change_date'];           
           $loan_data = $this->Loan->find('all',array(
            'conditions'=>array('Loan.customer_id'=>$cid,'Loan.loan_status_id'=>3,'Loan.status'=>1),
           ));
            foreach($loan_data as $loan_row){   // Loan Loop Start
                $start_date = $change_date;
                $loan_payment_list = $this->LoanTransaction->find('all',array(
                    'fields'=>array('LoanTransaction.*'),
                    'conditions'=>array('LoanTransaction.loan_id'=>$loan_row['Loan']['id'],'LoanTransaction.insta_due_on >='=>$change_date),
                    ));
                if(!empty($loan_payment_list)){
                    foreach($loan_payment_list as $trans_row)  {  // Transaction Loop Start
                        $date = strtotime($start_date);
                        if($loan_row['Loan']['loan_period_unit']=='WEEK'){
                            $date = strtotime("+1 Week", $date);
                        } // WEEK Block End
                        else{
                            $date = strtotime("+1 month", $date);
                        } // MONTH Block END
                        if($trans_row['LoanTransaction']['insta_due_on'] != $start_date){
                            $trans_data['LoanTransaction']['insta_due_on'] = $start_date;
                            $trans_data['LoanTransaction']['id'] = $trans_row['LoanTransaction']['id'];
                            $trans_data['LoanTransaction']['modified_on'] = date("Y-m-d H:i:s");
                            $this->LoanTransaction->clear();
                            $this->LoanTransaction->save($trans_data);
                            
                            if($this->request->data['all_date']=='0'){
                                $this->redirect('/customer_details/'.$cid);
                            }
                        }
                        $start_date = date("Y-m-d", $date);
                    }  // Transaction Loop End
                } // IF end   
            } // Loan Loop End 
            $this->redirect('/customer_details/'.$cid);
        } // POST IF End
    }
	// Set all collection value function end
    // Load kendra list of a branch in ajax function start
    public function ajax_market_list($bid='')
    {
        $this->layout = 'ajax';
        $market_list = $this->Market->find('list',array(
            'fields'=>array('id','market_name'),
            'conditions'=>array('Market.branch_id'=>$bid,'Market.status'=>1)
            ));
        $this->set('market_list', $market_list);
    }
	// Load kendra list of a branch in ajax function start
    
    // Load kendra list of a branch in ajax function start
    public function ajax_customer_list($mid='')
    {
        $this->layout = 'ajax';
        $cust_list = $this->Customer->find('list',array(
            'fields'=>array('id','fullname'),
            'conditions'=>array('Customer.branch_id'=>$mid,'Customer.status'=>1)
            ));
        $this->set('cust_list', $cust_list);
    }
	// Load kendra list of a branch in ajax function start
}
// END Setting controller
?>