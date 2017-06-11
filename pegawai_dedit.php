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

$pegawai_d_edit = NULL; // Initialize page object first

class cpegawai_d_edit extends cpegawai_d {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{035CBF11-745C-4982-814A-B6768131C8FC}";

	// Table name
	var $TableName = 'pegawai_d';

	// Page object name
	var $PageObjName = 'pegawai_d_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
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
		$this->pegawai_id->SetVisibility();
		$this->pend_id->SetVisibility();
		$this->gol_darah->SetVisibility();
		$this->stat_nikah->SetVisibility();
		$this->jml_anak->SetVisibility();
		$this->alamat->SetVisibility();
		$this->telp_extra->SetVisibility();
		$this->hubungan->SetVisibility();
		$this->nama_hubungan->SetVisibility();
		$this->agama->SetVisibility();

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
	var $FormClassName = "form-horizontal ewForm ewEditForm";
	var $IsModal = FALSE;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

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

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Load key from QueryString
		if (@$_GET["pegawai_id"] <> "") {
			$this->pegawai_id->setQueryStringValue($_GET["pegawai_id"]);
			$this->RecKey["pegawai_id"] = $this->pegawai_id->QueryStringValue;
		} else {
			$bLoadCurrentRecord = TRUE;
		}

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Load recordset
		$this->StartRec = 1; // Initialize start position
		if ($this->Recordset = $this->LoadRecordset()) // Load records
			$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
		if ($this->TotalRecs <= 0) { // No record found
			if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$this->Page_Terminate("pegawai_dlist.php"); // Return to list page
		} elseif ($bLoadCurrentRecord) { // Load current record position
			$this->SetUpStartRec(); // Set up start record position

			// Point to current record
			if (intval($this->StartRec) <= intval($this->TotalRecs)) {
				$bMatchRecord = TRUE;
				$this->Recordset->Move($this->StartRec-1);
			}
		} else { // Match key values
			while (!$this->Recordset->EOF) {
				if (strval($this->pegawai_id->CurrentValue) == strval($this->Recordset->fields('pegawai_id'))) {
					$this->setStartRecordNumber($this->StartRec); // Save record position
					$bMatchRecord = TRUE;
					break;
				} else {
					$this->StartRec++;
					$this->Recordset->MoveNext();
				}
			}
		}

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$bMatchRecord) {
					if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
						$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
					$this->Page_Terminate("pegawai_dlist.php"); // Return to list page
				} else {
					$this->LoadRowValues($this->Recordset); // Load row values
				}
				break;
			Case "U": // Update
				$sReturnUrl = $this->getReturnUrl();
				if (ew_GetPageName($sReturnUrl) == "pegawai_dlist.php")
					$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to list page with correct master key if necessary
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} elseif ($this->getFailureMessage() == $Language->Phrase("NoRecord")) {
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->pegawai_id->FldIsDetailKey) {
			$this->pegawai_id->setFormValue($objForm->GetValue("x_pegawai_id"));
		}
		if (!$this->pend_id->FldIsDetailKey) {
			$this->pend_id->setFormValue($objForm->GetValue("x_pend_id"));
		}
		if (!$this->gol_darah->FldIsDetailKey) {
			$this->gol_darah->setFormValue($objForm->GetValue("x_gol_darah"));
		}
		if (!$this->stat_nikah->FldIsDetailKey) {
			$this->stat_nikah->setFormValue($objForm->GetValue("x_stat_nikah"));
		}
		if (!$this->jml_anak->FldIsDetailKey) {
			$this->jml_anak->setFormValue($objForm->GetValue("x_jml_anak"));
		}
		if (!$this->alamat->FldIsDetailKey) {
			$this->alamat->setFormValue($objForm->GetValue("x_alamat"));
		}
		if (!$this->telp_extra->FldIsDetailKey) {
			$this->telp_extra->setFormValue($objForm->GetValue("x_telp_extra"));
		}
		if (!$this->hubungan->FldIsDetailKey) {
			$this->hubungan->setFormValue($objForm->GetValue("x_hubungan"));
		}
		if (!$this->nama_hubungan->FldIsDetailKey) {
			$this->nama_hubungan->setFormValue($objForm->GetValue("x_nama_hubungan"));
		}
		if (!$this->agama->FldIsDetailKey) {
			$this->agama->setFormValue($objForm->GetValue("x_agama"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->pegawai_id->CurrentValue = $this->pegawai_id->FormValue;
		$this->pend_id->CurrentValue = $this->pend_id->FormValue;
		$this->gol_darah->CurrentValue = $this->gol_darah->FormValue;
		$this->stat_nikah->CurrentValue = $this->stat_nikah->FormValue;
		$this->jml_anak->CurrentValue = $this->jml_anak->FormValue;
		$this->alamat->CurrentValue = $this->alamat->FormValue;
		$this->telp_extra->CurrentValue = $this->telp_extra->FormValue;
		$this->hubungan->CurrentValue = $this->hubungan->FormValue;
		$this->nama_hubungan->CurrentValue = $this->nama_hubungan->FormValue;
		$this->agama->CurrentValue = $this->agama->FormValue;
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
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
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
		$this->pend_id->setDbValue($rs->fields('pend_id'));
		$this->gol_darah->setDbValue($rs->fields('gol_darah'));
		$this->stat_nikah->setDbValue($rs->fields('stat_nikah'));
		$this->jml_anak->setDbValue($rs->fields('jml_anak'));
		$this->alamat->setDbValue($rs->fields('alamat'));
		$this->telp_extra->setDbValue($rs->fields('telp_extra'));
		$this->hubungan->setDbValue($rs->fields('hubungan'));
		$this->nama_hubungan->setDbValue($rs->fields('nama_hubungan'));
		$this->agama->setDbValue($rs->fields('agama'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->pegawai_id->DbValue = $row['pegawai_id'];
		$this->pend_id->DbValue = $row['pend_id'];
		$this->gol_darah->DbValue = $row['gol_darah'];
		$this->stat_nikah->DbValue = $row['stat_nikah'];
		$this->jml_anak->DbValue = $row['jml_anak'];
		$this->alamat->DbValue = $row['alamat'];
		$this->telp_extra->DbValue = $row['telp_extra'];
		$this->hubungan->DbValue = $row['hubungan'];
		$this->nama_hubungan->DbValue = $row['nama_hubungan'];
		$this->agama->DbValue = $row['agama'];
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
		// jml_anak
		// alamat
		// telp_extra
		// hubungan
		// nama_hubungan
		// agama

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// pegawai_id
		$this->pegawai_id->ViewValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// pend_id
		$this->pend_id->ViewValue = $this->pend_id->CurrentValue;
		$this->pend_id->ViewCustomAttributes = "";

		// gol_darah
		$this->gol_darah->ViewValue = $this->gol_darah->CurrentValue;
		$this->gol_darah->ViewCustomAttributes = "";

		// stat_nikah
		$this->stat_nikah->ViewValue = $this->stat_nikah->CurrentValue;
		$this->stat_nikah->ViewCustomAttributes = "";

		// jml_anak
		$this->jml_anak->ViewValue = $this->jml_anak->CurrentValue;
		$this->jml_anak->ViewCustomAttributes = "";

		// alamat
		$this->alamat->ViewValue = $this->alamat->CurrentValue;
		$this->alamat->ViewCustomAttributes = "";

		// telp_extra
		$this->telp_extra->ViewValue = $this->telp_extra->CurrentValue;
		$this->telp_extra->ViewCustomAttributes = "";

		// hubungan
		$this->hubungan->ViewValue = $this->hubungan->CurrentValue;
		$this->hubungan->ViewCustomAttributes = "";

		// nama_hubungan
		$this->nama_hubungan->ViewValue = $this->nama_hubungan->CurrentValue;
		$this->nama_hubungan->ViewCustomAttributes = "";

		// agama
		$this->agama->ViewValue = $this->agama->CurrentValue;
		$this->agama->ViewCustomAttributes = "";

			// pegawai_id
			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";
			$this->pegawai_id->TooltipValue = "";

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

			// jml_anak
			$this->jml_anak->LinkCustomAttributes = "";
			$this->jml_anak->HrefValue = "";
			$this->jml_anak->TooltipValue = "";

			// alamat
			$this->alamat->LinkCustomAttributes = "";
			$this->alamat->HrefValue = "";
			$this->alamat->TooltipValue = "";

			// telp_extra
			$this->telp_extra->LinkCustomAttributes = "";
			$this->telp_extra->HrefValue = "";
			$this->telp_extra->TooltipValue = "";

			// hubungan
			$this->hubungan->LinkCustomAttributes = "";
			$this->hubungan->HrefValue = "";
			$this->hubungan->TooltipValue = "";

			// nama_hubungan
			$this->nama_hubungan->LinkCustomAttributes = "";
			$this->nama_hubungan->HrefValue = "";
			$this->nama_hubungan->TooltipValue = "";

			// agama
			$this->agama->LinkCustomAttributes = "";
			$this->agama->HrefValue = "";
			$this->agama->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// pegawai_id
			$this->pegawai_id->EditAttrs["class"] = "form-control";
			$this->pegawai_id->EditCustomAttributes = "";
			$this->pegawai_id->EditValue = $this->pegawai_id->CurrentValue;
			$this->pegawai_id->ViewCustomAttributes = "";

			// pend_id
			$this->pend_id->EditAttrs["class"] = "form-control";
			$this->pend_id->EditCustomAttributes = "";
			$this->pend_id->EditValue = ew_HtmlEncode($this->pend_id->CurrentValue);
			$this->pend_id->PlaceHolder = ew_RemoveHtml($this->pend_id->FldCaption());

			// gol_darah
			$this->gol_darah->EditAttrs["class"] = "form-control";
			$this->gol_darah->EditCustomAttributes = "";
			$this->gol_darah->EditValue = ew_HtmlEncode($this->gol_darah->CurrentValue);
			$this->gol_darah->PlaceHolder = ew_RemoveHtml($this->gol_darah->FldCaption());

			// stat_nikah
			$this->stat_nikah->EditAttrs["class"] = "form-control";
			$this->stat_nikah->EditCustomAttributes = "";
			$this->stat_nikah->EditValue = ew_HtmlEncode($this->stat_nikah->CurrentValue);
			$this->stat_nikah->PlaceHolder = ew_RemoveHtml($this->stat_nikah->FldCaption());

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

			// telp_extra
			$this->telp_extra->EditAttrs["class"] = "form-control";
			$this->telp_extra->EditCustomAttributes = "";
			$this->telp_extra->EditValue = ew_HtmlEncode($this->telp_extra->CurrentValue);
			$this->telp_extra->PlaceHolder = ew_RemoveHtml($this->telp_extra->FldCaption());

			// hubungan
			$this->hubungan->EditAttrs["class"] = "form-control";
			$this->hubungan->EditCustomAttributes = "";
			$this->hubungan->EditValue = ew_HtmlEncode($this->hubungan->CurrentValue);
			$this->hubungan->PlaceHolder = ew_RemoveHtml($this->hubungan->FldCaption());

			// nama_hubungan
			$this->nama_hubungan->EditAttrs["class"] = "form-control";
			$this->nama_hubungan->EditCustomAttributes = "";
			$this->nama_hubungan->EditValue = ew_HtmlEncode($this->nama_hubungan->CurrentValue);
			$this->nama_hubungan->PlaceHolder = ew_RemoveHtml($this->nama_hubungan->FldCaption());

			// agama
			$this->agama->EditAttrs["class"] = "form-control";
			$this->agama->EditCustomAttributes = "";
			$this->agama->EditValue = ew_HtmlEncode($this->agama->CurrentValue);
			$this->agama->PlaceHolder = ew_RemoveHtml($this->agama->FldCaption());

			// Edit refer script
			// pegawai_id

			$this->pegawai_id->LinkCustomAttributes = "";
			$this->pegawai_id->HrefValue = "";

			// pend_id
			$this->pend_id->LinkCustomAttributes = "";
			$this->pend_id->HrefValue = "";

			// gol_darah
			$this->gol_darah->LinkCustomAttributes = "";
			$this->gol_darah->HrefValue = "";

			// stat_nikah
			$this->stat_nikah->LinkCustomAttributes = "";
			$this->stat_nikah->HrefValue = "";

			// jml_anak
			$this->jml_anak->LinkCustomAttributes = "";
			$this->jml_anak->HrefValue = "";

			// alamat
			$this->alamat->LinkCustomAttributes = "";
			$this->alamat->HrefValue = "";

			// telp_extra
			$this->telp_extra->LinkCustomAttributes = "";
			$this->telp_extra->HrefValue = "";

			// hubungan
			$this->hubungan->LinkCustomAttributes = "";
			$this->hubungan->HrefValue = "";

			// nama_hubungan
			$this->nama_hubungan->LinkCustomAttributes = "";
			$this->nama_hubungan->HrefValue = "";

			// agama
			$this->agama->LinkCustomAttributes = "";
			$this->agama->HrefValue = "";
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
		if (!$this->pegawai_id->FldIsDetailKey && !is_null($this->pegawai_id->FormValue) && $this->pegawai_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pegawai_id->FldCaption(), $this->pegawai_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->pegawai_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->pegawai_id->FldErrMsg());
		}
		if (!$this->pend_id->FldIsDetailKey && !is_null($this->pend_id->FormValue) && $this->pend_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->pend_id->FldCaption(), $this->pend_id->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->pend_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->pend_id->FldErrMsg());
		}
		if (!$this->gol_darah->FldIsDetailKey && !is_null($this->gol_darah->FormValue) && $this->gol_darah->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->gol_darah->FldCaption(), $this->gol_darah->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->gol_darah->FormValue)) {
			ew_AddMessage($gsFormError, $this->gol_darah->FldErrMsg());
		}
		if (!$this->stat_nikah->FldIsDetailKey && !is_null($this->stat_nikah->FormValue) && $this->stat_nikah->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->stat_nikah->FldCaption(), $this->stat_nikah->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->stat_nikah->FormValue)) {
			ew_AddMessage($gsFormError, $this->stat_nikah->FldErrMsg());
		}
		if (!$this->jml_anak->FldIsDetailKey && !is_null($this->jml_anak->FormValue) && $this->jml_anak->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->jml_anak->FldCaption(), $this->jml_anak->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->jml_anak->FormValue)) {
			ew_AddMessage($gsFormError, $this->jml_anak->FldErrMsg());
		}
		if (!$this->telp_extra->FldIsDetailKey && !is_null($this->telp_extra->FormValue) && $this->telp_extra->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->telp_extra->FldCaption(), $this->telp_extra->ReqErrMsg));
		}
		if (!$this->hubungan->FldIsDetailKey && !is_null($this->hubungan->FormValue) && $this->hubungan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->hubungan->FldCaption(), $this->hubungan->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->hubungan->FormValue)) {
			ew_AddMessage($gsFormError, $this->hubungan->FldErrMsg());
		}
		if (!$this->nama_hubungan->FldIsDetailKey && !is_null($this->nama_hubungan->FormValue) && $this->nama_hubungan->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->nama_hubungan->FldCaption(), $this->nama_hubungan->ReqErrMsg));
		}
		if (!$this->agama->FldIsDetailKey && !is_null($this->agama->FormValue) && $this->agama->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->agama->FldCaption(), $this->agama->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->agama->FormValue)) {
			ew_AddMessage($gsFormError, $this->agama->FldErrMsg());
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

	// Update record based on key values
	function EditRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$conn = &$this->Connection();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$this->LoadDbValues($rsold);
			$rsnew = array();

			// pegawai_id
			// pend_id

			$this->pend_id->SetDbValueDef($rsnew, $this->pend_id->CurrentValue, 0, $this->pend_id->ReadOnly);

			// gol_darah
			$this->gol_darah->SetDbValueDef($rsnew, $this->gol_darah->CurrentValue, 0, $this->gol_darah->ReadOnly);

			// stat_nikah
			$this->stat_nikah->SetDbValueDef($rsnew, $this->stat_nikah->CurrentValue, 0, $this->stat_nikah->ReadOnly);

			// jml_anak
			$this->jml_anak->SetDbValueDef($rsnew, $this->jml_anak->CurrentValue, 0, $this->jml_anak->ReadOnly);

			// alamat
			$this->alamat->SetDbValueDef($rsnew, $this->alamat->CurrentValue, NULL, $this->alamat->ReadOnly);

			// telp_extra
			$this->telp_extra->SetDbValueDef($rsnew, $this->telp_extra->CurrentValue, "", $this->telp_extra->ReadOnly);

			// hubungan
			$this->hubungan->SetDbValueDef($rsnew, $this->hubungan->CurrentValue, 0, $this->hubungan->ReadOnly);

			// nama_hubungan
			$this->nama_hubungan->SetDbValueDef($rsnew, $this->nama_hubungan->CurrentValue, "", $this->nama_hubungan->ReadOnly);

			// agama
			$this->agama->SetDbValueDef($rsnew, $this->agama->CurrentValue, 0, $this->agama->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew, "", $rsold);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
				if ($EditRow) {
				}
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
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
			$this->setSessionWhere($this->GetDetailFilter());

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
		$PageId = "edit";
		$Breadcrumb->Add("edit", $PageId, $url);
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
if (!isset($pegawai_d_edit)) $pegawai_d_edit = new cpegawai_d_edit();

// Page init
$pegawai_d_edit->Page_Init();

// Page main
$pegawai_d_edit->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pegawai_d_edit->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "edit";
var CurrentForm = fpegawai_dedit = new ew_Form("fpegawai_dedit", "edit");

// Validate form
fpegawai_dedit.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai_d->pegawai_id->FldCaption(), $pegawai_d->pegawai_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pegawai_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pegawai_d->pegawai_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_pend_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai_d->pend_id->FldCaption(), $pegawai_d->pend_id->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_pend_id");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pegawai_d->pend_id->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_gol_darah");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai_d->gol_darah->FldCaption(), $pegawai_d->gol_darah->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_gol_darah");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pegawai_d->gol_darah->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_stat_nikah");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai_d->stat_nikah->FldCaption(), $pegawai_d->stat_nikah->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_stat_nikah");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pegawai_d->stat_nikah->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_jml_anak");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai_d->jml_anak->FldCaption(), $pegawai_d->jml_anak->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_jml_anak");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pegawai_d->jml_anak->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_telp_extra");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai_d->telp_extra->FldCaption(), $pegawai_d->telp_extra->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hubungan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai_d->hubungan->FldCaption(), $pegawai_d->hubungan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_hubungan");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pegawai_d->hubungan->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_nama_hubungan");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai_d->nama_hubungan->FldCaption(), $pegawai_d->nama_hubungan->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_agama");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $pegawai_d->agama->FldCaption(), $pegawai_d->agama->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_agama");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($pegawai_d->agama->FldErrMsg()) ?>");

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
fpegawai_dedit.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpegawai_dedit.ValidateRequired = true;
<?php } else { ?>
fpegawai_dedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (!$pegawai_d_edit->IsModal) { ?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $pegawai_d_edit->ShowPageHeader(); ?>
<?php
$pegawai_d_edit->ShowMessage();
?>
<?php if (!$pegawai_d_edit->IsModal) { ?>
<form name="ewPagerForm" class="form-horizontal ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($pegawai_d_edit->Pager)) $pegawai_d_edit->Pager = new cPrevNextPager($pegawai_d_edit->StartRec, $pegawai_d_edit->DisplayRecs, $pegawai_d_edit->TotalRecs) ?>
<?php if ($pegawai_d_edit->Pager->RecordCount > 0 && $pegawai_d_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pegawai_d_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pegawai_d_edit->PageUrl() ?>start=<?php echo $pegawai_d_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pegawai_d_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pegawai_d_edit->PageUrl() ?>start=<?php echo $pegawai_d_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pegawai_d_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pegawai_d_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pegawai_d_edit->PageUrl() ?>start=<?php echo $pegawai_d_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pegawai_d_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pegawai_d_edit->PageUrl() ?>start=<?php echo $pegawai_d_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pegawai_d_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fpegawai_dedit" id="fpegawai_dedit" class="<?php echo $pegawai_d_edit->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pegawai_d_edit->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pegawai_d_edit->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pegawai_d">
<input type="hidden" name="a_edit" id="a_edit" value="U">
<?php if ($pegawai_d_edit->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<?php if ($pegawai_d->getCurrentMasterTable() == "pegawai") { ?>
<input type="hidden" name="<?php echo EW_TABLE_SHOW_MASTER ?>" value="pegawai">
<input type="hidden" name="fk_pegawai_id" value="<?php echo $pegawai_d->pegawai_id->getSessionValue() ?>">
<?php } ?>
<div>
<?php if ($pegawai_d->pegawai_id->Visible) { // pegawai_id ?>
	<div id="r_pegawai_id" class="form-group">
		<label id="elh_pegawai_d_pegawai_id" for="x_pegawai_id" class="col-sm-2 control-label ewLabel"><?php echo $pegawai_d->pegawai_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai_d->pegawai_id->CellAttributes() ?>>
<span id="el_pegawai_d_pegawai_id">
<span<?php echo $pegawai_d->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pegawai_d->pegawai_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_pegawai_id" name="x_pegawai_id" id="x_pegawai_id" value="<?php echo ew_HtmlEncode($pegawai_d->pegawai_id->CurrentValue) ?>">
<?php echo $pegawai_d->pegawai_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai_d->pend_id->Visible) { // pend_id ?>
	<div id="r_pend_id" class="form-group">
		<label id="elh_pegawai_d_pend_id" for="x_pend_id" class="col-sm-2 control-label ewLabel"><?php echo $pegawai_d->pend_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai_d->pend_id->CellAttributes() ?>>
<span id="el_pegawai_d_pend_id">
<input type="text" data-table="pegawai_d" data-field="x_pend_id" name="x_pend_id" id="x_pend_id" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->pend_id->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->pend_id->EditValue ?>"<?php echo $pegawai_d->pend_id->EditAttributes() ?>>
</span>
<?php echo $pegawai_d->pend_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai_d->gol_darah->Visible) { // gol_darah ?>
	<div id="r_gol_darah" class="form-group">
		<label id="elh_pegawai_d_gol_darah" for="x_gol_darah" class="col-sm-2 control-label ewLabel"><?php echo $pegawai_d->gol_darah->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai_d->gol_darah->CellAttributes() ?>>
<span id="el_pegawai_d_gol_darah">
<input type="text" data-table="pegawai_d" data-field="x_gol_darah" name="x_gol_darah" id="x_gol_darah" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->gol_darah->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->gol_darah->EditValue ?>"<?php echo $pegawai_d->gol_darah->EditAttributes() ?>>
</span>
<?php echo $pegawai_d->gol_darah->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai_d->stat_nikah->Visible) { // stat_nikah ?>
	<div id="r_stat_nikah" class="form-group">
		<label id="elh_pegawai_d_stat_nikah" for="x_stat_nikah" class="col-sm-2 control-label ewLabel"><?php echo $pegawai_d->stat_nikah->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai_d->stat_nikah->CellAttributes() ?>>
<span id="el_pegawai_d_stat_nikah">
<input type="text" data-table="pegawai_d" data-field="x_stat_nikah" name="x_stat_nikah" id="x_stat_nikah" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->stat_nikah->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->stat_nikah->EditValue ?>"<?php echo $pegawai_d->stat_nikah->EditAttributes() ?>>
</span>
<?php echo $pegawai_d->stat_nikah->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai_d->jml_anak->Visible) { // jml_anak ?>
	<div id="r_jml_anak" class="form-group">
		<label id="elh_pegawai_d_jml_anak" for="x_jml_anak" class="col-sm-2 control-label ewLabel"><?php echo $pegawai_d->jml_anak->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
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
<?php if ($pegawai_d->telp_extra->Visible) { // telp_extra ?>
	<div id="r_telp_extra" class="form-group">
		<label id="elh_pegawai_d_telp_extra" for="x_telp_extra" class="col-sm-2 control-label ewLabel"><?php echo $pegawai_d->telp_extra->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai_d->telp_extra->CellAttributes() ?>>
<span id="el_pegawai_d_telp_extra">
<input type="text" data-table="pegawai_d" data-field="x_telp_extra" name="x_telp_extra" id="x_telp_extra" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($pegawai_d->telp_extra->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->telp_extra->EditValue ?>"<?php echo $pegawai_d->telp_extra->EditAttributes() ?>>
</span>
<?php echo $pegawai_d->telp_extra->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai_d->hubungan->Visible) { // hubungan ?>
	<div id="r_hubungan" class="form-group">
		<label id="elh_pegawai_d_hubungan" for="x_hubungan" class="col-sm-2 control-label ewLabel"><?php echo $pegawai_d->hubungan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai_d->hubungan->CellAttributes() ?>>
<span id="el_pegawai_d_hubungan">
<input type="text" data-table="pegawai_d" data-field="x_hubungan" name="x_hubungan" id="x_hubungan" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->hubungan->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->hubungan->EditValue ?>"<?php echo $pegawai_d->hubungan->EditAttributes() ?>>
</span>
<?php echo $pegawai_d->hubungan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai_d->nama_hubungan->Visible) { // nama_hubungan ?>
	<div id="r_nama_hubungan" class="form-group">
		<label id="elh_pegawai_d_nama_hubungan" for="x_nama_hubungan" class="col-sm-2 control-label ewLabel"><?php echo $pegawai_d->nama_hubungan->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai_d->nama_hubungan->CellAttributes() ?>>
<span id="el_pegawai_d_nama_hubungan">
<input type="text" data-table="pegawai_d" data-field="x_nama_hubungan" name="x_nama_hubungan" id="x_nama_hubungan" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pegawai_d->nama_hubungan->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->nama_hubungan->EditValue ?>"<?php echo $pegawai_d->nama_hubungan->EditAttributes() ?>>
</span>
<?php echo $pegawai_d->nama_hubungan->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($pegawai_d->agama->Visible) { // agama ?>
	<div id="r_agama" class="form-group">
		<label id="elh_pegawai_d_agama" for="x_agama" class="col-sm-2 control-label ewLabel"><?php echo $pegawai_d->agama->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $pegawai_d->agama->CellAttributes() ?>>
<span id="el_pegawai_d_agama">
<input type="text" data-table="pegawai_d" data-field="x_agama" name="x_agama" id="x_agama" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->agama->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->agama->EditValue ?>"<?php echo $pegawai_d->agama->EditAttributes() ?>>
</span>
<?php echo $pegawai_d->agama->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<?php if (!$pegawai_d_edit->IsModal) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("SaveBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $pegawai_d_edit->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
<?php if (!isset($pegawai_d_edit->Pager)) $pegawai_d_edit->Pager = new cPrevNextPager($pegawai_d_edit->StartRec, $pegawai_d_edit->DisplayRecs, $pegawai_d_edit->TotalRecs) ?>
<?php if ($pegawai_d_edit->Pager->RecordCount > 0 && $pegawai_d_edit->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pegawai_d_edit->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pegawai_d_edit->PageUrl() ?>start=<?php echo $pegawai_d_edit->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pegawai_d_edit->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pegawai_d_edit->PageUrl() ?>start=<?php echo $pegawai_d_edit->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pegawai_d_edit->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pegawai_d_edit->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pegawai_d_edit->PageUrl() ?>start=<?php echo $pegawai_d_edit->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pegawai_d_edit->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pegawai_d_edit->PageUrl() ?>start=<?php echo $pegawai_d_edit->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pegawai_d_edit->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
fpegawai_dedit.Init();
</script>
<?php
$pegawai_d_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pegawai_d_edit->Page_Terminate();
?>