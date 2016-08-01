  
   <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Save Region Information
          </h1>
          <ol class="breadcrumb">
           <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Save Region Information</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
			
			<?php echo $this->Form->create('Region',array('class'=>'')); ?>
						
						<span style="color:red"><?php echo $this->Session->flash(); ?></span>
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                <div class="box-body col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="BranchName">Region Name</label>
                        <?php echo $this->Form->input('Region.region_name', array('type' => 'text','class'=>'form-control','placeholder'=>'Enter Region Name','required'=>'required','label'=>false)); ?>
                        
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                      <label for="City ">Regional Manager </label>
                      <?php echo $this->Form->input("Region.user_id", array(
                            'options' => $manger_list,
                            'empty' => 'Select Manager',
                            'required'=>'required',
                            'label'=>false,
          		            'class'	=> 'form-control'
                        ));
                    
                        ?>  
                      
                    </div><!-- /.form group -->
                    

                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
                    
                    <div class="form-group">
                        <label for="Address ">Region Description (Optional) </label>
                        <?php echo $this->Form->input('Region.region_details', array('type' => 'textarea','class'=>'form-control','placeholder'=>'Enter Region Information','label'=>false)); ?>
                        
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