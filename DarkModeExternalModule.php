<?php

namespace DCC\DarkModeExternalModule;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;
use \REDCap;

/**
 * Class DarkModeExternalModule
 * @package DCC\DarkModeExternalModule
 *
 */
class DarkModeExternalModule extends AbstractExternalModule
{
    /**
     * @var string $user_names CSV of user names that will see the custom css
     */
    private $user_names;

    /**
     * @var string $css css created by users selections and outputed.
     */
    private $css;

    /**
     * @var string $background_primary_color Primary background color.
     * May be used to calculate other background colors
     */
    private $background_primary_color;

    /**
     * @var string $background_secondary_color Secondary background color
     */
    private $background_secondary_color;

    /**
     * @var string $background_tertiary_color Tertiary background color
     */
    private $background_tertiary_color;

    /**
     * @var string $text_primary_color Primary text color: Main body
     */
    private $text_primary_color;

    /**
     * @var string $text_secondary_color Secondary text color
     */
    private $text_secondary_color;

    /**
     * @var string $text_tertiary_color Tertiary text color
     */
    private $text_tertiary_color;

    /**
     * @var string $link_primary_color All links and some buttons are set to this color.
     */
    private $link_primary_color;

    /**
     * @var string $white HTML color code for white
     */
    private $white;

    /**
     * @var string $black HTML color code for black
     */
    private $black;

    /**
     * @var string $primary_color // todo
     */
    private $primary_color;

    /**
     * @var string $success_color based on Bootstrap success color/idea
     */
    private $success_color;

    /**
     * @var string $warning_color based on Bootstrap warning color/idea
     */
    private $warning_color;

    /**
     * @var string $danger_color desc based on Bootstrap Danger color/idea
     */
    private $danger_color;
    /**
     * @var boolean $can_use Does a user have rights to use the css?
     */
    private $can_use;

    /**
     * @var string $background_brightness values: same, brighter, darker, specify
     */
    private $background_brightness;

    /**
     * @var string $background_brightness_percent
     * Percent that the secondary and tertiary background change in brightness.
     * Nullable
     */
    private $background_brightness_percent;


    /**
     * @var string $debug_info all of debug info you would like to know
     * use '\n' to create new lines
     */
    private $debug_info;

    /**
     *
     */
    function __construct()
    {
        parent::__construct();

        $this->check_users();

        if ($this->can_use) {
            $this->debug_info = "";
            $this->set_values();
            $this->set_colors();
            $this->adjust_background_colors();
            $this->adjust_text_colors();
            $this->create_css();
            $this->console_log();
        }
    }


    /**
     * Add the CSS to the top of every page.
     */
    function redcap_every_page_top($project_id, $record, $instrument)
    {
        if ($this->can_use) {
            $this->output_css();
        }
    }

    /**
     *
     * Set the value for can_use
     * allow for all users if no users are specified.
     */
    private function check_users()
    {
        $this->can_use = false;
        $this->user_names = $this->clean_values(AbstractExternalModule::getSystemSetting('user_names'));
        if (!$this->user_names) {
            $this->can_use = true;
        }
        $allowed_users = explode(",", $this->user_names);
        foreach ($allowed_users as $user) {
            if (strtoupper(trim($user)) === strtoupper(USERID)) {
                $this->can_use = true;
            }
        }
    }


    /**
     * @param $value
     * @return string string ready for output back to browser
     */
    private function clean_values($value)
    {
        $cleaned = trim(strip_tags($value));
        $cleaned = str_replace('"', "", $cleaned);
        $cleaned = str_replace('"', "", $cleaned);
        return $cleaned;
    }

