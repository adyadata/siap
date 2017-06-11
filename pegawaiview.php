<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg13.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql13.php") ?>
<?php include_once "phpfn13.php" ?>
<?php include_once "pegawaiinfo.php" ?>
<?php include_once "t_userinfo.php" ?>
<?php include_once "pegawai_dgridcls.php" ?>
<?php include_once "userfn13.php" ?>
<?php

//
// Page class
//

$pegawai_view = NULL; // Initialize page object first

class cpegawai_view extends cpegawai {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{035CBF11-745C-4982-814A-B6768131C8FC}";

	// Table name
	var $TableName = 'pegawai';

	// Page object name
	var $PageObjName = 'pegawai_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["pegawai_id"] <> "") {
			$this->RecKey["pegawai_id"] = $_GET["pegawai_id"];
			$KeyUrl .= "&amp;pegawai_id=" . urlencode($this->RecKey["pegawai_id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (t_user)
		if (!isset($GLOBALS['t_user'])) $GLOBALS['t_user'] = new ct_user();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

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

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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
		if (!$Security->CanView()) {
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

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header
		if (@$_GET["pegawai_id"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["pegawai_id"]);
		}

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Setup export options
		$this->SetupExportOptions();
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $IsModal = FALSE;
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		global $gbSkipHeaderFooter;

		// Check modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["pegawai_id"] <> "") {
				$this->pegawai_id->setQueryStringValue($_GET["pegawai_id"]);
				$this->RecKey["pegawai_id"] = $this->pegawai_id->QueryStringValue;
			} elseif (@$_POST["pegawai_id"] <> "") {
				$this->pegawai_id->setFormValue($_POST["pegawai_id"]);
				$this->RecKey["pegawai_id"] = $this->pegawai_id->FormValue;
			} else {
				$bLoadCurrentRecord = TRUE;
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					$this->StartRec = 1; // Initialize start position
					if ($this->Recordset = $this->LoadRecordset()) // Load records
						$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
					if ($this->TotalRecs <= 0) { // No record found
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$this->Page_Terminate("pegawailist.php"); // Return to list page
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
					if (!$bMatchRecord) {
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "pegawailist.php"; // No matching record, return to list
					} else {
						$this->LoadRowValues($this->Recordset); // Load row values
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "pegawailist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();

		// Set up detail parameters
		$this->SetUpDetailParms();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("ViewPageAddLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->AddUrl) . "',caption:'" . $addcaption . "'});\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$editcaption = ew_HtmlTitle($Language->Phrase("ViewPageEditLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->EditUrl) . "',caption:'" . $editcaption . "'});\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Copy
		$item = &$option->Add("copy");
		$copycaption = ew_HtmlTitle($Language->Phrase("ViewPageCopyLink"));
		if ($this->IsModal) // Modal
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"javascript:void(0);\" onclick=\"ew_ModalDialogShow({lnk:this,url:'" . ew_HtmlEncode($this->CopyUrl) . "',caption:'" . $copycaption . "'});\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		if ($this->IsModal) // Handle as inline delete
			$item->Body = "<a onclick=\"return ew_ConfirmDelete(this);\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode(ew_AddQueryStringToUrl($this->DeleteUrl, "a_delete=1")) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		else
			$item->Body = "<a class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());
		$option = &$options["detail"];
		$DetailTableLink = "";
		$DetailViewTblVar = "";
		$DetailCopyTblVar = "";
		$DetailEditTblVar = "";

		// "detail_pegawai_d"
		$item = &$option->Add("detail_pegawai_d");
		$body = $Language->Phrase("ViewPageDetailLink") . $Language->TablePhrase("pegawai_d", "TblCaption");
		$body = "<a class=\"btn btn-default btn-sm ewRowLink ewDetail\" data-action=\"list\" href=\"" . ew_HtmlEncode("pegawai_dlist.php?" . EW_TABLE_SHOW_MASTER . "=pegawai&fk_pegawai_id=" . urlencode(strval($this->pegawai_id->CurrentValue)) . "") . "\">" . $body . "</a>";
		$links = "";
		if ($GLOBALS["pegawai_d_grid"] && $GLOBALS["pegawai_d_grid"]->DetailView && $Security->CanView() && $Security->AllowView(CurrentProjectID() . 'pegawai_d')) {
			$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=pegawai_d")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			if ($DetailViewTblVar <> "") $DetailViewTblVar .= ",";
			$DetailViewTblVar .= "pegawai_d";
		}
		if ($GLOBALS["pegawai_d_grid"] && $GLOBALS["pegawai_d_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'pegawai_d')) {
			$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=pegawai_d")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			if ($DetailEditTblVar <> "") $DetailEditTblVar .= ",";
			$DetailEditTblVar .= "pegawai_d";
		}
		if ($GLOBALS["pegawai_d_grid"] && $GLOBALS["pegawai_d_grid"]->DetailAdd && $Security->CanAdd() && $Security->AllowAdd(CurrentProjectID() . 'pegawai_d')) {
			$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=pegawai_d")) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			if ($DetailCopyTblVar <> "") $DetailCopyTblVar .= ",";
			$DetailCopyTblVar .= "pegawai_d";
		}
		if ($links <> "") {
			$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewDetail\" data-toggle=\"dropdown\"><b class=\"caret\"></b></button>";
			$body .= "<ul class=\"dropdown-menu\">". $links . "</ul>";
		}
		$body = "<div class=\"btn-group\">" . $body . "</div>";
		$item->Body = $body;
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'pegawai_d');
		if ($item->Visible) {
			if ($DetailTableLink <> "") $DetailTableLink .= ",";
			$DetailTableLink .= "pegawai_d";
		}
		if ($this->ShowMultipleDetails) $item->Visible = FALSE;

		// Multiple details
		if ($this->ShowMultipleDetails) {
			$body = $Language->Phrase("MultipleMasterDetails");
			$body = "<div class=\"btn-group\">";
			$links = "";
			if ($DetailViewTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailView\" data-action=\"view\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailViewLink")) . "\" href=\"" . ew_HtmlEncode($this->GetViewUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailViewTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailViewLink")) . "</a></li>";
			}
			if ($DetailEditTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailEdit\" data-action=\"edit\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailEditLink")) . "\" href=\"" . ew_HtmlEncode($this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailEditTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailEditLink")) . "</a></li>";
			}
			if ($DetailCopyTblVar <> "") {
				$links .= "<li><a class=\"ewRowLink ewDetailCopy\" data-action=\"add\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("MasterDetailCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->GetCopyUrl(EW_TABLE_SHOW_DETAIL . "=" . $DetailCopyTblVar)) . "\">" . ew_HtmlImageAndText($Language->Phrase("MasterDetailCopyLink")) . "</a></li>";
			}
			if ($links <> "") {
				$body .= "<button class=\"dropdown-toggle btn btn-default btn-sm ewMasterDetail\" title=\"" . ew_HtmlTitle($Language->Phrase("MultipleMasterDetails")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("MultipleMasterDetails") . "<b class=\"caret\"></b></button>";
				$body .= "<ul class=\"dropdown-menu ewMenu\">". $links . "</ul>";
			}
			$body .= "</div>";

			// Multiple details
			$oListOpt = &$option->Add("details");
			$oListOpt->Body = $body;
		}

		// Set up detail default
		$option = &$options["detail"];
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$option->UseImageAndText = TRUE;
		$ar = explode(",", $DetailTableLink);
		$cnt = count($ar);
		$option->UseDropDownButton = ($cnt > 1);
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = TRUE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
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
		if ($this->AuditTrailOnView) $this->WriteAuditTrailOnView($row);
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// pegawai_id
		// pegawai_pin
		// pegawai_nip
		// pegawai_nama
		// pegawai_pwd
		// pegawai_rfid
		// pegawai_privilege
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

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = TRUE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = TRUE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = TRUE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_pegawai\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_pegawai',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fpegawaiview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = TRUE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = TRUE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if (!$this->Recordset)
				$this->Recordset = $this->LoadRecordset();
			$rs = &$this->Recordset;
			if ($rs)
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "v");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "view");

