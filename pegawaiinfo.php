<?php

// Global variable for table object
$pegawai = NULL;

//
// Table class for pegawai
//
class cpegawai extends cTable {
	var $AuditTrailOnAdd = TRUE;
	var $AuditTrailOnEdit = TRUE;
	var $AuditTrailOnDelete = TRUE;
	var $AuditTrailOnView = FALSE;
	var $AuditTrailOnViewData = FALSE;
	var $AuditTrailOnSearch = FALSE;
	var $pegawai_id;
	var $pegawai_pin;
	var $pegawai_nip;
	var $pegawai_nama;
	var $pegawai_pwd;
	var $pegawai_rfid;
	var $pegawai_privilege;
	var $pegawai_telp;
	var $pegawai_status;
	var $tempat_lahir;
	var $tgl_lahir;
	var $pembagian1_id;
	var $pembagian2_id;
	var $pembagian3_id;
	var $tgl_mulai_kerja;
	var $tgl_resign;
	var $gender;
	var $tgl_masuk_pertama;
	var $photo_path;
	var $tmp_img;
	var $nama_bank;
	var $nama_rek;
	var $no_rek;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'pegawai';
		$this->TableName = 'pegawai';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`pegawai`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// pegawai_id
		$this->pegawai_id = new cField('pegawai', 'pegawai', 'x_pegawai_id', 'pegawai_id', '`pegawai_id`', '`pegawai_id`', 3, -1, FALSE, '`pegawai_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pegawai_id->Sortable = TRUE; // Allow sort
		$this->pegawai_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pegawai_id'] = &$this->pegawai_id;

		// pegawai_pin
		$this->pegawai_pin = new cField('pegawai', 'pegawai', 'x_pegawai_pin', 'pegawai_pin', '`pegawai_pin`', '`pegawai_pin`', 200, -1, FALSE, '`pegawai_pin`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pegawai_pin->Sortable = TRUE; // Allow sort
		$this->fields['pegawai_pin'] = &$this->pegawai_pin;

		// pegawai_nip
		$this->pegawai_nip = new cField('pegawai', 'pegawai', 'x_pegawai_nip', 'pegawai_nip', '`pegawai_nip`', '`pegawai_nip`', 200, -1, FALSE, '`pegawai_nip`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pegawai_nip->Sortable = TRUE; // Allow sort
		$this->fields['pegawai_nip'] = &$this->pegawai_nip;

		// pegawai_nama
		$this->pegawai_nama = new cField('pegawai', 'pegawai', 'x_pegawai_nama', 'pegawai_nama', '`pegawai_nama`', '`pegawai_nama`', 200, -1, FALSE, '`pegawai_nama`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pegawai_nama->Sortable = TRUE; // Allow sort
		$this->fields['pegawai_nama'] = &$this->pegawai_nama;

		// pegawai_pwd
		$this->pegawai_pwd = new cField('pegawai', 'pegawai', 'x_pegawai_pwd', 'pegawai_pwd', '`pegawai_pwd`', '`pegawai_pwd`', 200, -1, FALSE, '`pegawai_pwd`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pegawai_pwd->Sortable = TRUE; // Allow sort
		$this->fields['pegawai_pwd'] = &$this->pegawai_pwd;

		// pegawai_rfid
		$this->pegawai_rfid = new cField('pegawai', 'pegawai', 'x_pegawai_rfid', 'pegawai_rfid', '`pegawai_rfid`', '`pegawai_rfid`', 200, -1, FALSE, '`pegawai_rfid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pegawai_rfid->Sortable = TRUE; // Allow sort
		$this->fields['pegawai_rfid'] = &$this->pegawai_rfid;

		// pegawai_privilege
		$this->pegawai_privilege = new cField('pegawai', 'pegawai', 'x_pegawai_privilege', 'pegawai_privilege', '`pegawai_privilege`', '`pegawai_privilege`', 200, -1, FALSE, '`pegawai_privilege`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pegawai_privilege->Sortable = TRUE; // Allow sort
		$this->fields['pegawai_privilege'] = &$this->pegawai_privilege;

		// pegawai_telp
		$this->pegawai_telp = new cField('pegawai', 'pegawai', 'x_pegawai_telp', 'pegawai_telp', '`pegawai_telp`', '`pegawai_telp`', 200, -1, FALSE, '`pegawai_telp`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pegawai_telp->Sortable = TRUE; // Allow sort
		$this->fields['pegawai_telp'] = &$this->pegawai_telp;

		// pegawai_status
		$this->pegawai_status = new cField('pegawai', 'pegawai', 'x_pegawai_status', 'pegawai_status', '`pegawai_status`', '`pegawai_status`', 16, -1, FALSE, '`pegawai_status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pegawai_status->Sortable = TRUE; // Allow sort
		$this->pegawai_status->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pegawai_status'] = &$this->pegawai_status;

		// tempat_lahir
		$this->tempat_lahir = new cField('pegawai', 'pegawai', 'x_tempat_lahir', 'tempat_lahir', '`tempat_lahir`', '`tempat_lahir`', 200, -1, FALSE, '`tempat_lahir`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tempat_lahir->Sortable = TRUE; // Allow sort
		$this->fields['tempat_lahir'] = &$this->tempat_lahir;

		// tgl_lahir
		$this->tgl_lahir = new cField('pegawai', 'pegawai', 'x_tgl_lahir', 'tgl_lahir', '`tgl_lahir`', ew_CastDateFieldForLike('`tgl_lahir`', 0, "DB"), 133, 0, FALSE, '`tgl_lahir`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tgl_lahir->Sortable = TRUE; // Allow sort
		$this->tgl_lahir->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tgl_lahir'] = &$this->tgl_lahir;

		// pembagian1_id
		$this->pembagian1_id = new cField('pegawai', 'pegawai', 'x_pembagian1_id', 'pembagian1_id', '`pembagian1_id`', '`pembagian1_id`', 3, -1, FALSE, '`pembagian1_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pembagian1_id->Sortable = TRUE; // Allow sort
		$this->pembagian1_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pembagian1_id'] = &$this->pembagian1_id;

		// pembagian2_id
		$this->pembagian2_id = new cField('pegawai', 'pegawai', 'x_pembagian2_id', 'pembagian2_id', '`pembagian2_id`', '`pembagian2_id`', 3, -1, FALSE, '`pembagian2_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pembagian2_id->Sortable = TRUE; // Allow sort
		$this->pembagian2_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pembagian2_id'] = &$this->pembagian2_id;

		// pembagian3_id
		$this->pembagian3_id = new cField('pegawai', 'pegawai', 'x_pembagian3_id', 'pembagian3_id', '`pembagian3_id`', '`pembagian3_id`', 3, -1, FALSE, '`pembagian3_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->pembagian3_id->Sortable = TRUE; // Allow sort
		$this->pembagian3_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['pembagian3_id'] = &$this->pembagian3_id;

		// tgl_mulai_kerja
		$this->tgl_mulai_kerja = new cField('pegawai', 'pegawai', 'x_tgl_mulai_kerja', 'tgl_mulai_kerja', '`tgl_mulai_kerja`', ew_CastDateFieldForLike('`tgl_mulai_kerja`', 0, "DB"), 133, 0, FALSE, '`tgl_mulai_kerja`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tgl_mulai_kerja->Sortable = TRUE; // Allow sort
		$this->tgl_mulai_kerja->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tgl_mulai_kerja'] = &$this->tgl_mulai_kerja;

		// tgl_resign
		$this->tgl_resign = new cField('pegawai', 'pegawai', 'x_tgl_resign', 'tgl_resign', '`tgl_resign`', ew_CastDateFieldForLike('`tgl_resign`', 0, "DB"), 133, 0, FALSE, '`tgl_resign`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tgl_resign->Sortable = TRUE; // Allow sort
		$this->tgl_resign->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tgl_resign'] = &$this->tgl_resign;

		// gender
		$this->gender = new cField('pegawai', 'pegawai', 'x_gender', 'gender', '`gender`', '`gender`', 16, -1, FALSE, '`gender`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->gender->Sortable = TRUE; // Allow sort
		$this->gender->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['gender'] = &$this->gender;

		// tgl_masuk_pertama
		$this->tgl_masuk_pertama = new cField('pegawai', 'pegawai', 'x_tgl_masuk_pertama', 'tgl_masuk_pertama', '`tgl_masuk_pertama`', ew_CastDateFieldForLike('`tgl_masuk_pertama`', 0, "DB"), 133, 0, FALSE, '`tgl_masuk_pertama`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->tgl_masuk_pertama->Sortable = TRUE; // Allow sort
		$this->tgl_masuk_pertama->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['tgl_masuk_pertama'] = &$this->tgl_masuk_pertama;

		// photo_path
		$this->photo_path = new cField('pegawai', 'pegawai', 'x_photo_path', 'photo_path', '`photo_path`', '`photo_path`', 200, -1, FALSE, '`photo_path`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->photo_path->Sortable = TRUE; // Allow sort
		$this->fields['photo_path'] = &$this->photo_path;

		// tmp_img
		$this->tmp_img = new cField('pegawai', 'pegawai', 'x_tmp_img', 'tmp_img', '`tmp_img`', '`tmp_img`', 201, -1, FALSE, '`tmp_img`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->tmp_img->Sortable = TRUE; // Allow sort
		$this->fields['tmp_img'] = &$this->tmp_img;

		// nama_bank
		$this->nama_bank = new cField('pegawai', 'pegawai', 'x_nama_bank', 'nama_bank', '`nama_bank`', '`nama_bank`', 200, -1, FALSE, '`nama_bank`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nama_bank->Sortable = TRUE; // Allow sort
		$this->fields['nama_bank'] = &$this->nama_bank;

		// nama_rek
		$this->nama_rek = new cField('pegawai', 'pegawai', 'x_nama_rek', 'nama_rek', '`nama_rek`', '`nama_rek`', 200, -1, FALSE, '`nama_rek`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->nama_rek->Sortable = TRUE; // Allow sort
		$this->fields['nama_rek'] = &$this->nama_rek;

		// no_rek
		$this->no_rek = new cField('pegawai', 'pegawai', 'x_no_rek', 'no_rek', '`no_rek`', '`no_rek`', 200, -1, FALSE, '`no_rek`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->no_rek->Sortable = TRUE; // Allow sort
		$this->fields['no_rek'] = &$this->no_rek;
	}

	// Set Field Visibility
	function SetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Multiple column sort
	function UpdateSort(&$ofld, $ctrl) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			if ($ctrl) {
				$sOrderBy = $this->getSessionOrderBy();
				if (strpos($sOrderBy, $sSortField . " " . $sLastSort) !== FALSE) {
					$sOrderBy = str_replace($sSortField . " " . $sLastSort, $sSortField . " " . $sThisSort, $sOrderBy);
				} else {
					if ($sOrderBy <> "") $sOrderBy .= ", ";
					$sOrderBy .= $sSortField . " " . $sThisSort;
				}
				$this->setSessionOrderBy($sOrderBy); // Save to Session
			} else {
				$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
			}
		} else {
			if (!$ctrl) $ofld->setSort("");
		}
	}

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "pegawai_d") {
			$sDetailUrl = $GLOBALS["pegawai_d"]->GetListUrl() . "?" . EW_TABLE_SHOW_MASTER . "=" . $this->TableVar;
			$sDetailUrl .= "&fk_pegawai_id=" . urlencode($this->pegawai_id->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "pegawailist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`pegawai`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {
			if ($this->AuditTrailOnAdd)
				$this->WriteAuditTrailOnAdd($rs);
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		if ($bUpdate && $this->AuditTrailOnEdit) {
			$rsaudit = $rs;
			$fldname = 'pegawai_id';
			if (!array_key_exists($fldname, $rsaudit)) $rsaudit[$fldname] = $rsold[$fldname];
			$this->WriteAuditTrailOnEdit($rsaudit, $rsold);
		}
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('pegawai_id', $rs))
				ew_AddFilter($where, ew_QuotedName('pegawai_id', $this->DBID) . '=' . ew_QuotedValue($rs['pegawai_id'], $this->pegawai_id->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		if ($bDelete && $this->AuditTrailOnDelete)
			$this->WriteAuditTrailOnDelete($rs);
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`pegawai_id` = @pegawai_id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->pegawai_id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@pegawai_id@", ew_AdjustSql($this->pegawai_id->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "pegawailist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "pegawailist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("pegawaiview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("pegawaiview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "pegawaiadd.php?" . $this->UrlParm($parm);
		else
			$url = "pegawaiadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("pegawaiedit.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("pegawaiedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("pegawaiadd.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("pegawaiadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("pegawaidelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "pegawai_id:" . ew_VarToJson($this->pegawai_id->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->pegawai_id->CurrentValue)) {
			$sUrl .= "pegawai_id=" . urlencode($this->pegawai_id->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			if ($isPost && isset($_POST["pegawai_id"]))
				$arKeys[] = ew_StripSlashes($_POST["pegawai_id"]);
			elseif (isset($_GET["pegawai_id"]))
				$arKeys[] = ew_StripSlashes($_GET["pegawai_id"]);
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->pegawai_id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
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
		$this->pembagian2_id->setDbValue($rs->fields('pembagian2_id'));
		$this->pembagian3_id->setDbValue($rs->fields('pembagian3_id'));
		$this->tgl_mulai_kerja->setDbValue($rs->fields('tgl_mulai_kerja'));
		$this->tgl_resign->setDbValue($rs->fields('tgl_resign'));
		$this->gender->setDbValue($rs->fields('gender'));
		$this->tgl_masuk_pertama->setDbValue($rs->fields('tgl_masuk_pertama'));
		$this->photo_path->setDbValue($rs->fields('photo_path'));
		$this->tmp_img->setDbValue($rs->fields('tmp_img'));
		$this->nama_bank->setDbValue($rs->fields('nama_bank'));
		$this->nama_rek->setDbValue($rs->fields('nama_rek'));
		$this->no_rek->setDbValue($rs->fields('no_rek'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// pegawai_pwd
		$this->pegawai_pwd->ViewValue = $this->pegawai_pwd->CurrentValue;
		$this->pegawai_pwd->ViewCustomAttributes = "";

		// pegawai_rfid
		$this->pegawai_rfid->ViewValue = $this->pegawai_rfid->CurrentValue;
		$this->pegawai_rfid->ViewCustomAttributes = "";

		// pegawai_privilege
		$this->pegawai_privilege->ViewValue = $this->pegawai_privilege->CurrentValue;
		$this->pegawai_privilege->ViewCustomAttributes = "";

		// pegawai_telp
		$this->pegawai_telp->ViewValue = $this->pegawai_telp->CurrentValue;
		$this->pegawai_telp->ViewCustomAttributes = "";

		// pegawai_status
		$this->pegawai_status->ViewValue = $this->pegawai_status->CurrentValue;
		$this->pegawai_status->ViewCustomAttributes = "";

		// tempat_lahir
		$this->tempat_lahir->ViewValue = $this->tempat_lahir->CurrentValue;
		$this->tempat_lahir->ViewCustomAttributes = "";

		// tgl_lahir
		$this->tgl_lahir->ViewValue = $this->tgl_lahir->CurrentValue;
		$this->tgl_lahir->ViewValue = ew_FormatDateTime($this->tgl_lahir->ViewValue, 0);
		$this->tgl_lahir->ViewCustomAttributes = "";

		// pembagian1_id
		$this->pembagian1_id->ViewValue = $this->pembagian1_id->CurrentValue;
		$this->pembagian1_id->ViewCustomAttributes = "";

		// pembagian2_id
		$this->pembagian2_id->ViewValue = $this->pembagian2_id->CurrentValue;
		$this->pembagian2_id->ViewCustomAttributes = "";

		// pembagian3_id
		$this->pembagian3_id->ViewValue = $this->pembagian3_id->CurrentValue;
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
		$this->gender->ViewValue = $this->gender->CurrentValue;
		$this->gender->ViewCustomAttributes = "";

		// tgl_masuk_pertama
		$this->tgl_masuk_pertama->ViewValue = $this->tgl_masuk_pertama->CurrentValue;
		$this->tgl_masuk_pertama->ViewValue = ew_FormatDateTime($this->tgl_masuk_pertama->ViewValue, 0);
		$this->tgl_masuk_pertama->ViewCustomAttributes = "";

		// photo_path
		$this->photo_path->ViewValue = $this->photo_path->CurrentValue;
		$this->photo_path->ViewCustomAttributes = "";

		// tmp_img
		$this->tmp_img->ViewValue = $this->tmp_img->CurrentValue;
		$this->tmp_img->ViewCustomAttributes = "";

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

		// pegawai_pwd
		$this->pegawai_pwd->LinkCustomAttributes = "";
		$this->pegawai_pwd->HrefValue = "";
		$this->pegawai_pwd->TooltipValue = "";

		// pegawai_rfid
		$this->pegawai_rfid->LinkCustomAttributes = "";
		$this->pegawai_rfid->HrefValue = "";
		$this->pegawai_rfid->TooltipValue = "";

		// pegawai_privilege
		$this->pegawai_privilege->LinkCustomAttributes = "";
		$this->pegawai_privilege->HrefValue = "";
		$this->pegawai_privilege->TooltipValue = "";

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
		$this->photo_path->TooltipValue = "";

		// tmp_img
		$this->tmp_img->LinkCustomAttributes = "";
		$this->tmp_img->HrefValue = "";
		$this->tmp_img->TooltipValue = "";

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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// pegawai_id
		$this->pegawai_id->EditAttrs["class"] = "form-control";
		$this->pegawai_id->EditCustomAttributes = "";
		$this->pegawai_id->EditValue = $this->pegawai_id->CurrentValue;
		$this->pegawai_id->ViewCustomAttributes = "";

		// pegawai_pin
		$this->pegawai_pin->EditAttrs["class"] = "form-control";
		$this->pegawai_pin->EditCustomAttributes = "";
		$this->pegawai_pin->EditValue = $this->pegawai_pin->CurrentValue;
		$this->pegawai_pin->PlaceHolder = ew_RemoveHtml($this->pegawai_pin->FldCaption());

		// pegawai_nip
		$this->pegawai_nip->EditAttrs["class"] = "form-control";
		$this->pegawai_nip->EditCustomAttributes = "";
		$this->pegawai_nip->EditValue = $this->pegawai_nip->CurrentValue;
		$this->pegawai_nip->PlaceHolder = ew_RemoveHtml($this->pegawai_nip->FldCaption());

		// pegawai_nama
		$this->pegawai_nama->EditAttrs["class"] = "form-control";
		$this->pegawai_nama->EditCustomAttributes = "";
		$this->pegawai_nama->EditValue = $this->pegawai_nama->CurrentValue;
		$this->pegawai_nama->PlaceHolder = ew_RemoveHtml($this->pegawai_nama->FldCaption());

		// pegawai_pwd
		$this->pegawai_pwd->EditAttrs["class"] = "form-control";
		$this->pegawai_pwd->EditCustomAttributes = "";
		$this->pegawai_pwd->EditValue = $this->pegawai_pwd->CurrentValue;
		$this->pegawai_pwd->PlaceHolder = ew_RemoveHtml($this->pegawai_pwd->FldCaption());

		// pegawai_rfid
		$this->pegawai_rfid->EditAttrs["class"] = "form-control";
		$this->pegawai_rfid->EditCustomAttributes = "";
		$this->pegawai_rfid->EditValue = $this->pegawai_rfid->CurrentValue;
		$this->pegawai_rfid->PlaceHolder = ew_RemoveHtml($this->pegawai_rfid->FldCaption());

		// pegawai_privilege
		$this->pegawai_privilege->EditAttrs["class"] = "form-control";
		$this->pegawai_privilege->EditCustomAttributes = "";
		$this->pegawai_privilege->EditValue = $this->pegawai_privilege->CurrentValue;
		$this->pegawai_privilege->PlaceHolder = ew_RemoveHtml($this->pegawai_privilege->FldCaption());

		// pegawai_telp
		$this->pegawai_telp->EditAttrs["class"] = "form-control";
		$this->pegawai_telp->EditCustomAttributes = "";
		$this->pegawai_telp->EditValue = $this->pegawai_telp->CurrentValue;
		$this->pegawai_telp->PlaceHolder = ew_RemoveHtml($this->pegawai_telp->FldCaption());

		// pegawai_status
		$this->pegawai_status->EditAttrs["class"] = "form-control";
		$this->pegawai_status->EditCustomAttributes = "";
		$this->pegawai_status->EditValue = $this->pegawai_status->CurrentValue;
		$this->pegawai_status->PlaceHolder = ew_RemoveHtml($this->pegawai_status->FldCaption());

		// tempat_lahir
		$this->tempat_lahir->EditAttrs["class"] = "form-control";
		$this->tempat_lahir->EditCustomAttributes = "";
		$this->tempat_lahir->EditValue = $this->tempat_lahir->CurrentValue;
		$this->tempat_lahir->PlaceHolder = ew_RemoveHtml($this->tempat_lahir->FldCaption());

		// tgl_lahir
		$this->tgl_lahir->EditAttrs["class"] = "form-control";
		$this->tgl_lahir->EditCustomAttributes = "";
		$this->tgl_lahir->EditValue = ew_FormatDateTime($this->tgl_lahir->CurrentValue, 8);
		$this->tgl_lahir->PlaceHolder = ew_RemoveHtml($this->tgl_lahir->FldCaption());

		// pembagian1_id
		$this->pembagian1_id->EditAttrs["class"] = "form-control";
		$this->pembagian1_id->EditCustomAttributes = "";
		$this->pembagian1_id->EditValue = $this->pembagian1_id->CurrentValue;
		$this->pembagian1_id->PlaceHolder = ew_RemoveHtml($this->pembagian1_id->FldCaption());

		// pembagian2_id
		$this->pembagian2_id->EditAttrs["class"] = "form-control";
		$this->pembagian2_id->EditCustomAttributes = "";
		$this->pembagian2_id->EditValue = $this->pembagian2_id->CurrentValue;
		$this->pembagian2_id->PlaceHolder = ew_RemoveHtml($this->pembagian2_id->FldCaption());

		// pembagian3_id
		$this->pembagian3_id->EditAttrs["class"] = "form-control";
		$this->pembagian3_id->EditCustomAttributes = "";
		$this->pembagian3_id->EditValue = $this->pembagian3_id->CurrentValue;
		$this->pembagian3_id->PlaceHolder = ew_RemoveHtml($this->pembagian3_id->FldCaption());

		// tgl_mulai_kerja
		$this->tgl_mulai_kerja->EditAttrs["class"] = "form-control";
		$this->tgl_mulai_kerja->EditCustomAttributes = "";
		$this->tgl_mulai_kerja->EditValue = ew_FormatDateTime($this->tgl_mulai_kerja->CurrentValue, 8);
		$this->tgl_mulai_kerja->PlaceHolder = ew_RemoveHtml($this->tgl_mulai_kerja->FldCaption());

		// tgl_resign
		$this->tgl_resign->EditAttrs["class"] = "form-control";
		$this->tgl_resign->EditCustomAttributes = "";
		$this->tgl_resign->EditValue = ew_FormatDateTime($this->tgl_resign->CurrentValue, 8);
		$this->tgl_resign->PlaceHolder = ew_RemoveHtml($this->tgl_resign->FldCaption());

		// gender
		$this->gender->EditAttrs["class"] = "form-control";
		$this->gender->EditCustomAttributes = "";
		$this->gender->EditValue = $this->gender->CurrentValue;
		$this->gender->PlaceHolder = ew_RemoveHtml($this->gender->FldCaption());

		// tgl_masuk_pertama
		$this->tgl_masuk_pertama->EditAttrs["class"] = "form-control";
		$this->tgl_masuk_pertama->EditCustomAttributes = "";
		$this->tgl_masuk_pertama->EditValue = ew_FormatDateTime($this->tgl_masuk_pertama->CurrentValue, 8);
		$this->tgl_masuk_pertama->PlaceHolder = ew_RemoveHtml($this->tgl_masuk_pertama->FldCaption());

		// photo_path
		$this->photo_path->EditAttrs["class"] = "form-control";
		$this->photo_path->EditCustomAttributes = "";
		$this->photo_path->EditValue = $this->photo_path->CurrentValue;
		$this->photo_path->PlaceHolder = ew_RemoveHtml($this->photo_path->FldCaption());

		// tmp_img
		$this->tmp_img->EditAttrs["class"] = "form-control";
		$this->tmp_img->EditCustomAttributes = "";
		$this->tmp_img->EditValue = $this->tmp_img->CurrentValue;
		$this->tmp_img->PlaceHolder = ew_RemoveHtml($this->tmp_img->FldCaption());

		// nama_bank
		$this->nama_bank->EditAttrs["class"] = "form-control";
		$this->nama_bank->EditCustomAttributes = "";
		$this->nama_bank->EditValue = $this->nama_bank->CurrentValue;
		$this->nama_bank->PlaceHolder = ew_RemoveHtml($this->nama_bank->FldCaption());

		// nama_rek
		$this->nama_rek->EditAttrs["class"] = "form-control";
		$this->nama_rek->EditCustomAttributes = "";
		$this->nama_rek->EditValue = $this->nama_rek->CurrentValue;
		$this->nama_rek->PlaceHolder = ew_RemoveHtml($this->nama_rek->FldCaption());

		// no_rek
		$this->no_rek->EditAttrs["class"] = "form-control";
		$this->no_rek->EditCustomAttributes = "";
		$this->no_rek->EditValue = $this->no_rek->CurrentValue;
		$this->no_rek->PlaceHolder = ew_RemoveHtml($this->no_rek->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->pegawai_id->Exportable) $Doc->ExportCaption($this->pegawai_id);
					if ($this->pegawai_pin->Exportable) $Doc->ExportCaption($this->pegawai_pin);
					if ($this->pegawai_nip->Exportable) $Doc->ExportCaption($this->pegawai_nip);
					if ($this->pegawai_nama->Exportable) $Doc->ExportCaption($this->pegawai_nama);
					if ($this->pegawai_pwd->Exportable) $Doc->ExportCaption($this->pegawai_pwd);
					if ($this->pegawai_rfid->Exportable) $Doc->ExportCaption($this->pegawai_rfid);
					if ($this->pegawai_privilege->Exportable) $Doc->ExportCaption($this->pegawai_privilege);
					if ($this->pegawai_telp->Exportable) $Doc->ExportCaption($this->pegawai_telp);
					if ($this->pegawai_status->Exportable) $Doc->ExportCaption($this->pegawai_status);
					if ($this->tempat_lahir->Exportable) $Doc->ExportCaption($this->tempat_lahir);
					if ($this->tgl_lahir->Exportable) $Doc->ExportCaption($this->tgl_lahir);
					if ($this->pembagian1_id->Exportable) $Doc->ExportCaption($this->pembagian1_id);
					if ($this->pembagian2_id->Exportable) $Doc->ExportCaption($this->pembagian2_id);
					if ($this->pembagian3_id->Exportable) $Doc->ExportCaption($this->pembagian3_id);
					if ($this->tgl_mulai_kerja->Exportable) $Doc->ExportCaption($this->tgl_mulai_kerja);
					if ($this->tgl_resign->Exportable) $Doc->ExportCaption($this->tgl_resign);
					if ($this->gender->Exportable) $Doc->ExportCaption($this->gender);
					if ($this->tgl_masuk_pertama->Exportable) $Doc->ExportCaption($this->tgl_masuk_pertama);
					if ($this->photo_path->Exportable) $Doc->ExportCaption($this->photo_path);
					if ($this->tmp_img->Exportable) $Doc->ExportCaption($this->tmp_img);
					if ($this->nama_bank->Exportable) $Doc->ExportCaption($this->nama_bank);
					if ($this->nama_rek->Exportable) $Doc->ExportCaption($this->nama_rek);
					if ($this->no_rek->Exportable) $Doc->ExportCaption($this->no_rek);
				} else {
					if ($this->pegawai_id->Exportable) $Doc->ExportCaption($this->pegawai_id);
					if ($this->pegawai_pin->Exportable) $Doc->ExportCaption($this->pegawai_pin);
					if ($this->pegawai_nip->Exportable) $Doc->ExportCaption($this->pegawai_nip);
					if ($this->pegawai_nama->Exportable) $Doc->ExportCaption($this->pegawai_nama);
					if ($this->pegawai_pwd->Exportable) $Doc->ExportCaption($this->pegawai_pwd);
					if ($this->pegawai_rfid->Exportable) $Doc->ExportCaption($this->pegawai_rfid);
					if ($this->pegawai_privilege->Exportable) $Doc->ExportCaption($this->pegawai_privilege);
					if ($this->pegawai_telp->Exportable) $Doc->ExportCaption($this->pegawai_telp);
					if ($this->pegawai_status->Exportable) $Doc->ExportCaption($this->pegawai_status);
					if ($this->tempat_lahir->Exportable) $Doc->ExportCaption($this->tempat_lahir);
					if ($this->tgl_lahir->Exportable) $Doc->ExportCaption($this->tgl_lahir);
					if ($this->pembagian1_id->Exportable) $Doc->ExportCaption($this->pembagian1_id);
					if ($this->pembagian2_id->Exportable) $Doc->ExportCaption($this->pembagian2_id);
					if ($this->pembagian3_id->Exportable) $Doc->ExportCaption($this->pembagian3_id);
					if ($this->tgl_mulai_kerja->Exportable) $Doc->ExportCaption($this->tgl_mulai_kerja);
					if ($this->tgl_resign->Exportable) $Doc->ExportCaption($this->tgl_resign);
					if ($this->gender->Exportable) $Doc->ExportCaption($this->gender);
					if ($this->tgl_masuk_pertama->Exportable) $Doc->ExportCaption($this->tgl_masuk_pertama);
					if ($this->photo_path->Exportable) $Doc->ExportCaption($this->photo_path);
					if ($this->nama_bank->Exportable) $Doc->ExportCaption($this->nama_bank);
					if ($this->nama_rek->Exportable) $Doc->ExportCaption($this->nama_rek);
					if ($this->no_rek->Exportable) $Doc->ExportCaption($this->no_rek);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->pegawai_id->Exportable) $Doc->ExportField($this->pegawai_id);
						if ($this->pegawai_pin->Exportable) $Doc->ExportField($this->pegawai_pin);
						if ($this->pegawai_nip->Exportable) $Doc->ExportField($this->pegawai_nip);
						if ($this->pegawai_nama->Exportable) $Doc->ExportField($this->pegawai_nama);
						if ($this->pegawai_pwd->Exportable) $Doc->ExportField($this->pegawai_pwd);
						if ($this->pegawai_rfid->Exportable) $Doc->ExportField($this->pegawai_rfid);
						if ($this->pegawai_privilege->Exportable) $Doc->ExportField($this->pegawai_privilege);
						if ($this->pegawai_telp->Exportable) $Doc->ExportField($this->pegawai_telp);
						if ($this->pegawai_status->Exportable) $Doc->ExportField($this->pegawai_status);
						if ($this->tempat_lahir->Exportable) $Doc->ExportField($this->tempat_lahir);
						if ($this->tgl_lahir->Exportable) $Doc->ExportField($this->tgl_lahir);
						if ($this->pembagian1_id->Exportable) $Doc->ExportField($this->pembagian1_id);
						if ($this->pembagian2_id->Exportable) $Doc->ExportField($this->pembagian2_id);
						if ($this->pembagian3_id->Exportable) $Doc->ExportField($this->pembagian3_id);
						if ($this->tgl_mulai_kerja->Exportable) $Doc->ExportField($this->tgl_mulai_kerja);
						if ($this->tgl_resign->Exportable) $Doc->ExportField($this->tgl_resign);
						if ($this->gender->Exportable) $Doc->ExportField($this->gender);
						if ($this->tgl_masuk_pertama->Exportable) $Doc->ExportField($this->tgl_masuk_pertama);
						if ($this->photo_path->Exportable) $Doc->ExportField($this->photo_path);
						if ($this->tmp_img->Exportable) $Doc->ExportField($this->tmp_img);
						if ($this->nama_bank->Exportable) $Doc->ExportField($this->nama_bank);
						if ($this->nama_rek->Exportable) $Doc->ExportField($this->nama_rek);
						if ($this->no_rek->Exportable) $Doc->ExportField($this->no_rek);
					} else {
						if ($this->pegawai_id->Exportable) $Doc->ExportField($this->pegawai_id);
						if ($this->pegawai_pin->Exportable) $Doc->ExportField($this->pegawai_pin);
						if ($this->pegawai_nip->Exportable) $Doc->ExportField($this->pegawai_nip);
						if ($this->pegawai_nama->Exportable) $Doc->ExportField($this->pegawai_nama);
						if ($this->pegawai_pwd->Exportable) $Doc->ExportField($this->pegawai_pwd);
						if ($this->pegawai_rfid->Exportable) $Doc->ExportField($this->pegawai_rfid);
						if ($this->pegawai_privilege->Exportable) $Doc->ExportField($this->pegawai_privilege);
						if ($this->pegawai_telp->Exportable) $Doc->ExportField($this->pegawai_telp);
						if ($this->pegawai_status->Exportable) $Doc->ExportField($this->pegawai_status);
						if ($this->tempat_lahir->Exportable) $Doc->ExportField($this->tempat_lahir);
						if ($this->tgl_lahir->Exportable) $Doc->ExportField($this->tgl_lahir);
						if ($this->pembagian1_id->Exportable) $Doc->ExportField($this->pembagian1_id);
						if ($this->pembagian2_id->Exportable) $Doc->ExportField($this->pembagian2_id);
						if ($this->pembagian3_id->Exportable) $Doc->ExportField($this->pembagian3_id);
						if ($this->tgl_mulai_kerja->Exportable) $Doc->ExportField($this->tgl_mulai_kerja);
						if ($this->tgl_resign->Exportable) $Doc->ExportField($this->tgl_resign);
						if ($this->gender->Exportable) $Doc->ExportField($this->gender);
						if ($this->tgl_masuk_pertama->Exportable) $Doc->ExportField($this->tgl_masuk_pertama);
						if ($this->photo_path->Exportable) $Doc->ExportField($this->photo_path);
						if ($this->nama_bank->Exportable) $Doc->ExportField($this->nama_bank);
						if ($this->nama_rek->Exportable) $Doc->ExportField($this->nama_rek);
						if ($this->no_rek->Exportable) $Doc->ExportField($this->no_rek);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Write Audit Trail start/end for grid update
	function WriteAuditTrailDummy($typ) {
		$table = 'pegawai';
		$usr = CurrentUserID();
		ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $typ, $table, "", "", "", "");
	}

	// Write Audit Trail (add page)
	function WriteAuditTrailOnAdd(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnAdd) return;
		$table = 'pegawai';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['pegawai_id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$newvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$newvalue = $rs[$fldname];
					else
						$newvalue = "[MEMO]"; // Memo Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$newvalue = "[XML]"; // XML Field
				} else {
					$newvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $usr, "A", $table, $fldname, $key, "", $newvalue);
			}
		}
	}

	// Write Audit Trail (edit page)
	function WriteAuditTrailOnEdit(&$rsold, &$rsnew) {
		global $Language;
		if (!$this->AuditTrailOnEdit) return;
		$table = 'pegawai';

		// Get key value
		$key = "";
		if ($key <> "") $key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rsold['pegawai_id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$usr = CurrentUserID();
		foreach (array_keys($rsnew) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && array_key_exists($fldname, $rsold) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldDataType == EW_DATATYPE_DATE) { // DateTime field
					$modified = (ew_FormatDateTime($rsold[$fldname], 0) <> ew_FormatDateTime($rsnew[$fldname], 0));
				} else {
					$modified = !ew_CompareValue($rsold[$fldname], $rsnew[$fldname]);
				}
				if ($modified) {
					if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") { // Password Field
						$oldvalue = $Language->Phrase("PasswordMask");
						$newvalue = $Language->Phrase("PasswordMask");
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) { // Memo field
						if (EW_AUDIT_TRAIL_TO_DATABASE) {
							$oldvalue = $rsold[$fldname];
							$newvalue = $rsnew[$fldname];
						} else {
							$oldvalue = "[MEMO]";
							$newvalue = "[MEMO]";
						}
					} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) { // XML field
						$oldvalue = "[XML]";
						$newvalue = "[XML]";
					} else {
						$oldvalue = $rsold[$fldname];
						$newvalue = $rsnew[$fldname];
					}
					ew_WriteAuditTrail("log", $dt, $id, $usr, "U", $table, $fldname, $key, $oldvalue, $newvalue);
				}
			}
		}
	}

	// Write Audit Trail (delete page)
	function WriteAuditTrailOnDelete(&$rs) {
		global $Language;
		if (!$this->AuditTrailOnDelete) return;
		$table = 'pegawai';

		// Get key value
		$key = "";
		if ($key <> "")
			$key .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
		$key .= $rs['pegawai_id'];

		// Write Audit Trail
		$dt = ew_StdCurrentDateTime();
		$id = ew_ScriptName();
		$curUser = CurrentUserID();
		foreach (array_keys($rs) as $fldname) {
			if (array_key_exists($fldname, $this->fields) && $this->fields[$fldname]->FldDataType <> EW_DATATYPE_BLOB) { // Ignore BLOB fields
				if ($this->fields[$fldname]->FldHtmlTag == "PASSWORD") {
					$oldvalue = $Language->Phrase("PasswordMask"); // Password Field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_MEMO) {
					if (EW_AUDIT_TRAIL_TO_DATABASE)
						$oldvalue = $rs[$fldname];
					else
						$oldvalue = "[MEMO]"; // Memo field
				} elseif ($this->fields[$fldname]->FldDataType == EW_DATATYPE_XML) {
					$oldvalue = "[XML]"; // XML field
				} else {
					$oldvalue = $rs[$fldname];
				}
				ew_WriteAuditTrail("log", $dt, $id, $curUser, "D", $table, $fldname, $key, $oldvalue, "");
			}
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
