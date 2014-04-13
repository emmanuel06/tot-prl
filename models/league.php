<?php
class League extends AppModel {

	var $name = 'League';
	
	var $validate = array(
		'name' => array('notempty')
	);

	var $belongsTo = array('Sport');

	function find_leagues_by_group($leagues){
		$detail = $this->find('all',array(
			'conditions' => array('League.id' => $leagues),
			'fields' => array('League.id','League.name','Sport.id','Sport.name')
		));
		
		$ordered = array();
		
		foreach($detail as $d){
			$ordered[$d['Sport']['id']]['name'] = $d['Sport']['name'];
			$ordered[$d['Sport']['id']]['Leagues'][$d['League']['id']] = $d['League']['name'];
		}
		
		return $ordered;	
	}
	
	function find_listed($league_ids){
		
		$leaguesfind = $this->find('all',array(
			'conditions' => array('League.id'=>$league_ids),
			'fields' => array('League.name','League.id','Sport.name')
		));
		
		$leagues = array();
		
		foreach($leaguesfind as $lf){
			$leagues[$lf['League']['id']] = $lf['League']['name']." (".$lf['Sport']['name'].")";
		}
		
		return $leagues;
	}
	
}
?>