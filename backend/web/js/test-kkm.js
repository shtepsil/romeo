// Функция обработки результата команд
function ExecuteSuccess(Rezult) {
    
//    $('.res').html(JSON.stringify(Rezult));

	switch(Rezult['Command']){
		case 'RegisterCheck':
			/**
			 * Регистрация чеков происходит только на странице "Товарный чек"
			 * по этому используем только селектор ".sales-reciept"
			 * Чтобы нам брать только то окно, которое на нужной странице
			 * и если там будут какие то ошибки, то они будут показаны.
			 */
			if(Rezult['Error'] != '') popUp('.sales-reciept',Rezult['Error'],'warning');
			else{
				var btnKkm = $('.sales-reciept button.kkm');
				/**
				 * После нажатия на кнопку ККМ, отключаем кнопку
				 * и тип кнопки делаем пустым
				 */
				btnKkm.attr('data-type-check','');
//				btnKkm.prop('disabled',true);
                LoadAlert('Успешно','Чек ККМ завершен',5000);
			}
			break;
		case 'OpenShift':
			if(Rezult['Error'] != ''){
                /**
                 * Номер ошибки, которую нужно найти в строке ошибок
                 * и заменить своим сообщением
                 */
				var mask = '60';
				// создаем шаблон с помощью конструктора регулярки
				var regex = new RegExp(mask,'i');
				// и собственно используем созданый шаблон в .match
				var result = Rezult['Error'].match( regex );
				
				if(result != null){
					LoadAlert('Внимание','Смена ККМ уже открыта',3000,'warning');
//					popUp('.cr',Rezult['Error'],'warning');
				}else{
					/**
					 * Если будет какая то другая ошибка
					 * то выведем её в alerts
					 */
					popUp('.cr',Rezult['Error'],'warning');
				}
			}
			else{
				// Если смена открылась, пополняем кассу.
                /**
                 * Чтобы скрит не зависал на сервере KKM
                 * Делаем отложенный запуск функции
                 * "Пополнение кассы"
                 */
                setTimeout(function(){
				    DepositingCash();
                },2000);
			}
			break;
		case 'CloseShift':
			/**
			 * Результат нажатия на кнопку "Закрыть смену"
			 */
			if(Rezult['Error'] != ''){
                /**
                 * Номер ошибки, которую нужно найти в строке ошибок
                 * и заменить своим сообщением
                 */
				var mask = '61';
				// создаем шаблон с помощью конструктора регулярки
				var regex = new RegExp(mask,'i');
				// и собственно используем созданый шаблон в .match
				var result = Rezult['Error'].match( regex );
				
				if(result != null){
					LoadAlert('Внимание','Не удалось закрыть смену ККМ,<br>смена уже закрыта...',3000,'warning');
				}else{
					/**
					 * Если будет какая то другая ошибка
					 * то выведем её в alerts
					 */
					popUp('.cr',Rezult['Error'],'warning');
				}
			}else LoadAlert('Успешно','Смена ККМ закрыта',3000);
			break;
		case 'GetDataKKT':
			/**
			 * Нажатие на кнопку "Закрыть смену"
			 */
			if(Rezult['Error'] != '') popUp('.cr',Rezult['Error'],'warning');
			else{
				/**
				 * Сначала запрашиваем баланс кассы.
				 * Если баланс кассы не 0
				 * то запускаем выемку наличных
				 */
				if(Rezult['Info']['BalanceCash'] != 0){
                    /**
                     * Чтобы скрит не зависал на сервере KKM
                     * Делаем отложенный запуск функции "Выемка денег из кассы"
                     */
                    setTimeout(function(){
                        paymentCash('0',Rezult['Info']['BalanceCash']);
                    },2000);
				}else{
					/**
					 * Если наличка 0
					 * то пытаемся закрыть смену
					 * (скорей всего смена уже будет закрыта
					 *  и сервер ККТ выдаст соответствующее сообщение об ошибке)
					 */
					CloseShift();
				}
			}
			break;
		case 'DepositingCash':
			if(Rezult['Error'] != '') popUp('.cr',Rezult['Error'],'warning');
            else LoadAlert('Успешно','Смена ККМ открыта',3000);
			break;
		case 'PaymentCash':
			if(Rezult['Error'] != '') popUp('.cr',Rezult['Error'],'warning');
			else{
				// Если выемка успешна, то закрываем смену.
                /**
                 * Чтобы скрит не зависал на сервере KKM
                 * Сделал отложенный запуск закрытия смены
                 */
                setTimeout(function(){
                    CloseShift();
                },2000);
			}
			break;
//		case '':
//			
//			break;
	}
}

// Пополнение кассы
function DepositingCash(NumDevice){
	OldIdCommand = KkmServer.DepositingCash(NumDevice, 10000.00, 'Вносильщик М.М.', '420514387085').Execute(ExecuteSuccess).IdCommand;
}

// Выемка наличности
function paymentCash(NumDevice,cash){
	OldIdCommand = KkmServer.PaymentCash(NumDevice, cash, 'Изымальщик И.И.', '420514387085').Execute(ExecuteSuccess).IdCommand;
}

