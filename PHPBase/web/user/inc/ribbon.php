<!-- RIBBON -->
<div id="ribbon">

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