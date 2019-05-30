/**
 * Переменная (zero) задается в AdminAppAsset через init()
 */

/**
 * Количество заказов в главной шапке сайта
 * ========================================
 * Функция запускается каждую минуту (60 сек)
 */
function countOrdersOnline(){
    var coo = $('.count-orders-online'),
        res = $('.res');
    
//    image.fadeOut(400);return;
    
    $.ajax({
        url:'ajax/count-orders-online',
        type:'post',
        dataType:'json',
        cashe:'false',
        data:null,
        beforeSend:function(){}
    }).done(function(data){
        
//        res.html('Done<br>'+JSON.stringify(data));
        
        coo.find('span').html(data.count_orders);
        
//        if(data.status == 200){
//            image.fadeOut(200,function(){ image.remove(); });
//            res.html('Файл удален');
//        }else{
//           res.html('Нет такого файла');
//        }
        
    }).fail(function(data){
//        res.html('Fail<br>'+JSON.stringify(data));
    });
    
}// function countOrdersOnline(...)

// Запуск проверки количества заказов
setInterval(function(){
    countOrdersOnline();
},60000);
//},1000);


/**
 * Страница "Номенклатура товара"
 * ===================================================
 */

// Опустошение/обнуление всех полей на странице
function productNomenclaturReset(){
	var form = $('.ptne'),
		// поле для редактирования артикулов
		edit = document.getElementById('edit'),

		// Делаем выборку необходимых полей
		// ================================
		// наименование номеклатуры
		nomenclatureName = document.getElementById('nomenclature_name'),
		// особенности модели
		featuresOfTheModel = document.getElementById('features_of_the_model'),
		// описание товара на сайте
		productDescriptionOnTheSite = 
			document.getElementById('product_description_on_the_site'),
		// надпись на этикетке
		labeling = document.getElementById('labeling'),
		// номенклатурные коды похожие на товары
		nomenclatureCodesSimilarProducts = 
			document.getElementById('nomenclature_codes_similar_products'),
		// надпись на этикетке
		nomenclaturalCodes = document.getElementById('nomenclatural_codes'),
		// признак новинка сезона (дата)
		noveltyOfTheSeason = document.getElementById('datepicker'),

		// ВЫПАДАЮЩИЕ СПИСКИ

		// выберите артикул
		vendorCode = form.find('input[name=reference_value]'),
		// выберите тованую группу
		productGroup = form.find('.product-group'),
		// выберите ворот
		neckband = form.find('.neckband'),
		// выберите ширина
		width = form.find('.width'),
		// выберите защипы
		defenses = form.find('.defenses'),
		// выберите пол
		gender = form.find('.gender'),
		// выберите застежка
		clasp = form.find('.clasp'),
		// выберите рукав
		sleeve = form.find('.sleeve'),
		// выберите шлицы
		splines = form.find('.splines'),
		// выберите рисунок/узор
		design = form.find('.design'),
		// выберите число пуговиц
		numberButtons = form.find('.number-buttons'),
		// выберите запонки
		silhouette = form.find('.silhouette'),
		// выберите сезон
		season = form.find('.season'),
		// выберите состав верх
		compositionTop = form.find('.composition-top'),
		// выберите карманы
		pockets = form.find('.pockets'),
		// выберите пояс
		belt = form.find('.belt'),
		// выберите утеплитель
		insulation = form.find('.insulation'),
		// выберите состав наполнитель
		compositionFiller = form.find('.composition-filler'),
		// выберите капюшон
		hood = form.find('.hood'),
		// выберите пряжка
		buckle = form.find('.buckle'),
		// выберите цвет
		color = form.find('.color'),
		// выберите состав подклад
		compositionLining = form.find('.composition-lining'),
		// выберите длинна
		length = form.find('.length'),
		// выберите линия посадки
		landingLine = form.find('.landing-line'),
		// отображать на сайте (да/нет)
		displayOnTheSite = form.find('input[name=display-on-the-site]'),
		// адрес страницы товара на сайте
		wrapDetailPageUrl = form.find('.dpu'),
		inputDetailPageUrl = 
			wrapDetailPageUrl.find('input[name=detail_page_url]'),
		linkDetailPageUrl = wrapDetailPageUrl.find('a');

	/**
	 * Обнуляем данные на странице
	 * -----------------------
	 * текстовые поля
	 */	
	// поле для редактирования артикулов
	edit.value='';
	// наименование номенклатуры
	nomenclatureName.value='';
	// особенности модели
	featuresOfTheModel.value='';
	// описание товара на сайте
	productDescriptionOnTheSite.value='';
	// надпись на этикетке
	labeling.value='';
	// номенклатурные коды похожие на товары
	nomenclatureCodesSimilarProducts.value='';
	// надпись на этикетке
	nomenclaturalCodes.value='';
	// признак новинка сезона (дата)
	noveltyOfTheSeason.value='';

	/**
	 * Вставляем данные в HTML
	 * -----------------------
	 * выпадающие списки
	 */
	// выберите тованую группу
	productGroup.val(''),
	// выберите ворот
	neckband.val('0'),
	// выберите ширина
	width.val('0'),
	// выберите защипы
	defenses.val('0'),
	// выберите пол
	gender.val(''),
	// выберите застежка
	clasp.val('0'),
	// выберите рукав
	sleeve.val('0'),
	// выберите шлицы
	splines.val('0'),
	// выберите рисунок/узор
	design.val('0'),
	// выберите число пуговиц
	numberButtons.val('0'),
	// выберите силуэт
	silhouette.val('0'),
	// выберите сезон
	season.val('0'),
	// выберите состав верх
	compositionTop.val('0'),
	// выберите карманы
	pockets.val('0'),
	// выберите пояс
	belt.val('0'),
	// выберите утеплитель
	insulation.val('0'),
	// выберите состав наполнитель
	compositionFiller.val('0'),
	// выберите капюшон
	hood.val('0'),
	// выберите пряжка
	buckle.val('0'),
	// выберите цвет
	color.val('0'),
	// выберите состав подклад
	compositionLining.val('0'),
	// выберите длина
	length.val('0'),
	// выберите линия посадки
	landingLine.val('0'),
	// отображать на сайте (да/нет)
	displayOnTheSite.val('0'),
	// адрес страницы товара на сайте					
	inputDetailPageUrl.val('0');
	linkDetailPageUrl.attr('href','#');
	linkDetailPageUrl.html('');
}

reloadImages();

/**
 * Страница "Номенклатура товара"
 * ==============================
 * Запускаем загрузку изображений
 * для текущей номенклатуры
 */

function reloadImages(path){
    /**
     * Если путь не передан,
     * то изображения загружать не нужно.
     * Просто останавливаем скрипт
     * ==================================
     * Это условие будет срабатывать всегда
     * когда будет загружаться страница,
     * но когда будет происходить перезагрузка изобаржений
     * то этот if срабатывать не будет
     */
    if(typeof path === 'undefined'){
        var mc = $('.modal-content'),
            mu = $('.modal-upload');
        
        // Прячем окно загрузки файлов на всю его выстоту
        var to_top = (-200 - mc.height());
        mu.css({'top':to_top});
        return;
    }
    
    /**
     * Страница "Номенклатура товара"
     * ==============================
     * Достаем загруженные файлы
     * для вывода на экран
     */
    Dropzone.options.myDropzone = {
        init: function() {

            thisDropzone = myDropzone;
            var progress = $('.dz-progress'),
                res = $('.res'),
                dropzone = $('#previews'),
                isImages = $('.is-images');

            $.ajax({
                url:'ajax/get-images',
                type:'get',
                dataType:'json',
                data:{path:path}
            }).done(function(data){
//                 res.html('Done<br>'+JSON.stringify(data));
                /**
                 * Если изображения по бренду и артикулу найдены
                 * то подгружаем их в модалку
                 */
                dropzone.html('');
                if(data.status == 200){
                    /**
                     * Перед загрузкой новых изображений
                     * удаляем все показывающиеся изображения
                     */
                    $.each(data.images, function(key,value){

                        var mockFile = { name: value.name, size: value.size };

                        thisDropzone.options.addedfile.call(thisDropzone, mockFile);

                        thisDropzone.options.thumbnail.call(
                            thisDropzone,
                            mockFile,
                            '../common/files/photos/'+path+'/thumb/'+value.name);

                    });
                    isImages.removeClass('ptne-is-images-red').addClass('ptne-is-images-green').html('Есть загруженные изображения');
                }else isImages.removeClass('ptne-is-images-green').addClass('ptne-is-images-red').html('Нет загруженных изображений');

                $('.dz-progress').fadeOut(100);

            }).fail(function(data){
//                res.html('Fail<br>'+JSON.stringify(data));
            });
        }
    };

    /**
     * Страница "Номенклатура товара"
     * ==============================
     * Запускаем загрузку изображений thumb для вывода на экран
     */
    Dropzone.options.myDropzone.init();
}

/**
 * Страница "Номенклатура товара"
 * ==============================
 * Удаление загруженных файлов
 */
function dzDelete(obj){
    var $this = $(obj),
        image = $this.parent(),
        load = $this.parent().find('.dz-wrap-animate'),
        fileName = $this.parent().find('.dz-image img').attr('alt'),
        // выбраный элемент поля бренд
        barnd = $('.ptne [name=brand_code] option:selected'),
        // выбраный элемент поля "Выберите артикул"
        $selected = $('.ptne .vendor-code option:selected'),
        res = $('.res');
    
//    image.fadeOut(400);return;
    
    $.ajax({
        url:'ajax/ptne-delete-files',
        type:'post',
        dataType:'json',
        cashe:'false',
        data:{
            type:'delete',
            file_name:fileName,
            dir:barnd.attr('data-code')+'-'+$selected.val()
        },
        beforeSend:function(){
            load.fadeIn(100);
        }
    }).done(function(data){
//        res.html('Done<br>'+JSON.stringify(data));
        if(data.status == 200){
            image.fadeOut(200,function(){ image.remove(); });
            res.html('Файл удален');
        }else{
           res.html('Нет такого файла');
        }
        load.fadeOut(100);
    }).fail(function(data){
        res.html('Fail<br>'+JSON.stringify(data));
        load.fadeOut(100);
    });
}


/**
 * ===================================================
 * END Страница "Номенклатура товара"
 */

/**
 * Страница "Поступление товара"
 * ===================================================
 */

/**
 * Удаляем строку tr из table
 */
function deleteTr(thisObj){
    $(thisObj).parent().parent().fadeOut(100).remove();
    /** 
     * Пересчитываем количество штук товаров
     * по значениям полей "количество"
     */
    var quantity = 0;
    $('.gr .table tbody tr').each(function(){
        quantity += Number($(this).find('input[name=quantity]').val());
    });

    /**
     * Пересчитываем общие суммы на странице
     * себестоимость/розничная стоимость
     */
    var totalCostPrice = $('.gr .t-cost-price'),
        totalRetailPrice = $('.gr .t-retail-price'),
        costPriceItogSumm = 0,
        retailPriceItogSumm = 0,
        ii = 1;

    $('.gr .table tbody').find('tr').each(function(){
        var costPrice = $(this).find('.cost-price'),
            retailPrice = $(this).find('.retail-price'),
            amount = $(this).find('.amount');

        costPriceItogSumm += (Number(amount.val()) * Number(costPrice.val())),
            retailPriceItogSumm += (Number(amount.val()) * Number(retailPrice.val()));
        $(this).find(':first span').html(ii);
        $(this).find(':first input').val(ii);
        ii++;

    });
    
    // Количество по полю - количество
    $('.gr .info .quantity').html(quantity);
    // Себестоимость
    totalCostPrice.html(number_format(costPriceItogSumm,2,',',' '));
    // Розничная стоимость
    totalRetailPrice.html(number_format(retailPriceItogSumm,2,',',' '));

}// function deleteTr(thisObj)

/**
 * По изменнию поля "выберите Бренд"
 * Заполняем select "Выберите артикул" списоком артикулов текущего Бренда
 */
function getVendorCodeByBrand(thisObj){
	
    var $this = $(thisObj),
        tr = $this.parent().parent(),
        res = $('.res'),
        live = 5000,
        // load = $('.srch .w-vc img'),
		/**
		 * Поле "Выберите бренд"
		 * получаем выбранный элемент
		 */
        $selected = tr.find('.td1 .brands option:selected'),
		// поле "Выберите артикул"
        vc = tr.find('.td2 .vendor-code');

	/**
	 * При изменении поля "Выберите бренд"
	 * обнуляем содержимое всех полей текущей строки
	 */
    tr.find('.td3').html('Товарная группа');
    tr.find('.td3').attr('code','');
    tr.find('.td4 span').html('Наименование номенклатуры');
    tr.find('.td4 input').val('');
    tr.find('.td4').attr('gender','');
    tr.find('.td5 select').val('0');
    tr.find('.td6 select').val('0');
    tr.find('.td7 select').val('0');
    tr.find('.td8 span').html('Штрихкод');
    tr.find('.td8 input').val('');
    tr.find('.td10 input').val('');
    tr.find('.td9 input').val('');
    tr.find('.td11 input').val('');

    /**
	 * Когда в поле "Выберите бренд"
	 * выбрано "ничего"
	 */
    if($selected.val() == ''){
        vc.html('<option value="0">Список артикулов пуст</option>');
        return;
    }

    $.ajax({
        url:$this.attr('action'),
        type:$this.attr('method'),
        cashe:'false',
        dataType:'json',
        data:{
            page:'product',
            brand_code:$this.val(),
            list_name: 'Выберите артикул'
        },
        beforeSend:function(){
            // load.fadeIn(100);
        }
    }).done(function(data){
//    	res.html('Done<br>'+JSON.stringify(data));
//        LoadAlert(data.header,data.message,live,data.type_message);
        if(data.status == 200){
            vc.html(data.option_s);
        }
        // load.fadeOut(100);
    }).fail(function(data){
//        res.html('Fail<br>'+JSON.stringify(data));
        LoadAlert('Error','Ошибка PHP',live,'error');
        // load.fadeOut(100);
    });
	
}// function getVendorCodeByBrand(thisObj)

