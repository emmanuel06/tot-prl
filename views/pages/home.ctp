<div>
	<div id="loginpass" style="width:400px; margin-left: 20%;">
		<?php
		$session->flash('auth');
		if(empty($authUser)){
			echo $form->create('User',array('action'=>"login")); 
			?>
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
				<tr>
					<td colspan="2"><?php echo $form->submit("Entrar"); ?></td>
				</tr>
				<tr>
					<td colspan="2">
						<?php echo $html->link("Desbloquear",array('controller'=>'users','action'=>'unblock')) ?>
					</td>
				</tr>
			</table>
		<?php 
			echo $form->end(); 
		}else{
			echo "<h2>Usuario ".$authUser['profile_name']." logueado</h2>";
		}
		?>
	</div>
</div>