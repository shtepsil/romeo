/**
 * Переменная (zero) задается в AdminAppAsset через init()
 */

$(function(){

    /**
     * Страница "Справочники"
     * ===================================================
     */

    /** 
     * Страница "Справочники"
     * ======================
     * По изменению поля "выберите справочник"
     * Ajax'ом заполняем поле "значение справочника"
     * directory - справочник
     */

    $('.reference-books .directory').change(function(e){
        e.preventDefault();
        var res = $('.res'),
            $this = $(this),
			form = $('.reference-books'),
			codeInput = form.find('input.code'),
			codeSelect = form.find('select.code'),
            load = $('.rb .wrb img'),
            url = $this.attr('url'),
            referenceValues = $('.reference-values'),
            edit = $('.edit'),
            code = $('.code'),
            method = $this.attr('method'),
			emptyOption = '<option value="">Код значения</option>';

        edit.val('').prop('disabled', true);
		codeSelect.html(emptyOption).prop('disabled', true).fadeIn(100);

        if($this.val() == ''){
            referenceValues.html('<option value="empty">Значение справочника пока пусто</option>');
            return;
        }

        $.ajax({
            url:url,
            type:method,
            cashe:'false',
            dataType:'json',
            data:{
                table:$this.val(),
                list_name: 'Выберите значение справочника',
                new_value: 'Добавить новое значение',
                empty_value: 'Справочник пока пуст'
            },
            beforeSend:function(){
                // res.html('Download');
                load.fadeIn(100);
            }
        }).done(function(data){
            // res.html('Done<br>'+JSON.stringify(data));
            if(data.status == 200){
                // LoadAlert(data.header,data.message,live,data.type_message);
                referenceValues.html(data.option_s);
                // LoadAlert('<span class="glyphicon glyphicon-thumbs-up"></span>',data.message,live);

            }else{
                LoadAlert('407','Ошибка',live,'warning');
            }
            load.fadeOut(100);
        }).fail(function(data){
            // res.html('Fail<br>'+JSON.stringify(data));
            LoadAlert('Error','Ошибка PHP',live,'error');
            load.fadeOut(100);
        });

    });

    /** 
     * Страница "Справочники"
     * ======================
     * По изменнию поля "выберите значение справочника"
     * Вставляем value в поле для редактирования
     */
    $('.reference-books .reference-values').change(function(){
        var $this = $(this),
            form = $('.reference-books'),
            $rv = $('.reference-values'),
            $value = $rv.find('option:selected'),
            $referenceBooks = $('.directory option:selected'),
			codeInput = form.find('input.code'),
			codeSelect = form.find('select.code'),
            edit = $('.edit'),
            editCode = false,
			optionDefault = '<option value="">Выберите не занятый код</option>',
			options = '',
			existingCodes = [],
			emptyOption = '<option value="">Код значения</option>',
			selected = '';// чтобы редактируемый код был выбран автоматически
		
		if($referenceBooks.val() == 'brand' ||
		$referenceBooks.val() == 'product_group' ||
		$referenceBooks.val() == 'gender') editCode = true;
		
		// Если выбран пол, нужно выбрать не занятые коды gender из массива codesG
		if($referenceBooks.val() == 'gender'){
			// Собираем в массив все занятые коды
			$rv.find('option').each(function(){
				if($(this).val() == 'new' || $(this).val() == 'empty') return;
				existingCodes.push($(this).attr('data-code'));
			});

			// строим HTML options для select с не занятыми кодами
			codesG.forEach(function(item, i, codesBP ) {
				// пропускаем занятые коды
				if(existingCodes.indexOf(item) != -1) return;
				options += '<option value="'+item+'">'+item+'</option>';
			});
		}else{
			/* 
			 * Если выбран бренд/товарная группа,
			 * то нужно выбрать не занятые коды brand, product_group
			 * из массива codesBP
			 */
			// Собираем в массив все занятые коды
			$rv.find('option').each(function(){
				if($(this).val() == 'new' || $(this).val() == 'empty') return;
				existingCodes.push($(this).attr('data-code'));
			});

			// строим HTML options для select с не занятыми кодами
			codesBP.forEach(function(item, i, codesBP ) {
				// пропускаем занятые коды
				if(existingCodes.indexOf(item) != -1) return;
				options += '<option value="'+item+'">'+item+'</option>';
			});
		}
		
        if($this.val() == 'empty'){
			
            if(editCode){
				codeSelect.html(emptyOption).prop('disabled', true).fadeIn(100);
			}
            edit.val('').prop('disabled', true);
			
        }else if($this.val() == 'new'){
			
			if(editCode){
				codeSelect.html(optionDefault+options).prop('disabled', false).fadeIn(100);
			}
            edit.val('').prop('disabled', false).focus();
			
        }else{
			if(editCode){
				options =
					optionDefault+
					'<option value="'+$this.find('option:selected').attr('data-code')+'" selected>'+$this.find('option:selected').attr('data-code')+'</option>'+
					options;
				codeSelect.html(options).prop('disabled', false);
			}
			edit.val($value.text()).prop('disabled', false).focus();
        }
    });

    /** 
     * Страница "Справочники"
     * ======================
     * Нажатие кнопки "внести изменения"
     */
    $('.reference-books .button').on('click',function(){
        var $this = $(this),
			res = $('.res'),
            form = $('.reference-books'),
            load = form.find('.button img'),
			// Выпадающий сисок "Выберите справочник"
            directory = form.find('.directory'),
			// Выпадающий список "Значение справочника"
            referenceValues = form.find('.reference-values'),
			// Выбранный элемент списка "Значение справочника"
            $selected = referenceValues.find('option:selected'),
			// поле для редактирования значений справочника
            edit = $('.edit'),
			// поле для редактирования кодов
            code = $('.code'),
			// по умолчанию, редактирование справочника
			type = 'edit';
		
		/**
		 * Если в поле "Значение справочника"
		 * выбрано значение "Добавить новое значение"
		 * то это значит, что будет добавление нового значения справочника
		 * Ставим флаг в new
		 */
		if($selected.val() == 'new') type = 'new';

        if(code.val() == '' && edit.val() == ''){
            LoadAlert('Внимание','Поля для редактирования пусты',live,'warning');
            return;
        }
		
        $.ajax({
            url:$this.attr('data-url'),
            type:$this.attr('method'),
            cashe:'false',
            dataType:'json',
            data:{
                table:directory.val(),// в value - имя таблицы
                type:type,
                id:$selected.val(),
                code:code.val(),
                name:edit.val()
            },
            beforeSend:function(){
                load.fadeIn(100);
            }
        }).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));
            LoadAlert(data.header,data.message,live,data.type_message);
            edit.attr('data-type','edit');
            if(data.status == 200){
                referenceValues.html(data.option_s);
                // code.val('');
                // edit.val('');
            }
            load.fadeOut(100);
        }).fail(function(data){
            res.html('Fail<br>'+JSON.stringify(data));
            LoadAlert('Error','Ошибка PHP',live,'error');
            load.fadeOut(100);
        });

    });

    /**
     * ===================================================
     * END Страница "Справочники"
     */

    /**
     * Страница "Номенклатура товара"
     * ===================================================
     */

    /**
     * Страница "Номенклатура товара"
     * ==============================
     * По изменнию поля "выберите Бренд"
     * Заполняем select "Выберите артикул" списоком артикулов текущего Бренда
     */
    $('.ptne .brands').on('change',function(){
        var $this = $(this),
            res = $('.res'),
            load = $('.ptne .wvc img'),
            $selected = $('.ptne .brands option:selected'),
            vc = $('.ptne .vendor-code'),
            edit = $('.ptne .edit'),
            // Блок с загруженными изображениями
            dropzone = $('#previews'),
            empty_value = 'Список артикулов пуст',
            // Кнопка "Загрузка изображений"
            btnDownloadImages = $('.modal-in'),
            isImages = $('.is-images');
        
        /**
         * Удаляем все изображения
         * которые были в модалке
         */
        dropzone.html('');
        // Убираем строку оповещения о загруженных файлах
        isImages.html('').removeClass('ptne-is-images-red,ptne-is-images-green');
        // Отключаем кнопку "Загрузка изображений"
        btnDownloadImages.prop('disabled', true);
		
		/**
		 * Обнуляем данные на странице
		 */
		productNomenclaturReset();
		
        // когда выбрано "ничего"
        if($selected.val() == ''){
            vc.html('<option value="">'+empty_value+'</option>');
            edit.val('').prop('disabled',true);
            return;
        }

        $.ajax({
            url:$this.attr('action'),
            type:$this.attr('method'),
            cashe:'false',
            dataType:'json',
            data:{
                page:'product_nomenclature',
                brand_code:$selected.attr('data-code'),
                list_name: 'Выберите артикул',
                new_value: 'Добавить новый артикул',
                empty_value:empty_value
            },
            beforeSend:function(){
                load.fadeIn(100);
            }
        }).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));
            // LoadAlert(data.header,data.message,live,data.type_message);
            edit.val('').prop('disabled',true);
            if(data.status == 200){
                vc.html(data.option_s);
            }
            load.fadeOut(100);
        }).fail(function(data){
            res.html('Fail<br>'+JSON.stringify(data));
            LoadAlert('Error','Ошибка PHP',live,'error');
            load.fadeOut(100);
        });
    });

    /**
     * Страница "Номенклатура товара"
     * ==============================
     * По изменнию поля "Выберите артикул"
     * заполняем поле для редактирования значением артикула
     * заполняем поля из БД по паре бренд-артикул
     */
    $('.ptne .vendor-code').on('change',function(){
        var $this = $(this),
			form = $('.ptne'),
//            res = $('.res'),
            load = $('.wvc img'),
			// выбраный элемент
            $selected = $('.ptne .vendor-code option:selected'),
			// выбраный элемент поля бренд
            barnd = $('.ptne [name=brand_code] option:selected'),
			// поля для редактирования артикулов
            edit = $('.ptne .edit'),
            // Блок с загруженными изображениями
            dropzone = $('#previews'),
            // Кнопка "Загрузка изображений"
            btnDownloadImages = $('.modal-in'),
            isImages = $('.is-images');
		
		/**
		 * Если в поле "Выберите артикул" не выбрано
		 * "Добавить новый артикул" или "Выберите артикул"
		 * то идем в БД и получаем данные по паре бренд-артикул
		 */		
		if($selected.val() != 'new' && $selected.val() != 'empty'){
			
			var action = $this.attr('action'),
				method = $this.attr('method');
		
			// Делаем выборку необходимых полей
			// ================================
				// код бренда
			var	brandCode = form.find('.brands option:selected'),
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
				// выберите силуэт
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
				displayOnTheSite = form.find('.display-on-the-site'),
				// адрес страницы товара на сайте
				wrapDetailPageUrl = form.find('.dpu'),
				inputDetailPageUrl = 
					wrapDetailPageUrl.find('input[name=detail_page_url]'),
				linkDetailPageUrl = wrapDetailPageUrl.find('a');
			
			/**
			 * Обнуляем данные на странице
			 */
			productNomenclaturReset();
			
			/**
			 * Запрашиваем данные из талблицы "product_nomenclature" 
			 * по паре бренд-артикул
			 */
			$.ajax({
				url:action,
				type:method,
				cash:'false',
				dataType:'json',
				data:{
					brand_code:brandCode.attr('data-code'),
					vendor_code:$selected.text()
				},
				beforeSend:function(){ load.fadeIn(100); }
			}).done(function(data){
//			 	res.html(JSON.stringify(data));
				if(data.status == 200){
					LoadAlert(data.header,data.message,live,data.type_message);
					/**
					 * Вставляем данные в HTML
					 * -----------------------
					 * текстовые поля
					 */
					
					// наименование номенклатуры
					nomenclatureName.value = data.nomenclature_name;
					// особенности модели
					featuresOfTheModel.value = data.features_of_the_model;
					// описание товара на сайте
					productDescriptionOnTheSite.value = 
						data.product_description_on_the_site;
					// надпись на этикетке
					labeling.value = data.labeling;
					// номенклатурные коды похожие на товары
					nomenclatureCodesSimilarProducts.value = 
						data.nomenclature_codes_similar_products;
					// надпись на этикетке
					nomenclaturalCodes.value = data.nomenclatural_codes;
					// признак новинка сезона (дата)
					noveltyOfTheSeason.value = data.novelty_of_the_season;
					
					/**
					 * Вставляем данные в HTML
					 * -----------------------
					 * выпадающие списки
					 */
					// выберите тованую группу
					productGroup.val(data.commodity_group_code),
					// выберите ворот
					neckband.val(data.code_collar),
					// выберите ширина
					width.val(data.code_width),
					// выберите защипы
					defenses.val(data.security_code),
					// выберите пол
					gender.val(data.code_sex),
					// выберите застежка
					clasp.val(data.code_clasp),
					// выберите рукав
					sleeve.val(data.code_sleeve),
					// выберите шлицы
					splines.val(data.code_slots),
					// выберите рисунок/узор
					design.val(data.code_pattern),
					// выберите число пуговиц
					numberButtons.val(data.code_number_of_buttons),
					// выберите силуэт
					silhouette.val(data.code_silhouette),
					// выберите сезон
					season.val(data.code_season),
					// выберите состав верх
					compositionTop.val(data.code_composition_top),
					// выберите карманы
					pockets.val(data.code_pockets),
					// выберите пояс
					belt.val(data.code_belt),
					// выберите утеплитель
					insulation.val(data.code_insulation),
					// выберите состав наполнитель
					compositionFiller.val(data.code_filler_composition),
					// выберите капюшон
					hood.val(data.code_hood),
					// выберите пряжка
					buckle.val(data.code_buckle),
					// выберите цвет
					color.val(data.code_color),
					// выберите состав подклад
					compositionLining.val(data.code_composition_lining),
					// выберите длина
					length.val(data.code_length),
					// выберите линия посадки
					landingLine.val(data.code_landing_line),
					// отображать на сайте (да/нет)
					displayOnTheSite.val(data.display),
					// адрес страницы товара на сайте					
					inputDetailPageUrl.val(data.detail_page_url);
					linkDetailPageUrl.attr('href',data.detail_page_url);
					linkDetailPageUrl.html(data.detail_page_url);
                    
                    /**
                     * Загружаем изображения
                     * по текущей номенклатуре
                     */
                    reloadImages(barnd.attr('data-code')+'/'+$selected.val());
                    
					
				}else{
					popUp('.pn',data.message,'danger');
				}
				load.fadeOut(100);
			}).fail(function(data){
			 	res.html(JSON.stringify(data));
				LoadAlert('Error','Ошибка PHP',live,'error');
				load.fadeOut(100);
			});
		}else{
			/**
			 * Если в поле "Выберите артикул" будет выбрано
			 * empty или new, то
			 * обнуляем данные на странице
			 */
			productNomenclaturReset();
            /**
             * Удаляем все изображения
             * которые были в модалке
             */
            dropzone.html('');
            // Убираем строку оповещения о загруженных файлах
            isImages.html('').removeClass('ptne-is-images-red,ptne-is-images-green');
		}
		
		/**
		 * Поле edit опустошается,
		 * по этому заполняем это поле после опустошения
		 */
		if($this.val() == 'new'){
			edit.val('').attr('data-type','new').prop('disabled', false).focus();
            btnDownloadImages.prop('disabled', false);
		}else if($this.val() == 'empty'){
			edit.val('').prop('disabled', true);
            btnDownloadImages.prop('disabled', true);
		}else{
			edit.val($selected.text()).prop('disabled',false);
            btnDownloadImages.prop('disabled', false);
		}
		
    });

    /**
     * Страница "Номенклатура товара"
     * ==============================
     * Поле "Признак новинка сезона (дата)"
     * Вызов jQuery календаря
     */
    $('#datepicker').datepicker({
        monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрьr','Октябрь','Ноябрь','Декабрь'],
        dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
        firstDay: 1,
        dateFormat: 'yy-mm-dd',
//        maxDate: new Date()
    });

    /**
     * Страница "Номенклатура товара"
     * ==============================
     * Кнопка "Внести изменения"
     * Отправка главной формы в БД
     */
    $('.ptne').submit(function(e){
		e.preventDefault();
		
		// закрываем окно об ошибках
        cea();

        var $this = $(this),
			res = $('.res'),
            method = $this.attr('method'),
            action = $this.attr('action'),
            $selected = $('.ptne select[name=brand_code] option:selected'),
			$selectedVC = $('.ptne .vendor-code option:selected'),
			$vcList = $('.ptne .vendor-code'),
			type = 'edit';// по умолчанию, редактирование номенклатуры
		
		/**
		 * Если в поле "Выберите артикул" выбрано значение "Добавить новую запись"
		 * то это значит, что будет добавление новой номенклатуры
		 * Ставим флаг в new
		 */
		if($selectedVC.val() == 'new') type = 'new';

        var DataPtne = {};
		// собираем все поля "input,textarea,select" - в объкект
        $(".ptne").find("input,textarea,select").not('[type="file"]').each(function() {
            if(
				// $(this).attr('name') == 'reference_value'
                $(this).attr('name') == '_method' ||
                $(this).attr('name') == '_csrf'){}
            else{
                if($(this).attr('name') == 'brand_code'){
                    DataPtne[$(this).attr('name')] = $selected.attr('data-code');
                }else if($(this).attr('name') == 'reference_value'){
                    DataPtne[$(this).attr('name')] = $(this).find('option:selected').text();
                }else{
                    DataPtne[$(this).attr('name')] = $(this).val();
                }
            }
        });
		
		/**
		 * Флаг, указывающий - делаем новую запись номенклатуры
		 * или редактируем существующую номенклатуру
		 */
		DataPtne['type'] = type;
        
//        cl(DataPtne);
//        return;

        $.ajax({
            url:action,
            type:method,
            cash:'false',
            dataType:'json',
            data:DataPtne,
            beforeSend:function(){}
        }).done(function(data){
//            res.html(JSON.stringify(data));
            if(data.status == 200){
                LoadAlert(data.header,data.message,live,data.type_message);
				if(data.content != false){
					$vcList.html(data.content);
				}
            }else{
                popUp('.pn',data.message,'danger');
            }
        }).fail(function(data){
//            res.html(JSON.stringify(data));
            LoadAlert('Error','Ошибка PHP',live,'error');
        });

    });

    // Показываем модальное окно загрузки файлов
    $('.modal-in').on('click',function(){

        var mu = $('.modal-upload'),
            mbd = $('.modal-background'),
            md = $('.modal-dialog'),
            vendorCode = $('.vendor-code');
        
        // Проверка, сохранена ли номенклатура
        if(vendorCode.val() == 'new'){
            var textDi = 'Сначала нужно сохранить номенклатуру';
            LoadAlert('Внимание',textDi,5000,'warning');
            return;
        }
        
        mbd.fadeIn(100,function(){
            mu.show().animate({'top':'80px'},100).animate({'top':'50px'},200);
        });

    });

    /**
     * Страница "Номенклатура товара"
     * ==============================
     * Закрываем модальное окно загрузки файлов
     */
    // Прячем модальное окно загрузки файлов
    $('.modal-out,.modal-background').on('click',function(){
        var mu = $('.modal-upload'),
            mbd = $('.modal-background'),
            mc = $('.modal-content'),
            m = $('.modal-dialog'),
			// выбраный элемент поля бренд
            barnd = $('.ptne [name=brand_code] option:selected'),
			// выбраный элемент поля "Выберите артикул"
            $selected = $('.ptne .vendor-code option:selected');
        
        // Перезагружаем изображения
        reloadImages(barnd.attr('data-code')+'/'+$selected.val());
        
        var to_top = (-200 - mc.height());
        mu.animate({'top':'80px'},150).animate({'top':to_top},100,function(){
            mu.hide();
            mbd.fadeOut(100);
        });
    });

    /**
     * ===================================================
     * END Страница "Номенклатура товара"
     */

    /**
     * Страница "Открыть документ"
     * ===================================================
     */

    /**
     * Страница "Открыть документ"
     * ===========================
     * Диапазон дат.
     * С Даты до даты
     */
    var dates = $(".open-document #from, .open-document #to").datepicker({
        monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрьr','Октябрь','Ноябрь','Декабрь'],
        dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
        firstDay: 1,
        dateFormat: 'yy-mm-dd',
        maxDate: new Date(), // добавил вот эту строку
        onSelect: function(selectedDate){
            var option = this.id == "from" ? "minDate" : "maxDate",
                instance = $( this ).data( "datepicker" ),
                date = $.datepicker.parseDate(
                    instance.settings.dateFormat || $.datepicker._defaults.dateFormat,
                    selectedDate, instance.settings);
            dates.not(this).datepicker("option", option, date);
        }
    });


    /**
     * ===================================================
     * END Страница "Открыть документ"
     */

    /**
     * Страница "Кассовый отчет"
     * ===================================================
     */

    /**
     * Страница "Кассовый отчет"
     * =========================
     * Диапазон дат.
     * С Даты до даты
     */
    var dates = $(".cr #date").datepicker({
        monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрьr','Октябрь','Ноябрь','Декабрь'],
        dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
        firstDay: 1,
        dateFormat: 'yy-mm-dd',
        maxDate: new Date(), // добавил вот эту строку
    });
    
