<?php
class oddsheetHelper extends AppHelper {
	
	var $helpers = array('Dtime');
	
	//coloca signo + a los logros hembra
	function sod($cypher){
		if($cypher > 0){
			$signed = "+$cypher";
		}else{
			$signed = $cypher;
		}
		return $signed;
	}

	function check_title($now_league,$old_league){
		$val = false;
		if($now_league != $old_league)
			$val = true;
			
		return $val;
	}	
	
	function set_title($sport_id,$league_name,$sports){
		//EL LEAGUE ID se usa para otras propiedades unicas
		$def = "COMPLETO";
		$oth = "RUNLINE COMPLETO";
		$mit = "MITAD";
		$rmi = "RUNLINE MITAD";
		$clp = 2;
		$oclp = 3;
		$mclp = 3;
		$omclp = 4;
		
		$ordered = "
			<th>Logro</th>
			<th>A/B</th>
			<th>Cod</th>
			<th>RLine</th>
			<th>Logro</th>
			<th>COD</th>
			<th>Logro</th>
			<th>A/B</th>
			<th>COD</th>
			<th>Rline</th>
			<th>Logro</th>";
		
		if($sports[$sport_id] == 2){
			$def = "SPREAD FINAL";
			$oth = "MONEYLINE FINAL";
			$mit = "SPREAD MITAD";
			$rmi = "MONEYLINE MITAD";
			$clp = 3;
			$oclp = 2;
			$mclp = 4;
			$omclp = 2;
		
			$ordered = "
				<th>RLine</th>
				<th>Logro</th>
				<th>A/B</th>
				<th>Cod</th>
				<th>Logro</th>
				<th>COD</th>
				<th>Rline</th>
				<th>Logro</th>
				<th>A/B</th>
				<th>COD</th>
				<th>Logro</th>";
		}
		
		$toshow = "<tr>
			<th colspan='3'><span class='leaguename'>$league_name</span></th>
			<th colspan='$clp'>$def</th>
			<th colspan='$oclp'>$oth</th>
			<th colspan='$mclp'>$mit</th>
			<th colspan='$omclp'>$rmi</th>
		</tr>
		<tr>
			<th>Hora</th>
			<th>Cod</th>
			<th>Equipos</th>
			$ordered
		</tr>";
		
		echo $toshow;
	}
	
	function mlb_title($league_name,$exotics = true){
		$rwsp = "";
		if($exotics){
			$rwsp = " rowspan='2'";
		}
		
		$title ="<tr>
			<th colspan='5'$rwsp><span class='leaguename'>$league_name</span></th>
			<th colspan='2'$rwsp> </th>
			<th colspan='3'$rwsp>RUNLINE 9 INNINGS</th>
			<th colspan='3'$rwsp>APUESTA 5 INNINGS</th>
			<th colspan='3'$rwsp>RUNLINE 5 INNINGS</th>";
		
		if($exotics){
			$title .= "<th colspan='9'>APUESTAS EXOTICAS</th>";
		}
		
		$title .= "</tr>";
		
		if($exotics){
			$title .= "<tr>
				<th colspan='2'>SI/NO</th>
				<th colspan='2'>Anota 1&ordm;</th>
				<th colspan='2'>H+C+E</th>
				<th colspan='3'>SUPER RUNLINE</th>
			</tr>";
		}
		
		$title .= "<tr>
			<th>Hora</th>
			<th>Cod</th>
			<th style='width:200px'>Equipos</th>
			<th style='width:220px'>Lanz. Probables</th>
			<th style='width:160px'>W-L</th>
			<th>Logro</th>
			<th>AB</th>
			<th>Cod</th>
			<th>Rline</th>
			<th>Logro</th>
			<th>Cod</th>
			<th>Logro</th>
			<th>AB</th>
			<th>Cod</th>
			<th>Rline</th>
			<th>Logro</th>";

			
		if($exotics){
			$title .= "<th>SI/NO</th>
			<th>Logro</th>
			<th>1&ordm;</th>
			<th>Logro</th>
			<th>O/U</th>
			<th>A/B</th>
			<th>Cod</th>
			<th>Rline</th>
			<th>Logro</td>";
		}			
			
		$title .= "</tr>";
		
		echo $title;
	
	}
	
