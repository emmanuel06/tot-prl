<h2>Tabla en Base de Datos no encontrada</h2>
<p class="error">
	<strong>Causa: </strong>
	<?php echo sprintf(__('Tabla %1$s para modelo %2$s no encontrada.', true),"<em>" . $table . "</em>",  "<em>" . $model . "</em>");?>
</p>