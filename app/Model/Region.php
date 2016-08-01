<?php
App::uses('AuthComponent', 'Controller/Component');

class Region extends AppModel {
	
	var $belongsTo = array(
		'Organization' => array(
			'className'    	=> 'Organization',
			'foriegnKey'	=> 'organization_id'
		),
        'User' => array(
			'className'    	=> 'User',
			'foriegnKey'	=> 'user_id'
		)
			
	);
    
    public $hasMany = array(
        'Branch' => array(
			'className'    	=> 'Branch',
			'foriegnKey'	=> 'market_id',
            'conditions' => array('Branch.status !=' => '2')
		)
    );

}

?>