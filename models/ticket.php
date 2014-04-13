<?php
class Ticket extends AppModel {

	var $name = 'Ticket';

	var $belongsTo = array(
		'Profile' => array('fields'=>'name'),
		'TicketStatus' => array('fields'=>'name')
	);

	var $hasAndBelongsToMany = array('Odd');
	
	var $validate = array(
		'amount' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'required' => true
			)
		)
	);
	
	function calculate_parlay($odd, $amount){
		
		if ($odd==0){
			$prize=$amount;
		}elseif ($odd > 0){ //HEMBRA POSITIVO
			$oddAbsolut = $odd/100;
			$prize = $amount *  $oddAbsolut;
			$fullPrize = $prize + $amount;
		}else{ // MACHO NEGATIVO
			$oddAbsolut = $odd/-100;
			$prize = $amount / $oddAbsolut;
			$fullPrize = $prize + $amount;	
		}
		return $fullPrize;
	}
	
	function calculate_prize($odds,$amount){
		$i = 0;
		$thePrize = 0;
		foreach ($odds as $odd){
			if($i == 0){
				$newAmount = $amount;
			}else{ 
				$newAmount = $thePrize;						
			}
			$thePrize = $this->calculate_parlay($odd,$newAmount);				
			$i++;
		}
		
		$thePrize = round($thePrize);
		
		return $thePrize;
	}
	
	function get_update($first, $last){
		$this->recursive = 1;
		$this->unbindModel(array('hasAndBelongsToMany'=>array('Odd')));
		$this->bindModel(array('hasAndBelongsToMany'=>array('Odd'=>
			array('fields'=>array('odd_status_id','odd')))));
		$tickets = $this->find('all',array(
			'conditions' => array(
				'Ticket.id BETWEEN ? AND ?' => array($first,$last),
				'ticket_status_id NOT' => array(5,6,7)
			), 'fields' => array('Ticket.id','Ticket.amount')
		));
		
		$torecalc = array();
		$toreset = array();
		foreach($tickets as $k =>$ticket){
			//$new_ticket_id = $ticketValue[0]['center_match_id'];
			$loser = false;
			$pending = false;
			
			$counter = 0;
			$countEmpate = 0;
			//En lo q consiga el primer PENDIENTE o PERDEDOR se sale
			$parlays = count($ticket['Odd']);
			$i = 0;
			$statTicket = 1;
			foreach ($ticket['Odd'] as $odd){
				if($odd['odd_status_id'] == 3){
					$loser = true;
					break;
				}
				if($odd['odd_status_id'] == 1){
					$pending = true;
					//break;
				}
				
				if($odd['odd_status_id'] == 4 || $odd['odd_status_id'] == 5){
					$countEmpate ++;
				}
				$counter ++;
			}
			
			if($loser == false){ // NO hay perdedores
				if($pending == false){ // NO hay pendientes
					if($countEmpate == $parlays){ // TODOS LOS PARLAYS SON EMPATADOS
						$statTicket = 4;
					}else{ //entonces es GANADOR
						$statTicket = 2;	
					}
				}			
			}else{ // HAY MINIMO UN PERDEDOR
				$statTicket = 3;
			}
			
			$this->updateAll(
				array('Ticket.ticket_status_id'=> $statTicket),
				array('Ticket.id'=> $ticket['Ticket']['id'])
			);
			
			//PARA BUSCAR LOS RECALCULOS SE TIENE Q VERIFICAR SI HAY STATUS DEVOLUCION:
			//ES DECIR MINIMO UN PARLAY EMPATADO O SUSPENDIDO
			if($countEmpate > 0){
				$torecalc[$ticket['Ticket']['id']] = $ticket['Odd'];
			}
			
			$toreset[$ticket['Ticket']['id']]['amount'] = $ticket['Ticket']['amount'];
			$toreset[$ticket['Ticket']['id']]['Odds'] = $ticket['Odd'];
		}
		
		//SE VUELVE A CALCULAR
		$this->reset_prize($toreset);
		
		//SE MANDA A RECALCULAR LOS Q TIENEN STATUS PARA RECALC
		$this->recalculate($torecalc);
		
		return count($tickets);
	}
	
	function vencidos($date){
		//$date_max_str = strtotime("-3 day");
		//$date_max = date("Y-m-d",$date_max_str);
		
		$vencidos = $this->find('list',array(
			'conditions'=> array(
				'date(created)' => $date,
				'ticket_status_id' => 2,
				'payed' => 0
			), 'fields'=>'Ticket.id'
		));
		
		$this->updateAll(
			array('ticket_status_id' => 7),
			array('Ticket.id' => $vencidos)
		);
		
		return count($vencidos);
	}
	
	function reset_prize($tickets){
		//pr($tickets); die();
		
		foreach ($tickets as $ticketid => $odds){
			$oddcalc = array();
			foreach ($odds['Odds'] as $odd){
				array_push($oddcalc,$odd['odd']);				
			}
			//pr($oddcalc);
			$prize = $this->calculate_prize($oddcalc,$odds['amount']);
			$this->updateAll(
				array('Ticket.prize'=> $prize),
				array('Ticket.id'=> $ticketid)
			);
		}
		//die();
	}
	
	function recalculate($tickets){
		//pr($tickets); die();
		foreach ($tickets as $ticketid => $odds){
			foreach ($odds as $odd){
				//CONSEGUIR LOS PARLAYS EMPATADOS
				if($odd['odd_status_id'] == 4 || $odd['odd_status_id'] == 5){
					//Esta operacion me da el porcentaje del premio q este parley tiene como responsabilidad
					//Le sumo uno en una propiedad de proporcionalidad
					$oddnew = (abs($odd['odd']) / 100) + 1;
					//Tengo dos montos uno que le suma y otro q le divide! depende de si son negativos o positivos
					//Dependiendo de su signo se actualiza la BD respecto al logro
					$recalc = "prize/$oddnew";
					if($odd['odd'] < 0)
						$recalc = "prize - (prize/$oddnew)";
						
					$this->updateAll(
						array('prize' => $recalc),
						array('Ticket.id' => $ticketid)
					);
				}
			}
		}
	}
	
	function valid_limits($profile_id,$amount,$odds){
		$limits = $this->Profile->find('first',array(
			'conditions' =>  array('Profile.id' => $profile_id),
			'fields' => array('max_parlays','max_amount_straight','max_amount_parlay','max_prize')
		));
		
		$error = "";
		$parls = count($odds);
		
		if($parls > $limits['Profile']['max_parlays']){
			$error = "El numero de parlays no esta permitido.";
		}else{
			if($parls == 1)
				$amount_check = $limits['Profile']['max_amount_straight'];
			else
				$amount_check = $limits['Profile']['max_amount_parlay'];
				
			if($amount > $amount_check){
				$error = "El monto no esta permitido.";
			}else{
				$prize = $this->calculate_prize($odds,$amount);
				
				if($prize > $limits['Profile']['max_prize'])
					$error = "El premio no esta permitido.";
			}
		}
		
		return $error;
	}
	
	function valid_started($times){
		$timesu = array_unique($times);
		$valid = "";
		$nowTime = date("H:i:s");
		foreach ($timesu as $t) {
			if (strtotime($t) < strtotime($nowTime)){
				$valid = "Alguno de los juegos ya empezo.";
				break;			
			}
		}
		return $valid;
	}
	
	function verify_started_ticket($ticket_id){
		$odds = $this->OddsTicket->find('list',array(
			'conditions' => array('ticket_id' => $ticket_id),
			'fields' => array('odd_id')
		));
		
		$games = $this->Odd->find('list',array(
			'conditions' => array('Odd.id' => $odds),
			'fields' => array('game_id')
		));
		
		$times = $this->Odd->Game->find('list',array(
			'conditions' => array('Game.id' => array_unique($games)),
			'fields' => array('time')
		));
		
		$dates = $this->Odd->Game->find('list',array(
			'conditions' => array('Game.id' => array_unique($games)),
			'fields' => array('date')
		));
		
		$valid = true;
		foreach($dates as $d){
			if(strtotime($d) < strtotime(date("Y-m-d"))){
				$valid = false;
				break;
			}	
		}
		
		if($valid == true){
			$started = $this->valid_started($times);
			//echo $started; die();
			if($started == "")
				$valid = false;
		}
		
		return $valid;
	}
	
}
?>