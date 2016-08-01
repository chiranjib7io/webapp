<label class="control-label">Customer Name:</label>
<?php
if(!empty($loan_cust_list)) {
    echo $this->Form->input("Order.customer_id", array(
                    'options' => $loan_cust_list,
                    'label'=>false,
                    'empty' => 'Select Customer',
                    'required' => 'required',
    	            'class'	=> 'form-control'
                ));
    echo $this->Form->input('Order.organization_id', array('type' => 'hidden','value'=>$kendra_data['Kendra']['organization_id'],'label'=>false));
    echo $this->Form->input('Order.region_id', array('type' => 'hidden','value'=>$kendra_data['Kendra']['region_id'],'label'=>false));
    echo $this->Form->input('Order.branch_id', array('type' => 'hidden','value'=>$kendra_data['Kendra']['branch_id'],'label'=>false));
}else{
    echo '<select class="form-control" required="required"><option value="">Select Customer</option></select>';
}
        
?>