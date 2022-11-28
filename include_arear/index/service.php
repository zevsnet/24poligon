<section id="feature">
    <div class="container">
        <br>
        <link rel="stylesheet" href="shepherd-theme-arrows.css"/>
        <script src="tether.js"></script>
        <script src="shepherd.js"></script>
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-12">Мастер*:</div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <select class="form-control" id="sel1">
                    <option>Светлана</option>
                </select>
            </div>
        </div>
        <div class="row step_2_anim" id="step_2" style='padding:10px;'>
            <div class="col-md-3 col-sm-3 col-xs-12 col-table-1">Выбери дату и время*:</div>
            <? if($_REQUEST['DEV'] != 1)
            { ?>
                <link rel="stylesheet" type="text/css" href="/local/js/jquery.datetimepicker.css"/>
                <script src="/local/js/jquery.datetimepicker.full.js"></script>
                <input type="text" id="datetimepicker"/>
                <?
                date_default_timezone_set('Asia/Krasnoyarsk');
                ?>
            <? } ?>
        </div>
        <div class="row step_3_anim" id="step_3" style="display: none;opacity: 0;">
            <div class="col-md-12 col-sm-12 col-xs-12">Выберите услугу*:</div>
            <div class="col-md-7 col-sm-7 col-xs-12">
                <? $APPLICATION->IncludeComponent("bitrix:news.list", "z_service_list", array(
                    "IBLOCK_TYPE"                     => "service",
                    "IBLOCK_ID"                       => "33",
                    "NEWS_COUNT"                      => "",
                    "SORT_BY1"                        => "ID",
                    "SORT_ORDER1"                     => "ASC",
                    "SORT_BY2"                        => "SORT",
                    "SORT_ORDER2"                     => "ASC",
                    "FILTER_NAME"                     => "",
                    "FIELD_CODE"                      => array(
                        0  => "ID",
                        1  => "CODE",
                        2  => "XML_ID",
                        3  => "NAME",
                        4  => "TAGS",
                        5  => "SORT",
                        6  => "PREVIEW_TEXT",
                        7  => "PREVIEW_PICTURE",
                        8  => "DETAIL_TEXT",
                        9  => "DETAIL_PICTURE",
                        10 => "DATE_ACTIVE_FROM",
                        11 => "ACTIVE_FROM",
                        12 => "DATE_ACTIVE_TO",
                        13 => "ACTIVE_TO",
                        14 => "SHOW_COUNTER",
                        15 => "SHOW_COUNTER_START",
                        16 => "IBLOCK_TYPE_ID",
                        17 => "IBLOCK_ID",
                        18 => "IBLOCK_CODE",
                        19 => "IBLOCK_NAME",
                        20 => "IBLOCK_EXTERNAL_ID",
                        21 => "DATE_CREATE",
                        22 => "CREATED_BY",
                        23 => "CREATED_USER_NAME",
                        24 => "TIMESTAMP_X",
                        25 => "MODIFIED_BY",
                        26 => "USER_NAME",
                        27 => "",
                    ),
                    "PROPERTY_CODE"                   => array(
                        0 => "",
                        1 => "Z_END_TIME",
                        2 => "Z_URL",
                        3 => "",
                    ),
                    "CHECK_DATES"                     => "N",
                    "DETAIL_URL"                      => "",
                    "AJAX_MODE"                       => "N",
                    "AJAX_OPTION_JUMP"                => "N",
                    "AJAX_OPTION_STYLE"               => "Y",
                    "AJAX_OPTION_HISTORY"             => "N",
                    "CACHE_TYPE"                      => "N",
                    "CACHE_TIME"                      => "36000000",
                    "CACHE_FILTER"                    => "N",
                    "CACHE_GROUPS"                    => "Y",
                    "PREVIEW_TRUNCATE_LEN"            => "",
                    "ACTIVE_DATE_FORMAT"              => "d.m.Y",
                    "SET_STATUS_404"                  => "N",
                    "SET_TITLE"                       => "N",
                    "INCLUDE_IBLOCK_INTO_CHAIN"       => "N",
                    "ADD_SECTIONS_CHAIN"              => "N",
                    "HIDE_LINK_WHEN_NO_DETAIL"        => "N",
                    "PARENT_SECTION"                  => "",
                    "PARENT_SECTION_CODE"             => "",
                    "INCLUDE_SUBSECTIONS"             => "Y",
                    "PAGER_TEMPLATE"                  => ".default",
                    "DISPLAY_TOP_PAGER"               => "N",
                    "DISPLAY_BOTTOM_PAGER"            => "N",
                    "PAGER_TITLE"                     => "Новости",
                    "PAGER_SHOW_ALWAYS"               => "N",
                    "PAGER_DESC_NUMBERING"            => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL"                  => "Y",
                    "DISPLAY_DATE"                    => "Y",
                    "DISPLAY_NAME"                    => "Y",
                    "DISPLAY_PICTURE"                 => "Y",
                    "DISPLAY_PREVIEW_TEXT"            => "Y",
                    "AJAX_OPTION_ADDITIONAL"          => "",
                    "COMPONENT_TEMPLATE"              => "z_service_list",
                    "SET_BROWSER_TITLE"               => "Y",
                    "SET_META_KEYWORDS"               => "Y",
                    "SET_META_DESCRIPTION"            => "Y",
                    "SET_LAST_MODIFIED"               => "N",
                    "PAGER_BASE_LINK_ENABLE"          => "N",
                    "SHOW_404"                        => "N",
                    "MESSAGE_404"                     => ""
                ), false); ?>
            </div>
        </div>
        <div class="row step_4_anim" id="step_4" style="display: none;opacity: 0;">
            <div class="col-md-3 col-sm-3 col-xs-12 col-table-1">ФИО*:</div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="text" class="form-control" id="service_name">
            </div>
        </div>
        <div class="row step_5_anim" id="step_5" style="display: none;opacity: 0;">
            <div class="col-md-3 col-sm-3 col-xs-12 col-table-1">Телефон*:</div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="tel" class="form-control" id="service_phone">
            </div>
        </div>
        <div class="row step_6_anim" id="step_6" style="display: none;opacity: 0;">
            <div class="col-md-3 col-sm-3 col-xs-12 col-table-1">E-mail(для накопления бонусов):</div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <input type="email" class="form-control" id="service_email">
            </div>
        </div>
        <div class="row step_7_anim" id="step_7" style="">
            <div class="col-md-7 col-sm-7 col-xs-12">
                <div class="z_bt_service_add">Записаться</div>
            </div>
        </div>

        <div class="row step_8_anim" id="step_8" style="display:none;">
            <div class="col-md-7 col-sm-7 col-xs-12">
                <div class="z_text_finish"></div>
            </div>
        </div>

    </div>
    <?
    $arSelect = Array('ACTIVE_FROM');
    $arFilter = Array(
        "IBLOCK_ID" => 35,
        "ACTIVE"    => "Y",
    );
    $res = CIBlockElement::GetList(Array('name' => 'ASC'), $arFilter, false, false, $arSelect);
    $arResult = array();
    $TIME_WORK = array();
    $arDateStr = '';
    while($ob = $res->GetNextElement())
    {
        $arFields = $ob->GetFields();
        $arDateStr .= '"' . substr($arFields['ACTIVE_FROM'], 0, strpos($arFields['ACTIVE_FROM'], ' ')) . '",';
    }
    $arDateStr = substr($arDateStr, 0, strlen($arDateStr) - 1);

    ?>
