<?php
/**
 * Copyright Craig Heydenburg 2010 - Captcha
 *
 * Captcha
 *
 * @license MIT
 */

/**
 * Class to control Admin interface
 */
class Captcha_Controller_Admin extends Zikula_Controller
{
    /**
     * the main administration function
     * This function is the default function, and is called whenever the
     * module is initiated without defining arguments.
     */
    public function main()
    {
        if (!SecurityUtil::checkPermission('Captcha::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        return $this->modifyconfig();
    }
    /**
     * @desc present administrator options to change module configuration
     * @return      config template
     */
    public function modifyconfig()
    {
        if (!SecurityUtil::checkPermission('Captcha::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }
        return $this->view->fetch('admin/modifyconfig.tpl');
    }
    /**
     * @desc sets module variables as requested by admin
     * @return      status/error ->back to modify config page
     */
    public function updateconfig()
    {
        if (!SecurityUtil::checkPermission('Captcha::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $modvars = array();
        $modvars['privatekey'] = FormUtil::getPassedValue('privatekey', '');
        $modvars['publickey'] = FormUtil::getPassedValue('publickey', '');

        // set the new variables
        $this->setVars($modvars);
    
        // clear the cache
        $this->view->clear_cache();
    
        LogUtil::registerStatus($this->__('Done! Updated the Captcha configuration.'));
        return $this->modifyconfig();
    }
    /**
     * @desc set caching to false for all admin functions
     * @return      null
     */
    public function postInitialize()
    {
        $this->view->setCaching(false);
    }
} // end class def