<?php
//define('D_ROOT', $_SERVER['DOCUMENT_ROOT']);
require_once(D_ROOT . "/include/config.php");

function print_head(){
    echo "<head>
        <meta http-equiv='Content-Type' content='text/html'>
        <title>" . SITE_TITLE . "</title>
        <link rel='stylesheet' type='text/css'
            href='" . THEME_LOC . "/css/style.css' />
        <link rel='icon' href='/src/uploads/favicon.ico'
            type='image/x-icon' />";
    if (file_exists(D_ROOT . '/src/themes/' .THEME. '/meta.php')){
        require_once(D_ROOT . '/src/themes/' .THEME. '/meta.php');
    }
    echo "</head>";
}

function print_nav(){
    print_head();
    echo "<div id='header'>";

    // use the theme's header file.
    if (file_exists(D_ROOT . '/src/themes/' .THEME. '/header.php')){
        require_once(D_ROOT . '/src/themes/' .THEME. '/header.php');
    // if there isn't one, then perform the default behavior.
    }else {
        echo "<h1><a href='/'>" . PAGE_TITLE . "</a></h1>
              <h2>" . SUB_PAGE_TITLE . "</h2>";
    }
    echo "</div>";
}

function print_footer(){
    // if the theme has a footer, include it.
    if (file_exists(D_ROOT . '/src/themes/' .THEME. '/footer.php')){
        echo "<div id='footer'>";
        require_once(D_ROOT . '/src/themes/' .THEME. '/footer.php');
        echo "</div>";
    }
}

function display_home(){
    print_nav();
    echo "<div id='content'>";

    $posts = file_get_contents("include/posts.json");
    $posts = json_decode($posts, True);
    $posts = array_reverse($posts);

    echo"<div id='blog-main'>";
    echo "<h2>Latest Posts</h2>";

    echo "<ul>";
    foreach($posts as $key => $info) {
        echo "<li><a href='/{$key}'>{$info['title']}</a></li>";
    }
    echo "</ul>";
    echo "</div>"; // blog-main

    // if the theme has anything else it wants to add to the content section,
    // add it in.
    if (file_exists(D_ROOT . '/src/themes/' .THEME. '/content.php')){
        require_once(D_ROOT . '/src/themes/' .THEME. '/content.php');
    }
    echo "</div>"; // content

    print_footer();
}

function check_post($post) {
    $posts = file_get_contents("include/posts.json");
    $posts = json_decode($posts, True);

    if (array_key_exists($post, $posts)){
        return True;
    }else{
        return False;
    }
}

function display_post($key) {
    $posts = file_get_contents("include/posts.json");
    $posts = json_decode($posts, True);
    $post = $posts[$key];

    require_once("include/Parsedown.php");
    $Parsedown = new Parsedown();
    $text = file_get_contents("src/posts/{$key}.txt");
    $text = $Parsedown->text($text);


    print_nav();
    echo "<div id='content'>
          <div id='post'>";

    echo "<h2>{$post['title']}</h2>";

    echo "<div id='post-content'>{$text}</div>";

    echo "</div></div>";

    print_footer();
}
