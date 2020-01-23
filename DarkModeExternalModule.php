<?php

namespace DCC\DarkModeExternalModule;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;
use \REDCap;

class DarkModeExternalModule extends AbstractExternalModule
{
    private $userNames;
    private $css;
    private $background_color;
    private $normal_text_color;
    private $link_color;
    private $light_gray;
    private $dark_gray;
    private $success;
    private $warning;
    private $canUse;

    function __construct()
    {
        parent::__construct();
        $this->setValues();
        $this->check_users();
        if ($this->canUse) {
            $this->setColors();
            $this->createCSS();
        } else {
            echo 'it did not run' . $this->canUse;
        }
    }

    function redcap_every_page_top($project_id, $record, $instrument)
    {
        $this->outputCSS();
    }

// todo
    private function check_users()
    {
        $this->canUse = false;
        if (USERID === $this->userNames) {
            $this->canUse = true;
        }
    }

    private function setValues()
    {
        /** Get Users that should have the color change effect */
        $this->userNames = AbstractExternalModule::getSystemSetting('userNames');

        /** Get Background color */
        $this->background_color = AbstractExternalModule::getSystemSetting('background_color');
        if (is_null($this->background_color)) {
            $this->background_color = "#000";
        }

        /** Get normal text color */
        $this->normal_text_color = AbstractExternalModule::getSystemSetting('normal_text_color');
        if (is_null($this->normal_text_color)) {
            $this->normal_text_color = "#FFF";
        }

        /** Get link color */
        $this->link_color = AbstractExternalModule::getSystemSetting('link_color');
        if (is_null($this->link_color)) {
            $this->link_color = "#FFF";
        }

        /** Get success color */
        $this->success_color = AbstractExternalModule::getSystemSetting('success_color');
        if (is_null($this->success_color)) {
            $this->success_color = "#A5CC7A";
        }

        /** Get link color */
        $this->warning_color = AbstractExternalModule::getSystemSetting('warning_color');
        if (is_null($this->warning_color)) {
            $this->warning_color = "#DC143C";
        }
    }

    private function setColors()
    {
        // $this->background_color = '#000';
        // $this->normal_text_color = '#f8f8f8';
        // $this->link_color = '#f8f8f8';
        $this->light_gray = '#EEE';
        $this->dark_gray = '#222';
        $this->success = '#A5CC7A';
        $this->warning = '#DC143C';
    }

