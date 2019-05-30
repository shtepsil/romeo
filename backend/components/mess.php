<?php

/*
 * Сообщения ошибок
 */
$MESS['E'] = '';

/*
 * Сообщения предупреждений
 */
$MESS['MOV_ROW_NOT_FOUND'] = 'Продажи (выдачи на обмен, отоваривание)<br>с таким кодом документа и штрихкодом не найдено';
$MESS['BARCODE_ERROR'] = 'Введен не верный штрихкод';
$MESS['BARCODE_NOT_FOUND'] = 'Штрихкод не найден';
$MESS['CERTIFICATE_ALREADY_COOKED'] = 'Сертификат уже отоварен';
$MESS['NO_SUCH_CERTIFICATE'] = 'Нет такого сертификата';
$MESS['BARCODES_OVER'] = 'Порядковый номер по данному штрих коду закончился.<br>Обратитесь к администратору системы.';
$MESS['NOMENCLATURE_NOT_FOUND'] = 'По паре "Бренд-Артикул" - номенклатура не найдена!';
$MESS['WRONG_FORMAT'] = 'Формат файла не правильный';
$MESS['WRITE_ERROR_IN_DOCUMENT'] = 'Ошибка записи данных<br>в таблицу "Документ"<br>';
$MESS['WRITE_ERROR_IN_GOODS_MOVEMENT'] = 'Ошибка записи данных в таблицу "Движение товара"<br>';
$MESS['WRITE_ERROR_IN_PRODUCT'] = 'Ошибка записи данных в таблицу "Товары"<br>';
$MESS['PRODUCT_NOT_ADDED'] = 'Вы не добавили ни одного товара';
$MESS['SECTION_2_ERROR'] = 'В разделе "Продажа сертификата"<br>при изменении статуса  на "продан", возникла ошибка<br>';
$MESS['SECTION_4_ERROR'] = 'В разделе "Отоваривание сертификата"<br>при изменении статуса  на "отоварен", возникла ошибка<br>';
$MESS['S3DC_ERROR'] = 'Ошибка записи данных в таблицу "Дисконтные карты"<br>';
$MESS['DC_ERROR'] = 'Ошибка обновления значений:<br>"накопление за текущий год"<br>"скидка по дисконтной карте"<br>';
$MESS['EDIT_ERROR'] = 'Ошибка, данные не изменены.';
$MESS['NEW_USER_CREATE_ERROR'] = 'Ошибка добавления пользователя';
$MESS['WRONG_BARCODE'] = 'Введен не корректный штрихкод';
$MESS['FILE_SUCCESS_DATA_ERROR'] = 'Файл загружен, но данные в БД не записаны!';
$MESS['FILE_SUCCESS_ALL_DATA_DUPLICATE'] = 'Файл загружен, но данные в БД не добавлены, потому что все строки файла уже присутствуют в БД';
$MESS['ALL_DATA_DUPLICATE'] = 'Все строки файла уже присутствуют в БД';
$MESS['DUPLICATE_HEADER'] = 'Список пропущеных штрихкодов, такие уже есть в БД<br>';
$MESS['NOT_EXISTING_HEADER'] = 'Список пропущеных штрихкодов, таких в БД не существует<br>';
$MESS['DELETE_DATA_SUCCESS_FILE_ERROR'] = 'Запись из БД удалена, но файл не удален. Возникла ошибка.';
$MESS['DELETE_DB_EXCEL_FILE_ERROR'] = 'Ошибка удаления файла из БД';
$MESS['NO_BAR_CODES_FOUND'] = 'Не найдено ни одного штрихкода';
$MESS['FILE_EXCEL_DELETE_SUCCESS'] = 'Файл удален';
$MESS['CERTIFICATE_ALREADY_CREDITED'] = 'Сертификат уже оприходован';
$MESS['POSTING_ERROR'] = 'Ошибка оприходования';
$MESS['NO_PRODUCT_FOR_THIS_DOCUMENT'] = 'По номеру документа товар не найден';
$MESS['NO_PRODUCT_FOR_THIS_BARCODE'] = 'По штрихкоду движение товара не найдено';
$MESS['DOCUMENT_ID_ERROR'] = 'Номер документа не существует';
$MESS['NO_ONE_PRODUCT_NOT_FOUND'] = 'Не найдено ни одного товара';
$MESS['NO_ONE_PRODUCT_NOT_FOUND_GM'] = 'В таблице "Движение товара" не найдено ни одного товара';
$MESS['NO_ONE_DOCUMENT_NOT_FOUND'] = 'Не найдено ни одного документа';
$MESS['DUPLICATE_ENTRY'] = 'Обнаружена дублирующая запись';
$MESS['FILE_EXCEL_IS_EMPTY'] = 'Файл загружен, но пуст.<br>Загружать в БД нечего.';
$MESS['CHECK_SEARCH_NOT_FOUND'] = 'По заданным параметрам<br>ничего не найдено';
$MESS['NO_SAND_MAIL'] = 'Данных для отправки нет';
$MESS['ERROR_SAND_MAIL'] = 'Ошибка отправки сообщения';
$MESS['DOCUMENTS_NOT_FOUND'] = 'Не найдено ни одного документа';
$MESS['DOCUMENT_TO_ZERO_ERROR'] = 'Ошибка списания документа на ноль';
$MESS['CHANGE_SAVED_ERROR'] = 'Ошибка сохранения';


