<?php

include_once(dirname(__FILE__) . "/../../../classes/base/Constants.php");


$breadcrumbs = array(
	"Home" => APP_URL
);


$page_nav = array(
    "dashboard" => array(
		"title" => getLocaleText('MENU_MSG_1', TXT_U),
		"url" => "home.php",
		"icon" => "fa-home"
	),
    "products" => array(
		"title" => getLocaleText('MENU_MSG_2', TXT_U),
		"url" => "products.php",
		"icon" => "fa-tags"
	),
    "tasks" => array(
		"title" => getLocaleText('MENU_MSG_3', TXT_U),
		"url" => "tasks.php",
		"icon" => "fa-list-alt"
	),
    "workers" => array(
		"title" => getLocaleText('MENU_MSG_4', TXT_U),
		"url" => "workers.php",
		"icon" => "fa-group"
	),
    "clients" => array(
		"title" => getLocaleText('MENU_MSG_5', TXT_U),
		"url" => "customers.php",
		"icon" => "fa-building"
	),
    "reporting" => array(
		"title" => getLocaleText('MENU_MSG_6', TXT_U),
		"icon" => "fa-suitcase",
                "sub" => array(
                        "dispreports" => array(
                                "title" => getLocaleText('MENU_MSG_7', TXT_U),
                                "url" => "dispReports.php"
                        ),
                        "rptQFilters" => array(
                                "title" => getLocaleText('MENU_MSG_8', TXT_U),
                                "sub" => array(
                                    
                                    )
                        )
                )
	)
);


if (isset($_SESSION['allQueryFilter'])){
    $menuQueryFilters = $_SESSION['allQueryFilter'];

    $selectedFilters = array();

    if (isset($_SESSION['queryFilter']) && !empty($_SESSION['queryFilter'])){
        $configuredQueryFilters = $_SESSION['queryFilter'];
        $configuredQueryFilters = explode(MENU_QUERY_FILTERS_SEPERATOR, $configuredQueryFilters);
        foreach ($menuQueryFilters as $key => $value) {
            if (in_array($key, $configuredQueryFilters)){
                $selectedFilters[$key]  = $value;
            }
        }
    }
    
    if (empty($selectedFilters)){
        $page_nav['reporting']['sub']['rptQFilters']['class'] = "inactive";
    }
    else{
        $page_nav['reporting']['sub']['rptQFilters']['sub'] = $selectedFilters;
    }
    
}
else{
    $page_nav['reporting']['sub']['rptQFilters']['class'] = "inactive";
}



//configuration variables
$page_title = "";
$page_css = array();
$no_main_header = false; //set true for lock.php and login.php
$page_body_prop = array("class" => "menu-on-top"); //optional properties for <body>
$page_html_prop = array(); //optional properties for <html>
?>