<?php
class Pitcher extends AppModel {

	var $name = 'Pitcher';
	
	var $belongsTo = array('Team'); 
	
	var $validate = array(
		'name' => array('notempty')
	);
	
	function teams_by_league($league_id){		
		$pitchers = $this->find('all',array(
			'conditions' => array('league_id'=>$league_id),
			'fields' => array('Pitcher.name','Pitcher.id','Pitcher.team_id'),
			'order' => 'Pitcher.name'
		));
		
		$final = array();
		
		foreach($pitchers as $p){
			$final[$p['Pitcher']['team_id']][$p['Pitcher']['id']] = $p['Pitcher']['name'];
		}
		
		return $final;
	}
}
?>