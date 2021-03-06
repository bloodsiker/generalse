<?php

return [

    /*********** REPAIRS REE   =>   CRM  ***********/
    'adm/repairs_ree/crm/s/([a-z0-9-_?&]+)' => 'umbrella/repairs_ree/Crm@search/$1',
    'adm/repairs_ree/crm/([a-z0-9-_?&]+)' => 'umbrella/repairs_ree/Crm@index/$1',
    'adm/repairs_ree/crm' => 'umbrella/repairs_ree/Crm@index',

    /*********** REPAIRS REE   =>   MDS  ***********/
    'adm/repairs_ree/mds/s/([a-z0-9-_?&]+)' => 'umbrella/repairs_ree/Mds@search/$1',
    'adm/repairs_ree/mds/([a-z0-9-_?&]+)' => 'umbrella/repairs_ree/Mds@index/$1',
    'adm/repairs_ree/mds' => 'umbrella/repairs_ree/Mds@index',

    'adm/repairs_ree' => 'umbrella/repairs_ree/Repairs@index',

    /*********** ENGINEERS  ***********/
    'adm/engineers/dashboard' => 'umbrella/engineers/Dashboard@index',

    'adm/engineers/kpi' => 'umbrella/engineers/Kpi@index',

    'adm/engineers/repairs/ajax([a-z0-9-_?&]+)' => 'umbrella/engineers/Repairs@ajax/$1',
    'adm/engineers/repairs' => 'umbrella/engineers/Repairs@index',

    'adm/engineers/returns' => 'umbrella/engineers/Returns@index',

    'adm/engineers/disassembly' => 'umbrella/engineers/Disassembly@index',

    'adm/engineers' => 'umbrella/engineers/Engineers@index',


    'adm/innovation/ajax_action' => 'umbrella/Innovation@ajaxAction',

    /*********** LOG  ***********/
    'adm/user/ajax_logs' => 'umbrella/Log@ajaxLoad',
    'adm/user/logs' => 'umbrella/Log@logs',


    /*********** USER DENIED  ***********/
    'adm/user/denied/([0-9]+)/([0-9]+)/([0-9]+)' => 'umbrella/User@userDenied/$1/$2/$3',
    'adm/user/denied/([0-9]+)/([0-9]+)' => 'umbrella/User@userDenied/$1/$2',
    'adm/user/denied/([0-9]+)' => 'umbrella/User@userDenied/$1',


    /*********** CONTROL USER  ***********/
    'adm/user/control/delete/([0-9]+)/([0-9]+)' => 'umbrella/User@userControlDelete/$1/$2',
    'adm/user/control/multi-delete' => 'umbrella/User@userControlMultiDelete',
    'adm/user/control/([0-9]+)' => 'umbrella/User@userControl/$1',


    /*********** USER ADDRESS  ***********/
    'adm/user/address/delete/([0-9]+)' => 'umbrella/User@userAddressDelete/$1',
    'adm/user/address/update' => 'umbrella/User@userAddressUpdate',
    'adm/user/address/([0-9]+)' => 'umbrella/User@userAddress/$1',


    /*********** USERS  ***********/
    'adm/user/delete/([0-9]+)' => 'umbrella/User@delete/$1',
    'adm/user/update/([0-9]+)' => 'umbrella/User@update/$1',
    'adm/user/show_list_func' => 'umbrella/User@showListFunc',
    'adm/user/info_gm_user' => 'umbrella/User@infoGmUser',
    'adm/user/ajax_action' => 'umbrella/User@ajaxAction',
    'adm/user/check_login' => 'umbrella/User@checkUserLogin',
    'adm/user/add' => 'umbrella/User@addUser',
    'adm/users' => 'umbrella/User@index',


    /*********** GROUP  ***********/
    'adm/group/denied/([0-9]+)/([0-9]+)/([0-9]+)' => 'umbrella/Group@groupDenied/$1/$2/$3',
    'adm/group/denied/([0-9]+)/([0-9]+)' => 'umbrella/Group@groupDenied/$1/$2',
    'adm/group/denied/([0-9]+)' => 'umbrella/Group@groupDenied/$1',
    'adm/group/delete/user/([0-9]+)/([0-9]+)' => 'umbrella/Group@deleteUser/$1/$2',
    'adm/group/delete/stock/([0-9]+)' => 'umbrella/Group@deleteStock/$1',
    'adm/group/([0-9]+)/stock/([a-z0-9-_?&]+)' => 'umbrella/Group@stock/$1/$2',
    'adm/group/([0-9]+)' => 'umbrella/Group@view/$1',
    'adm/group/add' => 'umbrella/Group@addGroup',


    /*********** COUNTRY  ***********/
    'adm/country/delete/([0-9]+)' => 'umbrella/Country@delete/$1',
    'adm/country/update/([0-9]+)' => 'umbrella/Country@update/$1',
    'adm/country/add' => 'umbrella/Country@addCountry',


    /*********** PSR  ***********/
    'adm/psr/activity' => 'umbrella/psr/PsrActivity@index',

    'adm/psr/test' => 'umbrella/psr/Psr@test',
    'adm/psr/show_upload_file' => 'umbrella/psr/Psr@showUploadFile',
    'adm/psr/export' => 'umbrella/psr/Psr@export',
    'adm/psr/psr_ajax' => 'umbrella/psr/Psr@psrAjax',
    'adm/psr/s/([a-z0-9-_?&]+)' => 'umbrella/psr/Psr@search/$1',
    'adm/psr/ua([a-z0-9-_?&]+)' => 'umbrella/psr/Psr@index/$1',
    'adm/psr/ua' => 'umbrella/psr/Psr@index',


    /*********** CCC  ***********/
    'adm/ccc/tree_knowledge/customer-([a-z0-9-]+)/cat-([0-9]+)/article-([a-z0-9-_?&]+)' => 'umbrella/ccc/TreeKnowledge@viewArticle/$1/$2/$3',
    'adm/ccc/tree_knowledge/customer-([a-z0-9-]+)/s/([a-z0-9-_?&]+)' => 'umbrella/ccc/TreeKnowledge@search/$1/$2',
    'adm/ccc/tree_knowledge/customer-([a-z0-9-]+)/cat-([0-9]+)' => 'umbrella/ccc/TreeKnowledge@articlesByCategory/$1/$2',
    'adm/ccc/tree_knowledge/customer-([a-z0-9-]+)/cat-popular' => 'umbrella/ccc/TreeKnowledge@popularCategory/$1',
    'adm/ccc/tree_knowledge/customer-([a-z0-9-]+)' => 'umbrella/ccc/TreeKnowledge@index/$1',

    'adm/ccc/tree_knowledge/category/edit/([a-z0-9-]+)' => 'umbrella/ccc/Category@categoryEdit/$1',
    'adm/ccc/tree_knowledge/category/([a-z0-9-]+)' => 'umbrella/ccc/Category@category/$1',
    'adm/ccc/tree_knowledge/category' => 'umbrella/ccc/Category@category',

    'adm/ccc/tree_knowledge/article/delete/([0-9-]+)' => 'umbrella/ccc/Article@deleteArticle/$1',
    'adm/ccc/tree_knowledge/article/edit/([a-z0-9-]+)' => 'umbrella/ccc/Article@editArticle/$1',
    'adm/ccc/tree_knowledge/articles' => 'umbrella/ccc/Article@index',

    'adm/ccc/kpi/date-([a-z0-9-_?&]+)' => 'umbrella/ccc/Kpi@index/$1',
    'adm/ccc/kpi/import-kpi' => 'umbrella/ccc/Kpi@importKpi',
    'adm/ccc/kpi' => 'umbrella/ccc/Kpi@index',

    'adm/ccc/debtors/call_is_over' => 'umbrella/ccc/Debtors@callIsOver',
    'adm/ccc/debtors/delete_comment' => 'umbrella/ccc/Debtors@deleteComment',
    'adm/ccc/debtors/add_comment' => 'umbrella/ccc/Debtors@addComment',
    'adm/ccc/debtors/show_comments' => 'umbrella/ccc/Debtors@showComments',
    'adm/ccc/debtors/filter([a-z0-9-_?&]+)' => 'umbrella/ccc/Debtors@index/$1',
    'adm/ccc/debtors' => 'umbrella/ccc/Debtors@index',

    'adm/ccc' => 'umbrella/ccc/CustomerCareCenter@index',


    /*********** OTHER REQUEST  ***********/
    'adm/crm/other-request/request_ajax' => 'umbrella/crm/OtherRequest@requestAjax',
    'adm/crm/other-request/import' => 'umbrella/crm/OtherRequest@requestImport',
    'adm/crm/other-request' => 'umbrella/crm/OtherRequest@index',


    /*********** REQUEST  ***********/
    'adm/crm/export/request' => 'umbrella/crm/Request@exportRequests',
    'adm/crm/request/all__ukraine_price' => 'umbrella/crm/Request@allUkrainePrice',
    'adm/crm/request/upload_price' => 'umbrella/crm/Request@uploadPrice',
    'adm/crm/request/list_analog' => 'umbrella/crm/Request@listAnalog',
    'adm/crm/request/request_ajax' => 'umbrella/crm/Request@requestAjax',
    'adm/crm/request/price_part_ajax' => 'umbrella/crm/Request@pricePartNumAjax',
    'adm/crm/request/delete/([0-9]+)' => 'umbrella/crm/Request@requestDelete/$1',
    'adm/crm/request/edit_status' => 'umbrella/crm/Request@editStatusFromExcel',
    'adm/crm/request/import' => 'umbrella/crm/Request@requestImport',
    'adm/crm/request/([a-z0-9-_?&]+)' => 'umbrella/crm/Request@index/$1',
    'adm/crm/request' => 'umbrella/crm/Request@index',


    /*********** BATCH  ***********/
    'adm/crm/export/batch' => 'umbrella/crm/Batch@exportBatch',


    /*********** BACKLOG  ***********/
    'adm/crm/export/backlog' => 'umbrella/crm/BacklogAnalysis@exportBacklog',
    'adm/crm/backlog' => 'umbrella/crm/BacklogAnalysis@index',


    /*********** SUPPLY  ***********/
    'adm/crm/import_add_parts' => 'umbrella/crm/Supply@importAddParts',
    'adm/crm/show_supply' => 'umbrella/crm/Supply@showDetailSupply',
    'adm/crm/supply_action_ajax' => 'umbrella/crm/Supply@actionSupplyAjax',
    'adm/crm/supply_ajax' => 'umbrella/crm/Supply@supplyAjax',
    'adm/crm/supply' => 'umbrella/crm/Supply@supply',


    /*********** PURCHASE  ***********/
    'adm/crm/export/purchase' => 'umbrella/crm/Purchase@exportPurchase',
    'adm/crm/purchase/s/([a-z0-9-_?&]+)' => 'umbrella/crm/Purchase@search/$1',
    'adm/crm/purchase/([a-z0-9-_?&]+)' => 'umbrella/crm/Purchase@purchase/$1',
    'adm/crm/purchase_part_num_ajax' => 'umbrella/crm/Purchase@purchasePartNumAjax',
    'adm/crm/show_purchses' => 'umbrella/crm/Purchase@showDetailPurchases',
    'adm/crm/purchase_ajax' => 'umbrella/crm/Purchase@purchaseAjax',
    'adm/crm/purchase_success' => 'umbrella/crm/Purchase@purchaseSuccess',
    'adm/crm/purchase' => 'umbrella/crm/Purchase@purchase',


    /*************** STOCKS  ***************/
    'adm/crm/stocks/s/([a-z0-9-_?&]+)' => 'umbrella/crm/Stock@search/$1',
    'adm/crm/stocks/list_products/s/([a-z0-9-_?&]+)' => 'umbrella/crm/Stock@searchListProducts/$1',
    'adm/crm/stocks/list_products' => 'umbrella/crm/Stock@listProducts',
    'adm/crm/stocks_ajax' => 'umbrella/crm/Stock@stockAjax',
    'adm/crm/stocks' => 'umbrella/crm/Stock@stocks',


    /*************** RETURNS  ***************/
    'adm/crm/export/returns/([a-z0-9-_?&]+)' => 'umbrella/crm/Return@exportReturns/$1',
    'adm/crm/returns/filter/([a-z0-9-_?&]+)' => 'umbrella/crm/Return@filterReturns/$1',
    'adm/crm/returns/s/([a-z0-9-_?&]+)' => 'umbrella/crm/Return@search/$1',
    'adm/crm/import_returns' => 'umbrella/crm/Return@importReturns',
    'adm/crm/returns_upload' => 'umbrella/crm/Return@returnsUpload',
    'adm/crm/returns_ajax' => 'umbrella/crm/Return@returnsAjax',
    'adm/crm/returns' => 'umbrella/crm/Return@returns',


    /*************** ORDER  ***************/
    'adm/crm/export/orders' => 'umbrella/crm/Order@exportOrders',
    'adm/crm/orders/s/([a-z0-9-_?&]+)' => 'umbrella/crm/Order@search/$1',
    'adm/crm/orders/([a-z0-9-_?&]+)' => 'umbrella/crm/Order@orders/$1',
    'adm/crm/orders_part_num_ajax' => 'umbrella/crm/Order@ordersPartNumAjax',
    'adm/crm/show_orders' => 'umbrella/crm/Order@showDetailOrders',
    'adm/crm/orders_action' => 'umbrella/crm/Order@ordersAction',
    'adm/crm/orders_ajax' => 'umbrella/crm/Order@ordersAjax',
    'adm/crm/orders_success' => 'umbrella/crm/Order@ordersSuccess',
    'adm/crm/orders' => 'umbrella/crm/Order@orders',


    /*********** DISASSEMBLY  ***********/
    'adm/crm/export/disassembly/([a-z0-9-_?&]+)' => 'umbrella/crm/Disassembly@exportDisassembly/$1',
    'adm/crm/show_disassembly' => 'umbrella/crm/Disassembly@showDetailDisassembl',
    'adm/crm/disassembly_action_ajax' => 'umbrella/crm/Disassembly@disassemblyActionAjax',
    'adm/crm/disassembly_ajax' => 'umbrella/crm/Disassembly@disassemblyAjax',
    'adm/crm/disassembly_list/([a-z0-9-_?&]+)' => 'umbrella/crm/Disassembly@disassemblyResult/$1',
    'adm/crm/disassembly_list' => 'umbrella/crm/Disassembly@disassemblyResult',
    'adm/crm/disassembly/s/([a-z0-9-_?&]+)' => 'umbrella/crm/Disassembly@search/$1',
    'adm/crm/disassembly' => 'umbrella/crm/Disassembly@disassembly',


    /*************** MOTO  ***************/
    'adm/crm/moto_serial_num_ajax' => 'umbrella/crm/Moto@motoSerialNumAjax',
    'adm/crm/show_moto' => 'umbrella/crm/Moto@showMoto',
    'adm/crm/moto_part_num_ajax' => 'umbrella/crm/Moto@motoPartNumAjax',
    'adm/crm/moto/([a-z0-9-_?&]+)' => 'umbrella/crm/Moto@moto/$1',
    'adm/crm/moto' => 'umbrella/crm/Moto@moto',

    'adm/crm' => 'umbrella/crm/Crm@index',


    /*********** LITHOGRAPHER  ***********/
    'adm/lithographer/s/([a-z0-9-_?&]+)' => 'umbrella/Lithographer@search/$1',
    'adm/lithographer/delete/([0-9]+)' => 'umbrella/Lithographer@delete/$1',
    'adm/lithographer/edit/([0-9]+)' => 'umbrella/Lithographer@edit/$1',
    'adm/lithographer/file_delete' => 'umbrella/Lithographer@fileDelete',
    'adm/lithographer/file_download' => 'umbrella/Lithographer@fileDownload',
    'adm/lithographer/list' => 'umbrella/Lithographer@list',
    'adm/lithographer/([a-z0-9-_?&]+)/view/([0-9]+)' => 'umbrella/Lithographer@view/$1/$2',
    'adm/lithographer/forms' => 'umbrella/Lithographer@forms',
    'adm/lithographer/([a-z0-9-_?&]+)' => 'umbrella/Lithographer@categories/$1',


    /*********** REFUND REQUEST  ***********/
    'adm/refund_request/filter/([a-z0-9-_?&]+)' => 'umbrella/RefundRequest@filterRequest/$1',
    'adm/refund_request/registration' => 'umbrella/RefundRequest@index',
    'adm/refund_request/view' => 'umbrella/RefundRequest@viewRequest',
    'adm/refund_request/thank_you_page' => 'umbrella/RefundRequest@thankYouPage',
    'adm/part_num_ajax' => 'umbrella/RefundRequest@partNumAjax',
    'adm/request_ajax' => 'umbrella/RefundRequest@requestAjax',


    /*********** KPI  ***********/
    'adm/result/([a-z0-9-_?&\.]+)' => 'umbrella/Kpi@result/$1',
    'adm/kpi/usage/([a-z0-9-_?&\.]+)' => 'umbrella/Kpi@usage/$1',
    'adm/kpi/show-problem' => 'umbrella/Kpi@showProblem',
    'adm/kpi/import' => 'umbrella/Kpi@import',
    'adm/kpi' => 'umbrella/Kpi@index',

    'adm/risks' => 'umbrella/Risk@risks',
    'adm/access_denied' => 'umbrella/Admin@access',
    'adm/return_my_account' => 'umbrella/Admin@returnMyAccount',
    'adm/re-login?([a-z0-9-_?&\.]+)' => 'umbrella/Admin@reLogin/$1',
    'adm/logout' => 'umbrella/Admin@logout',
    'auth' => 'umbrella/Admin@auth',


    'test-export' => 'Site@export',


    /*********** Admin site  ***********/

    'adm/site/service-center/delete/([0-9]+)' => 'umbrella/site/ServiceCenter@delete/$1',
    'adm/site/service-center/edit/([0-9]+)' => 'umbrella/site/ServiceCenter@edit/$1',
    'adm/site/service-center/add' => 'umbrella/site/ServiceCenter@add',
    'adm/site/service-center' => 'umbrella/site/ServiceCenter@all',

    'adm/site/news/delete/([a-z0-9-_?&\.]+)' => 'umbrella/site/News@delete/$1',
    'adm/site/news/edit/([a-z0-9-_?&\.]+)' => 'umbrella/site/News@edit/$1',
    'adm/site/news/add' => 'umbrella/site/News@add',
    'adm/site/news' => 'umbrella/site/News@allNews',

    'adm/site/vacancy/delete/([a-z0-9-_?&\.]+)' => 'umbrella/site/Vacancy@delete/$1',
    'adm/site/vacancy/edit/([a-z0-9-_?&\.]+)' => 'umbrella/site/Vacancy@edit/$1',
    'adm/site/vacancy/add' => 'umbrella/site/Vacancy@add',
    'adm/site/vacancy' => 'umbrella/site/Vacancy@allVacancy',

];
