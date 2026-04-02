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
    public function upload(): ?string
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

        return $filename;
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

        $files = glob("$dir/*", GLOB_NOSORT);
        if (!$files) {
            return [];
        }

        usort($files, static function ($a, $b) {
            $ta = @filemtime($a) ?: 0;
            $tb = @filemtime($b) ?: 0;
            if ($ta === $tb) {
                return strcmp($a, $b);
            }
            return $ta <=> $tb;
        });

        return $files;
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

        $files = $this->getUploadFilenames();
        if (!$files) {
            return null;
        }

        foreach ($files as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                return $file;
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

        $basename = basename((string)$basename);
        $basename = str_replace(["\0", '/', '\\'], '', $basename);
        $basename = trim($basename, '. ');

        $filename = $dir . '/' . $basename;

        if (!is_file($filename)) {
            return false;
        }

        return unlink($filename);
    }

    public function resolveContentFilePath(string $url): ?string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (!$path) {
            return null;
        }

        if (stripos($path, '.pdf') === false) {
            return null;
        }

        $path = str_replace(["\0"], '', $path);
        $path = '/' . ltrim($path, '/');
        $fullPath = \Yii::getAlias('@documentroot') . $path;

        return is_file($fullPath) ? $fullPath : null;
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
