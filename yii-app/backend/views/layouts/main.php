<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\helpers\Url;

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="utf-8">
    <?php $this->registerCsrfMetaTags() ?>
    <title>Панель управления | <?= Html::encode($this->title) ?></title>
    <?php $this->registerCsrfMetaTags() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
    <script type="text/javascript">
        var SITE_URL = "/";
    </script>

    <?php $this->head() ?>
</head>

<!--

TABLE OF CONTENTS.

Use search to find needed section.

===================================================================

|  01. #CSS Links                |  all CSS links and file paths  |
|  02. #FAVICONS                 |  Favicon links and file paths  |
|  03. #GOOGLE FONT              |  Google font link              |
|  04. #APP SCREEN / ICONS       |  app icons, screen backdrops   |
|  05. #BODY                     |  body tag                      |
|  06. #HEADER                   |  header tag                    |
|  07. #PROJECTS                 |  project lists                 |
|  08. #TOGGLE LAYOUT BUTTONS    |  layout buttons and actions    |
|  09. #MOBILE                   |  mobile view dropdown          |
|  10. #SEARCH                   |  search field                  |
|  11. #NAVIGATION               |  left panel & navigation       |
|  12. #MAIN PANEL               |  main panel                    |
|  13. #MAIN CONTENT             |  content holder                |
|  14. #PAGE FOOTER              |  page footer                   |
|  15. #SHORTCUT AREA            |  dropdown shortcuts area       |
|  16. #PLUGINS                  |  all scripts and plugins       |

===================================================================

-->

<!-- #BODY -->
<!-- Possible Classes

    * 'smart-style-{SKIN#}'
    * 'smart-rtl'         - Switch theme mode to RTL
    * 'menu-on-top'       - Switch to top navigation (no DOM change required)
    * 'no-menu'			  - Hides the menu completely
    * 'hidden-menu'       - Hides the main menu but still accessable by hovering over left edge
    * 'fixed-header'      - Fixes the header
    * 'fixed-navigation'  - Fixes the main menu
    * 'fixed-ribbon'      - Fixes breadcrumb
    * 'fixed-page-footer' - Fixes footer
    * 'container'         - boxed layout mode (non-responsive: will not work with fixed-navigation & fixed-ribbon)
-->
<body class="smart-style-0 fixed-header">

<?php $this->beginBody() ?>

<!-- #HEADER -->
<header id="header">
    <div id="logo-group">

        <!-- PLACE YOUR LOGO HERE -->
        <span id="logo">  </span>
        <!-- END LOGO PLACEHOLDER -->
    </div>

    <!-- #TOGGLE LAYOUT BUTTONS -->
    <!-- pulled right: nav area -->
    <div class="pull-right">

        <div id="fullscreen" class="btn-header transparent pull-right">
            <span>
                <a href="<?= Url::to(['site/logout']) ?>" data-action="userLogout" title="Выйти"><i
                            class="fa fa-sign-out fa-lg"></i></a>
            </span>
        </div>
        <!-- end fullscreen button -->


        <!-- collapse menu button -->
        <div id="hide-menu" class="btn-header pull-right">
            <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Свернуть меню"><i
                            class="fa fa-reorder"></i></a> </span>
        </div>
        <!-- end collapse menu -->

        <!-- fullscreen button -->
        <div id="fullscreen" class="btn-header transparent pull-right">
            <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Развернуть на весь экран"><i
                            class="fa fa-arrows-alt"></i></a>
            </span>
        </div>
        <!-- end fullscreen button -->

    </div>
    <!-- end pulled right: nav area -->

</header>
<!-- END HEADER -->