		// Export detail records (pegawai_d)
		if (EW_EXPORT_DETAIL_RECORDS && in_array("pegawai_d", explode(",", $this->getCurrentDetailTable()))) {
			global $pegawai_d;
			if (!isset($pegawai_d)) $pegawai_d = new cpegawai_d;
			$rsdetail = $pegawai_d->LoadRs($pegawai_d->GetDetailFilter()); // Load detail records
			if ($rsdetail && !$rsdetail->EOF) {
				$ExportStyle = $Doc->Style;
				$Doc->SetStyle("h"); // Change to horizontal
				if ($this->Export <> "csv" || EW_EXPORT_DETAIL_RECORDS_FOR_CSV) {
					$Doc->ExportEmptyRow();
					$detailcnt = $rsdetail->RecordCount();
					$pegawai_d->ExportDocument($Doc, $rsdetail, 1, $detailcnt);
				}
				$Doc->SetStyle($ExportStyle); // Restore
				$rsdetail->Close();
			}
		}
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Call Page Exported server event
		$this->Page_Exported();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED && $this->Export <> "pdf")
			echo ew_DebugMsg();

		// Output data
		if ($this->Export == "email") {
			echo $this->ExportEmail($Doc->Text);
		} else {
			$Doc->Export();
		}
	}

	// Export email
	function ExportEmail($EmailContent) {
		global $gTmpImages, $Language;
		$sSender = @$_POST["sender"];
		$sRecipient = @$_POST["recipient"];
		$sCc = @$_POST["cc"];
		$sBcc = @$_POST["bcc"];
		$sContentType = @$_POST["contenttype"];

		// Subject
		$sSubject = ew_StripSlashes(@$_POST["subject"]);
		$sEmailSubject = $sSubject;

		// Message
		$sContent = ew_StripSlashes(@$_POST["message"]);
		$sEmailMessage = $sContent;

		// Check sender
		if ($sSender == "") {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterSenderEmail") . "</p>";
		}
		if (!ew_CheckEmail($sSender)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperSenderEmail") . "</p>";
		}

		// Check recipient
		if (!ew_CheckEmailList($sRecipient, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperRecipientEmail") . "</p>";
		}

		// Check cc
		if (!ew_CheckEmailList($sCc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperCcEmail") . "</p>";
		}

		// Check bcc
		if (!ew_CheckEmailList($sBcc, EW_MAX_EMAIL_RECIPIENT)) {
			return "<p class=\"text-danger\">" . $Language->Phrase("EnterProperBccEmail") . "</p>";
		}

		// Check email sent count
		if (!isset($_SESSION[EW_EXPORT_EMAIL_COUNTER]))
			$_SESSION[EW_EXPORT_EMAIL_COUNTER] = 0;
		if (intval($_SESSION[EW_EXPORT_EMAIL_COUNTER]) > EW_MAX_EMAIL_SENT_COUNT) {
			return "<p class=\"text-danger\">" . $Language->Phrase("ExceedMaxEmailExport") . "</p>";
		}

		// Send email
		$Email = new cEmail();
		$Email->Sender = $sSender; // Sender
		$Email->Recipient = $sRecipient; // Recipient
		$Email->Cc = $sCc; // Cc
		$Email->Bcc = $sBcc; // Bcc
		$Email->Subject = $sEmailSubject; // Subject
		$Email->Format = ($sContentType == "url") ? "text" : "html";
		if ($sEmailMessage <> "") {
			$sEmailMessage = ew_RemoveXSS($sEmailMessage);
			$sEmailMessage .= ($sContentType == "url") ? "\r\n\r\n" : "<br><br>";
		}
		if ($sContentType == "url") {
			$sUrl = ew_ConvertFullUrl(ew_CurrentPage() . "?" . $this->ExportQueryString());
			$sEmailMessage .= $sUrl; // Send URL only
		} else {
			foreach ($gTmpImages as $tmpimage)
				$Email->AddEmbeddedImage($tmpimage);
			$sEmailMessage .= ew_CleanEmailContent($EmailContent); // Send HTML
		}
		$Email->Content = $sEmailMessage; // Content
		$EventArgs = array();
		if ($this->Recordset) {
			$this->RecCnt = $this->StartRec - 1;
			$this->Recordset->MoveFirst();
			if ($this->StartRec > 1)
				$this->Recordset->Move($this->StartRec - 1);
			$EventArgs["rs"] = &$this->Recordset;
		}
		$bEmailSent = FALSE;
		if ($this->Email_Sending($Email, $EventArgs))
			$bEmailSent = $Email->Send();

		// Check email sent status
		if ($bEmailSent) {

			// Update email sent count
			$_SESSION[EW_EXPORT_EMAIL_COUNTER]++;

			// Sent email success
			return "<p class=\"text-success\">" . $Language->Phrase("SendEmailSuccess") . "</p>"; // Set up success message
		} else {

			// Sent email failure
			return "<p class=\"text-danger\">" . $Email->SendErrDescription . "</p>";
		}
	}

	// Export QueryString
	function ExportQueryString() {

		// Initialize
		$sQry = "export=html";

		// Add record key QueryString
		$sQry .= "&" . substr($this->KeyUrl("", ""), 1);
		return $sQry;
	}

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			$DetailTblVar = explode(",", $sDetailTblVar);
			if (in_array("pegawai_d", $DetailTblVar)) {
				if (!isset($GLOBALS["pegawai_d_grid"]))
					$GLOBALS["pegawai_d_grid"] = new cpegawai_d_grid;
				if ($GLOBALS["pegawai_d_grid"]->DetailView) {
					$GLOBALS["pegawai_d_grid"]->CurrentMode = "view";

					// Save current master table to detail table
					$GLOBALS["pegawai_d_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["pegawai_d_grid"]->setStartRecordNumber(1);
					$GLOBALS["pegawai_d_grid"]->pegawai_id->FldIsDetailKey = TRUE;
					$GLOBALS["pegawai_d_grid"]->pegawai_id->CurrentValue = $this->pegawai_id->CurrentValue;
					$GLOBALS["pegawai_d_grid"]->pegawai_id->setSessionValue($GLOBALS["pegawai_d_grid"]->pegawai_id->CurrentValue);
				}
			}
		}
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("pegawailist.php"), "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, $url);
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($pegawai_view)) $pegawai_view = new cpegawai_view();

// Page init
$pegawai_view->Page_Init();

// Page main
$pegawai_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pegawai_view->Page_Render();
?>
<?php include_once "header.php" ?>
<?php if ($pegawai->Export == "") { ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "view";
var CurrentForm = fpegawaiview = new ew_Form("fpegawaiview", "view");

// Form_CustomValidate event
fpegawaiview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpegawaiview.ValidateRequired = true;
<?php } else { ?>
fpegawaiview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpegawaiview.Lists["x_pegawai_status"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpegawaiview.Lists["x_pegawai_status"].Options = <?php echo json_encode($pegawai->pegawai_status->Options()) ?>;
fpegawaiview.Lists["x_pembagian1_id"] = {"LinkField":"x_pembagian1_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pembagian1_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pembagian1"};
fpegawaiview.Lists["x_pembagian2_id"] = {"LinkField":"x_pembagian2_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pembagian2_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pembagian2"};
fpegawaiview.Lists["x_pembagian3_id"] = {"LinkField":"x_pembagian3_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_pembagian3_nama","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"pembagian3"};
fpegawaiview.Lists["x_gender"] = {"LinkField":"","Ajax":null,"AutoFill":false,"DisplayFields":["","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};
fpegawaiview.Lists["x_gender"].Options = <?php echo json_encode($pegawai->gender->Options()) ?>;

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($pegawai->Export == "") { ?>
<div class="ewToolbar">
<?php if (!$pegawai_view->IsModal) { ?>
<?php if ($pegawai->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php } ?>
<?php $pegawai_view->ExportOptions->Render("body") ?>
<?php
	foreach ($pegawai_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if (!$pegawai_view->IsModal) { ?>
<?php if ($pegawai->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $pegawai_view->ShowPageHeader(); ?>
<?php
$pegawai_view->ShowMessage();
?>
<?php if (!$pegawai_view->IsModal) { ?>
<?php if ($pegawai->Export == "") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($pegawai_view->Pager)) $pegawai_view->Pager = new cPrevNextPager($pegawai_view->StartRec, $pegawai_view->DisplayRecs, $pegawai_view->TotalRecs) ?>
<?php if ($pegawai_view->Pager->RecordCount > 0 && $pegawai_view->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pegawai_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pegawai_view->PageUrl() ?>start=<?php echo $pegawai_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pegawai_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pegawai_view->PageUrl() ?>start=<?php echo $pegawai_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pegawai_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pegawai_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pegawai_view->PageUrl() ?>start=<?php echo $pegawai_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pegawai_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pegawai_view->PageUrl() ?>start=<?php echo $pegawai_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pegawai_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<?php } ?>
<form name="fpegawaiview" id="fpegawaiview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pegawai_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pegawai_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pegawai">
<?php if ($pegawai_view->IsModal) { ?>
<input type="hidden" name="modal" value="1">
<?php } ?>
<table class="table table-bordered table-striped ewViewTable">
<?php if ($pegawai->pegawai_id->Visible) { // pegawai_id ?>
	<tr id="r_pegawai_id">
		<td><span id="elh_pegawai_pegawai_id"><?php echo $pegawai->pegawai_id->FldCaption() ?></span></td>
		<td data-name="pegawai_id"<?php echo $pegawai->pegawai_id->CellAttributes() ?>>
<span id="el_pegawai_pegawai_id">
<span<?php echo $pegawai->pegawai_id->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pegawai->pegawai_pin->Visible) { // pegawai_pin ?>
	<tr id="r_pegawai_pin">
		<td><span id="elh_pegawai_pegawai_pin"><?php echo $pegawai->pegawai_pin->FldCaption() ?></span></td>
		<td data-name="pegawai_pin"<?php echo $pegawai->pegawai_pin->CellAttributes() ?>>
<span id="el_pegawai_pegawai_pin">
<span<?php echo $pegawai->pegawai_pin->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_pin->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pegawai->pegawai_nip->Visible) { // pegawai_nip ?>
	<tr id="r_pegawai_nip">
		<td><span id="elh_pegawai_pegawai_nip"><?php echo $pegawai->pegawai_nip->FldCaption() ?></span></td>
		<td data-name="pegawai_nip"<?php echo $pegawai->pegawai_nip->CellAttributes() ?>>
<span id="el_pegawai_pegawai_nip">
<span<?php echo $pegawai->pegawai_nip->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_nip->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pegawai->pegawai_nama->Visible) { // pegawai_nama ?>
	<tr id="r_pegawai_nama">
		<td><span id="elh_pegawai_pegawai_nama"><?php echo $pegawai->pegawai_nama->FldCaption() ?></span></td>
		<td data-name="pegawai_nama"<?php echo $pegawai->pegawai_nama->CellAttributes() ?>>
<span id="el_pegawai_pegawai_nama">
<span<?php echo $pegawai->pegawai_nama->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_nama->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pegawai->pegawai_telp->Visible) { // pegawai_telp ?>
	<tr id="r_pegawai_telp">
		<td><span id="elh_pegawai_pegawai_telp"><?php echo $pegawai->pegawai_telp->FldCaption() ?></span></td>
		<td data-name="pegawai_telp"<?php echo $pegawai->pegawai_telp->CellAttributes() ?>>
<span id="el_pegawai_pegawai_telp">
<span<?php echo $pegawai->pegawai_telp->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_telp->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pegawai->pegawai_status->Visible) { // pegawai_status ?>
	<tr id="r_pegawai_status">
		<td><span id="elh_pegawai_pegawai_status"><?php echo $pegawai->pegawai_status->FldCaption() ?></span></td>
		<td data-name="pegawai_status"<?php echo $pegawai->pegawai_status->CellAttributes() ?>>
<span id="el_pegawai_pegawai_status">
<span<?php echo $pegawai->pegawai_status->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pegawai->tempat_lahir->Visible) { // tempat_lahir ?>
	<tr id="r_tempat_lahir">
		<td><span id="elh_pegawai_tempat_lahir"><?php echo $pegawai->tempat_lahir->FldCaption() ?></span></td>
		<td data-name="tempat_lahir"<?php echo $pegawai->tempat_lahir->CellAttributes() ?>>
<span id="el_pegawai_tempat_lahir">
<span<?php echo $pegawai->tempat_lahir->ViewAttributes() ?>>
<?php echo $pegawai->tempat_lahir->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pegawai->tgl_lahir->Visible) { // tgl_lahir ?>
	<tr id="r_tgl_lahir">
		<td><span id="elh_pegawai_tgl_lahir"><?php echo $pegawai->tgl_lahir->FldCaption() ?></span></td>
		<td data-name="tgl_lahir"<?php echo $pegawai->tgl_lahir->CellAttributes() ?>>
<span id="el_pegawai_tgl_lahir">
<span<?php echo $pegawai->tgl_lahir->ViewAttributes() ?>>
<?php echo $pegawai->tgl_lahir->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pegawai->pembagian1_id->Visible) { // pembagian1_id ?>
	<tr id="r_pembagian1_id">
		<td><span id="elh_pegawai_pembagian1_id"><?php echo $pegawai->pembagian1_id->FldCaption() ?></span></td>
		<td data-name="pembagian1_id"<?php echo $pegawai->pembagian1_id->CellAttributes() ?>>
<span id="el_pegawai_pembagian1_id">
<span<?php echo $pegawai->pembagian1_id->ViewAttributes() ?>>
<?php echo $pegawai->pembagian1_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pegawai->pembagian2_id->Visible) { // pembagian2_id ?>
	<tr id="r_pembagian2_id">
		<td><span id="elh_pegawai_pembagian2_id"><?php echo $pegawai->pembagian2_id->FldCaption() ?></span></td>
		<td data-name="pembagian2_id"<?php echo $pegawai->pembagian2_id->CellAttributes() ?>>
<span id="el_pegawai_pembagian2_id">
<span<?php echo $pegawai->pembagian2_id->ViewAttributes() ?>>
<?php echo $pegawai->pembagian2_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pegawai->pembagian3_id->Visible) { // pembagian3_id ?>
	<tr id="r_pembagian3_id">
		<td><span id="elh_pegawai_pembagian3_id"><?php echo $pegawai->pembagian3_id->FldCaption() ?></span></td>
		<td data-name="pembagian3_id"<?php echo $pegawai->pembagian3_id->CellAttributes() ?>>
<span id="el_pegawai_pembagian3_id">
<span<?php echo $pegawai->pembagian3_id->ViewAttributes() ?>>
<?php echo $pegawai->pembagian3_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pegawai->tgl_mulai_kerja->Visible) { // tgl_mulai_kerja ?>
	<tr id="r_tgl_mulai_kerja">
		<td><span id="elh_pegawai_tgl_mulai_kerja"><?php echo $pegawai->tgl_mulai_kerja->FldCaption() ?></span></td>
		<td data-name="tgl_mulai_kerja"<?php echo $pegawai->tgl_mulai_kerja->CellAttributes() ?>>
<span id="el_pegawai_tgl_mulai_kerja">
<span<?php echo $pegawai->tgl_mulai_kerja->ViewAttributes() ?>>
<?php echo $pegawai->tgl_mulai_kerja->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pegawai->tgl_resign->Visible) { // tgl_resign ?>
	<tr id="r_tgl_resign">
		<td><span id="elh_pegawai_tgl_resign"><?php echo $pegawai->tgl_resign->FldCaption() ?></span></td>
		<td data-name="tgl_resign"<?php echo $pegawai->tgl_resign->CellAttributes() ?>>
<span id="el_pegawai_tgl_resign">
<span<?php echo $pegawai->tgl_resign->ViewAttributes() ?>>
<?php echo $pegawai->tgl_resign->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pegawai->gender->Visible) { // gender ?>
	<tr id="r_gender">
		<td><span id="elh_pegawai_gender"><?php echo $pegawai->gender->FldCaption() ?></span></td>
		<td data-name="gender"<?php echo $pegawai->gender->CellAttributes() ?>>
<span id="el_pegawai_gender">
<span<?php echo $pegawai->gender->ViewAttributes() ?>>
<?php echo $pegawai->gender->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pegawai->tgl_masuk_pertama->Visible) { // tgl_masuk_pertama ?>
	<tr id="r_tgl_masuk_pertama">
		<td><span id="elh_pegawai_tgl_masuk_pertama"><?php echo $pegawai->tgl_masuk_pertama->FldCaption() ?></span></td>
		<td data-name="tgl_masuk_pertama"<?php echo $pegawai->tgl_masuk_pertama->CellAttributes() ?>>
<span id="el_pegawai_tgl_masuk_pertama">
<span<?php echo $pegawai->tgl_masuk_pertama->ViewAttributes() ?>>
<?php echo $pegawai->tgl_masuk_pertama->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pegawai->photo_path->Visible) { // photo_path ?>
	<tr id="r_photo_path">
		<td><span id="elh_pegawai_photo_path"><?php echo $pegawai->photo_path->FldCaption() ?></span></td>
		<td data-name="photo_path"<?php echo $pegawai->photo_path->CellAttributes() ?>>
<span id="el_pegawai_photo_path">
<span<?php echo $pegawai->photo_path->ViewAttributes() ?>>
<?php echo ew_GetFileViewTag($pegawai->photo_path, $pegawai->photo_path->ViewValue) ?>
</span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pegawai->nama_bank->Visible) { // nama_bank ?>
	<tr id="r_nama_bank">
		<td><span id="elh_pegawai_nama_bank"><?php echo $pegawai->nama_bank->FldCaption() ?></span></td>
		<td data-name="nama_bank"<?php echo $pegawai->nama_bank->CellAttributes() ?>>
<span id="el_pegawai_nama_bank">
<span<?php echo $pegawai->nama_bank->ViewAttributes() ?>>
<?php echo $pegawai->nama_bank->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pegawai->nama_rek->Visible) { // nama_rek ?>
	<tr id="r_nama_rek">
		<td><span id="elh_pegawai_nama_rek"><?php echo $pegawai->nama_rek->FldCaption() ?></span></td>
		<td data-name="nama_rek"<?php echo $pegawai->nama_rek->CellAttributes() ?>>
<span id="el_pegawai_nama_rek">
<span<?php echo $pegawai->nama_rek->ViewAttributes() ?>>
<?php echo $pegawai->nama_rek->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pegawai->no_rek->Visible) { // no_rek ?>
	<tr id="r_no_rek">
		<td><span id="elh_pegawai_no_rek"><?php echo $pegawai->no_rek->FldCaption() ?></span></td>
		<td data-name="no_rek"<?php echo $pegawai->no_rek->CellAttributes() ?>>
<span id="el_pegawai_no_rek">
<span<?php echo $pegawai->no_rek->ViewAttributes() ?>>
<?php echo $pegawai->no_rek->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if (!$pegawai_view->IsModal) { ?>
<?php if ($pegawai->Export == "") { ?>
<?php if (!isset($pegawai_view->Pager)) $pegawai_view->Pager = new cPrevNextPager($pegawai_view->StartRec, $pegawai_view->DisplayRecs, $pegawai_view->TotalRecs) ?>
<?php if ($pegawai_view->Pager->RecordCount > 0 && $pegawai_view->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($pegawai_view->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $pegawai_view->PageUrl() ?>start=<?php echo $pegawai_view->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($pegawai_view->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $pegawai_view->PageUrl() ?>start=<?php echo $pegawai_view->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $pegawai_view->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($pegawai_view->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $pegawai_view->PageUrl() ?>start=<?php echo $pegawai_view->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($pegawai_view->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $pegawai_view->PageUrl() ?>start=<?php echo $pegawai_view->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $pegawai_view->Pager->PageCount ?></span>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
<?php } ?>
<?php
	if (in_array("pegawai_d", explode(",", $pegawai->getCurrentDetailTable())) && $pegawai_d->DetailView) {
?>
<?php if ($pegawai->getCurrentDetailTable() <> "") { ?>
<h4 class="ewDetailCaption"><?php echo $Language->TablePhrase("pegawai_d", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "pegawai_dgrid.php" ?>
<?php } ?>
</form>
<?php if ($pegawai->Export == "") { ?>
<script type="text/javascript">
fpegawaiview.Init();
</script>
<?php } ?>
<?php
$pegawai_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($pegawai->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$pegawai_view->Page_Terminate();
?>
