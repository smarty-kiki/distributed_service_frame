<?php

service('demo@create', function ($column1 = 1, $column2 = 2)
{
    return demo::create($column1, $column2);
});

service('demo@update', function ($demo, $structs)
{
    otherwise($demo->is_not_null(), 'demo not found');

    foreach ($structs as $struct => $value) {
        $demo->{$struct} = $value;
    }

    return $demo;
});

service('demo@delete', function ($demo)
{
    otherwise($good->is_not_null(), 'good not found');

    $demo->delete();

    return true;
});
