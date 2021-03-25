<div class="container">
    <?php
    if (isset($_GET["page"]) && $_GET["page"] == "newPost") {
    ?>
        <div class="form-group">
            <a href="index.php?page=newPost&subpage=preview" class="btn btn-dark" role="button">Zurück</a>
        </div>
    <?php
    }
    ?>
    <h2 class="text-center mb-5">Ihre Bilder</h2>

    <form action="<?php
                    if (isset($_GET["page"]) && $_GET["page"] == "newPost") {
                        echo 'index.php?page=newPost&subpage=uploads';
                    } else {
                        echo 'index.php?page=uploads';
                    }
                    ?>" method="post" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group col-md-2">
                <h4>Bild hochladen:</h4>
            </div>
            <div class="form-group col-md-6">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="postImg" name="postImg">
                    <label class="custom-file-label text-truncate" for="postImg">Unterstütze Dateiformate: JPEG, JPG, PNG, GIF</label>
                    <?php
                    if (!empty($errImg)) {
                        Alert::inlineAlert($errImg);
                    }
                    ?>
                </div>
            </div>
            <div class="form-group col-md-4">
                <button class="btn btn-dark" type="submit">Hochladen</button>
            </div>
    </form>

    <script>
        // filename appears when selected
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>



    <?php
    //Print all user uploads
    if (is_dir($directory = "resources/userUploads/$currentUserId/uploads/")) {

        //check if files folders is empty after removing "." and ".." entries
        if (array_diff(scandir($directory), array('..', '.'))) {

            //open directory
            if ($handle = opendir($directory)) {
    ?>
                <div class="container border d-flex flex-wrap p-0">
                    <?php
                    //read all entries
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != "." && $entry != "..") {
                            $imgToPrint = new Img;
                            $imgToPrint->loadImage($entry, $currentUserId);
                    ?>
                            <div class="col-sm-12 col-md-4 col-lg-3 d-flex my-3 justify-content-center">
                                <div class="card ">

                                    <a data-fancybox="gallery" href="<?= $imgToPrint->getProperty("path") ?>"><img class="card-img-top upload-img" src="<?= $imgToPrint->getProperty("thumbPath") ?>"></a>

                                    <div class="card-footer text-center foreground">
                                        <?php
                                        if (isset($_GET["page"]) && $_GET["page"] == "newPost") {
                                        ?>
                                            <form method="post" action="index.php?page=newPost&subpage=uploads">
                                                <button class="btn btn-outline-dark mb-1 text-wrap" name="useImg" value="<?= $imgToPrint->getProperty("fileName") ?>" type="submit">Auswählen</button>
                                            </form>
                                        <?php

                                        } else {
                                        ?>
                                            <!-- use img as profil picture -->
                                            <form method="post" action="index.php?page=uploads">
                                                <button class="btn btn-outline-dark mb-1 text-wrap" name="useImg" value="<?= $imgToPrint->getProperty("fileName") ?>" type="submit">Als Profilbild verwenden</button>
                                            </form>
                                            <!-- delete img -->
                                            <form method="post" action="index.php?page=uploads" onsubmit="return confirm('Möchten Sie das Bild löschen?');">
                                                <button class="btn btn-danger mb-1" name="deleteImg" value="<?= $imgToPrint->getProperty("fileName") ?>" type="submit"><i class="fa fa-remove"></i></button>
                                            </form>
                                        <?php
                                        }

                                        ?>
                                    </div>

                                </div>
                            </div>
                    <?php
                        }
                    }
                    closedir($handle);
                    ?>
                </div>
    <?php
            }
            //if no images are uploaded yet
        } else {
            echo '<h5>Laden Sie hier Bilder hoch, um sie zu verwenden!</h5>';
        }
    } else {
        Alert::echoAlert("Ein unbekannter Fehler ist aufgetreten!", false);
    }
    ?>

</div>