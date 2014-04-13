<script>
$(function() {

	$("#league_id").change(function(){
		var le = $(this).val();
		
		location = '<?php echo $html->url(array("action"=>"index","/")) ?>' + '/' + le;
	});
	
	$("#team_id").change(function(){
		
		var te = $(this).val();
		var le = $("#league_id").val();
		
		location = '<?php echo $html->url(array("action"=>"index","/")) ?>' + '/' + le + '/' + te;
		
	});

	
	$("#panel_look").dialog({
		autoOpen: false,
		bgiframe: true,		
		modal: true,
		height: 250,
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
			primary: "ui-icon-trash"
		}
	}).next().button({
    	icons: {
			primary: "ui-icon-wrench"
		}
	});
	
});	
</script>
<div class="pitchers index">
<h2><?php __('Pitchers');?></h2>
<table style="width: 600px">
	<tr>
		<td>
		<?php
			echo $form->input('league_id',array('options'=>$leagues,'label'=>'Liga','empty'=>array(0=>'Seleccione'),'value'=>$league_id))
		?>
		</td>
		<td>
		<?php
			echo $form->input('team_id',array('options'=>$teams,'label'=>'Equipo','empty'=>array(0=>'Seleccione'),'value'=>$team_id))
		?>
		</td>
	</tr>
	
</table>
<p><?php
echo $paginator->counter(array(
'format' => "Pagina %page% de %pages%, mostrando %current% registros de %count% totales, empezando en %start%, terminando en %end%"));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('Nombre','name');?></th>
	<th><?php echo $paginator->sort('Equipo','team_id');?></th>
	<th><?php echo $paginator->sort('Liga','league_id');?></th>
	<th class="actions">Acciones</th>
</tr>
<?php
$i = 0;
foreach ($pitchers as $pitcher):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $pitcher['Pitcher']['name']; ?>
		</td>
		<td>
			<?php echo $pitcher['Team']['name']; ?>
		</td>
		<td>
			<?php echo $pitcher['Team']['League']['name']; ?>
		</td>
		<td class="actions">
			<div class="act_each">				
				<button class="to_redir" href="<?php echo $html->url(array("action"=>"delete", $pitcher['Pitcher']['id'])) ?>">
					Borrar
				</button>
				<button class="to_modal" href="<?php echo $html->url(array("action"=>"edit", $pitcher['Pitcher']['id'])) ?>">
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
if($team_id != "" && $team_id != 0){
?>
	<div class="act_general">
		<button class="to_modal" href="<?php echo $html->url(array("action"=>"add",$team_id)) ?>">
			Nuevo Pitcher para <?php echo $teams[$team_id] ?>
		</button>
	</div>
<?php
}
?>