<?php
/**
 * Multi Accessories for PrestaShop
 *
 * @author    PrestaMonster
 * @copyright PrestaMonster
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

require_once dirname(__FILE__) . '/AbstractAdminHsMultiAccessoriesCommon.php';

/**
 * Controller of admin page - Multi Accessories (Abstract).
 */
class AdminHsMultiAccessoriesWelcomePageAbstract extends AbstractAdminHsMultiAccessoriesCommon
{

    protected $change_logs = array(
        'v.2.10.0' => array(
            '[Fixed] Does not add accessories to cart in the case dropdown style (1.7.x)',
            '[Fixed] Doesn not install module on php 5.4',
            '[Improvement] Add multi accessories for multi products(PS 1.6 & 1.7).',
            '[Improvement] Delete main product & all accessories when delete an accessory in the basket.'
        ),
        'v.2.9.2' => array(
            '[Fixed] Does not add accessories in the case enable SSL at the back office',
            '[Fixed] Replace all accessories image by main product\'s image when customer changes the product combination, quantity ...',
            '[Fixed] Does not show icon warning out of stock in the case display type of group is dropdown.'
        ),
        'v.2.9.1' => array(
            '[Improvement] Make module compatible with PS 1.7.x.',
            '[Fixed] Error idCombination undefined with product without combination.',
            '[Add] Frontend - Collapse / Expand accessory groups'
        ),
        'v.2.9.0' => array(
            '[Feature] Add default accessory along with main product everywhere',
            '[Feature] Delete all accessories when delete main product item on basket',
            '[Added] An option to NOT show out-of-stock item at front-end',
            '[Fixed] Does not show input quantity in case display style radio and check box when the option "Allow your customers to change item quantity" is No',
            '[Fixed] Show group & accessories if the group is active',
            '[Fixed] Adding wrong accessories quantity in case display style is drop down',
            '[Fixed] Show message out of stock with in accessories in the cart',
            '[Fixed] Cannot sort accessories'
        ),
        'v.2.8.15' => array(
            '[Fixed] Delete accessory & main product cart rule after deleting cart rule'
        ),
        'v.2.8.14' => array(
            '[Fixed] Add quantity to cart does not correct in the case display style is dropdown.'
        ),
        'v.2.8.13' => array(
            '[Added] Display changelog in welcome page.',
            '[Fixed] Error when duplicate product'
        ),
        'v.2.8.12' => array(
            '[Fixed] Cross-out line on old price is not applicable',
            '[Fixed] Can\'t display right price of accessories if they don\'t have combinations',
            '[Fixed] Quantity of accessories are not based on main product'
        ),
        'v.2.8.11' => array(
            '[Fixed] Discount does not work in single shop (MS is not activated)',
            '[Fixed] Cannot access to Configuration page in PrestaShop 1.5.3.1',
            '[Changed] Accept min quantity and default quantity as 0'
        ),
        'v.2.8.10' => array(
            '[Fixed] Add discounts to shops which are enabled Multi Accessories Pro module only',
            '[Fixed] Hook "hookActionAdminProductsFormModifier" is not supported by PS1.5',
            '[Fixed] Strange character for accessory name due to wrong charset setting',
            '[Fixed] Remove discount amount',
        ),
        'v.2.8.9' => array(
            '[Fixed] Product\'s combination does not show when display style is "Dropdown" and option "Show images" is disabled',
            '[Fixed] Cannot change default option when enable Buy main product & accessories together',
            '[Fixed] Do not force visitors to buy accessory in case of radio / drop down style',
            '[Added] Display combination for selected accessories in price table',
        ),
        'v.2.8.8' => array(
            '[Fixed] Remove ui.tooltip - which is not in use and leads to js conflict.',
            '[Fixed] Add amount discount for accessory with or without tax based on current configuration',
            '[Fixed] Cannot copy accessories due to js error'
        ),
        'v.2.8.7' => array(
            '[Fixed] Can not sort groups if we move a group to 2nd, 3rd... position',
            '[Fixed] Disable behavior that scrolling to price table when option price table is disabled',
            '[Fixed] Empty accessory tab when there is no accessory products',
            '[Changed] Improve performance by optimizing the way to get product images',
            '[Fixed] Amount discount is not applied on PrestaShop version 1.6.0.9',
            '[Changed] Improve functions of changing discounts at back office',
            '[Added] Copy accessories from other products',
            '[Added] Specify custom quantity by admin'
        ),
        'v.2.8.6' => array(
            '[Fixed] Improve function updating main price so that it\'s stable on all versions of PrestaShop',
            '[Added] Support hook \'product tab\' and hook \'product tab content\'',
            '[Fixed] Price is rounded in a wrong way on PS 1.6.0.9'
        ),
        'v.2.8.5' => array(
            '[Fixed] Duplicated image type when re-installing the module',
            '[Fixed] Conflicts between hsmultiaccessoriespro and cronjob module',
            '[Fixed] There is no combination at Front-end and Back-end in case product has combinations but no combination is marked as default',
        ),
        'v.2.8.4' => array(
            '[Added] Allow adding discount for accessory items',
            '[Fixed] Fix break price into 2 lines on price table on mobile view',
            '[Fixed] If accessory has no combination, at front-end, price table always displays price is 0',
        ),
        'v.2.8.3' => array(
            '[Added] Show image in dropdown',
            '[Changed] Auto scroll to price table when accessory list is too long',
        ),
        'v.2.8.2' => array(
            '[Fixed] Get wrong accessory image',
            '[Fixed] Add 2 pieces of the main item to basket - conflict with theme Transformer',
            '[Fixed] Change main price when changing main product\'s combinations',
        ),
        'v.2.8.1' => array(
            '[Fix] Make accessories sortable when add new accessories',
            '[Fix] Does not update the unit price in case changing combination of main product, which has not accessory',
            '[Fix] Undefined index: combination',
        ),
        'v.2.8.0' => array(
            '[Feature] Make accessory groups sortable',
            '[Feature] Make accessories sortable',
        ),
        'v.2.7.0' => array(
            'Search accessories in multi shop',
            'Redesign layout of Multi Accessories tab in product page',
            'Show icon warning if accessory is out of stock but backordering is allowed',
        ),
        'v.2.6.0' => array(
            '[FEATURE] Introduce new option to show accessories as radios',
            '[FEATURE] Display style is set by accessory group',
            '[FEATURE] An ability to generate accessory images manually',
            '[FEATURE] Duplicate accessories when duplicating a product',
            '[IMPROVEMENT] Out-of-stock accessories are not allowed to check out',
            '[IMPROVEMENT] Show description when hovering on an accessory',
            '[IMPROVEMENT] Update product price on changing combinations even it does not have any accessory',
            '[IMPROVEMENT] Combination name is editable',
        ),
        'v.2.5.0' => array(
            'Generate product images manually',
            'Duplicate accessories when customer duplicate product',
            'Support edit name accessories',
        ),
        'v.2.4.0' => array(
            'FEATURE Work with combinations',
        ),
        'v.2.3.0' => array(
            'FEATURE Work with specific prices',
            'FEATURE Custom quantity for each accessory',
            'IMPROVEMENT Display accessory images in Fancybox',
            'FEATURE Custom quantity for each accessory',
        ),
        'v.2.2.0' => array(
            'FEATURE An ability to add each accessory to shopping cart',
            'IMPROVEMENT Display cost of main item and selected accessories together',
        ),
    );