// Печать чека
function RegisterCheck(TypeCheck, DataDocument, NumDevice, IsBarCode, Print) {
    
    /**
     * Если имя операции (продажа/возврат) не найдено
     * то печатаем слип чек
     */
    if(typeof DataDocument['name'] === 'undefined'){
        RegisterCheckSlip('0',DataDocument);return;
    }
    
    // Подготовка данных команды, параметры (TypeCheck = 0, NumDevice = 0, InnKkm = "", CashierName = "")
    var Data = KkmServer.GetDataCheck(TypeCheck, NumDevice, "", "", "");
    //***********************************************************************************************************
    // ПОЛЯ ПОИСКА УСТРОЙСТВА
    //***********************************************************************************************************
    // Номер устройства. Если 0 то первое не блокированное на сервере
    Data.NumDevice = NumDevice;
    // ИНН ККМ для поиска. Если "" то ККМ ищется только по NumDevice,
    // Если NumDevice = 0 а InnKkm заполнено то ККМ ищется только по InnKkm
    Data.InnKkm = "";
    //---------------------------------------------
    // Заводской номер ККМ для поиска. Если "" то ККМ ищется только по NumDevice,
    Data.KktNumber = "";
    // **********************************************************************************************************

    // Время (сек) ожидания выполнения команды.
    //Если За это время команда не выполнилась в статусе вернется результат "NotRun" или "Run"
    //Проверить результат еще не выполненной команды можно командой "GetRezult"
    //Если не указано или 0 - то значение по умолчанию 60 сек.
    // Поле не обязательно. Это поле можно указывать во всех командах
    Data.Timeout = 30;
    // Это фискальный или не фискальный чек
    Data.IsFiscalCheck = true;
    // Тип чека;
    // 0 – продажа;                             10 – покупка;
    // 1 – возврат продажи;                     11 - возврат покупки;
    // 8 - продажа только по ЕГАИС (обычный чек ККМ не печатается)
    // 9 - возврат продажи только по ЕГАИС (обычный чек ККМ не печатается)
    Data.TypeCheck = TypeCheck;
    // Не печатать чек на бумагу
    Data.NotPrint = false; //true,
    // Количество копий документа
    Data.NumberCopies = 0;
    // Продавец, тег ОФД 1021
    Data.CashierName = DataDocument['current_user'];
    // ИНН продавца тег ОФД 1203
    Data.CashierVATIN = "420514387085";
    // Телефон или е-Майл покупателя, тег ОФД 1008
    // Если чек не печатается (NotPrint = true) то указывать обязательно
    // Формат: Телефон +{Ц} Email {С}@{C}
    Data.ClientAddress = "";
    // Aдрес электронной почты отправителя чека тег ОФД 1117 (если задан при регистрации можно не указывать)
    // Формат: Email {С}@{C}
    Data.SenderEmail = "";
    // Система налогообложения (СНО) применяемая для чека
    // Если не указанно - система СНО настроенная в ККМ по умолчанию
    // 0: Общая ОСН
    // 1: Упрощенная УСН (Доход)
    // 2: Упрощенная УСН (Доход минус Расход)
    // 3: Единый налог на вмененный доход ЕНВД
    // 4: Единый сельскохозяйственный налог ЕСН
    // 5: Патентная система налогообложения
    // Комбинация разных СНО не возможна
    // Надо указывать если ККМ настроена на несколько систем СНО
    Data.TaxVariant = "";
    // Дополнительные произвольные реквизиты (не обязательно) пока только 1 строка
    // Data.AddAdditionalProps(true, true, "Дата транзакции", "10.11.2016 10:30");
    // Это только для тестов:
    //Data.ClientId = "e1e0c5dbb395acecda9e3ed86a798755b21a53de"; //"541a9db930c2e90670898943",
    // Это только для тестов: Получение ключа суб-лицензии : ВНИМАНИЕ: ключ суб-лицензии вы должны генерить у себя на сервере!!!!
    //Data.KeySubLicensing = "";
    // КПП организации, нужно только для ЕГАИС
    Data.KPP = "";
	
	// проверка раздела 1 на пустоту
	if(typeof DataDocument['section1']['header'] !== 'undefined'){
		
		Data.AddTextString(">#2#<"+DataDocument['section1']['header'], 1);
		Data.AddTextString(" "+"<#10#>");
		// Печать простой строки раздела 1
		for(key in DataDocument['section1']['text']['t1']){
			Data.AddTextString(DataDocument['section1']['text']['t1'][key]+"<#10#>");
			Data.AddTextString(" "+"<#10#>");
			
			var arr1 = (DataDocument['section1']['text']['t2'][key].split('|'));
			Data.AddTextString(arr1[0]+"<#0#>"+arr1[1]);
			Data.AddTextString(arr1[2]+"<#0#>"+arr1[3]);
			Data.AddTextString(arr1[4]+"<#0#>"+arr1[5]);
			Data.AddTextString(arr1[6]+"<#0#>"+arr1[7]);

			Data.AddTextString("------------"+"<#10#>");
			
		}
		var arr_p1 = DataDocument['section1']['p'].split('|');
			Data.AddTextString(arr_p1[0]+"<#0#>"+arr_p1[1]);
			Data.AddTextString(arr_p1[2]+"<#0#>"+arr_p1[3]);
			Data.AddTextString(arr_p1[4]+"<#0#>"+arr_p1[5]);

		Data.AddTextString("<<->>");
	}
	
	// проверка раздела 2 на пустоту
	if(typeof DataDocument['section2']['header'] !== 'undefined'){
		Data.AddTextString(">#2#<"+DataDocument['section2']['header'], 1);
		Data.AddTextString(" "+"<#10#>");

		i = 0;
		// Печать простой строки раздела 2
		for(key in DataDocument['section2']['text']){
			var arr2 = DataDocument['section2']['text'][key].split('|');
			Data.AddTextString(arr2[0]+"<#0#>"+arr2[1]);
			Data.AddTextString("------------"+"<#10#>");
			i++;
		}
		var arr_p2 = DataDocument['section2']['p'].split('|');
			Data.AddTextString(arr_p2[0]+"<#0#>"+arr_p2[1]);

		Data.AddTextString("<<->>");
	}
	
	// проверка раздела 3 на пустоту
	if(typeof DataDocument['section3']['header'] !== 'undefined'){
		Data.AddTextString(">#2#<"+DataDocument['section3']['header'], 1);
		Data.AddTextString(" "+"<#10#>");

		i = 0;
		// Печать простой строки раздела 3
		for(key in DataDocument['section3']['text']['t1']){
			Data.AddTextString(DataDocument['section3']['text']['t1'][key]+"<#10#>");
			Data.AddTextString(" "+"<#10#>");

			var arr3 = DataDocument['section3']['text']['t2'][key].split('|');
			Data.AddTextString(arr3[0]+"<#0#>"+arr3[1]);
			Data.AddTextString(arr3[2]+"<#0#>"+arr3[3]);
			Data.AddTextString(arr3[4]+"<#0#>"+arr3[5]);
			Data.AddTextString(arr3[6]+"<#0#>"+arr3[7]);
			Data.AddTextString("------------"+"<#10#>");
			i++;
		}

		var arr_p3 = DataDocument['section3']['p'].split('|');
			Data.AddTextString(arr_p3[0]+"<#0#>"+arr_p3[1]);
			Data.AddTextString(arr_p3[2]+"<#0#>"+arr_p3[3]);
			Data.AddTextString(arr_p3[4]+"<#0#>"+arr_p3[5]);

		Data.AddTextString("<<->>");
	}
	
	// проверка раздела 4 на пустоту
	if(typeof DataDocument['section4']['header'] !== 'undefined'){
		Data.AddTextString(">#2#<"+DataDocument['section4']['header'], 1);
		Data.AddTextString(" "+"<#10#>");

		// Печать простой строки раздела 4
		for(key in DataDocument['section4']['text']){
			var arr4 = DataDocument['section4']['text'][key].split('|');
			Data.AddTextString(arr4[0]+"<#0#>"+arr4[1]);
			Data.AddTextString("------------"+"<#10#>");
		}
		var arr_p4 = DataDocument['section4']['p'].split('|');
			Data.AddTextString(arr_p4[0]+"<#0#>"+arr_p4[1]);

		Data.AddTextString("<<->>");
	}
    
    // Добавление печати фискальной строки
    var DataStr = Data.AddRegisterString(
        // НаименованиеТовара(64 символа)
        DataDocument['name'],
        // Количество (3 знака после запятой)
		// всегда будет 1
        1,
        
		// ЦенаБезСкидки (2 знака после запятой)
		/*
		если Тип чека 0 (к оплате), то сюда сумма к оплате 
		если Тип чека 1 (к возврату), то сюда (наличка+банковская карта)
		*/
		// из расчета документа (наличка+банковская карта)
        DataDocument['price_without_discount'],
        
		// СуммаСтроки (2 знака после запятой)
		// ЦенаБезСкидки
        DataDocument['price_without_discount'],
		
        // СтавкаНДС(0(НДС 0%), 10(НДС 10%), 18(НДС 18%), -1(НДС не облагается), 118 (НДС 18/118), 110 (НДС 10/110))
        -1,
        
		// Отдел
        0,
        
		// Код товара EAN13 - не обязательно
        "",
        // Признак способа расчета. тег ОФД 1214. Для ФФД.1.05 и выше обязательное поле
        // 1: "ПРЕДОПЛАТА 100% (Полная предварительная оплата до момента передачи предмета расчета)"
        // 2: "ПРЕДОПЛАТА (Частичная предварительная оплата до момента передачи предмета расчета)"
        // 3: "АВАНС"
        // 4: "ПОЛНЫЙ РАСЧЕТ (Полная оплата, в том числе с учетом аванса в момент передачи предмета расчета)"
        // 5: "ЧАСТИЧНЫЙ РАСЧЕТ И КРЕДИТ (Частичная оплата предмета расчета в момент его передачи с последующей оплатой в кредит )"
        // 6: "ПЕРЕДАЧА В КРЕДИТ (Передача предмета расчета без его оплаты в момент его передачи с последующей оплатой в кредит)"
        // 7: "ОПЛАТА КРЕДИТА (Оплата предмета расчета после его передачи с оплатой в кредит )"
        
		4,// ставим 4 - всегда
        // Признак предмета расчета. тег ОФД 1212. Для ФФД.1.05 и выше обязательное поле
        // 1: "ТОВАР (наименование и иные сведения, описывающие товар)"
        // 2: "ПОДАКЦИЗНЫЙ ТОВАР (наименование и иные сведения, описывающие товар)"
        // 3: "РАБОТА (наименование и иные сведения, описывающие работу)"
        // 4: "УСЛУГА (наименование и иные сведения, описывающие услугу)"
        // 5: "СТАВКА АЗАРТНОЙ ИГРЫ (при осуществлении деятельности по проведению азартных игр)"
        // 6: "ВЫИГРЫШ АЗАРТНОЙ ИГРЫ (при осуществлении деятельности по проведению азартных игр)"
        // 7: "ЛОТЕРЕЙНЫЙ БИЛЕТ (при осуществлении деятельности по проведению лотерей)"
        // 8: "ВЫИГРЫШ ЛОТЕРЕИ (при осуществлении деятельности по проведению лотерей)"
        // 9: "ПРЕДОСТАВЛЕНИЕ РИД (предоставлении прав на использование результатов интеллектуальной деятельности или средств индивидуализации)"
        // 10: "ПЛАТЕЖ (аванс, задаток, предоплата, кредит, взнос в счет оплаты, пени, штраф, вознаграждение, бонус и иной аналогичный предмет расчета)"
        // 11: "АГЕНТСКОЕ ВОЗНАГРАЖДЕНИЕ (вознаграждение (банковского)платежного агента/субагента, комиссионера, поверенного или иным агентом)"
        // 12: "СОСТАВНОЙ ПРЕДМЕТ РАСЧЕТА (предмет расчета, состоящем из предметов, каждому из которых может быть присвоено вышестоящее значение"
        // 13: "ИНОЙ ПРЕДМЕТ РАСЧЕТА (предмет расчета, не относящемуся к предметам расчета, которым может быть присвоено вышестоящее значение"
        
		1,// ставим 1 - всегда
        // Код товарной номенклатуры Тег ОФД 1162 (Новый классификатор товаров и услуг. Пока не утвержден налоговой. Пока не указывать)
        // 4 символа – код справочника; последующие 8 символовт – код группы товаров; последние 20 символов – код идентификации товара
        "",
        // Единица измерения предмета расчета. Можно не указывать
        ""
    );

    // Наличная оплата (2 знака после запятой)
	// эти данные берем из расчета документа
	// тут будет наличка
    Data.Cash = DataDocument['cash'];
//    Data.Cash = 800;
	
    // Сумма электронной оплаты (2 знака после запятой)
	// эти данные берем из расчета документа
	// тут будет банковская карта
    Data.ElectronicPayment = DataDocument['payment_by_credit_card'];
//    Data.ElectronicPayment = 0.05;
    
	// Сумма из предоплаты (зачетом аванса) (2 знака после запятой)
    Data.AdvancePayment = 0;
    // Сумма постоплатой(в кредит) (2 знака после запятой)
    Data.Credit = 0;
    // Сумма оплаты встречным предоставлением (сертификаты, др. мат.ценности) (2 знака после запятой)
    Data.CashProvision = 0;
    
    // Вызов команды
    OldIdCommand = Data.Execute(ExecuteSuccess).IdCommand;

    // Возвращается JSON:
    //{
    //    "CheckNumber": 1,    // Номер документа
    //    "SessionNumber": 23, // Номер смены
    //    "URL": "https://ofd.ru/rec/7708806062/0000000006018032/9999078900002287/106/4160536402",
    //    "QRCode": "t=20170904T140900&s=0.01&fn=9999078900002287&i=106&fp=4160536402&n=1",
    //    "Command": "RegisterCheck",
    //    "Error": "",  // Текст ошибки если была - обязательно показать пользователю - по содержанию ошибки можно в 90% случаях понять как ее устранять
    //    "Status": 0   // Ok = 0, Run(Запущено на выполнение) = 1, Error = 2, NotFound(устройство не найдено) = 3, NotRun(Ждет очереди) = 4, AlreadyDone(Выполнено ранее) = 5, ErrorInEGAIS(Ошибка ЕГАИС) = 6
    //}
}

