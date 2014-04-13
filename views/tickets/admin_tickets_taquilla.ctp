<script>
var details = '<?php echo $html->url(array('controller'=>'ticket_details','action'=>'view')) ?>';
var ventas = '<?php echo $html->url(array('action'=>'tickets_taquilla')) ?>';
var load_img = '<?php echo $html->image('loading_small.gif') ?>';
$(function() {
	$("#since").datepicker();
	$("#until").datepicker();
	
	$("#filtrar").click(function(){
		var since = $("#since").val(); 
		var until = $("#until").val(); 
		var status = $("#ticket_status").val(); 
		var number = $("#nro").val(); 
		
		location = ventas + '/' + since + '/' + until + '/' + status + '/' + number;
	});

	$(".view_par").click(function(){
		var ider = $(this).attr('id');
		$(this).parents('td').html(load_img).load(details + '/' + ider);
	});

	$( ".act_each button" ).button({
	    icons: {
			primary: "ui-icon-zoomin"
		},
		text: false
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
#id{
	width: 100px
}
</style>
<div class="pitchers index">
<h2>Tickets</h2>
<table>
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
			echo $form->input('ticket_status',array('options'=>$ticket_statuses,'label'=>'Estado','empty'=>array(0=>'Seleccione'),'value'=>$status))
		?>
		</td>
		<td>
		<?php
			echo $form->input('nro',array('label'=>'Numero','value'=>$nro))
		?>
		</td>
		<td>
		<?php
			echo $form->button('filtrar',array('value'=>'Filtrar','id'=>'filtrar'))
		?>
		</td>
		
	</tr>
	
</table>
<p><?php
//pr($tickets); 
echo $paginator->counter(array(
'format' => "Pagina %page% de %pages%, mostrando %current% registros de %count% totales, empezando en %start%, terminando en %end%"));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('Numero','id');?></th>
	<th><?php echo $paginator->sort('Fecha','created');?></th>
	<th><?php echo $paginator->sort('Hora','created');?></th>
	<th><?php echo $paginator->sort('Monto','amount');?></th>
	<th><?php echo $paginator->sort('Premio','prize');?></th>
	<th><?php echo $paginator->sort('Estado','ticket_status_id');?></th>
	<th><?php echo $paginator->sort('Pagado','payed');?></th>
	<th class="actions">Acciones</th>
</tr>
<?php
$i = 0;
foreach ($tickets as $ticket):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $ticket['Ticket']['id']; ?>
		</td>
		<td>
			<?php echo $dtime->date_from_created($ticket['Ticket']['created']); ?>
		</td>
		<td>
			<?php echo $dtime->hour_exact_created($ticket['Ticket']['created']); ?>
		</td>
		<td>
			<?php echo number_format($ticket['Ticket']['amount'],2,',','.'); ?>
		</td>
		<td>
			<?php echo number_format($ticket['Ticket']['prize'],2,',','.'); ?>
		</td>
		<td>
			<?php echo $dtime->color_stat($ticket['TicketStatus']['name']); ?>
		</td>
		<td>
			<?php if($ticket['Ticket']['payed'] == 1) echo "PAGADO"; else echo "SIN PAGAR" ?>
		</td>
		<td class="actions">
			<div class="act_each">				
				<button class="view_par" id="<?php echo $ticket['Ticket']['id'] ?>">
					Ver Parlays
				</button>
			</div>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< anterior', array('url'=>array('action'=>'tickets_taquilla',$since,$until,$status,$nro)), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers(array('url'=>array('action'=>'tickets_taquilla',$since,$until,$status,$nro)));?>
	<?php echo $paginator->next('siguiente >>', array('url'=>array('action'=>'tickets_taquilla',$since,$until,$status,$nro)), null, array('class' => 'disabled'));?>
</div>