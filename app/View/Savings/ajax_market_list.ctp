<label>Select Market</label>                     
<?php
echo $this->Form->input('Market.market_id', array(
'type' => 'select', 
'options' => $market_list,
'class'=>'form-control', 
'label'=>false, 
'required'=>true, 
'empty' => 'Select Market',
'onChange'=>'getGroupOrKendraList(this.value)'));
?>