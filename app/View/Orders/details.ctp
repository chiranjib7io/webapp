<?php
$loan_principal = $order_data['Order']['order_amount'];
$loan_interest = $order_data['Order']['emi_interest'];
$inst_amount = $order_data['Order']['emi_rate'];
$repaytotal_amount = $order_data['Order']['repay_total'];
$currency = $order_data['Order']['currency'];
$period_unit = $order_data['Order']['emi_period_interval_day'];
$loan_period = $order_data['Order']['emi_period'];
$loan_type = $order_data['Order']['emi_type'];
$loan_no = $order_data['Order']['order_number'];
$loan_dateout = $order_data['Order']['order_dateout'];


?>

<!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Product Loan Details [<?=$loan_no?>]
                <small class="text-green"><?php echo $this->Session->flash() ?></small>
                <!--<small class="text-danger">Waring Message</small>-->
              </h1>
              <ol class="breadcrumb">
               <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Product Loan Details</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">
             <!---customer info starts----->
                <div class="col-xs-12">
                	<div class="box box-primary" style="float:left">
                    	<div class="box-header with-border">
                          <h3 class="box-title"><?php echo $order_data['Customer']['cust_fname']; ?> <?php echo $order_data['Customer']['cust_lname']; ?></h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="profile_info" style="width:100%; padding-bottom:20px;">
                                <ul>
                                    <li><strong>Product Loan principal:</strong> <?php echo $loan_principal; ?> <?php echo $currency; ?></li>
                                    <li><strong>Interest:</strong> <?php echo $loan_interest; ?>% p.a <?=$loan_type?> Interest</li>
                                    <?php 
                                    if($order_data['Order']['order_issued']==1){
                                    ?>
                                    <li><strong>EMI Start Date:</strong> <?php echo date("d-M-Y",strtotime($order_data['Order']['emi_start_date'])); ?></li>
                                    <li><strong>Loan Repay Amount:</strong> <?php echo $repaytotal_amount; ?> <?php echo $currency; ?></li>
                                    <?php
                                    }
                                    ?>
                                    <li><strong>Order Date:</strong> <?php echo date("d-M-Y",strtotime($order_data['Order']['order_date'])); ?></li>
                                    <li><strong>No. of Period:</strong> <?php echo $loan_period; ?> Installment</li>
                                    <li><strong>Total Overdue:</strong> <?=$this->Number->currency($loan_overdue[0][0]['total_overdue'],'',array('places'=>0))?></li>
                                    <li><strong>No. of Overdue:</strong> <?=$loan_overdue[0][0]['overdue_no']?></li>
                                    <li><strong>Order Status:</strong> <?php echo $order_data['OrderStatus']['status_name']; ?></li>
                                    <li><strong>Product Details:</strong> <?php echo $order_data['Product']['product_name']; ?></li>
                                </ul>
                            </div>
                            
                            <?php
							//pr($order_data['Order']); die;
                            if($userData['user_type_id']==2){
								$not_edit='';
								$text_read='';
								if($order_data['Order']['emi_period_interval_day']!= ''){
									$emi_interval=$order_data['Order']['emi_period_interval_day'];
								} else {
									$emi_interval=1;
								}
								if($order_data['Order']['emi_period']!= ''){
									$emi_interval_period=$order_data['Order']['emi_period'];
								} else {
									$emi_interval_period=1;
								}
								if($order_data['Order']['emi_start_date']!= '0000-00-00'){
									$emi_interval_start=$order_data['Order']['emi_start_date'];
								} else {
									$emi_interval_start=date("Y-m-d");
								}
								if($order_data['Order']['order_status_id']==3){
									//$not_edit= "'disabled' => 'disabled'";
									$not_edit= "disabled";
									//$text_read='required="required"';
									$text_read='readonly="readonly"';
									unset($loan_status[1]);
									unset($loan_status[2]);
								}
                            ?>
                            <?PHP echo $this->Form->create('Order', array('method' => 'post')); ?>
                            <?php echo $this->Form->input('Order.id', array('type' => 'hidden','value'=>$order_data['Order']['id'],'label'=>false)); ?>   
                            <div class="col-md-4 col-sm-6" style="margin-bottom:10px;">
                            <label for="LoanInterest">Update Order status</label>
                            <?php echo $this->Form->input("Order.order_status_id", array(
                                    'options' => $loan_status,
                                    'default' => $order_data['Order']['order_status_id'],
                                    'label'=>false,
                  		            'class'	=> 'form-control'
                                ));
                        
                            ?>
                            </div>
                            <div class="col-md-8 col-sm-6" style="margin-bottom:10px; margin-top:25px;">
                            	<input type="submit" class="btn btn-success" value="Submit" >
                            </div>
                            <?php echo $this->Form->end(); ?> 
                            <?php } ?> 
                            
                        </div>
                    </div>
                </div>
              	<!---customer info ends----->
                  
                  <div class="col-xs-12">
                  
                      <div class="box box-primary">
                        <div class="box-header with-border">
                          <h3 class="box-title">Product EMI Summary</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body" style="padding-bottom:20px;">
                        	<div class="table-responsive">
                                <table id="example2" class="table table-bordered table-hover kendra_list">
                                    <thead>
                                        <tr>
                                            <th>Installment No</th>
                                            <th>Due Date</th>
                                            <th>Paid Date</th>
                                           <!--  <th>Principal Due</th>
                                           <th>Interest Due</th> -->
                                            <th>Total Due</th>
                                            <th>Realized</th>
                                            <th>Overdue</th>
                                            <th>Current Outstanding</th>
                                            <th>Payment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach($order_data['LoanTransaction'] as $trans_row)
                                    {
                                        $realized = $trans_row['insta_principal_paid']+$trans_row['insta_interest_paid'];
                                        $today = strtotime(date("Y-m-d"));
                                        $due_day = strtotime($trans_row['insta_due_on']);
                                        $overdue=0;
                                        
                                        if(($today>=$due_day) && ($trans_row['insta_principal_paid']==0)){
                                            $overdue = $trans_row['total_installment'];
                                        }
                                    ?>
                                        <tr <?=($overdue>0)?'class="text-danger"':''?>>
                                            <td><?=$trans_row['insta_no']?></td>
                                            <td><?=date("d-M-Y",$due_day)?></td>
                                            <td><?=($trans_row['insta_paid_on']!="0000-00-00")?date("d-M-Y",strtotime($trans_row['insta_paid_on'])):''?></td>
                                           <!-- <td><?=$trans_row['insta_principal_due'].' '.$currency?></td>
                                             <td><?=$trans_row['insta_interest_due'].' '.$currency?></td> -->
                                            <td><?=$this->Number->currency($trans_row['total_installment'],'',array('places'=>0))?></td>
                                            <td <?=($realized>0)?'class="text-green"':''?> ><?=$this->Number->currency($realized,'',array('places'=>0))?></td>
                                            <td><?=$this->Number->currency($overdue,'',array('places'=>0))?></td>
                                            <td><?=$this->Number->currency($trans_row['current_outstanding'],'',array('places'=>0))?></td>
                                            <td>
                                            <?php
                                            if($trans_row['insta_principal_paid']==0){
                                            ?>
                                                <a href="<?php echo $this->Html->url('single_order_installment_collection/'.$trans_row['id']);?>"><input type="submit" class="btn btn-danger" onclick="return confirm('Are you confirm to make payment?')" value="Make Payment" ></a>
                                            <?php    
                                            }
                                            if($trans_row['insta_principal_paid']>0){
                                            ?>    
                                                <input type="submit" class="btn btn-success" value="Already Paid" disabled >
                                            <?php   
                                            }
                                            ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- /.box-body -->
                      </div>
                  </div><!-- /.col -->
              </div><!-- /.row -->
            
            </section><!-- /.content -->