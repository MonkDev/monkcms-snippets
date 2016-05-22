/*============================================
 *
 *               mcmsFilterSelect()
 *
 *=============================================
 *
 * Sets up select menus for long sidebar lists.
 * Demo: mcmsFilterSelect('#sermon_list_archive');
 */

function mcmsFilterSelect(list, width) {
    $(list).each(function() {
        var $ul = $(this),
            id = $ul.attr('id'),
            items = $('li', this),
            select = '',
            options = '';

        items.each(function() {
            var $a = $('> a', this);
            options += '<option value="' + $a.attr('href') + '">' + $a.text() + '</option>';

            if (!--items.length) {
                if (!width) width = 140;

                select += '<p>';
                select += '<select id="select_' + id + '" style="width:' + width + 'px;">';
                select += '<option value="">-</option>';
                select += options;
                select += '</select>';
                select += '</p>';

                $ul.replaceWith(select);

                $('body').on('change', '#select_' + id, function() {
                    window.location.href = $(this).val();
                });
            }
        });
    });
}
