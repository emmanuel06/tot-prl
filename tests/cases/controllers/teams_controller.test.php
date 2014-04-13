<?php 
/* SVN FILE: $Id$ */
/* TeamsController Test cases generated on: 2010-12-18 17:37:13 : 1292710033*/
App::import('Controller', 'Teams');

class TestTeams extends TeamsController {
	var $autoRender = false;
}

class TeamsControllerTest extends CakeTestCase {
	var $Teams = null;

	function startTest() {
		$this->Teams = new TestTeams();
		$this->Teams->constructClasses();
	}

	function testTeamsControllerInstance() {
		$this->assertTrue(is_a($this->Teams, 'TeamsController'));
	}

	function endTest() {
		unset($this->Teams);
	}
}
?>