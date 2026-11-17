<?php

namespace app\controllers;

use Yii;
use app\models\CartItem;
use app\models\Product;
use app\models\SiteSettings;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ProductController — public-facing shop for ICONIC INTERIORS.
 *
 * Routes:
 *   GET  /shop                  => index (all products, filterable)
 *   GET  /shop/<slug>           => view (product detail)
 *   POST /shop/cart/add         => addToCart (JSON)
 *   POST /shop/cart/remove      => removeFromCart (JSON)
 *   POST /shop/cart/update      => updateCart (JSON)
 *   GET  /shop/cart             => cartView
 *   POST /shop/checkout         => checkout (inquiry form)
 */
class ProductController extends Controller
{
    /* ------------------------------------------------------------------ *
     *  SHOP — Listing
     * ------------------------------------------------------------------ */
    public function actionIndex(?string $category = null, ?string $sort = null)
    {
        $settings = SiteSettings::get();
        $query    = Product::active();

        if ($category && in_array($category, Product::CATEGORIES, true)) {
            $query->andWhere(['category' => $category]);
        }

        switch ($sort) {
            case 'price_asc':
                $query->orderBy(['price' => SORT_ASC]);
                break;
            case 'price_desc':
                $query->orderBy(['price' => SORT_DESC]);
                break;
            case 'new':
                $query->orderBy(['id' => SORT_DESC]);
                break;
            default:
                $query->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC]);
        }

        $products   = $query->all();
        $categories = Product::CATEGORIES;
        $cartCount  = CartItem::countForSession();

        $this->view->title = 'Luxury Interior Products — ICONIC Shop';
        $this->view->params['meta_description'] = 'Curate your home with ICONIC INTERIORS — handcrafted lighting, bespoke furniture, artisan decor, luxury textiles and original wall art.';

        return $this->render('index', compact('products', 'categories', 'category', 'sort', 'settings', 'cartCount'));
    }

    /* ------------------------------------------------------------------ *
     *  SHOP — Product Detail
     * ------------------------------------------------------------------ */
    public function actionView(string $slug)
    {
        $product  = Product::find()->where(['slug' => $slug, 'is_active' => 1])->one();
        if (!$product) {
            throw new NotFoundHttpException('Product not found.');
        }
        $settings  = SiteSettings::get();
        $related   = Product::find()
            ->where(['is_active' => 1, 'category' => $product->category])
            ->andWhere(['<>', 'id', $product->id])
            ->orderBy(['sort_order' => SORT_ASC])
            ->limit(4)
            ->all();
        $cartCount = CartItem::countForSession();

        $this->view->title = $product->meta_title ?: $product->name . ' — ICONIC Shop';
        $this->view->params['meta_description'] = $product->meta_description ?: $product->short_description;

        return $this->render('view', compact('product', 'related', 'settings', 'cartCount'));
    }

    /* ------------------------------------------------------------------ *
     *  CART — Add
     * ------------------------------------------------------------------ */
    public function actionAddToCart(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $req = Yii::$app->request;
        if (!$req->isPost) {
            return ['success' => false, 'message' => 'Invalid request'];
        }
        $productId = (int) $req->post('product_id');
        $qty       = max(1, (int) $req->post('qty', 1));
        $product   = Product::find()->where(['id' => $productId, 'is_active' => 1, 'is_in_stock' => 1])->one();
        if (!$product) {
            return ['success' => false, 'message' => 'Product not available.'];
        }
        $sessionId = CartItem::getSessionId();
        CartItem::addToCart($sessionId, $productId, $qty);
        return [
            'success'    => true,
            'message'    => 'Added to cart!',
            'cart_count' => CartItem::countForSession($sessionId),
        ];
    }

    /* ------------------------------------------------------------------ *
     *  CART — Remove
     * ------------------------------------------------------------------ */
    public function actionRemoveFromCart(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!Yii::$app->request->isPost) {
            return ['success' => false];
        }
        $sessionId = CartItem::getSessionId();
        CartItem::removeFromCart(
            $sessionId,
            (int) Yii::$app->request->post('product_id')
        );
        return [
            'success'    => true,
            'cart_count' => CartItem::countForSession($sessionId),
        ];
    }

    /* ------------------------------------------------------------------ *
     *  CART — Update Quantity
     * ------------------------------------------------------------------ */
    public function actionUpdateCart(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!Yii::$app->request->isPost) {
            return ['success' => false];
        }
        $sessionId = CartItem::getSessionId();
        CartItem::updateQty(
            $sessionId,
            (int) Yii::$app->request->post('product_id'),
            (int) Yii::$app->request->post('qty')
        );
        return [
            'success'    => true,
            'cart_count' => CartItem::countForSession($sessionId),
        ];
    }

    /* ------------------------------------------------------------------ *
     *  CART — View
     * ------------------------------------------------------------------ */
    public function actionCart()
    {
        $sessionId = CartItem::getSessionId();
        $items     = CartItem::forSession($sessionId);
        $settings  = SiteSettings::get();
        $cartCount = CartItem::countForSession($sessionId);
        $total     = 0;
        foreach ($items as $item) {
            if ($item->product) {
                $total += $item->product->price * $item->quantity;
            }
        }
        $this->view->title = 'Your Cart — ICONIC Shop';
        return $this->render('cart', compact('items', 'total', 'settings', 'cartCount'));
    }
}
