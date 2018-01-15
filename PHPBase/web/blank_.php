<?php


require_once("../commonInc/init.php");

require_once("inc/config.ui.php");

require_once("../../classes/common.php");


/*---------------- PHP Custom Scripts ---------*/

$page_title = "Blank Page";

/* ---------------- END PHP Custom Scripts ------------- */


include("inc/header.php");

$page_nav["dashboard"]["active"] = true;
include("inc/nav.php");

?>

<div id="main" role="main">
	<?php

		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

        <div class="row">
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
                <h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-building"></i><i class="fa-fw fa fa-building" style="margin-left: -20px;"></i>Blank </h1>
            </div>
        </div>
        
        <?php include ('renderMsgsErrs.php'); ?>
        
        <?php
        
        if (1) { ?>
            
            <section id="widget-grid" class="">
                
                <div class="row">
                    
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        
                        <div class="jarviswidget" id="wid-id-0" 
                            data-widget-editbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false" data-widget-sortable="false">
                                 
                            <header>
                                <span class="widget-icon"> <i class="fa fa-pencil-square-o"></i> </span>
                                <h2>Blank </h2>
                            </header>
                            
                            <!-- widget div-->
                            <div>
                                
                                <div class="jarviswidget-editbox">
                                
                                </div>
                                
                                <div class="widget-body no-padding">
                                    
                                    
                                    
                                </div>
                                
                            </div>
                                
                        </div>
                        
                    </article>
                    
                </div>

            </section>
        
        <?php
        }
        ?>

    </div>
	<!-- END MAIN CONTENT -->

</div>

<?php

	include("inc/footer.php");
?>


<?php 

	include("../commonInc/scripts.php"); 
?>



<script>

	$(document).ready(function() {
		
            pageSetUp();

	})

</script>