    /**
     * Show result to view.
     *
     * @var type json
     */
    protected $ajax_json = array(
        'success' => false,
    );

    /**
     * All css files which should be loaded.
     *
     * @var array
     */
    protected $module_media_css = array(
        'welcome_page.css',
        'icon-menu-pos.css',
    );

    /**
     * Define link of addon page.
     *
     * @var string
     */
    protected $link_to_addon_page = 'http://addons.prestashop.com/en/ratings.php';

    /**
     * Define link of orestamonster page.
     *
     * @var string
     */
    protected $link_to_prestamonster = 'http://addons.prestashop.com/en/89_prestamonster';

    /**
     * construct.
     */
    public function __construct()
    {
        $this->bootstrap = true;
        $this->display = 'view';
        parent::__construct();
    }

    public function initToolBarTitle()
    {
        $this->toolbar_title[] = $this->l('Administration');
        $this->toolbar_title[] = $this->module->tab_admin_welcome_page['AdminHsMultiAccessoriesWelcomePagePro'];
    }

    public function renderView()
    {
        Configuration::updateValue($this->module->getKeyWelcomePage(), 1);
        $this->context->smarty->assign(array(
            'module_name' => $this->module->name,
            'module_version' => $this->module->version,
            'change_logs' => $this->change_logs,
            'link_to_addon_page' => $this->link_to_addon_page,
            'is_prestashop_16' => $this->module->isPrestashop16(),
            'link_module_homepage' => $this->context->link->getAdminLink($this->module->class_controller_admin_group),
        ));
        return parent::renderView();
    }
    
