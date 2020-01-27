<?php

namespace DCC\DarkModeExternalModule;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;
use \REDCap;

class DarkModeExternalModule extends AbstractExternalModule
{
    private $userNames;
    private $css;
    private $background_primary_color;
    private $background_secondary_color;
    private $background_tertiary_color;
    private $text_primary_color;
    private $text_secondary_color;
    private $text_tertiary_color;
    private $link_primary_color;
    private $white;
    private $black;
    private $light_gray;
    private $primary_color;
    private $success_color;
    private $warning_color;
    private $danger_color;
    private $canUse;

    function __construct()
    {
        parent::__construct();

        $this->check_users();

        if ($this->canUse) {
            $this->setValues();
            $this->setColors();
            $this->createCSS();
        }
    }

    function redcap_every_page_top($project_id, $record, $instrument)
    {
        $this->outputCSS();
    }

// todo
    private function check_users()
    {
        /** Get Users that should have the color change effect */
        $this->userNames = AbstractExternalModule::getSystemSetting('user_names');

        $id = USERID;
        $this->canUse = false;
        if (USERID === $this->userNames) {
            $this->canUse = true;
        } else {
            echo '<script>console.log("user name does NOT match ' . $id . ' & ' . $this->userNames . ' ");</script>';
        }
    }

    private function setValues()
    {


        /** Primary Background color */
        $this->background_primary_color = AbstractExternalModule::getSystemSetting('background_primary_color');

        /** Primary text color */
        $this->text_primary_color = AbstractExternalModule::getSystemSetting('text_primary_color');

        /** Link color */
        $this->link_primary_color = AbstractExternalModule::getSystemSetting('link_color');

        /** Primary color */
        $this->primary_color = AbstractExternalModule::getSystemSetting('primary_color');

        /** Success color */
        $this->success_color = AbstractExternalModule::getSystemSetting('success_color');

        /** Warning color */
        $this->warning_color = AbstractExternalModule::getSystemSetting('warning_color');

        /** Danger color */
        $this->danger_color = AbstractExternalModule::getSystemSetting('danger_color');

        /*
         * todo think about hover colors
        */
    }

    private function setColors()
    {
        // trick: enter in a text color like black and everything will be black
        if($this->isHex($this->background_primary_color)) {
            $this->background_secondary_color = $this->adjustBrightness($this->background_primary_color, 0.15);
            $this->background_tertiary_color = $this->adjustBrightness($this->background_primary_color, 0.30);
        } else {
            $this->background_secondary_color = $this->background_primary_color;
            $this->background_tertiary_color = $this->background_primary_color;
        }
        if($this->isHex($this->text_primary_color)) {
            $this->text_secondary_color = $this->adjustBrightness($this->text_primary_color, -0.15);
            $this->text_tertiary_color = $this->adjustBrightness($this->text_primary_color, -0.30);
        } else {
            $this->text_secondary_color = $this->text_primary_color;
            $this->text_tertiary_color = $this->text_primary_color;

        }
        $this->light_gray = '#DDD';
        $this->white = '#FFF';
        $this->black = '#000';

        if(!$this->primary_color) {
            $this->primary_color = '#2e6da4';
        }
        if(!$this->success_color) {
            $this->success_color = '#28a745';
        }
        if(!$this->warning_color) {
            $this->warning_color = '#ffc107';
        }

        if(!$this->danger_color) {
            $this->danger_color = '#dc3545';
        }
    }

