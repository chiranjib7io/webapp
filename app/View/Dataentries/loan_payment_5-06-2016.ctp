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
            New Loan Entry
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">New Customer Entry</li>
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
                     <b>    Account Information</b>
                       </div>
                      <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label>Enter Account Number</label>
                                <?= $this->Form->input('account_no', array('type' => 'text','class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>''));?>
                    </div>
                      </div> 
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
                      <div class="box-body col-md-12 col-sm-12">
                      
                       <div class="alert alert-success">
                     <b>    Loan Information</b>
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
                      
                      <label>Select Loan Type</label>
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
                     <!--   <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label> Principal Paid</label>
                                <?= $this->Form->input('pPaid', array('type' => 'text','class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>''));?>
                    </div>
                      </div> 
                      <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label> Interest Paid</label>
                                <?= $this->Form->input('iPaid', array('type' => 'text','class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>''));?>
                    </div>
                      </div> 
                        <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label> Installments Made</label>
                                <?= $this->Form->input('instMade', array('type' => 'text','class'=>'form-control', 'label'=>false, 'required'=>'required', 'default'=>''));?>
                    </div>
                      </div>-->
                        <div class="box-body col-md-3 col-sm-12">
                      <div class="form-group" id="">
                      
                      <label> Last Installments date</label>
                      <input type="date" name="linstdate" class="form-control" >
                                
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