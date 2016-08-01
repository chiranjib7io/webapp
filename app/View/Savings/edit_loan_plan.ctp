<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Edit Loan Plan
          
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="<?= $this->Html->url('/plan_list') ?>"><i class="fa fa-dashboard"></i> Plan List</a></li>
            <li class="active">Edit Saving Plan</li>
          </ol>
        </section>
		<?php
			//pr($plan_details); die;
			$plan_array=json_decode($plan_details['Plan']['plan_value'],true);
			//pr($plan_array); die;
		?>
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
			 <?php echo $this->Form->create('',array('class'=>'')); ?>
					<span style="color:red"><?php echo $this->Session->flash(); ?></span>
                <div class="box-body col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="Plan Name">Plan Name</label>
                        <input type="text" class="form-control" id="plan_name" name="data[plan_name]" value="<?=$plan_details['Plan']['plan_name'] ?>" required="required">
                    </div><!-- /.form group -->
					<input type="hidden" id="plan_id" name="data[plan_id]" value="<?=$plan_details['Plan']['id'] ?>">
					<div class="form-group">
                        <label for="Minimum Loan Amount">Minimum Loan Amount</label>
                        <input type="number" class="form-control" id="min_amount" name="data[min_amount]" value="<?=$plan_array['min_amount'] ?>" required="required">
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
						<label for="Loan Type">Loan Type</label>
						<?php
							echo $this->Form->input('Plan.loan_type', array('type' => 'select', 'options' => $this->Slt->loan_types(), 'default'=>$plan_array['loan_type'], 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'empty' => 'Select Loan Type'));
						?>
                    </div><!-- /.form group -->
					
					<div class="form-group">
                      <label>Loan Risk Type</label>
						<?php
							echo $this->Form->input('Plan.loan_risk_type', array('type' => 'select', 'options' => $this->Slt->loan_risk_type(), 'default'=>$plan_array['loan_risk_type'], 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'empty' => 'Select Risk Type'));
						?>
                    </div><!-- /.form group -->
                    

                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
									
					<div class="form-group">
                      <label for="Interest Rate">Interest Rate (%)</label>
                      <input type="text" class="form-control" id="interest_rate" name="data[interest_rate]" value="<?=$plan_array['interest_rate'] ?>">
                    </div>
					<!-- /.form group -->
					
					<div class="form-group">
                      <label>Interest Type</label>
						<?php
							echo $this->Form->input('Plan.interest_type', array('type' => 'select', 'options' => $this->Slt->loan_interest_types(), 'default'=>$plan_array['interest_type'], 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'empty' => 'Select Interest Type'));
						?>
                    </div><!-- /.form group -->
					
					
                	<div class="form-group">
                      <label for="ContactNo">Minimum Saving Percentage</label>
                      <input type="number" class="form-control" id="min_save_per" name="data[min_save_per]" value="<?=$plan_array['min_save_per'] ?>">
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="IdCardNo">Overdue Period Interest(%)</label>
                      <input type="number" class="form-control" id="overdue_period_interest" name="data[overdue_period_interest]" value="<?=$plan_array['overdue_period_interest'] ?>" required="required">
                    </div><!-- /.form group -->
                    
                   
                </div><!-- /.box-body -->
                
                <div class="box-footer" align="right">
                    <button type="submit" class="btn btn-primary btn-lg" >Submit</button>
                </div>
				
                </form>
				
              </div><!-- /.box -->

            </div>
          </div><!-- /.row -->

        </section><!-- /.content -->