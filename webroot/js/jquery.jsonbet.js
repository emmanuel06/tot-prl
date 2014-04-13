var odd_index = 0;
var calc_array = Array();

function del_this(obj){
	$(obj).parent().parent().remove();
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
					show_parlay(data.Result);	
				}
			}else{
				$("#err_msg").html('Error en busqueda, intente de nuevo');
			}
		}
	);
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
	
	forhid = "<input type='hidden' name='data[Odd][Odd]["+ odd_index +"]'" + 
	 "value='"+ result.Odd.id +"' id='OddOdd"+ odd_index +"'/>";
	
	forodd = "<input type='hidden' name='data[Odds]["+ odd_index +"]'" + 
	 "value='"+ odd +"' id='Odds"+ odd_index +"' class='only_odd'/>";
	
	fordel = "<div onclick='del_this(this)' class='del_lil'></div>";
	
	columns = 	"<tr><td>" + ref + "</td> " + 
				"<td>" + team + "</td>" +
				"<td>" + odd + factor + "</td>" + 
				"<td>" + abrevs + "</td>" + 
				"<td>" + type + "</td>" +
				"<td>" + forhid + forodd + fordel + "</td></tr>";
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