<script>
var league_id = '<?php echo $league ?>';
var exo_load = '<?php echo $html->url(array("controller"=>"odds","action"=>"exotics")) ?>';
var odd_url = '<?php echo $html->url(array('controller'=>'odds','action'=>'add')) ?>';

$(function() {
	$("#date").datepicker();
	
	$("#date").change(function(){
		var dat = $(this).val();
		location = '<?php echo $html->url(array("action"=>"index","/")) ?>' + '/' + dat;
	});
	
	$("#league").change(function(){
		var le = $(this).val();
		var dat = $("#date").val();
		location = '<?php echo $html->url(array("action"=>"index","/")) ?>' + '/' + dat + '/' + le;
	});
	
	$("#panel_look").dialog({
		autoOpen: false,
		bgiframe: true,		
		modal: true,
		height: 250,
		width:  650,
		resizable: true
	});
	
	$(".to_modal").click(function(){
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
    }).next().button({
		icons: {
			primary: "ui-icon-unlocked"
		}
	}).next().button({
		icons: {
			primary: "ui-icon-clock"
		}
	}).next().button({
		icons: {
			primary: "ui-icon-clock"
		}
	});

	$( ".act_each button" ).button({
	    icons: {
			primary: "ui-icon-trash"
		},
        text: false
	}).next().button({
	    icons: {
			primary: "ui-icon-wrench"
		},
    	text: false
	}).next().button({
		icons: {
			primary: "ui-icon-zoomin"
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

	$(".odds_normal").each(function(e){
		var myid = $(this).attr('id');
		var titles = $(this).attr('title').split("-");
		var count = titles[0];
		var mode = titles[1];
		
		var toload = odd_url + "/" + myid + "/" + mode + "/" + count;

		if(mode == 'e')
			toload = exo_load + '/' + myid + '/' + league_id + "/" + count;

		$(this).load(toload);
		
	});
	
});

</script>
<style>
	.act_each{
		margin: 3px 3px 3px 3px;
		padding: 2px 2px 2px 2px;
		border: 1px solid blue;
	}
	.act_general{
		margin-top: 2px;
		margin-bottom: 8px;
	}
	.odds_normal{
		padding: 2px 2px 2px 2px;
	}
</style>
<div class="matches index">
<h2>Juegos</h2>

<table style="width:450px">
	<tr>
		<td>
		<?php 
		echo $form->input("date",array('label'=>'Fecha','value'=>$date,'readonly'=>'readonly','style'=>'width:90px'));
		
		echo "(".$dtime->date_spa_mon_abr($date).")";
		?>
		
		</td>
		<td>
		<?php 
		echo $form->input("league",array('label'=>'Liga','value'=>$league,'options'=>$leagues,'empty'=>"Seleccione")) 	
		?>
		</td>
	</tr>
</table>
<div class="act_general">
	<?php 
	$comp = "";
	$cls_but = "to_redir";
	if($league != null && !empty($leagues[$league])) {
		$comp = " para ".$leagues[$league];
		$cls_but = "to_modal";	
	}
	?>

	<button class="<?php echo $cls_but ?>" href="<?php echo $html->url(array("action"=>"add",$date,$league)) ?>">
		Nuevo Juego<?php echo $comp ?>
	</button>
	<button class="to_redir" href="<?php echo $html->url(array("action"=>"free",$date,$league)) ?>">
		Liberar Liga
	</button>
	<?php 
	$hid = "";
	$mos = " style='display:none' ";
	if($not_started == 1){
		$hid = " style='display:none' ";
		$mos = "";
	}
	?>
	<button <?php echo $hid ?>class="to_redir" href="<?php echo $html->url(array("action"=>"index",$date,$league,1)) ?>">
		Ocultar Iniciados
	</button>
	<button <?php echo $mos ?>class="to_redir" href="<?php echo $html->url(array("action"=>"index",$date,$league)) ?>">
		Mostrar Iniciados
	</button>
</div>

<table cellpadding="2" cellspacing="0">
<tr>
	<th>Detalles</th>
	<th>Completo</th>
	<th>Mitad</th>
	<?php
	if($sport_id == ID_BASKET){
	?>
		<th>1er Cuarto</th>
	<?php	
	} 
	?>
	<th>Otras</th>
</tr>
<?php
$i = 0;
$counter = 0;
foreach ($games as $game):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<div class="act_each">				
				<button class="to_redir" href="<?php echo $html->url(array("action"=>"delete", $game['Game']['id'])) ?>">
					Borrar
				</button>
				<button class="to_modal" href="<?php echo $html->url(array("action"=>"edit", $game['Game']['id'])) ?>">
					Editar
				</button>
				<button class="to_modal" href="<?php echo $html->url(array("action"=>"view", $game['Game']['id'])) ?>">
					Detalles
				</button>
				<?php 
				$lev = " style='display:none' ";
				$sus = "";	
				
				if($game['Game']['enable'] == 0){
					$lev = "";
					$sus = " style='display:none' ";
				}				
				?>
				<button <?php echo $lev ?>class="to_redir" href="<?php echo $html->url(array("action"=>"set_enable", $game['Game']['id'],$game['Game']['enable'])) ?>">
					Levantar
				</button>
				<button <?php echo $sus ?>class="to_redir" href="<?php echo $html->url(array("action"=>"set_enable", $game['Game']['id'],$game['Game']['enable'])) ?>">
					Suspender
				</button>
			</div>
			<div style="font-size:120%">
				<?php 
				$passed = "";
				
				if(strtotime($game['Game']['time']) < strtotime(date("H:i:s")))
					$passed = " style='color:Red'";
				
				echo "<b$passed>";
				echo $dtime->time_to_human($game['Game']['time']); 
				echo "</b><br />";
				
				foreach($game['Team'] as $team){
					echo $team['GamesTeam']['reference'].": ".$team['name']."<br />";
					
					if($team['GamesTeam']['metadata'] != null)
						echo "<i>(".$team['GamesTeam']['metadata'].")</i><br />&nbsp;<br />";
					
				}
				
				if($game['Game']['enable'] == 0){
					echo "<span style='color:Red'>Suspendido</span>";
				}
				?>
				
			</div>
		</td>
		<td class="odds_normal <?php echo "proper-$counter-1" ?>" id="<?php echo $game['Game']['id'] ?>" title="<?php echo $counter ?>-1">
			<?php echo $html->image('loading.gif')?>
		</td>
		<td class="odds_normal <?php echo "proper-$counter-0" ?>" id="<?php echo $game['Game']['id'] ?>" title="<?php echo $counter ?>-0">
			<?php echo $html->image('loading.gif')?>
		</td>
		<?php
		if($sport_id == ID_BASKET){
		?>
			<td class="odds_normal <?php echo "proper-$counter-2" ?>" id="<?php echo $game['Game']['id'] ?>" title="<?php echo $counter ?>-2">
				<?php echo $html->image('loading.gif')?>
			</td>
		<?php	
		} 
		?>
		<td class="odds_normal <?php echo "proper-$counter-e" ?>" id="<?php echo $game['Game']['id'] ?>" title="<?php echo $counter ?>-e">
			<?php echo $html->image('loading.gif')?>
		</td>
		
	</tr>
<?php 
	$counter ++;
endforeach; ?>
</table>
</div>