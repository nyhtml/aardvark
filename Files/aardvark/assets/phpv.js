// Displays the current PHP version in the "At a Glance" admin dashboard widget.

jQuery(document).ready(function ($) {
    const versionText = "<p class='dpv-wrapper'><span class='dpv-php'>Server running PHP version: <b style='color:green;'>" + phpvObj.phpversion + "</b>.</span><span style='display:none;' class='dpv-mysql'> MySQL version: <b style='color:green;'>" + phpvObj.mysqlversion + "</b></span></p>";

    const target = $('#wp-version-message');
    if (target.length) {
        target.after(versionText);
    } else {
        $('.wrap h1').first().after(versionText); // fallback
    }

    $('.dpv-wrapper').hover(function () {
        $('.dpv-mysql').css('display', 'inline');
    }, function () {
        $('.dpv-mysql').css('display', 'none');
    });
});
