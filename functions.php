<?php
/**
 * Intentionally Blank Theme functions
 *
 * @package WordPress
 * @subpackage intentionally-blank
 */

if (!function_exists('wcd_theme_setup')) {

    function wcd_theme_setup()
    {
        load_theme_textdomain('intentionally-blank');
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('custom-logo');
        add_theme_support(
            'custom-logo',
            array(
                'height' => 256,
                'width' => 256,
                'flex-height' => true,
                'flex-width' => true,
                'header-text' => array('site-title', 'site-description'),
            )
        );
    }

} // end function_exists wcd_theme_setup.
add_action('after_setup_theme', 'wcd_theme_setup');

if (!function_exists('wcd_load_scripts')) {

    function wcd_load_scripts()
    {
        wp_enqueue_style('bootstrap', '//stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css');
        wp_enqueue_style('iconic-font', '//cdn.iconmonstr.com/1.3.0/css/iconmonstr-iconic-font.min.css');
        wp_enqueue_style('wcd-style', get_stylesheet_uri());
        wp_enqueue_script('jquery');
        wp_enqueue_script('wcd-repeater', get_template_directory_uri() . '/repeater.js');
        wp_enqueue_script('wcd-script', get_template_directory_uri() . '/script.js');
    }

} // end function_exists wcd_load_scripts.
add_action('wp_enqueue_scripts', 'wcd_load_scripts');

if (!function_exists('wcd_load_font')) {

    function wcd_load_font()
    {
        $content = '<link rel="preconnect" href="https://fonts.gstatic.com">';
        $content .= '<link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">';
        echo $content;
    }

} // end function_exists wcd_load_scripts.
add_action('wp_head', 'wcd_load_font');

add_action(
    'customize_register',
    function ($wp_customize) {
        $wp_customize->remove_section('static_front_page');
    }
);

if (!function_exists('wcd_flucon_parts')) {

// Register Custom Post Type
    function wcd_flucon_parts()
    {

        $labels = array(
            'name' => _x('Parts', 'Post Type General Name', 'wcd'),
            'singular_name' => _x('Part', 'Post Type Singular Name', 'wcd'),
            'menu_name' => __('Parts', 'wcd'),
            'name_admin_bar' => __('Part', 'wcd'),
            'archives' => __('Parts', 'wcd'),
            'attributes' => __('Part Attributes', 'wcd'),
            'parent_item_colon' => __('Parent Part:', 'wcd'),
            'all_items' => __('All Parts', 'wcd'),
            'add_new_item' => __('Add New Part', 'wcd'),
            'add_new' => __('Add Part', 'wcd'),
            'new_item' => __('New Part', 'wcd'),
            'edit_item' => __('Edit Part', 'wcd'),
            'update_item' => __('Update Part', 'wcd'),
            'view_item' => __('View Part', 'wcd'),
            'view_items' => __('View Parts', 'wcd'),
            'search_items' => __('Search Part', 'wcd'),
            'not_found' => __('Not found', 'wcd'),
            'not_found_in_trash' => __('Not found in Trash', 'wcd'),
            'featured_image' => __('Part Image', 'wcd'),
            'set_featured_image' => __('Set part image', 'wcd'),
            'remove_featured_image' => __('Remove part image', 'wcd'),
            'use_featured_image' => __('Use as part image', 'wcd'),
            'insert_into_item' => __('Insert into part', 'wcd'),
            'uploaded_to_this_item' => __('Uploaded to this part', 'wcd'),
            'items_list' => __('Parts list', 'wcd'),
            'items_list_navigation' => __('Parts list navigation', 'wcd'),
            'filter_items_list' => __('Filter parts list', 'wcd'),
        );
        $args = array(
            'label' => __('Part', 'wcd'),
            'description' => __('Parts', 'wcd'),
            'labels' => $labels,
            'supports' => array('title'),
            'hierarchical' => false,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-randomize',
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => false,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'page',
        );
        register_post_type('parts', $args);

    }
    add_action('init', 'wcd_flucon_parts', 0);

}

