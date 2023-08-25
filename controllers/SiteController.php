<?php

namespace app\controllers;

use app\models\Developer;
use app\models\DeveloperForm;
use app\models\Game;
use app\models\GameForm;
use app\models\Genre;
use app\models\GenreForm;
use MongoDB\BSON\PackedArray;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionDeveloper()
    {
        $model = new DeveloperForm();
        $developer = new DeveloperForm();

        $developers = Developer::findDevelopers();
        $dataProvider = new ActiveDataProvider([
            'query' => $developers,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

        if (Yii::$app->request->get('idU') && Yii::$app->request->getIsPjax()) {
            $developer->loadFromDB(Yii::$app->request->get('idU'));
        }

        if (Yii::$app->request->get('idD') && Yii::$app->request->getIsPjax()) {
            Developer::deleteDeveloper(Yii::$app->request->get('idD'));
        }

        if ($developer->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && Yii::$app->request->post('idU')) {
            if ($developer->validate()) {
                $developer->updateDeveloper(Yii::$app->request->post('idU'));
            }
        }

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && !Yii::$app->request->post('idU')) {
            if ($model->validate()) {
                $model->addDeveloper();
            }
        }

        return $this->render('developer', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'developer' => $developer
        ]);
    }

    public function actionGenre()
    {
        $model = new GenreForm();
        $genre = new GenreForm();

        $genres = Genre::findGenres();
        $dataProvider = new ActiveDataProvider([
            'query' => $genres,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

        if (Yii::$app->request->get('idU') && Yii::$app->request->getIsPjax()) {
            $genre->loadFromDB(Yii::$app->request->get('idU'));
        }

        if (Yii::$app->request->get('idD') && Yii::$app->request->getIsPjax()) {
            Genre::deleteGenre(Yii::$app->request->get('idD'));
        }

        if ($genre->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && Yii::$app->request->post('idU')) {
            if ($genre->validate()) {
                $genre->updateGenre(Yii::$app->request->post('idU'));
            }
        }

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && !Yii::$app->request->post('idU')) {
            if ($model->validate()) {
                $model->addGenre();
            }
        }

        return $this->render('genre', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'genre' => $genre
        ]);
    }

    public function actionGame()
    {
        $model = new GameForm();
        $developers = Developer::findAllDevelopers();
        $genres = Genre::findAllGenres();
        $game = new GameForm();

        $games = Game::findGames(Yii::$app->request->get('genre'));
        $dataProvider = new ActiveDataProvider([
            'query' => $games,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

        if (Yii::$app->request->get('idU') && Yii::$app->request->getIsPjax()) {
            $game->loadFromDB(Yii::$app->request->get('idU'));
        }

        if (Yii::$app->request->get('idD') && Yii::$app->request->getIsPjax()) {
            Game::deleteGame(Yii::$app->request->get('idD'));
        }

        if ($game->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && Yii::$app->request->post('idU')) {
            if ($game->validate()) {
                $game->updateGame(Yii::$app->request->post('idU'));
            }
        }

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax && !Yii::$app->request->post('idU')) {
            if ($model->validate()) {
                $model->addGame();
            }
        }

        return $this->render('game', [
            'model' => $model,
            'developers' => $developers,
            'genres' => $genres,
            'dataProvider' => $dataProvider,
            'game' => $game
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
}
