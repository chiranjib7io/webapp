<script>
$(function () {
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
<script type="text/javascript">
function filterGlobal () {
    $('#kendraListingTable').DataTable().search(
        $('#global_filter').val()
    ).draw();
}
 
function filterColumn ( i ) {
    $('#kendraListingTable').DataTable().column( i ).search(
        $('#col'+i+'_filter').val()
    ).draw();
}
  
function selectFilterColumn ( i , val) {
    $('#kendraListingTable').DataTable().column( i ).search( val ).draw();
} 
    
    
    
$(function () {
    
    
    var table = $('#kendraListingTable').DataTable( {
                    "scrollX": false,
                    "jQueryUI": false,
                    "ordering": true,
                    "info":     true,
                    //"ajax": "<?=$this->base.'/kendras/ajax_kendra_list/'?>",
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
    
    <? if(!empty($branch_data['Branch']['id'])){ ?>
        $('#kendraListingTable').DataTable().column( 1 ).search( "<?=$branch_data['Branch']['branch_name']?>" ).draw();
    <?} ?>
    
});
        
          
      
</script>

            <!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
              Maturity Reports
               <!--  <small class="text-green"><b></b></small>
               <small class="text-danger">Waring Message</small>-->
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Maturity Reports</li>
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
                                <div class="col-lg-3 "></div>
                                <div class="col-lg-4 ">From <input type="date" name="fdate" required="required"> Next 7 Days</div>	
								
								
                                  
                              
                                
                                
                                
                                <div class="col-lg-2">
                                	<button type="submit" class="btn btn-primary" >Reports</button>
                                    </div>
									
                                </div>
								  
							  <?php echo $this->Form->end(); ?>
                          </div><!-- /.form group -->
                        
                        
                        </div>
                        
                      </div><!-- /.box -->
                </div><!-- /.col -->
            <!-- Bulk Loan Release Table Start -->
           
            
                          <div class="row">
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
                                    <th>#</th>
                                    <th>Account Number</th>
                                    <th>Customer Name</th>
                                    <th>Branch Name</th>
                                    <th>Market Number</th>
                                   <th>Repay Total</th>
                                   <th>Principal Paid</th>
                                   <th>Interest Paid</th>
                                    <th>Application Date</th>
                                    <th>Maturity Date</th>
                                    <th>Details</th>
                                </tr>
                              </thead>
                              <tbody>
							  <?php
                             
								 if(!empty($loan_data)){
									foreach($loan_data as $k=>$loan_cust){	
										$loan_link='/customer_details/'. $loan_cust['Loan']['customer_id'];
										$details_loan_link='<a href="'. $this->Html->url($loan_link) .'"> Details </a>';
							  ?>
                                  <tr>
                                    <td><?=$k+1?></td>
                                    <td><?=$loan_cust['Account']['account_number']?></td>
                                    <td><?=$loan_cust['Customer']['fullname']?></td>
                                    <td><?=$loan_cust['Branch']['branch_name']?></td>
                                    <td><?=$loan_cust['Market']['market_name']?></td>
                                    <td><?=$loan_cust['Loan']['loan_repay_total']?></td>
                                     <td><?=$loan_cust['Loan']['loan_repay_total']?></td>
                                      <td><?=$this->Slt->getTotalInterestPaid($loan_cust['Loan']['id']);?></td>
                                    <td><?=date("d-M-Y",strtotime($loan_cust['Loan']['loan_date']))?></td>
                                      <td><?=date("d-M-Y",strtotime($loan_cust['Loan']['maturity_date']))?></td>
									<td><?= $details_loan_link ?></td>
								  </tr>
                                   <?php
										}
									}else{
									?>    
										<tr>
											<td colspan="7">No Result Found</td>
										</tr>
									<?php
									}
									?>
                              </tbody>
                            </table>
                          </div><!-- /.table-responsive -->
                          
                          
                          
                          
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                </div><!-- /.col -->
              </div><!-- /.row -->
            
            
            </section><!-- /.content -->
            
