<script>
var league_id = '<?php echo $league ?>';

$(function() {
	$(".set_met").hide();
	$("#date").datepicker();
	
	$("#date").change(function(){
		var dat = $(this).val();
		location = '<?php echo $html->url(array("action"=>"set_pitchers","/")) ?>' + '/' + dat;
	});
	
	$("#league").change(function(){
		var le = $(this).val();
		var dat = $("#date").val();
		location = '<?php echo $html->url(array("action"=>"set_pitchers","/")) ?>' + '/' + dat + '/' + le;
	});


	$(".sel_pit").each(function(){
		var myider = $(this).attr('id');

		$("#" + myider).change(function () {
			$("#" + myider + " option:selected").each(function () {
				mypit = $(this).text();
			});
			$(this).parents("td").find(".set_met").val(mypit);
		});
	});
});

</script>
<div class="matches index">
<h2>Colocar pitchers</h2>

<table style="width:450px">
	<tr>
		<td>
		<?php 
		echo $form->input("date",array('label'=>'Fecha','value'=>$date,'readonly'=>'readonly','style'=>'width:90px'));
		
		echo "(".$dtime->date_spa_mon_abr($date).")";
		?>
		
		</td>
		<td>
		<?php 
		echo $form->input("league",array('label'=>'Liga','value'=>$league,'options'=>$leagues,'empty'=>"Seleccione")) 	
		?>
		</td>
	</tr>
</table>
<?php 
echo $form->create('Game',array('action'=>'set_pitchers'))
?>
<table cellpadding="2" border="1" cellspacing="0" style="width:500px">
<tr>
	<th>Hora</th>
	<th>Equipos</th>
	<th>Pitchers</th>
</tr>
<?php
if($league != ""){
	foreach ($games as $game):
	?>
		<tr>
			<td rowspan="2">
				<?php 
				$passed = "";
				
				if(strtotime($game['Game']['time']) < strtotime(date("H:i:s")))
					$passed = " style='color:Red'";
				
				echo "<b$passed>";
				echo $dtime->time_to_human($game['Game']['time']); 
				echo "</b><br />";
				
				if($game['Game']['enable'] == 0){
					echo "<span style='color:Red'>Suspendido</span>";
				}
				?>
			</td>
			<?php
			$j = 0; 
			foreach($game['Team'] as $team){
			
				echo "<td>".$team['GamesTeam']['reference'].": ".$team['name']."</td>";
				
				echo "<td><b>Actual:".$team['GamesTeam']['metadata']."</b><br />";
				
				if(!empty($pitchers[$team['id']])){
					echo $form->input("Game.".$team['GamesTeam']['id'].".pitcher",array(
						'options' => $pitchers[$team['id']], 'empty' => 'Seleccione',
						'label' => '','class'=>'sel_pit'
					));
					echo $form->input("Game.".$team['GamesTeam']['id'].".metadata",array(
						'label' => '','class'=>'set_met'
					));
					
				}else
					echo 'Debe inscribir pitchers a este equipo.';
				
				echo "</td>";
			
				if($j != 0)
					echo "<tr>";
					
				echo "</tr>";
				$j++;
			}
			?>		
		</tr>
	<?php 
	endforeach; 
}
?>
</table>
<?php 
echo $form->end("Guardar");
?>
</div>