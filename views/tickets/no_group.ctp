<div style="width:400px; margin-left: 20%;">
	<?php
	echo $form->create('Ticket',array('action'=>"sales_group")); 
	?>
	<fieldset>
 		<legend>Ventas de Grupo</legend>
		<table width="100%" border="1">
			<tr>
				<td style="text-align:right">Nombre de Grupo</td>
				<td align="left"><?php echo $form->input('group',array('label'=>'')); ?></td>
			</tr>
			<tr>
				<td style="text-align:right">Clave Secreta</td>
				<td align="left">
					<?php echo $form->input('passkey',array('label'=>'','type'=>'password')); ?>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $form->submit("Entrar"); ?></td>
			</tr>
		</table>      
	</fieldset>
	<?php 
	echo $form->end(); 
	?>
</div>