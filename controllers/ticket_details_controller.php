<?php
class TicketDetailsController extends AppController {

	var $name = 'TicketDetails';
	var $helpers = array('Html', 'Form');

	function beforeFilter(){
		parent::beforeFilter();	
	}
	
	function isAuthorized(){
		
		$ret = true;
		
		$actions_adm = array(
			"admin_view"
		);

		$actions_taq = array(
			"admin_view"
		);
				
		if($this->isAdmin() && in_array($this->action, $actions_adm)){
			$ret = true;
		}elseif($this->isTaquilla() && in_array($this->action, $actions_taq)){
			$ret = true;
		}else{
			$ret = false;
		}
		
		return $ret;
	}
	
	function admin_view($ticket_id){
		$details = $this->TicketDetail->find('all',array(
			'conditions' => array('ticket_id' => $ticket_id)
		));
		//pr($details); die();
		$this->set('details',$details);
	}
	
}
?>