<script>

var odd_url = '<?php echo $html->url(array('action'=>'add')) ?>';
var exo_load = '<?php echo $html->url(array("action"=>"exotics")) ?>';
var esp_load = '<?php echo $html->url(array("action"=>"specials")) ?>';
var susp_url = '<?php echo $html->url(array('action'=>'suspend')) ?>';

$(function(){
	$("#date").datepicker();
	
	$("#date").change(function(){
		var dat = $(this).val();
		location = '<?php echo $html->url(array("action"=>"index","/")) ?>' + '/' + dat;
	});
	
	$("#league").change(function(){
		var le = $(this).val();
		var dat = $("#date").val();
		location = '<?php echo $html->url(array("action"=>"index","/")) ?>' + '/' + dat + '/' + le;
	});

	$(".results_normal").each(function(e){
		var myid = $(this).attr('id');
		var titles = $(this).attr('title').split("-");
		var count = titles[0];
		var mode = titles[1];

		var toload = odd_url;
		if(mode == 'e')
			toload = exo_load;
		if(mode == 's')
			toload = esp_load;
		
		$(this).load(toload + "/" + myid + "/" + count,function(){
			$(".selectah").mouseover(function(){
				$(this).find('td').addClass('select_over');
			}).mouseout(function(){
				$(this).find('td').removeClass('select_over');
			}).click(function(){
				$(this).find('.radios').attr('checked',true);
			});
		});
		
	});

	$(".results_suspended").each(function(e){
		var myid = $(this).attr('id');
		var count = $(this).attr('title');
		
		$(this).load(susp_url + "/" + myid + "/" + count);		
	});
	
});
</script>
<div class="results">
<h2>Resultados</h2>
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
<?php //pr($games) ?>
<table cellpadding="2" cellspacing="0" border="1" style="width:900px">
<tr>
	<th>Detalles</th>
	<th>Resultados del Juego</th>
	<th>Otros Resultados</th>
	<th>Resultados Especiales</th>
</tr>
<?php

$counter = 0;
foreach ($games as $game):
?>
	<tr>
		<td>
			<?php 
			echo "<b>";
			echo $dtime->time_to_human($game['Game']['time']); 
			echo "</b><br />";
			
			foreach($game['Team'] as $team){
				echo $team['GamesTeam']['reference'].": ".$team['name']."<br />";
			}
			
			if($game['Game']['enable'] == 0){
				echo "<span style='color:Red'>Suspendido</span>";
			}
			?>
		</td>
		<?php 
		$thisclass = "";
		if($game['Game']['enable'] == 0){
		?>
			<td colspan="3" class="results_suspended <?php echo "proper-$counter-n" ?>" id="<?php echo $game['Game']['id'] ?>" title="<?php echo $counter ?>">
				<?php echo $html->image('loading.gif')?>
			</td>
		<?php 
		}else{
		?>
			<td class="results_normal <?php echo "proper-$counter-n" ?>" id="<?php echo $game['Game']['id'] ?>" title="<?php echo $counter ?>-n">
				<?php echo $html->image('loading.gif')?>
			</td>
			<td class="results_normal <?php echo "proper-$counter-e" ?>" id="<?php echo $game['Game']['id'] ?>" title="<?php echo $counter ?>-e">
				<?php echo $html->image('loading.gif')?>
			</td>
			<td class="results_normal <?php echo "proper-$counter-s" ?>" id="<?php echo $game['Game']['id'] ?>" title="<?php echo $counter ?>-s">
				<?php echo $html->image('loading.gif')?>
			</td>
		<?php 
		}	
		?>
	</tr>
<?php 
	$counter ++;
endforeach; 
?>
</table>
</div>