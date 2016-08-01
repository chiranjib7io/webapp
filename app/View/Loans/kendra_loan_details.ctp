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
    
    
    var table = $('#customerListingTable').DataTable( {
                    "scrollX": false,
                    "jQueryUI": false,
                    "ordering": true,
                    "info":     true,
                    "data": <?= (!empty ($customers_data['table_val'] ))? $customers_data['table_val'] : " ' ' " ?>,
                    "deferRender": true 
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
                  <?php if($show_val ==1) { ?>
		
                  <div class="col-xs-12">
                      <div class="box box-primary">
                        <div class="box-header with-border">
                          <h3 class="box-title">Customer List</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body" style="padding-bottom:20px;">
                        	<div class="table-responsive">
                                 <table id="customerListingTable" class="table table-bordered ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer Name</th>
                                <!--<th>Branch Name</th>
                                <th>Kendra Name</th>      -->                           
                                <th>Loan Amount</th>
                                <th>Installment Paid No</th>
                                <th>Last paid date</th>
                                <th>Overdue no</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                       
                        </tbody>
                    </table>
                            </div>
                        </div>
				
						
						<!-- Customer List of the Kendra Wise Start-->
						
						
			<!-- Customer List of the Kendra Wise End-->
						
						
						
						<!-- /.box-body -->
						<?php } ?>
						
                    </div>
                  </div>
			  </div>		  
