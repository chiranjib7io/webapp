<?php
$time=strtotime(date("d-m-Y H:i:s.u"));
?>
<div id="<?=$time?>">

<div class="form-group col-md-4 col-sm-12">
  <label for="Garanter1">Gurranter Name</label>
  <?php echo $this->Form->input('', array('type' => 'text','name'=>'data[guranter][guranter_name][]','placeholder'=>'Enter Guranter Name','class'=>'form-control','label'=>false)); ?>
</div><!-- /.form group -->
                
<div class="form-group col-md-4 col-sm-12">
  <label for="Garanter1ContactNo">Gurranter Account No.</label>
  <?php echo $this->Form->input('', array('type' => 'text','name'=>'data[guranter][guranter_account_no][]','placeholder'=>'Enter Guranter Account No.','class'=>'form-control','label'=>false)); ?>
  
</div><!-- /.form group -->

<div class="form-group col-md-4 col-sm-12">
  <label for="Garanter1ContactNo">Gurranter Amount</label>
  <?php echo $this->Form->input('', array('type' => 'text','name'=>'data[guranter][guranter_amount][]','value'=>0,'placeholder'=>'Enter Guranter Contact No.','class'=>'form-control','label'=>false)); ?>
  <a href="javascript:void(0)" onclick="delete_gurranter_row('<?=$time?>')" style="color: red;">[-]Remove Guranter</a>
</div><!-- /.form group -->

</div>