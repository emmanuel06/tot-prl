<?php
class TicketsController extends AppController {

	var $name = 'Tickets';
	var $helpers = array('Html', 'Form');
	var $uses = array('Ticket','Operation');
	
	function beforeFilter(){
		parent::beforeFilter();
		//$this->Authed->allow(array("sales_group"));
	}
	
	function isAuthorized(){
		
		$ret = true;
		
		$actions_adm = array(
			"admin_tickets","admin_sales","admin_update_statuses","admin_pay",
			"admin_anull","admin_set_anulled","admin_anull_ticket","admin_sales_new",
			"admin_outdated","admin_edit"
		);
		
		$actions_taq = array(
			"admin_add","admin_print","admin_pay","admin_report","admin_sales_taquilla"
		);
				
		//if($this->_valid_time()){
			array_push($actions_taq,"admin_sales_taquilla");
			array_push($actions_taq,"admin_tickets_taquilla");
		//}
		
		if($this->isAdmin() && in_array($this->action, $actions_adm)){
			$ret = true;
		}elseif($this->isTaquilla() && in_array($this->action, $actions_taq)){
			$ret = true;
		}else{
			$ret = false;
		}
		
		return $ret;
	}

	/*
	function _valid_time(){
		$validation = false;
		$strnow = strtotime(date('H:i:s'));
		//$strnow = strtotime("11:15:00");
		//bloque 1
		$first_end = strtotime("11:30:00");
		//bloque 2
		$second_start = strtotime("15:00:00");
		$second_end = strtotime("16:00:00");
		//bloque 3
		$third_start = strtotime("19:00:00");
		
		if($strnow < $first_end){
			$validation = true;
		}
		
		if($strnow > $second_start && $strnow < $second_end){
			$validation = true;
		}
		
		if($strnow > $third_start){
			$validation = true;
		}
		
		return $validation;
	}*/
	
	function admin_add() {
		if (!empty($this->data)) {
			//pr($this->data);die();
			$error = $this->_validate_add($this->data);
			
			if($error == ""){
				$this->data['Ticket']['confirm'] = up(substr(md5(date('h:i:s'.$this->authUser['profile_id'])),0,7));;
				$this->data['Ticket']['profile_id'] = $this->authUser['profile_id'];
				$this->data['Ticket']['prize'] = $this->Ticket->calculate_prize($this->data['Odds'],$this->data['Ticket']['amount']);
				unset($this->data['Odds']);
				unset($this->data['Times']);
				unset($this->data['Games']);
				$this->Ticket->create();
				if ($this->Ticket->saveAll($this->data)) {
					$this->redirect(array('action' => 'print',$this->Ticket->id));
				} else {
					$this->Session->setFlash("Ticket NO Guardado");
				}
			}else{
				$this->Session->setFlash($error);
			}
				
			$this->redirect(array('action'=>'add'));
		}
		$this->set('date',date('Y-m-d'));
	}
	
	
	function admin_edit($id = null){
		if(!empty($this->data)){
			//pr($this->data); die();
			if($this->Ticket->updateAll(
				array(
					'ticket_status_id'=>$this->data['Ticket']['ticket_status_id'],
					'payed'=>$this->data['Ticket']['payed']
				),
				array('Ticket.id'=>$this->data['Ticket']['id'])
			))
				$this->Session->setFlash("Datos de TICKET editados");
			else
				$this->Session->setFlash("Error guardando intente de nuevo");
				
			$this->redirect($this->referer());
		}

		$ticket = $this->Ticket->find('first',array(
			'conditions' => "Ticket.id = $id",
			'fields' => array('Ticket.id','ticket_status_id','payed'),
			'recursive' => -1
		));
		//pr($ticket); die();
		
		$this->set('ticket_statuses',$this->Ticket->TicketStatus->find('list'));
		$this->set('ticket',$ticket['Ticket']);
	}
	
