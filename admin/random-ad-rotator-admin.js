jQuery(document).ready(function($) {
    // Add image
    $(document).on('click', '.add-ad-image', function(e) {
        e.preventDefault();
        var size = $(this).data('size');
        var frame = wp.media({
            title: 'Select or Upload Ad Images',
            button: { text: 'Use these images' },
            multiple: true // Enable multiple selections
        });
        frame.on('select', function() {
            var selection = frame.state().get('selection');
            var imageUrls = [];
            selection.map(function(attachment) {
                attachment = attachment.toJSON();
                var imageUrl = attachment.url;
                imageUrls.push(imageUrl);

                // Add each image to the field as a thumbnail
                var html = '<div class="ad-image-wrap" style="display:inline-block;margin-right:10px;">';
                html += '<img src="' + imageUrl + '" style="width: 100px; height: auto;" />';
                html += '<br><input type="hidden" name="random_ad_images[' + size + '][]" value="' + imageUrl + '" />';
                html += '<button class="button remove-ad-image">Remove</button></div>';
                $('#ad-images-' + size).append(html);
            });
        });
        frame.open();
    });

    // Remove image
    $(document).on('click', '.remove-ad-image', function(e) {
        e.preventDefault();
        $(this).parent().remove();
    });
});
