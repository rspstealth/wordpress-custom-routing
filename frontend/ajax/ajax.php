<?php
/**
 * @plugin_ajax
 */
require($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
$group_id = $_POST['group_id'];
$user_id = $_POST['user_id'];
if(!empty($group_id AND $user_id){
    if(!delete_user_meta($user_id, 'learndash_group_users_' . $group_id)){
        echo 'Couldnt delete user from group.';
    }else{
        echo 'deleted user from group.';
    }
}

?>