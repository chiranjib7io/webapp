<script>
function add_idproof_row(){
    $.post("<?= $this->Html->url('ajax_idproof_row') ?>", function(data, status){
            $('#id_proof').append(data);
    });
}
function delete_idproof_row(did){
    $('#'+did).remove();
}
$(document).ready(function(){
    
    $("#customer_image").change(function(){
        $('#blah').show();
      readURL(this);
    });
    
});
function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            
            $('#blah').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            New Customer Entry
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">New Customer Entry</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                <?php echo $this->Form->create('Dataentry',array('action'=>'save_customer','enctype'=>'multipart/form-data',$emp_id)); ?>
						
						<span style="color:red"><?php echo $this->Session->flash(); ?></span>
                <div class="box-body col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="Name">First Name</label>
                        <?php echo $this->Form->input('Customer.cust_fname', array('type' => 'text','placeholder'=>'Enter First Name','class'=>'form-control','label'=>false,'required'=>'required')); ?>
                        
                    </div><!-- /.form group -->
                    
                   <div class="form-group">
                        <label for="Name">Last Name</label>
                        
                        <?php echo $this->Form->input('Customer.cust_lname', array('type' => 'text','placeholder'=>'Enter Last Name','class'=>'form-control','label'=>false,'required'=>'required')); ?>
                      
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="Address ">Address </label>
                        
                        <?php echo $this->Form->input('Customer.cust_address', array('type' => 'textarea','placeholder'=>'Enter Address','class'=>'form-control','label'=>false)); ?>
                        
                    </div><!-- /.form group -->

                    <div class="form-group">
                      <label for="City ">City </label>
                      
                      <?php echo $this->Form->input('Customer.city', array('type' => 'text','placeholder'=>'Enter City','class'=>'form-control','label'=>false)); ?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="State">State</label>
                      
                      <?php echo $this->Form->input('Customer.state', array('type' => 'text','placeholder'=>'Enter State','class'=>'form-control','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="Zip">Zip</label>
                      <?php echo $this->Form->input('Customer.zip', array('type' => 'text','placeholder'=>'Enter Zip','class'=>'form-control','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
                    
					<div class="form-group">
                      <label for="ContactNo">Contact No </label>
                      
                      <?php echo $this->Form->input('Customer.cust_phone', array('type' => 'number','placeholder'=>'Enter Contact No','class'=>'form-control','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label>Market</label>
						<?php
							echo $this->Form->input('Customer.market_id', array('type' => 'select', 'options' => $market_list, 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'empty' => 'Select Market'));
						?>
                    </div><!-- /.form group -->
				   

                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
                
                	<div class="form-group">
                        <label for="Name">Upload Customer Image</label>
                        
                        <?php echo $this->Form->input('',array('type' => 'file','name'=>'customer_image','id'=>'customer_image','placeholder'=>'Enter Customer Image','class'=>'form-control','label'=>false)); ?>
                      
                    </div>
				
				<!-- /.form group -->
                	<div class="form-group">
                       <?php // pr($emp_data);?>
                        
                       <img id="blah" src="<?=(!empty($emp_data['Customer']['customer_image']))? $this->webroot . 'customerImages/'.$emp_data['Customer']['customer_image']:''?>" alt="your image" style="height: 100px; <?= (!empty($emp_data['Customer']['customer_image']) )? "visibility: visible; " : "display:none;" ?> width: 100px;"/>
                      
                    </div>
                 
                    
                    <div class="form-group">
                      <label>Caste</label>
                      <?php
						echo $this->Form->input('Customer.caste', array('type' => 'select', 'options' => $this->Slt->caste_list(), 'class'=>'form-control', 'label'=>false,  'empty' => 'Select Caste'));
					  ?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                        <label for="Name">Occupation</label>
                        
                        <?php echo $this->Form->input('Customer.occupation', array('type' => 'text','placeholder'=>'Enter Occupation','class'=>'form-control','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
                    
					<div class="form-group">
                      <label for="DOB">Date of Birth</label>
                      
                      <input type="date" class="form-control" value="<?=(!empty($emp_data['Customer']['cust_dob']))?$emp_data['Customer']['cust_dob']:''?>" name="data[Customer][cust_dob]" placeholder="Enter Date of Birth">
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="GuardianName">Guardian Name</label>
                      
                      <?php echo $this->Form->input('Customer.guardian_name', array('type' => 'text','placeholder'=>'Enter Husband/ Guardian Name','class'=>'form-control','label'=>false)); ?>
                    </div><!-- /.form group -->
					
					 <div class="form-group">
                      <label for="Email">Relation with Guardian</label>
					  <?php
						echo $this->Form->input('Customer.guardian_reletion_type', array('type' => 'select', 'options' => $this->Slt->relationship_type(),  'class'=>'form-control', 'label'=>false, 'empty' => 'Select Reletionship Type'));
					  ?>
                    </div>
                    
                    <div class="form-group">
                      <label>APL/BPL</label>
                      <?php
						echo $this->Form->input('Customer.apl_bpl', array('type' => 'select', 'options' => array('APL'=>'APL','BPL'=>'BPL'), 'class'=>'form-control', 'label'=>false, 'empty' => 'Select APL or BPL'));
					  ?>
                    </div><!-- /.form group -->
					
                    
					<div class="form-group">
                      <label>Group Name</label>
                      <?php
							echo $this->Form->input('Customer.kendra_id', array('type' => 'select','options' => $kendra_list, 'class'=>'form-control', 'label'=>false));
						?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label>Credit Officer Name</label>
                      <?php
							echo $this->Form->input('Customer.user_id', array('type' => 'select','options' => $user_list, 'class'=>'form-control', 'label'=>false));
						?>
                    </div><!-- /.form group -->
                    
                    

                </div><!-- /.box-body -->
                
                
                <div class="box-body col-md-12 col-sm-12">
                    <div class="box-header">
                      <h3 class="box-title">Id proof Information</h3>
                    </div>
                
                    <div id="id_proof">
                    <?php
                    if(empty($this->request->data['Customer']['id_proof'])){
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
                        $id_arr = json_decode($this->request->data['Customer']['id_proof'],true);
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