<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09/07/18
 * Time: 15:54
 */

class InfoTourismMap
{
    private static function includeDependencies() {
        echo '<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA71Qdr6MmM1JcoGOHusF_N8hPcjED1TA8&callback=initMap&libraries=places&callback=initAutocomplete" async defer></script>';
        echo '<script type="text/javascript" src="'.plugin_dir_url(__FILE__).'info_tourism.js"></script>';
    }

    public static function printMap() {
        echo '<input id="info-tourism-searchbar" type="text" placeholder="Search Place">';
        echo '<div id="info-tourism-map"></div>';
        InfoTourismMap::includeDependencies();
    }
}