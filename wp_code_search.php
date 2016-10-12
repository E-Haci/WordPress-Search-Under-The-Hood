<?php
/* 
Plugin name: Code Search by Elvin 
Version: 0.1.0
Author: Elvin Haci
Author URI: http://elwpin.com/
*/


add_action('admin_menu', 'register_code_search');



function register_code_search()
{
    add_submenu_page('options-general.php', 'Code Search', 'Code Search', 'manage_options', __FILE__, 'eh_code_search_init');
}

function eh_code_search_init()
{
    echo '<h1>Enter any input as a get variable</h1><form action="" method="get">
	<input type="text" name="string">
<input type="hidden" value="code_search" name="page">
<select name="wheretosearch"><option value="current_theme">Current theme</option><option value="all_themes">All themes</option><option value="plugins">All plugins</option></select>
<input type="submit" value="Find"></form>';
    if (isset($_GET["string"])) {
        echo '<h2>Started to search for <i>"' . esc_html($_GET["string"]) . '"</i>...</h2>';
        echo eh_code_search($_GET["string"]);
        echo 'Finished';
    }
    
    
}

function eh_code_search($string, $path = '')
{
    //$string = 'something';
    $res = '';
    
    if ($path == '') {
        if (isset($_GET["wheretosearch"]) and $_GET["wheretosearch"] == 'current_theme')
            $path = get_template_directory();
        elseif (isset($_GET["wheretosearch"]) and $_GET["wheretosearch"] == 'all_themes')
            $path = get_theme_root();
        else
            $path = untrailingslashit(plugin_dir_path(__FILE__));
    }
    
    $dir = new RecursiveDirectoryIterator($path);
    
    
    
    foreach ($dir as $file) {
        //	$res.= 'Name: '.$file.'<br>';
        if (is_dir($file) and strpos($file, '.') === false) {
            eh_code_search($string, $file);
        } else {
            $content = file_get_contents($file->getPathname());
            if (strpos(mb_strtolower($content), mb_strtolower($string)) !== false) {
                $res .= '<b>Found result:</b> ' . $file->getPathname() . '<br>';
            }
        }
        
    }
    
    echo $res;
}
