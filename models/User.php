<?php
//
///**
// * User.php
// * Author: Andrii Molchanov
// * Email: andymolchanov@gmail.com
// * 23.10.2025
// */
//
//namespace app\models;
//
//class User extends \yii\db\ActiveRecord
//{
//    public static function tableName()
//    {
//        return '{{%user}}';
//    }
//
//    public function rules()
//    {
//        return [
//            [['login', 'email', 'password'], 'required'],
//            [['login', 'email'], 'unique'],
//            [['login'], 'string', 'max' => 100],
//            [['email'], 'string', 'max' => 150],
//            [['password'], 'string', 'max' => 255],
//        ];
//    }
//
//    public function beforeSave($insert)
//    {
//        if (parent::beforeSave($insert)) {
//            $this->updated_at = time();
//            if ($this->isNewRecord) {
//                $this->created_at = time();
//            }
//            return true;
//        }
//        return false;
//    }
//}


/**
 * User.php
 * Author: Andrii Molchanov
 * Email: andymolchanov@gmail.com
 * 23.10.2025
 */

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return '{{%user}}';
    }

    public function rules()
    {
        return [
            [['login', 'email', 'password'], 'required'],
            [['login', 'email'], 'unique'],
            [['login'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 150],
            [['password'], 'string', 'max' => 255],
            [['token'], 'string', 'max' => 255],
        ];
    }
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    // Методы IdentityInterface
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return false;
    }
}
