(function ($) {
	"use strict";

    jQuery(document).ready(function($){
        
        $(function() {
        var owl = $('.filter-item').owlCarousel({
            loop    :true,
            margin  :10,
            nav     :true,
            navText: ["<i class='fa fa-chevron-left'></i>","<i class='fa fa-chevron-right'></i>"],
            responsive:{
                0:{
                    items:4
                },
                600:{
                    items:7
                },
                1000:{
                    items:10
                }
            }
        }); 
        
        /* animate filter */
        var owlAnimateFilter = function(even) {
            $(this)
            .addClass('__loading')
            .delay(70 * $(this).parent().index())
            .queue(function() {
                $(this).dequeue().removeClass('__loading')
            })
        };

        $('.filter-menu').on('click', '.filter-btn', function(e) {
            var filter_data = $(this).data('filter');

            /* return if current */
            if($(this).hasClass('btn-active')) return;

            /* active current */
            $(this).addClass('btn-active').siblings().removeClass('btn-active');

            /* Filter */
            owl.owlFilter(filter_data, function(_owl) { 
                $(_owl).find('.item').each(owlAnimateFilter); 
            });
        });
    })

    });


    jQuery(window).load(function(){

        
    });


}(jQuery)); 