// Печать слип чека
function RegisterCheckSlip(TypeCheck, DataDocument, NumDevice, IsBarCode, Print) {
    
    // Подготовка данных команды, параметры (TypeCheck = 0, NumDevice = 0, InnKkm = "", CashierName = "")
    var Data = KkmServer.GetDataCheck(TypeCheck, NumDevice, "", "", "");
    //***********************************************************************************************************
    // ПОЛЯ ПОИСКА УСТРОЙСТВА
    //***********************************************************************************************************
    // Номер устройства. Если 0 то первое не блокированное на сервере
    Data.NumDevice = NumDevice;
    
    // Время (сек) ожидания выполнения команды.
    //Если За это время команда не выполнилась в статусе вернется результат "NotRun" или "Run"
    //Проверить результат еще не выполненной команды можно командой "GetRezult"
    //Если не указано или 0 - то значение по умолчанию 60 сек.
    // Поле не обязательно. Это поле можно указывать во всех командах
    Data.Timeout = 30;
    // Это фискальный или не фискальный чек
    Data.IsFiscalCheck = false;
    // Не печатать чек на бумагу
    Data.NotPrint = false; //true,
    // Количество копий документа
    Data.NumberCopies = 0;
    
	// проверка раздела 1 на пустоту
	if(typeof DataDocument['section1']['header'] !== 'undefined'){
		
		Data.AddTextString(">#2#<"+DataDocument['section1']['header'], 1);
		Data.AddTextString(" "+"<#10#>");
		// Печать простой строки раздела 1
		for(key in DataDocument['section1']['text']['t1']){
			Data.AddTextString(DataDocument['section1']['text']['t1'][key]+"<#10#>");
			Data.AddTextString(" "+"<#10#>");
			
			var arr1 = (DataDocument['section1']['text']['t2'][key].split('|'));
			Data.AddTextString(arr1[0]+"<#0#>"+arr1[1]);
			Data.AddTextString(arr1[2]+"<#0#>"+arr1[3]);
			Data.AddTextString(arr1[4]+"<#0#>"+arr1[5]);
			Data.AddTextString(arr1[6]+"<#0#>"+arr1[7]);

			Data.AddTextString("------------"+"<#10#>");
			
		}
		var arr_p1 = DataDocument['section1']['p'].split('|');
			Data.AddTextString(arr_p1[0]+"<#0#>"+arr_p1[1]);
			Data.AddTextString(arr_p1[2]+"<#0#>"+arr_p1[3]);
			Data.AddTextString(arr_p1[4]+"<#0#>"+arr_p1[5]);

		Data.AddTextString("<<->>");
	}
	
	// проверка раздела 2 на пустоту
	if(typeof DataDocument['section2']['header'] !== 'undefined'){
		Data.AddTextString(">#2#<"+DataDocument['section2']['header'], 1);
		Data.AddTextString(" "+"<#10#>");

		i = 0;
		// Печать простой строки раздела 2
		for(key in DataDocument['section2']['text']){
			var arr2 = DataDocument['section2']['text'][key].split('|');
			Data.AddTextString(arr2[0]+"<#0#>"+arr2[1]);
			Data.AddTextString("------------"+"<#10#>");
			i++;
		}
		var arr_p2 = DataDocument['section2']['p'].split('|');
			Data.AddTextString(arr_p2[0]+"<#0#>"+arr_p2[1]);

		Data.AddTextString("<<->>");
	}
	
	// проверка раздела 3 на пустоту
	if(typeof DataDocument['section3']['header'] !== 'undefined'){
		Data.AddTextString(">#2#<"+DataDocument['section3']['header'], 1);
		Data.AddTextString(" "+"<#10#>");

		i = 0;
		// Печать простой строки раздела 3
		for(key in DataDocument['section3']['text']['t1']){
			Data.AddTextString(DataDocument['section3']['text']['t1'][key]+"<#10#>");
			Data.AddTextString(" "+"<#10#>");

			var arr3 = DataDocument['section3']['text']['t2'][key].split('|');
			Data.AddTextString(arr3[0]+"<#0#>"+arr3[1]);
			Data.AddTextString(arr3[2]+"<#0#>"+arr3[3]);
			Data.AddTextString(arr3[4]+"<#0#>"+arr3[5]);
			Data.AddTextString(arr3[6]+"<#0#>"+arr3[7]);
			Data.AddTextString("------------"+"<#10#>");
			i++;
		}

		var arr_p3 = DataDocument['section3']['p'].split('|');
			Data.AddTextString(arr_p3[0]+"<#0#>"+arr_p3[1]);
			Data.AddTextString(arr_p3[2]+"<#0#>"+arr_p3[3]);
			Data.AddTextString(arr_p3[4]+"<#0#>"+arr_p3[5]);

		Data.AddTextString("<<->>");
	}
	
	// проверка раздела 4 на пустоту
	if(typeof DataDocument['section4']['header'] !== 'undefined'){
		Data.AddTextString(">#2#<"+DataDocument['section4']['header'], 1);
		Data.AddTextString(" "+"<#10#>");

		// Печать простой строки раздела 4
		for(key in DataDocument['section4']['text']){
			var arr4 = DataDocument['section4']['text'][key].split('|');
			Data.AddTextString(arr4[0]+"<#0#>"+arr4[1]);
			Data.AddTextString("------------"+"<#10#>");
		}
		var arr_p4 = DataDocument['section4']['p'].split('|');
			Data.AddTextString(arr_p4[0]+"<#0#>"+arr_p4[1]);

		Data.AddTextString("<<->>");
	}
    
    // Вызов команды
    OldIdCommand = Data.Execute(ExecuteSuccessSlip).IdCommand;
}

