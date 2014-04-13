<div class="pitchers form">
<?php echo $form->create('Pitcher');?>
	<fieldset>
	<?php
		echo $form->input('id');
		echo $form->input('name',array('label'=>'Nombre'));
	?>
	</fieldset>
<?php echo $form->end('Guardar');?>
</div>