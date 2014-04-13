<?php
class Team extends AppModel {

	var $name = 'Team';
	
	var $belongsTo = array('League');	
		
}
?>