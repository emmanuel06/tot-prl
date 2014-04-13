<?php
class Sport extends AppModel {

	var $name = 'Sport';
	
	var $validate = array(
		'name' => array('notempty')
	);
	
}
?>