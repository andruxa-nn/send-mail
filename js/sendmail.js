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
        if (confirm('Подтверждаете удаление?')) {
            $.ajax({
                url: '/sendMail/http.php?do=delEmail&id=' + $(self).attr('value'),
                type: 'POST',
                success: function() {
                    $(self).closest('tr').fadeOut('fast', function() {
                        $(this).remove();
                    });
                }
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
        $('.alert').prepend('<button type="button" class="close"' +
        + 'data-dismiss="alert" aria-hidden="true">&times;</button>');
    }
}

$(function(){SendMail.autoInint()});