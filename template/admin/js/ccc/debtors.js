let showComments = (user_id, name_partner) => {
    $.ajax({
        url: "/adm/ccc/debtors/show_comments",
        type: "POST",
        data: {action : 'show_comments', user_id : user_id},
        cache: false,
        success: function (response) {
            let modal = $('#show-comments');
            modal.foundation('open');
            $('#container-comments').html(response);
            modal.find('#name_partner').text(name_partner);
            modal.find('#partner_id').attr('data-user-id', user_id);
            modal.find('#partner_id').attr('data-user-name', name_partner);
        }
    });
    return false;
};

let addComments = (e) => {
    $(e.target).siblings('.form-add-comment').show();
};

let sendComments = (e, week, year) => {
    e.preventDefault();
    let comment = $('#comment-' + week).val();
    let partner_id = $('#partner_id').attr('data-user-id');
    let name_partner = $('#partner_id').attr('data-user-name');
    if(comment.length < 1){
        $('#comment-' + week).css('background', '#ff00002e');
    } else {
        $('#comment-' + week).removeAttr('style');
        $.ajax({
            url: "/adm/ccc/debtors/add_comment",
            type: "POST",
            data: {action : 'send_comment', comment : comment, partner_id : partner_id, week: week, year : year},
            cache: false,
            success: function (response) {
                if(response == 200){
                    showNotification('Comment added','success');
                    hideComments(e);
                    showComments(partner_id, name_partner);
                }
            }
        });
        return false;
    }
};

let deleteComments = (e, id) => {
    e.preventDefault();
    let partner_id = $('#partner_id').attr('data-user-id');
    let name_partner = $('#partner_id').attr('data-user-name');
    $.ajax({
        url: "/adm/ccc/debtors/delete_comment",
        type: "POST",
        data: {action : 'delete_comment', id : id},
        cache: false,
        success: function (response) {
            if(response == 200){
                showNotification('Comment deleted','success');
                showComments(partner_id, name_partner);
            }
        }
    });
    return false;
};

let hideComments = (e) => {
    e.preventDefault();
    $(e.target).parents('.form-add-comment').hide();
};

let callIsOver = (e) => {
    e.preventDefault();
    $.ajax({
        url: "/adm/ccc/debtors/call_is_over",
        type: "POST",
        data: {action : 'call_over'},
        cache: false,
        success: function (response) {
            let obj = JSON.parse(response);
            if(obj.code == 200){
                showNotification(obj.text,'success');
            }
        }
    });
    return false;
};