// Функция обработки результата RegisterCheckSlip
function ExecuteSuccessSlip(Rezult) {
    /**
     * Регистрация чеков происходит только на странице "Товарный чек"
     * по этому используем только селектор ".sales-reciept"
     * Чтобы нам брать только то окно, которое на нужной странице
     * и если там будут какие то ошибки, то они будут показаны.
     */
    if(Rezult['Error'] != '') popUp('.sales-reciept',Rezult['Error'],'warning');
    else LoadAlert('Успешно','Слип чек завершен!',5000);
}

// меняем тип кнопки продажа/возврат
/**
 *  Функция не понадобилась
 *  дата данные кнопки меняются в functions calculationDocument()
 *  но пока оставил функцию, на всякий случай
 */
function changeType(obj){
//	var $this = $(obj),
//		printCheck = $('.but[data-type=RegisterCheck]');
//	
//	if(printCheck.attr('data-type-check') == '0'){
//		printCheck.attr('data-type-check','1');
//		$this.html('Возврат');
//	}else{
//		printCheck.attr('data-type-check','0');
//		$this.html('Продажа');
//	}
}

/**
 * Страница "Кассовый отчет"
 * Открыть смену
 */
function OpenShift(NumDevice, cashier) {
    
	// закрываем окно об ошибках
	cea();
    
    OldIdCommand = KkmServer.OpenShift(NumDevice, cashier, '420514387085').Execute(ExecuteSuccess).IdCommand;

    // Возвращается JSON:
    //{
    //    "CheckNumber": 1,    // Номер документа
    //    "SessionNumber": 23, // Номер смены
    //    "QRCode": "t=20170904T141100&fn=9999078900002287&i=108&fp=605445600",
    //    "Command": "OpenShift",
    //    "Error": "",  // Текст ошибки если была - обязательно показать пользователю - по содержанию ошибки можно в 90% случаях понять как ее устранять
    //    "Status": 0   // Ok = 0, Run(Запущено на выполнение) = 1, Error = 2, NotFound(устройство не найдено) = 3, NotRun = 4
    //}

}

