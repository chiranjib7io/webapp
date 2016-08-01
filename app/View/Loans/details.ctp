<?php
$loan_principal = $loan_data['Loan']['loan_principal'];
$loan_interest = $loan_data['Loan']['loan_interest'];
$inst_amount = $loan_data['Loan']['loan_rate'];
$repaytotal_amount = $loan_data['Loan']['loan_repay_total'];
$currency = $loan_data['Loan']['currency'];
$period_unit = $loan_data['Loan']['loan_period_unit'];
$loan_period = $loan_data['Loan']['loan_period'];
$loan_type = $loan_data['Loan']['interest_type'];
//$loan_no = $loan_summary['loan_number'];

$loan_dateout = $loan_data['Loan']['loan_dateout'];
//pr($loan_summary); die;

?>

<!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Loan Details [Account no: <?=$loan_data['Account']['account_number']?>]
                <small class="text-green"><?php echo $this->Session->flash() ?></small>
                <!--<small class="text-danger">Waring Message</small>-->
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Loan Details</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">
             <!---customer info starts----->
                <div class="col-xs-12">
                	<div class="box box-primary" style="float:left; padding-bottom:20px;">
                    	<div class="box-header with-border">
                          <h3 class="box-title"><?php echo $loan_data['Customer']['fullname']; ?> </h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="profile_info" style="width:100%; padding-bottom:20px;">
                                <ul>
                                    <li><strong>Loan principal:</strong> <?= $this->Number->currency($loan_principal,'',array('places'=>0)); ?> <?php echo $currency; ?></li>
                                    <li><strong>Interest:</strong> <?= $loan_interest ?>% p.a <?=$loan_type?> Interest</li>
                                    <?php 
                                    if($loan_data['Loan']['loan_issued']==1){
                                    ?>
                                    <li><strong>Loan Installment Start Date:</strong> <?php echo date("d-M-Y",strtotime($loan_data['Loan']['loan_repay_start'])); ?></li>
                                    <li><strong>Loan Repay Amount:</strong> <?= $this->Number->currency($repaytotal_amount,'',array('places'=>0)); ?> <?php echo $currency; ?></li>
                                    <?php
                                    }
                                    //pr($loan_overdue);die;
                                    ?>
                                    <li><strong>Application Date:</strong> <?php echo date("d-M-Y",strtotime($loan_data['Loan']['loan_date'])); ?></li>
                                    <li><strong>No. of Period:</strong> <?php echo $loan_period; ?> <?=$period_unit?></li>
                                    <li><strong>Realize amount:</strong> <?= $this->Number->currency($loan_data['overdue']['realise_amount'],'',array('places'=>0)).' '.$currency?></li>
                                    <li><strong>Realizable amount:</strong> <?= $this->Number->currency($loan_data['overdue']['realisable_amount'],'',array('places'=>0)).' '.$currency?></li>
                                    <li><strong>Total Overdue:</strong> <?= $this->Number->currency($loan_data['overdue']['current_overdue'],'',array('places'=>0)).' '.$currency?></li>
                                    <li><strong>No. of Overdue:</strong> <?=round($loan_data['overdue']['current_overdue']/$loan_data['Loan']['loan_rate'])?></li>
                                    <li><strong>Last Paid Date:</strong> <?php echo date("d-M-Y",strtotime($loan_data['overdue']['last_paid_date'])); ?></li>
                                    <li><strong>Loan Status:</strong> <?php echo $loan_data['LoanStatus']['status_name']; ?></li>
                                    <li><strong>Loan Purpose:</strong> <?php echo $loan_data['Loan']['loan_purpose']; ?></li>
                                    
                                </ul>
                            </div>
                            
                            <?php
                            if($userData['user_type_id']==2){
                            ?>
                            <?PHP echo $this->Form->create('Loan', array('method' => 'post')); ?>
                            <div class="col-md-5 col-sm-12" style="margin-bottom:10px;"><?php echo $this->Form->input('Loan.id', array('type' => 'hidden','value'=>$loan_data['Loan']['id'],'label'=>false)); ?>
                            <input type="date" class="form-control" id="loan_dateout" name="data[insta_start]" placeholder="Enter Installment Start Date" required="required">
                            </div>
                            <div class="col-md-5 col-sm-12" style="margin-bottom:10px;">
								<?php echo $this->Form->input("Loan.loan_status_id", array(
                                        'options' => $loan_status,
                                        'empty' => $loan_data['LoanStatus']['status_name'],
                                        'label'=>false,
                                        'class'	=> 'form-control',
                                        'required'=>'required'
                                    ));
                            
                                ?>
                             </div>   
                            <div class="col-md-2 col-sm-6" style="margin-bottom:10px;"><input type="submit" class="btn btn-success" value="Submit" ></div>
                            <?php echo $this->Form->end(); ?> 
                            <?php } ?> 
                            
                        </div>
                    </div>
                </div>
              	<!---customer info ends----->
                  <?php //if(!empty($loan_data['LoanTransaction'])) { ?>
                  <?php if(!empty($loan_trans)) { 
					//pr($loan_trans); die;
				  ?>
                  <div class="col-xs-12">
                  
                      <div class="box box-primary">
                        <div class="box-header with-border">
                          <h3 class="box-title">Loan Summary</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body" style="padding-bottom:20px;">
                        	<div class="table-responsive">
                                <table id="example2" class="table table-bordered table-hover kendra_list">
                                    <thead>
                                        <tr>
                                            <th>Serial No</th>
                                            <th>Due Date</th>
                                            <th>Paid Date</th>
                                            
                                            <th>Installment Due</th>
                                            <th>Total Installment Paid</th>
                                            <th>Overdue Paid</th>
                                            <th>Prepayment</th>
                                            
                                            <th>Principal Paid</th>
                                            <th>Interest Paid</th>
                                            
                                            <th>Current Overdue</th>
                                            <th>Current Outstanding</th>
                                            <th>Paid Installment no</th>
                                         <!--   <th>Payment</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    
                                    $i=1;
                                    foreach($loan_trans as $trans_row)
                                    {
										//pr($trans_row); die;
                                        $realized = $trans_row['LoanTransaction']['insta_principal_paid']+$trans_row['LoanTransaction']['insta_interest_paid'];
                                        $today = strtotime(date("Y-m-d"));
                                        $due_day = ($trans_row['LoanTransaction']['insta_due_on']!='0000-00-00')?date("d-M-Y",strtotime($trans_row['LoanTransaction']['insta_due_on'])):'-';
                                        $overdue=0;
                                        $insta_color = '';
                                        $overdue_color ='';
                                        $prepay_color='';
                                        $row_red = 0;
                                        
                                        if($realized>0)
                                            $insta_color = 'blue';
                                        if($trans_row['LoanTransaction']['overdue_paid']>0)
                                            $overdue_color = 'blue';
                                        if($trans_row['LoanTransaction']['prepayment']>0)
                                            $prepay_color = 'blue';
                                        if($today>=strtotime($trans_row['LoanTransaction']['insta_due_on']) && $realized==0){
                                            $row_red = 1;
                                        }
                                        /*if(($today>=$due_day) && ($trans_row['LoanTransaction']['total_installment']>$realized)){
                                            $overdue = $trans_row['LoanTransaction']['total_installment']-$realized;
                                        }*/
                                        $overdue = $trans_row['LoanTransaction']['overdue_principal']+$trans_row['LoanTransaction']['overdue_interest'];
                                    ?>
                                        <tr <?=($overdue>0 || $row_red==1)?'class="text-danger"':''?>>
                                          <!--  <td><?=($trans_row['LoanTransaction']['insta_no']>0)?$trans_row['LoanTransaction']['insta_no']:'-'?></td>-->
                                            <td><?=$i++?></td>
                                            <td><?=$due_day?></td>
                                            <td><?=($trans_row['LoanTransaction']['insta_paid_on']!="0000-00-00")?date("d-M-Y",strtotime($trans_row['LoanTransaction']['insta_paid_on'])):''?></td>
                                            
                                            <td><?=$this->Number->currency($trans_row['LoanTransaction']['total_installment'],'',array('places'=>0)).' '.$currency?></td>
                                            <td style="color:<?=$insta_color?>;"><?=$this->Number->currency($realized,'',array('places'=>0)).' '.$currency?></td>
                                            <td style="color:<?=$overdue_color?>;"><?=$this->Number->currency($trans_row['LoanTransaction']['overdue_paid'],'',array('places'=>0)).' '.$currency?></td>
                                            <td style="color:<?=$prepay_color?>;"><?=$this->Number->currency($trans_row['LoanTransaction']['prepayment'],'',array('places'=>0)).' '.$currency?></td>
                                            
                                            <td><?=$this->Number->currency($trans_row['LoanTransaction']['insta_principal_paid'],'',array('places'=>0)).' '.$currency?></td>
                                            <td><?=$this->Number->currency($trans_row['LoanTransaction']['insta_interest_paid'],'',array('places'=>0)).' '.$currency?></td>
                                            
                                            
                                            
                                            <td><?=$this->Number->currency($overdue,'',array('places'=>0)).' '.$currency?></td>
                                            <td><?=$this->Number->currency($trans_row['LoanTransaction']['current_outstanding'],'',array('places'=>0)).' '.$currency?></td>
                                            <td><?=($trans_row['LoanTransaction']['current_outstanding']>0)?intval(($repaytotal_amount-$trans_row['LoanTransaction']['current_outstanding'])/$trans_row['LoanTransaction']['total_installment']):'N/A'?></td>
                                           <!-- <td> -->
                                            <?php
                                            //if($trans_row['LoanTransaction']['total_installment']>$realized){
                                            ?>
                                            <!--    <a href="<?php echo $this->Html->url('/amount_collection/'.$loan_data['Account']['account_number']);?>"><input type="submit" class="btn btn-danger" value="Make Payment" ></a> -->
                                             <!--   <a href="<?php echo $this->Html->url('/single_loan_collection/'.$trans_row['LoanTransaction']['id']);?>"><input type="submit" class="btn btn-danger" value="Make Payment" ></a>-->
                                            <?php    
                                           // }
                                           // if($trans_row['LoanTransaction']['total_installment']==$realized){
                                            ?>    
                                             <!--   <input type="submit" class="btn btn-success" value="Already Paid" disabled > -->
                                            <?php   
                                           // }
                                            ?>
                                         <!--   </td> -->
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
                  <?php } ?>
              </div><!-- /.row -->
            
            </section><!-- /.content -->