    /**
     * Set Media file include when controller called.
     */
    public function setMedia()
    {
        parent::setMedia();
        if (!empty($this->module_media_css) && is_array($this->module_media_css)) {
            $css_files = array();
            foreach ($this->module_media_css as $css_file) {
                $css_file = $this->module->getCssPath() . $css_file;
                if (version_compare(_PS_VERSION_, '1.6') === -1) {
                    $css_file = $css_file . '?' . $this->module->name . '=' . $this->module->version;
                }
                $css_files[] = $css_file;
            }
            $this->addCSS($css_files);
        }
    }

    /**
     * Initinalize all translations.
     */
    protected function initTranslations()
    {
        parent::initTranslations();
        $source = basename(__FILE__, '.php');
        $this->context->smarty->assign(array(
            'welcome_to' => str_replace(array('[span]', '[/span]'), array('<span class="module_name">', '</span>'), sprintf($this->l('Welcome to [span]%s %s[/span]!', $source), $this->module->displayName, $this->module->version)),
            'an_awesome_PrestaShop_solution_provided_by_prestamonster' => $this->l('An awesome PrestaShop solution provided by PrestaMonster.', $source),
            'change_log' => $this->l('Change log', $source),
            'read_more' => $this->l('Read more...', $source),
            'thank_you_and_take_me_to' => sprintf($this->l('Thank you, and take me to %s', $source), $this->module->displayName),
            'share_your_reviews' => $this->l('Share your reviews', $source),
            'and_as_a_result_you_will_get_more_values_from_us' => $this->l('And as a result, you will get more values from us.', $source),
            'add_your_on_addons_prestashop_com_to_help_us' => str_replace(array('[span]', '[/span]'), array('<span class="vote_star">', '</span>'), sprintf($this->l('Add your [span]%s[/span] on %s to help us improve %s continuously.', $source), '<a href="'.$this->link_to_addon_page.'" target="_blank">'.$this->l('★★★★★', $source).'</a>', '<a href="'.$this->link_to_addon_page.'" target="_blank">'.$this->l('addons.prestashop.com', $source).'</a>', $this->module->displayName)),
            'just_log_into_prestashop_addons_with_your_credentials' => sprintf($this->l('Just log into %s with your credentials, then visit this page and look for the right order number.', $source), '<a href="'.$this->link_to_addon_page.'" target="_blank">'.$this->l('Prestashop Addons', $source).'</a>'),
            'looking_for_even_better_prestashop_modules' => $this->l('Looking for even better PrestaShop modules?', $source),
            'take_a_look_at_all_modules_developed_by_prestamonster' => sprintf($this->l('Take a look at all modules developed by %s.', $source), '<a href="'.$this->link_to_prestamonster.'" target="_blank">'.$this->l('PrestaMonster', $source).'</a>'),
        ));
    }
}