<!-- #NAVIGATION -->
<!-- Left panel : Navigation area -->
<!-- Note: This width of the aside area can be adjusted through LESS/SASS variables -->
<aside id="left-panel">

    <!-- User info -->
    <div class="login-info">
        <span> <!-- User image size is adjusted inside CSS, it should stay as is -->

            <a id="show-shortcut" data-action="toggleShortcut">
                <span>
                    <?= Yii::$app->user->identity->username ?>
                </span>
            </a>
        </span>
    </div>
    <!-- end user info -->

    <!-- NAVIGATION : This navigation is also responsive

    To make this navigation dynamic please make sure to link the node
    (the reference to the nav > ul) after page load. Or the navigation
    will not initialize.
    -->
    <nav>
        <!--
        NOTE: Notice the gaps after each icon usage <i></i>..
        Please note that these links work a bit different than
        traditional href="" links. See documentation for details.
        -->

        <ul>
            <li class="nav-item">
                <a href="<?= Url::to(['url/index', 'sort' => '-id']) ?>"><i class="fa fa-fw fa-chain"></i> <span
                            class="menu-item-parent"> URL</span></a>
            </li>
            <li class="nav-item">
                <a href="<?= Url::to(['redirect/index', 'sort' => '-id']) ?>"><i class="fa fa-fw fa-mail-forward"></i>
                    <span
                            class="menu-item-parent"> Редиректы</span></a>
            </li>
            <li class="nav-item">
                <a href="<?= Url::to(['seo/index', 'sort' => '-id']) ?>"><i class="fa fa-fw fa-globe"></i> <span
                            class="menu-item-parent"> SEO</span></a>
            </li>
            <li class="nav-item">
                <a href="<?= Url::to(['page/index', 'sort' => '-id']) ?>"><i class="fa fa-fw fa-file-text"></i> <span
                            class="menu-item-parent"> Страницы</span></a>
            </li>
            <li class="nav-item">
                <a href="<?= Url::to(['slider/index', 'sort' => '-id']) ?>"><i class="fa fa-fw fa-clone"></i> <span
                        class="menu-item-parent"> Слайдер</span></a>
            </li>
            <li class="nav-item">
                <a href="<?= Url::to(['news/index', 'sort' => '-id']) ?>"><i class="fa fa-fw fa-list-ul"></i> <span
                            class="menu-item-parent"> <?= Yii::t('app', 'News') ?></span></a>
            </li>
            <li class="nav-item">
                <a href="<?= Url::to(['gaz/index', 'sort' => '-id']) ?>"><i class="fa fa-fw fa-list-ul"></i> <span
                            class="menu-item-parent"> Газы</span></a>
            </li>

            <li class="nav-item">
                <a href="<?= Url::to(['measurement-type/index']) ?> "><i class="fa fa-fw fa-list-ul"></i> <span
                            class="menu-item-parent"> <?= Yii::t('app', 'Measurement Types') ?></span></a>
            </li>
            <li class="nav-item">
                <a href="<?= Url::to(['product/index', 'sort' => '-id']) ?>"><i class="fa fa-fw fa-list-ul"></i> <span
                            class="menu-item-parent"> <?= Yii::t('app', 'Products') ?></span></a>
            </li>
            <li class="nav-item">
                <a href="<?= Url::to(['manufacture/index', 'sort' => 'weight,-id']) ?>"><i
                            class="fa fa-fw fa-list-ul"></i> <span
                            class="menu-item-parent"> <?= Yii::t('app', 'Manufactures') ?></span></a>
            </li>
            <li class="nav-item">
                <a href="<?= Url::to(['applications/index']) ?>"><i class="fa fa-fw fa-list-ul"></i> <span
                            class="menu-item-parent"> <?= Yii::t('app', 'Applications') ?></span></a>
            </li>
            <?php if (!Yii::$app->user->isManager()): ?>
            <li class="nav-item">
                <a href="<?= Url::to(['user/index']) ?>"><i class="fa fa-fw fa-users"></i> <span
                            class="menu-item-parent"> <?= Yii::t('app', 'Users') ?></span></a>
            </li>
                <li class="nav-item">
                    <a href="<?= Url::to(['order/index', 'sort' => '-id']) ?>"><i class="fa fa-fw fa-shopping-cart"></i>
                        <span
                                class="menu-item-parent"> <?= Yii::t('app', 'Orders') ?></span></a>
                </li>
            <?php endif; ?>
            <?php if (!Yii::$app->user->isManager()): ?>
                <li class="nav-item">
                    <a href="<?= Url::to(['setting/index']) ?>"><i class="fa fa-fw fa-cog txt-color-blue"></i> <span
                                class="menu-item-parent"> Настройки</span></a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i></span>

</aside>
<!-- END NAVIGATION -->

<!-- #MAIN PANEL -->
<div id="main" role="main">

    <!-- RIBBON -->
    <div id="ribbon">

        <!-- breadcrumb -->
        <ol class="breadcrumb">
            <li>Панель управления</li>
            <li><?= Html::encode($this->title) ?></li>
        </ol>
        <!-- end breadcrumb -->

        <!-- You can also add more buttons to the
        ribbon for further usability

        Example below:

        <span class="ribbon-button-alignment pull-right" style="margin-right:25px">
            <a href="#" id="search" class="btn btn-ribbon hidden-xs" data-title="search"><i class="fa fa-grid"></i> Change Grid</a>
            <span id="add" class="btn btn-ribbon hidden-xs" data-title="add"><i class="fa fa-plus"></i> Add</span>
            <button id="search" class="btn btn-ribbon" data-title="search"><i class="fa fa-search"></i> <span class="hidden-mobile">Search</span></button>
        </span> -->

    </div>
    <!-- END RIBBON -->

    <!-- #MAIN CONTENT -->

    <div id="content">

        <!-- col -->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>
        <!-- end col -->

        <!-- end row -->
    </div>
    <!-- END #MAIN CONTENT -->

</div>
<!-- END #MAIN PANEL -->

<!-- #PAGE FOOTER -->
<div class="page-footer">
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <span class="txt-color-white"><span class="hidden-xs"></span> © Газсенсор. Все права защищены.</span>
        </div>
    </div>
    <!-- end row -->
</div>
<!-- END FOOTER -->

<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>

    if (!window.jQuery) {
        document.write('<script src="/js/libs/jquery-3.2.1.min.js"><\/script>');
    }

</script>

<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script>
    if (!window.jQuery.ui) {
        document.write('<script src="/js/libs/jquery-ui.min.js"><\/script>');
    }
</script>


<?php $this->endBody() ?>
</body>
</html>

<?php $this->endPage() ?>


