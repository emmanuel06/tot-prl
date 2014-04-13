<?php
class TeamsController extends AppController {

	var $name = 'Teams';
	var $helpers = array('Html', 'Form');

	function beforeFilter(){
		parent::beforeFilter();	
	}
	
	function isAuthorized(){
		
		$ret = true;
		
		$actions_adm = array(
			"admin_index","admin_add","admin_edit","admin_set_enable"
		);		
				
		if($this->isAdmin() && in_array($this->action, $actions_adm)){
			$ret = true;
		}else{
			$ret = false;
		}
		
		return $ret;
	}
	
	function admin_index($sport_id = null, $league_id = null) {
		
		$this->paginate['limit'] = 50;
		
		$crit = array();
		$leagues = array();
		
		if($sport_id != null && $sport_id != 0){
			
			
			$leagues = $this->Team->League->find('list',array('conditions'=>array('enable'=>1,'sport_id'=>$sport_id)));			
			$leagues_find = $this->Team->League->find('list',array('conditions'=>array('sport_id'=>$sport_id),'fields'=>'id'));
			$crit['league_id'] = $leagues_find;
		
		}
		
		if($league_id != null && $league_id != 0)
			$crit['league_id'] = $league_id;
			
		$this->paginate['conditions'] = $crit;
		
		$this->paginate['ORDER'] = array('Team.name','ASC');
		
		$this->Team->recursive = 2;
		
		$this->set('teams', $this->paginate());
		
		$sports = $this->Team->League->Sport->find('list',array('conditions'=>array('enable'=>1)));
		$this->set('sports',$sports);
		$this->set('leagues',$leagues);
		$this->set('sport_id',$sport_id);
		$this->set('league_id',$league_id);
	}

	function admin_add($league_id = null) {
		if (!empty($this->data)) {
			$this->Team->create();
			if ($this->Team->save($this->data)) {
				$this->Session->setFlash("Equipo salvado.");
			} else {
				$this->Session->setFlash("Equipo NO salvado. Intente de Nuevo.");
			}
			$this->redirect($this->referer());
		}
		
		$this->set('league_id',$league_id);
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Team', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Team->save($this->data)) {
				$this->Session->setFlash("Equipo salvado.");
			} else {
				$this->Session->setFlash("Equipo NO salvado. Intente de Nuevo.");
			}
			$this->redirect($this->referer());
		}
		if (empty($this->data)) {
			$this->data = $this->Team->read(null, $id);
		}
	}
	
	function admin_set_enable($id, $status) {
		$newstat = 1;
		if($status == 1)
			$newstat = 0;
		
		$this->Team->updateAll(array('enable'=>$newstat),array('Team.id'=>$id));
		
		$this->redirect($this->referer());
		
	}

}
?>