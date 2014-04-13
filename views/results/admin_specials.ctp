<style>
form {
	margin-right: 0px;
	padding: 0;
	width: 100%;
}
label{
	display:none;
}
.for_exos{
	width: 35px;
	font-size: 90%;
}
.for_regular{
	margin-top: 1px;
	margin-bottom: 1px;
}
.for_others{
	margin-top: 2px;
	margin-bottom: 1px;
}

.my_odd_table {
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
	width:120px;
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
.select_over{
	background: #CCC;
	cursor:pointer;
}
</style>
<script>
$(function(){
	$('.lil_load').hide();
	
	$("#<?php echo "button-$counter-s" ?>").click(function(){
	
		$(this).parent().find('.buts').hide();
		$(this).parent().find('.lil_load').show();
		$("#<?php echo "result-$counter-s" ?>").submit();
	});
	
	$("#<?php echo "result-$counter-s" ?>").ajaxForm(function(responseText, statusText) { 
		$(".<?php echo "proper-$counter-s" ?>").html('<center><H4>'+responseText+'</H4></center>');
		setTimeout(function (){$(".<?php echo "proper-$counter-s" ?>").load('<?php echo $html->url(array('action'=>'specials',$game_id,$counter)) ?>',
			function(){
			$(".selectah").mouseover(function(){
				$(this).find('td').addClass('select_over');
				}).mouseout(function(){
					$(this).find('td').removeClass('select_over');
				}).click(function(){
					$(this).find('.radios').attr('checked',true);
				});
			}
		)},3000);
	});
	
});
</script>
<div class="odds">
	<?php 
	echo $form->create('Result',array('action'=>'specials','id' => "result-$counter-s"));
	echo $form->input('game_id',array('value'=>$game_id,'type'=>'hidden'));
	?>
	<table border="1">
		<tr>
			<th>MODO</th>
			<th>ML</th>
			<th>RL</th>
			<th>AB</th>
			<th>Q.A.P.</th>
			<th>T.CHE</th>
			<th>SURL</th>
		</tr>
		<tr>
			<th>Mitad</th>
			<td><?php echo $form->input('Half.Moneyline',array('type'=>'checkbox','value'=>1,'label'=>''));?></td>
			<td><?php echo $form->input('Half.Runline',array('type'=>'checkbox','value'=>1,'label'=>''));?></td>
			<td><?php echo $form->input('Half.HighLow',array('type'=>'checkbox','value'=>1,'label'=>''));?></td>
			<td rowspan="2"><?php echo $form->input('Final.QUI',array('type'=>'checkbox','value'=>1,'label'=>''));?></td>
			<td rowspan="2"><?php echo $form->input('Final.CHE',array('type'=>'checkbox','value'=>1,'label'=>''));?></td>
			<td rowspan="2"><?php echo $form->input('Final.SRL',array('type'=>'checkbox','value'=>1,'label'=>''));?></td>
		</tr>
		<tr>
			<th>Final</th>
			<td><?php echo $form->input('Final.Moneyline',array('type'=>'checkbox','value'=>1,'label'=>''));?></td>
			<td><?php echo $form->input('Final.Runline',array('type'=>'checkbox','value'=>1,'label'=>''));?></td>
			<td><?php echo $form->input('Final.HighLow',array('type'=>'checkbox','value'=>1,'label'=>''));?></td>
		</tr>
		<tr>
			<td colspan="7">
				<input class="buts save" id="<?php echo "button-$counter-s" ?>" type="button" value="Guardar"/>
				<?php echo $html->image('loading_small.gif',array('class'=>'lil_load'))?>
			</td>
		</tr>		
	</table>
</div>