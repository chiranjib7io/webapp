<?php
App::import('Controller', 'App');
$AppCont = new AppController;
//$details = $AppCont ->customer_loan_summary('699');
//pr($details);die;
?>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<!-- Include Date Range Picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<script src="<?php echo $this->webroot; ?>asset/google_chart.js" type="text/javascript"></script>

 <script>
		function call_fun1(main_val){
			//alert(main_val);
			if(main_val=='4'){
				$('#hidden_date_range').show();
			}else{
			 $('#hidden_date_range').hide();
             $('#start_date').val('');
             $('#end_date').val('');
			}
		}
	</script>
	
<script type="text/javascript">
/*  
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawAnnotations);

function drawAnnotations() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Day');
      data.addColumn('number', 'Customer');
      
      
        data.addRows([
		<?php 
			foreach ($dashboard_array['loan_details'] as $ktran=>$trans_array){
			$date_start=date("d/m", strtotime($trans_array['LoanTransaction']['insta_due_on']));
			$realizable=$trans_array[0]['total_installment'];
			$realized=$trans_array[0]['total_principal_paid'] + $trans_array[0]['total_interest_paid'];
		?>
			  ['<?= $date_start ?>', <?= $realizable ?>, <?= $realized ?>],
		<?php
			} 
		?>
      ]);
      

      var options = {
        title: '',
        subtitle: '',
        annotations: {
          alwaysOutside: true,
          textStyle: {
            fontSize: 14,
            color: '#000',
            auraColor: 'none'
          }
        },
        hAxis: {
          title: 'Date',
          format: 'd-M-Y',
          viewWindow: {
            min: [7, 30, 0],
            max: [17, 30, 0]
          }
        },
        vAxis: {
          title: 'Customer No'
        }
      };

      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }

*/
    
    
    $(function() {
        $('#datefilter').daterangepicker({
             "autoApply": true,
			 "startDate":"<?= date('m-d-Y',strtotime($send_date['start_date'])) ?>",
			 "endDate":"<?= date('m-d-Y',strtotime($send_date['end_date'])) ?>",
             "opens": "left"
        }, function(start, end, label) {
            $('#start_date').val(start.format('YYYY/MM/DD'));
            $('#end_date').val(end.format('YYYY/MM/DD'));
          
        });
     }); 
     
function filterGlobal () {
    $('#customerListingTable').DataTable().search(
        $('#global_filter').val()
    ).draw();
}
 
function filterColumn ( i ) {
    $('#customerListingTable').DataTable().column( i ).search(
        $('#col'+i+'_filter').val()
    ).draw();
}
  
function selectFilterColumn ( i , val) {
    $('#customerListingTable').DataTable().column( i ).search( val ).draw();
} 
    
    
    
