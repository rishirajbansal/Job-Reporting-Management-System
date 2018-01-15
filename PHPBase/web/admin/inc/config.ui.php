<?php


$breadcrumbs = array(
	"Home" => APP_URL
);


$page_nav = array(
    "dashboard" => array(
		"title" => getLocaleText('MENU_MSG_1', TXT_A),
		"url" => "home.php",
		"icon" => "fa-home"
	),
    "organizations" => array(
		"title" => getLocaleText('MENU_MSG_2', TXT_A),
		"url" => "org.php",
		"icon" => "fa-building"
	),
    "reporting" => array(
		"title" => getLocaleText('MENU_MSG_3', TXT_A),
		"icon" => "fa-bar-chart-o",
                "sub" => array(
                        "rptTmplConfig" => array(
                                "title" => getLocaleText('MENU_MSG_4', TXT_A),
                                "url" => "rptTemplates.php"
                        ),
                        "rptStructconfig" => array(
                                "title" => getLocaleText('MENU_MSG_5', TXT_A),
                                "url" => "rptStructs.php"
                        ),
                        "rptQFilterconfig" => array(
                                "title" => getLocaleText('MENU_MSG_6', TXT_A),
                                "url" => "rptQFilters.php"
                        )
                )
	)
);

//configuration variables
$page_title = "";
$page_css = array();
$no_main_header = false; //set true for lock.php and login.php
$page_body_prop = array("class" => "menu-on-top"); //optional properties for <body>
$page_html_prop = array(); //optional properties for <html>
?>