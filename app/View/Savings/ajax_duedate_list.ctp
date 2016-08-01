<label for="CollectionDate">Select Collection Date</label>
<?php
if(!empty($loan_payment_list)) {
    echo $this->Form->input("Loan.insta_due_on", array(
                    'options' => $loan_payment_list,
                    'default' => '',
                    'label'=>false,
                    'required' => 'required',
    	            'class'	=> 'form-control'
                ));
}else{
    echo '<select class="form-control" required="required"></select>';
}
        
?>