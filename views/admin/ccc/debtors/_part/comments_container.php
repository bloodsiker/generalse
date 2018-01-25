<table class="comments-table">
    <thead>
    <tr>
        <?php foreach ($interval as $week): ?>
            <th class="text-center"><?= $week['interval'] ?></th>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>
    <tr valign="top">
        <?php foreach ($interval as $week): ?>
            <td width="25%">
                <?php if($week['add_comment'] == 'true'): ?>
                    <button
                            onclick="addComments(event)"
                            class="btn-add-comment">
                        <i class="fa fa-plus"></i> Add comment
                    </button>
                    <div class="form-add-comment" style="display: none">
                        <form action="" method="post">
                            <textarea name="comment" id="comment-<?= (int)$week['week'] ?>" cols="30" rows="3"></textarea>
                            <div class="clearfix">
                                <button onclick="sendComments(event,<?= (int)$week['week'] ?>, <?= (int)$week['year'] ?>)" class="btn-send-comment blue float-left">Send</button>
                                <button onclick="hideComments(event)" class="btn-cancel-comment red float-right">Cancel</button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
                <?php if(is_array($week['comments'])): ?>
                    <?php foreach ($week['comments'] as $comment): ?>
                    <div class="comment_block">
                        <?php if($week['delete_comment'] == 'true' && $comment['user_id'] == $user->getId()): ?>
                            <button onclick="deleteComments(event, <?= $comment['id'] ?>)" class="comment-delete"><i class="fa fa-trash"></i></button>
                        <?php endif; ?>
                        <?= $comment['comment'] ?>
                        <hr>
                        <div class="clearfix">
                            <span class="float-left"><b><?= $comment['name_partner'] ?></b></span>
                            <time class="comment-date float-right"><?= $comment['date_comment'] ?></time>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </td>
        <?php endforeach; ?>
    </tr>
    </tbody>
</table>