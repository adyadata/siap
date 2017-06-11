<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(63, "mmi_c_home_php", $Language->MenuPhrase("63", "MenuText"), "c_home.php", -1, "", AllowListMenu('{035CBF11-745C-4982-814A-B6768131C8FC}c_home.php'), FALSE, TRUE);
$RootMenu->AddMenuItem(29, "mmi_pegawai", $Language->MenuPhrase("29", "MenuText"), "pegawailist.php", -1, "", AllowListMenu('{035CBF11-745C-4982-814A-B6768131C8FC}pegawai'), FALSE, FALSE);
$RootMenu->AddMenuItem(65, "mmci_Setup", $Language->MenuPhrase("65", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(128, "mmci_Umum", $Language->MenuPhrase("128", "MenuText"), "", 65, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(66, "mmi_v_shift", $Language->MenuPhrase("66", "MenuText"), "v_shiftlist.php", 128, "", AllowListMenu('{035CBF11-745C-4982-814A-B6768131C8FC}v_shift'), FALSE, FALSE);
$RootMenu->AddMenuItem(188, "mmci_Pembagian_Pegawai", $Language->MenuPhrase("188", "MenuText"), "", 65, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(31, "mmi_pembagian1", $Language->MenuPhrase("31", "MenuText"), "pembagian1list.php", 188, "", AllowListMenu('{035CBF11-745C-4982-814A-B6768131C8FC}pembagian1'), FALSE, FALSE);
$RootMenu->AddMenuItem(32, "mmi_pembagian2", $Language->MenuPhrase("32", "MenuText"), "pembagian2list.php", 188, "", AllowListMenu('{035CBF11-745C-4982-814A-B6768131C8FC}pembagian2'), FALSE, FALSE);
$RootMenu->AddMenuItem(33, "mmi_pembagian3", $Language->MenuPhrase("33", "MenuText"), "pembagian3list.php", 188, "", AllowListMenu('{035CBF11-745C-4982-814A-B6768131C8FC}pembagian3'), FALSE, FALSE);
$RootMenu->AddMenuItem(62, "mmi_t_user", $Language->MenuPhrase("62", "MenuText"), "t_userlist.php", 65, "", AllowListMenu('{035CBF11-745C-4982-814A-B6768131C8FC}t_user'), FALSE, FALSE);
$RootMenu->AddMenuItem(64, "mmci_View", $Language->MenuPhrase("64", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(61, "mmi_t_audit_trail", $Language->MenuPhrase("61", "MenuText"), "t_audit_traillist.php", 64, "", AllowListMenu('{035CBF11-745C-4982-814A-B6768131C8FC}t_audit_trail'), FALSE, FALSE);
$RootMenu->AddMenuItem(-2, "mmi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
