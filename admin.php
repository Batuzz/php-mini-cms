<?php
require_once('controller/AdminPanelController.php');
require_once('controller/AdminLoginController.php');

/**
 * It's responsible for checking if admin's login data was inserted.
 * If so, redirects to main Admin Panel controller, else shows login site.
 */

try {
    if (isset($_SESSION['accountname']) && isset($_SESSION['password'])) {
        $adminPanelController = new AdminPanelController();
    } else {
        $adminPanelLogin = new AdminLoginController();
    }
} catch(WrongSessionException $ex) {
    echo ERROR.' #1. '.ERROR_TEXT;    
} catch(WrongDataException $ex) {
    echo ERROR_WRONG_PASSWORD;
} catch(AccessException $ex) {
    echo ERROR_NO_ACCESS;
}