<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "pegawaiinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$pegawai_delete = NULL; // Initialize page object first

class cpegawai_delete extends cpegawai {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{035CBF11-745C-4982-814A-B6768131C8FC}";

	// Table name
	var $TableName = 'pegawai';

	// Page object name
	var $PageObjName = 'pegawai_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (pegawai)
		if (!isset($GLOBALS["pegawai"]) || get_class($GLOBALS["pegawai"]) == "cpegawai") {
			$GLOBALS["pegawai"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pegawai"];
		}

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pegawai', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (t_user)
		if (!isset($UserTable)) {
			$UserTable = new ct_user();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("pegawailist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->pegawai_id->SetVisibility();
		$this->pegawai_id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->pegawai_pin->SetVisibility();
		$this->pegawai_nip->SetVisibility();
		$this->pegawai_nama->SetVisibility();
		$this->pegawai_telp->SetVisibility();
		$this->pegawai_status->SetVisibility();
		$this->tempat_lahir->SetVisibility();
		$this->tgl_lahir->SetVisibility();
		$this->pembagian1_id->SetVisibility();
		$this->pembagian2_id->SetVisibility();
		$this->pembagian3_id->SetVisibility();
		$this->tgl_mulai_kerja->SetVisibility();
		$this->tgl_resign->SetVisibility();
		$this->gender->SetVisibility();
		$this->tgl_masuk_pertama->SetVisibility();
		$this->photo_path->SetVisibility();
		$this->nama_bank->SetVisibility();
		$this->nama_rek->SetVisibility();
		$this->no_rek->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $pegawai;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($pegawai);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("pegawailist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in pegawai class, pegawaiinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("pegawailist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->SelectSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderByList())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->pegawai_id->setDbValue($rs->fields('pegawai_id'));
		$this->pegawai_pin->setDbValue($rs->fields('pegawai_pin'));
		$this->pegawai_nip->setDbValue($rs->fields('pegawai_nip'));
		$this->pegawai_nama->setDbValue($rs->fields('pegawai_nama'));
		$this->pegawai_pwd->setDbValue($rs->fields('pegawai_pwd'));
		$this->pegawai_rfid->setDbValue($rs->fields('pegawai_rfid'));
		$this->pegawai_privilege->setDbValue($rs->fields('pegawai_privilege'));
		$this->pegawai_telp->setDbValue($rs->fields('pegawai_telp'));
		$this->pegawai_status->setDbValue($rs->fields('pegawai_status'));
		$this->tempat_lahir->setDbValue($rs->fields('tempat_lahir'));
		$this->tgl_lahir->setDbValue($rs->fields('tgl_lahir'));
		$this->pembagian1_id->setDbValue($rs->fields('pembagian1_id'));
		if (array_key_exists('EV__pembagian1_id', $rs->fields)) {
			$this->pembagian1_id->VirtualValue = $rs->fields('EV__pembagian1_id'); // Set up virtual field value
		} else {
			$this->pembagian1_id->VirtualValue = ""; // Clear value
		}
		$this->pembagian2_id->setDbValue($rs->fields('pembagian2_id'));
		if (array_key_exists('EV__pembagian2_id', $rs->fields)) {
			$this->pembagian2_id->VirtualValue = $rs->fields('EV__pembagian2_id'); // Set up virtual field value
		} else {
			$this->pembagian2_id->VirtualValue = ""; // Clear value
		}
		$this->pembagian3_id->setDbValue($rs->fields('pembagian3_id'));
		if (array_key_exists('EV__pembagian3_id', $rs->fields)) {
			$this->pembagian3_id->VirtualValue = $rs->fields('EV__pembagian3_id'); // Set up virtual field value
		} else {
			$this->pembagian3_id->VirtualValue = ""; // Clear value
		}
		$this->tgl_mulai_kerja->setDbValue($rs->fields('tgl_mulai_kerja'));
		$this->tgl_resign->setDbValue($rs->fields('tgl_resign'));
		$this->gender->setDbValue($rs->fields('gender'));
		$this->tgl_masuk_pertama->setDbValue($rs->fields('tgl_masuk_pertama'));
		$this->photo_path->Upload->DbValue = $rs->fields('photo_path');
		$this->photo_path->CurrentValue = $this->photo_path->Upload->DbValue;
		$this->tmp_img->setDbValue($rs->fields('tmp_img'));
		$this->nama_bank->setDbValue($rs->fields('nama_bank'));
		$this->nama_rek->setDbValue($rs->fields('nama_rek'));
		$this->no_rek->setDbValue($rs->fields('no_rek'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->pegawai_id->DbValue = $row['pegawai_id'];
		$this->pegawai_pin->DbValue = $row['pegawai_pin'];
		$this->pegawai_nip->DbValue = $row['pegawai_nip'];
		$this->pegawai_nama->DbValue = $row['pegawai_nama'];
		$this->pegawai_pwd->DbValue = $row['pegawai_pwd'];
		$this->pegawai_rfid->DbValue = $row['pegawai_rfid'];
		$this->pegawai_privilege->DbValue = $row['pegawai_privilege'];
		$this->pegawai_telp->DbValue = $row['pegawai_telp'];
		$this->pegawai_status->DbValue = $row['pegawai_status'];
		$this->tempat_lahir->DbValue = $row['tempat_lahir'];
		$this->tgl_lahir->DbValue = $row['tgl_lahir'];
		$this->pembagian1_id->DbValue = $row['pembagian1_id'];
		$this->pembagian2_id->DbValue = $row['pembagian2_id'];
		$this->pembagian3_id->DbValue = $row['pembagian3_id'];
		$this->tgl_mulai_kerja->DbValue = $row['tgl_mulai_kerja'];
		$this->tgl_resign->DbValue = $row['tgl_resign'];
		$this->gender->DbValue = $row['gender'];
		$this->tgl_masuk_pertama->DbValue = $row['tgl_masuk_pertama'];
		$this->photo_path->Upload->DbValue = $row['photo_path'];
		$this->tmp_img->DbValue = $row['tmp_img'];
		$this->nama_bank->DbValue = $row['nama_bank'];
		$this->nama_rek->DbValue = $row['nama_rek'];
		$this->no_rek->DbValue = $row['no_rek'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// pegawai_id
		// pegawai_pin
		// pegawai_nip
		// pegawai_nama
		// pegawai_pwd

		$this->pegawai_pwd->CellCssStyle = "white-space: nowrap;";

		// pegawai_rfid
		$this->pegawai_rfid->CellCssStyle = "white-space: nowrap;";

		// pegawai_privilege
		$this->pegawai_privilege->CellCssStyle = "white-space: nowrap;";

		// pegawai_telp
		// pegawai_status
		// tempat_lahir
		// tgl_lahir
		// pembagian1_id
		// pembagian2_id
		// pembagian3_id
		// tgl_mulai_kerja
		// tgl_resign
		// gender
		// tgl_masuk_pertama
		// photo_path
		// tmp_img

		$this->tmp_img->CellCssStyle = "white-space: nowrap;";

		// nama_bank
		// nama_rek
		// no_rek

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// pegawai_id
		$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// pegawai_pin
		$this->pegawai_pin->ViewValue = $this->pegawai_pin->CurrentValue;
		$this->pegawai_pin->ViewCustomAttributes = "";

		// pegawai_nip
		$this->pegawai_nip->ViewValue = $this->pegawai_nip->CurrentValue;
		$this->pegawai_nip->ViewCustomAttributes = "";

		// pegawai_nama
		$this->pegawai_nama->ViewValue = $this->pegawai_nama->CurrentValue;
		$this->pegawai_nama->ViewCustomAttributes = "";

		// pegawai_telp
		$this->pegawai_telp->ViewValue = $this->pegawai_telp->CurrentValue;
		$this->pegawai_telp->ViewCustomAttributes = "";

		// pegawai_status
		if (strval($this->pegawai_status->CurrentValue) <> "") {
			$this->pegawai_status->ViewValue = $this->pegawai_status->OptionCaption($this->pegawai_status->CurrentValue);
		} else {
			$this->pegawai_status->ViewValue = NULL;
		}
		$this->pegawai_status->ViewCustomAttributes = "";

		// tempat_lahir
		$this->tempat_lahir->ViewValue = $this->tempat_lahir->CurrentValue;
		$this->tempat_lahir->ViewCustomAttributes = "";

		// tgl_lahir
		$this->tgl_lahir->ViewValue = $this->tgl_lahir->CurrentValue;
		$this->tgl_lahir->ViewValue = ew_FormatDateTime($this->tgl_lahir->ViewValue, 0);
		$this->tgl_lahir->ViewCustomAttributes = "";

		// pembagian1_id
		if ($this->pembagian1_id->VirtualValue <> "") {
			$this->pembagian1_id->ViewValue = $this->pembagian1_id->VirtualValue;
		} else {
		if (strval($this->pembagian1_id->CurrentValue) <> "") {
			$sFilterWrk = "`pembagian1_id`" . ew_SearchString("=", $this->pembagian1_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `pembagian1_id`, `pembagian1_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pembagian1`";
		$sWhereWrk = "";
		$this->pembagian1_id->LookupFilters = array("dx1" => '`pembagian1_nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->pembagian1_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->pembagian1_id->ViewValue = $this->pembagian1_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->pembagian1_id->ViewValue = $this->pembagian1_id->CurrentValue;
			}
		} else {
			$this->pembagian1_id->ViewValue = NULL;
		}
		}
		$this->pembagian1_id->ViewCustomAttributes = "";

		// pembagian2_id
		if ($this->pembagian2_id->VirtualValue <> "") {
			$this->pembagian2_id->ViewValue = $this->pembagian2_id->VirtualValue;
		} else {
		if (strval($this->pembagian2_id->CurrentValue) <> "") {
			$sFilterWrk = "`pembagian2_id`" . ew_SearchString("=", $this->pembagian2_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `pembagian2_id`, `pembagian2_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pembagian2`";
		$sWhereWrk = "";
		$this->pembagian2_id->LookupFilters = array("dx1" => '`pembagian2_nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->pembagian2_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->pembagian2_id->ViewValue = $this->pembagian2_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->pembagian2_id->ViewValue = $this->pembagian2_id->CurrentValue;
			}
		} else {
			$this->pembagian2_id->ViewValue = NULL;
		}
		}
		$this->pembagian2_id->ViewCustomAttributes = "";

		// pembagian3_id
		if ($this->pembagian3_id->VirtualValue <> "") {
			$this->pembagian3_id->ViewValue = $this->pembagian3_id->VirtualValue;
		} else {
		if (strval($this->pembagian3_id->CurrentValue) <> "") {
			$sFilterWrk = "`pembagian3_id`" . ew_SearchString("=", $this->pembagian3_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `pembagian3_id`, `pembagian3_nama` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pembagian3`";
		$sWhereWrk = "";
		$this->pembagian3_id->LookupFilters = array("dx1" => '`pembagian3_nama`');
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->pembagian3_id, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->pembagian3_id->ViewValue = $this->pembagian3_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->pembagian3_id->ViewValue = $this->pembagian3_id->CurrentValue;
			}
		} else {
			$this->pembagian3_id->ViewValue = NULL;
		}
		}
		$this->pembagian3_id->ViewCustomAttributes = "";

		// tgl_mulai_kerja
		$this->tgl_mulai_kerja->ViewValue = $this->tgl_mulai_kerja->CurrentValue;
		$this->tgl_mulai_kerja->ViewValue = ew_FormatDateTime($this->tgl_mulai_kerja->ViewValue, 0);
		$this->tgl_mulai_kerja->ViewCustomAttributes = "";

		// tgl_resign
		$this->tgl_resign->ViewValue = $this->tgl_resign->CurrentValue;
		$this->tgl_resign->ViewValue = ew_FormatDateTime($this->tgl_resign->ViewValue, 0);
		$this->tgl_resign->ViewCustomAttributes = "";

		// gender
		if (strval($this->gender->CurrentValue) <> "") {
			$this->gender->ViewValue = $this->gender->OptionCaption($this->gender->CurrentValue);
		} else {
			$this->gender->ViewValue = NULL;
		}
		$this->gender->ViewCustomAttributes = "";

		// tgl_masuk_pertama
		$this->tgl_masuk_pertama->ViewValue = $this->tgl_masuk_pertama->CurrentValue;
		$this->tgl_masuk_pertama->ViewValue = ew_FormatDateTime($this->tgl_masuk_pertama->ViewValue, 0);
		$this->tgl_masuk_pertama->ViewCustomAttributes = "";

		// photo_path
		if (!ew_Empty($this->photo_path->Upload->DbValue)) {
			$this->photo_path->ViewValue = $this->photo_path->Upload->DbValue;
		} else {
			$this->photo_path->ViewValue = "";
		}
		$this->photo_path->ViewCustomAttributes = "";

		// nama_bank
		$this->nama_bank->ViewValue = $this->nama_bank->CurrentValue;
		$this->nama_bank->ViewCustomAttributes = "";

		// nama_rek
		$this->nama_rek->ViewValue = $this->nama_rek->CurrentValue;
		$this->nama_rek->ViewCustomAttributes = "";

		// no_rek
		$this->no_rek->ViewValue = $this->no_rek->CurrentValue;
		$this->no_rek->ViewCustomAttributes = "";

			// pegawai_id
			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";
			$this->pegawai_id->TooltipValue = "";

			// pegawai_pin
			$this->pegawai_pin->LinkCustomAttributes = "";
			$this->pegawai_pin->HrefValue = "";
			$this->pegawai_pin->TooltipValue = "";

			// pegawai_nip
			$this->pegawai_nip->LinkCustomAttributes = "";
			$this->pegawai_nip->HrefValue = "";
			$this->pegawai_nip->TooltipValue = "";

			// pegawai_nama
			$this->pegawai_nama->LinkCustomAttributes = "";
			$this->pegawai_nama->HrefValue = "";
			$this->pegawai_nama->TooltipValue = "";

			// pegawai_telp
			$this->pegawai_telp->LinkCustomAttributes = "";
			$this->pegawai_telp->HrefValue = "";
			$this->pegawai_telp->TooltipValue = "";

			// pegawai_status
			$this->pegawai_status->LinkCustomAttributes = "";
			$this->pegawai_status->HrefValue = "";
			$this->pegawai_status->TooltipValue = "";

			// tempat_lahir
			$this->tempat_lahir->LinkCustomAttributes = "";
			$this->tempat_lahir->HrefValue = "";
			$this->tempat_lahir->TooltipValue = "";

			// tgl_lahir
			$this->tgl_lahir->LinkCustomAttributes = "";
			$this->tgl_lahir->HrefValue = "";
			$this->tgl_lahir->TooltipValue = "";

			// pembagian1_id
			$this->pembagian1_id->LinkCustomAttributes = "";
			$this->pembagian1_id->HrefValue = "";
			$this->pembagian1_id->TooltipValue = "";

			// pembagian2_id
			$this->pembagian2_id->LinkCustomAttributes = "";
			$this->pembagian2_id->HrefValue = "";
			$this->pembagian2_id->TooltipValue = "";

			// pembagian3_id
			$this->pembagian3_id->LinkCustomAttributes = "";
			$this->pembagian3_id->HrefValue = "";
			$this->pembagian3_id->TooltipValue = "";

			// tgl_mulai_kerja
			$this->tgl_mulai_kerja->LinkCustomAttributes = "";
			$this->tgl_mulai_kerja->HrefValue = "";
			$this->tgl_mulai_kerja->TooltipValue = "";

			// tgl_resign
			$this->tgl_resign->LinkCustomAttributes = "";
			$this->tgl_resign->HrefValue = "";
			$this->tgl_resign->TooltipValue = "";

			// gender
			$this->gender->LinkCustomAttributes = "";
			$this->gender->HrefValue = "";
			$this->gender->TooltipValue = "";

			// tgl_masuk_pertama
			$this->tgl_masuk_pertama->LinkCustomAttributes = "";
			$this->tgl_masuk_pertama->HrefValue = "";
			$this->tgl_masuk_pertama->TooltipValue = "";

			// photo_path
			$this->photo_path->LinkCustomAttributes = "";
			$this->photo_path->HrefValue = "";
			$this->photo_path->HrefValue2 = $this->photo_path->UploadPath . $this->photo_path->Upload->DbValue;
			$this->photo_path->TooltipValue = "";

			// nama_bank
			$this->nama_bank->LinkCustomAttributes = "";
			$this->nama_bank->HrefValue = "";
			$this->nama_bank->TooltipValue = "";

			// nama_rek
			$this->nama_rek->LinkCustomAttributes = "";
			$this->nama_rek->HrefValue = "";
			$this->nama_rek->TooltipValue = "";

			// no_rek
			$this->no_rek->LinkCustomAttributes = "";
			$this->no_rek->HrefValue = "";
			$this->no_rek->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();
		if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteBegin")); // Batch delete begin

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['pegawai_id'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteSuccess")); // Batch delete success
		} else {
			$conn->RollbackTrans(); // Rollback changes
			if ($this->AuditTrailOnDelete) $this->WriteAuditTrailDummy($Language->Phrase("BatchDeleteRollback")); // Batch delete rollback
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("pegawailist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($pegawai_delete)) $pegawai_delete = new cpegawai_delete();

// Page init
$pegawai_delete->Page_Init();

// Page main
$pegawai_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pegawai_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fpegawaidelete = new ew_Form("fpegawaidelete", "delete");

// Form_CustomValidate event
fpegawaidelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpegawaidelete.ValidateRequired = true;
<?php } else { ?>
fpegawaidelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpegawaidelete.Lists["x_pegawai_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpegawaidelete.Lists["x_pegawai_status"].Options = <?php echo json_encode($pegawai->pegawai_status->Options()) ?>;
fpegawaidelete.Lists["x_pembagian1_id"] = {"LinkField":"x_pembagian1_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pembagian1_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pembagian1"};
fpegawaidelete.Lists["x_pembagian2_id"] = {"LinkField":"x_pembagian2_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pembagian2_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pembagian2"};
fpegawaidelete.Lists["x_pembagian3_id"] = {"LinkField":"x_pembagian3_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pembagian3_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pembagian3"};
fpegawaidelete.Lists["x_gender"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpegawaidelete.Lists["x_gender"].Options = <?php echo json_encode($pegawai->gender->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $pegawai_delete->ShowPageHeader(); ?>
<?php
$pegawai_delete->ShowMessage();
?>
<form name="fpegawaidelete" id="fpegawaidelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pegawai_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pegawai_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pegawai">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($pegawai_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $pegawai->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($pegawai->pegawai_id->Visible) { // pegawai_id ?>
		<th><span id="elh_pegawai_pegawai_id" class="pegawai_pegawai_id"><?php echo $pegawai->pegawai_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai->pegawai_pin->Visible) { // pegawai_pin ?>
		<th><span id="elh_pegawai_pegawai_pin" class="pegawai_pegawai_pin"><?php echo $pegawai->pegawai_pin->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai->pegawai_nip->Visible) { // pegawai_nip ?>
		<th><span id="elh_pegawai_pegawai_nip" class="pegawai_pegawai_nip"><?php echo $pegawai->pegawai_nip->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai->pegawai_nama->Visible) { // pegawai_nama ?>
		<th><span id="elh_pegawai_pegawai_nama" class="pegawai_pegawai_nama"><?php echo $pegawai->pegawai_nama->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai->pegawai_telp->Visible) { // pegawai_telp ?>
		<th><span id="elh_pegawai_pegawai_telp" class="pegawai_pegawai_telp"><?php echo $pegawai->pegawai_telp->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai->pegawai_status->Visible) { // pegawai_status ?>
		<th><span id="elh_pegawai_pegawai_status" class="pegawai_pegawai_status"><?php echo $pegawai->pegawai_status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai->tempat_lahir->Visible) { // tempat_lahir ?>
		<th><span id="elh_pegawai_tempat_lahir" class="pegawai_tempat_lahir"><?php echo $pegawai->tempat_lahir->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai->tgl_lahir->Visible) { // tgl_lahir ?>
		<th><span id="elh_pegawai_tgl_lahir" class="pegawai_tgl_lahir"><?php echo $pegawai->tgl_lahir->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai->pembagian1_id->Visible) { // pembagian1_id ?>
		<th><span id="elh_pegawai_pembagian1_id" class="pegawai_pembagian1_id"><?php echo $pegawai->pembagian1_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai->pembagian2_id->Visible) { // pembagian2_id ?>
		<th><span id="elh_pegawai_pembagian2_id" class="pegawai_pembagian2_id"><?php echo $pegawai->pembagian2_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai->pembagian3_id->Visible) { // pembagian3_id ?>
		<th><span id="elh_pegawai_pembagian3_id" class="pegawai_pembagian3_id"><?php echo $pegawai->pembagian3_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai->tgl_mulai_kerja->Visible) { // tgl_mulai_kerja ?>
		<th><span id="elh_pegawai_tgl_mulai_kerja" class="pegawai_tgl_mulai_kerja"><?php echo $pegawai->tgl_mulai_kerja->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai->tgl_resign->Visible) { // tgl_resign ?>
		<th><span id="elh_pegawai_tgl_resign" class="pegawai_tgl_resign"><?php echo $pegawai->tgl_resign->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai->gender->Visible) { // gender ?>
		<th><span id="elh_pegawai_gender" class="pegawai_gender"><?php echo $pegawai->gender->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai->tgl_masuk_pertama->Visible) { // tgl_masuk_pertama ?>
		<th><span id="elh_pegawai_tgl_masuk_pertama" class="pegawai_tgl_masuk_pertama"><?php echo $pegawai->tgl_masuk_pertama->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai->photo_path->Visible) { // photo_path ?>
		<th><span id="elh_pegawai_photo_path" class="pegawai_photo_path"><?php echo $pegawai->photo_path->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai->nama_bank->Visible) { // nama_bank ?>
		<th><span id="elh_pegawai_nama_bank" class="pegawai_nama_bank"><?php echo $pegawai->nama_bank->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai->nama_rek->Visible) { // nama_rek ?>
		<th><span id="elh_pegawai_nama_rek" class="pegawai_nama_rek"><?php echo $pegawai->nama_rek->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai->no_rek->Visible) { // no_rek ?>
		<th><span id="elh_pegawai_no_rek" class="pegawai_no_rek"><?php echo $pegawai->no_rek->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$pegawai_delete->RecCnt = 0;
$i = 0;
while (!$pegawai_delete->Recordset->EOF) {
	$pegawai_delete->RecCnt++;
	$pegawai_delete->RowCnt++;

	// Set row properties
	$pegawai->ResetAttrs();
	$pegawai->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$pegawai_delete->LoadRowValues($pegawai_delete->Recordset);

	// Render row
	$pegawai_delete->RenderRow();
?>
	<tr<?php echo $pegawai->RowAttributes() ?>>
<?php if ($pegawai->pegawai_id->Visible) { // pegawai_id ?>
		<td<?php echo $pegawai->pegawai_id->CellAttributes() ?>>
<span id="el<?php echo $pegawai_delete->RowCnt ?>_pegawai_pegawai_id" class="pegawai_pegawai_id">
<span<?php echo $pegawai->pegawai_id->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai->pegawai_pin->Visible) { // pegawai_pin ?>
		<td<?php echo $pegawai->pegawai_pin->CellAttributes() ?>>
<span id="el<?php echo $pegawai_delete->RowCnt ?>_pegawai_pegawai_pin" class="pegawai_pegawai_pin">
<span<?php echo $pegawai->pegawai_pin->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_pin->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai->pegawai_nip->Visible) { // pegawai_nip ?>
		<td<?php echo $pegawai->pegawai_nip->CellAttributes() ?>>
<span id="el<?php echo $pegawai_delete->RowCnt ?>_pegawai_pegawai_nip" class="pegawai_pegawai_nip">
<span<?php echo $pegawai->pegawai_nip->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_nip->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai->pegawai_nama->Visible) { // pegawai_nama ?>
		<td<?php echo $pegawai->pegawai_nama->CellAttributes() ?>>
<span id="el<?php echo $pegawai_delete->RowCnt ?>_pegawai_pegawai_nama" class="pegawai_pegawai_nama">
<span<?php echo $pegawai->pegawai_nama->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_nama->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai->pegawai_telp->Visible) { // pegawai_telp ?>
		<td<?php echo $pegawai->pegawai_telp->CellAttributes() ?>>
<span id="el<?php echo $pegawai_delete->RowCnt ?>_pegawai_pegawai_telp" class="pegawai_pegawai_telp">
<span<?php echo $pegawai->pegawai_telp->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_telp->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai->pegawai_status->Visible) { // pegawai_status ?>
		<td<?php echo $pegawai->pegawai_status->CellAttributes() ?>>
<span id="el<?php echo $pegawai_delete->RowCnt ?>_pegawai_pegawai_status" class="pegawai_pegawai_status">
<span<?php echo $pegawai->pegawai_status->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai->tempat_lahir->Visible) { // tempat_lahir ?>
		<td<?php echo $pegawai->tempat_lahir->CellAttributes() ?>>
<span id="el<?php echo $pegawai_delete->RowCnt ?>_pegawai_tempat_lahir" class="pegawai_tempat_lahir">
<span<?php echo $pegawai->tempat_lahir->ViewAttributes() ?>>
<?php echo $pegawai->tempat_lahir->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai->tgl_lahir->Visible) { // tgl_lahir ?>
		<td<?php echo $pegawai->tgl_lahir->CellAttributes() ?>>
<span id="el<?php echo $pegawai_delete->RowCnt ?>_pegawai_tgl_lahir" class="pegawai_tgl_lahir">
<span<?php echo $pegawai->tgl_lahir->ViewAttributes() ?>>
<?php echo $pegawai->tgl_lahir->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai->pembagian1_id->Visible) { // pembagian1_id ?>
		<td<?php echo $pegawai->pembagian1_id->CellAttributes() ?>>
<span id="el<?php echo $pegawai_delete->RowCnt ?>_pegawai_pembagian1_id" class="pegawai_pembagian1_id">
<span<?php echo $pegawai->pembagian1_id->ViewAttributes() ?>>
<?php echo $pegawai->pembagian1_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai->pembagian2_id->Visible) { // pembagian2_id ?>
		<td<?php echo $pegawai->pembagian2_id->CellAttributes() ?>>
<span id="el<?php echo $pegawai_delete->RowCnt ?>_pegawai_pembagian2_id" class="pegawai_pembagian2_id">
<span<?php echo $pegawai->pembagian2_id->ViewAttributes() ?>>
<?php echo $pegawai->pembagian2_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai->pembagian3_id->Visible) { // pembagian3_id ?>
		<td<?php echo $pegawai->pembagian3_id->CellAttributes() ?>>
<span id="el<?php echo $pegawai_delete->RowCnt ?>_pegawai_pembagian3_id" class="pegawai_pembagian3_id">
<span<?php echo $pegawai->pembagian3_id->ViewAttributes() ?>>
<?php echo $pegawai->pembagian3_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai->tgl_mulai_kerja->Visible) { // tgl_mulai_kerja ?>
		<td<?php echo $pegawai->tgl_mulai_kerja->CellAttributes() ?>>
<span id="el<?php echo $pegawai_delete->RowCnt ?>_pegawai_tgl_mulai_kerja" class="pegawai_tgl_mulai_kerja">
<span<?php echo $pegawai->tgl_mulai_kerja->ViewAttributes() ?>>
<?php echo $pegawai->tgl_mulai_kerja->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai->tgl_resign->Visible) { // tgl_resign ?>
		<td<?php echo $pegawai->tgl_resign->CellAttributes() ?>>
<span id="el<?php echo $pegawai_delete->RowCnt ?>_pegawai_tgl_resign" class="pegawai_tgl_resign">
<span<?php echo $pegawai->tgl_resign->ViewAttributes() ?>>
<?php echo $pegawai->tgl_resign->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai->gender->Visible) { // gender ?>
		<td<?php echo $pegawai->gender->CellAttributes() ?>>
<span id="el<?php echo $pegawai_delete->RowCnt ?>_pegawai_gender" class="pegawai_gender">
<span<?php echo $pegawai->gender->ViewAttributes() ?>>
<?php echo $pegawai->gender->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai->tgl_masuk_pertama->Visible) { // tgl_masuk_pertama ?>
		<td<?php echo $pegawai->tgl_masuk_pertama->CellAttributes() ?>>
<span id="el<?php echo $pegawai_delete->RowCnt ?>_pegawai_tgl_masuk_pertama" class="pegawai_tgl_masuk_pertama">
<span<?php echo $pegawai->tgl_masuk_pertama->ViewAttributes() ?>>
<?php echo $pegawai->tgl_masuk_pertama->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai->photo_path->Visible) { // photo_path ?>
		<td<?php echo $pegawai->photo_path->CellAttributes() ?>>
<span id="el<?php echo $pegawai_delete->RowCnt ?>_pegawai_photo_path" class="pegawai_photo_path">
<span<?php echo $pegawai->photo_path->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($pegawai->photo_path, $pegawai->photo_path->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($pegawai->nama_bank->Visible) { // nama_bank ?>
		<td<?php echo $pegawai->nama_bank->CellAttributes() ?>>
<span id="el<?php echo $pegawai_delete->RowCnt ?>_pegawai_nama_bank" class="pegawai_nama_bank">
<span<?php echo $pegawai->nama_bank->ViewAttributes() ?>>
<?php echo $pegawai->nama_bank->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai->nama_rek->Visible) { // nama_rek ?>
		<td<?php echo $pegawai->nama_rek->CellAttributes() ?>>
<span id="el<?php echo $pegawai_delete->RowCnt ?>_pegawai_nama_rek" class="pegawai_nama_rek">
<span<?php echo $pegawai->nama_rek->ViewAttributes() ?>>
<?php echo $pegawai->nama_rek->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai->no_rek->Visible) { // no_rek ?>
		<td<?php echo $pegawai->no_rek->CellAttributes() ?>>
<span id="el<?php echo $pegawai_delete->RowCnt ?>_pegawai_no_rek" class="pegawai_no_rek">
<span<?php echo $pegawai->no_rek->ViewAttributes() ?>>
<?php echo $pegawai->no_rek->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$pegawai_delete->Recordset->MoveNext();
}
$pegawai_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $pegawai_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fpegawaidelete.Init();
</script>
<?php
$pegawai_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pegawai_delete->Page_Terminate();
?>