/*
 * Сообщения успешности
 */
$MESS['DONE'] = 'Выполнено';
$MESS['UPLOADED'] = 'Загружено';
$MESS['SAVED'] = 'Сохранено';
$MESS['EDIT_SUCCESS'] = 'Данные изменены';
$MESS['ITEMS_ADDED'] = 'Товары добавлены';
$MESS['CHECK_IS_SAVED'] = 'Товарный чек сохранен';
$MESS['RECORDING_UPDATED'] = 'Запись обновлена';
$MESS['NEW_ENTRY_ADDED'] = 'Новая запись добавлена';
$MESS['FILE_UPLOADED_DATABASE_DATA_ADDED'] = 'Файл загружен, данные в БД добавлены!';
$MESS['REPORT_RECEIVED'] = 'Отчет получен!';
$MESS['REPORT_RECEIVED'] = 'Отчет получен!';
$MESS['DATA_UPLOADED'] = 'Данные загружены';
$MESS['BARCODE_GENERATED'] = 'Штрихкод сгенерирован';
$MESS['NEW_USER_CREATE_SUCCESS'] = 'Новый пользователь добавлен';
$MESS['GOODS_RECEIVED'] = 'Строка получена из БД';
$MESS['CERTIFICATE_RECEIVED'] = 'Сертификат получен';
$MESS['SUCCES_SAND_MAIL'] = 'Письмо отправлено';
$MESS['DOCUMENTS_FOUND'] = 'Документы загружены';
$MESS['DOCUMENT_SAVED'] = 'Документ сохранён';
$MESS['DOCUMENT_TO_ZERO_SUCCESS'] = 'Документ списан на ноль';
$MESS['CHANGE_SAVED'] = 'Изменение сохранено';

/*
 * Сообщения для Ajax
 */
$MESS['AJAX_STATUS_SUCCESS'] = 200;
$MESS['AJAX_STATUS_ERROR'] = 407;
$MESS['TYPE_WARNING'] = 'warning';
$MESS['TYPE_ERROR'] = 'error';
$MESS['HEADER_SUCCESS'] = 'Успешно';
$MESS['HEADER_WARNING'] = 'Внимание';
$MESS['HEADER_ERROR'] = 'Ошибка';
$MESS['DATA_ERROR'] = 'Ошибка загрузки данных';
$MESS['NOT_FOUND'] = 'Ничего не найдено';
$MESS['DONE'] = 'Выполнено';

/*
 * Прочие сообщения
 */
$MESS['PAYMENT_METHOD_BANK_CARD'] = 'Банковская карта';
$MESS['PAYMENT_METHOD_CASH'] = 'Наличные';
$MESS['DATE_NOT_FOUND'] = 'По указанной дате, товарных чеков не найдено.';
$MESS['NO_ARTICLE_FOUND'] = 'Список артикулов пуст.';
$MESS['ADD_WORKER'] = 'Добавить работника';
$MESS['SELECT_WORKER'] = 'Выберите работника';















