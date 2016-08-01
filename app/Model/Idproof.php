<?php
App::uses('AuthComponent', 'Controller/Component');

class Idproof extends AppModel {
	/**
 * Upload Directory relative to WWW_ROOT
 * @param string
 */
public $uploadDir = 'upload/id_proof';

public $validate = array(
        'id_proof_pic' => array(
			// http://book.cakephp.org/2.0/en/models/data-validation.html#Validation::uploadError
			'uploadError' => array(
				'rule' => 'uploadError',
				'message' => 'Something went wrong with the file upload',
				'required' => FALSE,
				'allowEmpty' => TRUE,
			),
			// http://book.cakephp.org/2.0/en/models/data-validation.html#Validation::mimeType
			'mimeType' => array(
				'rule' => array('mimeType', array('image/gif','image/png','image/jpg','image/jpeg')),
				'message' => 'Invalid file, only images allowed',
				'required' => FALSE,
				'allowEmpty' => TRUE,
			),
			// custom callback to deal with the file upload
			'processUpload' => array(
				'rule' => 'processUpload',
				'message' => 'Something went wrong processing your file',
				'required' => FALSE,
				'allowEmpty' => TRUE,
				'last' => TRUE,
			)
		)
	
    );
  
  
/**
 * Process the Upload
 * @param array $check
 * @return boolean
 */
public function processUpload($check=array()) {
	// deal with uploaded file
	if (!empty($check['id_proof_pic']['tmp_name'])) {

		// check file is uploaded
		if (!is_uploaded_file($check['id_proof_pic']['tmp_name'])) {
			return FALSE;
		}

		// build full filename
		$filename = WWW_ROOT . $this->uploadDir . DS .time().'_'. Inflector::slug(pathinfo($check['id_proof_pic']['name'], PATHINFO_FILENAME)).'.'.pathinfo($check['id_proof_pic']['name'], PATHINFO_EXTENSION);
        
		// @todo check for duplicate filename

		// try moving file
		if (!move_uploaded_file($check['id_proof_pic']['tmp_name'], $filename)) {
			return FALSE;

		// file successfully uploaded
		} else {
			// save the file path relative from WWW_ROOT e.g. uploads/example_filename.jpg
			$this->data[$this->alias]['filepath'] = str_replace(DS, "/", str_replace(WWW_ROOT, "", $filename) );
            
		}
	}
    
	return TRUE;
}

/**
 * Before Save Callback
 * @param array $options
 * @return boolean
 */

public function beforeSave($options = array()) {
	// a file has been uploaded so grab the filepath
	if (!empty($this->data[$this->alias]['filepath'])) {
		$this->data[$this->alias]['id_proof_pic'] = $this->data[$this->alias]['filepath'];
	}
	return parent::beforeSave($options);
}


/**
 * Before Validation
 * @param array $options
 * @return boolean
 */
public function beforeValidate($options = array()) {
	// ignore empty file - causes issues with form validation when file is empty and optional
	if (!empty($this->data[$this->alias]['id_proof_pic']['error']) && $this->data[$this->alias]['id_proof_pic']['error']==4 && $this->data[$this->alias]['id_proof_pic']['size']==0) {
		unset($this->data[$this->alias]['id_proof_pic']);
	}

	parent::beforeValidate($options);
}
  
    
	var $belongsTo = array(
		
        'Customer' => array(
			'className'    	=> 'Customer',
			'foriegnKey'	=> 'customer_id'
		)
			
	);
    


}

?>