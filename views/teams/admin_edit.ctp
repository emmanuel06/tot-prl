<div class="teams form">
<?php echo $form->create('Team');?>
	<fieldset>
	<?php
		echo $form->input('id');
		echo $form->input('name',array('label'=>'Nombre'));
		echo $form->input('abrev',array('label'=>'Abreviatura'));
		echo $form->input('alt_name',array('label'=>'Otro Nombre'));
		echo $form->input('win_loses',array('label'=>'Gan - Per'));
		echo $form->input('league_id',array('type'=>'hidden'));
	?>
	</fieldset>
<?php echo $form->end('Guardar');?>
</div>