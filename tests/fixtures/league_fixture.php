<?php 
/* SVN FILE: $Id$ */
/* League Fixture generated on: 2010-12-18 16:32:42 : 1292706162*/

class LeagueFixture extends CakeTestFixture {
	var $name = 'League';
	var $table = 'leagues';
	var $fields = array(
		'id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type'=>'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'enable' => array('type'=>'boolean', 'null' => false, 'default' => '1'),
		'sport_id' => array('type'=>'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'fk_league_sport' => array('column' => 'sport_id', 'unique' => 0))
	);
	var $records = array(array(
		'id'  => 1,
		'name'  => 'Lorem ipsum dolor sit amet',
		'enable'  => 1,
		'sport_id'  => 1
	));
}
?>