	function get_factor($index){
		if($index != 0){
			$ret = "<br />(".$this->sod($index).")";
		}else{
			$ret = "";
		}
		return $ret;
	}
	
	function set_no_odds($cols){
		$vacios = "";
		for($i = 0; $i < $cols; $i ++){
			$vacios .= $this->td("&nbsp;");
		}
		return $vacios;
	}
	
	function game_row($game,$draw,$def,$oth,$format){	
		$away = $game['Team'][0];
		$home = $game['Team'][1];
		
		$torowspan = 2;
		if($draw)
			$torowspan = 3;

		$exos = "";
		$wl_pit = "";
		$abod = $this->show_ab_odds($game['League']['sport_id']);
		
		if($format == "mlb"){
			$exos = $this->mlb_exos($game,1,$away['GamesTeam']['reference']);
			$wl_pit = $this->td($away['GamesTeam']['metadata']).$this->td($away['win_loses']);
		}
			
		$this->tr(
			$this->td($this->Dtime->time_to_human($game['Game']['time']),$torowspan,2).
			$this->td($away['GamesTeam']['reference']).
			$this->td($this->get_name($away)).
			$wl_pit.
			$this->odds_row($game,1,$away['GamesTeam']['reference'],$torowspan,$def,$oth,$abod).
			$exos
		);
		
		if($draw){
			$this->tr(
				$this->draw_line($game['Odds'],$away['GamesTeam']['reference'])
			);
		}
		
		if($format == "mlb"){
			$exos = $this->mlb_exos($game,2,$home['GamesTeam']['reference']);
			$wl_pit = $this->td($home['GamesTeam']['metadata'],0,2).$this->td($home['win_loses'],0,2);
		}
		
		$this->tr(
			$this->td($home['GamesTeam']['reference'],0,2).
			$this->td($this->get_name($home),0,2).
			$wl_pit.
			$this->odds_row($game,2,$home['GamesTeam']['reference'],$torowspan,$def,$oth,$abod).
			$exos
		);
		
	}
	
	function odds_row($odds,$team,$ref,$trspan,$def,$oth,$abodd){
		if(empty($odds['Odds'])){
			$rt = $this->set_no_odds(11);
		}else{
			$rt = "";
			
			$param1 = 0;
			$param2 = $team;
			
			if($def == 1){
				//moneylines
				$rt .= $this->moneyl($odds['Odds'],1,$team);
				
				//ALTA BAJA
				if($team == 1)
					$rt .= $this->hi_lo($odds['Odds'],1,$trspan,$abodd);	
				
				//runlines
				$rt .= $this->td("1$ref",$param1,$param2);
				
				$rt .= $this->runl($odds['Odds'],1,$team);
					
				//MITAD
				$rt .= $this->td("5$ref",$param1,$param2);
				
				$rt.= $this->moneyl($odds['Odds'],0,$team);
				
				//ALTABAJA MITAD
				if($team == 1)
					$rt .= $this->hi_lo($odds['Odds'],0,$trspan,$abodd);
					
				//MITAD rl
				$rt .= $this->td("6$ref",$param1,$param2);
				
				$rt .= $this->runl($odds['Odds'],0,$team);	
					
			}else{
				//runlines
				$rt .= $this->runl($odds['Odds'],1,$team);
				
				if($team == 1)
					$rt .= $this->hi_lo($odds['Odds'],1,$trspan,$abodd);
				
				//moneylines
				$rt .= $this->td("1$ref",$param1,$param2);
				$rt .= $this->moneyl($odds['Odds'],1,$team);
				
				//MITAD
				$rt .= $this->td("5$ref",$param1,$param2);
				
				$rt .= $this->runl($odds['Odds'],0,$team);
				
				//ALTA BAJA MITAD
				if($team == 1)
					$rt .= $this->hi_lo($odds['Odds'],0,$trspan,$abodd);
				
				//MITAD ml
				$rt .= $this->td("6$ref",$param1,$param2);
				
				$rt .= $this->moneyl($odds['Odds'],1,$team);
								
			}
		}
		
		return $rt;
	}
	
	function get_name($team){
		if($team['alt_name'] != ""){
			return $team['alt_name'];
		}else{
			return $team['name'];
		}
	}
	
