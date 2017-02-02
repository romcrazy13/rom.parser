<?php

return array(

    '#root'                         => 'main/index',

// Обновление структуры и содержания базы данных
    'main/update'                   => 'main/update',

//
    '([a-z]+)/([a-z]+)/([0-9]+)'    => 'main/viewItem/$1/$2/$3',
    '([a-z]+)/([a-z]+)'             => 'main/viewSubCategories/$1/$2',
    '([a-z]+)'                      => 'main/viewCategories/$1',

);











