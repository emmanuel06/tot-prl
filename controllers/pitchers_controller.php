<?php
class PitchersController extends AppController {

	var $name = 'Pitchers';
	var $helpers = array('Html', 'Form');

	function beforeFilter(){
		parent::beforeFilter();	
	}
	
	function isAuthorized(){
		
		$ret = true;
		
		$actions_adm = array(
			"admin_index","admin_add","admin_edit","admin_delete","admin_get_listed"
		);		
				
		if($this->isAdmin() && in_array($this->action, $actions_adm)){
			$ret = true;
		}else{
			$ret = false;
		}
		
		return $ret;
	}
	
	function admin_index($league_id = null, $team_id = null) {
				
		$teams = array();
		$crit = array();
			
		if($league_id != null){
			$teams = $this->Pitcher->Team->find('list',array(
				'conditions'=>array('enable'=>1,'league_id'=>$league_id),
				'order' => array('Team.name' => 'ASC')
			));
			
			$team_find = $this->Pitcher->Team->find('list',array('conditions'=>array('enable'=>1,'league_id'=>$league_id),'fields'=>'id'));
			
			$crit['team_id'] = $team_find;
		}
		
		if($team_id != null){
			$crit['team_id'] = $team_id;
		}
		
		$this->paginate['conditions'] = $crit;
		$this->Pitcher->recursive = 2;
		$this->set('pitchers', $this->paginate());
		
		$leagues = $this->Pitcher->Team->League->find('list',array('conditions'=>array('enable'=>1,'sport_id'=>1)));
		$this->set('leagues',$leagues);
		$this->set('league_id',$league_id);
		$this->set('teams',$teams);
		$this->set('team_id',$team_id);
	}

	function admin_add($team_id = null) {
		if (!empty($this->data)) {
			$this->Pitcher->create();
			if ($this->Pitcher->save($this->data)) {
				$this->Session->setFlash("Pitcher Salvado");
			} else {
				$this->Session->setFlash("Pitcher NO Salvado. Intente de nuevo.");
			}
			$this->redirect($this->referer());
		}
		
		$this->set('team_id',$team_id);
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Pitcher', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Pitcher->save($this->data)) {
				$this->Session->setFlash("Pitcher Salvado");
			} else {
				$this->Session->setFlash("Pitcher NO Salvado. Intente de nuevo.");
			}
			$this->redirect($this->referer());
		}
		if (empty($this->data)) {
			$this->data = $this->Pitcher->read(null, $id);
		}
	}
	
	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Pitch', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Pitcher->del($id)) {
			$this->Session->setFlash("Pitcher Borrado");
			$this->redirect($this->referer());
		}
	}
	
	
	function admin_get_listed($team_id,$team_type){
		$this->set('pitchers',$this->Pitcher->find('list',array(
			'conditions'=>array('team_id' => $team_id),'order'=>'name'
		)));
		$this->set('team_type',$team_type);
	}
	
}
?>