/**
 * По изменению выпадающего списка "Размер производителя"
 * подгружаем
 *  "наименование номенклатуры"
 *  "товарную группу"
 *  "штрихкод"
 */
//function getBarcodeByBrandAndVendorCode(thisObj){
function createBarcode(thisObj){
    var $this = $(thisObj),
        tr = $this.parent().parent(),
        action = $this.attr('action'),
        method = $this.attr('method'),
        res = $('.gr .res'),
        $selectedBrands = tr.find('.td1 .brands option:selected'),
        $selectedVendorCode = tr.find('.td2 .vendor-code option:selected'),
        productGroup = tr.find('.td3'),
        nameNomenclature = tr.find('.td4'),
        barcode = tr.find('.td8 span'),
        barcodeInput = tr.find('.td8 input'),
        load = tr.find('.td8 img'),
        newBarcode;

    $.ajax({
        url:action,
        type:method,
        cashe:'false',
        dataType:'json',
        data:{
			get_barcode:'get_barcode',
            article_of_manufacture:$selectedVendorCode.text(),
            brand_code:$selectedBrands.attr('data-code')
        },
        beforeSend:function(){
			barcode.fadeOut(100);
//			load.fadeIn(100);
//            barcode.fadeOut(100,function(){
//                load.fadeIn(100);
//            });
        }
    }).done(function(data){
//        res.html('Done<br>'+JSON.stringify(data));
        if(data.status == 200){
//            load.fadeOut(100,function(){
//                barcode.fadeIn(100);
//            });

            /**
             * Если при смене значения размера
             * код товарной группы и код пола совпадают
             * то штрихкод менять незачем
             */
            if(
                productGroup.attr('code') != data.product_group_code ||
                nameNomenclature.attr('gender') != data.gender_code
            ){
                newBarcode = barcodeInc(data.barcode);
                barcode.html(newBarcode);
                barcodeInput.val(newBarcode);
            }
        }else{
            LoadAlert(data.header,data.message,10000,data.type_message);
            barcode.html('Штрихкод');
        }
		barcode.fadeIn(100);
//		load.fadeOut(100);
//		load.fadeOut(100,function(){
//			barcode.fadeIn(100);
//		});
    }).fail(function(data){
//        res.html('Fail<br>'+JSON.stringify(data));
		barcode.fadeIn(100);
//		load.fadeOut(100);
//        load.fadeOut(100,function(){
//            barcode.fadeIn(100);
//        });
    });
	
}// function getDataByBrandAndVendorCode(thisObj)

/**
 * По изменению выпадающего списка "Выберите артикул"
 * подгружаем
 *  "наименование номенклатуры"
 *  "товарную группу"
 */
function getDataByBrandAndVendorCode(thisObj){
    var $this = $(thisObj),
        tr = $this.parent().parent(),
        action = $this.attr('action'),
        method = $this.attr('method'),
        res = $('.gr .res'),
        load = tr.find('.td8 img'),
		// Выбранное значение Брнеда
        $selectedBrands = tr.find('.td1 .brands option:selected'),
		// Выбранное значение Артикула
        $selectedVendorCode = tr.find('.td2 .vendor-code option:selected'),
		// поле "товарная группа"
        productGroup = tr.find('.td3'),
		// поле "Наименование номенклатуры"
        nameNomenclature = tr.find('.td4'),
		// поле "Наименование номенклатуры" блок span
        nameNomenclatureText = nameNomenclature.find('span'),
		// поле Штрихкод
        barcode = tr.find('.td8 span');
	
	productGroup.html('Товарная группа');
	productGroup.attr('code','');
	nameNomenclatureText.html('Наименование номенклатуры');
	tr.find('.td4 input').val('');
	nameNomenclature.attr('gender','');
	barcode.html('Штрихкод');
	tr.find('.td3 input').val('');
	tr.find('.td5 select').val('0');
	tr.find('.td6 select').val('0');
	tr.find('.td7 select').val('0');
	tr.find('.td8 input').val('');

    $.ajax({
        url:action,
        type:method,
        cashe:'false',
        dataType:'json',
        data:{
            brand_code:$selectedBrands.attr('data-code'),
            article_of_manufacture:$selectedVendorCode.text()
        },
        beforeSend:function(){
//			load.fadeIn(100);
        }
    }).done(function(data){
//        res.html('Done<br>'+JSON.stringify(data));
		LoadAlert(data.header,data.message,10000,data.type_message);
        if(data.status == 200){
            productGroup.html(data.product_group);
            productGroup.attr('code',data.product_group_code);
            nameNomenclatureText.html(data.nomenclature_name);
            nameNomenclature.attr('gender',data.gender_code);
            tr.find('.td4 input').val(data.item_code);
			
        }else{
            productGroup.html('Товарная группа');
            productGroup.attr('code','');
            nameNomenclatureText.html('Наименование номенклатуры');
            tr.find('.td4 input').val('');
            nameNomenclature.attr('gender','');
            barcode.html('Штрихкод');
            tr.find('.td3 input').val('');
            tr.find('.td5 select').val('0');
            tr.find('.td6 select').val('0');
            tr.find('.td7 select').val('0');
            tr.find('.td8 input').val('');
        }
//		load.fadeOut(100);
    }).fail(function(data){
//        res.html('Fail<br>'+JSON.stringify(data));
//        load.fadeOut(100);
    });
	
}// function getDataByBrandAndVendorCode(thisObj)

function barcodeInc(barcode){
    var i = 0,b = barcode,bi,serial_number,new_barcode,inc,ean,nsn;
    // собираем все "заполненные" штрих коды на странице

    var array = [];
    /**
     * Собираем со страницы серийные номера штрихкодов
     * схожих по кодам "бренд/пол/товарная группа"
     */
    $('.gr .list-products tbody').find('tr .td8 span').each(function(){
        /**
         * Сделал переменную, для упрощения использования
         */
        bi = $(this).html();

        // создаем переменную шаблона
        // шаблон из "бренд/пол/товарная группа" из штрихкода с сервера
        var mask = bi[0]+bi[1]+bi[2]+bi[3]+bi[4]+bi[5]+bi[6];
        // создаем шаблон с помощью конструктора регулярки
        var regex = new RegExp(mask,'i');
        // и используем созданый шаблон в .match
        var result = barcode.match( regex );

        /**
         * Если совпадение найдено
         * то берем значение в массив
         */
        if(result){
            // порядковый номер
            array[i] = bi[7]+bi[8]+bi[9]+bi[10]+bi[11];
            i++;
        }
    });

    /**
     * Если на странице найден хоть один штрихкод
     * по группе "бренд/пол/товарная группа"
     */
    if(array.length > 0){
        /**
         * Выбираем порядковый номер из штрихкода
         * пришедшего с сервера
         * snb - serial number barcode
         */
        var go = false;
        var snb = b[7]+b[8]+b[9]+b[10]+b[11];

        array.sort(compareNumeric);

        // перебираем все порядковые номера
        for(var i = 0; i < array.length; i++) {
            if(array[i] == snb){
                snb = incFrontNull(snb);
            }else{
                go = snb;
                break;
            }
        }

        if(!go){

            var max = Math.max.apply(null, array);

            inc = incFrontNull(max);

            ean = b[0]+b[1]+b[2]+b[3]+b[4]+b[5]+b[6]+inc;
            new_barcode = barcodeGenEanSum(ean);

        }else{

            ean = b[0]+b[1]+b[2]+b[3]+b[4]+b[5]+b[6]+go;
            new_barcode = barcodeGenEanSum(ean);
            return new_barcode;
        }

    }else{
        /**
         * Если на странице вообще не найден ни один штрихкод
         */
        new_barcode = barcode;
    }

    return new_barcode;

}// function barcodeInc(barcode)

// Алгоритм EAN-13
function barcodeGenEanSum(ean){
    var even=true,
        esum=0,
        osum=0;
    for (var i=ean.length-1;i>=0;i--){
        if (even) esum+=Number(ean[i]);	else osum+=Number(ean[i]);
        even=!even;
    }
    var ean_sum = (10-((3*esum+osum)%10))%10;
    ean_sum = String(ean_sum);
    return ean+ean_sum;
	
}// function barcodeGenEanSum(ean)

/**
 * Инкрементим число
 * и ставим соответствующее количество нулей
 * перед числом
 */
function incFrontNull(str){
    str = (Number(str)+Number(1));
    str = String(str);
    switch(str.length){
        case 1:
            str = '0000'+str;
            break;
        case 2:
            str = '000'+str;
            break;
        case 3:
            str = '00'+str;
            break;
        case 4:
            str = '0'+str;
            break;
        default: str = str;
    }

    if(str > 99999){
        LoadAlert('Внимание','Порядковый номер по данному штрих коду закончился.<br>Обратитесь к администратору системы.',10000,'warning');
        return;
    }

    return str;
	
}// function incFrontNull(str)

/**
 * При редактировании полей "Количество","Себестоимость","Розничная стоимость"
 * Считаем общую сумму себестоимости/розничной стоимости всех товаров
 */
function totalProductInfo(){
		// Итого количество по документу
    var totalQuantity = $('.gr .quantity'),
		// Итого сумма себестоимости
        totalCostPrice = $('.gr .t-cost-price'),
		// Итого сумма розничной стоимости
        totalRetailPrice = $('.gr .t-retail-price'),
        quantityItogSumm = 0,
        costPriceItogSumm = 0,
        retailPriceItogSumm = 0;

    $('.gr .table tbody').find('tr').each(function(){
        var costPrice = $(this).find('.cost-price'),
            retailPrice = $(this).find('.retail-price'),
            amount = $(this).find('.amount');// Количество шт в строке
		
		strCostPrice = costPrice.val().replace(',','.');
		strRetailPrice = retailPrice.val().replace(',','.');
		strAmount = amount.val().replace(',','.');

        quantityItogSumm += (Number(strAmount));
        costPriceItogSumm += (Number(strAmount) * Number(strCostPrice)),
        retailPriceItogSumm += (Number(strAmount) * Number(strRetailPrice));

    });

    totalQuantity.html(quantityItogSumm);
    totalCostPrice.html(number_format(costPriceItogSumm,2,',',' '));
    totalRetailPrice.html(number_format(retailPriceItogSumm,2,',',' '));

}// function totalAllSummByAmount(thisObj)



/**
 * ===================================================
 * END Страница "Поступление товара"
 */



/**
 * Страница "Товарный чек"
 * ===================================================
 */

/**
 * Страница "Товарный чек"
 * =======================
 * Раздел 1
 * обработка поля "ручная скидка"
 */
function changeManualDiscount(thisObj){
	
	var $this = $(thisObj),
		tr = $this.parent().parent(),
		// штрихкод текущей строки
		barcode = tr.find('#barcode').html(),
		// поле "сумма без скидок"
		amountWithoutDiscounts = tr.find('.amount-without-discounts1'),
		// поле "сумма скидок"
		amountOfDiscounts = tr.find('.sum-of-discounts1'),
		// поле "сумма скидок" (значение без ручной скидки)
		amountOfDiscountsDefault = $this.attr('data-default'),
		// поле "сумма за вычетом скидок"
		amountAfterDeductionOfDiscounts = tr.find('.amount-after-deduction-of-discounts1'),
		/**
		 * Если поле пусто, то значение будет 0
		 */
		thisVal = ($this.val() != '')?$this.val():0,
		// поле "количество"
		quantity = tr.find('.quantity1').html(),
		message = 'Если нужно отразить выбытие нескольких единиц товара с одинаковым штрихкодом и поставить ручную скидку, которая не одинакова для каждой из них или относится не ко всем из них, то такое выбытие следует отражать отдельными товарными чеками.';
	
	if(thisVal != ''){
		thisVal = thisVal.replace(',', '.');
		thisVal = thisVal.replace(/[^\d\.]/g, '');
		thisVal = roundToTwo(thisVal,'.');
		var last = thisVal.toString().slice(-1);
		if(last == '.') return;
		
		thisValN = Number(thisVal);
		if(isNaN(thisValN)){
			return;
		}
	}
	
	// Если значение "количество" больше одного
	if(Number(quantity) > 1){
		/**
		 * Показываем сообщение только если
		 * количество символов в поле равняется 1
		 */
	    if($this.val().length == 1){
		   popUp('.sales-reciept',message,'info');
	    }
	}
	
	/**
	 * Новое значение "сумма скидок"
	 * =====================================================
	 * К значению по умолчанию прибавляем введенное значение.
	 * делается это для того, чтобы когда поле очищается
	 * всё возвращалось на место. Чтобы все значения строк
	 * возвращались обратно в те значения, когда поле
	 * "ручная скидка" было на заполнено.
	 */
	amountOfDiscounts.html(
		(Number(thisVal) + Number(amountOfDiscountsDefault))
	);
	
	// Считаем новое значение "сумма за вычетом скидок"
	amountAfterDeductionOfDiscounts.html(
		number_format((Number(amountWithoutDiscounts.html()) - Number(amountOfDiscounts.html())), 2,'.',' ')
	);
	
	// делаем новые подитоги
	recalculationPS1();
		
	/**
	 * Делаем пересчет Раздела 1
	 * c указанием, что пересчет запущен при
	 * редактировании ручной скидки
	 */
	 forS1('rs');

}// function changeManualDiscount(thisObj)

/* 
 * Страница "Товарный чек"
 * =======================
 * Пересчет Раздела 1
 * ------------------
 * flag - флаг, который нужен для отключения
 * изменения значения "data-default" (сумма скидок)
 * т.е. если пересчет происходит при редактировании поля "ручная скидка"
 * то data-default изменять не нужно
 *
 */
