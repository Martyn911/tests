<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_NOT_VERIFED = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    public function behaviors()
    {
        return [
            'class' => TimestampBehavior::className(),
        ];
    }

    public function rules()
    {
        return [
            [['username', 'email', 'password', 'authKey', 'accessToken'], 'required'],
            [['username', 'email', 'password', 'authKey', 'accessToken'], 'string'],
            [['username', 'email', 'password', 'authKey'], 'string', 'max' => 32],
            [['accessToken'], 'string', 'max' => 64],
            [['status', 'created_at', 'updated_at'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_NOT_VERIFED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return User::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return User::findOne(['accessToken' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return User::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public static function findByEmail($email)
    {
        return User::findOne(['email' => $email]);
    }

    public static function findIdentityByAuthKey($code)
    {
        return User::findOne(['authKey' => $code]);
    }
}
