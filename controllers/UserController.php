<?php

namespace app\controllers;

use Yii;
use \yii\rest\ActiveController;
use app\models\User;
use yii\web\Response;

class UserController extends ActiveController
{
    public $modelClass = 'app\models\User';

    public function actions()
    {
        $actions = parent::actions();

        // отключить действия "delete"
        unset($actions['delete']);

        return $actions;
    }

    /**
     * register new user
     * @return string
     */
    public function actionRegister()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $user = new User();
        $post = Yii::$app->request->post();
return $post;
        if (!empty($post['User'])) {
            $user->load($post, 'User');

            // Хэшируем пароль
            $user->password = Yii::$app->security->generatePasswordHash($user->password);

            if ($user->save()) {
                    return [
                        'status' => 'ok',
                        'message' => 'Регистрация успешна',
                        'user' => [
                            'id' => $user->id,
                            'login' => $user->login,
                            'email' => $user->email,
                        ]
                    ];
                } else {
                return [
                    'status' => 'error',
                    'errors' => $user->errors
                ];
            }
        }

        return [
            'status' => 'error',
            'message' => 'Нет данных для регистрации'
        ];
    }




    /**
     * login user
     */
    public function actionLogin()
    {
        return json_encode('login');
    }

}