	function admin_print($id){
		$this->Ticket->unbindModel(array('belongsTo'=>array('TicketStatus')),false);
		
		$ticket = $this->Ticket->find('first',array(
			'conditions' => array('Ticket.id'=>$id),'recursive' => 0
		));
		
		if($ticket['Ticket']['printed'] == 1){
			$this->Session->setFlash("Ticket YA FUE IMPRESO");
			$this->redirect(array('action' =>'add'));
		}else{
			$tik_detail = ClassRegistry::init("TicketDetail");
		
			$this->Ticket->updateAll(
				array('printed'=>1),
				array('Ticket.id'=>$id)
			);
			
			$ticket['Odds'] = $tik_detail->find('all',array(
				'conditions' => array('ticket_id' => $id)
			));
			
			//pr($ticket); die();
			$this->set('ticket',$ticket);
			$this->layout = 'print';
		}
	}
	
	function roer_789($id,$prize = null){
		$this->Ticket->unbindModel(array('belongsTo'=>array('TicketStatus')),false);
		$ticket = $this->Ticket->find('first',array(
			'conditions' => array('Ticket.id'=>$id),'recursive' => 0
		));
		
		$tik_detail = ClassRegistry::init("TicketDetail");
		
		$ticket['Odds'] = $tik_detail->find('all',array(
			'conditions' => array('ticket_id' => $id)
		));
		
		if($prize != null){
			$ticket['Ticket']['prize'] = $prize;
		}
		
		//pr($ticket); die();
		$this->set('ticket',$ticket);
		$this->layout = 'print';
	}
	
	function admin_tickets($since = null, $until = null, $profile = 0, $status = 0, $payed = 2, $nro = "") {
		if($since == null){
			$since = date('Y-m-d');
			$until = date('Y-m-d');
		}
		
		$conds['created BETWEEN ? AND ?'] = array("$since 00:00:00","$until 23:59:59");
		
		if($profile != 0)
			$conds['profile_id'] = $profile;
			
		if($status != 0)
			$conds['ticket_status_id'] = $status;

		if($payed != 2)
			$conds['payed'] = $payed;
		
		if($nro != "")
			$conds['Ticket.id LIKE'] = "%$nro%";
		
		$this->Ticket->recursive = 0;
		
		$this->paginate['conditions'] = $conds;
		$this->paginate['fields'] = array(
			'id','created','amount','prize','payed','TicketStatus.name','Profile.name'
		);
		$this->paginate['order'] = array('Ticket.id' => 'DESC');
		
		$profiles = $this->Ticket->Profile->find('list',array(
			'conditions'=>array('role_id'=>3),'order'=>array('name'=>'ASC')
		));
		
		$this->set('tickets', $this->paginate());
		
		$this->set(compact('since','until','status','profile','profiles','nro','payed'));
		
		$this->set('ticket_statuses', $this->Ticket->TicketStatus->find('list'));
		
	}
	
	function admin_tickets_taquilla($since = null, $until = null, $status = 0, $nro = "") {
		if($since == null){
			$since = date('Y-m-d');
			$until = date('Y-m-d');
		}
		
		$conds['profile_id'] = $this->authUser['profile_id'];
		
		$conds['created BETWEEN ? AND ?'] = array("$since 00:00:00","$until 23:59:59");
		
		if($status != 0)
			$conds['ticket_status_id'] = $status;
			
		if($nro != "")
			$conds['Ticket.id LIKE'] = "%$nro%";
		
		$this->Ticket->unbindModel(array('belongsTo'=>array('Profile')),false);
		
		$this->Ticket->recursive = 0;
		
		$this->paginate['conditions'] = $conds;
		$this->paginate['fields'] = array(
			'id','created','amount','prize','payed','TicketStatus.name'
		);
		$this->paginate['order'] = array('Ticket.id' => 'DESC');
		
		$this->set('tickets', $this->paginate());
		$this->set(compact('since','until','status','nro'));
		$this->set('ticket_statuses', $this->Ticket->TicketStatus->find('list'));
	}
	
