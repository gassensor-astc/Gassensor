<?php

namespace common\helpers;

use common\models\{Applications,Gaz,Manufacture,News,Page,Product,Seo};
use yii\base\BaseObject;

class Search extends BaseObject
{
    public $q;

    public function searchSeo()
    {
        $q = $this->q;
        return Seo::find()
            ->orWhere(['like', 'h1', $q])
            ->orWhere(['like', 'title', $q]);
    }

    public function searchProduct()
    {
        $q = $this->q;
        
        // Получаем ID товаров, у которых есть совпадение в SEO
        $seoProductIds = Seo::find()
            ->select('ref_id')
            ->where(['type' => Seo::TYPE_PRODUCT])
            ->andWhere([
                'or',
                ['like', 'title', $q],
                ['like', 'description', $q],
                ['like', 'h1', $q],
            ])
            ->column();
        
        return Product::find()
            ->orWhere(['like', 'name', $q])
            ->orWhere(['in', 'id', $seoProductIds]);
    }

    public function searchGaz()
    {
        $q = $this->q;
        return Gaz::find()
            ->orWhere(['like', 'title', $q])
            ->orWhere(['like', 'description', $q]);
    }

    public function searchManufacture()
    {
        $q = $this->q;
        return Manufacture::find()
            ->orWhere(['like', 'title', $q])
            ->orWhere(['like', 'short_description', $q])
            ->orWhere(['like', 'description', $q]);
    }

    public function searchNews()
    {
        $q = $this->q;
        
        // Получаем ID новостей, у которых есть совпадение в SEO
        $seoNewsIds = Seo::find()
            ->select('ref_id')
            ->where(['type' => Seo::TYPE_NEWS])
            ->andWhere([
                'or',
                ['like', 'title', $q],
                ['like', 'description', $q],
                ['like', 'h1', $q],
            ])
            ->column();
        
        return News::find()
            ->orWhere(['like', 'title', $q])
            ->orWhere(['like', 'content', $q])
            ->orWhere(['in', 'id', $seoNewsIds]);
    }

    public function searchApplications()
    {
        $q = $this->q;
        
        // Получаем ID статей, у которых есть совпадение в SEO
        $seoAppIds = Seo::find()
            ->select('ref_id')
            ->where(['type' => Seo::TYPE_APPLICATIONS])
            ->andWhere([
                'or',
                ['like', 'title', $q],
                ['like', 'description', $q],
                ['like', 'h1', $q],
            ])
            ->column();
        
        return Applications::find()
            ->orWhere(['like', 'title', $q])
            ->orWhere(['like', 'content', $q])
            ->orWhere(['in', 'id', $seoAppIds]);
    }

    public function searchPage()
    {
        $q = $this->q;
        return Page::find()->orWhere(['like', 'content', $q]);
    }

}
