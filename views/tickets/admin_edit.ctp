<div class="tickets pay">
	<h3>
		Datos del ticket nro. <?php echo $ticket['id'] ?>
	</h3>
	<?php 
	echo $form->create('Ticket',array('action'=>'edit'));
		echo $form->input('id',array('value' => $ticket['id']));
		?>
		<table>
			<tr>
				<td>Estado de Ticket</td>
				<td>
				<?php 
				echo $form->input('ticket_status_id',array(
					'value'=>$ticket['ticket_status_id'],'label'=>'','options'=>$ticket_statuses
				));
				?>
				</td>
			</tr>
			<tr>
				<td>Pagado</td>
				<td>
				<?php
				$checked = false;
				if($ticket['payed'] == 1)
					$checked = true;
				
				echo $form->input('payed',array('label'=>'','checked'=>$checked));
				?>
				</td>
			</tr>
		</table>
	<?php echo $form->end('Guardar');?>
</div>