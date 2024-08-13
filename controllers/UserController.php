<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    public function actionIndex()
    {
        $users = User::find()->all();

        return $this->render('index', [
            'users' => $users,
        ]);
    }

    public function actionCreate()
    {
        $model = new User();
        $model->scenario = 'create';
    
        if ($model->load(Yii::$app->request->post())) {
            $model->auth_key = Yii::$app->security->generateRandomString();
            $model->password_hash = Yii::$app->security->generatePasswordHash($model->password);
    
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Пользователь успешно создан.');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при создании пользователя: ' . json_encode($model->errors));
            }
        }
    
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    public function actionLogin()
{
    $model = new User();
    $model->scenario = 'login';

    if ($model->load(Yii::$app->request->post())) {
        $user = User::findOne(['username' => $model->username]);

        if ($user && Yii::$app->security->validatePassword($model->password, $user->password_hash)) {
            $user->auth_key = Yii::$app->security->generateRandomString();
            $user->save(false);
            
            Yii::$app->session->setFlash('success', 'Логин успешен.');
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('error', 'Неверное имя пользователя или пароль.');
        }
    }

    return $this->render('login', [
        'model' => $model,
    ]);
}

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Данные пользователя обновлены.');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Пользователь успешно удален.');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при удалении пользователя.');
        }

        return $this->redirect(['index']); 
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемый пользователь не существует.');
    }
}
