<!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Customer Profile Details
              </h1>
              <ol class="breadcrumb">
              <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Customer Profile Details</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">
                  <div class="col-md-6 col-sm-12">
                      <div class="box box-primary" style="float:left">
                        <div class="box-header with-border">
                          <h3 class="box-title">Customer Details</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                        	<div class="row">
                                <div class="col-sm-4 col-xs-12">
									<?php
										if($cust_data['Customer']['cust_pic']!=''){
											$profile_pic=$cust_data['Customer']['cust_pic'];
										} else {
											$profile_pic='no_img.jpg';
										}
									
									?>
                                    <img src="<?php echo $this->webroot; ?>upload/profile_pic/<?=$profile_pic?>" alt="<?= $cust_data['Customer']['cust_fname']?>" class="image">
                                </div>
                                <div class="col-sm-8 col-xs-12" style="padding-bottom:20px;">
                                    <h4><?= $cust_data['Customer']['cust_fname']?> <?= $cust_data['Customer']['cust_lname']?></h4>
                                    <h5><?= $cust_data['Customer']['cust_address']?></h5>
                                    <h5><?= $cust_data['Customer']['city']?>,<?= $cust_data['Kendra']['state']?></h5>
                                    <h5>Zip Code:<?= $cust_data['Customer']['zip']?></h5>
                                    <h5>Date Of Birth: <?= date("d-M-Y", strtotime($cust_data['Customer']['cust_dob']));?></h5>
                                   <!-- <h5>Date Of Birth: <?= $cust_data['Customer']['cust_dob']?></h5>-->
                                    <h5>Contact No: <?= $cust_data['Customer']['cust_phone']?></h5>
                                    <h5>Contact Email: <?= $cust_data['Customer']['cust_email']?></h5>
                                    <h5><?= $sequrity_type_name?>: <?= $cust_data['Idproof'][0]['id_proof_no']?></h5>
                                    <h5>Guardian Name: <?= $cust_data['Customer']['guardian_name']?></h5>
                                    <h5>Kendra Name: <?= $cust_data['Kendra']['kendra_name']?></h5>
                                </div>
                        	</div>  
                        </div><!-- /.box-body -->
                      </div>
                  </div><!-- /.col -->
                  
                  <div class="col-md-6 col-sm-12">
                  
                      <div class="box box-danger">
                        <div class="box-header with-border">
                          <h3 class="box-title">Loan Summery</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
							<div class="table-responsive">
                                <table id="example1" class="table table-bordered kendra_list">
                                    <thead>
                                        <tr>
                                            <th>Loan No</th>
                                            <th>Principal</th>
                                            <th>Application Date</th>
                                            <th>Loan Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php
											if(!empty($cust_data['Loan'])){
												foreach($cust_data['Loan'] as $k2=>$customerloan){
												
													foreach($loanStatus_data as $k3=>$loanStatus){
														if($loanStatus['LoanStatus']['id'] == $customerloan['status']) {
																$loanStatusName= $loanStatus['LoanStatus']['status_name'];
														} 
													}
											?>
                                        <tr>
                                            <td><?= $customerloan['loan_number'] ?></td>
                                            <td><?= $customerloan['loan_principal'] ?></td>
                                            <td><?= date("d-M-Y", strtotime($customerloan['loan_date']));?></td>
                                            <td><?= $loanStatusName ?></td>
                                        </tr>
										<?php		}		}else{	?>    
											<tr>
												<td colspan="5">No Result Found</td>
											</tr>
										<?php 	}	?>
                                    </tbody>
                                </table>
							</div>
                        </div><!-- /.box-body -->
                      </div>
                      
                      <div class="box box-warning">
                        <div class="box-header with-border">
                          <h3 class="box-title">Savings Summery</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
							<div class="table-responsive">
                                <table id="example1" class="table table-bordered kendra_list">
                                    <thead>
                                        <tr>
                                            <th>Savings No</th>
                                            <th>Deposite</td>
                                            <th>Deposite Date</th>
                                            <th>Deposite Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>2</td>
                                            <td>20000</td>
                                            <td>1/1/2016</td>
                                            <td>Active</td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>10000</td>
                                            <td>10/2/2015</td>
                                            <td>Active</td>
                                        </tr>
                                    </tbody>
                                </table>
							</div>
                        </div><!-- /.box-body -->
                      </div>
                      
                  </div><!-- /.col -->
                                                
              </div><!-- /.row -->
            
            </section><!-- /.content -->
			