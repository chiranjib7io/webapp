<label for="CollectionDate">Select Customer</label>
<?php
if(!empty($loan_cust_list)) {
    echo $this->Form->input("Loan.customer_id", array(
                    'options' => $loan_cust_list,
                    'default' => '',
                    'label'=>false,
                    'empty' => 'Select Customer',
                    'onChange'=>'get_sec_fee(this.value)',
                    'required' => 'required',
    	            'class'	=> 'form-control'
                ));
}else{
    echo '<select class="form-control" required="required"></select>';
}
        
?>