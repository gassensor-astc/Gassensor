<?php
/* @var $this \yii\web\View */
/* @var $content string */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use frontend\assets\LegacyAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);
LegacyAsset::register($this);

?>
<?php $this->beginPage() ?>
    <!doctype html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta http-equiv="Cache-Control" content="no-cache">
        <?= $this->render('google') ?>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>

        <?= Html::csrfMetaTags() ?>

        <?php
        // Номер страницы для заголовка.
        // Требования:
        // - /... и /...?page=1 считаются первой страницей (без суффикса);
        // - /...?page=2 → Страница 2 и т.д.
        $req = Yii::$app->request;
        $page = 1;
        $pageParam = $req->get('page', null);
        if ($pageParam !== null && $pageParam !== '') {
            $p = (int)$pageParam;
            $page = $p <= 1 ? 1 : $p;
        } elseif (isset(Yii::$app->controller, Yii::$app->controller->actionParams['page'])) {
            $p = (int)Yii::$app->controller->actionParams['page'];
            if ($p >= 1) {
                $page = $p;
            }
        }
        if ($page < 1) {
            $page = 1;
        }
        // Плавающая точка: в БД может быть точка в конце или нет — не дублируем.
        // Суффикс уже может быть добавлен в Seo::registerMetaTags().
        if ($page > 1 && !preg_match('/\. Страница \d+\.?$/u', $this->title)) {
            $titleText = rtrim($this->title, " \t\n.") . '. Страница ' . $page;
        } else {
            $titleText = $this->title;
        }
        ?>
        <title><?= defined('TITLE_PREFIX') ? TITLE_PREFIX : '' ?><?= Html::encode($titleText) ?></title>
        <?= $this->render('json-ld') ?>


        <?php $indent = '        '; ?>
        <?= $indent ?><link rel="shortcut icon" href="/i/favicon.png"/>

        <?php

        /* Canonical: при наличии page/sort — только путь, без query (filter, page и т.д.).
         * Другие canonical задаются через Seo::url_canonical в registerMetaTags();
         * шаблоны URL по типам страниц — в common\models\Seo::getRefUrl(). */
        /* @var $request \yii\web\Request */
        $request = Yii::$app->request;
        if (!empty($this->params['canonicalUrl'])) {
            echo $indent . "<link rel=\"canonical\" href=\"" . Html::encode($this->params['canonicalUrl']) . "\" />" . "\n";
        } elseif (!($this->params['customCanonical'] ?? false) && ($request->get('page') || $request->get('sort'))) {
            $path = trim($request->pathInfo, '/');
            $canonicalHref = 'https://gassensor.ru/' . ($path !== '' ? $path : '');
            echo $indent . "<link rel=\"canonical\" href=\"" . Html::encode($canonicalHref) . "\" />" . "\n";
        }

        ?>

        <?php
            ob_start();
            $this->head();
            $head = trim((string)ob_get_clean());
            if ($head !== '') {
                $head = preg_replace('/^/m', '        ', $head);
                echo $head . "\n";
            }
        ?>

<?php if (!defined('LOCAL_SITE')): ?>
<script src="//code.jivo.ru/widget/T5tUejTiZb" async></script>
<?php else: ?>
<!--  LOCAL_SITE -->
<?php endif; ?>

    </head>
    <body class="d-flex flex-column min-vh-100">
    <?php $this->beginBody() ?>
<style>.site main { min-height: 0; flex: 1 1 auto; }.site-footer { margin-top: auto; flex-shrink: 0; }</style>
    <div class="site flex-grow-1 d-flex flex-column">

        <?= $this->render('header') ?>

        <div class="m-3">
            <?= Breadcrumbs::widget([
                'homeLink' => ['label' => 'Главная', 'url' => '/',],
                'links' => $this->params['breadcrumbs'] ?? [],
            ]) ?>
        </div>

        <main class="p-2 flex-grow-1" style="min-height: 0;">

            <?= Alert::widget() ?>
            <?= $content ?>

        </main>

        <?= $this->render('footer') ?>

    </div>
    <?php $this->endBody() ?>

    <?php if (!defined('LOCAL_SITE')): ?>

        <?= $this->render('yandex-metrika') ?>

        <?= $this->render('googletagmanager') ?>

    <?php else: ?>
    <!--  LOCAL_SITE -->
    <?php endif; ?>

    <!-- START Cookie-Alert -->
    <div id="cookie_note">
        <p>Данный веб-сайт использует cookie-файлы в целях предоставления вам лучшего пользовательского опыта на нашем сайте. Продолжая использовать данный сайт, вы соглашаетесь с использованием нами cookie-файлов.
            Для получения дополнительной информации см. <a href="<?=Url::to(['/page/privacy']) ?>" target="_blank">Политика конфиденциальности.</a></p>
        <div class="d-flex justify-content-center">
            <button class="button cookie_accept btn btn-primary btn-sm">Я согласен</button>
        </div>
    </div>
    <!-- END Cookie-Alert -->

    </body>
    </html>
<?php $this->endPage() ?>