$(function () {
    
    
    var table = $('#customerListingTable').DataTable( {
                    "scrollX": false,
                    "jQueryUI": false,
                    "ordering": true,
                    "info":     true 
                });// table end
                
    //***************************SEARCH SECTION************************************************************/
    			$('input.global_filter').on( 'keyup click', function () {
    				filterGlobal();
    			} );
    		 
    			$('input.column_filter').on( 'keyup click', function () {
    				filterColumn( $(this).attr('data-column') );
    			} );
    			
    			$('.select_filter').on( 'change', function () {
    				selectFilterColumn($(this).attr('data-column') , this.value);			  
    			});       
                
    //*****************************************************************************************************/                        
    
    $('#branchList').change(function() {                
        var id = $(this).find(':selected').data('id');
        var url= '<?php echo $this->Html->url('/Customers/ajaxKendraList/'); ?>'+id;
		$.post( url, function( data ) {
		 $( "#kendraList" ).html( data );
		});                    
    });
    
});     
     
     
     
      
    </script>
    
    
            <!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                No Of Customer
              </h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">No Of Customer</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">                  
                  <div class="col-xs-12">
                	<div class="box no-border">
                    <div class="box-header no-border">
                          <div class="form-group" style="margin:10px auto 0; width:100%; text-align:center;">
                                
                               		<?php echo $this->Form->create('',array('class'=>'')); ?>
                                <div class="col-md-5 col-sm-5">
									<?php echo $this->Form->input('selectdate', array(
                                       'options' => array('1'=>'Current week', '2'=> 'Last week', '3'=> 'Current Month', '4'=> 'Choose Date'),
                                       'default' => $option_val,
                                       'label'=>false,
                                        'class' => 'form-control',
                                        'onchange' => 'call_fun1(this.value)'
                                    ));
                                    ?>
                                </div>	
                                  
                               <div id="hidden_date_range" style="display: <?=($option_val!='4')?'none':'block'?>;">
                                      <div class="col-md-5 col-sm-5"> 
                                      <input type="text" name="datefilter" id="datefilter"  class="form-group form-control" />
                                      <input type="hidden" name="start_date" id="start_date"  />
                                      <input type="hidden" name="end_date" id="end_date"  />
                                      
                                      </div>
                                </div>
                                <div class="col-md-2 col-sm-2" style="margin-top:2px;">
                                	<button type="submit" class="btn btn-primary btn-sm" >Submit</button>
                                </div>
								  <!--<div class="box-footer">
									<button type="submit" class="btn btn-primary btn-sm" >Submit</button>
								</div>-->
							  <?php echo $this->Form->end(); ?>
                          </div><!-- /.form group -->
                        </div><!-- /.chart_box_header -->
                        
                        <div class="chart_box">
                          <div class="chart_box" style="margin:0">
                            <!--<div class="chart_box_header">
                              <h3 class="chart_box_title">No Of Customer</h3>
                            </div>--><!-- /.chart_box_header -->
                            <!--chart starts--->
                            <div class="chart_box_body text-center">
                                <div class="chart_box_body text-center" id="chart_div">
                              
                                </div>
                            </div>
                            <!-- /.chart end -->
                          </div><!-- /.chart_box-->
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                </div><!-- /.col -->
                  
                  <div class="col-xs-12">
				  	<div class="row">
                  		<div class="col-md-4 col-sm-6">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Total Customer</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$no_of_cust_array['total_customer']?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                          </div><!-- /.col -->
                  		<div class="col-md-4 col-sm-6">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">New Customer</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$no_of_cust_array['new_customer']?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                          </div><!-- /.col -->
                  		<div class="col-md-4 col-sm-6">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Average Loan Amount</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$no_of_cust_array['avarage_loan']?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                          </div><!-- /.col -->
                  		<div class="col-md-4 col-sm-6">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Members Left</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$no_of_cust_array['customer_left']?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                          </div><!-- /.col -->
                  		<div class="col-md-4 col-sm-6">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Maximum Customer Duration</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$no_of_cust_array['max_customer_duration']?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                          </div><!-- /.col -->
                  		<div class="col-md-4 col-sm-6">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Highest Loan Amount</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$no_of_cust_array['max_loan_amount']?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                          </div><!-- /.col -->
                  		<div class="col-md-4 col-sm-6">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Lowest Loan Amount</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$no_of_cust_array['min_loan_amount']?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                          </div><!-- /.col -->
                  		<div class="col-md-4 col-sm-6">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Male Percentage</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                <?php
                                    if($no_of_cust_array['total_customer']==0){
                                        $total_male_percent=0;
                                        $total_female_percent=0;
                                    } else {
                                        $total_male_percent=$no_of_cust_array['total_male_customer']/$no_of_cust_array['total_customer']*100;
                                        $total_female_percent=$no_of_cust_array['total_female_customer']/$no_of_cust_array['total_customer']*100;
                                    }
                                ?>
                                    <h3 align="center"><?=$total_male_percent; ?>%</h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                          </div><!-- /.col -->
                  		<div class="col-md-4 col-sm-6">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Female Percentage</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$total_female_percent; ?>%</h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                          </div><!-- /.col -->
                  		<div class="col-md-4 col-sm-6">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Average Age</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$no_of_cust_array['avarage_age']?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                          </div><!-- /.col -->
                  		<div class="col-md-4 col-sm-6">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Minimum Age</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$no_of_cust_array['min_age']?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                          </div><!-- /.col -->
                  		<div class="col-md-4 col-sm-6">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Maximum Age</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$no_of_cust_array['max_age']?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                          </div><!-- /.col -->
                	</div>          
                </div><!-- /.col -->
                
                <div class="row">
            <div class="col-xs-12">
              <div class="box col-xs-12" id="customerListingTableDiv" style="display: block;">
                <div class="box-header">
                  <h3 class="box-title">Customer List</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                
                    <table id="customerListingTable" class="table table-bordered ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer Name</th>
                                <th>Branch Name</th>
                                <th>Kendra Name</th>                                
                                <th>Member Joining Date</th>
                                <th>Loan No</th>
                                <th>Repay Amount</th>
                                <th>Paid Percentage</th>
                                <th>Overdue</th>
                            </tr>
                        </thead>
                        <tbody>
                       <?php
                        if(!empty($customers_data)){
                            foreach($customers_data as $k2=>$customer){
								$customer_link='/customer_edit/'. $customer['Customer']['id'];
								$customer_url= $this->base.'/customer_details/'.$customer['Customer']['id'];
								$customer_loan_summary = $AppCont ->customer_loan_summary($customer['Customer']['id']);
                                $tot_loan =0;
                                $tot_repay_amt =0;
                                $tot_paid_percentage =0;
                                $tot_overdue =0;
                                foreach($customer_loan_summary as $k=> $summary){
                                    //pr($summary);
                                    $tot_overdue = $tot_overdue + $summary['total_overdue'];
                                    $tot_paid_percentage = $tot_paid_percentage + $summary['percentage_paid'];
                                    $tot_repay_amt = $tot_repay_amt + $summary['loan_repay_total'];
                                }
                                $tot_loan = count($customer_loan_summary);
                                $tot_paid_percentage = round($tot_paid_percentage/$tot_loan,2) ; 
                                
                        ?>
                            <tr>
							
                               <td><a href="<?=$customer_url?>" style="color:inherit"><?=$k2+1?></a></td>
                                <td><a href="<?=$customer_url?>" style="color:inherit"><?=$customer['Customer']['cust_fname'].'&nbsp;'.$customer['Customer']['cust_lname']?> </a></td>
                                <td><?=$customer['Branch']['branch_name']?></td>
                                <td><?=$customer['Kendra']['kendra_name'].' ( '.$customer['Kendra']['kendra_number'].' ) '?></td>                                
                                <td><?=date('d-M-Y',strtotime($customer['Customer']['created_on']))?></td>
								<td><?=$tot_loan?></td>
								<td><?=$tot_repay_amt?></td>
                                <td><?=$tot_paid_percentage?></td>
                                <td><?=$tot_overdue?></td>
                            </tr>
                        <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                    
                    
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
          
               
                        
                        
                         
              </div><!-- /.row -->
            
            </section><!-- /.content -->
            