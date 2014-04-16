<!DOCTYPE html>
<html>
<head>
    <title>Total Parlay</title>
    <link rel="shortcut icon" href="<?php echo $html->url('/favicon.ico') ?>" type="image/x-icon">
    <?php echo $html->css('allstyle'); ?>
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
    </div>
    <div id="content-wrapper">
        <?php

        if ($session->check('Message.flash'))
            $session->flash();


        $session->flash('auth');

        echo $content_for_layout;
        ?>
    </div>
    <div id="footer-wrapper">
        <span>Total Parlay &copy; All rights reserved.</span>
    </div>
</body>
</html>
