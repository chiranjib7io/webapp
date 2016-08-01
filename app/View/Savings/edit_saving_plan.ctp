<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Edit Saving Plan
          
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
                        <label for="Minimum Saving Amount">Minimum Saving Amount</label>
                        <input type="number" class="form-control" id="min_amount" name="data[min_amount]" value="<?=$plan_array['saving']['min_amount'] ?>" required="required">
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
						<label for="Saving Type">Saving Type</label>
						<?php
							echo $this->Form->input('Plan.saving_type', array('type' => 'select', 'options' => $this->Slt->saving_types(), 'default'=>$plan_array['saving']['saving_type'], 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'empty' => 'Select Saving Type'));
						?>
                    </div><!-- /.form group -->

                    <div class="form-group">
                      <label for="Collection Interval">Collection Interval (in days) </label>
                      <input type="number" class="form-control" id="interval_day" name="data[interval_day]" value="<?=$plan_array['saving']['interval_day'] ?>" required="required">
                    </div><!-- /.form group -->
					
                    <div class="form-group">
                      <label for="Interest Rate">Interest Rate</label>
                      <input type="number" class="form-control" id="interest_rate" name="data[interest_rate]" value="<?=$plan_array['saving']['interest_rate'] ?>">
                    </div>
					<!-- /.form group -->

                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
				
					<div class="form-group">
                      <label>Interest Type</label>
						<?php
							echo $this->Form->input('Plan.interest_type', array('type' => 'select', 'options' => $this->Slt->saving_interest_types(), 'default'=>$plan_array['saving']['interest_type'], 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'empty' => 'Select Interest Type'));
						?>
                    </div><!-- /.form group -->

					<div class="form-group">
                      <label for="Saving Period">Saving Period (in month)</label>
                      <input type="number" class="form-control" id="saving_period" name="data[saving_period]" value="<?=$plan_array['saving']['saving_period'] ?>" required="required">
                    </div><!-- /.form group -->
					
					<div class="form-group">
                      <label>Pre Maturity Fine (Before 12 month) Fixed</label>
						<input type="number" class="form-control" id="pre_mat_b12_fixed" name="data[pre_mat_b12_fixed]" value="<?=$plan_array['prematurity']['before12fixed'] ?>" required="required">
                    </div><!-- /.form group -->
					
                	<div class="form-group">
                      <label for="ContactNo">Pre Maturity Fine (Before 12 month) Percentage</label>
                      <input type="number" class="form-control" id="pre_mat_b12_percentage" name="data[pre_mat_b12_percentage]" value="<?=$plan_array['prematurity']['before12percentage'] ?>">
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="IdCardNo">Pre Maturity Fine (After 12 month) Percentage</label>
                      <input type="number" class="form-control" id="pre_mat_a12_percentage" name="data[pre_mat_a12_percentage]" value="<?=$plan_array['prematurity']['after12percentage'] ?>" required="required">
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