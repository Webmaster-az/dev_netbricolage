<?php
/**
 * Page Cache Ultimate, Page Cache standard and Speed pack are powered by Jpresta (jpresta . com)
 *
 *    @author    Jpresta
 *    @copyright Jpresta
 *    @license   You are just allowed to modify this copy for your own use. You must not redistribute it. License
 *               is permitted for one Prestashop instance only but you can install it on your test instances.
 */

if (!class_exists('JprestaCustomer')) {

    /**
     * Used to create fake user to anonimize the cache
     */
    class JprestaCustomer extends Customer
    {
        public function validateFields($die = true, $error_return = false)
        {
            // Set fake value to required fields to avoid SQL errors and disable all fields validation to avoid
            // functional errors
            foreach ($this->def['fields'] as $fieldName => $fieldDef) {
                if (in_array('required', $fieldDef) && ((bool)$fieldDef['required'])) {
                    if (Tools::isEmpty($this->$fieldName)) {
                        if (in_array('default', $fieldDef) && !Tools::isEmpty($fieldDef['default'])) {
                            // Use default value if any
                            $this->$fieldName = $fieldDef['default'];
                        } else {
                            if ($fieldDef['type'] == self::TYPE_INT) {
                                $this->$fieldName = 0;
                            } elseif ($fieldDef['type'] == self::TYPE_BOOL) {
                                $this->$fieldName = 0;
                            } elseif ($fieldDef['type'] == self::TYPE_HTML) {
                                $this->$fieldName = '<!-- -->';
                            } elseif ($fieldDef['type'] == self::TYPE_STRING) {
                                $this->$fieldName = '-';
                            } elseif ($fieldDef['type'] == self::TYPE_FLOAT) {
                                $this->$fieldName = 0.0;
                            } elseif ($fieldDef['type'] == self::TYPE_DATE) {
                                $this->$fieldName = '1970-01-01';
                            }
                        }
                    }
                }
            }
            if (property_exists($this, 'cpf')) {
                // A fake CPF for Brazilian users
                $this->cpf = '783.472.095-37';
            }
        }

        /**
         * Overrides default behavior to simulates logged in states for HTML cache
         */
        public function isLogged($withGuest = false)
        {
            if (!$withGuest && $this->is_guest == 1) {
                return false;
            }

            if (JprestaUtils::isCaller('ps_googleanalytics', 'run')) {
                // Don't want Google Analytics to use the ID of fake user.
                return false;
            }

            return $this->id && Validate::isUnsignedId($this->id);
        }

        public static function deleteAllFakeUsers() {
            $rows = JprestaUtils::dbSelectRows('SELECT id_customer FROM `' . _DB_PREFIX_ . 'customer` WHERE email like \'%@fakeemail.com\' AND active=0');
            foreach ($rows as $row) {
                $customerToDelete = new JprestaCustomer($row['id_customer']);
                $customerToDelete->delete();
            }
        }
    }
}
