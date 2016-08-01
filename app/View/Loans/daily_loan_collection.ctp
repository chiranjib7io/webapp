<?php
$mons = array('01' => "Jan", '02' => "Feb", '03' => "Mar", '04' => "Apr", '05' => "May", '06' => "Jun", '07' => "Jul", '08' => "Aug", '09' => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");

if(date("m-Y")==$month.'-'.$year){
    $max_day = date("d");
}else{
    $max_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);
}
?>
    


<!-- x-editable (bootstrap version) -->
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.4.6/bootstrap-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.4.6/bootstrap-editable/js/bootstrap-editable.min.js"></script>

<!-- bootstrap date picker -->
<link href="<?php echo $this->webroot; ?>asset/plugins/datepicker/bootstrap-combined.min.css" rel="stylesheet"/>
<script src="<?php echo $this->webroot; ?>asset/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<!-- Bootstrap date Picker -->
<link href="<?php echo $this->webroot; ?>asset/plugins/datepicker/datepicker3.css" rel="stylesheet"/>
    
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
                    "scrollX": true,
                    "jQueryUI": false,
                    "ordering": true,
                    "info":     true,
                    //"ajax": "<?=$this->base.'/kendras/ajax_kendra_list/'?>",
                    "deferRender": true
                }); //table end
      
      
      
                
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
  
  //toggle `popup` / `inline` mode
    $.fn.editable.defaults.mode = 'inline';   
      
<?php
    foreach($data_list as $k=>$data){
        
?>  
    <?php
        $day = 1;
                        
        while($day<=$max_day){
      ?> 
      
    
    //make column editable
    $('#deposit_<?=$day?>_<?=$data['Account']['id']?>').editable({
        type: 'text',
        name: 'amount',
        pk: <?=$data['Account']['id']?>,
        url: '<?= $this->Html->url('ajax_save_loan_transaction') ?>',
        title: 'Enter amount',
        validate: function(value) {
            if($.trim(value) == '') {
                return 'This field is required';
            }
        },
        params: function(params) {
            //originally params contain pk, name and value
            params.account_id = <?=$data['Account']['id']?>;
            params.transaction_on = '<?=$year.'-'.$month.'-'.sprintf("%'02s",$day)?>';
            return params;
        },
        success: function(response, newValue) {
            
            $('#deposit_<?=$day?>_<?=$data['Account']['id']?>').editable('toggleDisabled');
            $('#deposit_<?=$day?>_<?=$data['Account']['id']?>').html(newValue);
            balance = $('#balance_<?=$data['Account']['id']?>').html();
            newbalance = parseInt(newValue)+parseInt(balance);
            //$('#balance_<?=$data['Account']['id']?>').html(newbalance);
        }
    });
    



<?php
$day=$day+1;
}
?>
  
    
<?php
}
?>
 
 
 $("#datepicker").datepicker(
		    {viewMode: 'years',
            minViewMode: "months",
		     format: 'mm-yyyy'
		});
    
});

function group_list_of_market(mid){
        $.post("<?= $this->Html->url('ajax_group_list_of_market') ?>/"+mid, function(data, status){
                $('#kendra_id').html(data);
        });
    
}        

</script>
            <!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Daily Loan Collection
                <!--<small class="text-green">Success Message</small>
                <small class="text-danger">Waring Message</small>-->
              </h1>
              <ol class="breadcrumb">
                <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Loan Collection</li>
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
                <div class="box-body col-md-12 col-sm-12 ">
                <?php echo $this->Form->create('Loan',array('action'=>'')); ?>
                 
                 <div class="form-group col-md-4">
                        <label>Market</label>
                        <?php
							echo $this->Form->input('Loan.market_id', array('type' => 'select', 'options' => $market_list, 'class'=>'form-control', 'onchange'=>'group_list_of_market(this.value)', 'label'=>false, 'required'=>'required','default'=>$market_id , 'empty' => 'Select Market'));
						?>
                    </div><!-- /.form group -->
                    
                 <div class="form-group col-md-3">
                        <label for="Name">Group Name (optional)</label>
                        <?php
							echo $this->Form->input('kendra_id', array('type' => 'select','name'=>'kendra_id','id'=>'kendra_id', 'options' => $kendra_list, 'class'=>'form-control', 'label'=>false));
						?>
                    </div>
                    
                    
                   <div class="form-group col-md-3">
                        <label>Date</label>
                        <input id="datepicker" type="text" class="form-control" readonly="readonly" name="data[Loan][date]" required="required" value="<?=$month.'-'.$year?>" /> 
                        
                        
                        
                        
                    </div><!-- /.form group --> 
                    
                    
                    <div class="form-group col-md-2">
                        <br />
                        <button type="submit" class="btn btn-primary btn-lg" >Submit</button>
                    </div><!-- /.form group -->
                    
                </form>
                </div><!-- /.box-body -->
                
              </div><!-- /.box -->

            </div>
          </div><!-- /.row -->
            
              <div class="row">
              	<div class="col-xs-12">
                	<div class="box no-border">
                        <div class="box-header">
                          <h3 class="box-title"><?php echo $mons[$month].'-'.$year; ?></h3>
                        </div>
                        <div class="box-body" style="padding-top:15px; padding-bottom:15px;">
                          <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="collectionTable">
                              <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Account Number</th>
                                    <th>Customer Name</th>
                                    <th>Branch Name</th>
                                    
                                    <th>Loan Repay total</th>
                      <?php
                        $day = 1;
                        
                        
                        while($day<=$max_day){
                      ?>              
                                    <th><?=$day?>-<?=$mons[$month]?></th>
                      <?php
                        $day++;
                      }
                      ?>
                                    
                                </tr>
                              </thead>
                              <tbody>
							  <?php
                              //pr($trans_list);
                              //pr($pos);
                              foreach($data_list as $k=>$data){
							  ?>
                                  <tr>
                                    <td><?=$k+1;?></td>
                                    <td><?=$data['Account']['account_number']?></td>
                                    <td><?=$data['Customer']['cust_fname']." ".$data['Customer']['cust_lname']?></td>
                                    <td><?=$data['Branch']['branch_name']?></td>
                                    
                                    <td id="<?='balance_'.$data['Account']['id']?>"><?=$data['Loan']['loan_repay_total']?></td>
                                    
                       <?php
                        $day = 1;
                        
                        while($day<=$max_day){
                            //$pos = $this->Slt->array_find_deep($trans_list,date("Y-m").'-'.$day);
                           $flag =0;
                           foreach($trans_list as $tarr){
                           
                                    if($tarr['IncomeExpenditure']['account_id']==$data['Account']['id'] && $tarr['IncomeExpenditure']['transaction_date']==$year.'-'.$month.'-'.sprintf("%'02s",$day)){
                                        ?>
                                        <td id="<?=($tarr['IncomeExpenditure']['credit_amount']=='')?'deposit_'.$day.'_'.$data['Account']['id']:''?>" ><?=$tarr['IncomeExpenditure']['credit_amount']?></td>
                                        <?php
                                        $flag = 1;
                                        break;
                                    }
                                
                                    
                           }
                           if($flag==0){
                            ?>
                            <td id="<?='deposit_'.$day.'_'.$data['Account']['id']?>" ></td>
                            <?php
                           }

                        $day++;
                      }
                      ?>             
								  </tr>
                       <?php } ?>            
                              </tbody>
                            </table>
                          </div><!-- /.table-responsive -->
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                </div><!-- /.col -->
              </div><!-- /.row -->
            
            </section><!-- /.content -->