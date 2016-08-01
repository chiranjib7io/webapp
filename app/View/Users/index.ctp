<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<!-- Include Date Range Picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<script src="<?php echo $this->webroot; ?>asset/google_chart.js" type="text/javascript"></script>

<?php

//pr($send_date);
//pr($dashboard_array['loan_details']);

?>
 
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
<?php

if($send_date['date_diff']>'0'){

?>
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
       
        var data = new google.visualization.DataTable();
          data.addColumn('string', 'Date');
          data.addColumn('number', 'Realizable');
          data.addColumn('number', 'Realized');

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
          title: 'Collection Chart',
          curveType: 'function',
          legend: { position: 'top' },
          vAxis: {
              title: 'Rupees'
          },
          animation:{
            duration: 1000,
            easing: 'out',
          },
          hAxis: {
              title: 'Date',
              format: 'd-M-Y',
              /*titleTextStyle: {
                  fontSize: '20',
                  bold: 0,
                  italic: 0
              }*/
            },
          height:300,
          fontSize :12
          
        };
        

        var chart = new google.visualization.LineChart(document.getElementById('linechart_material'));

        chart.draw(data, options);
      }
    
    
    
    
     /*
      google.charts.load('current', {'packages':['annotationchart']});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', 'Date');
        data.addColumn('number', 'Realizable');
        data.addColumn('number', 'Realized');
       
        
        data.addRows([
		<?php
			foreach ($dashboard_array['loan_details'] as $ktran=>$trans_array){
			$date_start=date("Y-m-d", strtotime($trans_array['LoanTransaction']['insta_due_on']));
            $insta_due_on = strtotime($trans_array['LoanTransaction']['insta_due_on']).'000';
			$realizable=$trans_array[0]['total_installment'];
			$realized=$trans_array[0]['total_principal_paid'] + $trans_array[0]['total_interest_paid'];
		?>
			  [new Date(<?= $insta_due_on ?>), <?= $realizable ?>, <?= $realized ?>],
		<?php
			}
		?>
      ]);
        
        
        var chart = new google.visualization.AnnotationChart(document.getElementById('linechart_material'));

        var options = {
          displayAnnotations: false
        };

        chart.draw(data, options);
      }
      
      */
      
      
<?php
}else{
?>    
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawAnnotations);

function drawAnnotations() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Day');
      data.addColumn('number', 'Realizable');
      data.addColumn('number', 'Realized');
      
      
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
        title: 'Collection Chart',
        subtitle: 'in Rupees (INR)',
        legend: { position: 'top' },
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
          title: 'Rupees'
        }
      };

      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }

<?php
}
?>
    
    
    $(function() {
        $('#datefilter').daterangepicker({
             "autoApply": true,
			 "startDate":"<?= $send_date['start_date'] ?>",
			 "endDate":"<?= $send_date['end_date'] ?>",
             "opens": "left"
        }, function(start, end, label) {
            $('#start_date').val(start.format('YYYY/MM/DD'));
            $('#end_date').val(end.format('YYYY/MM/DD'));
          
        });
     });   
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
              <?php if(1){ ?>
                  <div class="col-sm-12">
                      <!-- TABLE: LATEST ORDERS -->
                      <div class="box no-border">
                        <div class="box-header no-border">
                          <!-- <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">DASHBOARD</h2> -->
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
                            <div class="chart_box_header">
                              <h3 class="chart_box_title">COLLECTION AND RELIZABLE <span style="font-size:12px; margin-left:10px;">Last Updated on <?= date("d-m-Y", strtotime($update_on)) ?></span></h3>
                            </div><!-- /.chart_box_header -->
                            <!--chart starts--->
                            <div class="chart_box_body text-center" id="linechart_material">
                              
                            </div>
                            <div class="chart_box_body text-center" id="chart_div">
                              
                            </div>
                            <!-- /.chart end -->
                          </div><!-- /.chart_box-->
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                  </div><!-- /.col -->
                  
                  <div class="col-md-4 col-sm-12">
                      <!-- TABLE: LATEST ORDERS -->
                      <div class="box no-border col-sm-12">
                        <div class="box-header no-border col-sm-12">
                          <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">STATISTICS</h2>
                        </div><!-- /.box-header -->
                        <div class="box-body col-sm-12" style="padding-top:15px; padding-bottom:15px;">
                        	<div class="statistic_table">	
                            	<div>
                                	<a href="<?= $this->Html->url('/stat_noc') ?>">
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
                                	<a href="<?= $this->Html->url('/stat_overdue') ?>">
                                    	<div>
                                        	<strong>No of Drops</strong>
                                        </div>
                                  		<div>
											<?= $dashboard_array['overdue_no'] ?>
                                        </div>
                                	</a>
                                </div>
								 <div>
                                	<a href="<?= $this->Html->url('/stat_realize_realizable') ?>">
                                    	<div>
                                        	<strong>Realizable Amount</strong>
                                        </div>
                                  		<div>
											<?= $this->Number->currency($dashboard_array['realizable_amount'],'',array('places'=>0)); ?>
                                        </div>
                                	</a>
                                </div>
                                <div>
                                	<a href="<?= $this->Html->url('/stat_realize_realizable') ?>">
                                    	<div>
                                        	<strong>Realized Amount</strong>
                                        </div>
                                  		<div>
											<?= $this->Number->currency($dashboard_array['realized_amount'],'',array('places'=>0)); ?>
                                        </div>
                                	</a>
                                </div>
								 <div>
                                	<a href="#">
                                    	<div>
                                        	<strong>Loan on Market</strong>
                                        </div>
                                  		<div>
											<?= $this->Number->currency($dashboard_array['total_loan_in_market'], '',array('places'=>0)) ?>
                                        </div>
                                	</a>
                                </div>
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
                  	  <div class="box no-border no-n-heading-box">
                        <div class="box-header no-border">
                          <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">BEST BRANCH</h2>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                          	<h1 class="heading2" align="center">Lakhikantapur</h1>
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
					  
                  </div><!-- /.col --> 
                  
                  <div class="col-md-4 col-sm-12">
                  	<?php
						if($dashboard_array['call_array_val']['user_type_id']== 5) {
					  ?>
                  	  <div class="box no-border no-n-heading-box">
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
                          	<a href="<?= $this->Html->url('/stat_loan_officers') ?>" ><h1 class="number_heading" align="center"><?= $dashboard_array['total_lo'] ?></h1></a>
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
					  <?php
						}
                        
					  ?>
                  
                  </div>
                  
                 
              <?php }else{ echo "<h3>Coming Soon</h3>"; } ?>                
              </div><!-- /.row -->
            
            </section><!-- /.content -->