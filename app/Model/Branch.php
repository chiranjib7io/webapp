<?php
App::uses('AuthComponent', 'Controller/Component');

class Branch extends AppModel {
	
	var $belongsTo = array(
		'Organization' => array(
			'className'    	=> 'Organization',
			'foriegnKey'	=> 'organization_id'
		),
        'Region' => array(
			'className'    	=> 'Region',
			'foriegnKey'	=> 'region_id'
		),
		 'User' => array(
			'className'    	=> 'User',
			'foriegnKey'	=> 'user_id'
		)
       
			
	);
    
    

}

?>