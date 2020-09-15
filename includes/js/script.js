jQuery(document).ready(function($) {
	
    //选择媒体库
    $('body').on("click", '.im-upload', function(e) {	
		e.preventDefault();	// 阻止事件默认行为。
		var upload	= $(this).prev("input");
		var type	= $(this).data('type');
		var title	= (type == 'image')?'选择图片':'选择媒体';

		uploader = wp.media({
			title:		title,
			library:	{ type: type },
			button:		{ text: title },
			multiple:	false 
		}).on('select', function() {
			var attachment = uploader.state().get('selection').first().toJSON();
			upload.val(attachment.url);
			$('.media-modal-close').trigger('click');
		}).open();
		return false;
	});


	
})