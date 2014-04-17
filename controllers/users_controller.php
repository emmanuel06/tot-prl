<?php
class UsersController extends AppController {

	var $name = 'Users';
	

	function beforeFilter(){
		parent::beforeFilter();	
		
		$this->Authed->allow(array("login","logout","get_hour"));
	}
	
	function isAuthorized(){

        $actions = array(
            ROLE_ADM => array(
                'index',
                'add',
                'edit'
            )
            ,
            ROLE_TAQ => array(
                'myview'
            )
        );

        //pr($actions); echo $this->loginData['role_id'];

        if(isset($actions[$this->loginData['role_id']])){
            if(in_array($this->action,$actions[$this->loginData['role_id']])) {
                return true;
            }
        }


        return parent::isAuthorized();
	}

    /*
    function set_enable($id, $status) {
        $newstat = 1;
        if($status == 1){
            $newstat = 0;
        }

        $this->User->updateAll(array('enable'=>$newstat),array('User.id'=>$id));

        $this->redirect($this->referer());

    }

     */

    /** ADM functions */

    function index ()
    {
        $this->paginate['fields']     = array('id','name','username','role_id','group_id','enable','created');
        $this->paginate['conditions'] = array();
        $this->paginate['order']      = array('role_id' => 'ASC', 'name' => 'ASC');
        $this->paginate['limit']      = 30;

        $this->set('users',$this->paginate('User'));
    }

    /** TAQ functions */

    function myview ()
    {

    }

    /** Common functions */

    function login(){

    }

	function logout(){
		$this->redirect($this->Authed->logout());
	}
		
	function get_hour() {
        $this->layout = 'ajax';
    }
}
?>