if (!function_exists('wcd_change_parts_title_text')) {
    function wcd_change_parts_title_text($title)
    {
        $screen = get_current_screen();

        if ('parts' == $screen->post_type) {
            $title = 'Enter the Fluid Connector Part Number';
        }

        return $title;
    }
}
add_filter('enter_title_here', 'wcd_change_parts_title_text');

if (!function_exists('wcd_parts_admin_columns')) {
    function wcd_parts_admin_columns($posts_columns)
    {
        return array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Fluid Connector Part #', 'wcd'),
            'hfp_part' => __('Holdfast Part #', 'wcd'),
            'post_id' => __('Post ID', 'wcd'),
        );
        return $columns;
    }
}
add_filter('manage_parts_posts_columns', 'wcd_parts_admin_columns');

if (!function_exists('wcd_parts_populate_columns')) {
    function wcd_parts_populate_columns($column, $post_id)
    {
        switch ($column) {
            case 'hfp_part':
                echo get_post_meta($post_id, 'wcd_holdfast-part-number', true);
                break;
            case 'post_id':
                echo $post_id;
                break;
        }
    }
}
add_action('manage_parts_posts_custom_column', 'wcd_parts_populate_columns', 10, 2);

class WCD_Parts
{
    private $config = '{"title":"Part Numbers","prefix":"wcd_","domain":"wcd","class_name":"WCD_Parts","post-type":["post"],"context":"normal","priority":"default","cpt":"parts","fields":[{"type":"text","label":"Holdfast Part Number","id":"wcd_holdfast-part-number"}]}';

    public function __construct()
    {
        $this->config = json_decode($this->config, true);
        $this->process_cpts();
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_post']);
    }

    public function process_cpts()
    {
        if (!empty($this->config['cpt'])) {
            if (empty($this->config['post-type'])) {
                $this->config['post-type'] = [];
            }
            $parts = explode(',', $this->config['cpt']);
            $parts = array_map('trim', $parts);
            $this->config['post-type'] = array_merge($this->config['post-type'], $parts);
        }
    }

    public function add_meta_boxes()
    {
        foreach ($this->config['post-type'] as $screen) {
            add_meta_box(
                sanitize_title($this->config['title']),
                $this->config['title'],
                [$this, 'add_meta_box_callback'],
                $screen,
                $this->config['context'],
                $this->config['priority']
            );
        }
    }

    public function save_post($post_id)
    {
        foreach ($this->config['fields'] as $field) {
            switch ($field['type']) {
                default:
                    if (isset($_POST[$field['id']])) {
                        $sanitized = sanitize_text_field($_POST[$field['id']]);
                        update_post_meta($post_id, $field['id'], $sanitized);
                    }
            }
        }
    }

    public function add_meta_box_callback()
    {
        $this->fields_table();
    }

    private function fields_table()
    {
        ?><table class="form-table" role="presentation">
			<tbody><?php
foreach ($this->config['fields'] as $field) {
            ?><tr>
						<th scope="row"><?php $this->label($field);?></th>
						<td><?php $this->field($field);?></td>
					</tr><?php
}
        ?></tbody>
		</table><?php
}

    private function label($field)
    {
        switch ($field['type']) {
            default:
                printf(
                    '<label class="" for="%s">%s</label>',
                    $field['id'], $field['label']
                );
        }
    }

    private function field($field)
    {
        switch ($field['type']) {
            default:
                $this->input($field);
        }
    }

    private function input($field)
    {
        printf(
            '<input class="regular-text %s" id="%s" name="%s" %s type="%s" value="%s">',
            isset($field['class']) ? $field['class'] : '',
            $field['id'], $field['id'],
            isset($field['pattern']) ? "pattern='{$field['pattern']}'" : '',
            $field['type'],
            $this->value($field)
        );
    }

    private function value($field)
    {
        global $post;
        if (metadata_exists('post', $post->ID, $field['id'])) {
            $value = get_post_meta($post->ID, $field['id'], true);
        } else if (isset($field['default'])) {
            $value = $field['default'];
        } else {
            return '';
        }
        return str_replace('\u0027', "'", $value);
    }

}
new WCD_Parts;