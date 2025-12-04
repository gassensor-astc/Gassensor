<?php
/**
 * generated 21-10-11 11:34:04
 *
 */

namespace common\models;

use common\models\base\NewsBase;
use common\models\query\NewsQuery;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Inflector;
use yii\web\UploadedFile;

/**
 *
 * @property Seo $seo
 *
 */
class News extends NewsBase
{
    /**
     * @var UploadedFile
     */
    public $uploadFile;

    public function rules()
    {
        $rules = parent::rules();

        $rules[] = ['uploadFile', 'file', 'extensions' => 'png, jpg, gif, pdf'];
        //$rules[] = [['lenght'], 'string', 'max' => 255];

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
            /*'sluggable' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                //'ensureUnique' => true,
                'immutable' => true,
            ],*/
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeo()
    {
        return $this->hasOne(Seo::class, ['ref_id' => 'id'])
            ->andOnCondition(['type' => Seo::TYPE_NEWS]);
    }

    /**
     * @throws \Exception
     */
    public function upload()
    {
        if (!$dir = $this->getUploadDir()) {
            throw new \Exception('invalid upload dir');
        }

        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $baseName = Inflector::slug($this->uploadFile->baseName);
        $ext = strtolower($this->uploadFile->extension);

        $filename = "$dir/$baseName.$ext";

        $i = 0;
        while (is_file($filename)) {
            $i++;
            $filename = "$dir/$baseName-$i.$ext";
        }

        $this->uploadFile->saveAs($filename);

    }

    /**
     * @return false|string
     */
    public static function getUploadBaseDir()
    {
        return \Yii::getAlias('@documentroot' . self::getUploadBaseUrl());
    }

    /**
     * @return string
     */
    public static function getUploadBaseUrl()
    {
        return '/upload/news';
    }

    /**
     * @return string|null
     */
    public function getUploadDir()
    {
        return $this->id ? self::getUploadBaseDir() . '/' . $this->id : null;
    }

    /**
     * @return string|null
     */
    public function getUploadUrl()
    {
        return $this->id ? self::getUploadBaseUrl() . '/' . $this->id : null;
    }

    /**
     * @return array|false
     */
    public function getUploadFilenames()
    {
        if (!$dir = $this->getUploadDir()) {
            return [];
        }

        return glob("$dir/*");
    }

    /**
     * @return string|void
     */
    public function getPictUrl()
    {
        if (!$filename = $this->getPictFilename()) {
            return;
        }

        return $this->getUploadUrl() . '/' . basename($filename);
    }

    /**
     * @return mixed|void|null
     */
    public function getPictFilename()
    {
        if (!$dir = $this->getUploadDir()) {
            return null;
        }

        foreach (['jpg', 'png', 'webp', 'gif',] as $ext) {
            if ($files = glob("$dir/*.$ext")) {
                return $files[0];
            }
        }
    }

    /**
     * @param $basename
     * @return bool
     * @throws \Exception
     */
    public function delFile($basename)
    {
        $dir = $this->getUploadDir();

        $basename = str_replace([' ', '/', '\\'], '', $basename);
        $basename = trim($basename, '. ');

        $filename = $dir . '/' . $basename;

        if (!is_file($filename)) {
            throw new \Exception("file not found");
        }

        return unlink($filename);
    }

    /**
     * @inheritdoc
     * @return NewsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NewsQuery(get_called_class());
    }
}

