<?php
/**
 * Zo2 (http://www.zo2framework.org)
 * A powerful Joomla template framework
 *
 * @link        http://www.zo2framework.org
 * @link        http://github.com/aploss/zo2
 * @author      ZooTemplate <http://zootemplate.com>
 * @copyright   Copyright (c) 2013 APL Solutions (http://apl.vn)
 * @license     GPL v2
 */
defined('_JEXEC') or die('Restricted access');

$framework = Zo2Factory::getFramework();
$addons = $framework->getRegisteredAddons();
?>
<div id="layoutbuilder-container">
    <!-- Hidden fields -->
    <fieldset>
        <!-- Input field to store generate layout data -->
        <input type="text" value="<?php echo htmlspecialchars($this->value) ?>" style="display: none" class="hfLayoutHtml" name="<?php echo $this->name ?>" id="<?php echo $this->id ?>" />
        <input type="hidden" id="hfTemplateName" value="<?php echo Zo2Factory::getTemplateName() ?>" />
        <input type="hidden" id="hdLayoutBuilder" value="0" />
        <input type="hidden" id="hfLayoutName" value="homepage" />
    </fieldset>

    <!-- Main layout -->
    <div id="droppable-container">
        <div class="zo2-container">
            <?php $this->renderLayout($layoutData) ?>
        </div>
    </div>

    <!-- Modal: Row settings -->
    <div id="rowSettingsModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <!-- Modal header -->
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_ROW_SETTINGS'); ?></h3>
            <!-- Tab titles -->
            <ul class="zo2-tabs">
                <li><a class="active" href="#row-basic" data-toggle="tab"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_BASIC'); ?></a></li>
                <li><a href="#row-responsive" data-toggle="tab"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_RESPONSIVE'); ?></a></li>
            </ul>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            <div class="form-horizontal">
                <!-- Tab contents -->
                <div class="zo2-tabs-content">
                    <!-- Basic -->
                    <div class="active" id="row-basic">
                        <div class="control-group">
                            <label class="control-label" for="txtRowName"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_NAME'); ?></label>
                            <div class="controls">
                                <input type="text" id="txtRowName" placeholder="<?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_ROWNAME'); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="txtRowId"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_ID'); ?></label>
                            <div class="controls">
                                <input type="text" id="txtRowId" placeholder="<?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_ROWID'); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="txtRowCss"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_CUSTOM_CSS'); ?></label>
                            <div class="controls">
                                <input type="text" id="txtRowCss" placeholder="<?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_CUSTOM_ROWCSS'); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <div class="control-label"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_FULLWDITH'); ?></div>
                            </div>
                            <div class="controls btn-group btn-group-onoff" id="btgFullWidth">
                                <button class="btn btn-on"><?php echo JText::_('ZO2_ADMIN_COMMON_ON'); ?></button>
                                <button class="btn btn-off"><?php echo JText::_('ZO2_ADMIN_COMMON_OFF'); ?></button>
                            </div>
                        </div>
                    </div>
                    <!-- Responsive -->
                    <div id="row-responsive">
                        <div class="control-group">
                            <!--
                            <span class="switch_title">Phone</span>
                            <label class="switch_wrap" for="cbRowPhoneVisibility">
                                <input id="cbRowPhoneVisibility" type="checkbox" value="1" />
                                <div class="switch"><span class="bullet"></span></div>
                            </label>
                            -->
                            <div class="control-label">
                                <div class="control-label"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_PHONE'); ?></div>
                            </div>
                            <div class="controls btn-group btn-group-onoff" id="btgRowPhone">
                                <button class="btn btn-on"><?php echo JText::_('ZO2_ADMIN_COMMON_ON'); ?></button>
                                <button class="btn btn-off"><?php echo JText::_('ZO2_ADMIN_COMMON_OFF'); ?></button>
                            </div>
                        </div>
                        <div class="control-group">
                            <!--
                            <span class="switch_title">Tablet</span>
                            <label class="switch_wrap" for="cbRowTabletVisibility">
                                <input id="cbRowTabletVisibility" type="checkbox" value="1" />
                                <div class="switch"><span class="bullet"></span></div>
                            </label>
                            -->
                            <div class="control-label">
                                <div class="control-label"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_TABLET'); ?></div>
                            </div>
                            <div class="controls btn-group btn-group-onoff" id="btgRowTablet">
                                <button class="btn btn-on"><?php echo JText::_('ZO2_ADMIN_COMMON_ON'); ?></button>
                                <button class="btn btn-off"><?php echo JText::_('ZO2_ADMIN_COMMON_OFF'); ?></button>
                            </div>
                        </div>
                        <div class="control-group">
                            <!--
                            <span class="switch_title">Desktop</span>
                            <label class="switch_wrap" for="cbRowDesktopVisibility">
                                <input id="cbRowDesktopVisibility" type="checkbox" value="1" />
                                <div class="switch"><span class="bullet"></span></div>
                            </label>
                            -->

                            <div class="control-label">
                                <div class="control-label"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_DESKTOP'); ?></div>
                            </div>
                            <div class="controls btn-group btn-group-onoff" id="btgRowDesktop">
                                <button class="btn btn-on"><?php echo JText::_('ZO2_ADMIN_COMMON_ON'); ?></button>
                                <button class="btn btn-off"><?php echo JText::_('ZO2_ADMIN_COMMON_OFF'); ?></button>
                            </div>
                        </div>
                        <div class="control-group">
                            <!--
                            <span class="switch_title">Large desktop</span>
                            <label class="switch_wrap" for="cbRowLargeDesktopVisibility">
                                <input id="cbRowLargeDesktopVisibility" type="checkbox" value="1" />
                                <div class="switch"><span class="bullet"></span></div>
                            </label>
                            -->

                            <div class="control-label">
                                <div class="control-label"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_LARGE_DESKTOP'); ?></div>
                            </div>
                            <div class="controls btn-group btn-group-onoff" id="btgRowLargeDesktop">
                                <button class="btn btn-on"><?php echo JText::_('ZO2_ADMIN_COMMON_ON'); ?></button>
                                <button class="btn btn-off"><?php echo JText::_('ZO2_ADMIN_COMMON_OFF'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Model footer -->
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('ZO2_ADMIN_COMMON_CLOSE'); ?></button>
            <button class="btn btn-primary" id="btnSaveRowSettings"><?php echo JText::_('ZO2_ADMIN_COMMON_SAVE_CHANGES'); ?></button>
        </div>
    </div>

    <!-- Model: Column settings -->
    <div id="colSettingsModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_COLUMN_SETTINGS'); ?></h3>
            <ul class="zo2-tabs">
                <li><a class="active" href="#column-basic" data-toggle="tab"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_BASIC'); ?></a></li>
                <li><a href="#column-responsive" data-toggle="tab"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_RESPONSIVE'); ?></a></li>
            </ul>
        </div>
        <div class="modal-body">
            <div class="form-horizontal">
                <div class="zo2-tabs-content">
                    <div class="active" id="column-basic">      
                        <!-- begin -->
                        <div class="control-group">
                            <label class="control-label" for="dlColJDoc"><?php echo JText::_('ZO2_ADMIN_COMMON_JDOC'); ?></label>
                            <div class="controls">
                                <!-- http://docs.joomla.org/Jdoc_statements -->
                                <select id="dlColJDoc">
                                    <optgroup label="Joomla! Document">
                                        <option value="component"><?php echo JText::_('ZO2_ADMIN_COMMON_COMPONENT'); ?></option>
                                        <option value="modules"><?php echo JText::_('ZO2_ADMIN_COMMON_MODULES'); ?></option>                                    
                                        <option value="messsge"><?php echo JText::_('ZO2_ADMIN_COMMON_MESSAGE'); ?></option>
                                    </optgroup>
                                    <!-- These are extended for 3rd parties -->
                                    <optgroup label="Menu">
                                        <option value="canvasmenu"><?php echo JText::_('ZO2_ADMIN_COMMON_CANVAS'); ?></option>
                                        <option value="megamenu"><?php echo JText::_('ZO2_ADMIN_COMMON_MEGA'); ?></option>
                                    </optgroup>
                                    <!-- These are extended for 3rd parties -->
                                    <optgroup label="3rd parties">
                                        <?php foreach ($addons as $key => $value) : ?>
                                            <option value="addon-<?php echo $key; ?>"><?php echo $key; ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <!-- begin -->
                        <div class="control-group">
                            <label class="control-label" for="dlColType"><?php echo JText::_('ZO2_ADMIN_COMMON_POSITION'); ?></label>
                            <div class="controls">
                                <select id="dlColPosition">
                                    <option value="">(<?php echo JText::_('ZO2_ADMIN_COMMON_SELECT_NONE'); ?>)</option>
                                    <option value="component"><?php echo JText::_('ZO2_ADMIN_COMMON_COMPONENT'); ?></option>
                                    <option value="message"><?php echo JText::_('ZO2_ADMIN_COMMON_MESSAGE'); ?></option>
                                    <option value="mega_menu"><?php echo JText::_('ZO2_ADMIN_COMMON_MEGA_MENU'); ?></option>
                                    <?php foreach ($positions as $pos) : ?>
                                        <option value="<?php echo $pos ?>"><?php echo $pos ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="dlColWidth"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_WIDTH'); ?></label>
                            <div class="controls">
                                <select id="dlColWidth">
                                    <option value="1"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_SPAN'); ?>1</option>
                                    <option value="2"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_SPAN'); ?>2</option>
                                    <option value="3"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_SPAN'); ?>3</option>
                                    <option value="4"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_SPAN'); ?>4</option>
                                    <option value="5"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_SPAN'); ?>5</option>
                                    <option value="6"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_SPAN'); ?>6</option>
                                    <option value="7"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_SPAN'); ?>7</option>
                                    <option value="8"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_SPAN'); ?>8</option>
                                    <option value="9"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_SPAN'); ?>9</option>
                                    <option value="10"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_SPAN'); ?>10</option>
                                    <option value="11"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_SPAN'); ?>11</option>
                                    <option value="12"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_SPAN'); ?>12</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="ddlColOffset"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_OFFSET'); ?></label>
                            <div class="controls">
                                <select id="ddlColOffset">
                                    <option value="0"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_NO_OFFSET'); ?></option>
                                    <option value="1"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_OFFSET_LB'); ?>1</option>
                                    <option value="2"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_OFFSET_LB'); ?>2</option>
                                    <option value="3"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_OFFSET_LB'); ?>3</option>
                                    <option value="4"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_OFFSET_LB'); ?>4</option>
                                    <option value="5"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_OFFSET_LB'); ?>5</option>
                                    <option value="6"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_OFFSET_LB'); ?>6</option>
                                    <option value="7"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_OFFSET_LB'); ?>7</option>
                                    <option value="8"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_OFFSET_LB'); ?>8</option>
                                    <option value="9"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_OFFSET_LB'); ?>9</option>
                                    <option value="10"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_OFFSET_LB'); ?>10</option>
                                    <option value="11"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_OFFSET_LB'); ?>11</option>
                                    <option value="12"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_OFFSET_LB'); ?>12</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="ddlColStyle"><?php echo JText::_('ZO2_ADMIN_STYLEEDIT_STYLE'); ?></label>
                            <div class="controls">
                                <select id="ddlColStyle">
                                    <option value="none"><?php echo JText::_('ZO2_FORMFIELD_SOCIALORDER_NONE'); ?></option>
                                    <?php foreach ($customStyles as $cs) : ?>
                                        <option value="<?php echo $cs ?>"><?php echo $cs ?></option>
                                    <?php endforeach; ?>
                                    <option value="rounded"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_ROUNED'); ?></option>
                                    <option value="table"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_TABLE'); ?></option>
                                    <option value="horz"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_HORZ'); ?></option>
                                    <option value="xhtml"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_XHTML'); ?></option>
                                    <option value="outline"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_OUTLINE'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="txtColId"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_ID'); ?></label>
                            <div class="controls">
                                <input type="text" id="txtColId" placeholder="<?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_COLID'); ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="txtColCss"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_CUSTOM_CSS'); ?></label>
                            <div class="controls">
                                <input type="text" id="txtColCss" placeholder="<?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_CUSTOM_COLCSS'); ?>">
                            </div>
                        </div>
                        <!-- end -->
                    </div>
                    <div id="column-responsive">
                        <div class="control-group">
                            <!--
                            <span class="switch_title">Phone</span>
                            <label class="switch_wrap" for="cbColumnPhoneVisibility">
                                <input id="cbColumnPhoneVisibility" type="checkbox" value="1" />
                                <div class="switch"><span class="bullet"></span></div>
                            </label>
                            -->
                            <div class="control-label">
                                <div class="control-label"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_PHONE'); ?></div>
                            </div>
                            <div class="controls btn-group btn-group-onoff" id="btgColPhone">
                                <button class="btn btn-on"><?php echo JText::_('ZO2_ADMIN_COMMON_ON'); ?></button>
                                <button class="btn btn-off"><?php echo JText::_('ZO2_ADMIN_COMMON_OFF'); ?></button>
                            </div>
                        </div>
                        <div class="control-group">
                            <!--
                            <span class="switch_title">Tablet</span>
                            <label class="switch_wrap" for="cbColumnTabletVisibility">
                                <input id="cbColumnTabletVisibility" type="checkbox" value="1" />
                                <div class="switch"><span class="bullet"></span></div>
                            </label>
                            -->
                            <div class="control-label">
                                <div class="control-label"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_TABLET'); ?></div>
                            </div>
                            <div class="controls btn-group btn-group-onoff" id="btgColTablet">
                                <button class="btn btn-on"><?php echo JText::_('ZO2_ADMIN_COMMON_ON'); ?></button>
                                <button class="btn btn-off"><?php echo JText::_('ZO2_ADMIN_COMMON_OFF'); ?></button>
                            </div>
                        </div>
                        <div class="control-group">
                            <!--
                            <span class="switch_title">Desktop</span>
                            <label class="switch_wrap" for="cbColumnDesktopVisibility">
                                <input id="cbColumnDesktopVisibility" type="checkbox" value="1" />
                                <div class="switch"><span class="bullet"></span></div>
                            </label>
                            -->
                            <div class="control-label">
                                <div class="control-label"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_DESKTOP'); ?></div>
                            </div>
                            <div class="controls btn-group btn-group-onoff" id="btgColDesktop">
                                <button class="btn btn-on"><?php echo JText::_('ZO2_ADMIN_COMMON_ON'); ?></button>
                                <button class="btn btn-off"><?php echo JText::_('ZO2_ADMIN_COMMON_OFF'); ?></button>
                            </div>
                        </div>
                        <div class="control-group">
                            <!--
                            <span class="switch_title">Large desktop</span>
                            <label class="switch_wrap" for="cbColumnLargeDesktopVisibility">
                                <input id="cbColumnLargeDesktopVisibility" type="checkbox" value="1" />
                                <div class="switch"><span class="bullet"></span></div>
                            </label>
                            -->
                            <div class="control-label">
                                <div class="control-label"><?php echo JText::_('ZO2_ADMIN_FORMFIELD_LAYOUT_LARGE_DESKTOP'); ?></div>
                            </div>
                            <div class="controls btn-group btn-group-onoff" id="btgColLargeDesktop">
                                <button class="btn btn-on"><?php echo JText::_('ZO2_ADMIN_COMMON_ON'); ?></button>
                                <button class="btn btn-off"><?php echo JText::_('ZO2_ADMIN_COMMON_OFF'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('ZO2_ADMIN_COMMON_CLOSE'); ?></button>
            <button id="btnSaveColSettings" class="btn btn-primary"><?php echo JText::_('ZO2_ADMIN_COMMON_SAVE_CHANGES'); ?></button>
        </div>
    </div>
</div>