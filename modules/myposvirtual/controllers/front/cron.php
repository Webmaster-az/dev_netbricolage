<?php

class MyposVirtualCronModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        $date_from = date('Y-m-d H:i:m', time()- 21600);
        $date_to = date('Y-m-d H:i:m');


        $sql = 'SELECT `id_order`
                FROM `'._DB_PREFIX_.'orders`
                WHERE date_add <= \'' . pSQL($date_to) . '\' AND date_add >= \'' . pSQL($date_from) . '\'
                    '.Shop::addSqlRestriction() . '
                    AND current_state = \'' . (int)Configuration::get('awaiting_mypos_order_state_id') . '\'';

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);

        if (!empty($result)) {
            foreach ($result as $order) {
                if (array_key_exists('id_order', $order)) {
                    $this->module->checkPaymentStatus($order['id_order']);
                }
            }
        }
        die('OK');
    }
}