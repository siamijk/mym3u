/**
 * jquery.owl-filter.js
 * Create: 07-09-2016
 * Author: Bearsthemes
 * Version: 1.0.0
 */

"use strict";

jQuery.fn.owlRemoveItem = function(num) {
	var owl_data = jQuery(this).data('owlCarousel');
	
	owl_data._items = jQuery.map(owl_data._items, function(data, index) {
		if(index != num) return data;
	})

	jQuery(this).find('.owl-item').eq(num).remove();
}

jQuery.fn.owlFilter = function(data, callback) {
	var owl = this,
		owl_data = jQuery(owl).data('owlCarousel'),
		$elemCopy = jQuery('<div>').css('display', 'none');
	
	/* check attr owl-clone exist */
	if(typeof(jQuery(owl).data('owl-clone')) == 'undefined') {
		jQuery(owl).find('.owl-item:not(.cloned)').clone().appendTo($elemCopy);
		jQuery(owl).data('owl-clone', $elemCopy);
	}else {
		$elemCopy = jQuery(owl).data('owl-clone');
	}
	
	/* clear content */
	owl.trigger('replace.owl.carousel', ['<div/>']);
	
	switch(data){
		case '*': 
			$elemCopy.children().each(function() {
				owl.trigger('add.owl.carousel', [jQuery(this).clone()]);
			})
			break;
		default: 
			$elemCopy.find(data).each(function() {
				owl.trigger('add.owl.carousel', [jQuery(this).parent().clone()]);
			})
			break;
	}

	/* remove item empty when clear */
	owl.owlRemoveItem(0);
	owl.trigger('refresh.owl.carousel').trigger('to.owl.carousel', [0]);

	// callback
	if(callback) callback.call(this, owl);
}