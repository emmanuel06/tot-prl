<?php
class ResultsController extends AppController {

	var $name = 'Results';
	var $helpers = array('Html', 'Form');

	function beforeFilter(){
		parent::beforeFilter();	
	}
	
	function isAuthorized(){
		
		$ret = true;
		
		$actions_adm = array(
			"admin_index","admin_add","admin_exotics","admin_suspend","admin_specials"
		);		
				
		if($this->isAdmin() && in_array($this->action, $actions_adm)){
			$ret = true;
		}else{
			$ret = false;
		}
		
		return $ret;
	}
	
	function admin_index($date = null, $league = null) {
		if($date == null)
			$date = date('Y-m-d');
			
		$crit['date'] = $date;
		
		$league_ids = $this->Result->Game->find('list',array('conditions'=>array('date'=>$date),'fields'=>'league_id'));

		$leagues = array();
		
		if(!empty($league_ids)){
			$leagues = $this->Result->Game->League->find_listed($league_ids);
		}	
					
		$games = array();
		
		if($league != null){
			$crit['league_id'] = $league;
			$games = $this->Result->Game->find_exact($crit);	
		}

		$this->set('games', $games);
		$this->set('date', $date);
		$this->set('leagues', $leagues);
		$this->set('league', $league);		
	}

	function admin_add($game_id = null, $counter = null) {
		if (!empty($this->data)) {
			if($this->data['Result']['eraser'] == 1){
				$this->Result->deleteAll(
					array('game_id' => $this->data['Result']['game_id'])
				);
				die("<span style='color:Red; font-weight:bold'>Resultados<br />borrados.<br /></span>");
			}else{
				unset($this->data['Result']['eraser']);
				unset($this->data['Result']['game_id']);
			}
			
			$not_saved = "";
			//COMPLETO
			if($this->data['Result'][0]['score'] != "" && $this->data['Result'][1]['score'] != ""){
				$this->data['Result'][0]['odd_type_id'] = 1;
				$this->data['Result'][1]['odd_type_id'] = 1;
				$this->data['Result'][0]['team_type_id'] = 1;
				$this->data['Result'][1]['team_type_id'] = 2;
				$this->data['Result'][0]['final'] = 1;
				$this->data['Result'][1]['final'] = 1;
			}else{
				unset($this->data['Result'][0]);
				unset($this->data['Result'][1]);
				$not_saved .= "Juego Completo<br /> NO salvado.";
			}
			
			//MITAD
			if($this->data['Result'][2]['score'] != "" && $this->data['Result'][3]['score'] != ""){
				$this->data['Result'][2]['odd_type_id'] = 1;
				$this->data['Result'][3]['odd_type_id'] = 1;
				$this->data['Result'][2]['team_type_id'] = 1;
				$this->data['Result'][3]['team_type_id'] = 2;
				$this->data['Result'][2]['final'] = 0;
				$this->data['Result'][3]['final'] = 0;
			}else{
				unset($this->data['Result'][2]);
				unset($this->data['Result'][3]);
				$not_saved .= "Juego a Mitad<br /> NO salvado.";
			}
			
			//1er CUART
			if(!empty($this->data['Result'][4])){
				if($this->data['Result'][4]['score'] != "" && $this->data['Result'][5]['score'] != ""){
					$this->data['Result'][4]['odd_type_id'] = 1;
					$this->data['Result'][5]['odd_type_id'] = 1;
					$this->data['Result'][4]['team_type_id'] = 1;
					$this->data['Result'][5]['team_type_id'] = 2;
					$this->data['Result'][4]['final'] = 2;
					$this->data['Result'][5]['final'] = 2;
				}else{
					unset($this->data['Result'][4]);
					unset($this->data['Result'][5]);
					$not_saved .= "Juego 1er cuarto<br /> NO salvado.";
				}	
			}
			
			//pr($this->data);die();
			$this->Result->create();
			$game_id = null;
			$res_opts = array();
			
			foreach($this->data['Result'] as $result){
				if($result['score'] != $result['scoreold']){
					$this->Result->save($result);
				}
				$res_opts[$result['final']][$result['team_type_id']] = $result['score'];
				unset($this->Result->id);
				if($game_id == null)
					$game_id = $result['game_id'];
			}

			if(!empty($res_opts)){
				foreach ($res_opts as $fin => $vals) {
					if(count($res_opts[$fin]) < 2)
						unset($res_opts[$fin]);
				}
				$this->Result->set_results_type_one($game_id,$res_opts);
			}
			$mess = "Resultados<br />Guardados.<br />$not_saved";	
			die("<span style='color:Red; font-weight:bold'>$mess</span>");
		}
		
		$type = $this->Result->Game->find('first',array(
			'conditions'=>array('Game.id'=>$game_id),'fields'=>'League.sport_id'));
		
		$hasqt = false;
		if($type['League']['sport_id'] == ID_BASKET)
			$hasqt = true;

		$results = $this->Result->find('all',array(
			'conditions' => array('game_id' => $game_id,'odd_type_id'=>1),
			'fields' => array('id','score','final','team_type_id','suspended')
		));
	
		$res = array();
		foreach($results as $result){
			$r = $result['Result'];
			$res[$r['final']][$r['team_type_id']]['score'] = $r['score'];
			$res[$r['final']][$r['team_type_id']]['id'] = $r['id']; 
			$res[$r['final']][$r['team_type_id']]['suspended'] = $r['suspended'];
		}
		
		//pr($results); die();
		$this->set('results',$res);
		$this->set('hasqt',$hasqt);
		$this->set('counter',$counter);
		$this->set('game_id',$game_id);
	}
	
