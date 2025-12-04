<?php
/**
 * The file is controller. Do not modify the file if you want to upgrade the module in future
 * 
 * @author    Globo Jsc <contact@globosoftware.net>
 * @copyright 2017 Globo., Jsc
 * @license   please read license in file license.txt
 * @link	     http://www.globosoftware.net
 */

class GcartremindercondreminderModel extends ObjectModel
{
    public $id_gconditionandreminder;
    public $position;
    public $active = 1;
    public $rulename;
    public $datefrom;
    public $dateto;
    public $coupon;
    public $mincartamount;
    public $maxcartamount;
    public $custormmer;
    public $reminder;
    public $reminder_group;
    public $countreminder;
    public $cartrule;
    public $validity;
    public static $definition = array(
        'table' => 'gconditionandreminder',
        'primary' => 'id_gconditionandreminder',
        'multilang' => true,
        'fields' => array(
            //Fields
            'position' => array('type' => self::TYPE_INT),
            'active'   => array('type' => self::TYPE_INT, 'validate' => 'isBool'),
            'rulename' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
                'required' => true),
            'datefrom' => array('type' => self::TYPE_DATE, 'validate' => 'isString'),
            'dateto'   => array('type' => self::TYPE_DATE, 'validate' => 'isString'),
            'coupon'   => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'mincartamount' => array('type' => self::TYPE_STRING),
            'maxcartamount' => array('type' => self::TYPE_STRING),
            'custormmer' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'reminder' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
                'required' => true),
            'reminder_group' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
                'required' => true),
            'countreminder' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'cartrule' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
            'validity' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            ),
        );

    public function __construct($id_gconditionandreminder = null, $id_lang = null, $id_shop = null)
    {
        Shop::addTableAssociation('gconditionandreminder', array('type' => 'shop'));
        parent::__construct($id_gconditionandreminder, $id_lang, $id_shop);
        return true;
    }
    public function add($autodate = true, $nullValues = false)
    {
        $nullValues;
        if ($this->position <= 0) {
            $this->position = GcartremindercondreminderModel::getHigherPosition() + 1;
        }
        $custormmer  = Tools::getValue("custormmers");
        $reminder    = Tools::getValue("jsreminder");
        $mincarts    = array();
        $reminder_groups= Tools::getValue("condition_group");
        $minvalue    = Tools::getValue('mincartamount');
        $maxvalue    = Tools::getValue('maxcartamount');
        if ($maxvalue < $minvalue) {
            Tools::displayError('Min Cart Amount Bigger Max Cart Amount');
        }
        $id_currency = Context::getContext()->currency->id;
        foreach ($minvalue as $key=>$mincart) {
            $mincarts[$key]   = (float)$mincart;
            if ($id_currency != $key && $mincart == '') {
                $mincarts[$key]   = (float)$minvalue[$id_currency];
            }
        }
        $maxcarts = array();
        foreach ($maxvalue as $key=>$mincart) {
            $maxcarts[$key]   = (float)$mincart;
            if ($id_currency != $key && $mincart == '') {
                $maxcarts[$key]   = (float)$minvalue[$id_currency];
            }
        }
        if (!isset($reminder_groups) || !is_array($reminder_groups)) {
            $reminder_groups = array();
        }
        $this->custormmer    = Tools::jsonEncode($custormmer);
        $this->reminder      = Tools::jsonEncode(GcartremindercondreminderModel::sortKey($reminder, 1));
        $this->mincartamount = Tools::jsonEncode($mincarts);
        $this->maxcartamount = Tools::jsonEncode($maxcarts);
        $this->reminder_group= Tools::jsonEncode(GcartremindercondreminderModel::sortKey($reminder_groups, 2));
        $return = parent::add($autodate, true);
        Hook::exec('actionGcartremindercondreminderModelSave', array('id_gconditionandreminder' => (int)$this->id));
        return $return;
    }
    public function update($nullValues = false)
    {
        $nullValues;
        if (!Tools::isSubmit('statusgconditionandreminder')) {
            $custormmer    = Tools::getValue("custormmers");
            $reminder      = Tools::getValue("jsreminder");
            $mincarts      = array();
            $reminder_groups= Tools::getValue("condition_group");
            $id_currency   = Context::getContext()->currency->id;
            $minvalue      = Tools::getValue('mincartamount');
            $maxvalue      = Tools::getValue('maxcartamount');
            if ($maxvalue < $minvalue) {
                Tools::displayError('Min Cart Amount Bigger Max Cart Amount');
            }
            foreach ($minvalue as $key=>$mincart) {
                $mincarts[$key]   = (float)$mincart;
                if ($id_currency != $key && $mincart == '') {
                    $mincarts[$key]   = (float)$minvalue[$id_currency];
                }
            }
            $maxcarts = array();
            foreach ($maxvalue as $key=>$mincart) {
                $maxcarts[$key]   = (float)$mincart;
                if ($id_currency != $key && $mincart == '') {
                    $maxcarts[$key]   = (float)$minvalue[$id_currency];
                }
            }
            if (!isset($reminder_groups) || !is_array($reminder_groups)) {
                $reminder_groups = array();
            }
            $this->custormmer    = Tools::jsonEncode($custormmer);
            $this->reminder      = Tools::jsonEncode(GcartremindercondreminderModel::sortKey($reminder, 1));
            $this->mincartamount = Tools::jsonEncode($mincarts);
            $this->maxcartamount = Tools::jsonEncode($maxcarts);
            $this->reminder_group= Tools::jsonEncode(GcartremindercondreminderModel::sortKey($reminder_groups, 2));
        }
        $return = parent::update(true);
        Hook::exec('actionGcartremindercondreminderModelUpdate', array('id_gconditionandreminder' => (int)$this->id));
        return $return;
    }
    public function delete()
    {
        $return = parent::delete();
        if ($return) {
            Hook::exec('actionGcartremindercondreminderModelDelete', array('id_gconditionandreminder' => (int)$this->id));
        }

        /* Reinitializing position */
        $this->cleanPositions();

        return $return;
    }
    public function updatePosition($way, $position, $id_gconditionandreminder = null)
    {
        if (!$res = Db::getInstance()->executeS('
			SELECT `position`, `id_gconditionandreminder`
			FROM `' . _DB_PREFIX_ . 'gconditionandreminder`
			WHERE `id_gconditionandreminder` = ' . (int)($id_gconditionandreminder ? $id_gconditionandreminder : $this->id) . '
			ORDER BY `position` ASC')) {
            return false;
        }
        $moved_GcartremindercondreminderModel = false;
        foreach ($res as $GcartremindercondreminderModel) {
            if ((int)$GcartremindercondreminderModel['id_gconditionandreminder'] == (int)$this->id) {
                $moved_GcartremindercondreminderModel = $GcartremindercondreminderModel;
            }
        }

        if ($moved_GcartremindercondreminderModel === false) {
            return false;
        }
        return (Db::getInstance()->execute('
			UPDATE `' . _DB_PREFIX_ . 'gconditionandreminder`
			SET `position`= `position` ' . ((int)$way ? '- 1' : '+ 1') . '
			WHERE `position`
			' . ((int)$way ? '> ' . (int)$moved_GcartremindercondreminderModel['position'] . '
            AND `position` <= ' . (int)$position : '< ' . (int)$moved_GcartremindercondreminderModel['position'] . '
            AND `position` >= ' . (int)$position)) && Db::getInstance()->execute(' UPDATE `' . _DB_PREFIX_ . 'gconditionandreminder`
			SET `position` = ' . (int)$position . '
			WHERE `id_gconditionandreminder`=' . (int)$moved_GcartremindercondreminderModel['id_gconditionandreminder']));
    }
    public static function cleanPositions()
    {
        Db::getInstance()->execute('SET @i = -1', false);
        $sql = 'UPDATE `' . _DB_PREFIX_ . 'gconditionandreminder`
        SET `position` = @i:=@i+1 ORDER BY `position` ASC';
        return (bool)Db::getInstance()->execute($sql);
    }
    public static function getHigherPosition()
    {
        $sql = 'SELECT MAX(`position`)
				FROM `' . _DB_PREFIX_ . 'gconditionandreminder`';
        $position = DB::getInstance()->getValue($sql);
        return (is_numeric($position)) ? $position : -1;
    }
    public function updatestatus($id_gconditionandreminder, $statust)
    {
        if ((int)$id_gconditionandreminder > 0) {
            $sql = 'UPDATE ' . _DB_PREFIX_ . 'gconditionandreminder SET active = ' . (int)$statust . '
            WHERE id_gconditionandreminder = ' . (int)$id_gconditionandreminder;
            return (bool)Db::getInstance()->execute($sql);
        } else {
            $sql = 'SELECT *
                FROM `' . _DB_PREFIX_ . 'gconditionandreminder`';
            $allstatus = Db::getInstance()->executeS($sql);
            foreach ($allstatus as $satustval) {
                if (!empty($satustval['datefrom']) || !empty($satustval['dateto'])) {
                    $timenows = date('Y-m-d H:i:s');
                    $timenow = strtotime($timenows);
                    $datefrom = strtotime($satustval['datefrom']);
                    $dateto = strtotime($satustval['dateto']);
                    $sqlupdate = 'UPDATE ' . _DB_PREFIX_ . 'gconditionandreminder SET active = ' . (int)$statust . '
                    WHERE id_gconditionandreminder = ' . (int)$satustval['id_gconditionandreminder'];
                    if (!empty($datefrom) && $timenow <= $datefrom && $datefrom > 0) {
                        return (bool)Db::getInstance()->execute($sqlupdate);
                    }
                    if (!empty($dateto) && $timenow >= $dateto && $dateto > 0) {
                        return (bool)Db::getInstance()->execute($sqlupdate);
                    }
                }
            }
        }
    }
    public static function sortKey($arraydefaults =array(), $level=1)
    {
        $arraynews = array();
        if ($arraydefaults) {
            foreach ($arraydefaults as $arraydefault) {
                if ($level == 2) {
                    $arraynew1s = array();
                    if ($arraydefault) {
                        foreach ($arraydefault as $arraydefault1) {
                            $arraynew1s[] = $arraydefault1;
                        }
                    }
                    $arraynews[] = $arraynew1s;
                } else {
                    $arraynews[] = $arraydefault;
                }
            }
        }
        return $arraynews;
    }
}
