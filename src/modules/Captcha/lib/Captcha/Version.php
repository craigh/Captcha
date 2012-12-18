<?php
/**
 * Copyright Craig Heydenburg 2010 - Captcha
 *
 * Captcha
 *
 * @license MIT
 */

/**
 * Class to control Version information
 */
class Captcha_Version extends Zikula_AbstractVersion
{
    public function getMetaData()
    {
        $meta = array();
        $meta['displayname']    = $this->__('Captcha');
        $meta['url']            = $this->__(/*!used in URL - nospaces, no special chars, lcase*/'captcha');
        $meta['description']    = $this->__('Captcha hook module');
        $meta['version']        = '1.0.0';

        $meta['securityschema'] = array(
            'Captcha::'      => '::');
        $meta['core_min']       = '1.3.0'; // requires minimum 1.3.x only
        $meta['core_max'] = '1.3.99'; // doesn't work with versions later than 1.3.99
        
        $meta['capabilities'] = array();
        $meta['capabilities'][HookUtil::PROVIDER_CAPABLE] = array('enabled' => true);

        return $meta;
    }

    protected function setupHookBundles()
    {
        $bundle = new Zikula_HookManager_ProviderBundle($this->name, 'provider.captcha.ui_hooks.service', 'ui_hooks', $this->__('Captcha Service Display'));
        $bundle->addServiceHandler('form_edit', 'Captcha_HookHandlers', 'uiEdit', 'captcha.service');
        $bundle->addServiceHandler('validate_edit', 'Captcha_HookHandlers', 'validateEdit', 'captcha.service');
        $this->registerHookProviderBundle($bundle);
    }
} // end class def
