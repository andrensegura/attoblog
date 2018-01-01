<?php
//define('D_ROOT', $_SERVER['DOCUMENT_ROOT']);
require_once(D_ROOT . "/include/config.php");

function print_head(){
    echo "<head>
        <meta http-equiv='Content-Type' content='text/html'>
        <title>" . SITE_TITLE . ": Admin Dashboard</title>
        <link rel='stylesheet' type='text/css'
            href='/admin/style.css' />
        <link rel='icon' href='/src/uploads/favicon.ico'
            type='image/x-icon' />";
    echo "</head>";
}

function print_nav(){
    print_head();
    echo "<div class='top'>
            <h1><a href='/admin/'>Atto Admin Dashboard</a></h1>
            <h2>" . PAGE_TITLE . "</h2>
            <a href='/'>[to main site]</a>
          </div>";

    echo "<div class='side'>
            <ul>
            <li><a href='/admin/posts/'>Posts</a></li>
            <li><a href='/admin/media/'>Media</a></li>
            <li><a href='/admin/settings/'>Settings</a></li>
            <ul>
          </div>";
}

function print_footer(){
    echo "<div id='footer'>";
    echo "</div>";
}

///////////////////////////////////////////////////
// POSTS STUFF
///////////////////////////////////////////////////

function show_posts_options(){
    $request = explode('/', $_SERVER['REQUEST_URI']);
    if (isset($request[3]) && ($request[3] != '')){
        switch ($request[3]) {
            case 'new':
                edit_post();
                break;
            case 'edit':
                edit_post($request[4]);
                break;
            case 'delete':
                delete_post($request[4]);
                break;
            default:
                echo "Invalid URL";
        }
    } else {
        echo "<a href='/admin/posts/new/'>New Post</a><hr>";
        show_all_posts();
    }
}

function show_all_posts(){
    $posts = file_get_contents("../include/posts.json");
    $posts = json_decode($posts, True);

    foreach($posts as $key => $info) {
        echo "<div class='post-ops'>
                <a href='/{$key}'>{$info['title']}</a>
                <a href='/admin/posts/edit/{$key}'>edit</a>
                <a href='/admin/posts/delete/{$key}'
                    onclick='return confirm(\"Are you sure you want to "
                    . " remove: " . $info['title'] . "?\")'>delete</a>
                </li></div>";
    }
}

function check_post($post) {
    $posts = file_get_contents("../include/posts.json");
    $posts = json_decode($posts, True);

    if (array_key_exists($post, $posts)){
        return True;
    }else{
        return False;
    }
}

function display_post($key) {
    $posts = file_get_contents("../include/posts.json");
    $posts = json_decode($posts, True);
    $post = $posts[$key];

    require_once("../include/Parsedown.php");
    $Parsedown = new Parsedown();
    $text = file_get_contents("../src/posts/{$key}.txt");
    $text = $Parsedown->text($text);

    echo "<div id='post'>";
    echo "<h2>{$post['title']}</h2>";
    echo "<div id='post-content'>{$text}</div>";
    echo "</div>";

    print_footer();
}

function edit_post($key = ''){
    handle_save_post();
    if (!empty($key)){
        $posts = file_get_contents("../include/posts.json");
        $posts = json_decode($posts, True);
        $post = $posts[$key];
        $text = file_get_contents("../src/posts/{$key}.txt");
    }else{
        $post = array("title" => '', "date" => '');
        $text = '';
    }

    echo "<form method='post'>
            <input type='text' name='title' placeholder='Subject'
                value='{$post['title']}'>
            <input type='text' name='url' placeholder='URL'
                value='{$key}'>
            <textarea name='body'>{$text}</textarea>
            <br>
            <input type='submit' name='preview' value='Preview'>
            <input type='submit' name='save' value='Save'>
          <br>";
}

function handle_save_post(){
    extract($_POST);
    if(isset($save)){
        $posts = file_get_contents("../include/posts.json");
        $posts = json_decode($posts, True);
        $posts[$url] = array("title" => $title, "date" => "test");
        $posts = json_encode($posts);
        file_put_contents("../include/posts.json", $posts);

        file_put_contents("../src/posts/{$url}.txt", $body);
    }
}

function delete_post($post){
    $posts = file_get_contents("../include/posts.json");
    $posts = json_decode($posts, True);
    if(isset($posts[$post])){
        unset($posts[$post]);
        $posts = json_encode($posts);
        file_put_contents("../include/posts.json", $posts);
        unlink("../src/posts/{$post}.txt");
        echo "<div class='removed'>
                <h2>The post \"{$post}\" has been removed.</h2>
                <a href='/admin/posts/'>Go back</a>
             </div>";
    }else{
        echo "<div class='removed'>
                <h2>Something went wrong.</h2>
                <a href='/admin/posts/'>Go back</a>
             </div>";
    }
}

/////////////////////////////////////////////
// END POSTS STUFF
/////////////////////////////////////////////

function display_home(){
    echo "main";
}

