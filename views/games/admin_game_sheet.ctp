<?php //pr($games); ?>
<table id="odds_table" border="1">
	<?php
	$actual_league = null;
	foreach($games as $game){
		
		$def_index = $sports[$game['League']['sport_id']];
		$oth_index = 2;
		if($def_index == 2)
			$oth_index = 1;	
			
		//hacer esto en una funcion
		if($oddsheet->check_title($game['League']['id'],$actual_league)){
			if($format == "mlb")
				$oddsheet->mlb_title($game['League']['name'],true);
			elseif($format == "mlbse")
				$oddsheet->mlb_title($game['League']['name'],false);
			else
				$oddsheet->set_title($game['League']['sport_id'],$game['League']['name'],$sports);
		}
		
		$draw = false;
		if($draws[$game['League']['sport_id']] == 1)
			$draw = true;
			
		//FUNCIONES				
			
		$oddsheet->game_row($game,$draw,$def_index,$oth_index,$format);
		 
		
		$actual_league = $game['League']['id'];
	}
	?>
</table>

<?php //pr($results) ?>
