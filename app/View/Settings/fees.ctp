<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Fees Setting
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Loan Setting Fees</li>
          </ol>
        </section>
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
				
				<?php echo $this->Form->create('Setting',array('class'=>'')); ?>
						
						<span style="color:red"><?php echo $this->Session->flash(); ?></span>
				
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                  <h3 class="box-title">Loan General Setting Fees</h3>
                <div class="box-body col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="CurrencyAbbreviation">Currency Abbreviation</label>
                        <input type="text" class="form-control" id="setting_id" name="data[Setting][<?= $loan_setting_data[2]['Setting']['id'] ?>]" value="<?= $loan_setting_data[2]['Setting']['set_value'] ?>" placeholder="Enter Currency Abbreviation" >
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="LoanFeeRate">Loan Interest Rate (%)</label>
                      <input type="text" class="form-control" id="fee_value" name="data[Fee][<?= $loan_fees_data[2]['Fee']['id'] ?>]" value="<?= $this->Number->currency($loan_fees_data[2]['Fee']['fee_value'],'',array('places'=>0));?>"placeholder="Enter Loan Fee Rate (%)">
                    </div><!-- /.form group -->
					
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
					
					<div class="form-group">
                        <label for="MinimumSavingBalance ">Loan Installment Type </label>
							<?php echo $this->Form->input("Setting.".$loan_setting_data[5]['Setting']['id'], array(
                           'options' => array('WEEK'=>'Weekly','MONTH'=> 'Monthly'),
                           'default' => $loan_setting_data[5]['Setting']['set_value'],
                           'label'=>false,
                                     'class'        => 'form-control'
                       ));
                       ?>
                    </div><!-- /.form group -->

                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
                
                	<div class="form-group">
                      <label for="MinLoanPrincipal">Minimum Loan Principal</label>
                      <input type="text" class="form-control" id="setting_id" name="data[Setting][<?= $loan_setting_data[0]['Setting']['id'] ?>]" value="<?= $this->Number->currency($loan_setting_data[0]['Setting']['set_value'],'',array('places'=>0)); ?>" placeholder="Enter Minimum Loan Principal">
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="MaxLoanPrincipal">Maximum Loan Principal</label>
                      <input type="text" class="form-control" id="setting_id" name="data[Setting][<?= $loan_setting_data[1]['Setting']['id'] ?>]" value="<?= $this->Number->currency($loan_setting_data[1]['Setting']['set_value'],'',array('places'=>0));?>" placeholder="Enter Maximum Loan Principal">
                    </div>
					
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
					
					<div class="form-group">
                        <label for="MinimumSavingBalance ">Loan Period Type </label>
                       <?php echo $this->Form->input("Setting.".$loan_setting_data[4]['Setting']['id'], array(
                           'options' => array('FIXED'=>'FIXED','REDUCE'=> 'REDUCE'),
                           'default' => $loan_setting_data[4]['Setting']['set_value'],
                           'label'=>false,
                                     'class'        => 'form-control'
                       ));
                       ?>
                    </div><!-- /.form group -->

                </div><!-- /.box-body -->
				
				 <!-- Fees Setting Page -->
				
                  <h3 class="box-title">Savings Genaral Setting Fees</h3>
                <div class="box-body col-md-6 col-sm-12">

                    
					<div class="form-group">
                        <label for="MinimumSavingBalance ">Minimum Saving Balance </label>
                        <input type="text" class="form-control" id="setting_id" name="data[Setting][<?= $loan_setting_data[3]['Setting']['id'] ?>]" value="<?= $this->Number->currency($loan_setting_data[3]['Setting']['set_value'],'',array('places'=>0)); ?>" placeholder="Enter Minimum Saving Balance ">
                    </div><!-- /.form group -->
					

                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
                    
                    
					 <div class="form-group">
                      <label for="RiskFundFee">Saving Interest Rate (%)</label>
                      <input type="text" class="form-control"id="fee_value" name="data[Fee][<?= $loan_fees_data[3]['Fee']['id'] ?>]" value="<?= $this->Number->currency($loan_fees_data[3]['Fee']['fee_value'],'',array('places'=>0)); ?>" placeholder="Enter Risk Fund Fee (%)">
                    </div><!-- /.form group -->
					
                    

                </div><!-- /.box-body -->
                
                <div class="box-footer col-xs-12" style="text-align:right;">
                    <button type="submit" class="btn btn-primary btn-lg"  style="margin-top:4px;">Submit</button>
                </div>
                
              </div><!-- /.box -->
			  
			 
			  </form>
			  
			  
			  

            </div>
          </div><!-- /.row -->

        </section><!-- /.content -->