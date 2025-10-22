// Wait for jQuery to be loaded
function waitForjQuery(callback) {
    if (typeof $ !== 'undefined' && typeof jQuery !== 'undefined') {
        callback();
    } else {
        setTimeout(function() {
            waitForjQuery(callback);
        }, 50);
    }
}

waitForjQuery(function() {
    $(document).ready(function() {
        console.log('Doctor links fix applied');
        
        // Ensure all doctor profile links are properly handled
        $('.doctor-profile-link').each(function() {
            var $link = $(this);
            
            // Ensure it's properly clickable
            $link.css({
                'pointer-events': 'auto',
                'cursor': 'pointer'
            });
        });
        
        // Add click handler for profile links
        $(document).on('click', '.doctor-profile-link', function(e) {
            var $link = $(this);
            
            // Allow the link to work normally
            console.log('Doctor profile link clicked:', $link.attr('href'));
        });
    });
});
