<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * CartItem — session-based shopping cart.
 *
 * @property int         $id
 * @property string      $session_id
 * @property int         $product_id
 * @property int         $quantity
 * @property string|null $added_at
 *
 * @property Product     $product
 */
class CartItem extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%cart_items}}';
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /** Get active session ID, ensuring the session is open. */
    public static function getSessionId(): string
    {
        $session = \Yii::$app->session;
        if (!$session->isActive) {
            $session->open();
        }
        return (string) $session->getId();
    }

    /** Return all cart items for a session with their products eager-loaded. */
    public static function forSession(string $sessionId = ''): array
    {
        if ($sessionId === '') {
            $sessionId = static::getSessionId();
        }
        return static::find()
            ->where(['session_id' => $sessionId])
            ->with('product')
            ->all();
    }

    /** Add 1 of $productId to cart, or increment if already present. */
    public static function addToCart(string $sessionId, int $productId, int $qty = 1): void
    {
        if ($sessionId === '') {
            $sessionId = static::getSessionId();
        }
        $item = static::find()->where(['session_id' => $sessionId, 'product_id' => $productId])->one();
        if ($item) {
            $item->quantity += $qty;
        } else {
            $item = new static([
                'session_id' => $sessionId,
                'product_id' => $productId,
                'quantity'   => $qty,
                'added_at'   => date('Y-m-d H:i:s'),
            ]);
        }
        $item->save(false);
    }

    /** Remove one item completely from the cart. */
    public static function removeFromCart(string $sessionId, int $productId): void
    {
        if ($sessionId === '') {
            $sessionId = static::getSessionId();
        }
        static::deleteAll(['session_id' => $sessionId, 'product_id' => $productId]);
    }

    /** Update quantity (delete if <= 0). */
    public static function updateQty(string $sessionId, int $productId, int $qty): void
    {
        if ($sessionId === '') {
            $sessionId = static::getSessionId();
        }
        if ($qty <= 0) {
            static::removeFromCart($sessionId, $productId);
            return;
        }
        static::updateAll(['quantity' => $qty], ['session_id' => $sessionId, 'product_id' => $productId]);
    }

    /** Total number of items in cart (sum of quantities). */
    public static function countForSession(string $sessionId = ''): int
    {
        if ($sessionId === '') {
            $sessionId = static::getSessionId();
        }
        return (int) static::find()
            ->where(['session_id' => $sessionId])
            ->sum('quantity');
    }
}
