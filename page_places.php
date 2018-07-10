<?php
require_once('places.php');

add_action( 'admin_enqueue_scripts', 'mw_enqueue_tourism_place' );
function mw_enqueue_tourism_place( $hook_suffix ) {
    wp_enqueue_script( 'page-places-js', plugins_url('info_tourism.js', __FILE__ ));
    wp_enqueue_style( 'info-tourism-css', plugins_url('info_tourism.css', __FILE__ ));
}

function check_new_place($places) {
    if(isset($_POST['submit_add_place'])){ ?>
        <div class="notice notice-info is-dismissible inline">
            <p> <?php
                $places->addNewPlace($_POST["place_name"], $_POST["latitude"], $_POST["longitude"], $_POST["places_categories"]);
                printf("New place successfuly added");
                ?>
            </p>
        </div>
    <?php }
}

function check_delete_place($places) {
    if(isset($_POST['submit_delete_place'])){
        if ($_POST["place_id"] == -1) { ?>
            <div class="notice notice-warning is-dismissible inline">
                <p> Nothing to delete </p>
            </div>
            <?php return;
        } ?>
        <div class="notice notice-info is-dismissible inline">
            <p> <?php
                $places->deletePlace($_POST["place_id"]);
                printf("Place successfuly deleted");
                ?>
            </p>
        </div>
    <?php }
}

function check_update_place($places) {
    if(isset($_POST['submit_update_place'])){ ?>
        <div class="notice notice-info is-dismissible inline">
            <p> <?php
                $places->updatePlace($_POST["place_id"], $_POST["place_name"], $_POST["latitude"], $_POST["longitude"], $_POST["places_categories"]);
                printf("Place successfuly updated");
                ?>
            </p>
        </div>
    <?php }
}

function info_tourism_print_clickable_categories($categories, $activated_categories)
{
    echo '<div class="tag-wrapper">';
    $i = 0;
    foreach ($categories as &$category) {
        $checked = (($activated_categories && in_array($category, $activated_categories)) ? 'checked' : '');
        echo '<div><span class="tourism-tag justified-tag spaced-tag" style="background-color: #'.$category['color'].'">';
        echo '<input class="info-tourism-map-receiver" '.$checked.' name="places_categories[]" type="checkbox" value="'.$category['id'].'" />';
        echo '<span>'.$category['name'].'</span></span></div>';
        ++$i;
    }
    echo '</div>';
}

function info_tourism_place() {
    if (!current_user_can('edit_posts')) {
        wp_die(__( 'You do not have sufficient permissions to access this page.'));
        return;
    }
    $places = new Places;
    $categories = new Categories;
    ?>
    <div class="wrap">
        <h1>Manage Places</h1>
        <?php check_new_place($places); check_delete_place($places); check_update_place($places); ?>
        <form method="post" id="add_place_form">
            <input id="place_id" name="place_id" type="number" value="-1" style="display: none;"/>
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row" style="line-height: 165%;">
                        <label for="place_name">Prefill<span class="description"> (optional)</span></label>
                    </th>
                    <td colspan="2">
                        <table class="widefat selectable-places">
                            <tbody>
                            <?php
                            $list = $places->getAll();
                            $i = 1;
                            foreach ($list as &$p) {
                                $place_classes = ($i % 2 == 0 ? 'class="alternate change-updatable-place"' : 'class="change-updatable-place"');
                                echo '<tr '.$place_classes.'>';
                                echo '<th class="place_id" style="display: none">'.$p['id'].'</th>';
                                echo '<th class="place_coord" style="display: none">'.$p['lat'].' '.$p['lng'].'</th>';
                                echo '<th class="place_name" style="width: 30%; max-width: 200px;">'.$p['name'].'</th>';
                                echo '<th class="place_categories">';
                                info_tourism_print_categories($p['categories'], true);
                                echo '</th>';
                                ++$i;
                            }
                            ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="place_name">Prefill from map <span class="description">(optional)</span></label></th>
                    <td colspan="2"> <?php InfoTourismMap::printMap(); ?> </td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="place_name">Place Name <span class="description">(required)</span></label></th>
                    <td colspan="2"><input class="info-tourism-map-receiver address" name="place_name" type="text" id="place_name" value="" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60"></td>
                </tr>
                <tr class="form-field form-required gps-coord">
                    <th scope="row"><label for="longitude">Coordonées GPS <span class="description">(required)</span></label></th>
                    <td><input class="info-tourism-map-receiver longitude"  step="any" id="longitude" name="longitude" type="number" value="" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" placeholder="Longitude"></td>
                    <td><input class="info-tourism-map-receiver latitude" step="any" id="latitude" name="latitude" type="number" value="" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60" placeholder="Latitude"></td>
                </tr>
                <tr class="form-field form-required place-categories">
                    <th scope="row"><label for="place_categories">Place Categories <span class="description">(optional)</span></label></th>
                    <td colspan="2"><?php
                        info_tourism_print_clickable_categories($categories->getAll(), null);
                        ?></td>
                </tr>
                </tbody>
            </table>
            <?php submit_button("Add", 'primary', 'submit_add_place') ?>
            <?php submit_button("Update", '', 'submit_update_place') ?>
            <p><button type="button" name="place-clear" id="place-clear" class="button" value="Clear">Clear</button></p>
            <?php submit_button("Delete", '', 'submit_delete_place') ?>
        </form>
    </div>
<?php }