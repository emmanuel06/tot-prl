<script>
$(function(){
	$("#date").datepicker({dateFormat: "yy-mm-dd"}).attr('readonly',true);
	$("#filt").button({ icons: {primary: "ui-icon-zoomin"}}).css('font-size','140%').click(function(){
		var da = $("#date").val();
		var ho = $("#hour").val();
		var us = $("#user").val();
		var ty = $("#type").val();
		var ta = $("#table").val();
		location = '<?php echo $html->url(array("action"=>"index","/")) ?>' + '/' + da + '/' + ho + '/' + us + '/' + ty + '/' + ta;
	});
	
	$("#panel_look").dialog({
		autoOpen: false,
		bgiframe: true,		
		modal: true,
		height: 500,
		width: 750,
		resizable: true
	});
	
	$(".details").button({icons:{primary:"ui-icon-zoomin"}}).click(function(){
		$('#panel_look').html('<?php echo $html->image("loading.gif")?>');
		$('#panel_look').dialog({title:$(this).text()});
		$('#panel_look').load($(this).attr('href'));
		$('#panel_look').dialog('open');
	});
});	
</script>
<div class="operations index">
<h2>Auditoria de movimientos</h2>
<table style="width:650px; font-size: 80%">
	<tr>
		<td><?php 
		echo $form->input("date",array('label'=>'Fecha','value'=>$date,'readonly'=>'readonly','style'=>'width:90px'));
		?></td>
		<td><?php 
		echo $form->input("hour",array('label'=>'Horas','value'=>$hour,'options'=>$hours,'empty'=>array(0=>"Todo el Dia")));
		?></td>
		<td><?php 
		echo $form->input("user",array('label'=>'Usuario','value'=>$profile_id,'options'=>$profiles,'empty'=>array(0=>"Todos"))) 	
		?></td>
		<td><?php 
		echo $form->input("type",array('label'=>'Tipo','value'=>$optype_id,'options'=>$op_types,'empty'=>array(0=>"Todos"))) 	
		?></td>
		<td><?php 
		echo $form->input("table",array('label'=>'Tabla','value'=>$table,'options'=>$tables,'empty'=>array(0=>"Todas"))) 	
		?></td>
		<td><button id="filt">Filtrar</button></td>
	</tr>
</table>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th>Id</th>
	<th>Fecha</th>
	<th>Hora</th>
	<th>Usuario</th>
	<th>Tipo</th>
	<th>Tabla</th>
	<th>Ref</th>
	<th>Detalles</th>
</tr>
<?php
$i = 0;
foreach ($operations as $operation):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $operation['Operation']['id'] ?>
		</td>
		<td>
			<?php echo $dtime->date_from_created($operation['Operation']['created']) ?>
		</td>
		<td>
			<?php echo $dtime->hour_exact_created($operation['Operation']['created']) ?>
		</td>
		<td>
			<?php echo $operation['Profile']['name'] ?>
		</td>
		<td>
			<?php echo $operation['OperationType']['name'] ?>
		</td>
		<td>
			<?php echo $operation['Operation']['metainf']; ?>
		</td>
		<td><?php 
		if($operation['Operation']['model_id'] != ""){
			$modelit = "Juego ";	
			if($operation['Operation']['metainf'] == "Ticket")
				$modelit = "Ticket ";	
				
			if($table != 0){
			?>
				<button class='details' href="<?php echo $html->url(array('action'=>"details",$table,$operation['Operation']['model_id']))?>">
					<?php echo "Detalles del $modelit ".$operation['Operation']['model_id'] ?>		
				</button>
			<?php
			}else
				echo $modelit." ".$operation['Operation']['model_id'];	
		}
		?></td>
		<td>
			<?php echo $operation['Operation']['metadata']; ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '.__('previous', true), array('url'=>array('action'=>'index',$date,$hour,$profile_id,$optype_id,$table)), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers(array('url'=>array('action'=>'index',$date,$hour,$profile_id,$optype_id,$table)));?>
	<?php echo $paginator->next(__('next', true).' >>', array('url'=>array('action'=>'index',$date,$hour,$profile_id,$optype_id,$table)), null, array('class' => 'disabled'));?>
</div>