/**
 * Страница "Кассовый отчет"
 * Закрыть смену
 */
function CloseShift(NumDevice) {
	var cashier = $('.cr .close-shift').attr('data-user');
	// закрываем окно об ошибках
	cea();
    
    OldIdCommand = KkmServer.CloseShift('0', cashier, '420514387085').Execute(ExecuteSuccess).IdCommand;

    // Возвращается JSON:
    //{
    //    "CheckNumber": 1,    // Номер документа
    //    "SessionNumber": 23, // Номер смены
    //    "QRCode": "t=20170904T141100&fn=9999078900002287&i=108&fp=605445600",
    //    "Command": "CloseShift",
    //    "Error": "",  // Текст ошибки если была - обязательно показать пользователю - по содержанию ошибки можно в 90% случаях понять как ее устранять
    //    "Status": 0   // Ok = 0, Run(Запущено на выполнение) = 1, Error = 2, NotFound(устройство не найдено) = 3, NotRun = 4
    //}

}

// Получить текущее состояние ККТ.
function GetDataKKT(NumDevice,type) {
	
	// закрываем окно об ошибках
	cea();
    
    OldIdCommand = KkmServer.GetDataKKT(NumDevice).Execute(ExecuteSuccess).IdCommand;

    // Возвращается JSON:
    //{
    //    "CheckNumber": 8,     // Номер последнего документа
    //    "SessionNumber": 24,  // Номер текущей смены
    //    "LineLength": 48,     // Ширина строки
    //    "URL": "",
    //    "Info": {
    //        "UrlServerOfd": "connect.ofd-ya.ru",
    //        "PortServerOfd": "7790",
    //        "NameOFD": "ООО \"Ярус\" (\"ОФД-Я\")",
    //        "UrlOfd": "",
    //        "InnOfd": "504404744207",
    //        "NameOrganization": "ООО \"Рога и Копыта\"",
    //        "TaxVariant": "0,3,5",                                // Описание смотри в команде KkmRegOfd
    //        "AddressSettle": "109097, Москва, ул. Ильинка, 9",    // Адрес установки
    //        "EncryptionMode": false,
    //        "OfflineMode": true,
    //        "AutomaticMode": false,
    //        "InternetMode": false,
    //        "BSOMode": false,
    //        "ServiceMode": true,
    //        "InnOrganization": "504404744207",
    //        "KktNumber": "0149060006000651",                      // Заводской номер
    //        "FnNumber": "99078900002287",                         // Номер ФН
    //        "RegNumber": "0149060006035849",                      // Регистрационный номер ККТ (из налоговой)
    //        "Command": "",
    //        "FN_IsFiscal": true,
    //        "OFD_Error": "",
    //        "OFD_NumErrorDoc": 32,
    //        "OFD_DateErrorDoc": "2017-01-13T14:56:00",
    //        "FN_DateEnd": "2018-02-01T00:00:00",
    //        "SessionState": 2                                     // Статус сессии 1-Закрыта, 2-Открыта, 3-Открыта, но закончилась (3 статус на старых ККМ может быть не опознан)
    //    },
    //    "Command": "GetDataKKT",
    //    "Error": "",  // Текст ошибки если была - обязательно показать пользователю - по содержанию ошибки можно в 90% случаях понять как ее устранять
    //    "Status": 0   // Ok = 0, Run(Запущено на выполнение) = 1, Error = 2, NotFound(устройство не найдено) = 3, NotRun = 4
    //}
 
}

