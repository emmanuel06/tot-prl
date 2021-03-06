<?php
class OperationsController extends AppController {

	var $name = 'Operations';
	var $helpers = array('Html', 'Form');

	function admin_index($date = null, $hour = 0, $profile_id = 0, $optype_id = 0, $table = 0) {
		
		$tables = array(1=>'Juego',2=>'Logro',3=>'Resultados',4=>'Ticket');
		$hours = array(1=>'De 12am a 6am',2=>'De 6am a 12pm',3=>'De 12pm a 6pm',4=>'De 6pm a 12am');	
			
		if($date == null)
			$date = date("Y-m-d");
		
		$crit = array('date(created)' => $date);
		
		if($hour != 0){
			switch ($hour) {
				case '1':
					$sin = "00:00:00"; $unt = "05:59:59";
					break;
				case '2':
					$sin = "06:00:00"; $unt = "11:59:59";
					break;
				case '3':
					$sin = "12:00:00"; $unt = "17:59:59";
					break;
				case '4':
					$sin = "18:00:00"; $unt = "23:59:59";
					break;
			}
			$crit['time(created) BETWEEN ? AND ?'] = array($sin,$unt);
		}
			
		
		if($profile_id != 0)
			$crit['profile_id'] = $profile_id;
		
		if($optype_id != 0)
			$crit['operation_type_id'] = $optype_id;
		
		if($table != 0)
			$crit['metainf LIKE'] = "%".$tables[$table]."%";
		
		$this->Operation->recursive = 0;
		$this->paginate['order'] = array('created'=>'DESC');
		$this->paginate['conditions'] = $crit;
		$this->set('operations', $this->paginate());
		$this->set('profiles',$this->Operation->Profile->find('list',array('order'=>array('name'=>'ASC'))));
		$this->set('op_types',$this->Operation->OperationType->find('list'));
		$this->set('hour',$hour);
		$this->set('profile_id',$profile_id);
		$this->set('optype_id',$optype_id);
		$this->set('table',$table);
		$this->set(compact('date','tables','hours'));
	}

	
	function admin_details($table,$model_id){
		
		if($table == 4){
			$mets = array("Ticket");
			$tickIns = ClassRegistry::init("Ticket");
			$tick = $tickIns->find('first',array(
				'conditions'=>'Ticket.id = '.$model_id,'recursive'=>0,
				'fields'=>array('id','created','amount','prize','Profile.name')
			));
		}else{ //juego, logros, resultados
			$mets = array("Juego","Logro","Resultados");
			$tick = array();
		}
		
		$operations = $this->Operation->find('all',array(
			'conditions' => array('model_id'=>$model_id,'metainf'=>$mets),
			'order' => array('created'=>'DESC'), 'recursive' => 0
		));
		//pr($tick);
		$this->set('operations', $operations);
		$this->set('ticket', $tick);
	}
}
?>