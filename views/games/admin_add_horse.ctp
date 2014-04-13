<style>
form {
	margin-right: 0px;
	padding: 0;
	width: 100%;
}
</style>
<script>
var i = 1;
$(function(){
	$("#GameDate").datepicker();
	
	$("#add_horse").button({
		icons: {
        	primary: "ui-icon-circle-plus"
    	}
    }).click(function(){
    	i = i + 1;
    	to_append(i);
    	return false;
    });
	
});

function to_append(i){
	var toadd= "<tr><td>\
				<select name='data[Team][0][team_id]' id='Team0TeamId'>\
				<option value=''>Seleccione</option>\
				<option value='19'>01</option>\
				<option value='20'>02</option>\
				<option value='21'>03</option>\
				<option value='22'>04</option>\
				<option value='23'>05</option>\
				<option value='24'>06</option>\
				<option value='25'>07</option>\
				<option value='26'>08</option>\
				<option value='27'>09</option>\
				<option value='28'>10</option>\
				<option value='29'>Otros</option>\
				</select>\
				<input name='data[Team][0][team_type_id]' value='1' id='Team0TeamTypeId' type='hidden'>\
				</td><td>\
				<input name='data[Team][0][reference]' style='width: 50px;' value=''\
				id='Team0Reference' type='text'></td><td>\
				<a href='/raicing/admin/games/add_horse/1' class='del_row'>Borrar</a></td></tr>";
	
	
	$("#mytable").append(toadd);
 
}
</script>
<div class="matches form">
<?php 
echo $form->create('Game');
?>
	<table border="1" style="width:600px" id="mytable">
		<tr>
			<th>Fecha</th>
			<th>Hora</th>
			<th>Carrera</th>
		</tr>
		<tr>
			<td>
				<?php 
				echo $form->input('league_id',array('value'=>$league_id,'type'=>'hidden'));
				echo $form->input('date',array('label'=>null,'div'=>null,'type'=>'text','readonly'=>'readonly','style'=>'width:90px','value'=>$date));
				?>	
			</td>
			<td>
				<div style='width:180px'>
					<?php 
					echo $form->input('time',array('label'=>'','interval'=>5));		
					?>
				</div>
			</td>
			<td>
				<?php
				echo $form->input('metadata',array('label'=>false));
				?>
			</td>
		</tr>
		<tr>
			<th colspan='3'>Caballos <button id="add_horse">Agregar</button></th>
		</tr>
		<tr>
			<td>
				<?php 
				echo $form->input("Team.0.team_id",array('label'=>false,'div'=>false,'options'=>$teams,'empty'=>'Seleccione'));
				echo $form->input("Team.0.team_type_id",array('type'=>'hidden','value'=>1));
				?>
			</td>
			<td>
				<?php 
				echo $form->input("Team.0.reference",array('label'=>false,'div'=>false,'style'=>'width:50px'));
				?>
			</td>
			<td>
				<?php echo $html->link("Borrar",array('action'>'#'),array('class'=>'del_row')) ?>
			</td>
		</tr>
	</table>
<?php echo $form->end('Guardar');?>
</div>