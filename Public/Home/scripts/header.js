jQuery(function($){
    'use strict';
    //share
    $('#topbar').on('mouseenter', '.share', function(){
        var me = $(this);
        var list = me.find('.share-list');
        me.addClass('share-block');
        list.show().height(10);
        var h = me.find('.share-list ul').height();
        list.stop().animate({
            height: h
        }, {
            duration: 400,
            easing: 'easeOutExpo'
        });
    }).on('mouseleave', '.share', function(){
        var me = $(this);
        var list = me.find('.share-list');
        list.stop().animate({
            height: 0
        }, {
            duration: 400,
            easing: 'easeInExpo',
            complete: function(){
                me.removeClass('share-block');
            }
        });
    });
    
    //return top
    $('#footer .return-top').find('a').bind('click', function(){
        $('html, body').animate({
            scrollTop:0
        }, 300, 'easeInOutExpo'); 
        return false;
    });
});