//    $('.test').on('click',function(){
//        if(!balanceCheck()){}
//        else alert('After balanceCheck');
//    });


    /**
     * ===================================================
     * END Страница "Кассовый отчет"
     */

    /**
     * Страница "Форма поиска"
     * ===================================================
     */

    /**
     * Страница "Форма поиска"
     * =======================
     * По изменнию поля "выберите Бренд"
     * Заполняем select "Выберите артикул" списоком артикулов текущего Бренда
     */
    $('.srch .brands').on('change',function(){
        var $this = $(this),
            res = $('.res'),
            load = $('.srch .w-vc img'),
            $selected = $('.srch .brands option:selected'),
            vc = $('.srch .vendor-code'),
            empty_value = 'Список артикулов пуст';

        // когда выбрано "ничего"
        if($selected.val() == ''){
            vc.html('\
                <option value="">Выберите артикул</option>\
                <option value="">'+empty_value+'</option>');
            edit.val('').prop('disabled',true);
            return;
        }

        $.ajax({
            url:$this.attr('action'),
            type:$this.attr('method'),
            cashe:'false',
            dataType:'json',
            data:{
                table:'vendor_code',
                page:'search',
                id:$this.val(),
                where:true,
                list_name: 'Выберите артикул',
                new_value: 'Добавить новый артикул',
                empty_value: empty_value
            },
            beforeSend:function(){
                load.fadeIn(100);
            }
        }).done(function(data){
            // res.html('Done<br>'+JSON.stringify(data));
            // LoadAlert(data.header,data.message,live,data.type_message);
            if(data.status == 200){
                vc.html(data.option_s);
            }
            load.fadeOut(100);
        }).fail(function(data){
            // res.html('Fail<br>'+JSON.stringify(data));
            LoadAlert('Error','Ошибка PHP',live,'error');
            load.fadeOut(100);
        });
    });

    /**
     * ===================================================
     * END Страница "Форма поиска"
     */

    /**
     * Страница "Поступление товара"
     * ===================================================
     */

    /**
     * Страница "Поступление товара"
     * =============================
     * Кнопка "Добавить строку"
     */
    $('.gr .add').on('click',function(){
        var $this = $(this),
            action = $this.attr('action'),
            method = $this.attr('method'),
            tbody = $('.gr .table tbody'),
            load = $('.gr .btn.add img'),
//            quantity = $('.gr .quantity'),
            res = $('.res'),
            trQuantity = 0,
            quantityTr = 0,
            total = 0,
			
			// объект для сбора обязательных полей
			RequiredFields = {},
			errorText = '',
			
			// Объект, для отправки данных в шаблон
			DataTpl = {};
        
		// закрываем окно об ошибках
        cea();
		
        // Получаем количество строк в таблице
        quantityTr = Number(tbody.find('tr').length);// для сдешнего кода
        trQuantity = quantityTr;// для серверного кода
        
		/**
		 * Проходим в цикле по всем строкам в tbody
		 * на каждой итерации переписывая предыдущие значения
		 * и на выходе получим данные последней строки
		 * ==================================================
		 * В номерации строк делаем пересчет
		 * берем общее количество строк и на каждой итерации
		 * делаем декремент
		 */
        tbody.find('tr').each(function(){
            $(this).find('.sn span').html(quantityTr);
            $(this).find('.sn input').val(quantityTr);
			// бренд
			RequiredFields['serial_number'] = $(this).find('[name=serial_number]');
			// бренд
			RequiredFields['brand'] = $(this).find('[name=brand]');
			// артикул
			RequiredFields['vendor_code'] = $(this).find('[name=articul]');
			
			// поле товарная группа
			RequiredFields['product_group'] = $(this).find('.product_group.td3');
			// наименование номенклатуры
			RequiredFields['name_nomenclature'] = $(this).find('.name_nomenclature.td4');
			
			// размер производителя
			RequiredFields['code_manufacturer_size'] = $(this).find('[name=code_manufacturer_size]');
			// количество
			RequiredFields['quantity'] = $(this).find('[name=quantity]');
			// себестоимость
			RequiredFields['cost_of_goods'] = $(this).find('[name=cost_of_goods]');
			// розничная цена
			RequiredFields['retail_price'] = $(this).find('[name=retail_price]');
			
            quantityTr--;
            
            return false;
        });
		
		// Если в tbody вообще что то есть
		if(trQuantity > 0){
			// проверяем заполненность необходимых полей
			for(key in RequiredFields){
				if(key == 'product_group' || key == 'name_nomenclature') continue;
				if(RequiredFields[key].val() == '' || RequiredFields[key].val() == ''){
					errorText += '<b>'+RequiredFields[key].attr('title')+'</b><br>';
				}
			}
			
			/**
			 * Собираем данные последней в списке строки,
			 * которые нужно отправить в шаблон
			 */
			DataTpl['brand_code'] = RequiredFields['brand'].val();
			DataTpl['vendor_code'] = RequiredFields['vendor_code'].val();
			DataTpl['vendor_code_name'] = 
				RequiredFields['vendor_code'].find('option:selected').text();
			DataTpl['product_group_text'] = RequiredFields['product_group'].html();
			DataTpl['product_group_code'] = RequiredFields['product_group'].attr('code');
			DataTpl['gender'] = RequiredFields['name_nomenclature'].attr('gender');
			DataTpl['item_code'] = RequiredFields['name_nomenclature'].find('input').val();
			DataTpl['name_nomenclature_text'] = 
				RequiredFields['name_nomenclature'].find('span').html();
			DataTpl['quantity'] = RequiredFields['quantity'].val();
			DataTpl['cost_of_goods'] = RequiredFields['cost_of_goods'].val();
			DataTpl['retail_price'] = RequiredFields['retail_price'].val();
		}
		
		if(errorText != ''){
			errorText = 'В списке товаров, в последней строке (<b>'+
				RequiredFields['serial_number'].val()+
				'</b>) есть не заполненые обязательные поля:<br>'+
				errorText;
			popUp('.gr',errorText,'warning');
			return;
		}
		
		DataTpl['sn'] = (trQuantity+1);
		/**
		 * Флаг, по которому мы говорим PHP
		 * это добавление новой строки или последующих
		 */
		DataTpl['tr_quantity'] = (trQuantity);
		
        $.ajax({
            url:action,
            type:method,
            cashe:'false',
            dataType:'json',
            data:DataTpl,
            beforeSend:function(){
                load.fadeIn(100);
                $this.prop('disabled',true);
            }
        }).done(function(data){
//            res.html('done<br>'+JSON.stringify(data));
            // res.html(data);
            if(data.status == 200){
                tbody.prepend(data.tpl);
				
				/**
				 * Пересчитываем общие суммы в блоке "info"
				 * "Итого количество по документу"
				 * "Итого сумма себестоимости"
				 * "Итого сумма розничной стоимости"
				 */
				totalProductInfo();
				
            }else{

            }
            load.fadeOut(100);
            $this.prop('disabled',false);
        }).fail(function(data){
//            res.html('fail<br>'+JSON.stringify(data));
            load.fadeOut(100);
            $this.prop('disabled',false);
        });

    });

    /**
     * Страница "Поступление товара"
     * =============================
	 * Кнопка "Добавить товары"
	 */
    $('.gr .send').on('click',function(){
        var $this = $(this),
            action = $this.attr('action'),
            method = $this.attr('method'),
            load = $this.find('img'),
            live = 5000,
//			buttonAdd = $('button.add'),
            res = $('.res');
		
		cea();

        var Document = {};
        var DataGoods = {};
        DataGoods['goods'] = {};
        var DataGoodsOne = {};
        var io = 0;

        // Собираем данные вне таблицы
        Document['vendor_code'] = $('.gr').find('select[name=vendor_code]').val();
        Document['document_type'] = $('.gr').find('select[name=document_type]').val();
        Document['counterparty_document_comment'] = $('.gr').find('textarea[name=counterparty_document_comment]').val();

        $('.gr').find('.table tr').each(function(){
            $(this).find('input,textarea,select').each(function(){
                // пропускаем лишнее
                if(
                    $(this).attr('name') == '_csrf' ||
                    $(this).attr('name') == 'delete_on' ||
                    $(this).attr('name') == 'brand' ||
                    $(this).attr('name') == 'articul'
                ) return;
				
				// Себестоимость и разничная цена - меняем запятые на точки
				if(
					$(this).attr('name') == 'cost_of_goods' || 
					$(this).attr('name') == 'retail_price'
				){
					DataGoodsOne[$(this).attr('name')] = $(this).val().replace(',','.');
				}else DataGoodsOne[$(this).attr('name')] = $(this).val();

            });
            DataGoodsOne = {};
            DataGoods['goods'][io] = DataGoodsOne;
            io++;
        });

        DataGoods['document'] = Document;
		
        $.ajax({
            url:action,
            type:method,
            cashe:'false',
            dataType:'json',
            data: DataGoods,
            beforeSend:function(){
                load.fadeIn(100);
            }
        }).done(function(data){
            // res.html('done<br>'+JSON.stringify(data));
//            res.html(data);
            if(data.status == 200){
                LoadAlert(data.header,data.message,live,data.type_message);
                $this.prop('disabled',true);
//                buttonAdd.prop('disabled',true);
            }else{
                popUp('.gr',data.document_errors+data.errors,'danger');
            }
            load.fadeOut(100);
        }).fail(function(data){
            // res.html('fail<br>'+JSON.stringify(data));
            LoadAlert('Ошибка','Не известная ошибка<br>код: s~518',live,'error');
            load.fadeOut(100);
        });

    });

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
     * Поле ввода "Введите комментарий"
     * --------------------------------
     * Множество пробелов идущих подряд
     * заменяем на один пробел
     */
    $('.sales-reciept [name=counterparty_document_comment]').on('keyup',function(){
        if($.trim($(this).val()) == ''){
            // Если первым введен пробел, то опустошаем поле
            $(this).val('');
        }else{
            // Заменяем множество пробелов на один пробел
            $(this).val($(this).val().replace(/ +/g, ' '));
        }
    });

    /**
     * Страница "Товарный чек"
     * =======================
     * Действие после ввода штрихкода
     */
    $('.sales-reciept .barcode').on('input',function(){
        var $this = $(this),
			res = $('.sales-reciept .res'),
            form = $('.sales-reciept'),
            cardContent = form.find('.card-content'),
			btnSave = form.find('.save'),
			documentId = form.find('input[name=document_id]'),
            $thisVal = $this.val(),
            s1 = form.find('.s1'),
            s2 = form.find('.s2'),
            s3 = form.find('.s3'),
            s4 = form.find('.s4'),
            live = 9000,
            isBarcode = false,
            isBarcode1 = false,
            isBarcode3 = false,
            quantity1 = '',
            quantity3 = '',
			
			// Выбираем объекты подитогов всех разделов
			// Раздел 1: подитог "сумма за вычетом скидок"
			tfoot1 = form.find('.tfoot1'),
			// Раздел 3: подитог "скидка по подарочным сертификатам"
			tfoot2 = form.find('.tfoot2'),
			// Раздел 3: подитог "сумма продажи"
			tfoot3 = form.find('.tfoot3'),
			// Раздел 4: подитог "скидка по подарочным сертификатам"
			tfoot4 = form.find('.tfoot4');
		
			// Выбираем подитоги всех разделов
			// Раздел 1: подитог "сумма за вычетом скидок"
			pSzvs1 = form.find('.tfoot .p-szvs1 b'),
			// Раздел 3: подитог "скидка по подарочным сертификатам"
			pSps31 = form.find('.tfoot .p-sps31 b'),
			// Раздел 3: подитог "сумма продажи"
			pSp32 = form.find('.tfoot .p-sp32 b'),
			// Раздел 4: подитог "скидка по подарочным сертификатам"
			pSps4 = form.find('.tfoot .p-sps4 b');

            // берем из строки только первые 13 символов
            thirteen = $thisVal.substr(0,13),
			manualDiscountMessage = 'Если нужно отразить выбытие нескольких единиц товара с одинаковым штрихкодом и поставить ручную скидку, которая не одинакова для каждой из них или относится не ко всем из них, то такое выбытие следует отражать отдельными товарными чеками.';

		if(btnSave.prop('disabled') == true) resetPageSR('barcode');
		
        /**
         * Перед тем как делать какие либо манипуляции
         * проверяем разделы
         * "Продажа сертификата" и "Отоваривание сертификата",
         * есть ли введеный штрихкод в этих разделах
         */
        form.find('.td-barcode24').each(function(){
            if($(this).html() == thirteen){
                isBarcode = true;
                return false;
            }
        });

        /**
         * Если штрихкод уже есть на странице
         * останавливаем выполнение скрипта
         * и выводим предупержедние
         * и очищаем поле ввода штрихкода
         */
        if(isBarcode){
            var message = 'По данному штрихкоду уже есть информация на странице';
            LoadAlert('Внимание',message,live,'warning');
            $this.val('');
            return;
        }

        /**
         * Проверка раздела 1,
         * присутствует ли уже строка с таким штрихкодом
         * =============================================
         * во всей форме, находим все строки с классом .td-barcode1
         * и перебираем их
         */
        form.find('.td-barcode1').each(function(){
            if($(this).html() == $thisVal && documentId.val() == ''){
				isBarcode1 = true;
				/**
				 * Делаем относительную выборку элементов строки таблицы
				 */
				// количество
				quantity1 = $(this).parent().find('.quantity1');
				// Розничная цена
				retailPrice = $(this).parent().find('.retail-price1');
				// Сумма без скидок
				amountWithoutDiscounts = $(this).parent().find('.amount-without-discounts1');
				// Скидка по дисконтной карте
				discountOnADiscountCard = $(this).parent().find('.discount-on-a-discount-card1');
				// Автоматическая скидка
				automaticDiscount = $(this).parent().find('.automatic-discount1');
				// Ручная скидка
				manualDiscount = $(this).parent().find('.table-input1 input');
				manualDiscountVal = (manualDiscount.val() != '')?manualDiscount.val():0;
				// Сумма скидок
				sumOfDiscounts = $(this).parent().find('.sum-of-discounts1');
				// Сумма за вычетом скидок
				amountAfterDeductionOfDiscounts = $(this).parent().find('.amount-after-deduction-of-discounts1');
				// Скидка по подарочным сертификатам
				discountOnGiftCertificates = $(this).parent().find('.discount-on-gift-certificates1');
				// Итого скидки
				totalDiscounts = $(this).parent().find('.total-discounts1');
				// Сумма продажи
				salesAmount = $(this).parent().find('.sales-amount1');
                return false;
            }
        });//each
		
		/**
         * Проверка раздела 3,
         * присутствует ли уже строка с таким штрихкодом
         * и соответствует ли номер документа текущей строки 
         * номеру документа
         * введеному в поле "введите номер документа для возврата"
         */
        form.find('.td-barcode3').each(function(){
			if(
				$(this).html() == $thisVal &&
				$(this).parent().find('.document-id').html() == documentId.val()
			){
				isBarcode3 = true;
				/**
				 * Делаем относительную выборку элементов строки таблицы
				 */
				// общее количество
				total3 = $(this).parent().find('.quantity3 input');
				// количество
				quantity3 = $(this).parent().find('.quantity3 span');
				// Розничная цена
				retailPrice3 = $(this).parent().find('.retail-price3');
				// Сумма без скидок
				amountWithoutDiscounts3 = $(this).parent().find('.amount-without-discounts3');
				// Скидка по дисконтной карте
				discountOnADiscountCard3 = $(this).parent().find('.discount-on-a-discount-card3');
				// Автоматическая скидка
				automaticDiscount3 = $(this).parent().find('.automatic-discount3');
				// Ручная скидка
				manualDiscount3 = $(this).parent().find('.manual-discount3');
				// Сумма скидок
				sumOfDiscounts3 = $(this).parent().find('.sum-of-discounts3');
				// Сумма за вычетом скидок
				amountAfterDeductionOfDiscounts3 = $(this).parent().find('.amount-after-deduction-of-discounts3');
				// Скидка по подарочным сертификатам
				discountOnGiftCertificates3 = $(this).parent().find('.discount-on-gift-certificates3');
				/*
				 * Общая сумма "Скидка по подарочным сертификатам"
				 * по всему количеству
				 */
				commonDiscountOnGiftCertificates3 = discountOnGiftCertificates3.attr('data-common');
				// Итого скидки
				totalDiscounts3 = $(this).parent().find('.total-discounts3');
				// Сумма продажи
				salesAmount3 = $(this).parent().find('.sales-amount3');
                return false;
            }
		});
		
        /**
         * Если в разделе 1 уже есть строка со штрихкодом
         * который ввели, то в строке с таким штрихкодом
		 * увеличиваем количество на 1
		 * и пересчитываем все поля по количеству
         */
        if(isBarcode1){
			// увеличиваем количество на 1
			var inc = (Number(quantity1.html()) + 1);
			// вставляем новое значение в html
            quantity1.html(inc);
			
			/**
			 * Создаем новое значение текущей строки "сумма за вычетом скидок"
			 * ===============================================================
			 * (розничная цена * количество) - сумма скидок
			 */
			var aadods = (
				(Number(retailPrice.html()) * inc)
				- Number(sumOfDiscounts.html())
			);
			
			/**
			 * Вставляем новое значение
			 * в текущую строку "сумма за вычетом скидок"
			 */
			amountAfterDeductionOfDiscounts.html(aadods);
			
			// Пересчитываем новые подитоги
			recalculationPS1();
			
			/**
			 * Пересчитываем все строки раздела 1
			 * ==================================
			 * Пересчет всех строк начнется уже
			 * с новым подитогом "сумма за вычетом скидок"
			 */
			forS1();
			
			// опустошаем поле штрихкода
			$this.val('');
			
			// останавливаем выполнение основной функции
			return;
        }
		
		/**
         * Если в разделе 3 уже есть строка со штрихкодом
         * который ввели, то в строке с таким штрихкодом
		 * сравниваем количество с общим количеством
		 * если количество не больше и не равно общему количеству,
		 * то увеличиваем количество на 1
		 * пересчитываем все поля по количеству,
		 * иначе останавливаем скрипт и выводим предупреждение.
         */
		if(isBarcode3){
			
			if(Number(quantity3.html()) >= Number(total3.val())){
				var quantityError = 'Количество принимаемого на обмен/возврат товара с данным штрихкодом по указанному документу не может быть больше, чем количество выданного товара с данным штрихкодом по указанному документу!';
				popUp('.sales-reciept',quantityError,'warning');
			}else{
				// увеличиваем количество на 1
				var inc = (Number(quantity3.html()) + 1);
				// вставляем новое значение в html
				quantity3.html(inc);
				
				/**
				 * С новым количеством пересчитываем
				 * все значения текущей строки раздела 3
				 * =====================================
				 * передаем в функцию текущий штрихкод
				 * -----------------------------------
				 * пересчет всех строк раздела 1
				 * происходит внутри функции
				 * в самом конце
				 */
				recalculationS3(thirteen);

			}// if(isBarcode3) else
			
			// опустошаем поле штрихкода
			$this.val('');
			
			// останавливаем выполнение основной функции
			return;
        }

        /**
         * Если количество символов первышает 13
         * то вставляем в поле thirteen
		 * и останавливаем скрипт
         */
        if($thisVal.length > 13){
            $this.val(thirteen);
			return;
        }else if($thisVal.length < 13){

        }

        /**
         * Начинаем весь процесс только
         * если поле содержит ровно 13 символов
         */
        if($this.val().length == 13){

            var load = $('.sales-reciept .w-barcode img'),
                
//				fio = $('.sales-reciept input[name=name_buyers_document_comment]'),
//                phone = $('.sales-reciept input[name=buyer_phone_number]'),
//                email = $('.sales-reciept input[name=buyer_email]'),
				
                document1 = form.find('.document1 tbody'),
                empty1 = document1.find('tr.empty1'),
                empty2 = document1.find('tr.empty2'),
                empty3 = document1.find('tr.empty3'),
                empty4 = document1.find('tr.empty4'),
                action = form.attr('action'),
                method = form.attr('method'),
				
				// Номер дисконтной карты (штрихкод)
                spanBarcode = form.find('.span-barcode b'),
				/**
				 * Скрытое поле
				 * с номером дисконтной карты (штрихкод)
				 */
                inputBarcode = form.find('input[name=discount_card]'),
                spanFio = form.find('.span-fio b'),// ФИО держателя
                spanPhone = form.find('.span-phone b'),// Номер телефона
                // Накопление по карте за предыдущий год
                accumulationPreviousYear = form.find('.span-accumulation-previous-year b'),
                // Накопление по карте за текущий год
                accumulationCurrentYear = form.find('.span-accumulation-current-year b'),
                // Сумма покупок в текущем году
                amountPurchasesCurrentYear = form.find('.span-amount-purchases-current-year'),
				// Текущая скидка по карте
                currentDiscountCard = form.find('.span-current-discount-card b'),
				// Знак процента
                percentSign = form.find('.span-current-discount-card + span b'),
				// Возврат, обмен по карте
                returnExchangeByCard = form.find('#return_exchange_by_card b');
				// Сумма до порога
                amountToThreshold = form.find('#amount_to_threshold b');
			
            // отправка запроса
            $.ajax({
                url:action,
                type:method,
                cashe:'false',
                dataType:'json',
                data:{
                    document_id:documentId.val(),
                    barcode:thirteen,
					current_discount_card:currentDiscountCard.html(),
					p_szvs1:pSzvs1.html(),
					p_sps3:pSps31.html(),
					p_sp3:pSp32.html(),
					p_sps4:pSps4.html()
                },
                beforeSend:function(){
                    load.fadeIn(100);
                }
            }).done(function(data){
//                 res.html('Done<br>'+JSON.stringify(data));
//                 res.html(data.status);
                if(data.status == 200){
                    // если штрихкод - дисконтная карта
                    if(data.type == 'discount_cards'){
//                        fio.val(data.fio);
//                        phone.val(data.phone);
//                        email.val(data.email);
						
						// сначала все значения делаем пустыми
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
						amountToThreshold.html('');
						
						/**
						 * Затем заполняем новыми данными
						 * ==============================
						 * Это на случай, если перед этим,
						 * там были данные другой карты
						 */
                        // инфоблок (по дисконтной карте)
                        spanBarcode.html(data.barcode);
                        inputBarcode.val(data.barcode);
                        if(data.fio != '') spanFio.html(data.fio+' ');
						if(data.phone != '') spanPhone.html(data.phone+',');
                        accumulationPreviousYear.html(data.accumulation_previous_year);
                        accumulationCurrentYear.html(data.accumulation_current_year);
//						amountPurchasesCurrentYear.html(data.amount_purchases_current_year);
                        currentDiscountCard.html(data.discount);
                        percentSign.html('%');
                        returnExchangeByCard.html(data.return_exchange_by_card);
                        amountToThreshold.html(data.amount_to_threshold);
						
						/**
						 * Добавляем значение скидки по дисконтным картам
						 * во все строки раздела 1
						 * и делаем перерасчет всего раздела 1
						 */
						recalculationS1BySPK(data.discount);
						
						cardContent.fadeIn(100);

                    }
                    // если штрихкод - сертификат
                    if(data.type == 'certificates'){
                        if(data.table_row == '2'){
							/**
							 * Удаляем из html строку tr-td
							 * с содержимым "Пока пусто"
							 */
                            empty2.hide().remove();
                            s2.show();
							/**
							 * Вставляем в table
							 * уже готовый собранный шаблон строки tr
							 */
//                            document2.append(data.content);
                            tfoot2.before(data.content);
							
							/**
							 * При вставке данных с сервера в document4
							 * делаем пересчет итоговых полей
							 */
							recalculationPS2();
							
                        }
                        if(data.table_row == '4'){
							/**
							 * Удаляем из html строку tr-td
							 * с содержимым "Пока пусто"
							 */
                            empty4.hide().remove();
                            s4.show();
							/**
							 * Вставляем в table
							 * уже готовый собранный шаблон строки tr
							 */
//                            document4.append(data.content);
                            tfoot4.before(data.content);
							
							/**
							 * При вставке данных с сервера в document4
							 * делаем пересчет итоговых полей
							 * ========================================
							 * пересчет раздела 1
							 * происходит внутри функции
							 */
							recalculationPS4();
                        }
                    }
                    // если штрихкод - товар
                    if(data.type == 'product'){
                        if(data.table_row == '1'){
							/**
							 * Удаляем из html строку tr-td
							 * с содержимым "Пока пусто"
							 */
                            empty1.hide().remove();
                            s1.show();
							/**
							 * Вставляем в table
							 * уже готовый собранный шаблон строки tr
							 */
//                            document1.append(data.content);
                            tfoot1.before(data.content);
//							$(".it2").before("<li class='item'>Тест</li>");
							
							/**
							 * Циклом проходим по всем строкам раздела 1
							 * и расчитываем новые значения
							 * 		"Скидка по подарочным сертификатам"
							 * 		"Итого скидки"
							 * 		"Сумма продажи"
							 */
							forS1();
							
                        }
						if(data.table_row == '3'){
							/**
							 * Удаляем из html строку tr-td
							 * с содержимым "Пока пусто"
							 */
                            empty3.hide().remove();
                            s3.show();
							/**
							 * Вставляем в table
							 * уже готовый собранный шаблон строки tr
							 */
//                            document3.append(data.content);
                            tfoot3.before(data.content);
							
							/** 
							 * Пересчитываем подитоги раздела 3
							 * ================================
							 * пересчет раздела 1
							 * происходит внутри функции
							 */
							recalculationPS3();
                        }
                    }
					
					// опустошаем поле штрихкода
					$this.val('');
					
                }else{
                    LoadAlert(data.header,data.message,live,data.type_message);
                    // $this.val('');
                    // popUp('.sales-reciept',data.errors,'danger');
					// опустошаем поле штрих кода
					$this.val('');
                }
                load.fadeOut(100);
            }).fail(function(data){
                res.html('Fail<br>'+JSON.stringify(data));
				LoadAlert('Error','Не известная ошибка',live,'error');
                load.fadeOut(100);
				// опустошаем поле штрихкода
				$this.val('');
            });
			
        }// if($this.val().length == 13)
    });
	
	/**
     * Страница "Товарный чек"
     * =======================
     * Кнопка "Сохранить"
     */
	$('.sales-reciept .save').on('click', function(){
		var $this = $(this),
			form = $('.sales-reciept'),
			btnKkm = form.find('button.kkm'),
			// Выпадающий список "Введите способ оплаты"
			paymentMethodBankCard = form.find('input[name=payment_method_bank_card]'),
			// скрытое поле "сумма к оплате"
			sko = form.find('input[name=payment_amount]'),
			res = form.find('.res'),// для разработки
			action = $(this).attr('action'),// action
			method = $(this).attr('method'),// method
			load = form.find('.save img'),// анимация загрузки
			/**
			 * SalesReceipt - Главный объект, в него соберем всё необходимое
			 * и отправим его в PHP
			 */
			SalesReceipt = {},
			/**
			 * Section - Вспомогательный объект, задействован для сбора информации
			 * для главного объекта
			 */
        	Section = {},
			emptyInput = false,// флаг для проверки обязательных полей
			/**
			 * Для штрихкода, чтобы указать
			 * в строке с каким штрихкодом обнаружена ошибка
			 */
			emptyEmployeeCode = false,
			each = true,// для остановки внешнего цикла
			is_manual_discount = false,// флаг для проверки поля "Введите комментарий"
        	is = 1,// счетчик итераций i section
        	errorText = '',// для текста ошибок
        	inputName = '';// имя обязательного поля;
        
		SalesReceipt['info'] = {},
		SalesReceipt['section1'] = {},
		SalesReceipt['section2'] = {},
		SalesReceipt['section3'] = {},
		/**
		 * s3dc - массив для таблицы "Дисконтные карты"
		 * раздел 3, строки с дисконтными картами
		 * и новыми значениями "возврат, обмен по карте"
		 */
		SalesReceipt['s3dc'] = {},
		SalesReceipt['section4'] = {};
		
		
		
        /**
		 * Собираем статичные поля
		 * информация в начале страницы, поля над разделами
		 */
		form.find('.document-head').find('input,textarea,select').each(function(){
            
            // Пропускаем поле "Наличные покупателя"
            if($(this).attr('name') == 'cash_of_buyer') return;
            
			/**
			 * Проверка поля "Выберите способ оплаты" на пустоту,
			 * это поле помечено атрибутом "data-required"
			 * ==================================================
			 * значение "1" поставлено просто на угад
			 * лишь бы атрибут не был пуст
			 */
			if($(this).attr('data-required') == '1'){
				/**
				 * Если в поле "Выберите способ оплаты"
				 * ничего не выбрано и значение "сумма к оплате ..."
				 * имеет значение НЕ 0 (т.е. эта строка есть на экране)
				 * то делаем проверку поля
				 * "Выберите способ оплаты" на пустоту
				 * А если строка "сумма к оплате ..." имеет значение 0,
				 * (т.е. этой строки на экране нет)
				 * то поле "Выберите способ оплаты" будет не активным
				 * и значит оно проверятся не будет.
				 * Оно проверяется только при не нулевом значении
				 * поля "сумма к оплате ..."
				 */
				if($(this).val() == '0' && sko.val() != zero){
					inputName = $(this).attr('title');
					emptyInput = true;
					return false;
				}
			}
			SalesReceipt['info'][$(this).attr('name')] = $(this).val();
		});
		
		// Если одно из обязательных полей пусто
		if(emptyInput){
			errorText = 'Поле: "'+inputName+'" должно быть заполнено!';
			LoadAlert('Внимание',errorText,live,'warning');
			return;
		}
		
		/**
		 * Если есть штрихкод дисконтной карты в скрытом input
		 * (Если значения блока "дисконтная карта" документа заполнено)
		 * то собираем необходимую информацию блока "дисконтная карта"
		 */
		if(SalesReceipt['info']['discount_card'] != ''){
			SalesReceipt['discount_card'] = {},
			/**
			 *       накопление за текущий год,   скидка
			 * AD - (Accumulation current year), (Discount)
			 */
			AD = {},
			AD['ad'] = {};
			/**
			 * В блоке "Дисконтная карта",
			 * собираем все данные, там где есть атрибут data-dtcd
			 */
			form.find('[data-dtcd=dtcd]').each(function(){
				if($(this).find('b').html() == SalesReceipt['info']['discount_card']){
					AD['ad']['barcode'] = $(this).find('b').html();
				}else{
					AD['ad'][$(this).attr('id')] = Number($(this).find('b').html());
				}
			});
            
//            console.log(JSON.stringify(AD['ad']['current_discount_card']));
//            return;
			
			/**
			 * Считаем новое значение
			 * "Накопление по карте за текущий год"
			 */
			var accumulation_current_year = (
				AD['ad']['accumulation_current_year'] +
				AD['ad']['p-szvs1'] -
				AD['ad']['p-szvs3']
			);
			
			/**
			 * Считаем новое значение "скидка"
			 * ===============================
			 * Берем большее значение из
			 * (новое значение "накопление за текущий год")
			 * и
			 * ("накопление за предыдущий год")
			 */
			var max_accumulation = Math.max(
				accumulation_current_year, 
				Number(AD['ad']['accumulation_previous_year'])
			);
			
			/**
             * сумма для вычисления скидки
             * ===========================
             * max_accumulation ПЛЮС сумма возвратов, обменов
             */
			var preDiscount = (
				max_accumulation + 
				// число уже с минусом. Такое пришло из БД
				Number(AD['ad']['return_exchange_by_card'])
			);
            
			/**
			 * Если сумма больше либо равно минимальной сумме для скидки,
			 * то вычисляем скидку. Иначе скидки нет.
			 * Перебираем объект
			 * получаем соответствующую скидку
			 * =========================================================
			 * Если скидки нет, то в БД поле "скидка" не изменится.
			 */
			// Скидка 5%
			if(preDiscount >= dt.d5[1] && preDiscount < dt.d10[1]){
				AD['ad']['discount'] = dt.d5[0];
			}// Скидка 10%
			else if(preDiscount >= dt.d10[1] && preDiscount < dt.d15[1]){
				AD['ad']['discount'] = dt.d10[0];
			}// Скидка 15%
			else if(preDiscount >= dt.d15[1] && preDiscount < dt.d20[1]){
				AD['ad']['discount'] = dt.d15[0];
			}// Скидка 20%
			else if(preDiscount >= dt.d20[1]){
				AD['ad']['discount'] = dt.d20[0];
			}
            
            // Если есть хоть какая то скидка
            if(typeof AD['ad']['discount'] !== 'undefined'){
                /**
                 * Сделаем сравнение новой скидки с текущей,
                 * Если новая скидка больше текущей
                 * то обновляем, если нет, то пишем текущую
                 */
                if(AD['ad']['discount'] < AD['ad']['current_discount_card'])
                    AD['ad']['discount'] = AD['ad']['current_discount_card'];
            }
            
			/**
			 * Перепишем "Накопление по карте за текущий год"
			 * новым значением для записи в БД
			 */
			AD['ad']['accumulation_current_year'] = 
				Number(number_format(accumulation_current_year, co, fl, th));
			
			// добавляем данные в главный объект
			SalesReceipt['discount_card']['barcode'] = AD['ad']['barcode'];
			SalesReceipt['discount_card']['accumulation_current_year'] = AD['ad']['accumulation_current_year'];
			// если по считалась скидка, то добавляем её
			if(typeof AD['ad']['discount'] !== 'undefined'){
				SalesReceipt['discount_card']['discount'] = AD['ad']['discount'];
			}
			
		}
        
//        console.log(JSON.stringify(SalesReceipt));
//        return;
		
		/**
		 * Собираем данные раздела 1
		 */
		form.find('.table.document1 tr.section1').each(function(){

			// Если раздел пуст, то останавливаем цикл
//			if($(this).attr('class') == 'empty') return false;
//			if(typeof form.find('.empty1').attr('class') === 'undefined'){
//				console.log('empty1 is');
//				return false;
//			}
			$(this).find('td').each(function(){
				/**
				 * Из td выбираем "value" у полей,
				 * которые содержат в себе атрибут "name"
				 * Если td имеют в себе элементы формы (input,textarea,select)
				 * то нужно брать их "value", а не "html"
				 */
				if(typeof $(this).find('input,textarea,select').attr('name') !== 'undefined'){
					if($(this).find('input,textarea,select').attr('name') != 'checkbox'){
						//Если итерация по полю "Выберите работника"
						if($(this).find('input,textarea,select').attr('name') == 'employee_code'){
							// Проверка поля "Выберите работника" на пустоту
							if($(this).find('input,textarea,select').val() == ''){
								/**
								 * Получаем штрихкод для сообщения
								 * чтобы уточнить, в какой именно строке
								 * раздела 1 не заполнено поле "Выберите работника"
								 */
								emptyEmployeeCode = $(this).parent().find('#barcode').html();
								// для внешнего цикла
								each = false;
								return false;
							}
					   	}
                        
						//Если итерация по полю "ручная скидка"
						if($(this).find('input,textarea,select').attr('name') == 'manual_discount'){
                            /**
                             * Если есть заполненное поле
                             * то ставим флаг в true
                             */
                            if($(this).find('input,textarea,select').val() != '')
                                is_manual_discount = true;
                        }
                        
						Section[$(this).find('input,textarea,select').attr('name')] = 
							$(this).find('input,textarea,select').val();
					}// if
				}else{
					/**
					 * Иначе, если td не содержит в себе элемента
					 * с атрибутом "name" то выбираем "html"
					 * ==========================================
					 * Выбираем содержимое только тех td
					 * у которых есть "id"
					 */
					if(typeof $(this).attr('id') !== 'undefined'){
						Section[$(this).attr('id')] = $(this).html();
					}
				}// else
			});// each
			
			/**
			 * Если во внутреннем цикле произошла ошибка
			 * то останавливаем и внешний цикл
			 */
			if(!each) return false;
			
            SalesReceipt['section1'][is] = Section;
			Section = {};
			is++;
		});// each
		
		/**
		 * Если в одной из строк Раздела 1
		 * поле "Выберите работника" не заполнено
		 */
		if(emptyEmployeeCode != false){
			errorText = 'В разделе "Выбытие товара..." в строке со штрихкодом: '+
				emptyEmployeeCode+'<br>'+
				'Не заполнено поле "Выберите работника"';
			popUp('.sales-reciept',errorText,'warning');
			return;
		}
		
		/**
		 * Если в одной из строк Раздела 1
		 * поле "Ручная скидка" заполнено,
		 * в этом случае - проверим поле "Введите комментарий" на пустоту
		 * оно должно быть заполнено обязательно
		 */
		if(is_manual_discount){
            if(form.find('[name=counterparty_document_comment]').val() == ''){
                LoadAlert('Внимание','Поле комментарий должно быть заполнено',4000,'warning');
                return;
            }
		}
		
		// Ставим счетчик в начало
		is = 1;
		
		/**
		 * Собираем данные раздела 3
		 */
		form.find('.table.document1 tr.section3').each(function(){

			// Если раздел пуст, то останавливаем цикл
//			if($(this).attr('class') == 'empty') return false;
			$(this).find('td').each(function(){
				/**
				 * Из td выбираем "value" у полей,
				 * которые содержат в себе атрибут "name"
				 * Если td имеют в себе элементы формы (input,textarea,select)
				 * то нужно брать их "value", а не "html"
				 */
				if(typeof $(this).find('input,textarea,select').attr('name') !== 'undefined'){
					if($(this).find('input,textarea,select').attr('name') != 'checkbox'){
						Section[$(this).find('input,textarea,select').attr('name')] = 
							$(this).find('input,textarea,select').val();
					}// if
				}else{
					/**
					 * Иначе, если td не содержит в себе элемента
					 * с атрибутом "name" то выбираем "html"
					 * ==========================================
					 * Выбираем содержимое только тех td
					 * у которых есть "id"
					 */
					if(typeof $(this).attr('id') !== 'undefined'){
						/**
						 * Если итерация по полю "Количество"
						 * в этом td в html содержится span с нужным нам значением
						 * и input, который нам выбирать не нужно
						 * по этому указываем, что мы выбираем html из span
						 */
						if($(this).attr('id') == 'quantity'){
							Section[$(this).attr('id')] = $(this).find('span').html();
						}else{
							Section[$(this).attr('id')] = $(this).html();
						}
					}
				}// else
			});// each td
			
			/**
			 * Если во внутреннем цикле произошла ошибка
			 * то останавливаем и внешний цикл
			 * =========================================
			 * переменная "each" была использована для раздела 1
			 * тут она вроде как не нужна.
			 * Закоментил пока что.
			 */
//			if(!each) return false;
			
			/* 
			 * Проверка на существование штрихкода дисконтной карты
			 * rebc - поле в БД "возврат, обмен по карте"
			 */
			if($(this).find('[data-discount-card]').attr('data-discount-card') != ''){
				/**
				 * Если в объекте значение по ключу НЕ ПУСТО,
				 * то берем значение из объекта и
				 * от него онимаем текущую "сумма за вычетом скидок"
				 */
				if(typeof SalesReceipt['s3dc'][$(this).find('[data-discount-card]').attr('data-discount-card')] !== 'undefined'){
					SalesReceipt['s3dc'][$(this).find('[data-discount-card]').attr('data-discount-card')] = (
					   SalesReceipt['s3dc'][$(this).find('[data-discount-card]').attr('data-discount-card')] - 
					   Number($(this).find('.amount-after-deduction-of-discounts3').html())
					)
				}else{
					/**
					 * Если в объекте значение по ключу ПУСТО, то
					 * то берем текущее значение "возврат, обмен по карте" и
					 * от него онимаем текущую "сумма за вычетом скидок"
					 * и сохраняем в объект с новым ключом
					 */
					SalesReceipt['s3dc'][$(this).find('[data-discount-card]').attr('data-discount-card')] = (
					   Number($(this).find('[data-return-exchange-by-card]').attr('data-return-exchange-by-card')) - 
					   Number($(this).find('.amount-after-deduction-of-discounts3').html())
					)
				}
			}
			
            SalesReceipt['section3'][is] = Section;
			Section = {};
			is++;
		});// each
		
		// Ставим счетчик в начало
		is = 1;
		
		/**
		 * Собираем данные раздела 2
		 */
		form.find('.table.document1 tr.section2').each(function(){

			// Если раздел пуст, то останавливаем цикл
//			if($(this).attr('class') == 'empty') return false;
			$(this).find('td').each(function(){
				/**
				 * Выбираем содержимое только тех td
				 * у которых есть "id"
				 */
				if(typeof $(this).attr('id') !== 'undefined'){
					/**
					 * Если итерация по полю "Количество"
					 * в этом td в html содержится span с нужным нам значением
					 * и input, который нам выбирать не нужно
					 * по этому указываем, что мы выбираем html из span
					 */
					Section[$(this).attr('id')] = $(this).html();
				}
			});// each
			
			/**
			 * Если во внутреннем цикле произошла ошибка
			 * то останавливаем и внешний цикл
			 */
			if(!each) return false;
			
            SalesReceipt['section2'][is] = Section;
			Section = {};
			is++;
		});// each
		
		// Ставим счетчик в начало
		is = 1;
		
		/**
		 * Собираем данные раздела 4
		 */
		form.find('.table.document1 tr.section4').each(function(){

			// Если раздел пуст, то останавливаем цикл
			if($(this).attr('class') == 'empty') return false;
			$(this).find('td').each(function(){
				/**
				 * Выбираем содержимое только тех td
				 * у которых есть "id"
				 */
				if(typeof $(this).attr('id') !== 'undefined'){
					/**
					 * Если итерация по полю "Количество"
					 * в этом td в html содержится span с нужным нам значением
					 * и input, который нам выбирать не нужно
					 * по этому указываем, что мы выбираем html из span
					 */
					Section[$(this).attr('id')] = $(this).html();
				}
			});// each
			
			/**
			 * Если во внутреннем цикле произошла ошибка
			 * то останавливаем и внешний цикл
			 */
			if(!each) return false;
			
            SalesReceipt['section4'][is] = Section;
			Section = {};
			is++;
		});// each
		
		/**
		 * Если раздел 3 не пуст, то ищем в нем все 
		 */
		
		/**
		 * Проверка всех разделов на пустоту
		 */
		if(
			!isEmpty(SalesReceipt['section1']) &&
			!isEmpty(SalesReceipt['section2']) &&
			!isEmpty(SalesReceipt['section3'])
		  ){
			LoadAlert('Внимание','Документ пуст',live,'warning');
			return;
	   	}
        
//        console.log(JSON.stringify(SalesReceipt));
//        return;

		$.ajax({
			url: action,
			type: method,
			dataType: 'json',
			cashe: 'false',
			data: SalesReceipt,
			beforeSend:function(){
				load.fadeIn(100);
			}
		}).done(function(data){
//			res.html('Done<br>'+JSON.stringify(data));
//			res.html(data.status);
			if(data.status == 200){
                LoadAlert(data.header,data.message,live,data.type_message);
				/**
				 * После сохранения всех данных,
				 * кнопку "сохранить" делаем не активной,
				 * а кнопку ККМ делаем активной
				 */
                $this.prop('disabled',true);
				btnKkm.prop('disabled','');
            }else{
                popUp('.sales-reciept',data.errors,'danger');
            }
			load.fadeOut(100);
		}).fail(function(data){
//			res.html('Fail<br>'+JSON.stringify(data));
			LoadAlert('Error','Не известная ошибка',live,'error');
			load.fadeOut(100);
		});
		
	});
    
    /**
     * Страница "Товарный чек"
     * =======================
     * Выпадающий список "Выберите способ оплаты"
     * ------------------------------------
     * Если способ оплаты выбран "Банковская карта"
     * то скрываем опустошаем занчения строки "Сдача"
     * и скрываем эту строку
     */
    $('.sales-reciept [name=payment_method_bank_card]').on('change',function(){
        var $this = $(this),
            form = $('.sales-reciept'),
            cashBuyer = form.find('[name=cash_of_buyer]'),
            span2 = form.find('.rd .span2'),
            b2 = form.find('.rd .b2');
        
        if($this.val() == '2'){
            span2.hide();
            b2.html('').hide();
            cashBuyer.val('');
        }
        
        // Если выбрано "Наличные", то считаем сдачу
        if($this.val() == '1') deliveryBuyer();
    });
    
    /**
     * Страница "Товарный чек"
     * =======================
     * Поле для ввода "Наличные покупателя"
     * ------------------------------------
     * По изменению поля проверяем введенное число
     * как только введенное число при вводе стало больше
     * чем сумма к оплате - показываем сдачу
     * Введенное число минус сумма к оплате = сдача
     */
    $('.sales-reciept [name=cash_of_buyer]').on('input',function(){
        deliveryBuyer();
    });

    /**
     * ===================================================
     * END Страница "Товарный чек"
     */

    /**
     * Страница "Кассовый отчет"
     * ===================================================
     */
	
	/**
     * Страница "Кассовый отчет"
     * =========================
     * Поле для ввода даты
     * -------------------
     * По изменению поля сравниваем
     * текущую дату и дату введенную в поле
     */
	$('input[name=date_report]').on('change',function(){
		
		var $this = $(this),
			closeShift = $('button.close-shift'),
			openShift = $('button.open-shift');
		
		if($this.attr('data-current-date') == $this.val()){
			closeShift.prop('disabled','');
			openShift.prop('disabled','');
		}else{
			closeShift.prop('disabled',true);
			openShift.prop('disabled',true);
		}
		
	});

    /**
     * Страница "Кассовый отчет"
     * =========================
     * Кнопка "Вывести отчет"
     */
	$('.cr .get-report').on('click',function(){
		var $this = $(this),
			action = $this.attr('action'),
			method = $this.attr('method'),
			date_report = $('.cr .date-report'),
			load = $this.find('img'),
			table1 = $('.cr .table1'),
			tbody = $('.cr .table2 tbody'),
			empty = tbody.find('.empty'),
			res = $('.cr .res'),
			
			son = table1.find('.son b'),// сумма оплата наличными
			svzn = table1.find('.svzn b'),// сумма возврата наличными
			sobk = table1.find('.sobk b'),// сумма оплата банковскими картами
			svnbk = table1.find('.svnbk b'),// сумма возврата на банковские карты
			svrn = table1.find('.svrn b'),// сумма выручка наличными
			svbk = table1.find('.svbk b'),// сумма выручка банковскими картами
			sos = table1.find('.sos b'),// сумма отоварено сертификатов
			str_ivzd = table1.find('.ivzd span'),// строка "дд.мм.гггг"
			ivzd = table1.find('.ivzd b'),// итого выручка за дд.мм.гггг
			
			apic = 0,// счетчик сумма оплата наличными
			cra = 0,// счетчик сумма возврата наличными
			apbbc = 0,// счетчик сумма оплата банковскими картами
			aortbc = 0;// счетчик сумма возврата на банковские карты
		
		tbody.html('');
		str_ivzd.html('дд.мм.гггг');
		
		// перед выводом отчета, обнуляем все данные .table1
		son.html(zero);// сумма оплата наличными
		svzn.html(zero);// сумма возврата наличными
		sobk.html(zero);// сумма оплата банковскими картами
		svnbk.html(zero);// сумма возврата на банковские карты

		svrn.html(zero);// сумма выручка наличными
		svbk.html(zero);// сумма выручка банковскими картами
		sos.html(zero);// сумма отоварено сертификатов
        
        if(date_report.val() != ''){
            // меняем (дд.мм.гггг) на дату (YYYY-MM-DD)
            str_ivzd.html(date_report.val());
        }
        
		ivzd.html(zero);// итого выручка за дд.мм.гггг
		
		// проверка поля на пустоту
		if(date_report.val() == ''){
			LoadAlert('Внимание','Вы не указали дату',live,'warning');
			return;
		}
		
		$.ajax({
			url: action,
			type: method,
			dataType: 'json',
			cashe: 'false',
			data: {date_report:date_report.val()},
			beforeSend:function(){
				load.fadeIn(100);
			}
		}).done(function(data){
//			res.html('Done<br>'+JSON.stringify(data));
			if(data.status == 200){
                LoadAlert(data.header,data.message,live,data.type_message);
				empty.hide().remove();
				tbody.append(data.content);
				
				// собираем и суммируем нужные поля
				tbody.find('tr').each(function(){
					// сумма оплата наличными
					apic += Number($(this).find('.amount-payment-in-cash').html());
					// сумма возврата наличными
					cra += Number($(this).find('.cash-repayment-amount').html());
					// сумма оплата банковскими картами
					apbbc += Number($(this).find('.amount-payment-by-bank-cards').html());
					// сумма возврата на банковские карты
					aortbc += Number($(this).find('.amount-of-refund-to-bank-cards').html());
				});
				
				v_svrn = (apic - cra);// сумма выручка наличными
				v_svbk = (apbbc - aortbc);// сумма выручка банковскими картами
				v_ivzd = (v_svrn + v_svbk);// итого выручка за дд.мм.гггг
				
//				v_svrn = (v_svrn > 0)?Number(number_format(v_svrn, co, fl, th)):'0.00';
//				v_svbk = (v_svbk > 0)?Number(number_format(v_svbk, co, fl, th)):'0.00';
//				v_ivzd = (v_ivzd > 0)?Number(number_format(v_ivzd, co, fl, th)):'0.00';
				v_svrn = Number(number_format(v_svrn, co, fl, th));
				v_svbk = Number(number_format(v_svbk, co, fl, th));
				v_ivzd = Number(number_format(v_ivzd, co, fl, th));
				
				// счетчик сумма оплата наличными
//				apic = (apic > 0)?Number(number_format(apic, co, fl, th)):'0.00';
				apic = Number(number_format(apic, co, fl, th));
				// счетчик сумма возврата наличными
//				cra = (cra > 0)?Number(number_format(cra, co, fl, th)):'0.00';
				cra = Number(number_format(cra, co, fl, th));
				// счетчик сумма оплата банковскими картами
//				apbbc = (apbbc > 0)?Number(number_format(apbbc, co, fl, th)):'0.00';
				apbbc = Number(number_format(apbbc, co, fl, th));
				// счетчик сумма возврата на банковские карты
//				aortbc = (aortbc > 0)?Number(number_format(aortbc, co, fl, th)):'0.00';
				aortbc = Number(number_format(aortbc, co, fl, th));
				
				// вставляем данные в HTML
				son.html(apic);// сумма оплата наличными
				svzn.html(cra);// сумма возврата наличными
				sobk.html(apbbc);// сумма оплата банковскими картами
				svnbk.html(aortbc);// сумма возврата на банковские карты
				
				svrn.html(v_svrn);// сумма выручка наличными
				svbk.html(v_svbk);// сумма выручка банковскими картами
				if(data.sum_of_denominations){
					// сумма отоварено сертификатов
					sos.html(data.sum_of_denominations);
				}
				ivzd.html(v_ivzd);// итого выручка за дд.мм.гггг
				
            }else{
                popUp('.cr',data.errors,'warning');
            }
			load.fadeOut(100);
		}).fail(function(data){
//			res.html('Fail<br>'+JSON.stringify(data));
			LoadAlert('Error','Не известная ошибка',live,'error');
			load.fadeOut(100);
		});
	});
	
	/**
     * ===================================================
     * END Страница "Кассовый отчет"
     */

    /**
     * Страница "Работники"
     * ===================================================
     */
	
	/**
     * Страница "Работники"
     * ====================
     * Выпадающий список работников
     */
	$('[name=workers]').on('change',function(){
		var $this = $(this),
			url = $this.attr('data-url'),
			method = $this.attr('method'),
			form = $('.workers'),
			res = $('.res'),
			load = form.find('.w-list img'),
			fio = form.find('[name=fio]'),
			username = form.find('[name=username]'),
			password = form.find('[name=password]'),
			active = form.find('[name=active]'),
			role = form.find('[name=role]'),
			button = form.find('.go-change button');
		/**
		 * Если выбрано значение "Выберите работника"
		 * то сбрасываем всю форму
		 */
		if($this.val() == ''){
			form.find('input,select,button').each(function(){
				if($(this).attr('name') == 'workers') return;
				$(this).val('').prop('disabled',true);
			});
			$this.attr('data-type','edit');
			button.find('span').html('Внести изменения');
		}else if($this.val() == 'new'){
			form.find('input,select,button').each(function(){
				if($(this).attr('name') == 'workers') return;
				$(this).val('').prop('disabled','');
			});
			// Включаем поле "Введите пароль"
			password.prop('disabled','');
			
			/**
			 * Если выбрано значение "Добавить пользователя"
			 * то меняем аттрибут кнопки на "new"
			 * что значит - будет добавление нового пользователя
			 */
			$this.attr('data-type','new');
			button.find('span').html('Добавить');
		}else{
			form.find('input,select,button').each(function(){
				$(this).prop('disabled','');
			});
			
			/**
			 * Выключаем поле "введите пароль"
			 * т.к. в базе есть что то, где укзаываются права
			 * на изменение поля "password"
			 */
			password.prop('disabled',true);
			
			/**
			 * Если выбран какой либо пользователь
			 * то меняем аттрибут кнопки на "edit"
			 * что значит - будет редактирование пользователя
			 */
			$this.attr('data-type','edit');
			button.find('span').html('Внести изменения');
			
			$.ajax({
				url:url,
				type:method,
				dataType:'json',
				cashe:'false',
				data:{id:$this.val()},
				beforeSend:function(){
					load.fadeIn(100);
				}
			}).done(function(data){
//				res.html('Done<br>'+JSON.stringify(data.username));
				if(data.status == 200){
					fio.val(data.fio);
					username.val(data.username);
					active.val(data.active);
					role.val(data.role);
					LoadAlert(data.header,data.message,5000);
				}else{
					LoadAlert(data.header,data.message,5000,data.type);
				}
				load.fadeOut(100);
			}).fail(function(data){
//				res.html('Fail<br>'+JSON.stringify(data));
				LoadAlert('Ошибка','Не известная ошибка PHP',5000,'error');
				load.fadeOut(100);
			});
			
		}
		
	});
	
	/**
     * Страница "Работники"
     * ====================
     * Кнопка "Добавить/Внести изменения" 
     */
	$('.workers .go-change button').on('click',function(){
		
		var $this = $(this),
			load = $this.find('img'),
			form = $('.workers'),
			listWorkers = form.find('[name=workers]'),
			res = $('.res'),
			User = {},
			required = false;
		
		User['type'] = $this.attr('data-type');
		User['email'] = unixTime()+'@mail.ru';
		
		// закрываем окно об ошибках
        cea();
		
		form.find('input,select').each(function(){
			if($(this).attr('name') == 'workers'){
				// Если значение пусто
				/**
				 * Значение пусто сюда не попадет
				 * потому что когда тут пусто
				 * кнопка не активна
				 */
//				if($(this).val() == '') required = true;
				User['id'] = $(this).val();
				User['type'] = $(this).attr('data-type');
			}else{
				/**
				 * Если РЕДАКТИРОВАНИЕ данных
				 * то мы не проверяем поле пароль на обязательность
				 */
				if(User['type'] == 'edit'){
					if($(this).attr('name') == 'password'){
						User[$(this).attr('name')] = $(this).val();
					}else{
						// Если значение пусто
						if($(this).val() == '') required = true;
						User[$(this).attr('name')] = $(this).val();
					}
				}else{
					/**
					 * Если ДОБАВЛЕНИЕ нового пользователя
					 * то все поля обязательны для заполнения
					 */
					if($(this).val() == '') required = true;
					User[$(this).attr('name')] = $(this).val();
				}
			}
			
			
		});
		
		/**
		 * Если какое то поле пусто
		 * то останавливаем скрипт с предупреждением
		 */
		if(required){
			LoadAlert('Внимание','Необходимо заполнить все поля',5000,'warning');
			return;
		}
		
//		console.log(JSON.stringify(User));
//		return;
		$.ajax({
			url:$this.attr('data-url'),
			type:$this.attr('method'),
			dataType:'json',
			cashe:'false',
			data:User,
			beforeSend:function(){
				load.fadeIn(100);
			}
		}).done(function(data){
//			res.html('Done<br>'+JSON.stringify(data));
			if(data.status == 200){
				listWorkers.html(data.list_options);
				LoadAlert(data.header,data.message,5000,data.type);
			}else popUp('.workers',data.message,'warning');
			
//			popUp('.workers','Внимание, какое то предупреждение','warning');
			load.fadeOut(100);
		}).fail(function(data){
			res.html('Fail<br>'+JSON.stringify(data));
			LoadAlert('Ошибка','Не известная ошибка PHP',5000,'error');
			load.fadeOut(100);
		});

	});
	
	/**
     * ===================================================
     * END Страница "Работники"/signup
     */
	
	

    /**
     * Страница "Оприходование товара"
     * ===================================================
     */
	
	/**
	 * Страница "Оприходование товара"
	 * ===============================
	 * Получаем товар по штрихкоду
	 */
	$('.cngs input[name=barcode]').on('input',function(){
		var $this = $(this),
			url = $this.attr('data-url'),
			method = $this.attr('method'),
			form = $('.cngs'),
			button = form.find('.debit-product'),
			res = form.find('.res'),
			load = form.find('.w-barcode img'),
			// берем из строки только первые 13 символов
            thirteen = $this.val().substr(0,13);
		
		/**
		 * Если кнопка "Оприходовать" не активна
		 */
		if(button.prop('disabled') == true) resetPageCG();
		
		/**
         * Если количество символов первышает 13
         * то вставляем в поле thirteen
		 * и останавливаем скрипт
         */
        if($this.val().length > 13){
            $this.val(thirteen);
			return;
        }else if($this.val().length < 13) return;
				
		/**
		 * Выбираем из таблицы строку со штрихкодом
		 * который был введен в поле
		 */
		var isRow = form.find('.table2 tbody td:contains("'+thirteen+'")').parent();

		/**
		 * Если в таблице уже есть строка со штрихкодом
		 * то просто прибавляем количество
		 * и останавливаем скрипт
		 */
		if(typeof isRow.html() !== 'undefined'){
			isRow.find('.quantity').html(
				(Number(isRow.find('.quantity').html()) + 1)
			);
			$this.val('');
			// Делаем пересчет итоговых значений
			recalculationCG();
			return;
		}
		
//		console.log(thirteen);return;
		
		/**
		 * Делаем Ajax запрос только в том случае
		 * если в поле ввода штрихкода
		 * ровно 13 символов
		 */
		if($this.val().length == 13){
			
			$.ajax({
				url:url,
				type:method,
				dataType:'json',
				cashe:'false',
				data:{
                    barcode:thirteen,
                    page:'capitalizatoin-goods'
                },
				beforeSend:function(){
					load.fadeIn(100);
				}
			}).done(function(data){

//				res.html('Done<br>'+JSON.stringify(data));

				if(data.status == 200){
					
					// Удаляем строку "Пока пусто"
					form.find('.table2 tbody tr.empty').remove();
					// Вставляем новую строку в table
					form.find('.table2 tbody').append(data.row);
					// Делаем пересчет итоговых значений
					recalculationCG();
					
					LoadAlert(data.header,data.message,2000);
					
				}else{
//					popUp('.cngs',data.error,'warning');
					LoadAlert(data.header,data.message,2000,data.type);
				}
				load.fadeOut(100);
				$this.val('');
			}).fail(function(data){
				res.html('Fail<br>'+JSON.stringify(data));
				load.fadeOut(100);
				$this.val('');
			});
		}
	});
    
	/**
	 * Страница "Оприходование товара"
	 * ===============================
	 * Кнопка "Оприходование"
	 */
	$('.cngs button.debit-product').on('click',function(){
		var $this = $(this),
			url = $this.attr('data-url'),
			method = $this.attr('method'),
			form = $('.cngs'),
			res = form.find('.res'),
			load = $this.find('img'),
			DataDebit = {},
			Row = {},
			i = 0;
        
        // Проверка таблицы на пустоту
        if(typeof form.find('.empty').attr('class') !== 'undefined'){
            LoadAlert('Внимание','Нет ни одного товара',5000,'warning');
            return;
        }
		
		DataDebit['info'] = {};
		DataDebit['table'] = {};
		
		// Получаем поле "Введите комментарий"
		DataDebit['info']['counterparty_document_comment'] = form.find('[name=description]').val();
		// Получаем тип документа
		DataDebit['info']['document_type'] = form.find('[name=document_type]').val();
		
		// Перебираем строки .table2
		form.find('.table2 tr').each(function(){
			Row[$(this).find('.barcode').attr('id')] = $(this).find('.barcode').html();
			Row[$(this).find('.quantity').attr('id')] = $(this).find('.quantity').html();
			DataDebit['table'][i] = Row;
			Row = {};
			i++;
		});
		
//		console.log(JSON.stringify(DataDebit));
//		return;
		
		$.ajax({
			url:url,
			type:method,
			dataType:'json',
			cashe:'false',
			data:DataDebit,
			beforeSend:function(){
				load.fadeIn(100);
			}
		}).done(function(data){

				res.html('Done<br>'+JSON.stringify(data));

			if(data.status == 200){

				LoadAlert(data.header,data.message,2000);

			}else{
				popUp('.cngs',data.errors,'warning');
//				LoadAlert(data.header,data.message,2000,data.type);
			}
			load.fadeOut(100);
//			$this.prop('disabled', true);
		}).fail(function(data){
			res.html('Fail<br>'+JSON.stringify(data));
			load.fadeOut(100);
			$this.val('');
		});
		
	});
	
	/**
     * ===================================================
     * END Страница "Оприходование товара"
     */
	
	

    /**
     * Страница "Оприходование сертификата"
     * ===================================================
     */
	
	/**
	 * Страница "Оприходование сертификата"
	 * ===============================
	 * Получаем товар по штрихкоду
	 */
	$('.cnce input[name=barcode]').on('input',function(){
		var $this = $(this),
			url = $this.attr('data-url'),
			method = $this.attr('method'),
			form = $('.cnce'),
			button = form.find('.debit-certificate'),
			res = form.find('.res'),
			load = form.find('.w-barcode img'),
			// берем из строки только первые 13 символов
            thirteen = $this.val().substr(0,13);
		
		/**
		 * Если кнопка "Оприходовать" не активна
		 */
		if(button.prop('disabled') == true) resetPageCC();
		
		/**
         * Если количество символов первышает 13
         * то вставляем в поле thirteen
		 * и останавливаем скрипт
         */
        if($this.val().length > 13){
            $this.val(thirteen);
			return;
        }else if($this.val().length < 13) return;
				
		/**
		 * Выбираем из таблицы строку со штрихкодом
		 * который был введен в поле
		 */
		var isRow = form.find('.table2 tbody td:contains("'+thirteen+'")').parent();

		/**
		 * Если в таблице уже есть строка со штрихкодом
		 * то просто прибавляем количество
		 * и останавливаем скрипт
		 */
		if(typeof isRow.html() !== 'undefined'){
			LoadAlert('Внимание','Строка с таким штрихкодом<br>уже есть',5000,'warning');
            $this.val('');
			return;
		}
		
//		console.log(thirteen);return;
		
		/**
		 * Делаем Ajax запрос только в том случае
		 * если в поле ввода штрихкода
		 * ровно 13 символов
		 */
		if($this.val().length == 13){
			
			$.ajax({
				url:url,
				type:method,
				dataType:'json',
				cashe:'false',
				data:{barcode:thirteen},
				beforeSend:function(){
					load.fadeIn(100);
				}
			}).done(function(data){

//				res.html('Done<br>'+JSON.stringify(data));

				if(data.status == 200){
					
					// Удаляем строку "Пока пусто"
					form.find('.table2 tbody tr.empty').remove();
					// Вставляем новую строку в table
					form.find('.table2 tbody').append(data.row);
					// Делаем пересчет итоговых значений
					recalculationCC();
					
					LoadAlert(data.header,data.message,2000);
					
				}else{
//					popUp('.cngs',data.error,'warning');
					LoadAlert(data.header,data.message,2000,data.type);
				}
				load.fadeOut(100);
				$this.val('');
			}).fail(function(data){
				res.html('Fail<br>'+JSON.stringify(data));
				load.fadeOut(100);
				$this.val('');
			});
		}
	});
	
	/**
	 * Страница "Оприходование сертификата"
	 * ====================================
	 * Кнопка "Оприходование"
	 */
	$('.cnce button.debit-certificate').on('click',function(){
		var $this = $(this),
			url = $this.attr('data-url'),
			method = $this.attr('method'),
			form = $('.cnce'),
			res = form.find('.res'),
			load = $this.find('img'),
			button = $this.find('img'),
			DataDebit = {},
			i = 0;
        DataDebit['barcodes'] = {};
        
        // Проверка таблицы на пустоту
        if(typeof form.find('.empty').attr('class') !== 'undefined'){
            LoadAlert('Внимание','Нет ни одного сертификата',5000,'warning');
            return;
        }
		
		// Перебираем строки .table2 tbody
		form.find('.table2 tbody tr').each(function(){
			DataDebit['barcodes'][i] = $(this).find('.barcode .text').html();
            i++;
		}); 
		
//		console.log(JSON.stringify(DataDebit));
//		return;
		
		$.ajax({
			url:url,
			type:method,
			dataType:'json',
			cashe:'false',
			data:DataDebit,
			beforeSend:function(){
				load.fadeIn(100);
			}
		}).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));
            LoadAlert(data.header,data.message,2000,data.type);
			load.fadeOut(100);
			$this.prop('disabled', true);
		}).fail(function(data){
			res.html('Fail<br>'+JSON.stringify(data));
			load.fadeOut(100);
			$this.val('');
		});
		
	});
	
	/**
     * ===================================================
     * END Страница "Оприходование сертификата"
     */
	
	

    /**
     * Страница "Выгрузка этикетки"
     * ===================================================
     */
	
	/**
	 * Страница "Выгрузка этикетки"
	 * ============================
	 * Получаем товар по штрихкоду
	 */
	$('.ugls input[name=barcode]').on('input',function(){
		var $this = $(this),
			url = $this.attr('data-url'),
			method = $this.attr('method'),
			form = $('.ugls'),
			res = form.find('.res'),
			load = $this.prev(),
            numberLines = form.find('.number_lines'),
			// берем из строки только первые 13 символов
            thirteen = $this.val().substr(0,13);
        
        // счетчик строк в таблице table
        var ii = 0;
		
		/**
         * Если количество символов первышает 13
         * то вставляем в поле thirteen
		 * и останавливаем скрипт
         */
        if($this.val().length > 13){
            $this.val(thirteen);
			return;
        }else if($this.val().length < 13) return;
		
//		console.log(thirteen);return; 
		
		/**
		 * Делаем Ajax запрос только в том случае
		 * если в поле ввода штрихкода
		 * ровно 13 символов
		 */
		if($this.val().length == 13){
			
			$.ajax({
				url:url,
				type:method,
				dataType:'json',
				cashe:'false',
				data:{
                    barcode:thirteen,
                    page:'uploading-labels'
                },
				beforeSend:function(){
					load.fadeIn(100);
				}
			}).done(function(data){

//				res.html('Done<br>'+JSON.stringify(data));

				if(data.status == 200){
					
					// Удаляем строку "Пока пусто"
					form.find('.table2 tbody tr.empty').remove();
					// Вставляем новую строку в table
					form.find('.table2 tbody').append(data.row);
					// Делаем пересчет итоговых значений
                    // Если вдруго понадобится сделать какие то пересчеты
                    // напишм функцию и запустим её здесь
//					recalculationCC();
                    
                    /**
                     * Пересчитываем количество строк в таблице
                     */
                    form.find('.table2 tbody tr').each(function(){ ii++; });
                    // Вставляем в строку шапки "Количество"
                    numberLines.find('span').html(ii);
					
                    /**
                     * Сортируем строки таблицы
                     * по наименованию номенклатуры
                     * и по размеру
                     */
                    sortRowsUnloadingLabels();
                    
					LoadAlert(data.header,data.message,2000);
					
				}else{
//					popUp('.cngs',data.error,'warning');
					LoadAlert(data.header,data.message,2000,data.type);
				}
				load.fadeOut(100);
				$this.val('');
			}).fail(function(data){
				res.html('Fail<br>'+JSON.stringify(data));
				load.fadeOut(100);
				$this.val('');
			});
		}
	});
	
	/**
	 * Страница "Выгрузка этикетки"
	 * ====================================
	 * Кнопка "Добавить"
	 */
	$('.ugls button.add').on('click',function(){
		var $this = $(this),
			url = $this.attr('data-url'),
			method = $this.attr('method'),
			form = $('.ugls'),
			res = form.find('.res'),
			load = $this.find('img'),
            numberLines = form.find('.number_lines'),
            documentId = form.find('[name=document_id]');
        
        // счетчик строк в таблице table
        var ii = 0;
        
        // Проверка таблицы на пустоту
        if(documentId.val() == ''){
            LoadAlert('Внимание','Не указан номер документа',5000,'warning');
            return;
        }
		
//		console.log(JSON.stringify(DataDebit));
//		return;
		
		$.ajax({
			url:url,
			type:method,
			dataType:'json',
			cashe:'false',
			data:{document_id:documentId.val()},
			beforeSend:function(){
				load.fadeIn(100);
			}
		}).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));
            if(data.status == 200){
					
					// Удаляем строку "Пока пусто"
					form.find('.table2 tbody tr.empty').remove();
					// Вставляем новую строку в table
					form.find('.table2 tbody').append(data.row);
					// Делаем пересчет итоговых значений
                    // Если вдруго понадобится сделать какие то пересчеты
                    // напишм функцию и запустим её здесь
//					recalculationCC();
                    
                    /**
                     * Пересчитываем количество строк в таблице
                     */
                    form.find('.table2 tbody tr').each(function(){ ii++; });
                    // Вставляем в строку шапки "Количество"
                    numberLines.find('span').html(ii);
					
                    /**
                     * Сортируем строки таблицы
                     * по наименованию номенклатуры
                     * и по размеру
                     */
                    sortRowsUnloadingLabels();
                
					LoadAlert(data.header,data.message,2000);
					
				}else{
//					popUp('.cngs',data.error,'warning');
					LoadAlert(data.header,data.message,2000,data.type);
				}
				load.fadeOut(100);
			load.fadeOut(100);