	function admin_sales($since = null, $until = null, $group = 0){
		if($since == null){
			$since = date('Y-m-d');
			$until = date('Y-m-d');			
		}
		
		$cond['created BETWEEN ? AND ?'] = array("$since 00:00:00","$until 23:59:59");
		$condProf['role_id'] = 3;
		
		if($group != 0){
			$profiles_group = $this->Ticket->Profile->find('list',array(
				'conditions'=>array('group_id'=>$group),
				'fields'=>'id'
			));
			$cond['profile_id'] = $profiles_group;
			$condProf['Profile.id'] = $profiles_group;
		}
		
		// todos menos los anulados
		$totals = $this->Ticket->find('first',array(
			'fields' => array('count(*) AS total','sum(amount) AS amounts'),
			'conditions' => array(
				 $cond,
				'ticket_status_id NOT' => array(4,6)
			),'recursive' => -1
		));
		
		//ganadores
		/*$winners = $this->Ticket->find('first',array(
			'fields' => array('count(*) AS total','sum(prize) AS prices'),
			'conditions' => array(
				$cond,
				'ticket_status_id' => 2
			),'recursive' => -1
		));*/
		$opers = ClassRegistry::init('Operation');
		$allpayed = $opers->find('list',array(
			'conditions' => array(
				'date(created) BETWEEN ? AND ?' => array($since,$until),
				'operation_type_id'=>4,'metadata'=>'Pago'
			), 'fields' => 'model_id'
		));
		
		$winners = $this->Ticket->find('first',array(
			'conditions' => array('Ticket.id' => $allpayed),
			'fields' => array('count(*) AS total','sum(prize) AS prices'),
 			'recursive' => -1
 		));
		
		//devueltos (amounts deberia ser igual q prices)
		$backs = $this->Ticket->find('first',array(
			'fields' => array('count(*) AS total','sum(amount) AS amounts','sum(prize) AS prices'),
			'conditions' => array(
				$cond,
				'ticket_status_id' => 4
			),'recursive' => -1
		));
		
		$this->Ticket->Profile->recursive = -1;
		
		$this->paginate['fields'] = array('Profile.name','Profile.id');
		
		$this->paginate['conditions'] = $condProf;
		
		$profiles = $this->paginate('Profile');
		
		$profiles_ids = array();
		
		foreach($profiles as $p){
			array_push($profiles_ids,$p['Profile']['id']);
		}
		
		$sales = ClassRegistry::init("ProfileSale");
		$wins = ClassRegistry::init("ProfileWin");
		$baks = ClassRegistry::init("ProfileBack");
		
		$profile_sales = $sales->find('all',array(
			'conditions' => array(
				'date BETWEEN ? AND ?'=>array($since,$until),
				'profile_id' => $profiles_ids
			),
			'fields' => array('profile_id',"SUM(totals) AS total","SUM(sold) AS sold"),
			'group' => 'profile_id'
		));
		/*
		$profile_wins = $wins->find('all',array(
			'conditions' => array(
				'date BETWEEN ? AND ?'=>array($since,$until),
				'profile_id' => $profiles_ids
			),
			'fields' => array('profile_id',"SUM(totals) AS total","SUM(wins) AS win"),
			'group' => 'profile_id'
		));
		*/
		$profile_wins = $this->Ticket->find('all',array(
			'conditions' => array('Ticket.id'=>$allpayed),
			'fields' => array('profile_id',"COUNT(*) AS total","SUM(prize) AS win"),
			'group' => 'profile_id','recursive'=>-1
		));
		//pr($profile_wins);pr($new_wins);
		
		$profile_backs = $baks->find('all',array(
			'conditions' => array(
				'date BETWEEN ? AND ?'=>array($since,$until),
				'profile_id' => $profiles_ids
			),
			'fields' => array('profile_id',"SUM(totals) AS total","SUM(backs) AS back"),
			'group' => 'profile_id'
		));
		
		$final_amounts = array();
		
		foreach($profile_sales as $sale){
			$final_amounts[$sale['ProfileSale']['profile_id']]['Sold']['tickets'] = $sale[0]['total'];
			$final_amounts[$sale['ProfileSale']['profile_id']]['Sold']['amount'] = $sale[0]['sold'];
		}
		
		foreach($profile_wins as $won){
			$final_amounts[$won['Ticket']['profile_id']]['Win']['tickets'] = $won[0]['total'];
			$final_amounts[$won['Ticket']['profile_id']]['Win']['amount'] = $won[0]['win'];
		}
		
		foreach($profile_backs as $bak){
			$final_amounts[$bak['ProfileBack']['profile_id']]['Back']['tickets'] = $bak[0]['total'];
			$final_amounts[$bak['ProfileBack']['profile_id']]['Back']['amount'] = $bak[0]['back'];
		}
		
		$this->set('final_amounts',$final_amounts);
		
		$this->set('profiles',$profiles);
		
		$this->set('groups',$this->Ticket->Profile->Group->find('list'));
		
		$this->set(compact('totals','winners','backs','since','until','group'));
	}
	
