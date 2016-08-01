<script>
 $(document).ready(function(){
     var cnt = 2;
     $("#anc_add").click(function(){
        $('#tbl1 tr').last().after('<tr><td><input type="text" class="form-control" name="first_name[]" required="required" /></td><td><input type="text" class="form-control" name="last_name[]" required="required" /></td><td><select name="sex[]" class="form-control" required="required"><option value="Female">Female</option><option value="Male">Male</option></select></td><td><input type="text" class="form-control" name="account_no[]" required="required" /></td><td><input type="number" class="form-control" name="principal[]" id="prnc'+cnt+'" onblur="calc_rate(\''+cnt+'\')" required="required" /></td><td><input type="text" class="form-control" name="interest_rate[]" id="rate'+cnt+'" onblur="calc_rate(\''+cnt+'\')" required="required" /></td><td><select name="interest_type[]" class="form-control" id="typ'+cnt+'" onchange="calc_rate(\''+cnt+'\')" required="required"><option value="Fixed">Fixed Interest</option><option value="Reducing">Reducing Interest</option></select></td><td><input type="text" class="form-control" name="term[]" id="trm'+cnt+'" onblur="calc_rate(\''+cnt+'\')" required="required" /></td><td><select name="repay_interval_days[]" id="intrval'+cnt+'" onblur="calc_rate(\''+cnt+'\')" required="required" class="form-control" required="required"><option value="WEEK">Week</option><option value="MONTH">Month</option></select></td><td id="int'+cnt+'">&nbsp;</td><td><input type="text" class="form-control" name="currentloan_balance[]" required="required" /></td><td><input type="date" class="form-control" name="last_installment_date[]" /></td></tr>');
        cnt++;
        //alert(cnt);
     });
     
    $("#anc_rem").click(function(){
        if($('#tbl1 tr').size()>2){
            $('#tbl1 tbody tr:last-child').remove();
        }else{
            alert('One row should be present in table');
        }
     });
 
});

function market_list_of_branch(bid){
        $.post("<?= $this->Html->url('ajax_market_list_of_branch') ?>/"+bid, function(data, status){
                $('#market_id').html(data);
        });
    
}
function group_list_of_market(mid){
        $.post("<?= $this->Html->url('ajax_group_list_of_market') ?>/"+mid, function(data, status){
                $('#kendra_id').html(data);
        });
    
}

