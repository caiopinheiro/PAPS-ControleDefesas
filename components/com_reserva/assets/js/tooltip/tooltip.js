$(document).ready(function() {
	//grab all the anchor tag with rel set to shareit
	$('a[rel=shareit], #shareit-box').click(function() {		
		
		//get the height, top and calculate the left value for the sharebox
		var height = $(this).height();
		var top = $(this).offset().top;
		
		//get the left and find the center value
		var left = $(this).offset().left + ($(this).width() /2) - ($('#shareit-box').width() / 2);		
		
		//grab the href value and explode the bar symbol to grab the url and title
		//the content should be in this format url|title
		var value = $(this).attr('href').split('|');
		
		//assign the value to variables and encode it to url friendly
		var field = value[0];
		var url = encodeURIComponent(value[0]);
		var title = encodeURIComponent(value[1]);
		
		$('#shareit-header').height(height);		
		$('#shareit-box').show();		
		$('#shareit-box').css({'top':top, 'left':left});
				
	});
});