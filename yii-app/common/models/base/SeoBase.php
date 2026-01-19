<?php
/**
 * generated 2022-01-27 19:31:07
 */

namespace common\models\base;

use Yii;


/**
 * This is the model class for table "seo".
 *
 * @property integer $id
 * @property integer $ref_id
 * @property integer $type
 * @property string $h1
 * @property string $title
 * @property string $keyword
 * @property string $description
 * @property string $url_canonical
 * @property string $opisanie
 */
class SeoBase extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ref_id', 'type'], 'integer'],
            [['type'], 'required'],
            [['keyword', 'description', 'opisanie'], 'string'],
            [['h1', 'title', 'url_canonical'], 'string', 'max' => 255],
            [['ref_id', 'type'], 'unique', 'targetAttribute' => ['ref_id', 'type']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'ref_id' => Yii::t('app', 'Ref ID'),
            'type' => Yii::t('app', 'Type'),
            'h1' => Yii::t('app', 'H1'),
            'title' => Yii::t('app', 'Title'),
            'keyword' => Yii::t('app', 'Keyword'),
            'description' => Yii::t('app', 'Description'),
            'url_canonical' => Yii::t('app', 'Url Canonical'),
            'opisanie' => Yii::t('app', 'Описание товара'),
        ];
    }

    /**
     * @inheritdoc
     * @return SeoBaseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeoBaseQuery(get_called_class());
    }
}
