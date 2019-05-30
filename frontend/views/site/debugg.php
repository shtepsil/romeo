<?php

use frontend\controllers\MainController as d;
use yii\helpers\Html;
use yii\widgets\Menu;

$this->title = 'Отладка';

?>
<div class="text-center h3 header"><?= Html::encode($this->title) ?></div>

<section style="min-height: 600px;">
    <div class="col-md-12">
        <nav class="clearfix">

            <?if(0):?>
                <ul class="clearfix s1">
                    <li><a href="#">Главная</a></li>
                    <li><a href="#">О компании</a></li>
                    <li class="catalog">
                        <a href="#">Каталог</a>
                        <ul class="drop-menu s2" style="display: none;">
                            <li>
                                <a href="#">Классическая одежда</a>
                                <ul class="sub-menu s3" style="display: none;">
                                    <li><a href="#">Костюм</a></li>
                                    <li><a href="#">Пиджак</a></li>
                                    <li><a href="#">Брюки</a></li>
                                    <li><a href="#">Сорочка</a></li>
                                    <li><a href="#">Жилет</a></li>
                                    <li><a href="#">Галстук</a></li>
                                    <li><a href="#">Галстук-бабочка</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Одежда Casual</a>
                                <ul class="sub-menu s3" style="display: none;">
                                    <li><a href="#">Пиджак блейзер, клубный</a></li>
                                    <li><a href="#">Брюки слаксы, чинос</a></li>
                                    <li><a href="#">Рубашка</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Трикотаж</a>
                                <ul class="sub-menu s3" style="display: none;">
                                    <li><a href="#">Свитер, кофта</a></li>
                                    <li><a href="#">Кардиган</a></li>
                                    <li><a href="#">Джемпер, пуловер</a></li>
                                    <li><a href="#">Водолазка</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Белье, Носки</a>
                                <ul class="sub-menu s3" style="display: none;">
                                    <li><a href="#">Белье нательное</a></li>
                                    <li><a href="#">Термобелье</a></li>
                                    <li><a href="#">Носки</a></li>
                                    <li><a href="#">Термоноски</a></li>
                                    <li><a href="#">Кальсоны</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Летняя одежда</a>
                                <ul class="sub-menu s3" style="display: none;">
                                    <li><a href="#">Футбока</a></li>
                                    <li><a href="#">Поло</a></li>
                                    <li><a href="#">Шорты</a></li>
                                    <li><a href="#">Пляжная одежда</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Верхняя одежда</a>
                                <ul class="sub-menu s3" style="display: none;">
                                    <li><a href="#">Пуховик</a></li>
                                    <li><a href="#">Зимняя куртка</a></li>
                                    <li><a href="#">Демисезонная куртка</a></li>
                                    <li><a href="#">Пальто</a></li>
                                    <li><a href="#">Плащ</a></li>
                                    <li><a href="#">Ветровка</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Аксессуары</a>
                                <ul class="sub-menu s3" style="display: none;">
                                    <li><a href="#">Ремень, подтяжки</a></li>
                                    <li><a href="#">Кожгалантерея</a></li>
                                    <li><a href="#">Шарф, кашне</a></li>
                                    <li><a href="#">Запонки, зажим</a></li>
                                    <li><a href="#">Шейный платок</a></li>
                                    <li><a href="#">Носовой платок</a></li>
                                    <li><a href="#">Подарочный набор</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Головные уборы </a>
                                <ul class="sub-menu s3" style="display: none;">
                                    <li><a href="#">Шапочка</a></li>
                                    <li><a href="#">Кепка</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="#">Контакты</a></li>
                </ul>
            <?endif;?>

            <a href="#" id="pull">Menu</a>
        </nav>
    </div>
</section>
