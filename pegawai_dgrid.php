<?php include_once "t_userinfo.php" ?>
<?php

// Create page object
if (!isset($pegawai_d_grid)) $pegawai_d_grid = new cpegawai_d_grid();

// Page init
$pegawai_d_grid->Page_Init();

// Page main
$pegawai_d_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pegawai_d_grid->Page_Render();
?>
<?php if ($pegawai_d->Export == "") { ?>
<script type="text/javascript">

// Form object
var fpegawai_dgrid = new ew_Form("fpegawai_dgrid", "grid");
fpegawai_dgrid.FormKeyCountName = '<?php echo $pegawai_d_grid->FormKeyCountName ?>';

// Validate form
fpegawai_dgrid.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
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
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fpegawai_dgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "pegawai_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "pend_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "gol_darah", false)) return false;
	if (ew_ValueChanged(fobj, infix, "stat_nikah", false)) return false;
	if (ew_ValueChanged(fobj, infix, "jml_anak", false)) return false;
	if (ew_ValueChanged(fobj, infix, "alamat", false)) return false;
	if (ew_ValueChanged(fobj, infix, "telp_extra", false)) return false;
	if (ew_ValueChanged(fobj, infix, "hubungan", false)) return false;
	if (ew_ValueChanged(fobj, infix, "nama_hubungan", false)) return false;
	if (ew_ValueChanged(fobj, infix, "agama", false)) return false;
	return true;
}

