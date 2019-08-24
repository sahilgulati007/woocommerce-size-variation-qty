
jQuery('#buyp').click(function(e) {
    //alert();
    e.preventDefault();
    //jQuery('');
    //get_variation_id_from_attributes();
    //alert(jQuery('input[name="product_id"]').val());
    var test_arr=jQuery('input[type="number"][name="qty"]');
    jQuery.each(test_arr, function(i, item) {  //i=index, item=element in array
        // alert(jQuery(item).val());
        // alert(jQuery(item).data("attr"));
        //alert(jQuery('#pa_color').val());

        if(jQuery('#pa_color').val() == ''){
            alert('Please select the color');
            return false;
        }

        var data = {
            action: 'get_variation_id_from_attributes',
            color: jQuery('#pa_color').val(),
            size: jQuery(item).data("attr"),
            prod_id: jQuery('input[name="product_id"]').val()
        };
        var var_id;
        //debugger
        if(Number(jQuery(item).val())>0) {
            // setTimeout(function(){jQuery.post(myAjax.ajaxurl, data, function (response) {
            //     //alert('Got this from the server: ' + response);
            //     var_id = response;
            //
            //     addToCart(var_id, jQuery(item).val());
            // });}, 1500);
            jQuery.ajax({
                url : myAjax.ajaxurl,
                type : "post",
                data: data,
                async : false,
                success : function(response) {
                    var_id = response;
                    addToCart(var_id, jQuery(item).val());
                }
            });
        }
        //location.reload();
        //alert('added to cart');


    });

    return false;
});

async function addToCart(p_id,qty) {
    //alert(p_id);
    jQuery.get('/wordpressliam/?add-to-cart=' + p_id +'&quantity='+ qty, function() {
        // call back
        //alert('added to cart');
    });
}
// function get_variation_id_from_attributes( $product_id, $colour, $size ) {
//     $colour = strtolower($colour);
//     $size = strtolower($size);
//
//     $variation_id = find_matching_product_variation_id ( $product_id,array(
//         'attribute_pa_colour' => $colour,
//         'attribute_pa_size' => $size
// ));
//
//     return $variation_id;
// }
