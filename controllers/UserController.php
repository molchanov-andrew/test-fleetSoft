<?php

namespace app\controllers;

use Yii;
use \yii\rest\ActiveController;
use app\models\User;
use yii\web\Response;
use yii\filters\auth\HttpBearerAuth;
use yii\web\ForbiddenHttpException;

class UserController extends ActiveController
{
    public $modelClass = 'app\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['view']);
        return $actions;
    }

    /**
     * register new user
     * @return array
     */
    public function actionCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $user = new User();
        $rawBody = Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);

        if (!empty($data)) {
            $user->login = $data['login'];
            $user->password = $data['password'];
            $user->email = $data['email'];

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

    public function actionView($id)
    {
        $user = User::findOne($id);

        if (!$user) {
            return ['success' => false, 'message' => 'Пользователь не найден'];
        }

        // Проверяем, что пользователь смотрит свой профиль
        $currentUser = Yii::$app->user->identity;

        if ($currentUser->id !== (int)$id) {
            throw new ForbiddenHttpException('Доступ запрещён: можно смотреть только свой профиль.');
        }

        return [
            'success' => true,
            'data' => [
                'id' => $user->id,
                'login' => $user->login,
                'email' => $user->email,
                'created_at' => $user->created_at,
            ],
        ];
    }


}
