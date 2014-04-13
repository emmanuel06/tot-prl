<?php 
/* SVN FILE: $Id$ */
/* League Test cases generated on: 2010-12-18 16:32:42 : 1292706162*/
App::import('Model', 'League');

class LeagueTestCase extends CakeTestCase {
	var $League = null;
	var $fixtures = array('app.league', 'app.sport');

	function startTest() {
		$this->League =& ClassRegistry::init('League');
	}

	function testLeagueInstance() {
		$this->assertTrue(is_a($this->League, 'League'));
	}

	function testLeagueFind() {
		$this->League->recursive = -1;
		$results = $this->League->find('first');
		$this->assertTrue(!empty($results));

		$expected = array('League' => array(
			'id'  => 1,
			'name'  => 'Lorem ipsum dolor sit amet',
			'enable'  => 1,
			'sport_id'  => 1
		));
		$this->assertEqual($results, $expected);
	}
}
?>