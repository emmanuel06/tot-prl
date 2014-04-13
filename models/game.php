<?php
class Game extends AppModel {

	var $name = 'Game';

	var $belongsTo = array('League');
	
	var $hasAndBelongsToMany = array('Team');
	
	function validDate($game_date,$game_time){
		
		$todays_date = date("Y-m-d");
		$today = strtotime($todays_date); 
		$this_date = strtotime($game_date); 
		
		if($this_date < $today){
			return false;
		}else{
			
			if($game_time['meridian'] == 'pm'){
				$game_time['hour'] = $game_time['hour'] + 12;
			}
			$realTime= $game_time['hour'].":".$game_time['min'].":00";
			$now = strtotime(date("H:i:s")); 
			$this_time = strtotime($realTime);
		
			if($this_time < $now && $this_date == $today){
				return false;
			}else{
				return true;
			}
		}
			
	}
	
	function find_exact($cond){
		
		$this->unbindModel(array(
			'belongsTo' => array('League'),
			'hasAndBelongsToMany' => array('Team')
		),false);
		
		$this->bindModel(array(
			'hasAndBelongsToMany' => array('Team' => array(
				'fields'=>array('Team.id','Team.name','GamesTeam.reference'),
				'order' => array('team_type_id' => 'ASC')
			))
		),false);
		
		$games = $this->find('all',array(
			'conditions'=>$cond,'recursive'=>1,
			'fields' => array('id','time','enable')
		));	
		
		return $games;
	}
	
	function to_odds_sheet($date,$leagues,$format){
		$this->unbindModel(array('hasAndBelongsToMany'=>array('Team')),false);
		
		$this->bindModel(array('hasAndBelongsToMany'=>array(
			'Team'=>array(
				'fields' => array('name','win_loses','alt_name')
			)		
		)),false);
		
		$odd_types = array(1,2,3,4);
		
		if($format == "mlb"){
			$odd_types = array_merge($odd_types,array(5,6,7,8));
		}
		
		//LOGROS
		$this->bindModel(array('hasMany'=>array(
			'Odd'=>array(
				'fields' => array('odd','factor','odd_type_id','team_type_id','final'),
				'conditions' => array('actual' => 1, 'odd_type_id' => $odd_types)
			)		
		)),false);
		
		
		$games = $this->find('all',array(
			'conditions' => array('date' => $date, 'league_id' => $leagues),
			'fields' => array('Game.id','time','League.id','League.name','League.sport_id'),
			'order' => array('league_id' => 'ASC','Game.id'=>'ASC')
		));
		
		return $games;
	}	
	
	function to_results($date,$leagues){
		$this->unbindModel(array(
				'hasAndBelongsToMany'=>array('Team'),
				'hasMany'=>array('Odd')
		),false);
		$this->bindModel(array(
			'hasAndBelongsToMany'=>array('Team'=>array('fields' => array('abrev'))),
			'hasMany'=>array('Result')
		),false);	
		
		$games = $this->find('all',array(
			'conditions' => array('date' => $date, 'league_id' => $leagues),
			'fields' => array('Game.id','League.id','League.name'),
			'order' => array('league_id' => 'ASC','Game.id'=>'ASC')
		));
		
		return $games;	
	}
	
	function get_abrevs($id){
		$this->GamesTeam->bindModel(array('belongsTo'=>array('Team')));
		$gteams = $this->GamesTeam->find('all',array(
			'conditions' => array('game_id' => $id),
			'fields' => array('team_id','Team.abrev'),
			'order' => array('team_type_id' => 'ASC')
		));
		
		$abrevs = $gteams[0]['Team']['abrev']." Vs ".$gteams[1]['Team']['abrev'];
		
		return $abrevs;
	}
	
	
		
}
?>