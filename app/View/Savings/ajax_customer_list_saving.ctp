<label for="CollectionDate">Select Customer</label>
<?php
if(!empty($loan_cust_list)) {
    echo $this->Form->input("customer_id", array(
                    'options' => $loan_cust_list,
                    'default' => '',
                    'label'=>false,
                    'empty' => 'Select Customer',
                    'onChange'=>'load_amount(this.value)',
                    'required' => 'required',
    	            'class'	=> 'form-control'
                ));
}else{
    echo '<select class="form-control" required="required">
	<option> No Customer Found </option>
	</select>';
}
        
?>