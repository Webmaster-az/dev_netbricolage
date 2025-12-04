<?php
/**
 * Copyright (c) Microsoft Corporation. All rights reserved.
 * Licensed under the AFL License.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 *  https://opensource.org/licenses/AFL-3.0
 *
 * @author    Microsoft Corporation <msftadsappsupport@microsoft.com>
 * @copyright Microsoft Corporation
 * @license    https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class AdminAjaxPsMicrosoftController extends ModuleAdminController
{
    public $moudule;

    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = false;
        $this->ajax = true;
    }

    public function initContent()
    {
        parent::initContent();
    }

    public function displayAjax()
    {
        try {
            $inputs = json_decode(Tools::file_get_contents('php://input'), true);
            $action = isset($inputs['action']) ? $inputs['action'] : null;

            switch ($action) {
                case 'savePublicKey':
                    $this->savePublicKey($inputs);

                    break;

                default:
                    http_response_code(400);

                    throw new Exception('Action is missing or incorrect.');
            }
        } catch (Exception $ex) {
            $this->ajaxDie(json_encode(['error' => ['picApiPoolErrorCode' => 'AjaxRequestFailed', 'Message' => $ex->getMessage()]]));
        }
    }

    private function savePublicKey(array $inputs)
    {
        if (!isset($inputs['publicKey'])) {
            throw new Exception('Missing public key');
        }

        Configuration::updateValue('PS_MICROSOFT_PUBLIC_KEY', $inputs['publicKey']);
        $this->ajaxDie(json_encode(['success' => true]));
    }
}
