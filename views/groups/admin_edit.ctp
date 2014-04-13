<div class="groups form">
<?php echo $form->create('Group');?>
	<fieldset>
 		<legend>Editar Grupo</legend>
	<?php
		echo $form->input('id');
		echo $form->input('name',array('label'=>'Nombre'));
		echo $form->input('location',array('label'=>'Lugar'));
		echo $form->input('passkey',array('label'=>'Clave Secreta'));
	?>
	</fieldset>
<?php echo $form->end('Submit');?>
</div>