</section>

<script>
    function severalDates(date)
    {
        var selectDate = new Date(date);
        var day = selectDate.getDate();
        var month = (selectDate.getMonth() + 1);
        day = (parseInt(day, 10) < 10 ) ? ('0' + day) : (day);
        month = (parseInt(month, 10) < 10 ) ? ('0' + month) : (month);
        var Date_final = day + '.' + month + '.' + selectDate.getFullYear();

        //console.log(Date_final);
        var dat = Date_final;
        var r = [""];

        var dates = new Array(<?=$arDateStr?>);

        if(jQuery.inArray(dat, dates) >= 0)
        {
            r = ["green"];
        }
        else
        {
            r = ["xdsoft_disabled"];
        }

        return r;

    }

    $(document).ready(function()
    {
        var shepherd;
        shepherd = new Shepherd.Tour({
            defaults: {
                classes: 'shepherd-element shepherd-open shepherd-theme-arrows',
                showCancelLink: true
            }
        });
        /*shepherd.addStep('day_time', {
         title: 'Выбор дня и времени',
         text: 'Выберете доступный день для записи',
         attachTo: '#step_2 bottom',
         buttons: [], scrollTo: true
         });
         shepherd.addStep('select_service', {
         title: 'Выберите услугу',
         text: '',
         attachTo: '#step_3 table top',
         buttons: [], scrollTo: true
         });
         shepherd.addStep('enter_FIO', {
         title: 'Персональные данные',
         text: 'Введите Имя (Фамилию) как к вам можно обращаться.',
         attachTo: '#step_4 input top',
         buttons: [], scrollTo: true
         });
         shepherd.addStep('enter_phone', {
         title: 'Укажите телефон',
         text: 'Сообщите телефон чтобы мастер мог с вами связаться и подтвердить вашу запись!!!',
         attachTo: '#step_5 input top',
         buttons: [], scrollTo: true
         });
         shepherd.addStep('z_finish', {
         title: 'Почти готово',
         text: 'Жми "Записаться"',
         attachTo: '.z_bt_service_add top',
         buttons: [], scrollTo: true
         });*/
        shepherd.start();
        /*********************/

        var date;
        var time;
        //step_2
        var e = document.getElementById("sel1");
        var Master = e.options[e.selectedIndex].value;
        if(Master != '')
        {
            //$("#step_2").css({'display':'block'});
            //$('#step_2').show();
            //$('#step_2').animate({'opacity': '1'}, 300);
        }
        //step_3
        /*$('.z_table tr td').click(function()
         {

         });*/

        $.datetimepicker.setLocale('ru');
        var $datepicker = $("#datetimepicker");
        $datepicker.datetimepicker({
            inline: true,
            format: 'd.m.Y H:i',
            step: 120,
            //value: '<?=date('d.m.Y  H:i')?>',
            minDate: '<?=date('d.m.Y')?>',
            startDate: '<?=date('d.m.Y')?>',
            timepicker: false,
            dayOfWeekStart: 1,

            beforeShowDay: severalDates,
            onSelectDate: function(dateText, inst)
            {
                var selectDate = new Date(dateText);
                var day = selectDate.getDate();
                var month = (selectDate.getMonth() + 1);
                day = (parseInt(day, 10) < 10 ) ? ('0' + day) : (day);
                month = (parseInt(month, 10) < 10 ) ? ('0' + month) : (month);
                data = day;

                var Date_final = day + '.' + month + '.' + selectDate.getFullYear();
                var query = {
                    ACTION: 'GETTIME',
                    DATE_FINAL: Date_final
                };
                $.ajax({
                    url: "/ajax/service.php",
                    data: query,
                    type: "GET",
                    dataType: 'json',
                    success: function(data)
                    {
                        if(data.length > 0)
                        {
                            $datepicker.datetimepicker({timepicker: true, allowTimes: data});
                        }
                        else
                        {
                            $datepicker.datetimepicker({timepicker: false});
                        }

                    }
                });

            },
            onSelectTime: function(dateText, inst)
            {
                var selectDateTime = new Date(dateText);

                var day = selectDateTime.getDate();
                day = (parseInt(day, 10) < 10 ) ? ('0' + day) : (day);

                var dateFull = day + '.' + (selectDateTime.getMonth() + 1) + '.' + selectDateTime.getFullYear();

                var minut = selectDateTime.getMinutes();
                minut = (parseInt(minut, 10) < 10 ) ? ('0' + minut) : (minut);

                time = selectDateTime.getHours() + ':' + minut;

                $('#step_3').show();
                $('#step_3').animate({'opacity': '1'}, 300);

                shepherd.show('select_service');

                //alert(dateFull+' '+time);
            },


        });

        //step_4
        $('.service_list_for_js .service_list').on("click", (function()
        {
            //$(this).find('input').click();
            var check = $(this).find('input');
            if(!check.prop("checked"))
            {
                check.prop("checked", true);
            }
            else
            {
                check.prop("checked", false);
            }

            //$( this ).trigger( "click" );
            var trElements = document.getElementsByClassName('service_list');
            var inputElements = $(trElements).find('input');

            var f_activitu = false;
            for(var i = 0; inputElements[i]; ++i)
            {
                if(inputElements[i].checked)
                {
                    f_activitu = true;
                }
            }
            if(f_activitu)
            {
                $('#step_4').show();
                $('#step_4').animate({'opacity': '1'}, 300);
                $('#step_5').show();
                $('#step_5').animate({'opacity': '1'}, 300);

            }
            else
            {
                $('#step_4').hide();
                $('#step_4').animate({'opacity': '0'}, 300);
            }
        }));

        $('label').on("click", function()
        {
            $('#' + $(this).attr('for')).click();
        });
        //step_5
        $('#service_name').keyup(function()
        {
            if(this.value.length > 3)
            {
                $('#step_5').show();

                $('#step_5').animate({'opacity': '1'}, 300);
            }
            else
            {
                $('#step_5').hide();
                $('#step_5').animate({'opacity': '0'}, 300);
            }
        });
        //step_6
        $('#service_phone').keyup(function()
        {
            if(this.value.length > 3)
            {
                //$('#step_6').show();
                //$('#step_6').animate({'opacity': '1'}, 300);
                /*$('#step_7').show();
                 $('#step_7').animate({'opacity': '1'}, 300);*/
                shepherd.show('z_finish');
            }
            else
            {
                //$('#step_6').hide();
                //$('#step_6').animate({'opacity': '0'}, 300);
                /*$('#step_7').hide();
                 $('#step_7').animate({'opacity': '0'}, 300);*/
            }
        });
        //step_7
        $('.z_bt_service_add').click(function()
        {

            var z_master = $('#sel1').val();
            var z_date;
            $('.z_table tr td').each(function()
            {
                if($(this).attr('class') == 'activity')
                {
                    z_date = {
                        'id_element': $(this).attr('id_element'),
                        'id_time': $(this).attr('id_time'),
                        'date': $(this).attr('date'),
                        'time': $(this).attr('time')
                    };
                }
            });
            var z_service_list = [];
            //var service_list_for_js_tr=$('.service_list_for_js tr');

            var trElements = document.getElementsByClassName('service_list');
            var inputElements = $(trElements).find('input');

            var f_activitu = false;
            for(var i = 0; inputElements[i]; ++i)
            {
                if(inputElements[i].checked)
                {
                    z_service_list.push(inputElements[i].value);
                }
            }

            var z_fio = $('#service_name').val();
            var z_phone = $('#service_phone').val();
            var z_email = $('#service_email').val();

            var input_dataFull = $("#datetimepicker").val();

            var z_date_q = {
                ACTION: 'ADDSHEDULE',
                MASTER: z_master,
                DATE: input_dataFull,
                TIME: time,
                SERVICES: z_service_list,
                FIO: z_fio,
                PHONE: z_phone,
                EMAIL: z_email
            };

            $.ajax({
                url: "/ajax/service.php",
                data: z_date_q,
                type: "GET",
                dataType: 'json',
                success: function(data)
                {
                    if(!data['error'])
                    {
                        /*$('#step_1').hide();*/
                        $('#step_2').hide();
                        $('#step_3').hide();
                        $('#step_4').hide();
                        $('#step_5').hide();
                        $('#step_6').hide();
                        $('#step_7').hide();

                        $(".z_text_finish").html('<span>' + data['FIO'] + ', вы записались на ' + data['DATE'] + ' ' + data['TIME'] + '</span><br/><span>Услуги: </span>' + data['SERVICE']);
                        $('#step_8').show();
                    }
                    else
                    {
                        console.log(data);

                        var step_8 = $('#step_8');
                        step_8.show();
                        var messageError = '';
                        for(var i = 0; i < data['len']; i++)
                        {
                            console.log(data[i]);
                            messageError += '<span style="color:red">*' + data[i] + '</span></br>';
                        }
                        step_8.html(messageError);

                    }

                    //$('#feature .container').each(function(){$(this).html = data});
                    //location.reload();
                }
            });
        });
    });

</script>
