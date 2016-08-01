<?php
if($flag==1){
?>
<div class="box-body col-md-3 col-sm-12">
    <div class="form-group" id="">             
        <label>Select Customer</label>
        <?php
            echo $this->Form->input('customer_id', array(
            'type' => 'select', 
            'options' => $cust_list,
            'class'=>'form-control', 
            'label'=>false, 
            'required'=>true, 
            'empty' => 'Select Customer'));
            ?>
    </div>
</div>

<?php   
}else{
?>
<div class="box-body col-md-3 col-sm-12">
    <div class="form-group" id="">

        <label>Enter Customer Name</label>
        <?= $this->Form->input('cust_fname', array('type' => 'text','class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>''));?>
    </div>
</div> 
<div class="box-body col-md-3 col-sm-12">
    <div class="form-group" id="">

        <label>Enter Guardian Name</label>
        <?= $this->Form->input('guardian_name', array('type' => 'text','class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>''));?>
    </div>
</div> 
<div class="box-body col-md-3 col-sm-12">
    <div class="form-group" id="">

        <label>Select Relationship Type</label>
        <?= $this->Form->input('guardian_reletion_type', array('type' => 'select', 'onChange'=>'', 'options' => $this->Slt->relationship_type(), 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>''));?>
    </div>
</div>


<?php
}
?>
