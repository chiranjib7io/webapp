<script>
function add_idproof_row(){
    $.post("<?= $this->Html->url('ajax_idproof_row') ?>", function(data, status){
            $('#id_proof').append(data);
    });
}
function delete_idproof_row(did){
    $('#'+did).remove();
}
function getMarketByBranchId(branch_id){
    $.get("<?=$this->Html->url('ajax_market_list/');?>"+branch_id,function(data){
        
        $("#market").html(data);
        
    });
    
    
}
function getGroupOrKendraList(market_id){
  
    $.get("<?=$this->Html->url('ajax_group_list/');?>"+market_id,function(data){
          
        $("#GroupOrKendra").html(data);
        
    });
    
    
}

function get_customer(i){
    market_id = $('#MarketMarketId').val();
    kendra_id = $('#group_id').val();
    console.log(market_id);
    console.log(kendra_id);
    if(kendra_id==undefined || kendra_id=='' || kendra_id==0){
        
        $.get("<?=$this->Html->url('ajax_get_customer_list_by_market/');?>"+i+"/"+market_id,function(data){
              
            $("#ext_cust").html(data);
            
        });
        
    }else{
        $.get("<?=$this->Html->url('ajax_get_customer_list_by_kendra/');?>"+i+"/"+kendra_id,function(data){
              
            $("#ext_cust").html(data);
            
        });
    }
}


