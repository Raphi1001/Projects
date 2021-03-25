<!-- First page of new post -->
<div class="container">

    <h2 class="text-center"><?=(isset($_SESSION['editPost']) && $_SESSION['editPost'] == true) ? 'Beitrag bearbeiten' : 'Neuen Beitrag erstellen'?></h2>

    <form action="index.php?page=newPost" method="post" enctype="multipart/form-data">

        <div class="form-group">
            <label class="col-form-label font-weight-bold" for="postDescription">Beschreibung:</label>
            <textarea class="form-control" id="postDescription" name="postDescription" rows="3" maxlength="499"><?php
            if (isset($newPost) && $newPost->getProperty("description")) echo $newPost->getProperty("description")?></textarea>
            <?php if (!empty($errArray["postDescription"])) Alert::inlineAlert($errArray["postDescription"]) ?>
        </div>

        <div class="form-group">
            <label class="col-form-label font-weight-bold" for="postPrivacy">Freigabe</label>
            <select class="form-control" id="postPrivacy" name="postPrivacy">
                <option <?php if ($newPost->getProperty("privacy") != true) echo "selected" ?>>Für alle sichtbar</option>
                <option <?php if ($newPost->getProperty("privacy") == true) echo "selected" ?>>Nur für eingeloggte User sichtbar</option>
            </select>
            <?php if (!empty($errArray["postPrivacy"])) Alert::inlineAlert($errArray["postPrivacy"]) ?>
        </div>
        <!-- Go Back/Confirm Buttons -->
        <div class="form-group">
            <a href="index.php?page=newPost&subpage=abort" class="btn btn-dark" role="button">Abbrechen</a>
            <button class="btn btn-dark" type="submit">Weiter</button>
           
        </div>
    </form>
</div>