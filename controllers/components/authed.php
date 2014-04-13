<?php 
/**
 * Authed Component
 * 
 * Custom scope rules for AuthComponent
 * 
 * The purpose of this extension is to add a flexible rule setup to 
 * the login process. You could compare their setup to model validation.
 * The rules are applied just before the usual auth is issued. 
 * 
 * Note: This extension overwrites Auth::login()
 * 
 * Example: 
 * 
 *	$this->Authed->loginError = __("Wrong password / username. Please try again.", true);
 *	$this->Authed->authError = __("Sorry, you are not authorized. Please log in first.", true);
 * 
 *	$this->Authed->userScopeRules = array(
 *		'is_banned' => array(
 *			'expected' => 0, 
 *			'message' => __("You are banned from this service. Sorry.", true)
 *		),
 *		'is_validated' => array(
 *			'expected' => 1,
 *			'message' => __("Your account is not active yet. Click the Link in our Mail.", true)
 *		)
 *	);
 *
 * @author       Kjell Bublitz <m3nt0r.de@gmail.com>
 * @version      1.0
 * @package      app
 * @subpackage   app.app.controller.components
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 * 
 * PHP versions 4 and 5
 */
App::import('component', 'Auth');

/**
 * Authed Component 
 * 
 * @package		app
 * @subpackage	app.app.controller.components
 */
class AuthedComponent extends AuthComponent
{ 
	/**
	 * Rules
	 *
	 * @var array
	 */
	var $userScopeRules = array();
	
	/**
	 * Check variable
	 *
	 * @var boolean
	 */
	var $_scopeRuleError = false;
	
	/**
	 * Walk through all available rules and compare with row data.
	 * Break on mismatch and reset loginError to rule.message
	 *
	 * @param array $data UserModel row
	 * @return boolean True on login success, false on failure
	 * @access public
	 */
	function hasScopeRuleMismatch($user) {
		foreach ($this->userScopeRules as $field => $rule) {
			if ($user[$field] != $rule['expected']) {
				$this->loginError = $rule['message'];
				$this->_scopeRuleError = true;
				break;
			}
		}
		return $this->_scopeRuleError;
	}
	
	/**
	 * Overwrites Auth::login()
	 *
	 * Basicly the same method, but after identify() was successful call
	 * the above hasScopeRuleMismatch passing $user.
	 * 
	 * Only if this method returns false we will continue the login process.
	 * 
	 * @param mixed $data
	 * @return boolean True on login success, false on failure
	 * @access public
	 */
	function login($data = null) { 
		$this->__setDefaults();
		$this->_loggedIn = false;
		//$operInstance = ClassRegistry::init('Operation');
		if (empty($data)) {
			$data = $this->data;
		}
		
		//pr($data); die();
		if ($user = $this->identify($data)) {
			if (!$this->hasScopeRuleMismatch($user)) {				
				
				$this->_loggedIn = true;
				$userInstance = ClassRegistry::init('User');
				$profileInstance = ClassRegistry::init('Profile');
				$profileData = $profileInstance->find('first', array(
					'conditions'=>array('user_id'=>$user['id']),
					'recursive' => -1
				));
				
				$user['profile_id'] = $profileData['Profile']['id'];
				$user['role_id'] = $profileData['Profile']['role_id'];
				$user['profile_name'] = $profileData['Profile']['name'];
				unset($user['username']);
				unset($user['email']);
				unset($user['secret_question']);
				unset($user['created']);
				unset($user['secret_answer']);
				unset($user['secret_question']);
				unset($user['enabled']);
				unset($user['logged']);
				unset($user['last_login']);
				unset($user['ip_last_login']);
				
				$userInstance->logon($user['id'],$_SERVER['REMOTE_ADDR']);
				$this->Session->write($this->sessionKey, $user);
				$this->Session->setFlash("Bienvenido ".$user['profile_name']);
			
			}
		}
		return $this->_loggedIn;
	}
	function logout(){
		//$operInstance = ClassRegistry::init('Operation');
		$userInstance = ClassRegistry::init('User');
		//$operInstance->logout($this->user('id'),0,"users", "", null,$_SERVER['REMOTE_ADDR']);
		$userInstance->logoff($this->user('id'));
		return parent::logout();
	}
	
		
	
	/**
	 * Returns true if the login error was scope rules related.
	 * Maybe someone needs this to go on with.
	 * 
	 * @return boolean
	 */
	function wasScopeRuleError() {
		return $this->_scopeRuleError;
	}
	
}
?>