<?php
/**
 * @var parent_items
 * @var child_items
 *
 */
//get Dashboard Menu
$dashboard_menu_items = wp_get_nav_menu_items('Dashboard Pages');
//var_dump($endpoints);
$parent_items = '<ul>';
$child_items = '<ul>';
$active_parent = 'none';

foreach ($dashboard_menu_items as $menu_item) {
    //get parents array
    if ($menu_item->menu_item_parent == 0) {
        //<ul>
        //<i class="fa fa-tachometer" aria-hidden="true"></i>
        $url_parts = explode('/', $menu_item->url);
        //echo 'part 2:'.$url_parts[4];
        $class_active = '';
        if ($endpoints[2] === $url_parts[4]) {
            //mark active
            $class_active = 'class="active"';
            $active_parent = $menu_item->ID;
        }
        $parent_items .= '<li '.$class_active.'>';
        $parent_items .= '<a href="' . $menu_item->url . '" title="' . $menu_item->post_title . '">';
        if (!empty($menu_item->icon)) {
            $parent_items .= '<i class="fa ' . $menu_item->icon . '" aria-hidden="true"></i>';
        }
        $parent_items .= $menu_item->post_title . '</a></li>';
    }
    //var_dump($child_items);
}

$class_active = '';//empty it
//now find active parent child items
foreach ($dashboard_menu_items as $menu_item) {
    //get child array
    if ($menu_item->menu_item_parent != 0) {
        if ($menu_item->menu_item_parent == $active_parent) {
            $child = strtolower(str_replace(' ', '-',$menu_item->post_title));
            if($endpoints[3] === $child){
                $class_active = 'class="active"';//empty it
            }else{
                $class_active = '';
            }
            $child_items .= '<li '.$class_active.'>';
            $child_items .= '<a href="' . $menu_item->url . '" title="' . $menu_item->post_title . '">';
            if (!empty($menu_item->icon)) {
                $child_items .= '<i class="fa ' . $menu_item->icon . '" aria-hidden="true"></i>';
            }
            $child_items .= $menu_item->post_title . '</a></li>';
        }
    }
}
$parent_items .= '</ul>';
$child_items .= '</ul>';
?>