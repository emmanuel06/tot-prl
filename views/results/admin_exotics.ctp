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
	
	$("#<?php echo "button-$counter-e" ?>").click(function(){
	
		$(this).parent().find('.buts').hide();
		$(this).parent().find('.lil_load').show();
		$("#<?php echo "result-$counter-e" ?>").submit();
	});
	
	$("#<?php echo "result-$counter-e" ?>").ajaxForm(function(responseText, statusText) { 
		$(".<?php echo "proper-$counter-e" ?>").html('<center><H4>'+responseText+'</H4></center>');
		setTimeout(function (){$(".<?php echo "proper-$counter-e" ?>").load('<?php echo $html->url(array('action'=>'exotics',$game_id,$counter)) ?>',
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
	if(empty($results)){
		echo "Sin resultados exoticos.";
	}else{
		echo $form->create('Result',array('action'=>'exotics','id' => "result-$counter-e"));
		//cuidao: cuando no se haya inscrito ninguna, colocar por default hide
		//y con un link mostrar pa crear de nuevo
		?>
		<table border="1" cellpadding="0" cellspacing="0" class="my_odd_table">
			<tr>
				<th>EQUIPO</th>
				<th>SELECCIONE</th>
			</tr>
		<?php 
		$i = 0;
		foreach($results as $result){
			$oddid = $result['Id'];
		?>
			<tr>
				<th colspan="3"><?php echo $result['Name'] ?></th>
			</tr>
			<?php
			foreach($result['Team'] as $teamid => $name){
				
				//VALUES FROM ODDS
				$chkd = "";	
				if(!empty($results_set[$oddid][$teamid]['score'])){
					$chkd = "checked='checked'";	
				}
				if(!empty($results_set[$oddid][$teamid]['id'])){
					echo $form->input("Selected.$oddid.id",array('type'=>'hidden','value'=>$results_set[$oddid][$teamid]['id']));	
				}
				//END VALUES FROM ODDS
							
				//DFEFAULT VALUES
				echo $form->input("Result.$i.game_id",array('type'=>'hidden','value'=>$game_id));
				echo $form->input("Result.$i.odd_type_id",array('type'=>'hidden','value'=>$oddid));
				echo $form->input("Result.$i.team_type_id",array('type'=>'hidden','value'=>$teamid));
				echo $form->input("Result.$i.final",array('type'=>'hidden','value'=>1));
				echo $form->input("Result.$i.suspended",array('type'=>'hidden','value'=>0));
				//END DEFAULT VALUES
			?>
				<tr class="selectah">
					<td style="padding-top: 3px">
						<?php echo $name ?>
					</td>
					<td style="padding: 3px 3px 3px 3px">
						<input type="radio" <?php echo $chkd ?> class="radios"
						name="data[Selected][<?php echo $oddid ?>][value]" value="<?php echo $teamid ?>" />
					</td>
				</tr>
			<?php
				$i ++; 
			} 
		}
		if($has_che){
			if($tot_che_id != 0)
				echo $form->input("Result.$i.id",array('type'=>'hidden','value'=>$tot_che_id));
			
			echo $form->input("Result.$i.game_id",array('type'=>'hidden','value'=>$game_id));
			echo $form->input("Result.$i.odd_type_id",array('type'=>'hidden','value'=>7));
			echo $form->input("Result.$i.team_type_id",array('type'=>'hidden','value'=>8));
			echo $form->input("Result.$i.final",array('type'=>'hidden','value'=>1));
			echo $form->input("Result.$i.suspended",array('type'=>'hidden','value'=>0));
			?>
				<tr>
					<th colspan="3">Total CHE</th>
				</tr>
				<tr>
					<td colspan="3" style="padding: 3px 3px 3px 3px">
						Puntos: <input name="data[Result][<?php echo $i ?>][score]" style="width:120px" value="<?php echo $tot_che ?>" />
					</td>
				</tr>
			<?php 
		}
		?>
			<tr>
				<td colspan="3">
					<input class="buts save" id="<?php echo "button-$counter-e" ?>" type="button" value="Guardar"/>
					<?php echo $html->image('loading_small.gif',array('class'=>'lil_load'))?>
				</td>
			</tr>
		</table>
	<?php 
		echo $form->end();
	}	
	?>
</div>