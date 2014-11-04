$(function() {
    $('.searchbox').focus();
    var regex = /[\\?&]keyword=([^&#]*)/;
    var match = regex.exec(document.location.href);
    if(match) {
        document.location.hash = match[1];
    }
    checkSearch();
    setInterval(checkSearch, 500);
});
function search(form) {
    var keyword = $('.searchbox', form).val();
    document.location.hash = encodeURIComponent(keyword);
}
var checkSearch = function() {
    var currentHash = '';
    return function() {
        var keyword = document.location.hash;
        if(keyword == currentHash)
            return;
        currentHash = keyword;
        if(keyword[0] == '#')
            keyword = keyword.substr(1);
        fetchSearchResult(keyword, 1);
    };
}();
var fetchSearchResult = function() {
    var prevKeyword = '';
    var loading = false;
    var step = 0;
    setInterval(function() {
        if(loading) {
            step = (step + 1) & 31;
            $('h1.wave').css({opacity:Math.abs(step / 16 - 1)});
        } else {
            step = 0;
            $('h1.wave').css({opacity:1});
        }
    }, 30);
    return function(keyword, page) {
        if(false === keyword)
            keyword = prevKeyword;
        else {
            prevKeyword = keyword;
            $('#page_card_result .pages').hide();
        }
        if(!page) page = 1;
        keyword = keyword.trim();
        $(document).scrollTop(0);
        if(keyword.length > 0) {
            $('#page_card_home').hide();
            $('#page_card_result').show();
            $('#page_card_result .searchbox').val(decodeURIComponent(keyword));
            loading = true;
            $.get('search.php', {keyword:keyword,page:page}, function(result) {
                loading = false;
                $('#searchresult').html(result);
                $('#page_card_result .pages').show();
                $('#page_card_result .pages a').removeClass('active');
                page--;
                $('#page_card_result .pages a:eq(' + page + ')').addClass('active');
                setTimeout(function() {
                    $('a.typotip').each(function() {
                        $(this).attr('href', '#' + encodeURIComponent($(this).html()));
                    });
                    $('.result a').each(function() {
                        $(this).attr('target', '_blank');
                        var u = $(this).attr('href');
                        u = 'go.php' + u.substr(4);
                        $(this).attr('href', u);
                    });
                    $('.related a').each(function() {
                        var u = encodeURIComponent($(this).text());
                        $(this).attr('href', '#' + u);
                    });
                }, 1);
            }, 'html');
        } else {
            $('#page_card_home').show();
            $('#page_card_result').hide();
        }
    }
}();
