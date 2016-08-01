<script>
function load_customer(kid){
    var url = '<?php echo $this->Html->url('ajax_security_fee_customer_list/');?>'+kid;
    $.post( url, function( data ) {
      $( "#customer" ).html( data );
      $( "#sec_fee" ).val(0);
      $('#refund_btn').hide();
    });
}
function get_sec_fee(cid){
    var url = '<?php echo $this->Html->url('ajax_security_fee_amount/');?>'+cid;
    $.post( url, function( data ) {
        var obj = JSON.parse(data);
      $( "#sec_fee" ).val(obj.fees);
      $( "#LoanLoanId" ).val(obj.loan_id);
      
      if(obj.fees==0){
        $('#refund_btn').hide();
      }else{
        $('#refund_btn').show();
      }
    });
}
</script>
<!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Security Deposit Return
                <small class="text-green"><?=$this->Session->Flash()?></small>
                <!--<small class="text-danger">Waring Message</small>-->
              </h1>
              <ol class="breadcrumb">
               <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Security Deposit Return</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">
             <!---customer info starts----->
                <div class="col-xs-12 col-sm-10 col-md-8 col-lg-8 col-xs-push-0 col-sm-push-1 col-md-push-2 col-lg-push-2">
                	<div class="box box-primary" style="float:left">
                    	<!--<div class="box-header with-border">
                          <h3 class="box-title">Mathurapur</h3>
                        </div>--><!-- /.box-header -->
                        <div class="box-body" style="padding-bottom:20px; padding-top:20px;">
                            <?PHP echo $this->Form->create('Loan', array('method' => 'post')); ?>
                          		  <?php echo $this->Form->input('Loan.loan_id', array('type' => 'hidden','value'=>'','label'=>false)); ?>                          
                            	<div class="input select form-group col-xs-12">
                                    <label for="CollectionDate">Select Market</label>
                                	<?php echo $this->Form->input("Loan.market_id", array(
                                        'options' => $market_list,
                                        'default' => $market_id,
                                        'empty' => 'Select Market',
                                        'required' => 'required',
                                        'onChange'=>'load_customer(this.value)',
                                        'label'=>false,
                      		            'class'	=> 'form-control'
                                    ));
                                
                                    ?>
                                </div> 
                               <div class="input select form-group col-xs-12" id="customer">
                               <label for="CollectionDate">Select Customer</label>
                               		<?php echo $this->Form->input("Loan.customer_id", array(
                                        'options' => $cust_list,
                                        'default' => $customer_id,
                                        'empty' => 'Select Customer',
                                        'onChange'=>'get_sec_fee(this.value)',
                                        'required' => 'required',
                                        'label'=>false,
                      		            'class'	=> 'form-control'
                                    ));
                                
                                    ?>
                                </div>  
                               <div class="input select form-group col-xs-12">
                               <?php
                               if(!empty($customer_id) && ($customer_id!='')){
                                $sec_amt = $fees_arr[$customer_id];
                                $btn_disp = '';
                               }else{
                                $sec_amt =0;
                                $btn_disp = 'display:none;';
                               }
                               
                               ?>
                               <label for="CollectionDate">Security Amount</label>
                               		<input type="text" readonly="readonly" class="form-control" id="sec_fee" name="data[Loan][sec_fee]" value="<?=$this->Number->currency($sec_amt,'',array('places'=>0));?>">
                                </div>                         
                                <div class="input-group col-xs-12" style="text-align:center">
                                
                                    <input type="submit" style="<?=$btn_disp?>" id="refund_btn" class="btn btn-success" value="Refund Amount" onclick="return confirm('Are you sure you would like to refund the security amount?');" >
                                </div>
                            <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
              </div><!-- /.row -->
            </section><!-- /.content -->