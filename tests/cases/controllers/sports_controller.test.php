<?php 
/* SVN FILE: $Id$ */
/* SportsController Test cases generated on: 2010-12-18 17:33:35 : 1292709815*/
App::import('Controller', 'Sports');

class TestSports extends SportsController {
	var $autoRender = false;
}

class SportsControllerTest extends CakeTestCase {
	var $Sports = null;

	function startTest() {
		$this->Sports = new TestSports();
		$this->Sports->constructClasses();
	}

	function testSportsControllerInstance() {
		$this->assertTrue(is_a($this->Sports, 'SportsController'));
	}

	function endTest() {
		unset($this->Sports);
	}
}
?>