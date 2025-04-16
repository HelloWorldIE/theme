<?php
/**
 * Recommended way to include parent theme styles.
 * (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
 *
 */

add_action('wp_enqueue_scripts', 'cobulma_style');
function cobulma_style()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
}

/**
 * Your code goes below.
 */

/* Add Bulma CSS and Custom CSS */
function enqueue_bulma_and_custom_css_for_cpt()
{
    // Your existing styles and scripts
    wp_enqueue_style('badge-css', get_stylesheet_directory_uri() . '/css/bulma-badge.min.css', array());
    wp_enqueue_style('steps-css', get_stylesheet_directory_uri() . '/css/bulma-steps.min.css', array());
    wp_enqueue_style('tooltip-css', get_stylesheet_directory_uri() . '/css/bulma-tooltip.min.css', array());
    wp_enqueue_style('swiper-css', get_stylesheet_directory_uri() . '/css/swiper-bundle.min.css', array());

    // Enqueue jQuery
    wp_enqueue_script('jquery');

    // Enqueue your existing custom script
    wp_enqueue_script('my-acf-custom-js', get_stylesheet_directory_uri() . '/js/my-acf-custom-js.js', array('jquery'), null, true);
    wp_enqueue_script('quick-copy-paste', get_stylesheet_directory_uri() . '/js/quick-copy-paste.js', array('jquery'), null, true);
    wp_enqueue_script('echarts-js', get_stylesheet_directory_uri() . '/js/echarts.min.js', array(), true);
    wp_enqueue_script('swiper-js', get_stylesheet_directory_uri() . '/js/swiper-bundle.min.js', array(), true);
    wp_enqueue_script('collapse-js', get_stylesheet_directory_uri() . '/js/collapse.js', array(), true);

    // Enqueue the ACF dynamic title script for live updates
    wp_enqueue_script('acf-dynamic-titles', get_stylesheet_directory_uri() . '/js/acf-dynamic-discovery-titles.js', array('jquery'), null, true);
}

add_action('wp_enqueue_scripts', 'enqueue_bulma_and_custom_css_for_cpt');


// Add shortcode for editing learning designs.
function acf_edit_post_form_shortcode($atts)
{
    if (!is_user_logged_in()) {
        return '<p>You need to be logged in to edit a post.</p>';
    }

    // Check if a post ID is provided
    if (isset($_GET['post_id']) && is_numeric($_GET['post_id'])) {
        $post_id = intval($_GET['post_id']);

        ob_start();

        acf_form(
            array(
                'post_id' => $post_id,
                'post_title' => true,
                'post_content' => true,
                'submit_value' => __('Update Post', 'acf'),
                'return' => add_query_arg('updated', 'true', get_permalink($post_id)), // Redirect back to post after update
            )
        );

        return ob_get_clean();
    } else {
        return '<p>No post specified for editing.</p>';
    }
}
add_shortcode('acf_edit_post_form', 'acf_edit_post_form_shortcode');


add_filter('acf/fields/flexible_content/layout_title', 'my_acf_fields_flexible_content_layout_title', 10, 4);

function my_acf_fields_flexible_content_layout_title($title, $field, $layout, $i)
{
    // Get the current layout data
    $layout_data = get_row(true);

    // Check if the layout has sub-fields
    if ($layout_data) {
        // Prioritise topic_title and subtopic_title for flexible content layout titles
        if (!empty($layout_data['topic_title'])) {
            $title = esc_html($layout_data['topic_title']);
        } elseif (!empty($layout_data['subtopic_title'])) {
            $title = esc_html($layout_data['subtopic_title']);
        } else {
            // Keep existing logic for fields with the 'title-field' class
            foreach ($layout_data as $sub_field_key => $sub_field_value) {
                $sub_field_object = get_sub_field_object($sub_field_key);

                // Check if the sub-field has the class 'title-field'
                if (isset($sub_field_object['wrapper']['class']) && strpos($sub_field_object['wrapper']['class'], 'title-field') !== false) {
                    // Use the sub-field value as the title
                    if (!empty($sub_field_value)) {
                        $title = esc_html($sub_field_value);
                    }
                    break; // Stop once we've found and set the title
                }
            }
        }
    }

    return $title;
}


