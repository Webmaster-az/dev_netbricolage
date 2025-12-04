<?php
/**
 * mitrocops
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
 /*
 * 
 * @author    mitrocops
 * @category seo
 * @package gsnipreview
 * @copyright Copyright mitrocops
 * @license   mitrocops
 */

class AdminReviewold extends AdminTab{



    private $_name_controller = 'AdminReviewsold';
    public function __construct()

    {
        $red_url = 'index.php?controller='.$this->_name_controller.'&token='.Tools::getAdminTokenLite($this->_name_controller);
        Tools::redirectAdmin($red_url);
    }
		

}

?>

