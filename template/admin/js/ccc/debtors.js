let showComments = (user_id) => {
    $.ajax({
        url: "/adm/ccc/debtors/show_comments",
        type: "POST",
        data: {action : 'show_comments', user_id : user_id},
        cache: false,
        success: function (response) {
            $('#show-comments').foundation('open');
            $('#container-comments').html(response);
            $('#show-comments').find('#partner_id').attr('data-user-id', user_id);
        }
    });
    return false;
};

let addComments = (e) => {
    console.log(e);
    $(e.target).siblings('.form-add-comment').show();
};

let sendComments = (e, week, year) => {
    e.preventDefault();
    let comment = $('#comment-' + week).val();
    let partner_id = $('#partner_id').attr('data-user-id');
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
                    showComments(partner_id);
                }
            }
        });
        return false;
    }
};

let deleteComments = (e, id) => {
    e.preventDefault();
    let partner_id = $('#partner_id').attr('data-user-id');
    $.ajax({
        url: "/adm/ccc/debtors/delete_comment",
        type: "POST",
        data: {action : 'delete_comment', id : id},
        cache: false,
        success: function (response) {
            if(response == 200){
                showNotification('Comment deleted','success');
                showComments(partner_id);
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