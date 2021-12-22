/* Plugins Functions */
/**
 * Subscription Cart functions
 * @enterprise_dashboard/settings/payments/extend
 */
//rp_ajax functions
jQuery( 'a.removeUserFromGroup' ).click( function( e ) {
    console.log('Removing...' +  this.id + 'group id:'+ this.name);
    var group_id = this.name;
    var user_id = this.id;

    jQuery.ajax({
        type: "POST",
        url: '../ajax/ajax.php',
        data: 'group_id=' + group_id + '&user_id=' + user_id,
        success: function (response) {
            console.log(response);
            // if('123' == '12'){
            //     jQuery('#rp_message').fadeOut("linear");
            // }else{
            //     jQuery('#rp_message').fadeOut("linear");
            // }
        }
    });
});

function increaseSlotsFromCartInput(){
    var total_slots = jQuery('#slots-total').text();
    var slots_remaining = jQuery('#slots-remaining').text();
    console.log(total_slots);
    jQuery('#slots-total').text( (parseInt(total_slots) + parseInt(1)) );
    jQuery('#slots-remaining').text( (parseInt(slots_remaining) + parseInt(1)) );
    //update cart
    updateNewTotal( '' , jQuery('#slots-total').text());

    if(jQuery('#slots-total').text() > 0){
        console.log("not ZERO");
        jQuery('.rp_message').css("height","0px");
        jQuery('.rp_message').fadeOut("linear");
    }else{
        console.log("ZERO");
        jQuery('.rp_message').fadeIn("linear");
        jQuery('.rp_message').css("height","30px");
    }
}

function decreaseSlotsFromCartInput(){
    var total_slots = jQuery('#slots-total').text();
    var slots_remaining = jQuery('#slots-remaining').text();

    if((total_slots-1) >= 0){
        console.log(total_slots);
        jQuery('#slots-total').text( (parseInt(total_slots) - parseInt(1)) );
        jQuery('#slots-remaining').text( (parseInt(slots_remaining) - parseInt(1)) );
        //update cart
        updateNewTotal( '' , jQuery('#slots-total').text());
    }

    if(jQuery('#slots-total').text() == 0){
        console.log("ZERO");
        jQuery('.rp_message').fadeIn("linear");
        jQuery('.rp_message').css("height","0px");

    }else{
        console.log("not ZERO");
        jQuery('.rp_message').fadeOut("linear");
        jQuery('.rp_message').css("height","30px");

    }
}


function updateNewTotal( cost , total_slots){
    var cost = jQuery('#product-cost').text();
    var new_total = jQuery('#slots-total').text();
    var new_total_price = jQuery('#new_total_price');

    //update the total
    var calc_total = new_total_price.text( cost * total_slots );
    //update product price for extend form
    jQuery('#product_quantity').val(total_slots);
}