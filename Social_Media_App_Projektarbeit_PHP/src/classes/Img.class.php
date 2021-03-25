<?php
class Img
{
    protected $fileName;
    protected $path;
    protected $thumbPath;
    protected $dataType;

    /**
     * Reads Image from $_FILES and saves original and thumbnail 
     * 
     * @param int $userId UserID (required for path and thumbPath)
     * @return string Error message
     */
    public function uploadImg($userId)
    {
        $imgDir = "resources/userUploads/$userId/uploads/";
        $thumbDir = "resources/userUploads/$userId/thumbnails/";
        $err = NULL;
        //Check if File has been uploaded
        if (empty($_FILES["postImg"]["name"])) {
            $err = "Dieses Feld darf nicht leer sein.";
        }
        //Check if path exists
        else if (!file_exists($imgDir) || !file_exists($thumbDir)) {
            $err = "Der angegeben Pfad existiert nicht.";

            //Check if filename is valid
        } else if (!preg_match("/^[-a-zA-Z0-9-' ^_.ÄäÖöÜüß]+$/", basename($_FILES["postImg"]["name"]))) {
            $err = "Der Dateinname ist ungültig.";
        } 
        //check filename length
        else if (strlen(($_FILES["postImg"]["name"])) > 255) {
            $err =  "Der Dateiname ist zu lang.";
        } else {
            //Create path for image
            $imgPath = $imgDir . basename($_FILES["postImg"]["name"]);

            //Create img name
            $imgFileName = pathinfo(basename($_FILES["postImg"]["name"]), PATHINFO_FILENAME);

            //Create path for thumbnail
            $thumbPath = $thumbDir . $imgFileName . ".jpeg";

            //get dataType of  Img;
            $dataType = strtolower(pathinfo($imgPath, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["postImg"]["tmp_name"]);
            if ($check == false) {
                $err =  "Bitte laden Sie ein Bild hoch.";
            }
            // Check if file already exists
            else if (file_exists($imgPath) || file_exists($thumbPath)) {
                $err =  "Das hochgeladene Bild existiert bereits.";
            }

            // Check file size
            else if ($_FILES["postImg"]["size"] > 10000000) {
                $err =  "Die hochgeladene Datei ist zu groß.";
            }

            // Allow certain file formats
            else if (
                $dataType != "jpg" && $dataType != "png" && $dataType != "jpeg"
                && $dataType != "gif"
            ) {
                $err = "Das hochgeladene Dateiformat wird nicht unterstützt.";
            }
        }
        if ($err == NULL) {
            $this->fileName = basename($_FILES["postImg"]["name"]);;
            $this->path = $imgPath;
            $this->thumbPath = $thumbPath;
            $this->dataType = $dataType;


            if (move_uploaded_file($_FILES["postImg"]["tmp_name"], $imgPath) && $this->createThumbnail($userId)) {
            } else {
                $err = "Beim Upload ist ein Fehler aufgetreten.";

                $this->deleteImg();
            }
        }
        return $err;
    }

    /** 
     * creates thumbnail of uploaded img
     * @return bool $continue true if no errors occured else false
     *  */  
    private function createThumbnail()
    {
        $continue = false;
        //get Original img with & height
        if (list($realWidth, $realHeight) = getimagesize($this->path)) {

            $thumbWidth = $realWidth;
            $thumbHeight = $realHeight;

            $factor = 400 / min($thumbWidth, $thumbHeight);

            $thumbWidth = round($thumbWidth * $factor);

            $thumbHeight = round($thumbHeight * $factor);

            //Create blank thumbnail
            if ($thumb = imagecreatetruecolor($thumbWidth, $thumbHeight)) {

                //load original img resource
                $realImg = NULL;
                switch ($this->dataType) {
                    case "gif":
                        $realImg = imagecreatefromgif($this->path);
                        break;

                    case "jpg":
                        //&&
                    case "jpeg":
                        $realImg = imagecreatefromjpeg($this->path);
                        break;

                    case "png":
                        $realImg = imagecreatefrompng($this->path);
                        break;

                    default:
                        return $continue;
                }

                //copy original img to blank thumbnail
                if (imagecopyresampled($thumb, $realImg, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $realWidth, $realHeight)) {

                    //crop thumbnail to center
                    if ($thumbWidth > $thumbHeight) {
                        $cropX = $thumbWidth / 2 - $thumbHeight / 2;
                        $thumb = imagecrop($thumb, ['x' => $cropX, 'y' => 0, 'width' => $thumbHeight, 'height' => $thumbHeight]);
                    } else {
                        $cropY = $thumbHeight / 2 - $thumbWidth / 2;
                        $thumb = imagecrop($thumb, ['x' => 0, 'y' => $cropY, 'width' =>  $thumbWidth, 'height' =>  $thumbWidth]);
                    }
                    //upload finished thumbnail
                    if (imagejpeg($thumb, $this->thumbPath)) {
                        //delete cache
                        imagedestroy($thumb);
                        $continue = true;
                    }
                }
                //delete cache
                imagedestroy($realImg);
            }
        }
        return $continue;
    }

    /** 
     * get all properties of object
     * @return array of all properties of object
     */
    public function getAllVars()
    {
        return get_object_vars($this);
    }

    public function getProperty($propName)
    {
        if (property_exists($this, $propName)) {
            return $this->$propName;
        }
        return NULL;
    }

    /**
     * Loads image from filesystem
     * 
     * @param string $imgName Image name
     * @param int $userId User_ID
     */
    public function loadImage($imgName, $userId)
    {

        $imgPath = "resources/userUploads/$userId/uploads/$imgName";

        //Create path for thumbnail
        $imgFileName = pathinfo($imgName, PATHINFO_FILENAME);
        $thumbPath = "resources/userUploads/$userId/thumbnails/" . $imgFileName . ".jpeg";
        if (file_exists($imgPath)) {
            $this->fileName = $imgName;
            $this->path = $imgPath;
            $this->dataType = strtolower(pathinfo($imgPath, PATHINFO_EXTENSION));
        }
        if (file_exists($thumbPath)) {
            $this->thumbPath = $thumbPath;
        }
    }
      /**
       * deletes img from filesystem 
       * */
    public function deleteImg()
    {
        //delete original img
        if (file_exists($this->path)) {
            unlink($this->path);
        }
        //delte thumbnail
        if (file_exists($this->thumbPath)) {
            unlink($this->thumbPath);
        }
    }
}