	function sales_group($group = 0, $since = null, $until = null){
		
		$groupins = ClassRegistry::init("Group");
		
		if(!empty($this->data)){
			//pr($this->data); die();
			$found = $groupins->find('first',array(
				'conditions' => array(
					'name'=>$this->data['Ticket']['group'],'passkey'=>$this->data['Ticket']['passkey']
				),
				'fields' => 'Group.id'
			));
			
			if(!empty($found)){
				//crear el cookiee
				
				
				//redireccionar con las propiedades
				$gid = $found['Group']['id'];
				$since = date('Y-m-d');
				$until = date('Y-m-d');	
				
				$this->redirect(array('action'=>'sales_group',$gid,$since,$until));
			}else{
				$this->Session->setFlash("Nombre de Grupo o Clave secreta incorrectos.");		
				$this->redirect(array('action'=>'sales_group'));
			}
			pr($found); die();
		}
		
		if($group == 0){
			$this->render("no_group");
		}
		
		//aqui comienza la traedera de datos
			
		$cond['created BETWEEN ? AND ?'] = array("$since 00:00:00","$until 23:59:59");
		$condProf['role_id'] = 3;
		
		$profiles_group = $this->Ticket->Profile->find('list',array(
			'conditions'=>array('group_id'=>$group),
			'fields'=>'id'
		));
		
		$cond['profile_id'] = $profiles_group;
		$condProf['Profile.id'] = $profiles_group;
		
		// todos menos los anulados
		$totals = $this->Ticket->find('first',array(
			'fields' => array('count(*) AS total','sum(amount) AS amounts'),
			'conditions' => array(
				 $cond,
				'ticket_status_id NOT' => array(4,6)
			),'recursive' => -1
		));
		
		//ganadores
		$winners = $this->Ticket->find('first',array(
			'fields' => array('count(*) AS total','sum(prize) AS prices'),
			'conditions' => array(
				$cond,
				'ticket_status_id' => 2
			),'recursive' => -1
		));
		
		//devueltos (amounts deberia ser igual q prices)
		$backs = $this->Ticket->find('first',array(
			'fields' => array('count(*) AS total','sum(amount) AS amounts','sum(prize) AS prices'),
			'conditions' => array(
				$cond,
				'ticket_status_id' => 4
			),'recursive' => -1
		));
		
		$this->Ticket->Profile->recursive = -1;
		
		$this->paginate['fields'] = array('Profile.name','Profile.id');
		
		$this->paginate['conditions'] = $condProf;
		
		$profiles = $this->paginate('Profile');
		
		$profiles_ids = array();
		
		foreach($profiles as $p){
			array_push($profiles_ids,$p['Profile']['id']);
		}
		
		$sales = ClassRegistry::init("ProfileSale");
		$wins = ClassRegistry::init("ProfileWin");
		$baks = ClassRegistry::init("ProfileBack");
		
		$profile_sales = $sales->find('all',array(
			'conditions' => array(
				'date BETWEEN ? AND ?'=>array($since,$until),
				'profile_id' => $profiles_ids
			),
			'fields' => array('profile_id',"SUM(totals) AS total","SUM(sold) AS sold"),
			'group' => 'profile_id'
		));
		
		$profile_wins = $wins->find('all',array(
			'conditions' => array(
				'date BETWEEN ? AND ?'=>array($since,$until),
				'profile_id' => $profiles_ids
			),
			'fields' => array('profile_id',"SUM(totals) AS total","SUM(wins) AS win"),
			'group' => 'profile_id'
		));
		
		$profile_backs = $baks->find('all',array(
			'conditions' => array(
				'date BETWEEN ? AND ?'=>array($since,$until),
				'profile_id' => $profiles_ids
			),
			'fields' => array('profile_id',"SUM(totals) AS total","SUM(backs) AS back"),
			'group' => 'profile_id'
		));
		
		$final_amounts = array();
		
		foreach($profile_sales as $sale){
			$final_amounts[$sale['ProfileSale']['profile_id']]['Sold']['tickets'] = $sale[0]['total'];
			$final_amounts[$sale['ProfileSale']['profile_id']]['Sold']['amount'] = $sale[0]['sold'];
		}
		
		foreach($profile_wins as $won){
			$final_amounts[$won['ProfileWin']['profile_id']]['Win']['tickets'] = $won[0]['total'];
			$final_amounts[$won['ProfileWin']['profile_id']]['Win']['amount'] = $won[0]['win'];
		}
		
		foreach($profile_backs as $bak){
			$final_amounts[$bak['ProfileBack']['profile_id']]['Back']['tickets'] = $bak[0]['total'];
			$final_amounts[$bak['ProfileBack']['profile_id']]['Back']['amount'] = $bak[0]['back'];
		}
		
		$group_name = $groupins->find('first',array(
			'conditions'=>array('Group.id'=>$group),
			'fields'=>'name'
		));
		
		$this->set('final_amounts',$final_amounts);
		
		$this->set('profiles',$profiles);
		
		$this->set('group_name',$group_name['Group']['name']);
		
		$this->set(compact('totals','winners','backs','since','until','group'));
		
		
	}
	
