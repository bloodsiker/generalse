<?php

return array(


    /*********** LOG  ***********/
    'adm/user/ajax_logs' => 'umbrella/Log@ajaxLoad',
    'adm/user/logs' => 'umbrella/Log@logs',


    /*********** USER DENIED  ***********/
    'adm/user/denied/([0-9]+)/([0-9]+)/([0-9]+)' => 'umbrella/User@userDenied/$1/$2/$3',
    'adm/user/denied/([0-9]+)/([0-9]+)' => 'umbrella/User@userDenied/$1/$2',
    'adm/user/denied/([0-9]+)' => 'umbrella/User@userDenied/$1',


    /*********** CONTROL USER  ***********/
    'adm/user/control/delete/([0-9]+)/([0-9]+)' => 'umbrella/User@userControlDelete/$1/$2',
    'adm/user/control/([0-9]+)' => 'umbrella/User@userControl/$1',


    /*********** WEEKEND  ***********/
    'adm/user/weekend/update/([0-9]+)' => 'umbrella/User@userWeekendUpdate/$1',
    'adm/user/weekend/delete/([0-9]+)' => 'umbrella/User@userWeekendDelete/$1',
    'adm/user/weekend/([0-9]+)' => 'umbrella/User@userWeekend/$1',


    /*********** USERS  ***********/
    'adm/user/delete/([0-9]+)' => 'umbrella/User@delete/$1',
    'adm/user/update/([0-9]+)' => 'umbrella/User@update/$1',
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


    /*********** BRANCH  ***********/
    'adm/branch/delete/([0-9]+)/([0-9]+)' => 'umbrella/Branch@delete/$1/$2',
    'adm/branch/view/([0-9]+)' => 'umbrella/Branch@view/$1',
    'adm/branch/add' => 'umbrella/Branch@addBranch',


    /*********** DASHBOARD  ***********/
    'adm/dashboard/balance-u/([0-9]+)/([a-z0-9-_?&]+)' => 'umbrella/Dashboard@userBalance/$1/$2',
    'adm/dashboard/balance-u/([0-9]+)' => 'umbrella/Dashboard@userBalance/$1',
    'adm/dashboard/request-payment' => 'umbrella/Dashboard@requestPayment',
    'adm/dashboard/task' => 'umbrella/Dashboard@task',
    'adm/dashboard/b-users' => 'umbrella/Dashboard@users',
    'adm/dashboard/ajax_balance' => 'umbrella/Dashboard@ajaxBalance',
    'adm/dashboard/ajax_show_info' => 'umbrella/Dashboard@ajaxShowInfo',
    'adm/dashboard/pay' => 'umbrella/Dashboard@postPay',
    'adm/dashboard/([a-z0-9-_?&]+)' => 'umbrella/Dashboard@index/$1',
    'adm/dashboard' => 'umbrella/Dashboard@index',


    /*********** CCC  ***********/
    'adm/ccc/tree_knowledge/customer-([a-z0-9-]+)/cat-([0-9]+)/article-([a-z0-9-_?&]+)' => 'umbrella/ccc/TreeKnowledge@viewArticle/$1/$2/$3',
    'adm/ccc/tree_knowledge/customer-([a-z0-9-]+)/cat-([0-9]+)' => 'umbrella/ccc/TreeKnowledge@articlesByCategory/$1/$2',
    'adm/ccc/tree_knowledge/customer-([a-z0-9-]+)/cat-popular' => 'umbrella/ccc/TreeKnowledge@popularCategory/$1',
    'adm/ccc/tree_knowledge/customer-([a-z0-9-]+)' => 'umbrella/ccc/TreeKnowledge@index/$1',

    'adm/ccc/tree_knowledge/category/edit/([a-z0-9-]+)' => 'umbrella/ccc/Category@categoryEdit/$1',
    'adm/ccc/tree_knowledge/category/([a-z0-9-]+)' => 'umbrella/ccc/Category@category/$1',
    'adm/ccc/tree_knowledge/category' => 'umbrella/ccc/Category@category',

    'adm/ccc/tree_knowledge/article/delete/([0-9-]+)' => 'umbrella/ccc/Article@deleteArticle/$1',
    'adm/ccc/tree_knowledge/article/edit/([a-z0-9-]+)' => 'umbrella/ccc/Article@editArticle/$1',
    'adm/ccc/tree_knowledge/articles' => 'umbrella/ccc/Article@index',

    'adm/ccc' => 'umbrella/ccc/CustomerCareCenter@index',


    /*********** OTHER REQUEST  ***********/
    'adm/crm/other-request/request_ajax' => 'umbrella/crm/OtherRequest@requestAjax',
    'adm/crm/other-request/import' => 'umbrella/crm/OtherRequest@requestImport',
    'adm/crm/other-request' => 'umbrella/crm/OtherRequest@index',


    /*********** REQUEST  ***********/
    'adm/crm/request/request_ajax' => 'umbrella/crm/Request@requestAjax',
    'adm/crm/request/price_part_ajax' => 'umbrella/crm/Request@pricePartNumAjax',
    'adm/crm/request/delete/([0-9]+)' => 'umbrella/crm/Request@requestDelete/$1',
    'adm/crm/request/import' => 'umbrella/crm/Request@requestImport',
    'adm/crm/request/completed' => 'umbrella/crm/Request@completedRequest',
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
    'adm/crm/purchase/([a-z0-9-_?&]+)' => 'umbrella/crm/Purchase@purchase/$1',
    'adm/crm/purchase_part_num_ajax' => 'umbrella/crm/Purchase@purchasePartNumAjax',
    'adm/crm/show_purchses' => 'umbrella/crm/Purchase@showDetailPurchases',
    'adm/crm/purchase_ajax' => 'umbrella/crm/Purchase@purchaseAjax',
    'adm/crm/purchase_success' => 'umbrella/crm/Purchase@purchaseSuccess',
    'adm/crm/purchase' => 'umbrella/crm/Purchase@purchase',


    /*********** STOCKS  ***********/
    'adm/crm/stocks/([a-z0-9-_?&]+)' => 'umbrella/crm/Stock@stocks/$1',
    'adm/crm/stocks' => 'umbrella/crm/Stock@stocks',


    /*********** RETURNS  ***********/
    'adm/crm/export/returns/([a-z0-9-_?&]+)' => 'umbrella/crm/Return@exportReturns/$1',
    'adm/crm/returns/filter/([a-z0-9-_?&]+)' => 'umbrella/crm/Return@filterReturns/$1',
    'adm/crm/import_returns' => 'umbrella/crm/Return@importReturns',
    'adm/crm/returns_ajax' => 'umbrella/crm/Return@returnsAjax',
    'adm/crm/returns' => 'umbrella/crm/Return@returns',


    /*********** ORDER  ***********/
    'adm/crm/export/orders' => 'umbrella/crm/Order@exportOrders',
    'adm/crm/orders/([a-z0-9-_?&]+)' => 'umbrella/crm/Order@orders/$1',
    'adm/crm/orders_part_num_ajax' => 'umbrella/crm/Order@ordersPartNumAjax',
    'adm/crm/show_orders' => 'umbrella/crm/Order@showDetailOrders',
    'adm/crm/orders_action' => 'umbrella/crm/Order@ordersAction',
    'adm/crm/orders_ajax' => 'umbrella/crm/Order@ordersAjax',
    'adm/crm/orders_success' => 'umbrella/crm/Order@ordersSuccess',
    'adm/crm/orders' => 'umbrella/crm/Order@orders',


    /*********** DISASSEMBLY  ***********/
    'adm/crm/export/disassembly/([a-z0-9-_?&]+)' => 'umbrella/crm/Disassembly@exportDisassembly/$1',
    'adm/crm/disassembly_all' => 'umbrella/crm/Disassembly@allDisassembl',
    'adm/crm/show_disassembly' => 'umbrella/crm/Disassembly@showDetailDisassembl',
    'adm/crm/disassembly_action_ajax' => 'umbrella/crm/Disassembly@disassemblyActionAjax',
    'adm/crm/disassembly_ajax' => 'umbrella/crm/Disassembly@disassemblyAjax',
    'adm/crm/disassembly_list/([a-z0-9-_?&]+)' => 'umbrella/crm/Disassembly@disassemblyResult/$1',
    'adm/crm/disassembly_list' => 'umbrella/crm/Disassembly@disassemblyResult',
    'adm/crm/disassembly/test' => 'umbrella/crm/Disassembly@test1',
    'adm/crm/disassembly' => 'umbrella/crm/Disassembly@disassembly',


    /*********** MOTO  ***********/
    'adm/crm/moto_serial_num_ajax' => 'umbrella/crm/Moto@motoSerialNumAjax',
    'adm/crm/show_moto' => 'umbrella/crm/Moto@showMoto',
    'adm/crm/moto_part_num_ajax' => 'umbrella/crm/Moto@motoPartNumAjax',
    'adm/crm/moto/([a-z0-9-_?&]+)' => 'umbrella/crm/Moto@moto/$1',
    'adm/crm/moto' => 'umbrella/crm/Moto@moto',


    /*********** PSR  ***********/
    'adm/crm/psr' => 'umbrella/crm/Psr@psr',


    /*********** LITHOGRAPHER  ***********/
    'adm/lithographer/s/([a-z0-9-_?&]+)' => 'umbrella/Lithographer@search/$1',
    'adm/lithographer/delete/([0-9]+)' => 'umbrella/Lithographer@delete/$1',
    'adm/lithographer/edit/([0-9]+)' => 'umbrella/Lithographer@edit/$1',
    'adm/lithographer/list' => 'umbrella/Lithographer@list',
    'adm/lithographer/tips/([0-9]+)' => 'umbrella/Lithographer@view/$1',
    'adm/lithographer/rules/([0-9]+)' => 'umbrella/Lithographer@view/$1',
    'adm/lithographer/forms' => 'umbrella/Lithographer@forms',
    'adm/lithographer/video' => 'umbrella/Lithographer@video',
    'adm/lithographer/tips' => 'umbrella/Lithographer@tips',
    'adm/lithographer/rules' => 'umbrella/Lithographer@rules',


    /*********** REFUND REQUEST  ***********/
    'adm/refund_request/filter/([a-z0-9-_?&]+)' => 'umbrella/RefundRequest@filterRequest/$1',
    'adm/refund_request/registration' => 'umbrella/RefundRequest@index',
    'adm/refund_request/view' => 'umbrella/RefundRequest@viewRequest',
    'adm/refund_request/thank_you_page' => 'umbrella/RefundRequest@thankYouPage',
    'adm/part_num_ajax' => 'umbrella/RefundRequest@partNumAjax',
    'adm/request_ajax' => 'umbrella/RefundRequest@requestAjax',
    'adm/refund_request/test' => 'umbrella/RefundRequest@test',


    /*********** KPI  ***********/
    'adm/result/([a-z0-9-_?&\.]+)' => 'umbrella/Kpi@result/$1',
    'adm/kpi/usage/([a-z0-9-_?&\.]+)' => 'umbrella/Kpi@usage/$1',
    'adm/kpi/show-problem' => 'umbrella/Kpi@showProblem',
    'adm/kpi/import' => 'umbrella/Kpi@import',
    'adm/kpi' => 'umbrella/Kpi@index',

    'adm/access_denied' => 'umbrella/Admin@access',
    'adm/logout' => 'umbrella/Admin@logout',
    'auth' => 'umbrella/Admin@auth',

    /*********** SITE  ***********/
    'contact_form' => 'Site@contactForm',
    'contact' => 'Site@contact',
    'career' => 'Site@career',
    'directions' => 'Site@directions',
    'for_business' => 'Site@forBusiness',
    'main' => 'Site@index', // actionIndex в SiteController
);
