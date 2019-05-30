$(function(){
    
    /**
     * Страница "single-product"
     * =========================
     * При нажатии на блок размера
     * устанавливаем цвет рамки в зеленый.
     * Если же блок уже выбран, и цвет рамки уже зеленый,
     * то убираем выделение и меняем цвет на общий
     */
    $('.sept .single-product-option .sort.product-type .size-blocks li button').on('click',function(){
        if($(this).attr('data-selected') == ''){
            $(this).attr('data-selected','selected');// Выбрано
            $(this).css({'border-color':'#cc3333'});// Красный цвет
//            $(this).css({'border-color':'#21db09'});// Зеленый цвет
        }else{
            $(this).attr('data-selected','');// Не выбрано
            $(this).css({'border-color':'#d5d5d5'});// Серый цвет
        }
    });
    
    /**
     * Модальное окно "Регистрация"
     * ============================
     * Показать/скрыть пароль
     */
    $('.wrap-reg-auth label.view-password').on('click',function(){
        
        var input_reg_pass =  $('.wrap-reg-auth [name=password]'),
            label_view_password = $('.wrap-reg-auth .view-password');
        
        if(input_reg_pass.attr('type') == 'password'){
            input_reg_pass.attr('type','text');
            label_view_password.attr('title','Скрыть пароль');
            label_view_password.find('.zmdi.zmdi-eye-off').fadeOut(40,function(){
                label_view_password.find('.zmdi.zmdi-eye').fadeIn(40);
            });
        }else{
            label_view_password.attr('title','Показать пароль');
            input_reg_pass.attr('type','password');
            label_view_password.find('.zmdi.zmdi-eye').fadeOut(100,function(){
                label_view_password.find('.zmdi.zmdi-eye-off').fadeIn(100);
            });
        }
    });
    
    /**
     * Модальное окно "Регистрация"
     * ============================
     * Поле ввода "Email"
     * ------------------
     * При фокусе, убираем все сообщения ошибок
     */
    $('.form-reg-auth').find('input').on('focus',function(){
        var form = $('.wrap-reg,.wrap-auth'),
            error_email = form.find('.reg-error.error-mail'),
            error_pass = form.find('.reg-error.error-passwd');
        error_email.html('');
        error_pass.html('');
    });
    
    /**
     * Модальное окно "Регистрация"
     * ============================
     * Кнопка "Зарегистрироваться"
     */
    $('.form-user-register .btn-user-register').on('click',function(){
        
        var $this = $(this),
            wrap = $('.wrap-reg'),
            form = $('.form-user-register'),
            load = $this.find('img'),
            res = form.find('.res'),
            error_email = wrap.find('.reg-error.error-mail'),
            error_pass = wrap.find('.reg-error.error-passwd'),
            email = wrap.find('[name=email]'),
            password = wrap.find('[name=password]'),
            Data = {};
        
        // Закрываем все информационные окна
        cea();
        
        // Проверка логин и пароль на пустоту
        if(email.val() == '' || password.val() == ''){
            LoadAlert('Внимание','Должны быть заполнены все поля',4000,'warning');
            return;
        }
        
        //Проверка Email на правильность
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        if(reg.test(email.val()) == false){
            error_email.html('Введен не корректный Email');
            return;
        }
        
        // Проверка пароля на минимальное количество символов 
        if(password.val().length < 6){
            error_pass.html('Пароль должен содержать минимум 6 символов');
            return;
        }
        
        // Проверка пароля на допустимые символы 
        var reg = /^([A-Za-z0-9_\-!@\(\)\^\$\%\*=#\+\&;:№\.\?]+)$/;
        if(reg.test(password.val()) == false){
            error_pass.html('Пароль содержит недопустимые симолы. Разрешенные спецсимволы: !@#$%^&*()_-+=:;№?');
            return;
        }
        
        form.find('input').each(function(){
            Data[$(this).attr('name')] = $(this).val();
        });
        
//        cl(Data);
//        return;
        
        $.ajax({
            url:form.attr('action'),
            type:form.attr('method'),
            cashe:'false',
            dataType:'json',
            data:Data,
            beforeSend:function(){ load.fadeIn(100); }
        }).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));
//            LoadAlert(data.header,data.message,5000,data.type_message);
            popUp('#modal-register',data.message,data.type_message);
            // Если регистрация успешна
            if(data.status == 200){
                /**
                 * Направляем пользователя
                 * на страницу подтверждения Email
                 * с задержкой 1 секунда
                 */
//                setTimeout(function(){
//                    location.href = '/user/';
//                },500);
            }
            load.fadeOut(100);
        }).fail(function(data){
//            res.html('Fail<br>'+JSON.stringify(data));
            LoadAlert('Внимание','Ошибка',3000,'error');
            load.fadeOut(100);
        });
        
    });
    
    /**
     * Модальное окно "Авторизация"
     * ============================
     * Кнопка "Войти"
     */
    $('.form-user-auth .btn-user-auth').on('click',function(){
        
        var $this = $(this),
            wrap = $('.wrap-auth'),
            form = $('.form-user-auth'),
            load = $this.find('img'),
            res = form.find('.res'),
            error_email = wrap.find('.reg-error.error-mail'),
            error_pass = wrap.find('.reg-error.error-passwd'),
            email = wrap.find('[name=email]'),
            password = wrap.find('[name=password]'),
            area_log_auth = $('.area-log-auth'),
            user_authorized = $('.user-authorized'),
            close = $('.modal .close'),
            user_id = $('[name=user_id]'),
            user_auth = $('[name=user_auth]'),
            backet = $('.cart-content-wraper'),
            cart_content = backet.find('.main-cart-content'),
            no_products = backet.find('.no-products'),
            count_product_backet = $('.header-cart .count-backet'),
            i=0,
            total_payment = 0,
            total_count_product = 0,
            Data = {};
        
        // Если страница "корзина"
        if($('[name=type_basket]').val() == 'main-bt'){
            var cart_page = $('.main-bt'),
                list_no_products = cart_page.find('.no-products'),
                list_is_products = cart_page.find('.is-products'),
                process_cart_total = cart_page.find('.process-cart-total .p-c-t'),
                type_basket = $('[name=type_basket]').val(),
                res = $('.res');
            
            Data['request_url'] = cart_page.find('[name=request_url]').val()
        }
        
        // Закрываем все информационные окна
        cea();
        
        // Проверка логин и пароль на пустоту
        if(email.val() == '' || password.val() == ''){
            LoadAlert('Внимание','Должны быть заполнены все поля',4000,'warning');
            return;
        }
        
        //Проверка Email на правильность
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        if(reg.test(email.val()) == false){
            error_email.html('Введен не корректный Email');
            return;
        }
        
        // Проверка пароля на минимальное количество символов 
        if(password.val().length < 6){
            error_pass.html('Пароль должен содержать минимум 6 символов');
            return;
        }
        
        // Проверка пароля на допустимые символы 
        var reg = /^([A-Za-z0-9_\-!@\(\)\^\$\%\*=#\+\&;:№\.\?]+)$/;
        if(reg.test(password.val()) == false){
            error_pass.html('Пароль содержит недопустимые симолы. Разрешенные спецсимволы: !@#$%^&*()_-+=:;№?');
            return;
        }
        
        form.find('input').each(function(){
            // Если это чекбокс "Запомнить меня"
            if($(this).attr('name') == 'remember_me'){
                // То нам нужен его prop(), а не val()
                Data[$(this).attr('name')] = $(this).prop('checked');
            }else{
                // Все остальные поля берем val()
                Data[$(this).attr('name')] = $(this).val();
            }
        });
        
        /**
         * Если в корзине что то есть,
         * соберем все товары
         */
        if(
            JSON.parse( JSON.stringify(localStorage.getItem('backet')) ) != '{}' &&
            localStorage.getItem('backet') != null
          ){
            var Backet = JSON.parse(localStorage.getItem('backet'));
            // Создаем объект "корзина"
            Data['backet'] = {};
            var Product = {};
            
            /**
             * Собираем только одни штрихкоды
             * ==============================
             * Заморочил не много через лишний объект "Product",
             * но это для того чтобы использовать общий метод класса
             * для добавления товаров в БД
             */
            for(key in Backet){
                Product = {};
                Product['barcode'] = Backet[key];
                
                Data['backet'][i] = Product;
                i++;
            }
        }
        
//        cl(Data);
//        return;
        
        $.ajax({
            url:form.attr('action'),
            type:form.attr('method'),
            cashe:'false',
            dataType:'json',
            data:Data,
            beforeSend:function(){ load.fadeIn(100); }
        }).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));
            // Если авторизация успешна
            if(data.status == 200){
                LoadAlert(data.header,data.message,3000,data.type_message);
                
                // Заполняем скрытое поле "ID пользователя"
                user_id.val(data.user_id);
                // Заполняем скрытое поле "авторизован ли пользователь"
                user_auth.val('1');
                
                /**
                 * Прячем панель (войти/авторизация)
                 * и показываем панель пользователя
                 */
                area_log_auth.fadeOut(100,function(){
                    user_authorized.fadeIn(100);
                });
                
                // Закрываем форму авторизации
                close.trigger('click');
                
                // Если есть товары в корзине БД
                if(typeof data.backet_products !== 'undefined'){
                    /**
                     * Заполняем корзину в top_panel товарами из БД,
                     * заменим товары из localStorage товарами из БД
                     */
                    backet.find('.list-products').html(data.backet_products);
                    
                    // Считаем товар в корзине top_panel общее количество и общую стоимость
                    if(typeof $('.list-products').html() !== 'undefined'){
                        $('.list-products .cart-single-wraper').each(function(){
                            total_payment+=Number($(this).find('.cart-price .price').text());
                            total_count_product++;
                        });
                    }
                    // Прячем строку "Товаров пока нет" и показываем список товаров
                    no_products.hide(10,function(){ cart_content.show(); });

                    // Меняем итоговую сумму к оплате
                    backet.find('.cart-subtotal .total-summ').html(
                        number_format(total_payment,2,'.',' ')
                    );

                    // Количество элементов в корзине
                    count_product_backet.html(total_count_product);
                    
                    // Если страница корзина
                    if(type_basket == 'main-bt'){
                        
                        cart_page.find('table.cart tbody').html(data.list_backet_products);

                        // Меняем итоговую сумму к оплате
                        process_cart_total.html(
                            number_format(total_payment,2,'.',' ')
                        );

                        list_no_products.fadeOut(200,function(){
                            list_is_products.fadeIn(200);
                        });

                    }

                }else{
                    // Прячем список товаров и показываем строку "Товаров пока нет"
                    cart_content.hide(10,function(){ no_products.show(); });

                    // Итоговую сумму к оплате ставим в 0
                    backet.find('.cart-subtotal .total-summ').html(total_payment);

                    // Количество элементов в корзине тоже в 0
                    count_product_backet.html(total_count_product);
                    
                }
                
                // Очистим localStorage(backet)
                localStorage.clear();
                
                /**
                 * Если пользователь авторизуется со страницы подтвержения Email
                 * то направим пользователя на странцу настроек профиля
                 */
                var verification = window.location.search.replace( '?', '');
                if (
                    ~verification.indexOf('email') || 
                    ~verification.indexOf('verification_key')
                ) {
                    location.href = '/user/';
                }
                
                /**
                 * Если пользователь авторизуется на странице "Корзина"
                 * перезагрузим страицу
                 */
                var pathname = window.location.pathname;
                if (~pathname.indexOf('cart')){
                    location.reload();
                }
                
            }else{
                popUp('#modal-auth',data.message,data.type_message);
            }
            load.fadeOut(100);
        }).fail(function(data){
//            res.html('Fail<br>'+JSON.stringify(data));
            LoadAlert('Внимание','Ошибка',3000,'error');
            load.fadeOut(100);
        });
        
    });
    
    /**
     * Кнопка "Выйти"
     */
    $('[name=logout]').on('click',function(){
        
        var $this = $(this),
            load = $this.find('img'),
            res = $('.res'),
            area_log_auth = $('.area-log-auth'),
            user_authorized = $('.user-authorized'),
            user_id = $('[name=user_id]'),
            user_auth = $('[name=user_auth]'),
            Data = {};
        
        Data[$('.csrf').attr('name')] = $('.csrf').val();
        
//        cl(Data);
//        return;
        
        $.ajax({
            url:$this.attr('data-url'),
            type:$this.attr('method'),
            cashe:'false',
            dataType:'json',
            data:Data,
            beforeSend:function(){ load.fadeIn(100); }
        }).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));
            
            // Скрытое поле "ID пользователя" ставим в 0
            user_id.val('0');
            // Скрытое поле "авторизован ли пользователь" ставим в 0
            user_auth.val('0');
            
            user_authorized.fadeOut(100,function(){
                area_log_auth.fadeIn(100);
                location.reload();
            });
            load.fadeOut(100);
        }).fail(function(data){
            LoadAlert('Внимание','Ошибка',3000,'error');
//            res.html('Fail<br>'+JSON.stringify(data));
            load.fadeOut(100);
        });
        
    });
    
    /**
     * Страница "Настройки профиля"
     * ============================
     * Кнопка "Подтвердить"
     */
    $('.urpe button[name=btn_email_verification]').on('click',function(){
        
        var $this = $(this),
            form = $('.urpe'),
            load = $this.find('img'),
            res = form.find('.res'),
            email = form.find('[name=email_verification]'),
            Data = {};
        
        // Скрываем все информационные окна
        cea();
        
        // Проверка логин и пароль на пустоту
        if(email.val() == ''){
            LoadAlert('Внимание','Поле Email должно быть заполнено',4000,'warning');
            return;
        }
        
        //Проверка Email на правильность
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        if(reg.test(email.val()) == false){
            LoadAlert('Внимание','Введен не корректный Email',4000,'warning');
            return;
        }
        
        form.find('.wrap-email-verification input').each(function(){
            Data[$(this).attr('name')] = $(this).val();
        });
        
//        cl(Data);
//        return;
        
        $.ajax({
            url:$this.attr('data-url'),
            type:$this.attr('method'),
            cashe:'false',
            dataType:'json',
            data:Data,
            beforeSend:function(){ load.fadeIn(100); }
        }).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));
            // Если Email отправлен успешно
            if(data.status == 200){
                popUp('.urpe',data.message);
                setTimeout(function(){
                    // Скрываем все информационные окна
                    cea();
                },20000);
            }else{ popUp('.urpe',data.message); }
            load.fadeOut(100);
        }).fail(function(data){
//            res.html('Fail<br>'+JSON.stringify(data));
            load.fadeOut(100);
        });
        
    });
    
    /**
     * Страница "Настройки профиля"
     * Страница "Корзина"
     * ============================
     * Поле ввода "Телефон"
     * Маска для поля
     */
    $('.urpe [name=phone],.main-bt [name=phone]').mask('+7(999)999-99-99');
    
    /**
     * Страница "Настройки профиля"
     * ============================
     * Поля ввода "Текущий пароль" "Новый пароль" "Повторите новый пароль"
     * Если в одном из них происходит ввод, то показываем звездочки
     * на обязательных полях паролей
     */
    $('.user-data').find('[type=password]').on('input',function(){
        var pass = false;
        $('.wrap-passwords').find('input').each(function(){
            if($(this).attr('name') == 'current_password') return;
            if($(this).val() != '') pass = true;
        });
        if(pass) $('.wrap-passwords').find('em').fadeIn(50);
        else $('.wrap-passwords').find('em').fadeOut(50);
    });
    
    /**
     * Страница "Настройки профиля"
     * ============================
     * Кнопка "Сохранить"
     */
    $('.urpe a.save-user-data').on('click',function(e){
        
        var $this = $(this),
            form = $('.user-data'),
            load = $this.find('img'),
            res = $('.res'),
            res2 = $('.res2'),
            first_name = form.find('[name=first_name]'),
            email = form.find('[name=email]'),
            empty_input = false,
            passwords_error = false,
            nothing_change = false,
            change_email_message = false,
            phone_not_confirm = form.find('.data-not-confirm.type-phone'),
            email_not_confirm = form.find('.data-not-confirm.type-email'),
            email_login = form.find('.save-user-data').attr('data-login'),
            Data = {};
        
        // Скрываем все информационные окна
        cea();
        
        //Проверка Email на правильность
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
        if(reg.test(email.val()) == false){
            LoadAlert('Внимание','Введен не корректный Email',4000,'warning');
            return;
        }
        
        form.find('input').each(function(){
            
            /**
             * Если был введен новый пароль
             * то проверим, заполнены ли поля
             * "текущий пароль" и "подтверждение нового пароля"
             */
            if($(this).attr('name') == 'new_password' || typeof Data['new_password'] !== 'undefined'){
                if($(this).val() != ''){
                    if(
                        form.find('[name=current_password]').val() == '' ||
                        form.find('[name=confirm_password]').val() == ''
                      ){ empty_input = true; return; }
                    
                    // Проверка новых паролей на совпадение
                    if(
                        form.find('[name=new_password]').val() !=
                        form.find('[name=confirm_password]').val()
                      ){ passwords_error = true; return; }
                    
                    Data[$(this).attr('name')] = '{"'+$(this).attr('data-change')+'":"'+$(this).val()+'"}';
                    
                }
            }
            
            // Проверка полей "Имя" и "Email" на пустоту
            if($(this).attr('name') == 'first_name' ||
               $(this).attr('name') == 'emial'
              ){ if($(this).val() == ''){ empty_input = true; } }
            
            /**
             * Если содержимое поля текущей итерации не совпадает с атрибутом "data-change"
             * значит это поле было редактировано и его нужно отправить в БД для изменения
             */
            if($(this).val() != $(this).attr('data-change')){
                
                /**
                 * csrf и поля паролей берем не в json формате,
                 * собираем их как обычно
                 */
                if(
                    $(this).attr('name') == csrf ||
                    $(this).attr('name') == 'current_password' ||
                    $(this).attr('name') == 'new_password' ||
                    $(this).attr('name') == 'confirm_password'
                  ){
                    Data[$(this).attr('name')] = $(this).val();
                }else{
                    // Если на итерации "email"
                    if($(this).attr('name') != 'email'){
                        Data[$(this).attr('name')] =
                        '{"'+$(this).attr('data-change')+
                            '":"'+$(this).val().toLowerCase()+'"}';
                    } 
                }
                
//                nothing_change = true; return;
                
            }// if() проверка по data-change
            
            /**
             * Если итерация на поле "email"
             * поле Email берем в выборку независимо от изменения
             */
            if($(this).attr('name') == 'email'){
                
                /**
                 * Атрибут "data-value-email" - содержит в себе неподтвержденный Email
                 * Если "value" поля Email не равняется Email'у атрибута "data-value-email"
                 * значит неподтвержденный Email был изменен
                 */
                if($(this).val().toLowerCase() != $(this).attr('data-value-email')){
                    /**
                     * Значит на сервер нужно отправить (второй)
                     * неподтвержденный Email.
                     * А в основную выборку попадет новый Email (третий)
                     */
                    var _val = $(this).attr('data-value-email');
                    Data['not_confirm_email'] = '{"not_confirm_email":"'+_val+'"}';
                }
                
                Data[$(this).attr('name')] =
                        '{"'+$this.attr('data-login')+'":"'+$(this).val().toLowerCase()+'"}';

            }
            
        });
        
        // Проверка обязательных полей на пустоту
        if(empty_input){
            LoadAlert('Внимание','Заполните все поля отмеченные звездочкой <em>*</em>',4000,'warning');
            return;
        }
        
        // Проверка новых паролей на совпадение
        if(passwords_error){
            LoadAlert('Внимание','Новые пароли не совпадают',4000,'warning');
            return;
        }
        
        /**
         * Если новый пароль не был заполнен
         * то удаляем из выборки все поля с паролями
         */
        if(typeof Data['new_password'] === 'undefined'){
            delete Data['current_password'];
            delete Data['new_password'];
            delete Data['confirm_password'];
        }
        
        // Если небыло ни каких изменений
//        if(!nothing_change) return;
        
        /**
         * В выборке всегда будет не менее одного элемента
         * csrf вседа будет в объекте выборки
         */
//        if(Object.keys(Data).length == '1') return;
        
//        cl(Data);
//        return;
        
        $.ajax({
            url:$this.attr('data-url'),
            type:$this.attr('method'),
            cashe:'false',
            dataType:'json',
            data:Data,
            beforeSend:function(){ load.fadeIn(100); }
        }).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));
            
            // Если status успешно
            if(data.status == 200){
                if(typeof data.user_data !== 'undefined' && data.user_data != null){
                    // Делаем объект из JSON user_data
                    var user_data = JSON.parse(JSON.stringify(data.user_data));
                    
                    /**
                     * Из объекта "user_data"
                     * Получам массив ключей
                     */
                    var keys = Object.keys(user_data);

                    /**
                     * Перебирая массив ключей
                     * Расставляем все данные по полям
                     * ориентируясь по атрибутам "name"
                     * подставляя элементы массива ключей в выборку
                     * обращаясь по текущему элементу массива ключей
                     * к атрибуту "name"
                     */
                    for(var i=0;i<keys.length;i++){
                        if(keys[i] == 'phone'){
                            var obj_phone = JSON.parse(JSON.stringify(user_data[keys[i]]));
                            form.find('[name='+keys[i]+']').val((obj_phone.text));
                            form.find('[name='+keys[i]+']').attr('data-change',(obj_phone.text));
                            
                            if(obj_phone.confirm == '0')
                                phone_not_confirm.fadeIn(100);

                        }else if(keys[i] == 'email'){
                            var obj_email = JSON.parse(JSON.stringify(user_data[keys[i]]));
                            
                            form.find('[name='+keys[i]+']').val(obj_email.text);
                            form.find('[name='+keys[i]+']').attr('data-change',obj_email.text);
                            /**
                             * Если пользователь сохранил Email, который такой же как логин
                             * то при заполнении данных в HTML, атрибут "data-value-email"
                             * нужно сделать пустым.
                             */
                            if(obj_email.text == email_login){
        form.find('[name='+keys[i]+']').attr('data-value-email','');
                            }else{
                                /**
                                 * При смене Email
                                 * поставим флаг в true, 
                                 * чтобы показать пользователю сообщение
                                 */
                                change_email_message = true;
        form.find('[name='+keys[i]+']').attr('data-value-email',obj_email.text);
                            }
                            
                            if(obj_email.confirm == '0'){
                                email_not_confirm.fadeIn(100);
                                email_not_confirm.find('.glyphicon-question-sign').hide();
//                                popUp('.user-data','Вы изменили Email.<br>Письмо со ссылкой для подтверждения нового адреса электронной почты, отправлено на ваш Email.<br>Ссылка будет действительна в течении 15 минут...<br><font color="red">После подтверждения Email ваша предыдущая почта логином являться уже не будет.<br>Ваш логин изменится на ваш новый Email!</font color="red">');
                            }else{
                                email_not_confirm.fadeOut(100);
                                email_not_confirm.find('.glyphicon-question-sign').hide();
                            }
                        }else{
                            form.find('[name='+keys[i]+']').val((user_data[keys[i]]));
                            form.find('[name='+keys[i]+']').attr('data-change',(user_data[keys[i]]));
                        }
                    }
                }
                
                if(change_email_message){
                    popUp('.urpe','Смена адреса электронной почты вступит в силу после первого входа с использованием нового e-mail');
                }
                
                if(typeof data.email_already_is !== 'undefined'){
                    popUp('.urpe',data.email_already_is,'warning');
                }
                
                form.find('[type=password]').val('');
                LoadAlert(data.header,data.message,3000);
            }else{
                LoadAlert(data.header,data.message,5000,data.type_message);
            }
            load.fadeOut(100);
        }).fail(function(data){
//            res.html('Fail<br>'+JSON.stringify(data));
            load.fadeOut(100);
        });
        
    });
    
    /**
     * Страница "Настройки профиля"
     * ============================
     * Ссылка "Удалить аккаунт"
     * ------------------------
     * Восстанавливаем содержимое модального окна
     * в первоначальное состояние
     */
    $('.urpe [data-target=#confirm-delete]').on('click',function(){
        var form = $('.urpe'),
            modal_title = form.find('.modal-title'),
            modal_body = form.find('.modal-body');
        
        modal_title.html('Подтверждение удаления аккаунта');
        modal_body.find('.text-delete-confirm').show();
        modal_body.find('.reload-after-delete').hide();
        modal_body.find('.errors').html('').hide();
    });
    
    /**
     * Страница "Настройки профиля"
     * ============================
     * Кнопка "Удалить аккаунт"
     */
    $('.urpe #confirm-delete .btn-delete-profile').on('click',function(e){
        
        var $this = $(this),
            form = $('.urpe'),
            load = $this.find('img'),
            res = $('.res'),
            modal_title = form.find('.modal-title'),
            modal_body = form.find('.modal-body'),
            Data = {};
        
        // Скрываем все информационные окна
        cea();
        
        Data[csrf] = $('meta[name=csrf-token]').attr('content');
        
//        cl(Data);
//        return;
        
        $.ajax({
            url:$this.attr('data-url'),
            type:$this.attr('method'),
            cashe:'false',
            dataType:'json',
            data:Data,
            beforeSend:function(){ load.fadeIn(100); }
        }).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));
            
            // Если status успешно
            if(data.status == 200){
                modal_title.html(data.header);
                modal_body.find('.text-delete-confirm').fadeOut(100,function(){
                    modal_body.find('.reload-after-delete').fadeIn(100);
                    setTimeout(function(){location.reload()},1000);
                });
                LoadAlert(data.header,data.message,3000);
            }else{
                modal_title.html(data.header);
                modal_body.find('.text-delete-confirm').fadeOut(100,function(){
                    modal_body.find('.errors').html(data.message).fadeIn(100);
                });
                modal_body.find('.reload-after-delete').hide();
                LoadAlert(data.header,data.message,5000,data.type_message);
            }
            load.fadeOut(100);
        }).fail(function(data){
//            res.html('Fail<br>'+JSON.stringify(data));
            load.fadeOut(100);
        });
        
    });
    
    /**
     * Страница "single_product"
     * =========================
     * Кнопка "Добавить в корзину"
     */
    $('.add-backet,.single-product-description .add-cart').on('click',function(){
        
        var $this = $(this),
            form = $('.sept'),
            load = $this.find('img'),
            user_auth = $('[name=user_auth]').val(),
            res = form.find('.res'),
            backet = $('.cart-content-wraper'),
            cart_content = backet.find('.main-cart-content'),
            no_products = backet.find('.no-products'),
            count_product_backet = $('.header-cart .count-backet'),
            html = '',
            img = '',
            total_payment = 0,
            total_count_product = 0,
            i=0,
            new_price = 0,
            old_price = 0,
            product_is = false,
            Product = {},
            Backet = {},
            BacketView = {},
            Data = {};
        
        /**
         * Если пользователь НЕ авторизован,
         * для корзины используем localStorage
         */
        if(user_auth == '0'){
            
            /**
             * Если на странице есть выбор размеров.
             * Если у товара есть размеры
             */
            if(form.find('.size-blocks li button').attr('disabled') != 'disabled'){
                // Соберем их в localStorage
                form.find('.size-blocks li').each(function(){
                    
                    // Неотмеченные пропускаем
                    if($(this).find('button').attr('data-selected') == '') return;
                    product_is = true;
                    
                    // Если в localStorage ещё ничего нет
                    if(localStorage.getItem('backet') === null){
                        
                        if(form.find('.old-price del').text() != form.find('[data-visible=visible] .new-price .price').text()){
                            old_price = form.find('.old-price del').text();
                            new_price = form.find('[data-visible=visible] .new-price .price').text();
                        }else{
                            old_price = form.find('[data-visible=visible] .new-price .price').text();
                            new_price = form.find('[data-visible=visible] .new-price .price').text();
                        }
                        
                        if(form.find('.discount span').text() != ''){
                            discount = form.find('.discount span').text();
                        }else discount = 0;
                        
                        // Штрихкод размера
                        Backet['product_'+0] = $(this).find('button').attr('data-barcode');
                        
                        BacketView['product_'+0] = {};
                        // Наименование товара
                        BacketView['product_'+0]['name'] = form.find('.label-product').text();
                        // Изображение
                        BacketView['product_'+0]['img'] = form.find('.tab-pane.active img').attr('data-img-name');
                        // Цена без скидки
                        BacketView['product_'+0]['old_price'] = old_price;
                        // Цена со скидкой
                        BacketView['product_'+0]['new_price'] = new_price;
                        // Скидка
                        BacketView['product_'+0]['discount'] = discount;
                        // Ключ товара в массиве
                        BacketView['product_'+0]['product_key'] = 'product_'+0;
                        
                        /**
                         * В корзину top_panel
                         * добавляем строку товара
                         * собранную через HTML шаблон
                         */
                        
                        if(form.find('.img-single-product').attr('data-img-name') == 'img_default')
                            img = '/images/product/01.jpg';
                        else img = '../../../common/files/photos'+form.find('.img-single-product').attr('data-img-name');
                        
                        html = renderTemplate('#cart-single-top',{
                            "barcode":$(this).find('button').attr('data-barcode'),
                            "path_img":img,
                            "name":form.find('.label-product').text(),
                            "new_price":number_format(new_price,2,'.',''),
                            "product_key":'product_'+0,
                        });

                        backet.find('.list-products').append(html);
                        
                        var jsonBacket = JSON.stringify(Backet);
                        var jsonBacketView = JSON.stringify(BacketView);

                        // Добавление в корзину
                        localStorage.setItem('backet', jsonBacket);
                        localStorage.setItem('backet_view', jsonBacketView);
                        
                        
                    }else{
                        
                        Backet = JSON.parse(localStorage.getItem('backet'));
                        BacketView = JSON.parse(localStorage.getItem('backet_view'));

                        var j = 0;
                        for(key in Backet){
                            Backet['product_'+j] = Backet[key];
                            BacketView['product_'+j] = BacketView[key];
                            j++;
                        }
                        
                        if(form.find('.old-price del').text() != form.find('[data-visible=visible] .new-price .price').text()){
                            old_price = form.find('.old-price del').text();
                            new_price = form.find('[data-visible=visible] .new-price .price').text();
                        }else{
                            old_price = form.find('[data-visible=visible] .new-price .price').text();
                            new_price = form.find('[data-visible=visible] .new-price .price').text();
                        }
                        
                        if(form.find('.discount span').text() != ''){
                            discount = form.find('.discount span').text();
                        }else discount = 0;
                        
                        // Штрихкод размера
                        Backet['product_'+j] = $(this).find('button').attr('data-barcode');
                        
                        BacketView['product_'+j] = {};
                        // Наименование товара
                        BacketView['product_'+j]['name'] = form.find('.label-product').text();
                        // Изображение
                        BacketView['product_'+j]['img'] = form.find('.tab-pane.active img').attr('data-img-name');
                        // Цена без скидки
                        BacketView['product_'+j]['old_price'] = old_price;
                        // Цена со скидкой
                        BacketView['product_'+j]['new_price'] = new_price;
                        // Скидка
                        BacketView['product_'+j]['discount'] = discount;
                        // Ключ товара в массиве
                        BacketView['product_'+j]['product_key'] = 'product_'+j;
                        
                        /**
                         * В корзину top_panel
                         * добавляем строку товара
                         * собранную через HTML шаблон
                         */
                        if(form.find('.img-single-product').attr('data-img-name') == 'img_default')
                            img = '/images/product/01.jpg';
                        else img = '../../../common/files/photos'+form.find('.img-single-product').attr('data-img-name');
                        
                        html = renderTemplate('#cart-single-top',{
                            "barcode":$(this).find('button').attr('data-barcode'),
                            "path_img":img,
                            "name":form.find('.label-product').text(),
                            "new_price":number_format(new_price,2,'.',''),
                            "product_key":'product_'+j,
                        });

                        backet.find('.list-products').append(html);
                        
                        var jsonBacket = JSON.stringify(Backet);
                        var jsonBacketView = JSON.stringify(BacketView);

                        // Добавление в корзину
                        localStorage.setItem('backet', jsonBacket);
                        localStorage.setItem('backet_view', jsonBacketView);
                        
                    }
                    
                    i++;
                    
                });
                
                // Считаем товар в корзине top_panel общее количество и общую стоимость
                if(typeof $('.list-products').html() !== 'undefined'){
                    $('.list-products .cart-single-wraper').each(function(){
                        total_payment+=Number($(this).find('.cart-price .price').text());
                        total_count_product++;
                    });
                }
                
                // Выбранным размерам по ставим зеленую рамку
                form.find('.size-blocks li').each(function(){
                    if($(this).find('button').attr('data-selected') != ''){
                        $(this).find('button').attr('data-selected','');
                        $(this).find('button').css({'border-color':'#21db09'});
                    }
                });
                
                if(product_is){
                    // Прячем строку "Товаров пока нет" и показываем список товаров
                    no_products.hide(10,function(){ cart_content.show(); });

                    // Меняем итоговую сумму к оплате
                    backet.find('.cart-subtotal .total-summ').html(total_payment);

                    // Количество элементов в корзине
                    count_product_backet.html(total_count_product);
                }
                
            }else{
            /**
             * Если у товара нет размеров
             */
                
                // Если в localStorage ещё ничего нет
                if(localStorage.getItem('backet') === null){
                    
                    if(form.find('.old-price del').text() != form.find('[data-visible=visible] .new-price .price').text()){
                        old_price = form.find('.old-price del').text();
                        new_price = form.find('[data-visible=visible] .new-price .price').text();
                    }else{
                        old_price = form.find('[data-visible=visible] .new-price .price').text();
                        new_price = form.find('[data-visible=visible] .new-price .price').text();
                    }

                    if(form.find('.discount span').text() != ''){
                        discount = form.find('.discount span').text();
                    }else discount = 0;

                    // Штрихкод размера
                    Backet['product_'+0] = form.find('.label-product').attr('data-barcode');

                    BacketView['product_'+0] = {};
                    // Наименование товара
                    BacketView['product_'+0]['name'] = form.find('.label-product').text();
                    // Изображение
                    BacketView['product_'+0]['img'] = form.find('.tab-pane.active img').attr('data-img-name');
                    // Цена без скидки
                    BacketView['product_'+0]['old_price'] = old_price;
                    // Цена со скидкой
                    BacketView['product_'+0]['new_price'] = new_price;
                    // Скидка
                    BacketView['product_'+0]['discount'] = discount;
                    // Ключ товара в массиве
                    BacketView['product_'+0]['product_key'] = 'product_'+0;
                    
                    /**
                     * В корзину top_panel
                     * добавляем строку товара
                     * собранную через HTML шаблон
                     */
                        
                    if(form.find('.img-single-product').attr('data-img-name') == 'img_default')
                        img = '/images/product/01.jpg';
                    else img = '../../../common/files/photos'+form.find('.img-single-product').attr('data-img-name');
                    
                    html = renderTemplate('#cart-single-top',{
                        "barcode":$(this).find('button').attr('data-barcode'),
                        "path_img":img,
                        "name":form.find('.label-product').text(),
                        "new_price":number_format(new_price,2,'.',''),
                        "product_key":'product_'+0,
                    });

                    backet.find('.list-products').append(html);

                    var jsonBacket = JSON.stringify(Backet);
                    var jsonBacketView = JSON.stringify(BacketView);

                    // Добавление в корзину
                    localStorage.setItem('backet', jsonBacket);
                    localStorage.setItem('backet_view', jsonBacketView);

                }else{

                    Backet = JSON.parse(localStorage.getItem('backet'));
                    BacketView = JSON.parse(localStorage.getItem('backet_view'));

                    var j = 0;
                    for(key in Backet){
                        Backet['product_'+j] = Backet[key];
                        BacketView['product_'+j] = BacketView[key];
                        j++;
                    }
                        
                    if(form.find('.old-price del').text() != form.find('[data-visible=visible] .new-price .price').text()){
                        old_price = form.find('.old-price del').text();
                        new_price = form.find('[data-visible=visible] .new-price .price').text();
                    }else{
                        old_price = form.find('[data-visible=visible] .new-price .price').text();
                        new_price = form.find('[data-visible=visible] .new-price .price').text();
                    }

                    if(form.find('.discount span').text() != ''){
                        discount = form.find('.discount span').text();
                    }else discount = 0;

                    // Штрихкод размера
                    Backet['product_'+j] = form.find('.label-product').attr('data-barcode');

                    BacketView['product_'+j] = {};
                    // Наименование товара
                    BacketView['product_'+j]['name'] = form.find('.label-product').text();
                    // Изображение
                    BacketView['product_'+j]['img'] = form.find('.tab-pane.active img').attr('data-img-name');
                    // Цена без скидки
                    BacketView['product_'+j]['old_price'] = old_price;
                    // Цена со скидкой
                    BacketView['product_'+j]['new_price'] = new_price;
                    // Скидка
                    BacketView['product_'+j]['discount'] = discount;
                    // Ключ товара в массиве
                    BacketView['product_'+j]['product_key'] = 'product_'+j;

                    /**
                     * В корзину top_panel
                     * добавляем строку товара
                     * собранную через HTML шаблон
                     */
                    if(form.find('.img-single-product').attr('data-img-name') == 'img_default')
                        img = '/images/product/01.jpg';
                    else img = '../../../common/files/photos'+form.find('.img-single-product').attr('data-img-name');
                    
                    html = renderTemplate('#cart-single-top',{
                        "barcode":form.find('.label-product').attr('data-barcode'),
                        "path_img":img,
                        "name":form.find('.label-product').text(),
                        "new_price":number_format(new_price,2,'.',''),
                        "product_key":'product_'+j,
                    });

                    backet.find('.list-products').append(html);

                    var jsonBacket = JSON.stringify(Backet);
                    var jsonBacketView = JSON.stringify(BacketView);

                    // Добавление в корзину
                    localStorage.setItem('backet', jsonBacket);
                    localStorage.setItem('backet_view', jsonBacketView);

                }
                
                /**
                 * Ставим флаг в true, чтобы не было предупреждения
                 * о том, что размер не выбран.
                 */
                product_is = true;
                
                // Считаем товар в корзине top_panel общее количество и общую стоимость
                if(typeof $('.list-products').html() !== 'undefined'){
                    $('.list-products .cart-single-wraper').each(function(){
                        total_payment+=Number($(this).find('.cart-price .price').text());
                        total_count_product++;
                    });
                }
                
                // Прячем строку "Товаров пока нет" и показываем список товаров
                no_products.hide(10,function(){ cart_content.show(); });

                // Меняем итоговую сумму к оплате
                backet.find('.cart-subtotal .total-summ').html(total_payment);

                // Количество элементов в корзине
                count_product_backet.html(total_count_product);
            }
            
            if(!product_is){
                LoadAlert('Внимание','Вы не выбрали размер',3000,'warning');
                return;
            }
            
            LoadAlert('Успешно','Товар добавлен в корзину',3000);
            
        }else{
        /**
         * Если пользователь авторизован
         * для корзины используем БД
         */
            
            /**
             * Если на странице есть выбор размеров.
             * Если у товара есть размеры
             */
            if(form.find('.size-blocks li button').attr('disabled') != 'disabled'){
                // Соберем их в объект
                form.find('.size-blocks li').each(function(){
                    
                    // Неотмеченные пропускаем
                    if($(this).find('button').attr('data-selected') == '') return;
                    product_is = true;
                    
                    Product[i] = {};
                    // Штрихкод
                    Product[i]['barcode'] = $(this).find('button').attr('data-barcode');
                    
                    i++;
                    
                });
                
            }else{
            /**
             * Если у товара нет размеров
             * Ставим флаг в true, чтобы не было предупреждения
             * о том, что размер не выбран.
             */
                product_is = true;
                
                Product[i] = {};
                // Штрихкод
                Product[i]['barcode'] = form.find('.label-product').attr('data-barcode');
            }
            
            Data['backet'] = {};
            Data['backet'] = Product;
            
            if(!product_is){
                LoadAlert('Внимание','Вы не выбрали размер',3000,'warning');
                return;
            }
            
//            cl(Data);
//            return;

            $.ajax({
                url:$this.attr('data-url'),
                type:$this.attr('method'),
                cashe:'false',
                dataType:'json',
                data:Data,
                beforeSend:function(){ load.fadeIn(100); }
            }).done(function(data){
//                res.html('Done<br>'+JSON.stringify(data));
                
                LoadAlert(data.header,data.message,2000,data.type_message);
                
                if(data.status == 200){
                    
                    // Если есть товары в корзине БД
                    if(
                        typeof data.backet_products !== 'undefined' &&
                        data.backet_products != ''
                    ){
                        /**
                         * Заполняем корзину в top_panel товарами из БД,
                         * заменим товары из localStorage товарами из БД
                         */
                        backet.find('.list-products').html(data.backet_products);

                        // Считаем товар в корзине top_panel общее количество и общую стоимость
                        if(typeof $('.list-products').html() !== 'undefined'){
                            $('.list-products .cart-single-wraper').each(function(){
                                total_payment+=Number($(this).find('.cart-price .price').text());
                                total_count_product++;
                            });
                        }
                        
                        // Выбранным размерам по ставим зеленую рамку
                        form.find('.size-blocks li').each(function(){
                            if($(this).find('button').attr('data-selected') != ''){
                                $(this).find('button').attr('data-selected','');
                                $(this).find('button').css({'border-color':'#21db09'});
                            }
                        });
                        
                        // Прячем строку "Товаров пока нет" и показываем список товаров
                        no_products.hide(10,function(){ cart_content.show(); });

                        // Меняем итоговую сумму к оплате
                        backet.find('.cart-subtotal .total-summ').html(
                            number_format(total_payment,2,'.',' ')
                        );

                        // Количество элементов в корзине
                        count_product_backet.html(total_count_product);
                        
                    }else{
                        // Прячем список товаров и показываем строку "Товаров пока нет"
                        cart_content.hide(10,function(){ no_products.show(); });

                        // Итоговую сумму к оплате ставим в 0
                        backet.find('.cart-subtotal .total-summ').html(total_payment);

                        // Количество элементов в корзине тоже в 0
                        count_product_backet.html(total_count_product);
                    }
                    
                }
                load.fadeOut(100);
            }).fail(function(data){
//                res.html('Fail<br>'+JSON.stringify(data));
                load.fadeOut(100);
            });
            
        }
        
    });
    
    /**
     * Страница "Корзина"
     * =========================
     * Кнопки "продолжить"
     * по функционалу такие же как кнопки (Корзина/Оформление заказа/Подтвердить заказ)
     * И кнопки "Вкладок" (01 Корзина/02 Оформление заказа/03 Подтвердить заказ)
     */
    $('.main-bt .steps').on('click',function(){
        
        var $this = $(this),
            form = $('.main-bt'),
            type = $this.attr('data-step'),
            error = false;
        
        // Закрываем все alert'ы
        cea();
        
        switch(type){
            case 'to-2':
                form.find('.nav li').removeClass('active');
                form.find('[href=#checkout]').parent().addClass('active');
                break;
            case 'to-3':
                if(!checkInputs()) error = true;
                else{
                    form.find('.nav li').removeClass('active');
                    form.find('[href=#complete-order]').parent().addClass('active');
                }
                break;
            case 's2':
                break;
            case 's3':
                if(!checkInputs()) error = true;
                break;
        }
        
        if(error){
            popUp('.alert-s2','Поля отмеченые звездочкой обязательны','warning');
            return false;
        }
        
    });
    
    /**
     * Страница "Корзина"
     * ==================
     * Кнопка "Подтвердить заказ"
     */
    $('.main-bt .add-order').on('click',function(e){
        
        var $this = $(this),
            form = $('.main-bt'),
            load = $this.find('img'),
            res = $('.res'),
            user_auth = $('[name=user_auth]').val(),
            count_product_backet = $('.header-cart .count-backet'),
            backet = $('.cart-content-wraper'),
            cart_content = backet.find('.main-cart-content'),
            no_products = backet.find('.no-products'),
            modal_title = form.find('.modal-title'),
            modal_body = form.find('.modal-body'),
            list_no_products = form.find('.no-products'),
            list_is_products = form.find('.is-products'),
            i=0,
            Data = {};
        
        // Скрываем все информационные окна
        cea();
        
        Data[csrf] = $('meta[name=csrf-token]').attr('content');
        
        Data['user'] = {};
        
        // Получим поля "информация о пользователе"
        form.find('.user-data input').each(function(){
            // Пустые поля пропускаем
            if($(this).val() == '') return;
            // Отключенные поля тоже пропускаем
            if($(this).attr('disabled') == 'disabled') return;
            Data['user'][$(this).attr('name')] = $(this).val();
        });
        
        // На свякий случай ещё раз проверим имя и телефон на пустоту
        if(Data['user']['first_name'] == '' || Data['user']['phone'] == ''){
            LoadAlert('Внимание','На шаге 02.<br>Поля отмеченные звездочкой должны быть заполнены!',5000,'warning');
            return;
        }
        
        /**
         * Если пользователь не авторизован
         * Работаем с localStorage
         */
        if(user_auth == '0'){
            
            /**
             * Проверим localStorage на пустоту (на всякий случай)
             * Если в localStorage ничего нет, то ничего не делаем
             */
            if(localStorage.getItem('backet') === null){
                LoadAlert('Внимание','Корзниа пуста!',3000,'warning');
                return;
            }

            // Получим список штрихкодов из localStorage
            Data['basket'] = JSON.parse(localStorage.getItem('backet'));
            
        }else{
        /**
         * Если пользователь авторизован
         * Собираем штрихкоды товара - со страницы
         */
            var Pt = {};
            
            $('.checkout-area.table tr[data-key]').each(function(){
                Pt[$(this).attr('data-key')] = $(this).find('[name=barcode]').val();
            });
            
            Data['basket'] = Pt;
        }
        
//        cl(Data);
//        return;
        
        $.ajax({
            url:$this.attr('data-url'),
            type:$this.attr('method'),
            cashe:'false',
            dataType:'json',
            data:Data,
            beforeSend:function(){ load.fadeIn(100); }
        }).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));
            LoadAlert(data.header,data.message,5000,data.type_message);
            // Если status успешно
            if(data.status == 200){
                
                // Скрываем всю страницу "корзина" и показываем "Товаров пока нет"
                list_is_products.fadeOut(100,function(){
                    list_no_products.fadeIn(100);
                });
                
                // Количество элементов в корзине делаем 0
                count_product_backet.html('0');
                
                // Прячем строку "список товаров" и показываем "Товаров пока нет"
                cart_content.hide(10,function(){ no_products.show(); });
                
                // Очищаем localStorage
                localStorage.clear();
                
                popUp('.main-bt','Заказ успешно оформлен!');
                
                // Скролим страницу на верх
                window.scrollTo(0,0);
                
            }else{
                
            }
            load.fadeOut(100);
        }).fail(function(data){
//            res.html('Fail<br>'+JSON.stringify(data));
            load.fadeOut(100);
        });
        
    });
    
    /**
     * Страница "Отправка Email"
     * =========================
     * Кнопка "Отправить Email"
     */
    $('.eml .send-mail-test').on('click', function(){
        var $this = $(this),
            form = $('.eml'),
            res = form.find('.res'),
            load = $this.find('img'),
            type_mail = $this.attr('name'),
            Data = {};

        // Убираем с экрана все оповещающие окна 
        cea();
		
		Data[csrf] = $('meta[name=csrf-token]').attr('content'); 
        Data['send_mail'] = $('input[name=email]:checked').val();
        Data['type_mail'] = type_mail;

        $.ajax({
            url:$this.attr('action'),
            type:$this.attr('method'),
            cashe:'false',
            dataType:'json',
            data:Data,
            beforeSend:function(){
                load.fadeIn(100);
            }
        }).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));

            LoadAlert(data.header,data.message,live,data.type_message);
            if(data.status == 200){

            }else{
                popUp('.eml','Done !200<br>'+JSON.stringify(data),'danger');
            }

            load.fadeOut(100);
        }).fail(function(data){
//            res.html('Fail<br>'+JSON.stringify(data));
//            popUp('.eml','Fail<br>'+JSON.stringify(data));
            LoadAlert('Error','Ошибка PHP',live,'error');
            load.fadeOut(100);
        });
    });
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
   
    
    
    
    
    
    
    
    
    
    
    /**
     * Тестовая функция
     */
    $('.test2,.l-s').on('click',function(){
        var $this = $(this),
            key = $('[name=ls_key]').val(),
            value = $('[name=ls_value]').val(),
            ls = '';
        
        switch($this.attr('data-type')){
            case "LSClear":
                localStorage.clear();
                break;
            case "LSView":
                cl(localStorage.getItem('backet'));
                cl(localStorage.getItem('backet_view'));
                break;
        }
    });
    /**
     * Тестовая функция
     */
    $('.test').on('click',function(){
        
//        var verification = window.location.pathname;
//        cl(verification);
//        
//        return;
        
        var Data = {};
        
        Data['name'] = $('.i-test').val();
        
        var res = $('.res');
        $.ajax({
            url:'/ajax/debug',
            type:'post',
            cashe:'false',
            dataType:'json',
            data:Data,
            beforeSend:function(){
//                load.fadeIn(100);
            }
        }).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));
            
//            load.fadeOut(100);
        }).fail(function(data){
//            res.html('Fail<br>'+JSON.stringify(data));
//            load.fadeOut(100);
        });
    });
    
    
    
});// JQuery