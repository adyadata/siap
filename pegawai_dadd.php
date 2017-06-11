<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "pegawai_dinfo.php" ?>
<?php include_once "pegawaiinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$pegawai_d_add = NULL; // Initialize page object first

class cpegawai_d_add extends cpegawai_d {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{035CBF11-745C-4982-814A-B6768131C8FC}";

	// Table name
	var $TableName = 'pegawai_d';

	// Page object name
	var $PageObjName = 'pegawai_d_add';

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

		// Table object (pegawai_d)
		if (!isset($GLOBALS["pegawai_d"]) || get_class($GLOBALS["pegawai_d"]) == "cpegawai_d") {
			$GLOBALS["pegawai_d"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pegawai_d"];
		}

		// Table object (pegawai)
		if (!isset($GLOBALS['pegawai'])) $GLOBALS['pegawai'] = new cpegawai();

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pegawai_d', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("pegawai_dlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->pend_id->SetVisibility();
		$this->gol_darah->SetVisibility();
		$this->stat_nikah->SetVisibility();
		$this->agama->SetVisibility();
		$this->jml_anak->SetVisibility();
		$this->alamat->SetVisibility();
		$this->nama_hubungan->SetVisibility();
		$this->telp_extra->SetVisibility();
		$this->hubungan->SetVisibility();

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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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
		global $EW_EXPORT, $pegawai_d;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($pegawai_d);
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

			// Handle modal response
			if ($this->IsModal) {
				$row = array();
				$row["url"] = $url;
				echo ew_ArrayToJson(array($row));
			} else {
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["pegawai_id"] != "") {
				$this->pegawai_id->setQueryStringValue($_GET["pegawai_id"]);
				$this->setKey("pegawai_id", $this->pegawai_id->CurrentValue); // Set up key
			} else {
				$this->setKey("pegawai_id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		} else {
			if ($this->CurrentAction == "I") // Load default values for blank record
				$this->LoadDefaultValues();
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("pegawai_dlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "pegawai_dlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "pegawai_dview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->pend_id->CurrentValue = 30;
		$this->gol_darah->CurrentValue = 1;
		$this->stat_nikah->CurrentValue = 1;
		$this->agama->CurrentValue = 1;
		$this->jml_anak->CurrentValue = 0;
		$this->alamat->CurrentValue = NULL;
		$this->alamat->OldValue = $this->alamat->CurrentValue;
		$this->nama_hubungan->CurrentValue = NULL;
		$this->nama_hubungan->OldValue = $this->nama_hubungan->CurrentValue;
		$this->telp_extra->CurrentValue = NULL;
		$this->telp_extra->OldValue = $this->telp_extra->CurrentValue;
		$this->hubungan->CurrentValue = 0;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->pend_id->FldIsDetailKey) {
			$this->pend_id->setFormValue($objForm->GetValue("x_pend_id"));
		}
		if (!$this->gol_darah->FldIsDetailKey) {
			$this->gol_darah->setFormValue($objForm->GetValue("x_gol_darah"));
		}
		if (!$this->stat_nikah->FldIsDetailKey) {
			$this->stat_nikah->setFormValue($objForm->GetValue("x_stat_nikah"));
		}
		if (!$this->agama->FldIsDetailKey) {
			$this->agama->setFormValue($objForm->GetValue("x_agama"));
		}
		if (!$this->jml_anak->FldIsDetailKey) {
			$this->jml_anak->setFormValue($objForm->GetValue("x_jml_anak"));
		}
		if (!$this->alamat->FldIsDetailKey) {
			$this->alamat->setFormValue($objForm->GetValue("x_alamat"));
		}
		if (!$this->nama_hubungan->FldIsDetailKey) {
			$this->nama_hubungan->setFormValue($objForm->GetValue("x_nama_hubungan"));
		}
		if (!$this->telp_extra->FldIsDetailKey) {
			$this->telp_extra->setFormValue($objForm->GetValue("x_telp_extra"));
		}
		if (!$this->hubungan->FldIsDetailKey) {
			$this->hubungan->setFormValue($objForm->GetValue("x_hubungan"));
		}
		if (!$this->pegawai_id->FldIsDetailKey)
			$this->pegawai_id->setFormValue($objForm->GetValue("x_pegawai_id"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->pegawai_id->CurrentValue = $this->pegawai_id->FormValue;
		$this->pend_id->CurrentValue = $this->pend_id->FormValue;
		$this->gol_darah->CurrentValue = $this->gol_darah->FormValue;
		$this->stat_nikah->CurrentValue = $this->stat_nikah->FormValue;
		$this->agama->CurrentValue = $this->agama->FormValue;
		$this->jml_anak->CurrentValue = $this->jml_anak->FormValue;
		$this->alamat->CurrentValue = $this->alamat->FormValue;
		$this->nama_hubungan->CurrentValue = $this->nama_hubungan->FormValue;
		$this->telp_extra->CurrentValue = $this->telp_extra->FormValue;
		$this->hubungan->CurrentValue = $this->hubungan->FormValue;
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
		$this->pend_id->setDbValue($rs->fields('pend_id'));
		$this->gol_darah->setDbValue($rs->fields('gol_darah'));
		$this->stat_nikah->setDbValue($rs->fields('stat_nikah'));
		$this->agama->setDbValue($rs->fields('agama'));
		$this->jml_anak->setDbValue($rs->fields('jml_anak'));
		$this->alamat->setDbValue($rs->fields('alamat'));
		$this->nama_hubungan->setDbValue($rs->fields('nama_hubungan'));
		$this->telp_extra->setDbValue($rs->fields('telp_extra'));
		$this->hubungan->setDbValue($rs->fields('hubungan'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->pegawai_id->DbValue = $row['pegawai_id'];
		$this->pend_id->DbValue = $row['pend_id'];
		$this->gol_darah->DbValue = $row['gol_darah'];
		$this->stat_nikah->DbValue = $row['stat_nikah'];
		$this->agama->DbValue = $row['agama'];
		$this->jml_anak->DbValue = $row['jml_anak'];
		$this->alamat->DbValue = $row['alamat'];
		$this->nama_hubungan->DbValue = $row['nama_hubungan'];
		$this->telp_extra->DbValue = $row['telp_extra'];
		$this->hubungan->DbValue = $row['hubungan'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("pegawai_id")) <> "")
			$this->pegawai_id->CurrentValue = $this->getKey("pegawai_id"); // pegawai_id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// pegawai_id
		// pend_id
		// gol_darah
		// stat_nikah
		// agama
		// jml_anak
		// alamat
		// nama_hubungan
		// telp_extra
		// hubungan

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// pegawai_id
		$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// pend_id
		if (strval($this->pend_id->CurrentValue) <> "") {
			$this->pend_id->ViewValue = $this->pend_id->OptionCaption($this->pend_id->CurrentValue);
		} else {
			$this->pend_id->ViewValue = NULL;
		}
		$this->pend_id->ViewCustomAttributes = "";

		// gol_darah
		if (strval($this->gol_darah->CurrentValue) <> "") {
			$this->gol_darah->ViewValue = $this->gol_darah->OptionCaption($this->gol_darah->CurrentValue);
		} else {
			$this->gol_darah->ViewValue = NULL;
		}
		$this->gol_darah->ViewCustomAttributes = "";

		// stat_nikah
		if (strval($this->stat_nikah->CurrentValue) <> "") {
			$this->stat_nikah->ViewValue = $this->stat_nikah->OptionCaption($this->stat_nikah->CurrentValue);
		} else {
			$this->stat_nikah->ViewValue = NULL;
		}
		$this->stat_nikah->ViewCustomAttributes = "";

		// agama
		if (strval($this->agama->CurrentValue) <> "") {
			$this->agama->ViewValue = $this->agama->OptionCaption($this->agama->CurrentValue);
		} else {
			$this->agama->ViewValue = NULL;
		}
		$this->agama->ViewCustomAttributes = "";

		// jml_anak
		$this->jml_anak->ViewValue = $this->jml_anak->CurrentValue;
		$this->jml_anak->ViewCustomAttributes = "";

		// alamat
		$this->alamat->ViewValue = $this->alamat->CurrentValue;
		$this->alamat->ViewCustomAttributes = "";

		// nama_hubungan
		$this->nama_hubungan->ViewValue = $this->nama_hubungan->CurrentValue;
		$this->nama_hubungan->ViewCustomAttributes = "";

		// telp_extra
		$this->telp_extra->ViewValue = $this->telp_extra->CurrentValue;
		$this->telp_extra->ViewCustomAttributes = "";

		// hubungan
		if (strval($this->hubungan->CurrentValue) <> "") {
			$this->hubungan->ViewValue = $this->hubungan->OptionCaption($this->hubungan->CurrentValue);
		} else {
			$this->hubungan->ViewValue = NULL;
		}
		$this->hubungan->ViewCustomAttributes = "";

			// pend_id
			$this->pend_id->LinkCustomAttributes = "";
			$this->pend_id->HrefValue = "";
			$this->pend_id->TooltipValue = "";

			// gol_darah
			$this->gol_darah->LinkCustomAttributes = "";
			$this->gol_darah->HrefValue = "";
			$this->gol_darah->TooltipValue = "";

			// stat_nikah
			$this->stat_nikah->LinkCustomAttributes = "";
			$this->stat_nikah->HrefValue = "";
			$this->stat_nikah->TooltipValue = "";

			// agama
			$this->agama->LinkCustomAttributes = "";
			$this->agama->HrefValue = "";
			$this->agama->TooltipValue = "";

			// jml_anak
			$this->jml_anak->LinkCustomAttributes = "";
			$this->jml_anak->HrefValue = "";
			$this->jml_anak->TooltipValue = "";

			// alamat
			$this->alamat->LinkCustomAttributes = "";
			$this->alamat->HrefValue = "";
			$this->alamat->TooltipValue = "";

			// nama_hubungan
			$this->nama_hubungan->LinkCustomAttributes = "";
			$this->nama_hubungan->HrefValue = "";
			$this->nama_hubungan->TooltipValue = "";

			// telp_extra
			$this->telp_extra->LinkCustomAttributes = "";
			$this->telp_extra->HrefValue = "";
			$this->telp_extra->TooltipValue = "";

			// hubungan
			$this->hubungan->LinkCustomAttributes = "";
			$this->hubungan->HrefValue = "";
			$this->hubungan->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// pend_id
			$this->pend_id->EditAttrs["class"] = "form-control";
			$this->pend_id->EditCustomAttributes = "";
			$this->pend_id->EditValue = $this->pend_id->Options(TRUE);

			// gol_darah
			$this->gol_darah->EditAttrs["class"] = "form-control";
			$this->gol_darah->EditCustomAttributes = "";
			$this->gol_darah->EditValue = $this->gol_darah->Options(TRUE);

			// stat_nikah
			$this->stat_nikah->EditAttrs["class"] = "form-control";
			$this->stat_nikah->EditCustomAttributes = "";
			$this->stat_nikah->EditValue = $this->stat_nikah->Options(TRUE);

			// agama
			$this->agama->EditAttrs["class"] = "form-control";
			$this->agama->EditCustomAttributes = "";
			$this->agama->EditValue = $this->agama->Options(TRUE);

			// jml_anak
			$this->jml_anak->EditAttrs["class"] = "form-control";
			$this->jml_anak->EditCustomAttributes = "";
			$this->jml_anak->EditValue = ew_HtmlEncode($this->jml_anak->CurrentValue);
			$this->jml_anak->PlaceHolder = ew_RemoveHtml($this->jml_anak->FldCaption());

			// alamat
			$this->alamat->EditAttrs["class"] = "form-control";
			$this->alamat->EditCustomAttributes = "";
			$this->alamat->EditValue = ew_HtmlEncode($this->alamat->CurrentValue);
			$this->alamat->PlaceHolder = ew_RemoveHtml($this->alamat->FldCaption());

			// nama_hubungan
			$this->nama_hubungan->EditAttrs["class"] = "form-control";
			$this->nama_hubungan->EditCustomAttributes = "";
			$this->nama_hubungan->EditValue = ew_HtmlEncode($this->nama_hubungan->CurrentValue);
			$this->nama_hubungan->PlaceHolder = ew_RemoveHtml($this->nama_hubungan->FldCaption());

			// telp_extra
			$this->telp_extra->EditAttrs["class"] = "form-control";
			$this->telp_extra->EditCustomAttributes = "";
			$this->telp_extra->EditValue = ew_HtmlEncode($this->telp_extra->CurrentValue);
			$this->telp_extra->PlaceHolder = ew_RemoveHtml($this->telp_extra->FldCaption());

			// hubungan
			$this->hubungan->EditAttrs["class"] = "form-control";
			$this->hubungan->EditCustomAttributes = "";
			$this->hubungan->EditValue = $this->hubungan->Options(TRUE);

			// Add refer script
			// pend_id

			$this->pend_id->LinkCustomAttributes = "";
			$this->pend_id->HrefValue = "";

			// gol_darah
			$this->gol_darah->LinkCustomAttributes = "";
			$this->gol_darah->HrefValue = "";

			// stat_nikah
			$this->stat_nikah->LinkCustomAttributes = "";
			$this->stat_nikah->HrefValue = "";

			// agama
			$this->agama->LinkCustomAttributes = "";
			$this->agama->HrefValue = "";

			// jml_anak
			$this->jml_anak->LinkCustomAttributes = "";
			$this->jml_anak->HrefValue = "";

			// alamat
			$this->alamat->LinkCustomAttributes = "";
			$this->alamat->HrefValue = "";

			// nama_hubungan
			$this->nama_hubungan->LinkCustomAttributes = "";
			$this->nama_hubungan->HrefValue = "";

			// telp_extra
			$this->telp_extra->LinkCustomAttributes = "";
			$this->telp_extra->HrefValue = "";

			// hubungan
			$this->hubungan->LinkCustomAttributes = "";
			$this->hubungan->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!ew_CheckInteger($this->jml_anak->FormValue)) {
			ew_AddMessage($gsFormError, $this->jml_anak->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// pend_id
		$this->pend_id->SetDbValueDef($rsnew, $this->pend_id->CurrentValue, NULL, FALSE);

		// gol_darah
		$this->gol_darah->SetDbValueDef($rsnew, $this->gol_darah->CurrentValue, NULL, FALSE);

		// stat_nikah
		$this->stat_nikah->SetDbValueDef($rsnew, $this->stat_nikah->CurrentValue, NULL, FALSE);

		// agama
		$this->agama->SetDbValueDef($rsnew, $this->agama->CurrentValue, NULL, FALSE);

		// jml_anak
		$this->jml_anak->SetDbValueDef($rsnew, $this->jml_anak->CurrentValue, NULL, FALSE);

		// alamat
		$this->alamat->SetDbValueDef($rsnew, $this->alamat->CurrentValue, NULL, FALSE);

		// nama_hubungan
		$this->nama_hubungan->SetDbValueDef($rsnew, $this->nama_hubungan->CurrentValue, NULL, FALSE);

		// telp_extra
		$this->telp_extra->SetDbValueDef($rsnew, $this->telp_extra->CurrentValue, NULL, FALSE);

		// hubungan
		$this->hubungan->SetDbValueDef($rsnew, $this->hubungan->CurrentValue, NULL, FALSE);

		// pegawai_id
		if ($this->pegawai_id->getSessionValue() <> "") {
			$rsnew['pegawai_id'] = $this->pegawai_id->getSessionValue();
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['pegawai_id']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
	}

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "pegawai") {
				$bValidMaster = TRUE;
				if (@$_GET["fk_pegawai_id"] <> "") {
					$GLOBALS["pegawai"]->pegawai_id->setQueryStringValue($_GET["fk_pegawai_id"]);
					$this->pegawai_id->setQueryStringValue($GLOBALS["pegawai"]->pegawai_id->QueryStringValue);
					$this->pegawai_id->setSessionValue($this->pegawai_id->QueryStringValue);
					if (!is_numeric($GLOBALS["pegawai"]->pegawai_id->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		} elseif (isset($_POST[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_POST[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "pegawai") {
				$bValidMaster = TRUE;
				if (@$_POST["fk_pegawai_id"] <> "") {
					$GLOBALS["pegawai"]->pegawai_id->setFormValue($_POST["fk_pegawai_id"]);
					$this->pegawai_id->setFormValue($GLOBALS["pegawai"]->pegawai_id->FormValue);
					$this->pegawai_id->setSessionValue($this->pegawai_id->FormValue);
					if (!is_numeric($GLOBALS["pegawai"]->pegawai_id->FormValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "pegawai") {
				if ($this->pegawai_id->CurrentValue == "") $this->pegawai_id->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); // Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("pegawai_dlist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($pegawai_d_add)) $pegawai_d_add = new cpegawai_d_add();

// Page init
$pegawai_d_add->Page_Init();

// Page main
$pegawai_d_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pegawai_d_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fpegawai_dadd = new ew_Form("fpegawai_dadd", "add");

// Validate form
fpegawai_dadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_jml_anak");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pegawai_d->jml_anak->FldErrMsg()) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fpegawai_dadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpegawai_dadd.ValidateRequired = true;
<?php } else { ?>
fpegawai_dadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpegawai_dadd.Lists["x_pend_id"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpegawai_dadd.Lists["x_pend_id"].Options = <?php echo json_encode($pegawai_d->pend_id->Options()) ?>;
fpegawai_dadd.Lists["x_gol_darah"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpegawai_dadd.Lists["x_gol_darah"].Options = <?php echo json_encode($pegawai_d->gol_darah->Options()) ?>;
fpegawai_dadd.Lists["x_stat_nikah"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpegawai_dadd.Lists["x_stat_nikah"].Options = <?php echo json_encode($pegawai_d->stat_nikah->Options()) ?>;
fpegawai_dadd.Lists["x_agama"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpegawai_dadd.Lists["x_agama"].Options = <?php echo json_encode($pegawai_d->agama->Options()) ?>;
fpegawai_dadd.Lists["x_hubungan"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpegawai_dadd.Lists["x_hubungan"].Options = <?php echo json_encode($pegawai_d->hubungan->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$pegawai_d_add->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $pegawai_d_add->ShowPageHeader(); ?>
<?php
$pegawai_d_add->ShowMessage();
?>
<form name="fpegawai_dadd" id="fpegawai_dadd" class="<?php echo $pegawai_d_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pegawai_d_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pegawai_d_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pegawai_d">
<input type="hidden" name="a_add" id="a_add" value="A">
<?php if ($pegawai_d_add->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($pegawai_d->getCurrentMasterTable() == "pegawai") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="pegawai">
<input type="hidden" name="fk_pegawai_id" value="<?php echo $pegawai_d->pegawai_id->getSessionValue() ?>">
<?php } ?>
<div>
<?php if ($pegawai_d->pend_id->Visible) { // pend_id ?>
	<div id="r_pend_id" class="form-group">
		<label id="elh_pegawai_d_pend_id" for="x_pend_id" class="col-sm-2 control-label ewLabel"><?php echo $pegawai_d->pend_id->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai_d->pend_id->CellAttributes() ?>>
<span id="el_pegawai_d_pend_id">
<select data-table="pegawai_d" data-field="x_pend_id" data-value-separator="<?php echo $pegawai_d->pend_id->DisplayValueSeparatorAttribute() ?>" id="x_pend_id" name="x_pend_id"<?php echo $pegawai_d->pend_id->EditAttributes() ?>>
<?php echo $pegawai_d->pend_id->SelectOptionListHtml("x_pend_id") ?>
</select>
</span>
<?php echo $pegawai_d->pend_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai_d->gol_darah->Visible) { // gol_darah ?>
	<div id="r_gol_darah" class="form-group">
		<label id="elh_pegawai_d_gol_darah" for="x_gol_darah" class="col-sm-2 control-label ewLabel"><?php echo $pegawai_d->gol_darah->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai_d->gol_darah->CellAttributes() ?>>
<span id="el_pegawai_d_gol_darah">
<select data-table="pegawai_d" data-field="x_gol_darah" data-value-separator="<?php echo $pegawai_d->gol_darah->DisplayValueSeparatorAttribute() ?>" id="x_gol_darah" name="x_gol_darah"<?php echo $pegawai_d->gol_darah->EditAttributes() ?>>
<?php echo $pegawai_d->gol_darah->SelectOptionListHtml("x_gol_darah") ?>
</select>
</span>
<?php echo $pegawai_d->gol_darah->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai_d->stat_nikah->Visible) { // stat_nikah ?>
	<div id="r_stat_nikah" class="form-group">
		<label id="elh_pegawai_d_stat_nikah" for="x_stat_nikah" class="col-sm-2 control-label ewLabel"><?php echo $pegawai_d->stat_nikah->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai_d->stat_nikah->CellAttributes() ?>>
<span id="el_pegawai_d_stat_nikah">
<select data-table="pegawai_d" data-field="x_stat_nikah" data-value-separator="<?php echo $pegawai_d->stat_nikah->DisplayValueSeparatorAttribute() ?>" id="x_stat_nikah" name="x_stat_nikah"<?php echo $pegawai_d->stat_nikah->EditAttributes() ?>>
<?php echo $pegawai_d->stat_nikah->SelectOptionListHtml("x_stat_nikah") ?>
</select>
</span>
<?php echo $pegawai_d->stat_nikah->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai_d->agama->Visible) { // agama ?>
	<div id="r_agama" class="form-group">
		<label id="elh_pegawai_d_agama" for="x_agama" class="col-sm-2 control-label ewLabel"><?php echo $pegawai_d->agama->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai_d->agama->CellAttributes() ?>>
<span id="el_pegawai_d_agama">
<select data-table="pegawai_d" data-field="x_agama" data-value-separator="<?php echo $pegawai_d->agama->DisplayValueSeparatorAttribute() ?>" id="x_agama" name="x_agama"<?php echo $pegawai_d->agama->EditAttributes() ?>>
<?php echo $pegawai_d->agama->SelectOptionListHtml("x_agama") ?>
</select>
</span>
<?php echo $pegawai_d->agama->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai_d->jml_anak->Visible) { // jml_anak ?>
	<div id="r_jml_anak" class="form-group">
		<label id="elh_pegawai_d_jml_anak" for="x_jml_anak" class="col-sm-2 control-label ewLabel"><?php echo $pegawai_d->jml_anak->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai_d->jml_anak->CellAttributes() ?>>
<span id="el_pegawai_d_jml_anak">
<input type="text" data-table="pegawai_d" data-field="x_jml_anak" name="x_jml_anak" id="x_jml_anak" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->jml_anak->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->jml_anak->EditValue ?>"<?php echo $pegawai_d->jml_anak->EditAttributes() ?>>
</span>
<?php echo $pegawai_d->jml_anak->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai_d->alamat->Visible) { // alamat ?>
	<div id="r_alamat" class="form-group">
		<label id="elh_pegawai_d_alamat" for="x_alamat" class="col-sm-2 control-label ewLabel"><?php echo $pegawai_d->alamat->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai_d->alamat->CellAttributes() ?>>
<span id="el_pegawai_d_alamat">
<input type="text" data-table="pegawai_d" data-field="x_alamat" name="x_alamat" id="x_alamat" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pegawai_d->alamat->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->alamat->EditValue ?>"<?php echo $pegawai_d->alamat->EditAttributes() ?>>
</span>
<?php echo $pegawai_d->alamat->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai_d->nama_hubungan->Visible) { // nama_hubungan ?>
	<div id="r_nama_hubungan" class="form-group">
		<label id="elh_pegawai_d_nama_hubungan" for="x_nama_hubungan" class="col-sm-2 control-label ewLabel"><?php echo $pegawai_d->nama_hubungan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai_d->nama_hubungan->CellAttributes() ?>>
<span id="el_pegawai_d_nama_hubungan">
<input type="text" data-table="pegawai_d" data-field="x_nama_hubungan" name="x_nama_hubungan" id="x_nama_hubungan" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pegawai_d->nama_hubungan->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->nama_hubungan->EditValue ?>"<?php echo $pegawai_d->nama_hubungan->EditAttributes() ?>>
</span>
<?php echo $pegawai_d->nama_hubungan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai_d->telp_extra->Visible) { // telp_extra ?>
	<div id="r_telp_extra" class="form-group">
		<label id="elh_pegawai_d_telp_extra" for="x_telp_extra" class="col-sm-2 control-label ewLabel"><?php echo $pegawai_d->telp_extra->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai_d->telp_extra->CellAttributes() ?>>
<span id="el_pegawai_d_telp_extra">
<input type="text" data-table="pegawai_d" data-field="x_telp_extra" name="x_telp_extra" id="x_telp_extra" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($pegawai_d->telp_extra->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->telp_extra->EditValue ?>"<?php echo $pegawai_d->telp_extra->EditAttributes() ?>>
</span>
<?php echo $pegawai_d->telp_extra->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai_d->hubungan->Visible) { // hubungan ?>
	<div id="r_hubungan" class="form-group">
		<label id="elh_pegawai_d_hubungan" for="x_hubungan" class="col-sm-2 control-label ewLabel"><?php echo $pegawai_d->hubungan->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai_d->hubungan->CellAttributes() ?>>
<span id="el_pegawai_d_hubungan">
<select data-table="pegawai_d" data-field="x_hubungan" data-value-separator="<?php echo $pegawai_d->hubungan->DisplayValueSeparatorAttribute() ?>" id="x_hubungan" name="x_hubungan"<?php echo $pegawai_d->hubungan->EditAttributes() ?>>
<?php echo $pegawai_d->hubungan->SelectOptionListHtml("x_hubungan") ?>
</select>
</span>
<?php echo $pegawai_d->hubungan->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (strval($pegawai_d->pegawai_id->getSessionValue()) <> "") { ?>
<input type="hidden" name="x_pegawai_id" id="x_pegawai_id" value="<?php echo ew_HtmlEncode(strval($pegawai_d->pegawai_id->getSessionValue())) ?>">
<?php } ?>
<?php if (!$pegawai_d_add->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $pegawai_d_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php } ?>
</form>
<script type="text/javascript">
fpegawai_dadd.Init();
</script>
<?php
$pegawai_d_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pegawai_d_add->Page_Terminate();
?>
