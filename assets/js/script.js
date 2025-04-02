jQuery(document).ready(function($) {
    $('.generate-content').on('click', function() {
        const id = $(this).data('id');
        $.post(seoBlackholeAjax.ajax_url, {
            action: 'generate_content',
            id: id,
            _ajax_nonce: seoBlackholeAjax.nonce
        }, function(response) {
            alert('محتوا تولید شد!');
            location.reload();
        });
    });
});