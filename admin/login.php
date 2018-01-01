<?php
define('D_ROOT', $_SERVER['DOCUMENT_ROOT']);
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

function show_login_screen(){
    print_head();
    echo "<div id='login_screen'>
            <div><h1 id='login_header' >Atto Admin Dashboard</h1></div>
            <form id='login_form' method='post' >
                <div class='login_input'>
                    <input type='input' placeholder='Username'
                    name='username'></div>
                <div class='login_input'>
                    <input type='password' placeholder='Password'
                    name='password'></div>
                <div class='login_input'>
                    <input type='submit' value='Login'></div>
            </form>
          </div>";
}

function test_credentials(){
    try {
        $inputUser = $_POST['username'];
        $inputPass = $_POST['password'];

        require_once('li.php'); //$user and $pass
        if ( ($inputUser == $user) && (password_verify($inputPass, $pass)) ) {
            return True;
        } else {
            return False;
        }
    } catch (Exception $e){
        echo "Caught exception: ", $e->getMessage(), "\n";
        return False;
    }
}

function set_cookie($user_string){
    //random 8 digit number between 00000000 and 99999999
    $random_num = str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);
    $cookie_value = hash('sha256', $random_num . $user_string );
    $cookie_name = "attodash";
    $time = time() + (86400 * 3);

    setcookie($cookie_name, $cookie_value, $time, "/");

}



///////////////////////////////////////////
if (test_credentials()){
    echo "Successfully logged in!";
} else{
    show_login_screen();
}
