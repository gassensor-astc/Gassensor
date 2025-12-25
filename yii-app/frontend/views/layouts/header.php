<?php
/* @var $this \yii\web\View */

use yii\helpers\Html;
use common\models\Setting;
use yii\helpers\Url;

$user = Yii::$app->user;
$currentUrl = Url::current();

$slides = \common\models\Slider::find()->where(['=', 'page', $currentUrl])->all();

//echo '$currentUrl = ' . $currentUrl;
?>
<style>
    .new-header {
        width: 100%;
        margin-top: 0.5rem;
        height: 7rem;
        display: flex;
        flex-direction: column;
    }
    .new-header-row {
        width: 100%;
        height: 3.5rem;
        display: flex;
        flex-direction: row;
    }
    .new-header-row div {
        display: flex;
        justify-content: center;
        align-items: center;
        justify-items: center;
        padding-left: 1rem;
        padding-right: 1.25rem;
    }
    .new-header-logo a img {
        width: 209px;
        height: 44px;
    }
    .new-header-right-border {
        border-right: 1px solid #DFDEDE;
    }
    .new-header-text {
        color: #03132B ;
        font-weight: 400 ;
        font-style: normal ;
        font-size: 0.875rem ;
        line-height: 1.625rem ;
        letter-spacing: 0 ;
        vertical-align: middle ;
    }
    .new-header-text a {
        color: #03132B ;
        font-weight: 400 ;
        font-style: normal ;
        font-size: 0.875rem ;
        line-height: 1.625rem ;
        letter-spacing: 0 ;
        vertical-align: middle ;
    }
    .new-header-text a i {
        color: #DFDEDE;
    }
    .new-header-menu a {
        color: #03132B ;
        font-weight: 400 ;
        font-style: normal ;
        font-size: 1rem ;
        line-height: 1rem ;
        letter-spacing: 0 ;
        vertical-align: middle;
    }

    .new-header-menu-current {
        background-color: #F2F4F5;
        border-radius: 2px;
    }

    .new-header-row-second {
        height: 2.5rem;
        margin-top: 0.5rem;
    }
    .new-header-blank {
        min-width: 1rem;
        padding: 0 !important;
    }
    .new-header-slider {
        width: 100%;
        min-height: 41.25rem;
        margin-top: 1.3rem;
        display: flex;
        flex-direction: column;
    }
    .new-header-slider-slides {
        width: 100%;
        height: 41.25rem;
        display: flex;
        flex-direction: row;
    }
    .new-header-slider-controls {
        width: 100%;
        display: flex;
        flex-direction: row;
        flex: 1;
        padding-top: 1rem;
        height: 5rem;
    }
    .new-header-slider-text {
        font-weight: 400;
        font-style: normal;
        font-size: 4.2rem;
        color: #05295E;
        width: 80rem;
        margin-top: 5.5rem;
        margin-left: 12rem;
        line-height: 5rem;
    }

    .new-header-search {
        display: flex;
        position: relative;
    }

    .new-header-search input {
        padding-left: 35px;
    }

    .new-header-search button {
        position: absolute;
        left: 15px;
        top: -5px;
    }

    .new-header-search input:focus {
        box-shadow: 0 0 0 2px rgba(13,110,253,.25) !important;
    }

    .new-header-bottom-bottom {
        border-bottom: 1px solid #DFDEDE;
    }

    .new-header-hr {
        width: 100%;
        height: 1px;
        background-color: rgba(34,34,34,.08);/*rgba(237,237,237,.08);*/
        margin-top: 0.8rem;
        margin-bottom: 1.3rem;
    }
</style>

