<?php
/**
 * Copyright Craig Heydenburg 2010 - Captcha
 *
 * Captcha
 *
 * @license MIT
 */

/**
 * Class to control Installer interface
 */
class Captcha_Installer extends Zikula_AbstractInstaller
{
    /**
     * Initializes a new install
     *
     * This function will initialize a new installation.
     * It is accessed via the Zikula Admin interface and should
     * not be called directly.
     *
     * @return  boolean    true/false
     */
    public function install()
    {
        // Set up config variables
        $this->setVar('publickey', '');
        $this->setVar('privatekey', '');
        $this->setVar('exemptAdmin', 0);
        $this->setVar('captchaTheme', 'red');
        HookUtil::registerHookProviderBundles($this->version);

        return true;
    }
    
    /**
     * Upgrades an old install
     *
     * This function is used to upgrade an old version
     * of the module.  It is accessed via the Zikula
     * Admin interface and should not be called directly.
     *
     * @param   string    $oldversion Version we're upgrading
     * @return  boolean   true/false
     */
    public function upgrade($oldversion)
    {
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Captcha::', '::', ACCESS_ADMIN), LogUtil::getErrorMsgPermission());
    
        switch ($oldversion) {
            case '1.0.0':
                //future development
        }
    
        return true;
    }
    
    /**
     * removes an install
     *
     * This function removes the module from your
     * Zikula install and should be accessed via
     * the Zikula Admin interface
     *
     * @return  boolean    true/false
     */
    public function uninstall()
    {
        $result = $this->delVars();
        HookUtil::unregisterHookProviderBundles($this->version);

        return $result;
    }
} // end class def