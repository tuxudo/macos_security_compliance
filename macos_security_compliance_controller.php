<?php
/**
 * macos_security_compliance module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Macos_security_compliance_controller extends Module_controller
{
    /*** Protect methods with auth! ****/
    public function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__);
    }

    /**
    * Default method
    *
    * @author AvB
    **/
    public function index()
    {
        echo "You've loaded the macos_security_compliance module!";
    }

    /**
     * Get data for scroll widget
     *
     * @return void
     * @author tuxudo
     **/
    public function get_scroll_widget($column)
    {
        // Remove non-column name characters
        $column = preg_replace("/[^A-Za-z0-9_\-]]/", '', $column);

        $sql = "SELECT COUNT(CASE WHEN ".$column." <> '' AND ".$column." IS NOT NULL THEN 1 END) AS count, ".$column." 
                FROM macos_security_compliance
                LEFT JOIN reportdata USING (serial_number)
                ".get_machine_group_filter()."
                AND ".$column." <> '' AND ".$column." IS NOT NULL 
                GROUP BY ".$column."
                ORDER BY count DESC";

        $queryobj = new Macos_security_compliance_model;
        jsonView($queryobj->query($sql));
    }

    /**
     * Get compliance data
     *
     *
     **/
    public function get_compliance_stats()
    {
        $sql = "SELECT COUNT(CASE WHEN `compliant` = 100 THEN 1 END) AS success,
                        COUNT(CASE WHEN `compliant` BETWEEN 90 AND 99 THEN 1 END) AS info,
                        COUNT(CASE WHEN `compliant` BETWEEN 75 AND 89 THEN 1 END) AS warning,
                        COUNT(CASE WHEN `compliant` BETWEEN 0 AND 74 THEN 1 END) AS danger
                        FROM macos_security_compliance
                        LEFT JOIN reportdata USING(serial_number)
                        ".get_machine_group_filter();

        $obj = new View();
        $pm = new Macos_security_compliance_model;
        $obj->view('json', array('msg' => $pm->query($sql)));
    }

    /**
    * Retrieve data in json format
    *
    * @return void
    * @author tuxudo
    **/
    public function get_tab_data($serial_number = '')
    {
        // Remove non-serial number characters
        $serial_number = preg_replace("/[^A-Za-z0-9_\-]]/", '', $serial_number);

        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $sql = "SELECT `last_compliance_check`, `baseline`, `compliant`, `fails`, `passes`, `exempt`, `total`, `compliance_json`
                    FROM macos_security_compliance 
                    LEFT JOIN reportdata USING (serial_number)
                    ".get_machine_group_filter()."
                    AND serial_number = '$serial_number'";
        
        $queryobj = new Macos_security_compliance_model();
        $macos_security_compliance_tab = $queryobj->query($sql);
        $obj->view('json', array('msg' => current(array('msg' => $macos_security_compliance_tab)))); 
    }
} // END class Macos_security_compliance_controller