    /**
     * get the user inputted settings
     */
    private function set_values()
    {

        /** Primary background color */
        $this->background_primary_color = $this->clean_values(
            AbstractExternalModule::getSystemSetting(
                'background_primary_color'
            ));

        /** background brightness */
        $this->background_brightness = $this->clean_values(
            AbstractExternalModule::getSystemSetting(
                'background_brightness'
            ));

        /** background BRIGHTNESS PERCENT */
        $this->background_brightness_percent = intval($this->clean_values(
                AbstractExternalModule::getSystemSetting(
                    'background_brightness_percent'
                ))) / 100;


        if ($this->background_brightness === 'specify') {
            /** Secondary background color */
            $this->background_secondary_color = $this->clean_values(
                AbstractExternalModule::getSystemSetting(
                    'background_secondary_color'
                ));

            /** Tertiary background color */
            $this->background_tertiary_color = $this->clean_values(
                AbstractExternalModule::getSystemSetting(
                    'background_tertiary_color'
                ));
        } else {
            $this->background_secondary_color = null;
            $this->background_tertiary_color = null;
        }

        /** Primary text color */
        $this->text_primary_color = $this->clean_values(
            AbstractExternalModule::getSystemSetting(
                'text_primary_color'
            ));

        /** Link color */
        $this->link_primary_color = $this->clean_values(
            AbstractExternalModule::getSystemSetting(
                'link_color'
            ));

        /** Primary color */
        $this->primary_color = $this->clean_values(
            AbstractExternalModule::getSystemSetting(
                'primary_color'
            ));

        /** Success color */
        $this->success_color = $this->clean_values(
            AbstractExternalModule::getSystemSetting(
                'success_color'
            ));

        /** Warning color */
        $this->warning_color = $this->clean_values(
            AbstractExternalModule::getSystemSetting(
                'warning_color'
            ));

        /** Danger color */
        $this->danger_color = $this->clean_values(
            AbstractExternalModule::getSystemSetting(
                'danger_color'
            ));


        $this->debug_info .= 'User Background primary: ' . $this->background_primary_color . '\n';
        $this->debug_info .= 'User Background secondary: ' . $this->background_secondary_color . '\n';
        $this->debug_info .= 'User Background tertiary: ' . $this->background_tertiary_color . '\n';
        $this->debug_info .= 'User Background brightness: ' . $this->background_brightness . '\n';
    }

    /**
     * sets the default values for basic colors
     */
    private function set_colors()
    {

        $this->white = '#FFF';
        $this->black = '#000';

        if (!$this->primary_color) {
            $this->primary_color = '#2e6da4';
        }
        if (!$this->success_color) {
            $this->success_color = '#28a745';
        }
        if (!$this->warning_color) {
            $this->warning_color = '#ffc107';
        }

        if (!$this->danger_color) {
            $this->danger_color = '#dc3545';
        }
    }

