<?php 
$orient = "short";
if($format == "mlb")
	$orient = "long";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="keywords" content="">
	<meta name="description" content="">
	<link rel="shortcut icon" href="<?php echo $html->url('/favicon.ico') ?>" type="image/x-icon">
	<title>Hoja de Logros</title>
	<?php echo $html->css('style_odds') ?>
	<style>		
	#wrapper{
		width: <?php echo $oddsheet->measures[$orient]?>;
	}
	</style>
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<div id="head_txt">
				<span style="font-size:180%;">Total Hipico Sports</span><br />
				<span style="font-size:120%;">J-XXXXX</span>
				<div style="float:right; font-size: 160%; bottom: 0px; margin-top: 15px;">
					<?php echo $dtime->date_spa_mon_abr(date("Y-m-d")); ?> 
				</div>
			</div>
			<div id="head_img"></div>
		</div>
		<div id="odds_content">
			<?php echo $content_for_layout ?>
		</div>	
		<div id="footer">
		
			<b>NOTA: </b> Los logros estan sujetos a cambios,la hoja es referencial.<br />  
			Actualizada a las <?php echo $dtime->time_to_human(date("H:i:s"))?> de hoy,  
			pregunte por los logros antes al operador. 
			<br />No se recibiran apuestas a menores ni estudiantes uniformados. Leer las reglas del juego.
		</div>
	</div>
</body>
</html>