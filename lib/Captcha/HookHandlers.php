<?php
/**
 * Copyright Craig Heydenburg 2010 - Captcha
 *
 * Captcha
 *
 * @license MIT
 */

class Captcha_HookHandlers extends Zikula_Hook_AbstractHandler
{
    /**
     * Name of the directory containing the library
     * contains version number - edit on upgrade
     * @var string
     */
    private $captchaLibDirectory = 'recaptcha-php-1.11';

    /**
     * Zikula_View instance
     * @var object
     */
    private $view;

    /**
     * Private Captcha key
     * @var string
     */
    private $privatekey;

    /**
     * Public Captcha key
     * @var string
     */
    private $publickey;

    /**
     * exempt Admin from captcha check
     * @var boolean
     */
    private $exemptAdmin;

    /**
     * Post constructor hook.
     *
     * @return void
     */
    public function setup()
    {
        $this->view = Zikula_View::getInstance("Captcha");
        $this->publickey = ModUtil::getVar('Captcha', 'publickey');
        $this->privatekey = ModUtil::getVar('Captcha', 'privatekey');
        $this->exemptAdmin = ModUtil::getVar('Captcha', 'exemptAdmin') && SecurityUtil::checkPermission('Captcha::', '::', ACCESS_ADMIN);
        require_once (DataUtil::formatForOS('modules/Captcha/lib/vendor/' . $this->captchaLibDirectory . '/recaptchalib.php'));
    }

     /**
     * Display hook for edit views.
     *
     * @param Zikula_DisplayHook $hook
     *
     * @return void
     */
    public function uiEdit(Zikula_DisplayHook $hook)
    {
        if (empty($this->privatekey) || empty($this->publickey)) {
            return;
        }
        if ($this->exemptAdmin) {
            return;
        }

        $https = System::serverGetVar('HTTPS');
        $html = recaptcha_get_html($this->publickey, null, isset($https) && $https == 'on');
        $this->view->assign('html', $html);

        // add this response to the event stack
        $area = 'provider.captcha.ui_hooks.service';
        $hook->setResponse(new Zikula_Response_DisplayHook($area, $this->view, 'hooks/edit.tpl'));
    }

    /**
     * validation handler for validate.edit hook type.
     *
     * @param Zikula_ValidationHook $hook
     *
     * @return void
     */
    public function validateEdit(Zikula_ValidationHook $hook)
    {
        if (empty($this->privatekey) || empty($this->publickey)) {
            return;
        }
        if ($this->exemptAdmin) {
            return;
        }
        $challenge = FormUtil::getPassedValue('recaptcha_challenge_field', null, 'POST');
        $response = FormUtil::getPassedValue('recaptcha_response_field', null, 'POST');

        $this->validation = new Zikula_Hook_ValidationResponse('data', array());

        if (!empty($challenge) && !empty($response)) {
            $resp = recaptcha_check_answer ($this->privatekey, $_SERVER["REMOTE_ADDR"], $challenge, $response);
            if (!$resp->is_valid) {
                $this->validation->addError('captcha', $resp->error);
            }
        } else {
            $this->validation->addError('captcha', __('Captcha values invalid (empty).', ZLanguage::getModuleDomain('Captcha')));
        }

        $hook->setValidator('provider.captcha.ui_hooks.service', $this->validation);
    }
}
