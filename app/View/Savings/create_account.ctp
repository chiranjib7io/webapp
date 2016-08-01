<script>
function load_plan(plan){
    if(plan!=''){
        var obj = jQuery.parseJSON( plan );
        //alert(obj.saving.min_amount);
        $.post("<?= $this->Html->url('ajax_load_plan') ?>", {data: plan}, function(result){
            $("#plan_div").html(result);
            $("#SavingSavingsAmount").val(obj.saving.min_amount);
            $("#SavingSavingsAmount").attr( "min", obj.saving.min_amount );
        });
    }else{
        $("#plan_div").html('');
    }
}
</script>

<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Create New Saving Account
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Create New Saving Account</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">

                <div class="box-header">
                  <h3 class="box-title">Customer Name: <?= $customer_data['Customer']['fullname']?></h3>
                </div>
				<?php echo $this->Form->create('Saving',array('action'=>'create_account/'.$cust_id)); ?>
						
						<span style="color:red"><?php echo $this->Session->flash(); ?></span>
				
                <div class="box-body col-md-6 col-sm-12">
                    
                    <div class="form-group">
                        <label for="Saving Account Number">Saving Account Number</label>
                        <?php echo $this->Form->input('Account.account_number', array('type' => 'text','class'=>'form-control','label'=>false,'placeholder'=>'Enter Account Number', 'required'=>'required')); ?>
                    </div><!-- /.form group -->
					
                    
                    
					<div class="form-group">
                        <label for="Saving Date">Saving Date</label>
						<input type="date" class="form-control" id="savings_date" name="data[Saving][savings_date]" placeholder="Enter Saving Date" required="required">
                    </div><!-- /.form group -->
                    
                    
                    
                </div>
                <div class="box-body col-md-6 col-sm-12">
                    
                    <div class="form-group">
                      <label>Plan Type</label>
						<?php
							echo $this->Form->input('Saving.plan', array('type' => 'select', 'options' => $plan_data, 'class'=>'form-control', 'label'=>false, 'onchange'=>'load_plan(this.value)' , 'required'=>'required', 'empty' => 'Select Plan'));
						?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                        <label for="Saving Amount">Saving Amount</label>
                        <?php echo $this->Form->input('Saving.savings_amount', array('type' => 'number','class'=>'form-control','label'=>false,'placeholder'=>'Enter Saving Amount', 'required'=>'required')); ?>
                    </div><!-- /.form group -->
                    
                </div>
            <div id="plan_div">
                
            </div>
            
            <div class="box-body col-md-6 col-sm-12">
                    
                    <div class="form-group">
                        <label for="Saving Account Number">Nominee Name</label>
                        <?php echo $this->Form->input('Saving.nominee_name', array('type' => 'text','class'=>'form-control','label'=>false,'placeholder'=>'Enter Nominee Name')); ?>
                    </div><!-- /.form group -->
					
                    
                    
					<div class="form-group">
                        <label for="Saving Date">Age of Nominee</label>
						<input type="text" class="form-control" id="savings_date" name="data[Saving][nominee_age]" placeholder="Enter Nominee age">
                    </div><!-- /.form group -->
                    
                    
                    
                </div>
                <div class="box-body col-md-6 col-sm-12">
                    
                    <div class="form-group">
                      <label>Relationship with Nominee</label>
						<?php
							echo $this->Form->input('Saving.nominee_relationship', array('type' => 'select', 'options' => $this->Slt->relationship_type(), 'class'=>'form-control', 'label'=>false, 'empty' => 'Select Relationship'));
						?>
                    </div><!-- /.form group -->
                    
                    
                    
                </div>
                
                <div class="box-footer" align="right">
                    <button type="submit" class="btn btn-primary btn-lg" >Submit</button>
                </div>
				
                </form>
				
              </div><!-- /.box -->

            </div>
          </div><!-- /.row -->

        </section><!-- /.content -->