// BITRIX ========================================================================

$MESS["MAIN_PROLOG_ADMIN_TITLE"] = "Административный раздел";
$MESS["TRIAL_ATTENTION"] = "Внимание! Воспользуйтесь технологией <a href=\"/bitrix/admin/sysupdate.php\">SiteUpdate</a> для получения последних обновлений.<br>";
$MESS["TRIAL_ATTENTION_TEXT2___"] = "До истечения пробного периода осталось";
$MESS["TRIAL_ATTENTION_TEXT3___"] = "дней";
$MESS["main_prolog_help"] = "Помощь";
$MESS["prolog_main_show_menu"] = "Показать меню";
$MESS["prolog_main_m_e_n_u"] = "М<br>е<br>н<br>ю<br>";
$MESS["prolog_main_less_buttons"] = "Уменьшить кнопки";
$MESS["prolog_main_hide_menu"] = "Скрыть меню";
$MESS["MAIN_PR_ADMIN_CUR_LINK"] = "Ссылка на текущую страницу";
$MESS["TRIAL_ATTENTION_TEXT1_1c_bitrix___"] = "Это пробная версия продукта \"1С-Битрикс: Управление сайтом\".";
$MESS["TRIAL_ATTENTION_TEXT4_1c_bitrix___"] = "Срок работы пробной версии продукта \"1С-Битрикс: Управление сайтом\" истек. Через две недели этот сайт заблокируется, разблокировать его будет невозможно.";
$MESS["TRIAL_ATTENTION_TEXT5_1c_bitrix"] = "Вы можете купить полнофункциональную версию продукта по адресу <a href=\"http://www.1c-bitrix.ru/buy/?r1=bsm7trial&amp;r2=expiried\">http://www.1c-bitrix.ru/buy/</a>";
$MESS["TRIAL_ATTENTION_TEXT1_bitrix"] = " ";
$MESS["TRIAL_ATTENTION_TEXT4_bitrix"] = " ";
$MESS["TRIAL_ATTENTION_TEXT5_bitrix"] = " ";
$MESS["TRIAL_ATTENTION_TEXT1_1c_bitrix_portal___"] = "Это пробная версия продукта \"1С-Битрикс: Корпоративный портал\".";
$MESS["TRIAL_ATTENTION_TEXT4_1c_bitrix_portal___"] = "Срок работы пробной версии продукта \"1С-Битрикс: Корпоративный портал\" истек. Через две недели этот сайт заблокируется, разблокировать его будет невозможно";
$MESS["TRIAL_ATTENTION_TEXT5_1c_bitrix_portal"] = "Вы можете купить полнофункциональную версию продукта по адресу <a href=\"http://www.1c-bitrix.ru/buy/?r1=bsm7trial&amp;r2=expiried\">http://www.1c-bitrix.ru/buy/</a>";
$MESS["TRIAL_ATTENTION_TEXT1_bitrix_portal"] = " ";
$MESS["TRIAL_ATTENTION_TEXT4_bitrix_portal"] = " ";
$MESS["TRIAL_ATTENTION_TEXT5_bitrix_portal"] = " ";
$MESS["prolog_main_more_buttons"] = "Увеличить кнопки";
$MESS["prolog_main_support1"] = "<span class=\"required\">Внимание!</span> Срок активности техподдержки и обновлений <b>заканчивается</b> #FINISH_DATE#, #DAYS_AGO#.#WHAT_IS_IT#<br />Вы можете приобрести льготное продление до #SUP_FINISH_DATE#.";
$MESS["prolog_main_support_days"] = "через <b>#N_DAYS_AGO#&nbsp;дней</b>";
$MESS["prolog_main_support2"] = "<span class=\"required\">Внимание!</span> Срок активности техподдержки и обновлений <b>закончился</b> #FINISH_DATE#, <b>#DAYS_AGO#&nbsp;дней</b> назад.#WHAT_IS_IT#<br />Вы можете приобрести льготное продление до #SUP_FINISH_DATE#.";
$MESS["prolog_main_support3"] = "<span class=\"required\">Внимание!</span> Срок активности техподдержки и обновлений <b>закончился</b> #FINISH_DATE#.#WHAT_IS_IT#<br />Вы можете приобрести стандартное продление техподдержки.";
$MESS["prolog_main_today"] = "<b>сегодня</b>";
$MESS["prolog_admin_headers_sent"] = "Внимание! Обнаружены лишние символы в служебном файле: #FILE#, строка #LINE#.";
$MESS["TRIAL_ATTENTION_TEXT1_1c_bitrix_eduportal___"] = "Это пробная версия продукта \"1С-Битрикс: Внутренний портал учебного заведения\".";
$MESS["TRIAL_ATTENTION_TEXT4_1c_bitrix_eduportal___"] = "Срок работы пробной версии продукта \"1С-Битрикс: Внутренний портал учебного заведения\" истек. Через две недели этот сайт заблокируется, разблокировать его будет невозможно";
$MESS["TRIAL_ATTENTION_TEXT5_1c_bitrix_eduportal"] = "Вы можете купить полнофункциональную версию продукта по адресу <a href=\"http://www.1c-bitrix.ru/buy/?r1=bsm7trial&amp;r2=expiried\">http://www.1c-bitrix.ru/buy/</a>";
$MESS["TRIAL_ATTENTION_TEXT1_1c_bitrix_gosportal___"] = "Это пробная версия продукта \"1С-Битрикс: Внутренний портал органа власти\".";
$MESS["TRIAL_ATTENTION_TEXT4_1c_bitrix_gosportal___"] = "Срок работы пробной версии продукта \"1С-Битрикс: Внутренний портал органа власти\" истек. Через две недели этот сайт заблокируется, разблокировать его будет невозможно";
$MESS["TRIAL_ATTENTION_TEXT5_1c_bitrix_gosportal___"] = "Вы можете купить полнофункциональную версию продукта по адресу <a href=\"http://www.1c-bitrix.ru/buy/?r1=bsm7trial&amp;r2=expiried\">http://www.1c-bitrix.ru/buy/</a>";
$MESS["TRIAL_ATTENTION_TEXT1_1c_bitrix_gossite___"] = "Это пробная версия продукта \"1С-Битрикс: Официальный сайт органа власти\".";
$MESS["TRIAL_ATTENTION_TEXT4_1c_bitrix_gossite___"] = "Срок работы пробной версии продукта \"1С-Битрикс: Официальный сайт органа власти\" истек. Через две недели этот сайт заблокируется, разблокировать его будет невозможно";
$MESS["TRIAL_ATTENTION_TEXT5_1c_bitrix_gossite"] = "Вы можете купить полнофункциональную версию продукта по адресу <a href=\"http://www.1c-bitrix.ru/buy/?r1=bsm7trial&amp;r2=expiried\">http://www.1c-bitrix.ru/buy/</a>";
$MESS["MAIN_PR_ADMIN_FAV_ADD"] = "Добавить в избранное";
$MESS["MAIN_PR_ADMIN_FAV_DEL"] = "Удалить из избранного";
$MESS["admin_panel_browser"] = "Административная панель не поддерживает Internet Explorer версии 7 и ниже. Установите современный браузер <a href=\"http://www.firefox.com\">Firefox</a>, <a href=\"http://www.google.com/chrome/\">Chrome</a>, <a href=\"http://www.opera.com\">Opera</a> или <a href=\"http://www.microsoft.com/windows/internet-explorer/\">Internet Explorer 9</a>.";
$MESS["MAIN_PR_ADMIN_FAV"] = "Избранное";
$MESS["prolog_main_support_wit"] = "Что это такое?";
$MESS["prolog_main_support_wit_descr1"] = "Что означает окончание срока «Стандартной лицензии»?";
$MESS["prolog_main_support_wit_descr2"] = "Окончание срока «Стандартной лицензии» приводит к наложению ограничений на систему обновления и на некоторые функциональные возможности продукта.<br /><br />
 После окончания срока активности «Стандартной лицензии» вы не сможете устанавливать обновления платформы, получать новые версии продукта и устанавливать дополнения из каталога решений Маркетплейс, а так же продления для них. Одновременно с этим прекращается работа наших облачных сервисов, например «CDN» или «Облачный бекап».<br /><br />
