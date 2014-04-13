<?php 
echo $javascript->link("jquery.numeric");
echo $html->css('add_bet');
?>

<script>
var url_odds = '<?php echo $html->url(array('controller'=>'game_details','action'=>'search'))?>';
var the_date = '<?php echo $date; ?>';
var load_img = '<?php echo $html->image('loading_small.gif')?>';
var odd_index = 0;
var calc_array = Array();

$(function(){
	$("#search").focus();

	$("#amount").focus(function(){
		$('#err_msg').html("");
		var search = $("#search").val();
		if(search != ""){
			$('#loading').html("Buscando... " + load_img);
			$("#search").attr('readonly',true);
			put_json(search);
			$("#search").val("");
			$("#search").focus();
			$("#search").attr('readonly',false);
		}
	}).numeric(false, 
		function() { 
			alert("Integers only"); 
			this.value = ""; 
			this.focus(); 
		}
	);

	$("#calculate").click(function(){
		var am = $("#amount").val();
		var calc_array = Array();		
		if(am == ""){
			$("#calculated").html('Sin monto');
		}else{
			$.each($(".only_odd"), function() { 
				calc_array.push($(this).val()); 
			});

			if(calc_array.length < 1){
				$("#calculated").html('Sin parlays');
			}else{
				$("#calculated").html(calculatePrize(calc_array,am));
			}			
		}
	});
	
	$("#bet").click(function(){
		var err = "";
		var amo = $("#amount").val();
		var odds = 0;

		$.each($(".only_odd"), function() { 
			odds ++; 
		});
		
		if(amo == ""){
			$("#amount").focus();
			err = "Escriba un monto";
		}
		if(odds == 0){
			$("#search").focus();
			err = "Seleccione al menos un parlay";
		}
		if(err == ""){
			$("#err_msg").html("");
			$("#TicketAmount").val($("#amount").val());
			$('#loading').html("Apostando... " + load_img);
			$("#TicketAddForm").submit();
			$(this).hide();
		}else{
			$("#err_msg").html(err);
		}	
	});

	$(".buts button:first").button({
        icons: {
            primary: "ui-icon-bullet"
        }
    }).next().button({
		icons: {
			primary: "ui-icon-help"
		}
	});

	//$("#foopart").load('<?php echo $html->url(array("action"=>"print",15)) ?>');

	   
});

function del_this(obj){
	$(obj).parents("tr").remove();
}

function put_json(ref){
	$.getJSON(url_odds + '/' + the_date + '/' + ref +'.json', 
		function(data) {
			if(data != null){
				$('#loading').html("");
				$('#finder').show();
				
				if(data.Error != ""){
					$("#err_msg").html('ERROR: ' + data.Error);
				}else{
					var typer = get_type(data.Result.Odd.odd_type_id,data.Result.Game.league_id);
					var toval = validate_bets(data.Result.Game.id,typer);

					if(toval == "")
						show_parlay(data.Result);
					else
						$("#err_msg").html(toval);
				}
			}else{
				$("#err_msg").html('Error en busqueda, intente de nuevo');
			}
		}
	);
}

function get_type(type_id,league_id){
	var type_name = "INDIV";	

	if(type_id == 1 || type_id == 2){ 
		if(type_id == 1 && league_id == 3){
			type_name = "INDIV";
		}else{
			type_name = "TEAM";
		}
	}else{
		if(type_id == 3) 
			type_name = "AB";
	}

	return type_name;
}

function validate_bets(gameid,tovalid){
	
	var valid = "";
	var thisvalid = "data[Games][" + gameid + "][" + tovalid + "]";
	
	$.each($(".to_validate"), function() { 
		var allname = $(this).attr('name');
			
		if(allname == thisvalid) {
			valid = "Apuesta ya existe.";
		}

		if(allname == "data[Games][" + gameid + "][INDIV]"){
			valid = "Existe individual de juego.";
		}

		if(tovalid == "INDIV"){
			if(allname == "data[Games][" + gameid + "][AB]" || allname == "data[Games][" + gameid + "][TEAM]"){
				valid = "Existe otro modo de juego.";
			}
		}
	});

	return valid;
}

