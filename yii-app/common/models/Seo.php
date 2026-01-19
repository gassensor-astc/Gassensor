<?php
/**
 * generated 21-10-20 20:34:08
 *
 */

namespace common\models;

use common\components\ClassConstNameTrait;
use common\helpers\Tools;
use common\models\base\SeoBase;
use common\models\query\SeoQuery;
use yii\helpers\Url;

/**
 *
 * @property string $typeName
 *
 */
class Seo extends SeoBase
{
    use ClassConstNameTrait;

    const TYPE_PAGE_HOME = 10;
    const TYPE_PAGE_CATALOG = 20;
    const TYPE_PAGE_CONTACT = 21;
    const TYPE_PAGE_VACANCY = 22;
    const TYPE_PAGE_ACCESSORIES = 23;
    const TYPE_PAGE_CONVERTER = 24;

    const TYPE_APPLICATIONS = 25;
    const TYPE_PAGE_REMAINS = 26;
    const TYPE_PAGE_NEWS = 27;

    const TYPE_NEWS = 30;
    const TYPE_MANUFACTURES = 40;
    const TYPE_MANUFACTURE = 50;
    const TYPE_PRODUCT = 60;
    const TYPE_CATALOG_GAZ = 70;
    const TYPE_CATALOG_MANUFACTURES = 80;
    const TYPE_PAGE_PRIVACY = 90;

    const TYPE_PAGE_ABOUT = 100;
    const TYPE_PAGE_GASES = 110;

    /**
     * @param false $isPrependEmpty
     * @return array
     */
    public static function getTypeDropDownData($isPrependEmpty = false)
    {
        $items = self::getClassConstNames('TYPE_', 'seo');

        if ($isPrependEmpty) {
            $items = Tools::array_unshift_assoc($items);
        }

        return $items;
    }

    /**
     * @return mixed
     */
    public function getTypeName()
    {
        return self::getTypeDropDownData()[$this->type];
    }

    /**
     * @return News|null
     */
    public function getNews()
    {
        if (!in_array($this->type, [self::TYPE_NEWS])) {
            return null;
        }

        return News::findOne($this->ref_id);
    }

    /**
     * @return Gaz|null
     */
    public function getGaz()
    {
        if (!in_array($this->type, [self::TYPE_CATALOG_GAZ])) {
            return null;
        }

        return Gaz::findOne($this->ref_id);
    }

    /**
     * @return Product|null
     */
    public function getProduct()
    {
        if (!in_array($this->type, [self::TYPE_PRODUCT])) {
            return null;
        }

        return Product::findOne($this->ref_id);
    }

    /**
     * @param \yii\web\View $view
     * @return $this
     */
    public function registerMetaTags(\yii\web\View $view)
    {
        $view->registerMetaTag(['name' => 'description', 'content' => $this->description,]);
       // $view->registerMetaTag(['name' => 'keywords', 'content' => $this->keyword,]);
        $view->title = $this->title;

        if ($this->url_canonical) {
            $view->registerLinkTag(['rel' => 'canonical', 'href' =>  Url::base(1) . $this->url_canonical]);
        }

        return $this;
    }

    /**
     * @return string|null
     * @throws \Exception
     */
    public function getRefUrl()
    {
        $result = null;

        switch ($this->type) {
            case self::TYPE_PAGE_HOME:
                $result = '/';
                break;
            case self::TYPE_PAGE_CATALOG:
                $result = '/catalog';
                break;
            case self::TYPE_MANUFACTURES:
                $result = '/manufacture';
                break;
            case self::TYPE_APPLICATIONS:
                $result = '/applications';
                break;
            case self::TYPE_PAGE_REMAINS:
                $result = '/remains';
                break;
            case self::TYPE_CATALOG_GAZ:
                $result = "/catalog/{$this->gaz->slug}";
                break;
            case self::TYPE_PRODUCT:
                $product = $this->getProduct();
                if ($gaz = $product->mainGaz) {
                    $result = "/catalog/{$gaz->slug}/{$product->slug}";
                } else {
                    $result = "/product/{$product->slug}";
                }
                break;
            case self::TYPE_PAGE_VACANCY:
                $result = '/page/vacancy';
                break;
            case self::TYPE_PAGE_CONTACT:
                $result = '/page/contacts';
                break;
            case self::TYPE_NEWS:
                if ($news = $this->news) {
                    $result = "/news/{$news->slug}";
                }
                break;
            case self::TYPE_PAGE_NEWS:
                $result = '/news';
                break;
            case self::TYPE_PAGE_PRIVACY:
                $result = '/page/privacy';
                break;
            case self::TYPE_PAGE_GASES:
                $result = '/gases';
                break;
            default:
                throw new \Exception("not implemented  {$this->type}");
        }

        return $result;
    }

    /**
     * @inheritdoc
     * @return SeoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SeoQuery(get_called_class());
    }
}