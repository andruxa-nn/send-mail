function parseURL(url) {
    var a =  document.createElement('a');
    a.href = url;
    return {
        source: url,
        protocol: a.protocol.replace(':',''),
        host: a.hostname,
        port: a.port,
        query: a.search,
        params: (function(){
            var ret = {},
                seg = a.search.replace(/^\?/,'').split('&'),
                len = seg.length, i = 0, s;
            for (;i<len;i++) {
                if (!seg[i]) { continue; }
                s = seg[i].split('=');
                ret[s[0]] = s[1];
            }
            return ret;
        })(),
        file: (a.pathname.match(/\/([^\/?#]+)$/i) || [,''])[1],
        hash: a.hash.replace('#',''),
        path: a.pathname.replace(/^([^\/])/,'/$1'),
        relative: (a.href.match(/tps?:\/\/[^\/]+(.+)/) || [,''])[1],
        segments: a.pathname.replace(/^\//,'').split('/')
    };
}

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
                    url: 'http.php?do=delEmail&id=' + trashIdArr[0],
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
        if (typeof self != 'object') return;
        var jsUrl = parseURL(self.href);
        var name = $(self).parent().siblings(':eq(1)');
        if (newName = prompt(name.text())) {
            $.ajax({
                url: 'http.php?do=editEmail',
                type: 'POST',
                data: {
                    id: jsUrl.params.id,
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
        var jsUrl = parseURL(self.href);
        var modelCont = $('#myModal');
        if (modelCont.length) {
            var trCont = $(self).closest('tr');
            modelCont.find('#myModalLabel').text('Удаление e-mail адреса');
            modelCont.find('.modal-body').html('' +
                'Подтверждаете удаление <strong>' + trCont.find('td:eq(1)').text() + '</strong> ?');
            modelCont.modal({
                backdrop: false
            });
            modelCont.find('.btn-primary').focus();
            modelCont.find('.modal-footer button:eq(0)').unbind().click(function(event) {
                $.ajax({
                    url: 'http.php?do=delEmail&id=' + jsUrl.params.id,
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
               url: 'http.php?do=parseFolder',
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