
<!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Save Product
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Save Product</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">
              	<div class="col-xs-12 col-sm-10 col-md-8 col-lg-8 col-xs-push-0 col-sm-push-1 col-md-push-2 col-lg-push-2">
                	<div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                            <div class="box-body col-sm-12">
                            	<?php echo $this->Form->create('Product',array('type'=>'file')); ?>
                                                               
                                    <div class="form-group col-xs-12">
                                    	<label>Product Name</label>
                                        <?php echo $this->Form->input('product_name', array('type' => 'text','class'=>'form-control','placeholder'=>'Product Name','required'=>'required','label'=>false)); ?>
                                        
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <label>Product Description</label>
                                        <?php echo $this->Form->input('product_description', array('type' => 'textarea','class'=>'form-control','placeholder'=>'Product Description','required'=>'required','label'=>false)); ?>
                                        
                                    </div>                           
                                    <div class="form-group col-xs-12">
                                    	<label>Product Price</label>
                                        <?php echo $this->Form->input('product_price', array('type' => 'number','class'=>'form-control','placeholder'=>'Product Price','required'=>'required','label'=>false)); ?>
                                        
                                    </div>
                                    <div class="input select form-group col-xs-12">
                                    	<label>Upload Product Image</label>
                                        <?php 
                                        $options = array('type' => 'file','placeholder'=>'Upload image','required'=>'required','label'=>false);
                                        if (!empty($product_data['Product']['product_image'])): 
                                        $options = array('type' => 'file','placeholder'=>'Upload image','label'=>false);
                                        ?>
                                        	<div class="input">
                                        		<label>Uploaded Image</label>
                                        		
                                        		<img src="<?php echo $this->webroot.$product_data['Product']['product_image']; ?>" width="150" >
                                        	</div>
                                        <?php endif; ?>
                                        
                                        <?php echo $this->Form->input('product_image', $options); ?>
                                    </div>                          
                                    <div class="form-group col-xs-12">
                                    	<label>Product Code</label>
                                        <?php echo $this->Form->input('product_number', array('type' => 'text','class'=>'form-control','placeholder'=>'Product Code','required'=>'required','label'=>false)); ?>
                                    </div>                          
                                    <div class="input-group col-xs-12" style="text-align:center"><input type="submit" class="btn btn-lg btn-success" value="Save Product" ></div>
                                <?php echo $this->Form->end(); ?>
                            </div>
                      </div><!-- /.box -->
                </div><!-- /.col -->
              </div><!-- /.row -->
            
            </section><!-- /.content -->