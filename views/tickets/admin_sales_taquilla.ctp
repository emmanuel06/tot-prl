<script>
	var ventas = '<?php echo $html->url(array('action'=>'sales_taquilla')) ?>';
	var load_img = '<?php echo $html->image('loading_small.gif') ?>';
	
	$(function() {

		$("#since").datepicker().attr('readonly',true);
		$("#until").datepicker().attr('readonly',true);
		
		$("#filtrar").click(function(){
			var since = $("#since").val(); 
			var until = $("#until").val();
			
			location = ventas + '/' + since + '/' + until;
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
.part_number{
	float: left;
	clear: none;
	margin-left: 10px;
	margin-top: 10px;
	padding: 5px;
	border: 1px solid black;
	height: 70px;
}
.part_number h3{
	color: blue;
}
</style>
<div class="tickets index">
	<h2>Ventas</h2>
	<table style="width:350px">
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
				echo $form->button('Filtrar',array('id'=>'filtrar'))
			?>
			</td>
			
		</tr>
		
	</table>
	<div class="part_number" id="total">
		<h3>Total Ventas</h3>
		<i>Tickets:</i> <b><?php echo $totals[0]['total'] ?></b> <br />
		<i>Monto:</i> <b>
			<?php 
			echo $number->currency($totals[0]['amounts'],"Bs ",array('thousands'=>'.','decimals'=>',')) 
			?></b>
	</div>
	<div class="part_number" id="ganador">
		<h3>Ganadores</h3>
		<i>Tickets:</i> <b><?php echo $winners[0]['total'] ?></b> <br />
		<i>Premio:</i> <b>
			<?php 
			echo $number->currency($winners[0]['prices'],"Bs ",array('thousands'=>'.','decimals'=>',')) 
			?></b>
	</div>
	<div class="part_number" id="devol">
		<h3>Devoluciones</h3>
		<i>Tickets:</i> <b><?php echo $backs[0]['total'] ?></b> <br />
		<i>Monto:</i> <b>
			<?php 
			echo $number->currency($backs[0]['amounts'],"Bs ",array('thousands'=>'.','decimals'=>',')) 
			?></b>
	</div>
</div>