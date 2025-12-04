<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class DiscountsByCategory extends Module
{
    public function __construct()
    {
        $this->name = 'discounts_by_category';
        $this->tab = 'pricing_promotion';
        $this->version = '1.0.0';
        $this->author = 'Seu Nome';
        $this->need_instance = 0;
        
        parent::__construct();

        $this->displayName = $this->l('Discounts by Category');
        $this->description = $this->l('Permite adicionar descontos por quantidade a categorias.');
        $this->ps_versions_compliancy = ['min' => '1.7.0.0', 'max' => _PS_VERSION_];
    }

    public function install()
    {
        if (!parent::install() || !Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'category_quantity_discounts (
                id_category INT(11) NOT NULL,
                quantity INT(11) NOT NULL,
                discount DECIMAL(10,2) NOT NULL,
                PRIMARY KEY (id_category, quantity)
            )
        ')) {
            return false;
        }

        // Definir valor de configuração para exibição
        Configuration::updateValue('DISPLAY_DISCOUNTS', 1);

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() || !Db::getInstance()->execute('DROP TABLE IF EXISTS '._DB_PREFIX_.'category_quantity_discounts')) {
            return false;
        }

        // Remover a configuração
        Configuration::deleteByName('DISPLAY_DISCOUNTS');

        return true;
    }

    // Função para exibir a interface de administração
    public function getContent()
    {
        // Verifica se o formulário foi enviado para salvar um desconto
        if (Tools::isSubmit('submit_discount_category')) {
            $this->saveCategoryDiscount();
        }

        // Assign para exibir o formulário
        $this->context->smarty->assign([
            'discount_categories' => $this->getCategoryDiscounts(),
        ]);

        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
    }

    // Função para salvar os descontos por categoria
    public function saveCategoryDiscount()
    {
        $id_category = (int)Tools::getValue('id_category');
        $quantity = (int)Tools::getValue('quantity');
        $discount = (float)Tools::getValue('discount');

        // Verifica se a categoria já tem um desconto ou se estamos criando um novo
        if ($id_category && $quantity && $discount) {
            $existing = Db::getInstance()->getRow('
                SELECT * FROM '._DB_PREFIX_.'category_quantity_discounts 
                WHERE id_category = '.(int)$id_category.' AND quantity = '.(int)$quantity
            );

            if ($existing) {
                // Atualiza o desconto
                Db::getInstance()->update(
                    'category_quantity_discounts',
                    ['discount' => $discount],
                    'id_category = '.(int)$id_category.' AND quantity = '.(int)$quantity
                );
            } else {
                // Insere um novo desconto
                Db::getInstance()->insert(
                    'category_quantity_discounts',
                    [
                        'id_category' => (int)$id_category,
                        'quantity' => (int)$quantity,
                        'discount' => (float)$discount
                    ]
                );
            }
        }
    }

    // Função para obter todos os descontos por categoria
    public function getCategoryDiscounts()
    {
        $sql = 'SELECT * FROM '._DB_PREFIX_.'category_quantity_discounts';
        return Db::getInstance()->executeS($sql);
    }

    // Função para exibir o desconto na página do produto
    public function hookDisplayProductPriceBlock($params)
    {
        $product = $params['product'];
        $category_discounts = $this->getCategoryDiscountsForProduct($product->getCategories());

        foreach ($category_discounts as $discount) {
            if ($product->quantity >= $discount['quantity']) {
                $product->discount = $discount['discount'];
            }
        }

        return $this->display(__FILE__, 'views/templates/hook/product_discount.tpl');
    }

    // Função para obter os descontos de acordo com as categorias do produto
    public function getCategoryDiscountsForProduct($categories)
    {
        $discounts = [];
        foreach ($categories as $category_id) {
            $query = "SELECT quantity, discount FROM "._DB_PREFIX_."category_quantity_discounts WHERE id_category = ".(int)$category_id;
            $results = Db::getInstance()->executeS($query);
            foreach ($results as $row) {
                $discounts[] = $row;
            }
        }
        return $discounts;
    }
    
    public function deleteDiscount()
    {
        $id_category = (int)Tools::getValue('id_category');
        $quantity = (int)Tools::getValue('quantity');

        if ($id_category && $quantity) {
            Db::getInstance()->delete('category_quantity_discounts', 'id_category = '.(int)$id_category.' AND quantity = '.(int)$quantity);
        }
    }
}