function recalculationS1(barcode,flag){
	
	var form = $('.sales-reciept'),
		stop = true;// флаг для остановки основной функции
	
	if(typeof form.find('.empty1').attr('class') !== 'undefined'){
		stop = false;// ставим stop в false
		return false;// останавливаем цикл
	}
	
//	// цикл нужен просто для проверки раздела на пустоту
//	form.find('.table.document1 tbody tr').each(function(){
//		/**
//		 * Проверка, есть ли что то в разделе 1
//		 * если раздел пуст, то пересчитывать раздел 1 не нужно
//		 * ставим флаг в false и останавливаем цикл
//		 */
//		if($(this).attr('class') == 'empty'){
//			stop = false;// ставим stop в false
//			return false;// останавливаем цикл
//		}
//	});
	
	// Если stop = false - то останавливаем функцию
	if(!stop) return;
	
	/**
	 * Выбираем необходимые поля подитогов
	 * разделов (1,3,4)
	 */
		// Раздел 1: подитог "сумма скидок"
	var	p_ss1 = form.find('.document1 .tfoot1 .p_ss1 b'),
		// Раздел 1: подитог "сумма за вычетом скидок"
		p_szvs1 = form.find('.document1 .tfoot1 .p-szvs1 b'),
		// Раздел 1: подитог "скидка по подарочным сертификатам"
//		p_sps1 = form.find('.document1 .tfoot1 .p_sps1 b'),
		// Раздел 1: подитог "итого скидки"
//		p_is1 = form.find('.document1 .tfoot1 .p_is1 b'),
		// Раздел 1: подитог "сумма продажи"
//		p_sp1 = form.find('.document1 .tfoot1 .p_sp1 b'),
		// Раздел 3: подитог "скидка по подарочным сертификатам"
		p_sps31 = form.find('.document1 .tfoot3 .p-sps31 b'),
		// Раздел 3: подитог "сумма продажи"
		p_sp32 = form.find('.document1 .tfoot3 .p-sp32 b'),
		// Раздел 4: подитог "скидка по подарочным сертификатам"
		p_sps4 = form.find('.document1 .tfoot4 .p-sps4 b');

	/**
	 * Берем родительский элемент tr раздела 1, той строки
	 * где содержимое td совпадает с переменной barcode
	 */
	var $this = $('.table.document1 tr.section1').find('td:contains("'+barcode+'")').parent(),
		// цена за 1шт
		$rp = $this.find('.retail-price1'),
		// количество
		$qty = $this.find('.quantity1'),
		// сумма без скидок
		$sbs = $this.find('.amount-without-discounts1'),
		// скидка по дисконтной карте
		$spdk = $this.find('.discount-on-a-discount-card1'),
		// автоматическая скидка
		$as = $this.find('.automatic-discount1'),
		// ручная скидка
		$rs = $this.find('.table-input1 input[name=manual_discount]'),
		// сумма скидок
		$ss = $this.find('.sum-of-discounts1'),
		// сумма за вычетом скидок
		$szs = $this.find('.amount-after-deduction-of-discounts1'),
		// скидка по подарочным сертификатам
		$spps = $this.find('.discount-on-gift-certificates1'),
		// итого скидки
		$is = $this.find('.total-discounts1'),
		// сумма продажи
		$sp = $this.find('.sales-amount1');
	
	/**
	 * Обрабатываем занчение
	 * Меняем все запятые на точки
	 * Удаляем все лишние символы
	 * Оставляем для копеек только два знака
	 */
	if($rs.val() != ''){
		
		$rsVal = $rs.val().replace(',', '.');
		$rsVal = $rsVal.replace(/[^\d\.]/g, '');
		$rsVal = roundToTwo($rsVal,'.');
		
	}else $rsVal = 0;
	
	// Делаем пересчеты
	// ================

	/**
	 * Сумма без скидок
	 * ================
	 * "Текущая цена" умножить на "Количество"
	 */ 
	var awds = (Number($rp.html()) * Number($qty.html()));

	/**
	 * Сумма скидок
	 */
	var mv = Math.max(
		Number($spdk.html()), 
		Number($as.html())
	);
	var sods = Math.min(awds,((awds * mv) / 100 + Number($rsVal)));

	/**
	 * Сумма за вычетом скидок
	 * =======================
	 * "Сумма без скидок" минус "Сумма скидок"
	 */
	var aadods = ((awds - sods) >= 0)?(awds - sods):0;
	
	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	// форматируем значение "Сумма за вычетом скидок"
	aadods = number_format(aadods, co, fl, th);
	/**
	 * Вставляем в текущую строку
	 * новое значение "Сумма за вычетом скидок"
	 */
	$szs.html(aadods);
	/**
	 * Пересчитываем поля подитога раздела 1
	 * чтобы получить правильный подитог поля "Сумма за вычетом скидок"
	 * для использования его в остальных расчетах
	 */
	recalculationPS1();

	/**
	 * Скидка по подарочным сертификатам
	 * ==================================================
	 * p_szvs1 - Раздел 1: 
	 *   уже пересчитанный новый подитог "сумма за вычетом скидок"
	 * p_sps31 - Раздел 3: подитог "скидка по подарочным сертификатам"
	 * p_sp32 - Раздел 3: подитог "сумма продажи"
	 * p_sps4 - Раздел 4: подитог "скидка по подарочным сертификатам"
	 * aadods - "сумма за вычетом скидок" текущей строки
	 * ========================================================================
	 * Складываем текущую "сумма за вычетом скидок"
	 * с подитогом "сумма за вычетом скидок" взятым с Раздела 1
	 */
	var dogc = 0;
	var np_szvs1 = Number(p_szvs1.html());
	if( np_szvs1 != '0'){
		var if1 = (np_szvs1 - Number(p_sp32.html()));
		var if2 = (Number(p_sps31.html()) + Number(p_sps4.html()));
		if(if1 > 0) dogc = if1;
		if(if2 < dogc) dogc = if2;
		var dalee = (dogc * aadods);

		dogc =
			(np_szvs1 != '0')?(dalee / np_szvs1):0;

	}else dogc = 0;

	/**
	 * Итого скидки
	 * ============
	 * "Сумма скидок" плюс "Скидка по подарочным сертификатам"
	 */
	var tds = (sods + dogc);

	/**
	 * Сумма продажи
	 * =============
	 * "Сумма без скидок" минус "Итого скидки"
	 */
	var sat = ((awds - tds) >= 0)?(awds - tds):0;

	awds = number_format(awds, co, fl, th);
	sods = number_format(sods, co, fl, th);
	aadods = number_format(aadods, co, fl, th);
	dogc = number_format(dogc, co, fl, th);
	tds = number_format(tds, co, fl, th);
	sat = number_format(sat, co, fl, th);

	$sbs.html(awds);
	$ss.html(sods);
	$szs.html(aadods);
	$spps.html(dogc);
	$is.html(tds);
	$sp.html(sat);

	/**
	 * data-default - это сумма скидок.
	 * это значение нужно для вычисления
	 * новой суммы скидок: (сумма скидок + ручная скидка)
	 * (data-default плюс "редактируемая" ручнуя скидка)
	 * т.е. когда уменьшаем или очищаем поле "ручная скидка"
	 * отталкиваемся от data-default
	 * =====================================================
	 * Если функция пересчета "recalculationS1()" запускается
	 * при редактировании поля "ручная скидка"
	 * то значение "data-default" изменять не нужно
	 */
	var ss = (sods - Number($rsVal));
	ss = number_format(ss, co, fl, th);
	if(typeof flag !== 'undefined' && flag == 'rs') $rs.attr('data-default',ss);
	
	// пересчет полей подитога раздела 1
	recalculationPS1();
	
}// function recalculationS1(barcode)

/**
 * Страница "Товарный чек"
 * =======================
 * Пересчет Раздела 3
 */
function recalculationS3(barcode){
	
	var form = $('.sales-reciept'),
		/**
	 	 * Берем родительский элемент tr раздела 3, той строки
		 * где содержимое td совпадает с переменной barcode
		 */
		$this = $('.table.document1 tr.section3').find('td:contains("'+barcode+'")').parent(),
		// цена за 1шт
		$rp = $this.find('.retail-price3'),
		// объект количества
		$objQty = $this.find('.quantity3'),
		// текущее количество
		$strQty = $objQty.find('span').html(),
		// общее количество
		$strCommonQty = $objQty.find('input').val(),
		// ручная скидка
		$rs = $this.find('.manual-discount3'),
		// общая ручная скидка
		$strCommonRs = $rs.attr('data-common'),
		// текущая скидка по подарочным сертификатам
		$spps = $this.find('.discount-on-gift-certificates3'),
		// общая скидка по подарочным сертификатам
		$strCommonSpps = $spps.attr('data-common'),
		// текущая сумма продажи
		$sp = $this.find('.sales-amount3'),
		// общая сумма продажи
		$strCommonSp = $sp.attr('data-common'),
		// сумма без скидок
		$sbs = $this.find('.amount-without-discounts3'),
		// итого скидки
		$is = $this.find('.total-discounts3'),
		// сумма скидок
		$ss = $this.find('.sum-of-discounts3'),
		// сумма за вычетом скидок
		$szs = $this.find('.amount-after-deduction-of-discounts3');

	// Делаем пересчеты
	// ================

	/**
	 * Сумма без скидок
	 * ================
	 * "Текущая цена" умножить на "Количество"
	 */ 
	var awds = (Number($rp.html()) * Number($strQty));

	/**
	 * Ручная скидка
	 * =============
	 * Общая "Ручная скидка" делим на "Общее количество"
	 * и умножаем на текущее "Количество"
	 */
	var rs = ((Number($strCommonRs) / Number($strCommonQty)) * Number($strQty));
	
	/**
	 * Скидка по подарочным сертификатам
	 * =================================
	 * Общая "Ручная скидка" делим на "Общее количество"
	 * и умножаем на текущее "Количество"
	 */
	var spps = ((Number($strCommonSpps) / Number($strCommonQty)) * Number($strQty));
	
	/**
	 * Ячейка таблицы "Сумма продажи"
	 * ==============================
	 * "Общая сумма продажи" делим на "Общее количество" = получим сумму продажи за 1ШТ
	 * и умножаем на текущее "Количество"
	 */
	var sp = ((Number($strCommonSp) / Number($strCommonQty)) * Number($strQty));

	/*
	 * Ячейка таблицы "Итого скидки"
	 * =============================
	 * "Сумма без скидок" минус "Сумма продажи"
	 * с проверкой на ноль
	 */
	var is = ( ((awds - sp) > 0)?(awds - sp):0 );

	/*
	 * Ячейка таблицы "Сумма скидок"
	 * =============================
	 * "Итого скидки" минус "Скидка по подарочным сертификатам"
	 * с проверкой на ноль
	 */
	var ss = ( ((is - spps) > 0)?(is - spps):0 );

	/*
	 * Ячейка таблицы "Сумма за вычетом скидок"
	 * ========================================
	 * "Сумма без скидок" минус "Сумма скидок"
	 * с проверкой на ноль
	 */
	var szs = ( ((awds - ss) > 0)?(awds - ss):0 );
	
	awds = number_format(awds, co, fl, th);
	rs = number_format(rs, co, fl, th);
	spps = number_format(spps, co, fl, th);
	sp = number_format(sp, co, fl, th);
	is = number_format(is, co, fl, th);
	ss = number_format(ss, co, fl, th);
	szs = number_format(szs, co, fl, th);

	$sbs.html(awds);
	$rs.html(rs);
	$spps.html(spps);
	$sp.html(sp);
	$is.html(is);
	$ss.html(ss);
	$szs.html(szs);
	
	// пересчет полей подитога раздела 3
	recalculationPS3();
	
	// пересчитываем все строки раздела 1
	forS1();
	
}// function recalculationS3(barcode)

/**
 * Страница "Товарный чек"
 * =======================
 * Пересчет полей подитога раздела 1
 */
function recalculationPS1(){
	
	/**
	 * Выбираем необходимые поля подитогов
	 * разделов (1,3,4)
	 */
	var form = $('.sales-reciept'),
		// Раздел 1: подитог "сумма без скидок"
		p_sbs1 = form.find('.document1 .tfoot1 .p_sbs1 b'),
		// Раздел 1: подитог "сумма скидок"
		p_ss1 = form.find('.document1 .tfoot1 .p_ss1 b'),
		// Раздел 1: подитог "сумма за вычетом скидок"
		p_szvs1 = form.find('.document1 .tfoot1 .p-szvs1 b'),
		// Раздел 1: подитог "скидка по подарочным сертификатам"
		p_sps1 = form.find('.document1 .tfoot1 .p_sps1 b'),
		// Раздел 1: подитог "итого скидки"
		p_is1 = form.find('.document1 .tfoot1 .p_is1 b'),
		// Раздел 1: подитог "сумма продажи"
		p_sp1 = form.find('.document1 .tfoot1 .p_sp1 b'),
		// Раздел 3: подитог "скидка по подарочным сертификатам"
		p_sps31 = form.find('.document1 .tfoot3 .p-sps31 b'),
		// Раздел 3: подитог "сумма продажи"
		p_sp32 = form.find('.document1 .tfoot3 .p-sp32 b'),
		// Раздел 4: подитог "скидка по подарочным сертификатам"
		p_sps4 = form.find('.document1 .tfoot4 .p-sps4 b');
	
	// задаем переменные для математики
	var p_awd, i_awd = 0,
		p_sod, i_sod = 0,
		p_aadod, i_aadod = 0,
		p_dogc, i_dogc = 0,
		p_tds, i_tds = 0,
		p_sa, i_sa = 0;
	
	/**
	 * После обновления количества текущей строки tr
	 * суммируем все значения нужных полей каждой строки в разделе 1,
	 * собираем обновленные данные
	 * и вставляем их в соответствующие поля подитога
	 */
	form.find('.table.document1 tr.section1').each(function(){
		p_awd = $(this).find('.amount-without-discounts1').html();
		i_awd += Number(p_awd);
		p_sod = $(this).find('.sum-of-discounts1').html();
		i_sod += Number(p_sod);
		p_aadod = $(this).find('.amount-after-deduction-of-discounts1').html();
		i_aadod += Number(p_aadod);
		p_dogc = $(this).find('.discount-on-gift-certificates1').html();
		i_dogc += Number(p_dogc);
		p_tds = $(this).find('.total-discounts1').html();
		i_tds += Number(p_tds);
		p_sa = $(this).find('.sales-amount1').html();
		i_sa += Number(p_sa);
	});

	i_awd = number_format(i_awd, co, fl, th);
	i_sod = number_format(i_sod, co, fl, th);
	i_aadod = number_format(i_aadod, co, fl, th);
	i_dogc = number_format(i_dogc, co, fl, th);
	i_tds = number_format(i_tds, co, fl, th);
	i_sa = number_format(i_sa, co, fl, th);

	// Сумма без скидок
	p_sbs1.html(i_awd);
	// Сумма скидок
	p_ss1.html(i_sod);
	// Сумма за вычетом скидок
	p_szvs1.html(i_aadod);
	// Скидка по подарочным сертификатам
	p_sps1.html(i_dogc);
	// Итого скидки
	p_is1.html(i_tds);
	// Сумма продажи
	p_sp1.html(i_sa);
	
	// расчет документа
	calculationDocument();
	
}// function recalculationPS1()

