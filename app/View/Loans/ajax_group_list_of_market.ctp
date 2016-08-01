<?php
if(!empty($kendra_list)){
    echo '<option value="">Not Applicable</option>';
    foreach($kendra_list as $id=>$mrow){
        echo '<option value="'.$id.'">'.$mrow.'</option>';
    }
}else{
    echo '<option value="">No Group Found</option>';
}