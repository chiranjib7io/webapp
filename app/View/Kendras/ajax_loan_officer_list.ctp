<label>Select Loan Officer</label>
<?php
if(!empty($u_list)) {
	//pr($u_list);die;
    echo $this->Form->input('Kendra.user_id', array('type' => 'select', 'options' => $u_list, 'class'=>'form-control', 'label'=>false, 'default'=>'', 'required'=>'required', 'empty' => 'Select Loan Officer'));
    echo $this->Form->input('Kendra.organization_id', array('type' => 'hidden','value'=>$branch_data['Branch']['organization_id'],'class'=>'form-control','label'=>false));
    echo $this->Form->input('Kendra.region_id', array('type' => 'hidden','class'=>'form-control','value'=>$branch_data['Branch']['region_id'],'label'=>false));

}else{
    echo '<select class="form-control" required="required"></select>';
}
        
?>