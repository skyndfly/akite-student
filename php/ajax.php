<?php   
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

include_once './mail.php';

$errors = false;
$response = false;

if(!empty($_FILES)){
    $errors = array();
    $userName = explode(' ', $_POST['user_info']);
    foreach($userName as $j => $word){
        if($j == 0){ $pathName = $word; } 
        else { $pathName .= '_'.$word; }
    }
    $upload_patch = "./../upload/".$pathName;

    if(!file_exists($upload_patch)){ $dirimg = mkdir($upload_patch, 0777, true); }

    $docNames = explode(',', $_POST['docNames']);

    foreach ($_FILES as $key => $file) {
        $user_file_name = $file['name'];

        $time = time();
        $rand = rand();

        $basename = $time + $rand;
        $extension = pathinfo($user_file_name, PATHINFO_EXTENSION);

        foreach($docNames as $item => $name){
            if($key == $item){
                $server_filename = $name .".". $extension;
                $_FILES[$key]['name'] = $server_filename;
                break;
            }
        }

        if($file['type'] == 'image/jpeg'){ $image = @imagecreatefromjpeg($file["tmp_name"]); }
        else if($file['type'] == 'image/png'){ $image = @imagecreatefrompng($file["tmp_name"]); }

        if(@imagejpeg($image, "$upload_patch/$server_filename", 70)){
            $errors[$key] = false;
            @imagedestroy($image);
        } else {
            $errors[$key] = true;
            @imagedestroy($image);
        } 
    }

    $uploadError = false;

        foreach ($errors as $key => $error) {
            if($error){ $uploadError = true; }
        }

        if(!$uploadError){ 
            if(sendMailAttachment($_POST['user_info'], $_FILES)){ $response = true; } 
        }
        
        echo json_encode($response);
}

