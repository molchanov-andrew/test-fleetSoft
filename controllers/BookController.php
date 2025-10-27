<?php
/**
 * AuthController.php
 * Author: Andrii Molchanov
 * Email: andymolchanov@gmail.com
 * 24.10.2025
 */

namespace app\controllers;

use app\models\Book;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class BookController extends ActiveController
{

    public $modelClass = 'app\models\Book';
    public $enableCsrfValidation = false;


    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['update'], $actions['delete']);
        $actions['index']['prepareDataProvider'] = function ($action) {
            $modelClass = $this->modelClass;
            return new ActiveDataProvider([
                'query' => $modelClass::find(),
                'pagination' => [
                    'pageSize' => 5,
                ],
            ]);
        };
        return $actions;
    }

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['index', 'view'],
        ];
        $behaviors['verbFilter'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'create' => ['POST'],
                'update' => ['PUT', 'PATCH'],
                'delete' => ['DELETE'],
                'view' => ['GET'],
                'index' => ['GET'],
            ],
        ];

        return $behaviors;
    }

    /**
     * @param $id
     * @return mixed
     */

    protected function findModel($id)
    {
        $modelClass = $this->modelClass;
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException("Книга с ID $id не найдена.");
    }

    /**
     * create book
     * @return Book[]
     * @throws ForbiddenHttpException
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {

        $rawBody = Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);

        $currentUser = Yii::$app->user->identity;
        if (!$currentUser) {
            throw new ForbiddenHttpException('Доступ запрещён: можно смотреть только свой профиль.');
        }
        $book = new Book();
        $book->name = $data['name'] ?? null;
        $book->author = $data['author'] ?? null;

        if (!$book->save()) {
            $errors = $book->errors;
            return ['success' => false, 'errors' => $errors];
        }
        return [$book];
    }

    /**
     * update book
     * @param $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $rawBody = Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);

        foreach ($model->attributes() as $attr) {
            if (array_key_exists($attr, $data)) {
                $model->$attr = $data[$attr];
            }
        }

        if ($model->save()) {
            Yii::$app->response->statusCode = 200;
            return [
                'status' => 'success',
                'data' => $model,
            ];
        }

        Yii::$app->response->statusCode = 422;
        return [
            'status' => 'error',
            'errors' => $model->errors,
        ];
    }

    /**
     * @param $id
     * @return string[]
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            Yii::$app->response->statusCode = 200;
            return [
                'status' => 'success',
                'message' => "Книга с  ID $id удалена."
            ];
        }

        Yii::$app->response->statusCode = 422;
        return [
            'status' => 'error',
            'message' => "Книга с ID $id не может быть удалена."
        ];
    }
}