function show_settings(){
    handle_setting_changes();

    ////////
    //THEME
    ////////
    $themes = array_filter(glob('../src/themes/*'), 'is_dir');
    echo "<form method='post'>
          <span>Current theme:</span>&nbsp<b>" . THEME . "</b><br>
          <span>Select theme:</span> <select name='theme'>";
    foreach ($themes as $theme) {
        $theme = basename($theme);
        echo "<option value='{$theme}'>{$theme}</option>";
    }
    echo "</select>";
    echo "<input type='submit' value='Change Theme'>";
    echo "</form>";
    echo "<hr>";

    //////////
    // TITLES
    //////////
    echo "<form method='post'>
            <span>Site Title:</span>
            <input type='text' name='site_title'
            value='" . SITE_TITLE . "'>
              <br>
            <span>Blog Title:</span>
            <input type='text' name='page_title'
            value='" . PAGE_TITLE . "'>
              <br>
            <span>Sub Title:</span>
            <input type='text' name='sub_page_title'
            value='" . SUB_PAGE_TITLE . "'>
            <input type='submit' value='Save Titles'>
          </form>
          <hr>";

    ////////////////
    // HOME URL
    ////////////////
    echo "<form method='post'>
            <span>Home URL (caution!):</span>
            <input type='text' name='home' value='" . HOME . "'>
            <input type='submit' value='Save Titles'>
          </form>";
}

function handle_setting_changes(){
    extract($_POST);
    $changed = false;
    $jcfg_file = $_SERVER['DOCUMENT_ROOT'] . "/include/config.json";
    $jcfg = file_get_contents($jcfg_file);
    $options = json_decode($jcfg, true);

    if (isset($theme)){
        $options['theme'] = $theme;
        $changed = true;
    }
    if (isset($site_title)){
        $options['site_title'] = $site_title;
        $changed = true;
    }
    if (isset($page_title)){
        $options['page_title'] = $page_title;
        $changed = true;
    }
    if (isset($sub_page_title)){
        $options['sub_page_title'] = $sub_page_title;
        $changed = true;
    }
    if (isset($home)){
        $options['home'] = $home;
        $changed = true;
    }


    $options = json_encode($options);
    file_put_contents($jcfg_file, $options);
    if ($changed) { header("Refresh:0"); }
}

function show_media(){
    if (isset($_FILES['uploaded_file'])){
        upload_file();
    } else if (isset($_POST['delete_file'])) {
        delete_file();
    }

    echo "<form enctype='multipart/form-data' method='post' action=''>
            Upload File <input type='file' name='uploaded_file'></input>
            <input type='submit' value='Upload'></input>
          </form>";

    echo "<h1>Pics</h1>";
    display_media_type("bmp,gif,jpeg,png,raw,tiff,jpg,webp,svg,eps,pct,pcx,pdf,psd,tga,wmf", 'img');
    echo "<h1>Vids</h1>";
    display_media_type("3g2,3gp,asf,avi,flv,m4v,mov,mp4,mpg,rm,srt,swf,vob,wmv", 'video');
}

function display_media_type($exts, $type){
    $delbutton1 = "<form enctype='multipart/form-data' method='post' action=''>
                   <input type='hidden' name='delete_file' value='";
    $delbutton2 = "'></input>
                   <input type='submit' value='&#x274C'></input>
                   </form>";

    $files = glob("../src/uploads/*.{" . $exts . "}", GLOB_BRACE);
    sort($files);
    echo "<div class='media-container'>";
    foreach($files as $file){
        $file = basename($file);
        echo "<div class='med-preview'>
                <div class='med-options'>
                    <div class='med-option-button'>
                        <a href='/src/uploads/{$file}' target='blank'>
                            &#x1F441</a>
                    </div>
                    <div class='med-option-button'>
                        <a href='/src/uploads/{$file}' download>
                            &#129035;</a>
                    </div>
                    <div class='med-option-button'>"
                     . $delbutton1 . $file . $delbutton2 . "</div>
                </div>";

        if ($type == 'img') {
            echo "<img src='/src/uploads/{$file}'>";
        }else if ($type == 'video') {
            echo "<video controls preload='metadata' poster=''>
                      <source src='/src/uploads/{$file}'>
                  </video>";
        }

        echo "</div>";
    }
    echo "</div>";
}

function upload_file(){
    $file = $_FILES['uploaded_file'];
    $path = "../src/uploads/";
    $path = $path . basename($file['name']);

    if (move_uploaded_file($file['tmp_name'], $path)) {
        echo "<span class='upload-success'>The file "
            . basename($file['name']) . " has been uploaded.</span>";
    } else {
        echo "There was an error uploading the file.";
    }
}

function delete_file(){
    unlink("../src/uploads/" . $_POST['delete_file']);
    header("Refresh:0");
}

function check_option($option){
    print_nav();
    echo "<div class='option'>";

    switch ($option){
        case 'posts':
            show_posts_options();
            break;
        case 'media':
            show_media();
            break;
        case 'settings':
            show_settings();
            break;
        default:
            display_home();
    }

    print_footer();
}
