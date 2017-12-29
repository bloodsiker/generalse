$(document).on('click', '.view-video', function(e) {
    var source = $(this).attr('data-video');
    var title = $(this).find('h4').text();
    var html = `<div class="row align-center">
                    <div class="medium-12 text-center small-12 columns">
                    <h5>` + title + `</h5>
                </div>
                <div class="large-12 medium-12 small-12 columns">
                      <video id="" class="video video-js vjs-fluid vjs-default-skin" controls>
                          <source src="` + source + `" type="video/mp4">
                       </video>
                  </div>
                </div>
                  <button class="close-button" data-close aria-label="Close reveal" type="button">
                    <span aria-hidden="true">&times;</span>
                  </button>`;
    
  $('#video-modal').html(html).foundation('open');
    var player = videojs(document.getElementsByClassName('video')[0]).ready(function() {
      this.play();
    });

});

$('.reveal').bind("contextmenu", function(e) {
    e.preventDefault();
});


// Stop video
$(document).on('click', '[data-close]', function() {
    var player = videojs(document.getElementsByClassName('video')[0]).ready(function() {
        this.pause();
    });
});
$(document).on('click', function(e) {
    if ($(e.target).closest(".reveal").length === 0) {
        if ($('#video-modal').css('display') == 'block') {
            var player = videojs(document.getElementsByClassName('video')[0]).ready(function() {
                this.pause();
            });
        }
    }
});

// check gorup users
$(document).on('change', '.select-group', (event) => {
    const element = $(event.target);
    const selected = element.prop('checked');
    const inputs = element.parents('.parent-block').find('.children-input-group');
    for(let i = 0; i < inputs.length; i++) {
        $(inputs[i]).prop('checked', selected);
    }
});

// SlideToggle users in group
$(document).ready(function(){
    $('.parent-block .dark').on('click', function() {
        $('.child-block  .show').slideToggle(500);
        $(this).parent().find('.show').slideToggle(500);
    });
});
