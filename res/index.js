$(function() {
    $('.searchbox').focus();
    var regex = /[\\?&]keyword=([^&#]*)/;
    var match = regex.exec(document.location.href);
    if(match) {
        document.location.hash = match[1];
    }
    fetchSearchResult();
    setInterval(fetchSearchResult, 500);
});
function search(form) {
    var keyword = $('.searchbox', form).val();
    document.location.hash = encodeURIComponent(keyword);
}
var fetchSearchResult = function() {
    var currentHash = '';
    return function() {
        var keyword = document.location.hash;
        if(keyword == currentHash)
            return;
        currentHash = keyword;
        if(keyword[0] == '#')
            keyword = keyword.substr(1);
        keyword = keyword.trim();
        if(keyword.length > 0) {
            $('#page_card_home').hide();
            $('#page_card_result').show();
            $('#page_card_result .searchbox').val(decodeURIComponent(keyword));
        } else {
            $('#page_card_home').show();
            $('#page_card_result').hide();
        }
    };
}();
