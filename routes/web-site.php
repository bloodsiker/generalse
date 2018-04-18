<?php
return [

    'lang/ru/change' => 'site/Language@changeLang',
    'lang/en/change' => 'site/Language@changeLang',

    /*********** NEW SITE RU  ***********/

    'ru/contacts' => 'site/Contacts@index',

    'ru/suppliers/send_form' => 'site/Suppliers@sendForm',
    'ru/suppliers' => 'site/Suppliers@index',

    'ru/career/([a-z0-9-_?&]+)' => 'site/Career@show/$1',
    'ru/career' => 'site/Career@index',

    'ru/news/([a-z0-9-_?&]+)' => 'site/News@new/$1',
    'ru/news' => 'site/News@index',

    'ru/recycling' => 'site/Recycling@index',

    'ru/services/manufacturers' => 'site/Services@manufacturers',
    'ru/services/retailers' => 'site/Services@retailers',
    'ru/services/repair-centers' => 'site/Services@repairCenters',
    'ru/services/enterprises' => 'site/Services@enterprises',

    'ru/about/company-information' => 'site/About@companyInfo',
    'ru/about/geography' => 'site/About@geography',
    'ru/about/responsibility' => 'site/About@responsibility',
    'ru/about/certificates' => 'site/About@certificates',

    'ru/login' => 'site/Auth@login',

    'ru/404/send' => 'site/NotFound@send',
    'ru/404' => 'site/NotFound@index',

    'ru' => 'site/Main@index',

    /*********** NEW SITE EN  ***********/

    'contacts' => 'site/Contacts@index',

    'suppliers' => 'site/Suppliers@index',

    'career/([a-z0-9-_?&]+)' => 'site/Career@show/$1',
    'career' => 'site/Career@index',

    'news/([a-z0-9-_?&]+)' => 'site/News@new/$1',
    'news' => 'site/News@index',

    'recycling' => 'site/Recycling@index',

    'services/manufacturers' => 'site/Services@manufacturers',
    'services/retailers' => 'site/Services@retailers',
    'services/repair-centers' => 'site/Services@repairCenters',
    'services/enterprises' => 'site/Services@enterprises',

    'about/company-information' => 'site/About@companyInfo',
    'about/geography' => 'site/About@geography',
    'about/responsibility' => 'site/About@responsibility',
    'about/certificates' => 'site/About@certificates',

    'login' => 'site/Auth@login',

    'main' => 'site/Main@index',


    /*********** OLD SITE  ***********/

    'old/sign_up' => 'Site@signUp',
    'old/contact_form' => 'Site@contactForm',
    'old/contact' => 'Site@contact',
    'old/career' => 'Site@career',
    'old/directions' => 'Site@directions',
    'old/for_business' => 'Site@forBusiness',
    'old/main' => 'Site@index', // actionIndex в SiteController
];