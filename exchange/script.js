$(document).ready(function () {
    $(document).on('click', '.links__item', function (e) {
        e.preventDefault();
        const type = $(this).data('type');
        const filename = $(this).data('filename');
        const mode = $(this).data('mode');
        const select = $(this).data('select');
        let url = '/local/exchange/import.php?type=' + type + '&mode=' + mode + '&filename=' + filename;
        if (select && select.length)
            url = url + '&select=' + select;
        $('.respond').html('');
        sendRequest(url, 1, mode);
        window.startImport = new Date();
        $('.links__item').removeClass('active');
        $(this).addClass('active');
    });
});

function sendRequest(url, step, mode) {
    $.ajax({
        url: url,
        dataType: 'text',
        success: function (respond) {
            var endDate = new Date();
            var diff = endDate - window.startImport;
            if (respond.substr(0, 7) == "success") {
                $('.respond').append('<p class="success">' + respond + '</p>');
                $(".respond").append('<p class="end-time">Выгрузка заняла ' + (diff / 1000) + ' сек</p><hr>');
            } else if (respond.substr(0, 7) == "failure") {
                $('.respond').append('<p class="error">' + respond + '</p>');
                $(".respond").append('<p class="end-time">Выгрузка заняла ' + (diff / 1000) + ' сек</p><hr>');
            } else {
                //$('.respond').append('<p>Шаг ' + step + '</p><p>' + respond + '</p><hr>');
                if (mode == 'query') {
                    $('.respond').append('<pre>' + respond + '</pre>');
                } else {
                    $('.respond').append('<p>Шаг ' + step + '</p><p>' + respond + '</p><hr>');
                    sendRequest(url, ++step);
                }
            }
        }
    })
}