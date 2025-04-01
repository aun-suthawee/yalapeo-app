"use strict";

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

(function ($) {
    $.fn.juiSortable = function (options) {
        var Setting = $.extend({
            selector: $(this),
            handle: "span",
            items: "> tr",
        }, options);

        return this.each(function () {

            $(function () {
                initialize();
            });

            function initialize() {
                $(Setting.selector).sortable({
                    handle: Setting.handle,
                    cursor: "move",
                    placeholder: "ui-state-highlight",
                    opacity: 0.7,
                    items: Setting.items,
                    helper: fixWidthHelper,
                    tolerance: "pointer",
                    revert: 100,
                    start: function (e, ui) {
                        ui.placeholder.width(ui.item.width());
                        ui.placeholder.height(ui.item.height());
                    },
                    update: function (event, ui) {
                        var href = $(this).data('href');
                        var data = $(this).sortable('serialize');
                        $.ajax({
                            url: href,
                            type: 'POST',
                            dataType: 'JSON',
                            data: {
                                serialize: data
                            },
                            cache: false,
                            processData: true,
                            success: function (result) {
                                // console.log(result)
                            }
                        });
                    }
                }).disableSelection();
            }

            function fixWidthHelper(e, ui) {
                ui.children().each(function () {
                    $(this).width($(this).width());
                });

                return ui;
            }
        });
    }
})(jQuery);