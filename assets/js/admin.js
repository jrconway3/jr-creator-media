/* global jQuery, wp, jrCreatorMedia */
(function($) {
    'use strict';

    function openMediaFrame($button) {
        var $picker = $button.closest('.jr-creator-media-image-picker');
        var frame = wp.media({
            title: jrCreatorMedia.selectImage,
            button: { text: jrCreatorMedia.useImage },
            library: { type: 'image' },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $picker.find('#icon_image').val(attachment.id);
            $picker.find('.jr-creator-media-image-preview').html('<img src="' + attachment.url + '" alt="">');
        });

        frame.open();
    }

    $(function() {
        $(document).on('click', '.jr-creator-media-select-image', function(event) {
            event.preventDefault();
            openMediaFrame($(this));
        });

        $(document).on('click', '.jr-creator-media-clear-image', function(event) {
            event.preventDefault();
            var $picker = $(this).closest('.jr-creator-media-image-picker');
            $picker.find('#icon_image').val('');
            $picker.find('.jr-creator-media-image-preview').html('<span class="description">' + jrCreatorMedia.emptyPreview + '</span>');
        });
    });
})(jQuery);
