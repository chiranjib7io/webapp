<?php
// This is order controller. All the function related with order is listed here.
App::uses('CakeEmail', 'Network/Email');
class OrdersController extends AppController {
	// List of models which are used in the order controller 
	var $uses = array('User','Organization','Region','Branch','Kendra','Customer','Loan','LoanTransaction','LoanStatus','Saving','Idproof','LogRecord','Country','Order','OrderStatus','Product');
	
	// This is a blank index function for safe navigation
    public function index() {
    }
	
    // Create an Order of a customer function start
    public function add($cust_id='') {
		$this->layout = 'panel_layout';
        $this->set('title', 'Create Order');
        $org_data= $this->get_organization_settings_fees($this->Auth->user('organization_id'));
        $user_data= $this->User->find('all',array('conditions'=>array('User.id'=>$this->Auth->user('id'))));
        $branch_list= $this->Branch->find('list',array('fields'=>array('id','branch_name'),'conditions'=>array('Branch.organization_id'=>$this->Auth->user('organization_id'))));
        $cust_data= $this->Customer->find('first',array('conditions'=>array('Customer.id'=>$cust_id)));
        $count_loan = $this->Order->find('count');
        $loan_no = 'ORD-'.$this->Auth->user('organization_id').'-'.$cust_id.'-'.($count_loan + 1); // Create the Order No.
        $this->set('org_data', $org_data);
        $this->set('user_data', $user_data);
        $this->set('branch_list', $branch_list);
        $this->set('cust_data', $cust_data);
        $this->set('loan_no', $loan_no);
        if ($this->request->is('post')) {
			$this->request->data['Order']['created_on'] = date("Y-m-d H:i:s");
			$this->request->data['Order']['order_number'] = $loan_no;
            $this->request->data['Order']['user_id'] = $this->Auth->user('id');
			$this->Order->create();
			if ($this->Order->save($this->request->data)) {
				$last_insert_loan=$this->Order->getLastInsertId();
				$this->Session->setFlash(__('The Order has been created'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The Order could not be created. Please, try again.'));
			}	
        }
    }
	// Create an Order of a customer function end
    
	// Order Details function start
    public function details($order_id='') {
		$this->layout = 'panel_layout';
        $this->set('title', 'Order details');
        if($order_id==''){
           $this->redirect(array('action' => 'details/')); 
        }
        $order_data= $this->Order->find('first',array('conditions'=>array('Order.id'=>$order_id)));
        $loan_status= $this->OrderStatus->find('list',array('fields'=>array('id','status_name'),'conditions'=>array('OrderStatus.status'=>1)));
        $loan_overdue = $this->LoanTransaction->find('all',array('fields'=>array('SUM(LoanTransaction.total_installment) as total_overdue','COUNT(LoanTransaction.id) as overdue_no'),'conditions'=>array('LoanTransaction.order_id'=>$order_id,'LoanTransaction.insta_due_on <='=>date("Y-m-d"),'LoanTransaction.insta_principal_paid'=>0)));
        $this->set('loan_overdue', $loan_overdue);
        $this->set('order_data', $order_data);
        $this->set('loan_status', $loan_status);
        if ($this->request->is('post')) {
			$this->request->data['Order']['modified_on'] = date("Y-m-d H:i:s");
			if ($this->Order->save($this->request->data)) {
				$this->Session->setFlash('The Order has been updated');
				$this->redirect(array('action' => 'details/'.$order_id));
			} else {
				$this->Session->setFlash('The Order could not be updated. Please, try again.');
			}	
        }
    }
	// Order Details function end
    
	// Collection of a order EMI or instalment function start
    public function single_order_installment_collection($trans_id=''){
        $this->layout = 'ajax';
        $this->autoRender = false;
		$this->set('title', 'Single Order Collection');
        $trans_data= $this->LoanTransaction->find('first',array('conditions'=>array('LoanTransaction.id'=>$trans_id)));
        $ord_id = $trans_data['LoanTransaction']['order_id'];        
        $due_on = $trans_data['LoanTransaction']['insta_due_on'];
        $amt = $trans_data['LoanTransaction']['total_installment'];
        $this->order_amount_collection($ord_id,$due_on,$trans_id,$amt);
        $this->redirect('/order_details/'.$ord_id);
    }
	// Collection of a order EMI or instalment function end
    
    // List of all order function start
    public function order_list() {
		$this->layout = 'panel_layout';
        $this->set('title', 'Order List');
        $order_list = $this->paginate('Order');
		$this->set(compact('order_list'));
    }
    // List of all order function end
}
// END of Order COntroller
?>