<div class="groups form">
<?php echo $form->create('Group');?>
	<fieldset>
 		<legend>Crear Grupo</legend>
	<?php
		echo $form->input('name',array('label'=>'Nombre'));
		echo $form->input('location',array('label'=>'Lugar'));
		echo $form->input('passkey',array('label'=>'Clave Secreta','value'=>'123456'));
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>