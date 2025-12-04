<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class DeleteOrders extends Module
{
    public function __construct()
    {
        $this->name = 'deleteorders';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Domingos Ferros para bonuspódio';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        
        $this->displayName = $this->l('Delete Orders');
        $this->description = $this->l('Permite excluir encomendas do banco de dados. (Separador defenições de encomenda > excluir encomendas)');
    }

    public function install()
    {
        return parent::install() && $this->registerHook('displayAdminOrder') && $this->installTab();
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallTab();
    }

    private function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminDeleteOrders';
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminParentOrders');
        $tab->module = $this->name;
        $tab->name = [];

        foreach (Language::getLanguages() as $lang) {
            $tab->name[$lang['id_lang']] = 'Excluir Encomendas';
        }

        return $tab->add();
    }

    private function uninstallTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminDeleteOrders');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }
        return true;
    }
}

// Criar controlador AdminDeleteOrdersController
if (!class_exists('AdminDeleteOrdersController')) {
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
}