    private function createCSS()
    {
        /** todo: create arrays for elements that should have the background changed,
         * Then create strings that build each element
         * This way some items, when left blank, will NOT be overriden by the E.M.
         * Instead leaving the default REDCap css un-affected.
         **/


        // This way the background-color would not be overriding anything when the user left it blank.
        $black = "#000";

        /*shorthand codes which allow the ability to NOT set a color if not defined.
         * bgc = Background Color
         * tc = Text Color
         * lc = Link Color
        */

        // set all background colors if the primary color is set
        if ($this->background_primary_color) {
            $bgc1 = '  background-color:' . $this->background_primary_color . ' !important;';
            $bgc2 = '  background-color:' . $this->background_secondary_color . ' !important;';
            $bgc3 = '  background-color:' . $this->background_tertiary_color . ' !important;';
            $bgTrans = '  background-color:transparent !important;';
        } else {
            $bgc1 = '';
            $bgc2 = '';
            $bgc3 = '';
            $bgTrans = '';
        }

        // set all text colors if the primary text color is set
        if ($this->text_primary_color) {
            $tc1 = '  color:' . $this->text_primary_color . ' !important;';
            $tc2 = '  color:' . $this->text_secondary_color . ' !important;';
            $tc3 = '  color:' . $this->text_tertiary_color . ' !important;';
        } else {
            $tc1 = '';
            $tc2 = '';
            $tc3 = '';
        }


        if ($this->link_primary_color) {
            $lc1 = '  color:' . $this->link_primary_color . ' !important;';
        } else {
            $lc1 = '';
        }

        $bg_pc = '  background-color:' . $this->primary_color . ' !important;';

        $css = '<style>' .
            'body{' .
            $tc1 .
            $bgc1 .
            '}' . PHP_EOL .

            '.menubox {' .
            $bgc2 .
            '}' . PHP_EOL .

            'A, A:visited, A:link {' .
            $lc1 .
            '}' . PHP_EOL .

            'a.aGrid:visited, a.aGrid:link {' .
            $lc1 .
            '}' . PHP_EOL .

            'a[style*="color:#800000"]{' .
            $lc1 .
            '}' . PHP_EOL .

            '#menuLnkChooseOtherRec {' .
            $lc1 . '}' . PHP_EOL .

            '.x-panel-header {' .
            $bgc2 .
            '  border-color:' . $this->background_secondary_color . ';' .
            $tc2.
            '}' . PHP_EOL .

            '#west .fas, #west .far, #west .fa {' .
            $tc2.
            '}' . PHP_EOL .

            '#west {' .
            '  border-color: ' . $this->background_tertiary_color . ';' .
            $bgc2 .
            '}' . PHP_EOL .

            '#south {' .
            '  border-color: transparent;' .
            $bgTrans .
            '}' . PHP_EOL .

            '#pagecontainer {' .
            $bgTrans .
            '}' . PHP_EOL .

            '#control_center_menu {' .
            $bgTrans .
            $tc2.
            '}' . PHP_EOL .

            '.cc_menu_divider {' .
            $bgc2 .
            '}' . PHP_EOL .


            '#center {' .
            $bgTrans .
            '}' . PHP_EOL .

            '#project-menu-logo {' .
            '  background-color:' . $this->white . ';' .
            '  border-color:' . $this->background_primary_color . ';' .
            '}' . PHP_EOL .

            '#senditbox {' .
            $bgTrans .
            '}' . PHP_EOL .

            '#subheader { background-image:none;}' . PHP_EOL .

            '.projhdr {' .
            $bgTrans .
            $tc2.
            '  border-color:' . $this->background_primary_color . ';' .
            '}' . PHP_EOL .

            '.yellow {' .
            $bgTrans .
            '  color:' . $this->white  . ';' .
            '  border-color:' . $this->background_primary_color . ';' .
            '}' . PHP_EOL .

            '.header {' .
            $bgTrans .
            $tc2.
            '  border-color:' . $this->background_primary_color . ';' .
            '}' . PHP_EOL .

            '.well {' .
            $bgc2 .
            '  border-color:' . $this->text_secondary_color . ';' .
            '}' . PHP_EOL .

            '.table {' .
            $tc2.
            '}' . PHP_EOL .

            '.external-modules-configure-button, ' .
            '.external-modules-disable-button,' .
            '.external-modules-usage-button {' .
            $tc2.
            $bgc2 .
            '  border-color: ' . $this->background_tertiary_color . ';' .
            '}' . PHP_EOL .

            '.labelrc,' .
            '.labelmatrix,' .
            '.data,' .
            '.data2,' .
            '.data_matrix {' .
            $bgTrans .
            '  border-color: transparent;' .
            $tc1 .
            ';}' . PHP_EOL .

            '.flexigrid div.mDiv,' .
            '.flexigrid div.hDiv,' .
            '.flexigrid div.bDiv {' .
            $bgTrans .
            '}' . PHP_EOL .

            '.flexigrid {' .
            $tc1 .
            '}' . PHP_EOL .

            '.flexigrid div.mDiv div.ftitle {' .
            $tc1 .
            '}' . PHP_EOL .

            '.myprojstripe {' .
            $bgTrans .
            '  border-color: transparent;' .
            '}' . PHP_EOL .

            '#table-proj_table tr:not(.nohover):hover td,' .
            '#table-proj_table tr:not(.nohover):hover td.sorted,' .
            '#table-proj_table tr:not(.nohover).trOver td.sorted,' .
            '#table-proj_table tr:not(.nohover).trOver td {' .
            $bgc2 .
            '}' . PHP_EOL .

            '.flexigrid tr.erow td {' .
            $bgTrans .
            '}' . PHP_EOL .

            '.flexigrid div.bDiv tr:hover td,' .
            '.flexigrid div.bDiv tr:hover td.sorted,' .
            '.flexigrid div.bDiv tr.trOver td.sorted,' .
            '.flexigrid div.bDiv tr.trOver td { ' .
            $bgTrans .
            '}' . PHP_EOL .

            '.modal-content {' .
            $bgc2 .
            $tc1 .
            '}' . PHP_EOL .

            'input[type="button"],' .
            'button.close {' .
            $bgc2 .
            $lc1 .
            '}' . PHP_EOL .

            'input[type="submit"] {' .
            $bgc2 .
            $lc1 .
            '}' . PHP_EOL .

            'input[type="file"] {' .
            $bgTrans .
            $lc1 .
            '}' . PHP_EOL .

            'input[type="text"] {' .
            $bgTrans .
            $tc1 .
            '}' . PHP_EOL .

            // todo this sets all buttons.  btn-success, btn-warning, btn-primary would need overrides!

            'button {' .
            $bgc2 .
            $lc1 .
            '}' . PHP_EOL .

            'button.success {' .
            $bgc2 .
            $lc1 .
            '}' . PHP_EOL .

            '.chklist {' .
            $bgc2 .
            '}' . PHP_EOL .

            '#img-external_resources,' .
            '#img-modules,' .
            '#img-define_events,' .
            '#img-test_project,' .
            '#img-user_rights,' .
            '#img-design,' .
            '#img-modify_project,' .
            '#img-randomization,' .
            '#img- {' .
            '  display:none;' .
            '}' . PHP_EOL .

            '.ui-widget-content {' .
            $bgc2 .
            $tc1 .
            '}' . PHP_EOL .

            '.ui-widget-header {' .
            $bgTrans .
            '  border-color: transparent !important;' .
            $tc1 .
            '}' . PHP_EOL .

            'textarea.x-form-field,' .
            '.x-form-field {' .
            $bgTrans .
            $tc1 .
            '}' . PHP_EOL .

            '#div_var_name {' .
            $bgTrans .
            $tc1 .
            '}' . PHP_EOL .

            '#div_add_field2 input,' .
            '#div_add_field2 select,' .
            '#div_add_field2 textarea,' .
            '#addMatrixPopup input,' .
            '#addMatrixPopup select,' .
            '#addMatrixPopup textarea,' .
            '.x-form-textarea {' .
            $bgTrans .
            $tc1 .
            '}' . PHP_EOL .

            '.datagreen {' .
            '  color:' . $this->success_color . ';' .
            $bgTrans .
            '  border-color: transparent;' .
            '  background-image: none;' .
            '}' . PHP_EOL .

            '.datared {' .
            '  color:' . $this->warning_color . ';' .
            $bgTrans .
            '  border-color: transparent;' .
            '}' . PHP_EOL .

            'div.darkgreen {' .
            $tc1 .
            $bgTrans .
            '}' . PHP_EOL .

            'div.green {' .
            $tc1 .
            $bgc2 .
            '}' . PHP_EOL .

            '.context_msg {' .
            $bgTrans .
            '}' . PHP_EOL .


            'div.blue {' .
            $tc1 .
            $bgTrans .
            '  border-color: transparent;' .
            '}' . PHP_EOL .

            'div.gray {' .
            $tc1 .
            $bgc2 .
            '  border-color: transparent;' .
            '}' . PHP_EOL .

            'div.red {' .
            '  color:' . $this->warning_color . ';' .
            $bgc2 .
            '  border-color: transparent;' .
            '}' . PHP_EOL .

            '.label_header {' .
            $tc1 .
            $bgTrans .
            '}' . PHP_EOL .

            '#addUsersRolesDiv {' .
            $tc1 .
            $bgTrans .
            '}' . PHP_EOL .

            '#rsd_legend {' .
            $tc1 .
            $bgTrans .
            '}' . PHP_EOL .


            '.ui-dialog .ui-dialog-buttonpane button {' .
            $lc1 .
            $bgTrans .
            '}' . PHP_EOL .


            '.jqbuttonsm, .jqbuttonmed {' .
            $lc1 .
            $bgTrans .
            '}' . PHP_EOL .

            '.ui-state-hover,' .
            '.ui-widget-content .ui-state-hover,' .
            '.ui-widget-header .ui-state-hover,' .
            '.ui-state-focus,' .
            '.ui-widget-content .ui-state-focus,' .
            '.ui-widget-header .ui-state-focus,' .
            '.ui-button:hover,' .
            '.ui-button:focus {' .
            $lc1 .
            $bgTrans .
            '}' . PHP_EOL .


            'table.dataTable thead tr th {' .
            $tc1 .
            $bgc1 .
            '}' . PHP_EOL .

            'tr.even {' .
            $tc1 .
            $bgc1 .
            '}' . PHP_EOL .

            'tr.odd {' .
            $tc1 .
            $bgc2 .
            '}' . PHP_EOL .

            'table.fixedHeader-floating {' .
            $tc1 .
            $bgc2 .
            '}' . PHP_EOL .

            '.greenhighlight,' .
            '.greenhighlight  table td {' .
            $tc1 .
            $bgc2 .
            '}' . PHP_EOL .

            '#formSaveTip {' .
            $bgc2 .
            '}' . PHP_EOL .


            '#group_table div {' .
            $bgc2 .
            $tc1 .
            '}' . PHP_EOL .

            '#group_table div div span {' .
            $tc1 .
            '}' . PHP_EOL .

            'h2.pending-title {' .
            $tc1 .
            $bgc2 .
            '}' . PHP_EOL .

            'div.request-container {' .
            $tc1 .
            $bgc2 .
            '}' . PHP_EOL .

            'div.graph-title {' .
            $tc1 .
            $bgc2 .
            '}' . PHP_EOL .


            'form table {' .
            $tc1 .
            $bgTrans .
            '}' . PHP_EOL .

            '.cc_info {' .
            $tc1 .
            '}' . PHP_EOL .

            'textarea.x-form-field, input.x-form-field, select.x-form-field {' .
            $bgTrans .
            $tc1 .
            '}' . PHP_EOL .

            'textarea {' .
            $bgTrans .
            $tc1 .
            '}' . PHP_EOL .


            '.modal-dialog {' .
            $tc1 .
            $bgc2 .
            '}' . PHP_EOL .

            '#enableListBtn {' .
            $lc1 .
            $bgc1 .
            $bgc1 .
            '}' . PHP_EOL .

            '.cc_label {' .
            $bgTrans .
            '}' . PHP_EOL .

            '#user_list_table tr {' .
            $tc1 .
            $bgTrans .
            '}' . PHP_EOL .

            '#mysql_dashboard td {' .
            $tc1 .
            $bgTrans .
            '}' . PHP_EOL .

            '#reload_dropdown {' .
            $tc1 .
            $bgTrans .
            '}' . PHP_EOL .


            'select {' .
            $tc1 .
            $bgTrans .
            '}' . PHP_EOL .

            '#pubContent table {' .
            $lc1 .
            $bgTrans .
            '}' . PHP_EOL .

            '.btn-defaultrc {' .
            $lc1 .
            $bgc2 .
            '}' . PHP_EOL .


            '#surveyEmailFieldEnableDialog div div {' .
            $tc1 .
            $bgTrans .
            '}' . PHP_EOL .

            'a.help:link, a.help:visited, a.help:active, a.help:hover {' .
            $lc1 .
            $bgc2 .
            '}' . PHP_EOL .

            'a.help:active, a.help:hover {' .
            '  border-color:' . $this->background_tertiary_color . ' !important;' .
            '}' . PHP_EOL .

            'div.chklist {' .
            $tc1 .
            $bgTrans .
            '}' . PHP_EOL .

            'img[src*="checkbox_cross.png"],' .
            'img[src*="checkbox_checked.png"] {' .
            '  display:none;' .
            '}' . PHP_EOL .

            'img[src*="qrcode.png"],' .
            'img[src*="progress_circle.gif"]' .
            '{' .
            '  background-color:' . $this->white  . ';' .
            '}' . PHP_EOL .

            'li.d-none a {' .
            $lc1 .
            '}' . PHP_EOL .

            'div[style*="background-color:#f5f5f5;"],' .
            'div[style*="background-color:#FAFAFA;"], '.
            'div[style*="background-color:#fafafa;"] '.
            '{' .
            $tc1 .
            $bgTrans .
            '}' . PHP_EOL .

            'div[style*="color:#444;"] {' .
            $tc1 .
            '}' . PHP_EOL .
            'h4[style*="color:#666;"] {' .
            $tc1 .
            '}' . PHP_EOL .

            'a[style*="color:#000;"] {' .
            $lc1 .
            '}' . PHP_EOL .

            'p[style*="color:#777;"], ' .
            'span[style*="color:#555;"], ' .
            'span[style*="color:#000;"] {' .
            $tc1 .
            '}' . PHP_EOL .

            'input[style*="background-color: rgb(255, 255, 255)"] {' .
            $bgTrans .
            '}' . PHP_EOL .

            'th[style*="background-color:#ddd;"],' .
            'td[style*="background-color:#f5f5f5;"]' .
            '{' .
            $tc1 .
            $bgc2 .
            '}' . PHP_EOL .


            'td[style*="background-color: rgb(245, 245, 245)"],' .
            'td[style*="background: rgb(240, 240, 240);"],' .
            'td[style*="background-color:#eee"] {' .
            $tc1 .
            $bgTrans .
            '}' . PHP_EOL .

            'td[style*="color:#333;"] {' .
            $tc1 .
            '}' . PHP_EOL .

            'div[style*="background-color:#EFF6E8;"],' .
            'div[style*="background-color:#eee;"],' .
            'div[style*="background-color:#ddd;"]' .
            '{' .
            $tc1 .
            $bgTrans .
            '}' . PHP_EOL .

            'div[style*="color:#800000;"]' .
            '{' .
            $tc1 .
            '}' . PHP_EOL .


            'textarea[style*="background: rgb(247, 235, 235)"] {' .
            $tc1 .
            $bgTrans .
            '}' . PHP_EOL .

            'td.frmedit, div.frmedit, td.frmedit_row {' .
            $tc1 .
            $bgTrans .
            $bgc2 .
            '}' . PHP_EOL .

            'table.form_border {' .
            '  border-color: transparent  !important;' .
            '}' . PHP_EOL .

            'input.btn2 {' .
            $lc1 .
            '}' . PHP_EOL .

            '#element_enum_clone {' .
            $tc1 .
            '}' . PHP_EOL .

            '.mc_raw_val_fix b {' .
            '  color:' . $this->warning_color . ';' .
            '}' . PHP_EOL .

            '.dropdown-menu {' .
            $bgc2 .
            '  color:' . $this->white  . ';' .
            '}' . PHP_EOL .

            '#dashboard-config {' .
            $bgTrans .
            $tc1 .
            '}' . PHP_EOL .

            '#choose_select_forms_events_div_sub {' .
            $bgc2 .
            $tc1 .
            '}' . PHP_EOL .

            'table.sched_table {' .
            $bgTrans .
            '}' . PHP_EOL .

            '.alert-success {' .
            $bgTrans .
            '}' . PHP_EOL .

            '#emailPartForm fieldset {' .
            $bgTrans .
            '}' . PHP_EOL .

            '.select2-container--default .select2-selection--single .select2-selection__rendered {' .
            $bgTrans .
            $tc1 .
            '}' . PHP_EOL .

            '.author-institution {' .
            $tc1 .
            '}' . PHP_EOL .

            'nav.navbar {' .
            '  background-color:' . $this->white  . ';' .
            '  border-color:' . $this->background_primary_color . ';' .
            '  color:' . $this->black . ';' .
            '}' . PHP_EOL .

            'nav.navbar a.nav-link{' .
            '  color:' . $this->black . ' !important;' .
            '}' . PHP_EOL .


            '</style>' . PHP_EOL;

        $this->css = str_replace('  ', ' ', $css);
    }