/**
 * Страница "Товарный чек"
 * =======================
 * Пересчет полей подитога раздела 3
 */
function recalculationPS3(){
	
	/**
	 * Выбираем необходимые поля подитогов
	 * разделов (1,3,4)
	 */
	var form = $('.sales-reciept'),
		// Раздел 3: подитог "сумма без скидок"
		p_sbs3 = form.find('.document1 .tfoot3 .p_sbs3 b'),
		// Раздел 3: подитог "сумма скидок"
		p_ss3 = form.find('.document1 .tfoot3 .p_ss3 b'),
		// Раздел 3: подитог "сумма за вычетом скидок"
		p_szvs3 = form.find('.document1 .tfoot3 .p-szvs3 b'),
		// Раздел 3: подитог "скидка по подарочным сертификатам"
		p_sps3 = form.find('.document1 .tfoot3 .p-sps31 b'),
		// Раздел 3: подитог "итого скидки"
		p_is3 = form.find('.document1 .tfoot3 .p_is3 b'),
		// Раздел 3: подитог "сумма продажи"
		p_sp3 = form.find('.document1 .tfoot3 .p-sp32 b');
	
	// задаем переменные для математики
	var p_awd, i_awd = 0,
		p_sod, i_sod = 0,
		p_aadod, i_aadod = 0,
		p_dogc, i_dogc = 0,
		p_tds, i_tds = 0,
		p_sa, i_sa = 0;
	
	/**
	 * После обновления количества текущей строки tr
	 * суммируем все значения нужных полей каждой строки в разделе 3,
	 * собираем обновленные данные
	 * и вставляем их в соответствующие поля подитога
	 */
	form.find('.table.document1 tr.section3').each(function(){
		p_awd = $(this).find('.amount-without-discounts3').html();
		i_awd += Number(p_awd);
		p_sod = $(this).find('.sum-of-discounts3').html();
		i_sod += Number(p_sod);
		p_aadod = $(this).find('.amount-after-deduction-of-discounts3').html();
		i_aadod += Number(p_aadod);
		p_dogc = $(this).find('.discount-on-gift-certificates3').html();
		i_dogc += Number(p_dogc);
		p_tds = $(this).find('.total-discounts3').html();
		i_tds += Number(p_tds);
		p_sa = $(this).find('.sales-amount3').html();
		i_sa += Number(p_sa);
	});

	i_awd = number_format(i_awd, co, fl, th);
	i_sod = number_format(i_sod, co, fl, th);
	i_aadod = number_format(i_aadod, co, fl, th);
	i_dogc = number_format(i_dogc, co, fl, th);
	i_tds = number_format(i_tds, co, fl, th);
	i_sa = number_format(i_sa, co, fl, th);

	// Сумма без скидок
	p_sbs3.html(i_awd);
	// Сумма скидок
	p_ss3.html(i_sod);
	// Сумма за вычетом скидок
	p_szvs3.html(i_aadod);
	// Скидка по подарочным сертификатам
	p_sps3.html(i_dogc);
	// Итого скидки
	p_is3.html(i_tds);
	// Сумма продажи
	p_sp3.html(i_sa);
	
	// пересчитываем раздел 1
	forS1();
	
	// делаем расчет документа
	calculationDocument();
	
}// function recalculationPS3()

/**
 * Страница "Товарный чек"
 * =======================
 * Пересчет полей подитога раздела 2
 */
function recalculationPS2(){
	
	var form = $('.sales-reciept'),
		p_sbs = form.find('.document1 .tfoot2 .p_sbs2 b'),
		p_sp = form.find('.document1 .tfoot2 .p_sp2 b'),
		sbs,i_sbs = 0,
		sp,i_sp = 0;
	
	form.find('.table.document1 tr.section2').each(function(){
		
		// если раздел пуст, то ничего не делаем
//		if($(this).attr('class') == 'empty') return false;
		if(typeof form.find('empty2').attr('class') !== 'undefined') return false;
		
		sbs = $(this).find('.sbs2').html();
		i_sbs += Number(sbs);
		sp = $(this).find('.sp2').html();
		i_sp += Number(sp);
	});

	i_sbs = number_format(i_sbs, co, fl, th);
	i_sp = number_format(i_sp, co, fl, th);

	// Сумма без скидок
	p_sbs.html(i_sbs);
	// Сумма продажи
	p_sp.html(i_sp);
	
	// делаем расчет документа
	calculationDocument();
	
}// function recalculationPS2()

/**
 * Страница "Товарный чек"
 * =======================
 * Пересчет полей подитога раздела 4
 */
function recalculationPS4(){
	
	var form = $('.sales-reciept'),
		p_sps = form.find('.document1 .tfoot4 .p-sps4 b'),
		sps,i_sps = 0;
	
	form.find('.table.document1 tr.section4').each(function(){
		
		// если раздел пуст, то ничего не делаем
//		if($(this).attr('class') == 'empty') return false;
		if(typeof form.find('empty4').attr('class') !== 'undefined') return false;
		
		sps = $(this).find('.sps4').html();
		i_sps += Number(sps);
	});

	i_sps = number_format(i_sps, co, fl, th);
	// Скидка по подарочным сертификатам
	p_sps.html(i_sps);
	
	// пересчитываем все строки раздела 1
	forS1();
	
	// делаем расчет документа
	calculationDocument();
	
}// function recalculationPS4()

/**
 * Страница "Товарный чек"
 * =======================
 * Пересчет раздела 1 со скидкой по дисконтной карте
 * -------------------------------------------------
 * При вставке штрихкода дисконтной карты
 * перебираем все строки раздела 1
 * расставляем значения "скидка по дисконтной карте"
 * и делаем перерасчет всех строк раздела 1
 */
function recalculationS1BySPK(spk){
	
	var form = $('.sales-reciept'),
		barcode = '';
	
	form.find('.table.document1 tr.section1').each(function(){
		
		/**
		 * Проверка, есть ли что то в разделе 1
		 * если раздел пуст,
		 * то добавлять скидку не куда
		 * и пересчитывать раздел 1 не нужно
		 * просто останавливаем цикл
		 */
//		if($(this).attr('class') == 'empty') return false;
		if(typeof form.find('empty1').attr('class') !== 'undefined') return false;
		
		// добавляем в верстку значение "скидка по дисконтной карте"
		$(this).find('#discount_on_discount_card').html(spk);
		
	});
	
	// делаем новые расчеты по всем строкам раздела 1
	forS1();
	forS1();// объяснения в notes.txt
	
}// function recalculationS1BySPK(spk)

/**
 * Страница "Товарный чек"
 * =======================
 * Блок информации
 * "Расчет документа"
 * ------------------
 * Собираем необходимые данные и после пересчета
 * выводим на экран
 */
function calculationDocument(){
	var form = $('.sales-reciept'),
		btnKkm = form.find('button.kkm'),
		// выпадающий список "Способ оплаты"
		so = form.find('select[name=payment_method_bank_card]'),
		// значение выпадающего списока "Способ оплаты"
		sos = form.find('select[name=payment_method_bank_card] option:selected'),
		// Выборка элементов блока "Расчет документа"
		npo = form.find('.npo'),// "Недобор при отоваривании"
		sko = form.find('.sko'),// "Сумма к оплате"
		kvn = form.find('.kvn'),// "К возврату наличными"
		kvnbk = form.find('.kvnbk'),// "К возврату на банковскую карту"
		
		/**
		 * Выборка полей подразделов
		 * разделов 1,3,4 "скидка по подарочным сертификатам"
		 */
		// раздел 1
		p_sps1 = Number(form.find('.p_sps1 b').html()),
		// раздел 3
		p_sps3 = Number(form.find('.p-sps31 b').html()),
		// раздел 4
		p_sps4 = Number(form.find('.p-sps4 b').html()),
		/**
		 * Выборка полей подразделов
		 * разделов 1,3,4 "сумма продажи"
		 */
		// раздел 1
		p_sp1 = Number(form.find('.p_sp1 b').html()),
		// раздел 2
		p_sp2 = Number(form.find('.p_sp2 b').html()),
		// раздел 3
		p_sp3 = Number(form.find('.p-sp32 b').html()),
		
		// переменная для сбора суммы "наличные"
		s3amount_cash = 0,
		td = '';
	
	/**
	 * Блок "Расчет документа"
	 * =======================
	 * Перед расчетами все строки прячем
	 * и все значения убираем
	 * т.к. некоторые строки при новых расчетах
	 * могут стать не нужными
	 */
	npo.hide();
	sko.hide();
	kvn.hide();
	kvnbk.hide();
	npo.find('b').html('');
	sko.find('input').val(zero);
	sko.find('span.span1').html('');
	sko.find('b.b1').html('');
	kvn.find('input').val(zero);
	kvn.find('b').html('');
	kvnbk.find('input').val(zero);
	kvnbk.find('b').html('');
	btnKkm.attr('data-type-check','');
	
	// поле "Выберите способ оплаты" делаем активным
	so.prop('disabled','');
  /**
   * Эту штуку делал, чтобы по умполчанию "способ оплаты"
   * делался по смыслу, если в РД наличка - то в СО - наличка
   * если же БК - то в СО - банковская карта
   * 
   * Если будет не нужно - то потом надо будет удалить эту строку
   */
//	so.val('0');
	
	/**
	 * Расчет недобора при отоваривании
	 * ================================
	 * Проверяем подитоги разделов (3,4) "скидка по подарочным сертификатам"
	 * Если сумма не 0, то делаем дальнейшие действия
	 */
	if((p_sps3 + p_sps4) > 0){
		// Считаем "Недобор при отоваривании"
		if((p_sps3 + p_sps4) > p_sps1){
		// (3) + (4) > (1)
			// недобор при отоваривании
			var v_npo = (p_sps3 + p_sps4 - p_sps1);
			/**
			 * Как только в html вставилось значение
			 * показываем строку "Недобор при отоваривании"
			 */
			v_npo = number_format(v_npo, co, fl, th);
			npo.find('b').html(v_npo).promise().done(function(){
				npo.show();
			});
		}
	}// if
	
	/**
	 * Расчет "сумма к оплате"
	 * ================================
	 * когда платить должен клиент
	 */
	if((p_sp1 + p_sp2) >= p_sp3){
		// (10) + (20) >= (30)
		/**
		 * Показываем строку РД по смыслу, по значению "способ оплаты"
		 * ========================================================
		 * Если способ оплаты не выбран или
		 * способ оплаты (Наличные), то "Сумма к оплате наличными"
		 * иначе "Сумма к оплате банковской картой"
		 */
		if(sos.val() == '0' || sos.val() == '1'){
			var str_sko = 'Сумма к оплате наличными: ';
//			so.val('1');
		}else{
			var str_sko = 'Сумма к оплате банковской картой: ';
//			so.val('2');
		}
		
		var v_number = (p_sp1 + p_sp2 - p_sp3);
		if(v_number != 0){
		
            // делаем счет, соединяем со строкой
            var v_sko = (number_format(v_number, co, fl, th));
            // вставляем в верстку
            sko.find('span.span1').html(str_sko);
            sko.find('b.b1').html(v_sko).promise().done(function(){
                /**
                 * Вставляем значение ещё и в скрытое поле
                 * потому что значения для отправки в БД
                 * собираются циклом по элементам(полям) формы
                 */
                sko.find('input').val(v_sko);
                sko.show();
            });
            
        

            // Задаем тип кнопки ККМ "Оплата"
            btnKkm.attr('data-type-check','0');
        }
	
	}else if((p_sp1 + p_sp2) < p_sp3){
        	
		/**
	 	 * Когда нужно возвращать деньги клиенту
		 * =====================================
		 * (10) + (20) < (30)
		 * ==================
		 * Если раздел 3 не пуст
		 * перебираем все строки, и суммируем только те строки
		 * у которых способ оплаты "наличные"
		 */
		form.find('.table.document1 tr.section3').each(function(){
			// проверка раздела 3 на пустоту
//			if($(this).attr('class') == 'empty') return false;
			if(typeof form.find('empty3').attr('class') !== 'undefined') return false;
			
			// получаем td которое содержит в себе код способа оплаты
			td = $(this).find('td.ptmd').attr('data-payment-method');
			
			/**
			 * Если у текущей строки способ оплаты "наличные"
			 * то суммируем поля "сумма продажи"
			 */
			if(td == '1'){
				s3amount_cash += Number($(this).find('#sales_amount').html());
			}
			
		});
		
		if(s3amount_cash > (p_sp1 + p_sp2)){
			// (30нал)-(10)-(20)
			var v_kvn = (s3amount_cash - p_sp1 - p_sp2);
			v_kvn = number_format(v_kvn, co, fl, th);
			// вставляем в верстку
			kvn.find('b').html(v_kvn).promise().done(function(){
				/**
				 * Вставляем значение ещё и в скрытое поле
				 * потому что значения для отправки в БД
				 * собираются циклом по элементам(полям) формы
				 */
				kvn.find('input').val(v_kvn);
				kvn.show();
			});
			if((p_sp3 - s3amount_cash) > 0){
				// (30) - (30нал)
				var v_kvnbk = (p_sp3 - s3amount_cash);
				v_kvnbk = number_format(v_kvnbk, co, fl, th);
				
				// вставляем в верстку
				kvnbk.find('b').html(v_kvnbk).promise().done(function(){
					/**
					 * Вставляем значение ещё и в скрытое поле
					 * потому что значения для отправки в БД
					 * собираются циклом по элементам(полям) формы
					 */
					kvnbk.find('input').val(v_kvnbk);
					kvnbk.show();
				});
			}
		}
		
		if(s3amount_cash <= (p_sp1 + p_sp2)){
			// (30)-(10)-(20)
			var v_kvnbk = (p_sp3 - p_sp1 - p_sp2);
				v_kvnbk = number_format(v_kvnbk, co, fl, th);
				
			// вставляем в верстку
			kvnbk.find('b').html(v_kvnbk).promise().done(function(){
				/**
				 * Вставляем значение ещё и в скрытое поле
				 * потому что значения для отправки в БД
				 * собираются циклом по элементам(полям) формы
				 */
				kvnbk.find('input').val(v_kvnbk);
				kvnbk.show();
			});
		}
		
		// Задаем тип кнопки ККМ "Возврат"
		btnKkm.attr('data-type-check','1');	
	}
    
    
    
	/**
	 * Если "сумма к оплате" 0
	 * то поле "Выберите способ оплаты" ставим в 0
	 * и отключаем его
	 */
	if(sko.find('input').val() == zero){
		so.val('0');
		so.prop('disabled','true');
	}
    
    /**
     * Если при расчете документа, тип операции продажа/возврат пуст
     * то делаем тип slip (т.е. слип чек)
     */
    if(btnKkm.attr('data-type-check') == '')
        btnKkm.attr('data-type-check','slip');
    
    // Делаем пересчет сдачи покупателю
    deliveryBuyer();
    
}// function calculationDocument()

