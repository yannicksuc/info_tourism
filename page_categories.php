<?php
require_once('categories.php');

add_action( 'admin_enqueue_scripts', 'mw_enqueue_color_picker' );
function mw_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'info-tourism-js', plugins_url('page_categories.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
    wp_enqueue_style( 'font-awesome', 'https://use.fontawesome.com/releases/v5.1.0/css/all.css' );
}

function check_new_category($categories) {
    if(isset($_POST['submit_category'])){ ?>
        <div class="notice notice-info is-dismissible inline">
            <p> <?php
                $categories->addNewCategory($_POST["category_name"], $_POST["category_color"]);
                printf("New category successfuly added ");
                ?>
            </p>
        </div>
    <?php }
}

function check_update_categories($categories) {
    if(isset($_POST['submit_update_categories'])){ ?>
        <div class="notice notice-info is-dismissible inline">
            <p> <?php
                for($i=0; $i < count($_POST['category_color']); $i++) {
                    if (isset($_POST['category_delete']) && in_array($i, $_POST['category_delete']))
                        $categories->deleteCategory($_POST['category_id'][$i]);
                    elseif (isset($_POST['category_update']) && in_array($i, $_POST['category_update'])) {
                        $categories->updateCategory($_POST['category_id'][$i], $_POST['category_name'][$i], $_POST['category_color'][$i]);
                    }
                }
//                $categories->addNewCategory($_POST["category_name"], $_POST["category_color"]);
                printf("Update done !");
                ?>
            </p>
        </div>
    <?php }
}

function info_tourism_category() {
    if (!current_user_can('edit_posts')) {
        wp_die(__( 'You do not have sufficient permissions to access this page.'));
        return;
    }
    $categories = new Categories;
    ?>
    <div class="wrap">
        <h1>Settings</h1>
        <?php check_new_category($categories) ?>
        <?php check_update_categories($categories) ?>
        <h2>Add Category</h2>
        <form method="post">
            <table class="form-table">
                <tbody>
                <tr class="form-field form-required">
                    <th scope="row"><label for="category_name">Category Name <span class="description">(required)</span></label></th>
                    <td><input name="category_name" type="text" id="category_name" class="regular-text" value="" aria-required="true" autocapitalize="none" autocorrect="off" maxlength="60"></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="category_color">Category Color <span class="description">(required)</span></label></th>
                    <td><input id="category_color" type="text" value="#bada55" class="category-color-field" name="category_color"/></td>
                </tr>
                </tbody>
            </table>
            <?php submit_button("Add", 'primary', 'submit_category') ?>
        </form>
        <h2> List Categories</h2>
        <form method="post">
            <table class="widefat">
                <thead>
                <tr>
                    <th class="row-title" style="width: 20%; max-width: 200px">Category Name</th>
                    <th class="row-title">Category Color</th>
                    <th class="row-title" style="width: 5%; min-width: 50px">Update</th>
                    <th class="row-title" style="width: 5%; min-width: 50px">Delete</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $list = $categories->getAll();
                $i = 0;
                foreach ($list as &$p) {
                    $alternate = ($i % 2 == 0 ? 'class="alternate"' : '');
                    echo '<tr '.$alternate.'>';
                    echo '<input name="category_id[]" type="number" value='.$p['id'].' style="display: none;"/>';
                    echo '<th><input type="text" value="'.$p['name'].'" name="category_name[]"/></th>';
                    echo '<th><input type="text" value="#'.$p['color'].'" class="category-color-field" name="category_color[]"/></th>';
                    ?>
                    <th>
                        <input name="category_update[]" type="checkbox" value="<?php echo $i; ?>" />
                        <i class="fas fa-sync-alt"></i>
                    </th>
                    <th>
                        <input name="category_delete[]" type="checkbox" value="<?php echo $i; ?>" />
                        <i class="far fa-trash-alt"></i>
                    </th>
                    </tr> <?php
                    ++$i;
                }
                ?>
                </tbody>
            </table>
            <?php submit_button("Update Categories", 'primary', 'submit_update_categories') ?>
        </form>
    </div>
<?php }