<?php //pr($details); ?>
<table style="font-size:80%">
	<tr>
		<th>Ref</th>
		<th>Logro</th>
		<th>Equipo</th>
		<th>Estado</th>
	</tr>
	<?php
	foreach ($details as $detail){
		$d = $detail['TicketDetail'];
		
		$lab = $d['type_name'];
				
		if($d['final'] == 1)
			$lab .= " (FINAL)";
		else
			$lab .= " (MITAD)";
			
	?>	
		<tr title="<?php echo $lab ?>">
			<td>
			<?php 
				if($d['odd_type_id'] == 1 || $d['odd_type_id'] == 2 || $d['odd_type_id'] == 8){
					if($d['prefix'] != 0) 
						echo $d['prefix'];
				}else
					echo $d['oth_pref'];
				
				if($d['team_type_id'] == 1)
					echo $d['aref'];
				else
					echo $d['href']; 
			?>
			</td>
			<td>
			<?php 
				echo $d['odd'];
				if($d['uses_factor'])
					echo " (".$d['factor'].")";
			?>
			</td>
			<td>
			<?php 
				if($d['team_type_id'] == 1)
					echo $d['away'];	
				elseif($d['team_type_id'] == 2)
					echo $d['home'];
				else{
					echo $d['team_name'];
					echo " (".$d['awayab']." Vs ".$d['homeab'].")";
				}
			?>
			</td>
			<td>
			<?php echo $d['stat'] ?>
			</td>
		</tr>		
	<?php	
	}
	?>
</table>