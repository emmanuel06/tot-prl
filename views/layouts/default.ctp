<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>
			Total Parlay
		</title>
		<link rel="shortcut icon" href="<?php echo $html->url('/favicon.ico') ?>" type="image/x-icon">
		<?php 
		echo $html->css(array('cake.generic','ui-thms/south-street/jquery-ui-1.10.4.custom.css'));

		echo $javascript->link(array('jquery-1.10.2','jquery-ui-1.10.4.custom'));

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