	function moneyl($odds,$final,$team){
		if(empty($odds[$final][1])){
			$ml = $this->set_no_odds(1);
		}else{
			if($team == 2)
				$ml = $this->td($this->sod($odds[$final][1][$team]['odd']),0,2);
			else
				$ml = $this->td($this->sod($odds[$final][1][$team]['odd']));
		}
		
		return $ml;
	}
	
	function runl($odds,$final,$team){
		if(empty($odds[$final][2])){
			$rl = $this->set_no_odds(2);
		}else{
			$p1 = 0; $p2 = $team;				
			
			$rl = $this->td($this->sod($odds[$final][2][$team]['factor']),$p1,$p2);
			$rl .= $this->td($this->sod($odds[$final][2][$team]['odd']),$p1,$p2);
		}
		
		return $rl;
	}
	
	function draw_line($odds,$ref){
		$rt = $this->td("E$ref");
		$rt .= $this->td("EMPATE");
		
		if(!empty($odds[1][4])){
			$rt .= $this->td($odds[1][4][3]['odd']);	
		}else{
			$rt .= $this->set_no_odds(1);
		}
		
		$rt .= $this->set_no_odds(3);
		$rt .= $this->td("E5$ref");
		if(!empty($odds[0][4])){
			$rt .= $this->td($game['Odds'][0][4][3]['odd']);
		}else{
			$rt .= $this->set_no_odds(1);
		}
		$rt .= $this->set_no_odds(3);
		
		return $rt;
	}
	
	function hi_lo($odds,$final,$trspan,$show_odds){
		if(empty($odds[$final][3])){
			$rt = $this->td("&nbsp;",$trspan);
		}else{
			$toshow = $odds[$final][3][4]['factor'];
			if($show_odds){
				$toshow .= "<br />A: ".$odds[$final][3][4]['odd']."<br />B: ".$odds[$final][3][5]['odd'];
			}
			$rt = $this->td($toshow,$trspan,2);		
		}
		
		return $rt;
	}
	
	function mlb_exos($odds,$row,$ref){
		if(empty($odds['Odds'][1][5])){
			$ex = $this->set_no_odds(2);
		}else{
			if($row == 1){
				$ex = $this->td("S"); 
				$ex .= $this->td($this->sod($odds['Odds'][1][5][6]['odd']));
				$ex .= $this->td("V");
				$ex .= $this->td($this->sod($odds['Odds'][1][6][1]['odd']));
				$ex .= $this->td("O");
				$ex .= $this->td($this->sod($odds['Odds'][1][7][8]['factor']),2,2);
				$ex .= $this->td("2$ref");
				if(!empty($odds['Odds'][1][8])){
					$ex .= $this->td($this->sod($odds['Odds'][1][8][1]['factor']));
					$ex .= $this->td($this->sod($odds['Odds'][1][8][1]['odd']));
				}else
					$ex .= "<td> </td><td> </td>";
				
			}else{
				$ex = $this->td("N",0,2);
				$ex .= $this->td($this->sod($odds['Odds'][1][5][7]['odd']),0,2);
				$ex .= $this->td("H",0,2);
				$ex .= $this->td($this->sod($odds['Odds'][1][6][2]['odd']),0,2);
				$ex .= $this->td("U",0,2);
				$ex .= $this->td("2$ref",0,2);
				if(!empty($odds['Odds'][1][8])){
					$ex .= $this->td($this->sod($odds['Odds'][1][8][2]['factor']),0,2);
					$ex .= $this->td($this->sod($odds['Odds'][1][8][2]['odd']),0,2);
				}else
					$ex .= "<td> </td><td> </td>";
				
			}	
		}		
		return $ex;
	}
	
	function tr($val){
		echo "<tr>\r\n$val</tr>\r\n";
	}
	
	function td($val,$rp = 0,$typ = 1){
		$ros = "";
		
		if($rp != 0)
			$ros = " rowspan='$rp'";

		$cls = "class='away_row'";
		if($typ == 2){
			$cls = "class='home_row'";
		}
		
		return "\t<td$ros $cls>$val</td>\r\n";
	}
	
	function th($val){
		echo "<th>$val</th>";
	}
	
	function show_ab_odds($league){
		$rt = false;
		switch ($league) {
		    case 2:
		        $rt = true;
		        break;
		}

		return $rt;
	}
	
	//array con valores de medidas
	var $measures = array(
		'long' => "950px",
		'short' => "700px"
	);
	
}
?>