/**
 * Страница "Товарный чек"
 * =======================
 * Удаляем строку tr из table
 */
function deleteTrSR(thisObj){
	var $this = $(thisObj),
		// вся строка tr
		tr = $this.parent().parent(),
		// td-ячейка строки tr
		td = $this.parent(),
		// получаем номер раздела
		section = td.find('input[name=section]').val();
	
	// удаляем строку
    tr.fadeOut(100).remove();
	
	// Определяем, из какого раздела происходит удаление строки
	switch(section){
		case '1':
			/**
			 * Перебирая все строки в цикле
			 * пересчитываем значения полей
			 * ==================================
			 * подитоги раздела пересчитаются автоматом
			 */
			forS1();
		case '2':
			/**
			 * Пересчитываем только раздел 2
			 * так как этот раздел не учавствует в пересчете раздела 1
			 */
			recalculationPS2();
			break;
		case '3':
			/**
			 * Пересчитываем поля подтитога раздела 3
			 * затем пересчитываем раздел 1
			 */
			recalculationPS3();
			forS1();
			
			break;
		case '4':
			/**
			 * Пересчитываем поля подтитога раздела 4
			 * затем пересчитываем раздел 1
			 */
			recalculationPS4();
			forS1();
			break;
	}
	
	/**
	 * Проверяем, если из текущего раздела была удалена последняя строка,
	 * то вставляем пустую строку в раздел и
	 * обнуляем подитоги текущего раздела
	 */
	pDataReset(section);
	
}// function deleteTrSR(thisObj)

/**
 * Страница "Товарный чек"
 * =======================
 * Перебираем все строки раздела 1
 * и пересчитываем значения каждой строки
 * --------------------------------------
 * flag - флаг, который нужен для отключения
 * изменения значения "data-default" (сумма скидок)
 * т.е. если пересчет происходит при редактировании поля "ручная скидка"
 * то data-default изменять не нужно
 */
function forS1(flag){
	
	var form = $('.sales-reciept'),
		barcode = '';
	
	form.find('.table.document1 tr.section1').each(function(){
		
		/**
		 * Проверка, есть ли что то в разделе 1
		 * если раздел пуст,
		 * то добавлять скидку не куда
		 * и пересчитывать раздел 1 не нужно
		 * просто останавливаем цикл
		 */
		if(typeof form.find('empty1').attr('class') !== 'undefined') return false;

		// получаем штрихкод текущей строки
		barcode = $(this).find('#barcode').html();

		// пересчитываем строку текущей итерации
		recalculationS1(barcode,flag);
		
	});
	
}// function forS1(flag)

/**
 * Страница "Товарный чек"
 * =======================
 * Обнуление подитогов
 * -------------------
 * если удалена последняя строка из люобва раздела (1,2,3,4)
 * то вставляем строку "Пока пусто"
 * и обнуляем подитоги
 */
function pDataReset(section){
	
	var i = 0,
		tfoot = $('.tfoot'+section);
	$('.sales-reciept .table tr.section'+section).each(function(){
		i++;
	});
	
	// если tbody пусто, то всталяем строку "Пока пусто"
	if(i == 0){
		var trEmpty = '<tr class="empty'+section+'"><td colspan="16">Пока пусто</td></tr>';
		tfoot.before(trEmpty);
        
        // Скрываем весь раздел полностью
        $('.sales-reciept .s'+section).hide();
		
		// далее обнуляем подитоги
		tfoot.find('td[class*="p-"] b,td[class*="p_"] b').html(zero);
		
		// и делаем пересчет блока "расчет документа"
		calculationDocument();
	}
	
}// function pDataReset(tbody)

/**
 * Страница "Товарный чек"
 * =======================
 * Сброс всех параметров страницы
 */
function resetPageSRFromDocementID(){
    var form = $('.sales-reciept'),
        btnSave = form.find('.save');
    
    if(btnSave.prop('disabled') == true) resetPageSR('document_id');
}

/**
 * Страница "Товарный чек"
 * =======================
 * Сброс всех параметров страницы
 */
function resetPageSR(input){
	
	var form = $('.sales-reciept'),
        btnSave = form.find('.save'),
		empty1 = form.find('.tfoot1'),
		empty2 = form.find('.tfoot2'),
		empty3 = form.find('.tfoot3'),
		empty4 = form.find('.tfoot4'),
		cardContent = form.find('.card-content'),
		btnKkm = form.find('button.kkm');
	
	/**
	 * Во всех разделах удаляем все строки с товарами
	 * и всталяем строки "Пока пусто"
	 */
	// Удаляем все строки товаров
	form.find('tr[class*=section]').remove();
	/**
	 * Удаляем все строки "Пока пусто"
	 * чтобы в разделы у которых уже есть "Пока пусто"
	 * не добавить "Пока пусто" второй раз
	 */
	form.find('tr[class*=empty]').remove();
	
	// Вставляем во все разделы строки "Пока пусто"
	empty1.before('<tr class="empty1"><td colspan="16">Пока пусто</td></tr>');
	empty2.before('<tr class="empty2"><td colspan="16">Пока пусто</td></tr>');
	empty3.before('<tr class="empty3"><td colspan="16">Пока пусто</td></tr>');
	empty4.before('<tr class="empty4"><td colspan="16">Пока пусто</td></tr>');

	// далее обнуляем подитоги на всей странице
	form.find('tr[class*="tfoot"] td[class*="p-"] b,tr[class*="tfoot"] td[class*="p_"] b').
		html(zero);

	// и делаем пересчет блока "расчет документа"
	calculationDocument();
	
	// обнуляем блок "Дисконтная карта"
	form.find('.w-discount-card-info span b').html('');
	
	// Включаем выключенные элементы
	form.find('.save').prop('disabled','');
	btnKkm.prop('disabled',true);
	form.find('[name=payment_method_bank_card]').prop('disabled','');
	
	// обнуляем все поля
	form.find('[name=payment_method_bank_card]').val('0');
    
    if(input != 'document_id'){
        form.find('[name=document_id]').val('');
    }
    
	form.find('[name=counterparty_document_comment]').val('');
	form.find('[name=name_buyers_document_comment]').val('');
	form.find('[name=buyer_phone_number]').val('');
	form.find('[name=buyer_email]').val('');
	form.find('[name=action]').val('');
	form.find('[name=order_code_on_the_site]').val('');
	form.find('[name=promotional_code]').val('');
	
	// информация о дисконтной карте
	cardContent.fadeOut(100);
	// Номер дисконтной карты (штрихкод)
	spanBarcode = form.find('.span-barcode b'),
	inputBarcode = form.find('input[name=discount_card]'),
	spanFio = form.find('.span-fio b'),// ФИО держателя
	spanPhone = form.find('.span-phone b'),// Номер телефона
	// Накопление по карте за предыдущий год
	accumulationPreviousYear = form.find('.span-accumulation-previous-year b'),
	// Накопление по карте за текущий год
	accumulationCurrentYear = form.find('.span-accumulation-current-year b'),
	// Сумма покупок в текущем году
//	amountPurchasesCurrentYear = form.find('.span-amount-purchases-current-year'),
	// Текущая скидка по карте
	currentDiscountCard = form.find('.span-current-discount-card b'),
	// Знак процента
	percentSign = form.find('.span-current-discount-card + span b'),
	// Возврат, обмен по карте
	returnExchangeByCard = form.find('#return_exchange_by_card b');
	
	spanBarcode.html('');
	inputBarcode.val('');
	spanFio.html('');
	spanPhone.html('');
	accumulationPreviousYear.html('');
	accumulationCurrentYear.html('');
//	amountPurchasesCurrentYear.html('');
	currentDiscountCard.html('');
	percentSign.html('');
	returnExchangeByCard.html('');
	
}// function resetPageSR(tbody)

/**
 * Страница "Товарный чек"
 * =======================
 * Счет сдачи покупателю
 * ------------------------------------
 * По изменению поля проверяем введенное число
 * как только введенное число при вводе стало больше
 * чем сумма к оплате - показываем сдачу
 * Введенное число минус сумма к оплате = сдача
 */
function deliveryBuyer(){
	
	var form = $('.sales-reciept'),
        cashBuyer = form.find('[name=cash_of_buyer]'),
        sko = Number(form.find('.b1').html()),
        span2 = form.find('.rd .span2'),
        b2 = form.find('.rd .b2'),
        $thisVal = Number(cashBuyer.val().replace(',','.')),
        paymentMethod = form.find('[name=payment_method_bank_card] option:selected');

    /**
     * Если способ оплаты "Банковская карта"
     * то ничего не делаем
     */
    if(paymentMethod.val() == '2') return;

    var delivery = Math.floor10(($thisVal - sko),-2);

    if($thisVal > sko){
        span2.show();
        b2.html(number_format(delivery,2,'=',' ')).show();
    }else{
        span2.hide();
        b2.html('').hide();
    }
	
}// function deliveryBuyer()

/**
 * ===================================================
 * END Страница "Товарный чек"
 */


/**
 * Страница "Оприходование товара"
 * ===================================================
 */

/**
 * Страница "Оприходование товара"
 * ===============================
 * Пересчет шапки, итоговые значения
 */
function recalculationCG($this){
	var form = $('.cngs'),
		quantity = form.find('.table1 tbody td.i-qy b'),
		costPrice = form.find('.table1 tbody td.i-cpe b'),
		retailPrice = form.find('.table1 tbody td.i-rpe b'),
		qy_i = 0,
		cpe_i = 0,
		rpe_i = 0;
	
	form.find('.table2 tbody tr').each(function(){
		if($(this).attr('class') == 'empty'){
			return false;// останавливаем цикл
		}
		
		qy_i += Number($(this).find('.quantity').html());
		cpe_i += (
			// Себестоимость умножаем на количество
			Number($(this).find('.cost-price').html()) * Number($(this).find('.quantity').html())
		);
		rpe_i += (
			// Розничную цену умножаем на количество
			Number($(this).find('.retail-price').html()) * Number($(this).find('.quantity').html())
		);
		
	});
	
	// Расставляем всё в верстку
	quantity.html(qy_i);// Количество
	costPrice.html(number_format(cpe_i,2,',',' '));// Себестоимость
	//retailPrice.html(number_format(rpe_i,2,',',' '));// Розничная цена
	retailPrice.html(rpe_i);// Розничная цена
}

/**
 * Страница "Оприходование товара"
 * ===============================
 * Обнуляем страницу
 */
function resetPageCG(){
	var form = $('.cngs'),
		description = form.find('[name=description]'),
		button = form.find('.debit'),
		iQy = form.find('.table1 .i-qy b'),
        iCpe = form.find('.table1 .i-cpe b'),
        iRpe = form.find('.table1 .i-rpe b'),
		table2 = form.find('.table2 tbody');
		
	button.prop('disabled','');
	description.val('');
	iQy.html(zeroz);
	iCpe.html(zeroz);
	iRpe.html(zeroz);
	table2.html('<tr class="empty"><td colspan="16">Пока пусто</td></tr>');
	
}

/**
 * ===================================================
 * END Страница "Оприходование товара"
 */


/**
 * Сервис "KKM"
 * ===================================================
 */

/**
 * ===================================================
 * END Сервис "KKM"
 */


/**
 * Страница "Загрузка файлов Excel"
 * ===================================================
 */

/**
 * Страница "Загрузка файлов Excel"
 * ================================
 * Удаление файла Excel
 */
