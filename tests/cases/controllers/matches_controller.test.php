<?php 
/* SVN FILE: $Id$ */
/* MatchesController Test cases generated on: 2010-12-18 17:47:37 : 1292710657*/
App::import('Controller', 'Matches');

class TestMatches extends MatchesController {
	var $autoRender = false;
}

class MatchesControllerTest extends CakeTestCase {
	var $Matches = null;

	function startTest() {
		$this->Matches = new TestMatches();
		$this->Matches->constructClasses();
	}

	function testMatchesControllerInstance() {
		$this->assertTrue(is_a($this->Matches, 'MatchesController'));
	}

	function endTest() {
		unset($this->Matches);
	}
}
?>