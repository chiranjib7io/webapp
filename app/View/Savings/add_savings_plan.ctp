<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Create New Saving Plan
          
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Create New Saving Plan</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
			  <!--
                <div class="box-header">
                  <h3 class="box-title">Create New Customer Form</h3>
                </div>
				-->
				<?php echo $this->Form->create('',array('class'=>'')); ?>
					<span style="color:red"><?php echo $this->Session->flash(); ?></span>
                <div class="box-body col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="Plan Name">Plan Name</label>
                        <input type="text" class="form-control" id="plan_name" name="data[plan_name]" placeholder="Enter Plan Name" required="required">
                    </div><!-- /.form group -->
					
					<div class="form-group">
                        <label for="Minimum Saving Amount">Minimum Saving Amount</label>
                        <input type="number" class="form-control" id="min_amount" name="data[min_amount]" placeholder="Enter Minimum Saving Amount" required="required">
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
						<label for="Saving Type">Saving Type</label>
						<?php
							echo $this->Form->input('Plan.saving_type', array('type' => 'select', 'options' => $this->Slt->saving_types(), 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'empty' => 'Select Saving Type'));
						?>
                    </div><!-- /.form group -->

                    <div class="form-group">
                      <label for="Collection Interval">Collection Interval (in days) </label>
                      <input type="number" class="form-control" id="interval_day" name="data[interval_day]" placeholder="Enter Collection Interval" required="required">
                    </div><!-- /.form group -->
					
                    <div class="form-group">
                      <label for="Interest Rate">Interest Rate</label>
                      <input type="number" class="form-control" id="interest_rate" name="data[interest_rate]" placeholder="Enter Interest Rate">
                    </div>
					<!-- /.form group -->

                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
				
					<div class="form-group">
                      <label>Interest Type</label>
						<?php
							echo $this->Form->input('Plan.interest_type', array('type' => 'select', 'options' => $this->Slt->saving_interest_types(), 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'empty' => 'Select Interest Type'));
						?>
                    </div><!-- /.form group -->

					<div class="form-group">
                      <label for="Saving Period">Saving Period (in month)</label>
                      <input type="number" class="form-control" id="saving_period" name="data[saving_period]" placeholder="Enter Saving Period" required="required">
                    </div><!-- /.form group -->
					
					<div class="form-group">
                      <label>Pre Maturity Fine (Before 12 month) Fixed</label>
						<input type="number" class="form-control" id="pre_mat_b12_fixed" name="data[pre_mat_b12_fixed]" placeholder="Enter Fixed Amount " required="required">
                    </div><!-- /.form group -->
					
                	<div class="form-group">
                      <label for="ContactNo">Pre Maturity Fine (Before 12 month) Percentage</label>
                      <input type="number" class="form-control" id="pre_mat_b12_percentage" name="data[pre_mat_b12_percentage]" placeholder="Enter Percentage Value">
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="IdCardNo">Pre Maturity Fine (After 12 month) Percentage</label>
                      <input type="number" class="form-control" id="pre_mat_a12_percentage" name="data[pre_mat_a12_percentage]" placeholder="Enter Percentage Value" required="required">
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