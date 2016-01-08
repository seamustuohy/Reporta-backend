/*globals jQuery, window, document */
(function ($, window, document) {
    "use strict";
    window.IWMF = window.IWMF || {
        $body: null,
        init: function () {
            this.$body = $('body');

            this.tabs();
        },

        tabs: function () {
            $('.js__tabs').each(function () {
                var $tab = $(this),
                    $nav = $tab.find('.js__nav--tabs'),
                    $nav_items = $nav.find('.js__nav__list__item'),
                    $tabs_items = $tab.find('.js__tabs__item');

                $nav_items.find('a').on('click', function (event) {
                    event.preventDefault();

                    var $this = $(this),
                        $url = $this.attr('href'),
                        $item = $this.closest('li');

                    $nav_items.removeClass('nav__list__item--active');
                    $item.addClass('nav__list__item--active');

                    $tabs_items.removeClass('tabs__item--active');
                    $($url).addClass('tabs__item--active');
                });

            });
        },
    };
    $(document).on('ready', function () {
        window.IWMF.init();
    });
}(jQuery, window, document));