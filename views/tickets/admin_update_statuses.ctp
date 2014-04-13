<script>
$(function(){
	$("#recarga").click(function(){
		location.reload(); 
		return false;
	});
});
</script>
<div>
	<b><?php echo $tickets ?> Tickets Actualizados</b><br/>
	Empezando por el <?php echo $first?> <br/>
	Terminando por el <?php echo $last?> <br/>
	<a href ="#" id ="recarga">Recargar pantalla de ventas</a>
</div>