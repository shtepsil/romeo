$(function(){

    $('.ddfs .form-import').on('submit', function(e){
        e.preventDefault();
		
		// закрываем окно об ошибках
        cea();
		
        var $this = $(this),
			button = $this.find('#submit'),
			badge = $('.ddfs .badge'),
			res = $('.res'),
			w = $('.w-list-files'),
			tbody = w.find('tbody'),
            progress = $('.'+$this.attr('data-type')+' .ddfs .progress-percent'),
            live = 5000,
            dataType = $this.attr('data-type'),
            load = $this.find('img'),
            load2 = $this.find('.over-view'),
            label = $this.find('.label-info'),
            formData = new FormData($this.get(0));

        // проверяем, какая форма к нам пришла
		switch(dataType){
			case 'certificates':
				var $image = $('#image-certificates');
			   	break;
			case 'discount-cards':
				var $image = $('#image-discount-cards');
			   	break;
			case 'automatic-discount':
				var $image = $('#image-automatic-discount');
			   	break;
	   	}

        formData['data-type'] = dataType;

        if($image.val() == ''){
            LoadAlert('Внимание','Выберите файл',5000,'warning');
            return;
        }

        $.ajax({
            url: $this.attr('action'),
            type: $this.attr('method'),
            contentType: false,
            processData: false,
            data: formData,
            dataType: 'json',
			beforeSend:function(){
				load.fadeIn(100);
				load2.fadeIn(100);
				button.prop('disabled',true);
			},
            xhr: function(){
                var xhr = $.ajaxSettings.xhr(); // получаем объект XMLHttpRequest
                xhr.upload.addEventListener('progress', function(evt){ // добавляем обработчик события progress (onprogress)
                    if (evt.lengthComputable) { // если известно количество байт
                        // высчитываем процент загруженного
                        var percentComplete = Math.ceil(evt.loaded / evt.total * 100);
                        // устанавливаем значение в атрибут value тега progress
                        // и это же значение альтернативным текстом для браузеров, не поддерживающих &lt;progress&gt;
                        progress.html(percentComplete + '%');
                    }
                }, false);
                return xhr;
            }
        }).done(function(data){
//            res.html('done<br>'+JSON.stringify(data));
            if(data.status == 200){
                LoadAlert(data.header,data.message,live,data.type_message);
				tbody.html(data.files_list);
				badge.html(data.count);
				if(data.duplicates)
					popUp('.ddfs',data.duplicates,'warning');
				if(data.not_existing)
					popUp('.ddfs',data.not_existing,'warning');
            }else{
                LoadAlert(data.header,data.message,5000,data.type_message);
            }
			load.fadeOut(100);
			load2.fadeOut(100);
			tbody.html(data.files_list);
			button.prop('disabled','');
        }).fail(function(data){
			res.html('fail<br>'+JSON.stringify(data));
            LoadAlert(data.header,'Не известная ошибка.',5000,'error');
			load.fadeOut(100);
			load2.fadeOut(100);
			button.prop('disabled','');
        });
    });

});// JQuery