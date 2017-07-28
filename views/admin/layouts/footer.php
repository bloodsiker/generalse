<div id = "toTop" > ^ Back to TOP </div>
<div id="wait" class="hide">
    <div id="container-wait">
        <h1>Please wait</h1>
        <img src="/template/admin/img/wait.svg" alt="">
    </div>
</div>
<script src="/template/admin/js/vendor/jquery.js"></script>
<script src="/template/admin/js/vendor/js-ui.js"></script>
<script src="/template/admin/js/calendar-local-eng.js"></script>
<script src="/template/admin/js/vendor/what-input.js"></script>
<script src="/template/admin/js/vendor/foundation.js"></script>
<script src="/template/admin/js/app.js"></script>
<script src="/template/admin/js/main.js"></script>
<script src="/template/admin/js/jquery.tablesort.min.js"></script>
<script src="/template/admin/js/object.js"></script>
<script src="/template/admin/js/kpi.js"></script>
<script src="/template/admin/plugins/ckeditor/ckeditor.js"></script>
<script src="/template/admin/plugins/jquery-search/jquery.filtertable.min.js"></script>

<!-- switch  -->
<?php
if (Url::Is_url($_SERVER['REQUEST_URI'], '/dashboard')) echo "<script src='/template/admin/js/dashboard.js'></script>";
if (Url::Is_url($_SERVER['REQUEST_URI'], '/crm/stocks')) echo "<script src='/template/admin/js/stocks.js'></script>";
if (Url::Is_url($_SERVER['REQUEST_URI'], '/crm/returns')) echo "<script src='/template/admin/js/returns.js'></script>";
if (Url::Is_url($_SERVER['REQUEST_URI'], '/crm/orders')) echo "<script src='/template/admin/js/orders.js'></script>";
if (Url::Is_url($_SERVER['REQUEST_URI'], '/crm/purchase')) echo "<script src='/template/admin/js/purchase.js'></script>";
if (Url::Is_url($_SERVER['REQUEST_URI'], '/crm/disassembly')) echo "<script src='/template/admin/js/disassembly.js'></script>";
if (Url::Is_url($_SERVER['REQUEST_URI'], '/crm/moto')) echo "<script src='/template/admin/js/moto.js'></script>";
if (Url::Is_url($_SERVER['REQUEST_URI'], '/crm/psr')) echo "<script src='/template/admin/js/psr.js'></script>";
if (Url::Is_url($_SERVER['REQUEST_URI'], '/crm/supply')) echo "<script src='/template/admin/js/supply.js'></script>";
if (Url::Is_url($_SERVER['REQUEST_URI'], '/crm/request')) echo "<script src='/template/admin/js/request.js'></script>";
if (Url::Is_url($_SERVER['REQUEST_URI'], '/crm/other-request')) echo "<script src='/template/admin/js/other_request.js'></script>";
if (Url::Is_url($_SERVER['REQUEST_URI'], '/ccc')) echo "<script src='/template/admin/js/ccc/knowledge.js'></script>";
if (Url::Is_url($_SERVER['REQUEST_URI'], '/adm/lithographer')) echo "
  <script src='https://cdnjs.cloudflare.com/ajax/libs/video.js/5.0.0/video.min.js'></script>
  <script src='/template/admin/js/lithographer.js'></script>
  ";

?>


<script>
    $(document).ready(function() {
        $('#goods_data').filterTable({ // apply filterTable to all tables on this page
            inputSelector: '#goods_search' // use the existing input instead of creating a new one
        });
    });

    $(document).ready(function() {
        $('#table_refund').filterTable({ // apply filterTable to all tables on this page
            inputSelector: '#goods_search' // use the existing input instead of creating a new one
        });
    });
</script>




<script>
    CKEDITOR.replace('ck_rules');
    CKEDITOR.replace('ck_tips');
    CKEDITOR.replace('edit');
//    CKEDITOR.replace( 'ck_rules',
//        {
//            filebrowserBrowseUrl : '/ckfinder/ckfinder.html',
//            filebrowserImageBrowseUrl : '/ckfinder/ckfinder.html?type=Images',
//            filebrowserFlashBrowseUrl : '/ckfinder/ckfinder.html?type=Flash',
//            filebrowserUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
//            filebrowserImageUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
//            filebrowserFlashUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
//        });
</script>

<script>
$("#date-start").datepicker({
   buttonText: "Choose",
   regional: 'en-GB',
   dateFormat: 'yy-mm-dd'
});
$("#date-end").datepicker({
   buttonText: "Choose",
   regional: 'en-GB',
   dateFormat: 'yy-mm-dd'
});
</script>
</body>

</html>