    private function createCSS()
    {
        /** todo: create arrays for elements that should have the background changed,
         * Then create strings that build each element
         * This way some items, when left blank, will NOT be overriden by the E.M.
         * Instead leaving the default REDCap css un-affected.
        **/

        $light_gray = $this->light_gray;
        $dark_gray = $this->dark_gray;
        $white = $this->normal_text_color;
        $success = $this->success;
        $warning = $this->warning;

        $css = '<style>' .
            'body{' .
            '  color:' . $white . ';' .
            '  background-color:' . $this->background_color . ';' .
            '}' . PHP_EOL .

            '.menubox {' .
            '  background-color:' . $dark_gray . ';' .
            '}' . PHP_EOL .

            'a, a:visited, a:link {' .
            '  color:' . $light_gray . ';' .
            '}' . PHP_EOL .

            '.x-panel-header {' .
            '  background-color:' . $dark_gray . ';' .
            '  color:' . $light_gray . ';' .
            '  border-color:' . $this->background_color . ';' .
            '}' . PHP_EOL .

            '#west .fas, #west .far, #west .fa {' .
            '  color: #555;' .
            '}' . PHP_EOL .

            '#west {' .
            '  border-color: ' . $this->background_color . ';' .
            '  background-color:' . $dark_gray . ';' .
            '}' . PHP_EOL .
            '#south {' .
            '  border-color: ' . $this->background_color . ';' .
            '  background-color:' . $this->background_color . ';' .
            '}' . PHP_EOL .

            '#pagecontainer {background-color:' . $this->background_color . ';}' . PHP_EOL .

            '#control_center_menu {' .
            '  background-color:' . $this->background_color . ';' .
            '  color:' . $light_gray . ';' .
            '}' . PHP_EOL .

            '#center{background-color:' . $this->background_color . ';}' . PHP_EOL .

            '#project-menu-logo {' .
            '  background-color:' . $white . ';' .
            '  border-color:' . $this->background_color . ';' .
            '}' . PHP_EOL .

            '#subheader { background-image:none;}' . PHP_EOL .

            '.projhdr {' .
            '  background-color:' . $this->background_color . ';' .
            '  color:' . $light_gray . ';' .
            '  border-color:' . $this->background_color . ';' .
            '}' . PHP_EOL .

            '.yellow {' .
            '  background-color:' . $this->background_color . ';' .
            '  color:' . $white . ';' .
            '  border-color:' . $this->background_color . ';' .
            '}' . PHP_EOL .

            '.header {' .
            '  background-color:' . $this->background_color . ';' .
            '  color:' . $light_gray . ';' .
            '  border-color:' . $this->background_color . ';' .
            '}' . PHP_EOL .

            '.well {' .
            '  background-color:' . $this->background_color . ';' .
            '  border-color:' . $light_gray . ';' .
            '}' . PHP_EOL .

            'nav.navbar {' .
            '  background-color:' . $this->background_color . ';' .
            '  color:' . $light_gray . ';' .
            '  border-color:' . $this->background_color . ';' .
            '}' . PHP_EOL .

            '.table {' .
            '  color:' . $light_gray . ';' .
            '}' . PHP_EOL .

            '.external-modules-configure-button, ' .
            '.external-modules-disable-button,' .
            '.external-modules-usage-button {' .
            '  color:' . $light_gray . ';' .
            '  background: transparent;' .
            '}' . PHP_EOL .

            '.labelrc,' .
            '.labelmatrix,' .
            '.data,' .
            '.data2,' .
            '.data_matrix {' .
            '  background-color:' . $this->background_color . ' !important;' .
            '  border-color:' . $this->background_color . ';' .
            '  color:' . $white . ' !important;' .
            ';}' . PHP_EOL .

            '.flexigrid div.mDiv,' .
            '.flexigrid div.hDiv,' .
            '.flexigrid div.bDiv {' .
            '  background-color:' . $this->background_color . ';' .
            '  color:' . $white . ';' .
            '}' . PHP_EOL .

            '.flexigrid div.mDiv div.ftitle {' .
            '  color:' . $white . ';' .
            '}' . PHP_EOL .

            '.myprojstripe {' .
            '  background-color:' . $this->background_color . ' !important;' .
            '  border-color: ' . $this->background_color . ';' .
            '}' . PHP_EOL .

            '#table-proj_table tr:not(.nohover):hover td,
            #table-proj_table tr:not(.nohover):hover td.sorted,
            #table-proj_table tr:not(.nohover).trOver td.sorted,
            #table-proj_table tr:not(.nohover).trOver td {' .
            '  background-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .

            '.flexigrid tr.erow td {' .
            '  background-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .

            '.flexigrid div.bDiv tr:hover td,
     		.flexigrid div.bDiv tr:hover td.sorted,
	    	.flexigrid div.bDiv tr.trOver td.sorted,
		    .flexigrid div.bDiv tr.trOver td { ' .
            '  background-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .

            '.modal-content {' .
            '  background-color:' . $dark_gray . ';' .
            '  color:' . $white . ';' .
            '}' . PHP_EOL .

            'input[type="button"],' .
            'button.close {' .
            '  background-color:' . $dark_gray . ';' .
            '  color:' . $white . ';' .
            '}' . PHP_EOL .

            'input[type="submit"] {' .
            '  background-color:' . $dark_gray . ';' .
            '  color:' . $white . ';' .
            '}' . PHP_EOL .

            'input[type="file"] {' .
            '  background-color:' . $this->background_color . ';' .
            '  color:' . $white . ';' .
            '}' . PHP_EOL .

            'button {' .
            '  background-color:' . $dark_gray . ';' .
            '  color:' . $white . ';' .
            '}' . PHP_EOL .

            '.chklist {' .
            '  background-color:' . $dark_gray . ';' .
            '}' . PHP_EOL .

            '#img-external_resources,
             #img-modules,
             #img-define_events,
             #img-test_project,
             #img-user_rights,
             #img-design,
             #img-modify_project,
             #img-randomization,
             #img- {' .
            '  display:none;' .
            '}' . PHP_EOL .

            '.ui-widget-content {' .
            '  background-color:' . $dark_gray . ' !important;' .
            '  color:' . $white . ';' .
            '}' . PHP_EOL .

            '.ui-widget-header {' .
            '  background: transparent !important;' .
            '  border-color: transparent !important;' .
            '  color:' . $white . ';' .
            '}' . PHP_EOL .

            'textarea.x-form-field,' .
            '.x-form-field {' .
            '  background-color:' . $this->background_color . ' !important;' .
            '  color:' . $white . ' !important;' .
            '}' . PHP_EOL .

            '#div_var_name {' .
            '  background-color:' . $this->background_color . ' !important;' .
            '  color:' . $white . ' !important;' .
            '}' . PHP_EOL .

            '#div_add_field2 input,' .
            '#div_add_field2 select,' .
            '#div_add_field2 textarea,' .
            '#addMatrixPopup input,' .
            '#addMatrixPopup select,' .
            '#addMatrixPopup textarea,' .
            '.x-form-textarea {' .
            '  background-color:' . $this->background_color . ' !important;' .
            '  color:' . $white . ' !important;' .
            '}' . PHP_EOL .

            '.datagreen {' .
            '  color:' . $success . ';' .
            '  background-color: transparent;' .
            '  border-color: transparent;' .
            '  background-image: none;' .
            '}' . PHP_EOL .

            '.datared {' .
            '  color:' . $warning . ';' .
            '  background-color: transparent;' .
            '  border-color: transparent;' .
            '}' . PHP_EOL .

            'div.darkgreen {' .
            '  color:' . $white . ';' .
            '  background-color: transparent !important;' .
            '}' . PHP_EOL .

            'div.green {' .
            '  color:' . $white . ';' .
            '  background-color:' . $dark_gray . ';' .
            '}' . PHP_EOL .

            '.context_msg {' .
            '  background-color:transparent;' .
            '}' . PHP_EOL .


            'div.blue {' .
            '  color:' . $white . ';' .
            '  background-color: transparent;' .
            '  border-color: transparent;' .
            '}' . PHP_EOL .

            'div.gray {' .
            '  color:' . $white . ';' .
            '  background-color:' . $dark_gray . ';' .
            '  border-color: transparent;' .
            '}' . PHP_EOL .

            'div.red {' .
            '  color:' . $warning . ';' .
            '  background-color:' . $dark_gray . ';' .
            '  border-color: transparent;' .
            '}' . PHP_EOL .

            '.label_header {' .
            '  color:' . $white . ';' .
            '  background-color: transparent !important;' .
            '}' . PHP_EOL .

            '#addUsersRolesDiv {' .
            '  color:' . $white . ';' .
            '  background-color: transparent !important;' .
            '}' . PHP_EOL .

            '#rsd_legend {' .
            '  color:' . $white . ';' .
            '  background-color: transparent !important;' .
            '}' . PHP_EOL .


            '.ui-dialog .ui-dialog-buttonpane button {' .
            '  color:' . $white . ' !important;' .
            '  background-color: transparent;' .
            '}' . PHP_EOL .


            '.jqbuttonsm, .jqbuttonmed {' .
            '  color:' . $white . ' !important;' .
            '  background-color: transparent;' .
            '}' . PHP_EOL .

            '.ui-state-hover,' .
            '.ui-widget-content .ui-state-hover,' .
            '.ui-widget-header .ui-state-hover,' .
            '.ui-state-focus,' .
            '.ui-widget-content .ui-state-focus,' .
            '.ui-widget-header .ui-state-focus,' .
            '.ui-button:hover,' .
            '.ui-button:focus {' .
            '  color:' . $white . ' !important;' .
            '  background-color: ' . $this->background_color . ';' .
            '}' . PHP_EOL .


            'table.dataTable thead tr th {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .

            'tr.even {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .

            'tr.odd {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $dark_gray . ' !important;' .
            '}' . PHP_EOL .

            'table.fixedHeader-floating {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $dark_gray . ' !important;' .
            '}' . PHP_EOL .

            '.greenhighlight,' .
            '.greenhighlight  table td {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $dark_gray . ' !important;' .
            '}' . PHP_EOL .

            '#formSaveTip {' .
            '  background-color:' . $dark_gray . ';' .
            '}' . PHP_EOL .


            '#group_table div {' .
            '  background-color:' . $dark_gray . ' !important;' .
            '  color:' . $white . ' !important;' .
            '}' . PHP_EOL .

            '#group_table div div span {' .
            '  color:' . $white . ' !important;' .
            '}' . PHP_EOL .

            'h2.pending-title {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $dark_gray . ' !important;' .
            '}' . PHP_EOL .

            'div.request-container {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $dark_gray . ' !important;' .
            '}' . PHP_EOL .

            'div.graph-title {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $dark_gray . ' !important;' .
            '}' . PHP_EOL .


            'form table {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .

            '.cc_info {' .
            '  color:' . $white . ';' .
            '}' . PHP_EOL .

            'textarea.x-form-field, input.x-form-field, select.x-form-field {' .
            '  color:' . $white . ';' .
            '  background-color:' . $this->background_color . ';' .
            '}' . PHP_EOL .


            '#control_center_window div {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .

            '#enableListBtn {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .

            '.cc_label {' .
            '  background-color:' . $this->background_color . ';' .
            '}' . PHP_EOL .

            '#user_list_table tr {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .

            '#mysql_dashboard td {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .

            '#reload_dropdown {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .


            'select {' .
            '  color:' . $white . ';' .
            '  background-color:' . $this->background_color . ';' .
            '}' . PHP_EOL .

            '#pubContent table {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .

            '.btn-defaultrc {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .


            '#surveyEmailFieldEnableDialog div div {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .

            'a.help:link, a.help:visited, a.help:active, a.help:hover {' .
            '  color:' . $dark_gray . ' !important;' .
            '  background-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .

            'div.chklist  {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .

            'img[src*="checkbox_cross.png"],' .
            'img[src*="checkbox_checked.png"] {' .
            '  display:none;' .
            '}' . PHP_EOL .

            'img[src*="qrcode.png"] {' .
            '  background-color:' . $white . ';' .
            '}' . PHP_EOL .

            'li.d-none a {' .
            '  color:' . $white . ' !important;' .
            '}' . PHP_EOL .

            'div[style*="background-color:#f5f5f5;"],' .
            'div[style*="background-color:#FAFAFA;"] {' .
            '  color:' . $white . ';' .
            '  background-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .

            'a[style*="color:#000;"] {' .
            '  color:' . $white . ' !important;' .
            '}' . PHP_EOL .

            'span[style*="color:#555;"] {' .
            '  color:' . $white . ' !important;' .
            '}' . PHP_EOL .

            'input[style*="background-color: rgb(255, 255, 255)"] {' .
            '  background-color: transparent !important;' .
            '}' . PHP_EOL .

            'th[style*="background-color:#ddd;"],' .
            'td[style*="background-color:#f5f5f5;"]' .
            '{' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $dark_gray . ' !important;' .
            '}' . PHP_EOL .


            'td[style*="background-color: rgb(245, 245, 245)"],
             td[style*="background-color:#eee"] {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .

            'td[style*="color:#333;"] {' .
            '  color:' . $white . ' !important;' .
            '}' . PHP_EOL .

            'div[style*="background-color:#EFF6E8;"],' .
            'div[style*="background-color:#eee;"],' .
            'div[style*="background-color:#ddd;"]' .
            '{' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $dark_gray . ' !important;' .
            '}' . PHP_EOL .


            'textarea[style*="background: rgb(247, 235, 235)"] {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .

            'td.frmedit, div.frmedit, td.frmedit_row {' .
            '  color:' . $white . ' !important;' .
            '  background-color:' . $this->background_color . ' !important;' .
            '  border-bottom-color:' . $dark_gray . ' !important;' .
            '}' . PHP_EOL .

            'table.form_border {' .
            '  border-color:' . $this->background_color . ' !important;' .
            '}' . PHP_EOL .

            'input.btn2 {' .
            '  border-color:' . $light_gray . ';' .
            '}' . PHP_EOL .

            '#element_enum_clone {' .
            '  color:' . $white . ' !important;' .
            '}' . PHP_EOL .

            '.mc_raw_val_fix b {' .
            '  color:' . $warning . ';' .
            '}' . PHP_EOL .

            '.dropdown-menu {' .
            '  background-color:' . $dark_gray . ';' .
            '  color:' . $white . ';' .
            '}' . PHP_EOL .

            '#dashboard-config {' .
            '  background-color:' . $this->background_color . ' !important;' .
            '  color:' . $white . ' !important;' .
            '}' . PHP_EOL .

            '#choose_select_forms_events_div_sub {' .
            '  background-color:' . $dark_gray . ' !important;' .
            '  color:' . $white . ' !important ;' .
            '}' . PHP_EOL .

            'table.sched_table {' .
            '  background-color: transparent !important;' .
            '}' . PHP_EOL .

            '.alert-success {' .
            '  background-color: transparent;' .
            '}' . PHP_EOL .

            '#emailPartForm fieldset {' .
            '  background-color: transparent !important;' .
            '}' . PHP_EOL .

            '.select2-container--default .select2-selection--single .select2-selection__rendered {' .
            '  background-color:' . $this->background_color . ' !important;' .
            '  color:' . $white . ' !important;' .
            '}' . PHP_EOL .

            '.author-institution {' .
            '  color:' . $this->normal_text_color . ';' .
            '}' . PHP_EOL .


            '</style>' . PHP_EOL;

        $this->css = str_replace('  ', ' ', $css);
    }

    private function outputCSS()
    {
        echo $this->css;
    }


}
