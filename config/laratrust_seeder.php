// config/laratrust_seeder.php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laratrust Seeder Configuration
    |--------------------------------------------------------------------------
    */

    /**
     * Whether to create users for each role.
     */
    'create_users' => true,

    /**
     * Whether to truncate the tables before seeding.
     */
    'truncate_tables' => true,

    /**
     * Roles and their permissions structure.
     */
    'roles_structure' => [
        'administrator' => [
            'users' => 'c,r,u,d',
            'payments' => 'c,r,u,d',
            'profile' => 'r,u',
            'categories' => 'c,r,u,d',
            'products' => 'c,r,u,d',
            'orders' => 'r,u',
        ],
        'customer' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u',
            'categories' => 'r',
            'products' => 'r',
            'orders' => 'c,r,u',
        ],
        'vendor' => [
            'profile' => 'r,u',
        ],
    ],

    /**
     * Mapping of permissions to their descriptions.
     */
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];
