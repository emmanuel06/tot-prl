<?php
class LeaguesController extends AppController {

	var $name = 'Leagues';
	var $helpers = array('Html', 'Form');

	function beforeFilter(){
		parent::beforeFilter();	
	}
	
	function isAuthorized(){
		
		$ret = true;
		
		$actions_adm = array(
			"admin_index","admin_add","admin_edit","admin_set_enable","admin_show_by_sport_id"
		);		
				
		if($this->isAdmin() && in_array($this->action, $actions_adm)){
			$ret = true;
		}else{
			$ret = false;
		}
		
		return $ret;
	}
	
	function admin_index($sport_id = null) {
		
		$crit = array();
		
		if($sport_id != null && $sport_id != 0)
			$crit['sport_id'] = $sport_id;
		
		$this->paginate['conditions'] = $crit;
		
		$this->League->recursive = 0;
		$this->set('leagues', $this->paginate());
		$sports = $this->League->Sport->find('list',array('conditions'=>array('enable'=>1)));
		$this->set('sports',$sports);
		$this->set('sport_id',$sport_id);
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->League->create();
			if ($this->League->save($this->data)) {
				$this->Session->setFlash("Liga salvada");
			} else {
				$this->Session->setFlash("Liga NO salvada. Intente de nuevo.");
			}
			$this->redirect($this->referer());
		}
		$sports = $this->League->Sport->find('list');
		$this->set(compact('sports'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid League', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->League->save($this->data)) {
				$this->Session->setFlash("Liga salvada");
			} else {
				$this->Session->setFlash("Liga NO salvada. Intente de nuevo.");
			}
			$this->redirect($this->referer());
		}
		if (empty($this->data)) {
			$this->data = $this->League->read(null, $id);
		}
		$sports = $this->League->Sport->find('list');
		$this->set(compact('sports'));
	}

	function admin_set_enable($id, $status) {
		$newstat = 1;
		if($status == 1)
			$newstat = 0;
		
		$this->League->updateAll(array('enable'=>$newstat),array('League.id'=>$id));
		
		$this->redirect($this->referer());
		
	}
	
	function admin_show_by_sport_id($date, $sid){
			
		$horse = false;
		if($sid == ID_HIPISMO)
			$horse = true;
		
		$leagues = $this->League->find('list',array('conditions'=>"sport_id = $sid",'order'=>'name ASC'));
		$this->set('leagues',$leagues);
		$this->set('date',$date);
		$this->set('horse',$horse);
		$this->layout = "ajax";
	}
	
}
?>