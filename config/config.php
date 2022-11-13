<?php
return [
    'defualt_fold_name'=>'App',
    'route'=>[
        'default_module'=>'index',
        'default_controller'=>'index',
        'default_action'=>'index',
    ],
    'template'=>[
         //--是否开启模板，True|False
        'tmp'=> true,
        'view_path'=>'view',
         //--缓存目录
        'cahce_dir'=>ROOT_PATH.'runtime/'
    ]
];