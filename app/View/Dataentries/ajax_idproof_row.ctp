<?php
$time=strtotime(date("d-m-Y H:i:s.u"));
?>
<div id="<?=$time?>">

<div class="form-group col-md-6 col-sm-12">
  <label>ID Card Type</label>
  <?php
		echo $this->Form->input('', array('type' => 'select','name'=>'data[idproof][id_proof_type][]', 'options' => $identity_type, 'class'=>'form-control', 'label'=>false, 'empty' => 'Select Identity Card Type'));
	?>
</div><!-- /.form group -->

<div class="form-group col-md-6 col-sm-12">
  <label for="IdCardNo">Id Card No</label>
  <?php echo $this->Form->input('', array('type' => 'text','name'=>'data[idproof][id_proof_no][]','placeholder'=>'Enter Id Card No','class'=>'form-control','label'=>false)); ?>
  <a href="javascript:void(0)" onclick="delete_idproof_row('<?=$time?>')" style="color: red;">[-]Remove Idproof</a>
  
</div><!-- /.form group -->

</div>