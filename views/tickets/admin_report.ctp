<style>
fieldset{
	width: 300px;
	
}
input{
	font-size:140%;
}
label{
	font-size:140%;
}
</style>
<div class="tickets pay">
	<h2>Reportar Ticket</h2>
	<h3>
		Esta informacion sera confirmada por el administrador, para <br />
		verificar la existencia fisica del ticket antes de anularlo.
	</h3>
	<?php echo $form->create('Ticket',array('action'=>'report'));?>
	<fieldset>
	<?php
		echo $form->input('number',array('label'=>'Numero'));
		echo $form->input('confirm',array('label'=>'Confirmacion'));
	?>
	</fieldset>
	<?php echo $form->end('Reportar');?>
</div>