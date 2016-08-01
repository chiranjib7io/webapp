   <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Update Branch
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Update Branch</li>
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
                        <?php echo $this->Form->input('Branch.branch_name', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter Branch Name','required'=>'required','label'=>false)); ?>
                        
                    </div><!-- /.form group -->
                    
                    

                    <div class="form-group">
                      <label for="City ">City </label>
                      <?php echo $this->Form->input('Branch.city', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter City','required'=>'required','label'=>false)); ?>
                        
                      
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="State">State</label>
                      <?php echo $this->Form->input('Branch.state', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter state','required'=>'required','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
					
					 <div class="form-group">
                      <label for="Zip">Zip</label>
                      <?php echo $this->Form->input('Branch.zip', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter zip','required'=>'required','label'=>false)); ?>
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
                        <?php echo $this->Form->input('Branch.address', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter address','required'=>'required','label'=>false)); ?>
                        
                    </div><!-- /.form group -->
                    
					
					 <div class="form-group">
                      <label for="BranchEmail">Branch Contact Number</label>
                      <?php echo $this->Form->input('Branch.phone_no', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter phone no','required'=>'required','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
                   
                    
                    <div class="form-group">
                      <label for="BranchEmail">Branch Email ID</label>
                      <?php echo $this->Form->input('Branch.contact_email', array('type' => 'email','class'=>'form-control','placeholder'=>'Enter Branch Email ID','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label>Choose Manager</label>
                     <?php
							echo $this->Form->input('Branch.user_id', array('type' => 'select', 'options' => $bm_list, 'class'=>'form-control', 'label'=>false, 'default'=>'', 'empty' => 'Select Branch Manager'));
						?>
                    </div><!-- /.form group -->

                </div><!-- /.box-body -->
                
                <div class="box-footer col-xs-12" style="text-align:right;">
                    <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                </div>
                
              </div><!-- /.box -->
			  
			  <?php echo $this->Form->end(); ?>

            </div>
          </div><!-- /.row -->

        </section><!-- /.content -->