/**
 * Кнопка "Отчет ККМ"
 * ==================
 * Получить текущее состояние ККТ
 */
function balanceCheck(NumDevice) {
	// закрываем окно об ошибках
	cea();
            
    OldIdCommand = KkmServer.GetDataKKT(NumDevice).Execute(ExecuteSuccessKkmReport).IdCommand;

}// function balanceCheck()

function ExecuteSuccessKkmReport(Rezult) {
	if(Rezult['Info']['SessionState'] == '1')
		LoadAlert('Внимание','Нет открытой смены',5000,'warning');
	
	popUp('.cr','Баланс кассы: '+Rezult['Info']['BalanceCash']+'р.');
    
}

/**
 * Кнопка "Чек ККМ"
 * ==================
 * Получить текущее состояние ККТ
 * и если смена открыта - печатать чек
 * если смена закрыта - выводим сообщение
 * и далее ни чего не делаем
 */
function checkStatusKkm(obj){
    
    $(obj).prop('disabled',true);
    var NumDevice = '0';
	
	// закрываем окно об ошибках
	cea();
    OldIdCommand = KkmServer.GetDataKKT(NumDevice).Execute(ExecuteSuccessCheckStatusKkm).IdCommand;

}// function balanceCheck()

function ExecuteSuccessCheckStatusKkm(Rezult) {
    /**
     * Если смена закрыта, то атрибут "data-shift" кнопки "Чек ККМ"
     * ставим 0
     */
	if(Rezult['Info']['SessionState'] == '1'){
		LoadAlert('Внимание','Нет открытой смены',5000,'warning');
        /**
         * Так как кнопка сразу же становится не активной
         * то после вывода сообщения о том, что нет открытой смены
         * делаем кнопку опять активной (prop - disabled:false)
         */
        $('.sales-reciept .kkm').attr('data-shift','0').prop('disabled',false);
        
    // Иначе атрибут "data-shift" кнопи "Чек ККМ" ставим 1
    }else{
        $('.sales-reciept .kkm').attr('data-shift','1');
        // И делаем запуск регистрации чека ККМ
        setTimeout(function(){
            kkmRegisterCheck();
        },2000);
    }
    
}

/**
 * Страница "Товарный чек"
 * =======================
 * Кнопка "Чек ККМ"
 */
