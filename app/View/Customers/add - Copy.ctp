<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Create New Customer
          
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Create New Customer</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                <div class="box-header">
                  <h3 class="box-title">Create New Customer Form</h3>
                </div>
				
				<?php echo $this->Form->create('Customer',array('class'=>'')); ?>
						
						<span style="color:red"><?php echo $this->Session->flash(); ?></span>
				
                <div class="box-body col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="Name">First Name</label>
                        <input type="text" class="form-control" id="cust_fname" name="data[Customer][cust_fname]" placeholder="Enter First Name" required="required">
                    </div><!-- /.form group -->
					
					<div class="form-group">
                        <label for="Name">Last Name</label>
                        <input type="text" class="form-control" id="cust_lname" name="data[Customer][cust_lname]" placeholder="Enter Last Name" required="required">
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                        <label for="Address ">Address </label>
                        <input type="text" class="form-control" id="cust_address" name="data[Customer][cust_address]" placeholder="Enter Address " required="required">
                    </div><!-- /.form group -->

                    <div class="form-group">
                      <label for="City ">City </label>
                      <input type="text" class="form-control" id="city" name="data[Customer][city]" placeholder="Enter City " required="required">
                    </div><!-- /.form group -->
                    <!-- 
                    <div class="form-group">
                      <label for="State">State</label>
                      <input type="text" class="form-control" id="cust_fname" name="data[Customer][cust_fname]" placeholder="Enter State">
                    </div>
                    -->
					<!-- /.form group -->
					
					<div class="form-group">
                      <label for="Zip">Zip</label>
                      <input type="number" class="form-control" id="zip" name="data[Customer][zip]" placeholder="Enter Zip" required="required">
                    </div><!-- /.form group -->
					
                    <div class="form-group">
                      <label>Kendra</label>
						<?php
							echo $this->Form->input('Customer.kendra_id', array('type' => 'select', 'options' => $kendra_list, 'class'=>'form-control', 'label'=>false, 'empty' => 'Select Kendra'));
						?>
                    </div><!-- /.form group -->
                    
                    

                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
                
                	<div class="form-group">
                      <label for="ContactNo">Contact No </label>
                      <input type="number" class="form-control" id="cust_phone" name="data[Customer][cust_phone]" placeholder="Enter Contact No">
                    </div><!-- /.form group -->

                    <div class="form-group">
                      <label>ID Card Type</label>
                      <?php
						echo $this->Form->input('Idproof.id_proof_type', array('type' => 'select', 'options' => $identity_type, 'class'=>'form-control', 'label'=>false, 'empty' => 'Select Identity Type'));
					  ?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="IdCardNo">ID Card No</label>
                      <input type="text" class="form-control" id="id_proof_no" name="data[Idproof][id_proof_no]" placeholder="Enter Id Card No" required="required">
                    </div><!-- /.form group -->
                    
                     <div class="form-group">
                      <label for="DOB">Date of Birth</label>
                      <input type="date" class="form-control" id="cust_dob" name="data[Customer][cust_dob]" placeholder="Enter Date of Birth" required="required">
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="GuardianName">Guardian Name</label>
                      <input type="text" class="form-control" id="guardian_name" name="data[Customer][guardian_name]" placeholder="Enter Husband/ Guardian Name" required="required">
                    </div><!-- /.form group -->
					
					 <div class="form-group">
                      <label for="Email">Reletion with Guardian</label>
					  <?php
						echo $this->Form->input('Customer.guardian_reletion_type', array('type' => 'select', 'options' => $relationship_type, 'class'=>'form-control', 'label'=>false, 'empty' => 'Select Reletionship Type'));
					  ?>
                    </div>

                </div><!-- /.box-body -->
                
                <div class="box-footer" align="right">
                    <button type="submit" class="btn btn-primary btn-lg" >Submit</button>
                </div>
				
                </form>
				
              </div><!-- /.box -->

            </div>
          </div><!-- /.row -->

        </section><!-- /.content -->