(function ($) {
    $('html').niceScroll();
    if ('undefined' === typeof markdown)
    	throw new Error('Markdown.js is required');
    $('.markdown[data-content]').each(function (idx, ele) {
    	var source = '/static/' + $(this).attr('data-content') + '.md';
    	$.get(source, function(data) {
    		var content = markdown.toHTML(data);
    		$(ele).html(content);
    	});
    });
}(window.jQuery));