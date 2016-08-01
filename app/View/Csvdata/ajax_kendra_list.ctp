<?php
if(!empty($kendra_list)) {
    echo $this->Form->input("UploadReport.kendra_id", array(
                    'options' => $kendra_list,
                    'default' => '',
                    'label'=>false,
                    'empty' => 'Select Kendra',
                    'required' => 'required',
    	            'class'	=> 'form-control'
                ));
    echo $this->Form->input('UploadReport.region_id', array('type' => 'hidden','value'=>$branch_data['Region']['id'],'label'=>false));
    echo $this->Form->input('UploadReport.branch_id', array('type' => 'hidden','value'=>$branch_data['Branch']['id'],'label'=>false));
    
}else{
    echo '<select class="form-control" required="required"><option>No Kendra Found</option></select>';
}
        
?>