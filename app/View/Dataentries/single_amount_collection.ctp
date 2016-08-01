<script type="text/javascript">
$(function() {
    //hang on event of form with id=myform
    $("#amount_collection").submit(function(e) {

        //prevent Default functionality
        e.preventDefault();

        //get the action-url of the form
        var actionurl = e.currentTarget.action;

        //do your own request an handle the results
        $.ajax({
                url: actionurl,
                type: 'post',
                dataType: 'json',
                data: $("#amount_collection").serialize(),
                beforeSend:function(){
                    $('#loader').html('<?=$this->Html->image('giphy.gif', array('alt' => 'Please wait...','width'=>'75'));?>');
                },
                complete:function(){
                    $('#loader').html('<font color="green">Transaction Complete!</font>');
                },
                success: function(data) {
                    $('#cur_bal').html('Rs. '+data);
                }
        });

    });

});
</script>

<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Amount Collection
          
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Amount Collection</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
               <!-- <div class="box-header">
                  <h3 class="box-title">Create New Customer Form</h3> 
                </div>-->
				
				<?php echo $this->Form->create('Account',array('class'=>'')); ?>
						
						<span style="color:red"><?php echo $this->Session->flash(); ?></span>
				
                <div class="box-body col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="Name">Account Number</label>
                        <?php echo $this->Form->input('Account.account_number', array('type' => 'text','class'=>'form-control','label'=>false,'placeholder'=>'Enter Account Number', 'required'=>'required')); ?>
                        
                    </div><!-- /.form group -->
					
                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
                
                	<div class="form-group">
                        <br />
                        <label for="Name">&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-lg" >Search</button>
                    </div>

                </div><!-- /.box-body -->
                </form>
				
                <?php
                if(!empty($account_data)){
                    $acct_type = ($account_data['Account']['account_type']=='LOAN')?'Loan':'Saving';
                ?>
                
                <div class="box-body col-md-6 col-sm-12">
                
                	<div class="form-group">
                        <label for="Name">Customer Name: </label>
                        <span><?=$account_data['Customer']['fullname']?></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="Name">Region Name: </label>
                        <span><?=$account_data['Region']['region_name']?></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="Name">Branch Name: </label>
                        <span><?=$account_data['Branch']['branch_name']?></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="Name">Market Name: </label>
                        <span><?=$account_data['Market']['market_name']?></span>
                    </div>
            <?php if(!empty($account_data['Kendra']['kendra_name'])){ ?>
                    <div class="form-group">
                        <label for="Name">Kendra Name: </label>
                        <span><?=$account_data['Kendra']['kendra_name']?></span>
                    </div>
            <?php } ?>        
                    <div class="form-group">
                        <label for="Name">Member Since: </label>
                        <span><?=date("d-m-Y",strtotime($account_data['Customer']['created_on']))?></span>
                    </div>
                 

                </div><!-- /.box-body -->

                
                
                <div class="box-body col-md-6 col-sm-12">
                
                	<div class="form-group">
                        <label for="Name">Account Number: </label>
                        <span><?=$account_data['Account']['account_number']?></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="Name">Account Type: </label>
                        <span><?=$account_data['Account']['account_type']?></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="Name">Rate of Interest: </label>
                        <span><?=$account_data['Account']['interest_rate']?> %</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="Name">Interest Type: </label>
                        <span><?=$account_data[$acct_type]['interest_type']?></span>
                    </div>
                    <?php
                    if($acct_type=='Saving'){
                    ?>
                    <div class="form-group">
                        <label for="Name">Current Balance: </label>
                        <span id="cur_bal">Rs. <?=$account_data['Saving']['current_balance']?></span>
                    </div>
                    <?php
                    }
                    if($acct_type=='Loan'){
                    ?>
                    <div class="form-group">
                        <label for="Name">Current Overdraft Balance: </label>
                        <span id="cur_bal">Rs. <?=($account_data['Loan']['loan_repay_total']-$loan_data['amount_paid'])?></span>
                    </div>
                  <!--  
                    <div class="form-group">
                        <label for="Name">Principal Paid: </label>
                        <span>Rs. <?=$loan_data['principal_paid']?></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="Name">Interest Paid: </label>
                        <span>Rs. <?=$loan_data['interest_paid']?></span>
                    </div>
                    -->
                    <?php
                    }
                    ?>
                    
                    
                    
                </div><!-- /.box-body -->
                
                <form id="amount_collection" action="<?=$this->Html->url('ajax_save_collection_amount')?>">
                <div class="box-body col-md-6 col-sm-12">
                    <?php echo $this->Form->input('account_type', array('type' => 'hidden','value'=>$acct_type,'label'=>false)); ?>
                    <?php echo $this->Form->input('account_id', array('type' => 'hidden','value'=>$account_data['Account']['id'],'label'=>false)); ?>
                    <div class="form-group">
                        <label for="Name">Collection Date</label>
                        <input type="date" class="form-control" id="cust_dob" name="date" value="<?=date("Y-m-d")?>" required="required">
                        
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                        <label for="Name">Collection Amount</label>
                        <?php echo $this->Form->input('amount', array('type' => 'number','class'=>'form-control','label'=>false,'placeholder'=>'Enter Collection Amount', 'required'=>'required')); ?>
                        
                    </div><!-- /.form group -->

                </div><!-- /.box-body -->
                
                <div class="box-body col-md-6 col-sm-12">
                
                    <div class="form-group">
                        <label for="Name">Note</label>
                        <input type="text" class="form-control" id="cust_dob" name="note" value="" />
                        
                    </div><!-- /.form group -->
                    
                    <div class="form-group">
                        <label for="Name">Fine Amount</label>
                        <?php echo $this->Form->input('fine', array('type' => 'number','class'=>'form-control','label'=>false,'placeholder'=>'Enter Fine Amount', 'value'=>0)); ?>
                        
                    </div><!-- /.form group -->
                    
                    <div class="form-group" id="loader">
                        
                    </div>
                    
                </div><!-- /.box-body -->
                
                    <div class="form-group">
                        <br />
                        <label for="Name">&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-lg" >Make Payment</button>
                    </div>
                    
                </form>
                
                <?php    
                }
                ?>
                
                
                </div><!-- /.box-body -->
                
              </div><!-- /.box -->

            </div>
          </div><!-- /.row -->

        </section><!-- /.content -->