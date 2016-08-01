   <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Create New Branch
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Create New Branch</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
			
			<?php echo $this->Form->create('Branch',array('class'=>'')); ?>
						
						<span style="color:red"><?php echo $this->Session->flash(); ?></span>
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                <div class="box-body col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="BranchName">Branch Name</label>
                        <input type="text" class="form-control" id="branch_name" name="data[Branch][branch_name]" placeholder="Enter Branch Name" required="required">
                    </div><!-- /.form group -->
                    
                    

                    <div class="form-group">
                      <label for="City ">City </label>
                      <input type="text" class="form-control" id="city" name="data[Branch][city]" placeholder="Enter City " required="required">
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="State">State</label>
                      <input type="text" class="form-control" id="state" name="data[Branch][state]" placeholder="Enter State" required="required">
                    </div><!-- /.form group -->
					
					 <div class="form-group">
                      <label for="Zip">Zip</label>
                      <input type="number" class="form-control" id="zip" name="data[Branch][zip]" placeholder="Enter Zip" required="required">
                    </div><!-- /.form group -->
					
					<div class="form-group">
                      <label>Choose Region</label>
                     <?php
							echo $this->Form->input('Branch.region_id', array('type' => 'select', 'options' => $region_list, 'required' => 'required', 'class'=>'form-control', 'label'=>false, 'empty' => 'Select Region'));
						?>
                    </div><!-- /.form group -->
					
                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
                    
                    <div class="form-group">
                        <label for="Address ">Address </label>
                        <input type="text" class="form-control" id="address" name="data[Branch][address]" placeholder="Enter Address " required="required">
                    </div><!-- /.form group -->
                    
					
					 <div class="form-group">
                      <label for="BranchEmail">Branch Contact Number</label>
                      <input type="number" class="form-control" id="phone_no" name="data[Branch][phone_no]" placeholder="Enter Branch Contact Number" required="required">
                    </div><!-- /.form group -->
                   
                    
                    <div class="form-group">
                      <label for="BranchEmail">Branch Email ID</label>
                      <input type="email" class="form-control" id="contact_email" name="data[Branch][contact_email]" placeholder="Enter Branch Email ID">
                    </div><!-- /.form group -->
                    <div class="form-group">
                      <label>Choose Manager</label>
                     <?php
							echo $this->Form->input('Branch.user_id', array('type' => 'select', 'options' => $bm_list, 'required' => 'required', 'class'=>'form-control', 'label'=>false, 'empty' => 'Select Branch Manager'));
						?>
                    </div><!-- /.form group -->
                </div><!-- /.box-body -->
                
                <div class="box-footer col-xs-12" style="text-align:right;">
                    <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                </div>
                
              </div><!-- /.box -->
			  
			  </form>

            </div>
          </div><!-- /.row -->

        </section><!-- /.content -->