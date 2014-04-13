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
	margin-top: 3px;
	margin-bottom: 3px;
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

	$("#<?php echo "button-$counter-n" ?>").click(function(){
		
		$(this).hide();
		$("#<?php echo "erase-$counter-n" ?>").hide();
		$(this).parent().find('.lil_load').show();
		
		$("#<?php echo "result-$counter-n" ?>").submit();
	});

	$("#<?php echo "erase-$counter-n" ?>").click(function(){
		$("#<?php echo "toeras-$counter-n" ?>").val(1);
		$(this).hide();
		$("#<?php echo "button-$counter-n" ?>").hide();
		$(this).parent().find('.lil_load').show();
		$("#<?php echo "result-$counter-n" ?>").submit();
	});
	
	$("#<?php echo "result-$counter-n" ?>").ajaxForm(function(responseText, statusText) { 
		$(".<?php echo "proper-$counter-n" ?>").html('<center><H4>'+responseText+'</H4></center>');
		setTimeout(function (){$(".<?php echo "proper-$counter-n" ?>").load('<?php echo $html->url(array('action'=>'add',$game_id,$counter)) ?>')},2000);
	});
	
});
</script>
<div class="results add">
<?php 
//pr($results);
echo $form->create('Result',array('id' => "result-$counter-n"));

$sco0 = ""; $sco1 = ""; $sco2 = ""; $sco3 = ""; $sco4 = ""; $sco5 = "";

if(!empty($results[2])){
	echo $form->input('Result.4.id',array('type'=>'hidden','value'=>$results[2][1]['id']));
	echo $form->input('Result.5.id',array('type'=>'hidden','value'=>$results[2][2]['id']));
	$sco4 = $results[2][1]['score'];
	$sco5 = $results[2][2]['score'];
}
if(!empty($results[1])){
	echo $form->input('Result.0.id',array('type'=>'hidden','value'=>$results[1][1]['id']));
	echo $form->input('Result.1.id',array('type'=>'hidden','value'=>$results[1][2]['id']));
	$sco0 = $results[1][1]['score'];
	$sco1 = $results[1][2]['score'];
}
if(!empty($results[0])){
	echo $form->input('Result.2.id',array('type'=>'hidden','value'=>$results[0][1]['id']));
	echo $form->input('Result.3.id',array('type'=>'hidden','value'=>$results[0][2]['id']));
	$sco2 = $results[0][1]['score']; 
	$sco3 = $results[0][2]['score'];
}
?>
<table border="1" style="width:200px" title="Resultados">
	<tr>
		<?php if($hasqt){ ?>
		<th>1er Cto.</th>	
		<?php } ?>
		<th>Mitad</th>
		<th>Completo</th>
	</tr>
	<tr>
		<?php
		if($hasqt){
			echo "<td>";
			echo $form->input('Result.4.score',array('class'=>'for_odds','value'=>$sco4));
			echo $form->input('Result.4.scoreold',array('type'=>'hidden','value'=>$sco4));
			echo $form->input('Result.4.game_id',array('type'=>'hidden','value'=>$game_id)); 
			
			echo $form->input('Result.5.score',array('class'=>'for_odds','value'=>$sco5));
			echo $form->input('Result.5.scoreold',array('type'=>'hidden','value'=>$sco5));
			echo $form->input('Result.5.game_id',array('type'=>'hidden','value'=>$game_id));
			echo "</td>";	
		}
		?>
		<td>
		<?php 
			
			echo $form->input('Result.2.score',array('class'=>'for_odds','value'=>$sco2));
			echo $form->input('Result.2.scoreold',array('type'=>'hidden','value'=>$sco2));
			echo $form->input('Result.2.game_id',array('type'=>'hidden','value'=>$game_id)); 
			
			echo $form->input('Result.3.score',array('class'=>'for_odds','value'=>$sco3));
			echo $form->input('Result.3.scoreold',array('type'=>'hidden','value'=>$sco3));
			echo $form->input('Result.3.game_id',array('type'=>'hidden','value'=>$game_id)); 
		?>
		</td>
		<td>
		<?php 
			echo $form->input('Result.0.score',array('class'=>'for_odds','value'=>$sco0));
			echo $form->input('Result.0.scoreold',array('type'=>'hidden','value'=>$sco0));
			echo $form->input('Result.0.game_id',array('type'=>'hidden','value'=>$game_id)); 
			
			echo $form->input('Result.1.score',array('class'=>'for_odds','value'=>$sco1));
			echo $form->input('Result.1.scoreold',array('type'=>'hidden','value'=>$sco1));
			echo $form->input('Result.1.game_id',array('type'=>'hidden','value'=>$game_id)); 
		?>
		</td>		
	</tr>
	<tr>
		<td colspan="<?php if($hasqt) echo '3'; else echo '2'; ?>">
			<?php echo $html->image('loading_small.gif',array('class'=>'lil_load'))?>
			<input type="button" class="buts" value="Guardar" id="<?php echo "button-$counter-n" ?>" />
			<input type="hidden" name="data[Result][eraser]" value="0" id="<?php echo "toeras-$counter-n" ?>" />
			<input type="hidden" name="data[Result][game_id]" value="<?php echo $game_id ?>" />
			<input type="button" class="buts" value="Borrar" style="color:Red;" id="<?php echo "erase-$counter-n" ?>" />
		</td>
	</tr>
</table>
<?php 
echo $form->end();
?>
</div>