	function admin_sales_taquilla($since = null, $until = null){
		if($since == null){
			$since = date('Y-m-d');
			$until = date('Y-m-d');			
		}
		
		$cond['created BETWEEN ? AND ?'] = array("$since 00:00:00","$until 23:59:59");
		$cond['profile_id'] = $this->authUser['profile_id'];
		
		// todos menos los anulados y devueltos
		$totals = $this->Ticket->find('first',array(
			'fields' => array('count(*) AS total','sum(amount) AS amounts'),
			'conditions' => array(
				 $cond,
				'ticket_status_id NOT' => array(4,6)
			),'recursive' => -1
		));
		
		//ganadores
		/*$winners = $this->Ticket->find('first',array(
			'fields' => array('count(*) AS total','sum(prize) AS prices'),
			'conditions' => array(
				$cond,
				'ticket_status_id' => 2
			),'recursive' => -1
		));
		*/
		$opers = ClassRegistry::init('Operation');
		$mypayed = $opers->find('list',array(
			'conditions' => array(
				'profile_id' => $this->authUser['profile_id'],
				'date(created) BETWEEN ? AND ?' => array($since,$until),
				'operation_type_id'=>4,'metadata'=>'Pago'
			), 'fields' => 'model_id'
		));
		
		$winners = $this->Ticket->find('first',array(
			'conditions' => array('Ticket.id' => $mypayed),
			'fields' => array('count(*) AS total','sum(prize) AS prices'),
 			'recursive' => -1
 		));
		
		//devueltos (amounts deberia ser igual q prices)
		$backs = $this->Ticket->find('first',array(
			'fields' => array('count(*) AS total','sum(amount) AS amounts','sum(prize) AS prices'),
			'conditions' => array(
				$cond,
				'ticket_status_id' => 4
			),'recursive' => -1
		));
		
		//pr($totals); pr($winners); pr($backs); die();
		$this->set(compact('totals','winners','backs','since','until'));
	}
	
