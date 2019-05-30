/**
 * Удаление элемента товара
 * из корзины top_panel
 */
function removeProductFromBucket(obj){
    var $this = $(obj),
        form = $('.sept'),
        load = $this.find('img'),
        res = $('.res'),
        user_auth = $('[name=user_auth]').val(),
        product_key = $this.attr('data-product-key'),
        backet = $('.cart-content-wraper'),
        cart_content = backet.find('.main-cart-content'),
        no_products = backet.find('.no-products'),
        count_product_backet = $('.header-cart .count-backet'),
        html = '',
        i=0,
        j=0,
        total_payment = 0,
        total_count_product = 0,
        Data = {},
        Backet = {},
        BacketView = {},
        Backet2 = {},
        BacketView2 = {};
    
    // Если страница "корзина"
    if($('[name=type_basket]').val() == 'main-bt'){
        var cart_page = $('.main-bt'),
            list_no_products = cart_page.find('.no-products'),
            list_is_products = cart_page.find('.is-products'),
            process_cart_total = cart_page.find('.process-cart-total .p-c-t'),
            type_basket = $('[name=type_basket]').val();
    }
    
    /**
     * Если пользователь не автоизован
     * то работаем с localStorage
     */
    if(user_auth == '0'){
        Backet = JSON.parse(localStorage.getItem('backet'));
        BacketView = JSON.parse(localStorage.getItem('backet_view'));

        for(key in Backet){
            /**
             * Если ID удаляемого товара совпадает с ID товара итерации
             * удаляем из объекта товар
             */
            if(key == product_key){
                delete Backet[key];
                delete BacketView[key];
            }else{
                Backet2['product_'+i] = Backet[key];
                BacketView2['product_'+i] = BacketView[key];
                i++;
            }
        }
        
        // Удаляем строку товара из корзины в top_panel
        backet.find('[data-key='+product_key+']').remove();

        var jsonBacket = JSON.stringify(Backet2);
        var jsonBacketView = JSON.stringify(BacketView2);
        // Заменяем предыдущее знчение корзины на новое
        localStorage.setItem('backet', jsonBacket);
        localStorage.setItem('backet_view', jsonBacketView);

        if(JSON.stringify(Backet2) == '{}'){
            // Прячем строку "список товаров" и показываем "Товаров пока нет"
            cart_content.hide(10,function(){ no_products.show(); });

            // Количество элементов в корзине делаем 0
            count_product_backet.html('0');

            // Увсех размеров убираем зеленую рамку, делаем обычную рамку
            form.find('.size-blocks li').each(function(){
                $(this).find('button').attr('data-selected','');
                $(this).find('button').css({'border-color':'#d5d5d5'});
            });

            // Очищаем localStorage
            localStorage.clear();
        }
        
        // Считаем товар в корзине top_panel общее количество и общую стоимость
        if(typeof $('.list-products').html() !== 'undefined'){
            $('.list-products .cart-single-wraper').each(function(){
                $(this).find('.cart-single-wraper').attr('data-key','product_'+j);
                $(this).find('.remove a').attr('data-product-key','product_'+j);
                total_payment+=Number($(this).find('.cart-price .price').text());
                total_count_product++;
            });
        }

        // Меняем итоговую сумму к оплате
        backet.find('.cart-subtotal .total-summ').html(
            number_format(total_payment,2,'.',' ')
        );

        // Количество элементов в корзине
        count_product_backet.html(total_count_product);
        
        // Если страница "Корзина"
        if($('[name=type_basket]').val() == 'main-bt'){
            
            var cart_page = $('.main-bt'),
                list_no_products = cart_page.find('.no-products'),
                list_is_products = cart_page.find('.is-products'),
                process_cart_total = cart_page.find('.process-cart-total .p-c-t'),
                type_basket = $('[name=type_basket]').val(),
                res = $('.res');
            
            // Удаляем строку из списка товаров
            cart_page.find('.cart_item[data-key='+product_key+']').fadeOut(100,function(){
                cart_page.find('.cart_item[data-key='+product_key+']').remove();
            });
            
            process_cart_total.html(number_format(total_payment,2,'.',' '));
            
        }

        if(total_count_product == 0){
            // Прячем строку "список товаров" и показываем "Товаров пока нет"
            cart_content.hide(10,function(){ no_products.show(); });

            // Количество элементов в корзине делаем 0
            count_product_backet.html('0');

            // Увсех размеров убираем зеленую рамку, делаем обычную рамку
            form.find('.size-blocks li').each(function(){
                $(this).find('button').attr('data-selected','');
                $(this).find('button').css({'border-color':'#d5d5d5'});
            });

            // Очищаем localStorage
            localStorage.clear();
            
            // Если страница "Корзина"
            if($('[name=type_basket]').val() == 'main-bt'){
                // Скрываем всю страницу "корзина" и показываем "Товаров пока нет"
                list_is_products.fadeOut(100,function(){
                    list_no_products.fadeIn(100);
                });
                
                process_cart_total.html(zero);
                
                // Скролим страницу на верх
                window.scrollTo(0,0);
                
            }
        }
        
    }else{
    /**
     * Если пользователь автортзован
     * работаем с БД
     */
        var cart_page = $('.main-bt'),
            process_cart_total = cart_page.find('.process-cart-total .p-c-t');
        
        Data[csrf] = $('meta[name=csrf-token]').attr('content');
        Data['product_id'] = $this.attr('data-product-key');
        
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
            LoadAlert(data.header,data.message,3000,data.type_message);
            if(data.status == 200){
                // Если страница "single_product"
                if($('[name=type_basket]').val() == 'sept'){
                    
                    // Удаляем элемент из общего списка
                    $this.parent().parent().remove();

                    // Считаем товар в корзине top_panel общее количество и общую стоимость
                    if(typeof $('.list-products').html() !== 'undefined'){
                        $('.list-products .cart-single-wraper').each(function(){
                            $(this).find('.cart-single-wraper').attr('data-key','product_'+j);
                            $(this).find('.remove a').attr('data-product-key','product_'+j);
                            total_payment+=Number($(this).find('.cart-price .price').text());
                            total_count_product++;
                        });
                    }

                    // Меняем итоговую сумму к оплате
                    backet.find('.cart-subtotal .total-summ').html(
                        number_format(total_payment,2,'.',' ')
                    );

                    // Количество элементов в корзине
                    count_product_backet.html(total_count_product);

                    if(total_count_product == 0){
                        // Прячем строку "список товаров" и показываем "Товаров пока нет"
                        cart_content.hide(10,function(){ no_products.show(); });

                        // Количество элементов в корзине делаем 0
                        count_product_backet.html('0');

                        // Увсех размеров убираем зеленую рамку, делаем обычную рамку
                        form.find('.size-blocks li').each(function(){
                            $(this).find('button').attr('data-selected','');
                            $(this).find('button').css({'border-color':'#d5d5d5'});
                        });

                        // Очищаем localStorage
                        localStorage.clear();
                        
                    }
                }
                
                // Если страница "Корзина"
                if($('[name=type_basket]').val() == 'main-bt'){
                    
                    /* ========================================== */
                    /*       Действия в корзине top_panel         */
                    /* ========================================== */
                    
                    // Удаляем строку товара из корзины в top_panel
                    backet.find('[data-key='+product_key+']').remove();
                    
                    // Считаем товар в корзине top_panel общее количество и общую стоимость
                    if(typeof $('.list-products').html() !== 'undefined'){
                        $('.list-products .cart-single-wraper').each(function(){
                            $(this).find('.cart-single-wraper').attr('data-key','product_'+j);
                            $(this).find('.remove a').attr('data-product-key','product_'+j);
                            total_payment+=Number($(this).find('.cart-price .price').text());
                            total_count_product++;
                        });
                    }

                    // Меняем итоговую сумму к оплате
                    backet.find('.cart-subtotal .total-summ').html(
                        number_format(total_payment,2,'.',' ')
                    );

                    // Количество элементов в корзине
                    count_product_backet.html(total_count_product);

                    if(total_count_product == 0){
                        // Прячем строку "список товаров" и показываем "Товаров пока нет"
                        cart_content.hide(10,function(){ no_products.show(); });

                        // Количество элементов в корзине делаем 0
                        count_product_backet.html('0');
                        
                        cart_page.find('table.cart tbody').html('');

                        // Меняем итоговую сумму к оплате
                        process_cart_total.html(
                            number_format(total_payment,2,'.',' ')
                        );

                        list_is_products.fadeOut(200,function(){
                            list_no_products.fadeIn(200);
                        });
                    }
                    
                    
                    /* ========================================== */
                    /*      Действия в корзине на странице        */
                    /* ========================================== */
                    
                    // Удаляем строку из списка товаров
                    cart_page.find('.cart_item[data-key='+product_key+']').fadeOut(100,function(){
                        cart_page.find('.cart_item[data-key='+product_key+']').remove();
                    });
                    
                    // Меняем итоговую сумму к оплате
                    process_cart_total.html(
                        number_format(total_payment,2,'.',' ')
                    );
                    
                }
                
            }
            load.fadeOut(100);
        }).fail(function(data){
//            res.html('Fail<br>'+JSON.stringify(data));
            LoadAlert('Внимание','Ошибка',3000,'error');
            load.fadeOut(100);
        });
        
    }
    
}// removeProductFromBucket(...)

