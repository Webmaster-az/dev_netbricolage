<?php
class AuthController extends AuthControllerCore
{
    /**
     * Surcharge specifique eicaptcha
     */
    /*
    * module: eicaptcha
    * date: 2022-03-16 12:45:56
    * version: 2.0.3
    */
    public function initContent()
    {
        if ( Tools::isSubmit('submitCreate') ) {
              Hook::exec('actionContactFormSubmitCaptcha');
              if ( ! sizeof( $this->context->controller->errors ) ) {
                parent::initContent();
            } else {
                $register_form = $this
                ->makeCustomerForm()
                ->setGuestAllowed(false)
                ->fillWith(Tools::getAllValues());
                FrontController::initContent();
                $this->context->smarty->assign([
                    'register_form' => $register_form->getProxy(),
                    'hook_create_account_top' => Hook::exec('displayCustomerAccountFormTop')
                ]);
                $this->setTemplate('customer/registration');
            }
        } else {
            parent::initContent();
        }
    }
}
