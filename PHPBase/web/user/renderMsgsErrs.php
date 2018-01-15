        <div class="row">

            <article class="col-sm-12">
                
                <?php
                    if (!empty($errMsgObject['msg']) && $errMsgObject['msg'] == 'error'){  ?>
                        <div class="alert alert-warning fade in">
                            <button class="close" data-dismiss="alert">
                                <i class="fa fa-times-circle"></i>
                            </button>
                            <?php echo show_errors($errMsgObject); ?>
                        </div>
                <?php } ?>
                
                <?php
                    if (!empty($errMsgObject['msg']) && $errMsgObject['msg'] == 'message'){  ?>
                        <div class="alert alert-info fade in">
                            <button class="close" data-dismiss="alert">
                                <i class="fa fa-times-circle"></i>
                            </button>
                            <?php echo show_infoMessages($errMsgObject); ?>
                        </div>
                <?php } ?>
                
                <?php
                    if (!empty($errMsgObject['msg']) && $errMsgObject['msg'] == 'success' && !empty($errMsgObject['text'])){  ?>
                        <div class="alert alert-success fade in">
                            <button class="close" data-dismiss="alert">
                                <i class="fa fa-times-circle"></i>
                            </button>
                            <?php echo show_successMessages($errMsgObject); ?>
                        </div>
                <?php } ?>
                
                <?php
                    if (!empty($errMsgObject['msg']) && $errMsgObject['msg'] == 'completeSuccess' && !empty($errMsgObject['text'])){  ?>
                        <div class="alert alert-block alert-success">
                            <a class="close" data-dismiss="alert" href="#"><i class="fa fa-times-circle"></i></a>
                            <h4 class="alert-heading"><i class="fa fa-check-square-o"></i> <?php echo getLocaleText('RENDER_MSG_ERRS_MSG_1', TXT_U); ?></h4>
                            <p>
                                <?php echo $errMsgObject['text']; ?>
                            </p>
                        </div>
                <?php } ?>
                
                <?php
                    if (!empty($errMsgObject['msg']) && $errMsgObject['msg'] == 'criticalError' && !empty($errMsgObject['text'])){  ?>
                        <div class="alert alert-block alert-danger">
                            <a class="close" data-dismiss="alert" href="#"><i class="fa fa-times-circle"></i></a>
                            <h4 class="alert-heading"><i class="fa fa-times"></i> <?php echo getLocaleText('RENDER_MSG_ERRS_MSG_2', TXT_U); ?></h4>
                            <p>
                                <?php echo $errMsgObject['text']; ?>
                            </p>
                        </div>
                <?php } ?>

            </article>

        </div>