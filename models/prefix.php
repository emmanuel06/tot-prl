<?php
class Prefix extends AppModel {

	var $name = 'Prefix';
	
	function define_odd($prefix,$team_type_id,$default){
		
		$prefixes_normal = array('0','1','5','6','8');
		$conds['prefix'] = $prefix;
		
		if(in_array($prefix,$prefixes_normal)){
			$conds['def'] = $default;
		}
		
		$details = $this->find('first',array(
			'conditions' => $conds,
			'fields' => array(
				'odd_type_id','team_type_id','factor','final','prefix'
			)
		));
		
		//echo "pref: $prefix -def: $default -ttype $team_type_id"; pr($details);pr($conds); die();
		$ret = array();
		if(!empty($details)){
			if($details['Prefix']['team_type_id'] == 0)
				$details['Prefix']['team_type_id'] = $team_type_id;
		
			$ret = $details['Prefix'];
		}
		
		return $ret;
	}
	
}	
?>