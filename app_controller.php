<?php 
class AppController extends Controller {
    var $components = array('Authed','Session','RequestHandler');
	var $helpers = array('Html','Form','Session','Javascript','Dtime');
	
	var $paginate = array(
		'limit' => 30
	);
	
	
	function beforeFilter(){
		
		$this->Authed->fields = array('username' => 'username', 'password' => 'password');
		$this->Authed->loginAction = array('controller' => 'users', 'action' => 'login','admin'=>0);
		$this->Authed->logoutRedirect = array('controller' => 'pages');
		$this->Authed->authorize = 'controller';
		$this->Authed->allow('display');
		$this->Authed->loginError = 'Combinacion login/password no valida';
		$this->Authed->authError = 'No tiene permitido ingresar';
		
		$this->Authed->userScopeRules = array(
			'enable' => array(
				'expected' => 1, 
				'message' => "Lo sentimos, su usuario esta bloqueado."
			)
		);
		
		$this->authUsr = $this->Authed->user();
		
		$this->authUser = $this->authUsr['User'];
		
		if($this->authUser != null){
			
			if(!($this->user_enable())){
				$this->Authed->logout();
			}		
			
			$this->set("authUser", $this->authUser);

            $this->set("menuActions", $this->getMenu($this->authUser['role_id']));
        }else{
            $this->layout = 'noauth';
        }



	}
	
	function isAuthorized() {
		return true;
	}
	
	function isAdmin(){
		return ($this->Authed->user('role_id') == ROLE_ADMIN);
	}
	function isSubAdm(){
		return ($this->Authed->user('role_id') == ROLE_SUBADM);
	}
	function isTaquilla(){
		return ($this->Authed->user('role_id') == ROLE_TAQUILLA);
	}
	
	function user_enable(){
		$userInstance = ClassRegistry::init('User');
		$userInstance->recursive = -1;
		$u = $userInstance->find("first",array(
			"conditions"=>array("User.id"=>$this->authUser['id']),
			'fields'=>'enable'
		));
		$ret = true;
		
		if($u['User']['enable'] == 0){
			$ret = false;
		}
		
		return $ret;
	}

    function getMenu () {
        if ($this->isAdmin() === TRUE)
            return $this->getAdminMenu();
    }

    function getAdminMenu () {

        $actions = array(

            array(
                'title'      => 'JUEGOS',
                'controller' => '',
                'action'     => '',
                'Submenus'   => array(
                                    array (
                                        'title'      => 'Adm. Juegos',
                                        'controller' => 'games',
                                        'action'     => 'index'
                                    )
                                    ,
                                    array (
                                        'title'      => 'H. Logros',
                                        'controller' => 'games',
                                        'action'     => 'index'
                                    )
                                )
            )
            ,
            array(
                'title'      => 'VENTAS',
                'controller' => '',
                'action'     => '',
                'Submenus'   => array(
                                    array (
                                        'title'      => 'Tickets',
                                        'controller' => 'tickets',
                                        'action'     => 'index'
                                    )
                                    ,
                                    array (
                                        'title'      => 'Totales',
                                        'controller' => 'tickets',
                                        'action'     => 'sales'
                                    )
                )
            )
            ,
            array(
                'title'      => 'USUARIOS',
                'controller' => 'users',
                'action'     => 'index',
                'Submenus'   => array()
            )
            ,
            array(
                'title'      => 'DATOS',
                'controller' => '',
                'action'     => '',
                'Submenus'   => array(
                                    array (
                                        'title'      => 'Deportes',
                                        'controller' => 'sports',
                                        'action'     => 'index'
                                    )
                                    ,
                                    array (
                                        'title'      => 'Ligas',
                                        'controller' => 'leagues',
                                        'action'     => 'index'
                                    )
                                    ,
                                    array (
                                        'title'      => 'Equipos',
                                        'controller' => 'teams',
                                        'action'     => 'index'
                                    )
                                    ,
                                    array (
                                        'title'      => 'Pitchers',
                                        'controller' => 'pitchers',
                                        'action'     => 'index'
                                    )
                                )
            )
        );

        return $actions;

    }
	
}
?>