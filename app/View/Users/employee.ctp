<script>
function add_idproof_row(){
    $.post("<?= $this->Html->url('ajax_idproof_row') ?>", function(data, status){
            $('#id_proof').append(data);
    });
}
function delete_idproof_row(did){
    $('#'+did).remove();
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Create Employee
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Create Employee</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                <?php echo $this->Form->create('User',array('action'=>'employee',$emp_id)); ?>
						
						<span style="color:red"><?php echo $this->Session->flash(); ?></span>
                <div class="box-body col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="Name">First Name</label>
                        <?php echo $this->Form->input('User.first_name', array('type' => 'text','placeholder'=>'Enter First Name','class'=>'form-control','label'=>false,'required'=>'required')); ?>
                        
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                        <label for="Address ">Address </label>
                        
                        <?php echo $this->Form->input('User.address', array('type' => 'textarea','placeholder'=>'Enter Address','class'=>'form-control','label'=>false,'required'=>'required')); ?>
                        
                    </div><!-- /.form group -->

                    <div class="form-group">
                      <label for="City ">City </label>
                      
                      <?php echo $this->Form->input('User.city', array('type' => 'text','placeholder'=>'Enter City','class'=>'form-control','label'=>false,'required'=>'required')); ?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="State">State</label>
                      
                      <?php echo $this->Form->input('User.state', array('type' => 'text','placeholder'=>'Enter State','class'=>'form-control','label'=>false,'required'=>'required')); ?>
                      
                    </div><!-- /.form group -->
                    
                    
                    
					
				   <div class="form-group">
                      <label>Type of Employee</label>
                       <?php
							echo $this->Form->input('User.user_type_id', array('type' => 'select', 'options' => $ut_list, 'class'=>'form-control', 'label'=>false, 'empty' => 'Select Employee Type'));
						?>
                    </div><!-- /.form group -->

                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
				
					<div class="form-group">
                        <label for="Name">Last Name</label>
                        
                        <?php echo $this->Form->input('User.last_name', array('type' => 'text','placeholder'=>'Enter Last Name','class'=>'form-control','label'=>false,'required'=>'required')); ?>
                      
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="ContactNo">Contact No </label>
                      
                      <?php echo $this->Form->input('User.phone_no', array('type' => 'number','placeholder'=>'Enter Contact No','class'=>'form-control','label'=>false,'required'=>'required')); ?>
                      
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="Email">Email Id</label>
                      
                      <?php echo $this->Form->input('User.email', array('type' => 'email','placeholder'=>'Enter Email','class'=>'form-control','label'=>false,'required'=>'required')); ?>
                      
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      
                      
                      <?php 
                      if($emp_id!=''){
                        echo '<label for="Email">Change Password</label>';
                        echo $this->Form->input('User.password', array('type' => 'password','placeholder'=>'Change Password','class'=>'form-control','label'=>false));
                      }else{
                        echo '<label for="Email">Password</label>';
                        echo $this->Form->input('User.password', array('type' => 'password','placeholder'=>'Enter Password','class'=>'form-control','label'=>false,'required'=>'required')); 
                      
                      }
                      ?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="Zip">Zip</label>
                      <?php echo $this->Form->input('User.zip', array('type' => 'text','placeholder'=>'Enter Zip','class'=>'form-control','label'=>false,'required'=>'required')); ?>
                      
                    </div><!-- /.form group -->
					
					<div class="form-group">
                      <label>Country</label>
                       <?php
					   echo $this->Form->input('User.country', array('type' => 'select', 'options' => $countryList, 'class'=>'form-control', 'label'=>false, 'value'=>99, 'empty' => 'Select Country'));
								?>
                    </div><!-- /.form group -->
                    
					
                    
                    

                </div><!-- /.box-body -->
                
                
                <div class="box-body col-md-12 col-sm-12">
                    <div class="box-header">
                      <h3 class="box-title">Id proof Information</h3>
                    </div>
                
                    <div id="id_proof">
                    <?php
                    if(empty($this->request->data['User']['id_proof'])){
                    ?>
                    <div>
                        <div class="form-group col-md-6 col-sm-12">
                          <label>ID Card Type</label>
                          <?php
    							echo $this->Form->input('', array('type' => 'select','name'=>'data[idproof][id_proof_type][]', 'options' => $identity_type, 'class'=>'form-control', 'label'=>false, 'required'=>'required','empty' => 'Select Identity Card Type'));
    						?>
                        </div><!-- /.form group -->
                        
                        <div class="form-group col-md-6 col-sm-12">
                          <label for="IdCardNo">Id Card No</label>
                          <?php echo $this->Form->input('', array('type' => 'text','name'=>'data[idproof][id_proof_no][]','placeholder'=>'Enter Id Card No','class'=>'form-control','label'=>false,'required'=>'required')); ?>
                          
                          
                        </div><!-- /.form group -->
                    </div>
                    <?php
                    }else{
                        $id_arr = json_decode($this->request->data['User']['id_proof'],true);
                        foreach($id_arr as $k=>$id_row){
                    ?>
                        <div id="<?=$k?>">
                            <div class="form-group col-md-6 col-sm-12">
                              <label>ID Card Type</label>
                              <?php
        							echo $this->Form->input('', array('type' => 'select','name'=>'data[idproof][id_proof_type][]','value'=> $id_row['id_proof_type'],'options' => $identity_type, 'class'=>'form-control', 'label'=>false, 'required'=>'required','empty' => 'Select Identity Card Type'));
        						?>
                            </div><!-- /.form group -->
                            
                            <div class="form-group col-md-6 col-sm-12">
                              <label for="IdCardNo">Id Card No</label>
                              <?php echo $this->Form->input('', array('type' => 'text','name'=>'data[idproof][id_proof_no][]','value'=> $id_row['id_proof_no'],'placeholder'=>'Enter Id Card No','class'=>'form-control','label'=>false,'required'=>'required')); ?>
                              <a href="javascript:void(0)" onclick="delete_idproof_row('<?=$k?>')" style="color: red;">[-]Remove Idproof</a>
                              
                            </div><!-- /.form group -->
                        </div>
                    <?php 
                        }   
                    }
                    ?>
                        
                    </div>
                    
                    
                </div><!-- /.box-body -->
                
                <div class="box-body col-md-12 col-sm-12">
                    
                        <a href="javascript:void(0)" onclick="add_idproof_row()">[+]Add another Id proof</a>
                    
                    <div class="box-footer" align="right">
                        <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                    </div>
                </div>
                
                
                
                
              </div><!-- /.box -->
			  
			  </form>

            </div>
          </div><!-- /.row -->

        </section><!-- /.content -->