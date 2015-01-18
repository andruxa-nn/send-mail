var SendMail = {
    // Очистка от автоматизированных адресов
    clearTrash : function() {
        if (confirm('Подтверждаете очистку?')) {
            var trashIdArr = [], trashPatterns = ['noreply', 'no-reply', 'nobody',
            'no-body', 'subscribe', 'unsubscribe', 'dymovoi.ru', '.yandex.ru',
            '.mail.ru', '.gmail.com', '.mcsv.net', '.hh.ru', '.usndr.com',
            '.atmprk.com', '.trade.su', '.nicmail.ru', '.hq2.rep', '.nsmail.ru',
            'ofsys.com', '-NO-REPLY-', '.superjob.ru', '.qip.ru', 'autoreply',
            '.masterhost.ru', '.nichost.ru', '.reg.ru', '.sweb.ru', 'MSG_ID_',
            '.timeweb.ru', 'apache', 'daemon','.intranet.ru', '.rabota.ru',
            '.job.ru','.google.com', '.rambler.ru', '.apple.com'];
            $.each($('tr'), function(index, element) {
                thisElem = $(element).find('td:eq(1)');
                $.each(trashPatterns, function(index, element) {
                    if (thisElem.text().indexOf(element) != -1) {
                        trashIdArr.push(thisElem.prev().text());
                    }
                });
            });
            (function delTrash() {
                $.ajax({
                    url: '/sendMail/http.php?do=delEmail&id=' + trashIdArr[0],
                    type: 'POST',
                    beforeSend: function() {
                        if (!trashIdArr.length) return false;
                    },
                    success: function() {
                        $('td:contains("' + trashIdArr[0] + '")').parent()
                        .fadeOut('fast', function() {
                            trashIdArr.shift();
                            delTrash();
                        });
                    }
                });
            })();
        }
        return false;
    },
    // Редактирование e-mail адреса
    editItem : function(self) {
        var name = $(self).parent().siblings(':eq(1)');
        if (newName = prompt(name.text())) {
            $.ajax({
                url: '/sendMail/',
                type: 'POST',
                data: {
                    editEmail: $(self).attr('value'),
                    newName: newName
                },
                success: function(data) {
                    var jsData = $.parseHTML(data);
                    var formCont = $('.table-hover').closest('form');
                    $(jsData).find('.alert').insertBefore(formCont);
                    name.text(newName);
                }
            });
        }
        return false;
    },
    // Удаление e-mail адреса
    delItem : function(self) {
        var modelCont = $('#myModal');
        if (modelCont.length) {
            var trCont = $(self).closest('tr');
            modelCont.find('#myModalLabel').text('Удаление e-mail адреса');
            modelCont.find('.modal-body').html('' +
                'Подтверждаете удаление <strong>' + trCont.find('td:eq(1)').text() + '</strong> ?');
            modelCont.modal({
                backdrop: false
            });
            modelCont.find('.modal-footer button:eq(0)').unbind().click(function(event) {
                $.ajax({
                    url: '/sendMail/http.php?do=delEmail&id=' + $(self).attr('value'),
                    type: 'POST',
                    success: function() {
                        modelCont.modal('hide').on('hidden.bs.modal', function(event) {
                            trCont.fadeOut('fast', function() {
                                $(this).remove();
                            });
                            $(this).unbind(event);
                        });
                    }
                });
            });
        }
        return false;
    },
    // Извлечение e-mail адресов из файлов в папке folder_input
    parseFolder : function() {
        if (confirm('Подтверждаете выполнение операции?')) {
            $.ajax({
               url: '/sendMail/http.php?do=parseFolder',
               type: 'POST',
               success: function() {}
            });
        }
        return false;  
    },
    // Инициализация элементов на странице
    autoInint : function() {
        $('.alert').prepend('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>');
        
        $('<div id="myModal" class="modal fade">' +
            '<div class="modal-dialog">' +
                '<div class="modal-content">' +
                    '<div class="modal-header">' +
                        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
                        '<h4 class="modal-title" id="myModalLabel"></h4>' +
                    '</div>' +
                    '<div class="modal-body"></div>' +
                    '<div class="modal-footer">' +
                        '<button type="button" class="btn btn-primary">Ок</button>' +
                        '<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>').appendTo('body');
    }
}

$(function(){SendMail.autoInint()});