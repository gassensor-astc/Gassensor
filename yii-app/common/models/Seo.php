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
        // Номер страницы в пагинации.
        // Требования:
        // - /catalog/slug   → страница 1 (без суффикса);
        // - /catalog/slug?page=1 → считаем той же первой страницей (без суффикса);
        // - /catalog/slug?page=2 → Страница 2, и т.д.
        // Поэтому:
        // - query-параметр `page` трактуем как человеко-понятный номер (1,2,3…),
        //   но значения 0 и 1 считаем первой страницей;
        // - если query-параметра нет, а номер приходит в параметрах экшена (обычно 1-based,
        //   как в случае /catalog/2), используем его как есть.
        $request = \Yii::$app->request;
        $page = 1;
        $pageParam = $request->get('page', null);
        if ($pageParam !== null && $pageParam !== '') {
            $p = (int)$pageParam;
            $page = $p <= 1 ? 1 : $p;
        } elseif (isset(\Yii::$app->controller, \Yii::$app->controller->actionParams['page'])) {
            $p = (int)\Yii::$app->controller->actionParams['page'];
            if ($p >= 1) {
                $page = $p;
            }
        }
        if ($page < 1) {
            $page = 1;
        }
        $pageSuffixTitle = $page > 1 ? '. Страница ' . $page : '';
        $pageSuffixDesc = $page > 1 ? '. Страница ' . $page . '.' : '';

        $title = rtrim((string) $this->title, " \t\n.") . $pageSuffixTitle;
        $description = rtrim((string) ($this->description ?? ''), " \t\n.") . $pageSuffixDesc;
        if ($pageSuffixDesc === '' && $description !== '' && !preg_match('/[.!?]$/u', $description)) {
            $description .= '.';
        }

        if ($description !== '') {
            $view->registerMetaTag(['name' => 'description', 'content' => $description]);
        }
       // $view->registerMetaTag(['name' => 'keywords', 'content' => $this->keyword,]);
        if ($title !== '') {
            $view->title = $title;
        }

        if ($this->url_canonical) {
            $path = preg_replace('/\?.*$/s', '', trim($this->url_canonical));
            if (preg_match('#^https?://[^/]+(.*)$#', $path, $m)) {
                $path = $m[1] !== '' ? $m[1] : '/';
            }
            $href = Url::base(1) . $path;
            $href = preg_replace('/\?.*$/s', '', $href);
            $view->registerLinkTag(['rel' => 'canonical', 'href' => $href]);
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