<script type="text/javascript">
function filterGlobal () {
    $('#collectionTable').DataTable().search(
        $('#global_filter').val()
    ).draw();
}
 
function filterColumn ( i ) {
    $('#collectionTable').DataTable().column( i ).search(
        $('#col'+i+'_filter').val()
    ).draw();
}
  
function selectFilterColumn ( i , val) {
    $('#collectionTable').DataTable().column( i ).search( val ).draw();
} 
    
    
    
$(function () {
    
    
    var table = $('#collectionTable').DataTable( {
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
                Saving Account List
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Saving Account List</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
            
            <div class="row">
            <div class="col-md-12">
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                <div class="box-body col-md-4 col-sm-4 ">
                <?php echo $this->Form->create('Statictic',array('action'=>'saving_account_list')); ?>
                    <div class="form-group">
                        <label>Market</label>
                        <?php
							echo $this->Form->input('Saving.market_id', array('type' => 'select', 'options' => $market_list, 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'empty' => 'Select Market'));
						?>
                    </div><!-- /.form group -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" >Submit</button>
                    </div><!-- /.form group -->
                </form>
                </div><!-- /.box-body -->
                
              </div><!-- /.box -->

            </div>
          </div><!-- /.row -->
            
              <div class="row">
              	<div class="col-xs-12">
                	<div class="box no-border">
                        
                        <div class="box-body" style="padding-top:15px; padding-bottom:15px;">
                          <div class="table-responsive">
						  <?php if ($post_val==0){ ?>
						  <table class="table table-bordered" id="collectionTable">
                              <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Account Number</th>
                                    <th>Customer Name</th>
                                    <th>Branch Name</th>
                                    <th>Saving Balance</th>
                                    <th>Last Payment Date</th>
                                    <th>Details</th>
                                </tr>
                              </thead>
                              <tbody>
							  <?php
								foreach($saving_account_data as $ks=>$saving){
							  ?>
							  <tr>
								<td><?= $ks+1; ?></td>
								<td><?= $saving['Account']['account_number']; ?></td>
								<td><?= $saving['Customer']['fullname']; ?></td>
								<td><?= $saving['Branch']['branch_name']; ?></td>
								<td><?= $saving['Saving']['current_balance']; ?></td>
								<td><?= date("d-m-Y", strtotime($saving['Saving']['modified_on'])); ?></td>
								<td>#</td>
							  </tr> 
								<?php } ?>
                              </tbody>
                            </table>
							<?php } ?>
                          </div><!-- /.table-responsive -->
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                </div><!-- /.col -->
              </div><!-- /.row -->
            
            </section><!-- /.content -->