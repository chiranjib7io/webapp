<script>
 $(document).ready(function(){
     var cnt = 2;
     $("#anc_add").click(function(){
        $('#tbl1 tr').last().after('<tr><td><input type="text" class="form-control" name="first_name[]" required="required" /></td><td><input type="text" class="form-control" name="last_name[]" required="required" /></td><td><select name="sex[]" class="form-control" required="required"><option value="Female">Female</option><option value="Male">Male</option></select></td><td><input type="text" class="form-control" name="account_no[]" required="required" /></td><td><input type="number" class="form-control" name="principal[]" id="prnc'+cnt+'" onblur="calc_rate(\''+cnt+'\')" required="required" /></td><td><input type="text" class="form-control" name="interest_rate[]" id="rate'+cnt+'" onblur="calc_rate(\''+cnt+'\')" required="required" /></td><td><select name="interest_type[]" class="form-control" id="typ'+cnt+'" onchange="calc_rate(\''+cnt+'\')" required="required"><option value="Fixed">Fixed Interest</option><option value="Reducing">Reducing Interest</option></select></td><td><input type="text" class="form-control" name="term[]" id="trm'+cnt+'" onblur="calc_rate(\''+cnt+'\')" required="required" /></td><td><select name="repay_interval_days[]" id="intrval'+cnt+'" onblur="calc_rate(\''+cnt+'\')" required="required" class="form-control" required="required"><option value="WEEK">Week</option><option value="MONTH">Month</option></select></td><td id="int'+cnt+'">&nbsp;</td><td><input type="text" class="form-control" name="currentloan_balance[]" required="required" /></td><td><input type="date" class="form-control" name="last_installment_date[]" /></td></tr>');
        cnt++;
        //alert(cnt);
     });
     
    $("#anc_rem").click(function(){
        if($('#tbl1 tr').size()>2){
            $('#tbl1 tbody tr:last-child').remove();
        }else{
            alert('One row should be present in table');
        }
     });
 
});

function market_list_of_branch(bid){
        $.post("<?= $this->Html->url('ajax_market_list_of_branch') ?>/"+bid, function(data, status){
                $('#market_id').html(data);
        });
    
}
function group_list_of_market(mid){
        $.post("<?= $this->Html->url('ajax_group_list_of_market') ?>/"+mid, function(data, status){
                $('#kendra_id').html(data);
        });
    
}


 </script>
   <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
           Loan Entry from file
          </h1>
          <ol class="breadcrumb">
           <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Loan Entry from file</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
			
			<?php echo $this->Form->create('',array('type' => 'file','class'=>'')); ?>
						
	           <span style="color:red"><?php echo $this->Session->flash(); ?></span>
 
              <div class="box box-danger col-xs-12 table-responsive" style="padding-top:20px; padding-bottom:20px;">
                
                <div class="box-body col-sm-12">
                    <div class="form-group col-md-3 ">
                        <label for="Name">Branch Name</label>
                        <?php
							echo $this->Form->input('branch_id', array('type' => 'select','name'=>'branch_id', 'options' => $branch_list, 'class'=>'form-control','onchange'=>'market_list_of_branch(this.value)', 'label'=>false, 'required'=>'required', 'empty' => 'Select Branch'));
						?>
                    </div><!-- /.form group -->
                    
                   <div class="form-group col-md-3">
                        <label for="Name">Market Name</label>
                        <?php
							echo $this->Form->input('market_id', array('type' => 'select','name'=>'market_id','id'=>'market_id', 'options' => array(), 'class'=>'form-control', 'onchange'=>'group_list_of_market(this.value)', 'label'=>false, 'required'=>'required', 'empty' => 'Select Market'));
						?>
                    </div>
                    
                    <div class="form-group col-md-3">
                        <label for="Name">Group Name (optional)</label>
                        <?php
							echo $this->Form->input('kendra_id', array('type' => 'select','name'=>'kendra_id','id'=>'kendra_id', 'options' => array(), 'class'=>'form-control', 'label'=>false));
						?>
                    </div>
                    
                    <div class="form-group col-md-3">
                        <label for="Name">Credit Officer</label>
                        <?php
							echo $this->Form->input('user_id', array('type' => 'select','name'=>'user_id','id'=>'user_id', 'options' => $user_list, 'empty' => 'Select Credit Officer', 'class'=>'form-control', 'required'=>'required', 'label'=>false));
						?>
                    </div>
                    
                    <div class="form-group col-md-3">
                        <label for="Name">Select File to Upload</label>
                        <input type="file" name="filename" class="form-control" required="required" />
                    </div>
                </div>
                
                
                
                
                
                
               <div class="box-body col-md-12 col-sm-12"> 
                    <div class="box-footer col-xs-12" style="text-align:left;">
                        
                        
                        <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                       
                    </div>
               </div>
              </div><!-- /.box -->
              
			  
			  <?php echo $this->Form->end(); ?>

            </div>
          </div><!-- /.row -->

        </section><!-- /.content -->