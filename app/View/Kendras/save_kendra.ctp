<script>
function load_loan_officer(bid){
    if(bid!=''){
        var url = '<?php echo $this->Html->url('ajax_loan_officer_list/');?>'+bid;
        $.post( url, function( data ) {
          $( "#lofficer" ).html( data );
        });
    }else{
        $( "#lofficer" ).html( '' );
    }
    
}

</script>   
   <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Save Kendra Information
          </h1>
          <ol class="breadcrumb">
           <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Save Kendra Information</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
			
			<?php echo $this->Form->create('Kendra',array('class'=>'')); ?>
						
						<span style="color:red"><?php echo $this->Session->flash(); ?></span>
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                <div class="box-body col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="BranchName">Kendra Name</label>
                        <?php echo $this->Form->input('Kendra.kendra_name', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter Branch Name','required'=>'required','label'=>false)); ?>
                        
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="City ">Kendra pradhan Name </label>
                      <?php echo $this->Form->input('Kendra.kendra_pradhan_name', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter City','required'=>'required','label'=>false)); ?>
                        
                      
                    </div><!-- /.form group -->
                    

                    <div class="form-group">
                      <label for="City ">City </label>
                      <?php echo $this->Form->input('Kendra.kendra_number', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter City','required'=>'required','label'=>false)); ?>
                        
                      
                    </div><!-- /.form group -->
                    
                    
                    
                    <div class="form-group">
                      <label for="State">State</label>
                      <?php echo $this->Form->input('Kendra.state', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter state','required'=>'required','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
					
					 <div class="form-group">
                      <label for="Zip">Zip</label>
                      <?php echo $this->Form->input('Kendra.zip', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter zip','required'=>'required','label'=>false)); ?>
                      
                    </div><!-- /.form group -->


                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
                    
                    <div class="form-group">
                        <label for="Address ">Address </label>
                        <?php echo $this->Form->input('Kendra.address', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter address','required'=>'required','label'=>false)); ?>
                        
                    </div><!-- /.form group -->
                    
					
					 <div class="form-group">
                      <label for="BranchEmail">Contact Number</label>
                      <?php echo $this->Form->input('Kendra.phone_no', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter phone no','required'=>'required','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
                   
                    
                    <div class="form-group">
                      <label for="BranchEmail">Email ID</label>
                      <?php echo $this->Form->input('Kendra.contact_email', array('type' => 'email','class'=>'form-control','placeholder'=>'Enter Branch Email ID','label'=>false)); ?>
                      
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label>Select Branch</label>
                     <?php
							echo $this->Form->input('Kendra.branch_id', array('type' => 'select', 'onChange'=>'load_loan_officer(this.value)', 'options' => $bm_list, 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>'', 'empty' => 'Select Branch'));
						?>
                    </div><!-- /.form group -->
                    
                    <div class="form-group" id="lofficer">
                      <label>Select Loan Officer</label>
                     <?php
							echo $this->Form->input('Kendra.user_id', array('type' => 'select', 'options' => $u_list, 'class'=>'form-control', 'label'=>false, 'default'=>'', 'required'=>'required', 'empty' => 'Select Loan Officer'));
						?>
                    <?php echo $this->Form->input('Kendra.organization_id', array('type' => 'hidden','class'=>'form-control','label'=>false)); ?>
                <?php echo $this->Form->input('Kendra.region_id', array('type' => 'hidden','class'=>'form-control','label'=>false)); ?>
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