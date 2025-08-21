<?php

/**
 * Loads a view file and passes data to it.
 * @param string $name The name of the view file to load.
 * @param array $data The data to pass to the view.
 */
function view($name, $data = []) {
    // NEW: Automatically fetch menu data
    static $menuItems = null;
    if (is_null($menuItems)) {
        $menuModel = new \App\Models\MenuModel();
        $menuItems = $menuModel->getAllActive();
    }
    // Merge the fetched menu data with other data sent to the page
    $data['menuItems'] = $menuItems;


    // Existing code: Convert data to variables and load the view
    extract($data);
    return require_once __DIR__ . "/Views/{$name}.php";
}

/**
 * Gets a setting value from the database.
 * It loads all settings once and stores them in a static variable for efficiency.
 * @param string $key The name of the setting to get.
 * @param mixed $default The default value to return if the setting is not found.
 * @return mixed The value of the setting or the default value.
 */
function setting($key, $default = null) {
    static $settings = null;

    // If settings have not been loaded yet, load them from the database
    if (is_null($settings)) {
        // Use the fully qualified namespace to find the class from the global scope
        $settingModel = new \App\Models\SettingModel();
        $settings = $settingModel->getAllSettings();
    }

    // Return the specific setting, or the default value if it doesn't exist
    return $settings[$key] ?? $default;
}

function slugify($text) {
    // Boşlukları ve özel karakterleri tire ile değiştir
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // URL için istenmeyen karakterleri kaldır
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    // Başındaki ve sonundaki tireleri kaldır
    $text = trim($text, '-');
    // Birden fazla tireyi tek tire yap
    $text = preg_replace('~-+~', '-', $text);
    // Tamamen küçük harf yap
    $text = strtolower($text);
    if (empty($text)) {
        return 'n-a-' . time(); // Eğer başlık boşsa, rastgele bir şey üret
    }
    return $text;
}
