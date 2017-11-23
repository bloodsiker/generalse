<div id = "toTop" > ^ Back to TOP </div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.7.1/clipboard.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
<script src="/template/admin/js/vendor/jquery.js"></script>
<script src="/template/admin/js/vendor/js-ui.js"></script>
<script src="/template/admin/js/calendar-local-eng.js"></script>
<script src="/template/admin/js/vendor/what-input.js"></script>
<script src="/template/admin/js/vendor/foundation.min.js"></script>
<script src="/template/admin/js/app.js"></script>
<script src="/template/admin/js/main.js?v.1.8.2"></script>
<script src="/template/admin/js/jquery.tablesort.min.js"></script>
<script src="/template/admin/js/object.js"></script>
<script src="/template/admin/js/kpi.js"></script>
<script src="/template/admin/plugins/jquery-search/jquery.filtertable.min.js"></script>

<!-- switch  -->
<?php
if (Umbrella\components\Url::Is_url($_SERVER['REQUEST_URI'], '/dashboard')) echo "<script src='/template/admin/js/dashboard.js'></script>";
if (Umbrella\components\Url::Is_url($_SERVER['REQUEST_URI'], '/user')) echo "<script src='/template/admin/js/users.js?v.1.9.2'></script>";
if (Umbrella\components\Url::Is_url($_SERVER['REQUEST_URI'], '/crm/stocks')) echo "<script src='/template/admin/js/stocks.js?v.1.6'></script>";
if (Umbrella\components\Url::Is_url($_SERVER['REQUEST_URI'], '/crm/returns')) echo "<script src='/template/admin/js/returns.js'></script>";
if (Umbrella\components\Url::Is_url($_SERVER['REQUEST_URI'], '/crm/orders')) echo "<script src='/template/admin/js/orders.js?v.1.6'></script>";
if (Umbrella\components\Url::Is_url($_SERVER['REQUEST_URI'], '/crm/purchase')) echo "<script src='/template/admin/js/purchase.js?v.1.5'></script>";
if (Umbrella\components\Url::Is_url($_SERVER['REQUEST_URI'], '/crm/disassembly')) echo "<script src='/template/admin/js/disassembly.js?v.1.5.0'></script>";
if (Umbrella\components\Url::Is_url($_SERVER['REQUEST_URI'], '/crm/moto')) echo "<script src='/template/admin/js/moto.js'></script>";
if (Umbrella\components\Url::Is_url($_SERVER['REQUEST_URI'], '/adm/psr')) echo "<script src='/template/admin/js/psr.js?v.1.6'></script>";
if (Umbrella\components\Url::Is_url($_SERVER['REQUEST_URI'], '/crm/supply')) echo "<script src='/template/admin/js/supply.js?v.1.6'></script>";
if (Umbrella\components\Url::Is_url($_SERVER['REQUEST_URI'], '/crm/request')) echo "<script src='/template/admin/js/vendor/jquery.form.js'></script>
<script src='/template/admin/js/request.js?v.2.1.1'></script>";
if (Umbrella\components\Url::Is_url($_SERVER['REQUEST_URI'], '/crm/other-request')) echo "<script src='/template/admin/js/other_request.js'></script>";
if (Umbrella\components\Url::Is_url($_SERVER['REQUEST_URI'], '/ccc')) echo "<script src='/template/admin/js/ccc/knowledge.js'></script>";
if (Umbrella\components\Url::Is_url($_SERVER['REQUEST_URI'], '/adm/lithographer') ||
    Umbrella\components\Url::Is_url($_SERVER['REQUEST_URI'], '/adm/ccc/tree_knowledge/article')) echo "
  <script src='https://cdnjs.cloudflare.com/ajax/libs/video.js/5.0.0/video.min.js'></script>
  <script src='/template/admin/js/lithographer.js'></script>
  <script src='/template/admin/plugins/ckeditor/ckeditor.js'></script>
  ";

?>

<script>
    new Clipboard('.btn-clip');
</script>

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


<?php if(Umbrella\components\Url::Is_url($_SERVER['REQUEST_URI'], '/adm/lithographer') ||
        Umbrella\components\Url::Is_url($_SERVER['REQUEST_URI'], '/adm/ccc/tree_knowledge/article')):?>
    <script>
        CKEDITOR.replace('ck_rules');
        CKEDITOR.replace('ck_tips');
        CKEDITOR.replace('edit');

        //    CKEDITOR.replace( 'ck_rules',
        //        {
        //            filebrowserBrowseUmbrella\components\Url : '/ckfinder/ckfinder.html',
        //            filebrowserImageBrowseUmbrella\components\Url : '/ckfinder/ckfinder.html?type=Images',
        //            filebrowserFlashBrowseUmbrella\components\Url : '/ckfinder/ckfinder.html?type=Flash',
        //            filebrowserUploadUmbrella\components\Url : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        //            filebrowserImageUploadUmbrella\components\Url : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        //            filebrowserFlashUploadUmbrella\components\Url : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
        //        });
    </script>
<?php endif;?>



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
