<?php
// This is kendra controller. All the function related with a kendra is written here.
App::uses('CakeEmail', 'Network/Email');
class KendrasController extends AppController
{
	// List of models which are used in the kendra controller 
    var $uses = array(
        'User',
        'Organization',
        'Region',
        'Branch',
        'Kendra',
        'Customer',
        'Loan',
        'Saving',
        'Idproof',
        'LogRecord',
        'Country',
        'Setting',
		'Account',
        'Fee');
	
	// This is a blank index function for safe navigation
    public function index()
    {

    }
	
	// Create or edit of a market function start
    public function save($kid = '')
    {
        $this->layout = 'panel_layout';
        $this->set('title', 'Save Market');
        $bm_list = $this->Branch->find('list', array('fields' => array('id','branch_name'), 'conditions' => array('Branch.organization_id' => $this->Auth->user('organization_id'))));
        $this->set('bm_list', $bm_list);
        $u_list = array();
        if ($kid != '') {
            $market_data = $this->Market->findById($kid);
            $this->User->virtualFields = array('full_name' =>
                    "CONCAT(User.first_name, ' ', User.last_name)");
            $u_list = $this->User->find('list', array('fields' => array('id', 'full_name'),
                    'conditions' => array(
                    'User.user_type_id' => 5,
                    'User.organization_id' => $this->Auth->user('organization_id'),
                    'User.status' => 1)));
            if (!$this->request->data) {
                $this->request->data = $market_data;
            }
        }
        $this->set('u_list', $u_list);
        if ($this->request->is(array('post', 'put'))) {

            if ($kid != '') {
                $this->request->data['Market']['modified_on'] = date("Y-m-d H:i:s");
                $this->Market->id = $kid;
            } else {
                $this->request->data['Market']['created_on'] = date("Y-m-d H:i:s");
                $this->Market->create();
            }
            if ($this->Market->save($this->request->data)) {

                $this->Customer->updateAll(array('Customer.branch_id' => $this->request->data['Market']['branch_id'],
                        'Customer.user_id' => $this->request->data['Market']['user_id']), array('Customer.market_id' =>
                        $kid));
                $this->Loan->updateAll(array('Loan.branch_id' => $this->request->data['Market']['branch_id'],
                        'Loan.user_id' => $this->request->data['Market']['user_id']), array('Loan.market_id' =>
                        $kid));
                $this->Saving->updateAll(array('Saving.branch_id' => $this->request->data['Market']['branch_id'],
                        'Saving.user_id' => $this->request->data['Market']['user_id']), array('Saving.market_id' =>
                        $kid));
				$this->Account->updateAll(array('Account.branch_id' => $this->request->data['Market']['branch_id'],
                        'Account.user_id' => $this->request->data['Market']['user_id']), array('Account.market_id' =>
                        $kid));

                $this->Session->setFlash(__('Your Market has been saved.'));
                return $this->redirect('/kendra_list');
            }
            $this->Flash->error(__('Unable to save your Market.'));
        }
    }	
	// Create or edit of a market function end
	
	// Load loan officer list based on a branch into ajax function start
    public function ajax_loan_officer_list($bid)
    {
        $this->layout = 'ajax';
        $this->User->virtualFields = array('full_name' =>
                "CONCAT(User.first_name, ' ', User.last_name)");
        $u_list = $this->User->find('list', array('fields' => array('id', 'full_name'),
                'conditions' => array(
                'User.user_type_id' => 5,
                'User.organization_id' => $this->Auth->user('organization_id'),
                'User.status' => 1)));
        $this->set('u_list', $u_list);
        $branch_data = $this->Branch->find('first', array('fields' => array('organization_id','region_id'), 'conditions' => array('Branch.id' => $bid)));
        $this->set('branch_data', $branch_data);
    }
	// Load loan officer list based on a branch into ajax function end
	
