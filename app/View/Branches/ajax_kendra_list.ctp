<?php
if($id!=''){
    if(!empty($kendraList)){
?>
        <option value="">All Kendra</option>
<?php    
        foreach($kendraList as $k=>$kendra){
        //pr($kendra);
?>
        <option value="<?=$kendra?>"><?=$kendra?></option>
<?php        
        }
    }else{
?>
<option value="No Kendra">No Kendra</option>
<?php
    }
}else{
?>
<option value="">All Kendra</option>
<?php
}
?>