<header id="site-header" class="new-header">
    <div class="new-header-row">
        <div class="new-header-blank">
        </div>
        <div class="new-header-logo ">
            <a href="/">
                <img src="/i/logo2.svg" loading="lazy" alt="Газсенсор: газовые датчики и сенсоры" title="Газсенсор: газовые датчики и сенсоры">
            </a>
        </div>
        <div class=" new-header-text">
            <a target="_blank" onclick="ym(85084891,'reachGoal','CLICK_ON_ADRESS')" href="https://yandex.ru/maps/-/CCQ~aVhHlD">
                <i class="icon ion-md-pin"></i>
                <span style="margin-left: 3px;"><?= Setting::getAdress() ?></span>
            </a>
        </div>
        <div class=" new-header-text">
            <a onclick="ym(85084891,'reachGoal','CLICK_ON_PHONE')" href="tel:+<?=Setting::getPhoneOnlyNumber() ?>">
                <i class="icon ion-md-call"></i>
                <span style="margin-left: 3px;"><?= Setting::getPhone() ?></span>
            </a>
        </div>
        <div class=" new-header-text">
            <a href="mailto:<?= Setting::getEmail() ?>">
                <i class="icon ion-md-mail"></i>
                <span style="margin-left: 3px;"><?= Setting::getEmail() ?></span>
            </a>
        </div>
        <div class="" style="flex: 1;">

        </div>
        <div class=" new-header-menu">
            <a href="<?= Url::to('/cart') ?>">
                <span class="icon ion-md-basket" style="color: #DFDEDE; font-size: 20px; position: relative; top: 1px;">
                  <span id="cartTotalNum" class="fs-09" style="vertical-align: middle;">

                 <?php if($count = Yii::$app->cart->getItemsCount()): ?>
                     (<span class="val"><?= $count ?></span>)
                 <?php endif; ?>

                  </span>
                </span>

                <?php if(!$count = Yii::$app->cart->getItemsCount()): ?>
                Корзина
                <?php endif; ?>
            </a>
        </div>
        <div class="new-header-menu">
            <span class="ion ion-md-person" style="color: #DFDEDE; font-size: 20px; position: relative; top: 2px;">
            </span>
            <?php if ($user->isGuest): ?>
                <a style="margin-top: 6px; margin-left: 6px;" href="<?= Url::to('/site/login') ?>">Войти</a>
            <?php else: ?>
                <a style="margin-top: 6px; margin-left: 6px;" href="<?= Url::to('/backend/site/index') ?>" target="_blank">admin</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="new-header-row">
        <div class="new-header-blank">
        </div>

        <div class="new-header-row-second new-header-menu <?=$currentUrl == '/site/index' ? 'new-header-menu-current':'' ?>">
            <a href="/">Главная</a>
        </div>
        <div class="new-header-row-second new-header-menu <?=str_starts_with($currentUrl,'/news') ? 'new-header-menu-current':'' ?>">
            <a href="<?=Url::to(['/news']) ?>">Новости</a>
        </div>
        <div class="new-header-row-second new-header-menu <?=str_starts_with($currentUrl, '/catalog') ? 'new-header-row-second new-header-menu-current':'' ?>">
            <a href="<?=Url::to(['/catalog']) ?>">Каталог</a>
        </div>
        <div class="new-header-row-second new-header-menu <?=str_starts_with($currentUrl, '/remains/index') ? 'new-header-menu-current':'' ?>">
            <a href="<?=Url::to(['/remains']) ?>">Склад</a>
        </div>
        <div class="new-header-row-second new-header-menu <?=str_starts_with($currentUrl, '/manufacture') ? 'new-header-menu-current':'' ?>">
            <a href="<?=Url::to(['/manufacture']) ?>">Производители</a>
        </div>
        <div class="new-header-row-second new-header-menu <?=str_starts_with($currentUrl, '/applications') ? 'new-header-menu-current':'' ?>">
            <a href="<?=Url::to(['/applications']) ?>">Статьи</a>
        </div>
        <div class="new-header-row-second new-header-menu <?=str_starts_with($currentUrl, '/page/accessories') ? 'new-header-menu-current':'' ?>">
            <a href="<?=Url::to(['/page/accessories']) ?>">Аксессуары</a>
        </div>
        <div class="new-header-row-second new-header-menu <?=str_starts_with($currentUrl, '/converter') ? 'new-header-menu-current':'' ?>">
            <a href="<?=Url::to(['/converter']) ?>">Конвертер газа</a>
        </div>
        <div class="new-header-row-second new-header-menu <?=str_starts_with($currentUrl, '/page/contacts') ? 'new-header-menu-current':'' ?>">
            <a href="<?=Url::to(['/page/contacts']) ?>">Контакты </a>
        </div>
        <div class="" style="width: 1rem;">
        </div>
        <div style="width: 42%; padding: 0;">
            <form action="/search" style="width: 100%;">
                <div class="new-header-search">
                    <button class="pt-1 ms-1 bg-transparent border-0"><i class="icon ion-md-search" style="color: #DFDEDE; font-size: 24px;"></i></button>
                    <?= Html::textInput('q', '', ['class' => 'form-control', 'style' => 'border: 0; width: 100%;', 'placeholder' => 'Поиск по сайту']) ?>
                </div>
            </form>
        </div>
    </div>
