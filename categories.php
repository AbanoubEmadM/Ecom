<?php
include 'init.php';
include $tbl . 'footer.php';
$Items = GetItems('*', 'products', 'CategoryID = ' . $_GET['id'], 'ProductID');
GetPageTitle(str_replace('-', ' ', $_GET['pagename']));
echo '<div class="row">';
foreach ($Items as $Item) {
    echo '<div class="col-md-4 col-sm-6">';
    echo '<div class="product-item">';
    echo '<img src="https://placehold.co/600x400/transparent/F00" alt="" class="img-fluid">';
    echo '<h4>' . $Item['ProductName'] . '</h4>';
    echo '<p>' . $Item['ProductDesc'] . '</p>';
    echo '<p>' . $Item['ProductPrice'] . '$</p>';
    echo '</div>';
    echo '</div>';
}
echo '</div>';
echo '</div>';
