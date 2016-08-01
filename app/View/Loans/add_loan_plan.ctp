<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Create New Loan Plan
          
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
                    <h3>Plan Settings</h3>
                <div class="box-body col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="Plan Name">Plan Name</label>
                        <input type="text" class="form-control" id="plan_name" name="data[plan_name]" placeholder="Enter Plan Name" required="required">
                    </div><!-- /.form group -->
					
					<div class="form-group">
                        <label for="Minimum Loan Amount">Minimum Loan Amount</label>
                        <input type="number" class="form-control" id="min_amount" name="data[min_amount]" placeholder="Enter Minimum Loan Amount" required="required">
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
						<label for="Loan Type">Loan Type</label>
						<?php
							echo $this->Form->input('Plan.loan_type', array('type' => 'select', 'options' => $this->Slt->loan_types(), 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'empty' => 'Select Loan Type'));
						?>
                    </div><!-- /.form group -->
					<!--
                    <div class="form-group">
                      <label for="Collection Interval">Collection Interval (in days) </label>
                      <input type="number" class="form-control" id="interval_day" name="data[interval_day]" placeholder="Enter Collection Interval" required="required">
                    </div>--><!-- /.form group -->
					
					<div class="form-group">
                      <label>Loan Risk Type</label>
						<?php
							echo $this->Form->input('Plan.loan_risk_type', array('type' => 'select', 'options' => $this->Slt->loan_risk_type(), 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'empty' => 'Select Risk Type'));
						?>
                    </div><!-- /.form group -->
                    

                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
					<!--
					<div class="form-group">
                      <label for="Loan Period">Loan Period</label>
                      <input type="number" class="form-control" id="loan_period" name="data[loan_period]" placeholder="Enter Loan Period" required="required">
                    </div>--><!-- /.form group -->
					<!--
					<div class="form-group">
                      <label>Loan Period Type</label>
						<?php
							echo $this->Form->input('Plan.loan_period_type', array('type' => 'select', 'options' => $this->Slt->loan_period_types(), 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'empty' => 'Select Period Type'));
						?>
                    </div>--><!-- /.form group -->
					
					<div class="form-group">
                      <label for="Interest Rate">Interest Rate (%)</label>
                      <input type="text" class="form-control" id="interest_rate" name="data[interest_rate]" placeholder="Enter Interest Rate">
                    </div>
					<!-- /.form group -->
					
					<div class="form-group">
                      <label>Interest Type</label>
						<?php
							echo $this->Form->input('Plan.interest_type', array('type' => 'select', 'options' => $this->Slt->loan_interest_types(), 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'empty' => 'Select Interest Type'));
						?>
                    </div><!-- /.form group -->
					
					
                	<div class="form-group">
                      <label for="ContactNo">Minimum Saving Percentage</label>
                      <input type="number" class="form-control" id="min_save_per" name="data[min_save_per]" placeholder="Enter Minimum Saving Percentage">
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="IdCardNo">Overdue Period Interest(%)</label>
                      <input type="number" class="form-control" id="overdue_period_interest" name="data[overdue_period_interest]" placeholder="Enter Overdue Period Percentage" required="required">
                    </div><!-- /.form group -->
                    
                   
                </div><!-- /.box-body -->
                <h3>Common Settings</h3>
                <div class="box-body col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="SecurityDeposit">Loan Fine</label>
                        <input type="text" class="form-control" id="fee_value" name="data[Fee][<?= $loan_fees_data[1]['Fee']['id'] ?>]" value="<?= $this->Number->currency($loan_fees_data[1]['Fee']['fee_value'],'',array('places'=>0)); ?>" placeholder="Enter Security Deposit(%)">
                    </div><!-- /.form group -->

                    <div class="form-group">
                      <label for="LoanApplicationFee">Loan Application Form Fee</label>
                      <input type="text" class="form-control" id="fee_value" name="data[Fee][<?= $loan_fees_data[0]['Fee']['id'] ?>]" value="<?= $this->Number->currency($loan_fees_data[0]['Fee']['fee_value'],'',array('places'=>0));?>" placeholder="Enter Loan Application Fee">
                    </div><!-- /.form group -->
					
					<div class="form-group">
                      <label for="LoanApplicationFee">Addmission Fee</label>
                      <input type="text" class="form-control" id="fee_value" name="data[Fee][<?= $loan_fees_data[6]['Fee']['id'] ?>]" value="<?= $this->Number->currency($loan_fees_data[6]['Fee']['fee_value'],'',array('places'=>0));?>" placeholder="Enter Addmission Fee">
                    </div><!-- /.form group -->
                    

                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
                                        
                    <div class="form-group">
                      <label for="ProcessingFee">Security Deposit Rate (%)</label>
                      <input type="text" class="form-control" id="fee_value" name="data[Fee][<?= $loan_fees_data[5]['Fee']['id'] ?>]" value="<?= $this->Number->currency($loan_fees_data[5]['Fee']['fee_value'],'',array('places'=>0));?>" placeholder="Enter Processing Fee (%)">
                    </div><!-- /.form group -->
                   
                    
                    <div class="form-group">
                      <label for="LoanInterestRateYear">Processing Fee (%)</label>
                      <input type="text" class="form-control" id="fee_value" name="data[Fee][<?= $loan_fees_data[4]['Fee']['id'] ?>]" value="<?= $this->Number->currency($loan_fees_data[4]['Fee']['fee_value'],'',array('places'=>0)); ?>" placeholder="Enter Loan Interest Rate/Year">
                    </div><!-- /.form group -->
					
					<div class="form-group">
                      <label for="LoanApplicationFee">Risk Fund Fee (%)</label>
                      <input type="text" class="form-control" id="fee_value" name="data[Fee][<?= $loan_fees_data[7]['Fee']['id'] ?>]" value="<?= $this->Number->currency($loan_fees_data[7]['Fee']['fee_value'],'',array('places'=>0));?>" placeholder="Enter Risk Fund Fee">
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