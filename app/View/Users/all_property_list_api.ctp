<?php
//pr($propertyList);
$properties = array();
foreach($propertyList as $k=>$property){
    $properties['venues'][] = $property['Property'];
}
//pr($properties);
echo json_encode($properties);


?>