<?php
// utils/settings.php

require_once 'helpers.php';

/**
 * Retrieves the value of a specific setting.
 *
 * @param string $key The settings key.
 * @param PDO $db The PDO database connection.
 * @return string|null The settings value or null if not found.
 */
function get_setting($key, $db) {
    $stmt = $db->prepare("SELECT Settings_Value FROM Settings WHERE Settings_Key = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['Settings_Value'] : null;
}

/**
 * Updates or inserts a setting.
 *
 * @param string $key The settings key.
 * @param string $value The settings value.
 * @param PDO $db The PDO database connection.
 * @return void
 */
function update_setting($key, $value, $db) {
    $stmt = $db->prepare("
        INSERT INTO Settings (Settings_Key, Settings_Value) 
        VALUES (:key, :value) 
        ON DUPLICATE KEY UPDATE Settings_Value = :value, Updated_At = CURRENT_TIMESTAMP
    ");
    $stmt->execute([':key' => $key, ':value' => $value]);
}
?>
