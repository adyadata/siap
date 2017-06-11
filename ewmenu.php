<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(63, "mi_c_home_php", $Language->MenuPhrase("63", "MenuText"), "c_home.php", -1, "", AllowListMenu('{035CBF11-745C-4982-814A-B6768131C8FC}c_home.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(29, "mi_pegawai", $Language->MenuPhrase("29", "MenuText"), "pegawailist.php", -1, "", AllowListMenu('{035CBF11-745C-4982-814A-B6768131C8FC}pegawai'), FALSE, FALSE);
$RootMenu->AddMenuItem(65, "mci_Setup", $Language->MenuPhrase("65", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(31, "mi_pembagian1", $Language->MenuPhrase("31", "MenuText"), "pembagian1list.php", 65, "", AllowListMenu('{035CBF11-745C-4982-814A-B6768131C8FC}pembagian1'), FALSE, FALSE);
$RootMenu->AddMenuItem(32, "mi_pembagian2", $Language->MenuPhrase("32", "MenuText"), "pembagian2list.php", 65, "", AllowListMenu('{035CBF11-745C-4982-814A-B6768131C8FC}pembagian2'), FALSE, FALSE);
$RootMenu->AddMenuItem(33, "mi_pembagian3", $Language->MenuPhrase("33", "MenuText"), "pembagian3list.php", 65, "", AllowListMenu('{035CBF11-745C-4982-814A-B6768131C8FC}pembagian3'), FALSE, FALSE);
$RootMenu->AddMenuItem(62, "mi_t_user", $Language->MenuPhrase("62", "MenuText"), "t_userlist.php", 65, "", AllowListMenu('{035CBF11-745C-4982-814A-B6768131C8FC}t_user'), FALSE, FALSE);
$RootMenu->AddMenuItem(64, "mci_View", $Language->MenuPhrase("64", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(61, "mi_t_audit_trail", $Language->MenuPhrase("61", "MenuText"), "t_audit_traillist.php", 64, "", AllowListMenu('{035CBF11-745C-4982-814A-B6768131C8FC}t_audit_trail'), FALSE, FALSE);
$RootMenu->AddMenuItem(-2, "mi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
