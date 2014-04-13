<script>
$(function() {
		
	$("#panel_look").dialog({
		autoOpen: false,
		bgiframe: true,		
		modal: true,
		height: 300,
		width: 400,
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
            primary: "ui-icon-home"
        }
    });

	$( ".act_each button" ).button({
	    icons: {
			primary: "ui-icon-pencil"
		}
	});
		
});	
</script>
<div class="groups index">
<h2>Grupos</h2>
<p><?php
echo $paginator->counter(array(
'format' => "Pagina %page% de %pages%, mostrando %current% registros de %count% totales, empezando en %start%, terminando en %end%"));
?></p>
<table cellpadding="0" cellspacing="0" style="width:70%">
<tr>
	<th><?php echo $paginator->sort('Nombre','name');?></th>
	<th><?php echo $paginator->sort('Lugar','location');?></th>
	<th><?php echo $paginator->sort('Clave','passkey');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($groups as $group):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $group['Group']['name']; ?>
		</td>
		<td>
			<?php echo $group['Group']['location']; ?>
		</td>
		<td>
			<?php echo $group['Group']['passkey']; ?>
		</td>
		<td class="actions">
			<div class="act_each">				
				<button class="to_modal" href="<?php echo $html->url(array("action"=>"edit",$group['Group']['id'])) ?>">
					Editar
				</button>
			</div>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $paginator->prev('<< '."anterior", array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $paginator->numbers();?>
	<?php echo $paginator->next("siguiente".' >>', array(), null, array('class' => 'disabled'));?>
</div>
<div class="act_general">
	<button class="to_modal" href="<?php echo $html->url(array("action"=>"add")) ?>">
		Nuevo Grupo
	</button>
</div>