    /** @noinspection CssInvalidHtmlTagReference */
    /**
     * prepare css for output
     * When left blank, element values will NOT be overwritten leaving the default REDCap css un-affected.
     * Shorthand codes
     * bgc = Background Color
     * tc = Text Color
     * lc = Link Color
     */
    private function create_css()
    {

        if ($this->background_primary_color) {
            $bg_trans = '  background-color:transparent !important;';
            $bgc1 = '  background-color:' . $this->background_primary_color . ' !important;';
            $bgc2 = '  background-color:' . $this->background_secondary_color . ' !important;';
            $bgc3 = '  background-color:' . $this->background_tertiary_color . ' !important;';
            $bc1 = '  border-color:' . $this->background_primary_color . ' !important;';
            $bc2 = '  border-color:' . $this->background_secondary_color . ' !important;';
            $bc3 = '  border-color:' . $this->background_tertiary_color . ' !important;';
            $bc_trans = '  border-color:transparent !important;';
        } else {
            $bg_trans = '';
            $bgc1 = '';
            $bgc2 = '';
            $bgc3 = '';
            $bc1 = '';
            $bc2 = '';
            $bc3 = '';
            $bc_trans = '';
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

        $css = '<style>' .
            'body{' .
            $tc1 .
            $bgc1 .
            '}' . PHP_EOL .

            '.menubox {' .
            $bgc2 .
            '}' . PHP_EOL .

            'A, ' .
            'A:visited,' .
            'A:link {' .
            $lc1 .
            '}' . PHP_EOL .

            '#sub-nav li a, ' .
            '#sub-nav li a:visited,' .
            '#sub-nav li a:link,'.
            '#sub-nav li,'.
            ' {' .
            'background:none !important;' .
            '}' . PHP_EOL .

            '#sub-nav li a,'.
            '#sub-nav li a:visited,'.
            '.extra-nav li a,'.
            '.extra-nav li a:visited' .
            ' {' .
            'background:none !important;' .
            $bgc2 .
            '}' . PHP_EOL .

            '#sub-nav li.active a,'.
            '.extra-nav li.active a' .
            ' {' .
            'background:none !important;' .
            $bgc3 .
            '}' . PHP_EOL .

            '#sub-nav li.active a:hover,'.
            '#sub-nav li a:hover,'.
            '.extra-nav li a:hover,'.
            '.extra-nav li.active a:hover' .
            ' {' .
            'background:none !important;' .
            $bgc3 .
            '}' . PHP_EOL .





            'a.aGrid:visited,' .
            ' a.aGrid:link {' .
            $lc1 .
            '}' . PHP_EOL .

            'a[style*="color:#800000"]{' .
            $lc1 .
            '}' . PHP_EOL .

            '#menuLnkChooseOtherRec {' .
            $lc1 . '}' . PHP_EOL .

            '.x-panel-header {' .
            $bgc2 .
            $bc2 .
            $tc2 .
            '}' . PHP_EOL .

            '#west .fas,' .
            '#west .far,' .
            '#west .fa {' .
            $tc2 .
            '}' . PHP_EOL .

            '#west {' .
            $bgc2 .
            $bc3 .
            '}' . PHP_EOL .

            '#south {' .
            $bg_trans .
            $bc_trans .
            '}' . PHP_EOL .

            '#pagecontainer {' .
            $bg_trans .
            '}' . PHP_EOL .

            '#control_center_menu {' .
            $bg_trans .
            $tc2 .
            $bc2 .
            '}' . PHP_EOL .

            '#control_center_menu .fas,' .
            '#control_center_menu .far,' .
            '#control_center_menu .fa  {' .
            'color: inherit;' .
            '}' . PHP_EOL .


            '.cc_menu_divider {' .
            $bgc2 .
            $bc_trans .
            '}' . PHP_EOL .


            '#center {' .
            $bg_trans .
            '}' . PHP_EOL .

            '#project-menu-logo {' .
            '  background-color:' . $this->white . ';' .
            $bc1 .
            '}' . PHP_EOL .

            '#senditbox {' .
            $bg_trans .
            '}' . PHP_EOL .

            '#subheader { background-image:none;}' . PHP_EOL .

            '.projhdr {' .
            $bg_trans .
            $tc2 .
            $bc1 .
            '}' . PHP_EOL .

            '.yellow {' .
            $bg_trans .
            $tc1 .
            $bc1 .
            '}' . PHP_EOL .

            '.header {' .
            $bg_trans .
            $tc2 .
            $bc1 .
            '}' . PHP_EOL .

            '.well {' .
            $bgc2 .
            $bc2 .
            '}' . PHP_EOL .

            '.table {' .
            $tc2 .
            '}' . PHP_EOL .

            '.external-modules-configure-button,' .
            '.external-modules-disable-button,' .
            '.external-modules-usage-button {' .
            $tc2 .
            $bgc2 .
            $bc3 .
            '}' . PHP_EOL .

            '.external-modules-input-element {' .
            $tc1 .
            $bgc2 .
            $bc3 .
            '}' . PHP_EOL .

            '.labelrc,' .
            '.labelmatrix,' .
            '.data,' .
            '.data2,' .
            '.data_matrix {' .
            $bg_trans .
            '  border-color: transparent;' .
            $tc1 .
            ';}' . PHP_EOL .

            '.flexigrid div.mDiv,' .
            '.flexigrid div.hDiv,' .
            '.flexigrid div.bDiv {' .
            $bg_trans .
            '}' . PHP_EOL .

            '.flexigrid {' .
            $tc1 .
            '}' . PHP_EOL .

            '.flexigrid div.mDiv div.ftitle {' .
            $tc1 .
            '}' . PHP_EOL .

            '.myprojstripe {' .
            $bg_trans .
            '  border-color: transparent;' .
            '}' . PHP_EOL .

            '#table-proj_table tr:not(.nohover):hover td,' .
            '#table-proj_table tr:not(.nohover):hover td.sorted,' .
            '#table-proj_table tr:not(.nohover).trOver td.sorted,' .
            '#table-proj_table tr:not(.nohover).trOver td {' .
            $bgc2 .
            '}' . PHP_EOL .

            '#userProfileTable td {' .
            $bg_trans .
            '}' . PHP_EOL .


            '#export_choices_table td[style*="background: rgb(238, 238, 238)"] {' .
            $bgc2 .
            '}' . PHP_EOL .

            '#exportFormatForm fieldset[style*="background-color:#f9f9f9;"] {' .
            $bgc2 .
            '}' . PHP_EOL .


            '.flexigrid tr.erow td {' .
            $bg_trans .
            '}' . PHP_EOL .

            '.flexigrid div.bDiv tr:hover td,' .
            '.flexigrid div.bDiv tr:hover td.sorted,' .
            '.flexigrid div.bDiv tr.trOver td.sorted,' .
            '.flexigrid div.bDiv tr.trOver td { ' .
            $bg_trans .
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
            $bgc2 .
            $lc1 .
            '}' . PHP_EOL .

            'input[type="text"] {' .
            $bg_trans .
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
            $bg_trans .
            $bc_trans .
            $tc1 .
            '}' . PHP_EOL .

            'textarea.x-form-field,' .
            '.x-form-field {' .
            $bg_trans .
            $tc1 .
            '}' . PHP_EOL .

            '#div_var_name {' .
            $bg_trans .
            $tc1 .
            '}' . PHP_EOL .

            '#div_add_field2 input,' .
            '#div_add_field2 select,' .
            '#div_add_field2 textarea,' .
            '#addMatrixPopup input,' .
            '#addMatrixPopup select,' .
            '#addMatrixPopup textarea,' .
            '.x-form-textarea {' .
            $bgc1 .
            $tc1 .
            '}' . PHP_EOL .

            '.datagreen {' .
            '  color:' . $this->success_color . ';' .
            $bg_trans .
            '  border-color: transparent;' .
            '  background-image: none;' .
            '}' . PHP_EOL .

            '.datared {' .
            '  color:' . $this->warning_color . ';' .
            $bg_trans .
            '  border-color: transparent;' .
            '}' . PHP_EOL .

            'div.darkgreen {' .
            $tc1 .
            $bg_trans .
            '}' . PHP_EOL .

            'div.green {' .
            $tc1 .
            $bgc2 .
            '}' . PHP_EOL .

            '.context_msg {' .
            $bg_trans .
            '}' . PHP_EOL .


            'div.blue,' .
            '.blue' .
            ' {' .
            $tc1 .
            $bg_trans .
            $bc_trans .
            '}' . PHP_EOL .

            'div.gray {' .
            $tc1 .
            $bgc2 .
            $bc_trans .
            '}' . PHP_EOL .

            'div.red {' .
            '  color:' . $this->warning_color . ';' .
            $bgc2 .
            '  border-color: transparent;' .
            '}' . PHP_EOL .


            'div.redcapAppCtrl {' .
            $tc1 .
            $bgc2 .
            $bc_trans .
            '}' . PHP_EOL .


            '.label_header {' .
            $tc1 .
            $bg_trans .
            '}' . PHP_EOL .

            '.notesp11 {' .
            $tc1 .
            $bgc2 .
            "background-image:none;" .
            '}' . PHP_EOL .

            '#addUsersRolesDiv {' .
            $tc1 .
            $bg_trans .
            '}' . PHP_EOL .

            '#rsd_legend {' .
            $tc1 .
            $bg_trans .
            '}' . PHP_EOL .


            '.ui-dialog .ui-dialog-buttonpane button {' .
            $lc1 .
            $bg_trans .
            '}' . PHP_EOL .


            '.jqbuttonsm, .jqbuttonmed {' .
            $lc1 .
            $bg_trans .
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
            $bg_trans .
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
            '.greenhighlight table td {' .
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
            $bg_trans .
            '}' . PHP_EOL .

            '.cc_info {' .
            $tc1 .
            '}' . PHP_EOL .

            'textarea.x-form-field, input.x-form-field, select.x-form-field {' .
            $bgc2 .
            $tc1 .
            '}' . PHP_EOL .

            'textarea {' .
            $bg_trans .
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
            $bg_trans .
            '}' . PHP_EOL .

            '#user_list_table tr {' .
            $tc1 .
            $bg_trans .
            '}' . PHP_EOL .

            '#mysql_dashboard td {' .
            $tc1 .
            $bg_trans .
            '}' . PHP_EOL .

            '#reload_dropdown {' .
            $tc1 .
            $bg_trans .
            '}' . PHP_EOL .


            'select {' .
            $tc1 .
            $bg_trans .
            '}' . PHP_EOL .

            '#pubContent table {' .
            $lc1 .
            $bg_trans .
            '}' . PHP_EOL .

            '.btn-defaultrc {' .
            $lc1 .
            $bgc2 .
            '}' . PHP_EOL .


            '#surveyEmailFieldEnableDialog div div {' .
            $tc1 .
            $bg_trans .
            '}' . PHP_EOL .

            'a.help:link,' .
            'a.help:visited,' .
            'a.help:active, ' .
            'a.help:hover {' .
            $lc1 .
            $bgc2 .
            '}' . PHP_EOL .

            'a.help:active,' .
            'a.help:hover {' .
            $bc3 .
            '}' . PHP_EOL .

            'div.chklist {' .
            $tc1 .
            $bg_trans .
            '}' . PHP_EOL .

            'img[src*="checkbox_cross.png"],' .
            'img[src*="checkbox_checked.png"] '.
            '{' .
            '  display:none;' .
            '}' . PHP_EOL .

            'img[src*="qrcode.png"],' .
            'img[src*="tick_shield_small.png"],' .
            'img[src*="progress_circle.gif"]' .
            '{' .
            '  background-color:' . $this->white . ';' .
            '}' . PHP_EOL .

            'li.d-none a {' .
            $lc1 .
            '}' . PHP_EOL .

            'div[style*="background-color:#f5f5f5;"],' .
            'div[style*="background-color:#FAFAFA;"], ' .
            'div[style*="background-color:#fafafa;"] ' .
            '{' .
            $tc1 .
            $bg_trans .
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
            'p[style*="color:#000066;"], ' .
            'span[style*="color:#000066;"], ' .
            'span[style*="color:#555;"], ' .
            'span[style*="color:#000;"] {' .
            $tc1 .
            '}' . PHP_EOL .

            'input[style*="background-color: rgb(255, 255, 255)"] {' .
            $bg_trans .
            '}' . PHP_EOL .

            'tr.grp2 {' .
            $bg_trans .
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
            $bg_trans .
            '}' . PHP_EOL .

            'td[style*="color:#333;"] {' .
            $tc1 .
            '}' . PHP_EOL .

            'div[style*="background-color:#EFF6E8;"],' .
            'div[style*="background-color:#F0F0F0;"],' .
            'div[style*="background-color:#eee;"],' .
            'div[style*="background-color:#ddd;"]' .
            '{' .
            $tc1 .
            $bg_trans .
            '}' . PHP_EOL .

            'div[style*="color:#800000;"],' .
            'div[style*="color:#000066;"]' .
            '{' .
            $tc1 .
            '}' . PHP_EOL .


            'textarea[style*="background: rgb(247, 235, 235)"] {' .
            $tc1 .
            $bg_trans .
            '}' . PHP_EOL .

            'td.frmedit, div.frmedit, td.frmedit_row {' .
            $tc1 .
            $bg_trans .
            $bgc2 .
            '}' . PHP_EOL .

            'table.form_border {' .
            '  border-color: transparent !important;' .
            '}' . PHP_EOL .


            '.form_menu_selected {' .
            $bgc2 .
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
            '  color:' . $this->white . ';' .
            '}' . PHP_EOL .

            '#dashboard-config {' .
            $bg_trans .
            $tc1 .
            '}' . PHP_EOL .

            '#choose_select_forms_events_div_sub {' .
            $bgc2 .
            $tc1 .
            '}' . PHP_EOL .

            'table.sched_table {' .
            $bg_trans .
            '}' . PHP_EOL .

            '.alert-success {' .
            $bg_trans .
            '}' . PHP_EOL .

            '#emailPartForm fieldset {' .
            $bg_trans .
            '}' . PHP_EOL .

            'fieldset[style*="color:#eee;"],' .
            'fieldset[style*="color:#FFFFD3;"] {' .
            $bg_trans .
            '}' . PHP_EOL .

            'legend[style*="color:#333;"]' .
            '{' .
            $tc1 .
            '}' . PHP_EOL .



            'fieldset[style*="background-color:#f3f5f5;"], '.
            'fieldset[style*="background-color:#F3F5F5;"] '.
            '{' .
            $bgc1 .
            '}' . PHP_EOL .



            '.select2-container--default .select2-selection--single .select2-selection__rendered {' .
            $bg_trans .
            $tc1 .
            '}' . PHP_EOL .

            '.author-institution {' .
            $tc1 .
            '}' . PHP_EOL .

            'nav.navbar {' .
            '  background-color:' . $this->white . ';' .
            $bc1 .
            '  color:' . $this->black . ';' .
            '}' . PHP_EOL .

            'nav.navbar a.nav-link{' .
            '  color:' . $this->black . ' !important;' .
            '}' . PHP_EOL .

            'table.frmedit_tbl {' .
            '  border: none;' .
            '  border-bottom: 10px solid ' . $this->background_tertiary_color . ' !important;' .
            '}' . PHP_EOL .


            '#record_display_name {' .
            $tc1 .
            '}' . PHP_EOL .


            '.logt {' .
            $bg_trans .
            '}' . PHP_EOL .


            'i.far[style*="color:#000088;"] {' .
            $tc2 .
            '}' . PHP_EOL .


            '</style>' . PHP_EOL;

        $this->css = str_replace('  ', ' ', $css);

        /*
         * More possible List page first followed by css.
         * http://localhost/redcap/redcap_v9.5.2/ExternalModules/manager/control_center.php
        .badge-warning, .badge-info on
        */
    }

    private function output_css()
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
     * @return  string
     */
    private function is_hex($text)
    {
        $hexCode = ltrim($text, '#');
        $isHex = true;
        if (!strlen($hexCode) === 3 || !strlen($hexCode) === 6) {
            $isHex = false;
        } else if (!ctype_xdigit($hexCode)) {
            $isHex = false;
        }
        return $isHex;
    }

    /**
     * set the secondary and tertiary background colors
     * if nothing is specified for the background use 12.5 brightness adjustment
     */
    private function adjust_background_colors()
    {
        if ($this->is_hex($this->background_primary_color) === false) {
            if ($this->background_brightness === "same") {
                $this->background_secondary_color = $this->background_primary_color;
                $this->background_tertiary_color = $this->background_primary_color;
            }
            return;
        }
        $adjust_percent = null;
        if ($this->background_brightness === "same") {
            $adjust_percent = 0;
            $this->debug_info .= 'Adjust Percent: None ' . '\n';

        } else if ($this->background_brightness === "lighter" || $this->background_brightness === "darker") {
            if ($this->background_brightness_percent >= 0 && $this->background_brightness_percent <= 100) {
                $this->debug_info .= 'Adjust Percent: user inputed 0-100 ' . '\n';
                $adjust_percent = $this->background_brightness_percent;
            } else {
                $this->debug_info .= 'Adjust Percent: defaulted to 20% ' . '\n';
                $adjust_percent = 20;
            }
        }

        // if darker than it should be a negative value.  Lighter is a positive value.
        if ($this->background_brightness === "darker") {
            $adjust_percent = -1 * $adjust_percent;
        }

        if (!is_null($adjust_percent)) {
            $this->debug_info .= 'Adjusting brightness\n';
            $this->background_secondary_color = $this->adjustBrightness(
                $this->background_primary_color, $adjust_percent
            );
            $this->background_tertiary_color = $this->adjustBrightness(
                $this->background_primary_color, 1.5 * $adjust_percent
            );
        }
        $this->debug_info .= 'Adjust Percent: ' . $adjust_percent . '\n' .
            'Secondary Background: ' . $this->background_secondary_color . '\n' .
            'Tertiary Background: ' . $this->background_tertiary_color . '\n';
    }

    /**
     * set the secondary and tertiary text colors
     */
    private function adjust_text_colors()
    {
        if ($this->is_hex($this->text_primary_color)) {
            $this->text_secondary_color = $this->adjustBrightness($this->text_primary_color, -0.15);
            $this->text_tertiary_color = $this->adjustBrightness($this->text_primary_color, -0.30);
        } else {
            $this->text_secondary_color = $this->text_primary_color;
            $this->text_tertiary_color = $this->text_primary_color;

        }
    }

    private function console_log()
    {
        echo '<script>console.log("' .
            $this->debug_info .
            '")</script>';
    }
}
