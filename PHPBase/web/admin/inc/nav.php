<!-- Left panel : Navigation area -->

<aside id="left-panel">

    <nav>
        <?php
            $ui = new ViewUI();
            $ui->create_nav($page_nav)->print_html();
        ?>

    </nav>
    <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>

</aside>
<!-- END NAVIGATION -->