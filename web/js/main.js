!function ($) {
    $(document.getElementsByTagName('html')[0]).niceScroll();
    if ('undefined' === typeof markdown) throw new Error('Markdown.js is required');
    var md = $('.markdown[data-content]'), url = document.URL.replace(window.location.protocol + '//').split('/')[1];
    if (md.length) {
        md.html('<img src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/images/ajax-loader.gif"> Loading...').each(function (idx, ele) {
    	   $.get('/static/' + $(this).attr('data-content') + '.md', function(data) {
    		  $(ele).html(markdown.toHTML(data)).trigger('markdown.compile.finished');
    	   });
        });
    }
	if ('' === url) return;
	$('.navbar li#' + url).addClass('active').find('a').attr('href', '#');
}(window.jQuery);