<?php require_once ROOT . '/views/admin/layouts/header.php'; ?>
<div class="row">
    <div class="medium-12 small-12 columns">
        <div class="row header-content">
            <div class="medium-12 small-12 top-gray columns">
                <h1>Lithographer</h1>
            </div>
            <div class="medium-12 small-12 top-gray colmns">
                <div class="row align-bottom">
                    <div class="medium-3 small-12 columns">
                        <button class="button primary tool" data-open="add-content"><i class="fi-plus"></i> Add</button>
                        <a href="/adm/lithographer/list" class="button primary tool"><i class="fi-pencil"></i> Edit</a>
                    </div>
                    <div class="medium-3 medium-offset-6 small-12 columns">
                        <form action="/adm/lithographer/s/" method="get" class="form">
                            <input type="text" class="search-input" placeholder="Search..." name="search">
                            <button class="search-button button primary"><i class="fi-magnifying-glass"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="medium-2 left-bar header-content small-12 columns">
        <ul class="menu">

            <?php require_once 'sidebar.php'; ?>

        </ul>
    </div>
    <div class="medium-10 small-12 columns container-litographer">
        <div class="row content-litographer">
            <?php if (is_array($listVideo)): ?>
                <?php foreach ($listVideo as $video): ?>
                    <?php if(!in_array($video['id'], $listArticlesCloseViewUser)):?>
                        <div class="medium-4 item small-12 columns">
                            <a class="view-video" data-video="<?=$video['file_path'] . $video['file_name']?>">
                                <div class="video-screen">
                                    <h4><?=$video['title']?></h4>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="reveal small" id="video-modal" data-reveal data-close-on-click="true" data-animation-in="fade-in"
     data-animation-out="fade-out"></div>

<div class="reveal small" id="add-content" data-reveal data-close-on-click="true">
    <div class="row align-center">
        <div class="medium-12 text-center small-12 columns">
            <h1>Add content</h1>
        </div>
        <div class="large-12 medium-12 small-12 columns">

            <?php require_once 'forms.php'; ?>

        </div>
    </div>
    <button class="close-button" data-close aria-label="Close reveal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>



<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
