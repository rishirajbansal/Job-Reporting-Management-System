<!-- RIBBON -->
<div id="ribbon">

    <!--<span class="ribbon-button-alignment"> 
        <span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh" rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all the widget settings." data-html="true"><i class="fa fa-refresh"></i></span> 
    </span>-->

    <!-- breadcrumb -->
    <ol class="breadcrumb">
        <?php
            foreach ($breadcrumbs as $display => $url) {
                    $breadcrumb = $url != "" ? '<a href="'.$url.'">'.$display.'</a>' : $display;
                    echo '<li>'.$breadcrumb.'</li>';
            }
            echo '<li>'.$page_title.'</li>';
        ?>
    </ol>
    <!-- end breadcrumb -->

</div>
<!-- END RIBBON -->