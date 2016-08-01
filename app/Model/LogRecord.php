<?php
App::uses('AuthComponent', 'Controller/Component');

class LogRecord extends AppModel {
	
    
    
	var $belongsTo = array(
        
        'User' => array(
			'className'    	=> 'User',
			'foriegnKey'	=> 'user_id'
		)
			
	);
    
    

}

?>