<?php

class AdminDeleteOrdersController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();
        $this->meta_title = $this->l('Excluir Encomendas');
    }

    public function initContent()
    {
        parent::initContent();

        if (Tools::isSubmit('delete_order')) {
            $orderId = (int)Tools::getValue('order_id');
            if ($orderId > 0) {
                $this->deleteOrder($orderId);
            }
        }

        $this->context->smarty->assign([
            'deleteorders_form_action' => $_SERVER['REQUEST_URI']
        ]);
        $this->setTemplate('delete_orders.tpl');
    }

    private function deleteOrder($orderId)
    {
        $db = Db::getInstance();
        $db->execute('DELETE FROM ' . _DB_PREFIX_ . 'order_detail WHERE id_order = ' . (int)$orderId);
        $db->execute('DELETE FROM ' . _DB_PREFIX_ . 'order_history WHERE id_order = ' . (int)$orderId);
        $db->execute('DELETE FROM ' . _DB_PREFIX_ . 'order_invoice WHERE id_order = ' . (int)$orderId);
        $db->execute('DELETE FROM ' . _DB_PREFIX_ . 'order_payment WHERE order_reference = (SELECT reference FROM ' . _DB_PREFIX_ . 'orders WHERE id_order = ' . (int)$orderId . ')');
        $db->execute('DELETE FROM ' . _DB_PREFIX_ . 'orders WHERE id_order = ' . (int)$orderId);

        $this->confirmations[] = $this->l('Encomenda excluída com sucesso.');
    }
}
