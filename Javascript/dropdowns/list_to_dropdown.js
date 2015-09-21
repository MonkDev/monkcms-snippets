/*============================================
 *
 *               mcmsFilterSelect()
 *
 *=============================================
 *
 * Sets up select menus for long sidebar lists.
 * Demo: mcmsFilterSelect('#sermon_list_archive');
 *
 */ 
function mcmsFilterSelect(list){
    
    $(list).each(function(){
        var    ul = $(this),
                id = ul.attr('id'),
                items = $('li', this),
                select = '',
                select_options = '';
        items.each(function(i){
            var    a = $('> a', this),
                    title = a.text(),
                    url = a.attr('href');
            var option = '<option value="'+url+'">'+title+'</option>';
            select_options = select_options + option;
            if (!--items.length){
                select = select + '<p>';
                select = select + '<select id="select_'+id+'" style="width:140px;">';
                select = select + '<option value="">-</option>';
                select = select + select_options;
                select = select + '</select></div>';
                select = select + '</p>';
                ul.replaceWith(select);
                $('body').on('change', '#select_' + id, function(){
                    window.location.href = $(this).val();
                });
            }
        });
        
    });
    
}