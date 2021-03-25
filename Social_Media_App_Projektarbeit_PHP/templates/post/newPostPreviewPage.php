 <!-- post preview (second page) -->
 <div class="container px-0">
     <h2 class="text-center">Post Vorschau</h2>
     <div class="col-sm-12 col-md-6 flex mx-auto px-0 my-3 justify-content-center">
         <div class="card post-card mx-auto mt-5">
             <div class="card-body">
                 <div class="d-flex align-items-center">
                     <a data-fancybox="gallery" href="<?= $pathProfilePic ?>"><img class="rounded-circle profilePic" src="<?= $thumbProfilePic ?>" alt="Profilbild"></a>
                     <h5 class=" mx-3 my-0"> <?= $currentUser ?> </h5>
                     <?php
                        $imgToPrint = $newPost->getProperty("imgObject");
                        if ($imgToPrint) {
                        ?>

                         <div class="ml-auto">
                             <form action="index.php?page=newPost&subpage=preview" method="post">
                                 <button class="btn btn-outline-dark btn-sm" type="submit" name="removeImg" value="true">Bild entfernen</button>
                             </form>
                         </div>
                     <?php
                        }
                        ?>
                 </div>
             </div>

             <?php
                if ($imgToPrint) {
                ?>
                 <a data-fancybox="gallery" href="<?= $imgToPrint->getProperty("path") ?>"><img class="card-img-top" src="<?= $imgToPrint->getProperty("thumbPath") ?>"></a>
             <?php
                }
                ?>
             <div class="card-body">
                 <p class="card-text">
                     <?php if ($newPost->getProperty("description")) echo $newPost->getProperty("description") ?>
                 </p>

                 <!-- print all added tags -->
                 <div class="d-flex flex-wrap justify-content-start">
                     <?php
                        if ($newPost->getProperty("tags")) {
                            foreach ($newPost->getProperty("tags") as $tag) {
                                echo '<a href="index.php?page=newPost&subpage=preview&tagToDelete=' . $tag . '" class="btn btn-outline-dark btn-sm mr-2 mb-2" role="button">#' . $tag . '</a>';
                            }
                        ?>
                         <small class="form-text text-muted">Klicken Sie auf einen Tag, um ihn zu entfernen!</small>
                     <?php
                        }
                        ?>
                 </div>
             </div>
         </div>
     </div>
     <!-- Form for Tag input -->
     <form action="index.php?page=newPost&subpage=preview" method="post">
         <!-- Input -->
         <div class="form-group">
             <label class="col-form-label font-weight-bold" for="postNewTag">Tags:</label>
             <input type="text" class="form-control" id="postNewTag" name="postNewTag" rows="1" maxlength="59" pattern="^[a-zA-Z0-9-:(),.!?' ÄäÖöÜüß]*$">

             <?php
                if (!empty($tagErr)) {
                    Alert::inlineAlert($tagErr);
                }
                ?>
         </div>

         <div class="form-group">
             <button class="btn btn-dark btn-sm" type="submit">Tag hinzufügen</button>
         </div>

         <div class="form-group">
             <label class="col-form-label font-weight-bold" for="postImg">Bild anfügen:</label>
             <a href="index.php?page=newPost&subpage=uploads" class="btn btn-outline-dark" role="button">Bilder durchsuchen</a>
         </div>
     </form>

     <!-- Go Back/Confirm Buttons -->
     <div class="btn-toolbar toolbar-center" role="toolbar" aria-label="postNavigation">
         <div class="btn-group m-2" role="group" aria-label="newPost">
             <a href="index.php?page=newPost" class="btn btn-dark" role="button">Zurück</a>
         </div>

         <div class="btn-group m-2" role="group" aria-label="CreatePost">
             <a href="index.php?page=newPost&subpage=upload" class="btn btn-dark" role="button">Posten</a>
         </div>
     </div>
 </div>