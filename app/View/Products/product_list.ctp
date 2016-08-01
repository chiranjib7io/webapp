<!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Product List
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Product List</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">
              	<div class="col-xs-12">
                  
                      <div class="box box-primary col-xs-12">
                        <div class="box-header with-border col-xs-12">
                          <h3 class="box-title">Product List</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body col-xs-12" style="padding-bottom:20px;">
                       		<div class="col-xs-12">
                         <?php
                         
                         foreach($product_list as $prod_row){
                          ?>  
                            	<div class="col-lg-3 col-md-4 col-sm-12 pro-box">
                                	<div class="visual_section">
										<a href="<?=$this->Html->url('/product_details/'.$prod_row['Product']['id'])?>" class="ecommerce_img">
											<img src="<?=$this->webroot.$prod_row['Product']['product_image']?>">
										</a>
									</div>
                                    <div class="info_section">
                                    	<div class="pro_heading">
                                        	<a href="<?=$this->Html->url('/product_details/'.$prod_row['Product']['id'])?>">
                                            <?php
                                        echo $this->Text->truncate(
                                                $prod_row['Product']['product_name'],
                                                35,
                                                array(
                                                    'ellipsis' => '...',
                                                    'exact' => false
                                                )
                                            );

                                        ?>
                                            </a>
                                        </div>
                                    	<div class="pro_rating">
                                        	<span>Code:</span> <?=$prod_row['Product']['product_number']?>
                                        </div>
                                        
                                        <div class="pro_price">
                                        	Rs. <?=$prod_row['Product']['product_price']?>
                                        </div>
                                        <div  class="pro_text">
                                        <?php
                                        echo $this->Text->truncate(
                                                $prod_row['Product']['product_description'],
                                                130,
                                                array(
                                                    'ellipsis' => '...',
                                                    'exact' => false
                                                )
                                            );

                                        ?>
                                           
                                    	</div>
                                    </div>
                                </div>
                           <?php
                           }
                           ?>     
                                
                                
                            </div>
                            <div>
                            <?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
                            <?php echo $this->Paginator->numbers(array(   'class' => 'numbers'     ));?>
                            <?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
                            </div>
                        </div><!-- /.box-body -->
                      </div>
                  </div><!-- /.col -->
              </div><!-- /.row -->
            
            </section><!-- /.content -->