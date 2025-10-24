<?php

/**
 * AuthController.php
 * Author: Andrii Molchanov
 * Email: andymolchanov@gmail.com
 * 24.10.2025
 */

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\User;
use yii\web\UnauthorizedHttpException;
use Firebase\JWT\JWT;

class AuthController extends ActiveController
{
    public $modelClass = 'app\models\User';
    public $enableCsrfValidation = false;

    /**
     * Отключаем стандартные действия ActiveController (index, view и т.д.)
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['view'], $actions['update'], $actions['delete'], $actions['create']);
        return $actions;
    }

    /**
     * POST /auth/login
     */
    public function actionLogin()
    {
        $rawBody = Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);

        $login = $data['login'] ?? null;
        $password = $data['password'] ?? null;

        if (!$login || !$password) {
            throw new UnauthorizedHttpException('Login и пароль обязательны.');
        }

        /** @var User $user */
        $user = User::findOne(['login' => $login]);

        if (!$user) {
            throw new UnauthorizedHttpException('Неверный login или пароль.');
        }

        $key = Yii::$app->params['jwtSecret'];
        $payload = [
            'iss' => 'localhost:8000',
            'iat' => time(),
            'exp' => time() + 3600 * 24,
            'uid' => $user->id,
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');

        $user->token = $jwt;

        if(!$user->save()){
            $errors = $user->errors;
            return ['success' => false, 'errors' => $errors];
        }

        return [
            'token' => $jwt,
            'expires_in' => 86400,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'login' => $user->login,
            ],
        ];
    }
}