function deleteFileExcel($this){
	var $this = $($this),
		badge = $('.ddfs .badge'),
		w = $('.w-list-files'),
		tbody = w.find('tbody'),
		load = $this.parent().find('.w-del img'),
		res = $('.res'),
		url = w.attr('data-url'),
		method = w.attr('data-method');
		
	// закрываем окно об ошибках
	cea();

	$.ajax({
		url:url,
		type:method,
		dataType:'json',
		cahse:'false',
		data:{
			id:$this.attr('data-id'),
			name:$this.attr('data-full-name'),
		},
		beforeSend:function(){
			load.fadeIn(100);
			w.addClass('over-dis');
		}
	}).done(function(data){
//		res.html('Done<br>'+JSON.stringify(data));
		if(data.status == 200){
			LoadAlert(data.header,data.message,5000,data.type);
			tbody.html(data.files_list);
			badge.html(data.count);
		}else{
			popUp('.ddfs',data.error,'warning');
		}
		load.fadeOut(100);
			w.removeClass('over-dis');
	}).fail(function(data){
//		res.html('Fail<br>'+JSON.stringify(data));
		LoadAlert('Ошибка','Не известная ошибка',5000,'error');
		load.fadeOut(100);
			w.removeClass('over-dis');
	});
}

/**
 * ===================================================
 * END Страница "Загрузка файлов Excel"
 */


/**
 * Страница "Оприходование товара"
 * ===================================================
 */

/**
 * Страница "Оприходование товара"
 * ===============================
 * Пересчет шапки, итоговые значения
 */
function recalculationCC($this){
	var form = $('.cnce'),
		iS = form.find('.table1 tbody td.i-s b'),
		iS_i = 0;
	
	form.find('.table2 tbody tr').each(function(){
		if($(this).attr('class') == 'empty'){
			return false;// останавливаем цикл
		}
		
		iS_i += Number($(this).find('.nom').html());
		
	});
	
	// Расставляем всё в верстку
	iS.html(number_format(iS_i,0,',',' '));
}

/**
 * Страница "Оприходование товара"
 * ===============================
 * Обнуляем страницу
 */
function resetPageCC(){
	var form = $('.cnce'),
		button = form.find('.debit-certificate'),
		iS = form.find('.table1 .i-s b'),
		table2 = form.find('.table2 tbody');
		
	button.prop('disabled','');
	iS.html(zero_one);
	table2.html('<tr class="empty"><td colspan="16">Пока пусто</td></tr>');
	
}

/**
 * Страница "Оприходование товара"
 * ================================
 * Показать скрыть подсказку
 */

function popoverShow(){
    $('[id=popover]').popover({
        container:'body',
        placement:'top',
        html:'text'
    });
	$('[id=popover]').hover(function () {
        $(this).popover('show');
    },function () {
        $(this).popover('hide');
    });
}

/**
 * ===================================================
 * END Страница "Оприходование товара"
 */


/**
 * Страница "Выгрузка этикеток"
 * ===================================================
 */

/**
 * Страница "Выгрузка этикеток"
 * ============================
 * Кнопка "Печать"
 * Выводим на печать таблицу с товаром
 */
function callPrintLabels() {
    
    var prtContent = $('.list-barcodes');
    var prtCSS = ''+
//        '<link rel="stylesheet" href="print-style.css" type="text/css" />';
    '\
<style type="text/css">\
#print{\
    padding: 0;\
}\
#print .barcode{\
    float: left;\
    width: 160px;\
    margin-bottom: 20px;\
    margin-right: 5px;\
    outline: 1px solid red;\
}\
#print .barcode img{\
    width: 110px;\
}\
#print .info{\
    float: left;\
    font-size: 8px;\
    text-align: center;\
    line-height: 9px;\
    width: 35px;\
    padding: 5px 0 0 0;\
    outline: 1px solid green;\
}\
</style>\
    ';
    var WinPrint = window.open(
        '','list-barcodes','left=50,top=50,width=800,height=640,toolbar=0,scrollbars=1,status=0'
    );
    WinPrint.document.write('<div id="print" class="contentpane">');
    WinPrint.document.write(prtCSS);
    WinPrint.document.write(prtContent.html());
    WinPrint.document.write('</div>');
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
//    prtContent.html(strOldOne);
    
    
}

/**
 * Страница "Выгрузка этикеток"
 * ============================
 * Кнопка "Печать"
 * Выводим на печать таблицу с товаром
 */
function callPrintReestr() {
    
    var prtContent = $('.list-barcodes');
    var prtCSS = ''+
//        '<link rel="stylesheet" href="print-style.css" type="text/css" />';
    '\
<style type="text/css">\
#print .list-string-barcodes{\
    margin-bottom: 11%;\
}\
#print .list-string-barcodes td{\
    outline: 1px solid rgba(0,0,0,.2);\
    padding: 0.15% 10px;\
    font-size: 88%;\
}\
</style>\
    ';
    var WinPrint = window.open(
        '','','left=50,top=50,width=1400,height=640,toolbar=0,scrollbars=1,status=0'
    );
    WinPrint.document.write('<div id="print" class="contentpane">');
    WinPrint.document.write(prtCSS);
    WinPrint.document.write(prtContent.html());
    WinPrint.document.write('</div>');
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
//    prtContent.html(strOldOne);
}

/**
 * Страница "Выгрузка этикеток"
 * ============================
 * Сортироввка строк таблицы
 * по наименованию номенклатуры
 * и по размеру
 */
function sortRowsUnloadingLabels(){
    
    var form = $('.ugls'),
        table = form.find('.table tbody'),
        tableRows = [],
        rows = [],
        html = '',
        i = 0;
    
    // Перебираем все строки таблицы
    table.find('tr').each(function(){
        
        /**
         * Массив со значениями, из которого
         * по индексу конца строки значений массива "tableRows"
         * будем получать подходящие данные
         */
        rows[i] = $(this).find('.content').html()+'|-|'+
            $(this).find('.manufacturer-size').html()+'|-|'+
            $(this).find('.inscription-label').html()+'|-|'+
            $(this).find('.barcode').html()+'|-|'+
            $(this).find('.retail-price').html()+'|-|'+
            $(this).find('.action-price').html()+'|-|'+
            $(this).find('.automatic-discount').html();
        /**
         * Собираем в одну строку данные
         * которые необходимо отсортировать.
         * В конце строки, подставляем индекс соответствующий ключам массива "rows".
         * Потому что индексы массива "tableRows" после сортировки
         * станут не соответствовать индексам массива "rows"
         * А строки сохранят индексы, соответствующие массиву "rows"
         * Далее в итерациях отсортированного массива
         * по этим индексам, взятым с конца строк значений массива "tableRows"
         * будем получать подходящие строки из массива "rows"
         */
        tableRows[i] = 
            $(this).find('.content').html()+'|-|'+
            $(this).find('.manufacturer-size').html()+'|-|.._..'+i;
        i++;
    });
    
    /**
     * После того как из таблицы собрали необходимые данные
     * очищаем HTML табилцы
     */
    table.html('');
    
    // Делаем сортировку массива по значениям
    tableRows.sort();
    
    // Перебираем отсортированный массив
    for(var j=0;tableRows.length;j++){
        
        /**
         * Почему то последнее значение j
         * больше чем последний индекс перебираемого массива
         * по этому если значение элемента tableRows[j] равно undefided
         * то значит, это последний элемент. Останавливаем цикл.
         */
        if(typeof tableRows[j] === 'undefined') break;
        
        /**
         * Получаем индекс от первого цикла
         * расположенный в конце строки текущей итерации
         */
        var last = Number(tableRows[j].substr(tableRows[j].lastIndexOf('.._..') + 5));
        
        /**
         * По индексу last, взятому из текущей итерации
         * отсортированного массива "tableRows"
         * last совпадает с индексом массива "rows"
         * Разбиваем строку на элементы
         * делаем массив
         */
        var newTableRows = rows[last].split('|-|');
        
        // Получаем собранный HTML шаблон tr
        html = renderTemplate('#unloading-labels',{
            "content":newTableRows[0],
            "manufacturer_size":newTableRows[1],
            "labeling":newTableRows[2],
            "barcode":newTableRows[3],
            "retail_price":newTableRows[4],
            "action_price":newTableRows[5],
            "automatic_discount":newTableRows[6]
        });
        
        // Добавляем строку tr в таблицу
        table.append(html);
        
    }
    
}

/**
 * ===================================================
 * END Страница "Выгрузка этикеток"
 */



/**
 * Страница "Поиск чека"
 * ===================================================
 */

/**
 * Страница "Поиск чека"
 * ================================
 * Сброс данных
 */
function resetCheckSearch(){
    var form = $('.cksh');
    
    // Сбрасываем все элементы формы
    form.find('input,select').val('');
    
    // Поле - период
    form.find('[name=time_period]').val('day');
    
    // Поле - "Выберите артикул
    form.find('[name=reference_value]').html('<option value="">Список артикулов пока пуст</option>');
}

/**
 * ===================================================
 * END Страница "Поиск чека"
 */



/**
 * Страница "Товарный учет"
 * ===================================================
 */

/**
 * Страница "Товарный учет"
 * ========================
 * Выпадающие списки
 * "Тип документа"
 * "Месяц"
 * "Год"
 * Заполняем выпадающий список "Выберите документ"
 * по выбранному типу документа
 */
function getDocuments(automatic_start){
    var res = $('.res'),
        form = $('.cyag'),
        url = form.find('[name=document_type]').attr('data-url'),
        method = form.find('[name=document_type]').attr('method'),
        load = form.find('.w-dt img'),
        month = form.find('[name=months] option:selected'),
        year = form.find('[name=years] option:selected'),
        document_type = form.find('[name=document_type]'),
        document = form.find('[name=document]'),
        documentVal = document.val(),// Сохраняем состояние списка "Выберите документ"
        Data = {},
        Data_IF = {};

    /**
     * Если в списке "Выберите документ"
     * Ничего не выбрано или выбрано "Добавить новый"
     */
    if(document.find(('option:selected')).val() == '') Data_IF['empty'] = 'empty';
    if(document.find(('option:selected')).val() == 'new'){
        Data_IF['new'] = 'new';
        Data['new'] = 'new';
    }
    
    /**
     * Если функция запускается вручную
     * значит аргумент "automatic_start" будет "undefined"
     */
    if(typeof automatic_start === 'undefined'){
    
        /**
         * Если в списке "Выберите документ" выбран какой то документ
         * значит объект Data_IF будет пуст,
         * значит выбран какой то документ.
         * Выбранное значение списка "Выберите документ" остается прежним
         */
        if(JSON.stringify(Data_IF) === '{}'){
            popUp('.cyag','Внимание, открытый документ в режиме редактирования!<br>В случае, если выбрано новое значения типа документа, при сохранении будет создана противоположная корректировка открытого документа и создан аналогичный документ с новым выбранным типом документа.','warning');
            return;
        }
        
        Data['document_type'] = document_type.find('option:selected').val();
        
    }else{
        /**
         * Если функция запускается автоматически, 
         * не через ручное изменение выпадающих списков,
         * то проверку списка "Выберите документ" делать не нужно
         * ======================================================
         * Делаем проверку - откуда происходит автоматический запуск
         */
        /**
         * Обновляем список "Выберите документ"
         * по коду типа документа, взятому из
         * выбранного элемента списка "Выберите документ"
         */
        if(automatic_start == 'document'){
            Data['document_type'] = document.find('option:selected').attr('data-type');
        }
        /**
         * Обновляем список "Выберите документ"
         * по коду типа документа, взятому из
         * выбранного элемента списка "Тип документа"
         */
        if(automatic_start == 'document_type'){
            Data['document_type'] = document_type.find('option:selected').val();
        }
        
    }

    Data['year'] = year.val();
    Data['month'] = month.val();

//    cl(Data,'j');
//    return;

    $.ajax({
        url:url,
        type:method,
        cashe:'false',
        dataType:'json',
        data:Data,
        beforeSend:function(){
            load.fadeIn(100);
        }
    }).done(function(data){
//        res.html('Done<br>'+JSON.stringify(data));
        if(data.status == 200){
            /**
             * Если перед сборкой в списке "Выберите документ"
             * было выбрано "Добавить новый"
             * то selected к этому значению поставится при сборке списка
             * и тем самым останется выбранным "Добавить новый"
             */
            document.html(data.document_options);
            // Меняем цвета border'а и шрифта
            document.css({'border':'1px solid rgba(77,193,0,.5)','color':'rgba(68,169,0,1)'});
        }else{
            LoadAlert(data.header,data.message,live,data.type_message);
            /**
             * Если выборка документов пуста
             * то в список "Выберите документ" ставим значение
             * которое было там перед поиском списка документов
             */
            document.html(documents_not_found);
            document.val(documentVal);
            // Меняем цвета border'а и шрифта
            document.css({'border':'1px solid rgba(0,0,0,.2)','color':'#55595c'});
        }
        load.fadeOut(100);
    }).fail(function(data){
        res.html('Fail<br>'+JSON.stringify(data));
        LoadAlert('Error','Ошибка PHP',live,'error');
        load.fadeOut(100);
    });
}

/**
 * Страница "Товарный учет"
 * ========================
 * Выпадающий список "Выберите документ"
 * -------------------------------------
 * Получаем данные по документу
 */
