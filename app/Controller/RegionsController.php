<?php
// This is kendra controller. All the function related with a kendra is written here.
App::uses('CakeEmail', 'Network/Email');
class RegionsController extends AppController
{
	// List of models which are used in the kendra controller 
    var $uses = array(
        'User',
        'Organization',
        'Region',
        'Branch',
        'Market',
        'Kendra',
        'Customer',
        'Loan',
        'Saving',
        'Idproof',
        'LogRecord',
        'Country',
        'Setting',
        'Fee');
	
	// This is a blank index function for safe navigation
    public function index()
    {

    }
	
	// Show all the Region lists function start
    public function region_list(){
        $this->layout = 'panel_layout';
        $this->set('title', 'Region List');
        $organisation_id = $this->Auth->User('organization_id');
        $this->Region->unBindModel(array('hasMany' => array('Branch'),'belongsTo'=>array('Organization')));
        $region_data = $this->Region->find('all',array('conditions'=>array('Region.organization_id'=>$organisation_id,'Region.status'=>1)));
        //pr($region_data);die;
        $this->set('region_data', $region_data);
        
    }
	// Show all the Region lists function end
	
	
    
    // Create or Edit a Region function start (If there is a Region id then it work as edit else as create)
    public function save($rid = '')
    {
        $this->layout = 'panel_layout';
        $this->set('title', 'Save Region');
        $manger_list = $this->User->find('list', array('fields' => array('User.id',
                    'User.fullname'), 'conditions' => array('User.organization_id' => $this->Auth->user('organization_id'),'User.user_type_id'=>3)));
        $this->set('manger_list', $manger_list);
        //pr($manger_list);die;

        if ($rid != '') {
            $region_data = $this->Region->findById($rid);
            if (!$this->request->data) {
                $this->request->data = $region_data;
            }
        }

        if ($this->request->is(array('post', 'put'))) {

            if ($rid != '') {
                $this->request->data['Region']['modified_on'] = date("Y-m-d H:i:s");
                $this->Region->id = $rid;
            } else {
                $this->request->data['Region']['organization_id'] = $this->Auth->user('organization_id');
                $this->request->data['Region']['created_on'] = date("Y-m-d H:i:s");
                $this->Region->create();
            }
            if ($this->Region->save($this->request->data)) {

                $this->Session->setFlash(__('Your Region has been saved.'));
                return $this->redirect('/region_list');
            }
            $this->Flash->error(__('Unable to save your Region.'));
        }
    }
	// Create or Edit a Region function end
	

}
// End of Kendra controller
?>