<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Update Customer
            <small class="text-green"><?=$this->Session->flash()?></small>
            
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Update Customer</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                <div class="box-header">
                  <h3 class="box-title">Update Customer Form</h3>
                </div>
				
				<?php echo $this->Form->create('Customer',array('type'=>'file')); ?>
						
						
			<?php	echo $this->Form->input('id', array('type'=>'hidden')); ?>
                <div class="box-body col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="Name">First Name</label>
                        <?php echo $this->Form->input('cust_fname', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter First Name','required'=>'required','label'=>false)); ?>

                    </div><!-- /.form group -->
					
					<div class="form-group">
                        <label for="Name">Last Name</label>
                        <?php echo $this->Form->input('cust_lname', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter Last Name','required'=>'required','label'=>false)); ?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                        <label for="Address ">Address </label>
                        <?php echo $this->Form->input('cust_address', array('type' => 'textarea','class'=>'form-control','placeholder'=>'Enter Address','required'=>'required','label'=>false)); ?>
                        
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
					
                    <div class="form-group">
                      <label>Kendra</label>
						<?php
							echo $this->Form->input('kendra_id', array('type' => 'select', 'options' => $kendra_list, 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>'', 'empty' => 'Select Kendra'));
						?>
                    </div><!-- /.form group -->
                    
                    

                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
                
                	<div class="form-group">
                      <label for="ContactNo">Contact No </label>
                      <?php echo $this->Form->input('cust_phone', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter Contact No','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="Email">Email Id</label>
                      <?php echo $this->Form->input('cust_email', array('type' => 'email','class'=>'form-control','placeholder'=>'Enter Email','label'=>false)); ?>
                      
                    </div>
                    
                    <div class="form-group">
                      <label>ID Card Type</label>
                      <?php
						echo $this->Form->input('Idproof.id_proof_type', array('type' => 'select', 'options' => $this->Slt->id_proof_name(), 'class'=>'form-control','default'=>$cust_data['Idproof'][0]['id_proof_type'] , 'label'=>false, 'default'=>'', 'empty' => 'Select Identity Type'));
					  ?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="IdCardNo">ID Card No</label>
                      <?php echo $this->Form->input('Idproof.id_proof_no', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter Id Card No','value'=>$cust_data['Idproof'][0]['id_proof_no'],'required'=>'required','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="IdCardNo">ID Card Image</label>
                      
                      <?php if (!empty($cust_data['Idproof'][0]['id_proof_pic'])): ?>
                        	<div class="input">
                        		<label>Uploaded File</label>
                        		<?php
                        		//echo $this->Form->input('id_proof_pic', array('type'=>'hidden','value'=>$cust_data['Idproof'][0]['id_proof_pic']));
                                ?>
                        		<img src="<?php echo $this->webroot.$cust_data['Idproof'][0]['id_proof_pic']; ?>" width="150" >
                        	</div>
                        <?php endif; ?>
                        
                        <?php echo $this->Form->input('id_proof_pic', array('type' => 'file','placeholder'=>'Upload image','label'=>false)); ?>
                                                
                    </div><!-- /.form group -->
                    
                     <div class="form-group">
                      <label for="DOB">Date of Birth</label>
                      <?php echo $this->Form->input('cust_dob', array('type' => 'date','placeholder'=>'Enter Date of Birth','required'=>'required','label'=>false,  'minYear' => date('Y') - 70,'maxYear' => date('Y') - 18)); ?>
                      
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                       <label for="GuardianName">Guardian Name</label>
                      <?php echo $this->Form->input('guardian_name', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter Guardian Name','required'=>'required','label'=>false ));
                       ?>
                    </div><!-- /.form group -->
					
					<div class="form-group">
                      <label for="Email">Reletion with Guardian</label>
                       <?php
						echo $this->Form->input('Customer.guardian_reletion_type', array('type' => 'select', 'options' => $this->Slt->relationship_type(), 'required'=>'required', 'class'=>'form-control', 'label'=>false, 'default'=>'', 'empty' => 'Select Reletionship Type'));
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