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

$pegawai_d_delete = NULL; // Initialize page object first

class cpegawai_d_delete extends cpegawai_d {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{035CBF11-745C-4982-814A-B6768131C8FC}";

	// Table name
	var $TableName = 'pegawai_d';

	// Page object name
	var $PageObjName = 'pegawai_d_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
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

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("pegawai_dlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in pegawai_d class, pegawai_dinfo.php

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
				$this->Page_Terminate("pegawai_dlist.php"); // Return to list
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
if (!isset($pegawai_d_delete)) $pegawai_d_delete = new cpegawai_d_delete();

// Page init
$pegawai_d_delete->Page_Init();

// Page main
$pegawai_d_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pegawai_d_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fpegawai_ddelete = new ew_Form("fpegawai_ddelete", "delete");

// Form_CustomValidate event
fpegawai_ddelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpegawai_ddelete.ValidateRequired = true;
<?php } else { ?>
fpegawai_ddelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpegawai_ddelete.Lists["x_pend_id"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpegawai_ddelete.Lists["x_pend_id"].Options = <?php echo json_encode($pegawai_d->pend_id->Options()) ?>;
fpegawai_ddelete.Lists["x_gol_darah"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpegawai_ddelete.Lists["x_gol_darah"].Options = <?php echo json_encode($pegawai_d->gol_darah->Options()) ?>;
fpegawai_ddelete.Lists["x_stat_nikah"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpegawai_ddelete.Lists["x_stat_nikah"].Options = <?php echo json_encode($pegawai_d->stat_nikah->Options()) ?>;
fpegawai_ddelete.Lists["x_agama"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpegawai_ddelete.Lists["x_agama"].Options = <?php echo json_encode($pegawai_d->agama->Options()) ?>;
fpegawai_ddelete.Lists["x_hubungan"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpegawai_ddelete.Lists["x_hubungan"].Options = <?php echo json_encode($pegawai_d->hubungan->Options()) ?>;

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
<?php $pegawai_d_delete->ShowPageHeader(); ?>
<?php
$pegawai_d_delete->ShowMessage();
?>
<form name="fpegawai_ddelete" id="fpegawai_ddelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pegawai_d_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pegawai_d_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pegawai_d">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($pegawai_d_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $pegawai_d->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($pegawai_d->pend_id->Visible) { // pend_id ?>
		<th><span id="elh_pegawai_d_pend_id" class="pegawai_d_pend_id"><?php echo $pegawai_d->pend_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai_d->gol_darah->Visible) { // gol_darah ?>
		<th><span id="elh_pegawai_d_gol_darah" class="pegawai_d_gol_darah"><?php echo $pegawai_d->gol_darah->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai_d->stat_nikah->Visible) { // stat_nikah ?>
		<th><span id="elh_pegawai_d_stat_nikah" class="pegawai_d_stat_nikah"><?php echo $pegawai_d->stat_nikah->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai_d->agama->Visible) { // agama ?>
		<th><span id="elh_pegawai_d_agama" class="pegawai_d_agama"><?php echo $pegawai_d->agama->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai_d->jml_anak->Visible) { // jml_anak ?>
		<th><span id="elh_pegawai_d_jml_anak" class="pegawai_d_jml_anak"><?php echo $pegawai_d->jml_anak->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai_d->alamat->Visible) { // alamat ?>
		<th><span id="elh_pegawai_d_alamat" class="pegawai_d_alamat"><?php echo $pegawai_d->alamat->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai_d->nama_hubungan->Visible) { // nama_hubungan ?>
		<th><span id="elh_pegawai_d_nama_hubungan" class="pegawai_d_nama_hubungan"><?php echo $pegawai_d->nama_hubungan->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai_d->telp_extra->Visible) { // telp_extra ?>
		<th><span id="elh_pegawai_d_telp_extra" class="pegawai_d_telp_extra"><?php echo $pegawai_d->telp_extra->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pegawai_d->hubungan->Visible) { // hubungan ?>
		<th><span id="elh_pegawai_d_hubungan" class="pegawai_d_hubungan"><?php echo $pegawai_d->hubungan->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$pegawai_d_delete->RecCnt = 0;
$i = 0;
while (!$pegawai_d_delete->Recordset->EOF) {
	$pegawai_d_delete->RecCnt++;
	$pegawai_d_delete->RowCnt++;

	// Set row properties
	$pegawai_d->ResetAttrs();
	$pegawai_d->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$pegawai_d_delete->LoadRowValues($pegawai_d_delete->Recordset);

	// Render row
	$pegawai_d_delete->RenderRow();
?>
	<tr<?php echo $pegawai_d->RowAttributes() ?>>
<?php if ($pegawai_d->pend_id->Visible) { // pend_id ?>
		<td<?php echo $pegawai_d->pend_id->CellAttributes() ?>>
<span id="el<?php echo $pegawai_d_delete->RowCnt ?>_pegawai_d_pend_id" class="pegawai_d_pend_id">
<span<?php echo $pegawai_d->pend_id->ViewAttributes() ?>>
<?php echo $pegawai_d->pend_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai_d->gol_darah->Visible) { // gol_darah ?>
		<td<?php echo $pegawai_d->gol_darah->CellAttributes() ?>>
<span id="el<?php echo $pegawai_d_delete->RowCnt ?>_pegawai_d_gol_darah" class="pegawai_d_gol_darah">
<span<?php echo $pegawai_d->gol_darah->ViewAttributes() ?>>
<?php echo $pegawai_d->gol_darah->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai_d->stat_nikah->Visible) { // stat_nikah ?>
		<td<?php echo $pegawai_d->stat_nikah->CellAttributes() ?>>
<span id="el<?php echo $pegawai_d_delete->RowCnt ?>_pegawai_d_stat_nikah" class="pegawai_d_stat_nikah">
<span<?php echo $pegawai_d->stat_nikah->ViewAttributes() ?>>
<?php echo $pegawai_d->stat_nikah->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai_d->agama->Visible) { // agama ?>
		<td<?php echo $pegawai_d->agama->CellAttributes() ?>>
<span id="el<?php echo $pegawai_d_delete->RowCnt ?>_pegawai_d_agama" class="pegawai_d_agama">
<span<?php echo $pegawai_d->agama->ViewAttributes() ?>>
<?php echo $pegawai_d->agama->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai_d->jml_anak->Visible) { // jml_anak ?>
		<td<?php echo $pegawai_d->jml_anak->CellAttributes() ?>>
<span id="el<?php echo $pegawai_d_delete->RowCnt ?>_pegawai_d_jml_anak" class="pegawai_d_jml_anak">
<span<?php echo $pegawai_d->jml_anak->ViewAttributes() ?>>
<?php echo $pegawai_d->jml_anak->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai_d->alamat->Visible) { // alamat ?>
		<td<?php echo $pegawai_d->alamat->CellAttributes() ?>>
<span id="el<?php echo $pegawai_d_delete->RowCnt ?>_pegawai_d_alamat" class="pegawai_d_alamat">
<span<?php echo $pegawai_d->alamat->ViewAttributes() ?>>
<?php echo $pegawai_d->alamat->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai_d->nama_hubungan->Visible) { // nama_hubungan ?>
		<td<?php echo $pegawai_d->nama_hubungan->CellAttributes() ?>>
<span id="el<?php echo $pegawai_d_delete->RowCnt ?>_pegawai_d_nama_hubungan" class="pegawai_d_nama_hubungan">
<span<?php echo $pegawai_d->nama_hubungan->ViewAttributes() ?>>
<?php echo $pegawai_d->nama_hubungan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai_d->telp_extra->Visible) { // telp_extra ?>
		<td<?php echo $pegawai_d->telp_extra->CellAttributes() ?>>
<span id="el<?php echo $pegawai_d_delete->RowCnt ?>_pegawai_d_telp_extra" class="pegawai_d_telp_extra">
<span<?php echo $pegawai_d->telp_extra->ViewAttributes() ?>>
<?php echo $pegawai_d->telp_extra->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pegawai_d->hubungan->Visible) { // hubungan ?>
		<td<?php echo $pegawai_d->hubungan->CellAttributes() ?>>
<span id="el<?php echo $pegawai_d_delete->RowCnt ?>_pegawai_d_hubungan" class="pegawai_d_hubungan">
<span<?php echo $pegawai_d->hubungan->ViewAttributes() ?>>
<?php echo $pegawai_d->hubungan->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$pegawai_d_delete->Recordset->MoveNext();
}
$pegawai_d_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $pegawai_d_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fpegawai_ddelete.Init();
</script>
<?php
$pegawai_d_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$pegawai_d_delete->Page_Terminate();
?>
