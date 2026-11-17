<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Filter and search model for Product in Admin.
 */
class ProductSearch extends Product
{
    public $q;

    public function rules(): array
    {
        return [
            [['id', 'stock', 'is_in_stock', 'is_featured', 'is_active', 'sort_order'], 'integer'],
            [['name', 'category', 'sku', 'q'], 'string', 'max' => 200],
            [['price', 'original_price'], 'number'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function behaviors(): array
    {
        return [];
    }

    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'q' => 'Search products (Name, SKU, Description)',
        ]);
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Product::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'sort_order' => SORT_ASC,
                    'id' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'category' => $this->category,
            'is_in_stock' => $this->is_in_stock,
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
        ]);

        if ($this->q) {
            $query->andWhere(['or',
                ['like', 'name', $this->q],
                ['like', 'sku', $this->q],
                ['like', 'short_description', $this->q],
                ['like', 'category', $this->q],
            ]);
        }

        return $dataProvider;
    }
}
