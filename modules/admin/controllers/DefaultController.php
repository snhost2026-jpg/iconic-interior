<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\HeroStyle;
use app\models\LoginForm;
use app\models\Product;
use app\models\Project;
use app\models\Service;
use app\models\UserQuery;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class DefaultController extends Controller
{
    public function behaviors(): array
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
     * Executive Dashboard (/admin)
     */
    public function actionIndex()
    {
        $totalQueries = (int) UserQuery::find()->count();
        $convertedQueries = (int) UserQuery::find()->where(['status' => 'Converted'])->count();
        $conversionRate = $totalQueries > 0 ? round(($convertedQueries / $totalQueries) * 100, 1) : 0;

        $kpis = [
            'total_queries' => $totalQueries,
            'unread_queries' => (int) UserQuery::find()->where(['is_read' => 0])->count(),
            'converted_queries' => $convertedQueries,
            'conversion_rate' => $conversionRate,
            'active_services' => (int) Service::find()->where(['is_active' => 1])->count(),
            'portfolio_projects' => (int) Project::find()->where(['is_active' => 1])->count(),
            'hero_styles' => (int) HeroStyle::find()->where(['is_active' => 1])->count(),
            'total_products' => (int) Product::find()->count(),
            'active_products' => (int) Product::find()->where(['is_active' => 1])->count(),
            'out_of_stock_products' => (int) Product::find()->where(['or', ['is_in_stock' => 0], ['<=', 'stock', 0]])->count(),
        ];

        // Status pipeline counts
        $statusCounts = [];
        foreach (UserQuery::STATUSES as $st) {
            $statusCounts[$st] = (int) UserQuery::find()->where(['status' => $st])->count();
        }

        // Recent 10 queries
        $recentQueries = UserQuery::find()
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(10)
            ->all();

        // Recent portfolio projects
        $recentProjects = Project::find()
            ->orderBy(['id' => SORT_DESC])
            ->limit(4)
            ->all();

        // Top featured products
        $topProducts = Product::find()
            ->orderBy(['is_featured' => SORT_DESC, 'id' => SORT_DESC])
            ->limit(4)
            ->all();

        return $this->render('index', [
            'kpis' => $kpis,
            'statusCounts' => $statusCounts,
            'recentQueries' => $recentQueries,
            'recentProjects' => $recentProjects,
            'topProducts' => $topProducts,
        ]);
    }

    /**
     * Admin Login (/admin/login)
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['index']);
        }

        $this->layout = false; // Isolated luxury login screen
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack(['/admin/default/index']);
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Admin Logout (/admin/logout)
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();
        return $this->redirect(['/admin/default/login']);
    }
}
