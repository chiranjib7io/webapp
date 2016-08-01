<?php
App::uses('AuthComponent', 'Controller/Component');

class Organization extends AppModel {
	
		/**
 * Upload Directory relative to WWW_ROOT
 * @param string
 */
  
    
    var $hasMany = array(
        
        'Region' => array(
			'className'    	=> 'Region',
			'foriegnKey'	=> 'organization_id',
            'conditions' => array('Region.status' => '1')
		),
		'Branch' => array(
			'className'    	=> 'Branch',
			'foriegnKey'	=> 'organization_id',
            'conditions' => array('Branch.status' => '1')
		),
        
        'Fee' => array(
			'className'    	=> 'Fee',
			'foriegnKey'	=> 'organization_id'
            
		),
        'Setting' => array(
			'className'    	=> 'Setting',
			'foriegnKey'	=> 'organization_id'
		),
        'Plan' => array(
			'className'    	=> 'Plan',
			'foriegnKey'	=> 'organization_id',
            'conditions' => array('Plan.status' => '1')
		)
        
        
			
	);
    
    var $belongsTo = array(
        
        'Country' => array(
			'className'    	=> 'Country',
			'foriegnKey'	=> 'country_id'
		),
        'User' => array(
			'className'    	=> 'User',
			'foriegnKey'	=> 'user_id',
            'conditions' => array('User.status' => '1')
		)
        
			
	);

}

?>