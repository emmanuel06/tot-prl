<script>
$(function() {
	
	$("#sport_id").change(function(){
		var sp = $(this).val();
		
		location = '<?php echo $html->url(array("action"=>"index","/")) ?>' + '/' + sp;
	});
	
	$("#league_id").change(function(){
		
		var le = $(this).val();
		var sp = $("#sport_id").val();
		
		location = '<?php echo $html->url(array("action"=>"index","/")) ?>' + '/' + sp + '/' + le;
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
<div class="teams index">
<h2>Equipos</h2>
<table style="width: 600px">
	<tr>
		<td>
		<?php
			echo $form->input('sport_id',array('options'=>$sports,'label'=>'Deporte','empty'=>array(0=>'Seleccione'),'value'=>$sport_id))
		?>
		</td>
		<td>
		<?php
			echo $form->input('league_id',array('options'=>$leagues,'label'=>'Liga','empty'=>array(0=>'Seleccione'),'value'=>$league_id))
		?>
		</td>
	</tr>
</table>
<p><?php
echo $paginator->counter(array(
'format' => "Pagina %page% de %pages%, mostrando %current% registros de %count% totales, empezando en %start%, terminando en %end%"));
?></p>
<table cellpadding="0" cellspacing="0" style="width:70%">
<tr>
	<th><?php echo $paginator->sort('Nombre','name');?></th>
	<th><?php echo $paginator->sort('Abrev','abrev');?></th>
	<th><?php echo $paginator->sort('Otro Nombre','alt_name');?></th>
	<th><?php echo $paginator->sort('Gan - Per','win_loses');?></th>
	<th><?php echo $paginator->sort('Liga','league_id');?></th>
	<th><?php echo $paginator->sort('Deporte','sport');?></th>
	<th><?php echo $paginator->sort('Activo','enable');?></th>
	<th class="actions">Acciones</th>
</tr>
<?php
$i = 0;
foreach ($teams as $team):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $team['Team']['name']; ?>
		</td>
		<td>
			<?php echo $team['Team']['abrev']; ?>
		</td>
		<td>
			&nbsp;<?php echo $team['Team']['alt_name']; ?>
		</td>
		<td>
			&nbsp;<?php echo $team['Team']['win_loses']; ?>
		</td>
		<td>
			<?php echo $team['League']['name']; ?>
		</td>
		<td>
			<?php echo $team['League']['Sport']['name']; ?>
		</td>
		<td>			
			<?php if($team['Team']['enable'] == 1) echo "SI"; else echo "NO"; ?>
		</td>
		<td class="actions">
			<div class="act_each">				
				<button class="to_redir" href="<?php echo $html->url(array("action"=>"set_enable",$team['Team']['id'],$team['Team']['enable'])) ?>">
					<?php if($team['Team']['enable'] == 1) echo "Desactivar"; else echo "Activar"; ?>
				</button>
				<button class="to_modal" href="<?php echo $html->url(array("action"=>"edit",$team['Team']['id'])) ?>">
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
<?php
if($league_id != "" && $league_id != 0){
?>
	<div class="act_general">
		<button class="to_modal" href="<?php echo $html->url(array("action"=>"add",$league_id)) ?>">
			Nuevo Equipo para <?php echo $leagues[$league_id] ?>
		</button>
	</div>
<?php
}
?>