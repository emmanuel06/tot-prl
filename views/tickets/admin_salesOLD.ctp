<script>
	var ventas = '<?php echo $html->url(array('action'=>'sales')) ?>';
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
			var profile = $("#profiles").val(); 
			
			location = ventas + '/' + since + '/' + until + '/' + profile;
		});
		
		$( ".update button" ).button({
		    icons: {
				primary: "ui-icon-clock"
			}
		}).click(function(){
			$(this).parent().html(load_img).load(upd + '/' + sin + '/' + unt);
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
.update{
	float:right;
	height: auto;
	border: 1px solid blue;
}
</style>
<div class="tickets index">
	<h2>Ventas</h2>
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
				echo $form->input('profiles',array('options'=>$profiles,'label'=>'Taquilla','empty'=>array(0=>'Seleccione'),'value'=>$profile))
			?>
			</td>
			<td>
			<?php
				echo $form->button('Filtrar',array('id'=>'filtrar'))
			?>
			</td>
			
		</tr>
		
	</table>
	<?php 
	//pr($totals); pr($winners); pr($backs);
	?>
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
	<div class="part_number update">					
		<button id="update_button">
			Actualizar tickets
		</button>
	</div>
	
	

</div>