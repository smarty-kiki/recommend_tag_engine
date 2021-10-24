<?php

function tag_add_to_good($tag_id, $good_id)
{/*{{{*/
    $tag_target = db_simple_query_first('tag_target', [
        'tag_id'      => $tag_id,
        'class'       => 'good',
        'class_id'    => $good_id,
    ]);

    if ($tag_target) {

        db_simple_update('tag_target',
            ['id' => $tag_target['id']],
            ['count' => $tag_target['count'] + 1]
        );

        $tag_target_id = $tag_target['id'];
    } else {

        $tag_target_id = db_simple_insert('tag_target', [
            'tag_id'      => $tag_id,
            'class'       => 'good',
            'class_id'    => $good_id,
            'count'       => 0
        ]);
    }

    return $tag_target_id;
}/*}}}*/

function tag_add_to_user($tag_id, $user_id)
{/*{{{*/
    $tag_target = db_simple_query_first('tag_target', [
        'tag_id'      => $tag_id,
        'class'       => 'user',
        'class_id'    => $user_id,
    ]);

    if ($tag_target) {

        db_simple_update('tag_target',
            ['id' => $tag_target['id']],
            ['count' => $tag_target['count'] + 1]
        );

        $tag_target_id = $tag_target['id'];
    } else {

        $tag_target_id = db_simple_insert('tag_target', [
            'tag_id'      => $tag_id,
            'class'       => 'user',
            'class_id'    => $user_id,
            'count'       => 0
        ]);
    }

    return $tag_target_id;
}/*}}}*/
