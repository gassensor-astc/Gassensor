<?php

namespace common\models;

use Yii;

class Slider extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'slider';
    }

    public function rules()
    {
        $rules = [];

        $rules[] = ['picture', 'file', 'extensions' => 'png, jpg, gif'];
        $rules[] = ['page', 'string'];
        $rules[] = ['description', 'string'];

        return $rules;
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'picture' => Yii::t('app', 'picture'),
            'page' => Yii::t('app', 'page'),
            'description' => Yii::t('app', 'description'),
        ];
    }

    public function getPictPath($name)
    {
        $name = time() . '_' . $name;
        return [\Yii::getAlias('@documentroot' . '/i/slides/'. $name), '/i/slides/'. $name];
    }
}