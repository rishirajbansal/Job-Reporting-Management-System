<!DOCTYPE html>
<html lang="en-us" <?php echo implode(' ', array_map(function($prop, $value) {
			return $prop.'="'.$value.'"';
		}, array_keys($page_html_prop), $page_html_prop)) ;?>>
    
    <head>
        <meta charset="utf-8">
        <!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

        <title> <?php echo $page_title != "" ? $page_title." - " : ""; ?><?php echo getLocaleText('APP_NAME', TXT_U); ?> </title>
        <meta name="description" content="">
        <meta name="author" content="">

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Basic Styles -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/css/font-awesome.min.css">

        <!-- STRICT order sequence -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/css/plugins.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/css/prod.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/css/appearance.min.css">

        <?php

            if ($page_css) {
                foreach ($page_css as $css) {
                    echo '<link rel="stylesheet" type="text/css" media="screen" href="'.ASSETS_URL.'/css/'.$css.'">';
                }
            }
        ?>
        
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/css/u-generic.css">


        <!-- FAVICONS -->
        <link rel="shortcut icon" href="<?php echo ASSETS_URL; ?>/img/favicon/favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?php echo ASSETS_URL; ?>/img/favicon/favicon.ico" type="image/x-icon">

        <!-- GOOGLE FONT -->
        <!-- <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700"> -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo ASSETS_URL; ?>/css/gfonts.css" >

        <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
        <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
        <script>
            if (!window.jQuery) {
                document.write('<script src="<?php echo ASSETS_URL; ?>/js/libs/jquery-2.1.1.min.js"><\/script>');
            }
        </script>

        <!--<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>-->
        <script>
            if (!window.jQuery.ui) {
                document.write('<script src="<?php echo ASSETS_URL; ?>/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
            }
        </script>

    </head>
    
    <body <?php echo implode(' ', array_map(function($prop, $value) {
                    return $prop.'="'.$value.'"';
            }, array_keys($page_body_prop), $page_body_prop)) ;?>>

            <?php
                
                if (!$no_main_header) {

            ?>
                    <!-- HEADER -->
                    <header id="header">
                        <!--
                        <?php
                        if (!empty($_SESSION['logo'])) { ?>
                        <div id="logo-group" style="width: auto">
                                <span id="logo" style="margin-top: 7px;" onclick="javascript:location.href='home.php'"> <img src="<?php echo ASSETS_URL . $_SESSION['logo']; ?>" alt="Organization" style="width: 130px;height: 36px;cursor: pointer"></span>
                            </div>
                        <?php
                        }
                        else{ ?>
                            <div id="logo-group" style="width: auto"></div>
                        <?php
                        }
                        ?>-->
                            
                        <div id="logo-group" style="width: auto">
                            <span id="logo" style="margin-top: 3px;" onclick="javascript:location.href='home.php'"> <img src="<?php echo ASSETS_URL; ?>/img/misc/logo.png" alt="SuperAdmin" style="width: 130px;height: 42px;cursor: pointer"></span>
                        </div>
                        
                        <div id="apptitle" class="col-sm-4">
                            <span><h1 style="color: #1f629d;font-weight: 500;text-shadow: 1px 1px 1px #687784;"><?php echo getLocaleText('APP_NAME', TXT_U); ?></h1></span>
                        </div>

                        <!-- pulled right: nav area -->
                        <div class="pull-right">

                            <div id="hide-menu" class="btn-header pull-right">
                                <span> <a href="javascript:void(0);" title="Collapse Menu" data-action="toggleMenu"><i class="fa fa-reorder"></i></a> </span>
                            </div>

                            <ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
                                <li class="">
                                    <a href="#" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown">
                                        <?php
                                        if (!empty($_SESSION['logo'])) { ?>
                                            <img src="<?php echo ASSETS_URL . $_SESSION['logo']; ?>" alt="<?php echo getLocaleText('HEADER_MSG_1', TXT_U); ?>" class="online" style="width: 45px;" />
                                        <?php
                                        }
                                        ?>
                                        <span class="profileheader"><?php echo $_SESSION['orgname']; ?></span>
                                        <i class="fa fa-angle-down" style="vertical-align: middle;margin-top: 8px;"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="settings.php" class="padding-10 padding-top-0 padding-bottom-0"><i class="fa fa-cog"></i> <?php echo getLocaleText('HEADER_MSG_2', TXT_U); ?></a>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i> <?php echo getLocaleText('HEADER_MSG_3', TXT_U); ?></a>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a href="logout.php" class="padding-10 padding-top-5 padding-bottom-5" data-action="userLogout" data-logout-title-msg="<?php echo getLocaleText('HEADER_MSG_8', TXT_U); ?>" data-logout-msg="<?php echo getLocaleText('HEADER_MSG_6', TXT_U); ?>" data-logout-cmd-yes="<?php echo getLocaleText('HEADER_MSG_9', TXT_U); ?>" data-logout-cmd-no="<?php echo getLocaleText('HEADER_MSG_10', TXT_U); ?>"><i class="fa fa-sign-out fa-lg"></i> <strong><?php echo getLocaleText('HEADER_MSG_4', TXT_U); ?></strong></a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                            
                            <div id="logout" class="btn-header transparent pull-right">
                                <span> <a href="<?php echo APP_URL; ?>/logout.php" title="<?php echo getLocaleText('HEADER_MSG_5', TXT_U); ?>" data-action="userLogout" data-logout-title-msg="<?php echo getLocaleText('HEADER_MSG_8', TXT_U); ?>" data-logout-msg="<?php echo getLocaleText('HEADER_MSG_6', TXT_U); ?>" data-logout-cmd-yes="<?php echo getLocaleText('HEADER_MSG_9', TXT_U); ?>" data-logout-cmd-no="<?php echo getLocaleText('HEADER_MSG_10', TXT_U); ?>"><i class="fa fa-sign-out"></i></a> </span>
                            </div>

                            <div id="fullscreen" class="btn-header transparent pull-right">
                                <span> <a href="javascript:void(0);" title="<?php echo getLocaleText('HEADER_MSG_3', TXT_U); ?>" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i></a> </span>
                            </div>
                            
                            <?php
                            $locale = '';
                            if (isset($_SESSION['locale'])){
                                $locale = $_SESSION['locale'];
                            }
                            else{
                                $locale = DEFAULT_LOCALE;
                            }
                            ?>
                            
                            <ul class="header-dropdown-list hidden-xs">
                                <li>
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <?php
                                        if ($locale == LOCALE_ESP_SP){ ?>
                                            <img src="<?php echo ASSETS_URL; ?>/img/misc/blank.gif" class="flag flag-es" alt="United States"> <span> Español </span> <i class="fa fa-angle-down"></i>
                                        <?php
                                        }
                                        else { ?>
                                            <img src="<?php echo ASSETS_URL; ?>/img/misc/blank.gif" class="flag flag-us" alt="Spanish"> <span> English (US) </span> <i class="fa fa-angle-down"></i>
                                        <?php
                                        }
                                        ?>
                                    </a>
                                        
                                    <ul class="dropdown-menu pull-right">
                                        <li <?php if ($locale == LOCALE_ENG_US){ ?> class="active" <?php }?> >
                                            <a href="javascript:window.location.href='login.php?loc=<?php echo LOCALE_ENG_US; ?>&redirect=lng';"><img src="<?php echo ASSETS_URL; ?>/img/misc/blank.gif" class="flag flag-us" alt="United States"> English (US)</a>
                                        </li>
                                        <li <?php if ($locale == LOCALE_ESP_SP){ ?> class="active" <?php }?> >
                                            <a href="javascript:window.location.href='login.php?loc=<?php echo LOCALE_ESP_SP; ?>&redirect=lng';"><img src="<?php echo ASSETS_URL; ?>/img/misc/blank.gif" class="flag flag-es" alt="Spanish"> Español</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>

                        </div>
                        <!-- end pulled right: nav area -->

                    </header>
                    <!-- END HEADER -->

            <?php
                }
            ?>