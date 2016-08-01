<script>
function change_divclass(id){
    var NAME = document.getElementById("custname"+id);
    var amt = $("#amt_"+id).val();
    var tot_amt = $("#tot_amt").val();
    var new_tot_amt = 0;
    //alert(amt);
    //alert(tot_amt);
    var currentClass = NAME.className;
    if (currentClass == "client-box-success") { // Check the current class name
        NAME.className = "client-box-due";   // Set other class name
        new_tot_amt = parseInt(tot_amt) - parseInt(amt);
        $("#tot_amt").val(new_tot_amt);
        $("#tot_collection").html(new_tot_amt+' INR');
    } else {
        NAME.className = "client-box-success";  // Otherwise, use `second_name`
        new_tot_amt = parseInt(tot_amt) + parseInt(amt);
        $("#tot_amt").val(new_tot_amt);
        $("#tot_collection").html(new_tot_amt+' INR');
    }
}
</script>

<div class="container">
            <!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Kendra Loan Collection Sheet on <?=date("d-M-Y",strtotime($due_date))?>
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
                          <h3 class="box-title"><?php echo $kendra_data['Kendra']['kendra_name']; ?> || <?php echo $kendra_data['Organization']['organization_name']; ?></h3>
                        </div><!-- /.box-header -->
                        
                     <?php
                     $total_amount=0;
                     foreach($kendra_data['Transaction'] as $k=>$data_row)
                     {
                        $total_amount += $data_row['LoanTransaction']['total_installment'];
                     ?>   
                        <div class="col-lg-2 col-md-4 sm-6" style="padding-top:20px;">
                          <!-- small box client-box-due-->
                          <div id="custname<?=$data_row['Customer']['id']?>" class="client-box-success">
                          <div class="inner" style="width: 100%;">
                            <?=$data_row['Customer']['cust_fname']?> <?=$data_row['Customer']['cust_lname']?>
                          </div>
                            <div class="inner">
                              <? /*?><p style="width: 130px;"><?=$data_row['Customer']['cust_fname']?> <?=$data_row['Customer']['cust_lname']?></p><? */?>
                              <h3><?=$this->Number->currency($data_row['LoanTransaction']['total_installment'],'',array('places'=>0))?></h3>
                              <input type="hidden" id="amt_<?=$data_row['Customer']['id']?>" value="<?=$data_row['LoanTransaction']['total_installment']?>" />
                            </div>
                            <div class="icon">
                              <input id="box<?=$data_row['Customer']['id']?>" type="checkbox" name="cust_arr[<?=$data_row['Customer']['id']?>]" value="<?=$data_row['LoanTransaction']['total_installment']?>" checked="checked" />
                              <label for="box<?=$data_row['Customer']['id']?>" onclick="change_divclass('<?=$data_row['Customer']['id']?>')" ></label>
                            </div>
                          </div>
                        </div><!-- ./col -->
                     <?php
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
                                  <td>Total Loan Amount</td>
                                  <td><?php echo $this->Number->currency($kendra_data['Kendra']['total_loan'],'',array('places'=>0)); ?></td>
                                </tr>
                                <tr>
                                  <td>Total Overdue Amount</td>
                                  <td><?=$this->Number->currency($kendra_data['Kendra']['total_overdue'],'',array('places'=>0))?></td>
                                </tr>
                                <tr>
                                  <td>Percent Paid</td>
                                  <td><?=$kendra_data['Kendra']['percent_paid']?>%</td>
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
                          	<h1 class="heading" align="center" id="tot_collection"><?=$this->Number->currency($total_amount,'Rs.',array('places'=>0))?></h1>
                            <input type="hidden" id="tot_amt" value="<?=$total_amount?>" />
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
                      
                            
                            <?php echo $this->Form->input('LoanTransaction.kendra_id', array('type' => 'hidden','value'=>$kendra_data['Kendra']['id'],'label'=>false)); ?>
                            <?php echo $this->Form->input('LoanTransaction.insta_due_on', array('type' => 'hidden','value'=>$due_date,'label'=>false)); ?>
                            <?php echo $this->Form->input('LoanTransaction.insta_no', array('type' => 'hidden','value'=>$kendra_data['Transaction'][0]['LoanTransaction']['insta_no'],'label'=>false)); ?>
                            <button type="submit" class="btn btn-success btn-lg  pull-right">Pay now</button>
                      
                        </div>
                    </div>
                </div>
    		  </div>
              <?php echo $this->Form->end(); ?> 
            </section><!-- /.content -->
            
        </div> 