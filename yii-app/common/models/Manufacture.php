<?php

namespace common\models;

use common\helpers\Tools;
use common\models\base\ManufactureBase;
use common\models\query\ManufactureQuery;
use common\models\search\ProductSearch;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use Yii;

/**
 * @property Seo $seo
 */
class Manufacture extends ManufactureBase
{
    /**
     * @var UploadedFile
     */
    public $uploadPict;

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['uploadPict', 'file', 'extensions' => 'png, jpg, gif'];
        return $rules;
    }

    /**
     * {@inheritDoc}
     * @see \yii\base\Component::behaviors()
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
            'sluggable' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'ensureUnique' => true,
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
            ->andOnCondition(['type' => Seo::TYPE_MANUFACTURE]);
    }

    /**
     * @param false $isPrependEmpty
     * @return array
     */
    public static function getDropDownData($isPrependEmpty = false)
    {
        $rows = self::find()->cache(30)->orderBy('title')->all();
        $rows = ArrayHelper::map($rows, 'id', 'title');

        if ($isPrependEmpty) {
            $rows = Tools::array_unshift_assoc($rows);
        }

        return $rows;
    }

    /**
     * @return false|string
     */
    public static function getUploadDir()
    {
        return Yii::getAlias('@documentroot' . self::getUploadBaseUrl());
    }

    public static function getUploadBaseUrl()
    {
        return '/i/manufactures';
    }

    /**
     * @return string
     */
    public function getLogoUrl()
    {
        return self::getUploadBaseUrl() . '/' . $this->id . '.' . $this->logo;
    }

    /**
     * @inheritdoc
     * @return ManufactureQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ManufactureQuery(get_called_class());
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function uploadPict()
    {
        $ext = strtolower($this->uploadPict->extension);
        $this->logo = $ext;

        $filename = $this->getUploadDir() . "/{$this->id}.{$this->logo}";

        if (is_file($filename) && !unlink($filename)) {
            throw new \Exception('unlink fail');
        }

        if ($result = $this->uploadPict->saveAs($filename)) {
            $this->save(false);//save attr logo
        }

        return $result;
    }

    /**
     * @param ProductSearch $searchModel
     * @return array
     */
    public static function manufactureOption(ProductSearch $searchModel): array
    {
        $rows = self::find()->orderBy('title')->asArray()->all();

        $q = self::find()->select(['manufacture.id']);
        $q->leftJoin('product', 'product.manufacture_id = manufacture.id');

        if ($searchModel->gaz_id) {
            $q->leftJoin('product_gaz', 'product_gaz.product_id = product.id');
            $q->andWhere(['product_gaz.gaz_id' => $searchModel->gaz_id]);
        }

        $options = ['' => ['label' => ' ']];

        foreach ($rows as $row) {
            if (in_array($row['id'], $q->column()) === false) $options[$row['id']] = ['disabled' => true];
        }

        return $options;
    }

    /**
     * @param ProductSearch $searchModel
     * @return array|null
     */
    public static function findAvailableManufacturesIds(ProductSearch $searchModel): ?array
    {
        $params = [
            'ProductSearch' => array_filter(ArrayHelper::merge(
                (array)(Yii::$app->request->queryParams['ProductSearch'] ?? []),
                $searchModel->getAttributes(),
                [
                    'gaz_id' => $searchModel->gaz_id,
                    'gaz_group_id' => $searchModel->gaz_group_id,
                    'selectedSignalTypes' => $searchModel->selectedSignalTypes,
                    'response_time_from' => $searchModel->response_time_from,
                    'response_time_to' => $searchModel->response_time_to,
                    'life_time_to' => $searchModel->life_time_to,
                    'resolution_from' => $searchModel->resolution_from,
                    'resolution_to' => $searchModel->resolution_to,
                ]
            ), static function ($value) {
                return $value !== null && $value !== '' && $value !== [];
            }),
        ];

        if ($searchModel->gaz_id) {
            unset($params['ProductSearch']['manufacture_id']);

            $ids = (new ProductSearch())->searchFront($params)->query->select(['product.id'])->column();

            if ($ids) {
                $manufactureAvailableIds = self::find()
                    ->select(['manufacture.id'])
                    ->leftJoin('product', 'product.manufacture_id = manufacture.id')
                    ->where(['in', 'product.id', $ids])
                    ->groupBy(['manufacture.id'])
                    ->column();
            } else {
                $manufactureAvailableIds = null;
            }
        } else {
            $manufactureAvailableIds = self::find()->select(['id'])->column();
        }

        return $manufactureAvailableIds;
    }

    /**
     * @param ProductSearch $model
     * @return array
     */
    public static function manufactureOption2(ProductSearch $searchModel): array
    {
        $availableIds = self::findAvailableManufacturesIds($searchModel);
        $option = ['' => ['label' => 'Производитель']];

        if ($availableIds) {
            foreach (self::getDropDownData(true) as $id => $label) {
                if (!in_array($id, $availableIds) && !empty($id)) {
                    $option[$id] = ['disabled' => true];
                }
            }
        }

        return $option;
    }
}

