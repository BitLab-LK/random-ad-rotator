jQuery(document).ready(function($) {
    $('.random-ad-container').each(function() {
        var adContainer = $(this);
        var size = adContainer.data('size'); // Get the ad size

        // Make the AJAX request
        $.post(randomAdRotator.ajax_url, {
            action: 'get_random_ad_image',
            size: size
        }, function(response) {
            if (response.success) {
                // Update the ad container with the random image
                adContainer.html('<img src="' + response.data + '" alt="Ad">');
            } else {
                adContainer.html('No ad available');
            }
        });
    });
});