	function admin_exotics($game_id = null, $counter = null){
		if (!empty($this->data)) {
			//pr($this->data); die();
			$this->Result->create();
			foreach($this->data['Result'] as $result){
				if(!empty($this->data['Selected'][$result['odd_type_id']])){
					if($this->data['Selected'][$result['odd_type_id']]['value']  == $result['team_type_id']){
						$result['score'] = 1;	
					}

					if(!empty($this->data['Selected'][$result['odd_type_id']]['id'])){
						$result['id'] = $this->data['Selected'][$result['odd_type_id']]['id'];
					}
				}
				
				if(!empty($result['score'])){
					$this->Result->save($result);					
				}
				
				if($result['odd_type_id'] == 7){
					$this->Result->set_total_che($result['game_id'],$result['score']);
				}
				
				unset($this->Result->id);
			}
			if(!empty($this->data['Selected'])){
				$this->Result->set_exotic_results($this->data['Result'][0]['game_id'],$this->data['Selected']);
			}
			die("<span style='color:Red; font-weight:bold'>Resultados<br />Guardados.</span>");
		}
		
		$league_id = $this->Result->Game->find('first',array(
			'conditions' => array('Game.id' => $game_id),
			'fields' => 'league_id'
		));
		
		$sport = $this->Result->Game->League->find('first',array(
			'conditions' => array('League.id' => $league_id['Game']['league_id']),
			'fields' => 'sport_id'
		));
		
		$sport_id = $sport['League']['sport_id'];
				
		$results = $this->Result->get_exotics($sport_id);

		$result_types = $this->Result->get_odd_type_ids($sport_id);
		//pr($result_types);
		
		$res_set = $this->Result->find('all',array(
			'conditions' => array('game_id' => $game_id, 'odd_type_id'=>$result_types),
			'fields' => array('id','odd_type_id','team_type_id','score'),
			'recursive' => -1
		));
		
		$tot_che = "";
		$tot_che_id = 0;
		$has_che = false;
		if(in_array(7,$result_types)){
			$has_che = true;
			$total_che = $this->Result->find('first',array(
				'conditions' => array('game_id' => $game_id, 'odd_type_id'=>7),
				'fields' => array('id','score'),
				'recursive' => -1
			));
			
			if(!empty($total_che)){
				$tot_che = $total_che['Result']['score'];
				$tot_che_id = $total_che['Result']['id'];
			}	
		}
		//pr($total_che); die();
		
		$results_set = array();
		
		foreach($res_set as $r){
			$re = $r['Result'];
			$results_set[$re['odd_type_id']][$re['team_type_id']]['score'] = $re['score'];
			$results_set[$re['odd_type_id']][$re['team_type_id']]['id'] = $re['id'];
		}
		
		//pr($results_set); die();
		$this->set('has_che',$has_che);
		$this->set('tot_che',$tot_che);
		$this->set('tot_che_id',$tot_che_id);
		$this->set('results',$results);
		$this->set('results_set',$results_set);
		$this->set('counter',$counter);
		$this->set('game_id',$game_id);
		
	}

	function admin_suspend($game_id = null, $counter= null) {
		if (!empty($this->data)) {
			$result = array(
				'game_id' => $this->data['Result']['game_id'],
				'odd_type_id' => 1, 'team_type_id' => 1,
				'score' => 0, 'suspended'=>1, 'final' =>1
			);
			$this->Result->save($result);
			
			$this->Result->set_suspended($this->data['Result']['game_id']);
	
			die("<span style='color:Red; font-weight:bold'>Resultados<br />Guardados.<br /></span>");
		}
		
		$results = $this->Result->find('first',array(
			'conditions' => array('game_id' => $game_id, 'suspended'=>1),
			'fields' => array('id','suspended'),
			'recursive' => -1
		));
		
		$this->set('results',$results);
		$this->set('counter',$counter);
		$this->set('game_id',$game_id);
	}
	
	function admin_specials($game_id = null, $counter = null){
		if (!empty($this->data)) {
			//pr($this->data); die();
			
			$tosuspend = array();
			
			if($this->data['Half']['Moneyline'] == 1)
				array_push($tosuspend,array('type'=>1,'final'=>0));
				
			if($this->data['Half']['Runline'] == 1)
				array_push($tosuspend,array('type'=>2,'final'=>0));
				
			if($this->data['Half']['HighLow'] == 1)
				array_push($tosuspend,array('type'=>3,'final'=>0));
				
			if($this->data['Final']['Moneyline'] == 1)
				array_push($tosuspend,array('type'=>1,'final'=>1));
				
			if($this->data['Final']['Runline'] == 1)
				array_push($tosuspend,array('type'=>2,'final'=>1));
				
			if($this->data['Final']['HighLow'] == 1)
				array_push($tosuspend,array('type'=>3,'final'=>1));
			
			if($this->data['Final']['QUI'] == 1)
				array_push($tosuspend,array('type'=>6,'final'=>1));
				
			if($this->data['Final']['CHE'] == 1)
				array_push($tosuspend,array('type'=>7,'final'=>1));
			
			if($this->data['Final']['SRL'] == 1)
				array_push($tosuspend,array('type'=>8,'final'=>1));
			
			if(!empty($tosuspend)){
				$this->Result->set_specials($this->data['Result']['game_id'],$tosuspend);
			}
			die("<span style='color:Red; font-weight:bold'>Resultados<br />Guardados.<br /></span>");
		}
		
		$this->set('counter',$counter);
		$this->set('game_id',$game_id);
	}
}
?>