function kkmRegisterCheck(){
    
    var $this = $('.sales-reciept .kkm'),
        tch = $this.attr('data-type-check');
    // Если тип кнопки пуст - то ничего не делаем
    if(tch == '') return;

    /**
     * Далее проверяем атрибут "data-shift"
     * ====================================
     * Если смена закрыта, то ни чего не делаем.
     * Все необходимые предупреждающие сообщения
     * и изменение атрибута "data-shift"
     * произойдут в функции checkStatusKkm()
     */
    if($this.attr('data-shift') == '0') return;

    var DataDocument = {},
        form = $('.sales-reciept'),
        p1 = form.find('.document1 .tfoot1'),
        p2 = form.find('.document1 .tfoot2'),
        p3 = form.find('.document1 .tfoot3'),
        p4 = form.find('.document1 .tfoot4'),
        pmbk = form.find('select[name=payment_method_bank_card]'),
        sko = form.find('.sko input'),
        kvn = form.find('.kvn input'),
        kvnbk = form.find('.kvnbk input'),
        i = 0;

    DataDocument['current_user'] = $this.attr('data-current-user');

    DataDocument['section1'] = {};
    DataDocument['section2'] = {};
    DataDocument['section3'] = {};
    DataDocument['section4'] = {};
    DataDocument['section1']['text'] = {};
    DataDocument['section2']['text'] = {};
    DataDocument['section3']['text'] = {};
    DataDocument['section4']['text'] = {};
    DataDocument['section1']['text']['t1'] = {};
    DataDocument['section1']['text']['t2'] = {};
    DataDocument['section3']['text']['t1'] = {};
    DataDocument['section3']['text']['t2'] = {};
    DataDocument['section1']['p'] = {};
    DataDocument['section2']['p'] = {};
    DataDocument['section3']['p'] = {};
    DataDocument['section4']['p'] = {};

    if(tch == '0'){

        DataDocument['name'] = 'Оплата за товар';

        /**
         * Если тип чека 0 (оплата),
         * то в общую сумму пишем "сумма к оплате"
         */
        DataDocument['price_without_discount'] = Number(sko.val());

        /**
         * Проверяем тип оплаты
         * ====================
         * Если тип оплаты не пуст
         */
        if(pmbk.val() != '0'){
            switch(pmbk.val()){
                // Если тип оплаты "Наличные"
                case '1':
                    DataDocument['cash'] = DataDocument['price_without_discount'];
                    DataDocument['payment_by_credit_card'] = 0;
                    break;
                    // Если тип оплаты "Банковская карта"
                case '2':
                    DataDocument['payment_by_credit_card'] = 
                        DataDocument['price_without_discount'];
                    DataDocument['cash'] = 0;
                break;
            }
        }

    }else if(tch == '1'){

        DataDocument['name'] = 'Возврат оплаты';

        /**
         * Если тип чека 1 (возврат),
         * то в общую сумму пишем "наличка+банковская карта"
         * и по отдельности присваиваем значения
         * каждое по своему значению возврат наличкой и на банковскую карту
         */
        DataDocument['price_without_discount'] = ( Number(kvn.val()) + Number(kvnbk.val()) );
        // сумма возврата наличкой
        DataDocument['cash'] = Number(kvn.val());
        // сумма возврата на банковскую карту
        DataDocument['payment_by_credit_card'] = Number(kvnbk.val());
    }

    /**
     * Сбор информации для текстового отображения в чеке ККМ
     */
    // Сбор строк раздела 1
//		if(form.find('.document1 tbody tr').attr('class') != 'empty'){
    if(typeof form.find('.empty1').attr('class') === 'undefined'){

        DataDocument['section1']['header'] = 'Продажа';

        // перебор строк раздела
        form.find('.table.document1 tr.section1').each(function(){
            DataDocument['section1']['text']['t1'][i] = $(this).find('[data-info-kkm]').attr('data-info-kkm');
            DataDocument['section1']['text']['t2'][i] = 
                'Цена:|'+$(this).find('.retail-price1').html()+' руб.|'+
                'Количество:|'+$(this).find('.quantity1').html()+' шт.|'+
                'Скидка:|'+$(this).find('.total-discounts1').html()+' руб.|'+
                'Сумма:|'+$(this).find('.sales-amount1').html()+' руб.';
            i++;
        });
        // Сбор подитога раздела 1
        DataDocument['section1']['p'] = 
            'Итого цена:|'+p1.find('.p_sbs1 b').html()+' руб.|'+
            'Итого скидка:|'+p1.find('.p_is1 b').html()+' руб.|'+
            'Итого сумма:|'+p1.find('.p_sp1 b').html()+' руб.';
    }

    // Сбор строк раздела 2
    if(typeof form.find('.empty2').attr('class') === 'undefined'){

        DataDocument['section2']['header'] = 'Продажа сертификата';

        i = 0;
        // перебор строк раздела
        form.find('.table.document1 tr.section2').each(function(){
            DataDocument['section2']['text'][i] = 
                'Сертификат на сумму:|'+$(this).find('.nominal').html()+' руб.';
            i++;
        });
        // Сбор подитога раздела 2
        DataDocument['section2']['p'] = 
            'Итого сумма:|'+p2.find('.p_sp2 b').html()+' руб.';
    }


    // Сбор строк раздела 3
    if(typeof form.find('.empty3').attr('class') === 'undefined'){

        DataDocument['section3']['header'] = 'Возврат';

        i = 0;
        // перебор строк раздела
        form.find('.table.document1 tr.section3').each(function(){
            DataDocument['section3']['text']['t1'][i] = $(this).find('[data-info-kkm]').attr('data-info-kkm');
            DataDocument['section3']['text']['t2'][i] = 
                'Цена:|'+$(this).find('.retail-price3').html()+' руб.|'+
                'Количество:|'+$(this).find('.quantity3 span').html()+' шт.|'+
                'Скидка:|'+$(this).find('.total-discounts3').html()+' руб.|'+
                'Сумма:|'+$(this).find('.sales-amount3').html()+' руб.';
            i++;
        });
        // Сбор подитога раздела 3
        DataDocument['section3']['p'] = 
            'Итого цена:|'+p3.find('.p_sbs3 b').html()+' руб.|'+
            'Итого скидка:|'+p3.find('.p_is3 b').html()+' руб.|'+
            'Итого сумма:|'+p3.find('.p-sp32 b').html()+' руб.';
    }


    // Сбор строк раздела 4
    if(typeof form.find('.empty4').attr('class') === 'undefined'){

        DataDocument['section4']['header'] = 'Отоваривание сертификата';

        i = 0;
        // перебор строк раздела
        form.find('.table.document1 tr.section4').each(function(){
            DataDocument['section4']['text'][i] = 
                'Сертификат на сумму:|'+$(this).find('.nominal').html()+' руб.';
            i++;
        });
        // Сбор подитога раздела 4
        DataDocument['section4']['p'] = 
            'Итого сумма отоваривания:|'+p4.find('.p-sps4 b').html()+' руб.';
    }



//    console.log(JSON.stringify(DataDocument));return;

    switch($this.attr('data-type')){
        // печать чека
        case 'RegisterCheck':
            // tch - тип чека
            RegisterCheck(tch,DataDocument);
            break;
    }
}





































// ===============================================================
// ===============================================================
// ===============================================================
// ===============================================================



