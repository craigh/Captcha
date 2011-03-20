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
        $meta['core_min']       = '1.3.0'; // requires minimum 1.3.0 or later
        //$meta['core_max'] = '1.3.0'; // doesn't work with versions later than x.x.x
        
        $meta['capabilities'] = array();
        $meta['capabilities'][HookUtil::PROVIDER_CAPABLE] = array('enabled' => true);

        return $meta;
    }

    protected function setupHookBundles()
    {
        $bundle = new Zikula_AbstractVersion_HookProviderBundle('modulehook_area.captcha.event', $this->__('Captcha Hook'));
        $bundle->addHook('hookhandler.captcha.ui.edit', 'ui.edit', 'Captcha_HookHandlers', 'ui_edit', 'captcha.service', 10);
        $bundle->addHook('hookhandler.captcha.validate.edit', 'validate.edit', 'Captcha_HookHandlers', 'validate_edit', 'captcha.service', 10);
        $this->registerHookProviderBundle($bundle);
    }
} // end class def