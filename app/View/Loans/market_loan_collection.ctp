<script>
function change_divclass(id){
    var NAME = document.getElementById("custname"+id);
    var amt = $("#amt_"+id).val();
    var tot_amt = $("#tot_amt").val();
    var new_tot_amt = 0;
    var realise_amt = parseInt($("#realise_amt").html());
    //alert(amt);
    //alert(tot_amt);
    var currentClass = NAME.className;
    if (currentClass == "client-box-success") { // Check the current class name
        NAME.className = "client-box-due";   // Set other class name
        new_tot_amt = parseInt(tot_amt) - parseInt(amt);
        $("#tot_amt").val(new_tot_amt);
        $("#boxval"+id).val('0');
        $("#tot_collection").html('INR '+new_tot_amt);
    } else {
        NAME.className = "client-box-success";  // Otherwise, use `second_name`
        new_tot_amt = parseInt(tot_amt) + parseInt(amt);
        $("#tot_amt").val(new_tot_amt);
        $("#boxval"+id).val(amt);
        $("#tot_collection").html('INR '+new_tot_amt);
    }
    if(new_tot_amt>realise_amt){
        $("#exces_amt").html(new_tot_amt-realise_amt);
    }else{
        $("#exces_amt").html(0);
    }
    
}

function update_amt(id){
    
    var NAME = document.getElementById("custname"+id);
    var amt = $("#amt_"+id).val();
    var amt2 = $("#boxval"+id).val();
    var tot_amt = $("#tot_amt").val();
    var new_tot_amt = 0;
    var realise_amt = parseInt($("#realise_amt").html());
    //alert(amt);
    //alert(tot_amt);
    
    if (amt2 == '0') { // Check the current class name
        NAME.className = "client-box-due";   // Set other class name
        new_tot_amt = parseInt(tot_amt) - parseInt(amt);
        $("#amt_"+id).val('0');
        $("#tot_amt").val(new_tot_amt);
        $("#tot_collection").html('INR '+new_tot_amt);
    }else{
        NAME.className = "client-box-success"; 
        new_tot_amt = parseInt(tot_amt) - parseInt(amt) + parseInt(amt2);
        $("#amt_"+id).val(amt2);
        $("#tot_amt").val(new_tot_amt);
        $("#tot_collection").html('INR '+new_tot_amt);
    } 
    if(new_tot_amt>realise_amt){
        $("#exces_amt").html(new_tot_amt-realise_amt);
    }else{
        $("#exces_amt").html(0);
    }
}
</script>

