<label>Select Group/Kendra</label>                     
<?php
echo $this->Form->input('group_id', array('type' => 'select', 'options' => $GroupOrKendra, 'class'=>'form-control', 'label'=>false, 'empty' => 'Select Group/Kendra','onChange'=>''));
?>