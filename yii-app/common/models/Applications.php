<?php

namespace common\models;

use common\models\base\ApplicationsBase;
use common\models\query\ApplicationsQuery;
use yii\behaviors\SluggableBehavior;

/**
 *
 * @property Seo $seo
 *
 */
class Applications extends ApplicationsBase
{

  //  public $description;
    public function rules()
    {
        $rules = parent::rules();

        return $rules;
    }

    /**
     * {@inheritDoc}
     * @see \yii\base\Component::behaviors()
     */
    public function behaviors()
    {
        return [
            'sluggable' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'immutable' => true,
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeo()
    {
        return $this->hasOne(Seo::class, ['ref_id' => 'id'])
            ->andOnCondition(['type' => Seo::TYPE_APPLICATIONS]);
    }

    public function getPictPath($name)
    {
        $name = time() . '_' . $name;
        return [\Yii::getAlias('@documentroot' . '/upload/articles/'. $name), '/upload/articles/'. $name];
    }

    /**
     * @inheritdoc
     * @return ApplicationsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ApplicationsQuery(get_called_class());
    }
}

