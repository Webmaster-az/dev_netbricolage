<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author    Musaffar Patel
 * @copyright 2016-2021 Musaffar Patel
 * @license   LICENSE.txt
 */

/* Library */
include_once(_PS_MODULE_DIR_ . "/productpricebysize/lib/classes/PPBSControllerCore.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/lib/classes/PPBSInstallCore.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/lib/classes/PPBSMathEval.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/lib/classes/PPBSEvalMath.php");

/* Models */
include_once(_PS_MODULE_DIR_ . "/productpricebysize/models/PPBSConfigModel.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/models/PPBSSchema.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/models/PPBSInstall.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/models/PPBSObjectModel.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/models/PPBSDimension.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/models/PPBSUnit.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/models/PPBSTranslation.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/models/PPBSProduct.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/models/PPBSProductField.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/models/PPBSProductFieldOption.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/models/PPBSProductUnitConversion.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/models/PPBSAreaPrice.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/models/PPBSEquation.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/models/PPBSEquationTemplateModel.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/models/PPBSEquationVarModel.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/models/PPBSAreaPriceSuffixModel.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/models/PPBSStockModel.php");

/* helpers */
include_once(_PS_MODULE_DIR_ . "/productpricebysize/helpers/PPBSDimensionHelper.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/helpers/PPBSProductFieldHelper.php");
include_once(_PS_MODULE_DIR_ . '/productpricebysize/helpers/PPBSProductHelper.php');
include_once(_PS_MODULE_DIR_ . '/productpricebysize/helpers/PPBSProductUnitConversionHelper.php');
include_once(_PS_MODULE_DIR_ . '/productpricebysize/helpers/PPBSCartHelper.php');
include_once(_PS_MODULE_DIR_ . '/productpricebysize/helpers/PPBSEquationTemplateHelper.php');
include_once(_PS_MODULE_DIR_ . "/productpricebysize/helpers/PPBSUnitHelper.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/helpers/PPBSOrderHelper.php");
include_once(_PS_MODULE_DIR_ . '/productpricebysize/helpers/PPBSToolsHelper.php');
include_once(_PS_MODULE_DIR_ . '/productpricebysize/helpers/PPBSMassAssignHelper.php');
include_once(_PS_MODULE_DIR_ . '/productpricebysize/helpers/PPBSStockHelper.php');
include_once(_PS_MODULE_DIR_ . '/productpricebysize/helpers/PPBSTranslationHelper.php');

/** Controllers Widgets */
include_once(_PS_MODULE_DIR_ . '/productpricebysize/controllers/widget/MPProductSearchWidgetController.php');

/* Controllers - Admin */
include_once(_PS_MODULE_DIR_ . "/productpricebysize/controllers/admin/config/PPBSAdminConfigMainController.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/controllers/admin/config/PPBSAdminConfigDimensionsController.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/controllers/admin/config/PPBSAdminConfigUnitsController.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/controllers/admin/config/PPBSAdminAreaPriceSuffixesController.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/controllers/admin/config/PPBSAdminConfigTranslationsController.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/controllers/admin/config/PPBSAdminConfigEquationTemplatesController.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/controllers/admin/config/PPBSAdminConfigOptionsController.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/controllers/admin/config/PPBSAdminConfigMassAssignController.php");

include_once(_PS_MODULE_DIR_ . "/productpricebysize/controllers/admin/producttab/PPBSAdminProductTabController.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/controllers/admin/producttab/PPBSAdminProductTabGeneralController.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/controllers/admin/producttab/PPBSAdminProductTabFieldsController.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/controllers/admin/producttab/PPBSAdminProductTabAreaPricesController.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/controllers/admin/producttab/PPBSAdminProductTabEquationController.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/controllers/admin/producttab/PPBSAdminProductTabStockManagementController.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/controllers/admin/producttab/PPBSAdminProductTabWeightCalculationsController.php");

include_once(_PS_MODULE_DIR_."/productpricebysize/controllers/admin/order/PPBSAdminOrderController.php");


/* Controllers - Front */
include_once(_PS_MODULE_DIR_ . "/productpricebysize/controllers/front/PPBSFrontProductController.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/controllers/front/PPBSFrontCartController.php");
include_once(_PS_MODULE_DIR_ . "/productpricebysize/controllers/front/PPBSFrontCheckoutController.php");

/* Widgets */
include_once(_PS_MODULE_DIR_."/productpricebysize/widgets/equationeditor/controllers/MPEquationEditorController.php");
