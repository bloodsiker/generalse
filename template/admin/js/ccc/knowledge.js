jQuery(document).ready(function(){
    var accordionsMenu = $('.cd-accordion-menu');

    if($(".cd-accordion-menu li").last().children('a').text() == 'Популярные (0)'){
        $( ".cd-accordion-menu li" ).last().children('a').text("Популярные");
    }

    if( accordionsMenu.length > 0 ) {
        //console.log(accordionsMenu);
        accordionsMenu.each(function(){
            var accordion = $(this);

            //detect change in the input[type="checkbox"] value
            accordion.on('change', 'input[type="checkbox"]', function(){
                var checkbox = $(this);
                console.log(checkbox.prop('checked'));
                ( checkbox.prop('checked') ) ? checkbox.siblings('ul').attr('style', 'display:none;').slideDown(300) : checkbox.siblings('ul').attr('style', 'display:block;').slideUp(300);
            });
        });
    }
});

