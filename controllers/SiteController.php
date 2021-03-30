<?php

namespace app\controllers;

use app\models\Images;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\UploadForm;
use yii\web\UploadedFile;
use yii\data\Sort;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
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
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage - rendering all uploaded images.
     *
     * @return string
     */
    public function actionIndex()
    {
        /* Получаем все изображения в БД */
        $images = Images::find();

        /* Настраиваем сортировку */
        $sort = new Sort([
            'attributes' => [
                'name' => [
                    'label' => 'Имя',
                ],
                'upload_datetime' => [
                    'default' => SORT_DESC,
                    'label' => 'Дата и время загрузки',
                ],
            ],
        ]);


        /* Добавляем пагинацию */
        $pagination = new Pagination([
            'defaultPageSize' => 9,
            'totalCount' => $images->count()
        ]);

        $images = $images->orderBy('upload_datetime')
            ->orderBy($sort->orders)
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'images' => $images,
            'pagination' => $pagination,
            'sort' => $sort
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Displays upload page | Upload images.
     *
     * @return string
     */
    public function actionUpload()
    {
        $model = new UploadForm();
        $after_upload = [
            'not_uploaded' => [],
            'uploaded' => []
        ];

        if (Yii::$app->request->isPost) {
            /* Получаем все файлы из формы */
            $model->images = UploadedFile::getInstances($model, 'images');
            /* Вызываем метод загрузки */
            $after_upload = $model->upload();
        }

        return $this->render('upload', [
            'model' => $model,
            'not_uploaded' => $after_upload['not_uploaded'],
            'uploaded' => $after_upload['uploaded']
        ]);
    }
}