//Include the Word Count Plugin in TinyMCE
function add_word_count_to_acf_wysiwyg($toolbars)
{
    // Check if Full toolbar exists
    if (isset($toolbars['Full'])) {
        // Add 'wordcount' to the plugins
        array_push($toolbars['Full'][2], 'wordcount');
    }

    // Optionally, add to other toolbar configurations
    if (isset($toolbars['Basic'])) {
        array_push($toolbars['Basic'][1], 'wordcount');
    }

    return $toolbars;
}

add_filter('acf/fields/wysiwyg/toolbars', 'add_word_count_to_acf_wysiwyg');


//Add Custom JavaScript to Initialize Word Count
function my_acf_input_admin_footer()
{
    ?>
    <script type="text/javascript">
        acf.add_filter('wysiwyg_tinymce_settings', function (mceInit, id) {
            // Enable the word count plugin
            mceInit.plugins = mceInit.plugins ? mceInit.plugins + " wordcount" : "wordcount";

            mceInit.toolbar1 += ' wordcount';

            mceInit.setup = function (editor) {
                editor.on('init', function () {
                    console.log('Word count feature initialised.');
                });
            };

            return mceInit;
        });
    </script>
    <?php
}
add_action('acf/input/admin_footer', 'my_acf_input_admin_footer');


// Redirect if not logged in
/*
function redirect_if_not_logged_in() {
    if (!is_user_logged_in() && !is_page('login')) {
        wp_redirect(wp_login_url());
        exit;
    }
}
add_action('template_redirect', 'redirect_if_not_logged_in');
*/

// Redirect after login
function custom_login_redirect($redirect_to, $request, $user) {
    // Check if the user is not an admin
    if (!is_wp_error($user) && !user_can($user, 'administrator')) {
        // Redirect non-admin users to /learning-design/
        return home_url('/');
    }
    // Allow administrators to access the dashboard
    return $redirect_to;
}
add_filter('login_redirect', 'custom_login_redirect', 10, 3);


/**
 **************************************************
 * Restrict client field options to show only clients linked to the current author
 *
 * Dynamically filters the ACF client post object field to show only clients associated with the current user.
 *
 * @filter acf/fields/post_object/query/name=client
 * @param array $args WP_Query arguments for fetching post objects
 * @param array $field ACF field data
 * @param int $post_id The ID of the post being edited
 * @return array Modified WP_Query arguments
 **************************************************
 */
add_filter('acf/fields/post_object/query/name=client', 'restrict_client_field_for_authors_via_query', 10, 3);
function restrict_client_field_for_authors_via_query($args, $field, $post_id) {
    $current_user = wp_get_current_user();

    // Check if the user is an author
    if (in_array('author', $current_user->roles)) {
        // Query to find all clients linked to the current user as a reviewer
        $client_query = new WP_Query(array(
            'post_type' => 'client',
            'meta_query' => array(
                array(
                    'key' => 'reviewers', // Meta key for linking users as reviewers
                    'value' => '"' . $current_user->ID . '"', // Serialized array of user IDs
                    'compare' => 'LIKE'
                )
            ),
            'posts_per_page' => -1 // Fetch all matching clients
        ));

        $client_ids = array();

        if ($client_query->have_posts()) {
            while ($client_query->have_posts()) {
                $client_query->the_post();
                $client_ids[] = get_the_ID();
            }
        }

        wp_reset_postdata(); // Reset the post data after the client query

        // Modify the WP_Query args to restrict to specific client IDs
        if (!empty($client_ids)) {
            $args['post__in'] = $client_ids;
        } else {
            // If no clients are found, return an empty result set
            $args['post__in'] = array(0); // An empty array will prevent any results from showing
        }
    }

    return $args;
}

/**
 **************************************************
 * Restrict archive access for specific post types
 *
 * Adjusts the archive query to restrict it to logged-in users only. Logged-in users can view posts authored by themselves or posts linked to clients they are associated with.
 * Non-logged-in users are redirected away from archive pages of specific post types.
 *
 * @action pre_get_posts
 * @param WP_Query $query The main query object
 **************************************************
 */
