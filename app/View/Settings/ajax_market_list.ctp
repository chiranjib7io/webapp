<label for="Kendra">Market</label>
<?php
if(!empty($market_list)) {
    echo $this->Form->input("market_id", array(
                    'options' => $market_list,
                    'default' => '',
                    'label'=>false,
                    'empty' => 'Select Market',
                    'required' => 'required',
    	            'class'	=> 'form-control',
                    'onChange'=>"select_customer(this.value)"
                ));
    //echo $this->Form->input('UploadReport.region_id', array('type' => 'hidden','value'=>$branch_data['Region']['id'],'label'=>false));
    //echo $this->Form->input('UploadReport.branch_id', array('type' => 'hidden','value'=>$branch_data['Branch']['id'],'label'=>false));
    
}else{
    echo '<select class="form-control" required="required"><option>No Market Found</option></select>';
}
        
?>