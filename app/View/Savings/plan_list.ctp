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
                Plan List
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Plan List</li>
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
                                    <th>Plan Name</th>
                                    <th>Plan Type</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                              </thead>
                              <tbody>
							  <?php
								 if(!empty($plan_list)){
									//pr($plan_list); die;
									foreach($plan_list as $k=>$plan){
										//pr($plan); die;
										if($plan['Plan']['plan_type']==1) {
											$plan_type='Saving';
											$edit_url='edit_saving_plan';
										} else{
											$plan_type='Loan';
											$edit_url='edit_loan_plan';
										}
										$plan_link='/'.$edit_url.'/'. $plan['Plan']['id'];
										$del_link='/delete_plan/'. $plan['Plan']['id'];
										$edit_link='<a href="'. $this->Html->url($plan_link) .'"> Edit </a>';
										$delete_link='<a href="'. $this->Html->url($del_link) .'" onclick="return confirm(\'Do you want to delete this plan?\');"> Delete </a>';
							  ?>
                                  <tr>
                                    <td><?=$k+1?></td>
                                    <td><?=$plan['Plan']['plan_name']?></td>
                                    <td><?=$plan_type ?></td>
                                    <td><?=$edit_link ?></td>
									<td><?= $delete_link ?></td>
								  </tr>
                                   <?php
										}
									}else{
									?>    
										<tr>
											<td colspan="5">No Result Found</td>
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