function calc_rate(id){
	     //alert(id);
		var amount, interest, instal, rate, loantype, interval, intervaltype, interest_amt;
		
		amount = (document.getElementById("prnc"+id).value * 1);
		interest = (document.getElementById("rate"+id).value * 1);
		instal = (document.getElementById("trm"+id).value * 1);
        loantype = document.getElementById("typ"+id).value;
        intervaltype = document.getElementById("intrval"+id).value;
		
        
        if(loantype=='Fixed'){
            repaytotal = Math.round((amount+(amount*interest/100)));
			rate = repaytotal / instal;
        }else{
            //intervaltype = 'WEEK';
            if(intervaltype=='WEEK'){
                interval = 52;
            }else{
                interval = 12;
            }
            r = interest/interval/100;
            rate = amount*r*(Math.pow((1+r),instal))/(Math.pow((1+r),instal)-1);
            repaytotal = Math.round(rate*instal);
			
        }
        interest_amt =repaytotal-amount;
		$("#int"+id).html(interest_amt);
        //alert(interest_amt);
		//document.getElementById("LoanLoanRate").value = Math.round(rate);
        
	}
 </script>
   <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Bulk Loan Entry
          </h1>
          <ol class="breadcrumb">
           <li><a href="<?= $this->Html->url('/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Bulk Loan Entry</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-md-12">
			
			<?php echo $this->Form->create('',array('class'=>'')); ?>
						
	           <span style="color:red"><?php echo $this->Session->flash(); ?></span>
 
              <div class="box box-danger col-xs-12 table-responsive" style="padding-top:20px; padding-bottom:20px;">
                
                <div class="box-body col-sm-12">
                    <div class="form-group col-md-3 ">
                        <label for="Name">Branch Name</label>
                        <?php
							echo $this->Form->input('branch_id', array('type' => 'select','name'=>'branch_id', 'options' => $branch_list, 'class'=>'form-control','onchange'=>'market_list_of_branch(this.value)', 'label'=>false, 'required'=>'required', 'empty' => 'Select Branch'));
						?>
                    </div><!-- /.form group -->
                    
                   <div class="form-group col-md-3">
                        <label for="Name">Market Name</label>
                        <?php
							echo $this->Form->input('market_id', array('type' => 'select','name'=>'market_id','id'=>'market_id', 'options' => array(), 'class'=>'form-control', 'onchange'=>'group_list_of_market(this.value)', 'label'=>false, 'required'=>'required', 'empty' => 'Select Market'));
						?>
                    </div>
                    
                    <div class="form-group col-md-3">
                        <label for="Name">Group Name (optional)</label>
                        <?php
							echo $this->Form->input('kendra_id', array('type' => 'select','name'=>'kendra_id','id'=>'kendra_id', 'options' => array(), 'class'=>'form-control', 'label'=>false));
						?>
                    </div>
                    
                    <div class="form-group col-md-3">
                        <label for="Name">Credit Officer</label>
                        <?php
							echo $this->Form->input('user_id', array('type' => 'select','name'=>'user_id','id'=>'user_id', 'options' => $user_list, 'empty' => 'Select Credit Officer', 'class'=>'form-control', 'required'=>'required', 'label'=>false));
						?>
                    </div>
                </div>
                
                
                
                <table  id="tbl1" class="table table-striped table-bordered table-sm" style="width: 100%;">
                    <thead class="thead-inverse">
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Sex</th>
                        
                        <th>Account no</th>
                        <th>Loan Principal</th>
                        <th>Interest Rate</th>
                        <th>Interest Type</th>
                        <th>Loan Term</th>
                        <th>Term Unit</th>
                        <th>Interest Amount</th>
                        
                        <th>Current Loan Balance</th>
                        <th>Account opening date</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><input type="text" class="form-control" name="first_name[]" required="required" /></td>
                        <td><input type="text" class="form-control" name="last_name[]" required="required" /></td>
                        <td><select name="sex[]" class="form-control" required="required"><option value="Female">Female</option><option value="Male">Male</option></select></td>
                        
                        <td><input type="text" class="form-control" name="account_no[]" required="required" /></td>
                        <td><input type="number" class="form-control" name="principal[]" id="prnc1" onblur="calc_rate('1')" required="required" /></td>
                        <td><input type="text" class="form-control" name="interest_rate[]" id="rate1" onblur="calc_rate('1')" required="required" /></td>
                        <td><select name="interest_type[]" class="form-control" id="typ1" onchange="calc_rate('1')" required="required"><option value="Fixed">Fixed Interest</option><option value="Reducing">Reducing Interest</option></select></td>
                        <td><input type="text" class="form-control" name="term[]" id="trm1" onblur="calc_rate('1')" required="required" /></td>
                        <td><select name="repay_interval_days[]" id="intrval1" onchange="calc_rate('1')" class="form-control" required="required"><option value="WEEK">Week</option><option value="MONTH">Month</option></select></td>
                        <td id="int1">&nbsp;</td>
                        
                        <td><input type="text" class="form-control" name="currentloan_balance[]" required="required" /></td>
                        <td><input type="date" class="form-control" name="last_installment_date[]" /></td>
                    </tr>
                    </tbody>
                </table>
                
                
               <div class="box-body col-md-12 col-sm-12"> 
                    <div class="box-footer col-xs-12" style="text-align:left;">
                        
                        
                        <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                        <a href="javascript:void(0);" id='anc_add' style="color: blue;">[+]Add another row</a>&nbsp;
                        <a href="javascript:void(0);" id='anc_rem' style="color: red;">[-]Remove Row</a>
                    </div>
               </div>
              </div><!-- /.box -->
              
			  
			  <?php echo $this->Form->end(); ?>

            </div>
          </div><!-- /.row -->

        </section><!-- /.content -->