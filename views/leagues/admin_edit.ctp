<div class="leagues form">
<?php echo $form->create('League');?>
	<fieldset>
	<?php
		echo $form->input('id');
		echo $form->input('name',array('label'=>'Nombre'));
		echo $form->input('sport_id',array('label'=>'Deporte'));
	?>
	</fieldset>
<?php echo $form->end('Guardar');?>
</div>