<?php
if($branch_id!=''){
    if(!empty($loanOfficerList)){
?>
        <option value="">Select Loan Officer</option>
<?php    
        foreach($loanOfficerList as $k=>$loan_officer){
?>
        <option value="<?=$k?>"><?=$loan_officer?></option>
<?php        
        }
    }else{
?>
<option value="">No Loan Officer</option>
<?php
    }
}else{
?>
<option value="">Select Loan Officer</option>
<?php
}
?>