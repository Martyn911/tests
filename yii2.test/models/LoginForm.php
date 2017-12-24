<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends User
{
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email']
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            if($user){
                return Yii::$app->user->login($user);
            }
        }
        return false;
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    public function getUser()
    {
        $user = User::findByEmail($this->email);

        if($user === null){
            $verify_token = Yii::$app->security->generateRandomString(32);
            $user = new User();
            $user->username = $this->email;
            $user->password = md5(Yii::$app->security->generateRandomString(10) . $verify_token);
            $user->email = $this->email;
            $user->authKey = $verify_token;
            $user->accessToken = Yii::$app->security->generateRandomString(64);
            $user->status = User::STATUS_NOT_VERIFED;
            $user->save();

            if($this->sendVeriryMail($this->email, $verify_token)){
                Yii::$app->session->setFlash('verifyCodeSend');
                return false;
            } else {
                throw new Exception('Ошибка при отправке письма');
            }
        }
        return $user;
    }

    public function sendVeriryMail($email, $token)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([Yii::$app->params['adminEmail']])
                ->setSubject('Верификация учетной записи')
                ->setTextBody('Для верификации учетной записи перейдите, пожалуйста, по ссылке ' . Yii::$app->urlManager->createAbsoluteUrl(['/site/verify-user', 'code' => $token]))
                ->send();

            return true;
        }
        return false;
    }
}
