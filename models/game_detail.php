<?php
class GameDetail extends AppModel {

	var $name = 'GameDetail';

	function find_game($date,$ref){
		$game = $this->find('first',array(
			'conditions' =>  array(
				'date'=>$date,'ref'=>$ref
			),
			'fields' => array(
				'id','default_type','league_id','team_type_id',
				'time','ref','name'
			)
		));
		
		$ret = array();
		if(!empty($game))
			$ret = $game['GameDetail'];
		
		return $ret;
	}
	
	function get_game_detail($date,$ref){
		
		$game = $this->find_game($date,$ref);
		
		$prefix = "0";

		$odd_ins = ClassRegistry::init("Odd");
		
		$prefix_ins = ClassRegistry::init("Prefix");
		
		$odd_total = array('Error'=>"",'Result'=>"");
		
		if(empty($game)){
			//busqueda con prefijo
			$prefix = substr($ref,0,1);
			$newref = substr($ref,1);
			
			$game = $this->find_game($date,$newref);
			
			if(empty($game)){
				//busqueda con prefijo de dos letras
				$prefix = substr($ref,0,2);
				$newref = substr($ref,2);
				$game = $this->find_game($date,$newref);
			}
			
		}
		
		if(!empty($game)){
			if(strtotime($game['time']) <= strtotime(date('H:i:s'))){
				$odd_total['Error'] = "Juego iniciado";
			}else{
				$odd_dets = $prefix_ins->define_odd($prefix,$game['team_type_id'],$game['default_type']);
				//pr($odd_dets); die();
				if(!empty($odd_dets)){
					$factor = 0;
					if($game['default_type'] == 2)
						$factor = 1; 
					
					$odd = $odd_ins->get_odd($game['id'],$odd_dets,$factor);
					if(!empty($odd)){
						$odd_total['Result']['Game'] = $game;
						$odd_total['Result']['Odd'] = $odd;
					}else{
						$odd_total['Error'] = "Logro no inscrito en el juego";
					}
				}else{
					$odd_total['Error'] = "Modo erroneo o inexistente";
				}
			}
		}else{
			$odd_total['Error'] = "Juego o referencia invalida";
		}
		
		return $odd_total;
	}
		
}
?>