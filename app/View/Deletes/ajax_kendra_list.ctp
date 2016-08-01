<?php
if(!empty($kendra_list)) {
    echo $this->Form->input("kendra_id", array(
                    'options' => $kendra_list,
                    'default' => '',
                    'label'=>false,
                    'empty' => 'Select Kendra',
                    'required' => 'required',
    	            'class'	=> 'form-control'
                ));
}else{
    echo '<select class="form-control" required="required"><option>No Kendra Found</option></select>';
}
        
?>