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
                    //"ajax": "<?=$this->base.'ajax_kendra_list/'?>",
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
                Market List
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Market List</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
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
                                    <th>Branch Name</th>
                                    <th>Market Name</th>
                                    <th>Total Customer</th>
                                    <th>Loan Repay Total(INR)</th>
                                    <th>Total Overdue (INR)</th>
                                    <th>Total Realizable (INR)</th>
                                    <th>Total Realized (INR)</th>
                                    <th>Paid Percent</th>
                                    <th>Market Loan Details</th>
                                    <th></th>
                                </tr>
                              </thead>
                              <tbody>
							  <?php
								 if(!empty($market_data)){
								 //pr($market_data['data']); die;
									foreach($market_data['data'] as $k=>$kendra){
										//pr($kendra); die;
										 /*
										$kendra_link='/save_kendra/'. $kendra[0];
										$edit_link='<a href="'. $this->Html->url($kendra_link) .'"> Edit </a>';
                                       
                                        if($kendra[6]>0)
                                            $paid_percent = round(($kendra[7]/$kendra[6]*100),2);
                                        else
                                            $paid_percent = 0;
										*/
										//pr($kendra); die;
							  ?>
                                  <tr>
                                    <td><?=$k+1?></td>
                                    <td><?=$kendra[1]?></td>
                                    <td><?=$kendra[2]?></td>
                                    <td><?=$kendra[3]?></td>
                                    <td><?=$kendra[4]?></td>
                                    <td><?=$kendra[5]?></td>
                                    <td><?=$kendra[6]?></td>
                                    <td><?=$kendra[7]?></td>
                                    <td><?=$kendra[8]?></td>
                                    <td><?=$kendra[9]?></td>
                                    <td><?=$kendra[10]?></td>
								  </tr>
                                   <?php
										}
									}else{
									?>    
										<tr>
											<td colspan="8">No Result Found</td>
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