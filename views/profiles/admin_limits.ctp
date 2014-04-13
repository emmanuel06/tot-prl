<div class="profiles form">
<?php 
	echo $form->create('Profile',array('action'=>'limits'));
	echo $form->input('id')
?>
	<h3>Configuracion de <?php echo $this->data['Profile']['name'] ?></h3>
	<table>
		<tr>
			<td><?php echo $form->input('max_parlays',array('label'=>'Parlays Maximos')) ?></td>
			<td><?php echo $form->input('max_prize',array('label'=>'Maximo Premio')) ?></td>
		</tr>
		<tr>
			<td><?php echo $form->input('max_amount_straight',array('label'=>'Maximo Monto Derecho')) ?></td>
			<td><?php echo $form->input('max_amount_parlay',array('label'=>'Maximo Monto Parlay')) ?></td>
		</tr>
		<tr>
			<td><?php echo $form->input('pct_sales',array('Porcentaje Ventas')) ?></td>	
			<td><?php echo $form->input('pct_won',array('Porcentaje Ganancias')) ?></td>
		</tr>
	</table>
<?php 
	echo $form->end('Guardar');
?>
</div>