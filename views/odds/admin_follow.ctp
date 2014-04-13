<script>

var follow = '<?php echo $html->url(array('action'=>'follow')) ?>';

$(function(){
	$("#date").datepicker();
	
	$("#date").change(function(){
		var dat = $(this).val();
		location = follow + '/' + dat;
	});
	
	$("#league").change(function(){
		var le = $(this).val();
		var dat = $("#date").val();
		location = follow + '/' + dat + '/' + le;
	});
	
});
</script>
<div class="results">
<h2>Seguimiento</h2>
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
	$therowspan = 2 + count($games);
?>
<table cellpadding="2" cellspacing="0" border="1" style="font-size:120%">
<tr>
	<th rowspan="2">Detalles</th>
	<th colspan="4">Visitante</th>
	<td rowspan="<?php echo $therowspan ?>"> - </td>
	<th colspan="4">HomeClub</th>
	<td rowspan="<?php echo $therowspan ?>"> - </td>
	<th colspan="3">Alta</th>
	<td rowspan="<?php echo $therowspan ?>"> - </td>
	<th colspan="3">Baja</th>
</tr>
<tr>
	<th>Equipo</th>
	<th>Tks</th>
	<th>Monto</th>
	<th>Premio</th>
	<th>Equipo</th>
	<th>Tks</th>
	<th>Monto</th>
	<th>Premio</th>
	<th>Tks</th>
	<th>Monto</th>
	<th>Premio</th>
	<th>Tks</th>
	<th>Monto</th>
	<th>Premio</th>
</tr>
<?php
$counter = 0;
$i = 0;
foreach ($games as $game):
	$class = null;
	if ($i++ % 2 != 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class?>>
		<td>
			<?php 
			echo "<b>";
			echo $dtime->time_to_human($game['Game']['time']); 
			echo "</b><br />";
			
			if($game['Game']['enable'] == 0){
				echo "<span style='color:Red'>Suspendido</span>";
			}
			?>
		</td>
		<td>
			<?php 
			echo "<b>".$game['Team'][0]['GamesTeam']['reference']."</b>: ".$game['Team'][0]['name']."<br />";
			?>
		</td>
		<td>
			<?php 
			echo number_format($totals[$game['Game']['id']]['AWAY']['tickets'],0,',','.') ?>
		</td>
		<td>
			<?php echo $number->currency($totals[$game['Game']['id']]['AWAY']['amounts'],"",array('thousands'=>'.','decimals'=>',','places'=>0)) ?>
		</td>
		<td>
			<?php echo $number->currency($totals[$game['Game']['id']]['AWAY']['prices'],"",array('thousands'=>'.','decimals'=>',','places'=>0)) ?>
		</td>
		<td>
			<?php 
			echo "<b>".$game['Team'][1]['GamesTeam']['reference']."</b>: ".$game['Team'][1]['name']."<br />";
			?>
		</td>
		<td>
			<?php echo number_format($totals[$game['Game']['id']]['HOME']['tickets'],0,',','.') ?>
		</td>
		<td>
			<?php echo $number->currency($totals[$game['Game']['id']]['HOME']['amounts'],"",array('thousands'=>'.','decimals'=>',','places'=>0)) ?>
		</td>
		<td>
			<?php echo $number->currency($totals[$game['Game']['id']]['HOME']['prices'],"",array('thousands'=>'.','decimals'=>',','places'=>0)) ?>
		</td>
		<td>
			<?php echo number_format($totals[$game['Game']['id']]['ALTA']['tickets'],0,',','.') ?>
		</td>
		<td>
			<?php echo $number->currency($totals[$game['Game']['id']]['ALTA']['amounts'],"",array('thousands'=>'.','decimals'=>',','places'=>0)) ?>
		</td>
		<td>
			<?php echo $number->currency($totals[$game['Game']['id']]['ALTA']['prices'],"",array('thousands'=>'.','decimals'=>',','places'=>0)) ?>
		</td>
		<td>
			<?php echo number_format($totals[$game['Game']['id']]['BAJA']['tickets'],0,',','.') ?>
		</td>
		<td>
			<?php echo $number->currency($totals[$game['Game']['id']]['BAJA']['amounts'],"",array('thousands'=>'.','decimals'=>',','places'=>0)) ?>
		</td>
		<td>
			<?php echo $number->currency($totals[$game['Game']['id']]['BAJA']['prices'],"",array('thousands'=>'.','decimals'=>',','places'=>0)) ?>
		</td>
	</tr>
	<?php 
	$counter ++;
endforeach; 
?>
</table>
</div>