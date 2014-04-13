<?php
class Result extends AppModel {

	var $name = 'Result';
	
	var $belongsTo = array('Game','OddType','TeamType');	
		
	function get_exotics($sport_id){
		
		$exotics = array();
		
		if($sport_id == 1){ //BASEBALL
			
			$prim_inn = array(
				'Name' => 'Hay Carrera 1er Inn',
				'Id' => 5, 'Factor' => false,
				'Team' => array(6 => 'SI', 7 => 'NO')
			);
			
			$quien = array(
				'Name' => 'Quien Anota Primero',
				'Id' => 6,'Factor' => false,
				'Team' => array(1 => 'Visitante', 2 => 'HomeClub')
			);
			
			array_push($exotics, $prim_inn);
			array_push($exotics, $quien);
		}
		/*
		if($sport_id == 3){ //BASKETBALL
			
			$quien = array(
				'Name' => 'Quien Anota Primero',
				'Id' => 6,'Factor' => false,
				'Team' => array(1 => 'Visitante', 2 => 'HomeClub')
			);
			
			array_push($exotics, $quien);
		}
		
		if($sport_id == ID_HOCKEY || $sport_id == ID_FOOTBALL){ //HOCKEY o FOOTBALL
			$quien = array(
				'Name' => 'Quien Anota Primero',
				'Id' => 6,'Factor' => false,
				'Team' => array(1 => 'Visitante', 2 => 'HomeClub')
			);
			array_push($exotics, $quien);
		}
		*/
		return $exotics;
	}
	
	function get_odd_type_ids($sport_id){
		
		$available_exotics = array();
		
		if($sport_id == 1){
			$available_exotics = array(5,6,7);
		}
		/*
		if($sport_id == 3){
			$available_exotics = array(6);
		}
		
		if($sport_id == ID_HOCKEY || $sport_id == ID_FOOTBALL){ //HOCKEY o FOOTBALL
			$available_exotics = array(6);
		}
		*/
		return $available_exotics;
	}
	
	function set_results_type_one($game_id,$results){
		$odd = ClassRegistry::init("Odd");
		
		$league = $this->Game->find('first',array(
			'conditions' => array('Game.id' => $game_id),
			'fields' => 'League.sport_id'
		));
		
		$draw = $this->Game->League->Sport->find('first',array(
			'conditions' => array('Sport.id' => $league['League']['sport_id']),
			'fields' => 'get_draw','recursive'=> -1
		));
		
		//pr($results); die();
		foreach($results as $mode => $teams){
			
			$empate = false;
			$winner = 0;
			$loser = 0;
			$total = $teams[1] + $teams[2];
			$runline = 0;
			
			if($teams[1] > $teams[2]){
				$winner = 1;
				$loser = 2;
				$runline = $teams[1] - $teams[2];
			}elseif($teams[2] > $teams[1]){
				$winner = 2;
				$loser = 1;
				$runline = $teams[2] - $teams[1];
			}else{
				$empate = true;
			}
			
			if($draw['Sport']['get_draw'] == 1){
				$odd->set_moneylines_draw($game_id,$mode,$winner,$empate);
			}else{
				$odd->set_moneylines($game_id,$mode,$winner,$empate);
			}
			
			//juego empatado
			if($winner == 0){
				$odd->set_runlines_draw($game_id,$mode);
			}else{
				$odd->set_runlines($game_id,$mode,$winner,$loser,$runline);
			}
			
			$odd->set_alta_baja($game_id,$mode,$total);
		
			if($mode == 1){
				$odd->set_ab_indiv($game_id,$teams[1],$teams[2]);
			}
		}
	}
	
	function set_exotic_results($game_id,$opts){
		$odd = ClassRegistry::init("Odd");
		foreach($opts as $odd_type => $winner){
			$odd->set_exotics($game_id,$odd_type,$winner['value']);
		}
	}
	
	function set_total_che($game_id,$total){
		$odd = ClassRegistry::init("Odd");
		$odd->set_che($game_id,$total);
	}
	
	function set_suspended($game_id){
		$odd = ClassRegistry::init("Odd");
		
		$odd->updateAll(
			array('odd_status_id' => 4),
			array('game_id' => $game_id)
		);
	}
	
	function set_specials($game_id,$types){
		
		//pr($types);die();
		$odd = ClassRegistry::init("Odd");
		
		foreach($types as $t){
			$odd->updateAll(
				array('odd_status_id' => 4),
				array('game_id' => $game_id,'odd_type_id'=>$t['type'],'final'=>$t['final'])
			);	
		}
		
	}
	
}
?>