	function admin_update_statuses($since,$until){
		$firstTicket = $this->Ticket->find('first',array(
			'conditions'=>array('Ticket.created >=' => $since." 00:00:00"),
			'fields'=>'Ticket.id','order'=>array('Ticket.id ASC'),'recursive'=>-1
		));
		
		$lastTicket = $this->Ticket->find('first',array(
			'conditions'=>array('Ticket.created <=' => $until." 23:59:59"),
			'fields'=>'Ticket.id','order'=>array('Ticket.id DESC'),'recursive'=>-1
		));
		
		$tiks = $this->Ticket->get_update($firstTicket['Ticket']['id'],$lastTicket['Ticket']['id']);
		
		
		
		$this->set('tickets',$tiks);
		
		$this->set('first',$firstTicket['Ticket']['id']);
		$this->set('last',$lastTicket['Ticket']['id']);
	}
	
	function admin_outdated($date = null){
		if(!empty($this->data)){
			//pr($this->data); die();
			$outd = $this->Ticket->vencidos($this->data['Ticket']['date']);
			$this->Session->setFlash("$outd Tickets colocados como vencidos.");
			$this->redirect($this->referer());
		}
		
		if($date == null)
			$date = date("Y-m-d");
	
		$outdated = $this->Ticket->find('count',array(
			'conditions' => array(
				'date(created)' => $date,
				'ticket_status_id' => 2,
				'payed' => 0
			)
		));
		
		$this->set('outdated',$outdated);
		$this->set('date',$date);
	}
	
	function admin_anull_ticket($id = ""){
		if(!empty($this->data)){						
			$this->Ticket->updateAll(
				array('ticket_status_id'=>6),array('Ticket.id'=>$this->data['Ticket']['id'])
			);	
			$this->Session->setFlash("Ticket ANULADO");
			$this->redirect(array('action'=>'anull_ticket'));
		}
		
		$ticket = array();
		
		if($id != ""){
			$ticket = $this->Ticket->find('first',array(
				'conditions' => array('Ticket.id' => $id),
				'recursive' => 0,'fields'=>'Ticket.id'
			));
			
			if(!empty($ticket)){
				$this->Ticket->get_update($ticket['Ticket']['id'],$ticket['Ticket']['id']);
			}
			
			$ticket = $this->Ticket->find('first',array(
				'conditions' => array('Ticket.id' => $id),
				'recursive' => 0
			));
			
		}
		$this->set(compact('ticket','id'));
	}
	
	function admin_pay($id = ""){
		if(!empty($this->data)){ //solo para pagar		
			//pr($this->data);die();
			$ticket = $this->Ticket->find('first',array(
				'conditions' => array('Ticket.id' => $this->data['Ticket']['id']),
				'recursive' => -1,'fields'=>array('id','confirm')
			));
			
			if(empty($ticket)){
				$msg = "ERROR: Ticket no existe";
			}else{				
				$this->data['Ticket']['confirm'] = up($this->data['Ticket']['confirm']);
				if($ticket['Ticket']['confirm'] != $this->data['Ticket']['confirm']){
					$msg = "ERROR: Numero de Confirmacion invalido";
				}else{
					$msg = "Ticket DEBE SER PAGADO";
					$this->Ticket->updateAll(
						array('payed'=>1),array('Ticket.id'=>$ticket['Ticket']['id'])
					);
					$this->Operation->ins_op(4,$this->authUser['profile_id'],"Ticket",$ticket['Ticket']['id'],"Pago");
				}
			}
			$this->Session->setFlash($msg);
			$this->redirect(array('action'=>'pay'));
		}
		
		$ticket = array();
		
		if($id != ""){
			$ticket = $this->Ticket->find('first',array(
				'conditions' => array('Ticket.id' => $id),
				'recursive' => 0,'fields'=>'Ticket.id'
			));
			
			if(!empty($ticket)){
				$this->Ticket->get_update($ticket['Ticket']['id'],$ticket['Ticket']['id']);
			}
			
			$ticket = $this->Ticket->find('first',array(
				'conditions' => array('Ticket.id' => $id),
				'recursive' => 0
			));
			
		}
		$this->set(compact('ticket','id'));
	}
	
