<?php
$saving_id = 'saving_'.$saving_data['Saving']['id'];
$savings_date = $saving_data['Saving']['savings_date'];
$current_balance = $saving_data['Saving']['current_balance'];
$interest_rate = $saving_data['Saving']['interest_rate'];
$branch_name = $saving_data['Branch']['branch_name'];
$kendra_name = $saving_data['Kendra']['kendra_name'];
$currency=$saving_data['Currency']['cur_short'];


?>

<!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Saving Details [<?=$saving_id?>]
                <small class="text-green"><?php echo $this->Session->flash() ?></small>
                <!--<small class="text-danger">Waring Message</small>-->
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Saving Details</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">
             <!---customer info starts----->
                <div class="col-xs-12">
                	<div class="box box-primary" style="float:left">
                    	<div class="box-header with-border">
                          <h3 class="box-title"><?php echo $saving_data['Customer']['cust_fname']; ?> <?php echo $saving_data['Customer']['cust_lname']; ?></h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="profile_info" style="width:100%; padding-bottom:20px;">
                                <ul>
                                    <li><strong>Current Balance:</strong> <?php echo $current_balance; ?> <?php echo $currency; ?></li>
                                    <li><strong>Interest:</strong> <?php echo $interest_rate; ?>% p.a</li>
                                    <li><strong>Saving Start From</strong> <?php echo date("d-M-Y",strtotime($saving_data['Saving']['created_on'])); ?></li>
                                </ul>
                            </div>
                            
                           
                        </div>
                    </div>
                </div>
              	<!---customer info ends----->
                  
                  <div class="col-xs-12">
                  
                      <div class="box box-primary">
                        <div class="box-header with-border">
                          <h3 class="box-title">Saving Summary</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body" style="padding-bottom:20px;">
                        	<div class="table-responsive">
                                <table id="example2" class="table table-bordered table-hover kendra_list">
                                    <thead>
                                        <tr>
                                            <th>Diposit No</th>
                                            <th>Paid Date</th>
                                            <th>Amount</th>
                                            <th>Total Saving</th>
                                            <th>Transaction Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
									$count=1;
                                    foreach($saving_data['SavingsTransaction'] as $trans_row)
                                    {
                                        $paid_day = strtotime($trans_row['transaction_on']);
                                    ?>
                                        <tr>
                                            <td><?=$count?></td>
                                            <td><?=date("d-M-Y",$paid_day)?></td>
                                            <td><?= $trans_row['amount'].' '.$currency ?></td>
                                            <td><?=$trans_row['balance'].' '.$currency?></td>
                                            <td><?=$trans_row['transaction_type']?></td>
                                        </tr>
                                        <?php
										$count++;
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