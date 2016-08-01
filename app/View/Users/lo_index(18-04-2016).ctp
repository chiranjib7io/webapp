<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<!-- Include Date Range Picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<script src="<?php echo $this->webroot; ?>asset/google_chart.js" type="text/javascript"></script>


	
<script type="text/javascript">
<?php
if($send_date['date_diff']>'0'){

?>
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
       
        var data = new google.visualization.DataTable();
          data.addColumn('string', 'Kendra');
          data.addColumn('number', 'Realizable');
          data.addColumn('number', 'Realized');

      data.addRows([
		<?php
			foreach ($dashboard_array['loan_collection'] as $ktran=>$trans_array){
			$kendra_name=$trans_array['Kendra']['kendra_name'];
			$realizable=$trans_array[0]['realizable_amount'];
			$realized=$trans_array[0]['realized_amount'];
		?>
			  ['<?= $kendra_name ?>', <?= $realizable ?>, <?= $realized ?>],
		<?php
			}
		?>
      ]);



        var options = {
          title: 'Collection Chart',
          curveType: 'function',
          legend: { position: 'bottom' },
          vAxis: {
              title: 'Rupees'
          },
          animation:{
            duration: 1000,
            easing: 'out',
          }
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('linechart_material'));

        chart.draw(data, options);
      }
    
    
    
    
      
      
<?php
}
?>
  
    </script>
	
	

	
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
                        <!--<div class="chart_box">
                          <div class="chart_box" style="margin:0">
                            <div class="chart_box_header">
                              <h3 class="chart_box_title">COLLECTION AND RELIZABLE AS ON <?=date("d-M-Y",strtotime($send_date['start_date']))?></h3>
                            </div><!-- /.chart_box_header -->
                            <!--chart starts---
                            <div class="chart_box_body text-center" id="linechart_material">
                              
                            </div>
                            <div class="chart_box_body text-center" id="chart_div">
                              
                            </div>
                            <!-- /.chart end --
                          </div><!-- /.chart_box--
                        </div>--><!-- /.box-body end chart box -->
                      </div><!-- /.box -->
                  </div><!-- /.col -->
                  
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
                        <div class="box-header no-border">
                          <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">BEST BRANCH</h2>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                          	<h1 class="heading2" align="center">Mathurapur</h1>
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
					  
                  </div><!-- /.col --> 
                  
                  <div class="col-md-4 col-sm-12">
                  	<?php
						if($dashboard_array['call_array_val']['user_type_id']== 5) {
					  ?>
                  	  <div class="box no-n-heading-box">
                        <div class="box-header no-border">
                          <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">KENDRA</h2>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                          	<h1 class="number_heading" align="center"><?= $dashboard_array['total_kendra'] ?></h1>
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
					  <?php
						} else {
					  ?>
					  <div class="box no-border no-n-heading-box">
                        <div class="box-header no-border">
                          <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">OFFICERS</h2>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                          	<h1 class="number_heading" align="center"><?= $dashboard_array['total_lo'] ?></h1>
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
					  <?php
						}
					  ?>
                  
                  </div>
                  
                  <!--table area---->
                  <div class="col-sm-12">
                  	<div class="chart_box">
                  		<div class="summery_box">
                                <div class="summery_box_header">
                                	<h2 class="summery_box_title" style="text-align:center;display:block;line-height:22px;">SUMMARY</h2>
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