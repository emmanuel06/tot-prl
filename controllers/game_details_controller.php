<?php
class GameDetailsController extends AppController {

	var $name = 'GameDetails';
	var $helpers = array('Html', 'Form');

	function beforeFilter(){
		parent::beforeFilter();	
	}
	
	function isAuthorized(){
		
		$ret = true;
		
		$actions_adm = array(
		);

		$actions_taq = array(
			"admin_search"
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
	
	function admin_search($date,$ref){
		$ref = strtoupper($ref);
		$odd = $this->GameDetail->get_game_detail($date,$ref);
		//pr($odd); die();
		Configure::write('debug',0); //When output Json
		$this->set('odd',$odd);
	}
	
}
?>