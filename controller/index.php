<?php

if_get('/', function ()
{
    return 'hello world';
});

if_get('/error_code_maps', function ()
{
    return config('error_code');
});
