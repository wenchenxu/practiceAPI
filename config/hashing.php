<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hash Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default hash driver that will be used to hash
    | passwords for your application. By default, the bcrypt algorithm is
    | used; however, you remain free to modify this option if you wish.
    |
    | Supported: "bcrypt", "argon", "argon2id"
    |
    */
    
    'driver' => 'argon2id',

    'argon' => [
        'memory'  => 65536, // 64MB
        'threads' => 2,
        'time'    => 4,
    ],
];