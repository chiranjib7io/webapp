<?php
if($id!=''){
    if(!empty($kendraList)){
?>
        <option value="">Select Kendra</option>
<?php    
        foreach($kendraList as $k=>$kendra){
        //pr($kendra);
?>
        <option value="<?=$k?>"><?=$kendra?></option>
<?php        
        }
    }else{
?>
<option value="No Kendra">No Kendra</option>
<?php
    }
}else{
?>
<option value="">Select Kendra</option>
<?php
}
?>