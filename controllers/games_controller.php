<?php
class GamesController extends AppController {

	var $name = 'Games';
	var $helpers = array('Html', 'Form', 'Oddsheet');
	
	function beforeFilter(){
		parent::beforeFilter();	
	}
	
	function isAuthorized(){
		
		$ret = true;
		
		$actions_adm = array(
			"admin_index","admin_add","admin_add_horse","admin_edit","admin_delete","admin_free",
			"admin_set_enable","admin_hoja","admin_game_sheet","admin_set_pitchers"
		);

		$actions_taq = array(
			"admin_hoja","admin_game_sheet"
		);
				
		if($this->isAdmin() && in_array($this->action, $actions_adm)){
			$ret = true;
		}elseif($this->isTaquilla() && in_array($this->action, $actions_taq)){
			$ret = true;
		}else{
			$ret = false;
		}
		
		return $ret;
	}

	function admin_index($date = null, $league = null, $not_started = 0) {
		
		if($date == null)
			$date = date('Y-m-d');
		
		$crit['date'] = $date;

		if($not_started != 0)
			$crit['time >'] = date("H:i:s");
		
		$games = array();
		$leagues = array();
		$sport_id = "";
		
		if($league != null){
			$crit['league_id'] = $league;
			$games = $this->Game->find_exact($crit);	
			$sport = $this->Game->League->find('first',
				array('conditions'=>array('League.id'=>$league),'fields'=>'sport_id')); 
			$sport_id = $sport['League']['sport_id'];
		}
		
		$league_ids = $this->Game->find('list',array('conditions'=>array('date'=>$date),'fields'=>'league_id'));

		if(!empty($league_ids)){
			$leagues = $this->Game->League->find_listed($league_ids);
		}
		
		$this->set('games', $games);
		$this->set('league', $league);
		$this->set('sport_id', $sport_id);
		$this->set('not_started',$not_started);
		$this->set('leagues', $leagues);
		$this->set('date', $date);
	}

	function admin_add($date = null, $league_id = null) {
		
		if($date == null)
			$date = date('Y-m-d');
		
		if (!empty($this->data)) {
			
			if($this->data['Game']['league_id'] == 7)
				$this->data['Team'] = $this->_patch_hockey($this->data['Team']);

			//pr($this->data);die();
			$this->Game->create();
			if ($this->Game->save($this->data)) {
				$this->Session->setFlash("Juego Salvado");
			} else {
				$this->Session->setFlash("Juego NO Salvado. Intente de nuevo");
			}
			//verificar si dentro del string de referer dice index o add,
			//si dice add redireccionar por fecha y liga q viene de data
			$mystring = $this->referer();
			
			if(!(strrpos($mystring, "index"))){ // es decir viene de add
				$this->redirect(array('action'=>'index',$this->data['Game']['date'],$this->data['Game']['league_id']));
			}else{
				$this->redirect($this->referer());	
			}			
		}
		
		$this->set('date', $date);
		
		if($league_id != null){
			
			$games = $this->Game->find('list',array(
				'conditions' => array('date'=>$date,'league_id'=>$league_id),
				'fields' => 'id'
			));
			
			$last_ref = $this->Game->GamesTeam->find('first',array(
				'conditions' => array(
					'game_id' => $games
				),
				'fields' => 'GamesTeam.reference',
				'order' => array('GamesTeam.reference' => 'DESC')
			));
			
			$teams = $this->Game->Team->find('list',array(
				'conditions'=>array('league_id'=>$league_id),'order'=>'name'
			));
			
			$this->set('teams',$teams);
			$this->set('league_id',$league_id);
			$this->set('last_ref',$last_ref['GamesTeam']['reference']);
			
		}else{
			$sports = $this->Game->League->Sport->find('list',array('conditions'=>array('enable'=>1)));
			$this->set('sports',$sports);
			$this->render("admin_pre_add");			
		}
				
	}

