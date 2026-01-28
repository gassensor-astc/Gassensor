<?php
/**
 * generated 21-10-19 14:22:52
 *
 */

namespace common\models;

use common\models\base\ProductGazBase;
use common\models\query\ProductGazQuery;

class ProductGaz extends ProductGazBase
{
    public $is_main_4;

    /**
     * @inheritdoc
     * @return ProductGazQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductGazQuery(get_called_class());
    }
}