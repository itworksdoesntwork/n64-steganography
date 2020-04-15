<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\ImageUploadForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $ImageUploadForm = new ImageUploadForm();

        if (
            $ImageUploadForm->load(Yii::$app->request->post()) &&
            $ImageUploadForm->validate() &&
            $ImageUploadForm->upload()
        ) {
            // image uploaded successfully !!!
            echo "Uploaded";
            die();
        }

        return $this->render('index', ['ImageUploadForm' => $ImageUploadForm]);
    }
}