	function admin_add_horse($date = null, $league_id = null) {
		
		if (!empty($this->data)) {
			
			pr($this->data);die();
			//esto puedo pasarlo al add normalazo, probar a ver
			
			
			$this->Game->create();
			if ($this->Game->save($this->data)) {
				$this->Session->setFlash("Juego Salvado");
			} else {
				$this->Session->setFlash("Juego NO Salvado. Intente de nuevo");
			}
			//verificar si dentro del string de referer dice index o add,
			//si dice add redireccionar por fecha y liga q viene de data
			$mystring = $this->referer();
			
			if(!(strrpos($mystring, "index"))){ // es decir viene de add
				$this->redirect(array('action'=>'index',$this->data['Game']['date'],$this->data['Game']['league_id']));
			}else{
				$this->redirect($this->referer());	
			}			
		}
		
		$this->set('date', $date);
		
		$teams = $this->Game->Team->find('list',array(
			'conditions'=>array('league_id'=>10),'order'=>'name'
		));
		
		$this->set('teams',$teams);
		$this->set('league_id',$league_id);
	}
	
	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Game', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Game->save($this->data)) {
				$this->Session->setFlash("Juego Salvado");
			} else {
				$this->Session->setFlash("Juego NO Salvado. Intente de nuevo");
			}
			$this->redirect($this->referer());
		}
		if (empty($this->data)) {
			$this->data = $this->Game->read(null, $id);
		}
		$teams = $this->Game->Team->find('list');
		$leagues = $this->Game->League->find('list');
		$this->set(compact('teams','leagues'));
	}

	function admin_hoja($date = null){
		if($date == null)
			$date = date('Y-m-d');
		
		$leagues = $this->Game->find('list',array(
			'conditions' => array('date' => $date),
			'fields' => 'league_id',
			'group' => 'league_id'
		));
		
		$league_detail = $this->Game->League->find_leagues_by_group($leagues);
		
		$this->set('league_detail',$league_detail);
		$this->set('date',$date);
		
	}
	
	function admin_game_sheet($date,$league_str,$format,$results,$scoreb){
		
		$leagues = explode("-",$league_str);
		
		$games = $this->Game->to_odds_sheet($date,$leagues,$format);
		
		foreach($games as $gk => $gv){
			foreach($gv['Odd'] as $odd){
				$games[$gk]['Odds'][$odd['final']][$odd['odd_type_id']][$odd['team_type_id']]['odd'] = $odd['odd'];
				$games[$gk]['Odds'][$odd['final']][$odd['odd_type_id']][$odd['team_type_id']]['factor'] = $odd['factor'];	
			}
			unset($games[$gk]['Odd']);			
		}		
		
		$sports = $this->Game->League->Sport->find('list',array(
			'fields' => array('default_type')
		));
		
		$draws = $this->Game->League->Sport->find('list',array(
			'fields' => array('get_draw')
		));	
		
		$yesterday = date("Y-m-d",strtotime("-1 day"));
		
		//echo $yesterday;
		
		$results = $this->Game->to_results($yesterday,$league_str);
		//pr($games);pr($sports);pr($draws); die();
		
		$this->set('games',$games);
		$this->set('results',$results);
		$this->set('sports',$sports);
		$this->set('draws',$draws);
		$this->set(compact('format','results','scoreb'));
		
		$this->layout = "odds_sheet";
	}
	
	function admin_set_pitchers($date = null, $league = null){
		if(!empty($this->data)){
			//pr($this->data); die();
			foreach($this->data['Game'] as $pk => $pv){
				if($pv['pitcher'] != ""){
					$this->Game->GamesTeam->updateAll(
						array('metadata' => "'".$pv['metadata']."'"),
						array('GamesTeam.id' => $pk)
					);	
				}
			}
			$this->Session->setFlash("Pitchers Guardados");
			$this->redirect($this->referer());
		}
		
		if($date == null)
			$date = date("Y-m-d");
			
		$crit['date'] = $date;

		$games = array();
		
		$baseballs = $this->Game->League->find('list',array(
			'conditions'=>array('sport_id' => 1),'fields'=>'League.id'
		));

		$crit['league_id'] = $baseballs;

		$pitchers = array();
		
		if($league != null){
			$crit['league_id'] = $league;
			
			$pitchs = ClassRegistry::init("Pitcher");
			$pitchers = $pitchs->teams_by_league($league);
		}
		
		$games = $this->Game->find_exact($crit);
		
		$today_leagues = $this->Game->find('list',array(
			'conditions'=>array('date'=>$date,'league_id'=>$baseballs),
			'fields'=>'league_id'
		));
		
		$leagues = $this->Game->League->find_listed($today_leagues);
		
		//pr($league_ids);die();
		$this->set(compact('games','leagues','date','league','pitchers'));		
	}
	
	function admin_delete($id) {
		$odd = ClassRegistry::init('Odd');
		$odds = $odd->find('list',array(
			'conditions'=>array('game_id'=>$id),
			'fields' => 'Odd.id'
		));
		
		$odds_tik = ClassRegistry::init('OddsTicket');
		$parlays = $odds_tik->find('count',array('conditions'=>array('odd_id'=>$odds)));
		//pr($parlays); die();
		if($parlays > 0){
			$this->Session->setFlash("ERROR: Juego NO puede ser borrado, tiene apuestas.");
		}else{
			$this->Game->del($id);
			$this->Session->setFlash("Juego Borrado");	
		}
		$this->redirect($this->referer());		
	}
	
	function admin_free($date,$league_id){
		$this->Game->updateAll(
			array('enable'=>1),
			array('date'=>$date ,'league_id' => $league_id)
		);
		$this->Session->setFlash("Liga liberada");
		$this->redirect($this->referer());
	}
	
	function admin_set_enable($id, $status) {
		$newstat = 1;
		if($status == 1)
			$newstat = 0;
		
		$this->Game->updateAll(array('enable'=>$newstat),array('Game.id'=>$id));
		
		$this->redirect($this->referer());
		
	}	
	
	function _patch_hockey($teams){
		foreach($teams as $k => $v){
			if($v['reference'] < 10){
				$teams[$k]['reference'] = "0".$v['reference'];
			}
		}
		return $teams;
	}
}
?>