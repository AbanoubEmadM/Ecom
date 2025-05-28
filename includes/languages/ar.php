<?php
function lang($phrase)
{
    static $lang = array(
        'HOME' => 'الرئيسية',
        'CATEGORIES' => 'التصنيفات',
        'DROPDOWN' => 'Dropdown',
        'EDIT_PROFIELS' => 'تعديل الملف الشخصي',
        'SETTINGS' => 'الاعدادات',
        'LOGOUT' => 'تسجيل خروج',
        'PRODUCTS' => 'المنتجات',
        'COMMENTS' => 'التعليقات',
        'MEMBERS' => 'الاعضاء',
        'LOGS' => 'Logs',
    );

    return $lang[$phrase];
}
