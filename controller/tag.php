<?php

if_get('/user_tags', function ()
{/*{{{*/
    return [
        'user' => db_simple_query_indexed('tag', 'id', ['type' => 'user']),
        'trans.user' => db_simple_query_indexed('tag', 'id', ['type' => 'trans.user']),
    ];
});/*}}}*/

if_get('/good_tags', function ()
{/*{{{*/
    return [
        'good' => db_simple_query_indexed('tag', 'id', ['type' => 'good']),
        'trans.good' => db_simple_query_indexed('tag', 'id', ['type' => 'trans.good']),
    ];
});/*}}}*/

if_post('/tags/add', function ()
{/*{{{*/
    $name = input('name');
    $type = input('type');

    $tag_id = db_simple_insert('tag', compact('name', 'type'));

    if ($type === 'user') {

        $trans_tag_id = db_simple_insert('tag', [
            'name' => "'${name}'的人喜欢",
            'type' => 'trans.good',
            'trans_tag_id' => $tag_id,
            'trans_type' => $type,
        ]);
    } elseif ($type === 'good') {
        $trans_tag_id = db_simple_insert('tag', [
            'name' => "喜欢'${name}'的",
            'type' => 'trans.user',
            'trans_tag_id' => $tag_id,
            'trans_type' => $type,
        ]);
    }

    return [
        'id' => $tag_id,
    ];
});/*}}}*/

if_post('/tags/delete/*', function ($tag_id)
{/*{{{*/
    return db_simple_delete('tag', ['id' => $tag_id]);
});/*}}}*/

if_post('/mark_like/*', function ($good_id)
{/*{{{*/
    $user = current_user();

    // 查出 user 的标签，找到传染标签，传给 good
    $trans_good_tag_ids = db_query("
        select t.id
        from tag_target tt
        inner join tag t on t.trans_tag_id = tt.tag_id
        where tt.class = 'user'
        and tt.class_id = :user_id
        and t.trans_type = 'user'
        and t.type = 'trans.good'
        order by tt.count desc
    ", [
        ':user_id' => $user['id'],
    ]);
    foreach ($trans_good_tag_ids as $trans_good_tag_id) {
        tag_add_to_good($trans_good_tag_id, $good_id);
    }

    // 查出 good 的标签，找到传染标签，传给 user
    $trans_user_tag_ids = db_query("
        select t.id
        from tag_target tt
        inner join tag t on t.trans_tag_id = tt.tag_id
        where tt.class = 'good'
        and tt.class_id = :good_id
        and t.trans_type = 'good'
        and t.type = 'trans.user'
        order by tt.count desc
    ", [
        ':good_id' => $good_id,
    ]);
    foreach ($trans_user_tag_ids as $trans_user_tag_id) {
        tag_add_to_user($trans_user_tag_id, $user['id']);
    }

    return true;
});/*}}}*/

if_get('/get_like', function ()
{/*{{{*/
    $user = current_user();

    $goods = db_query('
        select g.*
        from good g
        inner join tag_target ttg on ttg.class_id = g.id and ttg.class = "good"
        inner join tag t on ttg.tag_id = t.trans_tag_id and t.trans_type = "good" and t.type = "trans.user"
        inner join tag_target ttu on t.id = ttu.tag_id and ttu.class = "user" and ttu.class_id = :user_id
        group by g.id
        order by ttu.count desc
    ', [
        ':user_id' => $user['id'],
    ]);

    $goods2 = db_query('
        select g.*
        from good g
        inner join tag_target ttg on ttg.class_id = g.id and ttg.class = "good"
        inner join tag t on ttg.tag_id = t.id and t.trans_type = "user" and t.type = "trans.good"
        inner join tag_target ttu on t.trans_tag_id = ttu.tag_id and ttu.class = "user" and ttu.class_id = :user_id
        group by g.id
        order by ttg.count desc
    ', [
        ':user_id' => $user['id'],
    ]);

    return [
        'user_like' => $goods,
        'good_liked' => $goods2,
    ];
});/*}}}*/