<div class="container">
            <!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Market Loan Collection Sheet on <?=date("d-M-Y",strtotime($due_date))?>
              </h1>
            </section>

        	<!-- Main content -->
            <section class="content">
            <?PHP echo $this->Form->create('LoanTransaction', array('method' => 'post')); ?>
              <!-- Small boxes (Stat box) -->
              <div class="row">
              	<div class="col-md-12">
                	
                    <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                        <div class="box-header with-border">
                          <h3 class="box-title"><?php echo $market_data['Market']['market_name']; ?> || <?php echo $market_data['Organization']['organization_name']; ?></h3>
                        </div><!-- /.box-header -->
                        
                     <?php
                     $total_amount=0;
                 if(!empty($market_data['Transaction'])){
                     
                     foreach($market_data['Transaction'] as $k=>$data_row)
                     {
                        //pr($data_row);die;
                        $total_amount += $data_row['LoanTransaction']['total_installment'];
                     ?>   
                        <div class="col-lg-2 col-md-4 sm-6" style="padding-top:20px;">
                          <!-- small box client-box-due-->
                          <div id="custname<?=$data_row['Customer']['id']?>" class="client-box-success">
                          <div class="inner" style="width: 100%;">
                            <?=$data_row['Customer']['cust_fname']?> <?=$data_row['Customer']['cust_lname']?>
                          </div>
                            <div class="inner">
                              
                              <!--<h3><?=$this->Number->currency($data_row['LoanTransaction']['total_installment'],'',array('places'=>0))?></h3>-->
                              <input type="text" class="form-control" onblur="update_amt('<?=$data_row['Customer']['id']?>')" id="boxval<?=$data_row['Customer']['id']?>" name="cust_val[<?=$data_row['Customer']['id']?>]" value="<?=$data_row['LoanTransaction']['total_installment']?>" required="required">
                              <input type="hidden" id="amt_<?=$data_row['Customer']['id']?>" value="<?=$data_row['LoanTransaction']['total_installment']?>" />
                              <input type="hidden" id="acct_<?=$data_row['Customer']['id']?>" name="cust_acct[<?=$data_row['Customer']['id']?>]" value="<?=$data_row['LoanTransaction']['account_id']?>" />
                            </div>
                            <div class="icon">
                              
                              <input id="box<?=$data_row['Customer']['id']?>" type="checkbox" name="cust_arr[<?=$data_row['Customer']['id']?>]" value="<?=$data_row['LoanTransaction']['total_installment']?>" checked="checked" />
                              <label for="box<?=$data_row['Customer']['id']?>" onclick="change_divclass('<?=$data_row['Customer']['id']?>')" ></label>
                            </div>
                          </div>
                        </div><!-- ./col -->
                     <?php
                     }
                 }else{
                    echo '<h3>Sorry! There is no collection on this date.</h3>';
                 }
                     ?>   
                        
                        
                    </div>
				</div>                
              </div><!-- /.row -->
              
              <div class="row">
                  <div class="col-md-5 col-sm-12">
                      <!-- TABLE: LATEST ORDERS -->
                      <div class="box box-success">
                        <div class="box-header with-border">
                          <h2 class="box-title" style="text-align:center;display:block;line-height:22px;">SUMMARY</h2>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                          <div class="table-responsive">
                            <table class="table no-margin">
                              <thead>
                                <tr>
                                  <td>Excess Amount</td>
                                  <td>INR <span id="exces_amt">0</span></td>
                                </tr>
                                <tr>
                                  <td>Total Overdue Amount</td>
                                  <td><?=$this->Number->currency($market_data['Market']['total_overdue'],'',array('places'=>0))?></td>
                                </tr>
                                <tr>
                                  <td>Percent Paid</td>
                                  <td><?=$market_data['Market']['percent_paid']?>%</td>
                                </tr>
                              </thead>
                            </table>
                          </div><!-- /.table-responsive -->
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                  </div><!-- /.col -->
                  <div class="col-md-7 col-sm-12">
                  	  <div class="box box-warning">
                        <div class="box-header with-border">
                          <h3 class="box-title" style="text-align:center;display:block;line-height:22px;">TOTAL COLLECTION AMOUNT</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                          	<h1 class="heading" align="center" id="tot_collection"><?=$this->Number->currency($total_amount,'INR ',array('places'=>0))?></h1>
                            <input type="hidden" id="tot_amt" value="<?=$total_amount?>" />
                            <span id="realise_amt" style="display: none;"><?=$total_amount?></span>
                        </div><!-- /.box-body -->
                      </div><!-- /.box -->
                  </div><!-- /.col -->              
              </div><!-- /.row -->
              <div class="row">
              	<div class="col-xs-12"> 
                    <div class="box no-border" style="padding-top:20px; padding-bottom:20px; float:left">   
                      <div class="col-sm-6">
                            <a href="javascript: window.history.back()"><button type="button" class="btn btn-danger btn-lg">Cancel</button></a>
                        </div>
                      <div class="col-sm-6">
                      
                            
                            <?php echo $this->Form->input('LoanTransaction.market_id', array('type' => 'hidden','value'=>$market_data['Market']['id'],'label'=>false)); ?>
                            <?php echo $this->Form->input('LoanTransaction.insta_due_on', array('type' => 'hidden','value'=>$due_date,'label'=>false)); ?>
                            <?php echo $this->Form->input('LoanTransaction.insta_no', array('type' => 'hidden','value'=>!empty($market_data['Transaction'][0]['LoanTransaction']['insta_no'])?$market_data['Transaction'][0]['LoanTransaction']['insta_no']:0,'label'=>false)); ?>
                            <button type="submit" class="btn btn-success btn-lg  pull-right">Pay now</button>
                      
                        </div>
                    </div>
                </div>
    		  </div>
              <?php echo $this->Form->end(); ?> 
            </section><!-- /.content -->
            
        </div> 