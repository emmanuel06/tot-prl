<?php
class UsersController extends AppController {

	var $name = 'Users';
	//var $helpers = array('Html', 'Form','Dtime');
	//var $uses = array('User','Profile');
	

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
	
	function login(){
  	}
	
	function logout(){
		$this->Session->setFlash('Ha finalizado su sesion satisfactoriamente');
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
		
	function unblock(){
		
		if (!empty($this->data)){
			if(!empty($this->data['User']['username'])){
				$chk = 1;
				$user = $this->User->find('first',array(
					'conditions'=>array('username'=>$this->data['User']['username']),
					'fields'=>array('id','secret_question','secret_answer'),'recursive'=>-1
				));
				//pr($user); die();
				if(!empty($user)){
					$secretQuestion = $user['User']['secret_question'];
					$secretAnswer = $user['User']['secret_answer'];
					
					if(!empty($this->data['User']['answer'])){
						if($this->data['User']['answer'] == $secretAnswer){
							$this->User->updateAll(
								array('logged'=>0),
								array('User.id'=>$user['User']['id'])
							);
							$this->Session->setFlash("La RESPUESTA SECRETA es correcta. Ud ha sido desbloqueado");
							$this->redirect(array('action'=>'login'));
						
						}else{
							$this->Session->setFlash("La RESPUESTA SECRETA NO es correcta.");
						}
					}
				}
			}
		}else{
			$secretQuestion = "";
			$chk = 0;
		}
		$this->set(compact('secretQuestion','chk'));
	}	

    function get_hour() {
        $this->layout = 'ajax';
    }
}
?>