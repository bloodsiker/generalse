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
          </div>
          <div class="medium-3 medium-offset-6 small-12 columns">
            <form action="#" method="get" class="form">
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
      <li>
        <a href="#" class="active">Video Manuals</a>
      </li>
      <li>
        <a href="#" class="">Tips</a>
      </li>
      <li>
        <a href="#" class="">Rules</a>
      </li>
    </ul>
  </div>
  <div class="medium-10 small-12 columns">
    <div class="row content-litographer">
      <div class="medium-4 item small-12 columns">
        <a data-video="/upload/upload_video/video.mp4">
          <div class="video-screen">
            <h4>Title video 1</h4>
          </div>
        </a>
      </div>
      <div class="medium-4 item small-12 columns">
        <a data-video="/upload/upload_video/video.mp4">
          <div class="video-screen">
            <h4>Title video 3</h4>
          </div>
        </a>
      </div>
      <div class="medium-4 item small-12 columns">
        <a data-video="/upload/upload_video/video.mp4">
          <div class="video-screen">
            <h4>Title video 4</h4>
          </div>
        </a>
      </div>
      <div class="medium-4 item small-12 columns">
        <a data-video="/upload/upload_video/video.mp4">
          <div class="video-screen">
            <h4>Title video 5</h4>
          </div>
        </a>
      </div>
    </div>
    <div class="row content-litographer">
      <div class="medium-12 small-12 columns">
        <div class="callout">
          <h5>This is a callout.</h5>
          <p>It has an easy to override visual style, and is appropriately subdued.</p>
          <a href="#">read more...</a>
        </div>
        <div class="callout">
          <h5>This is a callout.</h5>
          <p>It has an easylorem  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur asperiores temporibus, fuga veniam corrupti est, doloremque quod aut architecto minus dolores consequuntur adipisci fugit dolorum. Totam dolorum architecto cum molestias. to override visual style, and is appropriately subdued.</p>
          <a href="#">read more...</a>
        </div>
        <div class="callout">
          <h5>This is a callout.</h5>
          <p>It has an easy to override visual style, and is appropriately subdued.</p>
          <a href="#">read more...</a>
        </div>
        <div class="callout">
          <h5>This is a callout.</h5>
          <p>It has an easy to override visual style, and is appropriately subdued.</p>
          <a href="#">read more...</a>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="reveal small" id="video-modal" data-reveal data-close-on-click="true" data-animation-in="fade-in" data-animation-out="fade-out"></div>

<div class="reveal small" id="add-content" data-reveal data-close-on-click="true" >
  <div class="row align-center">
    <div class="medium-12 text-center small-12 columns">
      <h1>Add content</h1>
    </div>
    <div class="large-12 medium-12 small-12 columns">
      <ul class="tabs" data-tabs id="add-tabs">
        <li class="tabs-title is-active"><a href="#video-manuals" aria-selected="true">Video Manuals</a></li>
        <li class="tabs-title"><a href="#tips">Tips</a></li>
        <li class="tabs-title"><a href="#rules">Rules</a></li>
      </ul>
      <div class="tabs-content" data-tabs-content="add-tabs">
        <div class="tabs-panel is-active" id="video-manuals">
          <form action="#" method="post">
            <div class="row align-bottom ">
              <div class="medium-6 small-12 columns">
                <label>Name</label>
                <input type="text" class="required" required>
              </div>
              <div class="medium-6 small-12 columns">
                <label for="exampleFileUpload" class="button primary">Attach</label>
                <input type="file" id="exampleFileUpload" class="show-for-sr" name="Attach_file[]" multiple="true">
              </div>
              <div class="medium-12 small-12 columns">
                <button class="button primary">add</button>
              </div>
            </div>
          </form>
        </div>
        <div class="tabs-panel" id="tips">
          <form action="#" method="post">
            <div class="row align-bottom ">
              <div class="medium-12 small-12 columns">
                <label>Name</label>
                <input type="text" class="required" required>
              </div>
              <div class="medium-12 small-12 columns">
                <label>Content</label>
                <textarea style="min-height: 200px; background-color: #fff; color: #000;" name="conent"></textarea>
              </div>
              <div class="medium-12 small-12 columns">
                <button class="button primary">add</button>
              </div>
            </div>
          </form>
        </div>
        <div class="tabs-panel" id="rules">
          <form action="#" method="post">
            <div class="row align-bottom ">
              <div class="medium-12 small-12 columns">
                <label>Name</label>
                <input type="text" class="required" required>
              </div>
              <div class="medium-12 small-12 columns">
                <label>Content</label>
                <textarea style="min-height: 200px; background-color: #fff; color: #000;" name="conent"></textarea>
              </div>
              <div class="medium-12 small-12 columns">
                <button class="button primary">add</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <button class="close-button" data-close aria-label="Close reveal" type="button">
    <span aria-hidden="true">&times;</span>
  </button>
</div>


<?php require_once ROOT . '/views/admin/layouts/footer.php'; ?>
