<?php

    require_once __DIR__ . "/helpers.php";
    require_once __DIR__ . "/Database.php";

    use \Cloudinary\Uploader as MediaUploader;
    
    $mediaModel = new MediaModel();
    $message = "";

    if(!empty($_POST["submit"])) {
        if(!empty($_FILES["media_file"])) {
            $uploads   = __DIR__ . "/../uploads";
            $mediaForm = $_FILES["media_file"];
            $userPrefferedName = sanitizeUserInput($_POST["media_title"]);
            $mediaName = !empty($userPrefferedName) ? $userPrefferedName : getFileName($mediaForm["name"]);
            
            if($mediaForm["error"] === 0) {
                if(isImage(($mediaForm["name"]))) {
                    if(filesize($mediaForm["tmp_name"]) < MAX_FILE_SIZE) {
                        if($uploadedMedia = MediaUploader::upload($mediaForm["tmp_name"])){
                            $uploadedMediaUrl = $uploadedMedia["secure_url"];
                            MediaModel::addMedia($uploadedMediaUrl,$mediaName);
                            $message = "Successfuly uploaded file";
                        } else {
                            $message = "Failure in uploading file";
                        }
                    } else {
                        $message = "Image is too large";
                    }
                } else {
                    $message = "File uploaded is not image";
                }
            } else {
                $message = defineErrorCode($mediaForm["error"]);
            }
        }
    
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Image/Video Uploader</title>
</head>
<body>
    <h2>Media Sharing Application</h2>
    <form method="POST" action="/index.php" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE"  value="<?php echo MAX_FILE_SIZE; ?>" />   
        <input type="text" placeholder="Preffered Name (optional)" name="media_title"/>
        <input type ="file" name="media_file" required/> 
        <input type="submit" name="submit" />
    </form>

    <br>
    <?php 
        if(!empty($message)) echo  $message;
    ?>
    <br />
    <div>
        <h2>Show all images here</h2>
        <?php 
            $media = MediaModel::all();
            foreach($media as $image) {
                echo "<img src='" . $image["url"] ."' height='300' width='300' />";
            }
        ?>
    </div>
</body>
</html>