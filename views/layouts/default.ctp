<html>		
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>
			Total Parlay: 
			<?php 
			if(!empty($for_titles_spa[$title_for_layout]))
				echo $for_titles_spa[$title_for_layout];
			else
				echo $title_for_layout;
			?> 
		</title>
		<link rel="shortcut icon" href="<?php echo $html->url('/favicon.ico') ?>" type="image/x-icon">
		<?php 
		echo $html->css('cake.generic');
		echo $html->css('jquery-ui-1.8.16.custom');
		
		echo $javascript->link("jquery-1.4.4");
		echo $javascript->link("jquery-ui-1.8.7.custom");
		echo $javascript->link("jquery.forms");
		echo $javascript->link("menu");

		?>
	</head>
	<body>
		<div id="banner">
			<div id='banner_logo'></div>
			<?php 
			if(!empty($authUser)){
			?>
				<div style="float:right; margin-right:20px; margin-top:10px; color: #000">
				Bienvenido, 
				<?php 
				//pr($authUser);
				echo "<b>".$authUser['profile_name']."</b> ";
				echo $html->link("Cerrar Sesion",array('controller'=>'users','action'=>'logout','admin'=>0))?>
				</div>
			<?php
			}
			?>
		</div>
	
		<div class="menu">	
			<?php 
			include("menu.ctp");
			if(!empty($authUser)){
			?>
				<div id="clock_now">
					<b>HORA: </b><?php echo $dtime->time_to_human(date("H:i:s"))?>
				</div>
			<?php	
			}
			?>
		</div>	
				
		<div id="content">
			<?php  
			if ($session->check('Message.flash')): $session->flash(); endif;
			$session->flash('auth');
			echo $content_for_layout;
			?>				
		</div>
		<div id="footer">
		Derechos Reservados &copy; <!--  Total Hipico. Caracas. -->
		</div>
		<div id="panel_look" style="text-align: justify"></div>
	</body>		   
</html>
