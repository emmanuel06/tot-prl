<?php
class ProfilesController extends AppController {

	var $name = 'Profiles';
	var $helpers = array('Html', 'Form');
	
	function beforeFilter(){
		parent::beforeFilter();	
	}
	
	function isAuthorized(){
		
		$ret = true;
		
		$actions_adm = array(
			"admin_index","admin_add","admin_edit","admin_limits",
			"admin_change_pass","admin_switch"
		);

		$actions_taq = array(
			"admin_my_view"
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

	function admin_index($role_id = 0, $enable = 2, $group_id = 0, $user_like = "none") {
		
		$conditions = array();
		
		if($role_id != 0)
			$conditions['role_id'] = $role_id;
		
		if($enable != 2)
			$conditions['User.enable'] = $enable;
		
		if($group_id != 0)
			$conditions['Group.id'] = $group_id;	
		
		if($user_like != "none"){
			$conditions['OR'] = array(
				'Profile.name LIKE' => "%$user_like%",
				'User.username LIKE' => "%$user_like%"
			);
		}else
			$user_like = "";
		
		$this->Profile->recursive = 0;
		
		$this->paginate['fields'] = array(
			'Profile.id','Profile.name','User.username','User.created','user_id',
			'User.enable','User.logged','User.last_login','Group.name','Role.name'
		);
		
		$this->paginate['conditions'] = $conditions;
				
		$roles = $this->Profile->Role->find('list');
		$groups = $this->Profile->Group->find('list');
		$enables = array(2 =>"Todos", 0 => "No Disp.", 1 => "Disponibles");
		
		$this->set(compact('roles','enables','groups'));
		$this->set('role_id',$role_id);
		$this->set('enable',$enable);
		$this->set('group_id',$group_id);
		$this->set('user_like',$user_like);
		$this->set('profiles', $this->paginate());
	}

	function admin_add($center_id = null) {				
		if (!empty($this->data)) {
			unset($this->data['User']['repassword']);
			$this->data['User']['enable'] = 1;
			$this->data['User']['logged'] = 0;
			//pr($this->data); die();
			$this->Profile->User->create();
			if($this->Profile->User->save($this->data['User'])){
				$this->data['Profile']['user_id'] = $this->Profile->User->id;
				$this->Profile->create();
				if ($this->Profile->save($this->data['Profile'])) {
					$this->Session->setFlash("Usuario Salvado");
				} else {
					$this->Session->setFlash("ERROR: Usuario NO salvado");
				}	
			}else{
				$this->Session->setFlash("ERROR: Usuario NO salvado");
			}
			
			$this->redirect($this->referer());
		}
		
		$roles = $this->Profile->Role->find('list');
		$groups = $this->Profile->Group->find('list');
	
		$this->set(compact('roles','groups'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Profile', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			//pr($this->data);die();
			if ($this->Profile->saveAll($this->data)) {
				$this->Session->setFlash("Usuario Salvado.");
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash("Usuario NO Salvado. Intente de nuevo.");
				$this->redirect(array('action'=>'index'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Profile->read(null, $id);
		}
		$roles = $this->Profile->Role->find('list');
		$groups = $this->Profile->Group->find('list');
		$this->set(compact('users','roles','groups'));
	}

	function admin_limits($id = null){
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Profile', true));
			die();
		}
		
		if (!empty($this->data)) {			
			if ($this->Profile->save($this->data)) {
				$this->Session->setFlash("Limites Salvados.");
			} else {
				$this->Session->setFlash("Limites NO Salvados. Intente de nuevo.");
			}
			$this->redirect($this->referer());
		}
		
		if (empty($this->data)) {
			//$this->data = $this->Profile->read(null, $id);
			$this->data = $this->Profile->find('first',array(
				'conditions' => array('Profile.id' => $id),
				'fields' => array(
					'id','name','max_parlays','max_amount_straight','max_amount_parlay',
					'max_prize','pct_sales','pct_won'					
				)
			));
		}
		
	}
	
	function admin_change_pass($id = null) {

		if (!$id && empty($this->data)) {
			$this->Session->setFlash("Usuario Invalido");
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			//pr($this->data);die();
			if ($this->Profile->User->updateAll(
				array('User.password' => "'".$this->Authed->password($this->data['Profile']['new_pass'])."'"),
				array('User.id' => $this->data['Profile']['user_id'])
			)) {
				
				$this->Session->setFlash("Password Cambiado");
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash("ERROR: Password NO Cambiado");
			}
		}
		if (empty($this->data)) {
			$user_id = $this->Profile->find('first',array(
				'conditions' => array('Profile.id'=>$id),
				'fields' => 'user_id', 'recursive' => -1
			));
			$this->set('user_id',$user_id['Profile']['user_id']);
		}
		
	}
	
	function admin_my_view(){
		$profile = $this->Profile->find('first',array(
			'conditions' => array('Profile.id' => $this->authUser['profile_id'])
		));
		//pr($profile);die();
		$this->set('profile',$profile);
	}
	
	function admin_switch(){
		$users = $this->Profile->find('list',array(
			'conditions'=>array('role_id'=>3),
			'fields' => array('user_id')
		));
		
		$enab = $this->Profile->User->find('count',array(
			'conditions' => array('User.id'=>$users,'enable'=>1)
		));
		
		$disa = $this->Profile->User->find('count',array(
			'conditions' => array('User.id'=>$users,'enable'=>0)
		));
		
		if($disa < $enab){
			$this->Profile->User->updateAll(
				array('enable' => 0),
				array('User.id' => $users)
			);
			$msg = "TAQUILLAS BLOQUEADAS";
		}else{
			$this->Profile->User->updateAll(
				array('enable' => 1),
				array('User.id' => $users)
			);
			$msg = "TAQUILLAS DESBLOQUEADAS";
		}
		$this->Session->setFlash($msg);
		$this->redirect($this->referer());
	}
	
}
?>