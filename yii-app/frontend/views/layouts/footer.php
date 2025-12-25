<?php

use common\models\Setting;
use yii\helpers\Url;

?>
<footer id="site-footer" class="site-footer bg-footer">
    <div class="main-footer">
        <div class="container-custom">
            <div class="row">

                <div class="col-md-3 col-sm-6">
                    <div class="widget-footer">
                        <div id="media_image-1" class="widget widget_media_image">
                            <a href="/"><img loading="lazy" src="/i/logo-footer.svg" alt="Газсенсор: ГАЗОВЫЕ ДАТЧИКИ И СЕНСОРЫ"></a>
                        </div>
                    </div>
                </div>
                <!-- end col-lg-3 -->

                <div class="col-md-3 col-sm-6">
                    <div class="widget-footer">
                        <div id="custom_html-2" class="widget_text widget widget_custom_html padding-left">
                            <div class="textwidget custom-html-widget">
                                <ul>
                                    <li><a href="<?=Url::to(['/']) ?>">Главная</a></li>
                                    <li><a href="<?=Url::to(['/catalog']) ?>">Каталог</a></li>
                                    <li><a href="<?=Url::to(['/applications']) ?>">Сататьи</a></li>
                                    <li><a href="<?=Url::to(['/page/accessories']) ?>">Аксессуары</a></li>
                                    <li><a href="<?=Url::to(['/manufacture']) ?>">Производители</a></li>
                                    <li><a href="<?=Url::to(['/converter']) ?>">Конвертер газа</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col-lg-3 -->

                <div class="col-md-3 col-sm-6">
                    <div class="widget-footer">
                        <div id="custom_html-3" class="widget_text widge widget-footer widget_custom_html padding-left">
                            <div class="textwidget custom-html-widget">
                                <ul>
                                    <li><a href="<?=Url::to(['/news']) ?>">Новости</a></li>
                                    <li><a href="<?=Url::to(['/page/vacancy']) ?>">Вакансии</a></li>
                                    <li><a href="<?=Url::to(['/page/privacy']) ?>">Политика конфиденциальности</a></li>
                                    <li><a href="<?=Url::to(['/page/contacts']) ?>">Контакты</a></li>
                                </ul>
                            </div>
                        <div class="mt-4"><a id="back-to-top" href="#" class="btn btn-back-to-top">Наверх<i class="icon ion-ios-arrow-dropup-circle"></i></a> </div>
                        </div>

                    </div>
                </div>
                <!-- end col-lg-3 -->

                <div class="col-md-3 col-sm-6 space-beetween">
                    <div class="widget-footer ">
                        <ul class="bottombar-left">
                            <li><p><a target="_blank" onclick="ym(85084891,'reachGoal','CLICK_ON_ADRESS_FOOTER')"
                                   href="https://yandex.ru/maps/-/CCQ~aVhHlD">
                                    <i class="icon ion-md-pin"></i><?= Setting::getAdress() ?>
                                </a><p>
                                <p><a onclick="ym(85084891,'reachGoal','CLICK_ON_PHONE')" href="tel:+<?=Setting::getPhoneOnlyNumber() ?>"><i class="icon ion-md-call"></i><?= Setting::getPhone() ?></a></p>
                                <p><a onclick="ym(85084891,'reachGoal','CLICK_ON_PHONE')" href="tel:+<?=Setting::getPhoneOnlyNumber2() ?>"><i class="icon ion-md-call"></i><?= Setting::getPhone2() ?></a></p>
                                <p><a href="mailto:<?= Setting::getEmail() ?>"><i class="icon ion-md-mail"></i><?= Setting::getEmail() ?></a></p>
                            </li>

                        </ul>

                    </div>

                </div>
                <!-- end col-lg-3 -->

            </div>
        </div>
    </div>
    <!-- .main-footer -->
    <div class="footer-bottom">
        <div class="container-custom">
            <div class="row">
                <div class="col-md-12 flex-end space-beetween">
                    <div>
                        <div id="custom_html-1" class="widget_text widget widget_custom_html mb-3">
                            <div class="textwidget custom-html-widget">
                                <p>© 2011-<?= date('Y') ?> Газсенсор. Все права защищены</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- .copyright-footer -->
</footer>
