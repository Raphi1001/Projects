<?php
class Navitem
{
    public $link;
    public $label;
    public function __construct($link, $label)
    {
        $this->link = $link;
        $this->label = $label;
    }
}

class Navbar
{
    /**
     * Shows a navbar depending on user permissions
     * 
     * @param array $navitem Array of Navitems
     * @param string $currUser username of the current user
     */
    public static function dynamicNav($navitems, $currUser)
    { ?>
        <nav class="navbar navbar-expand-lg navbar-light fixed-top">
            <a class="navbar-brand" href="index.php">
                <img src="public/img/logo.png" height="80" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <?php foreach ($navitems as $navitem) { ?>
                        <li class="nav-item">
                            <a class="nav-link text-nowrap" href=<?= $navitem->link ?>><?= $navitem->label ?></a>
                        </li>
                    <?php } ?>
                </ul>
                <?php if (!empty($currUser)) { ?>
                    <span class="navbar-text nav-item">Eingeloggt als: <?= $currUser ?></span>
                <?php }
                if (!isset($_GET["page"]) || $_GET["page"] != "search") { ?>
                    <form class="form-inline flex-nowrap" action="index.php" method="GET">
                        <input type="hidden" name="page" value="search">
                        <input class="form-control search" type="search" name="searchFor" placeholder="Suche" aria-label="Search" maxlength="499" pattern="^[a-zA-Z0-9-:()_,.!?' ÄäÖöÜüß]*$" required>
                        <button class="btn btn-dark fa fa-search search" type="submit" name="submit-search" value="true"></button>
                    </form>
                <?php } ?>
            </div>
        </nav> <?php
            }
        }
?>