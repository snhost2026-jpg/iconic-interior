<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Product;
use app\models\ProductSearch;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ProductController extends Controller
{
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'toggle' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $counts = [
            'total' => (int) Product::find()->count(),
            'active' => (int) Product::find()->where(['is_active' => 1])->count(),
            'featured' => (int) Product::find()->where(['is_featured' => 1])->count(),
            'in_stock' => (int) Product::find()->where(['is_in_stock' => 1])->count(),
            'out_of_stock' => (int) Product::find()->where(['or', ['is_in_stock' => 0], ['<=', 'stock', 0]])->count(),
        ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'counts' => $counts,
        ]);
    }

    public function actionCreate()
    {
        $model = new Product([
            'is_active' => 1,
            'is_in_stock' => 1,
            'is_featured' => 0,
            'category' => 'Lighting',
            'stock' => 10,
            'sort_order' => 0,
        ]);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->saveWithUploads()) {
            Yii::$app->session->setFlash('success', 'Product "' . $model->name . '" created successfully.');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->saveWithUploads()) {
            Yii::$app->session->setFlash('success', 'Product "' . $model->name . '" updated successfully.');
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionToggle(int $id, string $attribute): Response
    {
        $model = $this->findModel($id);
        $allowed = ['is_active', 'is_featured', 'is_in_stock'];

        if (in_array($attribute, $allowed, true)) {
            $model->$attribute = $model->$attribute ? 0 : 1;
            if ($attribute === 'is_in_stock' && $model->is_in_stock && $model->stock <= 0) {
                $model->stock = 1;
            } elseif ($attribute === 'is_in_stock' && !$model->is_in_stock) {
                $model->stock = 0;
            }
            $model->save(false);
            $label = $model->attributeLabels()[$attribute] ?? $attribute;
            Yii::$app->session->setFlash('success', "Product #{$id} {$label} updated.");
        }

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);
        $name = $model->name;
        $model->delete();
        Yii::$app->session->setFlash('success', 'Product "' . $name . '" has been removed.');
        return $this->redirect(['index']);
    }

    protected function findModel(int $id): Product
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested product does not exist.');
    }
}
