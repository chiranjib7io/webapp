<script>
function load_customer(kid){
    var url = '<?php echo $this->Html->url('ajax_customer_list/');?>'+kid;
    $.post( url, function( data ) {
      $( "#ldcustomer" ).html( data );
    });
}
</script>
<!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                <?=$product_data['Product']['product_name']?>
                <small class="text-green"><?=$this->Session->flash()?></small>
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="<?=$this->Html->url('/product_list/')?>">Product List</a></li>
                <li class="active"><?=$product_data['Product']['product_name']?></li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">
              	<div class="col-xs-12">
                  
                      <div class="box box-primary col-xs-12">
                        <div class="box-header with-border col-xs-12">
                          <h3 class="box-title"><?=$product_data['Product']['product_name']?></h3>
                          
                        </div><!-- /.box-header -->
                        <div class="box-body col-xs-12" style="padding-bottom:30px; padding-top:30px;">
                       		<div class="col-xs-12">
                            	<div class="col-lg-4 col-md-4 col-sm-12">
                                	<img src="<?=$this->webroot.$product_data['Product']['product_image']?>" class="full_img">
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                	<p><font size="+1">Product Code: <?=$product_data['Product']['product_number']?></font></p>
                                	<p>Selling Price : <font size="+3">Rs. <?=$product_data['Product']['product_price']?></font> (Inclusive of all taxes)</p>
                                    <p class="text-navy"><strong>Loan amount: Rs. <?=round($product_data['Product']['product_price']/10)?> x 10 Installment</strong></p>
                                    <p class="text-navy"><strong>Loan amount: Rs. <?=round($product_data['Product']['product_price']/12)?> x 12 Installment</strong></p>
                                    <p>WARRANTY: 1 Year Manufacturer Warranty on specific terms and conditons.</p>
                                    <p>
                                        <?=$product_data['Product']['product_description']?>
                                    </p>
                                <!--    <button type="button" class="btn btn-lg btn-success" data-toggle="modal" data-target="#ModalProConfirm" data-whatever="" style="margin-top:15px;">Place Order</button>  -->
                                <?php
                                if($userData['user_type_id']==2){
                                    echo $this->Html->link(
                                            'Edit Product',
                                           
                                            array(
                                                'controller'=>'',
                                                'action'=>'/save_product',
                                                $product_data['Product']['id']
                                            ),
                                            array(
                                                'class'=>'btn btn-lg btn-success'
                                            )
                                        );
                                }
                                ?>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                      </div>
                  </div><!-- /.col -->
              </div><!-- /.row -->
            
            </section><!-- /.content -->
            
            <!---Modal Confirm Order-----> 
                <div class="modal fade col-xs-12" id="ModalProConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content row">
                      <div class="modal-header  col-xs-12">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">Confirm Order</h4>
                      </div>
                      <?PHP echo $this->Form->create('Order', array('method' => 'post')); ?>
                      <div class="modal-body col-xs-12">
                          <div class="form-group col-md-6 col-sm-12 col-xs-12">
                            <label class="control-label">Kendra Name:</label>
                            <?php echo $this->Form->input("Order.kendra_id", array(
                                        'options' => $kendra_list,
                                        'default' => '',
                                        'empty' => 'Select Kendra',
                                        'required' => 'required',
                                        'onChange'=>'load_customer(this.value)',
                                        'label'=>false,
                      		            'class'	=> 'form-control'
                                    ));
                                
                                    ?>
                          </div>
                          <div class="form-group col-md-6 col-sm-12 col-xs-12" id="ldcustomer">
                            <label class="control-label">Customer Name:</label>
                            <select class="form-control" required="required">
                            	<option value="">Select Customer</option>
                            </select>
                          </div>
                        
                      </div>
                      <div class="modal-footer col-xs-12">
                        <?php echo $this->Form->input('Order.product_id', array('type' => 'hidden','value'=>$product_data['Product']['id'],'label'=>false)); ?>
                        <?php echo $this->Form->input('Order.order_amount', array('type' => 'hidden','value'=>$product_data['Product']['product_price'],'label'=>false)); ?>
                        <?php echo $this->Form->input('Order.repay_total', array('type' => 'hidden','value'=>$product_data['Product']['product_price'],'label'=>false)); ?>
                        <?php echo $this->Form->input('Order.currency', array('type' => 'hidden','value'=>'INR','label'=>false)); ?>
                        <?php echo $this->Form->input('Order.is_emi', array('type' => 'hidden','value'=>0,'label'=>false)); ?>
                
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                      </div>
                      <?php echo $this->Form->end(); ?>
                    </div>
                  </div>
                </div>
      		<!---Modal Confirm Order----->