<?php $mons = array('01' => "Jan", '02' => "Feb", '03' => "Mar", '04' => "Apr", '05' => "May", '06' => "Jun", '07' => "Jul", '08' => "Aug", '09' => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec"); ?>
<script src="<?php echo $this->webroot; ?>asset/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<!-- Bootstrap date Picker -->
<link href="<?php echo $this->webroot; ?>asset/plugins/datepicker/datepicker3.css" rel="stylesheet"/>


<script>
		function call_fun1(main_val){
			//alert(main_val);
			if(main_val=='3'){
				$('#hidden_date_range').show();
                $('#datefilter').attr("required","required");
                $('#hidden_date_range2').hide(); 
                $('#datefilter2').removeAttr("required");
                $('#hidden_date_range3').hide();
                $('#datefilter3').removeAttr("required");
                 
			}else if(main_val=='4'){
			    $('#hidden_date_range2').show(); 
                $('#datefilter2').attr("required","required");
                $('#hidden_date_range').hide(); 
                $('#datefilter').removeAttr("required");
                $('#hidden_date_range3').hide();
                $('#datefilter3').removeAttr("required");
			}else if(main_val=='1'){
			    $('#hidden_date_range3').show(); 
                $('#datefilter3').attr("required","required");
                $('#hidden_date_range2').hide();
                $('#datefilter2').removeAttr("required"); 
                $('#hidden_date_range').hide();
                $('#datefilter').removeAttr("required");
			}else{
			 $('#hidden_date_range').hide();
             $('#start_date').val('');
             $('#end_date').val('');
			}
		}
	</script>



<script>

$(function(){
      
  
     $("#datefilter").datepicker(
		    {
		     format: 'dd-mm-yyyy',
             
		});   
      
  }); 
</script>

<!----------------------- Table Design Start -------------------------->
<script>
$(document).ready(function () {
			   
	$('tbody tr').hover(function() {
	  $(this).addClass('odd');
	}, function() {
	  $(this).removeClass('odd');
	});
    
    var table = $('#travel1').DataTable( {
                    "scrollX": true,
                    "jQueryUI": false,
                    "ordering": false,
                    "info":     false,
                     "paging": false,
                     "searching": false,
                     
                      
                });// table end
                
    
    

});

function printData()
{
   var divToPrint=document.getElementById("dvData");
   newWin= window.open("");
   newWin.document.write(divToPrint.outerHTML);
   newWin.print();
   newWin.close();
}


</script>

<style>
/* ---- Some Resets ---- */

p,
table, caption, td, tr, th {
	margin:0;
	padding:0;
	font-weight:normal;
	}

	
/* ---- Table ---- */

table {
	border-collapse:collapse;
	margin-bottom:15px;
    
	width:100%;
	}
	
	caption {
		text-align:left;
		font-size:15px;
		padding-bottom:10px;
		}
	
	table td,
	table th {
		padding:5px;
		border:1px solid #fff;
		border-width:0 1px 1px 0;
		}
		
	thead th {
		background:#91c5d4;
		}
			
		thead th[colspan],
		thead th[rowspan] {
			background:#66a9bd;
			}
		
	tbody th,
	tfoot th {
		text-align:left;
		background:#91c5d4;
		}
		
	tbody td,
	tfoot td {
		text-align:center;
		background:#d5eaf0;
		}
		
	tfoot th {
		background:#b0cc7f;
		}
		
	tfoot td {
		background:#d7e1c5;
		font-weight:bold;
		}
			
	tbody tr.odd td { 
		background:#bcd9e1;
		}
</style>
<!----------------------- Table Design End -------------------------->
<?php //pr($send_date);die; ?>
<!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
               Credit officer wise Receipt Payment Report
              </h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Credit officer wise Receipt Payment Report</li>
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
                                
                                	
                                  
                               
                                <div id="hidden_date_range3">
                                      <div class="col-lg-4 col-md-5 col-sm-12"> 
                                      <input type="text" name="datefilter" id="datefilter" value="<?=!empty($send_date['start_date'])?date("d-m-Y",strtotime($send_date['start_date'])):''?>" class="form-group form-control" />
                                      </div>
                                </div>
                                
                                
                                
                                
                                <div class="col-lg-1 col-md-2 col-sm-2">
                                	<button type="submit" class="btn btn-primary" >Submit</button>
									
                                </div>
								  
							  <?php echo $this->Form->end(); ?>
                          </div><!-- /.form group -->
                        
                        
                        </div>
                        
                      </div><!-- /.box -->
                </div><!-- /.col -->
                                    
                  <div class="col-xs-12">
                    <button onclick="printData()"> Print </button> 
                        
				  	<div class="row">
                        
                       <div class="col-sm-12" class="table-responsive" id="dvData"> 
                       
                        <table>
                         
                            <thead>    
                            	<tr>
                                    <th scope="col" rowspan="2">Payments Particulars</th>
                                    <th scope="col" colspan="<?=count($co_list)?>">Report as on <?=!empty($send_date['start_date'])?$send_date['start_date']:''?></th>
                                </tr>
                                
                                <tr>
                             <?php
                                foreach($co_list as $usr_row){
                                    $heading = $usr_row['User']['fullname'];
                             ?>
                                    <th scope="col"><?=$heading?></th>   
                             <?php
                                }
                             ?>
                                </tr>        
                            </thead>
                          
                            
                            
                            <tbody>
                       <?php
                       foreach($exp_list as $ldg_id=>$list_val){
                       ?>
                                <tr>
                            		<th scope="row"><?=$list_val?></th>
                              
                         <?php
                            foreach($co_list as $usr_row){
                                $usr_id = $usr_row['User']['id'];
                                
                         ?>
                         <?php
                                $exp_arr = $this->Slt->get_exp_by_user($org_id,$ldg_id,$usr_id,$send_date['start_date']);
                         ?>
                                <td><?=!empty($exp_arr[0][0]['exp'])?$exp_arr[0][0]['exp']:'0'?></td>   
                         <?php
                                
                            }
                         ?>
                         
                                    
                                </tr>
                       <?php
                       } // Foreach end
                       ?>
                            
                            	
                                
                            </tbody>
                            
                            
                            
                            
                        
                   <!---     </table>   -->
                 <!------------------------------ Receipt Table Start---------------------------------------->       
                 <!---       <table id="receipt"> -->
                         
                            <thead>    
                            	<tr>
                                    <th scope="col" rowspan="2">Receipts Particulars</th>
                                    <th scope="col" colspan="<?=count($co_list)?>">Report as on <?=!empty($send_date['start_date'])?$send_date['start_date']:''?></th>
                                </tr>
                                
                                <tr>
                             <?php
                                foreach($co_list as $usr_row){
                                    $heading = $usr_row['User']['fullname'];
                             ?>
                                    <th scope="col"><?=$heading?></th>   
                             <?php
                                }
                             ?>
                                </tr>        
                            </thead>
                        
                            
                            
                            <tbody>
                       <?php
                       foreach($income_list as $ldg_id=>$list_val){
                       ?>
                                <tr>
                            		<th scope="row"><?=$list_val?></th>
                              
                         <?php
                            foreach($co_list as $usr_row){
                                $usr_id = $usr_row['User']['id'];
                                
                         ?>
                         <?php
                                $exp_arr = $this->Slt->get_income_by_user($org_id,$ldg_id,$usr_id,$send_date['start_date']);
                         ?>
                                <td><?=!empty($exp_arr[0][0]['exp'])?$exp_arr[0][0]['exp']:'0'?></td>   
                         <?php
                                
                            }
                         ?>
                        
                                    
                                </tr>
                       <?php
                       } // Foreach end
                       ?>
                            
                            	
                                
                            </tbody>
                            
                            
                            
                            <tfoot>
                            	<tr>
                                	<th scope="row"><strong>Excess/Defecit</strong></th>
                                    
                                     <?php
                                        foreach($co_list as $usr_row){
                                            $usr_id = $usr_row['User']['id'];
                                            $exp_tot_arr = $this->Slt->get_total_income_by_user($org_id,$usr_id,$send_date['start_date']);
                                            $exp_tot_arr2 = $this->Slt->get_total_exp_by_user($org_id,$usr_id,$send_date['start_date']);
                                     ?>
                                            <td><?=(!empty($exp_tot_arr[0][0]['exp'])||!empty($exp_tot_arr2[0][0]['exp']))?$exp_tot_arr[0][0]['exp']-$exp_tot_arr2[0][0]['exp']:'0'?></td>   
                                     <?php
                                        }
                                     ?>
                                     
                                </tr>
                            </tfoot>
                            <tfoot>
                            	<tr>
                                	<th scope="row"><strong>Total Expenses</strong></th>
                                    
                                     <?php
                                        foreach($co_list as $usr_row){
                                            $usr_id = $usr_row['User']['id'];
                                            $exp_tot_arr = $this->Slt->get_total_exp_by_user($org_id,$usr_id,$send_date['start_date']);
                                     ?>
                                            <td><?=!empty($exp_tot_arr[0][0]['exp'])?$exp_tot_arr[0][0]['exp']:'0'?></td>   
                                     <?php
                                        }
                                     ?>
                                     
                                </tr>
                            </tfoot>
                            <tfoot>
                            	<tr>
                                	<th scope="row"><strong>Total Receipts</strong></th>
                                    
                                     <?php
                                        foreach($co_list as $usr_row){
                                            $usr_id = $usr_row['User']['id'];
                                            $exp_tot_arr = $this->Slt->get_total_income_by_user($org_id,$usr_id,$send_date['start_date']);
                                     ?>
                                            <td><?=!empty($exp_tot_arr[0][0]['exp'])?$exp_tot_arr[0][0]['exp']:'0'?></td>   
                                     <?php
                                        }
                                     ?>
                                     
                                </tr>
                            </tfoot>
                            
                            
                            
                        
                        </table>
                         
                       </div>  
                                     
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
                            
                            
                            
                          </div><!-- /.table-responsive -->
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                </div><!-- /.col -->
              </div><!-- /.row -->
              
              
            
            </section><!-- /.content -->