$(document).ready(function(){
    
    $("#customer_image").change(function(){
        $('#blah').show();
      readURL(this);
    });
    
});
function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            
            $('#blah').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            New Customer Loan and Saving Entry
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">New Entry</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
            
           
 
              <div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
                    <?php if($this->request->is('post')){ ?>
                   
                <div class="alert alert-warning"><?php echo $this->Session->flash('form1') ?></div> 
                      <?php }?>
                       <div class="alert alert-success">
                <b>    Branch Information</b>
                       </div>
                    
                <?php echo $this->Form->create(); ?>
						
						<span style="color:red"><?php echo $this->Session->flash(); ?></span>
                <div class="box-body col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="Name">Select Branch</label>
                     <?= $this->Form->input('branch_id', array('type' => 'select', 'onChange'=>'getMarketByBranchId(this.value)', 'options' => $bm_list, 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>'', 'empty' => 'Select Branch'));?>
                        
                    </div><!-- /.form group -->
                    
                   <div class="form-group" id="GroupOrKendra">
                                    <label >Select Group/Kendra</label>
                                    <select name="" class="form-control" required="required" id="">
                                    <option id="0">Select Group/Kendra</option>
                                    </select>
                      
                      
                    </div>
                    
                    
                  
                 
                   
                   <!-- /.form group -->
				   

                </div><!-- /.box-body -->
                
                 <div class="box-body col-md-6 col-sm-12">
                
				 <div class="form-group"  id="market">
                     <label>Select Market</label>
                               		<select name="" class="form-control" required="required" id="">
                                    <option id="0">Select Market</option>
                                    </select>
                    </div>
				<!-- /.form group -->
                
                    
                    <div class="form-group" id="credit_officer_list">
                      
                      <label>Select Credit Officer</label>
                                <?= $this->Form->input('credit_officer_id', array('type' => 'select', 'onChange'=>'', 'options' => $creditOfficers, 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>'', 'empty' => 'Select Credit Officer'));?>
                    </div><!-- /.form group -->
                     </div><!-- /.box-body -->
                
                <div class="box-body col-md-12 col-sm-12">
                    
               
                    
                    <div class="alert alert-success">
                     <b>    Customer Information</b>
                       </div>
                       
                      <div class="box-body col-md-3 col-sm-12">
                        <div class="form-group" id="">
                            <label>Customer Type</label>
                            <?= $this->Form->input('', array('type' => 'select','name'=>'cust_type','onChange'=>'get_customer(this.value)', 'options' => array(0=>'New Customer',1=>'Existing Customer'), 'class'=>'form-control', 'label'=>false,));?>
                        </div>
                      </div> 
                      
               <div id="ext_cust"> 
                         <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Enter Customer Name</label>
                                <?= $this->Form->input('cust_fname', array('type' => 'text','class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>''));?>
                    </div>
                      </div> 
                       <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Enter Guardian Name</label>
                                <?= $this->Form->input('guardian_name', array('type' => 'text','class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>''));?>
                    </div>
                      </div> 
                       <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Select Relationship Type</label>
                                <?= $this->Form->input('guardian_reletion_type', array('type' => 'select', 'onChange'=>'', 'options' => $this->Slt->relationship_type(), 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>''));?>
                    </div>
                      </div> 
               </div>
               </div>
                      <div class="box-body col-md-12 col-sm-12">
                      
                       <div class="alert alert-success">
                     <b>    Loan Account Information</b>
                       </div>
                       
                      <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Enter Account Number</label>
                                <?= $this->Form->input('account_no', array('type' => 'text','class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>''));?>
                    </div>
                      </div>
                      
                       <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Enter Loan Amount</label>
                                <?= $this->Form->input('amount', array('type' => 'number','class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>''));?>
                    </div>
                      </div> 
                         <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Enter Loan Term</label>
                                <?= $this->Form->input('term', array('type' => 'number','class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>''));?>
                    </div>
                      </div> 
                      
                      <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Loan Term Unit</label>
                       <select name="repay_interval_days" class="form-control" required="required"><option value="WEEK">Week</option><option value="MONTH">Month</option></select>
                    </div>
                      </div> 
                      
                       <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Select Loan Purpose</label>
                                   <?= $this->Form->input('loan_type', array('type' => 'select', 'onChange'=>'', 'options' => $this->Slt->loan_types(), 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>''));?>
                    </div>
                      </div> 
                       <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Select Loan Risk Type</label>
                                   <?= $this->Form->input('risk_type', array('type' => 'select', 'onChange'=>'', 'options' => $this->Slt->loan_risk_type(), 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>''));?>
                    </div>
                      </div> 
                         <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Enter Interest Type</label>
                                <?= $this->Form->input('inerest_type', array('type' => 'select', 'onChange'=>'', 'options' => $this->Slt->loan_interest_types(), 'class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>''));?>
                    </div>
                      </div>
                      <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Enter Interest Rate</label>
                                <?= $this->Form->input('rateInt', array('type' => 'text','class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>''));?>
                    </div>
                      </div> 
                    
                         
                      
                       <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label> Current Loan Balance</label>
                                <?= $this->Form->input('currentloan_balance', array('type' => 'number','class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>''));?>
                    </div>
                      </div> 
                     
                        <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label> Account opening date</label>
                      <input type="date" name="linstdate" class="form-control" >
                                
                    </div>
                      </div>
                     
                  </div>
                  
                  <div class="box-body col-md-12 col-sm-12">
                    
               
                    
                    <div class="alert alert-success">
                     <b>  Saving  Account Information (If Any)</b>
                       </div>
                      <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Enter Account Number</label>
                                <?= $this->Form->input('Saving.account_no', array('type' => 'text','class'=>'form-control', 'label'=>false, 'default'=>''));?>
                    </div>
                      </div> 
                         
                     
                       <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Enter Current Balance</label>
                                <?= $this->Form->input('Saving.currentBalance', array('type' => 'number','class'=>'form-control', 'label'=>false, 'default'=>''));?>
                    </div>
                      </div> 
                        <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Enter Saving Term(In Weeks)</label>
                                <?= $this->Form->input('Saving.term', array('type' => 'number','class'=>'form-control', 'label'=>false, 'default'=>''));?>
                    </div>
                      </div> 
                       <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Enter Maturity Date</label>
                              <input type="date" name="maturityDate" class="form-control" >
                    </div>
                      </div>
                      
                      
                       <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Select Savings Type</label>
                                   <?= $this->Form->input('Saving.Savings_Type', array('type' => 'select', 'onChange'=>'', 'options' => $this->Slt->saving_types(), 'class'=>'form-control', 'label'=>false, 'default'=>''));?>
                    </div>
                      </div>
                      <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Select Interest Type</label>
                                   <?= $this->Form->input('Saving.interest_type', array('type' => 'select', 'onChange'=>'', 'options' => $this->Slt->saving_interest_types(), 'class'=>'form-control', 'label'=>false, 'default'=>''));?>
                    </div>
                      </div>
                         <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Enter Interest Rate</label>
                                <?= $this->Form->input('Saving.intRate', array('type' => 'number','class'=>'form-control', 'label'=>false, 'default'=>''));?>
                    </div>
                      </div> 
                       
                       <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Minimum Deposit Amount</label>
                                   <?= $this->Form->input('Saving.minDepostitAnmt', array('type' => 'number', 'class'=>'form-control', 'label'=>false, 'default'=>''));?>
                    </div>
                      </div> 
                         <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Enter Last installment date</label>
                              <input type="date" name="saving_lastInstDate" class="form-control" >
                    </div>
                      </div>
                     
                        
                  </div>
            
                
                <div class="box-body col-md-12 col-sm-12">
                    
               
                    
                    <div class="box-footer" align="right">
                        <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                    </div>
                </div>
                
                
                
                
              </div><!-- /.box -->
			  
			  </form>

            </div>
          </div><!-- /.row -->

        </section><!-- /.content -->