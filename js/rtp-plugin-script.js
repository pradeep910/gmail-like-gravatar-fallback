/* 
 * Plugin jQuery file
 */

jQuery(document).ready(function () {
    jQuery("img.avatar[data-src]").not(jQuery("img.avatar[data-src]").closest("#wpadminbar")).each(function () {
        jQuery(this).hide();

        var src = jQuery(this).attr('data-src');
        var size = jQuery(this).attr('width');

        var default_elem = jQuery(this).siblings('div.default-gravatar');
        if (default_elem.length) {
            jQuery(this).load(src, function (response, status, xhr) {

                if (status == "error") {
                    var title = '';
                    if (jQuery(this).siblings('div.default-gravatar span').length) {
                    } else {
                        //FIND AUTHOR NAME IF COMMENTS SECTION
                        if (jQuery(this).closest('*[id|="comment"]').find('.fn').length) {
                            title = jQuery(this).closest('*[id|="comment"]').find('.fn').text();
                        }

                        if ( title === '' && jQuery(this).parent().find('.fn').length) {
                            title = jQuery(this).parent().find('.fn').text();
                        }

                        if ( title !== '' ) {
                            var new_name = title.toString();
                            new_name = new_name.charAt(0);
                            default_elem.html('<span>' + new_name + '</span>');
                        }
                    }

                    var color = rtp_get_random_color();

                    default_elem.css({background: "#" + color, width: size + 'px', height: size + 'px'}).fadeIn();
                    default_elem.find('span').css({width: size + 'px', height: size + 'px', fontSize: (size / 20) + 'em'});
                }
                else {
                    jQuery(this).attr('src', src).fadeIn();
                }
            });
        } else
            jQuery(this).attr('src', src).fadeIn();
    });

    function rtp_get_random_color() {
        var randomColor = Math.floor(Math.random() * 16777215).toString(16);
        //var colors = [ 'red', 'green', 'cyan', 'blue', 'yellow', 'grey'  ];
        return randomColor;
    }

});