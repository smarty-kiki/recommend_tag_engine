<?php

if_get('/get_good_tags/*', function ($good_id)
{/*{{{*/
    $tag_targets = db_simple_query_indexed('tag_target', 'tag_id', [
        'class' => 'good',
        'class_id' => $good_id
    ], 'order by count desc');

    return $tag_targets;
});/*}}}*/

if_get('/goods', function ()
{/*{{{*/
    return db_simple_query('good');
});/*}}}*/

if_get('/last_one_good_info', function ()
{/*{{{*/
    return db_query_first('
        select g.*
        from good g
        order by g.id desc
        ');
});/*}}}*/

if_get('/last_goods', function ()
{/*{{{*/
    $user = current_user();

    $not_show_good_ids = db_simple_query_column('not_show', 'good_id', ['user_id' => $user['id']]);

    $not_show_good_ids[] = 0;

    return db_query('
        select g.*
        from good g
        where id not in :good_ids
        order by g.id desc limit 10
    ', [
        ':good_ids' => $not_show_good_ids,
    ]);
});/*}}}*/

if_post('/goods/add', function ()
{/*{{{*/
    $url = input('url');
    $name = input('name');
    $extend_id = input('extend_id');
    $content = input('content');

    return [
        'id' => db_simple_insert('good', compact('url', 'name', 'extend_id', 'content')),
    ];
});/*}}}*/

if_post('/goods/delete/*', function ($good_id)
{/*{{{*/
    db_simple_delete('not_show', [
        'good_id' => $good_id,
    ]);

    return db_simple_delete('good', [
        'id' => $good_id,
    ]);
});/*}}}*/

if_post('/goods/delete_keep_100', function ()
{/*{{{*/
    $good_ids = db_query_column('id', 'select id from good order by id desc limit 101, 1000');

    if (empty($good_ids)) {
        return [];
    }

    db_simple_delete('not_show', [
        'good_id' => $good_ids,
    ]);

    return db_simple_delete('good', [
        'id' => $good_ids,
    ]);
});/*}}}*/

if_get('/get_good_tags/*', function ($good_id)
{/*{{{*/
    $good = db_simple_query_first('good', ['id' => $good_id]);

    $tag_targets = db_simple_query('tag_target', [
        'class' => 'good',
        'class_id' => $good['id'],
    ], 'order by count desc');

    return $tag_targets;
});/*}}}*/

if_post('/tag_targets/add_to_good/*', function ($good_id)
{/*{{{*/
    $good = db_simple_query_first('good', ['id' => $good_id]);
    $tag_id = input('tag_id');

    return [
        'id' => tag_add_to_good($tag_id, $good['id'])
    ];
});/*}}}*/

if_post('/tag_targets/delete_from_good/*', function ($good_id)
{/*{{{*/
    $good = db_simple_query_first('good', ['id' => $good_id]);
    $tag_id = input('tag_id');

    $tag_target = db_simple_query_first('tag_target', [
        'tag_id'      => $tag_id,
        'class'       => 'good',
        'class_id'    => $good['id'],
    ]);

    if ($tag_target) {

        return db_simple_delete('tag_target', ['id' => $tag_target['id']]);
    }

    return false;
});/*}}}*/
