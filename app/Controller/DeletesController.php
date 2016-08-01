<?php
// This is Delete controller. In this controller all the function retreated with delete operation written here.
App::uses('CakeEmail', 'Network/Email');
class DeletesController extends AppController {
		// List of models which are used in the Delete controller
		var $uses = array('User','Organization','Region','Branch','Kendra','Customer','Loan','Saving','Idproof','LogRecord','Country', 'Setting', 'Fee', 'UserType','LoanTransaction', 'SavingsTransaction');
		
	// Delete all data of a kendra function start
	public function delete_kendra($kendra_id){
		 $customer_list = $this->Customer->find('all',array('conditions'=>array('Customer.status'=>1,'Customer.kendra_id'=>$kendra_id, 'Customer.upload_type' => 'CSV')));
		 //pr($customer_list); die;
		 if(!empty($customer_list)){
			  $i=0;
			foreach ($customer_list as $kcust=> $single_cust){
				$customer_id=$single_cust['Customer']['id'];
				$this->delete_customer($customer_id);
				$i++;
			}
			$this->Session->setFlash(__($i. ' number of customer deleted'));
		 } else {
			 $this->Session->setFlash(__('There is no customer to delete'));
		 }
	}
	// Delete all data of a kendra function end
	
	// Delete all data of a kendra functions option from where the kendra will selected function start
	public function delete_kendra_data(){
	   $this->layout = 'panel_layout';
	   $this->set('title', 'Delete Kendra Data');
       $branch_list = $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.status'=>1, 'Branch.organization_id'=>$this->Auth->user('organization_id'))));
       $kendra_list = array();
       $this->set('branch_list', $branch_list);
       $this->set('kendra_list', $kendra_list);
       if ($this->request->is('post')) {
            //pr($this->request->data);die;
            $this->delete_kendra($this->request->data['kendra_id']);
            $this->redirect('/delete_kendra_data/');
        } 
	}
	// Delete all data of a kendra functions option from where the kendra will selected function end
	
	// Load all kendra according to a branch in ajax function start
    public function ajax_kendra_list($bid='')
    {
        $this->layout = 'ajax';
        $kendra_list = $this->Kendra->find('list',array(
            'fields'=>array('Kendra.id','Kendra.kendra_name'),
            'conditions'=>array('Kendra.branch_id'=>$bid,'Kendra.status'=>1)
            ));
        $this->set('kendra_list', $kendra_list);
    }
	// Load all kendra according to a branch in ajax function end
    
	// Delete a transaction of a kendra of a specific date function start
    public function delete_transaction(){
	   $this->layout = 'panel_layout';
	   $this->set('title', 'Delete Transaction');
       $branch_list = $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.status'=>1, 'Branch.organization_id'=>$this->Auth->user('organization_id'))));
       $kendra_list = array();
       $this->set('branch_list', $branch_list);
       $this->set('kendra_list', $kendra_list);
       if ($this->request->is('post')) {
            //pr($this->request->data);die;
            $this->delete_trans($this->request->data['kendra_id'],$this->request->data['trans_date']);
            $this->redirect('/delete_transaction/');
        }
	}
	// Delete a transaction of a kendra of a specific date function end
}
// End of Delete Controller
?>