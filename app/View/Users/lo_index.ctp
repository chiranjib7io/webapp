<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<!-- Include Date Range Picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

	
		   <!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Dashboard
				<!--
                <small class="text-green">Success Message</small>
                <small class="text-danger">Waring Message</small>
				-->
              </h1>
              <ol class="breadcrumb">
                <li class="active"><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">
                  <div class="col-sm-12">
                      <!-- TABLE: LATEST ORDERS -->
                      <div class="box no-border">
                        <div class="box-header no-border">
                          
                          <div class="form-group" style="margin:10px auto 0; width:100%; text-align:center;">
                                
                               		<?php echo $this->Form->create('',array('class'=>'')); ?>
                                
                                  
                               <div id="hidden_date_range" style="display: block">
                                      <div class="col-md-5 col-sm-5"> 
                                      <!--<input type="text" name="datefilter" id="datefilter"  class="form-group form-control" />-->
                                      
                                      <input type="date" name="start_date" class="form-group form-control" value="<?=date("Y-m-d",strtotime($send_date['start_date']))?>" id="start_date"  />
                                      <input type="hidden" name="end_date" id="end_date"  />
                                      
                                      </div>
                                </div>
                                <div class="col-md-2 col-sm-2" style="margin-top:2px;">
                                	<button type="submit" class="btn btn-primary btn-sm" >Submit</button>
                                </div>
								  
							  <?php echo $this->Form->end(); ?>
                          </div><!-- /.form group -->
                        </div><!-- /.chart_box_header -->
                   	  </div>
                  </div>    	 	
                  
                  <div class="col-md-4 col-sm-12">
                      <!-- TABLE: LATEST ORDERS -->
                      <div class="box col-sm-12">
                        <div class="box-header no-border col-sm-12">
                          <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">STATISTICS</h2>
                        </div><!-- /.box-header -->
                        <div class="box-body col-sm-12" style="padding-top:15px; padding-bottom:15px;">
                        	<div class="statistic_table">	
                            	<div>
                                	<a href="#">
                                    	<div>
                                        	<strong>Members</strong>
                                        </div>
                                        <div>
                                        	<?= $dashboard_array['total_customer'] ?>
                                        </div>
                                    </a>
                                </div>	
                            	<div>
                                	<a href="#">
                                    	<div>
                                        	<strong>Pay Percentage</strong>	
                                        </div>
                                        <div>
                                        	<?= $dashboard_array['percentage_paid'] ?>%
                                        </div>
                                    </a>
                                </div>
                                
								 <div>
                                	<a href="#">
                                    	<div>
                                        	<strong>Realizable Amount</strong>
                                        </div>
                                  		<div>
											<?= $this->Number->currency($dashboard_array['realizable_amount'], '',array('places'=>0)) ?> 
                                        </div>
                                	</a>
                                </div>
							<!--	 <div>
                                	<a href="#">
                                    	<div>
                                        	<strong>Loan on Market</strong>
                                        </div>
                                  		<div>
											<?= $this->Number->currency($dashboard_array['total_loan_in_market'], '',array('places'=>0)) ?>
                                        </div>
                                	</a>
                                </div>-->
								 <div>
                                	<a href="#">
                                    	<div>
                                        	<strong>New Loan</strong>
                                        </div>
                                        <div>
											<?= $dashboard_array['new_loan'] ?>
                                        </div>
                                	</a>
                                </div>
                            </div>
                          
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                  </div><!-- /.col -->
                  
                  <div class="col-md-4 col-sm-12">
                  	  <div class="box no-n-heading-box">
                        
                          <h4 class="box-title" style="text-align:center;display:block; margin-top:20px;">BEST BRANCH</h4>
                          <h1 class="" align="center" style="margin:0; padding:0">Mathurapur</h1>
                        
                        <?php
						if($dashboard_array['call_array_val']['user_type_id']== 5) {
					  ?>
                         	<h4 class="box-title" style="text-align:center;display:block; margin-top:20px;">KENDRA</h4>
                          	<h1 class="" align="center" style="margin:0; padding:0; font-weight:bold;"><?= $dashboard_array['total_kendra'] ?></h1>
					  <?php
						} else {
					  ?>
                          <h4 class="box-title" style="text-align:center;display:block; margin-top:20px;">OFFICERS</h4>
                          	<h1 class="" align="center" style="margin:0; padding:0; font-weight:bold;"><?= $dashboard_array['total_lo'] ?></h1>
					  <?php
						}
					  ?>
                        
                      </div><!-- /.box -->
					  
                  </div><!-- /.col -->                   
                  
                  <div class="col-md-4 col-sm-12">
                  	  <!-- TABLE: LATEST ORDERS -->
                      <div class="box col-sm-12">
                        <div class="box-header  col-sm-12">
                          <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Quick Links</h2>
                        </div><!-- /.box-header -->
                        <div class="box-body col-sm-12" style="padding-top:15px; padding-bottom:15px;">
                        	<div class="statistic_table">	
                            	<div>
                                	<a href="<?= $this->Html->url('/create_customer') ?>">
                                    	<div style="width:100%;">
                                        	Create Customer
                                        </div>
                                    </a>
                                </div>
                                	
                            	<div>
                                	<a href="<?= $this->Html->url('/bulk_loan_collection') ?>">
                                    	<div style="width:100%;">
                                        	Bulk Loan Collection
                                        </div>
                                    </a>
                                </div>	
                                
								 <div>
                                	<a href="<?= $this->Html->url('/loan_overdue_payment') ?>">
                                    	<div style="width:100%;">
                                        	Overdue Collection
                                        </div>
                                    </a>
                                </div>
                                	
								<div>
                                	<a href="<?= $this->Html->url('/loan_prepayment') ?>">
                                    	<div style="width:100%;">
                                        	Loan Prepayment
                                        </div>
                                    </a>
                                </div>
                                
                                <div>
                                	<a href="<?= $this->Html->url('/customer_list') ?>">
                                    	<div style="width:100%;">
                                        	Customer List
                                        </div>
                                    </a>
                                </div>
                                
                            </div>
                          
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                  </div><!-- /.col --> 
                  
                  <!--table area---->
                  <div class="col-sm-12">
                  	<div class="chart_box">
                  		<div class="summery_box">
                                <div class="summery_box_header">
                                	<h2 class="summery_box_title" style="text-align:center;display:block;line-height:22px;">COLLECTION LIST AS ON <?=date("d M, Y",strtotime($send_date['start_date']))?></h2>
                                </div><!-- /.summery_box_header -->
                                <div class="summery_box_body">
                                    <div class="table-responsive">
                                        <table class="table no-margin">
                                          <thead>
                                            <tr>
                                              <td>Kendra</td>
                                              <td>Realizable</td>
                                              <td>Realized</td>
                                              <td>Overdue</td>
                                              <td>Action</td>
                                            </tr>
                                           </thead>
                                           <tbody>
										   
										<!-- This table is for Loan Officer. It is Start from Here -->
										<?php 
										if($dashboard_array['call_array_val']['user_type_id'] == 5){
												//pr($dashboard_array['branch_table_data']); die;
												if(!empty($dashboard_array['loan_collection'])){
													//pr($dashboard_array['branch_table_data']); die;
													foreach ($dashboard_array['loan_collection'] as $ktran=>$kendradata){
													$overdue_amt = $kendradata['0']['realizable_amount']-$kendradata['0']['realized_amount'];
										   ?>
                                                    <tr>
                                                      <td><?= $kendradata['Kendra']['kendra_name'] ?></td>
                                                      <td><?= $this->Number->currency($kendradata['0']['realizable_amount'], '',array('places'=>0)) ?></td>
                                                      <td><?= $this->Number->currency($kendradata['0']['realized_amount'], '',array('places'=>0)) ?></td>
                                                      <td><?= $this->Number->currency($overdue_amt, '',array('places'=>0)) ?></td>
                                                      <?php
                                                      if($overdue_amt>0){
                                                      ?>
                                                      <td><a href="<?= $this->Html->url('/kendra_loan_collection/'.$kendradata['Kendra']['id'].'/'.date("Y-m-d",strtotime($send_date['start_date']))) ?>" >Pay</a></td>
                                                      <?php
                                                      }else{
                                                        ?>
                                                      <td>Paid</td>  
                                                      <?php
                                                      }
                                                      ?>
                                                    </tr>
                                                   <?php
        										   	}
												} else {
										   ?>
                                           <div class="box-body">
												No Data Found
											 </div>
										<?php
												} 
                                        }
										?>
										<!-- This table is for Loan Officer. It is End Here -->
                                          </tbody>
                                        </table>
                                    </div><!-- /.table-responsive -->
                                </div><!-- /.summery_box_body -->
                            </div><!-- /.box -->
                    </div>
                  </div>
                  <!--------------> 
                              
              </div><!-- /.row -->
            
            </section><!-- /.content -->