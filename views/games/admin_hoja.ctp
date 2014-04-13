<style>
h2{
	margin-top: 10px;
}
input[type=checkbox] {
	clear: none;
	float: left;
	margin: 0px;
	width: auto;
}
#finder{
	width:120px;
	font-weight:bold;
	font-size:120%;
}
#left{
	width:40%;
	clear:none;
	float: left;
}
#right{
	width:40%;
	float: left;
}
#date{
	width:120px;
	margin-top: 10px;
	margin-bottom: 10px;
}
#to_print{
	width:30%; 
	float:left; 
	border: 1px solid black; 
	clear:none;
}
#layout{
	margin-top:15px;
	margin-bottom:15px;	
}
#addons{
	margin-top:15px;
	margin-bottom:15px;	
}
</style>
<script>

var my_url = '<?php echo $html->url(array('action'=>'hoja')) ?>';
var url_odds = '<?php echo $html->url(array('action'=>'game_sheet')) ?>';

$(function(){
	$("#date").datepicker();

	$(".sport").click(function(){
		var sp = $(this).attr('title');
		var ch = $(this).attr('checked');
		$("." + sp).attr('checked',ch);
	});

	$("#date").change(function(){
		location = my_url + '/' + $(this).val();

	});

	$("#gotosheet").button({
        icons: {
            primary: "ui-icon-print"
        }
    }).click(function(){
		var leagues = "";		
		var dater = $("#date").val();
		var lay = $('input:radio[name=layout]:checked').attr('id');
		var results = 0;
		var scoreb = 0;

		if($("#results").attr('checked') == true)
			results = 1;

		if($("#scorebs").attr('checked') == true)
			scoreb = 1;
		
		
		$(".leagues").each(function(){
			if($(this).attr('checked') == true){
				leagues = leagues + $(this).attr('title') + "-";
			}
		});
		
		if(leagues != ""){
			open(url_odds + "/" + dater + "/" +  leagues + "/" +  lay + "/" +  results + "/" + scoreb);
		}else{
			alert("Escoja al menos una liga");
		}		
		
	});

	$("#layout").buttonset();
	$("#addons").buttonset();

});
</script>
<div class="hoja">

	<h2>Hoja de logros</h2>
	
	<div id="left">
	
		<h1>Fecha</h1>
		<input type="text" readonly="readonly" id="date" value="<?php echo $date ?>"/>
		
		
		<h1>Ligas disponibles para la fecha</h1>
		
		<table border="1" style="width:450px; margin-left:5%;">
			<?php 
			foreach($league_detail as $sk => $sv){
				$counter = count($sv['Leagues']);
			?>
			<tr>
				<td rowspan="<?php echo $counter ?>">
				<?php 
					echo $form->input($sv['name'],array('type'=>'checkbox','title'=>$sk,'class'=>'sport'));
				?>
				</td>
				<?php
				//pr($sv['Leagues']);
				$i = 0;
				foreach($sv['Leagues'] as $id => $name){
					if($i != 0)
						echo "<tr>";
					
					echo "<td>";
					echo $form->input($name,array('type'=>'checkbox','title'=>$id,'class'=>"$sk leagues"));
					echo "</td></tr>";
				}		
			}
			?>
		</table>
				
	</div>
	<div id="right">
		<h1>Escoja Formato Matriz de Impresion</h1>
		
		<div id="layout">
			<input type="radio" id="basic" name="layout" checked="checked" /><label for="basic">Basica</label>
			<input type="radio" id="mlb" name="layout" /><label for="mlb">MLB</label>
			<input type="radio" id="mlbil" name="layout" /><label for="mlbil">MLB Interliga</label>
			<input type="radio" id="nba" name="layout" /><label for="nba">NBA</label>
		</div>
	
		<h1>Adicionales</h1>
		
		<div id="addons">
			<input type="checkbox" id="results" /><label for="results">Resultados</label>
			<input type="checkbox" id="scorebs" /><label for="scorebs">Scoreboard</label>
		</div>
	</div>
	
	<button id="gotosheet">IMPRIMIR</button>
</div>