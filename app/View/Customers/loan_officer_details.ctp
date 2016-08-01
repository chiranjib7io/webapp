<script>
function myfunction() {
    var x = document.getElementById("UserBranchId").selectedIndex;
    var action = document.getElementsByTagName("option")[x].value;
    if (action !== "") {
        document.getElementById("UserLoanOfficerDetailsForm").action = action;
        
    } else {
        alert("Please select Loan Officer");
    }
}
</script>
<!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Loan Officer Details
                <!--<small class="text-green">Success Message</small>
                <small class="text-danger">Waring Message</small>-->
              </h1>
              <ol class="breadcrumb">
               <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Loan Officer Details</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">
             <!---customer info starts----->
                <div class="col-xs-12">
                	<div class="box box-primary" style="float:left">
						<div class="box-header with-border" style="border-top:none; padding-top:20px;padding-bottom:20px;">
							 <?PHP echo $this->Form->create('Customer', array('method' => 'post', 'action' => '/loan_officer_details/')); ?>
								<div class="form-group col-sm-6 col-xs-12" style="text-align:center">
								<?php echo $this->Form->input("branch_id", array(
										'options' => $lo_list,
										'default' => $userData['branch_id'],
										'label'=>false,
										'class'	=> 'form-control'
									));
								?>
								</div>
								<div class="input-group col-sm-6 col-xs-12" >
									<input type="submit" class="btn btn-success" onclick="myfunction()" value="Submit" >
								</div>
								<?php echo $this->Form->end(); ?> 
                        </div><!-- /.box-header -->
						<?php 
								if($loan_officer_summery['data_status']!= 0) {
						?>
						<div class="box-header with-border">
                          <h3 class="box-title"><?=$loan_officer_summery['user_details']['first_name'].' '.$loan_officer_summery['user_details']['last_name']?></h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="profile_info" style="width:100%; padding-bottom:20px;">
                                <ul>
                                    <li><strong>Branch Name:</strong> <?=$loan_officer_summery['branch_details']['branch_name']?></li>
                                    
                                    <li><strong>Email ID:</strong> <?=$loan_officer_summery['user_details']['email']?></li>
                                    <li><strong>Organisation name:</strong> <?=$loan_officer_summery['organization_details']['organization_name']?></li>
                                    <li><strong>No. of Kendra:</strong> <?=$loan_officer_summery['total_kendra']?></li>
                                    <li><strong>No. of Customer:</strong> <?=$loan_officer_summery['total_cuatomer']?></li>
                                    <li><strong>Total Loan amount:</strong> <?=$loan_officer_summery['total_loan']?></li>
                                    <li><strong>Loan in Market:</strong> <?=$loan_officer_summery['total_loan_market']?></li>
                                    <li><strong>Total Realizable:</strong> <?=$loan_officer_summery['total_realizable']?></li>
                                    <li><strong>Total Realized:</strong> <?=$loan_officer_summery['total_relaized']?></li>
                                    <li><strong>Total Overdue:</strong> <?=$loan_officer_summery['total_overdue']?></li>
                                    <li><strong>Percent paid:</strong> <?=$loan_officer_summery['percentage_paid']?>%</li>
                                    <li><strong>Address:</strong> <?=$loan_officer_summery['user_details']['address'].', '.$loan_officer_summery['user_details']['city'].', '.$loan_officer_summery['user_details']['state'].', '.$loan_officer_summery['user_details']['zip']?></li>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
              	<!---customer info ends----->
                  
                  <div class="col-xs-12">
                      <div class="box box-primary">
                        <div class="box-header with-border">
                          <h3 class="box-title">Loan Summery</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body" style="padding-bottom:20px;">
                        	<div class="table-responsive">
                                <table id="example2" class="table table-bordered table-hover kendra_list">
                                    <thead>
                                        <tr>
                                            <th>Installment No</th>
                                            <th>Due Date</th>
                                            <th>Paid Date</th>
                                            
                                            <th>Realizable</th>
                                            <th>Realized</th>
                                            <th>Overdue</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $i=1;
                                    foreach($loan_officer_summery['loan_table'] as $pay_row)
                                    {
                                        $due_date = strtotime($pay_row['LoanTransaction']['insta_due_on']);
                                        $today = strtotime(date("Y-m-d"));
                                        $d_class = '';
                                        $paid_on = '';
                                        $overdue_amt = 0;
                                        if($due_date<=$today){
                                            $paid_amt = $pay_row['0']['total_principal_paid']+$pay_row['0']['total_interest_paid'];
                                              
                                              if($paid_amt<$pay_row['0']['total_installment']){
                                                $d_class = 'class="text-danger"';
                                                $overdue_amt = $pay_row['0']['total_installment']-$paid_amt;
                                              }  
                                        }
                                        if($pay_row['LoanTransaction']['insta_paid_on']!="0000-00-00"){
                                           $paid_on = date("d-M-Y",strtotime($pay_row['LoanTransaction']['insta_paid_on'])); 
                                        }
                                    ?>
                                        <tr <?=$d_class?> >
                                            <td><?=$i?></td>
                                            <td><?=date("d-M-Y",strtotime($pay_row['LoanTransaction']['insta_due_on']))?></td>
                                            <td><?=$paid_on?></td>
                                            <td><?=$pay_row['0']['total_installment']?></td>
                                            <td><?=$pay_row['0']['total_principal_paid']+$pay_row['0']['total_interest_paid']?></td>
                                            
                                            <td><?=$overdue_amt?></td>
                                        </tr>
                                    <?php
                                    $i++;
                                    }
                                    ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- /.box-body -->
		
						<?php
							} else { ?>
							 <div class="box-body">
								No Data Found
							 </div>
							
						<?php	}  ?>
                    </div>
                  </div>
			  </div>		  
						