<?php
class Profile extends AppModel {

	var $name = 'Profile';
	
	var $belongsTo = array(
		'User'=>array('fields'=>array('id','username','secret_question','secret_answer','email')),
		'Role'=>array('fields'=>'name'),
		'Group'=>array('fields'=>'name'));	
		
}
?>