Так же, для вас увеличивается время обработки ваших обращений в нашу службу поддержки. Для пользователей с неактивной лицензией, срок ответа на обращение в техническую поддержку может составлять до 24 рабочих часов. <br /><br />
Для того чтобы продолжить пользоваться вышеперечисленными возможностями, необходимо приобрести продление.<br /><br />
В течение одного месяца после окончания срока стандартной лицензии, вы можете это сделать на льготных условиях, за 22% от стоимости используемой вами редакции. В любое другое время – за 60%.<br /><br />
Обращаем ваше внимание, что вы можете продолжать использование продукта на условиях «Ограниченной лицензии» без ограничения по времени.<br /><br />
С подробностями можно ознакомится в «<a href=\"http://www.1c-bitrix.ru/download/law/eula_bus.pdf\" target=\"_blank\">Лицензионном соглашении</a>»
";
$MESS["prolog_main_support_wit_descr2_cp"] = "Окончание срока «Стандартной лицензии» приводит к наложению ограничений на систему обновления и на некоторые функциональные возможности продукта. <br /><br />
После окончания срока активности «Стандартной лицензии» вы не сможете устанавливать обновления платформы, получать новые версии продукта и устанавливать дополнения из каталога решений Маркетплейс, а так же продления для них. Одновременно с этим прекращается работа нашего сервиса «Облачный бекап». <br /><br />
Так же, для вас увеличивается время обработки ваших обращений в нашу службу поддержки. Для пользователей с неактивной лицензией, срок ответа на обращение в техническую поддержку может составлять до 24 рабочих часов.<br /><br />
Для того чтобы продолжить пользоваться вышеперечисленными возможностями, необходимо приобрести продление.<br /><br />
В течение одного месяца после окончания срока стандартной лицензии, вы можете это сделать на льготных условиях, за 22% от стоимости используемой вами редакции. В любое другое время – за 60%.<br /><br />
Обращаем ваше внимание, что вы можете продолжать использование продукта на условиях «Ограниченной лицензии» без ограничения по времени.<br /><br />
С подробностями можно ознакомится в «<a href=\"http://www.1c-bitrix.ru/download/law/eula_cp.pdf\" target=\"_blank\">Лицензионном соглашении</a>»";
$MESS["prolog_main_support_button_prolong"] = "Продлить лицензию";
$MESS["prolog_main_support_button_no_prolong"] = "Не буду продлевать";
$MESS["prolog_main_support11"] = "<span class=\"required\">Внимание!</span> Срок активности техподдержки и обновлений <b>заканчивается</b> #FINISH_DATE#, #DAYS_AGO#.#WHAT_IS_IT#<br />Вы можете приобрести льготное продление до #SUP_FINISH_DATE#.";
$MESS["prolog_main_support21"] = "<span class=\"required\">Внимание!</span> Срок активности техподдержки и обновлений <b>закончился</b> #FINISH_DATE#, <b>#DAYS_AGO#&nbsp;дней</b> назад.#WHAT_IS_IT#<br />Вы можете приобрести льготное продление до #SUP_FINISH_DATE#.";
$MESS["prolog_main_support31"] = "<span class=\"required\">Внимание!</span> Срок активности техподдержки и обновлений <b>закончился</b> #FINISH_DATE#.#WHAT_IS_IT#<br />Вы можете приобрести стандартное продление техподдержки.";
$MESS["prolog_main_support_button_no_prolong2"] = "Напомнить позже...";
$MESS["prolog_main_support_menu1"] = "Через:";
$MESS["prolog_main_support_menu2"] = "неделю";
$MESS["prolog_main_support_menu3"] = "две недели";
$MESS["prolog_main_support_menu4"] = "<span style=\"color:red;\">месяц</span>";
$MESS["DEVSERVER_ADMIN_MESSAGE___"] = "Эта установка предназначена для разработки на базе продукта \"1С-Битрикс: Управление сайтом\". Она не должна использоваться в качестве рабочего (боевого) сайта.";
?>