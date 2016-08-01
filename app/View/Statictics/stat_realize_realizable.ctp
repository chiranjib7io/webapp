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

google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawAnnotations);

function drawAnnotations() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Day');
      data.addColumn('number', 'Realizable');
      data.addColumn('number', 'Realized');
      
      
        data.addRows([
		<?php
			foreach ($summary_data['loan_details'] as $ktran=>$trans_array){
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
        },
        height:300,
        fontSize :12
      };

      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
    
</script>

<script>
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
</script>
<!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Realized/ Realizable Amount
              </h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Realized/ Realizable Amount</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">                  
                  <div class="col-sm-12">
                	<div class="box no-border">
                        <div class="chart_box">
                          <div class="chart_box" style="margin:0">
                            <div class="chart_box_header">
                              <!--<h3 class="chart_box_title">Realized/ Realizable Amount</h3>-->
                              <div class="form-group" style="margin:10px auto 0; width:100%; text-align:center;">
                                
                               		<?php echo $this->Form->create('User',array('class'=>'')); ?>
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
                            <!--chart starts--->
                            <div class="chart_box_body text-center">
                              
                              
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
                              
                              
                            </div>
                            <!-- /.chart end -->
                          </div><!-- /.chart_box-->
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                </div><!-- /.col -->
                                    
                  <div class="col-xs-12">
				  	<div class="row">
                  		<div class="col-md-4 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Total Realizable</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$this->Number->currency($summary_data['realizable_amt'], '',array('places'=>0))?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                  		<div class="col-md-4 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Total Realized</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$this->Number->currency($summary_data['realize_amt'], '',array('places'=>0))?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                  		<div class="col-md-4 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Total Loan in market</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$this->Number->currency($summary_data['total_loan_in_mkt'], '',array('places'=>0))?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                  		
                        <div class="clear"></div>
                        
                  		<div class="col-md-6 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Best Brunch</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$summary_data['best_wrost_data']['best_branch']?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                  		<div class="col-md-6 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Best Kendra</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$summary_data['best_wrost_data']['best_kendra']?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                        <div class="clear"></div>
                        
                  		<div class="col-md-3 col-sm-6">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">New Loan Application</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$summary_data['new_loan_application']['no_of_loan']?>/Rs. <?=($summary_data['new_loan_application']['total_loan_principal']=='')?0:$this->Number->currency($summary_data['new_loan_application']['total_loan_principal'], '',array('places'=>0))?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                  		<div class="col-md-3 col-sm-6">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Loan Approved</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$summary_data['approved_loan']['no_of_loan']?>/Rs. <?=($summary_data['approved_loan']['total_loan_principal']=='')?0:$this->Number->currency($summary_data['approved_loan']['total_loan_principal'], '',array('places'=>0))?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                  		<div class="col-md-3 col-sm-6">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Loan Disbursed</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$summary_data['disbursed_loan']['no_of_loan']?>/Rs. <?=($summary_data['disbursed_loan']['total_loan_principal']=='')?0:$this->Number->currency($summary_data['disbursed_loan']['total_loan_principal'], '',array('places'=>0))?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                  		<div class="col-md-3 col-sm-6">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Loan Closed</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <h3 align="center"><?=$summary_data['closed_loan']['no_of_loan']?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                        <div class="clear"></div>                   
                	</div>         
                </div><!-- /.col -->            
              </div><!-- /.row -->
            
            </section><!-- /.content -->