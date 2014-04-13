<style>
form {
	margin-right: 0px;
	padding: 0;
	width: 100%;
}
</style>
<script>
var pitchs = '<?php echo $html->url(array('controller'=>'pitchers','action'=>'get_listed')) ?>';
$(function(){
	$("#GameDate").datepicker();
	$('.mets').hide();
	
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

	$(".teams").change(function(){
		var pitch_url = pitchs + "/" + $(this).val() + "/" + $(this).attr('title');
		$(this).parents('tr').find('.pitcher').html("Cargando...").load(pitch_url,function(){
			$(".list_pitch").change(function(){ 
				var pitman = $("option:selected",this).text();
				$(this).parents('td').find('.mets').val(pitman); 
			});
		});
	});
	
});
</script>
<div class="matches form">
<?php 
$this_ref = "";
$other_ref = "";
if($last_ref != ""){
	$this_ref = $last_ref + 1;
	$other_ref = $last_ref + 2;	
}

echo $form->create('Game');
?>
	<table border="1">
		<tr>
			<th>Fecha</th>
			<th style="width:100px">Hora</th>
			<th>Equipos</th>
			<th>Pitchers</th>
			<th>Refs</th>
		</tr>
		<tr>
			<td rowspan="2">
				<?php 
				echo $form->input('league_id',array('value'=>$league_id,'type'=>'hidden'));
				echo $form->input('date',array('label'=>'','type'=>'text','readonly'=>'readonly','style'=>'width:90px','value'=>$date));
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
				echo $form->input('Team.0.team_id',array('label'=>'','options'=>$teams,'empty'=>'Seleccione','class'=>'teams','title'=>0));
				echo $form->input('Team.0.team_type_id',array('type'=>'hidden','value'=>1));
				?>
			</td>
			<td>
				<div class="pitcher">
				<?php 
 				echo $form->input('pitch_id',array('readonly'=>true,'options'=>array(""=>"Seleccione equipo"),'label'=>'')) 
 				?>
 				</div>
				<?php echo $form->input('Team.0.metadata',array('class'=>'mets')) ?>
			</td>
			<td>
				<?php 
				echo $form->input('Team.0.reference',array('label'=>'','style'=>'width:50px','value'=>$this_ref));
				?>
			</td>
		</tr>
		<tr>
			<td>
				<?php 
				echo $form->input('Team.1.team_id',array('label'=>'','options'=>$teams,'empty'=>'Seleccione','class'=>'teams','title'=>1));
				echo $form->input('Team.1.team_type_id',array('type'=>'hidden','value'=>2));
				?>
			</td>
			<td>
				<div class="pitcher">
				<?php 
 				echo $form->input('pitch_id',array('readonly'=>true,'options'=>array(""=>"Seleccione equipo"),'label'=>'')) 
 				?>
 				</div>
 				<?php echo $form->input('Team.1.metadata',array('class'=>'mets')) ?>
			</td>
			<td>
				<?php 
				echo $form->input('Team.1.reference',array('label'=>'','style'=>'width:50px','value'=>$other_ref));
				?>
			</td>
		</tr>
	</table>
<?php echo $form->end('Guardar');?>
</div>