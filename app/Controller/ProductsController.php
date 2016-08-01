<?php
// This is product controller. All the function related with Product is mention here.
App::uses('CakeEmail', 'Network/Email');
class ProductsController extends AppController {
	// List of models which are used in the product controller 
    var $uses = array('User','Organization','Region','Branch','Kendra','Customer','Loan','Saving','Idproof','LogRecord','Country', 'Setting', 'Fee','Product','Order');
   
	// This is a blank index function for safe navigation
    public function index() {
		
    }
	
	// Create a product function start
	public function save($pid=''){
		$this->layout = 'panel_layout';
		$this->set('title', 'Save Product');
        $product_data = array();
		if($pid!=''){
		  $product_data = $this->Product->findById($pid);
          if (!$this->request->data) {
                $this->request->data = $product_data;
          }
		}
        $this->set('product_data', $product_data);
        if ($this->request->is(array('post', 'put'))) {
            if($pid!=''){
                $this->request->data['Product']['modified_on'] = date("Y-m-d H:i:s");
                $this->request->data['Product']['organization_id'] = $this->Auth->user('organization_id');
                $this->request->data['Product']['region_id'] = $this->Auth->user('region_id');
                $this->request->data['Product']['branch_id'] = $this->Auth->user('branch_id');
                $this->request->data['Product']['user_id'] = $this->Auth->user('id');
                $this->Product->id = $pid;
            }else{
                $this->request->data['Product']['created_on'] = date("Y-m-d H:i:s");
                $this->request->data['Product']['organization_id'] = $this->Auth->user('organization_id');
                $this->request->data['Product']['region_id'] = $this->Auth->user('region_id');
                $this->request->data['Product']['branch_id'] = $this->Auth->user('branch_id');
                $this->request->data['Product']['user_id'] = $this->Auth->user('id');
                $this->Product->create(); 
            }
            if ($this->Product->save($this->request->data)) {                
                $this->Session->setFlash(__('Your Product has been saved.'));
                return $this->redirect('/product_list');
            }
            $this->Flash->error(__('Unable to save your Product.'));
        }
	}
	// Create a product function end
	
	// Show all product list function start
	public function product_list(){
		$this->layout = 'panel_layout';
		$this->set('title', 'Product List');
		$product_list = $this->paginate('Product');
		$this->set(compact('product_list'));
	}
    // Show all product list function end
	
	// Show details of a product function start
    public function details($pid){
		$this->layout = 'panel_layout';
		$product_data = $this->Product->find('first',array('conditions'=>array('Product.id'=>$pid)));
		$this->set(compact('product_data'));
        $this->set('title', 'Product Details || '.$product_data['Product']['product_name']);
        $kendra_list = array();
        if($this->Auth->user('user_type_id')==2){
            $kendra_list = $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.organization_id'=>$this->Auth->user('organization_id'))));
        }
        if($this->Auth->user('user_type_id')==5){
            $kendra_list = $this->Kendra->find('list',array('fields'=>array('id','kendra_name'),'conditions'=>array('Kendra.user_id'=>$this->Auth->user('id'))));
        }
        $this->set('kendra_list',$kendra_list);
        if ($this->request->is('post')) {
            $total_order = $this->Order->find('count');
            $num_padded = sprintf("%05d", $total_order+1);
            $this->request->data['Order']['order_number'] = 'ORD/'.$this->request->data['Order']['organization_id'].'/'.$this->request->data['Order']['customer_id'].'/'.$num_padded;
            $this->request->data['Order']['order_date'] = date("Y-m-d");
            $this->request->data['Order']['created_on'] = date("Y-m-d H:i:s");;
            $this->request->data['Order']['user_id'] = $this->Auth->user('id');
            $this->Order->create();
            if($this->Order->save($this->request->data))
            {
                $this->Session->setFlash(__('Your Order has been placed.'));
                return $this->redirect('/product_details/'.$pid);
            }
         }
	}
	// Show details of a product function end
	
	// Load customer list in ajax function start
	public function ajax_customer_list($kid='')
    {
        $this->layout = 'ajax';
        $this->Customer->virtualFields = array(
                'full_name' => "CONCAT(Customer.cust_fname, ' ', Customer.cust_lname)"
            );
        $loan_cust_list = $this->Customer->find('list',array(
            'fields'=>array('Customer.id','Customer.full_name'),
            'conditions'=>array('Customer.kendra_id'=>$kid,'Customer.status'=>1)
            ));
        $this->set('loan_cust_list', $loan_cust_list);
        $kendra_data = $this->Kendra->find('first',array('conditions'=>array('Kendra.id'=>$kid)));
        $this->set('kendra_data', $kendra_data);
    }
	// Load customer list in ajax function end
}
// END of Product controller
?>