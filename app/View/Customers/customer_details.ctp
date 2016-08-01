<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1> Customer Details 
            <small class="text-green"><?php echo $this->Session->flash() ?></small>
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
                  <h3 class="box-title"><?= $cust_data['Customer']['fullname']?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
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
                 	   $customer_dob='Not Set';
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
                  <div class="profile_img"> <img src="<?php echo $this->webroot; ?>upload/profile_pic/<?=$profile_pic?>" alt="<?= $cust_data['Customer']['cust_fname']?>"> </div>
                  <div class="profile_info">
                  <h4>Personal Details</h4>
                  <?php
                            
                    //prepare idproof data
                    $idprf = array();
                    if(!empty($cust_data['Customer']['id_proof'])){
                        $id_arr = json_decode($cust_data['Customer']['id_proof'],true);
                        foreach($id_arr as $k=>$id_row){
                            $idprf[] = $id_row['id_proof_type'].': '.$id_row['id_proof_no'];
                        }
                    }
                    // end idproof data prepare
                    
                    ?>
                    <ul>
                      
                      <li><i class="fa fa-universal-access"></i><span>Sex :</span> <?=$cust_data['Customer']['cust_sex']?></li>
                      
                      
                      <li><i class="fa fa-archive"></i><span>Occupation:</span> <?=$cust_data['Customer']['occupation']?></li>
                       <li><i class="fa fa-calendar"></i><span>Date of Birth:</span> <?=$customer_dob?></li>
                      
                      <li><i class="fa fa-user"></i><span>Guardian Name:</span> <?=$cust_data['Customer']['guardian_name']?></li>
                      <li><i class="fa fa-check-square"></i><span>Relation:</span> <?=$cust_data['Customer']['guardian_reletion_type']?></li>
                                       
                      <li><i class="fa fa-map-marker"></i><span>City:</span><?=$cust_data['Customer']['city']?></li>
                      <li><i class="fa fa-map-marker"></i><span>State:</span><?=$cust_data['Customer']['state']?></li>
                       <li><i class="fa fa-map-marker"></i><span>Pin:</span>: <?=$cust_add1?></li>
                       <li><i class="fa fa-map-marker"></i><span>Address:</span><?=$cust_data['Customer']['cust_address']?></li>
                       <li><i class="fa fa-credit-card"></i><span>Id Card details:</span> <?=implode(', ',$idprf)?></li>
                       
                    </ul>
                    <br class="clear">
                    <h4 class="marT30">Account Details</h4>
                    <ul>
                      <li><i class="fa fa-map-marker"></i><span>Branch:</span><?= $cust_data['Branch']['branch_name']?></li>
                      <li><i class="fa fa-map-o"></i><span>Market :</span><?= $cust_data['Market']['market_name']?></li>
                      <li><i class="fa fa-university"></i><span>Kendra Name </span><?= $cust_data['Kendra']['kendra_name']?></li>
                      <li><i class="fa fa-calendar"></i><span>Account Status :</span><?=$customer_status?></li>
                      
                      <li><i class="fa fa-user"></i><span>Member Since:</span> <?=date("d-M-Y", strtotime($cust_data['Customer']['created_on']))?></li>

                     <li><i class="fa fa-user"></i><span>APL / BPL:</span><?=$cust_data['Customer']['apl_bpl']?> </li>
                      
                      
                      
                      
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
                  <div class="col-md-6 col-sm-12 col-xs-12 overview">
                  <?php //pr($loan_summary); die; ?>
                    <h4>Loan Overview</h4>
                  <?php if(!empty($loan_summary)){ ?>
                    <ul>
                      <li><i class="fa fa-check-circle"></i><span>Application Date:</span> <?=date("d-M-Y",strtotime($loan_summary[0]['loan_date']))?></li>
                      <li><i class="fa fa-check-circle"></i><span>Disbursement Date:</span> <?=date("d-M-Y",strtotime($loan_summary[0]['loan_dateout']))?></li>
                      <li><i class="fa fa-check-circle"></i><span>Account opened by :</span> <?=$loan_summary[0]['loan_by']?></li>
                      <li><i class="fa fa-check-circle"></i><span>Loan Amount :</span> <?=$loan_summary[0]['loan_principal']?></li>
                      <li><i class="fa fa-check-circle"></i><span>Loan Type :</span> <?=$loan_summary[0]['loan_type']?></li>
                      <li><i class="fa fa-check-circle"></i><span>Interest % :</span><?=$loan_summary[0]['interest_rate']?>% </li>
                      <li><i class="fa fa-check-circle"></i><span>Interest Amount :</span> <?=$loan_summary[0]['loan_repay_total']-$loan_summary[0]['loan_principal']?></li>
                      <li><i class="fa fa-check-circle"></i><span>Total Installments:</span><?=$loan_summary[0]['total_installment_no']?></li>
                      <li><i class="fa fa-check-circle"></i><span>Installment Amount :</span> <?=$loan_summary[0]['installment_amount']?></li>
                      <li><i class="fa fa-check-circle"></i><span>Installments Paid :</span> <?=intval($loan_summary[0]['total_realized']/$loan_summary[0]['installment_amount'])?></li>
                      <li><i class="fa fa-check-circle"></i><span>Outstanding Amount :</span> <?=$loan_summary[0]['loan_repay_total']-$loan_summary[0]['total_realized']?></li>
                      <li><i class="fa fa-check-circle"></i><span>Last paid date :</span> <?=date("d-M-Y",strtotime($loan_summary[0]['last_paid_date']))?></li>
                      <li><i class="fa fa-check-circle"></i><span>Group or individual Payment :</span> <?=$loan_summary[0]['total_realized']?></li>
                      <li><i class="fa fa-check-circle"></i><span># of drops :</span> <?=$loan_summary[0]['overdue_no']?></li>
                      </ul>
                    <?php }else{ ?>
                        <p>No Loan is taken yet ... You can <a href="<?= $this->Html->url('/create_loan/'.$cust_data['Customer']['id']) ?>" class="btn btn-success">Create Loan</a> from here.</p>
                    <?php } ?>
                      <br class="clear">
                    <div class="table-responsive marT30">
            <?php if(!empty($loan_trans)){ ?>
                    <table class="table table-striped table-bordered">
                        <tr class="info">
                          <th>Installment paid</th>
                          <th>Due Date</th>
                          <th>Paid Date</th>
                          <th>Collection Amount</th>
                          <th>Collector</th>
                          <th>Overdraft Balance</th>
                        </tr>
                    <?php
                    $i=1;
                    foreach($loan_trans as $k=>$trow){
                        if($i==5){
                            break;
                        }
                        $class = ($i%2)?"":"danger";
                        $paid_on = ($trow['LoanTransaction']['insta_paid_on']!='0000-00-00')?date("d-M-Y",strtotime($trow['LoanTransaction']['insta_paid_on'])):'N/A';
                        $due_on = ($trow['LoanTransaction']['insta_due_on']!='0000-00-00')?date("d-M-Y",strtotime($trow['LoanTransaction']['insta_due_on'])):'N/A';
                    
                    ?>
                        <tr class="<?=$class?>">
                          <td><?=intval(($loan_summary[0]['loan_repay_total']-$trow['LoanTransaction']['current_outstanding'])/$loan_summary[0]['installment_amount'])?></td>
                          <td><?=$due_on?></td>
                          <td><?=$paid_on?></td>
                          <td><?=$trow['LoanTransaction']['insta_principal_paid']+$trow['LoanTransaction']['insta_interest_paid']+$trow['LoanTransaction']['prepayment']+$trow['LoanTransaction']['overdue_paid']?></td>
                          <td><?=$trow['User']['fullname']?></td>
                          <td><?=$trow['LoanTransaction']['current_outstanding']?></td>
                        </tr>
                    <?php
                        $i++;
                    }
                    ?>
                         
                      </tbody>
                    </table>
            <?php } ?>
                    </div>
                    
                    <a class="btn btn-success marBt" href="#">Loan Collection</a>
                    <a class="btn btn-info marBt" href="#">Loan Close</a>
                    <a class="btn btn-danger detailBtn marBt" href="#">View Details</a>
                    <a class="btn btn-warning marBt" href="#">Loan Adjustment form Savings</a>
                    
                  </div>
                  <div class="col-md-6 col-sm-12 col-xs-12 overview">
                  
                    <h4>Savings Summary</h4>
                <?php if(!empty($saving_summery)){ ?>
                <?php
					$added_date=date("d-M-Y", strtotime($saving_summery['savings_date']));
				?>
                    <ul>
                      <li><i class="fa fa-check-circle"></i><span>Savings Account:</span> <?=$saving_summery['account_number']?></li>
                      <li><i class="fa fa-check-circle"></i><span>Open date :</span> <?=$added_date?></li>
                      <li><i class="fa fa-check-circle"></i><span>Interest Type :</span> <?=$saving_summery['interest_type']?></li>
                      <li><i class="fa fa-check-circle"></i><span>Period length:</span> <?=$saving_summery['savings_term']?>month(s)</li>
                      <li><i class="fa fa-check-circle"></i><span>Balance :</span> <?=$saving_summery['current_balance']?></li>
                      <li><i class="fa fa-check-circle"></i><span>Interest earned :</span><?=$saving_summery['interest_till_date']?> </li>
                      <li><i class="fa fa-check-circle"></i><span>Last Paid Date :</span> <?=date("d-M-Y",strtotime($saving_summery['last_paid_date']))?></li>
                      <li><i class="fa fa-check-circle"></i><span>Maturity date :</span> <?=$saving_summery['maturity_date']?></li>
                       <li><i class="fa fa-check-circle"></i><span>Maturity Amount :</span> <?=$saving_summery['maturity_amount']?></li>
                      <li><i class="fa fa-check-circle"></i><span>Collection Frequency :</span> <?=$saving_summery['saving_type']?></li>
                      </ul>
                <?php }else{ ?>
                        <p>There is no Saving account. </p>
                <?php } ?>
                      <br class="clear">
                    <div class="table-responsive marT30">
                    <table class="table table-striped table-bordered">
                      <thead>
                        <tr class="info">
                          <th>Installment #</th>
                          <th>Date</th>
                          <th>Deposit amount</th>
                          <th>New Balance</th>
                          <th>Collector</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>24</td>
                          <td>5/29/2016</td>
                          <td>150</td>
                          <td>8150</td>
                          <td>Neena</td>
                        </tr>
                        <tr class="danger">
                          <td>25</td>
                          <td>6/6/2016</td>
                          <td>150</td>
                          <td>8300</td>
                          <td>Neena</td>
                        </tr>
                         <tr>
                          <td>26</td>
                          <td>6/15/2016</td>
                          <td>150</td>
                          <td>8450</td>
                          <td>Neena</td>
                        </tr>
                        <tr class="danger">
                          <td>27</td>
                          <td>6/22/2016</td>
                          <td>150</td>
                          <td>8600</td>
                          <td>Neena</td>
                        </tr>
                      </tbody>
                    </table>
                    </div>
                    <a class="btn btn-warning  marBt" href="#">Savings Deposit</a>
                    <a class="btn btn-success marBt" href="#">Savings Withdraw</a>
                    <a class="btn btn-danger detailBtn" href="#">View Details</a>
                  </div>
                </div>
              </div>
            </div>
            <!---customer info ends-----> 
            
          </div>
          <!-- /.row --> 
        </section>
        <!-- /.content --> 