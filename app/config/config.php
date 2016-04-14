<?php

return array(
    'mode'        => 'dev',
    'routes'      => include('routes.php'),
    'main_layout' => __DIR__.'/../../src/Blog/views/layout.html.php',
    'error_500'   => __DIR__.'/../../src/Blog/views/500.html.php',
    'reclama_txt'     => 'ho - бесплатный хостинг!',
    'reclama_lnk'     => 'http://www.ho.ua/',
    'pdo'         => array(
        'dsn'      => 'mysql:dbname=education;host=localhost',
        'user'     => 'education',
        'password' => 'n29OB4uIYGii'
    ),
    'security'    => array(
        'user_class'  => 'Blog\\Model\\User',
        'login_route' => 'login'
    )
);