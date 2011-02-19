<?php
/**
 * Copyright Craig Heydenburg 2010 - Captcha
 *
 * Captcha
 *
 * @license MIT
 */

class Captcha_HookHandlers extends Zikula_HookHandler
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
     * Post constructor hook.
     *
     * @return void
     */
    public function setup()
    {
        $this->view = Zikula_View::getInstance("Captcha");
        $this->publickey = ModUtil::getVar('Captcha', 'publickey');
        $this->privatekey = ModUtil::getVar('Captcha', 'privatekey');
        require_once (DataUtil::formatForOS('modules/Captcha/lib/vendor/' . $this->captchaLibDirectory . '/recaptchalib.php'));
    }

     /**
     * Display hook for edit views.
     *
     * @param Zikula_Event $z_event
     *
     * @return void
     */
    public function ui_edit(Zikula_Event $z_event)
    {
        // Security check
        if (!SecurityUtil::checkPermission('Captcha::', '::', ACCESS_COMMENT)) {
            return;
        }
        if (empty($this->privatekey) || empty($this->publickey)) {
            return;
        }

        $html = recaptcha_get_html($this->publickey);
        $this->view->assign('html', $html);

        // add this response to the event stack
        $area = 'modulehook_area.captcha.event';
        $z_event->data[$area] = new Zikula_Response_DisplayHook($area, $this->view, 'hooks/edit.tpl');
    }

    /**
     * validation handler for validate.edit hook type.
     *
     * @param Zikula_Event $z_event
     *
     * @return void
     */
    public function validate_edit(Zikula_Event $z_event)
    {
        if (empty($this->privatekey) || empty($this->publickey)) {
            return;
        }
        $challenge = FormUtil::getPassedValue('recaptcha_challenge_field', null, 'POST');
        $response = FormUtil::getPassedValue('recaptcha_response_field', null, 'POST');

        $this->validation = new Zikula_Provider_HookValidation('data', array());

        $resp = recaptcha_check_answer ($this->privatekey, $_SERVER["REMOTE_ADDR"], $challenge, $response);

        if (!$resp->is_valid) {
            $this->validation->addError('captcha', $resp->error);
        }

        $z_event->data->set('hookhandler.captcha.ui.edit', $this->validation);
    }
}
