<div class="pitchers form">
<?php echo $form->create('Pitcher');?>
	<fieldset>
	<?php
		echo $form->input('name',array('label'=>'Nombre'));
		echo $form->input('team_id',array('value'=>$team_id,'type'=>'hidden'));
	?>
	</fieldset>
<?php echo $form->end('Guardar');?>
</div>