<?php

return array(

    'adm/user/ajax_logs' => 'log/ajaxLoad',
    'adm/user/logs' => 'log/logs',

    'adm/user/denied/([0-9]+)/([0-9]+)/([0-9]+)' => 'user/userDenied/$1/$2/$3',
    'adm/user/denied/([0-9]+)/([0-9]+)' => 'user/userDenied/$1/$2',
    'adm/user/denied/([0-9]+)' => 'user/userDenied/$1',

    'adm/user/control/delete/([0-9]+)/([0-9]+)' => 'user/userControlDelete/$1/$2',
    'adm/user/control/([0-9]+)' => 'user/userControl/$1',

    'adm/user/weekend/update/([0-9]+)' => 'user/userWeekendUpdate/$1',
    'adm/user/weekend/delete/([0-9]+)' => 'user/userWeekendDelete/$1',
    'adm/user/weekend/([0-9]+)' => 'user/userWeekend/$1',

    'adm/user/delete/([0-9]+)' => 'user/delete/$1',
    'adm/user/update/([0-9]+)' => 'user/update/$1',
    'adm/user/check_login' => 'user/checkUserLogin',
    'adm/user/add' => 'user/addUser',
    'adm/users' => 'user/index',


    'adm/group/denied/([0-9]+)/([0-9]+)/([0-9]+)' => 'group/groupDenied/$1/$2/$3',
    'adm/group/denied/([0-9]+)/([0-9]+)' => 'group/groupDenied/$1/$2',
    'adm/group/denied/([0-9]+)' => 'group/groupDenied/$1',
    'adm/group/delete/user/([0-9]+)/([0-9]+)' => 'group/deleteUser/$1/$2',
    'adm/group/delete/stock/([0-9]+)' => 'group/deleteStock/$1',
    'adm/group/([0-9]+)/stock/([a-z0-9-_?&]+)' => 'group/stock/$1/$2',
    'adm/group/([0-9]+)' => 'group/view/$1',
    'adm/group/add' => 'group/addGroup',


    'adm/country/delete/([0-9]+)' => 'country/delete/$1',
    'adm/country/update/([0-9]+)' => 'country/update/$1',
    'adm/country/add' => 'country/addCountry',

    'adm/branch/delete/([0-9]+)/([0-9]+)' => 'branch/delete/$1/$2',
    'adm/branch/view/([0-9]+)' => 'branch/view/$1',
    'adm/branch/add' => 'branch/addBranch',

    'adm/dashboard/balance-u/([0-9]+)/([a-z0-9-_?&]+)' => 'dashboard/userBalance/$1/$2',
    'adm/dashboard/balance-u/([0-9]+)' => 'dashboard/userBalance/$1',
    'adm/dashboard/request-payment' => 'dashboard/requestPayment',
    'adm/dashboard/task' => 'dashboard/task',
    'adm/dashboard/b-users' => 'dashboard/users',
    'adm/dashboard/ajax_balance' => 'dashboard/ajaxBalance',
    'adm/dashboard/ajax_show_info' => 'dashboard/ajaxShowInfo',
    'adm/dashboard/pay' => 'dashboard/postPay',
    'adm/dashboard/([a-z0-9-_?&]+)' => 'dashboard/index/$1',
    'adm/dashboard' => 'dashboard/index',


    'adm/crm/request/request_ajax' => 'request/requestAjax',
    'adm/crm/request/price_part_ajax' => 'request/pricePartNumAjax',
    'adm/crm/request/delete/([0-9]+)' => 'request/requestDelete/$1',
    'adm/crm/request/import' => 'request/requestImport',
    'adm/crm/request/completed' => 'request/completedRequest',
    'adm/crm/request/([a-z0-9-_?&]+)' => 'request/index/$1',
    'adm/crm/request' => 'request/index',

    'adm/crm/export/batch' => 'batch/exportBatch',

    'adm/crm/export/backlog' => 'backlogAnalysis/exportBacklog',
    'adm/crm/backlog' => 'backlogAnalysis/index',

    'adm/crm/show_supply' => 'supply/showDetailSupply',
    'adm/crm/supply_ajax' => 'supply/supplyAjax',
    'adm/crm/supply' => 'supply/supply',

    'adm/crm/export/purchase/([a-z0-9-_?&]+)' => 'purchase/exportPurchase/$1',
    'adm/crm/purchase/([a-z0-9-_?&]+)' => 'purchase/purchase/$1',
    'adm/crm/purchase_part_num_ajax' => 'purchase/purchasePartNumAjax',
    'adm/crm/show_purchses' => 'purchase/showDetailPurchases',
    'adm/crm/purchase_ajax' => 'purchase/purchaseAjax',
    'adm/crm/purchase_success' => 'purchase/purchaseSuccess',
    'adm/crm/purchase' => 'purchase/purchase',


    'adm/crm/stocks/([a-z0-9-_?&]+)' => 'stock/stocks/$1',
    'adm/crm/stocks' => 'stock/stocks',

    'adm/crm/export/returns/([a-z0-9-_?&]+)' => 'return/exportReturns/$1',
    'adm/crm/returns/filter/([a-z0-9-_?&]+)' => 'return/filterReturns/$1',
    'adm/crm/import_returns' => 'return/importReturns',
    'adm/crm/returns_ajax' => 'return/returnsAjax',
    'adm/crm/returns' => 'return/returns',

    'adm/crm/export/orders' => 'order/exportOrders',
    'adm/crm/orders/([a-z0-9-_?&]+)' => 'order/orders/$1',
    'adm/crm/orders_part_num_ajax' => 'order/ordersPartNumAjax',
    'adm/crm/show_orders' => 'order/showDetailOrders',
    'adm/crm/orders_action' => 'order/ordersAction',
    'adm/crm/orders_ajax' => 'order/ordersAjax',
    'adm/crm/orders_success' => 'order/ordersSuccess',
    'adm/crm/orders' => 'order/orders',

    'adm/crm/export/disassembly/([a-z0-9-_?&]+)' => 'disassembly/exportDisassembly/$1',
    'adm/crm/disassembly_all' => 'disassembly/allDisassembl',
    'adm/crm/show_disassembly' => 'disassembly/showDetailDisassembl',
    'adm/crm/disassembly_action_ajax' => 'disassembly/disassemblyActionAjax',
    'adm/crm/disassembly_ajax' => 'disassembly/disassemblyAjax',
    'adm/crm/disassembly_list/([a-z0-9-_?&]+)' => 'disassembly/disassemblyResult/$1',
    'adm/crm/disassembly_list' => 'disassembly/disassemblyResult',
    'adm/crm/disassembly/test' => 'disassembly/test1',
    'adm/crm/disassembly' => 'disassembly/disassembly',

    'adm/crm/moto_serial_num_ajax' => 'moto/motoSerialNumAjax',
    'adm/crm/show_moto' => 'moto/showMoto',
    'adm/crm/moto_part_num_ajax' => 'moto/motoPartNumAjax',
    'adm/crm/moto/([a-z0-9-_?&]+)' => 'moto/moto/$1',
    'adm/crm/moto' => 'moto/moto',

    'adm/crm/psr' => 'psr/psr',

    'adm/lithographer/s/([a-z0-9-_?&]+)' => 'lithographer/search/$1',
    'adm/lithographer/delete/([0-9]+)' => 'lithographer/delete/$1',
    'adm/lithographer/edit/([0-9]+)' => 'lithographer/edit/$1',
    'adm/lithographer/list' => 'lithographer/list',
    'adm/lithographer/tips/([0-9]+)' => 'lithographer/view/$1',
    'adm/lithographer/rules/([0-9]+)' => 'lithographer/view/$1',
    'adm/lithographer/forms' => 'lithographer/forms',
    'adm/lithographer/video' => 'lithographer/video',
    'adm/lithographer/tips' => 'lithographer/tips',
    'adm/lithographer/rules' => 'lithographer/rules',


    'adm/refund_request/filter/([a-z0-9-_?&]+)' => 'refundRequest/filterRequest/$1',
    'adm/refund_request/registration' => 'refundRequest/index',
    'adm/refund_request/view' => 'refundRequest/viewRequest',
    'adm/refund_request/thank_you_page' => 'refundRequest/thankYouPage',
    'adm/part_num_ajax' => 'refundRequest/partNumAjax',
    'adm/request_ajax' => 'refundRequest/requestAjax',
    'adm/refund_request/test' => 'refundRequest/test',


    'adm/result/([a-z0-9-_?&\.]+)' => 'kpi/result/$1',
    'adm/kpi/usage/([a-z0-9-_?&\.]+)' => 'kpi/usage/$1',
    'adm/kpi/show-problem' => 'kpi/showProblem',
    'adm/kpi/import' => 'kpi/import',
    'adm/kpi' => 'kpi/index',

    'adm/access_denied' => 'admin/access',
    'adm/logout' => 'admin/logout',
    'auth' => 'admin/auth',

    /** Site **/
    'contact_form' => 'site/contactForm',
    'contact' => 'site/contact',
    'career' => 'site/career',
    'directions' => 'site/directions',
    'for_business' => 'site/forBusiness',
    'main' => 'site/index', // actionIndex в SiteController
);
