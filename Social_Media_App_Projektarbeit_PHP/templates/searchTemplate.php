<form class="mb-3" action="index.php" method="GET">
    <input type="hidden" name="page" value="search">
    <div class="form-row justify-content-center">
        <div class="form-group col-3">
            <select class="form-control" id="filter" name="filter-search">
                <option value="Beitrag" <?php if (isset($_GET["filter-search"]) && $_GET["filter-search"]  == "Beitrag") echo "selected"; ?>>Beitrag</option>
                <option value="Bildname" <?php if (isset($_GET["filter-search"]) && $_GET["filter-search"]  == "Bildname") echo "selected"; ?>>Bildname</option>
                <option value="Tag" <?php if (isset($_GET["filter-search"]) && $_GET["filter-search"]  == "Tag") echo "selected"; ?>>Tag</option>
                <option value="Kommentar" <?php if (isset($_GET["filter-search"]) && $_GET["filter-search"]  == "Kommentar") echo "selected"; ?>>Kommentar</option>
                <option value="User" <?php if (isset($_GET["filter-search"]) && $_GET["filter-search"]  == "User") echo "selected"; ?>>User</option>
            </select>
        </div>
        <div class="form-group col-5">
            <input type="text" class="form-control" name="searchFor" placeholder="Suche.." value="<?php if (isset($_GET["searchFor"])) {
                                                                                                        echo $_GET["searchFor"];
                                                                                                    } else {
                                                                                                        echo "";
                                                                                                    } ?>" maxlength="499" pattern="^[a-zA-Z0-9-:()_,.!?' ÄäÖöÜüß]*$" required>
        </div>
        <div class="form-group col-1">
            <button class="btn btn-dark" type="submit" name="submit-search" value="true">
                <span class="fa fa-search"></span>
            </button>
        </div>
    </div>
    <div class="form-row justify-content-center">
        <small class="form-text text-muted">Suchen Sie nach Beiträgen, Bildnamen, Tags, Kommentaren oder Usern.</small>
    </div>
</form>