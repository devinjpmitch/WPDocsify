<?php
/**
 * @package MBC\Docsify
 */
namespace MBC\docsify;
class Git extends WPDocsify {
    private static $style_applied = false;
	public static function register(){
		self::admin();
	}
    public static function admin(){
       $git_icon = file_get_contents(plugin_dir_path(__DIR__).'assets/img/giticon.svg');
       $git_label_template = '<span class="with-icon">%s%s</span>';
       $git_label = sprintf($git_label_template, $git_icon, __('Add Git Repos', 'textdomain'));
       //create a new post type git-repositories under parent::$adminpage['slug']
       $labels = array(
            'name'               => _x( 'Git Repositories', 'post type general name', 'WPDocsify' ),
            'singular_name'      => _x( 'Git Repository', 'post type singular name', 'WPDocsify' ),
            'menu_name'          => _x( 'Git Repositories', 'admin menu', 'WPDocsify' ),
            'name_admin_bar'     => _x( 'Git Repository', 'add new on admin bar', 'WPDocsify' ),
            'add_new'            => _x( 'Add New', 'Git Repository', 'WPDocsify' ),
            'add_new_item'       => __( 'Add New Git Repository', 'WPDocsify' ),
            'new_item'           => __( 'New Git Repository', 'WPDocsify' ),
            'edit_item'          => __( 'Edit Git Repository', 'WPDocsify' ),
            'view_item'          => __( 'View Git Repository', 'WPDocsify' ),
            'all_items'          => $git_label,
            'search_items'       => __( 'Search Git Repositories', 'WPDocsify' ),
            'parent_item_colon'  => __( 'Parent Git Repositories:', 'WPDocsify' ),
            'not_found'          => __( 'No Git Repositories found.', 'WPDocsify' ),
            'not_found_in_trash' => __( 'No Git Repositories found in Trash.', 'WPDocsify' )
        );
        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'WPDocsify' ),
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'front_end'          => false,
            'show_in_menu'       => parent::$adminpage['slug'],
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'git-repositories' ),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 99,
            'supports'           => array( 'title' )
        );
        register_post_type( 'git-repos', $args );
        add_action('add_meta_boxes', array(__CLASS__, 'metaBoxes'));
        add_action('save_post', array(__CLASS__, 'savePost'));
        add_filter('manage_git-repos_posts_columns', array(__CLASS__, 'customColumns'));
        add_action('manage_git-repos_posts_custom_column', array(__CLASS__, 'customColumnsContent'), 10, 2);
    }
    public static function metaBoxes(){
        add_meta_box(
            'git-repos',
            __( 'Git Repository', 'WPDocsify' ),
            array( __CLASS__, 'gitRepoRender' ),
            'git-repos',
            'normal',
            'high'
        );
        add_meta_box(
            'docsify-options',
            __( 'Docsify Options', 'WPDocsify' ),
            array( __CLASS__, 'wpdRender' ),
            'git-repos',
            'normal',
            'high'
        );
        add_meta_box(
            'prism-options',
            __( 'Prism Options', 'WPDocsify' ),
            array( __CLASS__, 'prismRender' ),
            'git-repos',
            'normal',
            'high'
        );
        add_meta_box(
            'plugin-options',
            __( 'Plugin Options', 'WPDocsify' ),
            array( __CLASS__, 'pluginRender' ),
            'git-repos',
            'normal',
            'high'
        );
    }
    public static function savePost($post_id){
        if(!isset($_POST) || isset($_POST) && !isset($_POST['git-repo-url'])) return;
        foreach($_POST as $key => $value){
            if(strpos($key, 'git-repo-') !== false) update_post_meta($post_id, $key, $value);
        }
        return;
    }
    public static function customColumns($columns){
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __( 'Title', 'WPDocsify' ),
            'git-repo-url'  => __( 'Git Repository', 'WPDocsify' ),
        );
        return $columns;
    }
    public static function customColumnsContent($column, $post_id){
        switch($column){
            default:
                echo get_post_meta($post_id, $column, true);
                break;
        }
    }
    public static function gitRepoRender(){
        $inputs = array(
            array(
                'type' => 'text',
                'name' => 'url',
                'label' => 'Git Repository URL',
                'placeholder' => 'https://github.com/docsifyjs/docsify/tree/develop/docs',
                'description' => 'Enter the URL of the Git repository you want to use for your documentation.',
                'required' => true
            ),
            array(
                'type' => 'text',
                'name' => 'homepage',
                'label' => 'Homepage',
                'placeholder' => '_coverpage.md',
                'description' => 'Enter the markdown filename for the homepage.',
                'required' => true
            ),
            array(
                'type' => 'text',
                'name' => 'loadsidebar',
                'label' => 'Sidebar',
                'placeholder' => '_sidebar.md',
                'description' => 'Enter the markdown filename for the sidebar. no value is no sidebar.',
                'required' => false
            )
        );
        self::renderInputs($inputs);
    }
    public static function wpdRender(){
        $inputs = array(
            array(
                'type' => 'checkbox',
                'name' => 'excutescript',
                'label' => 'Excute Scripts',
                'value' => 'true',
                'description' => 'If you want to excute scripts in your markdown files, check this box.',
                'required' => false
            ),
            array(
                'type' => 'checkbox',
                'name' => 'nativeemoji',
                'label' => 'Native Emoji Support',
                'value' => 'true',
                'description' => 'If you want to use native emoji support, check this box.',
                'required' => false
            ),
        );
        self::renderInputs($inputs);
    }
    public static function prismRender(){
        $inputs = array(
            array(
                'type' => 'select',
                'name' => 'prism',
                'label' => 'Prism Support',
                'values' => array(
                    "actionscript",
                    "apacheconf",
                    "applescript",
                    "aspnet",
                    "autohotkey",
                    "bash",
                    "c",
                    "clike",
                    "coffeescript",
                    "core",
                    "cpp",
                    "csharp",
                    "css-extras",
                    "css",
                    "dart",
                    "eiffel",
                    "erlang",
                    "fortran",
                    "fsharp",
                    "gherkin",
                    "git",
                    "go",
                    "groovy",
                    "haml",
                    "handlebars",
                    "haskell",
                    "http",
                    "ini",
                    "jade",
                    "java",
                    "javascript",
                    "jsx",
                    "julia",
                    "latex",
                    "less",
                    "lolcode",
                    "markdown",
                    "markup",
                    "matlab",
                    "nasm",
                    "nsis",
                    "objectivec",
                    "pascal",
                    "perl",
                    "php-extras",
                    "php",
                    "powershell",
                    "python",
                    "r",
                    "rest",
                    "rip",
                    "ruby",
                    "rust",
                    "sas",
                    "scala",
                    "scheme",
                    "scss",
                    "smalltalk",
                    "smarty",
                    "sql",
                    "stylus",
                    "swift",
                    "twig",
                    "typescript",
                    "wiki",
                    "yaml"
                ),
                'description' => 'Select the prism languages you want to load.',
                'required' => false
            )
        );
        self::renderInputs($inputs);
    }
    public static function pluginRender(){
        $inputs = array(
            array(
                'type' => 'repeater',
                'name' => 'plugins',
                'label' => 'Load Plugins',
                'description' => 'Add CDN urls of plugins you wish to load with docsify.',
                'required' => false
            )
        );
        self::renderInputs($inputs);
    }
    public static function renderInputs($inputs){
        foreach($inputs as $input){
            $input['name'] = 'git-repo-'.$input['name'];
            $value = get_post_meta(get_the_ID(), $input['name'], true);
            echo "<div class='wpdocsify-repo-inputs'>";
            $label = "<label for='%s'>%s</label>";
            echo sprintf($label, $input['name'], $input['label']);
            $description = "<p class='description'>%s</p>";
            echo sprintf($description, $input['description']);
            switch($input['type']){
                case 'text':
                        $template = "<input type='text' name='%s' id='%s' value='%s' placeholder='%s' %s>";
                        echo sprintf($template, $input['name'], $input['name'], $value, $input['placeholder'], $input['required'] ? 'required' : '');
                        break;
                case 'checkbox':
                        $template = '<label class="switch"><input type="%s" name="%s" id="%s" value="%s" %s %s><span class="slider"></span></label>';
                        echo sprintf($template, $input['type'], $input['name'], $input['name'], $input['value'], checked($value, $input['value'], false), $input['required'] ? 'required' : '');
                        break;
                case 'select':
                        if(!is_array($value)) $value = $value !== '' ? explode(',',$value) : array();
                        $template_input = "<input type='hidden' name='%s' id='%s' value='%s' %s>";
                        echo sprintf($template_input, $input['name'], $input['name'], implode(',',$value), $input['required'] ? 'required' : '');
                        $template = "<select multiple name='%s' id='%s' %s>";
                        echo sprintf($template, $input['name'].'_multi', $input['name'].'_multi', $input['required'] ? 'required' : '');
                        foreach($input['values'] as $select_value){
                            $selected = in_array($select_value, $value) ? 'selected' : '';
                            $option_template = "<option value='%s' %s>%s</option>";
                            echo sprintf($option_template, $select_value, $selected,ucfirst($select_value));
                        }
                        echo "</select>";
                        break;
                case 'repeater':
                    $repeater = $value ? json_decode($value) : array(
                        array(
                            'url' => '',
                            'config' => ''
                        )
                    );
                    $template = '
                    <div class="wpdocsify-repeater">
                        <div class="default" hidden>
                            %s
                        </div>
                        <div class="rows">
                            %s
                        </div>
                        <div class="button button-primary wpdocsify-repeater-add">Add New Plugin</div>
                    </div>
                    ';
                    $template_input_output = '';
                    $template_input_default = '';

                    $template_input = "
                    <div class='repeater_row_%s row'>
                        <label>URL</label>
                        <input type='text' name='%s' id='%s' value='%s' placeholder='https://cdn.example.com/js/plugin.min.js'>
                        <label>Config</label>
                        <textarea name='%s' id='%s' rows='5' placeholder='&#x0063;&#x006F;&#x0070;&#x0079;&#x0043;&#x006F;&#x0064;&#x0065;&#x003A;&#x0020;&#x007B;&#x000A;&#x0020;&#x0020;&#x0020;&#x0020;&#x0062;&#x0075;&#x0074;&#x0074;&#x006F;&#x006E;&#x0054;&#x0065;&#x0078;&#x0074;&#x0020;&#x003A;&#x0020;&#x0027;&#x0043;&#x006F;&#x0070;&#x0079;&#x0020;&#x0074;&#x006F;&#x0020;&#x0063;&#x006C;&#x0069;&#x0070;&#x0062;&#x006F;&#x0061;&#x0072;&#x0064;&#x0027;&#x002C;&#x000A;&#x0020;&#x0020;&#x0020;&#x0020;&#x0065;&#x0072;&#x0072;&#x006F;&#x0072;&#x0054;&#x0065;&#x0078;&#x0074;&#x0020;&#x0020;&#x003A;&#x0020;&#x0027;&#x0045;&#x0072;&#x0072;&#x006F;&#x0072;&#x0027;&#x002C;&#x000A;&#x0020;&#x0020;&#x0020;&#x0020;&#x0073;&#x0075;&#x0063;&#x0063;&#x0065;&#x0073;&#x0073;&#x0054;&#x0065;&#x0078;&#x0074;&#x003A;&#x0020;&#x0027;&#x0043;&#x006F;&#x0070;&#x0069;&#x0065;&#x0064;&#x0027;&#x000A;&#x007D;'>%s</textarea>
                        <div class='actions'>
                            <div class='button button-primary' 
                            onclick='this.parentNode.parentNode.remove()'>Delete Row</div>
                        </div>
                    </div>
                    ";
                    $default_url_name = $input['name'].'_$number_url';
                    $default_config_name = $input['name'].'_$number_config';
                    $template_input_default = sprintf(
                        $template_input,
                        '$number',
                        $default_url_name,
                        $default_url_name,
                        '',
                        $default_config_name,
                        $default_config_name,
                        ''
                    );
                    foreach($repeater as $index => $repeater_item){
                        $url_name =  $input['name'].'_'.$index.'_url';
                        $config_name = $input['name'].'_'.$index.'_config';
                        $template_input_output .= sprintf(
                            $template_input,
                            $index,
                            $url_name,
                            $url_name,
                            $repeater_item['url'] ? $repeater_item['url'] : '',
                            $config_name,
                            $config_name,
                            $repeater_item['config'] ? $repeater_item['config'] : ''
                        );
                    }
                    echo sprintf($template,$template_input_default,$template_input_output);
                    break;
            }
            echo "</div>";
        }
        if(!self::$style_applied){
            $style_template = '<style>%s</style>';
            $script_template = '<script>%s</script>';
            $style = file_get_contents(plugin_dir_path(__DIR__).'assets/css/git-repos.min.css');
            $script = file_get_contents(plugin_dir_path(__DIR__).'assets/js/git-repos.min.js');
            echo sprintf($style_template, $style);
            echo sprintf($script_template, $script);
            self::$style_applied = true;
        }
    }
}