/**
 * При загрузке страницы,
 * если localStorge(backet) не пуст
 * подгружаем все товары в корзину top_panel
 * и если пользователь на странце "корзина"
 * то показваем список товаров на странице
 */
function getProductFromBasket(){
    var html = '',
        user_auth = $('[name=user_auth]').val(),
        backet = $('.cart-content-wraper'),
        cart_table = $('.main-bt').find('.cart.table'),
        cart_content = backet.find('.main-cart-content'),
        no_products = backet.find('.no-products'),
        count_product_backet = $('.header-cart .count-backet'),
        total_count_product = 0,
        total_payment = 0,
        new_total_payment = 0,
        old_total_payment = 0,
        old_price = 0,
        discount_price = 0,
        total_old_price = 0,
        total_new_price = 0,
        img = '';
    
    // Если страница "корзина"
    if($('[name=type_basket]').val() == 'main-bt'){
        var cart_page = $('.main-bt'),
            list_no_products = cart_page.find('.no-products'),
            list_is_products = cart_page.find('.is-products'),
            process_cart_total = cart_page.find('.process-cart-total .p-c-t'),
            type_basket = $('[name=type_basket]').val(),
            res = $('.res');
    }
    
    /**
     * Если пользователь не авторизован
     * то работаем с localStorage
     */
    if(user_auth == '0'){
        if(
            JSON.parse( JSON.stringify(localStorage.getItem('backet')) ) != '{}' &&
            localStorage.getItem('backet') != null
          ){

            var Backet2 = JSON.parse(localStorage.getItem('backet_view'));

            for(key in Backet2){
                
                if(Backet2[key].img == 'img_default') img = '/images/product/01.jpg';
                else img = '../../../common/files/photos'+Backet2[key].img;
                
                html = renderTemplate('#cart-single-top',{
                    "path_img":img,
                    "name":Backet2[key].name,
                    "new_price":Backet2[key].new_price,
                    "product_key":key,
                });

                backet.find('.list-products').append(html);

                total_payment+=Number(Backet2[key].new_price);
                old_total_payment+=Number(Backet2[key].old_price);

                total_count_product++;

            }

            // Прячем строку "Товаров пока нет" и показываем список товаров
            no_products.hide(10,function(){ cart_content.show(); });

            // Меняем итоговую сумму к оплате
            backet.find('.cart-subtotal .total-summ').html(
                number_format(total_payment,2,'.',' ')
            );

            // Количество элементов в корзине
            count_product_backet.html(total_count_product);

            // Если страница "корзина"
            if(type_basket == 'main-bt'){
                
                // Собриаем список товаров по шаблону для страницы "Корзина"
                for(key in Backet2){
                    
                    // Если у товара нет изображения, показываем стандартное.
                    if(Backet2[key].img == 'img_default') img = '/images/product/01.jpg';
                    else img = '../../../common/files/photos'+Backet2[key].img;
                    
                    /**
                     * Если основная цена совпадает с ценой со скидкой
                     * значит скидки нет
                     * и отображать цену со скидкой
                     * и скидку нужно нулевыми занчениями
                     */
                    if(Backet2[key].old_price == Backet2[key].new_price){
                        old_price = Backet2[key].old_price;
                        new_price = zero;
                        total_old_price = Backet2[key].old_price;
                        total_new_price = Backet2[key].old_price;
                    }else{
                        old_price = Backet2[key].old_price;
                        new_price = Backet2[key].new_price;
                        total_old_price = Backet2[key].old_price;
                        total_new_price = Backet2[key].new_price;
                    }
                    
                    html = renderTemplate('#cart-single-list',{
                        "path_img":img,
                        "name":Backet2[key].name,
                        "old_price":number_format(old_price,2,'.',''),
                        "new_price":number_format(new_price,2,'.',''),
                        "discount":Backet2[key].discount,
                        "product_key":key,
                    });

                    cart_page.find('table.cart tbody').append(html);
                    
                    discount_price = (old_total_payment-total_payment);
                    
                    html = renderTemplate('#cart-single-total-list',{
                        "name":Backet2[key].name,
                        "old_price":number_format(total_old_price,2,'.',''),
                        "new_price":number_format(total_new_price,2,'.',''),
                        "discount_price":number_format(discount_price,2,'.',''),
                        "discount":Backet2[key].discount,
                        "product_key":key,
                    });
                    
                    /**
                     * Вставляем строки после tr,
                     * который расположен сразу после тега table
                     */
                    cart_page.find('.checkout-area.table tbody .begin').after(html);

                }

                // Меняем итоговую сумму к оплате
                process_cart_total.html(
                    number_format(total_payment,2,'.',' ')
                );
                
                // Меняем итоговые суммы (вкладка "Оформить заказ")
                cart_page.find('.prc-total span').html(
                    number_format(total_payment,2,'.',' ')
                );
                cart_page.find('.discount-price span').html(
                    number_format(total_payment,2,'.',' ')
                );
                // Сумма скидки
                cart_page.find('.discount-amount span').html(
                    number_format(discount_price,2,'.',' ')
                );

                list_no_products.fadeOut(200,function(){
                    list_is_products.fadeIn(200);
                });
            }

        }else{
            // Прячем строку "список товаров" и показываем "Товаров пока нет"
            cart_content.hide(10,function(){ no_products.show(); });

            // Количество элементов в корзине делаем 0
            count_product_backet.html('0');
        }
    }else{
    /**
     * Если пользователь авторизован
     * считаем итоговые суммы
     */
        new_total_payment = 0;
        old_total_payment = 0;
        var new_total_payment_top_panel = 0;
        var i_new_price = 0;
        var i_old = 0;
        
        cart_table.find('tbody tr').each(function(){
            
            if($(this).find('.new-p').html() == zero)
                i_new_price = Number($(this).find('.old-p').html());
            else i_new_price = Number($(this).find('.new-p').html());
            
            old_total_payment+=Number($(this).find('.old-p').html());
            new_total_payment+=Number(i_new_price);
        });
        
        /**
         * Считаем итоговую сумму для корзины top_panel
         * отдельно, потому что эта корзина отображается
         * не только на странице корзина, но и на всех страницах
         */
        backet.find('.list-products .cart-single-wraper').each(function(){
            new_total_payment_top_panel+=Number($(this).find('.cart-price .price').html());
        });
        
        // Итоговая сумма на первой вкладке 01
        $('.main-bt').find('.process-cart-total .p-c-t').text(
            number_format(new_total_payment,2,'.',' ')
        );
        // Итоговая сумма в корзине top_panel
        backet.find('.total-summ').text(
            number_format(new_total_payment_top_panel,2,'.',' ')
        );
        // Заказ на сумму
        $('.main-bt').find('.cgt-des.prc-total span').text(
            number_format(new_total_payment,2,'.',' ')
        );
        // Цена (с учетом скидки)
        $('.main-bt').find('.cgt-des.discount-price span').text(
            number_format(new_total_payment,2,'.',' ')
        );
        // Ваша скидка составила
        $('.main-bt').find('.cgt-des.discount-amount span').text(
            number_format((old_total_payment-new_total_payment),2,'.','')
        );
    }
    
}// getProductFromBasket()

getProductFromBasket();

/**
 * Страница "Корзина"
 * ==================
 * Проверка обязательных полей
 * на пустоту
 */
function checkInputs(){
    var form = $('.main-bt'),
        first_name = form.find('[name=first_name]'),
        phone = form.find('[name=phone]');
    
    if(first_name.val() == '' || phone.val() == '') return false;
    return true;
}


