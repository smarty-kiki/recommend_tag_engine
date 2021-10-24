<?php

function current_user()
{/*{{{*/
    $sign = cookie('sign');
    $sign = $sign? $sign: input('sign');

    $user = false;

    if ($sign) {

        $user = db_simple_query_first('user', ['sign' => $sign]);
    }

    if (! $user) {

        $sign = md5(datetime().ip());

        $user_id = db_simple_insert('user', ['sign' => $sign]);

        $user = [
            'id' => $user_id,
            'sign' => $sign,
        ];

        setcookie('sign', $sign);
    }

    return $user;
}/*}}}*/

if_get('/users', function ()
{/*{{{*/
    return db_simple_query('user');
});/*}}}*/

if_post('/users/delete/*', function ($user_id)
{/*{{{*/
    return db_simple_delete('user', ['id' => $user_id]);
});/*}}}*/

if_get('/get_user_tags', function ()
{/*{{{*/
    $user = current_user();

    $tag_targets = db_simple_query_indexed('tag_target', 'tag_id', [
        'class' => 'user',
        'class_id' => $user['id']
    ], 'order by count desc');

    return $tag_targets;
});/*}}}*/

if_post('/tag_targets/add_to_user', function ()
{/*{{{*/
    $user = current_user();
    $tag_id = input('tag_id');

    return [
        'id' => tag_add_to_user($tag_id, $user['id']),
    ];
});/*}}}*/

if_post('/tag_targets/delete_from_user', function ()
{/*{{{*/
    $user = current_user();
    $tag_id = input('tag_id');

    $tag_target = db_simple_query_first('tag_target', [
        'tag_id'      => $tag_id,
        'class'       => 'user',
        'class_id'    => $user['id'],
    ]);

    if ($tag_target) {

        return db_simple_delete('tag_target', ['id' => $tag_target['id']]);
    }

    return false;
});/*}}}*/
