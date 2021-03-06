<?php 
class AppController extends Controller {
    var $components = array('Authed','Session'); //,'RequestHandler'
	var $helpers = array('Html','Form','Session','Javascript','Dtime');
	
	function beforeFilter(){
		
		$this->Authed->fields         = array('username' => 'username', 'password' => 'password');
		$this->Authed->loginAction    = array('controller' => 'users', 'action' => 'login');
        $this->Authed->logoutRedirect = array('controller' => 'users', 'action' => 'login');
        $this->Authed->loginRedirect  = array('controller' => 'pages', 'action' => 'welcome');
        $this->Authed->loginError     = 'Usuario/password no existe.';
		$this->Authed->authError      = 'Acceso denegado.';
        //$this->Authed->allow('login','logout','display');
        $this->Authed->authorize = 'controller';

        $this->Authed->userScopeRules = array(
			'enable' => array(
                        'expected' => 1,
                        'message'  => "Lo sentimos, su usuario esta bloqueado."
			)
		);

        $this->disableCache();

        $logdataUser     = $this->Authed->user();
		$this->loginData = $logdataUser['User'];

        if($this->loginData != null){
			if (!($this->user_enable())){
				$this->Authed->logout();
			}		

            $this->set("loginData", $this->loginData);
            $this->set("menuActions", $this->getMenu());
        }else{
            $this->layout = 'noauth';
        }

	}

    function isAdm()
    {
		return ($this->Authed->user('role_id') == ROLE_ADM);
	}

	function isTaq()
    {
		return ($this->Authed->user('role_id') == ROLE_TAQ);
	}
	
	function user_enable(){
		$userInstance = ClassRegistry::init('User');
		$userInstance->recursive = -1;
		$u = $userInstance->find("first",array(
			"conditions"=>array("User.id"=>$this->loginData['id']),
			'fields'=>'enable'
		));
		$ret = true;
		
		if($u['User']['enable'] == 0){
			$ret = false;
		}
		
		return $ret;
	}

    function getMenu () {
        if ($this->isAdm() === TRUE)
            return $this->admMenu();

        if ($this->isTaq() === TRUE)
            return $this->taqMenu();
    }

    function admMenu () {

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