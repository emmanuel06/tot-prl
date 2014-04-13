<style>
form {
	margin-right: 0px;
	padding: 0;
	width: 100%;
}
label{
	display:none;
}
.for_odds{
	width: 45px;
	font-size: 110%;
}
.for_regular{
	margin-top: 3px;
	margin-bottom: 3px;
}
.for_others{
	margin-top: 2px;
	margin-bottom: 1px;
}

.my_odd_table {
	background: #fff;
	border: none;
	border-right:none;
	clear: both;
	margin: 2px 2px 2px 2px;
	width: 100%;
}
.my_odd_table tr td {
	padding: 0px;
}

.buts{
	width:80px;
	margin-left: 10px;
	margin-right: 10px;
	margin-top: 2px;
	margin-bottom: 2px;
	padding: 1px 1px;
	font-size: 100%;
}
form div {
	clear: none;
	margin-bottom: 0px;
	padding: 0px;
	vertical-align: text-top;
}
</style>
<script>

$(function(){
	$('.lil_load').hide();

	$(".difone").blur(function(){
		$(this).parents('td').find(".diftwo").val($(this).val() * -1);
	});
	
	$("#<?php echo "button-$counter-$final" ?>").click(function(){
	
		$(this).parent().find('.buts').hide();
		$(this).parent().find('.lil_load').show();
		$("#<?php echo "odd-$counter-$final" ?>").submit();
	});
	
	$("#<?php echo "odd-$counter-$final" ?>").ajaxForm(function(responseText, statusText) { 
		$(".<?php echo "proper-$counter-$final" ?>").html('<center><H4>'+responseText+'</H4></center>');
		setTimeout(function (){$(".<?php echo "proper-$counter-$final" ?>").load('<?php echo $html->url(array('action'=>'add',$game_id,$final,$counter)) ?>')},3000);
	});
	
});
</script>
<div class="odds">
	<?php 
	
	//pr($odds_set);
		
	echo $form->create('Odd',array('id' => "odd-$counter-$final"));

	//VALUES FROM ODDS
	
	$ml_away = "";
	$ml_home = "";
	$factor_away = "";
	$factor_home = "";
	$factor_ab = "";
	$draw_odd = "";
	
	if(!empty($odds_set[1])){
		$ml_away = $odds_set[1][1]['odd'];
		$ml_home = $odds_set[1][2]['odd'];
	}
	
	if(!empty($odds_set[2])){
		$rl_away = $odds_set[2][1]['odd'];
		$factor_away = $odds_set[2][1]['factor'];
		$rl_home = $odds_set[2][2]['odd'];
		$factor_home = $odds_set[2][2]['factor'];
	}
		
	if(!empty($odds_set[3])){
		$factor_ab = $odds_set[3][4]['factor'];
		$odd_alta = $odds_set[3][4]['odd'];
		$odd_baja = $odds_set[3][5]['odd'];
	}
	
	if(!empty($odds_set[4])){
		$draw_odd = $odds_set[4][3]['odd'];		
	}
	
	//END VALUES FROM ODDS
	
	//VALUES OLD
	echo $form->input('Odd.0.oddold',array('type'=>'hidden','value'=>$ml_away));
	echo $form->input('Odd.1.oddold',array('type'=>'hidden','value'=>$ml_home));
	echo $form->input('Odd.2.oddold',array('type'=>'hidden','value'=>$rl_away));
	echo $form->input('Odd.3.oddold',array('type'=>'hidden','value'=>$rl_home)); 
	echo $form->input('Odd.2.factorold',array('type'=>'hidden','value'=>$factor_away));
	echo $form->input('Odd.3.factorold',array('type'=>'hidden','value'=>$factor_home));
	echo $form->input('Odd.4.factorold',array('type'=>'hidden','value'=>$factor_ab));
 	echo $form->input('Odd.4.oddold',array('type'=>'hidden','value'=>$odd_alta));
	echo $form->input('Odd.5.oddold',array('type'=>'hidden','value'=>$odd_baja));
	//END VALUES OLD
	
	//DFEFAULT VALUES
	echo $form->input('Commoms.game_id',array('type'=>'hidden','value'=>$game_id));
	
	echo $form->input('Commoms.final',array('type'=>'hidden','value'=>$final));
		
	echo $form->input('Odd.0.odd_type_id',array('type'=>'hidden','value'=>1));
	echo $form->input('Odd.1.odd_type_id',array('type'=>'hidden','value'=>1));
	echo $form->input('Odd.2.odd_type_id',array('type'=>'hidden','value'=>2));
	echo $form->input('Odd.3.odd_type_id',array('type'=>'hidden','value'=>2));
	echo $form->input('Odd.4.odd_type_id',array('type'=>'hidden','value'=>3));
	echo $form->input('Odd.5.odd_type_id',array('type'=>'hidden','value'=>3));
	
	echo $form->input('Odd.0.team_type_id',array('type'=>'hidden','value'=>1));
	echo $form->input('Odd.1.team_type_id',array('type'=>'hidden','value'=>2));
	echo $form->input('Odd.2.team_type_id',array('type'=>'hidden','value'=>1));
	echo $form->input('Odd.3.team_type_id',array('type'=>'hidden','value'=>2));
	echo $form->input('Odd.4.team_type_id',array('type'=>'hidden','value'=>4));
	echo $form->input('Odd.5.team_type_id',array('type'=>'hidden','value'=>5));
	
	//END DEFAULT VALUES
	
	if($draw){
		echo $form->input('Odd.6.oddold',array('type'=>'hidden','value'=>$draw_odd));
		echo $form->input('Odd.6.odd_type_id',array('type'=>'hidden','value'=>4));
		echo $form->input('Odd.6.team_type_id',array('type'=>'hidden','value'=>3));
	}
	
	?>
	<table border="1" cellpadding="0" cellspacing="0" class="my_odd_table">
		<tr>
			<td>Money</td>
			<td>Run</td>
			<td>Dif</td>
			<td colspan="2">A/B</td>
			<?php 
			if($draw)
				echo "<td>Empate</td>";
			?>
		</tr>
		<tr>
			<td rowspan="2">
				<?php 
					echo $form->input('Odd.0.odd',array('class'=>'for_odds for_regular','value'=>$ml_away));
					echo $form->input('Odd.1.odd',array('class'=>'for_odds for_regular','value'=>$ml_home));
					?>
			</td>
			<td rowspan="2">
				<?php 
					echo $form->input('Odd.2.odd',array('class'=>'for_odds for_regular','value'=>$rl_away));
					echo $form->input('Odd.3.odd',array('class'=>'for_odds for_regular','value'=>$rl_home)); 
				?>
			</td>
			<td rowspan="2">
				<?php 
					echo $form->input('Odd.2.factor',array('class'=>'for_odds for_regular difone','value'=>$factor_away));
					echo $form->input('Odd.3.factor',array('class'=>'for_odds for_regular diftwo','value'=>$factor_home));
				?>
			</td>
			<td colspan="2">
				<?php 
					echo $form->input('Odd.4.factor',array('class'=>'for_odds for_others','value'=>$factor_ab,'title'=>'Limite'));
				?>
			</td>
			<?php 
			if($draw){
			?>	
				<td rowspan="2">
					<?php 
						echo $form->input('Odd.6.odd',array('class'=>'for_odds for_regular','value'=>$draw_odd,'title'=>'Empate', 'style'=>'margin-top:10px'));
					?>
				</td>
			<?php
			}
			?>
		</tr>
		<tr>
			<td>
				<?php 
					echo $form->input('Odd.4.odd',array('class'=>'for_odds for_others','value'=>$odd_alta,'title'=>'Alta'));
				?>
			</td>
			<td>
				<?php 
					echo $form->input('Odd.5.odd',array('class'=>'for_odds for_others','value'=>$odd_baja,'title'=>'Baja'));
				?>
			</td>
		</tr>
		<tr>
			<td colspan="6">
				<?php echo $html->image('loading_small.gif',array('class'=>'lil_load'))?>
				<input class="buts save" id="<?php echo "button-$counter-$final" ?>" type="button" value="Guardar"/>
				<input class="buts del" type="button" value="Borrar"/>
			</td>
		</tr>
	</table>
	<?php 
	echo $form->end()
	?>
</div>