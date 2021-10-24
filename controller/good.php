<?php

if_get('/goods', function ()
{/*{{{*/
    return db_simple_query('good');
});/*}}}*/

if_post('/goods/add', function ()
{/*{{{*/
    $url = input('url');
    $name = input('name');

    return [
        'id' => db_simple_insert('good', compact('url', 'name')),
    ];
});/*}}}*/

if_post('/goods/delete/*', function ($good_id)
{/*{{{*/
    return db_simple_delete('good', [
        'id' => $good_id,
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

    $tag_targets = db_simple_query_first('tag_target', [
        'tag_id'      => $tag_id,
        'class'       => 'good',
        'class_id'    => $good['id'],
    ]);

    $tag_target = reset($tag_targets);

    if ($tag_target) {

        return db_simple_delete('tag_target', ['id' => $tag_target['id']]);
    }

    return false;
});/*}}}*/
