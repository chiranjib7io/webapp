<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Update Organization
            <small class="text-green"><?=$this->Session->flash()?></small>
            
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Update Organization</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                <div class="box-header">
                  <h3 class="box-title">Update Organization Form</h3>
                </div>
				
				<?php echo $this->Form->create('Organization',array('type'=>'file')); ?>
						
						
			<?php	echo $this->Form->input('id', array('type'=>'hidden')); ?>
                <div class="box-body col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="Name">Organization Name</label>
                        <?php echo $this->Form->input('organization_name', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter Organization Name','readonly'=>'readonly','required'=>'required','label'=>false)); ?>

                    </div><!-- /.form group -->
					
					<div class="form-group">
                        <label for="Name">Owner Name</label>
                        <?php echo $this->Form->input('owner_name', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter Owner Name','required'=>'required','label'=>false)); ?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                        <label for="Address ">Address </label>
                        <?php echo $this->Form->input('address', array('type' => 'textarea','class'=>'form-control','placeholder'=>'Enter Address','readonly'=>'readonly','required'=>'required','label'=>false)); ?>
                        
                    </div><!-- /.form group -->

                    <div class="form-group">
                      <label for="City ">City </label>
                      <?php echo $this->Form->input('city', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter City','required'=>'required','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
                    
					<!-- /.form group -->
					
					<div class="form-group">
                      <label for="Zip">Zip</label>
                      <?php echo $this->Form->input('zip', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter Zip','required'=>'required','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
					
                    
                    
                    

                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
                
                	<div class="form-group">
                      <label for="ContactNo">Contact No </label>
                      <?php echo $this->Form->input('phone_no', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter Contact No','required'=>'required','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="Email">Email Id</label>
                      <?php echo $this->Form->input('contact_email', array('type' => 'email','class'=>'form-control','placeholder'=>'Enter Email','required'=>'required','label'=>false)); ?>
                      
                    </div>
                    
                    
                    
                    <div class="form-group">
                      <label for="IdCardNo">Govt. Regintration No</label>
                      <?php echo $this->Form->input('registration_no', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter Registration No','required'=>'required','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="IdCardNo">ID Card Image</label>
                      
                      <?php if (!empty($org_data['Organization']['id_proof_pic'])): ?>
                        	<div class="input">
                        		<label>Uploaded File</label>
                        		<?php
                        		//echo $this->Form->input('id_proof_pic', array('type'=>'hidden','value'=>$org_data['Organization']['id_proof_pic']));
                                ?>
                        		<img src="<?php echo $this->webroot.$org_data['Organization']['id_proof_pic']; ?>" width="150" >
                        	</div>
                        <?php endif; ?>
                        
                        <?php echo $this->Form->input('id_proof_pic', array('type' => 'file','placeholder'=>'Upload image','label'=>false)); ?>
                                                
                    </div><!-- /.form group -->
                    
                     <div class="form-group">
                      <label>Owner Email</label>
						<?php
							echo $this->Form->input('owner_email', array('type' => 'email', 'class'=>'form-control', 'label'=>false, 'placeholder' => 'Enter Owner Email'));
						?>
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