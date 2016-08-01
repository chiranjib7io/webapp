<?php
if(!empty($market_list)){
    echo '<option value="">Select Market</option>';
    foreach($market_list as $id=>$mrow){
        echo '<option value="'.$id.'">'.$mrow.'</option>';
    }
}else{
    echo '<option value="">No Market Found</option>';
}