<?php
class OddsController extends AppController {

	var $name = 'Odds';
	var $helpers = array('Html','Form');

	function beforeFilter(){
		parent::beforeFilter();	
	}
	
	function isAuthorized(){
		
		$ret = true;
		
		$actions_adm = array(
			"admin_add","admin_exotics","admin_follow"
		);		
				
		if($this->isAdmin() && in_array($this->action, $actions_adm)){
			$ret = true;
		}else{
			$ret = false;
		}
		
		return $ret;
	}
	
	function admin_add($game_id = null, $final = null, $counter = null) {
		if (!empty($this->data)) {
			$this->data['Odd'][5]['factor'] = $this->data['Odd'][4]['factor'];
			$this->data['Odd'][5]['factorold'] = $this->data['Odd'][4]['factorold'];
			//pr($this->data);die();
			$this->Odd->create();
			
			foreach($this->data['Odd'] as $odd){
				
				$odd['game_id'] = $this->data['Commoms']['game_id'];
				$odd['final'] = $this->data['Commoms']['final'];
				
				if((!empty($odd['odd']) && $odd['odd'] != $odd['oddold']) ||
					(!empty($odd['factor']) && $odd['factor'] != $odd['factorold'])
					) {
					//EDIT OLDS 
					$this->Odd->updateAll(
						array('actual'=>0,'until'=>'current_timestamp'),
						array(
							'game_id'=>$odd['game_id'],
							'odd_type_id'=>$odd['odd_type_id'],
							'team_type_id'=>$odd['team_type_id'],
							'final'=>$odd['final']
						)
					);
					//SAVE IT!
					$this->Odd->save($odd);	
				}		
				unset($this->Odd->id);
			}
			//$this->redirect($this->referer());
			echo "<span style='color:Red; font-weight:bold'>Logros <br />Salvados</span>";
			die();
			
		}
		
		$odds_set = array();
		$odds = $this->Odd->find('all',array(
			'conditions' => array('game_id' => $game_id, 'final'=>$final,'actual'=>1),
			'fields' => array('id','odd_type_id','team_type_id','odd','factor','final'),
			'recursive' => -1
		));
		//echo $final; pr($odds);
		foreach($odds as $o){
			$od = $o['Odd'];
			$odds_set[$od['odd_type_id']][$od['team_type_id']]['odd'] = $od['odd'];
			$odds_set[$od['odd_type_id']][$od['team_type_id']]['factor'] = $od['factor'];
		}
		
		$league = $this->Odd->Game->find('first',array(
			'conditions' => array('Game.id' => $game_id),
			'fields' => array('league_id')
		));
		
		$sport = $this->Odd->Game->League->find('first',array(
			'conditions' => array('League.id' => $league['Game']['league_id']),
			'fields' => 'sport_id'
		));
		
		$rl_away = "";
		$rl_home = "";
		$draw = false;
		$odd_alta = "-120";
		$odd_baja = "-120";
		
		//CASO BASKETBALL
		if($sport['League']['sport_id'] == 3 || $sport['League']['sport_id'] == ID_FOOTBALL){
			$rl_away = "-110";
			$rl_home = "-110";
			//$odd_alta = "-110"; $odd_baja = "-110";
		}
		//FIN CASO BASKET
		
		//CASO FUTBOL SOCCER
		if($sport['League']['sport_id'] == 2){
			$draw = true;
		}
		//FIN CASO BASKET
		
		$this->set('counter',$counter);
		$this->set('draw',$draw);
		$this->set('rl_away',$rl_away);
		$this->set('rl_home',$rl_home);
		$this->set('odd_alta',$odd_alta);
		$this->set('odd_baja',$odd_baja);
		$this->set('final',$final);
		$this->set('game_id',$game_id);
		$this->set('odds_set',$odds_set);
		
	}
	
	function admin_exotics($game_id = null, $league_id = null, $counter = null){
		if(!empty($this->data)){
			//pr($this->data); die();
			$this->Odd->create();
			
			foreach($this->data['Odd'] as $odd){
				
				if((!empty($odd['odd']) && $odd['odd'] != $odd['oddold']) ||
					(!empty($odd['factor']) && $odd['factor'] != $odd['factorold'])
					) {
					//EDIT OLDS 
					$this->Odd->updateAll(
						array('actual'=>0,'until'=>'current_timestamp'),
						array(
							'game_id'=>$odd['game_id'],
							'odd_type_id'=>$odd['odd_type_id'],
							'team_type_id'=>$odd['team_type_id']
						)
					);
					//SAVE IT!
					$this->Odd->save($odd);	
				}		
				unset($this->Odd->id);
			}
			echo "<span style='color:Red; font-weight:bold'>Logros <br />Salvados</span>";
			die();
		}
		
		$sport = $this->Odd->Game->League->find('first',array(
			'conditions' => array('League.id' => $league_id),
			'fields' => 'sport_id'
		));
		
		$sport_id = $sport['League']['sport_id'];
		
		$odds_set = array();
		
		$exotics = $this->Odd->get_exotics($sport_id);
		
		$odd_types = $this->Odd->get_odd_type_ids($sport_id);
		
		$odds = $this->Odd->find('all',array(
			'conditions' => array('game_id' => $game_id, 'odd_type_id'=>$odd_types, 'actual' => 1),
			'fields' => array('odd_type_id','team_type_id','odd','factor'),
			'recursive' => -1
		));
		
		foreach($odds as $o){
			$od = $o['Odd'];
			$odds_set[$od['odd_type_id']][$od['team_type_id']]['odd'] = $od['odd'];
			$odds_set[$od['odd_type_id']][$od['team_type_id']]['factor'] = $od['factor'];
		}
		
		$this->set('game_id',$game_id);
		$this->set('league_id',$league_id);
		$this->set('counter',$counter);
		$this->set('odds_set',$odds_set);
		$this->set('exotics',$exotics);
	}
	
	function admin_follow($date = null, $league = null){
		if($date == null)
			$date = date('Y-m-d');
			
		$crit['date'] = $date;
		
		$league_ids = $this->Odd->Game->find('list',array('conditions'=>array('date'=>$date),'fields'=>'league_id'));

		$leagues = array();
		
		if(!empty($league_ids)){
			$leagues = $this->Odd->Game->League->find_listed($league_ids);
		}	
					
		$games = array();
		$totals = array();
		
		if($league != null){
			$crit['league_id'] = $league;
			$games = $this->Odd->Game->find_exact($crit);
			
			$games_list = $this->Odd->Game->find('list',array(
				'conditions'=>$crit,'fields'=>'Game.id'
			));
			
			$odds_by_type = $this->Odd->get_fixed_odds($games_list);
			
			if(!empty($odds_by_type)){
				$totals = $this->Odd->get_totals_follow($odds_by_type);	
			}
		}

		$this->set('games', $games);
		$this->set('totals', $totals);
		$this->set('date', $date);
		$this->set('leagues', $leagues);
		$this->set('league', $league);	
	}
	
}
?>