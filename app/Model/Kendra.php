<?php
App::uses('AuthComponent', 'Controller/Component');

class Kendra extends AppModel {
	
	var $belongsTo = array(
		'Organization' => array(
			'className'    	=> 'Organization',
			'foriegnKey'	=> 'organization_id'
		),
        'Region' => array(
			'className'    	=> 'Region',
			'foriegnKey'	=> 'region_id'
		),
        'Branch' => array(
			'className'    	=> 'Branch',
			'foriegnKey'	=> 'branch_id'
		),
        'Market' => array(
			'className'    	=> 'Market',
			'foriegnKey'	=> 'market_id'
		),
        'User' => array(
			'className'    	=> 'User',
			'foriegnKey'	=> 'user_id'
		)
			
	);
    
    /*
  public $hasAndBelongsToMany = array(
          'Member' => array(
              'className' => 'Customer',
              'conditions' => array('Customer.status' => '1')
          )
      );
 */

}

?>