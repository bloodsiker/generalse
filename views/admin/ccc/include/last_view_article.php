<div class="row header-content" style="margin-top: 30px; margin-bottom: 50px;">
    <div class="medium-12 small-12 top-gray columns">
        <h1>Зона предыдущих просмотров</h1>
    </div>
    <div class="medium-12 small-12 bottom-gray colmns">
        <div class="row align-bottom">
            <div class="medium-12 small-12 columns">
                <div class="row align-bottom">

                    <?php $lastViewArticle = \Umbrella\Models\ccc\KnowledgeCatalog::getLastVisitArticle($user->id_user)?>

                    <?php foreach ($lastViewArticle as $lastArticle):?>
                    <div class="medium-3 item small-12 columns">
                        <a href="/adm/ccc/tree_knowledge/customer-<?= $lastArticle['customer']?>/article-<?= $lastArticle['id_article']?>">
                            <div class="last-article">
                                <h4><?= $lastArticle['name']?></h4>
                                <p><?= $lastArticle['title']?></p>
                            </div>
                        </a>
                    </div>
                    <?php endforeach;?>

                </div>
            </div>
        </div>
    </div>
</div>