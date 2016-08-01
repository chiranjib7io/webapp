<div class="box-body col-md-6 col-sm-12">
                    

                    <div class="form-group">
                      <label for="Interest Rate">Interest Rate</label>
					  <?php echo $this->Form->input('Saving.interest_rate', array('type' => 'number', 'value'=>$plan_data['saving']['interest_rate'] ,'class'=>'form-control','label'=>false,'placeholder'=>'Enter Interest Rate', 'required'=>'required')); ?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label>Interest Type</label>
						<?php
							echo $this->Form->input('Saving.interest_type', array('type' => 'select', 'options' => $this->Slt->saving_interest_types(), 'class'=>'form-control', 'default'=>$plan_data['saving']['interest_type'] ,'label'=>false, 'required'=>'required', 'empty' => 'Select Interest Type'));
						?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="ContactNo">Minimum Deposit Amount</label>
					  <?php echo $this->Form->input('Saving.min_deposit_amount', array('type' => 'number','value'=>$plan_data['saving']['min_amount'],'class'=>'form-control','label'=>false, 'placeholder'=>'Enter Minimum Deposit', 'required'=>'required')); ?>
                    </div><!-- /.form group -->

                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
				    
                    
                    
					<div class="form-group">
                      <label for="State">Deposit Interval (In days)</label>
                      <?php echo $this->Form->input('Saving.deposit_interval', array('type' => 'number','value'=>$plan_data['saving']['interval_day'],'class'=>'form-control','label'=>false,'placeholder'=>'Enter Deposit Interval', 'required'=>'required')); ?>
                    </div> <!-- /.form group -->
					
					<div class="form-group">
                      <label for="Zip">Saving Period (In Month)</label>
					  <?php echo $this->Form->input('Saving.savings_term', array('type' => 'number','value'=>$plan_data['saving']['saving_period'],'class'=>'form-control','label'=>false,'placeholder'=>'Enter Saving Period', 'required'=>'required')); ?>
                    </div><!-- /.form group -->
					
                	<div class="form-group">
						<label for="Saving Type">Saving Type</label>
						<?php
							echo $this->Form->input('Saving.saving_type', array('type' => 'select', 'default'=>$plan_data['saving']['saving_type'] , 'options' => $this->Slt->saving_types(), 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'empty' => 'Select Saving Type'));
						?>
                    </div><!-- /.form group -->
					
					

                </div><!-- /.box-body -->