add_action('pre_get_posts', 'restrict_custom_archives_for_authors');
function restrict_custom_archives_for_authors($query) {
    // Only modify the main query on front-end archive pages
    if (!is_admin() && $query->is_main_query() && is_archive()) {
        $current_user = wp_get_current_user();

        // Define the restricted post types
        $restricted_post_types = array('client', 'learning-design', 'discovery-document');

        // Check if the current archive is one of the restricted post types
        if (is_post_type_archive($restricted_post_types)) {

            // Redirect non-logged-in users away from the archive
            if (!is_user_logged_in()) {
                wp_redirect(home_url());
                exit;
            }

            // Get associated client IDs for authors
            if (in_array('author', $current_user->roles)) {
                // Step 1: Retrieve client IDs linked to the current user from the user profile
                $associated_clients = get_field('client', 'user_' . $current_user->ID);

                if ($associated_clients) {
                    // Extract client IDs from the user’s client associations
                    $client_ids = wp_list_pluck($associated_clients, 'ID');
                } else {
                    $client_ids = array(); // Default to an empty array if no clients are found
                }

                // Step 2: Adjust query based on the post type being viewed
                if (is_post_type_archive('client')) {
                    // For 'client' archives, restrict to the user's associated clients
                    if (!empty($client_ids)) {
                        $query->set('post__in', $client_ids);
                    } else {
                        $query->set('post__in', array(0)); // No posts if no associated clients are found
                    }
                } else {
                    // For 'learning-design' and 'discovery-document', filter by associated clients
                    $meta_query = array('relation' => 'OR');

                    // a) Allow posts authored by the current user
                    $meta_query[] = array(
                        'key' => '_edit_last',
                        'value' => $current_user->ID,
                        'compare' => '='
                    );

                    // b) Allow posts where the 'client' field matches any of the user's associated client IDs
                    if (!empty($client_ids)) {
                        $meta_query[] = array(
                            'key' => 'client',
                            'value' => $client_ids,
                            'compare' => 'IN'
                        );
                    }

                    // Apply the meta query to filter relevant posts
                    $query->set('meta_query', $meta_query);
                }
            }
        }
    }
}


/**
 **************************************************
 * Allow authors to edit posts associated with their clients
 *
 * Modifies the `edit_post` capability to allow authors to edit posts linked to their associated clients.
 *
 * @filter user_has_cap
 * @param array $allcaps All capabilities of the user
 * @param array $caps Specific capability being checked
 * @param array $args Arguments, usually containing post ID
 * @return array Modified capabilities
 **************************************************
 */
add_filter('user_has_cap', 'grant_author_edit_access_for_associated_posts', 10, 4);
function grant_author_edit_access_for_associated_posts($allcaps, $caps, $args, $user) {
    // Ensure we're checking `edit_post` and the required arguments are available
    if (isset($args[0], $args[2]) && $args[0] === 'edit_post') {
        $post_id = $args[2];
        $post = get_post($post_id);
        $current_user = wp_get_current_user();

        // Allow authors to edit their own posts
        if ($post->post_author == $current_user->ID) {
            $allcaps[$caps[0]] = true;
            return $allcaps;
        }

        // Check if the user is an author
        if (in_array('author', $current_user->roles)) {
            // Step 1: Get all client IDs associated with the current user (as a reviewer)
            $client_query = new WP_Query(array(
                'post_type' => 'client',
                'meta_query' => array(
                    array(
                        'key' => 'reviewers', // Meta key for linking users as reviewers
                        'value' => '"' . $current_user->ID . '"', // Serialized array of user IDs
                        'compare' => 'LIKE'
                    )
                ),
                'posts_per_page' => -1 // Fetch all matching clients
            ));

            $client_ids = array();
            if ($client_query->have_posts()) {
                while ($client_query->have_posts()) {
                    $client_query->the_post();
                    $client_ids[] = get_the_ID();
                }
            }
            wp_reset_postdata();

            // Step 2: Check if the post (learning-design or discovery) is associated with any of the client's IDs
            $associated_client = get_field('client', $post_id); // The ACF relationship field that links to a client

            if (in_array($associated_client, $client_ids)) {
                // Grant access to edit the post
                $allcaps[$caps[0]] = true;
            }
        }
    }

    return $allcaps;
}