    private function outputCSS()
    {
        echo $this->css;
    }

    /**
     * Increases or decreases the brightness of a color by a percentage of the current brightness.
     *
     * @param string $hexCode Supported formats: `#FFF`, `#FFFFFF`, `FFF`, `FFFFFF`
     * @param float $adjustPercent A number between -1 and 1. E.g. 0.3 = 30% lighter; -0.4 = 40% darker.
     *
     * @return  string
     */
    private function adjustBrightness($hexCode, $adjustPercent)
    {
        $hexCode = ltrim($hexCode, '#');

        if (strlen($hexCode) == 3) {
            $hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
        }

        $hexCode = array_map('hexdec', str_split($hexCode, 2));

        foreach ($hexCode as & $color) {
            $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
            $adjustAmount = ceil($adjustableLimit * $adjustPercent);

            $color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
        }

        return '#' . implode($hexCode);
    }

    /**
     * Check if a code entered by user is hexidecimal
     *
     * @param string $text
     * @param float $adjustPercent A number between -1 and 1. E.g. 0.3 = 30% lighter; -0.4 = 40% darker.
     *
     * @return  string
     */
    private function isHex($text) {
        $hexCode = ltrim($text, '#');
        $isHex = true;
        if (!strlen($hexCode) === 3 || !strlen($hexCode) === 6) {
            $isHex = false;
        } else if (!ctype_xdigit($hexCode)) {
            $isHex = false;
        }
        return $isHex;
    }

