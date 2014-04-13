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
<script>
var myurl = '<?php echo $html->url(array('action'=>'outdated')) ?>';
$(function(){
	$("#TicketDate").datepicker().change(function(){
		location = myurl + "/" + $(this).val();
	});
});
</script>
<div class="tickets pay">
	<h2>Tickets Vencidos</h2>
	<h3>
		Los tickets GANADORES NO PAGADOS del <?php echo $dtime->date_spa_mon_abr($date) ?> <br />
		son <?php echo $outdated ?>. &iquest;Desea colocarlos como vencidos?
	</h3>
	<?php echo $form->create('Ticket',array('action'=>'outdated'));?>
	<fieldset>
	<?php
		echo $form->input('date',array('label'=>'Fecha','value'=>$date,'type'=>'text'));
	?>
	</fieldset>
	<?php echo $form->end('Colocar Vencidos');?>
</div>