<script>
function load_customer(kid){
    var url = '<?php echo $this->Html->url('ajax_customer_list/');?>'+kid;
    $.post( url, function( data ) {
      $( "#customer" ).html( data );
      
      $( "#custname" ).html( '<strong>Customer Name:</strong>');
      $( "#loan_number" ).html( '<strong>Loan Number:</strong>');
      $( "#last_paid" ).html( '<strong>Last paid date:</strong>');
      $( "#realize" ).html( '<strong>Total Amount Realize:</strong>');
      $( "#prepay_amt" ).html( '<strong class="text-green">Total Prepayment Amount: </strong>');
      $( "#repay_total" ).html( '<strong>Loan Repay Total:</strong>');
      $( "#loanid" ).val('');
      $( "#prepayamt" ).val('');
      $('#paybtn').hide();
    });
}
function load_amount(cid){
    
    if(cid!=''){
        var url = '<?php echo $this->Html->url('ajax_prepayment_amount/');?>'+cid;
        $.post( url, function( data ) {
            //console.log(data);
            var obj = JSON.parse(data);
          $( "#custname" ).html( '<strong>Customer Name: </strong>'+obj.cust_name );
          $( "#loan_number" ).html( '<strong>Loan Number: </strong>'+obj.loan_number );
          $( "#last_paid" ).html( '<strong>Last paid date: </strong>'+obj.last_paid_date );
          $( "#realize" ).html( '<strong>Total Amount Realize: </strong>'+obj.total_realized );
          $( "#prepay_amt" ).html( '<strong class="text-green">Total Prepayment Amount: </strong>'+obj.prepayment_amount );
          $( "#repay_total" ).html( '<strong>Loan Repay Total: </strong>'+obj.repay_total );
          $( "#loanid" ).val(obj.loan_id );
          $( "#prepayamt" ).val(obj.prepayment_amount);
          if(obj.prepayment_amount==0){
            $('#paybtn').hide();
          }else{
            $('#paybtn').show();
          }
        });
    }else{
        $( "#custname" ).html( '<strong>Customer Name:</strong>');
          $( "#loan_number" ).html( '<strong>Loan Number:</strong>');
          $( "#last_paid" ).html( '<strong>Last paid date:</strong>');
          $( "#realize" ).html( '<strong>Total Amount Realize:</strong>');
          $( "#prepay_amt" ).html( '<strong class="text-green">Total Prepayment Amount: </strong>');
          $( "#repay_total" ).html( '<strong>Loan Repay Total:</strong>');
          $( "#loanid" ).val('');
          $( "#prepayamt" ).val('');
          $('#paybtn').hide();
    }
}
</script>

<!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Loan Prepayment
                <!--<small class="text-green">Success Message</small>
                <small class="text-danger">Waring Message</small>-->
              </h1>
              <ol class="breadcrumb">
               <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Loan Prepayment</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">
                <div class="col-xs-12">
                	<div class="box box-primary" style="float:left">
                        <div class="box-header with-border" style="border-top:none; padding-top:20px;padding-bottom:20px;">
                          <?PHP echo $this->Form->create('Loan', array('method' => 'post')); ?>
                          		                            
                            	<div class="input select form-group col-md-4 col-sm-12">
                                	<label>Select Market</label>
                                    <?php echo $this->Form->input("Loan.kendra_id", array(
                                        'options' => $market_list,
                                        'default' => '',
                                        'empty' => 'Select Market',
                                        'required' => 'required',
                                        'onChange'=>'load_customer(this.value)',
                                        'label'=>false,
                      		            'class'	=> 'form-control'
                                    ));
                                
                                    ?>
                                </div>
                               <div class="input select form-group col-md-4 col-sm-12" id="customer">
                                    <label>Select Customer</label>
                               		<select name="" class="form-control" required="required" id="">
                                    
                                    </select>
                                </div> 
                               <div class="input select form-group col-md-4 col-sm-12">
                                    <label>Payment Date</label>
                               		<input type="date" name="payment_date" required="required" value="<?=date("Y-m-d")?>" class="form-control">
                                </div>                          
                                
                            
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="profile_info" style="width:100%; padding-bottom:20px; padding-top:10px;" id="custdata">
                                <ul>
                                    <li id="custname"><strong>Customer Name:</strong> </li>
                                    <li id="loan_number"><strong>Loan Number:</strong> </li>
                                    <li id="last_paid"><strong>Last paid date:</strong> </li>
                                    <li id="realize"><strong>Total Amount Realize:</strong> </li>
                                    <li id="prepay_amt"><strong class="text-green">Total Prepayment Amount: </strong></li>
                                    <li id="repay_total"><strong>Loan Repay Total:</strong> </li>
                                </ul>
                                <div class="clear"></div>
                                
                                    <div style="text-align:center; margin:15px 0; width:100%; display:block">
                                        <input type="hidden" name="loan_id" value="" id="loanid" >
                                        <input type="hidden" name="prepay_amount" value="" id="prepayamt" >
                                        <input type="submit" class="btn btn-lg bg-navy" id="paybtn" value="Confirm Pre-payment">
                                    </div>
                                <?php echo $this->Form->end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
              </div><!-- /.row -->
            
            </section><!-- /.content -->