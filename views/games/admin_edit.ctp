<style>
form {
	margin-right: 0px;
	padding: 0;
	width: 100%;
}
</style>
<script>
$(function(){
	$("#GameDate").datepicker();

	$("#Team0Reference").blur(function() {
		 var normal = $(this).val();
		 var nueva = normal*1 + 1;
		 $("#Team1Reference").val(nueva);
   });
	
	$('#GameAddForm').submit(function(){
		
		//VACIOS
		if($('#Team0TeamId').val() == "" || $('#Team1TeamId').val() == ""){
			alert('Debe seleccionar TODOS los equipos.');
			return false;
		}

		if($('#Team0TeamId').val() == $('#Team1TeamId').val()){
			alert('Los equipos no pueden ser iguales.');
			return false;
		}
		
		if($('#Team0Ref').val() == "" || $('#Team1Ref').val() == ""){
			alert('Debe llenar TODAS las referencias');
			return false;
		}
	});
	
});
</script>
<div class="matches form">
<?php 
echo $form->create('Game'); 
?>
	<table border="1">
		<tr>
			<th>Fecha</th>
			<th style="width:100px">Hora</th>
			<th>Equipos</th>
			<th>Refs</th>
		</tr>
		<tr>
			<td rowspan="2">
				<?php 
				echo $form->input('id');
				echo $form->input('date',array('label'=>'','type'=>'text','readonly'=>'readonly','style'=>'width:90px','value'=>$this->data['Game']['date']));
				?>	
			</td>
			<td rowspan="2">
				<div style='width:180px'>
					<?php 
					echo $form->input('time',array('label'=>'','interval'=>5));		
					?>
				</div>
			</td>
			<td>
				<?php 
				echo $form->input('Team.0.team_id',array('label'=>'','options'=>$teams,'empty'=>'Seleccione'));
				echo $form->input('Team.0.team_type_id',array('type'=>'hidden','value'=>1));
				?>
			</td>
			<td>
				<?php 
				echo $form->input('Team.0.reference',array('label'=>'','style'=>'width:50px','value'=>$this->data['Team'][0]['GamesTeam']['reference']));
				?>
			</td>
		</tr>
		<tr>
			<td>
				<?php 
				echo $form->input('Team.1.team_id',array('label'=>'','options'=>$teams,'empty'=>'Seleccione'));
				echo $form->input('Team.1.team_type_id',array('type'=>'hidden','value'=>2));
				?>
			</td>
			<td>
				<?php 
				echo $form->input('Team.1.reference',array('label'=>'','style'=>'width:50px','value'=>$this->data['Team'][1]['GamesTeam']['reference']));
				?>
			</td>
		</tr>
	</table>
<?php echo $form->end('Guardar');?>
</div>