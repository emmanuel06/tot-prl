<?php
class Odd extends AppModel {

	var $name = 'Odd';

	var $belongsTo = array(
		'Game' => array('fields' => 'id'),
		'OddType' => array('fields' => 'name'),
		'TeamType' => array('fields' => 'name')
	);
	

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
			
			$che = array(
				'Name' => 'Total Che',
				'Id' => 7, 'Factor' => true,
				'Team' => array(8 => 'Over', 9 => 'Under')
			);			
			/*
			$super = array(
				'Name' => 'Super Runline',
				'Id' => 8,'Factor' => true,
				'Team' => array(1 => 'Visitante', 2 => 'HomeClub')
			);
			*/		
			array_push($exotics, $prim_inn);
			array_push($exotics, $quien);
			array_push($exotics, $che);
			//array_push($exotics, $super);
		}
		
		/* si el quiere esto se le pone
		if($sport_id == 3){ //BASKET
			
			$ab_ind = array(
				'Name' => 'Alta Baja Individual',
				'Id' => 9, 'Factor' => true,
				'Team' => array(
					10 => 'Alta Visitante', 
					11 => 'Baja Visitante', 
					12 => 'Alta HomeClub',
					13 => 'Baja HomeClub'
					)
			);					
			array_push($exotics, $ab_ind);
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
			$available_exotics = array(5,6,7,8);
		}
		
		if($sport_id == 3){
			$available_exotics = array(6,9);
		}
		
		if($sport_id == ID_HOCKEY || $sport_id == ID_FOOTBALL){ //HOCKEY o FOOTBALL
			$available_exotics = array(6);
		}
		
		return $available_exotics;
	}
	
	//obtiene el logro segun el juego y las opciones
	function get_odd($game_id,$conds){
		
		$fields =  array('Odd.id','Odd.odd','Odd.odd_type_id','Odd.team_type_id','Odd.final','TeamType.name','OddType.name');
		
		if($conds['factor'] == 1)
			array_push($fields,'factor');
			
		$prefix = $conds['prefix'];
		
		unset($conds['prefix']);
		unset($conds['factor']);
		
		$conds['actual'] = 1;		
		$conds['game_id'] = $game_id;
		
			
		$odd = $this->find('first',array(
			'conditions' => $conds,
			'fields' => $fields
		));
		
		$odd_data = array();
		
		if(!empty($odd)){
			$odd_data['id'] = $odd['Odd']['id'];
			$odd_data['odd'] = $odd['Odd']['odd'];
			$odd_data['abrevs'] = $this->Game->get_abrevs($conds['game_id']);
			
			if(!empty($odd['Odd']['factor']))
				$odd_data['factor'] = $odd['Odd']['factor'];
			
			if($odd['Odd']['final'] == 1)
				$odd_data['mode'] = "FINAL";
			elseif($odd['Odd']['final'] == 2)
				$odd_data['mode'] = "1rCUARTO";
			else
				$odd_data['mode'] = "MITAD";
			
			$odd_data['type'] = $odd['OddType']['name'];
			
			$odd_data['odd_type_id'] = $odd['Odd']['odd_type_id'];
			
			if(in_array($conds['odd_type_id'],array(1,2,6,8))) //solo las q no son visit/hclub
				$odd_data['team'] = "";
			else
				$odd_data['team'] = $odd['TeamType']['name'];
			
			$odd_data['prefix'] = "";
			if($prefix != '0')
				$odd_data['prefix'] = $prefix;
				
		}
		return $odd_data;
	}
	
	function get_by_ticket($ticket_id){
		$odds = $this->find('list',array('conditions'=>array('ticket_id')));
		
	}
	
	//SETEADOR ESTATUS POR OPCIONES
	function setter_status($odd_status,$where){
		$this->updateAll(array('odd_status_id' => $odd_status),$where);	
		/*
		$comp = ClassRegistry::init("Comp");
		$val = "Valor: $odd_status en --";
		foreach($where as $field => $value){
			$val .= "$field: $value, ";
		}
		$comp->save(array('wrote'=>$val));
		unset($comp->id);
		*/
	}
	
	//M  O  N  E  Y  L  I  N  E  S
	function set_moneylines($game_id,$mode,$winner,$empate){
		if($empate == true){
			$this->setter_status(5,array('game_id'=>$game_id,'odd_type_id'=>1,'final'=>$mode,'team_type_id'=>array(1,2)));
		}else{
			$this->setter_status(2,array('game_id'=>$game_id,'odd_type_id'=>1,'final'=>$mode,'team_type_id'=>array(1,2),'team_type_id'=>$winner));
			$this->setter_status(3,array('game_id'=>$game_id,'odd_type_id'=>1,'final'=>$mode,'team_type_id'=>array(1,2),'team_type_id <>'=>$winner));
		}
	}
	
	//M  O  N  E  Y  L  I  N  E  S    D  R  A  W
	function set_moneylines_draw($game_id,$mode,$winner,$empate){
		if($empate == true){
			$this->setter_status(3,array('game_id'=>$game_id,'odd_type_id'=>1,'final'=>$mode));
			$this->setter_status(2,array('game_id'=>$game_id,'odd_type_id'=>4,'final'=>$mode));
		}else{
			$this->setter_status(2,array('game_id'=>$game_id,'odd_type_id'=>1,'final'=>$mode,'team_type_id'=>array(1,2),'team_type_id'=>$winner));
			$this->setter_status(3,array('game_id'=>$game_id,'odd_type_id'=>1,'final'=>$mode,'team_type_id'=>array(1,2),'team_type_id <>'=>$winner));
			$this->setter_status(3,array('game_id'=>$game_id,'odd_type_id'=>4,'final'=>$mode));
		}
	}
	
	//R  U  N  L  I  N  E  S    E  M  P  A  T  A  D  O
	function set_runlines_draw($game_id,$mode){
		//En caso de q hay ganador nulo (Juego Empatado) 
		//SIEMPRE GANA LA HEMBRA por NO tener puntos en contra
		//Siendo hembra GANA
		$this->setter_status(2,array('game_id'=>$game_id,'odd_type_id'=>array(2,8),'final'=>$mode,'factor >'=>0));
	
		//Siendo macho PIERDE
		$this->setter_status(3,array('game_id'=>$game_id,'odd_type_id'=>array(2,8),'final'=>$mode,'factor <'=>0));
		
	}
	
	//aqui va super runline tambien
	//R  U  N  L  I  N  E  S
	function set_runlines($game_id,$mode,$winner,$loser,$runline){
		
		$options = array('game_id'=>$game_id,'odd_type_id'=>array(2,8),'final'=>$mode);
		$runline_neg = $runline * -1;
		
		//          ----------------    GANADORES:         ----------------
		
		//Siendo hembra y ganando
		$options['team_type_id'] = $winner;
		$options['factor >'] = 0;
		$this->setter_status(2,$options);
		
		//Siendo hembra y perdiendo pero no por lo q deberia
		$options['team_type_id'] = $loser;
		$options['factor >'] = 0;
		$this->setter_status(2,$options);
		
		//Siendo macho y gano por mas de lo q deberia
		$options['team_type_id'] = $winner;
		$options['factor >'] = $runline_neg;
		$this->setter_status(2,$options);
		
		//        -----------------    PERDEDORES:         ----------------
		
		//Siendo macho gano pero no por el runline minimo
		unset($options['factor >']);
		$options['team_type_id'] = $winner;
		$options['factor <'] = $runline_neg;
		$this->setter_status(3,$options);
		
		//Siendo macho y perdiendo
		$options['team_type_id'] = $loser;
		$options['factor <'] = 0;
		$this->setter_status(3,$options);
		
		//Siendo hembra y perdiendo por lo q deberia
		$options['team_type_id'] = $loser;
		$options['factor <'] = $runline;
		$this->setter_status(3,$options);
				
		//        -----------------    EMPATADOS:          ----------------
		//Siendo el runline igual a la diferencia en contra del MACHO ganando
		unset($options['factor <']);
		$options['team_type_id'] = $winner;
		$options['factor'] = $runline_neg;
		$this->setter_status(5,$options);
		
		//Siendo el runline igual a la diferencia a favor de la HEMBRA perdiendo
		$options['team_type_id'] = $loser;
		$options['factor'] = $runline;	
		$this->setter_status(5,$options);
		
	}		
	
	// A  L  T  A  B  A  J  A  S
	function set_alta_baja($game_id,$mode,$total){			
		//ALTA GANADORA
		$this->setter_status(2,array('game_id'=>$game_id,'odd_type_id'=>3,'final'=>$mode,'team_type_id'=>4,'factor <'=>$total));
		//BAJA GANADORA
		$this->setter_status(2,array('game_id'=>$game_id,'odd_type_id'=>3,'final'=>$mode,'team_type_id'=>5,'factor >'=>$total));
		//ALTA PERDEDORA
		$this->setter_status(3,array('game_id'=>$game_id,'odd_type_id'=>3,'final'=>$mode,'team_type_id'=>4,'factor >'=>$total));
		//BAJA PERDEDORA
		$this->setter_status(3,array('game_id'=>$game_id,'odd_type_id'=>3,'final'=>$mode,'team_type_id'=>5,'factor <'=>$total));
		//EMPATADOS
		$this->setter_status(5,array('game_id'=>$game_id,'odd_type_id'=>3,'final'=>$mode,'team_type_id'=>array(4,5),'factor'=>$total));		
	}
	
	// E  X  O  T  I  C  A  S
	function set_exotics($game_id,$odd_type,$team_winner){
		//GANADORES
		$this->setter_status(2,array('game_id'=>$game_id,'odd_type_id'=>$odd_type,'team_type_id'=>$team_winner));
		//PERDEDORES
		$this->setter_status(3,array('game_id'=>$game_id,'odd_type_id'=>$odd_type,'team_type_id <>'=>$team_winner));
	}
	
	// T  O  T  A  L    C  H  E
	function set_che($game_id,$score){
		//GANADORES
		$this->setter_status(2,array('game_id'=>$game_id,'odd_type_id'=>7,'team_type_id'=>8,'factor <'=>$score));
		$this->setter_status(2,array('game_id'=>$game_id,'odd_type_id'=>7,'team_type_id'=>9,'factor >'=>$score));
		//PERDEDORES
		$this->setter_status(3,array('game_id'=>$game_id,'odd_type_id'=>7,'team_type_id'=>8,'factor >'=>$score));
		$this->setter_status(3,array('game_id'=>$game_id,'odd_type_id'=>7,'team_type_id'=>9,'factor <'=>$score));
		//EMPATADOS
		$this->setter_status(5,array('game_id'=>$game_id,'odd_type_id'=>7,'factor'=>$score));
	}
	
	// A  L  T  A  B  A  J  A    I  N  D  I  V  .
	function set_ab_indiv($game_id,$away,$home){
		//EMPATADOS
		//alta visit
		$this->setter_status(5,array('game_id'=>$game_id,'odd_type_id'=>9,'team_type_id'=>10,'factor'=>$away));
		//baja visit
		$this->setter_status(5,array('game_id'=>$game_id,'odd_type_id'=>9,'team_type_id'=>11,'factor'=>$away));
		//alta home
		$this->setter_status(5,array('game_id'=>$game_id,'odd_type_id'=>9,'team_type_id'=>12,'factor'=>$home));
		//baja home
		$this->setter_status(5,array('game_id'=>$game_id,'odd_type_id'=>9,'team_type_id'=>13,'factor'=>$home));
		
		//GANADORES
		//alta visit
		$this->setter_status(2,array('game_id'=>$game_id,'odd_type_id'=>9,'team_type_id'=>10,'factor <'=>$away));
		//baja visit
		$this->setter_status(2,array('game_id'=>$game_id,'odd_type_id'=>9,'team_type_id'=>11,'factor >'=>$away));
		//alta home
		$this->setter_status(2,array('game_id'=>$game_id,'odd_type_id'=>9,'team_type_id'=>12,'factor <'=>$home));
		//baja home
		$this->setter_status(2,array('game_id'=>$game_id,'odd_type_id'=>9,'team_type_id'=>13,'factor >'=>$home));
		
		//PERDEDORES
		//alta visit
		$this->setter_status(3,array('game_id'=>$game_id,'odd_type_id'=>9,'team_type_id'=>10,'factor >'=>$away));
		//baja visit
		$this->setter_status(3,array('game_id'=>$game_id,'odd_type_id'=>9,'team_type_id'=>11,'factor <'=>$away));
		//alta home
		$this->setter_status(3,array('game_id'=>$game_id,'odd_type_id'=>9,'team_type_id'=>12,'factor >'=>$home));
		//baja home
		$this->setter_status(3,array('game_id'=>$game_id,'odd_type_id'=>9,'team_type_id'=>13,'factor <'=>$home));
	}
	
	
	//arreglar el array para los diferentes logros de los juegos
	function get_fixed_odds($games){
		$odds = $this->find('all',array(
			'conditions' => array(
				'game_id' => $games,'odd_type_id'=>array(1,2,3)
			),'recursive' => -1,
			'fields' => array('id','game_id','odd_type_id','team_type_id')
		));
		
		$odds_by_type = array();
		
		$j = 0; $k = 0; $l = 0; $m = 0; $gmeid = 0;
		
		foreach($odds as $odd){
			$o = $odd['Odd'];
			if($gmeid != $o['game_id']){
				$j = 0; $k = 0; $l = 0; $m = 0;
			}					
			
			if($o['odd_type_id'] == 1 || $o['odd_type_id'] == 2){
				if($o['team_type_id'] == 1){
					$odds_by_type[$o['game_id']]['AWAY'][$j++] = $o['id'];
				}else{
					$odds_by_type[$o['game_id']]['HOME'][$k++] = $o['id'];
				}
			}
			
			if($o['odd_type_id'] == 3){
				if($o['team_type_id'] == 4){
					$odds_by_type[$o['game_id']]['ALTA'][$l++] = $o['id'];
				}else{
					$odds_by_type[$o['game_id']]['BAJA'][$m++] = $o['id'];
				}
			}
			
			$gmeid = $o['game_id'];
		}
		return $odds_by_type;
	}
	
	//traerme los totales segun arreglo anterior
	function get_totals_follow($array_odds){
		$total_final = array();
		
		$oddTiks = ClassRegistry::init('OddsTicket');
		$ticket = ClassRegistry::init('Ticket');
		
		foreach($array_odds as $game => $types){
			$tiks_away = $oddTiks->find('list',array(
				'conditions' => array('odd_id' => $types['AWAY']),
				'fields' => array('ticket_id')
			));
			$away = $ticket->find('all',array(
				'conditions' => array('Ticket.id'=>$tiks_away),'recursive'=>-1,
				'fields' => array('COUNT(*) as tickets','SUM(amount) as amounts','SUM(prize) as prices')
			));
			
			$tiks_home = $oddTiks->find('list',array(
				'conditions' => array('odd_id' => $types['HOME']),
				'fields' => array('ticket_id')
			));
			$home = $ticket->find('all',array(
				'conditions' => array('Ticket.id'=>$tiks_home),'recursive'=>-1,
				'fields' => array('COUNT(*) as tickets','SUM(amount) as amounts','SUM(prize) as prices')
			));
			
			$tiks_alta = $oddTiks->find('list',array(
				'conditions' => array('odd_id' => $types['ALTA']),
				'fields' => array('ticket_id')
			));
			$alta = $ticket->find('all',array(
				'conditions' => array('Ticket.id'=>$tiks_alta),'recursive'=>-1,
				'fields' => array('COUNT(*) as tickets','SUM(amount) as amounts','SUM(prize) as prices')
			));
			
			$tiks_baja = $oddTiks->find('list',array(
				'conditions' => array('odd_id' => $types['BAJA']),
				'fields' => array('ticket_id')
			));
			$baja = $ticket->find('all',array(
				'conditions' => array('Ticket.id'=>$tiks_baja),'recursive'=>-1,
				'fields' => array('COUNT(*) as tickets','SUM(amount) as amounts','SUM(prize) as prices')
			));
			
			$total_final[$game] = array(
				'AWAY' => $away[0][0],'HOME' => $home[0][0],
				'ALTA' => $alta[0][0],'BAJA' => $baja[0][0]
			);
		}
		
		return $total_final;
	}
	
}
?>