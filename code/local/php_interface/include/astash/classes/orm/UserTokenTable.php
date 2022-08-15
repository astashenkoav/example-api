<?php
/**
 * Created by PhpStorm.
 * User: Alexander Astashchenko
 * Date: 14.08.2022
 * Time: 10:03
 */

namespace Astash\Orm;

use Bitrix\Main;
use Bitrix\Main\Type;

class UserTokenTable extends \Bitrix\Main\Entity\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'b_eic_user_token';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return array(
            'ID' => new Main\Entity\IntegerField('ID', array(
                'primary' => true,
                'autocomplete' => true,
                'title' => 'ID'
            )),
            'USER_ID' => new Main\Entity\IntegerField('USER_ID', array(
                'required' => true,
                'title' => 'ID пользователя'
            )),
            'TOKEN' => new Main\Entity\StringField('TOKEN', array(
                'required' => true,
                'title' => 'токен'
            )),
            'DATE_CREATE' => new Main\Entity\DatetimeField('DATE_CREATE', array(
                'default_value' => new Type\DateTime(),
                'title' => 'токен'
            )),
        );
    }
}

//Astash\Orm\UserTokenTable::getEntity()->compileDbTableStructureDump();