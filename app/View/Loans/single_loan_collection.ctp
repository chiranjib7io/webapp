<?php
$loan_principal = $loan_summary['loan_principal'];
$loan_interest = $loan_summary['loan_interest'];
$inst_amount = $loan_summary['installment_amount'];
$repaytotal_amount = $loan_summary['loan_repay_total'];
$currency = $loan_summary['currency'];
$period_unit = $loan_summary['loan_period_unit'];
$loan_period = $loan_summary['total_installment_no'];
$loan_type = $loan_summary['loan_type'];
$loan_no = $loan_summary['loan_number'];
$loan_dateout = $loan_summary['loan_dateout'];
$due_date = $trans_data['LoanTransaction']['insta_due_on'];

?>

<div class="container">
            <!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Loan Collection Sheet on <?=date("d-M-Y",strtotime($due_date))?>
              </h1>
            </section>

        	<!-- Main content -->
            <section class="content">
              <!-- Small boxes (Stat box) -->
              <div class="row">
              	<div class="col-md-12">
                	<div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                        <div class="box-header with-border">
                          <h3 class="box-title"><?php echo $loan_data['Customer']['cust_fname']; ?> <?php echo $loan_data['Customer']['cust_lname']; ?></h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="profile_info" style="width:100%; padding-bottom:20px;">
                                <ul>
                                    <li><strong>Loan principal:</strong> <?php echo $loan_principal; ?> <?php echo $currency; ?></li>
                                    <li><strong>Interest:</strong> <?php echo $loan_interest; ?>% p.a <?=$loan_type?> Interest</li>
                                    
                                    <li><strong>Loan Installment Start Date:</strong> <?php echo date("d-M-Y",strtotime($loan_data['Loan']['loan_repay_start'])); ?></li>
                                    <li><strong>Loan Repay Amount:</strong> <?php echo $repaytotal_amount; ?> <?php echo $currency; ?></li>
                                    
                                    <li><strong>Application Date:</strong> <?php echo date("d-M-Y",strtotime($loan_data['Loan']['loan_date'])); ?></li>
                                    <li><strong>No. of Period:</strong> <?php echo $loan_period; ?> <?=$period_unit?></li>
                                    <li><strong>Total Overdue:</strong> <?=$loan_overdue[0][0]['total_overdue'].' '.$currency?></li>
                                    <li><strong>No. of Overdue:</strong> <?=$loan_overdue[0][0]['overdue_no']?></li>
                                    <li><strong>Loan Status:</strong> <?php echo $loan_data['LoanStatus']['status_name']; ?></li>
                                    <li><strong>Loan Purpose:</strong> <?php echo $loan_data['Loan']['loan_purpose']; ?></li>
                                </ul>
                            </div>
                            
                            
                        </div>
                    </div>
				</div>                
              </div><!-- /.row -->
              
              <div class="row">
                  <div class="col-md-5 col-sm-12">
                      <!-- TABLE: LATEST ORDERS -->
                      <div class="box box-success">
                        <div class="box-header with-border">
                          <h2 class="box-title" style="text-align:center;display:block;line-height:22px;">SUMMARY</h2>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                          <div class="table-responsive">
                            <table class="table no-margin">
                              <thead>
                                <tr>
                                  <td>Last Payment Date</td>
                                  <td><?=($loan_summary['last_paid_date']!='0000-00-00')?date("d-M-Y",strtotime($loan_summary['last_paid_date'])):'N/A'?></td>
                                </tr>
                                <tr>
                                  <td>Current Installment No.</td>
                                  <td><?=$trans_data['LoanTransaction']['insta_no']?></td>
                                </tr>
                                <tr>
                                  <td>Loan no</td>
                                  <td><?=$loan_no?></td>
                                </tr>
                              </thead>
                            </table>
                          </div><!-- /.table-responsive -->
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                  </div><!-- /.col -->
                  <div class="col-md-7 col-sm-12">
                  	  <div class="box box-warning">
                        <div class="box-header with-border">
                          <h3 class="box-title" style="text-align:center;display:block;line-height:22px;">TOTAL COLLECTION AMOUNT</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                          	<h1 class="heading" align="center"><?=$trans_data['LoanTransaction']['total_installment'].' '.$currency?></h1>
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                  </div><!-- /.col -->              
              </div><!-- /.row -->
              <div class="row">
              	<div class="col-xs-12"> 
                    <div class="box no-border" style="padding-top:20px; padding-bottom:20px; float:left">   
                      <div class="col-xs-6">
                            <a href="javascript: window.history.back()"><button type="button" class="btn btn-danger btn-lg">Cancel</button></a>
                        </div>
                      <div class="col-xs-6">
                      <?PHP echo $this->Form->create('LoanTransaction', array('method' => 'post')); ?>
                            <input type="hidden" name="cust_arr[<?=$trans_data['LoanTransaction']['customer_id']?>]" value="<?=$trans_data['LoanTransaction']['total_installment']?>" id="cust_arr1">
                            <?php echo $this->Form->input('LoanTransaction.kendra_id', array('type' => 'hidden','value'=>$trans_data['LoanTransaction']['kendra_id'],'label'=>false)); ?>
                            <?php echo $this->Form->input('LoanTransaction.insta_due_on', array('type' => 'hidden','value'=>$trans_data['LoanTransaction']['insta_due_on'],'label'=>false)); ?>
                            <?php echo $this->Form->input('LoanTransaction.insta_no', array('type' => 'hidden','value'=>$trans_data['LoanTransaction']['insta_no'],'label'=>false)); ?>
                            <button type="submit" class="btn btn-success btn-lg  pull-right">Pay now</button>
                      <?php echo $this->Form->end(); ?> 
                        </div>
                    </div>
                </div>
    		  </div>
              
            </section><!-- /.content -->
            
        </div> 