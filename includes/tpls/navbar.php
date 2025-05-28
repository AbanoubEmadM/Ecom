<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <h3><a class="navbar-brand" href="dashboard.php"><?php echo lang('HOME') ?></a></h3>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="app-nav">
        <ul class="navbar-nav nav ml-auto">
            <?php
            foreach (getCategories() as $cat) {
                echo '<li class="nav-item"><a class="nav-link" href="categories.php?id=' . $cat['CategoryID'] . '&pagename=' . str_replace(' ', '-', $cat['CategoryName']) . '">' . $cat['CategoryName'] . '</a></li>';
            }
            ?>
        </ul>
    </div>
</nav>