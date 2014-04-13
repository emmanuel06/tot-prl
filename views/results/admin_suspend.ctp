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
		$(this).parent().find('.lil_load').show();
		
		$("#<?php echo "result-$counter-n" ?>").submit();
	});
	
	$("#<?php echo "result-$counter-n" ?>").ajaxForm(function(responseText, statusText) { 
		$(".<?php echo "proper-$counter-n" ?>").html('<center><H4>'+responseText+'</H4></center>');
		setTimeout(function (){$(".<?php echo "proper-$counter-n" ?>").load('<?php echo $html->url(array('action'=>'suspend',$game_id,$counter)) ?>')},2000);
	});
	
});
</script>
<div class="results add">
<?php 
//pr($results);
echo $form->create('Result',array('action'=>'suspend','id' => "result-$counter-n"));
?>
<table border="1" style="width:400px" title="Resultados">	
	<tr>
		<?php 
		if(!empty($results)){
		?>
			<td style="color:red">
				Juego y Logros SUSPENDIDOS
			</td>
		<?php 
		}else{
		?>
			<td style="color:red">
				Este juego se encuentra suspendido, confirme si desea que todos 
				los logros sean suspendidos todos. 
			</td>
			<td>
				<?php echo $html->image('loading_small.gif',array('class'=>'lil_load'))?>
				<input type="hidden" name="data[Result][game_id]" value="<?php echo $game_id ?>" />
				<input type="button" class="buts" value="Suspender Logros" style="color:Red;" id="<?php echo "button-$counter-n" ?>" />
			</td>
		<?php 
		}
		?>
	</tr>
</table>
<?php 
echo $form->end();
?>
</div>