    private function createElements()
    {
        // todo think about the order of the elements.  Should they be specified or not
        // If Not ordered than merging arrays is OK.  If order is important that a master array will be needed.
        // There is also the issue that "!important will have to be thrown on every settings which is not good.
        // $elements = ['body', '.menubox', 'a', 'a:visited', 'a:link'];


        $background_transparent_elements = [
            '#pagecontainer',
            '#south'
        ];
        $background_primary_elements = [
            'body',
            '.menubox'
        ];
        $color_primary_elements = [
            'body'
        ];
        $color_secondary_elements = [
            '.menuboxsub'
        ];
        $link_primary_elements = [
            'a',
            'a:visited',
            'a:link',
            'a.aGrid:visited',
            'a.aGrid:link',
            'a[style*="color:#800000"]',
            'a[style*="color:#A00000"]',
            '#menuLnkChooseOtherRec'
        ];
        $elements = array_unique(
            array_merge(
                $background_transparent_elements,
                $background_primary_elements,
                $color_primary_elements,
                $color_secondary_elements,
                $link_primary_elements
            )
        );
        $el = [];
        foreach ($elements as $key => $element) {
            if (in_array($element, $background_primary_elements)) {
                $el[$element]['background-color'] = $this->background_primary_color;
            }
            if (in_array($element, $background_transparent_elements)) {
                $el[$element]['background-color'] = 'transparent';
            }
            if (in_array($element, $color_primary_elements)) {
                $el[$element]['color'] = $this->text_primary_color;
            }
            if (in_array($element, $color_secondary_elements)) {
                $el[$element]['color'] = $this->text_secondary_color;
            }
            if (in_array($element, $link_primary_elements)) {
                $el[$element]['color'] = $this->link_primary_color;
            }
        }
        $css = '<style>' . PHP_EOL;
        foreach ($el as $element => $attributes) {
            $css .= $element . '{' . PHP_EOL;
            if (count($attributes) > 0) {
                foreach ($attributes as $attribute => $value) {
                    $css .= $attribute . ':' . $value . ';' . PHP_EOL;
                }
            }
            $css .= '}' . PHP_EOL;
        }
        $css = '</style>' . PHP_EOL;

        return $css;

    }

}