</header>
<?php if ($currentUrl != '/site/index'): ?>
    <div class="new-header-hr"></div>
<?php else: ?>
    <div class="new-header-slider">
        <div class="new-header-slider-slides">
            <?php
                $scounter = 0;
                foreach ($slides as $slide) {
                    $display = $scounter ? 'none' : 'block';
                    echo '
                    <div class="new-header-slide" style="background-image: url(\'' . $slide->picture . '\'); background-size: cover; background-position: center; background-repeat: no-repeat; width: 100%; display: ' . $display . '; " id="new-header-slide-' . $scounter . '">
                        <div class="new-header-slider-text">' . nl2br($slide->description) . '</div>
                    </div>    
                    ';
                    $scounter++;
                }
            ?>
        </div>
        <div class="new-header-slider-controls" <?php if ($scounter < 2) echo 'style="visibility: hidden; max-height: 2rem;"'; ?>>
            <div class="new-header-blank"></div>
            <div style="display: flex; flex-direction: row">
            <?php
            $scounter2 = 0;
            $fildot = '
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="6" cy="6" r="5.5" fill="#A0A1BD" stroke="#A0A1BD"/>
            </svg>
            ';
            $emptydot = '
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="6" cy="6" r="5.5" stroke="#A0A1BD"/>
            </svg>
            ';
            foreach ($slides as $slide) {
                if (!$scounter2) {
                    echo '
                    <div class="new-header-dota s-counter-' . $scounter2 . '">
                     '.$fildot.'
                    </div>
                    ';
                } else {
                    echo '
                    <div class="new-header-dota s-counter-' . $scounter2 . '">
                     '.$emptydot.'
                    </div>
                    ';
                }
                $scounter2++;
            }
            ?>
            </div>
            <div style="flex: 1"></div>
            <div style="display: flex; flex-direction: row; gap: 5px;">
                <div class="new-header-slider-move-left">
                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="36" height="36" rx="4" fill="#F2F4F5"/>
                        <path d="M20.8125 23.625L15.1875 18L20.8125 12.375" stroke="#03132B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="new-header-slider-move-right">
                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="36" height="36" rx="4" fill="#F2F4F5"/>
                        <path d="M15.1875 12.375L20.8125 18L15.1875 23.625" stroke="#03132B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
            <div class="new-header-blank"></div>
        </div>
    </div>
<?php endif; ?>