//			$this.prop('disabled', true);
		}).fail(function(data){
//			res.html('Fail<br>'+JSON.stringify(data));
			load.fadeOut(100);
//			$this.val('');
		});
		
	});
	
	/**
	 * Страница "Выгрузка этикетки"
	 * ====================================
	 * Кнопка "Реестр"
	 */
	$('.ugls button.strings-list-barcodes').on('click',function(){
		var $this = $(this),
			url = $this.attr('data-url'),
			method = $this.attr('method'),
            empty = false,
			form = $('.ugls'),
			res = form.find('.res'),
			load = $this.find('img'),
            tbody = form.find('table.table2 tbody'),
            wmReestr = form.find('.wm-reestr'),
            listBarcodes = wmReestr.find('.list-barcodes'),
            regDownloadExportExcel = form.find('.registry-download-export-excel'),
            labDownloadExportExcel = form.find('.labels-download-export-excel'),
            i=1;
        Data = {};
        
        // Скрываем все кнопки скачивания файлов Excel
        regDownloadExportExcel.fadeOut(150);
        labDownloadExportExcel.fadeOut(150);
        
        // Показываем нужную кнопку
        $('.print-reestr').show();
        
        /**
         * Перебираем строки таблицы
         * и собираем нужную информацию
         */
        tbody.find('tr').each(function(){
            // Если таблица пуста
            if($(this).attr('class') == 'empty'){
                empty = true;
                return false;
            }
            Data[i] = {};
            Data[i]['description'] = $(this).find('.content').text();
            /**
             * Размер, где строка no_size - "0 без размера"
             * пропускаем
             */
            if($(this).find('.manufacturer-size').html() != no_size)
                Data[i]['manufacturer_size'] = $(this).find('.manufacturer-size').html();
            else Data[i]['manufacturer_size'] = '';
            
            Data[i]['retail_price'] = $(this).find('.retail-price').html();
            Data[i]['barcode'] = $(this).find('.barcode').html();
            i++;
        });
        
        // Проверка таблицы на пустоту
        if(empty){
            LoadAlert('Внимание','Таблица пуста',5000,'warning');
            return;
        }
        
//		console.log(JSON.stringify(Data));
//		return;
		
		$.ajax({
			url:url,
			type:method,
			dataType:'json',
			cashe:'false',
			data:Data,
			beforeSend:function(){
				load.fadeIn(100);
			}
		}).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));
            if(data.status == 200){
                /**
                 * Вставляем в href
                 * ссылку на созданный Excel файл
                 */
                regDownloadExportExcel.fadeIn(200);
            }else{
                LoadAlert('Внимание','Ошибка контроллера (не 200)',2000,'error');
            }
			load.fadeOut(100);
		}).fail(function(data){
//			res.html('Fail<br>'+JSON.stringify(data));
			load.fadeOut(100);
            LoadAlert('Внимание','Ошибка PHP',3000,'error');
		});
		
	});
	
	/**
	 * Страница "Выгрузка этикетки"
	 * ====================================
	 * Кнопка "Этикетки"
	 */
	$('.ugls button.graphic-list-barcodes').on('click',function(){
		var $this = $(this),
			url = $this.attr('data-url'),
			method = $this.attr('method'),
            empty = false,
			form = $('.ugls'),
			res = form.find('.res'),
			load = $this.find('img'),
            tbody = form.find('table.table2 tbody'),
            wmReestr = form.find('.wm-reestr'),
            listBarcodes = wmReestr.find('.list-barcodes'),
            regDownloadExportExcel = form.find('.registry-download-export-excel'),
            labDownloadExportExcel = form.find('.labels-download-export-excel'),
            manufacturerSize = '',
            i=0;
        Data = {};
        
        // Скрываем все кнопки скачивания файлов Excel
        regDownloadExportExcel.fadeOut(150);
        labDownloadExportExcel.fadeOut(150);
        
        // Показываем нужную кнопку
        $('.print-labels').show();
        
        /**
         * Перебираем строки таблицы
         * и собираем нужную информацию
         */
        tbody.find('tr').each(function(){
            // Если таблица пуста
            if($(this).attr('class') == 'empty'){
                empty = true;
                return false;
            }
            if($(this).find('.manufacturer-size').html() != no_size){
                manufacturerSize = $(this).find('.manufacturer-size').html()+'\n';
            }else manufacturerSize = '';
            Data[i] = {};
            Data[i]['barcode'] = $(this).find('.barcode').html();
            Data[i]['info'] = 
                $(this).find('.inscription-label').text()+'\n'+
                manufacturerSize+
                $(this).find('.retail-price').html()+'р.';
            i++;
        });
        // Проверка таблицы на пустоту
        if(empty){
            LoadAlert('Внимание','Таблица пуста',5000,'warning');
            return;
        }
        
//		console.log(JSON.stringify(Data));
//		return;
		
		$.ajax({
			url:url,
			type:method,
			dataType:'json',
			cashe:'false',
			data:Data,
			beforeSend:function(){
				load.fadeIn(100);
			}
		}).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));
            if(data.status == 200){
                /**
                 * Вставляем в href
                 * ссылку на созданный Excel файл
                 */
                labDownloadExportExcel.fadeIn(200);
            }else{
//                popUp('.cngs',data.error,'warning');
                LoadAlert(data.header,data.message,2000,data.type);
            }
			load.fadeOut(100);
		}).fail(function(data){
//			res.html('Fail<br>'+JSON.stringify(data));
			load.fadeOut(100);
            LoadAlert('Внимание','Ошибка PHP',3000,'error');
		});
		
	});
	
	/**
     * ===================================================
     * END Страница "Выгрузка этикетки"
     */
	
	

    /**
     * Страница "Загрузка файлов Excel"
     * ===================================================
     */
	
	/**
     * Страница "Загрузка файлов Excel"
     * ================================
     * Показать/скрыть таблицу со списком файлов
     */
	$('.view-list-files a').on('click',function(){
		var $this = $(this),
			listFiles = $('.w-list-files');
		
		listFiles.slideToggle( 100, function(){
			if(listFiles.is(":visible")) $this.find('.text').html('Скрыть список загруженых файлов');
			if(listFiles.is(":hidden")) $this.find('.text').html('Показать список загруженых файлов');
		});
	});
	
	/**
     * ===================================================
     * END Страница "Загрузка файлов Excel"
     */
    
    
    
    
    
    /**
     * Страница "Поиск чека"
     * ===================================================
     */
	
	/**
     * Страница "Поиск чека"
     * ================================
     * Поле "Введите дату"
     * Календать datepicker
     */
    $('[name=search_date]').datepicker({
        monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрьr','Октябрь','Ноябрь','Декабрь'],
        dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
        firstDay: 1,
        dateFormat: 'yy-mm-dd',
        maxDate: new Date()
    });
	
	/**
     * Страница "Поиск чека"
     * ================================
     * Поле "Введите штрихкод"
     * -----------------------
     * Производим поиск по штрихкоду
     */
    $('.cksh .w-barcode input').on('input',function(){
//        resetCheckSearch()
        var $this = $(this),
            form = $('.cksh'),
            res = form.find('.res'),
            load = form.find('.w-barcode img'),
            thirteen = $this.val().substr(0,13),
            table = form.find('.table tbody'),
            Data = {};
        
        /**
         * Если количество символов первышает 13
         * то вставляем в поле thirteen
		 * и останавливаем скрипт
         */
        if($this.val().length > 13){
            $this.val(thirteen);
			return;
        }else if($this.val().length < 13) return;
        
        // Проверка поля "Введите дату" на пустоту
        if(form.find('[name=search_date]').val() == ''){
            LoadAlert('Внимание','Вы не указали дату поиска',3000,'warning');
            $this.val('');
            return;
        }
        
        // Добавляем в объект дату поиска
        Data['search_date'] = form.find('[name=search_date]').val();
        
        // Добавляем в объект период поиска
        Data['time_period'] = form.find('[name=time_period]').val();
        
        // Добавляем в объект штрихкод
        Data['barcode'] = $this.val();
        
//        console.log(JSON.stringify(Data));
//        return;
        
        $.ajax({
            url:$this.attr('data-url'),
            type:$this.attr('method'),
            dataType:'json',
            cashe:'false',
            data:Data,
            beforeSend:function(){
                load.fadeIn(100);
            }
        }).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));
            if(data.status == 200){
                table.html(data.tr);
                resetCheckSearch();
            }else{
//                popUp('.cngs',data.error,'warning');
                LoadAlert(data.header,data.message,2000,data.type);
            }
			load.fadeOut(100);
		}).fail(function(data){
//			res.html('Fail<br>'+JSON.stringify(data));
			load.fadeOut(100);
            LoadAlert('Внимание','Ошибка PHP',3000,'error');
		});
        
    });
	
	/**
     * Страница "Поиск чека"
     * ================================
     * Кнопка "Поиск"
     * --------------
     * Производим поиск по заданным параметрам фильтра
     */
    $('.cksh .btn-search').on('click',function(){
        var $this = $(this),
            form = $('.cksh'),
            res = form.find('.res'),
            load = $this.find('img'),
            table = form.find('.table tbody'),
            Data = {};
        
        form.find('input,select').each(function(){
//            if(typeof $(this).attr('name') === 'undefined') return;
            Data[$(this).attr('name')] = $(this).val();
        });
        
        // Проверка поля "Введите дату" на пустоту
        if(Data['search_date'] == '' || Data['product_group'] == ''){
            LoadAlert('Внимание','Поля "Дата" и "Товарная группа" должны быть заполнены',3000,'warning');
            $this.val('');
            return;
        }
        
//        console.log(JSON.stringify(Data));
//        return;
        
        $.ajax({
            url:$this.attr('data-url'),
            type:$this.attr('method'),
            dataType:'json',
            cashe:'false',
            data:Data,
            beforeSend:function(){
                load.fadeIn(100);
            }
        }).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));
            if(data.status == 200){
                // Если поиск не пуст
                if(data.tr) table.html(data.tr);
                // Если поиск пуст
                else table.html(tr_empty);
            }else{
//                popUp('.cngs',data.error,'warning');
                LoadAlert(data.header,data.message,2000,data.type);
            }
			load.fadeOut(100);
		}).fail(function(data){
//			res.html('Fail<br>'+JSON.stringify(data));
			load.fadeOut(100);
            LoadAlert('Внимание','Ошибка PHP',3000,'error');
		});
        
    });
	
	/**
     * Страница "Поиск чека"
     * ================================
     * Поле "Выберите бренд"
     * Подгружаем артикулы по выбранному бренду
     */
    $('.cksh [name=brand_code]').on('change',function(){
        //
        var $this = $(this),
            res = $('.res'),
            form = $('.cksh'),
            load = form.find('.vendor-code img'),
            vc = form.find('.vendor-code select'),
            $selected = $('.cksh .brands option:selected'),
            empty_value = 'Список артикулов пуст';
		
        // когда выбрано "ничего"
        if($selected.val() == ''){
            vc.html('<option value="">'+empty_value+'</option>');
            return;
        }
        
        $.ajax({
            url:$this.attr('action'),
            type:$this.attr('method'),
            cashe:'false',
            dataType:'json',
            data:{
                page:'product_nomenclature',
                brand_code:$selected.val(),
                list_name: 'Выберите артикул',
                check_search:'check_search',
                empty_value:empty_value
            },
            beforeSend:function(){
                load.fadeIn(100);
            }
        }).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));
            // LoadAlert(data.header,data.message,live,data.type_message);
            if(data.status == 200){
                vc.html(data.option_s);
            }
            load.fadeOut(100);
        }).fail(function(data){
            res.html('Fail<br>'+JSON.stringify(data));
            LoadAlert('Error','Ошибка PHP',live,'error');
            load.fadeOut(100);
        });
        
        
        
        
        
    });
	
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
    $('.cyag [name=document_type],\
        .cyag [name=months],\
        .cyag [name=years]').on('change',function(){
        // Загрузка списка "Выберите документ"
        getDocuments();
    });
    
	/**
     * Страница "Товарный учет"
     * ========================
     * Выпадающий список "Выберите документ"
     */
    $('.cyag [name=document]').on('change',function(){
        // Получаем данные по документу
        getDocumentById();
    });
    
	/**
     * Страница "Товарный учет"
     * ========================
     * Поле "Введите штрихкод"
     */
    $('.cyag [name=barcode]').on('input',function(){
        
        var $this = $(this),
            res = $('.res'),
            form = $('.cyag'),
            load = form.find('.w-barcode img'),
            document = form.find('[name=document]'),
            table_blocks = form.find('.blocks tbody'),
            btn_delete = form.find('[name=delete_rows]'),
            // Получаем общее количество сток в блоке1
            count_rows = table_blocks.find('tr.section1').length;
            // берем из строки только первые 13 символов
            thirteen = $this.val().substr(0,13),
            Data = {};
        
        /**
         * Номер блока в таблице
         * Подставляется в шаблоне к классу section
         */
        Data['number_section'] = '1';
        
		/**
         * Если количество символов первышает 13
         * то вставляем в поле thirteen
		 * и останавливаем скрипт
         */
        if($this.val().length > 13){
            $this.val(thirteen);
			return;
        }else if($this.val().length < 13) return;
        
        /**
         * Если выпадающий список "Выберите документ"
         * ничего не выбрано, то выбираем "Добавить новый"
         */
        if(document.val() == '') document.val('new');
        
        // Скрываем 2 и 3 блоки
        table_blocks.find('.s2,.section2,.s3,.section3').hide();
        
        // Пробуем выбрать штрихкод в таблице
        var is_barcode = table_blocks.find('tr.section1 td:contains("'+thirteen+'")').parent();
        /**
         * Если штрихкод найден
         * то берем весь HTML строки tr
         * и вставляем копию в начало таблицы
         */
        if(typeof is_barcode.html() !== 'undefined'){
            table_blocks.find('.t-header.s1').show();
            // В начало таблицы вставляем новую строку
            table_blocks.find('.s1')
                .after('<tr class="section'+Data['number_section']+'"  data-visible="">'+is_barcode.html()+'</tr>');
            
            /**
             * Если вставленная строка была скопирована от деактивированной
             * то нужно изменить данные по количеству
             * в артибуте делаем 0, а в HTML вставляем 1
             */
            table_blocks.find('tr.section1 .quantity1:first').attr('data-quantity','');
            table_blocks.find('tr.section1 .quantity1:first').html('1');
            /**
             * Если копируется деактивированная строка
             * то в новой строке чекбок будет тоже деактивированным
             * его нужно снова активировать
             */
            table_blocks.find('.section1:first [name=checkbox]').prop('disabled','');
            
            // Опустошаем поле "Введите штрихкод"
            $this.val('');
            
            /**
             * Если в выпадающем списке "Выберите поставщика"
             * что то выбрано, то сравниваем выбранного поставщика
             * с поставщиком пришедшей строки.
             * Если совпадения нет, то чекбок текущей строки
             * делаем отмеченным
             */
            if(form.find('[name=provider]').val() != '' &&
                (form.find('[name=provider]').val() !=
                form.find('.section1 [name=provider_row]').val())){
                
                table_blocks.children('.section1:first').find('[name=checkbox]')
                    .prop('checked',true);
                LoadAlert('Внимание','Товар в отмеченных строках получен не от выбранного поставщика',4000,'warning');
            }

            /**
             * Если в выпадающем списке "Выберите документ"
             * выбран пункт "Добавить новый"
             * то вместо номера документа нужно вставить строку "new"
             */
            if(document.val() != '' || document.val() == 'new'){
                table_blocks.children('.section1:first').find('.dock1')
                    .html('new');
            }
            
            // Ставим номер строки на один больше. (Общее количество + 1)
            table_blocks.find('.counter1:first').html(rowSequenceIncrement());
            
            /**
             * После добавления делаем
             * пересчет строк в таблице с перенумерованием строк
             * пересчет итоговых значений
             */
            calculationCA('1');
            /**
             * Не видимые строки почему то появляются
             * скрываем их опять
             */
            table_blocks.find('[data-visible=dn]').hide();
            return;
        }
        
        /**
         * Если на странице штрихкода нет
         * то идем искать его в БД
         */
        
        Data['barcode'] = thirteen;
        Data['page'] = 'commodity-accounting';
        
//        cl(Data,'j');return;
        
        $.ajax({
            url:$this.attr('data-url'),
            type:$this.attr('method'),
            cashe:'false',
            dataType:'json',
            data:Data,
            beforeSend:function(){ load.fadeIn(100); }
        }).done(function(data){
//            res.html('Done<br>'+JSON.stringify(data));
            if(data.status == 200){
                
                // Делаем активной кнопку "Удалить выбранные строки"
                btn_delete.prop('disabled','');
                
                // Показываем заголовок первого блока
                table_blocks.find('.t-header.s1').show();
                // Добавляем новую строку в 1 блок, в самое начало
                table_blocks.find('.s1').after(data.row);
                
                // Ставим номер строки на один больше. (Общее количество + 1)
                table_blocks.find('.counter1:first').html(rowSequenceIncrement());
                
                // Показываем первый блок
                table_blocks.find('.s1,.section1').show();
                
                /**
                 * Если в выпадающем списке "Выберите поставщика"
                 * что то выбрано, то сравниваем выбранного поставщика
                 * с поставщиком пришедшей строки.
                 * Если совпадения нет, то чекбок текущей строки
                 * делаем отмеченным
                 */
                if(form.find('[name=provider]').val() != '' &&
                    (form.find('[name=provider]').val() !=
                    form.find('.section1 [name=provider_row]').val())
                ){
                    table_blocks.children('.section1:first').find('[name=checkbox]')
                        .prop('checked',true);
                    
                    LoadAlert('Внимание','Товар в отмеченных строках получен не от выбранного поставщика',4000,'warning');
                }
                
                /**
                 * Если в выпадающем списке "Выберите документ"
                 * выбран пункт "Добавить новый"
                 * то вместо номера документа нужно вставить строку "new"
                 */
                if(document.val() != '' || document.val() == 'new'){
                    table_blocks.children('.section1:first').find('.dock1')
                        .html('new');
                }
                
    
                /**
                 * После добавления делаем
                 * пересчет строк в таблице с перенумерованием строк
                 * пересчет итоговых значений
                 */
                calculationCA('1');
                
                /**
                 * Не видимые строки почему то появляются
                 * скрываем их опять
                 */
                table_blocks.find('[data-visible=dn]').hide();
                
            }else{
                LoadAlert(data.header,data.message,live,data.type);
            }
            $this.val('');// Опустошаем поле "Введите штрихкод"
            load.fadeOut(100);
        }).fail(function(data){
            res.html('Fail<br>'+JSON.stringify(data));
            LoadAlert('Error','Ошибка PHP',live,'error');
            load.fadeOut(100);
        });
        
    });
    
	/**
     * Страница "Товарный учет"
     * ========================
     * Кнопка "Остатки ШК"
     */
    $('.cyag button.leftovers-shk').on('click',function(){
        
        leftoversShkCA();
        
    });
    
	/**
     * Страница "Товарный учет"
     * ========================
     * Кнопка "Остатки Размеры"
     */
    $('.cyag button.leftovers-dimensions').on('click',function(){
            
        // Запускаем функцию "Остатки ШК"
        // DS - ...dimensions
        leftoversShkCA('DS');
        
    });
    
    /**
     * Страница "Товарный учет"
     * ========================
     * Кнопка "Развернуто"
     */
    $('.cyag button.block-show').on('click',function(){
        var form = $('.cyag'),
            table_blocks = form.find('.blocks'),
            btn_delete = form.find('[name=delete_rows]');
        
        table_blocks.find('.s2,.section2,.s3,.section3').hide();
        table_blocks.find('.s1,.section1').show();
        
        /**
         * Почему то появляются невидимые строки
         * Убираем их снова
         */
        table_blocks.find('[data-visible=dn]').hide();
        
        // Делаем активной кнопку "Удалить выбранные строки"
        btn_delete.prop('disabled','');
        
        // Делаем пересчет по блоку 1
        calculationCA('1');
        
    });
    
    /**
     * Страница "Товарный учет"
     * ========================
     * Выпадающий список "Выберите поставщика"
     */
    $('.cyag [name=provider]').on('change',function(){
        var $this = $(this),
            form = $('.cyag'),
            table_blocks = form.find('.blocks'),
            btn_delete = form.find('[name=delete_rows]'),
            provider_error = false;
        
        table_blocks.find('.s2,.section2,.s3,.section3').hide();
        table_blocks.find('.s1,.section1').show();
        
        // Делаем активной кнопку "Удалить выбранные строки"
        btn_delete.prop('disabled','');
        
        table_blocks.find('tr.section1').each(function(){
            
            // Деактивированные строки не нужны, пропускаем их
            if($(this).find('.quantity1').html() == '0') return;
            
            /**
             * Если в выпадающем списке "Выберите поставщика" выбрано "ничего"
             * с чекбоксов убираем все отметки
             */
            if($this.val() == ''){
                
                // Если строка видима
                if($(this).attr('data-visible') == '')
                    $(this).find('[name=checkbox]').prop('checked','');
                
            }else if($(this).find('[name=provider_row]').val() != $this.val()){
                /**
                 * Если в выпадающем списке "Выберите поставщика" выбранное значение
                 * не равняется с текущем поставщиком
                 * checkbox делаем отмеченным
                 */
                
                // Если строка видима
                if($(this).attr('data-visible') == ''){
                    $(this).find('[name=checkbox]').prop('checked',true);
                    provider_error = true;
                }
                
            }else {
                /**
                 * Если в выпадающем списке "Выберите поставщика" выбранное значение
                 * равняется с текущим поставщиком
                 * checkbox делаем пустым
                 */
                
                // Если строка видима
                if($(this).attr('data-visible') == '')
                    $(this).find('[name=checkbox]').prop('checked','');
                
            }
        });
        
        /**
         * Если есть отмеченные строки
         * у которых поставщик не совпадает с выбранным
         */
        if(provider_error){
                LoadAlert('Внимание','Товар в отмеченных строках получен не от выбранного поставщика',4000,'warning');
        }
        
        /**
         * Строки, у которых атрибут data-vivible не пуст
         * скрываем. Потому что они почему то появляются
         * при изменении в них состояния чекбокса.
         */
        form.find('[data-visible=dn]').hide();
        
        // Делаем пересчет по блоку 1
//        calculationCA('1');
        
    });
    
    /**
     * Страница "Товарный учет"
     * ========================
     * Кнопка "Сохранить документ"
     */
    $('.cyag button.save').on('click',function(){
        var $this = $(this),
            form = $('.cyag'),
            load = $this.find('img'),
            res = form.find('.res'),
            table_blocks = form.find('.blocks'),
            btn_delete = form.find('[name=delete_rows]'),
            document_type = form.find('[name=document_type]'),
            document = form.find('[name=document]'),
            provider = form.find('[name=provider]'),
            months = form.find('[name=months]'),
            years = form.find('[name=years]'),
            existing_document = false,
            provider_error = false,
            no_change = false,
            type_match = true,
            returns = false,
            tr_is = false,
            Data = {},
            Row = {},
			i = 0;
        
        // Переводим режим просмотра в состояние "Развернуто"
        table_blocks.find('.s2,.section2,.s3,.section3').hide();
        
        /**
         * Если в таблице есть строки
         * показываем заголовок блока
         */
        if(table_blocks.find('tr.section1').length != 0)
            table_blocks.find('.s1,.section1').show();
        
        // Делаем активной кнопку "Удалить выбранные строки"
        btn_delete.prop('disabled','');
        
        // Если значение списка «Выберите документ» не выбрано
        if(document.val() == ''){
            LoadAlert('Внимание','Действие не выбрано<br>сохранение не произведено',4000,'warning');
            return;
        }
        
        // Если значение списка «Тип документа» не выбрано
        if(document_type.val() == ''){
            LoadAlert('Внимание','Документ не сохранен<br>Выберите тип документа',4000,'warning');
            return;
        }
        
        // Если на странице нет ни одной строки
        if(table_blocks.find('tr.section1').length == 0){
            LoadAlert('Внимание','Документ не сохранен. Отсутствует содержание',4000,'warning');
            return;
        }
        
        // Снимаем все отметки с чекбоксов
        table_blocks.find('tr.section1 [name=checkbox]').prop('checked','');
        /**
         * Сразу же
         * Не видимые строки почему то появляются
         * скрываем их опять
         */
        table_blocks.find('[data-visible=dn]').hide();
        
        /**
         * ==================================
         * Выборка статичных данных документа
         * ----------------------------------
         */
        Data['info'] = {};
        Data['table'] = {};
        
        // Поле комментарий
        Data['info']['counterparty_document_comment'] = form.find('[name=description]').val();
        
        /**
         * Если значение списка «Тип документа» равно 
         * «Возврат товара Комитенту» или «Возврат брака Поставщику»
         * то проверим список "Выберите поставщика"
         * и пройдемся по всем строкам с проверкой
         * есть ли в таблице строки, не принадлежащие выбранному поставщику
         */
        if(document_type.val() == '05' || document_type.val() == '06'){
            
            // Тип документа - какой то из возвратов (...)
            returns = true;
            
            // Код поставщика
            Data['info']['vendor_code'] = provider.val();
            
            // Если список "Выберите поставщика" пуст
            if(provider.val() == ''){
                LoadAlert('Внимание','Документ не сохранен<br>Выберите поставщика',4000,'warning');
                return;
            }
            
            /**
             * Перебирая все строки, проверям на соответствие выбанному поставщику
             * Не соответствующие строки омечаем чекбоксом
             * и если такие найдены, останавливаем скрипт
             * и выдаем сообщение с предупреждением
             * ===================================================================
             * Во втором, основном цикле, следующем ниже, проверяются только те строки
             * у которых есть Док "new" или Количество 0,
             * а тут, на соответствие поставщика, проверяются - все строки
             */
            table_blocks.find('tr.section1').each(function(){
            
                // Деактивированные строки не нужны, пропускаем их
                if($(this).find('.quantity1').html() == '0') return;
                
                // Невидимые строки не нужны, пропускаем их
                if($(this).attr('data-visible') != '') return;
                
                /**
                 * Проверка на соответствие поставщика
                 * ===================================
                 * Если есть не соответствие, отмечаем эти строки чекбоксом
                 */
                if($(this).find('[name=provider_row]').val() != provider.val()){
                    // Отмечаем чекбокс
                    $(this).find('.w-checkbox [name=checkbox]').prop('checked',true);
                    // Делаем флаг для запуска предупреждающего сообщения
                    provider_error = true;
                }
                
            });
            
        }
        
        /**
         * Если выбран существующий документ
         * т.е. не пустое значение и не "Добавить новый"
         */
        if(document.val() != '' && document.val() != 'new'){
            
            /**
             * Тип документа
             * =============
             * Нужно для определения знака количества (плюс/минус)
             * ---------------------------------------------------
             * Если "Выберите документ" не "new" и не пусто
             * берем тип документа из дата-атрибута выбранного документа
             */
            Data['info']['document_type'] = document.find('option:selected').attr('data-type');
            
            // Тип операции "document_correction"
            Data['action_type'] = 'document_correction';
            // ID редактируемого документа
            Data['info']['document_id'] = document.val();
            
            // Флаг, что в поле "Выберите документ" выбран какой то документ
            existing_document = true;
            
            // Выбираем строки с "new"
            var dock1 = table_blocks.find('tr.section1').find('td.dock1:contains("new")').html(),
                // Выбираем строки с "0"
                quantity1 = table_blocks.find('tr.section1').find('td.quantity1:contains("0")').html();
            
            // Если нет строк с "new" И нет строк где количество "0"
            if(typeof dock1 === 'undefined' && typeof quantity1 === 'undefined'){
                
                // Если на странице есть строки, где значение чекбокса «выбрано»
                if(provider_error){
                    LoadAlert('Внимание','Документ не сохранен <br>Товар в отмеченных строках<br>получен не от выбранного поставщика',5000,'warning');
                    /**
                     * Не видимые строки почему то появляются
                     * скрываем их опять
                     */
                    table_blocks.find('[data-visible=dn]').hide();
                    return;
                }
                
                // Если "Тип документа" совпадает с типом выбранным в "Выберите документ"
                if(form.find('[name=document_type]').val() == 
                   form.find('[name=document] option:selected').attr('data-type')){
                    
                    LoadAlert('Внимание','Нет изменений для сохранения',4000,'warning');
                    /**
                     * Не видимые строки почему то появляются
                     * скрываем их опять
                     */
                    table_blocks.find('[data-visible=dn]').hide();
                    return;
                }else{
                    /**
                     * Если НЕ НУЖНО делать коррекцию,
                     * а нужно сразу списать документ на ноль
                     * ======================================
                     * Если в списках "Тип документа" и "Выберите документ"
                     * типы документов не совпадают
                     * то сразу запустим функцию,
                     * которая спишет документ на ноль
                     * и создаст новый документ с соответствующим типом
                     * и останавливаем скрипт здесь, после этой функции.
                     */
                    saveNewDocument($this);
                    return;
                }
            }
            
            // Тип операции "document_correction"
            Data['action_type'] = 'document_correction';
            
        }else if(document.val() == 'new'){
            /**
             * Если тип документа "new"
             * ========================
             * Нужно для определения знака количества (плюс/минус)
             * ---------------------------------------------------
             * Если "Выберите документ" - "new"
             * берем тип документа из поля "Тип документа"
             */
            Data['info']['document_type'] = document_type.val();
            
            // Тип операции "new"
            Data['action_type'] = 'new';
            
        }
        
        
        /**
         * Не видимые строки почему то появляются
         * перед сборкой строк, все строки которые должны быть невидимы
         * нужно скрыть, чтобы они не попали в выборку
         * ------------------------------------------------------------
         * Но и в выборке стоит проверка на нивидимость
         * по атрибуту data-visible
         */
        table_blocks.find('[data-visible=dn]').hide();
        
        // Перебираем строки .table
        table_blocks.find('tr.section1').each(function(){
            
            // Не видимые строки пропускаем
            if($(this).attr('data-visible') != '') return;
            
            // Если поле "Выберите документ" - не "пусто" и не "new"
            if(existing_document){
                /**
                 * Если в списках "Тип документа" и "Выберите документ"
                 * типы документов совпадают,
                 * то собираем только те строки, у которых
                 * Док - "new" и Количество - 0
                 * Остальные строки пропускаем
                 */
                if($(this).find('.dock1').html() != 'new' && $(this).find('.quantity1').html() != '0') return;
            }
            
            /**
             * Тут проверяются только те строки
             * у которых есть Док "new" или Количество 0
             */
            // Если тип документа - какой то из возвратов (...)
            if(returns){
                // Если на странице есть строки, где значение чекбокса «выбрано»
                if($(this).find('[name=checkbox]').prop('checked')) provider_error = true;
            }
            
            Row = {};
            Row['str_dock'] = $(this).find('.counter1').html();
            Row['barcode'] = $(this).find('.td-barcode1').html();
            
            /**
             * Если количество 0, значит это деактивированная строка
             * значит количество берем из атрибута "data-quantity"
             */
            if($(this).find('.quantity1').html() == '0'){
                
                // Если количество 0, значит берем из атрибута
                quantity_row = Number($(this).find('.quantity1').attr('data-quantity'));
                
            }else{
                // Если количество не 0, то берем из HTML
                quantity_row = Number($(this).find('.quantity1').html());
            }
            
            Row['quantity'] = quantity_row;
            
            Data['table'][$(this).find('.counter1').html()] = Row;
        });
        
        // Если на странице есть строки, где значение чекбокса «выбрано»
        if(provider_error){
            LoadAlert('Внимание','Документ не сохранен <br>Товар в отмеченных строках<br>получен не от выбранного поставщика',5000,'warning');
            /**
             * Не видимые строки почему то появляются
             * скрываем их опять
             */
            table_blocks.find('[data-visible=dn]').hide();
            return;
        }
        
        /**
         * Не видимые строки почему то появляются
         * скрываем их опять
         */
        table_blocks.find('[data-visible=dn]').hide();
        
//        cl('btn');
//        cl(Data);
//        return;
    
        $.ajax({
            url:$this.attr('data-url'),
            type:$this.attr('method'),
            cashe:'false',
            dataType:'json',
            data:Data,
            beforeSend:function(){
                load.fadeIn(100);
                // Кнопку "Сохранить документ" деактивируем
                $this.prop('disabled',true);
            }
        }).done(function(data){
            res.html('Done<br>'+JSON.stringify(data));
            LoadAlert(data.header,data.message,live,data.type_message);
            if(data.status == 200){
                /**
                 * Обновляем список "Выберите документ"
                 */
                if(document.val() == 'new'){
                    /**
                     * Если выбран новый документ
                     * ==========================
                     * Отправляем аргумент "document_type"
                     * чтобы список "Выберите документ" перезагрузился
                     * по коду типа документа, взятому из выбранного элемента
                     * списка "Тип документа"
                     */
                    getDocuments('document_type');
                }else{
                    /**
                     * Если выбран существующий документ
                     * =================================
                     * Отправляем аргумент "document"
                     * чтобы список "Выберите документ" перезагрузился
                     * по коду типа документа, взятому из выбранного элемента
                     * списка "Выберите документ"
                     */
                    getDocuments('document');
                }

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
                
                // Кнопку "Сохранить документ" делаем активной
                $this.prop('disabled','');
                
                /**
                 * Пометка документа на ноль, будет отложенной
                 * чтобы дать время на загрузку строк на страницу
                 */
                setTimeout(function(){
                    /**
                     * Если по выбранному ID документа,
                     * в таблицу не было загружено ни одной строки. 
                     * Проверим таблицу на пустоту
                     * Если пусто, то соанавливаем скрипт,
                     * и сообщаем, что таблица пуста
                     */
                    table_blocks.find('tr.section1').each(function(){
                        if($(this).attr('data-visible') == '') tr_is = true;
                    });

                    if(!tr_is){
                        // Удаляем выбранный документ из списка
                        document.find('option:selected').remove();
                        // Сбрасываем страницу
                        resetCA();

                        // Ставим поле document_correction_code в 0
                        documentCorrectionCodeOff(data.document_id);

                        LoadAlert('Внимание','Нет строк для отображения',4000,'warning');
                        return;
                    }
                },1000);
                
                /**
                 * На всякий случай сделаю проверку на action_type (тип операции)
                 * если тип операции action_type не "new"
                 * то типы документов нужно проверить
                 * т.е. функция создания документа с новым типом
                 * будет запущена, если типы не совпадают
                 */
                if(Data['action_type'] != 'new'){
                    /**
                     * Если НУЖНО сначала сделать коррекцию,
                     * а потом сделать списание документа на ноль
                     * ==========================================
                     * Если в списках "Тип документа" и "Выберите документ"
                     * типы документов не совпадают
                     */
                    if(document_type.val() != document.find('option:selected').attr('data-type')){
                        // Кнопку "Сохранить документ" деактивируем
                        $this.prop('disabled',true);
                        setTimeout(function(){
                            saveNewDocument($this);
                        },2000);
                    }
                }
                
                
                
                
                

    //                table_blocks.find('.t-header.s1').show();
    //                table_blocks.find('.section1').remove();
    //                table_blocks.find('.s1').after(data.rows);
    //                comment.val(data.comment);
                /**
                 * После добавления делаем
                 * пересчет строк в таблице с перенумерованием строк
                 * пересчет итоговых значений
                 */
    //            calculationCA('1');
            }else{
                // Кнопку "Сохранить документ" делаем активной
                $this.prop('disabled','');
            }
            load.fadeOut(100);
        }).fail(function(data){
            res.html('Fail<br>'+JSON.stringify(data));
            LoadAlert('Error','Ошибка PHP',live,'error');
            load.fadeOut(100);
        });
         
    });
	
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
     * Выпадающий список "Новые заказы"
     * Выпадающий список "готовые заказы"
     */
    $('.orrs [name*=_orders]').on('change', function(){
        var $this = $(this),
            form = $('.orrs'),
            info = form.find('.info'),
            itogo = form.find('.itogo'),
            res = form.find('.res'),
            load = form.find('.new-orders img'),
            
            order_number = info.find('.order-number span'),
            customer = info.find('.customer span'),
            order_status = info.find('.order-status span'),
            phone = info.find('.phone span'),
            comment = info.find('textarea'),
            total_amount = itogo.find('.total-amount span'),
            total_discount = itogo.find('.total-discount span'),
            i_total_amount = 0,
            i_total_discount = 0,
            order_compiled = form.find('[name=order_compiled]'),
            order_c = form.find('[name=order_c]'),
            
            table = form.find('.table tbody'),
            Data = {};
        
        // Убираем с экрана все оповещающие окна
        cea();
        
        /**
         * Если список "Новые заказы",
         * то сбрасываем "Готовые заказы"
         * и наоборот
         */
        if($this.attr('name') == 'new_orders'){
            
            form.find('[name=ready_orders]').val('');
            
            order_compiled.removeAttr('checked').prop('disabled','');
            order_c.removeAttr('checked').prop('disabled',true);
            
        }else{
            
            form.find('[name=new_orders]').val('');
            
            order_compiled.prop('checked',true).prop('disabled',true);
            order_c.removeAttr('checked').prop('disabled','');
            
        }
        
        // Если выбрано "ничего"
        if($this.val() == ''){
            // Сбрасываем всю страницу
            clearOrdersPage();
            return;
        }
        
        Data['type'] = $this.attr('name');
        Data['order_id'] = $this.val();
        
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
            
            // Заполняем данные информационного блока о пользователе
            // Номер заказа
            order_number.html(data.order_number);
            // Имя пользователя
            customer.html(data.first_name);
            
            if($this.attr('name') == 'new_orders') var os = 'Новый';
            else var os = 'Готовый';
            
            // Статус заказа
            order_status.html(os);
            // Телефон пользователя
            phone.html(data.phone);
            // Комментарий
            comment.val(data.comment);
            
            // Если товары заказа найдены
            if(data.rows != ''){
                table.html(data.rows);
            }
            
            // Считаем итоговые суммы
            table.find('tr').each(function(){
                // Если таблица пуста, то ничего не делаем
                if($(this).attr('class') == 'empty') return false;
                
                i_total_amount += Number($(this).find('.ret-price').html().replace(',','.'));
                i_total_discount += Number($(this).find('.disc-price').html().replace(',','.'));
                
            });
            
            // Итоговые данные
            total_discount.html(number_format((i_total_amount - i_total_discount),2,',',''));
            total_amount.html(number_format(i_total_discount,2,',',''));

            load.fadeOut(100);
        }).fail(function(data){
            res.html('Fail<br>'+JSON.stringify(data));

            LoadAlert('Error','Ошибка PHP',live,'error');
            load.fadeOut(100);
        });
    });
    
    /**
     * Страница "Заказы"
     * =================
     * Кнопка "Сохранить"
     */
    $('.orrs [name=save_orders]').on('click', function(){
        var $this = $(this),
            form = $('.orrs'),
            res = form.find('.res'),
            load = $this.find('img'),
            new_orders = form.find('[name=new_orders]'),
            ready_orders = form.find('[name=ready_orders]'),
            order_compiled = form.find('[name=order_compiled]'),
            order_c = form.find('[name=order_c]'),
            clear_orders = false,
            new_orders_flag = false,
            Data = {};
        
        
        // Убираем с экрана все оповещающие окна
        cea();
        
        // Если оба выпадающих списка пусты
        if(
            form.find('[name=new_orders]').val() == '' &&
            form.find('[name=ready_orders]').val() == ''
        ){
            LoadAlert('Внимание','Не выбран заказ',3000,'warning');
            return;
        }
        
        // Если выбран "Новые заказы", а "Готовые заказы" не выбран
        if(
            form.find('[name=new_orders]').val() != '' &&
            form.find('[name=ready_orders]').val() == ''
        ){
            // Тип "Новый заказ"
            Data['type'] = 'new_orders';
            // ID заказа
            Data['order_id'] = form.find('[name=new_orders]').val();
            if(form.find('[name=order_compiled]').prop('checked')){
                Data['type_status'] = 1;
            }else{
                Data['type_status'] = 0;
            }
            
            /**
             * Флаг, обозначает что кнопка "сохранить" нажата при
             * сохранении "нового" заказа
             */
            new_orders_flag = true;
        }else if(
            // Если "Новые заказы" не выбран, а "Готовые заказы" выбран
            form.find('[name=new_orders]').val() == '' &&
            form.find('[name=ready_orders]').val() != ''
        ){
            // Тип "Готовый заказ"
            Data['type'] = 'ready_orders';
            // ID заказа
            Data['order_id'] = form.find('[name=ready_orders]').val();
            
            // Если одна из радио кнопок выбрана
            if(typeof form.find('[name=order_c]:checked').attr('id') !== 'undefined'){
                // Получаем ID выбранной радио кнопки
                Data['type_status'] = form.find('[name=order_c]:checked').attr('id');
                clear_orders = true;
            }else{
                // Если ни одна из радио кнопок не выбрана
                Data['type_status'] = '';
            }
            
            
        }
        // Поле комментария
        Data['comment'] = form.find('.info textarea').val();

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

            if(data.status == 200){
                LoadAlert(data.header,data.message,live,data.type_message);
                
                // Перезаписываем выпадающий список "Новые заказы"
                new_orders.html(data.new_orders_rows);
                
                // Перезаписываем выпадающий список "Готовые заказы"
                ready_orders.html(data.ready_orders_rows);
                
                /**
                 * Если одна из радио кнопок выбрана
                 * сбросим страницу
                 */
                if(clear_orders){
                    clearOrdersPage();
                }
                
                /**
                 * Условие сработает если сохраняется "новый" заказ
                 */
                if(new_orders_flag){
                    /**
                     * Если в выпадающем списке "Готовые заказы"
                     * найден option с атрибутом selected
                     */
                    if(typeof ready_orders.find('option[selected=selected]').val() !== 'undefined'){
                        // checkbox делаем отмеченным и недоступным
                        order_compiled.prop('checked',true).prop('disabled',true);
                        // Радио кнопки делаем доступными
                        order_c.removeAttr('checked').prop('disabled','');
                    }
                    
                }
                
            }else{
                popUp('.orrs','Done !200<br>'+JSON.stringify(data),'danger');
            }

            load.fadeOut(100);
        }).fail(function(data){
//            res.html('Fail<br>'+JSON.stringify(data));
            LoadAlert('Error','Ошибка PHP',live,'error');
            load.fadeOut(100);
        });
    });
	
	/**
     * ===================================================
     *             END Страница "Заказы"
     * ===================================================
     */
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /* ====================================================
    =======================================================
    
                  ОБЩИЕ СКРИПТЫ ДЛЯ СТРАНИЦ
    
    =======================================================
    ==================================================== */
	// кнопка "Удалить выделенные строки"
    // удаляем со страницы выделенные строки
    $('.sales-reciept .delete,.gr .delete').on('click',function(){
        // собираем все поля "input,textarea,select" - в объкект
        $('.sales-reciept,.gr').find('input:checkbox:checked').each(function() {
            $(this).parent().parent().fadeOut(100).remove();
        });
        if($(this).attr('data-type') == 'goods-receipt'){
            /** 
             * Пересчитываем количество штук товаров
             * по значениям полей "количество"
             */
            var quantity = 0;
            var quantityTr = $('.gr .table tbody tr').length;
            $('.gr .table tbody tr').each(function(){
                quantity += Number($(this).find('input[name=quantity]').val());
                $(this).find('.sn span').html(quantityTr);
                $(this).find('.sn input').val(quantityTr);
                quantityTr--;
            });

            /**
             * Пересчитываем общие суммы на странице
             * себестоимость/розничная стоимость
             */
            var totalCostPrice = $('.gr .t-cost-price'),
                totalRetailPrice = $('.gr .t-retail-price'),
                costPriceItogSumm = 0,
                retailPriceItogSumm = 0;

            $('.gr .table tbody').find('tr').each(function(){
                var costPrice = $(this).find('.cost-price'),
                    retailPrice = $(this).find('.retail-price'),
                    amount = $(this).find('.amount');

                costPriceItogSumm += (Number(amount.val()) * Number(costPrice.val())),
                retailPriceItogSumm += (Number(amount.val()) * Number(retailPrice.val()));

            });

            // Количество по полю - количество
            $('.gr .info .quantity').html(quantity);
            // Себестоимость
            totalCostPrice.html(number_format(costPriceItogSumm,2,',',' '));
            // Розничная стоимость
            totalRetailPrice.html(number_format(retailPriceItogSumm,2,',',' '));

        }
    });
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
// DEBUG ==================================================
	
	$('button.debug').on('click',function(){
		var $this = $(this),
            res = $('.res'),
            type = $this.attr('name');
        Data = {};
        Data['type'] = type;
        
        cea();
		
		$.ajax({
			url:$this.attr('data-url'),
			type:$this.attr('data-type'),
			dataType:'json',
			cashe:'false',
			data:Data,
			berforeSend:function(){res.html('Создаю...');}
		}).done(function(data){
            res.html('Done<br>'+JSON.stringify(data));
			if(data.status == 200){
				LoadAlert('Успешно',data.message,5000);
			}else{
				LoadAlert('Внимание','Статус не 200',5000,'warning');
			}
		}).fail(function(data){
			LoadAlert('Ошибка','Не известная ошибка',5000,'error');
//			popUp('.debug',JSON.stringify(data),'danger');
            res.html('Fail<br>'+JSON.stringify(data));
		});
		
	});

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    /**
     * Страница "Отправка Email"
     * ===================================================
     */



    /**
     * Страница "Отправка Email"
     * =========================
     * Кнопка "Отправить Email"
     */
    $('.eml .send-mail').on('click', function(){
        var $this = $(this),
            form = $('.eml'),
            res = form.find('.res'),
            load = $this.find('img'),
            type_mail = $this.attr('name'),
            Data = {};

        // Убираем с экрана все оповещающие окна
        cea();

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
            popUp('.eml','Fail<br>'+JSON.stringify(data));
            LoadAlert('Error','Ошибка PHP',live,'error');
            load.fadeOut(100);
        });
    });



    /**
     * Страница "Отправка Email"
     * =========================
     * Кнопка "Отправить Email"
     */
    $('[name=but2]').on('click', function(){
        cl($('.table.blocks').find('tr.section1').length);
        
        
        
        
        
        
        return;
        var $this = $(this),
            form = $('.eml'),
            res = form.find('.res'),
            load = $this.find('img'),
            type_mail = $this.attr('name'),
            Data = {};

        // Убираем с экрана все попыещающие окна
        cea();

        Data['send_mail'] = true;
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
            popUp('.eml','Fail<br>'+JSON.stringify(data));
            LoadAlert('Error','Ошибка PHP',live,'error');
            load.fadeOut(100);
        });
    });
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    // Для тестов
    $('[name=but],.test').on('click', function(){
        var Data = {},
            res = $('.res');
        
        $.ajax({
            url:'ajax/debug',
            type:'post',
            cashe:'false',
            dataType:'json',
            data:Data,
            beforeSend:function(){
//                load.fadeIn(100);
            }
        }).done(function(data){
            res.html('Done<br>'+JSON.stringify(data));

//            LoadAlert(data.header,data.message,live,data.type_message);
//            if(data.status == 200){
//
//            }else{
//                popUp('.eml','Done !200<br>'+JSON.stringify(data),'danger');
//            }

//            load.fadeOut(100);
        }).fail(function(data){
            res.html('Fail<br>'+JSON.stringify(data));
//            popUp('.eml','Fail<br>'+JSON.stringify(data));
//            LoadAlert('Error','Ошибка PHP',live,'error');
//            load.fadeOut(100);
        });
    });

});// JQuery

