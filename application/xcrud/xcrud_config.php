<?php
/** Configuration file; f0ska xCRUD v.1.6.23; 08/2014 */
/**
* Name:  Xcrud_config 
*
* @package Xcrud_config
* @version 1.0
* @ignore
*/
class Xcrud_config
{
    // Your database name
    public static $dbname = 'iwmf';
    // Your database username
    public static $dbuser = 'root';
    // Your database password
    public static $dbpass = '';
    // Your database host, 'localhost' is default.
    public static $dbhost = 'localhost';
    
    // theme and language
    // can be 'default', 'bootstrap', 'minimal' or your custom. Theme of xCRUD visual presentation. For using bootstrap you need to load it on your page.
    public static $theme = 'bootstrap';
    // sets default localization
    public static $language = 'en';
    // enables right-to-left (RTL) mode
    public static $is_rtl = false;
    
    // database advanced
    // Your database encoding, default is 'utf8'. Do not change, if not sure.
    public static $dbencoding = 'utf8';
    // database time zone, if you want use system default - leave empty.
    public static $db_time_zone = '';
    // Your mb_string encoding, default is 'utf-8'. Do not change, if not sure.
    public static $mbencoding = 'utf-8';
    public static $dbprefix = '';


    // session
    // If your script is already using the session, specify the session name for it. By default, the name of the session in PHP equal 'PHPSESSID'.
    public static $sess_name = 'PHPSESSID';
    // Specifies the lifetime of the session, as well as the existence of a key safety (for example, the maximum edit-saving timeout).
    public static $sess_expire = 30;
    // this option is used for compatibility with with frameworks and cms that using dynamic session name.
    public static $dynamic_session = false;
    
    
    // alternative session (reqires memcache(d) and mcrypt)
    // use this only if you have troubles with native php sessions
    public static $alt_session = false;
    // needs to protect alt. session data, required if alt. session is enabled
    public static $alt_encription_key = 'super enc key';
    // in minutes, maximum time between requests when instance's data can be valid
    public static $alt_lifetime = 30;
    // Memcache(d) host
    public static $mc_host = '23.21.226.54';
    // Memcache(d) port
    public static $mc_port = 11211;
    
    
    // scripts
    // turn on, if you want to load bootstrap via xCRUD
    public static $load_bootstrap = true;
    // loads google map api for 'POINT' type. Turn off, if your site already uses it.
    public static $load_googlemap = true;
    // loads jQuery, turn it off if you already have jQuery on your page. jQuery version must be at least 1.7. If your jQuery loads in the bottom of page, you must activate $manual_load and use  Xcrud::load_css() & Xcrud::load_js() on your page.
    public static $load_jquery = true;
    // jQueryUI, turn it on if you already have jQueryUI on your page (datepicker and slider widgets are mandatory).
    public static $load_jquery_ui = true;
    // disable, if your page already uses jCrop
    public static $load_jcrop = true;
    // Includes jQuery.noConflict(). Use according to jQuery documentation.
    public static $jquery_no_conflict = false;
    // Allows you to disable xcruds css and js output, but you can use Xcrud::load_css() & Xcrud::load_js() in your code manually.
    public static $manual_load = false;

    
    // editor
    public static $editor_init_url = 'application/xcrud/plugins/tinymce.min.js';
    public static $editor_url = 'application/xcrud/plugins/tinymce.min.js';
    // Forced initialization of editor, even if the path is not specified. Check this if you're already using editor on your page.
    public static $force_editor = false;
    // inserts visual editor on textarea fields.
    public static $auto_editor_insertion = true;
    
    
    // grid settings
    // Show primary auto-increment field in create/edit view.
    public static $show_primary_ai_field = false;
    // Show primary auto-increment column in list view.
    public static $show_primary_ai_column = false;
    // allows 'minimize' arrow in grid
    public static $can_minimize = true;
    // Start all xCRUD instances minimized.
    public static $start_minimized = false;
    // Show confirmation dialog on remove action.
    public static $remove_confirm = true;
    // Sets the maximum number of characters in the column.
    public static $column_cut = 50;
    // default limit of rows per page
    public static $limit = 25;
    // default limits list
    public static $limit_list = array('25', '50', '100', 'all');
    // make all links, emails clikable in list view
    public static $clickable_list_links = true;
    // makes filenames clikable in list view
    public static $clickable_filenames = true;
    // it allows to fix the action buttons on the right side of the table. Appears when you hover on row.
    public static $fixed_action_buttons = true;
    // shows images in list view
    public static $images_in_grid = true;
    // maximal height of thumbnails in list view
    public static $images_in_grid_height = 55;
    // displays button labels in grid
    public static $button_labels = false;
    // remove all tags from data in grid view. This is not affected to user patterns or other custom.
    public static $strip_tags = true;
    // encodes special characters to html-entities in grid view
    public static $safe_output = false;
    
    
    // print
    // print all fields and rows of table or only visible.
    public static $print_all_fields = false;
    // print grid without cutting
    public static $print_full_texts = false;
    
    
    // csv export
    // default delimiter in CSV file.
    public static $csv_delimiter = ',';
    // default enclosure in CSV file.
    public static $csv_enclosure = '"';
    // export all fields and rows of table or only visible.
    public static $csv_all_fields = true;

    
    // editing
    // display TINYINT(1),BIT(1),BOOL(1),BOOLEAN(1) fields like checkboxes
    public static $make_checkbox = true;
    // display null(empty) option in all dropdowns and multiselects
    public static $lists_null_opt = true;
    // shows ENUM field as radiobox, dropdown by default
    public static $enum_as_radio = false;
    // shows SET field as checkboxes, multiselect by default
    public static $set_as_checkboxes = false;
    // Default uploads folder on your site, relative to xCRUD folder or absolute path required. Folder is must exist.
    public static $upload_folder_def = '../uploads';
    
    
    // features
    // show print button
    public static $enable_printout = true;
    // show searck block
    public static $enable_search = true;
    // show pagination
    public static $enable_pagination = true;
    // show csv export button
    public static $enable_csv_export = true;
    // show table title and toggle button
    public static $enable_table_title = true;
     // show row numbers in grid
    public static $enable_numbers = true;
    // show row numbers in grid
    public static $enable_limitlist = true;
    // alows to sort by column
    public static $enable_sorting = true;
    // Displays information about the performance in the lower right corner.
    public static $benchmark = false;
    // turn of editing nested tables when viewing parent (can edit only when editing parent)
    public static $nested_readonly_on_view = true;
    // Sets name of tab for fields which not assigned with any tab. This tab will be created automatically. Tab will not be created when is FALSE.
    public static $default_tab = false;
    // Nested will be displayed in tab if tabs are active
    public static $nested_in_tab = true;
    
    
    // alert settings
    // email from address
    public static $email_from = 'mailer@example.com';
    // email from name
    public static $email_from_name = 'xCRUD Data Management System';
    // enables html in email letters
    public static $email_enable_html = true;

    
    // remote request options (call_page() methods)
    // allow to use your browser cookie, referer, user agent for http request to some file or url. BE CAREFUL: DON'T USE IT FOR REQUESTS TO EXTERNAL SITES!!!
    public static $use_browser_info = false;

    
    // date
    // 0 - Sunday, 1 - Monday etc. Uses in datepicker and search ranges
    public static $date_first_day = 1;
    // jqueryui date format
    public static $date_format = 'dd-mm-yy';
    // jqueryui time format
    public static $time_format = 'HH:mm:ss';
    // php date format
    public static $php_date_format = 'm-d-Y';
    // php time format
    public static $php_time_format = 'H:i:s';
    
    
    // search
    // enables -all- option for search
    public static $search_all = true;
    // available date ranges, can be translated in language file
    public static $available_date_ranges = array( 
        'next_year',
        'next_month',
        'today',
        'this_week_today',
        'this_week_full',
        'last_week',
        'last_2weeks',
        'this_month',
        'last_month',
        'last_3months',
        'last_6months',
        'this_year',
        'last_year');
    // uses for LIKE operator in SQL request
    public static $search_pattern = array('%','%');
    // make search always opened
    public static $search_opened = false;
    
    
    // map
    public static $default_point = '35.6894875,139.69170639999993';
    public static $default_text = 'your_position';
    public static $default_zoom = 8;
    public static $default_width = 500;
    public static $default_height = 300;
    public static $default_coord = true;
    public static $default_search = true;
    public static $default_search_text = 'search_here';
    
    
    // xcrud folder url
    // URL to the xCRUD folder, not real path, without a trailing slash, can be relative, e.g. 'some_folder/xcrud' or absolute, e.g. 'http://www.your_site.com/some_folder/xcrud'. If empty - will be detected automatically
    public static $scripts_url = '';
    // makes relative urls to absolute. Turn off if you have some troubles with relative urls.
    public static $urls2abs = true;
    
    
    // system integration options. NO ANY TRAILING SLASHES!
    // scripts and libraries
    public static $plugins_uri = 'plugins';
    // css, images
    public static $themes_uri = 'themes';
    // js files
    public static $lang_uri = 'languages';
    // main ajax file or url
    public static $ajax_uri = 'xcrud_ajax.php';
    // paths (relative to xcrud's folder)
    // php and ini files
    public static $themes_path = 'themes';
    // ini files
    public static $lang_path = 'languages';
    // external session
    // use only when you use integration with externall session
    public static $external_session = false;
    // loading events
    // callable param, runs before instance creation
    public static $before_construct = false;
    // callable param, runs after instance was rendered
    public static $after_render = false;
    
    
    // system
    // disables any changing data in database
    public static $demo_mode = false;
    // experimental, disables {field_tags} features
    public static $performance_mode = false;
    // in seconds. Do not change, if not sure. Xcrud clears old instances in session when you reload browser tab or open new tab with xcrud. In this case Xcrud can't work in two tabs in the same time. You can increase timeout on your risk.
    public static $autoclean_timeout = 3;
    
    
    // anti XSS
    // enable all xcrud's POST and GET data filtering
    public static $auto_xss_filtering = true;
    // Remove bad attributes such as style, onclick and xmlns
    public static $xss_disalowed_attibutes = array('on\w*', /*'style',*/ 'xmlns', 'formaction');
    // If a tag containing any of the words in the list below is found, the tag gets converted to entities.
    public static $xss_naughty_html = 'alert|applet|audio|basefont|base|behavior|bgsound|blink|body|embed|expression|form|frameset|frame|head|html|ilayer|input|isindex|layer|link|meta|object|plaintext|script|textarea|title|video|xml|xss';
    // imilar to above, only instead of looking for tags it looks for PHP and JavaScript commands that are disallowed.  Rather than removing the code, it simply converts the parenthesis to entities rendering the code un-executable.
    public static $xss_naughty_scripts = 'alert|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink';
}
