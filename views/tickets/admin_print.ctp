<style>
@media print {
	.for_screen{ display:none }
}
@media screen {
  	.printing{ display:none }
}
.printing{
	font-size: 10pt; 
	color: #000000; 
	font-family: Verdana, Arial, Helvetica, sans-serif; 
	font-weight: bolder;
}
.details td{
	vertical-align: top;
}
b{
	font-size: 110%;
}
</style>
<script type="text/javascript">

$(document).ready(function() {
   print();
});

function refrescador(){
	window.location='<?php echo $html->url(array("controller"=>"tickets","action"=>"add")) ?>';
}
setInterval("refrescador()",3000);
/*
averiguar como abrir en una nueva ventana, y asi direccionar desde apostar 
y cosas por el estilo
*/
</script>
<?php //pr($ticket) ?>
<div class="printing">
	<table cellpadding="0" cellspacing="0" style="width:230px">
		<tr>
			<td><b>Total Parlay</b></td>
			<td><?php echo $ticket['Profile']['name'] ?></td>
		</tr>
		<tr>
			<td><?php echo $dtime->date_from_created($ticket['Ticket']['created']) ?></td>
			<td><?php echo $dtime->hour_exact_created($ticket['Ticket']['created']) ?></td>
		</tr>
		<tr>
			<td><b>NO. </b><?php echo $ticket['Ticket']['id'] ?></td>
			<td><b>NC. </b><?php echo $ticket['Ticket']['confirm'] ?></td>
		</tr>
		<tr>
			<td><b>Monto</b></td>
			<td><?php echo number_format($ticket['Ticket']['amount'],0,',','.') ?></td>
		</tr>
		<tr>
			<td><b>Premio</b></td>
			<td><?php echo number_format($ticket['Ticket']['prize'],0,',','.') ?></td>
		</tr>	
		<tr>
			<td colspan="2">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<th>Ref</th>
						<th>Modo</th>
						<th>Equipo</th>
						<th>Logro</th>
					</tr>
				<?php
				foreach ($ticket['Odds'] as $odd){
					$d = $odd['TicketDetail'];						
				?>	
					<tr class="details">
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
						if($d['odd_type_id'] == 1 || $d['odd_type_id'] == 2 || $d['odd_type_id'] == 3){					
							if($d['final'] == 1)
								echo " FINAL";
							elseif($d['final'] == 2)
								echo " 1rCTO";
							else
								echo " MITAD";
						}else
							echo " ";
						?>
						</td>
						<td>
						<?php 
							if($d['team_type_id'] == 1){
								echo $d['away'];
								
								if($d['amet'] != null)
									echo "<br>(".$d['amet'].")<br>";
									
							}elseif($d['team_type_id'] == 2){
								echo $d['home'];
								
								if($d['hmet'] != null)
									echo "<br>(".$d['hmet'].")<br>";
									
							}else{
								echo $d['team_name'];
								echo " (".$d['awayab']." Vs ".$d['homeab'].")";
							}
						?>
						</td>
						<td>
						<?php 
							echo $d['odd'];
							if($d['uses_factor'])
								echo " (".$d['factor'].")";
						?>
						</td>
						
					</tr>		
				<?php	
				}
				?>	
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				Ticket caduca a los 3 d&iacute;as.
			</td>
		</tr>
		<!--
		<tr>
			<td colspan="2">
				<b>www.totalhipico.com</b>
			</td>
		</tr>
		-->
	</table>
</div>
<div class="for_screen">
	Ticket imprimiendose... <br />
	<input value="Aceptar" type="button" onclick="window.location='<?php echo $html->url(array("controller"=>"tickets","action"=>"add")) ?>'">
</div>