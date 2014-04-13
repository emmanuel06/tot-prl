<div id="loginpass" style="width:400px; margin-left: 20%;">
	<?php
	if(empty($authUser)){
		echo $form->create('User',array('action'=>"login"));
		?>
		<fieldset>
	 		<legend>Ingresar</legend>
			<table width="100%" border="1">
				<tr>
					<td style="text-align:right">Usuario</td>
					<td align="left"><?php echo $form->input('username',array('label'=>'')); ?></td>
				</tr>
				<tr>
					<td style="text-align:right">Contrase&ntilde;a</td>
					<td align="left">
						<?php echo $form->input('password',array('label'=>'')); ?>
					</td>
				</tr>
			</table>      
		</fieldset>
	<?php 
		echo $form->end('Ingresar'); 
	}else{
		echo "<h2>Usuario ".$authUser['profile_name']." logueado</h2>";
	}
	?>
</div>