                                        <fieldset style="min-height: 380px">

                                            <legend></legend>
                                            <br/>
                                            
                                            <div style="min-height: 240px">
                                                <?php
                                                $userEntities = $userOrgDao->getUserEntities();
                                                foreach ($userEntities as $userEntity){ ?>

                                                    <div class="form-group" <?php if ($userEntity->getHtmlType() === DYNA_CONTROL_TYPE_IMAGE) {?> style="margin-bottom: 0px" <?php } ?> >
                                                        <label class="col-md-3 control-label">
                                                            <?php
                                                            if ($userEntity->getHtmlType() === DYNA_CONTROL_TYPE_CHECKBOX || $userEntity->getHtmlType() === DYNA_CONTROL_TYPE_IMAGE || $userEntity->getHtmlType() === DYNA_CONTROL_TYPE_SIGNPAD){
                                                                
                                                            }
                                                            else{
                                                                echo $userEntity->getName();
                                                            }
                                                            ?>
                                                        </label>
                                                        <div class="col-md-5">

                                                            <?php
                                                             switch ($userEntity->getHtmlType()) {
                                                                case DYNA_CONTROL_TYPE_TEXT:
                                                                    ?>
                                                                    <div class="input-group input-group-md">
                                                                        <span class="input-group-addon "><i class="fa <?php echo $userEntity->getIcon(); ?> fa-fw"></i></span>
                                                                        <input class="form-control" type="text" placeholder="<?php echo $userEntity->getName(); ?>" name="<?php echo $userEntity->getHtmlName(); ?>" id="<?php echo $userEntity->getHtmlName(); ?>" value="<?php echo $userEntity->getSavedValue(); ?>">
                                                                    </div>
                                                                    <?php
                                                                    break;

                                                                case DYNA_CONTROL_TYPE_TEXTAREA:
                                                                    ?>
                                                                    <textarea class="form-control" placeholder="<?php echo $userEntity->getName(); ?>" name="<?php echo $userEntity->getHtmlName(); ?>" id="<?php echo $userEntity->getHtmlName(); ?>" rows="4" ><?php echo $userEntity->getSavedValue(); ?></textarea>
                                                                    <?php
                                                                    break;

                                                                case DYNA_CONTROL_TYPE_CHECKBOX:
                                                                    ?>
                                                                    <label class="col-md-12" style="margin-left: -25px;">
                                                                        <input class="checkbox style-0" type="checkbox" name="<?php echo $userEntity->getHtmlName(); ?>" id="<?php echo $userEntity->getHtmlName(); ?>" value="Yes" <?php if (!empty($userEntity->getSavedValue())) { ?> checked <?php echo $userEntity->getSavedValue(); } ?>>
                                                                        <span class="col-md-12"><?php echo $userEntity->getName(); ?></span>
                                                                    </label>
                                                                    <?php
                                                                    break;

                                                                case DYNA_CONTROL_TYPE_COMBO:
                                                                    ?>
                                                                    <select class="form-control" name="<?php echo $userEntity->getHtmlName(); ?>" id="<?php echo $userEntity->getHtmlName(); ?>">
                                                                        <?php
                                                                        $listValues = $userEntity->getSelectedListValuesEn();
                                                                        foreach ($listValues as $value){ ?>
                                                                            <option <?php if (!empty($userEntity->getSavedValue()) && $userEntity->getSavedValue() == $value) { ?> selected<?php } ?>><?php echo $value; ?></option>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    <?php
                                                                    break;
                                                                    
                                                                case DYNA_CONTROL_TYPE_DATE:
                                                                    ?>
                                                                    <div class="input-group input-group-md" id="dp-div">
                                                                        <span class="input-group-addon "><i class="fa <?php echo $userEntity->getIcon(); ?> fa-fw"></i></span>
                                                                        <input class="form-control datepicker" type="text" placeholder="<?php echo $userEntity->getName(); ?>" name="<?php echo $userEntity->getHtmlName(); ?>" id="<?php echo $userEntity->getHtmlName(); ?>" value="<?php echo $userEntity->getSavedValue(); ?>" data-dateformat="dd/mm/yy">
                                                                    </div>
                                                                    <?php
                                                                    break;
                                                                
                                                                case DYNA_CONTROL_TYPE_TIME:
                                                                    $time = $userEntity->getSavedValue();
                                                                    if (!empty($time)){
                                                                        $time = str_replace('.', ':', $time);
                                                                    }
                                                                    ?>
                                                                    <div class="input-group input-group-md" >
                                                                        <span class="input-group-addon "><i class="fa <?php echo $userEntity->getIcon(); ?> fa-fw"></i></span>
                                                                        <input class="form-control" type="text" placeholder="<?php echo $userEntity->getName(); ?>" name="<?php echo $userEntity->getHtmlName(); ?>" id="<?php echo $userEntity->getHtmlName(); ?>" value="<?php echo $time; ?>" onclick="javascript:jtime('<?php echo $userEntity->getHtmlName(); ?>');">
                                                                    </div>
                                                                    <?php
                                                                    break;
                                                                    
                                                                case DYNA_CONTROL_TYPE_DYNAMIC_TEXTBOXES_2:
                                                                    $values = $userEntity->getSavedValue();
                                                                    if (null == $values || empty($values)){
                                                                        $values = FIELD_2TB_VALUES_SEPERATOR;
                                                                    }
                                                                    $values = explode(FIELD_2TB_VALUES_DATASET_SEPERATOR, $values);
                                                                    $ctr = 1;
                                                                    ?>
                                                                    <div id="2TB-<?php echo $userEntity->getIdDynaFields(); ?>">
                                                                        <?php
                                                                        foreach ($values as $value){
                                                                            $valueArr = explode(FIELD_2TB_VALUES_SEPERATOR, $value);
                                                                            ?>
                                                                            <div id="inner2TB-<?php echo $userEntity->getIdDynaFields().'-'.$ctr; ?>">
                                                                                <?php
                                                                                if ($ctr <= count($values) && $ctr != 1){?>
                                                                                    <br/><br/>
                                                                                <?php } ?>
                                                                                <div class="col-md-5" style="padding-left: 0px;">
                                                                                    <div class="input-group input-group-md" style="width:100%">
                                                                                        <input class="form-control cstValid" type="text" placeholder="" name="<?php echo $userEntity->getHtmlName().'_1-'.$ctr; ?>" id="<?php echo $userEntity->getHtmlName().'_1-'.$ctr; ?>" value="<?php echo $valueArr[0]; ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-5" style="padding-right: 0px;">
                                                                                    <div class="input-group input-group-md" style="width:100%">
                                                                                        <input class="form-control cstValid" type="text" placeholder="" name="<?php echo $userEntity->getHtmlName().'_2-'.$ctr; ?>" id="<?php echo $userEntity->getHtmlName().'_2-'.$ctr; ?>" value="<?php echo $valueArr[1]; ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-2" style="padding-left: 15px; padding-top: 7px;">
                                                                                    <i class="fa fa-lg fa-minus-circle" style="color: #dc2d2d;cursor: pointer;font-size: 1.6em;" id="2TBminus-<?php echo $userEntity->getIdDynaFields(); ?>" onclick="javascript:minusRow2TB('<?php echo $userEntity->getHtmlName(); ?>', <?php echo $ctr; ?>, '<?php echo $userEntity->getIdDynaFields(); ?>');" title="Remove Task"></i>&nbsp;&nbsp;
                                                                                <?php    
                                                                                if ($ctr < count($values)){?>
                                                                                    </div>
                                                                                <?php } else{ ?>
                                                                                    <i class="fa fa-lg fa-plus-circle" style="color: #739e73;cursor: pointer;font-size: 1.6em;" id="2TBadd-<?php echo $userEntity->getIdDynaFields(); ?>" onclick="javascript:addRow2TB('<?php echo $userEntity->getHtmlName(); ?>', <?php echo $ctr; ?>, '<?php echo $userEntity->getIdDynaFields(); ?>');" title="Add new Task"></i>
                                                                                </div>
                                                                                <?php
                                                                                }
                                                                                ++$ctr;
                                                                                ?>
                                                                            </div>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                    
                                                                    <?php
                                                                    break;
                                                                    
                                                                case DYNA_CONTROL_TYPE_IMAGE:
                                                                    ?>
                                                                    <input type="hidden" name="<?php echo $userEntity->getHtmlName(); ?>" id="<?php echo $userEntity->getHtmlName(); ?>" value="<?php echo $userEntity->getSavedValue(); ?>"/>
                                                                    <?php
                                                                    break;
                                                                
                                                                case DYNA_CONTROL_TYPE_SIGNPAD:
                                                                    ?>
                                                                    <input type="hidden" name="<?php echo $userEntity->getHtmlName(); ?>" id="<?php echo $userEntity->getHtmlName(); ?>" value="<?php echo $userEntity->getSavedValue(); ?>"/>
                                                                    <?php
                                                                    break;

                                                                default:
                                                                    break;
                                                             }
                                                            ?>

                                                        </div>
                                                    </div>

                                                <?php
                                                    if (!empty($userEntity->getHtmlValidations())){
                                                        $validationRules = $validationRules . $userEntity->getHtmlName() . ': { ';
                                                        foreach ($userEntity->getHtmlValidationsArr() as $validation){
                                                            $validationRules = $validationRules . $validation . ', ';
                                                        }
                                                        $validationRules = substr($validationRules, 0, strlen($validationRules)-2);
                                                        $validationRules = $validationRules . ' }, ';
                                                        
                                                        $validationMessages = $validationMessages . $userEntity->getHtmlName() . ': { ';
                                                        foreach ($userEntity->getHtmlValidationsMessagesArr() as $validationMsg){
                                                            $validationMessages = $validationMessages . $validationMsg . ', ';
                                                        }
                                                        $validationMessages = substr($validationMessages, 0, strlen($validationMessages)-2);
                                                        $validationMessages = $validationMessages . ' }, ';
                                                    }
                                                }
                                                $validationRules = substr($validationRules, 0, strlen($validationRules)-2) . ' ';
                                                $validationMessages = substr($validationMessages, 0, strlen($validationMessages)-2) . ' ';
                                                ?>
                                            </div>
                                            <br/>
                                            <div class="form-actions" style="border: none;background: none;border-top: 1px solid rgba(0,0,0,.1);">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <button type="button" class="btn btn-default bg-color-blueLight txt-color-white" id="btnCancel">
                                                            <i class="glyphicon glyphicon-remove"></i> <?php echo getLocaleText('DYNA_COMPS_MSG_BTN_1', TXT_U); ?>
                                                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <button type="button" class="btn btn-default" id="btnReset">
                                                            <i class="fa fa-refresh"></i> <?php echo getLocaleText('DYNA_COMPS_MSG_BTN_2', TXT_U); ?>
                                                        </button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <button type="button" class="btn btn-primary" id="btnSave">
                                                            <i class="fa fa-save"></i> <?php echo getLocaleText('DYNA_COMPS_MSG_BTN_3', TXT_U); ?>
                                                        </button>
                                                        <?php
                                                        if ( null != $userOrgDao->getPublishBtn() && $userOrgDao->getPublishBtn()){ ?>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <button type="button" class="btn btn-primary" id="btnPublish">
                                                                <i class="fa fa-save"></i> <?php echo getLocaleText('DYNA_COMPS_MSG_BTN_3', TXT_U); ?> &amp; <?php echo getLocaleText('DYNA_COMPS_MSG_BTN_4', TXT_U); ?> <i class="fa fa-file-pdf-o"></i>
                                                            </button>
                                                        <?php    
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <br/><br/>

                                        </fieldset>