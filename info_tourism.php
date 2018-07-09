<?php
/*
Plugin Name: Info Tourism
Plugin URI: http://sucyannick.github.io
Description:
Version: 1.0
Author: root
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/

require_once('places.php');
require_once('categories.php');
require_once('page_categories.php');
require_once('page_places.php');
require_once('Map.php');

$plugin_name = "Info Tourism";

add_action( 'admin_menu', 'info_tourism_menu' );
function info_tourism_menu() {
    $icon_svg = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyAgIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgICB4bWxuczpjYz0iaHR0cDovL2NyZWF0aXZlY29tbW9ucy5vcmcvbnMjIiAgIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyIgICB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiAgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgICB2aWV3Qm94PSIwIDAgMjY2LjY2NjY2IDI2Ni42NjY2NiIgICBoZWlnaHQ9IjI2Ni42NjY2NiIgICB3aWR0aD0iMjY2LjY2NjY2IiAgIGlkPSJzdmc4ODUiICAgdmVyc2lvbj0iMS4xIj4gIDxtZXRhZGF0YSAgICAgaWQ9Im1ldGFkYXRhODkxIj4gICAgPHJkZjpSREY+ICAgICAgPGNjOldvcmsgICAgICAgICByZGY6YWJvdXQ9IiI+ICAgICAgICA8ZGM6Zm9ybWF0PmltYWdlL3N2Zyt4bWw8L2RjOmZvcm1hdD4gICAgICAgIDxkYzp0eXBlICAgICAgICAgICByZGY6cmVzb3VyY2U9Imh0dHA6Ly9wdXJsLm9yZy9kYy9kY21pdHlwZS9TdGlsbEltYWdlIiAvPiAgICAgICAgPGRjOnRpdGxlPjwvZGM6dGl0bGU+ICAgICAgPC9jYzpXb3JrPiAgICA8L3JkZjpSREY+ICA8L21ldGFkYXRhPiAgPGRlZnMgICAgIGlkPSJkZWZzODg5IiAvPiAgPHBhdGggICAgIGlkPSJwYXRoODk1IiAgICAgZD0iTSAxMzMuMDE3NTggMzkuMTU4MjAzIEEgNjQuNzkxNzMzIDY0Ljc5MTczMyAwIDAgMCA2OC4yMjY1NjIgMTAzLjk0OTIyIEEgNjQuNzkxNzMzIDY0Ljc5MTczMyAwIDAgMCAxMDYuNjEzMjggMTYzLjAyNzM0IEwgMTM0LjI4MTI1IDIyNi44NTU0NyBMIDE2Mi4wOTc2NiAxNjEuNzY1NjIgQSA2NC43OTE3MzMgNjQuNzkxNzMzIDAgMCAwIDE5Ny44MDg1OSAxMDMuOTQ5MjIgQSA2NC43OTE3MzMgNjQuNzkxNzMzIDAgMCAwIDEzMy4wMTc1OCAzOS4xNTgyMDMgeiBNIDEzMy4wMTc1OCA2OC44NTc0MjIgQSAzNS4wOTE4OTIgMzUuMDkxODkyIDAgMCAxIDE2OC4xMDkzOCAxMDMuOTQ5MjIgQSAzNS4wOTE4OTIgMzUuMDkxODkyIDAgMCAxIDEzMy4wMTc1OCAxMzkuMDQxMDIgQSAzNS4wOTE4OTIgMzUuMDkxODkyIDAgMCAxIDk3LjkyNTc4MSAxMDMuOTQ5MjIgQSAzNS4wOTE4OTIgMzUuMDkxODkyIDAgMCAxIDEzMy4wMTc1OCA2OC44NTc0MjIgeiAiICAgICBzdHlsZT0iZmlsbDojMDAwMDAwO2ZpbGwtb3BhY2l0eToxO3N0cm9rZTojMWExYTFhO3N0cm9rZS13aWR0aDowO3N0cm9rZS1taXRlcmxpbWl0OjQ7c3Ryb2tlLWRhc2hhcnJheTpub25lO3N0cm9rZS1vcGFjaXR5OjEiIC8+ICA8cGF0aCAgICAgaWQ9InBhdGg4OTUtMyIgICAgIGQ9Ik0gNTIuMjMwNDY5IDk4LjgwODU5NCBBIDQyLjY3NDU4NSA0Mi42NzQ1ODUgMCAwIDAgMTcuMzU3NDIyIDE0MC43MzQzOCBBIDQyLjY3NDU4NSA0Mi42NzQ1ODUgMCAwIDAgNDIuNjQwNjI1IDE3OS42NDY0OCBMIDYwLjg2MzI4MSAyMjEuNjg1NTUgTCA3OS4xODU1NDcgMTc4LjgxNDQ1IEEgNDIuNjc0NTg1IDQyLjY3NDU4NSAwIDAgMCA5MC4wNjI1IDE3MC45NTg5OCBBIDgwLjI3MzczNiA3OC4zMTU4NzEgMCAwIDEgNzQuNTU0Njg4IDE1OC43MDMxMiBBIDIzLjExMzAxIDIzLjExMzAxIDAgMCAxIDYwLjAzMTI1IDE2My44NDc2NiBBIDIzLjExMzAxIDIzLjExMzAxIDAgMCAxIDM2LjkxNzk2OSAxNDAuNzM0MzggQSAyMy4xMTMwMSAyMy4xMTMwMSAwIDAgMSA1My40MDIzNDQgMTE4LjYxMzI4IEEgODAuMjczNzM2IDc4LjMxNTg3MSAwIDAgMSA1Mi4wMDc4MTIgMTA0LjUxOTUzIEEgODAuMjczNzM2IDc4LjMxNTg3MSAwIDAgMSA1Mi4yMzA0NjkgOTguODA4NTk0IHogIiAgICAgc3R5bGU9ImZpbGw6IzAwMDAwMDtmaWxsLW9wYWNpdHk6MTtzdHJva2U6IzFhMWExYTtzdHJva2Utd2lkdGg6MDtzdHJva2UtbWl0ZXJsaW1pdDo0O3N0cm9rZS1kYXNoYXJyYXk6bm9uZTtzdHJva2Utb3BhY2l0eToxIiAvPiAgPHBhdGggICAgIGlkPSJwYXRoODk1LTMtNyIgICAgIGQ9Ik0gMjEzLjU5NTcgOTkuNjQwNjI1IEEgODAuMjczNzM2IDc4LjMxNTg3MSAwIDAgMSAyMTMuODE4MzYgMTA1LjM1MTU2IEEgODAuMjczNzM2IDc4LjMxNTg3MSAwIDAgMSAyMTIuNDIzODMgMTE5LjQ0NTMxIEEgMjMuMTEzMDEgMjMuMTEzMDEgMCAwIDEgMjI4LjkwODIgMTQxLjU2NjQxIEEgMjMuMTEzMDEgMjMuMTEzMDEgMCAwIDEgMjA1Ljc5NDkyIDE2NC42Nzk2OSBBIDIzLjExMzAxIDIzLjExMzAxIDAgMCAxIDE5MS4yNzE0OCAxNTkuNTM1MTYgQSA4MC4yNzM3MzYgNzguMzE1ODcxIDAgMCAxIDE3NS43NjM2NyAxNzEuNzkxMDIgQSA0Mi42NzQ1ODUgNDIuNjc0NTg1IDAgMCAwIDE4Ni42NDA2MiAxNzkuNjQ2NDggTCAyMDQuOTYyODkgMjIyLjUxNzU4IEwgMjIzLjE4NTU1IDE4MC40Nzg1MiBBIDQyLjY3NDU4NSA0Mi42NzQ1ODUgMCAwIDAgMjQ4LjQ2ODc1IDE0MS41NjY0MSBBIDQyLjY3NDU4NSA0Mi42NzQ1ODUgMCAwIDAgMjEzLjU5NTcgOTkuNjQwNjI1IHogIiAgICAgc3R5bGU9ImZpbGw6IzAwMDAwMDtmaWxsLW9wYWNpdHk6MTtzdHJva2U6IzFhMWExYTtzdHJva2Utd2lkdGg6MDtzdHJva2UtbWl0ZXJsaW1pdDo0O3N0cm9rZS1kYXNoYXJyYXk6bm9uZTtzdHJva2Utb3BhY2l0eToxIiAvPjwvc3ZnPg==';
    add_menu_page("Info Tourism", "Info Tourism", 'edit_posts',
        'info_tourism', 'info_tourism_global', $icon_svg, '80.025');
    add_submenu_page( 'info_tourism', 'Manage Categories', 'Manage Categories', 'edit_posts',
        'info_tourism_category', 'info_tourism_category');
    add_submenu_page( 'info_tourism', 'Manage Places', 'Manage Places', 'edit_posts',
        'info_tourism_place', 'info_tourism_place');
}

function info_tourism_global() {
    if (!current_user_can('edit_posts')) {
        wp_die(__( 'You do not have sufficient permissions to access this page.'));
        return;
    }
    $categories = new Categories;
    InfoTourismMap::printMap();
    ?>
    <div class="wrap">
    <h1>Settings</h1>
    <hr>
    <section class="pattern" id="tourism_categories">
        <h2>Categories</h2>
        <?php info_tourism_print_categories($categories->getAll()); ?>
    </section>
    <hr>
    <section class="pattern" id="tourism_places">
        <h2>Places</h2>
        <table class="widefat">
            <thead>
            <tr>
                <th class="row-title" style="width: 20%; max-width: 200px">Place Name</th>
                <th class="row-title">Categories</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $places = new Places;
            $list = $places->getAll();
            for ($i = 0; $i < count($list); ++$i) {
                $alternate = ($i % 2 == 0 ? 'class="alternate"' : '');
                echo '<tr '.$alternate.'>';
                echo '<th>'.$list[$i]['name'].'</th><th>';
                info_tourism_print_categories($list[$i]['categories'], true);
                echo '</th></tr>';
            } ?>
            </tbody>
        </table>
    </section>
<?php }