<div id = "toTop" > ^ Back to TOP </div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.7.1/clipboard.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
<script src="/template/admin/js/vendor/jquery.js"></script>
<script src="/template/admin/js/vendor/js-ui.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="/template/admin/js/calendar-local-eng.js"></script>
<script src="/template/admin/js/vendor/what-input.js"></script>
<script src="/template/admin/js/vendor/foundation.min.js"></script>
<script src="/template/admin/js/app.js"></script>
<script src="/template/admin/js/main.js?v.1.8.4"></script>
<script src="/template/admin/js/jquery.tablesort.min.js"></script>
<script src="/template/admin/js/object.js"></script>
<script src="/template/admin/js/kpi.js"></script>
<script src="/template/admin/plugins/jquery-search/jquery.filtertable.min.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src='/template/admin/plugins/ckeditor/ckeditor.js'></script>

<!-- switch  -->
<?php
if (Umbrella\components\Url::Is_url('/dashboard')) echo "<script src='/template/admin/js/dashboard.js'></script>";
if (Umbrella\components\Url::Is_url('/user')) echo "<script src='/template/admin/js/users.js?v.1.9.5'></script>";
if (Umbrella\components\Url::Is_url('/crm/stocks')) echo "<script src='/template/admin/js/stocks.js?v.1.9.1'></script>";
if (Umbrella\components\Url::Is_url('/crm/returns')) echo "<script src='/template/admin/js/returns.js?.1.5.1'></script>";
if (Umbrella\components\Url::Is_url('/crm/orders')) echo "<script src='/template/admin/js/orders.js?v.1.9'></script>";
if (Umbrella\components\Url::Is_url('/crm/purchase')) echo "<script src='/template/admin/js/purchase.js?v.1.6'></script>";
if (Umbrella\components\Url::Is_url('/crm/disassembly')) echo "<script src='/template/admin/js/disassembly.js?v.1.5.0'></script>";
if (Umbrella\components\Url::Is_url('/crm/moto')) echo "<script src='/template/admin/js/moto.js'></script>";
if (Umbrella\components\Url::Is_url('/adm/psr')) echo "<script src='/template/admin/js/psr.js?v.1.8'></script>";
if (Umbrella\components\Url::Is_url('/crm/supply')) echo "<script src='/template/admin/js/supply.js?v.1.7'></script>";
if (Umbrella\components\Url::Is_url('/crm/request')) echo "<script src='/template/admin/js/vendor/jquery.form.js'></script>
<script src='/template/admin/js/request.js?v.2.1.8'></script>";
if (Umbrella\components\Url::Is_url('/crm/other-request')) echo "<script src='/template/admin/js/other_request.js?v.1.5.0'></script>";
if (Umbrella\components\Url::Is_url('/ccc')) echo "<script src='/template/admin/js/ccc/knowledge.js'></script>";
if (Umbrella\components\Url::Is_url('/ccc/debtors')) echo "<script src='/template/admin/js/ccc/debtors.js?v.1.5.2'></script>";
if (Umbrella\components\Url::Is_url('/repairs_ree/mds')) echo "<script src='/template/admin/js/repairs_ree/mds.js?v.1.5.1'></script>";
if (Umbrella\components\Url::Is_url(['/adm/lithographer', '/adm/ccc/tree_knowledge/article'])) echo "
  <script src='https://cdnjs.cloudflare.com/ajax/libs/video.js/5.0.0/video.min.js'></script>
  <script src='/template/admin/js/lithographer.js?v1.5.2'></script>
  ";
if (Umbrella\components\Url::Is_url('/engineers')) echo "<script src='https://www.gstatic.com/charts/loader.js'></script>
    <script src='/template/admin/js/engineers/engineers.js'></script>";
?>

<script>
    $(document).ready(function() {
        $('#wait').addClass('hide');
    });
</script>

<script>
    toastr.options.timeOut = '10000';

    let showNotification = function (message, type) {
        switch (type) {
            case 'success':
                toastr.success(message);
                break;
            case 'error':
                toastr.error(message);
                break;
            case 'warning':
                toastr.warning(message);
                break;
            case 'info':
                toastr.info(message);
                break;
            default:
                toastr.warning('Не известная ошибка!');
        }
    };
</script>

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

<script>
    CKEDITOR.replace('ck_article');
    CKEDITOR.replace('edit');
    CKEDITOR.replace('add');
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
