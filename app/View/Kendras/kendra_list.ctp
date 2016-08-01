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
                    "ajax": "<?=$this->base.'/kendras/ajax_kendra_list/'?>",
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
                Group List
                <!--<small class="text-green">Success Message</small>
                <small class="text-danger">Waring Message</small>-->
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Group List</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
            
            <div class="row">
            <div class="col-md-12">
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                <!--<div class="box-header">
                  <h3 class="box-title">Search Customer Form</h3>
                </div>-->
                <div class="box-body col-md-4 col-sm-4 ">
                    <div class="form-group">
                        <label>Branch</label>
                        <select class="form-control select_filter" data-column="1" onchange="" id="branchList">
                            <option value="" data-id="">All Branches</option>
                            <?php
                            
                            foreach($branches_data as $k=>$branch){
                            ?>
                            
							<option value="<?=$branch?>" data-id="<?=$k?>"><?=$branch?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div><!-- /.form group -->
                </div><!-- /.box-body -->
                
              </div><!-- /.box -->

            </div>
          </div><!-- /.row -->
            
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
                                   <!--  <th>Organization Name</th>-->
                                    <th>Branch Name</th>
                                    <th>Credit Officer Name</th>
                                    <th>Group Name</th>
                                  <!--  <th>Kendra Number</th> -->
                                    <th>Total Customer</th>
                                    <th>Loan Repay Total(INR)</th>
                                    
                                    <th>Total Overdue (INR)</th>
                                    <th>Total Realizable (INR)</th>
                                    <th>Total Realized (INR)</th>
                                    <th>Paid Percent</th>
                                    <th>Group Loan Details</th>
                                    <th></th>
                                </tr>
                              </thead>
                              <tbody>
							  <?php
                              /*
								 if(!empty($kendra_list)){
									foreach($kendra_list as $k=>$kendra){	
										$kendra_link='/save_kendra/'. $kendra['Kendra']['id'];
										$edit_link='<a href="'. $this->Html->url($kendra_link) .'"> Edit </a>';
                                        
                                        if($kendra['LoanSummary']['total_realiable']>0)
                                            $paid_percent = round(($kendra['LoanSummary']['total_realized']/$kendra['LoanSummary']['total_realiable']*100),2);
                                        else
                                            $paid_percent = 0;
							  ?>
                                  <tr>
                                    <td><?=$k+1?></td>
                                    <td><?=$kendra['Organization']['organization_name']?></td>
                                    <td><?=$kendra['Branch']['branch_name']?></td>
                                    <td><?=$kendra['Kendra']['kendra_name']?></td>
                                  <!--  <td><?=$kendra['Kendra']['kendra_number']?></td> -->
                                    <td><?=count($kendra['Customer'])?></td>
                                    <td><?=$kendra['LoanSummary']['total_loan']?></td>
                                    
                                    <td><?=$kendra['LoanSummary']['total_overdue']?></td>
                                    <td><?=$kendra['LoanSummary']['total_realiable']?></td>
                                    <td><?=$kendra['LoanSummary']['total_realized']?></td>
                                    <td><?=$paid_percent?> %</td> 
                                    <td><? if($kendra['LoanSummary']['total_loan']>0){ ?><a href="<?=$this->base.'/kendra_loan_details/'.$kendra['Kendra']['id']?>">List</a><? }else{ echo 'N/A'; }?></td>
									<td><?= $edit_link ?></td>
								  </tr>
                                   <?php
										}
									}else{
									?>    
										<tr>
											<td colspan="8">No Result Found</td>
										</tr>
									<?php
									}*/
									?>
                              </tbody>
                            </table>
                          </div><!-- /.table-responsive -->
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                </div><!-- /.col -->
              </div><!-- /.row -->
            
            </section><!-- /.content -->