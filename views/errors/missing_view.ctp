<h2>Vista NO encontrada</h2>
<p class="error">
	<strong>Causa: </strong>
	<?php echo sprintf(__('La vista %1$s%2$s no existe.', true), "<em>" . $controller . "Controller::</em>", "<em>". $action . "()</em>");?>
</p>