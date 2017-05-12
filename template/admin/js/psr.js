$(document).on('click', '#add-create-button', function(e) {
    $('#add-checkout-modal form')[0].reset();
    $('#add-checkout-modal').foundation('open');
});
var cout = 1;
$(document).on('click', '#add-parts-info', function(e) {
    $('.delete-parts-info').remove();
    cout++;
    var html = `<div class="row l1-show align-bottom" style="display: flex;">
				<div data-parts='` + cout + `' class="medium-6 small-12 columns">
                <label>Part Used</label>
                <input type="text" name="Part_Used_` + cout + `" class="required" required>
              </div>
              <div data-parts='` + cout + `' class="medium-6 small-12 columns">
                <label>Source</label>
                <select class="source required" name="Source_` + cout + `" class="required" required>
                  <option value="" selected disabled>none</option>
                  <option value="Local Source">Local Source</option>
                  <option value="Not Used">Not Used</option>
                  <option value="Dismantling">Dismantling</option>
                  <option value="Restored">Restored</option>
                </select>
              </div>
              <div data-parts='` + cout + `' class="medium-6 ls-show small-12 columns">
                <label>Price</label>
                <input type="text" pattern="[0-9]" name="Repair_Price_` + cout + `">
              </div>
              <div data-parts='` + cout + `' class="medium-6 ls-show small-12 columns">
                  <select name="Price_` + cout + `" class="required" required>
                     <option value="USD" selected>USD</option>
                     <option value="UAH">UAH</option>
                  </select>
               </div>
              <span data-parts="` + cout + `" class="fi-trash delete-parts-info"></span>
              </div>`
    $(this).parent().parent().before(html);
});

$(document).on('click', '.delete-parts-info', function(e) {
    $(this).parent().remove();
    cout--;
    $('[data-parts=' + cout + ']').last().after('<span data-parts='+cout+' class="fi-trash delete-parts-info"></span>')
});



// LEVEL SELECT
$(document).on('change', '[name="Level"]', function(event) {
    if (event.target.value === 'L1') {
        $('.l1-show').slideDown('slow').css('display', 'flex').find('input, select').addClass('required').attr('required', '');
    } else {
        $('.l1-show').slideUp('slow').find('input, select').removeClass('required').removeAttr('required', '');
    }
});

// SOURCE SELECT
$(document).on('change', '.source', function(event) {
    if (event.target.value === 'Local Source') {
        $(this).parent().parent().find('.ls-show').slideDown('slow')
            .find('input').addClass('required').attr('required', '')
            .find('select').addClass('required').attr('required', '');
    } else {
        $(this).parent().parent().find('.ls-show').slideUp('slow')
            .find('input').removeClass('required').removeAttr('required', '')
            .find('select').removeClass('required').removeAttr('required', '');
    }
});







var startDate = 0, endDate = 0;
$(document).on('change', '[name="Manufacture_Date"]', function(event) {
    startDate = new Date(event.target.value);
    dateRez();
});
$(document).on('change', '[name="Purchase_date"]', function(event) {
    endDate = new Date(event.target.value);
    dateRez();
});



function dateRez() {
	if (startDate == 0 || endDate == 0) {
		return false;
	} else {
		var days = ((endDate - startDate)/86400)/1000;
		if (days >= 365 && days <= 730) {
			$('.error-date').hide();
			$('[name="Days"]').val(days)
		} else {
			$('.error-date').show();
			$('[name="Days"]').val(days)
			
		}
	}
}


$(document).on('keydown', '[name="Days"]', function() {
	return false;
});


// HOVER
$(document).on('mouseover', '.l1-show', function(event) {
    console.log('msg')
    $(this).css('background-color', 'rgba(255, 255, 255, 0.08)')
});
$(document).on('mouseout', '.l1-show', function(event) {
    $(this).css('background-color', 'rgba(0,0,0,0)')
});