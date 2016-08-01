<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<!-- Include Date Range Picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />


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

<script type="text/javascript">

        
function market_list(val){
    var url= '<?php echo $this->Html->url('/Loans/ajaxMarketList/'); ?>'+val;
	$.post( url, function( data ) {
	   //alert(data);
	   $( "#marketList" ).html( data );
	}); 
    
}          
      
</script>
<!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
               Market Summary
              </h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Market Summary</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">                  
                  <div class="col-xs-12">
                	<div class="box box-primary" style="float:left">
						<div class="box-header with-border" style="border-top:none; padding-top:20px;padding-bottom:20px;">
                        
                        
                        <div class="form-group" style="margin:10px auto 0; width:100%; text-align:center;">
                                
                               <?php echo $this->Form->create('User',array('class'=>'')); ?>
                                <div class="col-lg-4 col-md-5 col-sm-12" style="margin-bottom:5px;">
                                    <?php echo $this->Form->input("branch_id", array(
    										'options' => $branch_list,
    										'empty' => 'Select Branch',
    										'label'=>false,
                                            'required'=>true,
    										'class'	=> 'form-control',
                                            'onchange'=> 'market_list(this.value)'
    									));
    								?>									
                                </div>
                                
                                <div class="form-group col-md-4 col-sm-12" style="text-align:center">
								<?php echo $this->Form->input("market_id", array(
										'options' => $market_list,
										'empty' => 'Select Market',
										'label'=>false,
                                        'required'=>true,
										'class'	=> 'form-control',
                                        'id'=>'marketList'
									));
								?>
								</div>
                                
                                <div class="col-lg-4 col-md-5 col-sm-12" style="margin-bottom:5px;">
									<?php echo $this->Form->input('selectdate', array(
                                       'options' => array('1'=>'Current week', '2'=> 'Last week', '3'=> 'Current Month', '4'=> 'Choose Date'),
                                       'default' => $option_val,
                                       'label'=>false,
                                       'required'=>true,
                                        'class' => 'form-control',
                                        'onchange' => 'call_fun1(this.value)'
                                    ));
                                    ?>
								</div>	
                                  
                               <div id="hidden_date_range" style="display: <?=($option_val!='4')?'none':'block'?>;">
                                      <div class="col-lg-4 col-md-5 col-sm-12"> 
                                      <input type="text" name="datefilter" id="datefilter"  class="form-group form-control" />
                                      <input type="hidden" name="start_date" id="start_date"  />
                                      <input type="hidden" name="end_date" id="end_date"  />
                                      
                                      </div>
                                </div>
                                <div class="col-lg-1 col-md-2 col-sm-2">
                                	<button type="submit" class="btn btn-primary" >Submit</button>
									
                                </div>
								  <!--<div class="box-footer">
									<button type="submit" class="btn btn-primary btn-sm" >Submit</button>
								</div>-->
							  <?php echo $this->Form->end(); ?>
                          </div><!-- /.form group -->
                        
                        
                        </div>
                        
                      </div><!-- /.box -->
                </div><!-- /.col -->
                                    
                  <div class="col-xs-12">
				  	<div class="row">
                      <?php
                      if(!empty($branchLoanSummary)){
                      ?>  
                        <div class="col-md-6 col-sm-12">
                      <!-- TABLE: LATEST ORDERS -->
                      <div class="box no-border col-sm-12">
                        <div class="box-header no-border col-sm-12">
                          <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Market Information</h2>
                        </div><!-- /.box-header -->
                        <div class="box-body col-sm-12" style="padding-top:15px; padding-bottom:15px;">
                        	<div class="statistic_table1">
                                <div>
                                	
                                    	<div>
                                        	<strong>Name: </strong>
                                        </div>
                                        <div>
                                        	<?=$branchLoanSummary['market_details']['market_name'] ?>
                                        </div>
                                    
                                </div>	
                            	<div>
                                	
                                    	<div>
                                        	<strong>Credit Officer:</strong>
                                        </div>
                                        <div>
                                        	<?=$branchLoanSummary['officer_details']['first_name']?>  <?=$branchLoanSummary['officer_details']['last_name']?>
                                        </div>
                                    
                                </div>	
                            	<div>
                                	
                                    	<div>
                                        	<strong>Email ID:</strong>	
                                        </div>
                                        <div>
                                        	<?=$branchLoanSummary['officer_details']['email']?>
                                        </div>
                                   
                                </div>
                                <div>
                                	
                                    	<div>
                                        	<strong>No. of Group:</strong>
                                        </div>
                                  		<div>
											<?=$branchLoanSummary['total_group']?>
                                        </div>
                                	
                                </div>
								 <div>
                                	
                                    	<div>
                                        	<strong>No. of Customer:</strong>
                                        </div>
                                  		<div>
											<?=$branchLoanSummary['total_customer']?>
                                        </div>
                                	
                                </div>
                                
								 <div>
                                	
                                    	<div>
                                        	<strong>Address:</strong>
                                        </div>
                                  		<div>
											<?=$branchLoanSummary['market_details']['market_address'].', '.$branchLoanSummary['market_details']['market_city'].', '.$branchLoanSummary['market_details']['market_state'].', '.$branchLoanSummary['market_details']['market_zip']?>
                                        </div>
                                	
                                </div>
                                
                                <div>
                                	
                                    	<div>
                                        	<strong>Branch Name: </strong>
                                        </div>
                                        <div>
                                        	<?=$branchLoanSummary['branch_details']['branch_name'] ?>
                                        </div>
                                    
                                </div>
                                
                                
								
                            </div>
                          
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                  </div><!-- /.col -->
                        
                        
                        <div class="col-md-6 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Total Loan in market</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                    <!--<h3 align="center"><?= $summary_data['total_loan_in_mkt']['no_of_loan']?>/<?=$this->Number->currency($summary_data['total_loan_in_mkt']['due_balance'], 'Rs.',array('places'=>0))?></h3> -->
                                    <h3 align="center"><?= $summary_data['total_loan_in_mkt']?> Loan of <?=$this->Number->currency($summary_data['loan_amount_in_mkt'], 'Rs.',array('places'=>0))?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                        
                        <div class="col-md-6 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Total Loan</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
                                  <!--  <h3 align="center"><?= $summary_data['total_loan_ever']['no_of_loan']?>/<?=$this->Number->currency($summary_data['total_loan_ever']['total_loan_principal'], 'Rs.',array('places'=>0))?></h3> -->
                                    <h3 align="center"><?= $summary_data['total_loan_ever']['no_of_loan']?> Loan of <?=$this->Number->currency($summary_data['total_loan_ever']['total_loan_principal'], 'Rs.',array('places'=>0))?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                        <div class="clear"></div>
                        
                  		<div class="col-md-4 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Realizable Amount</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
								<?php
									if($send_date['option_val']==4){
								?>
								<h6 align="center"><?=date("d-m-Y", strtotime($send_date['start_date']))?> to <?=date("d-m-Y", strtotime($send_date['end_date']))?></h6>
								<?php
									} else {
								?>
								<h6 align="center"><?php echo $send_date['option_name']; ?></h6>
								<?php
									}
								?>
                                    <h3 align="center"><?=$this->Number->currency($summary_data['realizable_amt'], 'Rs.',array('places'=>0))?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                  		<div class="col-md-4 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Realized Amount</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
									<?php
									if($send_date['option_val']==4){
								?>
								<h6 align="center"><?=date("d-m-Y", strtotime($send_date['start_date']))?> to <?=date("d-m-Y", strtotime($send_date['end_date']))?></h6>
								<?php
									} else {
								?>
								<h6 align="center"><?php echo $send_date['option_name']; ?></h6>
								<?php
									}
								?>
                                    <h3 align="center"><?=$this->Number->currency($summary_data['realize_amt'], 'Rs.',array('places'=>0))?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                        
                  		<div class="col-md-4 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Percent Paid</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
								<?php
									if($send_date['option_val']==4){
								?>
								<h6 align="center"><?=date("d-m-Y", strtotime($send_date['start_date']))?> to <?=date("d-m-Y", strtotime($send_date['end_date']))?></h6>
								<?php
									} else {
								?>
								<h6 align="center"><?php echo $send_date['option_name']; ?></h6>
								<?php
									}
								?>
                                    <h3 align="center"><?=$summary_data['percentage_paid']?> %</h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                  		<div class="col-md-4 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Loan Overdue</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
								<?php
									if($send_date['option_val']==4){
								?>
								<h6 align="center"><?=date("d-m-Y", strtotime($send_date['start_date']))?> to <?=date("d-m-Y", strtotime($send_date['end_date']))?></h6>
								<?php
									} else {
								?>
								<h6 align="center"><?php echo $send_date['option_name']; ?></h6>
								<?php
									}
								?>
                                    <h3 align="center"><?=$this->Number->currency($summary_data['overdue_amount'], 'Rs.',array('places'=>0))?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                        
                        
                  		<div class="col-md-4 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">New Loan Application</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
								<?php
									if($send_date['option_val']==4){
								?>
								<h6 align="center"><?=date("d-m-Y", strtotime($send_date['start_date']))?> to <?=date("d-m-Y", strtotime($send_date['end_date']))?></h6>
								<?php
									} else {
								?>
								<h6 align="center"><?php echo $send_date['option_name']; ?></h6>
								<?php
									}
								?>
                                    <h3 align="center"><?=$summary_data['new_loan_application']['no_of_loan']?> Loan of <?=($summary_data['new_loan_application']['total_loan_principal']=='')?0:$this->Number->currency($summary_data['new_loan_application']['total_loan_principal'], 'Rs.',array('places'=>0))?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                        
                        <div class="col-md-4 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Savings Amount</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
								<?php
									if($send_date['option_val']==4){
								?>
								<h6 align="center"><?=date("d-m-Y", strtotime($send_date['start_date']))?> to <?=date("d-m-Y", strtotime($send_date['end_date']))?></h6>
								<?php
									} else {
								?>
								<h6 align="center"><?php echo $send_date['option_name']; ?></h6>
								<?php
									}
								?>
                                    <h3 align="center"><?=$summary_data['total_saving']['no_of_saving']?> Saving(s) of  <?=($summary_data['total_saving']['total_saving_balance']=='')?0:$this->Number->currency($summary_data['total_saving']['total_saving_balance'], 'Rs.',array('places'=>0))?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                        
					 <div class="clear"></div>
						
                  		<div class="col-md-4 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Loan Approved</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
								<?php
									if($send_date['option_val']==4){
								?>
								<h6 align="center"><?=date("d-m-Y", strtotime($send_date['start_date']))?> to <?=date("d-m-Y", strtotime($send_date['end_date']))?></h6>
								<?php
									} else {
								?>
								<h6 align="center"><?php echo $send_date['option_name']; ?></h6>
								<?php
									}
								?>
                                    <h3 align="center"><?=$summary_data['approved_loan']['no_of_loan']?> Loan of <?=($summary_data['approved_loan']['total_loan_principal']=='')?0:$this->Number->currency($summary_data['approved_loan']['total_loan_principal'], 'Rs.',array('places'=>0))?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                  		<div class="col-md-4 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Loan Disbursed</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
								<?php
									if($send_date['option_val']==4){
								?>
								<h6 align="center"><?=date("d-m-Y", strtotime($send_date['start_date']))?> to <?=date("d-m-Y", strtotime($send_date['end_date']))?></h6>
								<?php
									} else {
								?>
								<h6 align="center"><?php echo $send_date['option_name']; ?></h6>
								<?php
									}
								?>
                                    <h3 align="center"><?=$summary_data['disbursed_loan']['no_of_loan']?> Loan of  <?=($summary_data['disbursed_loan']['total_loan_principal']=='')?0:$this->Number->currency($summary_data['disbursed_loan']['total_loan_principal'], 'Rs.',array('places'=>0))?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                  		<div class="col-md-4 col-sm-12">
                              <div class="box no-border">
                                <div class="box-header no-border">
                                  <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">Loan Closed</h2>
                                </div><!-- /.box-header -->
                                <div class="box-body">
								<?php
									if($send_date['option_val']==4){
								?>
								<h6 align="center"><?=date("d-m-Y", strtotime($send_date['start_date']))?> to <?=date("d-m-Y", strtotime($send_date['end_date']))?></h6>
								<?php
									} else {
								?>
								<h6 align="center"><?php echo $send_date['option_name']; ?></h6>
								<?php
									}
								?>
                                    <h3 align="center"><?=$summary_data['closed_loan']['no_of_loan']?></h3>
                                </div><!-- /.box-body -->
                              </div><!-- /.box -->
                        </div><!-- /.col -->
                        <div class="clear"></div>   
                        <?php
                        }
                        ?>                
                	</div>         
                </div><!-- /.col -->            
              </div><!-- /.row -->
              
              
              <div class="row" style="display: none;">
              	<div class="col-xs-12">
                	<div class="box no-border">
                        <!--<div class="box-header no-border">
                          <h2 class="box-title" style="text-align:center;display:block; margin-top:10px;">STATISTICS</h2>
                        </div>--><!-- /.box-header -->
                        <div class="box-body" style="padding-top:15px; padding-bottom:15px;">
                          <div class="table-responsive">
                            <table class="table table-bordered" id="kendraListingTable">
                              <thead>
                                <tr>
                                    <th>Rank #</th>
                                   
                                    <th>Kendra Name</th>
                                  
                                    <th>Loan in market (Rs)</th>
                                    <th>Paid Percent</th>
                                    <th>Kendra Details</th>
                                    
                                </tr>
                              </thead>
                              <tbody>
							  
                              </tbody>
                            </table>
                          </div><!-- /.table-responsive -->
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                </div><!-- /.col -->
              </div><!-- /.row -->
              
              
            
            </section><!-- /.content -->