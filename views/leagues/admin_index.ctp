<script>
$(function() {
	
	$("#sport_id").change(function(){
		var sp = $(this).val();
		
		location = '<?php echo $html->url(array("action"=>"index","/")) ?>' + '/' + sp;
	});

	$("#panel_look").dialog({
		autoOpen: false,
		bgiframe: true,		
		modal: true,
		height: 350,
		width:  400,
		resizable: true
	});
	
	$(".to_modal").click(function(){
		//quiero ver si el load lo puedo hacer con la progress bar
		$('#panel_look').html('<?php echo $html->image("loading.gif")?>');
		$('#panel_look').dialog({title:$(this).text()});
		$('#panel_look').load($(this).attr('href'));
		$('#panel_look').dialog('open');
	});

	$(".to_redir").click(function(){
		location = $(this).attr('href');
	});

	$( ".act_general button:first" ).button({
        icons: {
            primary: "ui-icon-circle-plus"
        }
    });

	$( ".act_each button" ).button({
	    icons: {
			primary: "ui-icon-power"
		}
	}).next().button({
    	icons: {
			primary: "ui-icon-wrench"
		}
	});
	
});	
</script>
<div class="leagues index">
<h2>Ligas</h2>
<p>
<table style="width: 300px">
	<tr>
		<td>
		<?php
			echo $form->input('sport_id',array('options'=>$sports,'label'=>'Deporte','empty'=>array(0=>'Seleccione'),'value'=>$sport_id))
		?>
		</td>
	</tr>
</table>

</p>
<p><?php
echo $paginator->counter(array(
'format' => "Pagina %page% de %pages%, mostrando %current% registros de %count% totales, empezando en %start%, terminando en %end%"));
?></p>
<table cellpadding="0" cellspacing="0" style="width:70%">
<tr>
	<th><?php echo $paginator->sort('Nombre','name');?></th>
	<th><?php echo $paginator->sort('Activo','enable');?></th>
	<th><?php echo $paginator->sort('Deporte','sport_id');?></th>
	<th class="actions">Acciones</th>
</tr>
<?php
$i = 0;
foreach ($leagues as $league):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $league['League']['name']; ?>
		</td>
		<td>
			<?php if($league['League']['enable'] == 1) echo "SI"; else echo "NO"; ?>
		</td>
		<td>
			<?php echo $league['Sport']['name']; ?>
		</td>
		<td class="actions">
			<div class="act_each">				
				<button class="to_redir" href="<?php echo $html->url(array("action"=>"set_enable",$league['League']['id'],$league['League']['enable'])) ?>">
					<?php if($league['League']['enable'] == 1) echo "Desactivar"; else echo "Activar"; ?>
				</button>
				<button class="to_modal" href="<?php echo $html->url(array("action"=>"edit",$league['League']['id'])) ?>">
					Editar
				</button>
			</div>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< anterior', array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next('siguiente >>', array(), null, array('class' => 'disabled'));?>
</div>
<div class="act_general">
	<button class="to_modal" href="<?php echo $html->url(array("action"=>"add")) ?>">
		Nueva Liga
	</button>
</div>