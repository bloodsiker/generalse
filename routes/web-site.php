<?php
return [
    /*********** NEW SITE RU  ***********/

    'ru/new/contacts' => 'site/Contacts@index',

    'ru/new/suppliers/send_form' => 'site/Suppliers@sendForm',
    'ru/new/suppliers' => 'site/Suppliers@index',

    'ru/new/career/([a-z0-9-_?&\.]+)' => 'site/Career@show/$1',
    'ru/new/career' => 'site/Career@index',

    'ru/new/news/([a-z0-9-_?&\.]+)' => 'site/News@new/$1',
    'ru/new/news' => 'site/News@index',

    'ru/new/recycling' => 'site/Recycling@index',

    'ru/new/services/manufacturers' => 'site/Services@manufacturers',
    'ru/new/services/retailers' => 'site/Services@retailers',
    'ru/new/services/repair-centers' => 'site/Services@repairCenters',
    'ru/new/services/enterprises' => 'site/Services@enterprises',

    'ru/new/about/company-information' => 'site/About@companyInfo',
    'ru/new/about/geography' => 'site/About@geography',
    'ru/new/about/responsibility' => 'site/About@responsibility',
    'ru/new/about/certificates' => 'site/About@certificates',

    'ru/404/send' => 'site/NotFound@send',
    'ru/404' => 'site/NotFound@index',

    'ru/new' => 'site/Main@index',

    /*********** NEW SITE EN  ***********/

    'new/contacts' => 'site/Contacts@index',

    'new/suppliers' => 'site/Suppliers@index',

    'new/career/([a-z0-9-_?&\.]+)' => 'site/Career@show/$1',
    'new/career' => 'site/Career@index',

    'new/news/([a-z0-9-_?&\.]+)' => 'site/News@new/$1',
    'new/news' => 'site/News@index',

    'new/recycling' => 'site/Recycling@index',

    'new/services/manufacturers' => 'site/Services@manufacturers',
    'new/services/retailers' => 'site/Services@retailers',
    'new/services/repair-centers' => 'site/Services@repairCenters',
    'new/services/enterprises' => 'site/Services@enterprises',

    'new/about/company-information' => 'site/About@companyInfo',
    'new/about/geography' => 'site/About@geography',
    'new/about/responsibility' => 'site/About@responsibility',
    'new/about/certificates' => 'site/About@certificates',

    'new' => 'site/Main@index',

    'lang/en' => 'site/Language@changeLang',
    'lang/ru' => 'site/Language@changeLang',

    /*********** SITE  ***********/
    'sign_up' => 'Site@signUp',
    'contact_form' => 'Site@contactForm',
    'contact' => 'Site@contact',
    'career' => 'Site@career',
    'directions' => 'Site@directions',
    'for_business' => 'Site@forBusiness',
    'main' => 'Site@index', // actionIndex в SiteController
];