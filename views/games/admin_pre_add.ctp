<script>
$(document).ready(function() {
	var toLoad = '<?php echo $html->url(array("controller"=>"leagues","action"=>"show_by_sport_id"))?>';
	var theHtml = '<?php echo $html->image('loader.gif')?>';
	
    $(".categ").click(function() {
		$("#subcats_part").html("<span style='font-size:200%; color:#666;'>Cargando... </span>" + theHtml);
		$("#subcats_part").load(toLoad + "/" + '<?php echo $date ?>' + "/" + $(this).attr('id'));
		return false;
	});
});
</script>
<div style="height:600px">
	<h2>Seleccionar Liga</h2>
	<div style="width:25%; clear:left; float:left;">
		<ul>
		<?php
			foreach ($sports as $sk => $sv){
				echo "<li style='margin-top:15px'>".$html->link($sv, array('action'=>'#'),array('id'=>$sk,'class'=>'categ'))."</li>";
			}
		?>
		</ul>	
	</div>
	<div style="width:65%; clear:right; float:right;" id="subcats_part">
		<span style="font-size:200%; color:#666;">Seleccione Categoria.</span>
	</div>	
</div>