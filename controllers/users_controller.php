<?php
class UsersController extends AppController {

	var $name = 'Users';
	
    /*
	function beforeFilter(){
		parent::beforeFilter();	
		
		$this->Authed->allow(array("login","logout","unblock","get_hour"));
	}
	
	function isAuthorized(){
		
		$ret = true;
		
		$actions_adm = array(
			"admin_set_enable","admin_set_logged",
		);		
				
		if($this->isAdmin() && in_array($this->action, $actions_adm)){
			$ret = true;
		}else{
			$ret = false;
		}
				
		if($ret == false)
			$this->Session->setFlash("NO permitida");
		
		return $ret;
	}

    */

	function login(){

  	}
	
	function logout(){
		$this->redirect($this->Authed->logout());
	}

	function admin_set_enable($id, $status) {
		$newstat = 1;
		if($status == 1){
			$newstat = 0;
		}
		
		$this->User->updateAll(array('enable'=>$newstat),array('User.id'=>$id));
		
		$this->redirect($this->referer());
		
	}
	
	function admin_set_logged($id,$status) {
		$this->User->updateAll(array('logged'=>$status),array('User.id'=>$id));
		$this->redirect($this->referer());
	}
		
	function get_hour() {
        $this->layout = 'ajax';
    }
}
?>