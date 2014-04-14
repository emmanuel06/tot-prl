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
        <script>
        var url_hour = '<?= $html->url(array('controller'=>'users','action'=>'get_hour','admin'=>0)) ?>';
        setInterval( function() {
            $('#auth-time').html('...').load(url_hour);
        }, 3000);
        </script>
        <style>
            /*  HIDING IN CASE
            #logo-wrapper,#name-wrapper,#footer-wrapper {
                display: none
            }
            */
        </style>
	</head>
	<body>
		<div id="banner-wrapper">
			<div id='logo-wrapper'></div>
            <div id="name-wrapper">
                TOTAL<span id="parlay-letters">Parlay</span>
            </div>
            <div id="logged-wrapper">
            <?php
			if(!empty($authUser)){
			?>
                <span class="auth-data" id="auth-date"><?= $dtime->date_spa_show(date('d-D-m-Y')) ?> |</span>
                <span class="auth-data" id="auth-time"><?= $dtime->time_to_human(date('H:i:s')) ?></span>
                <br />
                <div id="auth-name">
                    <?= $authUser['name'] ?>
                    <span id="auth-logout"><?= $html->link("Salir",array('controller'=>'users','action'=>'logout','admin'=>0)) ?></span>
                </div>
			<?php
			}
			?>
            </div>
		</div>

        <?php
        if(!empty($authUser)){
        ?>
		<div id="menu-wrapper">
			<?php
			//include("menu.ctp");
            $dtime->construct_menu($menuActions);
			?>
		</div>
        <?php
        }
        ?>
		<div id="content-wrapper">
			<?php  
			if ($session->check('Message.flash')): $session->flash(); endif;
			$session->flash('auth');
			echo $content_for_layout;
			?>				
		</div>
		<div id="footer-wrapper">
		    <span>Total Parlay &copy; All rights reserved.</span>
		</div>
		<div id="modal-view"></div>
	</body>		   
</html>