function getDocumentById(document_id){
    var form = $('.cyag'),
        $this = form.find('[name=document]'),
        res = $('.res'),
        load = form.find('.w-dt img'),
        table_blocks = form.find('.blocks'),
        comment = form.find('[name=description]'),
        btn_delete = form.find('button[name=delete_rows]'),
        Data = {};

    /**
     * Если выбрано ничего или новый,
     * то очищаем строки и останавливаем скрипт
     */
    if($this.val() == '' || $this.val() == 'new'){
//        if($this.val() == 'new'){
        resetCA();return;
    }

    // Очищаем блоки 1/2/3
    table_blocks.find('.s1,.s2,.s3').hide();
    table_blocks.find('.section1,.section2,.section3').remove();
    
    /** 
     * Если функция запущена автоматически
     * то будет передан document_id
     */
    if(typeof document_id !== 'undefined')
        /**
         * Если функция запущена автоматически,
         * то список "Тип документа" изменен не будет
         */
        Data['document_id'] = document_id;
    else{
        /** 
         * Если функция запускается вручную,
         * если вручную меняем значения списка "Выберит документ"
         * то document_id передан не будет
         * ------------------------------------------------------
         * Если вручную, то список "Тип документа"
         * будет выбран автоматически, в соответствии с выбранным документом
         */
    
        /**
         * Поле "Тип документа"
         * ставим в соответствие выбранному документу
         */
        form.find('[name=document_type]')
            .val(form.find('[name=document] option:selected')
                 .attr('data-type'));
        
        Data['document_id'] = $this.val();
        /**
         * Номер блока в таблице
         * Подставляется в шаблоне к классу section
         */
        Data['number_section'] = '1';
    }
    
//        cl(Data,'j');
//        return;

    $.ajax({
        url:$this.attr('data-url'),
        type:$this.attr('method'),
        cashe:'false',
        dataType:'json',
        data:Data,
        beforeSend:function(){
            load.fadeIn(100);
        }
    }).done(function(data){
//        res.html('Done<br>'+JSON.stringify(data));
//        res.html(data.dg);
        LoadAlert(data.header,data.message,live,data.type_message);
        if(data.status == 200){
            // Кнопку "Удалить выбранные" делаем активной
            btn_delete.prop('disabled','');
            
            table_blocks.find('.t-header.s1').show();
            table_blocks.find('.section1').remove();
            table_blocks.find('.s1').after(data.rows);
            comment.val(data.comment);
            if(typeof document_id !== 'undefined')
                $this.val(document_id);
            /**
             * После добавления делаем
             * пересчет строк в таблице с перенумерованием строк
             * пересчет итоговых значений
             */
            calculationCA('1');
        }else{

        }
        load.fadeOut(100);
    }).fail(function(data){
        res.html('Fail<br>'+JSON.stringify(data));
        LoadAlert('Error','Ошибка PHP',live,'error');
        load.fadeOut(100);
    });
}

/**
 * Страница "Товарный учет"
 * ========================
 * Функция запускается, если
 * поля "Тип документа" и "Выберите документ"
 * не совпадают
 * -------------------------------------
 * Списываем текущий документ в 0
 * и создаем новый документ с новым типом
 */
function saveNewDocument(btn_save){
    var form = $('.cyag'),
        document = form.find('[name=document]'),
        document_type = form.find('[name=document_type]'),
        res = $('.res'),
        load = form.find('.w-dt img'),
        table_blocks = form.find('.blocks'),
        comment = form.find('[name=description]'),
        provider = form.find('[name=provider]'),
        months = form.find('[name=months]'),
        years = form.find('[name=years]'),
        quantity_row = '',
        Row = {},
        Data = {},
        i=0;
    
    Data['info'] = {};
    Data['table'] = {};
    
    /* ============================================
     Собираем статичные данные со страницы
    ============================================ */
    
    // Номер корректируемого документа
    Data['info']['document_id'] = document.val();
    /**
     * Тип документа
     * Нужен для создания нового документа
     */
    Data['info']['document_type'] = document_type.val();
    // Поле комментарий
    Data['info']['counterparty_document_comment'] = form.find('[name=description]').val();
    
    // Собираем ВСЕ строки со страницы
    table_blocks.find('tr.section1').each(function(){
        
        // Пропускаем невидимые строки
        if($(this).attr('data-visible') != '') return;
        
        Row = {};
        Row['document_id'] = document.val();
        Row['str_dock'] = $(this).find('.counter1').html();
        Row['barcode'] = $(this).find('.td-barcode1').html();
        quantity_row = Number($(this).find('.quantity1').html());
        /**
         * Если тип документа "Оприходование"
         * то количество со знаком +
         * иначе со знаком -
         */
        if(document_type.val() == '04') quantity_row = (0 - quantity_row);
        
        Row['quantity'] = quantity_row;
        
        Data['table'][i] = Row;
        i++;
    });
    
    // Тип операции "document_correction"
    Data['action_type'] = 'document_correction';
    /**
     * Команда, что сразу же нужно будет создать новый документ
     * на основе списанного в ноль
     */
    Data['new_document_type'] = 'new_document_type';
    
    cl('sND');
    cl(Data);
//    return;

    $.ajax({
        url:btn_save.attr('data-url'),
        type:btn_save.attr('method'),
        cashe:'false',
        dataType:'json',
        data:Data,
        beforeSend:function(){
            load.fadeIn(100);
            // Кнопку "Сохранить документ" деактивируем
            btn_save.prop('disabled',true);
        }
    }).done(function(data){
        res.html('Done<br>'+JSON.stringify(data));
//        res.html(data.dg);
        LoadAlert(data.header,data.message,live,data.type_message);
        if(data.status == 200){
            /**
             * Отправляем в аргумент строку
             * Указывающую, что код типа документа нужно взять
             * из выбранного элемента списка "Тип документа"
             */
            getDocuments('document_type');

            /**
             * В списке "Выберите документ
             * делаем выбранным сохраненый документ
             */
            document.val(data.document_id);

            // Выводим на экран данные сохраненного документа
            getDocumentById(data.document_id);

            // Если тип документа 04 или 05, сбрасываем "Выберите поставщика"
            if(document_type.val() == '04' || document_type.val() == '05'){
                provider.val('');
            }

            months.val(data.month);// Устанавливаем текущий месяц
            years.val(data.year);// Устанавливаем текущий год
        }else{

        }
        // Кнопку "Сохранить документ" делаем активной
        btn_save.prop('disabled','');
        load.fadeOut(100);
    }).fail(function(data){
        res.html('Fail<br>'+JSON.stringify(data));
        LoadAlert('Error','Ошибка PHP',live,'error');
        load.fadeOut(100);
    });
}

/**
 * Страница "Товарный учет"
 * ========================
 * Пересчитываем строки документа
 * и итоговые данные
 * ------------------------------
 * s - передает номер блока (1/2/3)
 */
function calculationCA(s){
    var form = $('.cyag'),
        totals = form.find('.totals'),
        blocks = form.find('.blocks'),
        count_rows = blocks.find('tr.section'+s).length,
        quantity = totals.find('.quantity'),
        cost_price = totals.find('.cost-price'),
        retail_price = totals.find('.retail-price'),
        cp_i = 0,rp_i = 0,q_ty = 0;
   
    blocks.find('tr.section'+s).each(function(){
        
        // Невидимые строки не нужны, пропускаем их
        if($(this).attr('data-visible') != '') return;
        
        if($(this).find('.quantity'+s).html() != '0'){
            // Суммируем себестоимость
            cp_i += Number($(this).find('.cost-price'+s).html());
            // Суммируем себестоимость
            rp_i += Number($(this).find('.retail-price'+s).html());
            // Суммируем количетво строк (по полю количество)
            q_ty += Number($(this).find('.quantity'+s).html());
        }
    });
    
    /**
     * Если была удалена последняя строка
     * скрываем заголовок блока
     */
    if(blocks.find('tr.section'+s).length == 0)
        blocks.find('.t-header.s'+s).hide();
    
    // Заполняем итоговые данные
    quantity.html(q_ty);
    cost_price.html(number_format(cp_i,2,',',' '));
    retail_price.html(number_format(rp_i,2,',',' '));
    
}

function quantityCalculationRowsCA(s){
    var form = $('.cyag'),
        totals = form.find('.totals'),
        table = form.find('.table'),
        quantityRows = Number(table.find('tr.section'+s).length),
        quantity = totals.find('.quantity'),
        cost_price = totals.find('.cost-price'),
        retail_price = totals.find('.retail-price'),
        cp_i = 0,rp_i = 0,q_ty = 0;
    
    table.find('tr.section'+s).each(function(){
        $(this).find('.counter1').html(quantityRows);
        quantityRows--;
        
        // Суммируем себестоимость
        cp_i += Number($(this).find('.cost-price1').html());
        // Суммируем себестоимость
        rp_i += Number($(this).find('.retail-price1').html());
        // Суммируем количетво строк (по полю количество)
        q_ty += Number($(this).find('.quantity1').html());
        
    });
    
    // Заполняем итоговые данные
    quantity.html(q_ty);
    cost_price.html(number_format(cp_i,2,',',' '));
    retail_price.html(number_format(rp_i,2,',',' '));
    
}

/**
 * Страница "Товарный учет"
 * ========================
 */
function leftoversShkCA(type){
    var form = $('.cyag'),
        $this = form.find('.btn.leftovers-shk'),// Данные кнопки "Остатки ШК"
        load = $this.find('img'),
        res = form.find('.res'),
        table_blocks = form.find('.blocks'),
        btn_delete = form.find('[name=delete_rows]'),
        document = form.find('[name=document]'),
        document_type = form.find('[name=document_type]'),
        all_b = 0,
        Row = {},
        // Объект для сбора уникальных строк по странице
        Data = {},
        DataDB = {},
        /**
         * Объект для "общего числа по странице"
         * =====================================
         * Количество для вычисления "Остаток на учете".
         * Q_new - Quantity new
         * Q_d - Quantity document
         * -------------------------------------------------------------------
         * В ключе будет уникальный штрихкод,
         * в значении будет сумма количеств всех строк
         * по одному шштрихкоду на странице
         */
        Q_new = {},// количество новых строк по uniq barcode
        Q_d = {},// Количество строк существующего документа по uniq barcode
        All_b = {};// Для уникальных штрихкодов (всех и "new" и "existing")

    // Проверка списка "Выберите документ"
    // Если не "пусто" и не "new"
    if(document.val() != '' && document.val() != 'new'){
        DataDB['action_type'] = 'existing_document';
    }else if(document.val() == 'new'){
        // Если "new"
        DataDB['action_type'] = 'new';

        // Проверка списка "Тип документа"
//            if(document_type.val() == ''){
//                var color = document_type.css('color');
//                var border = document_type.css('border');
//                document_type.css({'border-color':'rgba(255,0,0,1)','color':'rgba(255,0,0,1)'});
//                setTimeout(function(){
//                    document_type.css({'border':border,'color':color});
//                },300);
//                return;
//            }

        if(typeof table_blocks.find('tr.section1').html() === 'undefined'){
            table_blocks.find('.thead')
                .css({'font-size':'15px','color':'red'})
                .animate({fontSize:'13px'}, 250,function(){
                table_blocks.find('.thead').css({color:'#000'})
            });
            return;
        }

    }else{ // Если "пусто", останавливаем скрипт
        var color = document.css('color');
        var border = document.css('border');
        document.css({'border-color':'rgba(255,0,0,1)','color':'rgba(255,0,0,1)'});
        table_blocks.find('.thead')
            .css({'font-size':'15px','color':'red'})
            .animate({fontSize:'13px'}, 250,function(){
            table_blocks.find('.thead').css({color:'#000'});
            document.css({'border':border,'color':color});
        });
        return;   
    }

    // Перебираем строки первого блока
    table_blocks.find('tr.section1').each(function(){

        // Невидимые строки не нужны, пропускаем их
        if($(this).attr('data-visible') != '') return;

        // Сбрасываем объект для сбора информации каждой итерации
        Row = {};

        // Если штрихкод в объекте уже есть
        if(typeof Data[$(this).find('.td-barcode1').html()] !== 'undefined'){

            /**
             * Строки, где ID документа не совпадает ID
             * который выбран в списке "Выберите документ"
             * пропускаем
             */
//                document

            // Суммируем себестоимость
            Row['cost_price'] = Number($(this).find('.cost-price1').html())+
                Number(Data[$(this).find('.td-barcode1').html()]['cost_price']);
            // Суммируем розничную цену
            Row['retail_price'] = Number($(this).find('.retail-price1').html())+
                Number(Data[$(this).find('.td-barcode1').html()]['retail_price']);
            // Суммируем количество
            Row['quantity'] = Number($(this).find('.quantity1').html())+
                Number(Data[$(this).find('.td-barcode1').html()]['quantity']);

//                Row['dock'] = $(this).find('.dock1').html();
            // Описание
            Row['description'] = $(this).find('.description1').html();
            // Размер
            Row['size_manufacturer'] = $(this).find('.size-manufacturer1').html();
            // Дата документа строки
            Row['receipt_date'] = $(this).find('.receipt-date1').html();
            // Штрихкод
            Row['barcode'] = $(this).find('.td-barcode1').html();

            // Собираем всё в один общий объект
            Data[$(this).find('.td-barcode1').html()] = Row;

        }else{
            // Если штрихкода в объекте ещё нет

            // Себестоимость
            Row['cost_price'] = $(this).find('.cost-price1').html();
            // Розничная цена
            Row['retail_price'] = $(this).find('.retail-price1').html();
            // Количество
            Row['quantity'] = Number($(this).find('.quantity1').html());

//                Row['dock'] = $(this).find('.dock1').html();
            // Описание
            Row['description'] = $(this).find('.description1').html();
            // Размер
            Row['size_manufacturer'] = $(this).find('.size-manufacturer1').html();
            // Дата документа строки
            Row['receipt_date'] = $(this).find('.receipt-date1').html();
            // Штрихкод
            Row['barcode'] = $(this).find('.td-barcode1').html();

            // Собираем всё в один общий объект
            Data[$(this).find('.td-barcode1').html()] = Row;

        }

        if($(this).find('.dock1').html() != 'new'){
            // Суммируем количество строк существующего документа
            if(typeof Q_d[$(this).find('.td-barcode1').html()] !== 'undefined'){
                // Количество для вычисления "Остаток на учете"
                Q_d[$(this).find('.td-barcode1').html()] =
                    Number(Q_d[$(this).find('.td-barcode1').html()]) +
                    Number($(this).find('.quantity1').html());
            }else{
                // Количество для вычисления "Остаток на учете"
                Q_d[$(this).find('.td-barcode1').html()] = 
                    Number($(this).find('.quantity1').html());
            }
        }else{
            // Суммируем количество строк "new"
            if(typeof Q_new[$(this).find('.td-barcode1').html()] !== 'undefined'){
                // Количество для вычисления "Остаток на учете"
                Q_new[$(this).find('.td-barcode1').html()] =
                    Number(Q_new[$(this).find('.td-barcode1').html()]) +
                    Number($(this).find('.quantity1').html());
            }else{
                // Количество для вычисления "Остаток на учете"
                Q_new[$(this).find('.td-barcode1').html()] = 
                    Number($(this).find('.quantity1').html());
            }
        }

        if(typeof All_b[$(this).find('.td-barcode1').html()] === 'undefined'){
            // Количество для вычисления "Остаток на учете"
            All_b[$(this).find('.td-barcode1').html()] = $(this).find('.td-barcode1').html();
        }
        all_b++;
    });

    DataDB['document_type'] = document_type.val();
    DataDB['all_barcodes'] = All_b;// Все уникальные штрихкоды таблицы
    DataDB['new_barcodes'] = Q_new;// Новые уникальные штрихкоды таблицы
    DataDB['existing_barcodes'] = Q_d;// Уникальныые штихкоды существующего документа
    /**
     * Номер блока в таблице
     * Подставляется в шаблоне к классу section
     */
    DataDB['number_section'] = '2';

//        cl(DataDB);
//        return;

    $.ajax({
        url:$this.attr('data-url'),
        type:$this.attr('method'),
        dateType:'json',
        cashe:'false',
        data:DataDB,
        beforeSend:function(){ load.fadeIn(100); }
    }).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));
        if(data.status == 200){

            // Удаляем все строки из блока 2
            table_blocks.find('tr.section2').remove();

            table_blocks.find('.s1').hide();
            table_blocks.find('tr.section1').hide();

            // Вставляем в HTML собранный шаблон
            table_blocks.find('.s2').after(data.rows);
            table_blocks.find('.s2').show();
            table_blocks.find('tr.section2').show();

            quantityCalculationRowsCA('2');
            
            /**
             * Если функция была запущена через кнопку "Остатки Размеры"
             * то запустим функцию расчета по размерам
             */
            if(typeof type !== 'undefined'){
                /**
                 * Отложенный запуск кнопки - "Остатки Размеры"
                 * дадим время на загрузку данных на страницу.
                 */
                setTimeout(function(){
                    $this.prop('disabled',true);
                    leftoversDSCA();
                }, 2000);
            }

        }else{

        }
        load.fadeOut(100);
    }).fail(function(data){
        res.html('Fail<br>'+JSON.stringify(data));
        load.fadeOut(100);
    });
    
}

