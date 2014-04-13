<script>
$(function() {
		
	$("#panel_look").dialog({
		autoOpen: false,
		bgiframe: true,		
		modal: true,
		height: 350,
		width: 600,
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
	        primary: "ui-icon-person"
	    }
	}).next().button({
	    icons: {
	   		primary: "ui-icon-notice"
		}
	});

	$( ".act_each button" ).button({
	    icons: {
			primary: "ui-icon-contact"
		},
		text: false
	}).next().button({
	    icons: {
			primary: "ui-icon-wrench"
		},
		text: false
	}).next().button({
	    icons: {
			primary: "ui-icon-key"
		},
		text: false
	}).next().button({
	   	icons: {
			primary: "ui-icon-unlocked"
		},
		text: false
	}).next().button({
		icons: {
			primary: "ui-icon-locked"
		},
		text: false	
	});
	
	$("#filt").click(function(){
		var filt_url = '<?php echo $html->url(array("controller"=>"profiles","action"=>"index/")) ?>'
		var role_id = $("#role_id").val();
		var group_id = $("#group_id").val();
		var enable = $("#enable").val();
			
		var user_like = "none";
		if($("#user_like").val() != "")
			user_like = $("#user_like").val();
		
		location = filt_url + "/" + role_id + "/" + enable + "/" + group_id + "/" + user_like;
	});
	
});	
</script>
<div class="profiles index">
<h2>Usuarios</h2>
<table style="width:80%">
<tr>
	<th>Filtrar Por:</th>
	<td><?php 
		echo $form->input('role_id',array('options'=>$roles,'value'=>$role_id,'label'=>"Grados",'empty'=>array(0=>"Todos"),'class'=>'filter_input'))
	?></td>
	<td><?php 
		echo $form->input('group_id',array('options'=>$groups,'value'=>$group_id,'label'=>"Grupo",'empty'=>array(0=>"Todos"),'class'=>'filter_input'))
	?></td>
	<td><?php 
		echo $form->input('enable',array('options'=>$enables,'value'=>$enable,'label'=>"Estado",'class'=>'filter_input'))
	?></td>
	<td><?php 
		echo $form->input('user_like',array('label'=>"Nombre de Usuario",'value'=>$user_like,'class'=>'filter_input','style'=>'width: 180px;'))
	?></td>
	<td><?php 
		echo $form->button("Filtrar",array('id'=>'filt','style'=>'font-size:120%')) 
	?></td>
</tr>
</table>
<div class="act_general" style="margin-top:10px; margin-bottom:10px">
	<button class="to_modal" href="<?php echo $html->url(array("action"=>"add")) ?>">
		Nuevo Usuario
	</button>
	<button class="to_redir" href="<?php echo $html->url(array("action"=>"switch")) ?>">
		Switchear TAQUILLAS
	</button>
</div>
<p><?php
echo $paginator->counter(array(
'format' => "Pagina %page% de %pages%, mostrando %current% registros de %count% totales, empezando en %start%, terminando en %end%"));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('Nombre','Profile.name');?></th>
	<th><?php echo $paginator->sort('Usuario','username');?></th>
	<th><?php echo $paginator->sort('Creación','created');?></th>
	<th><?php echo $paginator->sort('Grado','role_id');?></th>
	<th><?php echo $paginator->sort('Grupo','group_id');?></th>
	<th><?php echo $paginator->sort('Ultimo logueo','last_login');?></th>
	<th><?php echo $paginator->sort('Activo','enable');?></th>
	<th class="actions">Acciones</th>
</tr>
<?php
$i = 0;
foreach ($profiles as $profile):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $profile['Profile']['name']; ?>
		</td>
		<td>
			<?php echo $profile['User']['username']; ?>
		</td>
		<td>
			<?php 
			echo $dtime->date_from_created($profile['User']['created']); 
			echo ", ";
			echo $dtime->hour_from_created($profile['User']['created']);
			?>
		</td>
		<td>
			<?php echo $profile['Role']['name']; ?>
		</td>		
		<td>
			<?php echo $profile['Group']['name']; ?>
		</td>		
		
		<td>
			<?php 
			if($profile['User']['last_login'] != ""){
				echo $dtime->date_from_created($profile['User']['last_login']); 
				echo ", ";
				echo $dtime->hour_from_created($profile['User']['last_login']);
			}else{
				echo "Nunca";
			}
			?>
		</td>
		<td>
			<?php if($profile['User']['enable'] == 1) echo "SI"; else echo "NO"; ?>
		</td>
		<td>
			<div class="act_each">				
				<button class="to_modal" href="<?php echo $html->url(array("action"=>"edit",$profile['Profile']['id'])) ?>">
					Editar Datos
				</button>
				<button class="to_modal" href="<?php echo $html->url(array("action"=>"limits",$profile['Profile']['id'])) ?>">
					Configurar Limites
				</button>
				<button class="to_modal" href="<?php echo $html->url(array("action"=>"change_pass",$profile['Profile']['id'])) ?>">
					Cambiar Password
				</button>
				<?php 
				$act = " style='display:none' ";
				$des = "";	
				
				if($profile['User']['enable'] == 0){
					$act = "";
					$des = " style='display:none' ";
				}				
				?>
				<button <?php echo $act ?>class="to_redir" href="<?php echo $html->url(array("controller"=>"users","action"=>"set_enable", $profile['Profile']['user_id'],$profile['User']['enable'])) ?>">
					Activar
				</button>
				<button <?php echo $des ?>class="to_redir" href="<?php echo $html->url(array("controller"=>"users","action"=>"set_enable", $profile['Profile']['user_id'],$profile['User']['enable'])) ?>">
					Desactivar
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