<?php


namespace Astash;


use Bitrix\Main\Loader;

class HighLoadBlockHelper
{

    static private $instance = [];
    /** @var \Bitrix\Highloadblock\DataManager $entity_data_class */
    public $entityClass;

    private function __construct($hlBlockId)
    {
        Loader::includeModule('highloadblock');

        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($hlBlockId)->fetch(); // получаем объект вашего HL блока
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);  // получаем рабочую сущность
        $this->entityClass = $entity->getDataClass(); // получаем экземпляр класса
    }

    /**
     * @param $hlblockId
     *
     * @return mixed
     */
    public static function getInstance($hlBlockId)
    {

        if (self::$instance[$hlBlockId]) {
            return self::$instance[$hlBlockId];
        }

        return new self($hlBlockId);

    }


    /**
     * Получить id $hlBlock по названию
     *
     * @param string $name
     *
     * @return false|mixed
     */
    public static function getIdByName(string $name = '')
    {
        $name = trim($name);
        if (!$name) {
            return false;
        }

        try {
            Loader::includeModule('highloadblock');
            $list = \Bitrix\Highloadblock\HighloadBlockTable::getList(["cache" => ["ttl" => 360000]])->fetchAll();
            foreach ($list as $item) {
                if ($item['NAME'] == $name) {
                    return $item['ID'];
                }
            }
        } catch (\Exception $e) {

        }

        return false;
    }

    /**
     * Получить id $hlBlock по таблице
     *
     * @param string $tableName
     *
     * @return false|mixed
     */
    public static function getIdByTableName(string $tableName = '')
    {
        $tableName = trim($tableName);
        if (!$tableName) {
            return false;
        }

        try {
            Loader::includeModule('highloadblock');
            $list = \Bitrix\Highloadblock\HighloadBlockTable::getList(["cache" => ["ttl" => 360000]])->fetchAll();
            foreach ($list as $item) {
                if ($item['TABLE_NAME'] == $tableName) {
                    return $item['ID'];
                }
            }
        } catch (\Exception $e) {

        }

        return false;
    }
}