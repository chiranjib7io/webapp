<!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Customer Details
                 <small class="text-green"><?php echo $this->Session->flash() ?></small>
                <!--<small class="text-green">Success Message</small>
                <small class="text-danger">Waring Message</small>-->
              </h1>
              <ol class="breadcrumb">
               <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Customer Details</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">
             <!---customer info starts----->
                <div class="col-xs-12">
                	<div class="box box-primary" style="float:left; padding-bottom: 20px;">
                    	<div class="box-header with-border">
                          <h3 class="box-title"><?= $cust_data['Customer']['cust_fname']?> <?= $cust_data['Customer']['cust_lname']?></h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                        	<div class="profile_img">
								<?php
										//pr($cust_data); die;
										if($cust_data['Customer']['cust_pic']!=''){
											$profile_pic=$cust_data['Customer']['cust_pic'];
										} else {
											$profile_pic='no_img.jpg';
										}
										if($cust_data['Customer']['status']==1){
											$customer_status='Active';
										} else {
											$customer_status='Not Active';
										}
			                         	if($cust_data['Customer']['cust_dob']=='' || $cust_data['Customer']['cust_dob']=='0000-00-00'){
			                         	   $customer_dob='No Set';
			                         	} else{
			                         	   $customer_dob=date("d-M-Y", strtotime($cust_data['Customer']['cust_dob']));
			                         	}
                                        if($cust_data['Customer']['cust_address']==''){
                                            $cust_add='Not Set';
                                        } else{
                                             $cust_add=$cust_data['Customer']['cust_address'].', '.$cust_data['Customer']['city'];
                                        }
                                        if($cust_data['Customer']['zip']==''){
                                            $cust_add1='Not Set';
                                        } else{
                                             $cust_add1=$cust_data['Customer']['zip'].', '.$cust_data['Customer']['state'];
                                        }
									?>
                            	<img src="<?php echo $this->webroot; ?>upload/profile_pic/<?=$profile_pic?>" alt="<?= $cust_data['Customer']['cust_fname']?>">
                            </div>	
                            <div class="profile_info">
                                <ul>
                                    <li><i class="fa fa-map-marker"></i><strong>Address: </strong> <?= $cust_add; ?></li>
									<li><i class="fa fa-map-o"></i> <?= $cust_add1; ?></li>
                                    <li><i class="fa fa-calendar"></i><strong>Date of Birth:  </strong> <?= $customer_dob;?></li>
                                    <li><i class="fa fa-credit-card"></i><strong>Id Card details:</strong> <?= $sequrity_type_name?>: <?= $cust_data['Idproof'][0]['id_proof_no']?></li>
                                    <li><i class="fa fa-user"></i><strong>Guardian Name:</strong> <?= $cust_data['Customer']['guardian_name']?></li>
                                    <li><i class="fa fa-university"></i><strong>Market Name </strong><?= $cust_data['Market']['market_name']?></li>
                                    <li><i class="fa fa-calendar-check-o"></i><strong>Join date:</strong> <?= date("d-M-Y", strtotime($cust_data['Customer']['created_on']));?></li>
                                    <li><i class="fa fa-check-square"></i><strong>Status:</strong> <?= $customer_status?></li>
                                    <li><i class="fa fa-archive"></i><strong>Occupation:</strong> Business</li>
                                </ul>
                            </div>
							<div class="col-xs-12" style="text-align:right;">
                            	<?php echo $this->Form->create('Customer',array('class'=>'', 'action'=> 'delete_single_customer')); ?>
									<input type="hidden" name="customer_id" value="<?= $cust_data['Customer']['id']?>" />
									<?php if($loan_active<1){ ?>
											<a href="<?= $this->Html->url('/create_loan/'.$cust_data['Customer']['id']) ?>" class="btn btn-success">Create Loan</a>
									<?php } ?>
									<?php if($cust_data['Customer']['upload_type']=='CSV'){ ?>
										<input type="submit" class="btn btn-danger" value="Delete Customer" onclick="return confirm('Do you really want to Delete this customer?');">
									<?php } ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
              	<!---customer info ends----->
                  
                    <div class="col-xs-12">
                  
                      <div class="box col-xs-12 box-primary">
                        <div class="box-header with-border">
                          <h3 class="box-title"><strong>Customer Summary</strong></h3>
                        </div><!-- /.box-header -->
                        <div class="box-body" style="padding-bottom:20px;">
                        	<div class="nav-tabs-custom">
                                <ul class="nav nav-tabs" style="padding-bottom:10px;">
                                  <li class="active tab1"><a href="#tab_1" data-toggle="tab">Loan Summary</a></li>
                                  <li class="tab2"><a href="#tab_2" data-toggle="tab">Savings Summary</a></li>
                                <!--  <li class="tab3"><a href="#tab_3" data-toggle="tab">Product Summary</a></li> -->
                             	</ul>  
                                   
                                <div class="tab-content row" style="padding-top:20px; padding-bottom:20px;">
                                  <!----------->
                                  <div class="tab-pane active" id="tab_1" style="box-shadow:none;">
                                    	<div class="box box-solid" style="box-shadow:none;">
                                            <div class="box-body">
                                              <div class="box-group" id="accordion">
											  
											  <!-- Accordion Menu Start -->
											  <?php
													$i=1;
													//pr($loan_summery); die;
													foreach($loan_summery as $kls => $loan_summ){
														if($loan_summ['last_paid_date']=='0000-00-00' || $loan_summ['last_paid_date']==''){
															$last_paid_date='No Payment Yet';
															$last_paid=0;
														} else{
															$last_paid_date=date("d-M-Y", strtotime($loan_summ['last_paid_date']));
															$last_paid=$loan_summ['installment_amount'];
														}
											  ?>
											  
                                                <div class="panel box box-danger" style="box-shadow:none;">
                                                  <div class="box-header with-border bg-red disabled color-palette">
                                                    <h4 class="box-title">
                                                      <a data-toggle="collapse" data-parent="#accordion" href="#Loancollapse<?= $kls?>" style="color:inherit;">
                                                      <?= $loan_summ['account_number']?> | <?= date("d-M-Y", strtotime($loan_summ['loan_date']));?> | <?= $loan_summ['loan_status']?> | <?= $loan_summ['total_installment_no']?>
                                                      </a>
                                                    </h4>
                                                  </div>
                                                  <div id="Loancollapse<?= $kls?>" class="panel-collapse collapse <?=$i==1?'in':''?>">
                                                    <div class="box-body">
                                                      	<ul class="list_style1">
                                                            <li>Account Number: <?= $loan_summ['account_number']?></li>
                                                            <li>Principal: <?= $this->Number->currency($loan_summ['loan_principal'],'',array('places'=>0));?></li>
                                                            <li>Application Date: <?= date("d-M-Y", strtotime($loan_summ['loan_date']));?></li>
                                                            <li>Last Payment Date: <?= $last_paid_date ?></li>
                                                            <li>Instalment Amount: <?= $this->Number->currency($loan_summ['installment_amount'],'',array('places'=>0));?></li>
                                                            <li>Instalment Number: <?= $loan_summ['paid_installment']?></li>
                                                            <li>Total Instalment: <?= $loan_summ['total_installment_no']?></li>
                                                            <li>Remaining Payment Balance: <?= $this->Number->currency($loan_summ['loan_due_balance'],'',array('places'=>0));?></li>
                                                            <li>Repay Amount: <?= $loan_summ['loan_repay_total']?></li>
                                                            <li>Amount of Overdue: <?= $this->Number->currency($loan_summ['total_overdue'],'',array('places'=>0));?></li>
                                                            <li>Instalment Amount: <?= $this->Number->currency($loan_summ['installment_amount'],'',array('places'=>0));?></li>
                                                            <li>Paid Amount: <?= $this->Number->currency($loan_summ['total_paid_amount'],'',array('places'=>0));?></li>
                                                            <li>Status: <?= $loan_summ['loan_status']?></li>
                                                            <li>Use of Loan: <?= $loan_summ['loan_purpose']?></li>
                                                        </ul>
														<div style="margin-top:15px; text-align:right;">
															<?php
																if($loan_summ['loan_status_no']== 6){
															?>
																<a href="<?= $this->Html->url('/security_deposite_return/'.$cust_data['Market']['id'].'/'.$cust_data['Customer']['id']) ?>" class="btn btn-lg bg-navy">Security Diposit Return</a>
															<?php } ?>
															<!-- <button type="button" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#ModalOne<?= $loan_summ['loan_id']?>" data-whatever="Deposite">Deposite</button> -->
															<a href="<?= $this->Html->url('/loan_details/'.$loan_summ['loan_id']) ?>" class="btn btn-lg bg-navy">Details</a>
														</div>
                                                    </div>
                                                  </div>
                                                </div>
												<!-- Accordion Menu End -->
												
												<!-----modal one area--------->
											<div class="modal fade" id="ModalOne<?= $loan_summ['loan_id']?>" tabindex="-1" role="dialog" aria-labelledby="ModalOneLabel">
											  <div class="modal-dialog" role="document">
												<div class="modal-content row">
												  <div class="modal-header col-xs-12">
													<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
													<h4 class="modal-title" id="exampleModalLabel">Deposite</h4>
												  </div>
												<?php echo $this->Form->create('Customer',array('method' => 'post', 'action' => '/customer_details/'.$cust_data['Customer']['id'])); ?>
												  <div class="modal-body col-xs-12">
													  <div class="form-group col-xs-12">
														<div class="col-md-2 col-sm-3 col-xs-4"><label for="recipient-name" class="control-label">Amount:</label></div>
														<div class="col-md-10 col-sm-9 col-xs-8"><input type="text" class="form-control" id="" name="" disabled="disabled" value="<?= $loan_summ['security_diposit']?>"></div>
													  </div>
													  <div class="form-group col-xs-12">
														<div class="col-md-2 col-sm-3 col-xs-4"><label for="recipient-name" class="control-label">Date:</label></div>
														<div class="col-md-10 col-sm-9 col-xs-8"><input type="date" class="form-control" id="recept_date" name="recept_date" required="required"></div>
													  </div>
												  </div>
												  <input type="hidden" id="cust_id" name="cust_id" value="<?= $cust_data['Customer']['id'] ?>" >
												  <input type="hidden" id="kendra_id" name="kendra_id" value="<?= $cust_data['Kendra']['id']?>" >
												  <div class="modal-footer col-xs-12">
													<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
													<button type="submit" class="btn btn-primary">Save</button>
												  </div>
													</form>
												</div>
											  </div>
											</div>
										<!-----modal one area--------->
										
												<?php
                                                $i++;
														}
												?>
                                              </div>
                                            </div><!-- /.box-body -->
                                          </div>
										  
                                  </div><!-- /.tab-pane -->
                                  <!----------->
                                  <div class="tab-pane" id="tab_2"  style="box-shadow:none;">
                                    	<div class="box box-solid" style="box-shadow:none;">
                                            <div class="box-body">
                                              <div class="box-group" id="accordion1">
                                                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                                                <div class="panel box box-success"  style="box-shadow:none;">
                                                  <div class="box-header with-border bg-green disabled color-palette">
												  <?php
														//date("d-M-Y", strtotime($loan_summ['loan_date']));
														$modified_date=strtotime($saving_summery['modified_on']);
														if($modified_date==''){
															$modied_date=date("d-M-Y", strtotime($saving_summery['created_on']));
														} else {
															$modied_date=date("d-M-Y", strtotime($saving_summery['modified_on']));
														}
														$added_date=date("d-M-Y", strtotime($saving_summery['created_on']));
													?>
                                                    <h4 class="box-title">
                                                      <a data-toggle="collapse" data-parent="#accordion1" href="#SavcollapseOne" style="color:inherit;">
                                                       saving_<?= $saving_summery['id']?> | <?= $modied_date?> | <?= $saving_summery['current_balance']?> | <?= $added_date?>
                                                      </a>
                                                    </h4>
                                                  </div>
                                                  <div id="SavcollapseOne" class="panel-collapse collapse in">
												 
                                                    <div class="box-body">
                                                        <ul class="list_style1">
                                                            <li>Account No: saving_<?= $saving_summery['id']?></li>
                                                            <li>Last Deposit Date: <?= $modied_date?></li>
                                                            <li>Total Savings Balance: <?= $this->Number->currency($saving_summery['current_balance'],'',array('places'=>0));?></li>
                                                            <li>Status: <?= $saving_summery['saving_status']?></li>
                                                            <li>Start Date: <?= $added_date?></li>
                                                        </ul>
														<div style="margin-top:15px; text-align:right;">
															<button type="button" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#ModalTwo" data-whatever="Deposite">Deposite</button>
															<a href="<?= $this->Html->url('/saving_details/'.$saving_summery['id']) ?>" class="btn btn-lg bg-navy">Details</a>
														</div>
                                                    </div>
													
                                                  </div>
                                                </div>
												
												
                                              </div>
                                            </div><!-- /.box-body -->
                                      </div>
                                  	<!-----modal two area--------->
									<div class="modal fade" id="ModalTwo" tabindex="-1" role="dialog" aria-labelledby="ModalTwoLabel">
									  <div class="modal-dialog" role="document">
										<div class="modal-content row">
										  <div class="modal-header col-xs-12">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="exampleModalLabel">Deposite</h4>
										  </div>
										<?php echo $this->Form->create('Customer',array('method' => 'post', 'action' => '/customer_details/'.$cust_data['Customer']['id'])); ?>
										  <div class="modal-body col-xs-12">
											  <div class="form-group col-xs-12">
												<div class="col-md-2 col-sm-3 col-xs-4"><label for="recipient-name" class="control-label">Date:</label></div>
												<div class="col-md-10 col-sm-9 col-xs-8"><input type="date" class="form-control" id="recept_date" name="recept_date" required="required"></div>
											  </div>
											  <div class="form-group col-xs-12">
												<div class="col-md-2 col-sm-3 col-xs-4"><label for="recipient-name" class="control-label">Amount:</label></div>
												<div class="col-md-10 col-sm-9 col-xs-8"><input type="text" class="form-control" id="recept_amount" name="recept_amount" plaseholder="Enter the amount" required="required"></div>
											  </div>
										  </div>
										  <input type="hidden" id="cust_id" name="cust_id" value="<?= $cust_data['Customer']['id'] ?>" >
										  <input type="hidden" id="kendra_id" name="kendra_id" value="<?= $cust_data['Kendra']['id']?>" >
										  <div class="modal-footer col-xs-12">
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											<button type="submit" class="btn btn-primary">Save</button>
										  </div>
										  	</form>
										</div>
									  </div>
									</div>
									<!-----modal one area--------->
                                  </div><!-- /.tab-pane -->
                                  <!----------->
                                  <div class="tab-pane" id="tab_3">
                                    	<div class="box box-solid" style="box-shadow:none;">
                                            <div class="box-body">
                                              <div class="box-group" id="accordion2">
                                                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
												
												<?php
                                                $i =1;
													foreach($order_summery as $kos => $order_summ){
											  
											  ?>
												
                                                <div class="panel box box-warning"  style="box-shadow:none;">
                                                  <div class="box-header with-border bg-yellow disabled">
                                                    <h4 class="box-title">
                                                      <a data-toggle="collapse" data-parent="#accordion2" href="#Procollapse<?= $kos?>" style="color:inherit;">
                                                       <?= $order_summ['order_number']?> | <?= $order_summ['product']?> | <?= $order_summ['product_number']?> | <?= $order_summ['payment_type']?>
                                                      </a>
                                                    </h4>
                                                  </div>
                                                  <div id="Procollapse<?= $kos?>" class="panel-collapse collapse <?=$i==1?'in':''?>">
                                                    <div class="box-body">
                                                      	<ul class="list_style1">
                                                            <li>Order Number: <?= $order_summ['order_number']?></li>
                                                            <li>Product Name: <?= $order_summ['product']?></li>
															<li>Product Price: <?= $this->Number->currency($order_summ['order_amount'],'',array('places'=>0));?></li>
                                                            <li>Product Id: <?= $order_summ['product_number']?></li>
                                                            <li>Due Balance: <?= $this->Number->currency($order_summ['emi_due_balance'],'',array('places'=>0));?></li>
                                                            <li>Payment Type: <?= $order_summ['payment_type']?></li>
                                                            <li>Payment Start Date: <?= $order_summ['emi_start_date']?></li>
                                                            <li>Order Status: <?= $order_summ['oreder_status']?></li>
                                                        </ul>
														<div style="margin-top:15px; text-align:right;"><a href="<?= $this->Html->url('/order_details/'.$order_summ['order_id']) ?>" class="btn btn-lg bg-navy">Details</a></div>
                                                    </div>
                                                  </div>
                                                </div>
												<?php
                                                $i++;
													}
												?>
												
                                              </div>
                                            </div><!-- /.box-body -->
                                          </div>
                                  </div><!-- /.tab-pane -->
                                  
                                </div><!-- /.tab-content -->
                                
              				</div><!-- nav-tabs-custom -->
                        </div><!-- /.box-body -->
                      </div>
                  </div><!-- /.col -->
				  
              </div><!-- /.row -->
            
            </section><!-- /.content -->