<?php
/**
 * Created by PhpStorm.
 * User: Сергей
 * Date: 24.04.2019
 * Time: 16:39
 */

namespace frontend\components;


class Mess
{

    /*
     * Сообщения предупреждений
     */

    const REGISTRATION_ERROR = 'Ошибка регистрации.<br>Обратитесь к администратору сайта!';
    const REGISTRATION_ERROR_USER_IS_ALREADY = 'Ошибка регистрации.<br>Пользователь с таким Email уже зарегистрирован!';
    const EMAIL_VERIFICATION_SENT_ERROR = 'Ошибка отправки Email<br>Обратитесь к администратору сайта!';
    const AUTH_ERROR = 'Неверный логин или пароль!';
    const VERIFICATION_KEY_ERROR = 'Код верификации устарел';
    const EMAIL_CONFIRM_ERROR = 'Ошибка подтверждения Email. Обратитесь к администратору сайта.';
    const DATA_SAVE_ERROR = 'Ошибка сохранения данных';
    const CURRENT_PASSWORD_ERROR = 'Введен не верный текущий пароль';
    const USER_DELETE_ERROR = 'Ошибка удаления аккаунта. Обратитесь к администратору сайта.';
    const USER_NOT_FOUND = 'Пользователь не найден';
    const AUTH_ERROR_EMAIL_VERIFICATION_SENT = 'Ваш Email не подтвержден.<br>Для того чтобы пользоваться личным кабинетом, вам необходимо подтвердить вашу электронную почту.<br>На ваш Email отправлено письмо со ссылкой для подтверждения.<br>Ссылка будет действительна в течении 15 минут...';
    const ITEMS_NOT_ADDED = 'Ошибка добавления товаров в корзину';
    const ERROR_ITEM_DELETED = 'Ошибка удаления товара';
    const ADD_ORDER_ERROR = 'Ошибка оформления заказа';
    const EMAIL_ALREADY_IS = 'Email не обновлен, потому что такой эл.адрес уже существует!';


        /*
         * Сообщения успешности
         */
    const UPLOADED = 'Загружено';
    const EDIT_SUCCESS = 'Данные изменены';
    const DATA_UPLOADED = 'Данные загружены';
    const REGISTRATION_SUCCESSFUL = 'Регистрация успешна';
    const AUTH_SUCCESSFUL = 'Авторизация успешна';
    const EMAIL_VERIFICATION_SENT = 'Письмо со ссылкой для подтверждения, отправлено на ваш Email.<br>Ссылка будет действительна в течении 15 минут...';
    const REGISTRATION_SUCCESSFUL_EMAIL_VERIFICATION_SENT = 'Ваш аккаунт создан.<br>Для того чтобы пользоваться личным кабинетом, вам необходимо подтвердить вашу электронную почту.<br>На ваш Email отправлено письмо со ссылкой для подтверждения.<br>Ссылка будет действительна в течении 15 минут...';
    const EMAIL_CONFIRM = 'Подтреждение Email успешно!';
    const DATA_SAVE_SUCCESS = 'Данные сохранены';
    const ITEM_ADDED = 'Товар добавлен в корзину';
    const ITEM_DELETED = 'Товар удален';
    const ADD_ORDER_SUCCESS = 'Заказ оформлен';


        /*
         * Сообщения для Ajax
         */
    const AJAX_STATUS_SUCCESS = 200;
    const HEADER_SUCCESS = 'Успешно';
    const SAVED = 'Сохранено';
    const DONE = 'Выполнено';
    // -------------------------
    const AJAX_STATUS_ERROR = 407;
    const TYPE_WARNING = 'warning';
    const TYPE_ERROR = 'error';
    const HEADER_WARNING = 'Внимание';
    const HEADER_ERROR = 'Ошибка';
    const DATA_ERROR = 'Ошибка загрузки данных';
    const NOT_FOUND = 'Ничего не найдено';

        /*
         * Прочие сообщения
         */
    const PAYMENT_METHOD_BANK_CARD = 'Банковская карта';
    const PAYMENT_METHOD_CASH = 'Наличные';
    const DATE_NOT_FOUND = 'По указанной дате, товарных чеков не найдено.';
    const NO_ARTICLE_FOUND = 'Список артикулов пуст.';
    const ADD_WORKER = 'Добавить работника';
    const SELECT_WORKER = 'Выберите работника';
}