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
	<h2>Anular Ticket</h2>
	<?php echo $form->create('Ticket',array('action'=>'anull_ticket'));?>
	<fieldset>
	<?php
		echo $form->input('number',array('label'=>'Numero'));
	?>
	</fieldset>
	<?php echo $form->end('Anular');?>
</div>