	// Show all the Market lists function start
    public function market_list($branch_id = ''){
        $this->layout = 'panel_layout';
        $this->set('title', 'Market List');
        $organisation_id = $this->Auth->User('organization_id');
		// Market Details Start
		App::import('Helper', 'Number');
        $Number = new NumberHelper(new View(null));
        if($this->Auth->user('user_type_id')==2){
            
            $kendra_list = $this->Market->find('all',array('conditions'=>array('Market.status'=>1,'Market.organization_id'=>$this->Auth->User('organization_id'))));
        }else{
            
            $kendra_list = $this->Market->find('all',array('conditions'=>array('Market.status'=>1,'Market.user_id'=>$this->Auth->user('id'))));
        }
        $data = array();
        if (!empty($kendra_list)) {
            foreach ($kendra_list as $k => $kendra_details) {
                $total_loan = 0;
                $total_paid = 0;
                $total_overdue = 0;
                $total_realiable = 0;
                $total_realized = 0;
                $max_date = $this->LoanTransaction->find('all',array(
					'fields'=>array(
						'MAX(LoanTransaction.insta_paid_on) as max_date'
					),
					'conditions'=>array(
						'LoanTransaction.insta_paid_on !='=> '0000-00-00',
						 'Loan.loan_status_id'=>3,
						 'Loan.market_id'=>$kendra_details['Market']['id']
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
				$last_update_date=date("Y-m-d",strtotime($max_date[0][0]['max_date']));
                $loan_overdue = $this->LoanTransaction->find('all', array(
                        'fields' => array(
                            'SUM(LoanTransaction.total_installment) as total_installment',
                            'SUM(LoanTransaction.insta_principal_paid + LoanTransaction.insta_interest_paid) as total_installment_paid',
                            'SUM(LoanTransaction.insta_principal_paid) as total_principal_paid',
                            'SUM(LoanTransaction.insta_interest_paid) as total_interest_paid'),
                        'conditions' => array(
                            'Loan.market_id' => $kendra_details['Market']['id'],
                            'Loan.loan_status_id' => 3,
                            'Loan.status' => 1,
                            'LoanTransaction.insta_due_on <'=> $last_update_date),
                        'joins' => array(array(
                                'table' => 'loans',
                                'alias' => 'Loan',
                                'type' => 'inner',
                                'foreignKey' => true,
                                'conditions' => array('Loan.id = LoanTransaction.loan_id')))));
                $loan_data = $this->Loan->find('all', array(
                        'fields' => array(
                            'SUM(Loan.loan_principal) as total_loan'),
                        'conditions' => array(
                            'Loan.market_id' => $kendra_details['Market']['id'],
                            'Loan.loan_status_id' => 3,
                            'Loan.status' => 1
                        )));
                $total_loan = $loan_data[0][0]['total_loan'];
                $total_realiable = $loan_overdue[0][0]['total_installment'];
                $total_realized = $loan_overdue[0][0]['total_installment_paid'];
                $total_overdue = $total_realiable - $total_realized;
                if($total_realiable>0)
                    $paid_percent = round(($total_realized/$total_realiable*100),2);
                else
                    $paid_percent = 0;
                
                $kendra_link='/save_market/'. $kendra_details['Market']['id'];
				$edit_link='<a href="'. $this->base.$kendra_link .'"> Edit </a>';
                $list_link='<a href="'. $this->base.'/market_loan_details/'.$kendra_details['Market']['id'] .'"> List </a>';
                if($total_loan <= 0){
                    $list_link='N/A';
                }
				$mid=$kendra_details['Market']['id'];
                $data['data'][$k][0] = $k + 1;
                $data['data'][$k]['market_id'] = $mid;
                $data['data'][$k][1] = $kendra_details['Branch']['branch_name'];
                $data['data'][$k][2] = $kendra_details['Market']['market_name'];
                $data['data'][$k][3] = $this->Customer->find('count', array('conditions'=> array('Customer.market_id'=>$mid, 'Customer.status'=>1)));  ;
                $data['data'][$k][4] = $Number->currency($total_loan,'',array('places'=>0));
                $data['data'][$k][5] = $Number->currency($total_overdue,'',array('places'=>0));
                $data['data'][$k][6] = $Number->currency($total_realiable,'',array('places'=>0));
                $data['data'][$k][7] = $Number->currency($total_realized,'',array('places'=>0));
                $data['data'][$k][8] = $paid_percent.' %';
                $data['data'][$k][9] = $list_link;
                $data['data'][$k][10] = $edit_link;
            }
			 $this->set('market_data', $data);
		}
		
    }
	// Show all the kendra lists function end
	
	// Show the kendra list in ajax call function start (Making all data into a json format)
    public function ajax_market_list()
    {
        App::import('Helper', 'Number');
        $Number = new NumberHelper(new View(null));
        if($this->Auth->user('user_type_id')==2){
            
            $kendra_list = $this->Kendra->find('all',array('conditions'=>array('Kendra.status'=>1,'Kendra.organization_id'=>$this->Auth->User('organization_id'))));
        }else{
            
            $kendra_list = $this->Kendra->find('all',array('conditions'=>array('Kendra.status'=>1,'Kendra.user_id'=>$this->Auth->user('id'))));
        }
        $data = array();
        if (!empty($kendra_list)) {
            foreach ($kendra_list as $k => $kendra_details) {
                $total_loan = 0;
                $total_paid = 0;
                $total_overdue = 0;
                $total_realiable = 0;
                $total_realized = 0;
                $max_date = $this->LoanTransaction->find('all',array(
					'fields'=>array(
						'MAX(LoanTransaction.insta_paid_on) as max_date'
					),
					'conditions'=>array(
						'LoanTransaction.order_id'=> 0,
						'LoanTransaction.insta_paid_on !='=> '0000-00-00',
						 'Loan.loan_status_id'=>3,
						 'Loan.kendra_id'=>$kendra_details['Kendra']['id']
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
				$last_update_date=date("Y-m-d",strtotime($max_date[0][0]['max_date']));
                $loan_overdue = $this->LoanTransaction->find('all', array(
                        'fields' => array(
                            'SUM(LoanTransaction.total_installment) as total_installment',
                            'SUM(LoanTransaction.insta_principal_paid + LoanTransaction.insta_interest_paid) as total_installment_paid',
                            'SUM(LoanTransaction.insta_principal_paid) as total_principal_paid',
                            'SUM(LoanTransaction.insta_interest_paid) as total_interest_paid'),
                        'conditions' => array(
                            'Loan.kendra_id' => $kendra_details['Kendra']['id'],
                            'Loan.loan_status_id' => 3,
                            'Loan.status' => 1,
                            'LoanTransaction.insta_due_on <'=> $last_update_date),
                        'joins' => array(array(
                                'table' => 'loans',
                                'alias' => 'Loan',
                                'type' => 'inner',
                                'foreignKey' => true,
                                'conditions' => array('Loan.id = LoanTransaction.loan_id')))));
                $loan_data = $this->Loan->find('all', array(
                        'fields' => array(
                            'SUM(Loan.loan_principal) as total_loan'),
                        'conditions' => array(
                            'Loan.kendra_id' => $kendra_details['Kendra']['id'],
                            'Loan.loan_status_id' => 3,
                            'Loan.status' => 1
                        )));
                $total_loan = $loan_data[0][0]['total_loan'];
                $total_realiable = $loan_overdue[0][0]['total_installment'];
                $total_realized = $loan_overdue[0][0]['total_installment_paid'];
                $total_overdue = $total_realiable - $total_realized;
                if($total_realiable>0)
                    $paid_percent = round(($total_realized/$total_realiable*100),2);
                else
                    $paid_percent = 0;
                
                $kendra_link='/save_kendra/'. $kendra_details['Kendra']['id'];
				$edit_link='<a href="'. $this->base.$kendra_link .'"> Edit </a>';
                $list_link='<a href="'. $this->base.'/kendra_loan_details/'.$kendra_details['Kendra']['id'] .'"> List </a>';
                if($total_loan <= 0){
                    $list_link='N/A';
                }
                $data['data'][$k][0] = $k + 1;
                $data['data'][$k][1] = $kendra_details['Branch']['branch_name'];
                $data['data'][$k][2] = $kendra_details['Kendra']['kendra_name'];
                $data['data'][$k][3] = count($kendra_details['Customer']);
                $data['data'][$k][4] = $Number->currency($total_loan,'',array('places'=>0));
                $data['data'][$k][5] = $Number->currency($total_overdue,'',array('places'=>0));
                $data['data'][$k][6] = $Number->currency($total_realiable,'',array('places'=>0));
                $data['data'][$k][7] = $Number->currency($total_realized,'',array('places'=>0));
                $data['data'][$k][8] = $paid_percent;
                $data['data'][$k][9] = $list_link;
                $data['data'][$k][10] = $edit_link;
            }
            echo $this->prepare_json($data);
        }
        $this->layout = 'ajax';
    }
    // Show the kendra list in ajax call function end
    
	// Show all the kendra lists function start
    public function kendra_list($branch_id = ''){
        $this->layout = 'panel_layout';
        $this->set('title', 'Kendra List');
        $organisation_id = $this->Auth->User('organization_id');
        $options['Kendra.organization_id'] = $organisation_id;
        $branches_data = $this->Branch->find('list',array('fields'=> array('Branch.id', 'Branch.branch_name'),'conditions'=>array('Branch.status'=>1,),'recursive'=>1));
        $this->set('branches_data', $branches_data);
        if (!empty($branch_id)) {
            $branch_data = $this->Branch->find('first', array('conditions' => array('Branch.status' => 1, 'Branch.id' => $branch_id)));
            $this->set('branch_data', $branch_data);
        } else {
            $branch_data['Branch']['id'] = '';
            $branch_data['Branch']['branch_name'] = '';
            $this->set('branch_data', $branch_data);
        }
    }
	// Show all the kendra lists function end
	
	// Show the kendra list in ajax call function start (Making all data into a json format)
    public function ajax_kendra_list()
    {
        App::import('Helper', 'Number');
        $Number = new NumberHelper(new View(null));
        if($this->Auth->user('user_type_id')==2){
            
            $kendra_list = $this->Kendra->find('all',array('conditions'=>array('Kendra.status'=>1,'Kendra.organization_id'=>$this->Auth->User('organization_id'))));
        }else{
            
            $kendra_list = $this->Kendra->find('all',array('conditions'=>array('Kendra.status'=>1,'Kendra.user_id'=>$this->Auth->user('id'))));
        }
        $data = array();
        if (!empty($kendra_list)) {
            foreach ($kendra_list as $k => $kendra_details) {
                $total_loan = 0;
                $total_paid = 0;
                $total_overdue = 0;
                $total_realiable = 0;
                $total_realized = 0;
                $max_date = $this->LoanTransaction->find('all',array(
					'fields'=>array(
						'MAX(LoanTransaction.insta_paid_on) as max_date'
					),
					'conditions'=>array(
						'LoanTransaction.insta_paid_on !='=> '0000-00-00',
						 'Loan.loan_status_id'=>3,
						 'Loan.kendra_id'=>$kendra_details['Kendra']['id']
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
				$last_update_date=date("Y-m-d",strtotime($max_date[0][0]['max_date']));
                $loan_overdue = $this->LoanTransaction->find('all', array(
                        'fields' => array(
                            'SUM(LoanTransaction.total_installment) as total_installment',
                            'SUM(LoanTransaction.insta_principal_paid + LoanTransaction.insta_interest_paid) as total_installment_paid',
                            'SUM(LoanTransaction.insta_principal_paid) as total_principal_paid',
                            'SUM(LoanTransaction.insta_interest_paid) as total_interest_paid'),
                        'conditions' => array(
                            'Loan.kendra_id' => $kendra_details['Kendra']['id'],
                            'Loan.loan_status_id' => 3,
                            'Loan.status' => 1,
                            'LoanTransaction.insta_due_on <'=> $last_update_date),
                        'joins' => array(array(
                                'table' => 'loans',
                                'alias' => 'Loan',
                                'type' => 'inner',
                                'foreignKey' => true,
                                'conditions' => array('Loan.id = LoanTransaction.loan_id')))));
                $loan_data = $this->Loan->find('all', array(
                        'fields' => array(
                            'SUM(Loan.loan_principal) as total_loan'),
                        'conditions' => array(
                            'Loan.kendra_id' => $kendra_details['Kendra']['id'],
                            'Loan.loan_status_id' => 3,
                            'Loan.status' => 1
                        )));
                $total_loan = $loan_data[0][0]['total_loan'];
                $total_realiable = $loan_overdue[0][0]['total_installment'];
                $total_realized = $loan_overdue[0][0]['total_installment_paid'];
                $total_overdue = $total_realiable - $total_realized;
                if($total_realiable>0)
                    $paid_percent = round(($total_realized/$total_realiable*100),2);
                else
                    $paid_percent = 0;
                
                $kendra_link='/save_kendra/'. $kendra_details['Kendra']['id'];
				$edit_link='<a href="'. $this->base.$kendra_link .'"> Edit </a>';
                $list_link='<a href="'. $this->base.'/kendra_loan_details/'.$kendra_details['Kendra']['id'] .'"> List </a>';
                if($total_loan <= 0){
                    $list_link='N/A';
                }
                $data['data'][$k][0] = $k + 1;
                $data['data'][$k][1] = $kendra_details['Branch']['branch_name'];
                $data['data'][$k][2] = $kendra_details['User']['first_name'].' '.$kendra_details['User']['last_name'];
                $data['data'][$k][3] = $kendra_details['Kendra']['kendra_name'];
                $data['data'][$k][4] = count($kendra_details['Customer']);
                $data['data'][$k][5] = $Number->currency($total_loan,'',array('places'=>0));
                $data['data'][$k][6] = $Number->currency($total_overdue,'',array('places'=>0));
                $data['data'][$k][7] = $Number->currency($total_realiable,'',array('places'=>0));
                $data['data'][$k][8] = $Number->currency($total_realized,'',array('places'=>0));
                $data['data'][$k][9] = $paid_percent;
                $data['data'][$k][10] = $list_link;
                $data['data'][$k][11] = $edit_link;
            }
            echo $this->prepare_json($data);
        }
        $this->layout = 'ajax';
    }
    // Show the kendra list in ajax call function end
	
	
	
	
    
    // Create or Edit a kendra function start (If there is a kendra id then it work as create else as edit)
    public function save_kendra($kid = '')
    {
        $this->layout = 'panel_layout';
        $this->set('title', 'Save Kendra');
        $bm_list = $this->Branch->find('list', array('fields' => array('id',
                    'branch_name'), 'conditions' => array('Branch.organization_id' => $this->Auth->
                    user('organization_id'))));
        $this->set('bm_list', $bm_list);
        $u_list = array();
        if ($kid != '') {
            $kendra_data = $this->Kendra->findById($kid);
            $this->User->virtualFields = array('full_name' =>
                    "CONCAT(User.first_name, ' ', User.last_name)");
            $u_list = $this->User->find('list', array('fields' => array('id', 'full_name'),
                    'conditions' => array(
                    'User.user_type_id' => 5,
                    'User.branch_id' => $kendra_data['Kendra']['branch_id'],
                    'User.status' => 1)));
            if (!$this->request->data) {
                $this->request->data = $kendra_data;
            }
        }
        $this->set('u_list', $u_list);
        if ($this->request->is(array('post', 'put'))) {

            if ($kid != '') {
                $this->request->data['Kendra']['modified_on'] = date("Y-m-d H:i:s");
                $this->Kendra->id = $kid;
            } else {
                $this->request->data['Kendra']['created_on'] = date("Y-m-d H:i:s");
                $this->Kendra->create();
            }
            if ($this->Kendra->save($this->request->data)) {

                $this->Customer->updateAll(array('Customer.branch_id' => $this->request->data['Kendra']['branch_id'],
                        'Customer.user_id' => $this->request->data['Kendra']['user_id']), array('Customer.kendra_id' =>
                        $kid));
                $this->Loan->updateAll(array('Loan.branch_id' => $this->request->data['Kendra']['branch_id'],
                        'Loan.user_id' => $this->request->data['Kendra']['user_id']), array('Loan.kendra_id' =>
                        $kid));
                $this->Saving->updateAll(array('Saving.branch_id' => $this->request->data['Kendra']['branch_id'],
                        'Saving.user_id' => $this->request->data['Kendra']['user_id']), array('Saving.kendra_id' =>
                        $kid));
                $this->Account->updateAll(array('Account.branch_id' => $this->request->data['Kendra']['branch_id'],
                        'Account.user_id' => $this->request->data['Kendra']['user_id']), array('Account.kendra_id' =>
                        $kid));
                

                $this->Session->setFlash(__('Your Kendra has been saved.'));
                return $this->redirect('/kendra_list');
            }
            $this->Flash->error(__('Unable to save your Kendra.'));
        }
    }
	// Create or Edit a kendra function end
	
	// Load loan officer list based on a branch into ajax function start
    public function ajax_loan_officer_list_kendra($bid)
    {
        $this->layout = 'ajax';
        $this->User->virtualFields = array('full_name' =>
                "CONCAT(User.first_name, ' ', User.last_name)");
        $u_list = $this->User->find('list', array('fields' => array('id', 'full_name'),
                'conditions' => array(
                'User.user_type_id' => 5,
                'User.branch_id' => $bid,
                'User.status' => 1)));
        $this->set('u_list', $u_list);
        $branch_data = $this->Branch->find('first', array('fields' => array('organization_id',
                    'region_id'), 'conditions' => array('Branch.id' => $bid)));
        $this->set('branch_data', $branch_data);
    }
	// Load loan officer list based on a branch into ajax function end
    
    
    
    
    
    
    
    
    
    
}
// End of Kendra controller
?>