<script>
	var thisurl = '<?php echo $html->url(array('action'=>'anull')) ?>';
	var upd = '<?php echo $html->url(array('action'=>'update_statuses')) ?>';
	var load_img = '<?php echo $html->image('loading_small.gif') ?>';
	var sin = '<?php echo $since ?>';
	var unt = '<?php echo $until ?>';
	
	$(function() {

		$("#since").datepicker();
		$("#until").datepicker();
		
		$("#filtrar").click(function(){
			var since = $("#since").val(); 
			var until = $("#until").val();
			
			location = thisurl + '/' + since + '/' + until;
		});	
	});	
</script>
<style>
#since{
	width: 100px
}
#until{
	width: 100px
}
</style>
<div class="tickets index">
	<h2>Anular Tickets</h2>
	<table style="width:400px">
		<tr>
			<td>
			<?php
				echo $form->input('since',array('label'=>'Desde','value'=>$since))
			?>
			</td>
			<td>
			<?php
				echo $form->input('until',array('label'=>'Hasta','value'=>$until))
			?>
			</td>
			<td>
			<?php
				echo $form->button('filtrar',array('value'=>'Filtrar','id'=>'filtrar'))
			?>
			</td>
			
		</tr>
	</table>	

	<table>
		<tr>
			<th>Taquilla</th>
			<th>Tickets Reportados</th>
			<th>Anular</th>
			<th>Ver detalles</th>
		</tr>
		<?php
		foreach($reported as $r){
		?>
			<tr>
				<td><?php echo $r['Profile']['name'] ?></td>
				<td><?php echo $r[0]['total'] ?></td>
				<td>
				<?php 
				echo $html->link("Anular Todos",array('action'=>'set_anulled',$r['Ticket']['profile_id'],$since,$until));
				?>
				</td>
				<td>
				<?php 
				echo $html->link("Detalles",array('action'=>'tickets',$since,$until,$r['Ticket']['profile_id'],5));
				?>
				</td>
			</tr>
		<?php 	
		}
		?>
	</table>
</div>