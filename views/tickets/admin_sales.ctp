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
			var group = $("#group").val(); 
			
			location = ventas + '/' + since + '/' + until + '/' + group;
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
				echo $form->input('group',array('options'=>$groups,'label'=>'Grupo','empty'=>array(0=>'Seleccione'),'value'=>$group))
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
	<div class="part_number" id="util">
		<h3>Utilidad</h3>
		<i>Monto:</i> <b>
			<?php 
			echo $number->currency(($totals[0]['amounts'] - $winners[0]['prices']),"Bs ",array('thousands'=>'.','decimals'=>',')) 
			?></b>
	</div>
	<div class="part_number update">					
		<button id="update_button">
			Actualizar tickets
		</button>
	</div>
	<div style="clear:both; margin-top:15px"></div>
	<h3>Ventas Individuales</h3>
	<p><?php
	echo $paginator->counter(array(
	'format' => "Pagina %page% de %pages%, mostrando %current% registros de %count% totales, empezando en %start%, terminando en %end%"));
	?></p>
	<table style="font-size:120%">
		<tr>
			<th rowspan="2">Taquilla</th><th colspan="2">Ventas</th>
			<th colspan="2">Ganadores</th><th colspan="2">Devueltos</th><th rowspan="2">Utilidad Total</th>
		</tr>
		<tr>
			<th>Tickets</th><th>Monto</th><th>Tickets</th><th>Monto</th>
			<th>Tickets</th><th>Monto</th>
		</tr>
		<?php 
		$i = 0;
		foreach($profiles as $p){
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
			
			$tik_sold = 0;
			if(!empty($final_amounts[$p['Profile']['id']]['Sold']['tickets']))
				$tik_sold = $final_amounts[$p['Profile']['id']]['Sold']['tickets']; 
			
			$amo_sold = 0;
			if(!empty($final_amounts[$p['Profile']['id']]['Sold']['amount']))
				$amo_sold = $final_amounts[$p['Profile']['id']]['Sold']['amount']; 
			
			$tik_win = 0;
			if(!empty($final_amounts[$p['Profile']['id']]['Win']['tickets']))
				$tik_win = $final_amounts[$p['Profile']['id']]['Win']['tickets'];
				
			$amo_win = 0;
			if(!empty($final_amounts[$p['Profile']['id']]['Win']['amount']))
				$amo_win = $final_amounts[$p['Profile']['id']]['Win']['amount'];
				
			$tik_bak = 0;
			if(!empty($final_amounts[$p['Profile']['id']]['Back']['tickets']))
				$tik_bak = $final_amounts[$p['Profile']['id']]['Back']['tickets'];
			
			$amo_bak = 0;
			if(!empty($final_amounts[$p['Profile']['id']]['Back']['amount']))
				$amo_bak = $final_amounts[$p['Profile']['id']]['Back']['amount']; 
			
			$util = $amo_sold - $amo_win;			
		?>
			<tr<?php echo $class;?>>
				<td><?php echo $p['Profile']['name'] ?></td>
				<td><?php echo $tik_sold ?></td>
				<td><?php echo $number->currency($amo_sold,"Bs ",array('thousands'=>'.','decimals'=>',')) ?></td>
				<td><?php echo $tik_win	?></td>
				<td><?php echo $number->currency($amo_win,"Bs ",array('thousands'=>'.','decimals'=>',')) ?></td>
				<td><?php echo $tik_bak	?></td>
				<td><?php echo $number->currency($amo_bak,"Bs ",array('thousands'=>'.','decimals'=>',')) ?></td>
				<td>
					<b><?php echo $number->currency($util,"Bs ",array('thousands'=>'.','decimals'=>','))?></b>
				</td>
			</tr>
		<?php 
		}
		?>
	</table>
	<div class="paging">
		<?php echo $paginator->prev('<< anterior', array('url'=>array('action'=>'sales',$since,$until,$group)), null, array('class'=>'disabled'));?>
	 | 	<?php echo $paginator->numbers(array('url'=>array('action'=>'sales',$since,$until,$group)));?>
		<?php echo $paginator->next('siguiente >>', array('url'=>array('action'=>'sales',$since,$until,$group)), null, array('class' => 'disabled'));?>
	</div>

</div>