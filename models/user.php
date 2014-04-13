<?php
class User extends AppModel {

	var $name = 'User';
	
	function logon($id,$ipaddress){
		$this->save(array('id'=>$id,'logged'=> 1,'last_login'=>date('Y-m-d h:i:s'),'ip_last_login'=>$ipaddress));
	}
	 
	function logoff($id){
		if(!empty($id)){
			$this->updateAll(array('logged' => 0),array('User.id' => $id));
		}
	}
}
?>