/**
 * Страница "Товарный учет"
 * ========================
 * Кнопка "Остатки Размеры"
 */
function leftoversDSCA(){       //btnShK.prop('disabled',true);
    var form = $('.cyag'),
        btnShK = form.find('.btn.leftovers-shk'),// Данные кнопки "Остатки ШК"
        table = form.find('.blocks tbody'),
        Row = {},
        Section3 = {},
        html = '';
    
    // Скрываем заголовки блоки - 1 и 2
    table.find('.s1,.s2').hide();
    table.find('tr.section1,tr.section2').hide();
        
    table.find('tr.section2').each(function(){
        
        Row = {};
        
        if(typeof Section3[$(this).find('.description1').attr('data-nomenclature-name')+$(this).find('.size-manufacturer1').html()] !== 'undefined'){
            
            Row['dock'] = $(this).find('.dock1').html();
            Row['description'] = $(this).find('.description1').html();
            Row['size_manufacturer'] = $(this).find('.size-manufacturer1').html();
            Row['receipt_date'] = $(this).find('.receipt-date1').html();
            Row['barcode'] = $(this).find('.td-barcode1').html();
            Row['cost_price'] = $(this).find('.cost-price1').html();
            Row['retail_price'] = $(this).find('.retail-price1').html();
            
            Row['account_balance'] = (Number($(this).find('.account-balance1').html()) + Section3[$(this).find('.description1').attr('data-nomenclature-name')+$(this).find('.size-manufacturer1').html()]['account_balance']);
            
            Row['quantity'] = (Number($(this).find('.quantity1').html()) + 
                Section3[$(this).find('.description1').attr('data-nomenclature-name')+$(this).find('.size-manufacturer1').html()]['quantity']);
            
            Row['remainder_fact'] = (Number($(this).find('.remainder-fact1').html()) + 
                Section3[$(this).find('.description1').attr('data-nomenclature-name')+$(this).find('.size-manufacturer1').html()]['remainder_fact']);
            
            Section3[$(this).find('.description1').attr('data-nomenclature-name')+$(this).find('.size-manufacturer1').html()]
                = Row;
            
        }else{
            
            Row['dock'] = $(this).find('.dock1').html();
            Row['description'] = $(this).find('.description1').html();
            Row['size_manufacturer'] = $(this).find('.size-manufacturer1').html();
            Row['receipt_date'] = $(this).find('.receipt-date1').html();
            Row['barcode'] = $(this).find('.td-barcode1').html();
            Row['cost_price'] = $(this).find('.cost-price1').html();
            Row['retail_price'] = $(this).find('.retail-price1').html();
            Row['account_balance'] = Number($(this).find('.account-balance1').html());
            Row['quantity'] = Number($(this).find('.quantity1').html());
            Row['remainder_fact'] = Number($(this).find('.remainder-fact1').html());
            
            Section3[$(this).find('.description1').attr('data-nomenclature-name')+$(this).find('.size-manufacturer1').html()]
                = Row;
        }
        
    });
    
    for(key in Section3){
        html = renderTemplate('#section3',{
            "dock":Section3[key]['dock'],
            "description":Section3[key]['description'],
            "size_manufacturer":Section3[key]['size_manufacturer'],
            "receipt_date":Section3[key]['receipt_date'],
            "barcode":Section3[key]['barcode'],
            "cost_price":Section3[key]['cost_price'],
            "retail_price":Section3[key]['retail_price'],
            "account_balance":Section3[key]['account_balance'],
            "quantity":Section3[key]['quantity'],
            "remainder_fact":Section3[key]['remainder_fact'],
        });
        
        table.find('.s3').after(html);
        
    }
    
    table.find('.s3').show();
    table.find('tr.section3').show();
    
    quantityCalculationRowsCA('3');
    
}

/**
 * Страница "Товарный учет"
 * ========================
 * Кнопка "Удалить выделенные строки"
 */
function deleteRowCA(){
    var form = $('.cyag'),
        blocks = form.find('.blocks tbody'),
        document = form.find('[name=document]'),
        $this_each = '',
        have_deactivated = false;
    
    /**
     * Скрываем заголовки и
     * удаляем все строки
     * блоков 2 и 3
     */
    blocks.find('.s2,.s3').hide();
    blocks.find('tr.section2,tr.section3').remove();
    
    // Показываем блок 1
    blocks.find('.s1').show();
    blocks.find('tr.section1').show();
   
    blocks.find('tr.section1').each(function(){
        $this_each = $(this);
        // Если чекбокс отмечен
        if($this_each.find('.w-checkbox [name=checkbox]').prop('checked')){
            // Если ячейка "Док" имеет строку "new" то удаляем строку
            if($(this).find('.dock1').text() == 'new') $this_each.remove();
            else{
                // Иначе деактивируем строку
                
                // Количество ставим 0
                $this_each.find('.quantity1')
                    .attr('data-quantity',$this_each.find('.quantity1').html())
                    .html('0');
                // Деативируем поле checkbox
                $this_each
                    .find('.w-checkbox [name=checkbox]')
                    .prop('checked',false)
                    .prop('disabled',true);
                // Подсвечиваем строку легким красным
                $this_each.css({'background-color':'rgba(255,0,0,.1)'});
                have_deactivated = true;
            }
        }
    });
    
    /**
     * Если есть деактивированные строки
     * выводим сообщение с пояснением
     */
    if(have_deactivated){
       popUp('.cyag','Строки, помеченные на удаление, будут удалены при нажатии кнопки "Сохранить документ"','warning');
    }
    
    // После удаления делаем пересчет документа(страницы)
    calculationCA('1');
}

/**
 * Страница "Товарный учет"
 * ========================
 * Сбрасываем страницу в ноль
 */
function resetCA(){
    
    var form = $('.cyag'),
        table_blocks = form.find('.blocks');
    // Очищаем все блоки
    table_blocks.find('.section1,.section2,.section3').remove();
    // Скрываем все заголовки
    table_blocks.find('.t-header').hide();
    
    // Обнуляем блок итоговых значений
    form.find('.totals .quantity,.totals .cost-price').html(zero_one);
    form.find('.totals .retail-price').html(zeroz);
    
    // Сбрасываем список "Выберите поставщика"
    form.find('[name=provider]').val('');
    // Сбрасываем поле комментария
    form.find('[name=description]').val('');
    
}

/**
 * Страница "Товарный учет"
 * ========================
 * Инкрементим порядковый номер, на один - больше числа
 * на котором прерывается правильный порядок
 * Пример:
 * из порядка 123_56
 * нужно получить 4
 */
function rowSequenceIncrement(){
    var form = $('.cyag'),
        table_blocks = form.find('.blocks'),
        arr = [],
        i = 0;
    
    // Собираем все цифры в массив
    table_blocks.find('tr.section1').each(function(){
        arr[i] = $(this).find('.counter1').html();
        i++;
    });
    
    // Удаляем из массива все дубликаты
    var arr_unique_numbers = unique(arr);
    // Сортируем по возрастанию
    arr_sort = arr_unique_numbers.sort(sortFunction);
    
    var first_element = Number(arr_sort[0]);
    
    for(var j=0;j<arr_sort.length;j++){
        if(first_element == arr_sort[j]) first_element++;
        else break;
    }
    
    return first_element;
}
// Для правильной сортировки функции .sort();
function sortFunction(a, b){ return (a - b) }

// Из массива удаляем все дубликаты
function unique(arr) {
    var result = [];

    nextInput:
    for (var i = 0; i < arr.length; i++) {
        var str = arr[i]; // для каждого элемента
        for (var j = 0; j < result.length; j++) { // ищем, был ли он уже?
            if (result[j] == str) continue nextInput; // если да, то следующий
        }
        result.push(str);
    }

    return result;
}

/**
 * Страница "Товарный учет"
 * ========================
 * В таблице document поле document_correction_code ставим в 0
 */
function documentCorrectionCodeOff(document_id){
    var form = $('.cyag'),
        btn_save = form.find('.save'),
        res = $('.res'),
        load = form.find('.w-dt img'),
        Data = {};
    
    // Номер корректируемого документа
    Data['document_id'] = document_id;
    Data['action_type'] = 'disabled_document_id';
    
    cl(Data);
//    return;

    $.ajax({
        url:btn_save.attr('data-url'),
        type:btn_save.attr('method'),
        cashe:'false',
        dataType:'json',
        data:Data,
        beforeSend:function(){
            load.fadeIn(100);
            // Кнопку "Сохранить документ" деактивируем
            btn_save.prop('disabled',true);
        }
    }).done(function(data){
        res.html('Done<br>'+JSON.stringify(data));
        LoadAlert(data.header,data.message,live,data.type_message);
        if(data.status == 200){
            /**
             * Обновляем список "Выберите документ"
             * по коду типа взятому из списка "Тип документа"
             */
            getDocuments('document_type');
            
        }else{

        }
        // Кнопку "Сохранить документ" делаем активной
        btn_save.prop('disabled','');
        load.fadeOut(100);
    }).fail(function(data){
        res.html('Fail<br>'+JSON.stringify(data));
        LoadAlert('Error','Ошибка PHP',live,'error');
        load.fadeOut(100);
    });
}

/**
 * ===================================================
 * END Страница "Товарный учет"
 */



/**
 * ===================================================
 *                  Страница "Заказы"
 * ===================================================
 */

/**
 * Страница "Заказы"
 * =================
 * Сбрасываем страницу
 */
function clearOrdersPage(){
    var form = $('.orrs'),
        info = form.find('.info'),
        itogo = form.find('.itogo'),
        order_number = info.find('.order-number span'),
        customer = info.find('.customer span'),
        order_status = info.find('.order-status span'),
        phone = info.find('.phone span'),
        comment = info.find('textarea'),
        total_amount = itogo.find('.total-amount span'),
        total_discount = itogo.find('.total-discount span'),
        order_compiled = form.find('[name=order_compiled]'),
        order_c = form.find('[name=order_c]'),

        table = form.find('.table tbody');
    
    // Сбрасываем всю страницу
            
    // Номер заказа
    order_number.html('...');
    // Имя пользователя
    customer.html('...');
    // Статус заказа
    order_status.html('...');
    // Телефон пользователя
    phone.html('...');
    // Комментарий
    comment.val('');

    // Выпадающий список "Готорвые заказы"
    form.find('[name=ready_orders]').val('');

    // Таблица
    table.html(tr_empty);

    // Итоговые данные
    total_amount.html(zeroz);
    total_discount.html(zeroz);

    // Сбрасываем и отклчаем все checkbox и radio
    order_compiled.removeAttr('checked').prop('disabled',true);
    order_c.removeAttr('checked').prop('disabled',true);
}

/**
 * ===================================================
 *                  END Страница "Заказы"
 * ===================================================
 */
































/**
 * ===================================================
 *                  Страница ""
 * ===================================================
 */



/**
 * ===================================================
 *                  END Страница ""
 * ===================================================
 */





// HTML шаблонизатор
function renderTemplate(name, data) {
	var template = $(name).html();
    for (var property in data) {
        if (data.hasOwnProperty(property)) {
            var search = new RegExp('{' + property + '}', 'g');
            template = template.replace(search, data[property]);
        }
    }
    return template;
}






























































