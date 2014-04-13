<div class="profiles form">
<?php 
	//pr($profile);
?>
<table style="width:600px">
	<tr>
		<th colspan="2">
			Datos generales
		</th>
		<th rowspan="7">
			|
		</th>
		<th colspan="2">
			Limites
		</th>
	</tr>
	<tr>
		<td>Nombre</td>
		<td><?php echo $profile['Profile']['name'] ?></td>
		<td>Parlays Maximos</td>
		<td><?php echo $profile['Profile']['max_parlays'] ?></td>
	</tr>
	<tr>
		<td>Telefono</td>
		<td><?php echo $profile['Profile']['phone_number'] ?></td>
		<td>Monto Maximo Derecho</td>
		<td><?php echo $profile['Profile']['max_amount_straight'] ?></td>
	</tr>
	<tr>
		<td>Grupo</td>
		<td><?php echo $profile['Group']['name'] ?></td>
		<td>Monto Maximo Parlay</td>
		<td><?php echo $profile['Profile']['max_amount_parlay'] ?></td>
	</tr>
	<tr>
		<td>Grado</td>
		<td><?php echo $profile['Role']['name'] ?></td>
		<td>Premio Maximo</td>
		<td><?php echo $profile['Profile']['max_prize'] ?></td>
	</tr>
	<tr>
		<td>Usuario</td>
		<td><?php echo $profile['User']['username'] ?></td>
		<td>Porcentaje Ventas</td>
		<td><?php echo $profile['Profile']['pct_sales'] ?></td>
	</tr>
	<tr>
		<td>Email</td>
		<td><?php echo $profile['User']['email'] ?></td>
		<td>Porcentaje Ganancias</td>
		<td><?php echo $profile['Profile']['pct_won'] ?></td>
	</tr>
</table>
</div>