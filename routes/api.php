<?php

return [

    /*********** Umbrella  ***********/
    'api/umbrella/currency/rate/([a-z0-9-_?&]+)' => 'api/umbrella/Currency@rate/$1',



    /*********** HR Matrix  ***********/
    'api/form-users/achievements/add' => 'api/hr/FormUserAchievements@addAchievements',

    'api/form-users/comment/delete([a-z0-9-_?&]+)' => 'api/hr/FormUserComment@deleteComment/$1',
    'api/form-users/comment/see([a-z0-9-_?&]+)' => 'api/hr/FormUserComment@seeComment/$1',
    'api/form-users/comment/add' => 'api/hr/FormUserComment@addComment',

    'api/form-users/upload-file' => 'api/hr/File@uploadFile',
    'api/form-users/delete-file([a-z0-9-_?&]+)' => 'api/hr/File@deleteFile/$1',

    'api/form-users/upload-photo' => 'api/hr/FormUser@uploadPhoto',
    'api/form-users/delete([a-z0-9-_?&]+)' => 'api/hr/FormUser@deleteFormUser/$1',
    'api/form-users/apply_save' => 'api/hr/FormUser@applySaveFormUser',
    'api/form-users/add' => 'api/hr/FormUser@addFormUser',
    'api/form-users/get([a-z0-9-_?&]+)' => 'api/hr/FormUser@getUser/$1',
    'api/form-users([a-z0-9-_?&]+)' => 'api/hr/FormUser@usersInStructure/$1',


    'api/new-form-users/apply' => 'api/hr/FormUser@applyNewFormUser',
    'api/new-form-users/edit' => 'api/hr/FormUser@editNewFormUser',
    'api/new-form-users/add' => 'api/hr/FormUser@add',
    'api/new-form-users/get([a-z0-9-_?&]+)' => 'api/hr/FormUser@getFormUser/$1',
    'api/new-form-users([a-z0-9-_?&]+)' => 'api/hr/FormUser@newForm/$1',

    'api/bands([a-z0-9-_?&]+)' => 'api/hr/Band@allBand/$1',
    'api/band([a-z0-9-_?&]+)' => 'api/hr/Band@band/$1',

    'api/users([a-z0-9-_?&]+)' => 'api/hr/User@allUsers/$1',

    'api/staff([a-z0-9-_?&]+)' => 'api/hr/Staff@allStaff/$1',

    'api/structure/delete([a-z0-9-_?&]+)' => 'api/hr/Structure@deleteStructure/$1',
    'api/structure/edit([a-z0-9-_?&]+)' => 'api/hr/Structure@editStructure/$1',
    'api/structure/add([a-z0-9-_?&]+)' => 'api/hr/Structure@addStructure/$1',
    'api/structure([a-z0-9-_?&]+)' => 'api/hr/Structure@structure/$1',

    'api/logout([a-z0-9-_?&]+)' => 'api/hr/Auth@logout/$1',
    'api/auth([a-z0-9-_?&]+)' => 'api/hr/Auth@auth/$1',
    'api/auth' => 'api/hr/Auth@auth',
];