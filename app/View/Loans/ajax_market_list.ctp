<?php
if($branch_id!=''){
    if(!empty($marketList)){
?>
        <option value="">Select Market</option>
<?php    
        foreach($marketList as $k=>$market){
?>
        <option value="<?=$k?>"><?=$market?></option>
<?php        
        }
    }else{
?>
<option value="">No Market Found</option>
<?php
    }
}else{
?>
<option value="">Select Market</option>
<?php
}
?>