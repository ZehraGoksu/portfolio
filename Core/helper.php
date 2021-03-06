<?php

use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use App\Models\AdminNotifications;
use Cocur\Slugify\Slugify;

function config($key, $default = null ){
    return \Arrilot\DotEnv\DotEnv::get( $key, $default );
}



function base_url( $url = "" ){
    return config("BASE_URL") . $url ;
}


function auth(){
    return \Core\Auth::getInstance();
}


function passwordHash($password){
    $factory = new PasswordHasherFactory([
        'common' => ['algorithm' => 'bcrypt'],
        'memory-hard' => ['algorithm' => 'sodium'],
    ]); 
    
    $passwordHasher = $factory->getPasswordHasher('common');

    return $passwordHasher->hash($password);
}


function passwordVerify($hash, $password  ){
    $factory = new PasswordHasherFactory([
        'common' => ['algorithm' => 'bcrypt'],
        'memory-hard' => ['algorithm' => 'sodium'],
    ]); 
    
    $passwordHasher = $factory->getPasswordHasher('common');

    return $passwordHasher->verify($hash, $password  );
}


function response( $content = [] ){
    $response = new \Symfony\Component\HttpFoundation\Response();
    $response->headers->set("Content-type", "application/json");
    $response->setContent( json_encode($content) );
    $response->send(); 
}




    function redirect($url = "", $status = null, $message = null){
        $tmpMessage = ""; 
        if( $status != null || $status == false ){
            $tmpMessage = "?status=". ($status ? "basarili" : "hata") ."&message=".$message;
        } 
        header("Location:". base_url( $url.$tmpMessage ) );
    }


    function format_date( $date, $format = "d/F/Y H:i" ){
        $date_formatter = new \Jenssegers\Date\Date();
        $date_formatter->setLocale("tr");
        return $date_formatter->parse($date)->format($format);
    }


    function addAdminNotification( $title, $type = "success" ){
       
      AdminNotifications::create(
            [
                "title"=> $title, 
                "type" =>$type 
             ]
        );

    }


    function slug( $text ){        
        $slugify = new Slugify();
        return $slugify->slugify( $text );
    }


    function upload( $name ){
        return \Core\Upload::getInstance($name);
    }

    function guid() {
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

    function imgDir(){
        return "uploads/".date("Y")."/".date("m");
    }


?>