<script>
$(function() {
		
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
<div class="sports index">
<h2>Deportes</h2>
<p><?php
echo $paginator->counter(array(
'format' => "Pagina %page% de %pages%, mostrando %current% registros de %count% totales, empezando en %start%, terminando en %end%"));
?></p>
<table cellpadding="0" cellspacing="0" style="width:70%">
<tr>
	<th><?php echo $paginator->sort('Nombre','name');?></th>
	<th><?php echo $paginator->sort('Activo','enable');?></th>
	<th><?php echo $paginator->sort('Empata','get_draw');?></th>
	<th><?php echo $paginator->sort('Apuesta Ppal','default_type');?></th>
	<th class="actions">Acciones</th>
</tr>
<?php
$i = 0;
foreach ($sports as $sport):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $sport['Sport']['name']; ?>
		</td>
		<td>
			<?php if($sport['Sport']['enable'] == 1) echo "SI"; else echo "NO"; ?>
		</td>
		<td>
			<?php if($sport['Sport']['get_draw'] == 1) echo "SI"; else echo "NO"; ?>
		</td>
		<td>
			<?php 
				if($sport['Sport']['default_type'] == 1)
					echo "Moneyline";
				else
					echo "Runline";
			?>
		</td>
		<td class="actions">
			<div class="act_each">				
				<button class="to_redir" href="<?php echo $html->url(array("action"=>"set_enable",$sport['Sport']['id'],$sport['Sport']['enable'])) ?>">
					<?php if($sport['Sport']['enable'] == 1) echo "Desactivar"; else echo "Activar"; ?>
				</button>
				<button class="to_modal" href="<?php echo $html->url(array("action"=>"edit",$sport['Sport']['id'])) ?>">
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
		Nuevo Deporte
	</button>
</div>
