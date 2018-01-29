<?php

if (!defined('__IN_SYMPHONY__')) die('<h2>Symphony Error</h2><p>You cannot directly access this file</p>');

class fieldincremental_number extends Field
{

    /*-------------------------------------------------------------------------
        DEFINITION
    -------------------------------------------------------------------------*/

    /**
     *
     * construct
     *
     * @since version 1.0.0
     */

    function __construct()
    {
        // call the parent constructor
        parent::__construct();

        // set the name of the field
        $this->_name = 'Incremental Number';

        // force "required"-setting
        $this->_required = true;
        $this->set('required', 'yes');
    }

    /**
     *
     * create table
     *
     * @since version 1.0.0
     */

    public function createTable()
    {
        return Symphony::Database()->query(
            "CREATE TABLE IF NOT EXISTS `tbl_entries_data_".$this->get('id')."` (
                `id` int(11) unsigned NOT NULL auto_increment,
                `entry_id` int(11) unsigned NOT NULL,
                `value` int(11) default NULL,
                PRIMARY KEY  (`id`),
                KEY `entry_id` (`entry_id`),
                KEY `value` (`value`)
            ) TYPE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
        );
    }

    function isSortable()
    {
        return true;
    }

    function canFilter()
    {
        return true;
    }

    function allowDatasourceParamOutput()
    {
        return true;
    }

    function canPrePopulate()
    {
        return true;
    }

    function canToggle()
    {
        return false;
    }

    /*-------------------------------------------------------------------------
        SETTINGS
    -------------------------------------------------------------------------*/

    /**
     *
     * display settings panel
     *
     * @since version 1.0.0
     */

    public function displaySettingsPanel(XMLElement &$wrapper, $errors = NULL)
    {
        parent::displaySettingsPanel($wrapper, $errors);

        // input "start number"

        $label = Widget::Label(__('Start Number <i>(Must be a natural number)</i>'));
        $label->appendChild(WIDGET::Input('fields['.$this->get('sortorder').'][start_number]', $this->get('start_number')));
        if (isset($errors['start_number'])) {
            $wrapper->appendChild(Widget::Error($label, $errors['start_number']));
        } else {
            $wrapper->appendChild($label);
        }

        $this->appendShowColumnCheckbox($wrapper);
    }

    /**
     *
     * check fields
     *
     * validate the fields settings and return errors if wrong or missing input is detected
     *
     * @since version 1.2.0
     */

    public function checkFields(Array &$errors, $checkForDuplicates = true)
    {
        // check if general field data is missing (label, handle)

        $parent = parent::checkFields($errors, $checkForDuplicates);
        if ($parent != self::__OK__) return $parent;

        // build array of input data that needs to be validated by the extension

        $check = array();
        $check['start_number'] = $this->get('start_number');

        // validate start number (must be a natural number)

        if(!preg_match('/^[0-9]+$/', $check['start_number'])) {
            $errors['start_number'] = __('Start Number must be a natural number. e.g. <code>0</code> or <code>1</code>');
        }

        return (!empty($errors) ? self::__ERROR__ : self::__OK__);
    }

    /**
     *
     * commit field settings
     *
     * @since version 1.0.0
     */

    public function commit()
    {
        if( !parent::commit() ) return false;

        $id = $this->get('id');
        if( $id === false ) return false;

        $fields = array();
        $fields['field_id'] = $id;
        $fields['start_number'] = $this->get('start_number');

        Symphony::Database()->query("DELETE FROM `tbl_fields_".$this->handle()."` WHERE `field_id` = '$id' LIMIT 1");

        return Symphony::Database()->insert($fields, 'tbl_fields_'.$this->handle());
    }

    /*-------------------------------------------------------------------------
        UI
    -------------------------------------------------------------------------*/

    /**
     *
     * prepare table value
     *
     * @since version 1.0.0
     */

    public function displayPublishPanel(XMLElement &$wrapper, $data = NULL, $flagWithError = NULL, $fieldnamePrefix = NULL, $fieldnamePostfix = NULL, $entry_id = NULL)
    {
        $value = $data['value'];
        $label = Widget::Label($this->get('label'));

        $label->appendChild(
            Widget::Input(
                'fields'.$fieldnamePrefix.'['.$this->get('element_name').']'.$fieldnamePostfix,
                (string) (strlen($value) != 0 ? $value : $this->getNewNumber()),
                'text',
                array('readonly' => 'readonly')
            )
        );

        if( $flagWithError != NULL ) {
            $wrapper->appendChild(Widget::Error($label, $flagWithError));
        } else {
            $wrapper->appendChild($label);
        }
    }

    /**
     *
     * display datasource filter panel
     *
     * @since version 1.0.0
     */

    public function displayDatasourceFilterPanel(XMLElement &$wrapper, $data = NULL, $errors = NULL, $fieldnamePrefix = NULL, $fieldnamePostfix = NULL)
    {
        $wrapper->appendChild(new XMLElement('h4', $this->get('label').' <span>'.$this->Name().'</span>'));
        $label = Widget::Label('Value');
        $label->appendChild(
            Widget::Input(
                'fields[filter]'.($fieldnamePrefix ? '['.$fieldnamePrefix.']' : '').'['.$this->get('id').']'.($fieldnamePostfix ? '['.$fieldnamePostfix.']' : ''),
                ($data ? General::sanitize($data) : NULL))
            );
        $wrapper->appendChild($label);

        $wrapper->appendChild(new XMLElement('p', 'To filter by ranges, add <code>mysql:</code> to the beginning of the filter input. Use <code>value</code> for field name. E.G. <code>mysql: value &gt;= 1.01 AND value &lt;= {$price}</code>', array('class' => 'help')));
    }

    /*-------------------------------------------------------------------------
        INPUT / SAVE FIELD DATA
    -------------------------------------------------------------------------*/

    /**
     *
     * check post field data
     *
     * @since version 1.0.0
     */

    public function checkPostFieldData($data, &$message, $entry_id = NULL)
    {
        $message = NULL;

        if( $this->get('required') == 'yes' && strlen($data) == 0 ){
            $message = 'This is a required field.';
            return self::__MISSING_FIELDS__;
        }

        if( strlen($data) > 0 && !is_numeric($data) ){
            $message = 'Must be a number.';
            return self::__INVALID_FIELDS__;
        }

        return self::__OK__;
    }

    /**
     *
     * process raw field data
     *
     * @since version 1.0.0
     */

    public function processRawFieldData($data, &$status, &$message = NULL, $simulate = false, $entry_id = null)
    {
        if( !$data ) $data = $this->getNewNumber();

        return parent::processRawFieldData($data, $status, $message, $simulate, $entry_id);
    }

    /**
     *
     * get new number
     *
     * @since version 1.0.0
     */

    public function getNewNumber()
    {
        $last_num = Symphony::Database()->fetch("
            SELECT `value`
            FROM `tbl_entries_data_".$this->get('id')."`
            ORDER BY `value` DESC LIMIT 1
        ");

        return (int) (!empty($last_num)) ? $last_num[0]['value'] + 1 : $this->get('start_number');
    }

    /*-------------------------------------------------------------------------
        DATA SOURCE OUTPUT
    -------------------------------------------------------------------------*/

    /**
     *
     * build ds retrieval sql
     *
     * @since version 1.0.0
     */

    function buildDSRetrievalSQL($data, &$joins, &$where, $andOperation = false)
    {
        if( preg_match('/^mysql:/i', $data[0]) ){

            $field_id = $this->get('id');

            $expression = str_replace(array('mysql:', 'value'), array('', " `t$field_id`.`value` "), $data[0]);

            $joins .= " LEFT JOIN `tbl_entries_data_$field_id` AS `t$field_id` ON (`e`.`id` = `t$field_id`.entry_id) ";
            $where .= " AND $expression ";
        }

        else parent::buildDSRetrievalSQL($data, $joins, $where, $andOperation);

        return true;
    }
}
