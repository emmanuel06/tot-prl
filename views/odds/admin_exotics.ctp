<style>
form {
	margin-right: 0px;
	padding: 0;
	width: 100%;
}
label{
	display:none;
}
.for_exos{
	width: 35px;
	font-size: 90%;
}
.for_regular{
	margin-top: 1px;
	margin-bottom: 1px;
}
.for_others{
	margin-top: 2px;
	margin-bottom: 1px;
}

.my_odd_table {
	background: #fff;
	border: none;
	border-right:none;
	clear: both;
	margin: 2px 2px 2px 2px;
	width: 100%;
}
.my_odd_table tr td {
	padding: 0px;
}

.buts{
	width:120px;
	margin-left: 10px;
	margin-right: 10px;
	margin-top: 2px;
	margin-bottom: 2px;
	padding: 1px 1px;
	font-size: 100%;
}
form div {
	clear: none;
	margin-bottom: 0px;
	padding: 0px;
	vertical-align: text-top;
}
</style>
<script>
$(function(){
	$('.lil_load').hide();

	$(".difone").blur(function(){
		$(this).parents('td').find(".diftwo").val($(this).val() * -1);
	});

	$(".sameone").blur(function(){
		$(this).parents('td').find(".sametwo").val($(this).val());
	});

	$(".samethree").blur(function(){
		$(this).parents('td').find(".samefour").val($(this).val());
	});

	$("#<?php echo "button-$counter-e" ?>").click(function(){
	
		$(this).parent().find('.buts').hide();
		$(this).parent().find('.lil_load').show();
		$("#<?php echo "odd-$counter-e" ?>").submit();
	});
	
	$("#<?php echo "odd-$counter-e" ?>").ajaxForm(function(responseText, statusText) { 
		$(".<?php echo "proper-$counter-e" ?>").html('<center><H4>'+responseText+'</H4></center>');
		setTimeout(function (){$(".<?php echo "proper-$counter-e" ?>").load('<?php echo $html->url(array('action'=>'exotics',$game_id,$league_id,$counter)) ?>')},3000);
	});
	
});
</script>
<div class="odds">
	<?php
	if(empty($exotics)){
		echo "Sin posibles aun.";
	}else{
		//pr($odds_set);pr($exotics);
		echo $form->create('Odd',array('action'=>'exotics','id' => "odd-$counter-e"));
		?>
		<table border="1" cellpadding="0" cellspacing="0" class="my_odd_table">
			<tr>
				<th>EQUIPO</th>
				<th>LOGRO</th>
				<th>DIF/LC</th>
			</tr>
		<?php 
		$i = 0;
		foreach($exotics as $exotic){
			$oddid = $exotic['Id'];
		?>
			<tr>
				<th colspan="3"><?php echo $exotic['Name'] ?></th>
			</tr>
			<?php
			$ti = 0;
			foreach($exotic['Team'] as $teamid => $name){
				
				//VALUES FROM ODDS
				$odd = "";
				$factor = "";
				
				if(!empty($odds_set[$oddid][$teamid])){
					$odd = $odds_set[$oddid][$teamid]['odd'];
					$factor = $odds_set[$oddid][$teamid]['factor'];
				}
				//END VALUES FROM ODDS
				
				//VALUES OLD
				echo $form->input("Odd.$i.oddold",array('type'=>'hidden','value'=>$odd));
				echo $form->input("Odd.$i.factorold",array('type'=>'hidden','value'=>$factor));
				//END VALUES OLD
				
				//DFEFAULT VALUES
				echo $form->input("Odd.$i.game_id",array('type'=>'hidden','value'=>$game_id));
				echo $form->input("Odd.$i.odd_type_id",array('type'=>'hidden','value'=>$oddid));
				echo $form->input("Odd.$i.team_type_id",array('type'=>'hidden','value'=>$teamid));
				echo $form->input("Odd.$i.final",array('type'=>'hidden','value'=>1));
				//END DEFAULT VALUES
				
			?>
				<tr>
					<td>
						<?php 
						echo $name; 
						
						$srunline = "";
						if($oddid == 7){
							
							if($odd == "")
								$odd = "-120";
								
							if($ti == 0)
								$srunline = "sameone";
							else
								$srunline = "sametwo";
						}elseif($oddid == 9){
							
							if($odd == "")
								$odd = "-110";
							
							if($ti == 0)
								$srunline = "sameone";
							elseif($ti == 1)
								$srunline = "sametwo";
							elseif($ti == 2)
								$srunline = "samethree";
							else
								$srunline = "samefour";
						}elseif($oddid == 8){
							if($ti == 0)
								$srunline = "difone";
							else
								$srunline = "diftwo";
						}
						
						?>
					</td>
					<td>
						<?php 
							echo $form->input("Odd.$i.odd",array('class'=>'for_exos for_regular','value'=>$odd));
						?>
					</td>
					<td>
						<?php 
						$typ = 'text';
						if(!$exotic['Factor']){
							$typ = 'hidden';
							echo "&nbsp;";
						}
							echo $form->input("Odd.$i.factor",array('class'=>"for_exos for_regular $srunline",'value'=>$factor,'type'=>$typ));
						?>
					</td>
				</tr>
			<?php
				$ti ++;
				$i ++; 
			} 
		}	
		?>
			<tr>
				<td colspan="3">
					<input class="buts save" id="<?php echo "button-$counter-e" ?>" type="button" value="Guardar"/>
					<?php echo $html->image('loading_small.gif',array('class'=>'lil_load'))?>
				</td>
			</tr>
		</table>
	<?php 
		echo $form->end();
	}	
	?>
</div>