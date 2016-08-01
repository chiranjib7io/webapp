<label for="Kendra">Customer</label>
<?php
if(!empty($cust_list)) {
    echo $this->Form->input("customer_id", array(
                    'options' => $cust_list,
                    'default' => '',
                    'label'=>false,
                    'empty' => 'Select Customer',
                    'required' => 'required',
    	            'class'	=> 'form-control'
                ));
    //echo $this->Form->input('UploadReport.region_id', array('type' => 'hidden','value'=>$branch_data['Region']['id'],'label'=>false));
    //echo $this->Form->input('UploadReport.branch_id', array('type' => 'hidden','value'=>$branch_data['Branch']['id'],'label'=>false));
    
}else{
    echo '<select class="form-control" required="required"><option>No Customer Found</option></select>';
}
        
?>