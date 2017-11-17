<?php

return [

    'api/form-users/comment/add' => 'api/hr/FormUserComment@addComment',

    'api/form-users/upload-photo' => 'api/hr/FormUser@uploadPhoto',
    'api/form-users/delete([a-z0-9-_?&]+)' => 'api/hr/FormUser@deleteFormUser/$1',
    'api/form-users/edit' => 'api/hr/FormUser@editFormUser',
    'api/form-users/add' => 'api/hr/FormUser@addFormUser',
    'api/form-users/get([a-z0-9-_?&]+)' => 'api/hr/FormUser@getUser/$1',
    'api/form-users([a-z0-9-_?&]+)' => 'api/hr/FormUser@usersInStructure/$1',

    'api/bands([a-z0-9-_?&]+)' => 'api/hr/Band@allBand/$1',
    'api/band([a-z0-9-_?&]+)' => 'api/hr/Band@band/$1',

    'api/structure/delete([a-z0-9-_?&]+)' => 'api/hr/Structure@deleteStructure/$1',
    'api/structure/edit([a-z0-9-_?&]+)' => 'api/hr/Structure@editStructure/$1',
    'api/structure/add([a-z0-9-_?&]+)' => 'api/hr/Structure@addStructure/$1',
    'api/structure([a-z0-9-_?&]+)' => 'api/hr/Structure@structure/$1',

    'api/logout([a-z0-9-_?&]+)' => 'api/hr/Auth@logout/$1',
    'api/auth([a-z0-9-_?&]+)' => 'api/hr/Auth@auth/$1',
    'api/auth' => 'api/hr/Auth@auth',
];