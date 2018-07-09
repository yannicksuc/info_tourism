<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 07/07/18
 * Time: 20:48
 */

class Places {
    public function getAll() {
        global $wpdb;
        $categories = new Categories;

        $cats = $categories->getLinkedPlaces();
        $query = "SELECT * FROM wp_tourism_place;";
        $data = $wpdb->get_results($query, ARRAY_A);
        foreach ($data as &$d) {
            $d['categories'] = $cats[$d['id']];
        }
        return $data;
    }

    public function addCategoriesLink($cats, $id = -1) {
        global $wpdb;

        if ($id == -1)
            $id = $wpdb->get_results("SELECT MAX(id) AS id from wp_tourism_place;", ARRAY_A)[0]['id'];
        $categories = new Categories;
        $cats_ref = $categories->getAll();
        $wpdb->query("DELETE from wp_tourism_place_has_category where fk_id_place = ".$id.";");
        if (!isset($cats))
            return;
        $rslt = "INSERT INTO wp_tourism_place_has_category (fk_id_place, fk_id_category) VALUES ";
        foreach ($cats_ref as $category) {
            if (in_array($category['id'], $cats)) {
                $rslt .= "(".$id.",".$category['id']."),";
            }
        }
        if (substr($rslt, -1) !== ",")
            return;
        $wpdb->query(rtrim($rslt,",").";");
    }

    public function addNewPlace($name, $lat, $lng, $cats) {
        global $wpdb;
        $query = "INSERT INTO wp_tourism_place (name, lat, lng) VALUES ('".$name."',".$lat.",".$lng.")";
        $wpdb->query($query);
        $this->addCategoriesLink($cats);
    }

    public function deletePlace($id) {
        global $wpdb;

        $query = "DELETE FROM wp_tourism_place WHERE id=".$id;
        $wpdb->query($query);
    }

    public function updatePlace($id, $name, $lat, $lng, $cats) {
        global $wpdb;

        $query = "UPDATE wp_tourism_place SET name = '".$name."', lat = ".$lat.", lng = ".$lng." WHERE id = ".$id.";";
        $wpdb->query($query);
        $this->addCategoriesLink($cats, $id);
    }
}