	function admin_report(){
		if(!empty($this->data)){
			$ticket = $this->Ticket->find('first',array(
				'conditions' => array('Ticket.id' => $this->data['Ticket']['number']),
				'fields' => array('confirm','ticket_status_id'),'recursive'=>-1
			));
			
			if(empty($ticket)){
				$msg = "ERROR: Ticket no existe";
			}else{
				$this->data['Ticket']['confirm'] = up($this->data['Ticket']['confirm']);
				if($ticket['Ticket']['confirm'] != $this->data['Ticket']['confirm']){
					$msg = "ERROR: Numero de Confirmacion invalido";
				}else{
					if($ticket['Ticket']['ticket_status_id'] == 5){
						$msg = "ERROR: Ticket YA ESTA REPORTADO";
					}elseif($ticket['Ticket']['ticket_status_id'] == 6){
						$msg = "ERROR: Ticket YA ESTA ANULADO";
					}else{
						$started = $this->Ticket->verify_started_ticket($this->data['Ticket']['number']);
						
						if(!$started){
							$msg = "ERROR: Alguno de los juegos ya empezo";
						}else{
							$msg = "Ticket REPORTADO";
							$this->Ticket->updateAll(
								array('ticket_status_id'=>5),array('Ticket.id'=>$this->data['Ticket']['number'])
							);	
						}						
					}					
				}
			}
			$this->Session->setFlash($msg);
			$this->redirect(array('action'=>'report'));
		}		
		
	}
	
	function admin_anull($since = null, $until = null){
		if($since == null){
			$since = date('Y-m-d');
			$until = date('Y-m-d');			
		}
		
		$reported = $this->Ticket->find('all',array(
			'conditions' => array(
				'ticket_status_id' => 5, 
				'created BETWEEN ? AND ?' => array("$since 00:00:00","$until 23:59:59")
			),
			'fields' => array('profile_id','Profile.name','count(*) AS total'),
			'group' => 'profile_id','recursive' => 0
		));
		//pr($reported); die();
		
		$this->set(compact('since','until','reported'));
	}
	
	function admin_set_anulled($profile,$since,$until){
		
		$this->Ticket->updateAll(
			array('ticket_status_id' => 6),
			array(
				'ticket_status_id' => 5, 
				'created BETWEEN ? AND ?' => array("$since 00:00:00","$until 23:59:59"),
				'profile_id' => $profile
			)
		);
		
		$this->Session->setFlash("Tickets anulados definitivamente");
		$this->redirect($this->referer());
	}
	
	function admin_anull_ticketOLD(){
		if(!empty($this->data)){
			$ticket = $this->Ticket->find('first',array(
				'conditions' => array('Ticket.id' => $this->data['Ticket']['number']),
				'fields' => array('ticket_status_id'),'recursive'=>-1
			));
			
			if(empty($ticket)){
				$msg = "ERROR: Ticket no existe";
			}else{
				if($ticket['Ticket']['ticket_status_id'] == 6){
					$msg = "ERROR: Ticket YA ESTA ANULADO";
				}else{					
					$msg = "Ticket ANULADO";
					$this->Ticket->updateAll(
						array('ticket_status_id'=>6),array('Ticket.id'=>$this->data['Ticket']['number'])
					);	
										
				}
			}
			$this->Session->setFlash($msg);
			$this->redirect(array('action'=>'anull_ticket'));
		}		
		
	}
	
	function _validate_add($data){
		
		$error = "";
		
		if(empty($data['Odd'])){
			$error = "Seleccione minimo un parlay";
		}
		
		if($error == ""){
			//limites
			$error = $this->Ticket->valid_limits($this->authUser['profile_id'],$data['Ticket']['amount'],$data['Odds']);
		}
		if($error == ""){
			//empezados
			$error = $this->Ticket->valid_started($data['Times']);
		}
		/*if($error == ""){
			//repetidos
			$error = $this->Ticket->valid_money_nba($data['Games']);
		}*/
		
		return $error;
		
	}
	
}
?>