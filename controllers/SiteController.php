<?php

namespace app\controllers;

use app\models\ImageModel;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\ImageUploadForm;
use app\models\ImageEncodeForm;

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
            return $this->redirect(['site/image', 'imageHash' => $ImageUploadForm->imageName]);
        }

        return $this->render('index', ['ImageUploadForm' => $ImageUploadForm]);
    }

    /**
     * Displays image viewer.
     *
     * @param string $imageHash
     * @return string
     */
    public function actionImage(string $imageHash)
    {
        $imageModel = new ImageModel(['imageName' => $imageHash]);

        if (!$imageModel->validate()) {
            throw new NotFoundHttpException('Image not exist');
        }

        // load encode form only if we are sure the image exists
        $imageEncodeForm = new ImageEncodeForm(['imageName' => $imageHash]);

        if ($imageEncodeForm->load(Yii::$app->request->post()) && $imageEncodeForm->validate()) {
            print_r($imageEncodeForm);
            die();
            //return $this->redirect(['site/image', 'imageHash' => $ImageUploadForm->imageName]);
        }

        return $this->render('image', [
            'imageModel'        => $imageModel,
            'imageEncodeForm'   => $imageEncodeForm,
        ]);
    }
}