// Чек корекции
function RegisterCorrectionCheck(NumDevice, TypeCheck) {

    // Подготовка данных команды, параметры (TypeCheck = 0, NumDevice = 0, InnKkm = "", CashierName = "")
    var Data = KkmServer.GetDataCheck(TypeCheck, NumDevice, "", "Kазакова Н.А.", "420514387085");

    //***********************************************************************************************************
    // ПОЛЯ ПОИСКА УСТРОЙСТВА
    //***********************************************************************************************************
    // Номер устройства. Если 0 то первое не блокированное на сервере
    Data.NumDevice = NumDevice;
    // ИНН ККМ для поиска. Если "" то ККМ ищется только по NumDevice,
    // Если NumDevice = 0 а InnKkm заполнено то ККМ ищется только по InnKkm
    Data.InnKkm = "";
    //---------------------------------------------
    // Заводской номер ККМ для поиска. Если "" то ККМ ищется только по NumDevice,
    Data.KktNumber = "";
    // **********************************************************************************************************

    // Время (сек) ожидания выполнения команды.
    //Если За это время команда не выполнилась в статусе вернется результат "NotRun" или "Run"
    //Проверить результат еще не выполненной команды можно командой "GetRezult"
    //Если не указано или 0 - то значение по умолчанию 60 сек.
    // Поле не обязательно. Это поле можно указывать во всех командах
    Data.Timeout = 30;
    // Это фискальный или не фискальный чек
    Data.IsFiscalCheck = true;
    // Тип чека;
    // Для новых ККМ:
    // 2 – корректировка приход;
    // 12 – корректировка расход;
    Data.TypeCheck = TypeCheck;
    // Не печатать чек на бумагу
    Data.NotPrint = false; //true,
    // Количество копий документа
    Data.NumberCopies = 0;
    // Продавец, тег ОФД 1021
    Data.CashierName = "Kазакова Н.А.";
    // ИНН продавца тег ОФД 1203
    Data.CashierVATIN = "430601071197";
    // Система налогообложения (СНО) применяемая для чека
    // Если не указанно - система СНО настроенная в ККМ по умолчанию
    // 0: Общая ОСН
    // 1: Упрощенная УСН (Доход)
    // 2: Упрощенная УСН (Доход минус Расход)
    // 3: Единый налог на вмененный доход ЕНВД
    // 4: Единый сельскохозяйственный налог ЕСН
    // 5: Патентная система налогообложения
    // Комбинация разных СНО не возможна
    // Надо указывать если ККМ настроена на несколько систем СНО
    Data.TaxVariant = "";
    // Это только для тестов:
    //Data.ClientId = "e1e0c5dbb395acecda9e3ed86a798755b21a53de"; //"541a9db930c2e90670898943",
    // Это только для тестов: Получение ключа суб-лицензии : ВНИМАНИЕ: ключ суб-лицензии вы должны генерить у себя на сервере!!!!
    //Data.KeySubLicensing = "";

    //При вставке в текст символов >#10#< строка при печати выровнеется по центру, где 10 - это на сколько меньше станет строка ККТ
    Data.AddTextString("Это чек корректровки. Делается только по предписанию налоговой или глав.буха.", 2);

    Data.AddCorrectionDataCheck(
        // Наименование основания для коррекции Тег ОФД 1177
        "Предписание налоговой",
        // Дата документа основания для коррекции Тег ОФД 1178
        '2017-06-21T15:30:45',
        // Номер документа основания для коррекции Тег ОФД 1179
        "MOS-4516",
        // Сумма коррекции расчета, игнорируется для ККТ ФФД 1.0
        1.21,
        // Сумма коррекции НДС чека по ставке Без НДС, игнорируется для ККТ ФФД 1.0 (2 знака после запятой)
        0.01,
        // Сумма коррекции НДС чека по ставке 18%, игнорируется для ККТ ФФД 1.0 (2 знака после запятой)
        0.02,
        // Сумма коррекции НДС чека по ставке 10%, игнорируется для ККТ ФФД 1.0 (2 знака после запятой)
        0.03,
        // Сумма коррекции НДС чека по ставке 0%, игнорируется для ККТ ФФД 1.0 (2 знака после запятой)
        0.04,
        // Сумма коррекции НДС чека по ставке 18/118%, игнорируется для ККТ ФФД 1.0 (2 знака после запятой)
        0.05,
        // Сумма коррекции НДС чека по ставке 10/110%, игнорируется для ККТ ФФД 1.0 (2 знака после запятой)
        0.06
    );

    // Сумма коррекции Наличной оплаты (2 знака после запятой)
    Data.Cash = 1.11;
    // Сумма коррекции электронной оплаты (2 знака после запятой)
    Data.ElectronicPayment = 0.01;
    // Сумма коррекции постоплатой(в кредит) (2 знака после запятой)
    Data.AdvancePayment = 0.02;
    // Сумма коррекции постоплатой(в кредит) (2 знака после запятой)
    Data.Credit = 0.03;
    // Сумма коррекции встречным предоставлением (2 знака после запятой)
    Data.CashProvision = 0.04;

    // Вызов команды
    OldIdCommand = Data.Execute(ExecuteSuccess).IdCommand;

    // Возвращается JSON:
    //{
    //    "CheckNumber": 1,    // Номер документа
    //    "SessionNumber": 23, // Номер смены
    //    "QRCode": "t=20170115T155100&s=0.01&fn=99078900002287&i=120&fp=2216493490&n=2", // URL проверки чека, где: t-дата-время, s-сумма документа, fn-номер ФН, i-номер документа, fp-фискальная подпись, n-тип документа
    //    "Command": "RegisterCheck",
    //    "Error": "",  // Текст ошибки если была - обязательно показать пользователю - по содержанию ошибки можно в 90% случаях понять как ее устранять
    //    "Status": 0   // Ok = 0, Run(Запущено на выполнение) = 1, Error = 2, NotFound(устройство не найдено) = 3, NotRun(Ждет очереди) = 4, AlreadyDone(Выполнено ранее) = 5, ErrorInEGAIS(Ошибка ЕГАИС) = 6
    //}
}











