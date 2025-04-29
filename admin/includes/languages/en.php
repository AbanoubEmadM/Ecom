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
        'ITEMS' => 'Items',
        'STATISTICS' => 'Statistics',
        'MEMBERS' => 'Members',
        'LOGS' => 'Logs',
    );

    return $lang[$phrase];
}
