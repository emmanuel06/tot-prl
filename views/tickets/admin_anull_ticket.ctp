<script>
	var details = '<?php echo $html->url(array('controller'=>'ticket_details','action'=>'view')) ?>';
	var load_img = '<?php echo $html->image('loading_small.gif') ?>';
	var myurl = '<?php echo $html->url(array('action'=>'anull_ticket')) ?>';
	$(function(){
		$("#buscar").click(function(){
			var tikid = $("#number").val();
			location = myurl + '/' +tikid;
		});

		$( ".view_par" ).button({
		    icons: {
				primary: "ui-icon-zoomin"
			}
		}).click(function(){
			var ider = $(this).attr('id');
			$(this).parents('td').html(load_img).load(details + '/' + ider);
		});
	});
</script>
<style>
fieldset{
	width: 300px;
	
}
input{
	font-size:140%;
}
label{
	font-size:140%;
}
</style>
<div class="tickets pay">
	<h2>Anular Ticket</h2>
	<table style="width: 200px">
		<tr>
			<td>
				<?php
				echo $form->input('number',array('label'=>'Numero','value'=>$id));
				?>
			</td>
		</tr>
		<tr>
			<td>
				<?php
				echo $form->button('Buscar',array('id'=>'buscar'));
				?>
			</td>
		</tr>
	</table>
	<?php 	
	if(!empty($ticket)){
	?>
		<table cellpadding="0" cellspacing="0" style="width: 500px">
			<tr>
				<th>Numero</th>
				<td><?php echo $ticket['Ticket']['id']; ?></td>
				<td rowspan="8">				
					<button class="view_par" id="<?php echo $ticket['Ticket']['id'] ?>">
						Ver Parlays
					</button>
				</td>
			</tr>
			<tr>
				<th>Fecha</th>
				<td><?php echo $dtime->date_from_created($ticket['Ticket']['created']); ?></td>
			</tr>
			<tr>
				<th>Hora</th>
				<td><?php echo $dtime->hour_exact_created($ticket['Ticket']['created']); ?></td>
			</tr>
			<tr>
				<th>Taquilla</th>
				<td><?php echo $ticket['Profile']['name'] ?></td>
			</tr>
			<tr>
				<th>Monto</th>
				<td><?php echo number_format($ticket['Ticket']['amount'],2,',','.'); ?></td>
			</tr>
			<tr>
				<th>Premio</th>
				<td><?php echo number_format($ticket['Ticket']['prize'],2,',','.'); ?></td>
			</tr>
			<tr>
				<th>Estado</th>
				<td><?php echo $ticket['TicketStatus']['name']; ?></td>
			</tr>
			<tr>
				<th>Pagado</th>
				<td><?php if($ticket['Ticket']['payed'] == 1) echo "PAGADO"; else echo "SIN PAGAR" ?></td>
			</tr>
			<tr>
				<td colspan="3">
				<?php 
				if($ticket['Ticket']['ticket_status_id'] == 6)
					echo "Ticket YA ANULADO";
				else{
					echo $form->create('Ticket',array('action'=>'anull_ticket'));
					echo $form->input('id',array('value'=>$ticket['Ticket']['id']));
					echo $form->end('Anular');
				}
					
				?>
				</td>
			</tr>
		</table>
	<?php 
	}
	?>
</div>