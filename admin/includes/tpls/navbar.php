<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<h3><a class="navbar-brand" href="dashboard.php"><?php echo lang('HOME') ?></a></h3>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="app-nav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Login' ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="../index.php">Visit Shop</a>
                    <a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>"><?php echo lang('EDIT_PROFIELS') ?></a>
                    <a class="dropdown-item" href="#"><?php echo lang('SETTINGS') ?></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php"><?php echo lang('LOGOUT') ?></a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="categories.php"><?php echo lang('CATEGORIES') ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="products.php"><?php echo lang('PRODUCTS') ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="comments.php"><?php echo lang('COMMENTS') ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="members.php?do=Manage"><?php echo lang('MEMBERS') ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><?php echo lang('LOGS') ?></a>
            </li>
        </ul>
    </div>
</nav>