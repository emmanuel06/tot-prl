<?php
class SportsController extends AppController {

	var $name = 'Sports';
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
	
	function admin_index() {
		$this->Sport->recursive = 0;
		$this->set('sports', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Sport->create();
			if ($this->Sport->save($this->data)) {
				$this->Session->setFlash("Deporte Guardado");
			} else {
				$this->Session->setFlash("Deporte NO Guardado. Intente de nuevo.");
			}
			$this->redirect($this->referer());
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Sport', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Sport->save($this->data)) {
				$this->Session->setFlash("Deporte Guardado");
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash("Deporte NO Guardado. Intente de nuevo.");
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Sport->read(null, $id);
		}
	}

	function admin_set_enable($id, $status) {
		$newstat = 1;
		if($status == 1){
			$newstat = 0;
		}
		
		$this->Sport->updateAll(array('enable'=>$newstat),array('Sport.id'=>$id));
		
		$this->redirect($this->referer());
		
	}

}
?>