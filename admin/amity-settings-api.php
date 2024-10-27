<?php

/**
 * WordPress settings API
 * @author Amity Theme
 */

if ( !class_exists('ARP_Settings_API_Related_Post' ) ):
class ARP_Settings_API_Related_Post {

    private $settings_api;

    function __construct() {
        $this->settings_api = new AmityTheme_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_options_page( 'ARP Options', 'ARP Options', 'delete_posts', 'amity-related-posts', array($this, 'arp_plugin_page') );

        // Returning true.
        return true;
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'arp_basics',
                'title' => __( 'Basic Options', 'arp' )
            ),
            
            array(
                'id' => 'arp_styles',
                'title' => __('Style Options', 'arp')
            )

        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {

        $settings_fields = array(
            'arp_basics' => array(
                array(
                    'name'              => 'arp-active-mode',
                    'label'             => __('Enable or Disable'),
                    'label'             => __( 'Default Enable', 'arp' ),
                    'type'              => 'select',
                    'default'           => '1',
                    'options'           => array(
                        '1'        => 'Enable',
                        '0'         => 'Disable' 
                    )
                ),

                array(
                    'name'              => 'arp-main-title',
                    'label'             => __( 'Type In The Main Title', 'arp' ),
                    'type'              => 'text',
                    'default'           => 'Amity Related Posts'
                ),

                array(
                    'name'              => 'arp-no-posts',
                    'label'             => __( 'Number Of Posts Display', 'arp' ),
                    'desc'              => __( 'How many post you want to display. Recommended 3 or 6', 'arp' ),
                    'type'              => 'number',
                    'default'           => '6'
                ),

                array(
                    'name'              => 'arp-grid-list',
                    'label'             => __('Post Display Style', 'arp'),
                    'desc'              => __('Grid Style or List Style', 'arp'),
                    'type'              => 'radio',
                    'default'           => 'grid',
                    'options'           => array(
                            'grid'  => 'Grid',
                            'list' => 'List'
                    )
                ),

                array(
                    'name'              => 'arp-col-style',
                    'label'             => __('Gird Styles', 'arp'),
                    'desc'              => __('If you Select Grid style.', 'arp'),
                    'type'              => 'select',
                    'default'           => 'col-3',
                    'options'           => array(
                            'col-1' => 'Column 1',
                            'col-2' => 'Column 2',
                            'col-3' => 'Column 3',
                            'col-4' => 'Column 4'
                    )
                ),

                array(
                    'name'              => 'arp-author',
                    'label'             => __('Show/Hide Author Name'),
                    'desc'              => __('Default Show.', 'arp'),
                    'type'              => 'select',
                    'options'           => array(
                        '0'        => 'Show',
                        '1'         => 'Hide' 
                    )
                ),

                array(
                    'name'              => 'arp-date',
                    'label'             => __('Show/Hide Published Date'),
                    'desc'              => __('Default Show.', 'arp'),
                    'type'              => 'select',
                    'options'           => array(
                        '0'        => 'Show',
                        '1'         => 'Hide' 
                    )
                ),

                array(
                    'name'              => 'arp-excerpt',
                    'label'             => __('Show/Hide Excerpt'),
                    'desc'              => __('Default Show.', 'arp'),
                    'type'              => 'select',
                    'options'           => array(
                        '0'        => 'Show',
                        '1'         => 'Hide' 
                    )
                ),

                array(
                    'name'              => 'arp-read-more',
                    'label'             => __('Show/Hide "Read More"'),
                    'desc'              => __('Default Show.', 'arp'),
                    'type'              => 'select',
                    'options'           => array(
                        '0'        => 'Show',
                        '1'         => 'Hide' 
                    )
                ),

            ),

            'arp_styles' => array(
                array(
                    'name'              => 'arp-main-title-font-size',
                    'label'             => __('Main Title Font Size', 'arp'),
                    'desc'              => __('Recommended Font Size: 24px;', 'arp'),
                    'type'              => 'number',
                    'default'           => '24'
                ),

                array(
                    'name'              => 'arp-main-title-font-color',
                    'label'             => __('Main Title Color', 'arp'),
                    'desc'              => __('Select a color for main title', 'arp'),
                    'type'              => 'color',
                    'default'           => '#222'
                ),

                array(
                    'name'              => 'arp-author-font-color',
                    'label'             => __('Author Text Color', 'arp'),
                    'desc'              => __('Select a color for author', 'arp'),
                    'type'              => 'color',
                    'default'           => '#222'
                ),

                array(
                    'name'              => 'arp-date-font-color',
                    'label'             => __('Date Color', 'arp'),
                    'desc'              => __('Select a color for date', 'arp'),
                    'type'              => 'color',
                    'default'           => '#222'
                ),

                array(
                    'name'              => 'arp-post-title-font-size',
                    'label'             => __('Post Title Font Size', 'arp'),
                    'desc'              => __('Recommended Font Size: 16px;', 'arp'),
                    'type'              => 'number',
                    'default'           => '16'
                ),

                array(
                    'name'              => 'arp-title-length',
                    'label'             => __('Post Title Length', 'arp'),
                    'desc'              => __('Recommended Excerpt Length: 10', 'arp'),
                    'type'              => 'number',
                    'default'           => '999'
                ),

                array(
                    'name'              => 'arp-post-title-font-color',
                    'label'             => __('Post Title Color', 'arp'),
                    'desc'              => __('Select a color for post title', 'arp'),
                    'type'              => 'color',
                    'default'           => '#222'
                ),

                array(
                    'name'              => 'arp-post-title-transform',
                    'label'             => __('Post Title Text Transform', 'arp'),
                    'desc'              => __('Default Uppercase', 'arp'),
                    'type'              => 'select',
                    'default'           => 'uppercase',
                    'options'           => array(
                        'none'             => 'Normal',
                        'uppercase'        => 'Uppercase',
                        'lowercase'        => 'Lowercase',
                        'capitalize'       => 'Capitalize'
                    )
                ),

                array(
                    'name'              => 'arp-excerpt-length',
                    'label'             => __('Excerpt Length', 'arp'),
                    'desc'              => __('Default Excerpt Length: 15', 'arp'),
                    'type'              => 'number',
                    'default'           => '15'
                ),

                array(
                    'name'              => 'arp-excerpt-font-size',
                    'label'             => __('Post Excerpt Font Size', 'arp'),
                    'desc'              => __('Default Font Size: 16px;', 'arp'),
                    'type'              => 'number',
                    'default'           => '16'
                ),

                array(
                    'name'              => 'arp-excerpt-color',
                    'label'             => __('Excerpt Text Color', 'arp'),
                    'desc'              => __('Select a Color for Post Excerpt.', 'arp'),
                    'type'              => 'color',
                    'default'           => '#222'
                ),

                array(
                    'name'              => 'arp-excerpt-line-height',
                    'label'             => __('Excerpt Line Height', 'arp'),
                    'desc'              => __('Default Line Height: 1.5', 'arp'),
                    'type'              => 'number',
                    'default'           => '1.5'
                ),

                array(
                    'name'              => 'arp-read-more-style',
                    'label'             => __('Read More Style'),
                    'desc'              => __('Default Button', 'arp'),
                    'type'              => 'select',
                    'options'           => array(
                        'btn'        => 'Button',
                        'p_text'     => 'Plain Text' 
                    )
                ),

                array(
                    'name'              => 'arp-read-more-text',
                    'label'             => __( '"Read More" Text ', 'arp' ),
                    'type'              => 'text',
                    'default'           => 'Read More &#8594;'
                ),

                array(
                    'name'              => 'arp-read-more-font-size',
                    'label'             => __('Read More Font Size', 'arp'),
                    'desc'              => __('Default Font Size: 16px;', 'arp'),
                    'type'              => 'number',
                    'default'           => '16'
                ),

                array(
                    'name'              => 'arp-read-more-bg-color',
                    'label'             => __('Read More Button BG Color', 'arp'),
                    'desc'              => __('Select a Color for "Read More" Button Background', 'arp'),
                    'type'              => 'color',
                    'default'           => '#FFF'
                ),

                array(
                    'name'              => 'arp-read-more-border-color',
                    'label'             => __('Read More Border Color', 'arp'),
                    'desc'              => __('Select a Color for "Read More" Button Border.', 'arp'),
                    'type'              => 'color',
                    'default'           => '#DFDFDF'
                ),

                array(
                    'name'              => 'arp-read-more-text-color',
                    'label'             => __('Read More Text Color', 'arp'),
                    'desc'              => __('Select a Color for "Read More" Button Text.', 'arp'),
                    'type'              => 'color',
                    'default'           => '#222'
                ),

                array(
                    'name'              => 'arp-read-more-border-radius',
                    'label'             => __('Read More Button Border Radius', 'arp'),
                    'desc'              => __('Default Font Size: 3px', 'arp'),
                    'type'              => 'number',
                    'default'           => '3'
                ),

                array(
                    'name'              => 'arp-read-more-bg-hover-color',
                    'label'             => __('Read More Hover BG Color', 'arp'),
                    'desc'              => __('Select a Color for "Read More" Button Hover Background.', 'arp'),
                    'type'              => 'color',
                    'default'           => '#DD3333'
                ),

                array(
                    'name'              => 'arp-read-more-hover-text-color',
                    'label'             => __('Read More Hover Text Color', 'arp'),
                    'desc'              => __('Select a Color for "Read More" Button Hover Text.', 'arp'),
                    'type'              => 'color',
                    'default'           => '#FFF'
                ),
            )
            
        );

        return $settings_fields;
    }

    function arp_plugin_page() {
        echo '<div class="wrap">';
        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();
        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}

endif;

$settings = new ARP_Settings_API_Related_Post();