!function ($){
	$('.markdown').bind('markdown.compile.finished', function (){
		$('.markdown blockquote').each(function () {
			var 
				content = $(this).html(),
				tips = $('<div></div>').addClass('panel panel-info').append($('<div></div>').addClass('panel-heading').text('小提示'));
			tips.append($('<div></div>').addClass('panel-body').css('color', '#000000').html(content));
			tips.css('margin-top', '1em');
			$(this).before(tips).remove();
		});

		$('.markdown img').css('width', '400px').addClass('img-thumbnail pull-center');
		$('.markdown ul').each(function () {
			var
				content = $(this).html(),
				row = $('<div></div>').addClass('row'),
				img = $('<div></div>').addClass('col-sm-3'),
				image = $('<img />').css('cursor', 'pointer'),
				link = $('<a></a>').addClass('comics');

			image.attr('src', $(this).next('p').find('img').attr('src'))
			.attr({
                'href': image.attr('src')
            }).addClass('img-thumbnail');

			link.attr({
				'href': image.attr('src')
			}).colorbox({
				rel: "comics",
				transition: "fade",
				height:"80%"
			});

            row.append($('<div></div>').addClass('col-sm-7').html(content));
			img.append($('<a></a>').attr('href', image.attr('src')).addClass('comics'));
            row.append(img.append(link.append(image)));
            
            $(this).before(row).next('p').remove();
            $(this).remove();
		});
	});
}(jQuery);