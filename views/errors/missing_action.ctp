<h2><?php echo sprintf(__('Metodo fallido en %s', true), $controller);?></h2>
<p class="error">
	<strong>Causa: </strong>
	<?php echo sprintf(__('La accion %1$s no esta definida en el controlador %2$s', true), "<em>" . $action . "</em>", "<em>" . $controller . "</em>");?>
</p>