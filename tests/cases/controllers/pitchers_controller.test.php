<?php 
/* SVN FILE: $Id$ */
/* PitchersController Test cases generated on: 2010-12-18 17:48:42 : 1292710722*/
App::import('Controller', 'Pitchers');

class TestPitchers extends PitchersController {
	var $autoRender = false;
}

class PitchersControllerTest extends CakeTestCase {
	var $Pitchers = null;

	function startTest() {
		$this->Pitchers = new TestPitchers();
		$this->Pitchers->constructClasses();
	}

	function testPitchersControllerInstance() {
		$this->assertTrue(is_a($this->Pitchers, 'PitchersController'));
	}

	function endTest() {
		unset($this->Pitchers);
	}
}
?>