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
    var x = document.getElementById("UserKendraId").selectedIndex;
    var action = document.getElementsByTagName("option")[x+1].value;
    if (action !== "") {
        document.getElementById("UserKendraLoanDetailsForm").action = action;
        
    } else {
        alert("Please select Kendra");
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
        
        
        $('#branchList').change(function() {                
            //var id = $(this).find(':selected').data('id');
            var id = $(this).val();
            var url= '<?php echo $this->Html->url('/Loans/ajaxKendraList/'); ?>'+id;
        	$.post( url, function( data ) {
        	   $( "#kendraList" ).html( data );
        	});                    
        });
                
});
</script>

<!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Kendra Loan Summary
                <!--<small class="text-green">Success Message</small>
                <small class="text-danger">Waring Message</small>-->
              </h1>
              <ol class="breadcrumb">
               <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Kendra Loan Summary</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">
             <!---customer info starts----->
                <div class="col-xs-12">
                	<div class="box box-primary" style="float:left">
                    	<div class="box-header with-border">
						<?php
							if(!empty($kendra_data)){
						?>
                          <h3 class="box-title"><?=$kendra_data['Kendra']['kendra_name'].'-'.$kendra_data['Kendra']['kendra_number']?></h3>
						  <?php } ?>
                        </div><!-- /.box-header -->
						<div class="box-header with-border" style="border-top:none; padding-top:20px;padding-bottom:20px;">
							 <?PHP echo $this->Form->create('Loan', array('method' => 'post', 'action' => '/kendra_loan_details/')); ?>
								<div class="form-group col-md-6 col-sm-12">
								<?php echo $this->Form->input("branch_id", array(
    										'options' => $branch_list,
    										'default' => $branch_id,
    										'label'=>false,
                                            'empty' => 'Select Branch',
    										'class'	=> 'form-control',
                                            'id'=>'branchList'
    									));
								?>
								</div>
								<div class="form-group col-md-6 col-sm-12">	
								<?php echo $this->Form->input("kendra_id", array(
										'options' => $kendra_list,
										'default' => $kendra_id,
										'label'=>false,
                                        'empty' => 'Select Kendra',
										'class'	=> 'form-control',
										'id'=>"kendraList"
									));
							
								?>
								</div>
								<div class="input-group col-xs-12" style="text-align:center">
									<input type="submit" class="btn btn-success" onclick="myfunction()" value="Submit" >
								</div>
								<?php echo $this->Form->end(); ?> 
                        </div><!-- /.box-header -->
						<?php
							if(!empty($kendra_data)){
						?>
                        <div class="box-body">
                            <div class="profile_info" style="width:100%; padding-bottom:20px;">
                                <ul>
                                    <li><strong>Kendra Pradhan Name:</strong> <?=$kendra_data['Kendra']['kendra_pradhan_name']?></li>
                                    
                                    <li><strong>Phone no:</strong> <?=$kendra_data['Kendra']['phone_no']?></li>
                                    <li><strong>Organisation name:</strong> <?=$kendra_data['Organization']['organization_name']?></li>
                                    <li><strong>No. of Customer:</strong> <?=count($kendra_data['Customer'])?></li>                                    
                                    <li><strong>Address:</strong> <?=$kendra_data['Kendra']['address'].', '.$kendra_data['Kendra']['city'].', '.$kendra_data['Kendra']['state'].', '.$kendra_data['Kendra']['zip']?></li>
                                    <li><strong>Total Loan Principal:</strong> <?=$this->Number->currency($kendra_data['Kendra']['total_loan'],'',array('places'=>0))?></li>
                                    <li><strong>Loan in Market:</strong> <?=$this->Number->currency($kendra_data['Kendra']['total_loan_in_market'],'',array('places'=>0))?></li>
                                    <li><strong>Total Overdue:</strong> <?=$this->Number->currency($kendra_data['Kendra']['total_overdue'],'',array('places'=>0))?></li>
                                    <li><strong>Percent paid:</strong> <?=$kendra_data['Kendra']['percent_paid']?>%</li>
                                    <li><strong>Total Realizable:</strong> <?=$this->Number->currency($summary_data['realizable_amt'],'',array('places'=>0))?></li>
                                    <li><strong>Total Realized:</strong> <?=$this->Number->currency($summary_data['realizable_amt'],'',array('places'=>0))?></li>
                                    <li><strong>New Loan Application:</strong> <?=$summary_data['new_loan_application']['no_of_loan']?></li>
                                    <li><strong>Loan Approved:</strong> <?=$summary_data['approved_loan']['no_of_loan']?></li>
                                    <li><strong>Loan Disbursed:</strong> <?=$summary_data['disbursed_loan']['no_of_loan']?></li>
                                    <li><strong>Loan Closed:</strong> <?=$summary_data['closed_loan']['no_of_loan']?></li>
                                    
                                    
                                </ul>
                                
									
                            </div>
                        </div>
						<?php } ?>
                    </div>
                </div>
              	<!---customer info ends----->
                  
                  <div class="col-xs-12">
                      <div class="box box-primary">
                        <div class="box-header with-border">
                          <h3 class="box-title">Loan Details</h3>
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
                                            <th>Serial No</th>
                                            <th>Customer Name</th>
                                            <th>Due Date (mm/dd/yyyy)</th>
                                            <th>Paid Date(mm/dd/yyyy)</th>
                                            
                                            <th>Realizable</th>
                                            <th>Realized</th>
                                            <th>Overdue</th>
                                            
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $i=1;
                                    foreach($loan_payment_list as $pay_row)
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
										$customer_details=$pay_row['0']['customer_details'];
										$final_string=$this->Text->toList(explode(',',$customer_details));
                                    ?>
                                        <tr <?=$d_class?> >
                                            <td><?=$i?></td>
											<td><?=wordwrap($final_string,25,"<br>\n");?></td>
                                            <td><?=date("m/d/Y",strtotime($pay_row['LoanTransaction']['insta_due_on']))?></td>
                                            <td><?=$paid_on?></td>
                                            <td><?=$this->Number->currency($pay_row['0']['total_installment'],'',array('places'=>0))?></td>
                                            <td><?=$this->Number->currency(($pay_row['0']['total_principal_paid']+$pay_row['0']['total_interest_paid']),'',array('places'=>0))?></td>
                                            
                                            <td><?=$this->Number->currency($overdue_amt,'',array('places'=>0))?></td>
                                            <td>
                                            <?php
                                            if(($overdue_amt>0) || ($paid_on=='')){
                                            ?>
                                                <a href="<?php echo $this->Html->url('/kendra_loan_collection/'.$kendra_data['Kendra']['id'].'/'.$pay_row['LoanTransaction']['insta_due_on']);?>"><input type="submit" class="btn btn-danger" value="Make Payment" ></a>
                                            <?php    
                                            }
                                            if(($overdue_amt==0) && ($paid_on!='')){
                                            ?>    
                                                <input type="submit" class="btn btn-success" value="Already Paid" disabled >
                                            <?php   
                                            }
                                            ?>
                                            
                                            </td>
                                        </tr>
                                    <?php
                                    $i++;
                                    }
                                    ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- /.box-body -->
                    </div>
                  </div>
			  </div>		  
						