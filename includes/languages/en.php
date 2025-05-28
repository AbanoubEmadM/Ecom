<?php
function lang($phrase)
{
    static $lang = array(
        'HOME' => 'Home',
        'CATEGORIES' => 'Categories',
        'DROPDOWN' => 'Dropdown',
        'EDIT_PROFIELS' => 'Edit Profiles',
        'SETTINGS' => 'Settings',
        'LOGOUT' => 'Logout',
        'PRODUCTS' => 'Products',
        'COMMENTS' => 'Comments',
        'MEMBERS' => 'Members',
        'LOGS' => 'Logs',
    );

    return $lang[$phrase];
}
