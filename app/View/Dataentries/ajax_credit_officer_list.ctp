<label>Select Credit Officer</label>                     
<?php
echo $this->Form->input('credit_officer_id', array('type' => 'select', 'options' =>$credit_officers, 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'empty' => 'Select Group/Kendra','onChange'=>'getCreditOfficre(this.value)'));
?>