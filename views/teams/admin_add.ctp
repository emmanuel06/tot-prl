<div class="teams form">
<?php echo $form->create('Team');?>
	<fieldset>
	<?php
		echo $form->input('name',array('label'=>'Nombre'));
		echo $form->input('abrev',array('label'=>'Abreviatura'));
		echo $form->input('league_id',array('value'=>$league_id,'type'=>'hidden'));
	?>
	</fieldset>
<?php echo $form->end('Guardar');?>
</div>