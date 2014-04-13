<?php 
/* SVN FILE: $Id$ */
/* TicketsController Test cases generated on: 2010-12-18 18:03:47 : 1292711627*/
App::import('Controller', 'Tickets');

class TestTickets extends TicketsController {
	var $autoRender = false;
}

class TicketsControllerTest extends CakeTestCase {
	var $Tickets = null;

	function startTest() {
		$this->Tickets = new TestTickets();
		$this->Tickets->constructClasses();
	}

	function testTicketsControllerInstance() {
		$this->assertTrue(is_a($this->Tickets, 'TicketsController'));
	}

	function endTest() {
		unset($this->Tickets);
	}
}
?>