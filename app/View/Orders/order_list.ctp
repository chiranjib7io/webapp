<!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Order List
              </h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Order List</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">
              	<div class="col-xs-12">
                  
                      <div class="box box-primary">
                        <div class="box-header with-border">
                          <h3 class="box-title">Product Order List</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body" style="padding-bottom:20px;">
                        	<div class="table-responsive">
                                <table id="example2" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Order number</th>
                                            <th>Product Code</th>
                                            <th>Product Name</th>
                                            <th>Order Amount</th>
                                            <th>Order By</th>
                                            <th>Order Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                           <?php
                           foreach($order_list as $ord_row)
                           {
                                //$btn_class = ($ord_row['Order']['order_status_id']==1 || $ord_row['Order']['order_status_id']==2)?'btn-facebook':($ord_row['Order']['order_status_id']==3 || $ord_row['Order']['order_status_id']==4)?'btn-success':'btn-danger';
								if($ord_row['Order']['order_status_id']==1 || $ord_row['Order']['order_status_id']==2){
									$btn_class= 'btn-facebook';
								}
								if($ord_row['Order']['order_status_id']==3 || $ord_row['Order']['order_status_id']==4){
									$btn_class= 'btn-success';
								} else {
									$btn_class= 'btn-warning';
								}
							?>
                                    
                                        <tr>
                                            <td><?=$ord_row['Order']['order_number']?></td>
                                            <td><?=$ord_row['Product']['product_number']?></td>
                                            <td><?=$ord_row['Product']['product_name']?></td>
                                            <td><?=$this->Number->currency($ord_row['Order']['order_amount'],'',array('places'=>0))?></td>
                                            <td style="text-align:left">
                                                    <p><strong>Branch Name:</strong> <?=$ord_row['Branch']['branch_name']?></p>
                                                    <p><strong>Loan Officer name:</strong> <?=$ord_row['User']['first_name'].' '.$ord_row['User']['last_name']?></p>
                                                    <p><strong>Kendra name:</strong> <?=$ord_row['Kendra']['kendra_name']?></p>
                                                    <p><strong>Customer Name:</strong> <?=$ord_row['Customer']['cust_fname'].' '.$ord_row['Customer']['cust_lname']?></p>
                                                    <p><strong>Address:</strong> <?=$ord_row['Customer']['cust_address'].','.$ord_row['Customer']['city'].','.$ord_row['Customer']['state'].','.$ord_row['Customer']['zip']?></p>
                                            </td>
                                            <td><?=date("d-M-Y",strtotime($ord_row['Order']['order_date']))?></td>
                                            <td>
                                            <input type="button" class="btn <?=$btn_class?>" value="<?=$ord_row['OrderStatus']['status_name']?>">                                      </td>
                                            <td>
                                            <a href="<?=$this->Html->url('/order_details/'.$ord_row['Order']['id'])?>"><input type="button" class="btn btn-facebook" value="View Details"></a>
                                            </td>
                                        </tr>
                             <?php
                             }
                             ?>           

                                    </tbody>
                                </table>
                                
                        <div>
                            <?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
                            <?php echo $this->Paginator->numbers(array(   'class' => 'numbers'     ));?>
                            <?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
                        </div>
                                
                            </div>
                        </div><!-- /.box-body -->
                      </div>
                  </div><!-- /.col -->
              </div><!-- /.row -->
            
            </section><!-- /.content -->