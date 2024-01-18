<?php

use CFPropertyList\CFPropertyList;

class Macos_security_compliance_model extends \Model
{
    public function __construct($serial = '')
    {
        parent::__construct('id', 'macos_security_compliance'); // Primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['last_compliance_check'] = null;
        $this->rs['baseline'] = null;
        $this->rs['compliant'] = null;
        $this->rs['fails'] = null;
        $this->rs['passes'] = null;
        $this->rs['exempt'] = null;
        $this->rs['total'] = null;
        $this->rs['compliance_json'] = null;
    }

    // ------------------------------------------------------------------------
    /**
     * Process data sent by postflight
     *
     * @param string data
     *
     **/
    public function process($data)
    {
        // If data is empty, echo out error
        if (! $data) {
            echo ("Error Processing macos_security_compliance module: No data found");
        } else { 
            
            // Delete previous entries
            $this->deleteWhere('serial_number=?', $this->serial_number);

            // Process incoming macos_security_compliance.plist
            $parser = new CFPropertyList();
            $parser->parse($data, CFPropertyList::FORMAT_XML);
            $plist = $parser->toArray();

            // Process all of the keys in the plist
            foreach (array('last_compliance_check', 'baseline', 'compliant', 'fails', 'passes', 'exempt', 'total', 'compliance_json') as $item) {
                // If key does not exist in $plist, null it
                if ( ! array_key_exists($item, $plist) || $plist[$item] == '') {
                    $this->$item = null;
                // Set the db fields to be the same as those in the preference file
                } else {
                    $this->$item = $plist[$item];
                }
            }

            // Save the data, need to be compliant to make Gary happy
            $this->id = '';
            $this->save(); 
        }
    }
}