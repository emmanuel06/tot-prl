<?php 
//pr($games);
?>
<table id="odds_table" border="1px">
	<?php
	$actual_league = null;
	foreach($games as $game){
		
		$away = $game['Team'][0];
		$home = $game['Team'][1];
		
		$def_index = $sports[$game['League']['sport_id']];
		$oth_index = 2;
		if($def_index == 2)
			$oth_index = 1;	
			
		if($actual_league != $game['League']['id']){
			if($format == "mlb")
				echo $oddsheet->mlb_title($game['League']['name'],true);
			elseif($format == "mlbse")
				echo $oddsheet->mlb_title($game['League']['name'],false);
			else
				echo $oddsheet->set_title($game['League']['sport_id'],$game['League']['name'],$sports);	
		}
		
		$torowspan = 2;
		if($draws[$game['League']['sport_id']] == 1)
			$torowspan = 3;
			
			
		//FUNCIONES	
			
		//una pa traer un juego, como parametro el array $game
			
			
			
			
	?>
		<!-- A  W  A  Y    P  A  R  T   -->
		
		<tr>
			<td rowspan='<?php echo $torowspan ?>'>
				<?php  echo $dtime->time_to_human($game['Game']['time'])?>
			</td>
			<td><?php echo $away['GamesTeam']['reference'] ?></td>
			<td><?php echo $away['name'] ?></td>
			<td><?php echo $away['GamesTeam']['metadata'] ?></td>
			<td> 80 -69 </td>
			<?php 
			if(empty($game['Odds'])){
				echo $oddsheet->set_no_odds(10);
			}else{
				
			if(empty($game['Odds'][1]))
					echo $oddsheet->set_no_odds(5);
				else{
					$finals = $game['Odds'][1];
					// M  O  D  O     D  E  F .   F  I  N  A  L
				
					if(empty($finals[$def_index])){
						echo $oddsheet->set_no_odds(1);
					}else{
						
					?>
						<td>
							<?php 
							echo $oddsheet->sod($finals[$def_index][1]['odd']);
							echo $oddsheet->get_factor($finals[$def_index][1]['factor']);
							?>
						</td>
						<?php	
					}
					
					// A  L  T  A  B  A  J  A   F  I  N  A  L
				
					if(empty($finals[3])){
					?>
						<td rowspan="<?php echo $torowspan ?>">&nbsp;</td>
					<?php 
					}else{
						$abfs = $finals[3];
						?>
						<td rowspan="<?php echo $torowspan ?>">
							<?php 
							echo $abfs[4]['factor'];
							echo "<br />A: ".$abfs[4]['odd'];
							echo "<br />B: ".$abfs[5]['odd'];
							?>
						</td>
						<?php 
					}
					
					// M  O  D  O    A  L  T .   F  I  N  A  L
					
					if(empty($finals[$oth_index]))
						echo $oddsheet->set_no_odds(2);
					else{
					?>
						<td><?php echo "1".$away['GamesTeam']['reference'] ?></td>
						<td>
							<?php 
							echo $oddsheet->sod($finals[$oth_index][1]['odd']);
							echo $oddsheet->get_factor($finals[$oth_index][1]['factor']);
							?>
						</td>
					<?php
					}
				}
				
				//  M  I  T  A  D  E  S
				
				if(empty($game['Odds'][0]))
					echo $oddsheet->set_no_odds(5);
				else{
					$halfs = $game['Odds'][0];
					// M  O  D  O     D  E  F .   M  I  T  A  D
				
					if(empty($halfs[$def_index])){
						echo $oddsheet->set_no_odds(2);
					}else{
						
					?>
						<td><?php echo "5".$away['GamesTeam']['reference'] ?></td>
						<td>
							<?php 
							echo $oddsheet->sod($halfs[$def_index][1]['odd']);
							echo $oddsheet->get_factor($halfs[$def_index][1]['factor']);
							?>
						</td>
					<?php	
					}
					
					// A  L  T  A  B  A  J  A     M  I  T  A  D
				
					if(empty($halfs[3])){
					?>
						<td rowspan="<?php echo $torowspan ?>">&nbsp;</td>
					<?php 
					}else{
						$abhs = $halfs[3];
						?>
						<td rowspan="<?php echo $torowspan ?>">
							<?php 
							echo $abhs[4]['factor'];
							echo "<br />A: ".$abhs[4]['odd'];
							echo "<br />B: ".$abhs[5]['odd'];
							?>
						</td>
						<?php 
					}
					
					// M  O  D  O    A  L  T .   M  I  T  A  D
					
					if(empty($halfs[$oth_index]))
						echo $oddsheet->set_no_odds(2);
					else{
					?>
						<td><?php echo "6".$away['GamesTeam']['reference'] ?></td>
						<td>
							<?php 
							echo $oddsheet->sod($halfs[$oth_index][1]['odd']);
							echo $oddsheet->get_factor($halfs[$oth_index][1]['factor']);
							?>
						</td>
					<?php
					}
				}				
			}			
			?>
		</tr>
		<?php
		
		//  D  R  A  W  S
		
		if($draws[$game['League']['sport_id']] == 1){
		?>	
			<tr>
				<td>EMPATE</td>
				<?php 
				if(!empty($finals[4][3])){
				?>
					<td><?php echo "E".$away['GamesTeam']['reference'] ?></td>
					<td><?php echo $finals[4][3]['odd']; ?></td>
				<?php 
				}else{
					echo $oddsheet->set_no_odds(2);
				}
				?>
				<td colspan="2">&nbsp;</td>
				<?php 
				if(!empty($halfs[4][3])){
				?>
					<td><?php echo "E5".$away['GamesTeam']['reference'] ?></td>
					<td><?php echo $halfs[4][3]['odd']; ?></td>
				<?php 
				}else{
					echo $oddsheet->set_no_odds(2);
				}
				?>
				<td colspan="2">&nbsp;</td>
			</tr>
		<?php 
		}		
		?>
		
		
		<!-- H  O  M  E    P  A  R  T   -->
		
		
		<tr>
			<td><?php echo $home['GamesTeam']['reference'] ?></td>
			<td><?php echo $home['name'] ?></td>
			<td><?php echo $home['GamesTeam']['metadata'] ?></td>
			<td> 69 - 80 </td>
			<?php 
			if(empty($game['Odds'])){
				echo $oddsheet->set_no_odds(10);
			}else{
				
				if(empty($game['Odds'][1]))
					echo $oddsheet->set_no_odds(4);
				else{
					$finals = $game['Odds'][1];
					// M  O  D  O     D  E  F .   F  I  N  A  L
				
					if(empty($finals[$def_index])){
						echo $oddsheet->set_no_odds(1);
					}else{
						
					?>
						<td>
							<?php 
							echo $oddsheet->sod($finals[$def_index][2]['odd']);
							echo $oddsheet->get_factor($finals[$def_index][2]['factor']);
							?>
						</td>
						<?php	
					}
						
					if(empty($finals[$oth_index]))
						echo $oddsheet->set_no_odds(2);
					else{
					?>
						<td><?php echo "1".$home['GamesTeam']['reference'] ?></td>
						<td>
							<?php 
							echo $oddsheet->sod($finals[$oth_index][2]['odd']);
							echo $oddsheet->get_factor($finals[$oth_index][2]['factor']);
							?>
						</td>
					<?php
					}
				}
				
				if(empty($game['Odds'][0]))
					echo $oddsheet->set_no_odds(5);
				else{
					$halfs = $game['Odds'][0];
					// M  O  D  O     D  E  F .   M  I  T  A  D
				
					if(empty($halfs[$def_index])){
						echo $oddsheet->set_no_odds(2);
					}else{
						
					?>
						<td><?php echo "5".$home['GamesTeam']['reference'] ?></td>
						<td>
							<?php 
							echo $oddsheet->sod($halfs[$def_index][2]['odd']);
							echo $oddsheet->get_factor($halfs[$def_index][2]['factor']);
							?>
						</td>
					<?php	
					}
					
					// M  O  D  O    A  L  T .   M  I  T  A  D
					
					if(empty($halfs[$oth_index]))
						echo $oddsheet->set_no_odds(2);
					else{
					?>
						<td><?php echo "6".$home['GamesTeam']['reference'] ?></td>
						<td>
							<?php 
							echo $oddsheet->sod($halfs[$oth_index][2]['odd']);
							echo $oddsheet->get_factor($halfs[$oth_index][2]['factor']);
							?>
						</td>
					<?php
					}
				}
			}
			?>
		</tr>
	<?php 
		$actual_league = $game['League']['id'];
	}
	?>
</table>
