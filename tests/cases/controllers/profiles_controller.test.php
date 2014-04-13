<?php 
/* SVN FILE: $Id$ */
/* ProfilesController Test cases generated on: 2010-12-18 18:06:17 : 1292711777*/
App::import('Controller', 'Profiles');

class TestProfiles extends ProfilesController {
	var $autoRender = false;
}

class ProfilesControllerTest extends CakeTestCase {
	var $Profiles = null;

	function startTest() {
		$this->Profiles = new TestProfiles();
		$this->Profiles->constructClasses();
	}

	function testProfilesControllerInstance() {
		$this->assertTrue(is_a($this->Profiles, 'ProfilesController'));
	}

	function endTest() {
		unset($this->Profiles);
	}
}
?>