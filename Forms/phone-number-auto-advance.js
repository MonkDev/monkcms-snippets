// auto-advance phone number fields
$('.monkForm .phone').each(function(){
    var field = $(this);
    var areacode = field.find('input[id$=areacode]');
    var prefix = field.find('input[id$=prefix]');
    var line = field.find('input[id$=line]');
    areacode.keyup(function(){
        if($(this).val().length >= $(this).attr('size')){
            prefix.focus();
        }
    });
    prefix.keyup(function(){
        if($(this).val().length >= $(this).attr('size')){
            line.focus();
        }
    });
    line.keyup(function(){
        if($(this).val().length >= $(this).attr('size')){
            $(this).blur();
        }
    });
});
