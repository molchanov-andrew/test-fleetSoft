<?php
/**
 * User.php
 * Author: Andrii Molchanov
 * Email: andymolchanov@gmail.com
 * 23.10.2025
 */

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
class User extends ActiveRecord implements IdentityInterface
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => time(),
            ],
        ];
    }
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

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        try {
            $decoded = JWT::decode($token, new Key(Yii::$app->params['jwtSecret'], 'HS256'));
            return static::findOne($decoded->uid ?? null);
        } catch (\Exception $e) {
            return null;
        }
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
