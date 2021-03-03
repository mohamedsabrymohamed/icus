<?php
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\SMTP;
use \PHPMailer\PHPMailer\Exception;
//////////////////////////////////global variables////////////////////////////////
$user_agent     =   $_SERVER['HTTP_USER_AGENT'];
//////////////////////////////////global functions////////////////////////////////
//////////////////////////////// check session time out //////////////////////////
function verify_session_time_out()
{
    $user_id = @$_SESSION['user_id'];
    if($user_id and !empty($user_id) and isset($user_id))
    {
        $session_timeout = $_SESSION['web_session_timeout'];
        $saved_time = strtotime(date('Y-m-d H:i:s',$_SESSION['timeout']));
        $current_time = strtotime(date('Y-m-d H:i:s',time()));

        $interval  = abs($current_time - $saved_time);
        if($interval > $session_timeout)
        {
            $login_table=new log_table();
            $update_login=$login_table->update_login_user($user_id,session_id());
            unset_user_session();
            return false;
        }
    }
    $_SESSION['timeout'] = time();
    return true;
}
/////////////////////////////////////unset user data after logout ///////////////////
function unset_user_session()
{
    $user_id = @$_SESSION['user_id'];
    if($user_id and !empty($user_id) and isset($user_id))
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['access_token']);
        unset($_SESSION['timeout']);
        unset($_SESSION['web_session_timeout']);
        $_SESSION['LOGOUT'] = NULL;
        unset($_SESSION);
        session_destroy();
    }
}
//////////////////////////////////check if user is login or not ///////////////////
function is_user_login()
{
    if(verify_session_time_out()) {
        $user_id = @$_SESSION['user_id'];
        if ($user_id and !empty($user_id) and isset($user_id)) {
            return true;
        }
    }
    return false;
}
////////////////////////////////// get login user id//////////////////////////////
function get_login_user_id()
{
    if(is_user_login())
    {
        $user_id = @$_SESSION['user_id'];
        return $user_id;
    }
    return false;

}

////////////////////////////////// get login user OS//////////////////////////////
function getOS() {

    global $user_agent;

    $os_platform    =   "Unknown OS Platform";

    $os_array       =   array(
        '/windows nt 10/i'     =>  'Windows 10',
        '/windows nt 6.3/i'     =>  'Windows 8.1',
        '/windows nt 6.2/i'     =>  'Windows 8',
        '/windows nt 6.1/i'     =>  'Windows 7',
        '/windows nt 6.0/i'     =>  'Windows Vista',
        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     =>  'Windows XP',
        '/windows xp/i'         =>  'Windows XP',
        '/windows nt 5.0/i'     =>  'Windows 2000',
        '/windows me/i'         =>  'Windows ME',
        '/win98/i'              =>  'Windows 98',
        '/win95/i'              =>  'Windows 95',
        '/win16/i'              =>  'Windows 3.11',
        '/macintosh|mac os x/i' =>  'Mac OS X',
        '/mac_powerpc/i'        =>  'Mac OS 9',
        '/linux/i'              =>  'Linux',
        '/ubuntu/i'             =>  'Ubuntu',
        '/iphone/i'             =>  'iPhone',
        '/ipod/i'               =>  'iPod',
        '/ipad/i'               =>  'iPad',
        '/android/i'            =>  'Android',
        '/blackberry/i'         =>  'BlackBerry',
        '/webos/i'              =>  'Mobile'
    );

    foreach ($os_array as $regex => $value) {

        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }

    }

    return $os_platform;

}
////////////////////////////////// get login user Browser//////////////////////////////
function getBrowser() {

    global $user_agent;

    $browser        =   "Unknown Browser";

    $browser_array  =   array(
        '/msie/i'       =>  'Internet Explorer',
        '/firefox/i'    =>  'Firefox',
        '/safari/i'     =>  'Safari',
        '/chrome/i'     =>  'Chrome',
        '/opera/i'      =>  'Opera',
        '/netscape/i'   =>  'Netscape',
        '/maxthon/i'    =>  'Maxthon',
        '/konqueror/i'  =>  'Konqueror',
        '/mobile/i'     =>  'Handheld Browser'
    );

    foreach ($browser_array as $regex => $value) {

        if (preg_match($regex, $user_agent)) {
            $browser    =   $value;
        }

    }

    return $browser;

}


/////////////////////////  file upload /////////////////////////////
function file_upload($file)
{
    $fileName    = $_FILES[$file]["name"];
    $fileSize    = $_FILES[$file]["size"] / 1024;
    $fileType    = $_FILES[$file]["type"];
    $fileTmpName = $_FILES[$file]["tmp_name"];


        if ($fileSize <= 15120) {

            //New file name
            $random = rand(1111, 9999);
            $newFileName = $random . $fileName;

            //File upload path
            $uploadPath = "uploads/" .  $newFileName;

            //function for upload file
            if (move_uploaded_file($fileTmpName, $uploadPath)) {
                return $newFileName;
            }
        } else {
            return("Maximum upload file size limit is 15 Mb");
        }
    return false;
}