<header id="site-header_old" class="site-header mobile-header-blue header-style-1" style="display: none;">
      <div id="header_topbar" class="header-topbar md-hidden sm-hidden "> <!-- .clearfix -->
        <div class="container-custom">
          <div class="my-row">
            <div>
              <!-- Info on Topbar -->
              <ul class="topbar-left">
                <li><a target="_blank" onclick="ym(85084891,'reachGoal','CLICK_ON_ADRESS')" href="https://yandex.ru/maps/-/CCQ~aVhHlD">
                  <i class="icon ion-md-pin"></i><?= Setting::getAdress() ?>
                </a></li>
                  <li><a onclick="ym(85084891,'reachGoal','CLICK_ON_PHONE')" href="tel:+<?=Setting::getPhoneOnlyNumber() ?>"><i class="icon ion-md-call"></i><?= Setting::getPhone() ?></a></li>
                  <li><a onclick="ym(85084891,'reachGoal','CLICK_ON_PHONE')" href="tel:+<?=Setting::getPhoneOnlyNumber2() ?>"><i class="icon ion-md-call"></i><?= Setting::getPhone2() ?></a></li>
                  <li><a href="mailto:<?= Setting::getEmail() ?>"><i class="icon ion-md-mail"></i><?= Setting::getEmail() ?></a></li>
              </ul>
            </div>
            <!-- Info on topbar close -->
            <div>
              <ul class="topbar-right">

                <li class="toggle_search topbar-search">
                  <form action="/search">
                  <div class="d-flex">
                    <?= Html::textInput('q', '', ['class' => 'form-control']) ?>
                    <button class="pt-1 ms-1 bg-transparent border-0"><i class="icon ion-md-search"></i></button>
                  </div>
                  </form>
                </li>
                <li class="topbar-search">
                  <a href="<?= Url::to('/cart') ?>">
                    <span class="icon ion-md-basket">
                      <span id="cartTotalNum" class="fs-09" style="vertical-align: middle;">

                     <?php if($count = Yii::$app->cart->getItemsCount()): ?>
                      (<span class="val"><?= $count ?></span>)
                     <?php endif; ?>

                      </span>
                    </span>
                  </a>
                </li>
                <li>
                <?php if ($user->isGuest): ?>
                  <a href="<?= Url::to('/site/login') ?>">Login</a>
                <?php else: ?>
                  <a href="<?= Url::to('/backend/site/index') ?>" target="_blank">admin</a>
                <?php endif; ?>

                </li>

            </ul>
          </div>
        </div>
      </div>
    </div>

    <div id="sky">
      <img src="/i/header.jpg" loading="lazy" alt="Газсенсор: ГАЗОВЫЕ ДАТЧИКИ И СЕНСОРЫ" title="Газсенсор: ГАЗОВЫЕ ДАТЧИКИ И СЕНСОРЫ">
      <div class="lozung-block">
        <span>Поиск, подбор, поставка и техническая<br>поддержка газовых датчиков и сенсоров</span>
      </div>
      <div class="logo-block">
        <div class="logo-brand">
          <a href="/">
            <img src="/i/logo.svg" loading="lazy" alt="Газсенсор: ГАЗОВЫЕ ДАТЧИКИ И СЕНСОРЫ" title="Газсенсор: ГАЗОВЫЕ ДАТЧИКИ И СЕНСОРЫ">
          </a>
        </div>
      </div>
      <div class="main-navigation">
        <ul id="primary-menu" class="menu">
            <li class="menu-item <?=$currentUrl == '/site/index' ? 'current-menu-ancestor current-menu-parent':'' ?>">
                <a href="/">Главная</a>
            </li>
            <li class="menu-item <?=$currentUrl == '/news/index' ? 'current-menu-ancestor current-menu-parent':'' ?>">
                <a href="<?=Url::to(['/news']) ?>">Новости</a>
            </li>
            <li class="menu-item <?=$currentUrl == '/catalog/index' ? 'current-menu-ancestor current-menu-parent':'' ?>">
                <a href="<?=Url::to(['/catalog']) ?>">Каталог</a>
            </li>
            <li class="menu-item <?=$currentUrl == '/remains/index' ? 'current-menu-ancestor current-menu-parent':'' ?>">
                <a href="<?=Url::to(['/remains']) ?>">Склад</a>
            </li>
            <li class="menu-item <?=$currentUrl == '/manufacture/index' ? 'current-menu-ancestor current-menu-parent':'' ?>">
                <a href="<?=Url::to(['/manufacture']) ?>">Производители</a>
            </li>

            <li class="menu-item <?=$currentUrl == '/applications/index' ? 'current-menu-ancestor current-menu-parent':'' ?>">
                <a href="<?=Url::to(['/applications']) ?>">Сататьи</a>
            </li>
            <li class="menu-item <?=$currentUrl == '/page/accessories' ? 'current-menu-ancestor current-menu-parent':'' ?>">
                <a href="<?=Url::to(['/page/accessories']) ?>">Аксессуары</a>
            </li>
            <li class="menu-item <?=$currentUrl == '/converter/index' ? 'current-menu-ancestor current-menu-parent':'' ?>">
                <a href="<?=Url::to(['/converter']) ?>">Конвертер газа</a>
            </li>
            <li class="menu-item <?=$currentUrl == '/page/contacts' ? 'current-menu-ancestor current-menu-parent':'' ?>">
                <a href="<?=Url::to(['/page/contacts']) ?>">Контакты </a>
            </li>
        </ul>
        <a style="display: none" href="#" class="btn btn-primary">Вопрос<i class="icon ion-md-paper-plane"></i></a>
      </div>
    </div>

    <div class="collapse searchbar" id="searchbar">
      <div class="search-area">
        <div class="container">
          <div class="row">
              <form action="/products" method="post">
                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
              <div class="input-group">
                <input type="text" class="form-control" name="titleSearch" placeholder="Поиск...">
                <span class="input-group-btn">
                  <button class="btn btn-primary" type="submit">Поиск</button>
                </span>

              </div>
              <!-- /input-group -->
            </div>
                  </form>
            <!-- /.col-lg-6 -->
          </div>
        </div>
      </div>
    </div>

    <!-- Main header start -->

    <!-- Mobile header start -->
    <div class="mobile-header">
      <div class="container-custom">
        <div class="row">
          <div class="col-6">
            <div class="logo-brand-mobile">
              <a href="/"><img src="/i/logo.svg" loading="lazy" alt="Газсенсор: газовые датчики и сенсоры" title="Газсенсор: газовые датчики и сенсоры" /></a>
            </div>
          </div>
          <div class="col-6">
            <div id="mmenu_toggle" class="">
              <button></button>
            </div>
          </div>
          <div class="col-12">
            <div class="mobile-nav" style="display: none;">
              <ul id="primary-menu-mobile" class="mobile-menu">
                  <li class="menu-item <?=$currentUrl == '/site/index' ? 'current-menu-ancestor current-menu-parent':'' ?>"><a href="<?=Url::to('/') ?>">Главная</a></li>
                  <li class="menu-item <?=$currentUrl == '/news/index' ? 'current-menu-ancestor current-menu-parent':'' ?>"><a href="<?=Url::to('/news') ?>">Новости</a></li>
                  <li class="menu-item <?=$currentUrl == '/catalog/index' ? 'current-menu-ancestor current-menu-parent':'' ?>"><a href="<?=Url::to('/catalog') ?>">Каталог</a></li>
                  <li class="menu-item <?=$currentUrl == '/remains/index' ? 'current-menu-ancestor current-menu-parent':'' ?>"><a href="<?=Url::to('/remains') ?>">Склад</a></li>
                  <li class="menu-item <?=$currentUrl == '/manufacture/index' ? 'current-menu-ancestor current-menu-parent':'' ?>"><a href="<?=Url::to('/manufacture') ?>">Производители</a></li>
                  <li class="menu-item <?=$currentUrl == '/applications/index' ? 'current-menu-ancestor current-menu-parent':'' ?>"><a href="<?=Url::to('/applications') ?>">Сататьи</a></li>
                  <li class="menu-item <?=$currentUrl == '/page/accessories' ? 'current-menu-ancestor current-menu-parent':'' ?>"><a href="<?=Url::to('/page/accessories') ?>">Аксессуары</a></li>
                  <li class="menu-item <?=$currentUrl == '/converter/index' ? 'current-menu-ancestor current-menu-parent':'' ?>"><a href="<?=Url::to('/converter') ?>">Конвертер газа</a></li>
                  <li class="menu-item <?=$currentUrl == '/page/contacts' ? 'current-menu-ancestor current-menu-parent':'' ?>"><a href="<?=Url::to('/page/contacts') ?>">Контакты</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
