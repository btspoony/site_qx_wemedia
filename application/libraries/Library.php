<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Library
 *
 * @author jasper
 */
class Library {

    /**
     * @var CI_Loader
     */
    var $load;
    /**
     * @var CI_Controller
     */
    var $CI;
    /**
     * Constructor
     *
     * @access public
     */
    function __construct() {
        $this->CI = & get_instance();
        $this->load = $this->CI->load;
        log_message('debug', "Library Class Initialized");
    }

    /**
     * __get
     *
     * Allows models to access CI's loaded classes using the same
     * syntax as controllers.
     *
     * @param	string
     * @access private
     */
    function __get($key) {
        return $this->CI->$key;
    }

}

?>
