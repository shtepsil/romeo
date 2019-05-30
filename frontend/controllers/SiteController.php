<?php
namespace frontend\controllers;

use frontend\components\BasketProduct;
use frontend\models\Ads;
use frontend\models\ProductNomenclature;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use frontend\controllers\MainController as d;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\data\Pagination;

/**
 * Site controller
 */
class SiteController extends d
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup','about'],
                'rules' => [
                    [
                        'actions' => ['signup','about'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    $this->getActoinsByRole()

//                    [
//                        'actions' => [
//                            'logout',
//                            'about',
//                        ],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
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
     * Метод возвращает тип массива разрешенных actions
     * в зависимости от роли пользователя
     */
    private function getActoinsByRole(){

        $access_rights = [
            'user'=>[
                'logout',
                'index',
                'search',
                'about',
                'debug',
            ],
            'admin'=>[],
            'sadmin'=>[
                'ajax',
            ]
        ];

//        switch(Yii::$app->user->role){}
        $role = 'user';
        switch($role){
            case 'user':
                $actions = $access_rights['user'];
                break;
            case 'admin':
                $actions = array_merge(
                    $access_rights['user'],
                    $access_rights['admin']);
                break;
            default:
                $actions = array_merge(
                    $access_rights['user'],
                    $access_rights['admin'],
                    $access_rights['sadmin']);
        }

//        $arr_access = [
//            'actions' => $actions,
//            'allow' => true,
//            'roles' => [
//                User::ROLE_USER,
//                User::ROLE_ADMIN,
//                User::ROLE_SADMIN
//            ],
//        ];

        $arr_access = [
            'actions' => $actions,
            'allow' => true,
            'roles' => ['@'],
        ];

        return $arr_access;

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
     * @return mixed
     */
    public function actionIndex()
    {

        Yii::$app->opengraph->set([
            'title' => Yii::$app->name,
            'description' => Yii::$app->params['description'],
            'image' => Yii::getAlias('@web').Yii::$app->params['img_preview_link'],
        ]);
        return $this->render('index',[
            'alerts' => $this->renderAjax('shortcodes/alerts'),
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['admin_email'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        Yii::$app->opengraph->set([
            'title' => Yii::$app->name,
            'description' => Yii::$app->params['description'],
            'image' => Yii::getAlias('@web').Yii::$app->params['img_preview_link'],
        ]);
        return $this->render('about');
    }

    /**
     * Displays stocks page.
     *
     * @return mixed
     */
    public function actionStocks()
    {
        Yii::$app->opengraph->set([
            'title' => Yii::$app->name,
            'description' => Yii::$app->params['description'],
            'image' => Yii::getAlias('@web').Yii::$app->params['img_preview_link'],
        ]);
        $get = d::secureEncode(Yii::$app->request->get());
        /*
         * Если существует ID рекламы
         * прибавим один к счетчику переходов
         */
        if(isset($get['ads']) AND $get['ads'] AND is_int((int)$get['ads'])){
            $ads = Ads::findOne(['id'=>$get['ads']]);
            $ads->counter = ($ads->counter + 1);
            $ads->save();
            // Перезагружаем страницу без GET параметров
            Yii::$app->response->redirect(Url::to('stocks'));
        }






        $ptne = [];

//        $pn = ProductNomenclature::findAll(['commodity_group_code'=>$get['cgc']]);

        /*
         * Получаем общее количество
         * выбранных по условию элементов
         * cgc=012&level_id=2
         */
        $pn_query_count = ProductNomenclature::find()
            ->where([
                'commodity_group_code'=>'012',
                'display' => '1'
            ])
            ->count();

        $pn_query = ProductNomenclature::find();
        $pages = new Pagination([
            'totalCount' => $pn_query->count(),
            'pageSize' => 1000,
            'forcePageParam' => false,
            'pageSizeParam' => false
        ]);
//        $pages->pageSizeParam = false;
        $pn = $pn_query->offset($pages->offset)
            ->where([
                'commodity_group_code'=>'012',
                'display' => '1'
            ])
            ->limit($pages->limit)
            ->all();

        if($pn){
            $count_query = count($pn);
            foreach($pn as $pn_one){
                foreach($pn_one as $key=>$val) $ptne[$pn_one['id']][$key] = $val;
                // Получаем список загруженных файлов
                $files = @scandir(Yii::getAlias('@photos').'/'.$pn_one['brand_code'].'/'.$pn_one['id']);
                if($files){
                    foreach($files as $file){
                        if($file != '.' AND $file != '..' AND $file != 'thumb')
                            $ptne[$pn_one['id']]['photos'][] =
                                Yii::getAlias('@photos_rel').
                                '/'.$pn_one['brand_code'].'/'.$pn_one['id'].'/'.$file;
                    }
                }else $ptne[$pn_one['id']]['photos'] = [];
            }
        }else $pn = false;






        return $this->render('stocks',[
            'catalog' => $ptne
        ]);
    }

    /**
     * Displays discounts page.
     *
     * @return mixed
     */
    public function actionDiscounts()
    {
        Yii::$app->opengraph->set([
            'title' => Yii::$app->name,
            'description' => Yii::$app->params['description'],
            'image' => Yii::getAlias('@web').Yii::$app->params['img_preview_link'],
        ]);
        return $this->render('discounts');
    }

    /**
     * Displays contacts page.
     *
     * @return mixed
     */
    public function actionContacts()
    {
        Yii::$app->opengraph->set([
            'title' => Yii::$app->name,
            'description' => Yii::$app->params['description'],
            'image' => Yii::getAlias('@web').Yii::$app->params['img_preview_link'],
        ]);
        return $this->render('contacts');
    }

    /**
     * Displays cart page.
     *
     * @return mixed
     */
    public function actionCart()
    {
        return $this->render('cart',[
            'alerts' => $this->renderAjax('shortcodes/alerts')
        ]);
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Страница "Отправка Email"
     *
     * @return mixed
     */
    public function actionEmail()
    {
        return $this->render('email',[
            'alerts' => $this->renderAjax('shortcodes/alerts')
        ]);
    }


    /**
     * Запуск сессии
     * Проверка пользователя на авторизацию
     * Проверка подтверждения Email
     */
    public function beforeAction($action)
    {
        $session = Yii::$app->session;
        // Стартуем сессию
        $session->open();

        return parent::beforeAction($action);
    }


































    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionDebugg()
    {
        return $this->render('debugg');
    }

}// End Class
