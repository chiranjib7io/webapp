<?php
// This is Branch controller. All the functions related to branch is listed here.
App::uses('CakeEmail', 'Network/Email');
class BranchesController extends AppController {
		// List of models which are used in the branch controller
		var $uses = array('User','Organization','Region','Branch','Kendra','Customer','Loan','Saving','Idproof','LogRecord','Country', 'Setting', 'Fee');

	// If there is any wrong navigation
    public function index() {
		
    }
	
	// Create a Branch function Start
	public function add(){
		$this->layout = 'panel_layout';
		$this->set('title', 'Create Branch');
		$org_id=$this->Auth->user('organization_id');
		$bm_list = $this->User->find('list', array('fields' => array('id', 'first_name'),'conditions'=> array('user_type_id'=>4, 'status'=>1)));
		$this->set('bm_list', $bm_list);
		$region_list = $this->Region->find('list', array('fields' => array('id', 'region_name'),'conditions'=> array('organization_id'=>$org_id, 'status'=>1)));
		$this->set('region_list', $region_list);
		if ($this->request->is('post')) {
			$this->request->data['Branch']['created_on'] = date("Y-m-d");
			$this->request->data['Branch']['modified_on'] = date("Y-m-d");
			$this->request->data['Branch']['organization_id'] = $this->Auth->user('organization_id');
			$this->Branch->create();
			if ($this->Branch->save($this->request->data)) {
				$this->Session->setFlash(__('The Branch has been created'));
				$this->redirect(array('action' => 'add'));
			} else {
				$this->Session->setFlash(__('The Branch could not be created. Please, try again.'));
			}	
        }
	}
	// Create a Branch function End
    
	// Edit an branch function start
    public function edit($bid=''){
		$this->layout = 'panel_layout';
		$this->set('title', 'Update Branch');
		$org_id=$this->Auth->user('organization_id');
		$bm_list = $this->User->find('list', array('fields' => array('id', 'first_name'),'conditions'=> array('user_type_id'=>4, 'status'=>1)));
		$this->set('bm_list', $bm_list);
		$region_list = $this->Region->find('list', array('fields' => array('id', 'region_name'),'conditions'=> array('organization_id'=>$org_id, 'status'=>1)));
		$this->set('region_list', $region_list);
        $branch_data = $this->Branch->findById($bid);      
        if ($this->request->is(array('post', 'put'))) {
            $this->request->data['Branch']['modified_on'] = date("Y-m-d");
            $this->Branch->id = $bid;
            if ($this->Branch->save($this->request->data)) {
                $this->Session->setFlash(__('Your Branch has been updated.'));
                return $this->redirect('/branch_list');
            }
            $this->Flash->error(__('Unable to update your Branch.'));
        }
        if (!$this->request->data) {
            $this->request->data = $branch_data;
        }
	}
	// Edit an branch function end
	
	// All branch list of a particular organization
	public function branch_list(){
		$this->layout = 'panel_layout';
		$this->set('title', 'Branch List');
		$this->Branch->unBindModel(array(
		'belongsTo' => array(
                'Organization',
                'Region'
               ),
		'hasMany' => array(
                'Loan'
               ),
				));
		$branch_list= $this->Branch->find('all',array('conditions'=>array('Branch.organization_id'=>$this->Auth->user('organization_id'))));
		$this->set('branch_list', $branch_list);
		//pr($branch_list); die;
	}
}
// Branch controller end here
?>