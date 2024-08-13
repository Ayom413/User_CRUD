<?php
namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\User;
use yii\web\NotFoundHttpException;

class ApiUserController extends Controller
{
    public function actionIndex()
    {
        return User::find()->all();
    }

    public function actionView($id)
    {
        return $this->findModel($id);
    }

    public function actionCreate()
    {
        $model = new User();
        $model->scenario = 'create';
    
        if ($model->load(Yii::$app->request->post(), '')) {
            $model->auth_key = Yii::$app->security->generateRandomString();
            $model->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            
            if ($model->save()) {
                Yii::$app->response->statusCode = 201;
                return [
                    'id' => $model->id,
                    'username' => $model->username,
                ];
            }
        }
    
        Yii::$app->response->statusCode = 400;
        return $model->errors;
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
    
        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            if (!empty($model->password)) {
                $model->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            }
            if ($model->save()) {
                return [
                    'id' => $model->id,
                    'username' => $model->username,
                ];
            }
        }
    
        Yii::$app->response->statusCode = 400;
        return $model->errors;
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->response->statusCode = 204;
    }

    public function actionLogin()
    {
        $data = Yii::$app->request->post();
        $username = isset($data['username']) ? $data['username'] : null;
        $password = isset($data['password']) ? $data['password'] : null;
    
        if ($username && $password) {
            $user = User::findOne(['username' => $username]);
            
            if ($user && Yii::$app->security->validatePassword($password, $user->password_hash)) {
                $user->auth_key = Yii::$app->security->generateRandomString();
                $user->save(false);
                return ['message' => 'Аутентификация успешна'];
            } else {
                return ['error' => 'Неверное имя пользователя или пароль.'];
            }
        }
    
        Yii::$app->response->statusCode = 400;
        return ['error' => 'Неверные данные запроса.'];
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемый пользователь не существует.');
    }
}
