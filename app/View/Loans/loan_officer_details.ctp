<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<!-- Include Date Range Picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
<?php

$final_date= strtotime($final_date);
$current_date = date('m/d/Y',$final_date);
$last_date = date('m/d/Y', strtotime(date("d-m-Y",$final_date).'-70 days'));
?>
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

$(function () {
    
    
    var table = $('#example2').DataTable( {
                    "scrollX": false,
                    "jQueryUI": false,
                    "ordering": true,
                    "info":     true 
                });// table end
                
    
                
        $.fn.dataTable.ext.search.push(
			  function( settings, data, dataIndex ) {
				  var min = ( $('#start_date').val() );
				  var max = ( $('#end_date').val() );
				  var dateR = ( data[2] ) || 2; // use data for the date column
				  
				  if(min!='' && max!=''){
					if (
						 ( min <= dateR   && dateR <= max )
						)
					{
						return true;
					}
				  }else if(min!='' && max==''){
					if ( 
						 ( min <= dateR )
						)
					{
						return true;
					}
				  }else{
					return true;
				  }
				  return false;
			  }
		);
			
	   $('#start_date , #end_date').on( 'change', function () {	 
				table.draw();
		});
        $('#clearDataTable').on( 'click', function () {
                $('#datefilter').val('');
                $('#start_date').val('');
                $('#end_date').val('');
				table.draw();
		});     
             
           
        $('#datefilter').daterangepicker({
             "autoApply": true,
             "opens": "left",
             locale: {
                format: 'MM/DD/YYYY'
             },
             "startDate": "<?=$last_date?>",
             "endDate": "<?=$current_date?>"
        }, function(start, end, label) {
            $('#start_date').val(start.format('MM/DD/YYYY'));
            $('#end_date').val(end.format('MM/DD/YYYY'));
            table.draw();
          
        });        
        table.draw(); // starting with the fileration 
                
});
function loan_officer_list(val){
    var url= '<?php echo $this->Html->url('/Loans/ajaxLoanOfficerList/'); ?>'+val;
	$.post( url, function( data ) {
	   //alert(data);
	   $( "#loanOfficerList" ).html( data );
	}); 
    
}
</script>
<? 
//pr($loan_officer_summery['loan_table']);
//die;

?>
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
							 <?PHP echo $this->Form->create('Loan', array('method' => 'post', 'action' => '/loan_officer_details/')); ?>
                             <?php if(!empty($branch_list)){?>
                             <div class="form-group col-md-5 col-sm-12" style="text-align:center">
								<?php echo $this->Form->input("branch_id", array(
										'options' => $branch_list,
										'empty' => 'Select Branch',
										'label'=>false,
										'class'	=> 'form-control',
                                        'onchange'=> 'loan_officer_list(this.value)'
									));
								?>
								</div>
                                <?php } ?>
								<div class="form-group col-md-4 col-sm-12" style="text-align:center">
								<?php echo $this->Form->input("user_id", array(
										'options' => $lo_list,
										'empty' => 'Select Loan Officer',
										'label'=>false,
										'class'	=> 'form-control',
                                        'id'=>'loanOfficerList'
									));
								?>
								</div>
								<div class="input-group col-md-2 col-sm-12" >
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
                                    <li><strong>Total Repay amount:</strong> <?=$this->Number->currency($loan_officer_summery['total_loan'],'',array('places'=>0))?></li>
                                    <li><strong>Loan in Market:</strong> <?=$this->Number->currency($loan_officer_summery['total_loan_market'],'',array('places'=>0))?></li>
                                    <li><strong>Total Realizable:</strong> <?=$this->Number->currency($loan_officer_summery['total_realizable'],'',array('places'=>0))?></li>
                                    <li><strong>Total Realized:</strong> <?=$this->Number->currency($loan_officer_summery['total_relaized'],'',array('places'=>0))?></li>
                                    <li><strong>Total Overdue:</strong> <?=$this->Number->currency($loan_officer_summery['total_overdue'],'',array('places'=>0))?></li>
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
                          <h3 class="box-title">Loan Summary</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body" style="padding-bottom:20px;">
                        	<div class="table-responsive">
                            <div id="" class="dataTables_filter" style="position: absolute;right:300px;z-index: 100;">
                                    <label>Due Date Range:
                                        <input type="text" name="datefilter" id="datefilter" placeholder="Due Date Range" />
                                          <input type="hidden" name="start_date" id="start_date" value="<?=$last_date?>"  />
                                          <input type="hidden" name="end_date" id="end_date" value="<?=$current_date?>"  />
                                          <a style="cursor: pointer;" id="clearDataTable">clear</a>
                                    </label>
                                </div>
                                
                                <table id="example2" class="table table-bordered table-hover kendra_list">
                                    <thead>
                                        <tr>
                                            <th>#</th>
											
                                            <th>Due Date</th>
                                            <th>Paid Date</th>
                                            
                                            <th>Realizable</th>
                                            <th>Realized</th>
                                            <th>Overdue Paid</th>
                                            <th>Prepayment</th>
                                            <th>Overdue</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    
                                    $i=1;
                                    foreach($loan_officer_summery['loan_table'] as $k=>$pay_row)
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
                                           $paid_on = date("m/d/Y",strtotime($pay_row['LoanTransaction']['insta_paid_on'])); 
                                        }
										//$kendra_names=$pay_row['0']['kendra_details'];
										//$final_string=$this->Text->toList(explode(',',$kendra_names));
                                    ?>
                                        <tr <?=$d_class?> >
                                            <td><?=$k+1?></td>
											
                                            <td><?=date("m/d/Y",strtotime($pay_row['LoanTransaction']['insta_due_on']))?></td>
                                            <td><?=$paid_on?></td>
                                            <td><?=$pay_row['0']['total_installment']?></td>
                                            <td><?=$pay_row['0']['total_principal_paid']+$pay_row['0']['total_interest_paid']?></td>
                                            <td><?=$pay_row['0']['total_overdue_paid']?></td>
                                            <td><?=$pay_row['0']['total_prepayment']?></td>
                                            
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
								
							 </div>
							
						<?php	}  ?>
                    </div>
                  </div>
			  </div>		  
						