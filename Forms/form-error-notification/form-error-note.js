function getQueryParam(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

if (getQueryParam('ferr') && getQueryParam('fkey')) {
    $('body').append('<div id="formErrorNotice">Your form submission is not complete.</div>');
    $('#formErrorNotice')
        .css('left',Math.round(($(window).width()-$('#formErrorNotice').width())/2))
        .fadeIn('fast')
        .delay(7000)
        .fadeOut();
}
