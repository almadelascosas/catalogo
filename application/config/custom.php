<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* APPLICATION SETTINGS */
$config['app_name'] = 'Alma de las Cosas App';
$config['app_email'] = "";
$config['app_default_uri'] = 'dashboard';
$config['app_theme'] = 'admin';
$config['app_theme_front'] = 'modern';
$config['valids_formats_image'] = array('jpg','jpeg','png');

/* SYSTEM SETTINGS */
$config['language']	= 'english';

/* EMAIL SETTINGS */
$config['email_protocol'] = 'smtp';
$config['email_smtp_host'] = 'smtp.gmail.com';
$config['email_smtp_port'] = '587';
$config['email_smtp_user'] = '';
$config['email_smtp_pass'] = '';
$config['email_mailtype'] = 'html';
$config['email_newline'] = "\r\n";
$config['email_charset'] = 'utf-8';

/* CARABINER SETTINGS */
$config['carabiner_script_dir'] = 'assets/'.$config['app_theme'].'/';
$config['carabiner_style_dir'] = 'assets/'.$config['app_theme'].'/';
$config['carabiner_cache_dir'] = 'assets/cache/';
$config['carabiner_dev'] = FALSE;
$config['carabiner_combine'] = TRUE;
$config['carabiner_minify_js'] = TRUE;
$config['carabiner_minify_css'] = TRUE;
$config['carabiner_force_curl'] = TRUE;

/*GROCERY CRUD SETTINGS */
//For view all the languages go to the folder assets/grocery_crud/languages/
$config['grocery_crud_default_language']  = $config['language'];

// There are only three choices: "uk-date" (dd/mm/yyyy), "us-date" (mm/dd/yyyy) or "sql-date" (yyyy-mm-dd)
$config['grocery_crud_date_format']         = 'uk-date';

// The default per page when a user firstly see a list page
$config['grocery_crud_default_per_page']    = 50;

$config['grocery_crud_file_upload_allow_file_types']        = 'gif|jpeg|jpg|png|tiff|doc|docx|txt|odt|xls|xlsx|pdf|ppt|pptx|pps|ppsx|mp3|m4a|ogg|wav|mp4|m4v|mov|wmv|flv|avi|mpg|ogv|3gp|3g2';
$config['grocery_crud_file_upload_max_file_size']           = '20MB'; //ex. '10MB' (Mega Bytes), '1067KB' (Kilo Bytes), '5000B' (Bytes)

//You can choose 'ckeditor','tinymce' or 'markitup'
$config['grocery_crud_default_text_editor'] = 'ckeditor';
//You can choose 'minimal' or 'full'
$config['grocery_crud_text_editor_type']    = 'full';

//The character limiter at the list page, zero(0) value if you don't want character limiter at your list page
$config['grocery_crud_character_limiter']   = 30;

//All the forms are opening with dialog forms without refreshing the page once again.
//IMPORTANT: PLease be aware that this functionality is still in BETA phase and it is
//not suggested to use this in production mode
$config['grocery_crud_dialog_forms'] = false;

//Having some options at the list paging. This is the default one that all the websites are using.
//Make sure that the number of grocery_crud_default_per_page variable is included to this array.
$config['grocery_crud_paging_options'] = array('10','25','50','100');

//Default theme for grocery CRUD
$config['grocery_crud_default_theme'] = 'bootstrap';

//The environment is important so we can have specific configurations for specific environments
$config['grocery_crud_environment'] = 'development';
/* End of file config.php */
/* Location: ./application/config/application.php */