function show_parlay(result){

	var trnext = $('#odds_list');
	
	var odd = result.Odd.odd;
	var factor = "";
	if(result.Odd.factor)
		factor = " (" + result.Odd.factor + ")";
	var type = result.Odd.type + " (" + result.Odd.mode + ")";
	var abrevs = result.Odd.abrevs;
	var ref = result.Odd.prefix + result.Game.ref;
	
	var team = result.Game.name;
	if(result.Odd.team != "")
		team = result.Odd.team;
	
	var mode = "FINAL";
	if(result.Odd.final == 0)
		mode = "MITAD";
	if(result.Odd.final == 2)
		mode = "1ER QT"
		
	var type_name_val = get_type(result.Odd.odd_type_id,result.Game.league_id);
	
	var games = "<input type='hidden' value='1' class='to_validate' " + 
	 "name='data[Games]["+ result.Game.id +"][" + type_name_val + "]'/>";

	var odd_ids = "<input type='hidden' name='data[Odd][Odd]["+ odd_index +"]'" + 
	 "value='"+ result.Odd.id +"' id='OddOdd"+ odd_index +"'/>";
	
	var odd_calc = "<input type='hidden' name='data[Odds]["+ odd_index +"]'" + 
	 "value='"+ odd +"' class='only_odd'/>";

	var game_times = "<input type='hidden' name='data[Times]["+ odd_index +"]'" + 
	 "value='"+ result.Game.time +"'/>";

	var button_delete = "<div onclick='del_this(this)' class='del_lil'></div>";
	
	columns = 	"<tr><td>" + ref + "</td><td>" + team + "</td><td>" + odd + factor + "</td>" + 
				"<td>" + abrevs + "</td><td>" + type + "</td><td>" + 
				odd_ids + odd_calc + game_times + games + button_delete + "</td></tr>";
	trnext = trnext.append(columns);
	odd_index = odd_index + 1;
	
}

function calculateParlay(odd, amount){
	var prize = 0;
	var oddAbsolut = 0;
	var fullPrize = 0;
	
	if (odd == 0){
		prize = amount;
	}
	if (odd > 0){ //HEMBRA POSITIVO
		oddAbsolut = odd/100;
		prize = amount *  oddAbsolut;
		fullPrize = prize*1 + amount*1;
	}
	if	(odd < 0){ // MACHO NEGATIVO
		oddAbsolut = odd/-100;
		prize = amount / oddAbsolut;
		fullPrize = prize*1 + amount*1;	
	}
	return fullPrize;
}

function calculatePrize(options,amount){
	//run in the complete array for calculate the final Prize
	var thePrize = 0;
	var prize = 0;
	var newAmount = 0;
	for(i = 0; i < options.length; i++){
		if(i == 0){
			newAmount = amount;
		}else{ 
			newAmount = thePrize;						
		}
		
		prize = calculateParlay(options[i],newAmount);
		
		thePrize = prize.toFixed(2); 
	}
				
	return thePrize;
}
</script>
<div class="tickets form">
	<h2>Apostar</h2>
	<div style="width: 150px; height: 500px; float: left">
		<?php 
		echo $form->input('search',array('label'=>'Buscar'));
		echo $form->input('amount',array('label'=>'Monto'));
		?>
		<div class="buts">
			<button id="bet">
				Apostar
			</button>
			<button id="calculate">
				Calcular
			</button>
		</div>
		<div id="calculated"></div>
	</div>
	
	<div style="width: 600px; height: 500px; float: left">
		<div id="err_msg"></div>
		<div id="loading"></div>
		<?php 
		echo $form->create('Ticket');
		echo $form->input('amount',array('type'=>'hidden'));
		?>
		<table id="odds_list">
			<tr>
				<th>REF</th>
				<th>EQUIPO</th>
				<th>LOGRO</th>
				<th>JUEGO</th>
				<th>TIPO</th>
				<th>-</th>
			</tr>
		</table>
		<?php echo $form->end();?>
	</div>
	
	
	<div id="foopart">
	
	</div>
	
</div>