<script>
    setTimeout(()=> {
        let curSlide = 0;
        let totalSlides = <?php if (isset($scounter)) {echo $scounter;} else {echo 0;}?>;

        if (totalSlides > 1) {
            let fillDot = $('.s-counter-0').html();
            let emptyDot = $('.s-counter-1').html();

            $('.new-header-dota').css('cursor', 'pointer');
            $('.new-header-slider-move-right').css('cursor', 'pointer');
            $('.new-header-slider-move-left').css('cursor', 'pointer');
            $('.new-header-dota').click(function() {
                let num = $(this).attr('class').split(/\s+/);
                num = num[1].split('-');
                num = parseInt(num[2]);

                $('.new-header-dota').each(function() {
                   $(this).html(emptyDot);
                });

                $('.s-counter-' + num).html(fillDot);

                $('.new-header-slide').each(function() {
                    $(this).css('display', 'none');
                });

                $('#new-header-slide-' + num).css('display', 'block');

                curSlide = num;
            });

            $('.new-header-slider-move-left').click(function() {
                if (curSlide > 0) {
                    curSlide--;

                    $('.new-header-dota').each(function() {
                        $(this).html(emptyDot);
                    });

                    $('.s-counter-' + curSlide).html(fillDot);

                    $('.new-header-slide').each(function() {
                        $(this).css('display', 'none');
                    });

                    $('#new-header-slide-' + curSlide).css('display', 'block');
                }
            });

            $('.new-header-slider-move-left').click(function() {
                if (curSlide < totalSlides) {
                    curSlide++;

                    $('.new-header-dota').each(function() {
                        $(this).html(emptyDot);
                    });

                    $('.s-counter-' + curSlide).html(fillDot);

                    $('.new-header-slide').each(function() {
                        $(this).css('display', 'none');
                    });

                    $('#new-header-slide-' + curSlide).css('display', 'block');
                }
            });
        }
    }, 1000);
</script>