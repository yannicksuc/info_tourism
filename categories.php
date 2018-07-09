<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 07/07/18
 * Time: 20:48
 */

class Categories {
    public static function getAll() {
        global $wpdb;
        $query = "SELECT * FROM wp_tourism_category ORDER BY name;";
        return $wpdb->get_results($query, ARRAY_A);
    }

    public static function addNewCategory($name, $color) {
        global $wpdb;
        $query = "INSERT INTO wp_tourism_category (name, color) VALUES ('".$name."','".substr($color, 1)."')";
        $wpdb->query($query);
    }

    public static function deleteCategory($id) {
        global $wpdb;
        $query = "DELETE FROM wp_tourism_category WHERE id=".$id;
        $wpdb->query($query);
    }

    public static function updateCategory($id, $name, $color) {
        global $wpdb;
        $query = "UPDATE wp_tourism_category SET name = '".$name."', color = '".substr($color, 1)."' WHERE id = ".$id.";";
        $wpdb->query($query);
    }

    public static function getLinkedPlaces() {
        global $wpdb;

        $query = "SELECT fk_id_place AS id_place, fk_id_category AS id_category, name, color
                  FROM wp_tourism_place_has_category
                  INNER JOIN wp_tourism_category ON fk_id_category = id
                  ORDER BY name;";
        $data = $wpdb->get_results($query, ARRAY_A);
        $newData = array();
        foreach($data as &$d) {
            if(!isset($newData[$d['id_place']]))
                $newData[$d['id_place']] = array();
            $newData[$d['id_place']][] = $d;
        }
        return $newData;
    }

    public static function getAllFromPlace($id) {
        global $wpdb;
        $query = "SELECT * FROM wp_tourism_place_has_category";
        $query = "UPDATE wp_tourism_category SET name = '".$name."', color = '".substr($color, 1)."' WHERE id = ".$id.";";
        $wpdb->query($query);
    }
}

function info_tourism_print_categories($categories, $justified = false)
{
    $justified = $justified ? 'justified-tag' : '';
    if (!$categories)
        return;
    echo '<div class="tag-wrapper">';
    $i = 0;
    foreach ($categories as &$category) {
        echo '<span class="tourism-tag '.$justified.'" style="background-color: #'.$category['color'].'">'.$category['name'].'</span>';
        ++$i;
    }
    echo '</div>';
}

function info_tourism_print_categories_clickable($categories, $justified = false)
{
    $justified = $justified ? 'justified-tag' : '';
    echo '<div class="tag-wrapper">';
    $i = 0;
    foreach ($categories as &$category) {
        echo '<span class="tourism-tag '.$justified.'" style="background-color: #'.$category['color'].'">'.$category['name'].'</span>';
        ++$i;
    }
    echo '</div>';
}