// Form_CustomValidate event
fpegawai_dgrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpegawai_dgrid.ValidateRequired = true;
<?php } else { ?>
fpegawai_dgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($pegawai_d->CurrentAction == "gridadd") {
	if ($pegawai_d->CurrentMode == "copy") {
		$bSelectLimit = $pegawai_d_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$pegawai_d_grid->TotalRecs = $pegawai_d->SelectRecordCount();
			$pegawai_d_grid->Recordset = $pegawai_d_grid->LoadRecordset($pegawai_d_grid->StartRec-1, $pegawai_d_grid->DisplayRecs);
		} else {
			if ($pegawai_d_grid->Recordset = $pegawai_d_grid->LoadRecordset())
				$pegawai_d_grid->TotalRecs = $pegawai_d_grid->Recordset->RecordCount();
		}
		$pegawai_d_grid->StartRec = 1;
		$pegawai_d_grid->DisplayRecs = $pegawai_d_grid->TotalRecs;
	} else {
		$pegawai_d->CurrentFilter = "0=1";
		$pegawai_d_grid->StartRec = 1;
		$pegawai_d_grid->DisplayRecs = $pegawai_d->GridAddRowCount;
	}
	$pegawai_d_grid->TotalRecs = $pegawai_d_grid->DisplayRecs;
	$pegawai_d_grid->StopRec = $pegawai_d_grid->DisplayRecs;
} else {
	$bSelectLimit = $pegawai_d_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($pegawai_d_grid->TotalRecs <= 0)
			$pegawai_d_grid->TotalRecs = $pegawai_d->SelectRecordCount();
	} else {
		if (!$pegawai_d_grid->Recordset && ($pegawai_d_grid->Recordset = $pegawai_d_grid->LoadRecordset()))
			$pegawai_d_grid->TotalRecs = $pegawai_d_grid->Recordset->RecordCount();
	}
	$pegawai_d_grid->StartRec = 1;
	$pegawai_d_grid->DisplayRecs = $pegawai_d_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$pegawai_d_grid->Recordset = $pegawai_d_grid->LoadRecordset($pegawai_d_grid->StartRec-1, $pegawai_d_grid->DisplayRecs);

	// Set no record found message
	if ($pegawai_d->CurrentAction == "" && $pegawai_d_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$pegawai_d_grid->setWarningMessage(ew_DeniedMsg());
		if ($pegawai_d_grid->SearchWhere == "0=101")
			$pegawai_d_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$pegawai_d_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$pegawai_d_grid->RenderOtherOptions();
?>
<?php $pegawai_d_grid->ShowPageHeader(); ?>
<?php
$pegawai_d_grid->ShowMessage();
?>
<?php if ($pegawai_d_grid->TotalRecs > 0 || $pegawai_d->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid pegawai_d">
<div id="fpegawai_dgrid" class="ewForm form-inline">
<?php if ($pegawai_d_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($pegawai_d_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_pegawai_d" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_pegawai_dgrid" class="table ewTable">
<?php echo $pegawai_d->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$pegawai_d_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$pegawai_d_grid->RenderListOptions();

// Render list options (header, left)
$pegawai_d_grid->ListOptions->Render("header", "left");
?>
<?php if ($pegawai_d->pegawai_id->Visible) { // pegawai_id ?>
	<?php if ($pegawai_d->SortUrl($pegawai_d->pegawai_id) == "") { ?>
		<th data-name="pegawai_id"><div id="elh_pegawai_d_pegawai_id" class="pegawai_d_pegawai_id"><div class="ewTableHeaderCaption"><?php echo $pegawai_d->pegawai_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pegawai_id"><div><div id="elh_pegawai_d_pegawai_id" class="pegawai_d_pegawai_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pegawai_d->pegawai_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pegawai_d->pegawai_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pegawai_d->pegawai_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pegawai_d->pend_id->Visible) { // pend_id ?>
	<?php if ($pegawai_d->SortUrl($pegawai_d->pend_id) == "") { ?>
		<th data-name="pend_id"><div id="elh_pegawai_d_pend_id" class="pegawai_d_pend_id"><div class="ewTableHeaderCaption"><?php echo $pegawai_d->pend_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pend_id"><div><div id="elh_pegawai_d_pend_id" class="pegawai_d_pend_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pegawai_d->pend_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pegawai_d->pend_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pegawai_d->pend_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pegawai_d->gol_darah->Visible) { // gol_darah ?>
	<?php if ($pegawai_d->SortUrl($pegawai_d->gol_darah) == "") { ?>
		<th data-name="gol_darah"><div id="elh_pegawai_d_gol_darah" class="pegawai_d_gol_darah"><div class="ewTableHeaderCaption"><?php echo $pegawai_d->gol_darah->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="gol_darah"><div><div id="elh_pegawai_d_gol_darah" class="pegawai_d_gol_darah">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pegawai_d->gol_darah->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pegawai_d->gol_darah->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pegawai_d->gol_darah->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pegawai_d->stat_nikah->Visible) { // stat_nikah ?>
	<?php if ($pegawai_d->SortUrl($pegawai_d->stat_nikah) == "") { ?>
		<th data-name="stat_nikah"><div id="elh_pegawai_d_stat_nikah" class="pegawai_d_stat_nikah"><div class="ewTableHeaderCaption"><?php echo $pegawai_d->stat_nikah->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="stat_nikah"><div><div id="elh_pegawai_d_stat_nikah" class="pegawai_d_stat_nikah">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pegawai_d->stat_nikah->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pegawai_d->stat_nikah->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pegawai_d->stat_nikah->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pegawai_d->jml_anak->Visible) { // jml_anak ?>
	<?php if ($pegawai_d->SortUrl($pegawai_d->jml_anak) == "") { ?>
		<th data-name="jml_anak"><div id="elh_pegawai_d_jml_anak" class="pegawai_d_jml_anak"><div class="ewTableHeaderCaption"><?php echo $pegawai_d->jml_anak->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="jml_anak"><div><div id="elh_pegawai_d_jml_anak" class="pegawai_d_jml_anak">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pegawai_d->jml_anak->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pegawai_d->jml_anak->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pegawai_d->jml_anak->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pegawai_d->alamat->Visible) { // alamat ?>
	<?php if ($pegawai_d->SortUrl($pegawai_d->alamat) == "") { ?>
		<th data-name="alamat"><div id="elh_pegawai_d_alamat" class="pegawai_d_alamat"><div class="ewTableHeaderCaption"><?php echo $pegawai_d->alamat->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="alamat"><div><div id="elh_pegawai_d_alamat" class="pegawai_d_alamat">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pegawai_d->alamat->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pegawai_d->alamat->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pegawai_d->alamat->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pegawai_d->telp_extra->Visible) { // telp_extra ?>
	<?php if ($pegawai_d->SortUrl($pegawai_d->telp_extra) == "") { ?>
		<th data-name="telp_extra"><div id="elh_pegawai_d_telp_extra" class="pegawai_d_telp_extra"><div class="ewTableHeaderCaption"><?php echo $pegawai_d->telp_extra->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="telp_extra"><div><div id="elh_pegawai_d_telp_extra" class="pegawai_d_telp_extra">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pegawai_d->telp_extra->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pegawai_d->telp_extra->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pegawai_d->telp_extra->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pegawai_d->hubungan->Visible) { // hubungan ?>
	<?php if ($pegawai_d->SortUrl($pegawai_d->hubungan) == "") { ?>
		<th data-name="hubungan"><div id="elh_pegawai_d_hubungan" class="pegawai_d_hubungan"><div class="ewTableHeaderCaption"><?php echo $pegawai_d->hubungan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="hubungan"><div><div id="elh_pegawai_d_hubungan" class="pegawai_d_hubungan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pegawai_d->hubungan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pegawai_d->hubungan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pegawai_d->hubungan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pegawai_d->nama_hubungan->Visible) { // nama_hubungan ?>
	<?php if ($pegawai_d->SortUrl($pegawai_d->nama_hubungan) == "") { ?>
		<th data-name="nama_hubungan"><div id="elh_pegawai_d_nama_hubungan" class="pegawai_d_nama_hubungan"><div class="ewTableHeaderCaption"><?php echo $pegawai_d->nama_hubungan->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="nama_hubungan"><div><div id="elh_pegawai_d_nama_hubungan" class="pegawai_d_nama_hubungan">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pegawai_d->nama_hubungan->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pegawai_d->nama_hubungan->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pegawai_d->nama_hubungan->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pegawai_d->agama->Visible) { // agama ?>
	<?php if ($pegawai_d->SortUrl($pegawai_d->agama) == "") { ?>
		<th data-name="agama"><div id="elh_pegawai_d_agama" class="pegawai_d_agama"><div class="ewTableHeaderCaption"><?php echo $pegawai_d->agama->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="agama"><div><div id="elh_pegawai_d_agama" class="pegawai_d_agama">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pegawai_d->agama->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pegawai_d->agama->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pegawai_d->agama->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$pegawai_d_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$pegawai_d_grid->StartRec = 1;
$pegawai_d_grid->StopRec = $pegawai_d_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($pegawai_d_grid->FormKeyCountName) && ($pegawai_d->CurrentAction == "gridadd" || $pegawai_d->CurrentAction == "gridedit" || $pegawai_d->CurrentAction == "F")) {
		$pegawai_d_grid->KeyCount = $objForm->GetValue($pegawai_d_grid->FormKeyCountName);
		$pegawai_d_grid->StopRec = $pegawai_d_grid->StartRec + $pegawai_d_grid->KeyCount - 1;
	}
}
$pegawai_d_grid->RecCnt = $pegawai_d_grid->StartRec - 1;
if ($pegawai_d_grid->Recordset && !$pegawai_d_grid->Recordset->EOF) {
	$pegawai_d_grid->Recordset->MoveFirst();
	$bSelectLimit = $pegawai_d_grid->UseSelectLimit;
	if (!$bSelectLimit && $pegawai_d_grid->StartRec > 1)
		$pegawai_d_grid->Recordset->Move($pegawai_d_grid->StartRec - 1);
} elseif (!$pegawai_d->AllowAddDeleteRow && $pegawai_d_grid->StopRec == 0) {
	$pegawai_d_grid->StopRec = $pegawai_d->GridAddRowCount;
}

// Initialize aggregate
$pegawai_d->RowType = EW_ROWTYPE_AGGREGATEINIT;
$pegawai_d->ResetAttrs();
$pegawai_d_grid->RenderRow();
if ($pegawai_d->CurrentAction == "gridadd")
	$pegawai_d_grid->RowIndex = 0;
if ($pegawai_d->CurrentAction == "gridedit")
	$pegawai_d_grid->RowIndex = 0;
while ($pegawai_d_grid->RecCnt < $pegawai_d_grid->StopRec) {
	$pegawai_d_grid->RecCnt++;
	if (intval($pegawai_d_grid->RecCnt) >= intval($pegawai_d_grid->StartRec)) {
		$pegawai_d_grid->RowCnt++;
		if ($pegawai_d->CurrentAction == "gridadd" || $pegawai_d->CurrentAction == "gridedit" || $pegawai_d->CurrentAction == "F") {
			$pegawai_d_grid->RowIndex++;
			$objForm->Index = $pegawai_d_grid->RowIndex;
			if ($objForm->HasValue($pegawai_d_grid->FormActionName))
				$pegawai_d_grid->RowAction = strval($objForm->GetValue($pegawai_d_grid->FormActionName));
			elseif ($pegawai_d->CurrentAction == "gridadd")
				$pegawai_d_grid->RowAction = "insert";
			else
				$pegawai_d_grid->RowAction = "";
		}

		// Set up key count
		$pegawai_d_grid->KeyCount = $pegawai_d_grid->RowIndex;

		// Init row class and style
		$pegawai_d->ResetAttrs();
		$pegawai_d->CssClass = "";
		if ($pegawai_d->CurrentAction == "gridadd") {
			if ($pegawai_d->CurrentMode == "copy") {
				$pegawai_d_grid->LoadRowValues($pegawai_d_grid->Recordset); // Load row values
				$pegawai_d_grid->SetRecordKey($pegawai_d_grid->RowOldKey, $pegawai_d_grid->Recordset); // Set old record key
			} else {
				$pegawai_d_grid->LoadDefaultValues(); // Load default values
				$pegawai_d_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$pegawai_d_grid->LoadRowValues($pegawai_d_grid->Recordset); // Load row values
		}
		$pegawai_d->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($pegawai_d->CurrentAction == "gridadd") // Grid add
			$pegawai_d->RowType = EW_ROWTYPE_ADD; // Render add
		if ($pegawai_d->CurrentAction == "gridadd" && $pegawai_d->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$pegawai_d_grid->RestoreCurrentRowFormValues($pegawai_d_grid->RowIndex); // Restore form values
		if ($pegawai_d->CurrentAction == "gridedit") { // Grid edit
			if ($pegawai_d->EventCancelled) {
				$pegawai_d_grid->RestoreCurrentRowFormValues($pegawai_d_grid->RowIndex); // Restore form values
			}
			if ($pegawai_d_grid->RowAction == "insert")
				$pegawai_d->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$pegawai_d->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($pegawai_d->CurrentAction == "gridedit" && ($pegawai_d->RowType == EW_ROWTYPE_EDIT || $pegawai_d->RowType == EW_ROWTYPE_ADD) && $pegawai_d->EventCancelled) // Update failed
			$pegawai_d_grid->RestoreCurrentRowFormValues($pegawai_d_grid->RowIndex); // Restore form values
		if ($pegawai_d->RowType == EW_ROWTYPE_EDIT) // Edit row
			$pegawai_d_grid->EditRowCnt++;
		if ($pegawai_d->CurrentAction == "F") // Confirm row
			$pegawai_d_grid->RestoreCurrentRowFormValues($pegawai_d_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$pegawai_d->RowAttrs = array_merge($pegawai_d->RowAttrs, array('data-rowindex'=>$pegawai_d_grid->RowCnt, 'id'=>'r' . $pegawai_d_grid->RowCnt . '_pegawai_d', 'data-rowtype'=>$pegawai_d->RowType));

		// Render row
		$pegawai_d_grid->RenderRow();

		// Render list options
		$pegawai_d_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($pegawai_d_grid->RowAction <> "delete" && $pegawai_d_grid->RowAction <> "insertdelete" && !($pegawai_d_grid->RowAction == "insert" && $pegawai_d->CurrentAction == "F" && $pegawai_d_grid->EmptyRow())) {
?>
	<tr<?php echo $pegawai_d->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pegawai_d_grid->ListOptions->Render("body", "left", $pegawai_d_grid->RowCnt);
?>
	<?php if ($pegawai_d->pegawai_id->Visible) { // pegawai_id ?>
		<td data-name="pegawai_id"<?php echo $pegawai_d->pegawai_id->CellAttributes() ?>>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($pegawai_d->pegawai_id->getSessionValue() <> "") { ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_pegawai_id" class="form-group pegawai_d_pegawai_id">
<span<?php echo $pegawai_d->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pegawai_d->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" name="x<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($pegawai_d->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_pegawai_id" class="form-group pegawai_d_pegawai_id">
<input type="text" data-table="pegawai_d" data-field="x_pegawai_id" name="x<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" id="x<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->pegawai_id->EditValue ?>"<?php echo $pegawai_d->pegawai_id->EditAttributes() ?>>
</span>
<?php } ?>
<input type="hidden" data-table="pegawai_d" data-field="x_pegawai_id" name="o<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" id="o<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($pegawai_d->pegawai_id->OldValue) ?>">
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_pegawai_id" class="form-group pegawai_d_pegawai_id">
<span<?php echo $pegawai_d->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pegawai_d->pegawai_id->EditValue ?></p></span>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_pegawai_id" name="x<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" id="x<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($pegawai_d->pegawai_id->CurrentValue) ?>">
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_pegawai_id" class="pegawai_d_pegawai_id">
<span<?php echo $pegawai_d->pegawai_id->ViewAttributes() ?>>
<?php echo $pegawai_d->pegawai_id->ListViewValue() ?></span>
</span>
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_pegawai_id" name="x<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" id="x<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($pegawai_d->pegawai_id->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_pegawai_id" name="o<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" id="o<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($pegawai_d->pegawai_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_pegawai_id" name="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" id="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($pegawai_d->pegawai_id->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_pegawai_id" name="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" id="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($pegawai_d->pegawai_id->OldValue) ?>">
<?php } ?>
<?php } ?>
<a id="<?php echo $pegawai_d_grid->PageObjName . "_row_" . $pegawai_d_grid->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($pegawai_d->pend_id->Visible) { // pend_id ?>
		<td data-name="pend_id"<?php echo $pegawai_d->pend_id->CellAttributes() ?>>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_pend_id" class="form-group pegawai_d_pend_id">
<input type="text" data-table="pegawai_d" data-field="x_pend_id" name="x<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" id="x<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->pend_id->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->pend_id->EditValue ?>"<?php echo $pegawai_d->pend_id->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_pend_id" name="o<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" id="o<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" value="<?php echo ew_HtmlEncode($pegawai_d->pend_id->OldValue) ?>">
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_pend_id" class="form-group pegawai_d_pend_id">
<input type="text" data-table="pegawai_d" data-field="x_pend_id" name="x<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" id="x<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->pend_id->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->pend_id->EditValue ?>"<?php echo $pegawai_d->pend_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_pend_id" class="pegawai_d_pend_id">
<span<?php echo $pegawai_d->pend_id->ViewAttributes() ?>>
<?php echo $pegawai_d->pend_id->ListViewValue() ?></span>
</span>
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_pend_id" name="x<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" id="x<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" value="<?php echo ew_HtmlEncode($pegawai_d->pend_id->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_pend_id" name="o<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" id="o<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" value="<?php echo ew_HtmlEncode($pegawai_d->pend_id->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_pend_id" name="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" id="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" value="<?php echo ew_HtmlEncode($pegawai_d->pend_id->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_pend_id" name="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" id="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" value="<?php echo ew_HtmlEncode($pegawai_d->pend_id->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pegawai_d->gol_darah->Visible) { // gol_darah ?>
		<td data-name="gol_darah"<?php echo $pegawai_d->gol_darah->CellAttributes() ?>>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_gol_darah" class="form-group pegawai_d_gol_darah">
<input type="text" data-table="pegawai_d" data-field="x_gol_darah" name="x<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" id="x<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->gol_darah->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->gol_darah->EditValue ?>"<?php echo $pegawai_d->gol_darah->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_gol_darah" name="o<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" id="o<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" value="<?php echo ew_HtmlEncode($pegawai_d->gol_darah->OldValue) ?>">
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_gol_darah" class="form-group pegawai_d_gol_darah">
<input type="text" data-table="pegawai_d" data-field="x_gol_darah" name="x<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" id="x<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->gol_darah->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->gol_darah->EditValue ?>"<?php echo $pegawai_d->gol_darah->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_gol_darah" class="pegawai_d_gol_darah">
<span<?php echo $pegawai_d->gol_darah->ViewAttributes() ?>>
<?php echo $pegawai_d->gol_darah->ListViewValue() ?></span>
</span>
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_gol_darah" name="x<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" id="x<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" value="<?php echo ew_HtmlEncode($pegawai_d->gol_darah->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_gol_darah" name="o<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" id="o<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" value="<?php echo ew_HtmlEncode($pegawai_d->gol_darah->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_gol_darah" name="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" id="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" value="<?php echo ew_HtmlEncode($pegawai_d->gol_darah->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_gol_darah" name="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" id="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" value="<?php echo ew_HtmlEncode($pegawai_d->gol_darah->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pegawai_d->stat_nikah->Visible) { // stat_nikah ?>
		<td data-name="stat_nikah"<?php echo $pegawai_d->stat_nikah->CellAttributes() ?>>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_stat_nikah" class="form-group pegawai_d_stat_nikah">
<input type="text" data-table="pegawai_d" data-field="x_stat_nikah" name="x<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" id="x<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->stat_nikah->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->stat_nikah->EditValue ?>"<?php echo $pegawai_d->stat_nikah->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_stat_nikah" name="o<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" id="o<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" value="<?php echo ew_HtmlEncode($pegawai_d->stat_nikah->OldValue) ?>">
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_stat_nikah" class="form-group pegawai_d_stat_nikah">
<input type="text" data-table="pegawai_d" data-field="x_stat_nikah" name="x<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" id="x<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->stat_nikah->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->stat_nikah->EditValue ?>"<?php echo $pegawai_d->stat_nikah->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_stat_nikah" class="pegawai_d_stat_nikah">
<span<?php echo $pegawai_d->stat_nikah->ViewAttributes() ?>>
<?php echo $pegawai_d->stat_nikah->ListViewValue() ?></span>
</span>
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_stat_nikah" name="x<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" id="x<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" value="<?php echo ew_HtmlEncode($pegawai_d->stat_nikah->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_stat_nikah" name="o<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" id="o<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" value="<?php echo ew_HtmlEncode($pegawai_d->stat_nikah->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_stat_nikah" name="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" id="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" value="<?php echo ew_HtmlEncode($pegawai_d->stat_nikah->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_stat_nikah" name="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" id="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" value="<?php echo ew_HtmlEncode($pegawai_d->stat_nikah->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pegawai_d->jml_anak->Visible) { // jml_anak ?>
		<td data-name="jml_anak"<?php echo $pegawai_d->jml_anak->CellAttributes() ?>>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_jml_anak" class="form-group pegawai_d_jml_anak">
<input type="text" data-table="pegawai_d" data-field="x_jml_anak" name="x<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" id="x<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->jml_anak->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->jml_anak->EditValue ?>"<?php echo $pegawai_d->jml_anak->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_jml_anak" name="o<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" id="o<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" value="<?php echo ew_HtmlEncode($pegawai_d->jml_anak->OldValue) ?>">
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_jml_anak" class="form-group pegawai_d_jml_anak">
<input type="text" data-table="pegawai_d" data-field="x_jml_anak" name="x<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" id="x<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->jml_anak->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->jml_anak->EditValue ?>"<?php echo $pegawai_d->jml_anak->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_jml_anak" class="pegawai_d_jml_anak">
<span<?php echo $pegawai_d->jml_anak->ViewAttributes() ?>>
<?php echo $pegawai_d->jml_anak->ListViewValue() ?></span>
</span>
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_jml_anak" name="x<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" id="x<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" value="<?php echo ew_HtmlEncode($pegawai_d->jml_anak->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_jml_anak" name="o<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" id="o<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" value="<?php echo ew_HtmlEncode($pegawai_d->jml_anak->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_jml_anak" name="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" id="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" value="<?php echo ew_HtmlEncode($pegawai_d->jml_anak->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_jml_anak" name="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" id="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" value="<?php echo ew_HtmlEncode($pegawai_d->jml_anak->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pegawai_d->alamat->Visible) { // alamat ?>
		<td data-name="alamat"<?php echo $pegawai_d->alamat->CellAttributes() ?>>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_alamat" class="form-group pegawai_d_alamat">
<input type="text" data-table="pegawai_d" data-field="x_alamat" name="x<?php echo $pegawai_d_grid->RowIndex ?>_alamat" id="x<?php echo $pegawai_d_grid->RowIndex ?>_alamat" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pegawai_d->alamat->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->alamat->EditValue ?>"<?php echo $pegawai_d->alamat->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_alamat" name="o<?php echo $pegawai_d_grid->RowIndex ?>_alamat" id="o<?php echo $pegawai_d_grid->RowIndex ?>_alamat" value="<?php echo ew_HtmlEncode($pegawai_d->alamat->OldValue) ?>">
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_alamat" class="form-group pegawai_d_alamat">
<input type="text" data-table="pegawai_d" data-field="x_alamat" name="x<?php echo $pegawai_d_grid->RowIndex ?>_alamat" id="x<?php echo $pegawai_d_grid->RowIndex ?>_alamat" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pegawai_d->alamat->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->alamat->EditValue ?>"<?php echo $pegawai_d->alamat->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_alamat" class="pegawai_d_alamat">
<span<?php echo $pegawai_d->alamat->ViewAttributes() ?>>
<?php echo $pegawai_d->alamat->ListViewValue() ?></span>
</span>
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_alamat" name="x<?php echo $pegawai_d_grid->RowIndex ?>_alamat" id="x<?php echo $pegawai_d_grid->RowIndex ?>_alamat" value="<?php echo ew_HtmlEncode($pegawai_d->alamat->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_alamat" name="o<?php echo $pegawai_d_grid->RowIndex ?>_alamat" id="o<?php echo $pegawai_d_grid->RowIndex ?>_alamat" value="<?php echo ew_HtmlEncode($pegawai_d->alamat->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_alamat" name="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_alamat" id="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_alamat" value="<?php echo ew_HtmlEncode($pegawai_d->alamat->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_alamat" name="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_alamat" id="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_alamat" value="<?php echo ew_HtmlEncode($pegawai_d->alamat->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pegawai_d->telp_extra->Visible) { // telp_extra ?>
		<td data-name="telp_extra"<?php echo $pegawai_d->telp_extra->CellAttributes() ?>>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_telp_extra" class="form-group pegawai_d_telp_extra">
<input type="text" data-table="pegawai_d" data-field="x_telp_extra" name="x<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" id="x<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($pegawai_d->telp_extra->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->telp_extra->EditValue ?>"<?php echo $pegawai_d->telp_extra->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_telp_extra" name="o<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" id="o<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" value="<?php echo ew_HtmlEncode($pegawai_d->telp_extra->OldValue) ?>">
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_telp_extra" class="form-group pegawai_d_telp_extra">
<input type="text" data-table="pegawai_d" data-field="x_telp_extra" name="x<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" id="x<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($pegawai_d->telp_extra->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->telp_extra->EditValue ?>"<?php echo $pegawai_d->telp_extra->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_telp_extra" class="pegawai_d_telp_extra">
<span<?php echo $pegawai_d->telp_extra->ViewAttributes() ?>>
<?php echo $pegawai_d->telp_extra->ListViewValue() ?></span>
</span>
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_telp_extra" name="x<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" id="x<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" value="<?php echo ew_HtmlEncode($pegawai_d->telp_extra->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_telp_extra" name="o<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" id="o<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" value="<?php echo ew_HtmlEncode($pegawai_d->telp_extra->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_telp_extra" name="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" id="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" value="<?php echo ew_HtmlEncode($pegawai_d->telp_extra->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_telp_extra" name="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" id="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" value="<?php echo ew_HtmlEncode($pegawai_d->telp_extra->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pegawai_d->hubungan->Visible) { // hubungan ?>
		<td data-name="hubungan"<?php echo $pegawai_d->hubungan->CellAttributes() ?>>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_hubungan" class="form-group pegawai_d_hubungan">
<input type="text" data-table="pegawai_d" data-field="x_hubungan" name="x<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" id="x<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->hubungan->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->hubungan->EditValue ?>"<?php echo $pegawai_d->hubungan->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_hubungan" name="o<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" id="o<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" value="<?php echo ew_HtmlEncode($pegawai_d->hubungan->OldValue) ?>">
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_hubungan" class="form-group pegawai_d_hubungan">
<input type="text" data-table="pegawai_d" data-field="x_hubungan" name="x<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" id="x<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->hubungan->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->hubungan->EditValue ?>"<?php echo $pegawai_d->hubungan->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_hubungan" class="pegawai_d_hubungan">
<span<?php echo $pegawai_d->hubungan->ViewAttributes() ?>>
<?php echo $pegawai_d->hubungan->ListViewValue() ?></span>
</span>
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_hubungan" name="x<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" id="x<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" value="<?php echo ew_HtmlEncode($pegawai_d->hubungan->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_hubungan" name="o<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" id="o<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" value="<?php echo ew_HtmlEncode($pegawai_d->hubungan->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_hubungan" name="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" id="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" value="<?php echo ew_HtmlEncode($pegawai_d->hubungan->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_hubungan" name="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" id="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" value="<?php echo ew_HtmlEncode($pegawai_d->hubungan->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pegawai_d->nama_hubungan->Visible) { // nama_hubungan ?>
		<td data-name="nama_hubungan"<?php echo $pegawai_d->nama_hubungan->CellAttributes() ?>>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_nama_hubungan" class="form-group pegawai_d_nama_hubungan">
<input type="text" data-table="pegawai_d" data-field="x_nama_hubungan" name="x<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" id="x<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pegawai_d->nama_hubungan->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->nama_hubungan->EditValue ?>"<?php echo $pegawai_d->nama_hubungan->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_nama_hubungan" name="o<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" id="o<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" value="<?php echo ew_HtmlEncode($pegawai_d->nama_hubungan->OldValue) ?>">
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_nama_hubungan" class="form-group pegawai_d_nama_hubungan">
<input type="text" data-table="pegawai_d" data-field="x_nama_hubungan" name="x<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" id="x<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pegawai_d->nama_hubungan->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->nama_hubungan->EditValue ?>"<?php echo $pegawai_d->nama_hubungan->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_nama_hubungan" class="pegawai_d_nama_hubungan">
<span<?php echo $pegawai_d->nama_hubungan->ViewAttributes() ?>>
<?php echo $pegawai_d->nama_hubungan->ListViewValue() ?></span>
</span>
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_nama_hubungan" name="x<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" id="x<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" value="<?php echo ew_HtmlEncode($pegawai_d->nama_hubungan->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_nama_hubungan" name="o<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" id="o<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" value="<?php echo ew_HtmlEncode($pegawai_d->nama_hubungan->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_nama_hubungan" name="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" id="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" value="<?php echo ew_HtmlEncode($pegawai_d->nama_hubungan->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_nama_hubungan" name="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" id="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" value="<?php echo ew_HtmlEncode($pegawai_d->nama_hubungan->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($pegawai_d->agama->Visible) { // agama ?>
		<td data-name="agama"<?php echo $pegawai_d->agama->CellAttributes() ?>>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_agama" class="form-group pegawai_d_agama">
<input type="text" data-table="pegawai_d" data-field="x_agama" name="x<?php echo $pegawai_d_grid->RowIndex ?>_agama" id="x<?php echo $pegawai_d_grid->RowIndex ?>_agama" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->agama->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->agama->EditValue ?>"<?php echo $pegawai_d->agama->EditAttributes() ?>>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_agama" name="o<?php echo $pegawai_d_grid->RowIndex ?>_agama" id="o<?php echo $pegawai_d_grid->RowIndex ?>_agama" value="<?php echo ew_HtmlEncode($pegawai_d->agama->OldValue) ?>">
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_agama" class="form-group pegawai_d_agama">
<input type="text" data-table="pegawai_d" data-field="x_agama" name="x<?php echo $pegawai_d_grid->RowIndex ?>_agama" id="x<?php echo $pegawai_d_grid->RowIndex ?>_agama" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->agama->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->agama->EditValue ?>"<?php echo $pegawai_d->agama->EditAttributes() ?>>
</span>
<?php } ?>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pegawai_d_grid->RowCnt ?>_pegawai_d_agama" class="pegawai_d_agama">
<span<?php echo $pegawai_d->agama->ViewAttributes() ?>>
<?php echo $pegawai_d->agama->ListViewValue() ?></span>
</span>
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_agama" name="x<?php echo $pegawai_d_grid->RowIndex ?>_agama" id="x<?php echo $pegawai_d_grid->RowIndex ?>_agama" value="<?php echo ew_HtmlEncode($pegawai_d->agama->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_agama" name="o<?php echo $pegawai_d_grid->RowIndex ?>_agama" id="o<?php echo $pegawai_d_grid->RowIndex ?>_agama" value="<?php echo ew_HtmlEncode($pegawai_d->agama->OldValue) ?>">
<?php } else { ?>
<input type="hidden" data-table="pegawai_d" data-field="x_agama" name="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_agama" id="fpegawai_dgrid$x<?php echo $pegawai_d_grid->RowIndex ?>_agama" value="<?php echo ew_HtmlEncode($pegawai_d->agama->FormValue) ?>">
<input type="hidden" data-table="pegawai_d" data-field="x_agama" name="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_agama" id="fpegawai_dgrid$o<?php echo $pegawai_d_grid->RowIndex ?>_agama" value="<?php echo ew_HtmlEncode($pegawai_d->agama->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pegawai_d_grid->ListOptions->Render("body", "right", $pegawai_d_grid->RowCnt);
?>
	</tr>
<?php if ($pegawai_d->RowType == EW_ROWTYPE_ADD || $pegawai_d->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fpegawai_dgrid.UpdateOpts(<?php echo $pegawai_d_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($pegawai_d->CurrentAction <> "gridadd" || $pegawai_d->CurrentMode == "copy")
		if (!$pegawai_d_grid->Recordset->EOF) $pegawai_d_grid->Recordset->MoveNext();
}
?>
<?php
	if ($pegawai_d->CurrentMode == "add" || $pegawai_d->CurrentMode == "copy" || $pegawai_d->CurrentMode == "edit") {
		$pegawai_d_grid->RowIndex = '$rowindex$';
		$pegawai_d_grid->LoadDefaultValues();

		// Set row properties
		$pegawai_d->ResetAttrs();
		$pegawai_d->RowAttrs = array_merge($pegawai_d->RowAttrs, array('data-rowindex'=>$pegawai_d_grid->RowIndex, 'id'=>'r0_pegawai_d', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($pegawai_d->RowAttrs["class"], "ewTemplate");
		$pegawai_d->RowType = EW_ROWTYPE_ADD;

		// Render row
		$pegawai_d_grid->RenderRow();

		// Render list options
		$pegawai_d_grid->RenderListOptions();
		$pegawai_d_grid->StartRowCnt = 0;
?>
	<tr<?php echo $pegawai_d->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pegawai_d_grid->ListOptions->Render("body", "left", $pegawai_d_grid->RowIndex);
?>
	<?php if ($pegawai_d->pegawai_id->Visible) { // pegawai_id ?>
		<td data-name="pegawai_id">
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<?php if ($pegawai_d->pegawai_id->getSessionValue() <> "") { ?>
<span id="el$rowindex$_pegawai_d_pegawai_id" class="form-group pegawai_d_pegawai_id">
<span<?php echo $pegawai_d->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pegawai_d->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" id="x<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" name="x<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($pegawai_d->pegawai_id->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_pegawai_d_pegawai_id" class="form-group pegawai_d_pegawai_id">
<input type="text" data-table="pegawai_d" data-field="x_pegawai_id" name="x<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" id="x<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->pegawai_id->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->pegawai_id->EditValue ?>"<?php echo $pegawai_d->pegawai_id->EditAttributes() ?>>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_pegawai_d_pegawai_id" class="form-group pegawai_d_pegawai_id">
<span<?php echo $pegawai_d->pegawai_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pegawai_d->pegawai_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_pegawai_id" name="x<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" id="x<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($pegawai_d->pegawai_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pegawai_d" data-field="x_pegawai_id" name="o<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" id="o<?php echo $pegawai_d_grid->RowIndex ?>_pegawai_id" value="<?php echo ew_HtmlEncode($pegawai_d->pegawai_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pegawai_d->pend_id->Visible) { // pend_id ?>
		<td data-name="pend_id">
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pegawai_d_pend_id" class="form-group pegawai_d_pend_id">
<input type="text" data-table="pegawai_d" data-field="x_pend_id" name="x<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" id="x<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->pend_id->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->pend_id->EditValue ?>"<?php echo $pegawai_d->pend_id->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pegawai_d_pend_id" class="form-group pegawai_d_pend_id">
<span<?php echo $pegawai_d->pend_id->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pegawai_d->pend_id->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_pend_id" name="x<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" id="x<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" value="<?php echo ew_HtmlEncode($pegawai_d->pend_id->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pegawai_d" data-field="x_pend_id" name="o<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" id="o<?php echo $pegawai_d_grid->RowIndex ?>_pend_id" value="<?php echo ew_HtmlEncode($pegawai_d->pend_id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pegawai_d->gol_darah->Visible) { // gol_darah ?>
		<td data-name="gol_darah">
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pegawai_d_gol_darah" class="form-group pegawai_d_gol_darah">
<input type="text" data-table="pegawai_d" data-field="x_gol_darah" name="x<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" id="x<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->gol_darah->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->gol_darah->EditValue ?>"<?php echo $pegawai_d->gol_darah->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pegawai_d_gol_darah" class="form-group pegawai_d_gol_darah">
<span<?php echo $pegawai_d->gol_darah->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pegawai_d->gol_darah->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_gol_darah" name="x<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" id="x<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" value="<?php echo ew_HtmlEncode($pegawai_d->gol_darah->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pegawai_d" data-field="x_gol_darah" name="o<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" id="o<?php echo $pegawai_d_grid->RowIndex ?>_gol_darah" value="<?php echo ew_HtmlEncode($pegawai_d->gol_darah->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pegawai_d->stat_nikah->Visible) { // stat_nikah ?>
		<td data-name="stat_nikah">
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pegawai_d_stat_nikah" class="form-group pegawai_d_stat_nikah">
<input type="text" data-table="pegawai_d" data-field="x_stat_nikah" name="x<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" id="x<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->stat_nikah->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->stat_nikah->EditValue ?>"<?php echo $pegawai_d->stat_nikah->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pegawai_d_stat_nikah" class="form-group pegawai_d_stat_nikah">
<span<?php echo $pegawai_d->stat_nikah->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pegawai_d->stat_nikah->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_stat_nikah" name="x<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" id="x<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" value="<?php echo ew_HtmlEncode($pegawai_d->stat_nikah->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pegawai_d" data-field="x_stat_nikah" name="o<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" id="o<?php echo $pegawai_d_grid->RowIndex ?>_stat_nikah" value="<?php echo ew_HtmlEncode($pegawai_d->stat_nikah->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pegawai_d->jml_anak->Visible) { // jml_anak ?>
		<td data-name="jml_anak">
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pegawai_d_jml_anak" class="form-group pegawai_d_jml_anak">
<input type="text" data-table="pegawai_d" data-field="x_jml_anak" name="x<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" id="x<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->jml_anak->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->jml_anak->EditValue ?>"<?php echo $pegawai_d->jml_anak->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pegawai_d_jml_anak" class="form-group pegawai_d_jml_anak">
<span<?php echo $pegawai_d->jml_anak->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pegawai_d->jml_anak->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_jml_anak" name="x<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" id="x<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" value="<?php echo ew_HtmlEncode($pegawai_d->jml_anak->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pegawai_d" data-field="x_jml_anak" name="o<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" id="o<?php echo $pegawai_d_grid->RowIndex ?>_jml_anak" value="<?php echo ew_HtmlEncode($pegawai_d->jml_anak->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pegawai_d->alamat->Visible) { // alamat ?>
		<td data-name="alamat">
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pegawai_d_alamat" class="form-group pegawai_d_alamat">
<input type="text" data-table="pegawai_d" data-field="x_alamat" name="x<?php echo $pegawai_d_grid->RowIndex ?>_alamat" id="x<?php echo $pegawai_d_grid->RowIndex ?>_alamat" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pegawai_d->alamat->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->alamat->EditValue ?>"<?php echo $pegawai_d->alamat->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pegawai_d_alamat" class="form-group pegawai_d_alamat">
<span<?php echo $pegawai_d->alamat->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pegawai_d->alamat->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_alamat" name="x<?php echo $pegawai_d_grid->RowIndex ?>_alamat" id="x<?php echo $pegawai_d_grid->RowIndex ?>_alamat" value="<?php echo ew_HtmlEncode($pegawai_d->alamat->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pegawai_d" data-field="x_alamat" name="o<?php echo $pegawai_d_grid->RowIndex ?>_alamat" id="o<?php echo $pegawai_d_grid->RowIndex ?>_alamat" value="<?php echo ew_HtmlEncode($pegawai_d->alamat->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pegawai_d->telp_extra->Visible) { // telp_extra ?>
		<td data-name="telp_extra">
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pegawai_d_telp_extra" class="form-group pegawai_d_telp_extra">
<input type="text" data-table="pegawai_d" data-field="x_telp_extra" name="x<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" id="x<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" size="30" maxlength="20" placeholder="<?php echo ew_HtmlEncode($pegawai_d->telp_extra->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->telp_extra->EditValue ?>"<?php echo $pegawai_d->telp_extra->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pegawai_d_telp_extra" class="form-group pegawai_d_telp_extra">
<span<?php echo $pegawai_d->telp_extra->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pegawai_d->telp_extra->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_telp_extra" name="x<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" id="x<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" value="<?php echo ew_HtmlEncode($pegawai_d->telp_extra->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pegawai_d" data-field="x_telp_extra" name="o<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" id="o<?php echo $pegawai_d_grid->RowIndex ?>_telp_extra" value="<?php echo ew_HtmlEncode($pegawai_d->telp_extra->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pegawai_d->hubungan->Visible) { // hubungan ?>
		<td data-name="hubungan">
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pegawai_d_hubungan" class="form-group pegawai_d_hubungan">
<input type="text" data-table="pegawai_d" data-field="x_hubungan" name="x<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" id="x<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->hubungan->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->hubungan->EditValue ?>"<?php echo $pegawai_d->hubungan->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pegawai_d_hubungan" class="form-group pegawai_d_hubungan">
<span<?php echo $pegawai_d->hubungan->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pegawai_d->hubungan->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_hubungan" name="x<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" id="x<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" value="<?php echo ew_HtmlEncode($pegawai_d->hubungan->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pegawai_d" data-field="x_hubungan" name="o<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" id="o<?php echo $pegawai_d_grid->RowIndex ?>_hubungan" value="<?php echo ew_HtmlEncode($pegawai_d->hubungan->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pegawai_d->nama_hubungan->Visible) { // nama_hubungan ?>
		<td data-name="nama_hubungan">
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pegawai_d_nama_hubungan" class="form-group pegawai_d_nama_hubungan">
<input type="text" data-table="pegawai_d" data-field="x_nama_hubungan" name="x<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" id="x<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" size="30" maxlength="200" placeholder="<?php echo ew_HtmlEncode($pegawai_d->nama_hubungan->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->nama_hubungan->EditValue ?>"<?php echo $pegawai_d->nama_hubungan->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pegawai_d_nama_hubungan" class="form-group pegawai_d_nama_hubungan">
<span<?php echo $pegawai_d->nama_hubungan->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pegawai_d->nama_hubungan->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_nama_hubungan" name="x<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" id="x<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" value="<?php echo ew_HtmlEncode($pegawai_d->nama_hubungan->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pegawai_d" data-field="x_nama_hubungan" name="o<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" id="o<?php echo $pegawai_d_grid->RowIndex ?>_nama_hubungan" value="<?php echo ew_HtmlEncode($pegawai_d->nama_hubungan->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($pegawai_d->agama->Visible) { // agama ?>
		<td data-name="agama">
<?php if ($pegawai_d->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pegawai_d_agama" class="form-group pegawai_d_agama">
<input type="text" data-table="pegawai_d" data-field="x_agama" name="x<?php echo $pegawai_d_grid->RowIndex ?>_agama" id="x<?php echo $pegawai_d_grid->RowIndex ?>_agama" size="30" placeholder="<?php echo ew_HtmlEncode($pegawai_d->agama->getPlaceHolder()) ?>" value="<?php echo $pegawai_d->agama->EditValue ?>"<?php echo $pegawai_d->agama->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el$rowindex$_pegawai_d_agama" class="form-group pegawai_d_agama">
<span<?php echo $pegawai_d->agama->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pegawai_d->agama->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pegawai_d" data-field="x_agama" name="x<?php echo $pegawai_d_grid->RowIndex ?>_agama" id="x<?php echo $pegawai_d_grid->RowIndex ?>_agama" value="<?php echo ew_HtmlEncode($pegawai_d->agama->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pegawai_d" data-field="x_agama" name="o<?php echo $pegawai_d_grid->RowIndex ?>_agama" id="o<?php echo $pegawai_d_grid->RowIndex ?>_agama" value="<?php echo ew_HtmlEncode($pegawai_d->agama->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pegawai_d_grid->ListOptions->Render("body", "right", $pegawai_d_grid->RowCnt);
?>
<script type="text/javascript">
fpegawai_dgrid.UpdateOpts(<?php echo $pegawai_d_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($pegawai_d->CurrentMode == "add" || $pegawai_d->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $pegawai_d_grid->FormKeyCountName ?>" id="<?php echo $pegawai_d_grid->FormKeyCountName ?>" value="<?php echo $pegawai_d_grid->KeyCount ?>">
<?php echo $pegawai_d_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($pegawai_d->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $pegawai_d_grid->FormKeyCountName ?>" id="<?php echo $pegawai_d_grid->FormKeyCountName ?>" value="<?php echo $pegawai_d_grid->KeyCount ?>">
<?php echo $pegawai_d_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($pegawai_d->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fpegawai_dgrid">
</div>
<?php

// Close recordset
if ($pegawai_d_grid->Recordset)
	$pegawai_d_grid->Recordset->Close();
?>
<?php if ($pegawai_d_grid->ShowOtherOptions) { ?>
<div class="panel-footer ewGridLowerPanel">
<?php
	foreach ($pegawai_d_grid->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div>
</div>
<?php } ?>
<?php if ($pegawai_d_grid->TotalRecs == 0 && $pegawai_d->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pegawai_d_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($pegawai_d->Export == "") { ?>
<script type="text/javascript">
fpegawai_dgrid.Init();
</script>
<?php } ?>
<?php
$pegawai_d_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$pegawai_d_grid->Page_Terminate();
?>
