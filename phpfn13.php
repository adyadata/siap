<?php
/**
 * PHPMaker Common classes and functions
 * (C) 2002-2017 e.World Technology Limited. All rights reserved.
*/

// Autoload class
function ew_AutoLoad($class) {
	global $EW_RELATIVE_PATH;
	$file = "";
	if ($class == "GD") {
		$file = "phpinc/PHPThumb.php";
	} elseif ($class == "Html2Text\Html2Text") {
		$file = "html2text/html2text.php";
	} elseif (preg_match('/^c\w+_db$/', $class)) {
		$file = "ewdbhelper13.php";
	} elseif (ew_StartsStr("c", $class)) {
		$file = str_replace("%cls%", substr($class, 1), "%cls%info.php");
	}
	if ($file <> "" && file_exists($EW_RELATIVE_PATH . $file))
		include_once $EW_RELATIVE_PATH . $file;
}

// Create Database helper class
function &DbHelper($dbid = "") {
	$dbclass = "cdb_siap_db";
	$dbhelper = new $dbclass();
	return $dbhelper;
}
spl_autoload_register("ew_AutoLoad");

// Get page object
function &Page($tblname = "") {
	if (!$tblname)
		return $GLOBALS["Page"];
	foreach ($GLOBALS as $k => $v) {
		if (is_object($v) && $k == $tblname)
			return $GLOBALS[$k];
	}
	$res = NULL;
	return $res;
}

// Get current language ID
function CurrentLanguageID() {
	return $GLOBALS["gsLanguage"];
}

// Get current project ID
function CurrentProjectID() {
	if (isset($GLOBALS["Page"]))
		return $GLOBALS["Page"]->ProjectID;
	return "{035CBF11-745C-4982-814A-B6768131C8FC}";
}

// Get current page object
function &CurrentPage() {
	return $GLOBALS["Page"];
}

// Get user table object
function &UserTable() {
	return $GLOBALS["UserTable"];
}

// Get current main table object
function &CurrentTable() {
	return $GLOBALS["Table"];
}

// Get current master table object
function &CurrentMasterTable() {
	$res = NULL;
	$tbl = &CurrentTable();
	if ($tbl && method_exists($tbl, "getCurrentMasterTable") && $tbl->getCurrentMasterTable() <> "")
		$res = $GLOBALS[$tbl->getCurrentMasterTable()];
	return $res;
}

// Get current detail table object
function &CurrentDetailTable() {
	return $GLOBALS["Grid"];
}

// Get PHP errors
function ew_ErrorHandler($errno, $errstr, $errfile, $errline) {
	switch ($errno) {
		case E_USER_ERROR:
		case E_RECOVERABLE_ERROR:
			ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $errstr . ", file: " . $errfile . ", line: " . $errline);
			break;
		case E_WARNING:
		case E_USER_WARNING:
			ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $errstr . ", file: " . $errfile . ", line: " . $errline);
			break;

		//case E_NOTICE: // Skip
		case E_USER_NOTICE:
		case E_STRICT:
		case E_DEPRECATED:
		case E_USER_DEPRECATED:
			ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $errstr . ", file: " . $errfile . ", line: " . $errline);
			break;
		default:
			break;
	}
	return FALSE; // Restore standard PHP error handler
}
/**
 * Export document classes
 */

// Get export document object
function &ew_ExportDocument(&$tbl, $style) {
	global $EW_EXPORT;
	$inst = NULL;
	$type = strtolower($tbl->Export);
	$class = $EW_EXPORT[$type];
	if (class_exists($class))
		$inst = new $class($tbl, $style);
	return $inst;
}

//
// Base class for export
//
class cExportBase {
	var $Table;
	var $Text;
	var $Line = "";
	var $Header = "";
	var $Style = "h"; // "v"(Vertical) or "h"(Horizontal)
	var $Horizontal = TRUE; // Horizontal
	var $RowCnt = 0;
	var $FldCnt = 0;
	var $ExportCustom = FALSE;

	// Constructor
	function __construct(&$tbl = NULL, $style = "") {
		$this->Table = $tbl;
		$this->SetStyle($style);
	}

	// Style
	function SetStyle($style) {
		if (strtolower($style) == "v" || strtolower($style) == "h")
			$this->Style = strtolower($style);		
		$this->Horizontal = ($this->Style <> "v");
	}

	// Field caption
	function ExportCaption(&$fld) {
		$this->FldCnt++;
		$this->ExportValueEx($fld, $fld->ExportCaption());
	}

	// Field value
	function ExportValue(&$fld) {
		$this->ExportValueEx($fld, $fld->ExportValue());
	}

	// Field aggregate
	function ExportAggregate(&$fld, $type) {
		$this->FldCnt++;
		if ($this->Horizontal) {
			global $Language;
			$val = "";
			if (in_array($type, array("TOTAL", "COUNT", "AVERAGE")))
				$val = $Language->Phrase($type) . ": " . $fld->ExportValue();
			$this->ExportValueEx($fld, $val);
		}
	}

	// Get meta tag for charset
	function CharsetMetaTag() {
		return "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" . EW_CHARSET . "\">\r\n";
	}

	// Table header
	function ExportTableHeader() {
		$this->Text .= "<table class=\"ewExportTable\">";
	}

	// Cell styles
	function CellStyles($fld, $usestyle = TRUE) {
		return ($usestyle && EW_EXPORT_CSS_STYLES) ? $fld->CellStyles() : "";
	}

	// Row styles
	function RowStyles($usestyle = TRUE) {
		return ($usestyle && EW_EXPORT_CSS_STYLES) ? $this->Table->RowStyles() : "";
	}

	// Export a value (caption, field value, or aggregate)
	function ExportValueEx(&$fld, $val, $usestyle = TRUE) {
		$this->Text .= "<td" . $this->CellStyles($fld, $usestyle) . ">";
		$this->Text .= strval($val);
		$this->Text .= "</td>";
	}

	// Begin a row
	function BeginExportRow($rowcnt = 0, $usestyle = TRUE) {
		$this->RowCnt++;
		$this->FldCnt = 0;
		if ($this->Horizontal) {
			if ($rowcnt == -1) {
				$this->Table->CssClass = "ewExportTableFooter";
			} elseif ($rowcnt == 0) {
				$this->Table->CssClass = "ewExportTableHeader";
			} else {
				$this->Table->CssClass = (($rowcnt % 2) == 1) ? "ewExportTableRow" : "ewExportTableAltRow";
			}
			$this->Text .= "<tr" . $this->RowStyles($usestyle) . ">";
		}
	}

	// End a row
	function EndExportRow() {
		if ($this->Horizontal)
			$this->Text .= "</tr>";
	}

	// Empty row
	function ExportEmptyRow() {
		$this->RowCnt++;
		$this->Text .= "<br>";
	}

	// Page break
	function ExportPageBreak() {
	}

	// Export a field
	function ExportField(&$fld) {
		$this->FldCnt++;
		$wrkExportValue = "";
		if ($fld->HrefValue2 <> "" && is_object($fld->Upload)) { // Upload field
			if (!ew_Empty($fld->Upload->DbValue))
				$wrkExportValue = ew_GetFileATag($fld, $fld->HrefValue2);
		} else {
			$wrkExportValue = $fld->ExportValue();
		}
		if ($this->Horizontal) {
			$this->ExportValueEx($fld, $wrkExportValue);
		} else { // Vertical, export as a row
			$this->RowCnt++;
			$this->Text .= "<tr class=\"" . (($this->FldCnt % 2 == 1) ? "ewExportTableRow" : "ewExportTableAltRow") . "\">" .
				"<td>" . $fld->ExportCaption() . "</td>";
			$this->Text .= "<td" . $this->CellStyles($fld) . ">" . $wrkExportValue . "</td></tr>";
		}
	}

	// Table Footer
	function ExportTableFooter() {
		$this->Text .= "</table>";
	}

	// Add HTML tags
	function ExportHeaderAndFooter() {
		$header = "<html><head>\r\n";
		$header .= $this->CharsetMetaTag();
		if (EW_EXPORT_CSS_STYLES && EW_PROJECT_STYLESHEET_FILENAME <> "")
			$header .= "<style type=\"text/css\">" . file_get_contents(EW_PROJECT_STYLESHEET_FILENAME) . "</style>\r\n";
		$header .= "</" . "head>\r\n<body>\r\n";
		$this->Text = $header . $this->Text . "</body></html>";
	}

	// Export
	function Export() {
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();
		if (strtolower(EW_CHARSET) == "utf-8")
			echo "\xEF\xBB\xBF";
		echo $this->Text;
	}
}

// Get file img tag
function ew_GetFileImgTag($fld, $fn) {
	$html = "";
	if ($fn <> "") {
		if ($fld->UploadMultiple) {
			$wrkfiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $fn);
			foreach ($wrkfiles as $wrkfile) {
				if ($wrkfile <> "") {
					if ($html <> "")
						$html .= "<br>";
					$html .= "<img class=\"ewImage\" src=\"" . $wrkfile . "\" alt=\"\">";
				}
			}
		} else {
			$html = "<img class=\"ewImage\" src=\"" . $fn . "\" alt=\"\">";
		}
	}
	return $html;
}

// Get file A tag
function ew_GetFileATag($fld, $fn) {
	$wrkfiles = array();
	$wrkpath = "";
	$html = "";
	if ($fld->FldDataType == EW_DATATYPE_BLOB) {
		if (!ew_Empty($fld->Upload->DbValue))
			$wrkfiles = array($fn);
	} elseif ($fld->UploadMultiple) {
		$wrkfiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $fn);
		$pos = strrpos($wrkfiles[0], '/');
		if ($pos !== FALSE) {
			$wrkpath = substr($wrkfiles[0], 0, $pos+1); // Get path from first file name
			$wrkfiles[0] = substr($wrkfiles[0], $pos+1);
		}
	} else {
		if (!ew_Empty($fld->Upload->DbValue))
			$wrkfiles = array($fn);
	}
	foreach ($wrkfiles as $wrkfile) {
		if ($wrkfile <> "") {
			if ($html <> "")
				$html .= "<br>";
			$attrs = array("href" => ew_ConvertFullUrl($wrkpath . $wrkfile));
			$html .= ew_HtmlElement("a", $attrs, $fld->FldCaption());
		}
	}
	return $html;
}

// Get file temp image
function ew_GetFileTempImage($fld, $val) {
	if ($fld->UploadMultiple) {
		$files = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $val);
		$cnt = count($files);
		$images = "";
		for ($i = 0; $i < $cnt; $i++) {
			if ($files[$i] <> "") {
				$tmpimage = file_get_contents(ew_GetFileUploadUrl($fld, $files[$i], FALSE, FALSE, FALSE));
				if ($fld->ImageResize)
					ew_ResizeBinary($tmpimage, $fld->ImageWidth, $fld->ImageHeight);
				if ($images <> "") $images .= EW_MULTIPLE_UPLOAD_SEPARATOR;
				$images .= ew_TmpImage($tmpimage);
			}
		}
		return $images;
	} else {
		$tmpimage = file_get_contents(ew_GetFileUploadUrl($fld, $val, FALSE, FALSE, FALSE));
		if ($fld->ImageResize)
			ew_ResizeBinary($tmpimage, $fld->ImageWidth, $fld->ImageHeight);
		return ew_TmpImage($tmpimage);
	}
}

// Get file upload url
function ew_GetFileUploadUrl($fld, $val, $resize = FALSE, $encrypt = EW_ENCRYPT_FILE_PATH, $urlencode = TRUE) {
	if (!ew_EmptyStr($val)) {
		$path = ($encrypt || $resize) ? ew_IncludeTrailingDelimiter($fld->UploadPath, FALSE) : ew_UploadPathEx(FALSE, $fld->UploadPath);
		if ($encrypt) {
			$key = EW_RANDOM_KEY . session_id();
			$fn = "ewfile13.php?t=" . ew_Encrypt($fld->TblName, $key) ."&fn=" . ew_Encrypt($path . $val, $key);
			if ($resize)
				$fn .= "&width=" . $fld->ImageWidth . "&height=" . $fld->ImageHeight;
		} elseif ($resize) {
			$fn = "ewfile13.php?t=" . rawurlencode($fld->TblName) . "&fn=" . ew_UrlEncodeFilePath($path . $val) .
				"&width=" . $fld->ImageWidth . "&height=" . $fld->ImageHeight;
		} else {
			$fn = $path . $val;
			if ($urlencode)
				$fn = ew_UrlEncodeFilePath($fn);
		}
		return $fn;
	} else {
		return "";
	}
}

// URL Encode file path
function ew_UrlEncodeFilePath($path) {
	$ar = explode("/", $path);
	$cnt = count($ar);
	for ($i = 0; $i < $cnt; $i++)
		$ar[$i] = rawurlencode($ar[$i]);
	return implode("/", $ar);
}

// Get file view tag
function ew_GetFileViewTag(&$fld, $val) {
	global $Page;
	if (!ew_EmptyStr($val)) {
		if ($fld->FldDataType == EW_DATATYPE_BLOB) {
			$wrknames = array($val);
			$wrkfiles = array($val);
		} elseif ($fld->UploadMultiple) {
			$wrknames = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $val);
			$wrkfiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $fld->Upload->DbValue);
		} else {
			$wrknames = array($val);
			$wrkfiles = array($fld->Upload->DbValue);
		}
		$bMultiple = (count($wrkfiles) > 1);
		$href = $fld->HrefValue;
		$images = "";
		$wrkcnt = 0;
		foreach ($wrkfiles as $wrkfile) {
			$image = "";
			if ($fld->FldDataType == EW_DATATYPE_BLOB)
				$fn = $val;
			elseif ($Page && ($Page->TableType == "REPORT" && ($Page->Export == "excel" && defined('EW_USE_PHPEXCEL') || $Page->Export == "word" && defined('EW_USE_PHPWORD')) || $Page->TableType <> "REPORT" && ($Page->CustomExport == "pdf" || $Page->CustomExport == "email")))
				$fn = ew_GetFileTempImage($fld, $wrkfile);
			else
				$fn = ew_GetFileUploadUrl($fld, $wrkfile, $fld->ImageResize);
			if ($fld->FldViewTag == "IMAGE" && ($fld->IsBlobImage || ew_IsImageFile($wrkfile))) {
				if ($href == "" && !$fld->UseColorbox) {
					if ($fn <> "")
						$image = "<img class=\"ewImage img-thumbnail\" alt=\"\" src=\"" . $fn . "\"" . $fld->ViewAttributes() . ">";
				} else {
					if ($fld->UploadMultiple && strpos($href, "%u") !== FALSE)
						$fld->HrefValue = str_replace("%u", ew_GetFileUploadUrl($fld, $wrkfile), $href);
					if ($fn <> "")
						$image = "<a" . $fld->LinkAttributes() . "><img class=\"ewImage img-thumbnail\" alt=\"\" src=\"" . $fn . "\"" . $fld->ViewAttributes() . "></a>";
				}
			} else {
				if ($fld->FldDataType == EW_DATATYPE_BLOB) {
					$url = $href;
					$name = ($fld->Upload->FileName <> "") ? $fld->Upload->FileName : $fld->FldCaption();
				} else {
					$url = ew_GetFileUploadUrl($fld, $wrkfile);
					$cnt = count($wrknames);
					$name = ($wrkcnt < $cnt) ? $wrknames[$wrkcnt] : $wrknames[$cnt-1];
				}
				if ($url <> "") {
					if ($fld->UploadMultiple && strpos($href, "%u") !== FALSE)
						$fld->HrefValue = str_replace("%u", $url, $href);
					$image = "<a" . $fld->LinkAttributes() . ">" . $name . "</a>";
				}
			}
			if ($image <> "") {
				if ($bMultiple)
					$images .= "<li>" . $image . "</li>";
				else
					$images .= $image;
			}
			$wrkcnt += 1;
		}
		if ($bMultiple && $images <> "")
			$images = "<ul class=\"list-inline\">" . $images . "</ul>";
		return $images;
	} else {
		return "";
	}
}

// Get image view tag
function ew_GetImgViewTag(&$fld, $val) {
	if (!ew_EmptyStr($val)) {
		$href = $fld->HrefValue;
		$image = $val;
		if ($val != "" && strpos($val, "://") === FALSE && strpos($val, "\\") === FALSE && strpos($val, "javascript:") === FALSE)
			$fn = ew_GetImageUrl($fld, $val, $fld->ImageResize);
		else
			$fn = $val;
		if (ew_IsImageFile($val)) {
			if ($href == "" && !$fld->UseColorbox) {
				if ($fn <> "")
					$image = "<img class=\"ewImage img-thumbnail\" alt=\"\" src=\"" . $fn . "\"" . $fld->ViewAttributes() . ">";
			} else {
				if ($fn <> "")
					$image = "<a" . $fld->LinkAttributes() . "><img class=\"ewImage img-thumbnail\" alt=\"\" src=\"" . $fn . "\"" . $fld->ViewAttributes() . "></a>";
			}
		} else {
			$name = $val;
			if ($href <> "")
				$image = "<a" . $fld->LinkAttributes() . ">" . $name . "</a>";
			else
				$image = $name;
		}
		return $image;
	} else {
		return "";
	}
}

// Get image url
function ew_GetImageUrl($fld, $val, $resize = FALSE, $encrypt = EW_ENCRYPT_FILE_PATH, $urlencode = TRUE) {
	if (!ew_EmptyStr($val)) {
		if ($encrypt) {
			$key = EW_RANDOM_KEY . session_id();
			$fn = "ewfile13.php?t=" . ew_Encrypt($fld->TblName, $key) ."&fn=" . ew_Encrypt($val, $key);
			if ($resize)
				$fn .= "&width=" . $fld->ImageWidth . "&height=" . $fld->ImageHeight;
		} elseif ($resize) {
			$fn = "ewfile13.php?t=" . rawurlencode($fld->TblName) . "&fn=" . ew_UrlEncodeFilePath($val) .
				"&width=" . $fld->ImageWidth . "&height=" . $fld->ImageHeight;
		} else {
			$fn = $val;
			if ($urlencode)
				$fn = ew_UrlEncodeFilePath($fn);
		}
		return $fn;
	} else {
		return "";
	}
}

// Check if image file
function ew_IsImageFile($fn) {
	if ($fn <> "") {
		$ar = parse_url($fn);
		if ($ar && array_key_exists('query', $ar)) { // Thumbnail url
 			if ($q = parse_str($ar['query']))
				$fn = $q['fn'];
		}
		$pathinfo = pathinfo($fn);
		$ext = strtolower(@$pathinfo["extension"]);
		return in_array($ext, explode(",", EW_IMAGE_ALLOWED_FILE_EXT));
	} else {
		return FALSE;
	}
}

//
// Class for export to email
// 
class cExportEmail extends cExportBase {

	// Table border styles
	var $cellStyles = "border: 1px solid #dddddd; padding: 5px;";

	// Table header
	function ExportTableHeader() {
		$this->Text .= "<table style=\"border-collapse: collapse;\">"; // Use inline style for Gmail
	}

	// Cell styles
	function CellStyles($fld, $usestyle = TRUE) {
		$fld->CellAttrs["style"] = ew_Concat($this->cellStyles, @$fld->CellAttrs["style"], ";"); // Use inline style for Gmail
		return ($usestyle && EW_EXPORT_CSS_STYLES) ? $fld->CellStyles() : "";
	}

	// Export a field
	function ExportField(&$fld) {
		$this->FldCnt++;
		$ExportValue = $fld->ExportValue();
		if ($fld->FldViewTag == "IMAGE") {
			if ($fld->ImageResize) {
				$ExportValue = ew_GetFileImgTag($fld, $fld->GetTempImage());
			} elseif ($fld->HrefValue2 <> "" && is_object($fld->Upload)) {
				if (!ew_Empty($fld->Upload->DbValue))
					$ExportValue = ew_GetFileATag($fld, $fld->HrefValue2);
			}
		} elseif (is_array($fld->HrefValue2)) { // Export custom view tag
			$ar = $fld->HrefValue2;
			$fn = is_array($ar) ? @$ar["exportfn"] : ""; // Get export function name
			if (is_callable($fn)) $ExportValue = $fn($ar);
		}
		if ($this->Horizontal) {
			$this->ExportValueEx($fld, $ExportValue);
		} else { // Vertical, export as a row
			$this->RowCnt++;
			$this->Text .= "<tr class=\"" . (($this->FldCnt % 2 == 1) ? "ewExportTableRow" : "ewExportTableAltRow") . "\">" .
				"<td>" . $fld->ExportCaption() . "</td>";
			$this->Text .= "<td" . $this->CellStyles($fld) . ">" . $ExportValue . "</td></tr>";
		}
	}

	// Export
	function Export() {
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();
		echo $this->Text;
	}

	// Destructor
	function __destruct() {
		ew_DeleteTmpImages();
	}
}

//
// Class for export to HTML
// 
class cExportHtml extends cExportBase {

	// Same as base class
}

//
// Class for export to Word
// 
class cExportWord extends cExportBase {

	// Export
	function Export() {
		global $gsExportFile;
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();
		header('Content-Type: application/vnd.ms-word' . ((EW_CHARSET <> "") ? ";charset=" . EW_CHARSET : ""));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.doc');
		if (strtolower(EW_CHARSET) == "utf-8")
			echo "\xEF\xBB\xBF";
		echo $this->Text;
	}
}

//
// Class for export to Excel
// 
class cExportExcel extends cExportBase {

	// Export a value (caption, field value, or aggregate)
	function ExportValueEx(&$fld, $val, $usestyle = TRUE) {
		if (($fld->FldDataType == EW_DATATYPE_STRING || $fld->FldDataType == EW_DATATYPE_MEMO) && is_numeric($val))
			$val = "=\"" . strval($val) . "\"";
		$this->Text .= parent::ExportValueEx($fld, $val, $usestyle);
	}

	// Export
	function Export() {
		global $gsExportFile;
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();
		header('Content-Type: application/vnd.ms-excel' . ((EW_CHARSET <> "") ? ";charset=" . EW_CHARSET : ""));
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.xls');
		if (strtolower(EW_CHARSET) == "utf-8")
			echo "\xEF\xBB\xBF";
		echo $this->Text;
	}
}

//
// Class for export to CSV
// 
class cExportCsv extends cExportBase {
	var $QuoteChar = "\"";

	// Style
	function ChangeStyle($style) {
		$this->Horizontal = TRUE;
	}

	// Table header
	function ExportTableHeader() {

		// Skip
	}

	// Export a value (caption, field value, or aggregate)
	function ExportValueEx(&$fld, $val, $usestyle = TRUE) {
		if ($fld->FldDataType <> EW_DATATYPE_BLOB) {
			if ($this->Line <> "")
				$this->Line .= ",";
			$this->Line .= $this->QuoteChar . str_replace($this->QuoteChar, $this->QuoteChar . $this->QuoteChar, strval($val)) . $this->QuoteChar;
		}
	}

	// Begin a row
	function BeginExportRow($rowcnt = 0, $usestyle = TRUE) {
		$this->Line = "";
	}

	// End a row
	function EndExportRow() {
		$this->Line .= "\r\n";
		$this->Text .= $this->Line;
	}

	// Empty line
	function ExportEmptyLine() {

		// Skip
	}

	// Export a field
	function ExportField(&$fld) {
		if ($fld->UploadMultiple)
			$this->ExportValueEx($fld, $fld->Upload->DbValue);
		else
			$this->ExportValue($fld);
	}

	// Table Footer
	function ExportTableFooter() {

		// Skip
	}

	// Add HTML tags
	function ExportHeaderAndFooter() {

		// Skip
	}

	// Export
	function Export() {
		global $gsExportFile;
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename=' . $gsExportFile . '.csv');
		if (strtolower(EW_CHARSET) == "utf-8")
			echo "\xEF\xBB\xBF";
		echo $this->Text;
	}
}

//
// Class for export to XML
//
class cExportXml extends cExportBase {
	var $XmlDoc;
	var $HasParent;

	// Constructor
	function __construct(&$tbl = NULL, $style = "") {
		parent::__construct($tbl, $style);
		$this->XmlDoc = new cXMLDocument(EW_XML_ENCODING);
	}

	// Style
	function SetStyle($style) {}

	// Field caption
	function ExportCaption(&$fld) {}

	// Field value
	function ExportValue(&$fld) {}

	// Field aggregate
	function ExportAggregate(&$fld, $type) {}

	// Get meta tag for charset
	function CharsetMetaTag() {}

	// Table header
	function ExportTableHeader() {
		$this->HasParent = is_object($this->XmlDoc->DocumentElement());
		if (!$this->HasParent)
			$this->XmlDoc->AddRoot($this->Table->TableVar);
	}

	// Export a value (caption, field value, or aggregate)
	function ExportValueEx(&$fld, $val, $usestyle = TRUE) {}

	// Begin a row
	function BeginExportRow($rowcnt = 0, $usestyle = TRUE) {
		if ($rowcnt <= 0)
			return; 
		if ($this->HasParent)
			$this->XmlDoc->AddRow($this->Table->TableVar);
		else
			$this->XmlDoc->AddRow();
	}

	// End a row
	function EndExportRow() {}

	// Empty row
	function ExportEmptyRow() {}

	// Page break
	function ExportPageBreak() {}

	// Export a field
	function ExportField(&$fld) {
		if ($fld->FldDataType <> EW_DATATYPE_BLOB) {
			if ($fld->UploadMultiple)
				$ExportValue = $fld->Upload->DbValue;
			else
				$ExportValue = $fld->ExportValue();
			if (is_null($ExportValue))
				$ExportValue = "<Null>";
			$this->XmlDoc->AddField(substr($fld->FldVar, 2), $ExportValue);
		}
	}

	// Table Footer
	function ExportTableFooter() {}

	// Add HTML tags
	function ExportHeaderAndFooter() {}

	// Export
	function Export() {
		global $gsExportFile;
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();
		header('Content-Type: text/xml');

		//header('Content-Disposition: attachment; filename=' . $gsExportFile . '.xml');
		echo $this->XmlDoc->XML();
	}
}

// Include dompdf
include_once "dompdf070/src/Autoloader.php";
$DompdfAutoloader = new \Dompdf\Autoloader();
$DompdfAutoloader->register();

//
// Class for export to PDF
//
class cExportPdf extends cExportBase {

	// Table header
	function ExportTableHeader() {
		$this->Text .= "<table class=\"ewTable\">\r\n";
	}

	// Export a value (caption, field value, or aggregate)
	function ExportValueEx(&$fld, $val, $usestyle = TRUE) {
		$wrkval = strval($val);
		$wrkval = "<td" . (($usestyle && EW_EXPORT_CSS_STYLES) ? $fld->CellStyles() : "") . ">" . $wrkval . "</td>\r\n";
		$this->Line .= $wrkval;
		$this->Text .= $wrkval;
	}

	// Begin a row
	function BeginExportRow($rowcnt = 0, $usestyle = TRUE) {
		$this->FldCnt = 0;
		if ($this->Horizontal) {
			if ($rowcnt == -1)
				$this->Table->CssClass = "ewTableFooter";
			elseif ($rowcnt == 0)
				$this->Table->CssClass = "ewTableHeader";
			else
				$this->Table->CssClass = (($rowcnt % 2) == 1) ? "ewTableRow" : "ewTableAltRow";
			$this->Line = "<tr" . (($usestyle && EW_EXPORT_CSS_STYLES) ? $this->Table->RowStyles() : "") . ">";
			$this->Text .= $this->Line;
		}
	}

	// End a row
	function EndExportRow() {
		if ($this->Horizontal) {
			$this->Line .= "</tr>";
			$this->Text .= "</tr>";
			$this->Header = $this->Line;
		}
	}

	// Page break
	function ExportPageBreak() {
		if ($this->Horizontal) {
			$this->Text .= "</table>\r\n"; // end current table
			$this->Text .= "<p style=\"page-break-after:always;\">&nbsp;</p>\r\n"; // page break
			$this->Text .= "<table class=\"ewTable ewTableBorder\">\r\n"; // new page header
			$this->Text .= $this->Header;
		}
	}

	// Export a field
	function ExportField(&$fld) {
		$ExportValue = $fld->ExportValue();
		if ($fld->FldViewTag == "IMAGE") {
			$ExportValue = ew_GetFileImgTag($fld, $fld->GetTempImage());
		} elseif (is_array($fld->HrefValue2)) { // Export custom view tag
			$ar = $fld->HrefValue2;
			$fn = is_array($ar) ? @$ar["exportfn"] : ""; // Get export function name
			if (is_callable($fn)) $ExportValue = $fn($ar);
		} else {
			$ExportValue = str_replace("<br>", "\r\n", $ExportValue);
			$ExportValue = strip_tags($ExportValue);
			$ExportValue = str_replace("\r\n", "<br>", $ExportValue);
		}
		if ($this->Horizontal) {
			$this->ExportValueEx($fld, $ExportValue);
		} else { // Vertical, export as a row
			$this->FldCnt++;
			$fld->CellCssClass = ($this->FldCnt % 2 == 1) ? "ewTableRow" : "ewTableAltRow";
			$this->Text .= "<tr><td" . ((EW_EXPORT_CSS_STYLES) ? $fld->CellStyles() : "") . ">" . $fld->ExportCaption() . "</td>";
			$this->Text .= "<td" . ((EW_EXPORT_CSS_STYLES) ? $fld->CellStyles() : "") . ">" .
				$ExportValue . "</td></tr>";
		}
	}

	// Add HTML tags
	function ExportHeaderAndFooter() {
		$header = "<html><head>\r\n";
		$header .= $this->CharsetMetaTag();
		if (EW_PDF_STYLESHEET_FILENAME <> "")
			$header .= "<style type=\"text/css\">" . file_get_contents(EW_PDF_STYLESHEET_FILENAME) . "</style>\r\n";
		$header .= "</" . "head>\r\n<body>\r\n";
		$this->Text = $header . $this->Text . "</body></html>";
	}

	// Export
	function Export() {
		global $gsExportFile;
		@ini_set("memory_limit", EW_PDF_MEMORY_LIMIT);
		set_time_limit(EW_PDF_TIME_LIMIT);
		$txt = $this->Text;
		if (EW_DEBUG_ENABLED) // Add debug message
			$txt = str_replace("</body>", ew_DebugMsg() . "</body>", $txt);
		$dompdf = new \Dompdf\Dompdf(array("pdf_backend" => "Cpdf"));
		$dompdf->load_html($txt);
		$dompdf->set_paper($this->Table->ExportPageSize, $this->Table->ExportPageOrientation);
		$dompdf->render();
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();
		$dompdf->stream($gsExportFile, array("Attachment" => 1)); // 0 to open in browser, 1 to download
		ew_DeleteTmpImages();
	}

	// Destructor
	function __destruct() {
		ew_DeleteTmpImages();
	}
}
/**
 * Email class
 */

class cEmail {

	// Class properties
	var $Sender = ""; // Sender
	var $Recipient = ""; // Recipient
	var $Cc = ""; // Cc
	var $Bcc = ""; // Bcc
	var $Subject = ""; // Subject
	var $Format = ""; // Format
	var $Content = ""; // Content
	var $Attachments = array(); // Attachments
	var $EmbeddedImages = array(); // Embedded image
	var $Charset = EW_EMAIL_CHARSET; // Charset
	var $SendErrDescription; // Send error description
	var $SmtpSecure = EW_SMTP_SECURE_OPTION; // Send secure option
	var $Prop = array(); // PHPMailer properties

	// Set PHPMailer property
	function __set($name, $value) {
		$this->Prop[$name] = $value;
	}

	// Method to load email from template
	function Load($fn, $langid = "") {
		global $gsLanguage;
		$langid = ($langid == "") ? $gsLanguage : $langid;
		$pos = strrpos($fn, '.');
		if ($pos !== FALSE) {
			$wrkname = substr($fn, 0, $pos); // Get file name
			$wrkext = substr($fn, $pos+1); // Get file extension
			$wrkpath = ew_ScriptFolder() . EW_PATH_DELIMITER . EW_EMAIL_TEMPLATE_PATH . EW_PATH_DELIMITER; // Get file path
			$ar = ($langid <> "") ? array("_" . $langid, "-" . $langid, "") : array("");
			$exist = FALSE;
			foreach ($ar as $suffix) {
				$wrkfile = $wrkpath . $wrkname . $suffix . "." . $wrkext;
				$exist = file_exists($wrkfile);
				if ($exist) break;
			}
			if (!$exist) return;
			$sWrk = file_get_contents($wrkfile); // Load template file content
			if (ew_StartsStr("\xEF\xBB\xBF", $sWrk)) // UTF-8 BOM
				$sWrk = substr($sWrk, 3);
			$wrkid = $wrkname . "_content";
			if (ew_ContainsStr($sWrk, $wrkid)) { // Replace content
				$wrkfile = $wrkpath . $wrkid . "." . $wrkext;
				if (file_exists($wrkfile)) {
					$sContent = file_get_contents($wrkfile);
					if (ew_StartsStr("\xEF\xBB\xBF", $sContent)) // UTF-8 BOM
						$sContent = substr($sContent, 3);
					$sWrk = str_replace("<!--" . $wrkid . "-->", $sContent, $sWrk);
				}
			}
		}
		if ($sWrk <> "" && preg_match('/\n\n|\r\n\r\n/', $sWrk, $m, PREG_OFFSET_CAPTURE)) { // Locate Header & Mail Content
			$i = $m[0][1];
			$sHeader = trim(substr($sWrk, 0, $i)) . "\r\n"; // Add last CrLf for matching
			$this->Content = trim(substr($sWrk, $i));
			if (preg_match_all('/^\s*(Subject|From|To|Cc|Bcc|Format)\s*:([^\r\n]*)[\r\n]/m', $sHeader, $m)) {
				$ar = array_combine($m[1], $m[2]);
				$this->Subject = trim(@$ar["Subject"]);
				$this->Sender = trim(@$ar["From"]);
				$this->Recipient = trim(@$ar["To"]);
				$this->Cc = trim(@$ar["Cc"]);
				$this->Bcc = trim(@$ar["Bcc"]);
				$this->Format = trim(@$ar["Format"]);
			}
		}
	}

	// Method to replace sender
	function ReplaceSender($ASender) {
		if (ew_ContainsStr($this->Sender, '<!--$From-->'))
			$this->Sender = str_replace('<!--$From-->', $ASender, $this->Sender);
		else
			$this->Sender = $ASender;
	}

	// Method to replace recipient
	function ReplaceRecipient($ARecipient) {
		if (ew_ContainsStr($this->Recipient, '<!--$To-->'))
			$this->Recipient = str_replace('<!--$To-->', $ARecipient, $this->Recipient);
		else
			$this->AddRecipient($ARecipient);
	}

	// Method to add recipient
	function AddRecipient($ARecipient) {
		$this->Recipient = ew_Concat($this->Recipient, $ARecipient, ";");
	}

	// Method to add Cc email
	function AddCc($ACc) {
		$this->Cc = ew_Concat($this->Cc, $ACc, ";");
	}

	// Method to add Bcc email
	function AddBcc($ABcc) {
		$this->Bcc = ew_Concat($this->Bcc, $ABcc, ";");
	}

	// Method to replace subject
	function ReplaceSubject($ASubject) {
		if (ew_ContainsStr($this->Subject, '<!--$Subject-->'))
			$this->Subject = str_replace('<!--$Subject-->', $ASubject, $this->Subject);
		else
			$this->Subject = $ASubject;
	}

	// Method to replace content
	function ReplaceContent($Find, $ReplaceWith) {
		$this->Content = str_replace($Find, $ReplaceWith, $this->Content);
	}

	// Method to add embedded image
	function AddEmbeddedImage($image) {
		if ($image <> "")
			$this->EmbeddedImages[] = $image;
	}

	// Method to add attachment
	function AddAttachment($filename, $content = "") {
		if ($filename <> "")
			$this->Attachments[] = array("filename" => $filename, "content" => $content);
	}

	// Method to send email
	function Send() {
		global $gsEmailErrDesc;
		$result = ew_SendEmail($this->Sender, $this->Recipient, $this->Cc, $this->Bcc,
			$this->Subject, $this->Content, $this->Format, $this->Charset, $this->SmtpSecure,
			$this->Attachments, $this->EmbeddedImages, $this->Prop);
		$this->SendErrDescription = $gsEmailErrDesc;
		return $result;
	}
}
/**
 * Pager item class
 */

class cPagerItem {
	var $Start;
	var $Text;
	var $Enabled;
}
/**
 * Numeric pager class
 */

class cNumericPager {
	var $Items = array();
	var $Count, $FromIndex, $ToIndex, $RecordCount, $PageSize, $Range;
	var $FirstButton, $PrevButton, $NextButton, $LastButton;
	var $ButtonCount = 0;
	var $AutoHidePager = TRUE;
	var $Visible = TRUE;

	// Constructor
	function __construct($StartRec, $DisplayRecs, $TotalRecs, $RecRange, $AutoHidePager = EW_AUTO_HIDE_PAGER)
	{
		$this->AutoHidePager = $AutoHidePager;
		if ($this->AutoHidePager && $StartRec == 1 && $TotalRecs <= $DisplayRecs)
			$this->Visible = FALSE;
		$this->FirstButton = new cPagerItem;
		$this->PrevButton = new cPagerItem;
		$this->NextButton = new cPagerItem;
		$this->LastButton = new cPagerItem;
		$this->FromIndex = intval($StartRec);
		$this->PageSize = intval($DisplayRecs);
		$this->RecordCount = intval($TotalRecs);
		$this->Range = intval($RecRange);
		if ($this->PageSize == 0) return;
		if ($this->FromIndex > $this->RecordCount)
			$this->FromIndex = $this->RecordCount;
		$this->ToIndex = $this->FromIndex + $this->PageSize - 1;
		if ($this->ToIndex > $this->RecordCount)
			$this->ToIndex = $this->RecordCount;

		// Setup
		$this->SetupNumericPager();

		// Update button count
		if ($this->FirstButton->Enabled) $this->ButtonCount++;
		if ($this->PrevButton->Enabled) $this->ButtonCount++;
		if ($this->NextButton->Enabled) $this->ButtonCount++;
		if ($this->LastButton->Enabled) $this->ButtonCount++;
		$this->ButtonCount += count($this->Items);
  }

	// Add pager item
	function AddPagerItem($StartIndex, $Text, $Enabled)
	{
		$Item = new cPagerItem;
		$Item->Start = $StartIndex;
		$Item->Text = $Text;
		$Item->Enabled = $Enabled;
		$this->Items[] = $Item;
	}

	// Setup pager items
	function SetupNumericPager()
	{
		if ($this->RecordCount > $this->PageSize) {
			$Eof = ($this->RecordCount < ($this->FromIndex + $this->PageSize));
			$HasPrev = ($this->FromIndex > 1);

			// First Button
			$TempIndex = 1;
			$this->FirstButton->Start = $TempIndex;
			$this->FirstButton->Enabled = ($this->FromIndex > $TempIndex);

			// Prev Button
			$TempIndex = $this->FromIndex - $this->PageSize;
			if ($TempIndex < 1) $TempIndex = 1;
			$this->PrevButton->Start = $TempIndex;
			$this->PrevButton->Enabled = $HasPrev;

			// Page links
			if ($HasPrev || !$Eof) {
				$x = 1;
				$y = 1;
				$dx1 = intval(($this->FromIndex-1)/($this->PageSize*$this->Range))*$this->PageSize*$this->Range + 1;
				$dy1 = intval(($this->FromIndex-1)/($this->PageSize*$this->Range))*$this->Range + 1;
				if (($dx1+$this->PageSize*$this->Range-1) > $this->RecordCount) {
					$dx2 = intval($this->RecordCount/$this->PageSize)*$this->PageSize + 1;
					$dy2 = intval($this->RecordCount/$this->PageSize) + 1;
				} else {
					$dx2 = $dx1 + $this->PageSize*$this->Range - 1;
					$dy2 = $dy1 + $this->Range - 1;
				}
				while ($x <= $this->RecordCount) {
					if ($x >= $dx1 && $x <= $dx2) {
						$this->AddPagerItem($x, $y, $this->FromIndex<>$x);
						$x += $this->PageSize;
						$y++;
					} elseif ($x >= ($dx1-$this->PageSize*$this->Range) && $x <= ($dx2+$this->PageSize*$this->Range)) {
						if ($x+$this->Range*$this->PageSize < $this->RecordCount) {
							$this->AddPagerItem($x, $y . "-" . ($y+$this->Range-1), TRUE);
						} else {
							$ny = intval(($this->RecordCount-1)/$this->PageSize) + 1;
							if ($ny == $y) {
								$this->AddPagerItem($x, $y, TRUE);
							} else {
								$this->AddPagerItem($x, $y . "-" . $ny, TRUE);
							}
						}
						$x += $this->Range*$this->PageSize;
						$y += $this->Range;
					} else {
						$x += $this->Range*$this->PageSize;
						$y += $this->Range;
					}
				}
			}

			// Next Button
			$TempIndex = $this->FromIndex + $this->PageSize;
			$this->NextButton->Start = $TempIndex;
			$this->NextButton->Enabled = !$Eof;

			// Last Button
			$TempIndex = intval(($this->RecordCount-1)/$this->PageSize)*$this->PageSize + 1;
			$this->LastButton->Start = $TempIndex;
			$this->LastButton->Enabled = ($this->FromIndex < $TempIndex);
		}
	}
}
/**
 * PrevNext pager class
 */

class cPrevNextPager {
	var $FirstButton, $PrevButton, $NextButton, $LastButton;
	var $CurrentPage, $PageCount, $FromIndex, $ToIndex, $RecordCount;
	var $AutoHidePager = TRUE;
	var $Visible = TRUE;

	// Constructor
	function __construct($StartRec, $DisplayRecs, $TotalRecs, $AutoHidePager = EW_AUTO_HIDE_PAGER)
	{
		$this->AutoHidePager = $AutoHidePager;
		if ($this->AutoHidePager && $StartRec == 1 && $TotalRecs <= $DisplayRecs)
			$this->Visible = FALSE;
		$this->FirstButton = new cPagerItem;
		$this->PrevButton = new cPagerItem;
		$this->NextButton = new cPagerItem;
		$this->LastButton = new cPagerItem;
		$this->FromIndex = intval($StartRec);
		$this->PageSize = intval($DisplayRecs);
		$this->RecordCount = intval($TotalRecs);
		if ($this->PageSize == 0) return;
		$this->CurrentPage = intval(($this->FromIndex-1)/$this->PageSize) + 1;
		$this->PageCount = intval(($this->RecordCount-1)/$this->PageSize) + 1;
		if ($this->FromIndex > $this->RecordCount)
			$this->FromIndex = $this->RecordCount;
		$this->ToIndex = $this->FromIndex + $this->PageSize - 1;
		if ($this->ToIndex > $this->RecordCount)
			$this->ToIndex = $this->RecordCount;

		// First Button
		$TempIndex = 1;
		$this->FirstButton->Start = $TempIndex;
		$this->FirstButton->Enabled = ($TempIndex <> $this->FromIndex);

		// Prev Button
		$TempIndex = $this->FromIndex - $this->PageSize;
		if ($TempIndex < 1) $TempIndex = 1;
		$this->PrevButton->Start = $TempIndex;
		$this->PrevButton->Enabled = ($TempIndex <> $this->FromIndex);

		// Next Button
		$TempIndex = $this->FromIndex + $this->PageSize;
		if ($TempIndex > $this->RecordCount)
			$TempIndex = $this->FromIndex;
		$this->NextButton->Start = $TempIndex;
		$this->NextButton->Enabled = ($TempIndex <> $this->FromIndex);

		// Last Button
		$TempIndex = intval(($this->RecordCount-1)/$this->PageSize)*$this->PageSize + 1;
		$this->LastButton->Start = $TempIndex;
		$this->LastButton->Enabled = ($TempIndex <> $this->FromIndex);
  }
}
/**
 * Breadcrumb class
 */

class cBreadcrumb {
	var $Links = array();
	var $SessionLinks = array();
	var $Visible = TRUE;

	// Constructor
	function __construct() {
		global $Language;
		$this->Links[] = array("home", "HomePage", "index.php", "ewHome", "", FALSE); // Home
	}

	// Check if an item exists
	function Exists($pageid, $table, $pageurl) {
		if (is_array($this->Links)) {
			$cnt = count($this->Links);
			for ($i = 0; $i < $cnt; $i++) {
				@list($id, $title, $url, $tablevar, $cur) = $this->Links[$i];
				if ($pageid == $id && $table == $tablevar && $pageurl == $url)
					return TRUE;
			}
		}
		return FALSE;
	}

	// Add breadcrumb
	function Add($pageid, $pagetitle, $pageurl, $pageurlclass = "", $table = "", $current = FALSE) {

		// Load session links
		$this->LoadSession();

		// Get list of master tables
		$mastertable = array();
		if ($table <> "") {
			$tablevar = $table;
			while (@$_SESSION[EW_PROJECT_NAME . "_" . $tablevar . "_" . EW_TABLE_MASTER_TABLE] <> "") {
				$tablevar = $_SESSION[EW_PROJECT_NAME . "_" . $tablevar . "_" . EW_TABLE_MASTER_TABLE];
				if (in_array($tablevar, $mastertable))
					break;
				$mastertable[] = $tablevar;
			}
		}

		// Add master links first
		if (is_array($this->SessionLinks)) {
			$cnt = count($this->SessionLinks);
			for ($i = 0; $i < $cnt; $i++) {
				@list($id, $title, $url, $cls, $tbl, $cur) = $this->SessionLinks[$i];

				//if ((in_array($tbl, $mastertable) || $tbl == $table) && $id == "list") {
				if (in_array($tbl, $mastertable) && $id == "list") {
					if ($url == $pageurl)
						break;
					if (!$this->Exists($id, $tbl, $url))
						$this->Links[] = array($id, $title, $url, $cls, $tbl, FALSE);
				}
			}
		}

		// Add this link
		if (!$this->Exists($pageid, $table, $pageurl))
			$this->Links[] = array($pageid, $pagetitle, $pageurl, $pageurlclass, $table, $current);

		// Save session links
		$this->SaveSession();
	}

	// Save links to Session
	function SaveSession() {
		$_SESSION[EW_SESSION_BREADCRUMB] = $this->Links;
	}

	// Load links from Session
	function LoadSession() {
		if (is_array(@$_SESSION[EW_SESSION_BREADCRUMB]))
			$this->SessionLinks = $_SESSION[EW_SESSION_BREADCRUMB];
	}

	// Load language phrase
	function LanguagePhrase($title, $table, $current) {
		global $Language;
		$wrktitle = ($title == $table) ? $Language->TablePhrase($title, "TblCaption") : $Language->Phrase($title);
		if ($current)
			$wrktitle = "<span id=\"ewPageCaption\">" . $wrktitle . "</span>";
		return $wrktitle;
	}

	// Render
	function Render() {
		if (!$this->Visible || EW_PAGE_TITLE_STYLE == "" || EW_PAGE_TITLE_STYLE == "None")
			return;
		$nav = "<ul class=\"breadcrumb ewBreadcrumbs\">";
		if (is_array($this->Links)) {
			$cnt = count($this->Links);
			if (EW_PAGE_TITLE_STYLE == "Caption") {
				list($id, $title, $url, $cls, $table, $cur) = $this->Links[$cnt-1];
				echo "<div class=\"ewPageTitle\">" . $this->LanguagePhrase($title, $table, $cur) . "</div>";
				return;
			} else {
				for ($i = 0; $i < $cnt; $i++) {
					list($id, $title, $url, $cls, $table, $cur) = $this->Links[$i];
					if ($i < $cnt - 1) {
						$nav .= "<li id=\"ewBreadcrumb" . ($i + 1) . "\">";
					} else {
						$nav .= "<li id=\"ewBreadcrumb" . ($i + 1) . "\" class=\"active\">";
						$url = ""; // No need to show URL for current page
					}
					$text = $this->LanguagePhrase($title, $table, $cur);
					$title = ew_HtmlTitle($text);
					if ($url <> "") {
						$nav .= "<a href=\"" . ew_GetUrl($url) . "\"";
						if ($title <> "" && $title <> $text)
							$nav .= " title=\"" . ew_HtmlEncode($title) . "\"";
						if ($cls <> "")
							$nav .= " class=\"" . $cls . "\"";
						$nav .= ">" . $text . "</a>";
					} else {
						$nav .= $text;
					}
					$nav .= "</li>";
				}
			}
		}
		$nav .= "</ul>";
		echo $nav;
	}
}
/**
 * Table classes
 */

// Common class for table and report
class cTableBase {
	var $TableVar;
	var $TableName;
	var $TableType;
	var $DBID = "DB"; // Table database id
	var $UseSelectLimit = TRUE;
	var $Visible = TRUE;
	var $fields = array();
	var $UseTokenInUrl = EW_USE_TOKEN_IN_URL;
	var $Export; // Export
	var $CustomExport; // Custom export
	var $ExportAll;
	var $ExportPageBreakCount; // Page break per every n record (PDF only)
	var $ExportPageOrientation; // Page orientation (PDF only)
	var $ExportPageSize; // Page size (PDF only)
	var $ExportExcelPageOrientation; // Page orientation (PHPExcel only)
	var $ExportExcelPageSize; // Page size (PHPExcel only)
	var $SendEmail; // Send email
	var $TableCustomInnerHtml; // Custom inner HTML
	var $BasicSearch; // Basic search
	var $CurrentFilter; // Current filter
	var $CurrentOrder; // Current order
	var $CurrentOrderType; // Current order type
	var $RowType; // Row type
	var $CssClass; // CSS class
	var $CssStyle; // CSS style
	var $RowAttrs = array(); // Row custom attributes
	var $CurrentAction; // Current action
	var $LastAction; // Last action
	var $UserIDAllowSecurity = 0; // User ID Allow

	// Update Table
	var $UpdateTable = "";

	// Connection
	function &Connection() {
		return Conn($this->DBID);
	}

	// Build filter from array
	function ArrayToFilter(&$rs) {
		$filter = "";
		foreach ($rs as $name => $value) {
			if (array_key_exists($name, $this->fields))
				ew_AddFilter($filter, ew_QuotedName($this->fields[$name]->FldName, $this->DBID) . '=' . ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID));
		}
		return $filter;
	}

	// Build UPDATE statement with WHERE clause
	// $rs (array) array of field to be updated
	// $where (string|array) WHERE clause as string or array of field
	function UpdateSQL(&$rs, $where) {
		if (empty($this->UpdateTable) || empty($where))
			return ""; // Does not allow updating all records
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = is_array($where) ? $this->ArrayToFilter($where) : $where;
		return $sql . " WHERE " . $filter;
	}

	// Update
	function Update(&$rs, $where) {
		if (empty($this->UpdateTable) || empty($where))
			return FALSE; // Does not allow updating all records
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// Build DELETE statement
	// $where (string|array) WHERE clause as string or array of field
	function DeleteSQL(&$where) {
		if (empty($this->UpdateTable) || empty($where))
			return ""; // Does not allow deleting all records
		$sql = "DELETE FROM " . $this->UpdateTable;
		$filter = is_array($where) ? $this->ArrayToFilter($where) : $where;
		return $sql . " WHERE " . $filter;
	}

	// Delete
	// $where (string|array) WHERE clause as string or array of field
	function Delete(&$where) {
		if (empty($this->UpdateTable) || empty($where))
			return FALSE; // Does not allow deleting all records
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs));
	}

	// Reset attributes for table object
	function ResetAttrs() {
		$this->CssClass = "";
		$this->CssStyle = "";
    	$this->RowAttrs = array();
		foreach ($this->fields as $fld) {
			$fld->ResetAttrs();
		}
	}

	// Setup field titles
	function SetupFieldTitles() {
		foreach ($this->fields as &$fld) {
			if (strval($fld->FldTitle()) <> "") {
				$fld->EditAttrs["data-toggle"] = "tooltip";
				$fld->EditAttrs["title"] = ew_HtmlEncode($fld->FldTitle());
			}
		}
	}
	var $TableFilter = "";

	// Get field values
	function GetFieldValues($propertyname) {
		$values = array();
		foreach ($this->fields as $fldname => $fld)
			$values[$fldname] = $fld->$propertyname;
		return $values;
	}
	var $TableCaption = "";

	// Set table caption
	function setTableCaption($v) {
		$this->TableCaption = $v;
	}

	// Table caption
	function TableCaption() {
		global $Language;
		if ($this->TableCaption <> "")
			return $this->TableCaption;
		else
			return $Language->TablePhrase($this->TableVar, "TblCaption");
	}
	var $PgCaption = array();

	// Set page caption
	function setPageCaption($Page, $v) {
		$this->PgCaption[$Page] = $v;
	}

	// Page caption
	function PageCaption($Page) {
		global $Language;
		$Caption = @$this->PgCaption[$Page];
		if ($Caption <> "") {
			return $Caption;
		} else {
			$Caption = $Language->TablePhrase($this->TableVar, "TblPageCaption" . $Page);
			if ($Caption == "") $Caption = "Page " . $Page;
			return $Caption;
		}
	}

	// Add URL parameter
	function UrlParm($parm = "") {
		$UrlParm = ($this->UseTokenInUrl) ? "t=" . $this->TablVar : "";
		if ($parm <> "") {
			if ($UrlParm <> "")
				$UrlParm .= "&";
			$UrlParm .= $parm;
		}
		return $UrlParm;
	}

	// Row styles
	function RowStyles() {
		$sAtt = "";
		$sStyle = trim($this->CssStyle);
		if (@$this->RowAttrs["style"] <> "")
			$sStyle .= " " . $this->RowAttrs["style"];
		$sClass = trim($this->CssClass);
		if (@$this->RowAttrs["class"] <> "")
			$sClass .= " " . $this->RowAttrs["class"];
		if (trim($sStyle) <> "")
			$sAtt .= " style=\"" . trim($sStyle) . "\"";
		if (trim($sClass) <> "")
			$sAtt .= " class=\"" . trim($sClass) . "\"";
		return $sAtt;
	}

	// Row attributes
	function RowAttributes() {
		$sAtt = $this->RowStyles();
		if ($this->Export == "") {
			foreach ($this->RowAttrs as $k => $v) {
				if ($k <> "class" && $k <> "style" && trim($v) <> "")
					$sAtt .= " " . $k . "=\"" . trim($v) . "\"";
			}
		}
		return $sAtt;
	}

	// Field object by name
	function fields($fldname) {
		return $this->fields[$fldname];
	}
}

// Class for table
class cTable extends cTableBase {
	var $CurrentMode = ""; // Current mode
	var $UpdateConflict; // Update conflict
	var $EventName; // Event name
	var $EventCancelled; // Event cancelled
	var $CancelMessage; // Cancel message
	var $AllowAddDeleteRow = TRUE; // Allow add/delete row
	var $ValidateKey = TRUE; // Validate key
	var $DetailAdd; // Allow detail add
	var $DetailEdit; // Allow detail edit
	var $DetailView; // Allow detail view
	var $ShowMultipleDetails; // Show multiple details
	var $GridAddRowCount;
	var $CustomActions = array(); // Custom action array

	// Check current action
	// - Add
	function IsAdd() {
		return $this->CurrentAction == "add";
	}

	// - Copy
	function IsCopy() {
		return $this->CurrentAction == "copy" || $this->CurrentAction == "C";
	}

	// - Edit
	function IsEdit() {
		return $this->CurrentAction == "edit";
	}

	// - Delete
	function IsDelete() {
		return $this->CurrentAction == "D";
	}

	// - Confirm
	function IsConfirm() {
		return $this->CurrentAction == "F";
	}

	// - Confirm cancelled
	function IsConfirmCancel() {
		return $this->CurrentAction == "X";
	}

	// - Overwrite
	function IsOverwrite() {
		return $this->CurrentAction == "overwrite";
	}

	// - Cancel
	function IsCancel() {
		return $this->CurrentAction == "cancel";
	}

	// - Grid add
	function IsGridAdd() {
		return $this->CurrentAction == "gridadd";
	}

	// - Grid edit
	function IsGridEdit() {
		return $this->CurrentAction == "gridedit";
	}

	// - Add/Copy/Edit/GridAdd/GridEdit
	function IsAddOrEdit() {
		return $this->IsAdd() || $this->IsCopy() || $this->IsEdit() || $this->IsGridAdd() || $this->IsGridEdit();
	}

	// - Insert
	function IsInsert() {
		return $this->CurrentAction == "insert" || $this->CurrentAction == "A";
	}

	// - Update
	function IsUpdate() {
		return $this->CurrentAction == "update" || $this->CurrentAction == "U";
	}

	// - Grid update
	function IsGridUpdate() {
		return $this->CurrentAction == "gridupdate";
	}

	// - Grid insert
	function IsGridInsert() {
		return $this->CurrentAction == "gridinsert";
	}

	// - Grid overwrite
	function IsGridOverwrite() {
		return $this->CurrentAction == "gridoverwrite";
	}

	// Check last action
	// - Cancelled
	function IsCanceled() {
		return $this->LastAction == "cancel" && $this->CurrentAction == "";
	}

	// - Inline inserted
	function IsInlineInserted() {
		return $this->LastAction == "insert" && $this->CurrentAction == "";
	}

	// - Inline updated
	function IsInlineUpdated() {
		return $this->LastAction == "update" && $this->CurrentAction == "";
	}

	// - Grid updated
	function IsGridUpdated() {
		return $this->LastAction == "gridupdate" && $this->CurrentAction == "";
	}

	// - Grid inserted
	function IsGridInserted() {
		return $this->LastAction == "gridinsert" && $this->CurrentAction == "";
	}

	// Export return page
	function ExportReturnUrl() {
		$url = @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_EXPORT_RETURN_URL];
		return ($url <> "") ? $url : ew_CurrentPage();
	}

	function setExportReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_EXPORT_RETURN_URL] = $v;
	}

	// Records per page
	function getRecordsPerPage() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_REC_PER_PAGE];
	}

	function setRecordsPerPage($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_REC_PER_PAGE] = $v;
	}

	// Start record number
	function getStartRecordNumber() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_START_REC];
	}

	function setStartRecordNumber($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_START_REC] = $v;
	}

	// Search highlight name
	function HighlightName() {
		return $this->TableVar . "_Highlight";
	}

	// Search WHERE clause
	function getSearchWhere() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_SEARCH_WHERE];
	}

	function setSearchWhere($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_SEARCH_WHERE] = $v;
	}

	// Session WHERE clause
	function getSessionWhere() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_WHERE];
	}

	function setSessionWhere($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_WHERE] = $v;
	}

	// Session ORDER BY
	function getSessionOrderBy() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY];
	}

	function setSessionOrderBy($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY] = $v;
	}

	// Session key
	function getKey($fld) {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_KEY . "_" . $fld];
	}

	function setKey($fld, $v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_KEY . "_" . $fld] = $v;
	}

	// URL encode
	function UrlEncode($str) {
		return urlencode($str);
	}

	// Print
	function Raw($str) {
		return $str;
	}
}
/**
 * Field class
 */

class cField {
	var $TblName; // Table name
	var $TblVar; // Table variable name
	var $FldName; // Field name
	var $FldVar; // Field variable name
	var $FldExpression; // Field expression (used in SQL)
	var $FldBasicSearchExpression; // Field expression (used in basic search SQL)
	var $FldIsCustom = FALSE; // Custom field
	var $FldIsVirtual; // Virtual field
	var $FldVirtualExpression; // Virtual field expression (used in ListSQL)
	var $FldForceSelection; // Autosuggest force selection
	var $FldSelectMultiple; // Select multiple
	var $FldVirtualSearch; // Search as virtual field
	var $FldDefaultErrMsg; // Default error message
	var $VirtualValue; // Virtual field value
	var $TooltipValue; // Field tooltip value
	var $TooltipWidth = 0; // Field tooltip width
	var $FldType; // Field type
	var $FldDataType; // PHPMaker Field type
	var $FldBlobType; // For Oracle only
	var $FldViewTag; // View Tag
	var $FldHtmlTag; // Html Tag
	var $FldIsDetailKey = FALSE; // Field is detail key
	var $AdvancedSearch; // AdvancedSearch Object
	var $Upload; // Upload Object
	var $FldDateTimeFormat; // Date time format
	var $CssStyle; // CSS style
	var $CssClass; // CSS class
	var $ImageAlt; // Image alt
	var $ImageWidth = 0; // Image width
	var $ImageHeight = 0; // Image height
	var $ImageResize = FALSE; // Image resize
	var $IsBlobImage = FALSE; // Is blob image
	var $ViewCustomAttributes; // View custom attributes
	var $EditCustomAttributes; // Edit custom attributes
	var $LinkCustomAttributes; // Link custom attributes
	var $Count; // Count
	var $Total; // Total
	var $TrueValue = '1';
	var $FalseValue = '0';
	var $Visible = TRUE; // Visible
	var $Disabled; // Disabled
	var $ReadOnly = FALSE; // Read only
	var $TruncateMemoRemoveHtml; // Remove HTML from memo field
	var $CustomMsg = ""; // Custom message
	var $CellCssClass = ""; // Cell CSS class
	var $CellCssStyle = ""; // Cell CSS style
	var $CellCustomAttributes = ""; // Cell custom attributes
	var $MultiUpdate; // Multi update
	var $OldValue; // Old Value
	var $ConfirmValue; // Confirm value
	var $CurrentValue; // Current value
	var $ViewValue; // View value
	var $EditValue; // Edit value
	var $EditValue2; // Edit value 2 (search)
	var $HrefValue; // Href value
	var $HrefValue2; // Href value 2 (confirm page upload control)
	var $FormValue; // Form value
	var $QueryStringValue; // QueryString value
	var $DbValue; // Database value
	var $Sortable = TRUE; // Sortable
	var $UploadPath = EW_UPLOAD_DEST_PATH; // Upload path
	var $OldUploadPath = EW_UPLOAD_DEST_PATH; // Old upload path (for deleting old image)
	var $UploadAllowedFileExt = EW_UPLOAD_ALLOWED_FILE_EXT; // Allowed file extensions
	var $UploadMaxFileSize = EW_MAX_FILE_SIZE; // Upload max file size
	var $UploadMaxFileCount = EW_MAX_FILE_COUNT; // Upload max file count
	var $UploadMultiple = FALSE; // Multiple Upload
	var $UseColorbox = EW_USE_COLORBOX; // Use Colorbox
	var $CellAttrs = array(); // Cell custom attributes
	var $EditAttrs = array(); // Edit custom attributes
	var $ViewAttrs = array(); // View custom attributes
	var $LinkAttrs = array(); // Link custom attributes
	var $DisplayValueSeparator = ", ";
	var $AutoFillOriginalValue = EW_AUTO_FILL_ORIGINAL_VALUE;
	var $ReqErrMsg;
	var $LookupFilters = array();
	var $OptionCount = 0;

	// Constructor
	function __construct($tblvar, $tblname, $fldvar, $fldname, $fldexp, $fldbsexp, $fldtype, $flddtfmt, $upload, $fldvirtualexp, $fldvirtual, $forceselect, $fldvirtualsrch, $fldviewtag="", $fldhtmltag="") {
		global $Language;
		$this->TblVar = $tblvar;
		$this->TblName = $tblname;
		$this->FldVar = $fldvar;
		$this->FldName = $fldname;
		$this->FldExpression = $fldexp;
		$this->FldBasicSearchExpression = $fldbsexp;
		$this->FldType = $fldtype;
		$this->FldDataType = ew_FieldDataType($fldtype);
		$this->FldDateTimeFormat = $flddtfmt;
		$this->AdvancedSearch = new cAdvancedSearch($this->TblVar, $this->FldVar);
		if ($upload) {
			$this->Upload = new cUpload($this->TblVar, $this->FldVar);
		}
		$this->FldVirtualExpression = $fldvirtualexp;
		$this->FldIsVirtual = $fldvirtual;
		$this->FldForceSelection = $forceselect;
		$this->FldVirtualSearch = $fldvirtualsrch;
		$this->FldViewTag = $fldviewtag;
		$this->FldHtmlTag = $fldhtmltag;
		if (isset($_GET[$fldvar]))
			$this->setQueryStringValue($_GET[$fldvar], FALSE);
		if (isset($_POST[$fldvar]))
			$this->setFormValue($_POST[$fldvar], FALSE);
		$this->ReqErrMsg = $Language->Phrase("EnterRequiredField");
	}
	var $PlaceHolder = "";

	// Get place holder
	function getPlaceHolder() {
		return ($this->ReadOnly || array_key_exists("readonly", $this->EditAttrs)) ? "" : $this->PlaceHolder;
	}
	var $Caption = "";

	// Set field caption
	function setFldCaption($v) {
		$this->Caption = $v;
	}

	// Field caption
	function FldCaption() {
		global $Language;
		if ($this->Caption <> "")
			return $this->Caption;
		else
			return $Language->FieldPhrase($this->TblVar, substr($this->FldVar, 2), "FldCaption");
	}

	// Field title
	function FldTitle() {
		global $Language;
		return $Language->FieldPhrase($this->TblVar, substr($this->FldVar, 2), "FldTitle");
	}

	// Field image alt
	function FldAlt() {
		global $Language;
		return $Language->FieldPhrase($this->TblVar, substr($this->FldVar, 2), "FldAlt");
	}

	// Field error message
	function FldErrMsg() {
		global $Language;
		$err = $Language->FieldPhrase($this->TblVar, substr($this->FldVar, 2), "FldErrMsg");
		if ($err == "") $err = $this->FldDefaultErrMsg . " - " . $this->FldCaption();
		return $err;
	}

	// Field option value
	function FldTagValue($i) {
		global $Language;
		return $Language->FieldPhrase($this->TblVar, substr($this->FldVar, 2), "FldTagValue" . $i);
	}

	// Field option caption
	function FldTagCaption($i) {
		global $Language;
		return $Language->FieldPhrase($this->TblVar, substr($this->FldVar, 2), "FldTagCaption" . $i);
	}

	// Set field visibility
	function SetVisibility() {
		$this->Visible = $GLOBALS[$this->TblVar]->SetFieldVisibility(substr($this->FldVar, 2));
	}

	// Field option caption by option value
	function OptionCaption($val) {
		global $Language;
		for ($i = 0; $i < $this->OptionCount; $i++) {
			if ($val == $this->FldTagValue($i + 1)) {
				$caption = $this->FldTagCaption($i + 1);
				return ($caption <> "") ? $caption : $val;
			}
		}
		return $val;
	}

	// Get field user options as array
	function Options($pleaseSelect = FALSE) {
		global $Language;
		$arwrk = array();
		if ($pleaseSelect) // Add "Please Select"
			$arwrk[] = array("", $Language->Phrase("PleaseSelect"));
		for ($i = 0; $i < $this->OptionCount; $i++) {
			$value = $this->FldTagValue($i + 1);
			$caption = $this->FldTagCaption($i + 1);
			$caption = ($caption <> "") ? $caption : $value;
			$arwrk[] = array($value, $caption);
		}
		return $arwrk;
	}
	var $UsePleaseSelect = TRUE;
	var $PleaseSelectText = "";

	// Get select options HTML
	function SelectOptionListHtml($name = "") {
		global $Language;
		$emptywrk = TRUE;
		$curValue = (CurrentPage()->RowType == EW_ROWTYPE_SEARCH) ? ((substr($name,0,1) == "y") ? $this->AdvancedSearch->SearchValue2 : $this->AdvancedSearch->SearchValue) : $this->CurrentValue;
		$str = "";
		if (is_array($this->EditValue)) {
			$arwrk = $this->EditValue;
			if ($this->FldSelectMultiple) {
				$armultiwrk = (strval($curValue) <> "") ? explode(",", strval($curValue)) : array();
				$cnt = count($armultiwrk);
				$rowswrk = count($arwrk);
				$emptywrk = TRUE;
				for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
					$selwrk = FALSE;
					for ($ari = 0; $ari < $cnt; $ari++) {
						if (ew_SameStr($arwrk[$rowcntwrk][0], $armultiwrk[$ari]) && !is_null($armultiwrk[$ari])) {
							$armultiwrk[$ari] = NULL; // Marked for removal
							$selwrk = TRUE;
							$emptywrk = FALSE;
							break;
						}
					}
					if (!$selwrk)
						continue;
					foreach ($arwrk[$rowcntwrk] as $k => $v)
						$arwrk[$rowcntwrk][$k] = ew_RemoveHtml(strval($arwrk[$rowcntwrk][$k]));
					$str .= "<option value=\"" . ew_HtmlEncode($arwrk[$rowcntwrk][0]) . "\" selected>" . $this->DisplayValue($arwrk[$rowcntwrk]) . "</option>";
				}
			} else {
				if ($this->UsePleaseSelect)
					$str .= "<option value=\"\">" . $this->PleaseSelectText . "</option>";
				$rowswrk = count($arwrk);
				$emptywrk = TRUE;
				for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
					if (ew_SameStr($curValue, $arwrk[$rowcntwrk][0]))
						$emptywrk = FALSE;
					else
						continue;
					foreach ($arwrk[$rowcntwrk] as $k => $v)
						$arwrk[$rowcntwrk][$k] = ew_RemoveHtml(strval($arwrk[$rowcntwrk][$k]));
					$str .= "<option value=\"" . ew_HtmlEncode($arwrk[$rowcntwrk][0]) . "\" selected>" . $this->DisplayValue($arwrk[$rowcntwrk]) . "</option>";
				}
			}
			if ($this->FldSelectMultiple) {
				for ($ari = 0; $ari < $cnt; $ari++) {
					if (!is_null($armultiwrk[$ari]))
						$str .= "<option value=\"" . ew_HtmlEncode($armultiwrk[$ari]) . "\" selected>" . $armultiwrk[$ari] . "</option>";
				}
			} else {
				if ($emptywrk && strval($curValue) <> "")
					$str .= "<option value=\"" . ew_HtmlEncode($curValue) . "\" selected>" . $curValue . "</option>";
			}
		}
		if ($emptywrk)
			$this->OldValue = "";
		return $str;
	}

	// Get radio buttons HTML
	function RadioButtonListHtml($isDropdown, $name, $page = -1) {
		$emptywrk = TRUE;
		$curValue = (CurrentPage()->RowType == EW_ROWTYPE_SEARCH) ? ((substr($name,0,1) == "y") ? $this->AdvancedSearch->SearchValue2 : $this->AdvancedSearch->SearchValue) : $this->CurrentValue;
		$str = "";
		$arwrk = $this->EditValue;
		if (is_array($arwrk)) {
			$rowswrk = count($arwrk);
			for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
				if (ew_SameStr($curValue, $arwrk[$rowcntwrk][0]))
					$emptywrk = FALSE;
				else
					continue;
				$html = "<input type=\"radio\" data-table=\"" . $this->TblVar . "\" data-field=\"" . $this->FldVar . "\"" .
					(($page > -1) ? " data-page=\"" . $page . "\"" : "") .
					" name=\"" . $name . "\" id=\"" . $name . "_" . $rowcntwrk . "\"" .
					" data-value-separator=\"" . $this->DisplayValueSeparatorAttribute() . "\"" .
					" value=\"" . ew_HtmlEncode($arwrk[$rowcntwrk][0]) . "\" checked" . $this->EditAttributes() . ">" . $this->DisplayValue($arwrk[$rowcntwrk]);
				if (!$isDropdown)
					$html = "<label class=\"radio-inline\">" . $html . "</label>";
				$str .= $html;
			}
			if ($emptywrk && strval($curValue) <> "") {
				$html = "<input type=\"radio\" data-table=\"" . $this->TblVar . "\" data-field=\"" . $this->FldVar . "\"" .
					(($page > -1) ? " data-page=\"" . $page . "\"" : "") .
					" name=\"" . $name . "\" id=\"" . $name . "_" .  $rowswrk . "\"" .
					" data-value-separator=\"" . $this->DisplayValueSeparatorAttribute() . "\"" .
					" value=\"" . ew_HtmlEncode($curValue) . "\" checked" . $this->EditAttributes() . ">" . $curValue;
				if (!$isDropdown)
					$html = "<label class=\"radio-inline\">" . $html . "</label>";
				$str .= $html;
			}
		}
		if ($emptywrk)
			$this->OldValue = "";
		return $str;
	}

	// Get checkboxes HTML
	function CheckBoxListHtml($isDropdown, $name, $page = -1) {
		$emptywrk = TRUE;
		$curValue = (CurrentPage()->RowType == EW_ROWTYPE_SEARCH) ? ((substr($name,0,1) == "y") ? $this->AdvancedSearch->SearchValue2 : $this->AdvancedSearch->SearchValue) : $this->CurrentValue;
		$str = "";
		$arwrk = $this->EditValue;
		if (is_array($arwrk)) {
			$armultiwrk = (strval($curValue) <> "") ? explode(",", strval($curValue)) : array();
			$cnt = count($armultiwrk);
			$rowswrk = count($arwrk);
			$emptywrk = TRUE;
			for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
				$selwrk = FALSE;
				for ($ari = 0; $ari < $cnt; $ari++) {
					if (ew_SameStr($arwrk[$rowcntwrk][0], $armultiwrk[$ari]) && !is_null($armultiwrk[$ari])) {
						$armultiwrk[$ari] = NULL; // Marked for removal
						$selwrk = TRUE;
						$emptywrk = FALSE;
						break;
					}
				}
				if (!$selwrk)
					continue;
				$html = "<input type=\"checkbox\" data-table=\"" . $this->TblVar . "\" data-field=\"" . $this->FldVar . "\"" .
					(($page > -1) ? " data-page=\"" . $page . "\"" : "") .
					" name=\"" . $name . "\" id=\"" . $name . "_" . $rowcntwrk . "\"" .
					" data-value-separator=\"" . $this->DisplayValueSeparatorAttribute() . "\"" .
					" value=\"" . ew_HtmlEncode($arwrk[$rowcntwrk][0]) . "\" checked" . $this->EditAttributes() . ">" . $this->DisplayValue($arwrk[$rowcntwrk]);
				if (!$isDropdown)
					$html = "<label class=\"checkbox-inline\">" . $html . "</label>"; // Note: No spacing within the LABEL tag
				$str .= $html;
			}
			for ($ari = 0; $ari < $cnt; $ari++) {
				if (!is_null($armultiwrk[$ari])) {
					$html = "<input type=\"checkbox\" data-table=\"" . $this->TblVar . "\" data-field=\"" . $this->FldVar . "\"" .
						(($page > -1) ? " data-page=\"" . $page . "\"" : "") .
						" name=\"" . $name . "\" value=\"" . ew_HtmlEncode($armultiwrk[$ari]) . "\" checked" .
						" data-value-separator=\"" . $this->DisplayValueSeparatorAttribute() . "\"" .
						$this->EditAttributes() . ">" . $armultiwrk[$ari];
					if (!$isDropdown)
						$html = "<label class=\"checkbox-inline\">" . $html . "</label>";
					$str .= $html;
				}
			}
		}
		if ($emptywrk)
			$this->OldValue = "";
		return $str;
	}

	// Get display field value separator
	// idx (int) display field index (1|2|3)
	function GetDisplayValueSeparator($idx) {
		$sep = $this->DisplayValueSeparator;
		return (is_array($sep)) ? @$sep[$idx - 1] : ($sep ?: ", ");
	}

	// Get display field value separator as attribute value
	function DisplayValueSeparatorAttribute() {
		return ew_HtmlEncode(is_array($this->DisplayValueSeparator) ? json_encode($this->DisplayValueSeparator) : $this->DisplayValueSeparator);
	}

	// Get display value (for lookup field)
	// $rs (array|recordset)
	function DisplayValue($rs) {
		$ar = is_array($rs) ? $rs : $rs->fields;
		$val = strval(@$ar[1]); // Display field 1
		for ($i = 2; $i <= 4; $i++) { // Display field 2 to 4
			$sep = $this->GetDisplayValueSeparator($i - 1);
			if (is_null($sep)) // No separator, break
				break;
			if (@$ar[$i] <> "")
				$val .= $sep . $ar[$i];
		}
		return $val;
	}

	// Reset attributes for field object
	function ResetAttrs() {
		$this->CssStyle = "";
		$this->CssClass = "";
		$this->CellCssStyle = "";
		$this->CellCssClass = "";
		$this->CellAttrs = array();
		$this->EditAttrs = array();
		$this->ViewAttrs = array();
		$this->LinkAttrs = array();
	}

	// View Attributes
	function ViewAttributes() {
		$viewattrs = $this->ViewAttrs;
		if ($this->FldViewTag == "IMAGE")
			$viewattrs["alt"] = (trim($this->ImageAlt) <> "") ? trim($this->ImageAlt) : ""; // IMG tag requires alt attribute
		$attrs = $this->ViewCustomAttributes; // Custom attributes
		if (is_array($attrs)) { // Custom attributes as array
			$ar = $attrs;
			$attrs = "";
			$aik = array_intersect_key($ar, $viewattrs);
			$viewattrs += $ar; // Combine attributes
			foreach ($aik as $k => $v) { // Duplicate attributes
				if ($k == "style" || substr($k, 0, 2) == "on") // "style" and events
					$viewattrs[$k] = ew_Concat($viewattrs[$k], $v, ";");
				else // "class" and others
					$viewattrs[$k] = ew_Concat($viewattrs[$k], $v, " ");
			}
		}
		$sStyle = "";
		if ($this->FldViewTag == "IMAGE" && intval($this->ImageWidth) > 0 && (!$this->ImageResize || intval($this->ImageHeight) <= 0))
			$sStyle .= "width: " . intval($this->ImageWidth) . "px; ";
		if ($this->FldViewTag == "IMAGE" && intval($this->ImageHeight) > 0 && (!$this->ImageResize || intval($this->ImageWidth) <= 0))
			$sStyle .= "height: " . intval($this->ImageHeight) . "px; ";
		$viewattrs["style"] = ew_Concat(@$viewattrs["style"], $sStyle . trim($this->CssStyle), ";");
		$viewattrs["class"] = ew_Concat(@$viewattrs["class"], $this->CssClass, " ");
		$sAtt = "";
		foreach ($viewattrs as $k => $v) {
			if (trim($k) <> "" && (trim($v) <> "" || ew_IsBooleanAttr($k))) { // Allow boolean attributes, e.g. "disabled"
				$sAtt .= " " . trim($k);
				if (trim($v) <> "")
					$sAtt .= "=\"" . trim($v) . "\"";
			} elseif (trim($k) == "alt" && trim($v) == "") { // Allow alt="" since it is a required attribute
				$sAtt .= " alt=\"\"";
			}
		}
		if ($attrs <> "") // Custom attributes as string
			$sAtt .= " " . $attrs;
		return $sAtt;
	}

	// Edit attributes
	function EditAttributes() {
		$editattrs = $this->EditAttrs;
		$attrs = $this->EditCustomAttributes; // Custom attributes
		if (is_array($attrs)) { // Custom attributes as array
			$ar = $attrs;
			$attrs = "";
			$aik = array_intersect_key($ar, $editattrs);
			$editattrs += $ar; // Combine attributes
			foreach ($aik as $k => $v) { // Duplicate attributes
				if ($k == "style" || substr($k, 0, 2) == "on") // "style" and events
					$editattrs[$k] = ew_Concat($editattrs[$k], $v, ";");
				else // "class" and others
					$editattrs[$k] = ew_Concat($editattrs[$k], $v, " ");
			}
		}
		$editattrs["style"] = ew_Concat(@$editattrs["style"], $this->CssStyle, ";");
		$editattrs["class"] = ew_Concat(@$editattrs["class"], $this->CssClass, " ");
		if ($this->Disabled)
			$editattrs["disabled"] = "disabled";
		if ($this->ReadOnly)
			$editattrs["readonly"] = "readonly";
		$sAtt = "";
		foreach ($editattrs as $k => $v) {
			if (trim($k) <> "" && (trim($v) <> "" || ew_IsBooleanAttr($k))) { // Allow boolean attributes, e.g. "disabled"
				$sAtt .= " " . trim($k);
				if (trim($v) <> "")
					$sAtt .= "=\"" . trim($v) . "\"";
			}
		}
		if ($attrs <> "") // Custom attributes as string
			$sAtt .= " " . $attrs;
		return $sAtt;
	}

	// Cell styles (Used in export)
	function CellStyles() {
		$sAtt = "";
		$sStyle = trim($this->CellCssStyle);
		if (@$this->CellAttrs["style"] <> "")
			$sStyle .= " " . $this->CellAttrs["style"];
		$sClass = trim($this->CellCssClass);
		if (@$this->CellAttrs["class"] <> "")
			$sClass .= " " . $this->CellAttrs["class"];
		if (trim($sStyle) <> "")
			$sAtt .= " style=\"" . trim($sStyle) . "\"";
		if (trim($sClass) <> "")
			$sAtt .= " class=\"" . trim($sClass) . "\"";
		return $sAtt;
	}

	// Cell attributes
	function CellAttributes() {
		$cellattrs = $this->CellAttrs;
		$attrs = $this->CellCustomAttributes; // Custom attributes
		if (is_array($attrs)) { // Custom attributes as array
			$ar = $attrs;
			$attrs = "";
			$aik = array_intersect_key($ar, $cellattrs);
			$cellattrs += $ar; // Combine attributes
			foreach ($aik as $k => $v) { // Duplicate attributes
				if ($k == "style" || substr($k, 0, 2) == "on") // "style" and events
					$cellattrs[$k] = ew_Concat($cellattrs[$k], $v, ";");
				else // "class" and others
					$cellattrs[$k] = ew_Concat($cellattrs[$k], $v, " ");
			}
		}
		$cellattrs["style"] = ew_Concat(@$cellattrs["style"], $this->CellCssStyle, ";");
		$cellattrs["class"] = ew_Concat(@$cellattrs["class"], $this->CellCssClass, " ");
		$sAtt = "";
		foreach ($cellattrs as $k => $v) {
			if (trim($k) <> "" && (trim($v) <> "" || ew_IsBooleanAttr($k))) { // Allow boolean attributes, e.g. "disabled"
				$sAtt .= " " . trim($k);
				if (trim($v) <> "")
					$sAtt .= "=\"" . trim($v) . "\"";
			}
		}
		if ($attrs <> "") // Custom attributes as string
			$sAtt .= " " . $attrs;
		return $sAtt;
	}

	// Link attributes
	function LinkAttributes() {
		$linkattrs = $this->LinkAttrs;
		$attrs = $this->LinkCustomAttributes; // Custom attributes
		if (is_array($attrs)) { // Custom attributes as array
			$ar = $attrs;
			$attrs = "";
			$aik = array_intersect_key($ar, $linkattrs);
			$linkattrs += $ar; // Combine attributes
			foreach ($aik as $k => $v) { // Duplicate attributes
				if ($k == "style" || substr($k, 0, 2) == "on") // "style" and events
					$linkattrs[$k] = ew_Concat($linkattrs[$k], $v, ";");
				else // "class" and others
					$linkattrs[$k] = ew_Concat($linkattrs[$k], $v, " ");
			}
		}
		$sHref = trim($this->HrefValue);
		if ($sHref <> "")
			$linkattrs["href"] = $sHref;
		$sAtt = "";
		foreach ($linkattrs as $k => $v) {
			if (trim($k) <> "" && (trim($v) <> "" || ew_IsBooleanAttr($k))) { // Allow boolean attributes, e.g. "disabled"
				$sAtt .= " " . trim($k);
				if (trim($v) <> "")
					$sAtt .= "=\"" . trim($v) . "\"";
			}
		}
		if ($attrs <> "") // Custom attributes as string
			$sAtt .= " " . $attrs;
		return $sAtt;
	}

	// Sort
	function getSort() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TblVar . "_" . EW_TABLE_SORT . "_" . $this->FldVar];
	}

	function setSort($v) {
		if (@$_SESSION[EW_PROJECT_NAME . "_" . $this->TblVar . "_" . EW_TABLE_SORT . "_" . $this->FldVar] <> $v) {
			$_SESSION[EW_PROJECT_NAME . "_" . $this->TblVar . "_" . EW_TABLE_SORT . "_" . $this->FldVar] = $v;
		}
	}

	// Reverse sort
	function ReverseSort() {
		return ($this->getSort() == "ASC") ? "DESC" : "ASC";
	}

	// Advanced search
	function UrlParameterName($name) {
		$fldparm = substr($this->FldVar, 2);
		if (strcasecmp($name, "SearchValue") == 0) {
			$fldparm = "x_" . $fldparm;
		} elseif (strcasecmp($name, "SearchOperator") == 0) {
			$fldparm = "z_" . $fldparm;
		} elseif (strcasecmp($name, "SearchCondition") == 0) {
			$fldparm = "v_" . $fldparm;
		} elseif (strcasecmp($name, "SearchValue2") == 0) {
			$fldparm = "y_" . $fldparm;
		} elseif (strcasecmp($name, "SearchOperator2") == 0) {
			$fldparm = "w_" . $fldparm;
		}
		return $fldparm;
	}

	// List view value
	function ListViewValue() {
		if ($this->FldDataType == EW_DATATYPE_XML) {
			return $this->ViewValue . "&nbsp;";
		} else {
			$value = trim(strval($this->ViewValue));
			if ($value <> "") {
				$value2 = trim(preg_replace('/<[^img][^>]*>/i', '', strval($value)));
				return ($value2 <> "") ? $this->ViewValue : "&nbsp;";
			} else {
				return "&nbsp;";
			}
		}
	}
	var $Exportable = TRUE;

	// Export caption
	function ExportCaption() {
		return (EW_EXPORT_FIELD_CAPTION) ? $this->FldCaption() : $this->FldName;
	}
	var $ExportOriginalValue = EW_EXPORT_ORIGINAL_VALUE;

	// Export value
	function ExportValue() {
		return ($this->ExportOriginalValue) ? $this->CurrentValue : $this->ViewValue;
	}

	// Get temp image
	function GetTempImage() {
		if ($this->FldDataType == EW_DATATYPE_BLOB) {
			$wrkdata = $this->Upload->DbValue;
			if (is_array($wrkdata) || is_object($wrkdata)) // Byte array
				$wrkdata = ew_BytesToStr($wrkdata);		
			if (!empty($wrkdata)) {
				if ($this->ImageResize) {
					$wrkwidth = $this->ImageWidth;
					$wrkheight = $this->ImageHeight;
					ew_ResizeBinary($wrkdata, $wrkwidth, $wrkheight);
				}
				return ew_TmpImage($wrkdata);
			}
		} else {
			$wrkfile = $this->Upload->DbValue;
			if (empty($wrkfile)) $wrkfile = $this->CurrentValue;
			if (!empty($wrkfile)) {
				if (!$this->UploadMultiple) {
					$imagefn = ew_UploadPathEx(TRUE, $this->UploadPath) . $wrkfile;
					if ($this->ImageResize) {
						$wrkwidth = $this->ImageWidth;
						$wrkheight = $this->ImageHeight;
						$wrkdata = ew_ResizeFileToBinary($imagefn, $wrkwidth, $wrkheight);
						return ew_TmpImage($wrkdata);
					} else {
						return $imagefn;
					}
				} else {
					$tmpfiles = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $wrkfile);
					$tmpimage = "";
					foreach ($tmpfiles as $tmpfile) {
						if ($tmpfile <> "") {
							$imagefn = ew_UploadPathEx(TRUE, $this->UploadPath) . $tmpfile;
							if ($this->ImageResize) {
								$wrkwidth = $this->ImageWidth;
								$wrkheight = $this->ImageHeight;
								$wrkdata = ew_ResizeFileToBinary($imagefn, $wrkwidth, $wrkheight);
								if ($tmpimage <> "")
									$tmpimage .= ",";
								$tmpimage .= ew_TmpImage($wrkdata);
							} else {
								if ($tmpimage <> "")
									$tmpimage .= ",";
								$tmpimage .= ew_ConvertFullUrl($this->UploadPath . $tmpfile);
							}
						}
					}
					return $tmpimage;
				}
			}
		}
	}

	// Form value
	function setFormValue($v, $current = TRUE) {
		$v = ew_StripSlashes($v);
		if (is_array($v))
			$v = implode(",", $v);
		if ($this->FldDataType == EW_DATATYPE_NUMBER && !ew_IsNumeric($v) && !ew_Empty($v)) // Check data type
			$this->FormValue = NULL;
		else
			$this->FormValue = $v;
		if ($current)
			$this->CurrentValue = $this->FormValue;
	}

	// Old value (from $_POST)
	function setOldValue($v) {
		$v = ew_StripSlashes($v);
		if (is_array($v))
			$v = implode(",", $v);
		if ($this->FldDataType == EW_DATATYPE_NUMBER && !ew_IsNumeric($v) && !ew_Empty($v)) // Check data type
			$this->OldValue = NULL;
		else
			$this->OldValue = $v;
	}

	// QueryString value
	function setQueryStringValue($v, $current = TRUE) {
		$v = ew_StripSlashes($v);
		if (is_array($v))
			$v = implode(",", $v);
		if ($this->FldDataType == EW_DATATYPE_NUMBER && !ew_IsNumeric($v) && !ew_Empty($v)) // Check data type
			$this->QueryStringValue = NULL;
		else
			$this->QueryStringValue = $v;
		if ($current)
			$this->CurrentValue = $this->QueryStringValue;
	}

	// Database value
	function setDbValue($v) {
		$this->DbValue = $v;
		$this->CurrentValue = $this->DbValue;
	}

	// Set database value with error default
	function SetDbValueDef(&$rs, $value, $default, $skip = FALSE) {
		if ($skip || !$this->Visible || $this->Disabled)
			return;
		switch ($this->FldType) {
			case 2:
			case 3:
			case 16:
			case 17:
			case 18:  // Integer
				$value = trim($value);
				$value = ew_StrToInt($value);
				$DbValue = (is_numeric($value)) ? intval($value) : $default;
				break;
			case 19:
			case 20:
			case 21: // Big integer
				$value = trim($value);
				$value = ew_StrToInt($value);
				$DbValue = (is_numeric($value)) ? $value : $default;
				break;
			case 5:
			case 6:
			case 14:
			case 131: // Double
			case 139:
			case 4: // Single
				$value = trim($value);
				$value = ew_StrToFloat($value);
				$DbValue = (is_numeric($value)) ? $value : $default;
				break;
			case 7:
			case 133:
			case 134:
			case 135: // Date
			case 141: // XML
			case 145: // Time
			case 146: // DateTiemOffset
			case 201:
			case 203:
			case 129:
			case 130:
			case 200:
			case 202: // String
				$value = trim($value);
				$DbValue = ($value == "") ? $default : $value;
				break;
			case 128:
			case 204:
			case 205: // Binary
				$DbValue = (is_null($value)) ? $default : $value;
				break;
			case 72: // GUID
				$value = trim($value);
				$DbValue = ($value <> "" && ew_CheckGUID($value)) ? $value : $default;
				break;
			case 11: // Boolean
				$DbValue = (is_bool($value) || is_numeric($value)) ? $value : $default;
				break;
			default:
				$DbValue = $value;
		}

		//$this->setDbValue($DbValue); // Do not override CurrentValue
		$this->DbValue = $DbValue;
		$rs[$this->FldName] = $this->DbValue;
	}

	// Session value
	function getSessionValue() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TblVar . "_" . $this->FldVar . "_SessionValue"];
	}

	function setSessionValue($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TblVar . "_" . $this->FldVar . "_SessionValue"] = $v;
	}

	// Lookup filter query
	function LookupFilterQuery($isAutoSuggest = FALSE, $pageId = NULL) {
		global $gsLanguage;
		$tbl = $GLOBALS[$this->TblVar];
		if ($isAutoSuggest) {
			if (method_exists($tbl, "SetupAutoSuggestFilters"))
				$tbl->SetupAutoSuggestFilters($this, $pageId);
		} else {
			if (method_exists($tbl, "SetupLookupFilters"))
				$tbl->SetupLookupFilters($this, $pageId);
		}
		foreach ($this->LookupFilters as $key => &$value) {
			if (preg_match('/^f\d+$|^s$|^dx\d+$/', $key)) // "f<n>" or "s" or "dx<n>"
				$value = ew_Encrypt($value); // Encrypt SQL and filter
		}
		$this->LookupFilters["lang"] = @$gsLanguage;
		return http_build_query($this->LookupFilters);
	}
}
/**
 * List option collection class
 */

class cListOptions {
	var $Items = array();
	var $CustomItem = "";
	var $Tag = "td";
	var $TagClassName = "";
	var $TableVar = "";
	var $RowCnt = "";
	var $ScriptType = "block";
	var $ScriptId = "";
	var $ScriptClassName = "";
	var $JavaScript = "";
	var $RowSpan = 1;
	var $UseDropDownButton = FALSE;
	var $UseButtonGroup = FALSE;
	var $ButtonClass = "";
	var $GroupOptionName = "button";
	var $DropDownButtonPhrase = "";
	var $UseImageAndText = FALSE;

	// Check visible
	function Visible() {
		foreach ($this->Items as $item) {
			if ($item->Visible)
				return TRUE;
		}
		return FALSE;
	}

	// Check group option visible
	function GroupOptionVisible() {
		$cnt = 0;
		foreach ($this->Items as $item) {
			if ($item->Name <> $this->GroupOptionName && 
				(($item->Visible && $item->ShowInDropDown && $this->UseDropDownButton) ||
				($item->Visible && $item->ShowInButtonGroup && $this->UseButtonGroup))) {
				$cnt += 1;
				if ($this->UseDropDownButton && $cnt > 1)
					return TRUE;
				elseif ($this->UseButtonGroup)
					return TRUE;
			}
		}
		return FALSE;
	}

	// Add and return a new option
	function &Add($Name) {
		$item = new cListOption($Name);
		$item->Parent = &$this;
		$this->Items[$Name] = $item;
		return $item;
	}

	// Load default settings
	function LoadDefault() {
		$this->CustomItem = "";
		foreach ($this->Items as $key => $item)
			$this->Items[$key]->Body = "";
	}

	// Hide all options
	function HideAllOptions($Lists=array()) {
		foreach ($this->Items as $key => $item)
			if (!in_array($key, $Lists))
				$this->Items[$key]->Visible = FALSE;
	}

	// Show all options
	function ShowAllOptions() {
		foreach ($this->Items as $key => $item)
			$this->Items[$key]->Visible = TRUE;
	}

	// Get item by name
	// Predefined names: view/edit/copy/delete/detail_<DetailTable>/userpermission/checkbox
	function &GetItem($Name) {
		$item = array_key_exists($Name, $this->Items) ? $this->Items[$Name] : NULL;
		return $item;
	}

	// Get item position
	function ItemPos($Name) {
		$pos = 0;
		foreach ($this->Items as $item) {
			if ($item->Name == $Name)
				return $pos;
			$pos++;
		}
		return FALSE;
	}

	// Move item to position
	function MoveItem($Name, $Pos) {
		$cnt = count($this->Items);
		if ($Pos < 0) // If negative, count from the end
			$Pos = $cnt + $Pos;
		if ($Pos < 0)
			$Pos = 0;
		if ($Pos >= $cnt)
			$Pos = $cnt - 1;
		$item = $this->GetItem($Name);
		if ($item) {
			unset($this->Items[$Name]);
			$this->Items = array_merge(array_slice($this->Items, 0, $Pos),
				array($Name => $item), array_slice($this->Items, $Pos));
		}
	}

	// Render list options
	function Render($Part, $Pos="", $RowCnt="", $ScriptType="block", $ScriptId="", $ScriptClassName="") {
		if ($this->CustomItem == "" && $groupitem = &$this->GetItem($this->GroupOptionName) && $this->ShowPos($groupitem->OnLeft, $Pos)) {
			if ($this->UseDropDownButton) { // Render dropdown
				$buttonvalue = "";
				$cnt = 0;
				foreach ($this->Items as $item) {
					if ($item->Name <> $this->GroupOptionName && $item->Visible) {
						if ($item->ShowInDropDown) {
							$buttonvalue .= $item->Body;
							$cnt += 1;
						} elseif ($item->Name == "listactions") { // Show listactions as button group
							$item->Body = $this->RenderButtonGroup($item->Body);
						}
					}
				}
				if ($cnt <= 1) {
					$this->UseDropDownButton = FALSE; // No need to use drop down button
				} else {
					$groupitem->Body = $this->RenderDropDownButton($buttonvalue, $Pos);
					$groupitem->Visible = TRUE;
				}
			}
			if (!$this->UseDropDownButton && $this->UseButtonGroup) { // Render button group
				$visible = FALSE;
				$buttongroups = array();
				foreach ($this->Items as $item) {
					if ($item->Name <> $this->GroupOptionName && $item->Visible && $item->Body <> "") {
						if ($item->ShowInButtonGroup) {
							$visible = TRUE;
							$buttonvalue = ($this->UseImageAndText) ? $item->GetImageAndText($item->Body) : $item->Body;
							if (!array_key_exists($item->ButtonGroupName, $buttongroups)) $buttongroups[$item->ButtonGroupName] = "";
							$buttongroups[$item->ButtonGroupName] .= $buttonvalue;
						} elseif ($item->Name == "listactions") { // Show listactions as button group
							$item->Body = $this->RenderButtonGroup($item->Body);
						}
					}
				}
				$groupitem->Body = "";
				foreach ($buttongroups as $buttongroup => $buttonvalue)
					$groupitem->Body .= $this->RenderButtonGroup($buttonvalue);
				if ($visible)
					$groupitem->Visible = TRUE;
			}
		}
		if ($ScriptId <> "") {
			$this->RenderEx($Part, $Pos, $RowCnt, "block", $ScriptId, $ScriptClassName); // Original block for ew_ShowTemplates
			$this->RenderEx($Part, $Pos, $RowCnt, "blocknotd", $ScriptId);
			$this->RenderEx($Part, $Pos, $RowCnt, "single", $ScriptId);
		} else {
			$this->RenderEx($Part, $Pos, $RowCnt, $ScriptType, $ScriptId, $ScriptClassName);
		}
	}

	// Render
	function RenderEx($Part, $Pos="", $RowCnt="", $ScriptType="block", $ScriptId="", $ScriptClassName="") {
		$this->RowCnt = $RowCnt;
		$this->ScriptType = $ScriptType;
		$this->ScriptId = $ScriptId;
		$this->ScriptClassName = $ScriptClassName;
		$this->JavaScript = "";
		if ($ScriptId <> "") {
			$this->Tag = ($ScriptType == "block") ? "td" : "span";
			if ($ScriptType == "block") {
				if ($Part == "header")
					echo "<script id=\"tpoh_" . $ScriptId . "\" class=\"" . $ScriptClassName . "\" type=\"text/html\">";
				else if ($Part == "body")
					echo "<script id=\"tpob" . $RowCnt . "_" . $ScriptId . "\" class=\"" . $ScriptClassName . "\" type=\"text/html\">";
				else if ($Part == "footer")
					echo "<script id=\"tpof_" . $ScriptId . "\" class=\"" . $ScriptClassName . "\" type=\"text/html\">";
			} elseif ($ScriptType == "blocknotd") {
				if ($Part == "header")
					echo "<script id=\"tpo2h_" . $ScriptId . "\" class=\"" . $ScriptClassName . "\" type=\"text/html\">";
				else if ($Part == "body")
					echo "<script id=\"tpo2b" . $RowCnt . "_" . $ScriptId . "\" class=\"" . $ScriptClassName . "\" type=\"text/html\">";
				else if ($Part == "footer")
					echo "<script id=\"tpo2f_" . $ScriptId . "\" class=\"" . $ScriptClassName . "\" type=\"text/html\">";
				echo "<span>";
			}
		} else {

			//$this->Tag = ($Pos <> "" && $Pos <> "bottom") ? "td" : "span";
			$this->Tag = ($Pos <> "" && $Pos <> "bottom") ? "td" : "div";
		}
		if ($this->CustomItem <> "") {
			$cnt = 0;
			$opt = NULL;
			foreach ($this->Items as &$item) {
				if ($this->ShowItem($item, $ScriptId,  $Pos))
					$cnt++;
				if ($item->Name == $this->CustomItem)
					$opt = &$item;
			}
			$bUseButtonGroup = $this->UseButtonGroup; // Backup options
			$bUseImageAndText = $this->UseImageAndText;
			$this->UseButtonGroup = TRUE; // Show button group for custom item
			$this->UseImageAndText = TRUE; // Use image and text for custom item
			if (is_object($opt) && $cnt > 0) {
				if ($ScriptId <> "" || $this->ShowPos($opt->OnLeft, $Pos)) {
					echo $opt->Render($Part, $cnt);
				} else {
					echo $opt->Render("", $cnt);
				}
			}
			$this->UseButtonGroup = $bUseButtonGroup; // Restore options
			$this->UseImageAndText = $bUseImageAndText;
		} else {
			foreach ($this->Items as &$item) {
				if ($this->ShowItem($item, $ScriptId,  $Pos))
					echo $item->Render($Part, 1);
			}
		}
		if (($ScriptType == "block" || $ScriptType == "blocknotd") && $ScriptId <> "") {
			if ($ScriptType == "blocknotd")
				echo "</span>";
			echo "</script>";
			if ($this->JavaScript <> "")
				echo $this->JavaScript;
		}
	}

	// Show item
	function ShowItem($item, $ScriptId, $Pos) {
		$show = $item->Visible && ($ScriptId <> "" || $this->ShowPos($item->OnLeft, $Pos));
		if ($show)
			if ($this->UseDropDownButton)
				$show = ($item->Name == $this->GroupOptionName || !$item->ShowInDropDown);
			elseif ($this->UseButtonGroup)
				$show = ($item->Name == $this->GroupOptionName || !$item->ShowInButtonGroup);
		return $show;
	}

	// Show position
	function ShowPos($OnLeft, $Pos) {
		return ($OnLeft && $Pos == "left") || (!$OnLeft && $Pos == "right") || ($Pos == "") || ($Pos == "bottom");
	}

	// Concat options and return concatenated HTML
	// - pattern - regular expression pattern for matching the option names, e.g. '/^detail_/'
	function Concat($pattern, $separator = "") {
		$ar = array();
		$keys = array_keys($this->Items);
		foreach ($keys as $key) {
			if (preg_match($pattern, $key) && trim($this->Items[$key]->Body) <> "")
				$ar[] = $this->Items[$key]->Body;
		}
		return implode($separator, $ar);
	}

	// Merge options to the first option and return it
	// - pattern - regular expression pattern for matching the option names, e.g. '/^detail_/'
	function &Merge($pattern, $separator = "") {
		$keys = array_keys($this->Items);
		$first = NULL;
		foreach ($keys as $key) {
			if (preg_match($pattern, $key)) {
				if (!$first) {
					$first = $this->Items[$key];
					$first->Body = $this->Concat($pattern, $separator);
				} else {
					$this->Items[$key]->Visible = FALSE;
				}
			}
		}
		return $first;
	}

	// Get button group link
	function RenderButtonGroup($body) {

		// Get all hidden inputs
		// format: <input type="hidden" ...>

		$inputs = array();
		if (preg_match_all('/<input\s+([^>]*)>/i', $body, $inputmatches, PREG_SET_ORDER)) {
			foreach ($inputmatches as $inputmatch) {
				$body = str_replace($inputmatch[0], '', $body); 
				if (preg_match('/\s+type\s*=\s*[\'"]hidden[\'"]/i', $inputmatch[0])) // Match type='hidden'
					$inputs[] = $inputmatch[0];
			}
		}

		// Get all buttons
		// format: <div class="btn-group">...</div>

		$btns = array();
		if (preg_match_all('/<div\s+class\s*=\s*[\'"]btn-group[\'"]([^>]*)>([\s\S]*?)<\/div\s*>/i', $body, $btnmatches, PREG_SET_ORDER)) {
			foreach ($btnmatches as $btnmatch) {
				$body = str_replace($btnmatch[0], '', $body); 
				$btns[] = $btnmatch[0];
			}
		}
		$links = '';

		// Get all links/buttons
		// format: <a ...>...</a> / <button ...>...</button>

		if (preg_match_all('/<(a|button)([^>]*)>([\s\S]*?)<\/(a|button)\s*>/i', $body, $matches, PREG_SET_ORDER)) {
			foreach ($matches as $match) {
				$tag = $match[1];
				if (preg_match('/\s+class\s*=\s*[\'"]([\s\S]*?)[\'"]/i', $match[2], $submatches)) { // Match class='class'
					$class = $submatches[1];
					$attrs = str_replace($submatches[0], '', $match[2]);
				} else {
					$class = '';
					$attrs = $match[2];
				}
				$caption = $match[3];
				if (strpos($class, 'btn btn-default') === FALSE) // Prepend button classes
					ew_PrependClass($class, 'btn btn-default');
				if ($this->ButtonClass <> "")
					ew_AppendClass($class, $this->ButtonClass);
				$attrs = ' class="' . $class . '" ' . $attrs;
 				$link ='<' . $tag . $attrs . '>' . $caption . '</' . $tag . '>';
				$links .= $link;
			}
		}
		if ($links <> "")
			$btngroup = '<div class="btn-group ewButtonGroup">' . $links . '</div>';
		else
			$btngroup = "";
		foreach ($btns as $btn)
			$btngroup .= $btn;
		foreach ($inputs as $input)
			$btngroup .= $input;
		return $btngroup;
	}

	// Render drop down button
	function RenderDropDownButton($body, $pos) {

		// Get all hidden inputs
		// format: <input type="hidden" ...>

		$inputs = array();
		if (preg_match_all('/<input\s+([^>]*)>/i', $body, $inputmatches, PREG_SET_ORDER)) {
			foreach ($inputmatches as $inputmatch) {
				$body = str_replace($inputmatch[0], '', $body); 
				if (preg_match('/\s+type\s*=\s*[\'"]hidden[\'"]/i', $inputmatch[0])) // Match type='hidden'
					$inputs[] = $inputmatch[0];
			}
		}

		// Remove all <div class="hide ewPreview">...</div>
		$previewlinks = "";
		if (preg_match_all('/<div\s+class\s*=\s*[\'"]hide\s+ewPreview[\'"]>([\s\S]*?)(<div([^>]*)>([\s\S]*?)<\/div\s*>)+([\s\S]*?)<\/div\s*>/i', $body, $inputmatches, PREG_SET_ORDER)) {
			foreach ($inputmatches as $inputmatch) {
				$body = str_replace($inputmatch[0], '', $body);
				$previewlinks .= $inputmatch[0];
			}
		}

		// Remove toggle button first <button ... data-toggle="dropdown">...</button>
		if (preg_match_all('/<button\s+([\s\S]*?)data-toggle\s*=\s*[\'"]dropdown[\'"]\s*>([\s\S]*?)<\/button\s*>/i', $body, $btnmatches, PREG_SET_ORDER)) {
			foreach ($btnmatches as $btnmatch)
				$body = str_replace($btnmatch[0], '', $body);
		}

		// Get all links/buttons <a ...>...</a> / <button ...>...</button>
		if (!preg_match_all('/<(a|button)([^>]*)>([\s\S]*?)<\/(a|button)\s*>/i', $body, $matches, PREG_SET_ORDER))
			return '';
		$links = '';
		$submenu = FALSE;
		$submenulink = "";
		$submenulinks = "";
		foreach ($matches as $match) {
			$tag = $match[1];
			if (preg_match('/\s+data-action\s*=\s*[\'"]([\s\S]*?)[\'"]/i', $match[2], $actionmatches)) { // Match data-action='action'
				$action = $actionmatches[1];
			} else {
				$action = '';
			}
			if (preg_match('/\s+class\s*=\s*[\'"]([\s\S]*?)[\'"]/i', $match[2], $submatches)) { // Match class='class'
				$class = preg_replace('/btn[\S]*\s+/i', '', $submatches[1]);
				$attrs = str_replace($submatches[0], '', $match[2]);
			} else {
				$class = '';
				$attrs = $match[2];
			}
			$attrs = preg_replace('/\s+title\s*=\s*[\'"]([\s\S]*?)[\'"]/i', '', $attrs); // Remove title='title'
			if (preg_match('/\s+data-caption\s*=\s*[\'"]([\s\S]*?)[\'"]/i', $attrs, $submatches)) // Match data-caption='caption'
				$caption = $submatches[1];
			else
				$caption = '';
			$attrs = ' class="' . $class . '" ' . $attrs;
			if (strtolower($tag) == "button") // Add href for button
				$attrs .= ' href="javascript:void(0);"';
			if ($this->UseImageAndText) { // Image and text
				if (preg_match('/<img([^>]*)>/i', $match[3], $submatch)) // <img> tag
					$caption = $submatch[0] . '&nbsp;&nbsp;' . $caption;
				elseif (preg_match('/<span([^>]*)>([\s\S]*?)<\/span\s*>/i', $match[3], $submatch)) // <span class='class'></span> tag
					if (preg_match('/\s+class\s*=\s*[\'"]([\s\S]*?)[\'"]/i', $submatch[1], $submatches)) // Match class='class'
						$caption = $submatch[0] . '&nbsp;&nbsp;' . $caption;
			}
			if ($caption == '')
				$caption = $match[3];
			$link = '<a' . $attrs . '>' . $caption . '</a>';
			if ($action == 'list') { // Start new submenu
				if ($submenu) { // End previous submenu
					if ($submenulinks <> '') { // Set up submenu
						$links .= '<li class="dropdown-submenu">' . $submenulink . '<ul class="dropdown-menu">' . $submenulinks . '</ul></li>';
					} else {
						$links .= '<li>' . $submenulink . '</li>';
					}
				}
				$submenu = TRUE;
				$submenulink = $link;
				$submenulinks = "";
			} else {
				if ($action == '' && $submenu) { // End previous submenu
					if ($submenulinks <> '') { // Set up submenu
						$links .= '<li class="dropdown-submenu">' . $submenulink . '<ul class="dropdown-menu">' . $submenulinks . '</ul></li>';
					} else {
						$links .= '<li>' . $submenulink . '</li>';
					}
					$submenu = FALSE;
				}
				if ($submenu)
					$submenulinks .= '<li>' . $link . '</li>';
				else
					$links .= '<li>' . $link . '</li>';
			}
		}
		if ($links <> "") {
			if ($submenu) { // End previous submenu
				if ($submenulinks <> '') { // Set up submenu
					$links .= '<li class="dropdown-submenu">' . $submenulink . '<ul class="dropdown-menu">' . $submenulinks . '</ul></li>';
				} else {
					$links .= '<li>' . $submenulink . '</li>';
				}
			}
			$buttonclass = "dropdown-toggle btn btn-default";
			if ($this->ButtonClass <> "")
				ew_AppendClass($buttonclass, $this->ButtonClass);
			$buttontitle = ew_HtmlTitle($this->DropDownButtonPhrase);
			$buttontitle = ($this->DropDownButtonPhrase <> $buttontitle) ? ' title="' . $buttontitle . '"' : '';
			$button = '<button class="' . $buttonclass .'"' . $buttontitle . ' data-toggle="dropdown">' . $this->DropDownButtonPhrase . '<b class="caret"></b></button><ul class="dropdown-menu ' . (($pos == 'right') ? 'dropdown-menu-right ' : '') . 'ewMenu">' . $links . '</ul>';
			if ($pos == "bottom") // Use dropup
				$btndropdown = '<div class="btn-group dropup ewButtonDropdown">' . $button . '</div>';
			else
				$btndropdown = '<div class="btn-group ewButtonDropdown">' . $button . '</div>';
		} else {
			$btndropdown = "";
		}
		foreach ($inputs as $input)
			$btndropdown .= $input;
		$btndropdown .= $previewlinks;
		return $btndropdown;
	}

	// Hide detail items for dropdown
	function HideDetailItemsForDropDown() {
		$showdtl = FALSE;
		if ($this->UseDropDownButton) {
			foreach ($this->Items as $item) {
				if ($item->Name <> $this->GroupOptionName && $item->Visible && $item->ShowInDropDown && substr($item->Name,0,7) <> "detail_") {
					$showdtl = TRUE;
					break;
				}
			}
		}
		if (!$showdtl) {
			foreach ($this->Items as $item) {
				if (substr($item->Name,0,7) == "detail_") {
					$item->Visible = FALSE;
				}
			}
		}
	}
}
/**
 * List option class
 */

class cListOption {
	var $Name;
	var $OnLeft;
	var $CssStyle;
	var $CssClass;
	var $Visible = TRUE;
	var $Header;
	var $Body;
	var $Footer;
	var $Parent;
	var $ShowInButtonGroup = TRUE;
	var $ShowInDropDown = TRUE;
	var $ButtonGroupName = "_default";

	// Constructor
	function __construct($Name) {
		$this->Name = $Name;
	}

	// Clear
	function Clear() {
		$this->Body = "";
	}

	// Move to
	function MoveTo($Pos) {
		$this->Parent->MoveItem($this->Name, $Pos);
	}

	// Render
	function Render($Part, $ColSpan = 1) {
		$tagclass = $this->Parent->TagClassName;
		if ($Part == "header") {
			if ($tagclass == "") $tagclass = "ewListOptionHeader";
			$value = $this->Header;
		} elseif ($Part == "body") {
			if ($tagclass == "") $tagclass = "ewListOptionBody";
			if ($this->Parent->Tag <> "td")
				ew_AppendClass($tagclass, "ewListOptionSeparator");
			$value = $this->Body;
		} elseif ($Part == "footer") {
			if ($tagclass == "") $tagclass = "ewListOptionFooter";
			$value = $this->Footer;
		} else {
			$value = $Part;
		}
		if (strval($value) == "" && $this->Parent->Tag == "span" && $this->Parent->ScriptId == "")
			return "";
		$res = ($value <> "") ? $value : "&nbsp;";
		ew_AppendClass($tagclass, $this->CssClass);
		$attrs = array("class" => $tagclass,  "style" => $this->CssStyle, "data-name" => $this->Name);
		if (strtolower($this->Parent->Tag) == "td" && $this->Parent->RowSpan > 1)
			$attrs["rowspan"] = $this->Parent->RowSpan;
		if (strtolower($this->Parent->Tag) == "td" && $ColSpan > 1)
			$attrs["colspan"] = $ColSpan;
		$name = $this->Parent->TableVar . "_" . $this->Name;
		if ($this->Name <> $this->Parent->GroupOptionName) {
			if (!in_array($this->Name, array('checkbox', 'rowcnt'))) {
				if ($this->Parent->UseImageAndText)
					$res = $this->GetImageAndText($res);
				if ($this->Parent->UseButtonGroup && $this->ShowInButtonGroup) {
					$res = $this->Parent->RenderButtonGroup($res);
					if ($this->OnLeft && strtolower($this->Parent->Tag) == "td" && $ColSpan > 1)
						$res = '<div style="text-align: right">' . $res . '</div>';
				}
			}
			if ($Part == "header")
				$res = "<span id=\"elh_" . $name . "\" class=\"" . $name . "\">" . $res . "</span>";
			else if ($Part == "body")
				$res = "<span id=\"el" . $this->Parent->RowCnt . "_" . $name . "\" class=\"" . $name . "\">" . $res . "</span>";
			else if ($Part == "footer")
				$res = "<span id=\"elf_" . $name . "\" class=\"" . $name . "\">" . $res . "</span>";
		}
		$tag = ($this->Parent->Tag == "td" && $Part == "header") ? "th" : $this->Parent->Tag;
		if ($this->Parent->UseButtonGroup && $this->ShowInButtonGroup)
			$attrs["style"] .= "white-space: nowrap;";
		$res = ew_HtmlElement($tag, $attrs, $res);
		if ($this->Parent->ScriptId <> "") {
			$js = ew_ExtractScript($res, $this->Parent->ScriptClassName . "_js");
			if ($this->Parent->ScriptType == "single") {
				if ($Part == "header")
					$res = "<script id=\"tpoh_" . $this->Parent->ScriptId . "_" . $this->Name . "\" type=\"text/html\">" . $res . "</script>";
				else if ($Part == "body")
					$res = "<script id=\"tpob" . $this->Parent->RowCnt . "_" . $this->Parent->ScriptId . "_" . $this->Name . "\" type=\"text/html\">" . $res . "</script>";
				else if ($Part == "footer")
					$res = "<script id=\"tpof_" . $this->Parent->ScriptId . "_" . $this->Name . "\" type=\"text/html\">" . $res . "</script>";
			}
			if ($js <> "")
				if ($this->Parent->ScriptType == "single")
					$res .= $js;
				else
					$this->Parent->JavaScript .= $js;
		}
		return $res;
	}

	// Get image and text link
	function GetImageAndText($body) {
		if (!preg_match_all('/<a([^>]*)>([\s\S]*?)<\/a\s*>/i', $body, $matches, PREG_SET_ORDER))
			return $body;
		foreach ($matches as $match) {
			if (preg_match('/\s+data-caption\s*=\s*[\'"]([\s\S]*?)[\'"]/i', $match[1], $submatches)) { // Match data-caption='caption'
				$caption = $submatches[1];
				if (preg_match('/<img([^>]*)>/i', $match[2])) // Image and text
					$body = str_replace($match[2], $match[2] . '&nbsp;&nbsp;' . $caption, $body);
			}
		}
		return $body;
	}
}

// List actions
class cListActions {
	var $Items = array();

	// Add and return a new option
	function &Add($Name, $Action, $Allow = TRUE, $Method = EW_ACTION_POSTBACK, $Select = EW_ACTION_MULTIPLE, $ConfirmMsg = "", $Icon = "glyphicon glyphicon-star ewIcon", $Success = "") {
		if (is_string($Action))
			$item = new cListAction($Name, $Action, $Allow, $Method, $Select, $ConfirmMsg, $Icon, $Success);
		elseif ($Action instanceof cListAction)
			$item = $Action;
		$this->Items[$Name] = $item;
		return $item;
	}

	// Get item by name
	function &GetItem($Name) {
		$item = array_key_exists($Name, $this->Items) ? $this->Items[$Name] : NULL;
		return $item;
	}
}

// List action
class cListAction {
	var $Action = "";
	var $Caption = "";
	var $Allow = TRUE;
	var $Method = EW_ACTION_POSTBACK; // Post back (p) / Ajax (a)
	var $Select = EW_ACTION_MULTIPLE; // Multiple (m) / Single (s)
	var $ConfirmMsg = "";
	var $Icon = "glyphicon glyphicon-star ewIcon"; // Icon
	var $Success = ""; // JavaScript callback function name

	// Constructor
	function __construct($Action, $Caption, $Allow = TRUE, $Method = EW_ACTION_POSTBACK, $Select = EW_ACTION_MULTIPLE, $ConfirmMsg = "", $Icon = "glyphicon glyphicon-star ewIcon", $Success = "") {
		$this->Action = $Action;
		$this->Caption = $Caption;
		$this->Allow = $Allow;
		$this->Method = $Method;
		$this->Select = $Select;
		$this->ConfirmMsg = $ConfirmMsg;
		$this->Icon = $Icon;
		$this->Success = $Success;
	}

	// To JSON
	function ToJson($htmlencode = FALSE) {
		$ar = array("msg" => $this->ConfirmMsg,
			"action" => $this->Action,
			"method" => $this->Method,
			"select" => $this->Select,
			"success" => $this->Success);
		$json = json_encode($ar);
		if ($htmlencode)
			$json = ew_HtmlEncode($json);
		return $json;
	}
}

// Sub pages
class cSubPages {
	var $Justified = FALSE;
	var $Style = ""; // "tabs" or "pills" or "" (panels)
	var $Items = array();
	var $ValidKeys = NULL;
	var $ActiveIndex = "";

	// Get nav style
	function NavStyle() {
		$style = " nav-" . $this->Style;
		if ($this->Justified) $style .= " nav-justified";
		return $style;
	}

	// Get page style
	function TabStyle($k) {
		$item = $this->GetItem($k);
		$style = "";
		if ($this->ActivePageIndex() == $k)
			$style = "active";
		elseif ($item)
			if (!$item->Visible)
				$style = "hidden ewHidden";
			elseif ($item->Disabled && $this->Style <> "")
				$style = "disabled ewDisabled";
		return ($style <> "") ? " class=\"" . $style . "\"" : "";
	}

	// Get page style
	function PageStyle($k) {
		if ($this->ActivePageIndex() == $k)
			if ($this->Style == "")
				return " in";
			else
				return " active";
		$item = $this->GetItem($k);
		if ($item)
			if (!$item->Visible)
				return " hidden ewHidden";
			elseif ($item->Disabled && $this->Style <> "")
				return " disabled ewDisabled";
		return "";
	}

	// Get count
	function Count() {
		return count($this->Items);
	}

	// Add item by name
	function &Add($Name = "") {
		$item = new cSubPage();
		if (strval($Name) <> "")
			$this->Items[$Name] = $item;
		if (!is_int($Name))
			$this->Items[] = $item;
		return $item;
	}

	// Get item by key
	function &GetItem($k) {
		$item = array_key_exists($k, $this->Items) ? $this->Items[$k] : NULL;
		return $item;
	}

	// Active page index
	function ActivePageIndex() {

		// Return first active page
		foreach ($this->Items as $key => $item)
			if ((!is_array($this->ValidKeys) || in_array($key, $this->ValidKeys)) && $item->Visible && !$item->Disabled && $item->Active && $key !== 0) { // Not common page
				$this->ActiveIndex = $key;
				return $this->ActiveIndex;
			}

		// If not found, return first visible page
		foreach ($this->Items as $key => $item)
			if ((!is_array($this->ValidKeys) || in_array($key, $this->ValidKeys)) && $item->Visible && !$item->Disabled && $key !== 0) { // Not common page
				$this->ActiveIndex = $key;
				return $this->ActiveIndex;
			}

		// Not found
		return NULL;
	}
}

// Sub page
class cSubPage {
	var $Active = FALSE;
	var $Visible = TRUE; // If FALSE, add class "hidden ewHidden" to the li or div.panel
	var $Disabled = FALSE; // If TRUE, add class "disabled ewDisabled" to the li (for tabs only, panels cannot be disabled)
}
?>
<?php

//
// Basic Search class
//
class cBasicSearch {
	var $TblVar = "";
	var $BasicSearchAnyFields = EW_BASIC_SEARCH_ANY_FIELDS;
	var $Keyword = "";
	var $KeywordDefault = "";
	var $Type = "";
	var $TypeDefault = "";
	private $_Prefix = "";

	// Constructor
	function __construct($tblvar) {
		$this->TblVar = $tblvar;
		$this->_Prefix = EW_PROJECT_NAME . "_" . $tblvar . "_";
	}

	// Session variable name
	function GetSessionName($suffix) {
		return $this->_Prefix . $suffix;
	}

	// Load default
	function LoadDefault() {
		$this->Keyword = $this->KeywordDefault;
		$this->Type = $this->TypeDefault;
		if (!isset($_SESSION[$this->GetSessionName(EW_TABLE_BASIC_SEARCH_TYPE)]) && $this->TypeDefault != "") // Save default to session
			$this->setType($this->TypeDefault);
	}

	// Unset session
	function UnsetSession() {
		unset($_SESSION[$this->GetSessionName(EW_TABLE_BASIC_SEARCH_TYPE)]);
		unset($_SESSION[$this->GetSessionName(EW_TABLE_BASIC_SEARCH)]);
	}

	// Isset session
	function IssetSession() {
		return isset($_SESSION[$this->GetSessionName(EW_TABLE_BASIC_SEARCH)]);
	}

	// Set keyword
	function setKeyword($v) {
		$this->Keyword = $v;
		$_SESSION[$this->GetSessionName(EW_TABLE_BASIC_SEARCH)] = $v;
	}

	// Set type
	function setType($v) {
		$this->Type = $v;
		$_SESSION[$this->GetSessionName(EW_TABLE_BASIC_SEARCH_TYPE)] = $v;
	}

	// Save
	function Save() {
		$_SESSION[$this->GetSessionName(EW_TABLE_BASIC_SEARCH)] = $this->Keyword;
		$_SESSION[$this->GetSessionName(EW_TABLE_BASIC_SEARCH_TYPE)] = $this->Type;
	}

	// Get keyword
	function getKeyword() {
		return @$_SESSION[$this->GetSessionName(EW_TABLE_BASIC_SEARCH)];
	}

	// Get type
	function getType() {
		return @$_SESSION[$this->GetSessionName(EW_TABLE_BASIC_SEARCH_TYPE)];
	}

	// Get type name
	function getTypeName() {
		global $Language;
		$typ = $this->getType();
		switch ($typ) {
			case "=": return $Language->Phrase("QuickSearchExact");
			case "AND": return $Language->Phrase("QuickSearchAll");
			case "OR": return $Language->Phrase("QuickSearchAny");
			default: return $Language->Phrase("QuickSearchAuto");
		}
	}

	// Get short type name
	function getTypeNameShort() {
		global $Language;
		$typ = $this->getType();
		switch ($typ) {
			case "=": $typname = $Language->Phrase("QuickSearchExactShort"); break;
			case "AND": $typname = $Language->Phrase("QuickSearchAllShort"); break;
			case "OR": $typname = $Language->Phrase("QuickSearchAnyShort"); break;
			default: $typname = $Language->Phrase("QuickSearchAutoShort"); break;
		}
		if ($typname <> "") $typname .= "&nbsp;";
		return $typname;
	}

	// Load
	function Load() {
		$this->Keyword = $this->getKeyword();
		$this->Type = $this->getType();
	}
}
/**
 * Advanced Search class
 */

class cAdvancedSearch {
	var $TblVar;
	var $FldVar;
	var $SearchValue; // Search value
	var $ViewValue = ""; // View value
	var $SearchOperator; // Search operator
	var $SearchCondition; // Search condition
	var $SearchValue2; // Search value 2
	var $ViewValue2 = ""; // View value 2
	var $SearchOperator2; // Search operator 2
	var $SearchValueDefault = ""; // Search value default
	var $SearchOperatorDefault = ""; // Search operator default
	var $SearchConditionDefault = ""; // Search condition default
	var $SearchValue2Default = ""; // Search value 2 default
	var $SearchOperator2Default = ""; // Search operator 2 default
	private $_Prefix = "";
	private $_Suffix = "";

	// Constructor
	function __construct($tblvar, $fldvar) {
		$this->TblVar = $tblvar;
		$this->FldVar = $fldvar;
		$this->_Prefix = EW_PROJECT_NAME . "_" . $tblvar . "_" . EW_TABLE_ADVANCED_SEARCH . "_";
		$this->_Suffix = "_" . substr($fldvar, 2);
	}

	// Session variable name
	function GetSessionName($infix) {
		return $this->_Prefix . $infix . $this->_Suffix;
	}

	// Unset session
	function UnsetSession() {
		unset($_SESSION[$this->GetSessionName("x")]);
		unset($_SESSION[$this->GetSessionName("z")]);
		unset($_SESSION[$this->GetSessionName("v")]);
		unset($_SESSION[$this->GetSessionName("y")]);
		unset($_SESSION[$this->GetSessionName("w")]);
	}

	// Isset session
	function IssetSession() {
		return isset($_SESSION[$this->GetSessionName("x")]) ||
			isset($_SESSION[$this->GetSessionName("y")]);
	}

	// Save to session
	function Save() {
		$FldVal = ew_StripSlashes($this->SearchValue);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);
		$FldVal2 = ew_StripSlashes($this->SearchValue2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		if (@$_SESSION[$this->GetSessionName("x")] <> $FldVal)
			$_SESSION[$this->GetSessionName("x")] = $FldVal;
		if (@$_SESSION[$this->GetSessionName("y")] <> $FldVal2)
			$_SESSION[$this->GetSessionName("y")] = $FldVal2;
		if (@$_SESSION[$this->GetSessionName("z")] <> $this->SearchOperator)
			$_SESSION[$this->GetSessionName("z")] = $this->SearchOperator;
		if (@$_SESSION[$this->GetSessionName("v")] <> $this->SearchCondition)
			$_SESSION[$this->GetSessionName("v")] = $this->SearchCondition;
		if (@$_SESSION[$this->GetSessionName("w")] <> $this->SearchOperator2)
			$_SESSION[$this->GetSessionName("w")] = $this->SearchOperator2;
	}

	// Load from session
	function Load() {
		$this->SearchValue = @$_SESSION[$this->GetSessionName("x")];
		$this->SearchOperator = @$_SESSION[$this->GetSessionName("z")];
		$this->SearchCondition = @$_SESSION[$this->GetSessionName("v")];
		$this->SearchValue2 = @$_SESSION[$this->GetSessionName("y")];
		$this->SearchOperator2 = @$_SESSION[$this->GetSessionName("w")];
	}

	// Get value
	function getValue($infix) {
		return @$_SESSION[$this->GetSessionName($infix)];
	}

	// Load default values
	function LoadDefault() {
		if ($this->SearchValueDefault != "") $this->SearchValue = $this->SearchValueDefault;
		if ($this->SearchOperatorDefault != "") $this->SearchOperator = $this->SearchOperatorDefault;
		if ($this->SearchConditionDefault != "") $this->SearchCondition = $this->SearchConditionDefault;
		if ($this->SearchValue2Default != "") $this->SearchValue2 = $this->SearchValue2Default;
		if ($this->SearchOperator2Default != "") $this->SearchOperator2 = $this->SearchOperator2Default;
	}

	// Convert to JSON
	function ToJSON() {
		if ($this->SearchValue <> "" || $this->SearchValue2 <> "" || in_array($this->SearchOperator, array("IS NULL", "IS NOT NULL")) || in_array($this->SearchOperator2, array("IS NULL", "IS NOT NULL"))) {
			return "\"x" . $this->_Suffix . "\":\"" . ew_JsEncode2($this->SearchValue) . "\"," .
				"\"z" . $this->_Suffix . "\":\"" . ew_JsEncode2($this->SearchOperator) . "\"," .
				"\"v" . $this->_Suffix . "\":\"" . ew_JsEncode2($this->SearchCondition) . "\"," .
				"\"y" . $this->_Suffix . "\":\"" . ew_JsEncode2($this->SearchValue2) . "\"," .
				"\"w" . $this->_Suffix . "\":\"" . ew_JsEncode2($this->SearchOperator2) . "\"";
		} else {
			return "";
		}
	}
}
?>
<?php
/**
 * Upload class
 */

class cUpload {
	var $Index = -1; // Index for multiple form elements
	var $TblVar; // Table variable
	var $FldVar; // Field variable
	var $Message; // Error message
	var $DbValue; // Value from database
	var $Value = NULL; // Upload value
	var $FileName; // Upload file name
	var $FileSize; // Upload file size
	var $ContentType; // File content type
	var $ImageWidth; // Image width
	var $ImageHeight; // Image height
	var $Error; // Upload error
	var $UploadMultiple = FALSE; // Multiple upload
	var $KeepFile = TRUE; // Keep old file
	var $Plugins = array(); // Plugins for Resize()

	// Constructor
	function __construct($TblVar, $FldVar, $Binary = FALSE) {
		$this->TblVar = $TblVar;
		$this->FldVar = $FldVar;
	}

	// Check file type of the uploaded file
	function UploadAllowedFileExt($filename) {
		return ew_CheckFileType($filename);
	}

	// Get upload file
	function UploadFile() {
		global $objForm;
		$this->Value = NULL; // Reset first
		$fldvar = ($this->Index < 0) ? $this->FldVar : substr($this->FldVar, 0, 1) . $this->Index . substr($this->FldVar, 1);
		$wrkvar = "fn_" . $fldvar;
		$this->FileName = @$_POST[$wrkvar]; // Get file name
		$wrkvar = "fa_" . $fldvar;
		$this->KeepFile = (@$_POST[$wrkvar] == "1"); // Check if keep old file
		if (!$this->KeepFile && $this->FileName <> "" && !$this->UploadMultiple) {
			$f = ew_UploadTempPath($fldvar, $this->TblVar) . EW_PATH_DELIMITER . $this->FileName;
			if (file_exists($f)) {
				$this->Value = file_get_contents($f);
				$this->FileSize = filesize($f);
				$this->ContentType = ew_ContentType(substr($this->Value, 0, 11), $f);
				$sizes = @getimagesize($f);
				$this->ImageWidth = @$sizes[0];
				$this->ImageHeight = @$sizes[1];
			}
		}
		return TRUE; // Normal return
	}

	// Resize image
	// Note: $quality is deprecated, kept for backward compatibility only.
	function Resize($width, $height, $quality = EW_THUMBNAIL_DEFAULT_QUALITY) {
		if (!ew_Empty($this->Value)) {
			$wrkwidth = $width;
			$wrkheight = $height;
			if (ew_ResizeBinary($this->Value, $wrkwidth, $wrkheight, $quality, $this->Plugins)) {
				if ($wrkwidth > 0 && $wrkheight > 0) {
					$this->ImageWidth = $wrkwidth;
					$this->ImageHeight = $wrkheight;
				}
				$this->FileSize = strlen($this->Value);
			}
		}
		return $this;
	}

	// Get file count
	function Count() {
		if (!$this->UploadMultiple && !ew_Empty($this->Value)) {
			return 1;
		} elseif ($this->UploadMultiple && $this->FileName <> "") {
			$ar = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->FileName);
			return count($ar);
		}
		return 0;
	}

	// Get temp file path as string or string[]
	function GetTempFile($idx = -1) {
		$fldvar = ($this->Index < 0) ? $this->FldVar : substr($this->FldVar, 0, 1) . $this->Index . substr($this->FldVar, 1);
		if ($this->FileName <> "") {
			if ($this->UploadMultiple) {
				$ar = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $this->FileName);
				if ($idx > -1 && $idx < count($ar)) {
					return ew_UploadTempPath($fldvar, $this->TblVar) . EW_PATH_DELIMITER . $ar[$idx];
				} else {
					$files = array();
					foreach ($ar as $fn)
						$files[] = ew_UploadTempPath($fldvar, $this->TblVar) . EW_PATH_DELIMITER . $fn;
					return $files;
				}
			} else {
				return ew_UploadTempPath($fldvar, $this->TblVar) . EW_PATH_DELIMITER . $this->FileName;
			}
		}
		return NULL;
	}

	// Get temp file as $EW_THUMBNAIL_CLASS object or object[]
	function GetTempThumb($idx = -1) {
		global $EW_RESIZE_OPTIONS, $EW_THUMBNAIL_CLASS;
		$file = $this->GetTempFile($idx);
		if (is_string($file)) {
			return file_exists($file) ? new $EW_THUMBNAIL_CLASS($file, $EW_RESIZE_OPTIONS, $this->Plugins) : NULL;
		} elseif (is_array($file)) {
			$thumbs = array();
			foreach ($file as $fn) {
				if (file_exists($fn))
					$thumbs[] = new $EW_THUMBNAIL_CLASS($fn, $EW_RESIZE_OPTIONS, $this->Plugins);
			}
			return $thumbs;
		}
		return NULL;
	}

	// Save uploaded data to file (Path relative to application root)
	function SaveToFile($Path, $NewFileName, $OverWrite, $idx = -1) {
		if (!ew_Empty($this->Value)) {
			$Path = ew_UploadPathEx(TRUE, $Path);
			if (trim(strval($NewFileName)) == "") $NewFileName = $this->FileName;
			if (!$OverWrite)
				$NewFileName = ew_UploadFileNameEx($Path, $NewFileName);
			return ew_SaveFile($Path, $NewFileName, $this->Value);
		} elseif ($idx >= 0) { // Use file from upload temp folder
			$file = $this->GetTempFile($idx);
			if (file_exists($file)) {
				if (!$OverWrite)
					$NewFileName = ew_UploadFileNameEx($Path, $NewFileName);
				return ew_CopyFile($Path, $NewFileName, $file);
			}
		}
		return FALSE;
	}

	// Resize and save uploaded data to file (Path relative to application root)
	// Note: $Quality is deprecated, kept for backward compatibility only.
	function ResizeAndSaveToFile($Width, $Height, $Quality, $Path, $NewFileName, $OverWrite, $idx = -1) {
		$bResult = FALSE;
		if (!ew_Empty($this->Value)) {
			$OldValue = $this->Value;
			$bResult = $this->Resize($Width, $Height)->SaveToFile($Path, $NewFileName, $OverWrite);
			$this->Value = $OldValue;
		} elseif ($idx >= 0) { // Use file from upload temp folder
			$file = $this->GetTempFile($idx);
			if (file_exists($file)) {
				$this->Value = file_get_contents($file);
				$bResult = $this->Resize($Width, $Height)->SaveToFile($Path, $NewFileName, $OverWrite);
				$this->Value = NULL;
			}
		}
		return $bResult;
	}
}
?>
<?php
/**
 * Advanced Security class
 */

class cAdvancedSecurity {
	var $UserLevel = array(); // All User Levels
	var $UserLevelPriv = array(); // All User Level permissions
	var $UserLevelID = array(); // User Level ID array
	var $UserID = array(); // User ID array
	var $CurrentUserLevelID;
	var $CurrentUserLevel; // Permissions
	var $CurrentUserID;
	var $CurrentParentUserID;

	// Constructor
	function __construct() {

		// Init User Level
		if ($this->IsLoggedIn()) {
			$this->CurrentUserLevelID = $this->SessionUserLevelID();
			if (is_numeric($this->CurrentUserLevelID) && intval($this->CurrentUserLevelID) >= -2) {
				$this->UserLevelID[] = $this->CurrentUserLevelID;
			}
		} else { // Anonymous user
			$this->CurrentUserLevelID = -2;
			$this->UserLevelID[] = $this->CurrentUserLevelID;
		}
		$_SESSION[EW_SESSION_USER_LEVEL_LIST] = $this->UserLevelList();

		// Init User ID
		$this->CurrentUserID = $this->SessionUserID();
		$this->CurrentParentUserID = $this->SessionParentUserID();

		// Load user level
		$this->LoadUserLevel();
	}

	// Session User ID
	function SessionUserID() {
		return strval(@$_SESSION[EW_SESSION_USER_ID]);
	}

	function setSessionUserID($v) {
		$_SESSION[EW_SESSION_USER_ID] = trim(strval($v));
		$this->CurrentUserID = trim(strval($v));
	}

	// Session Parent User ID
	function SessionParentUserID() {
		return strval(@$_SESSION[EW_SESSION_PARENT_USER_ID]);
	}

	function setSessionParentUserID($v) {
		$_SESSION[EW_SESSION_PARENT_USER_ID] = trim(strval($v));
		$this->CurrentParentUserID = trim(strval($v));
	}

	// Session User Level ID
	function SessionUserLevelID() {
		return @$_SESSION[EW_SESSION_USER_LEVEL_ID];
	}

	function setSessionUserLevelID($v) {
		$_SESSION[EW_SESSION_USER_LEVEL_ID] = $v;
		$this->CurrentUserLevelID = $v;
		if (is_numeric($v) && $v >= -2)
			$this->UserLevelID = array($v);
	}

	// Session User Level value
	function SessionUserLevel() {
		return @$_SESSION[EW_SESSION_USER_LEVEL];
	}

	function setSessionUserLevel($v) {
		$_SESSION[EW_SESSION_USER_LEVEL] = $v;
		$this->CurrentUserLevel = $v;
	}

	// Current user name
	function getCurrentUserName() {
		return strval(@$_SESSION[EW_SESSION_USER_NAME]);
	}

	function setCurrentUserName($v) {
		$_SESSION[EW_SESSION_USER_NAME] = $v;
	}

	function CurrentUserName() {
		return $this->getCurrentUserName();
	}

	// Current User ID
	function CurrentUserID() {
		return $this->CurrentUserID;
	}

	// Current Parent User ID
	function CurrentParentUserID() {
		return $this->CurrentParentUserID;
	}

	// Current User Level ID
	function CurrentUserLevelID() {
		return $this->CurrentUserLevelID;
	}

	// Current User Level value
	function CurrentUserLevel() {
		return $this->CurrentUserLevel;
	}

	// Can add
	function CanAdd() {
		return (($this->CurrentUserLevel & EW_ALLOW_ADD) == EW_ALLOW_ADD);
	}

	function setCanAdd($b) {
		if ($b) {
			$this->CurrentUserLevel = ($this->CurrentUserLevel | EW_ALLOW_ADD);
		} else {
			$this->CurrentUserLevel = ($this->CurrentUserLevel & (~ EW_ALLOW_ADD));
		}
	}

	// Can delete
	function CanDelete() {
		return (($this->CurrentUserLevel & EW_ALLOW_DELETE) == EW_ALLOW_DELETE);
	}

	function setCanDelete($b) {
		if ($b) {
			$this->CurrentUserLevel = ($this->CurrentUserLevel | EW_ALLOW_DELETE);
		} else {
			$this->CurrentUserLevel = ($this->CurrentUserLevel & (~ EW_ALLOW_DELETE));
		}
	}

	// Can edit
	function CanEdit() {
		return (($this->CurrentUserLevel & EW_ALLOW_EDIT) == EW_ALLOW_EDIT);
	}

	function setCanEdit($b) {
		if ($b) {
			$this->CurrentUserLevel = ($this->CurrentUserLevel | EW_ALLOW_EDIT);
		} else {
			$this->CurrentUserLevel = ($this->CurrentUserLevel & (~ EW_ALLOW_EDIT));
		}
	}

	// Can view
	function CanView() {
		return (($this->CurrentUserLevel & EW_ALLOW_VIEW) == EW_ALLOW_VIEW);
	}

	function setCanView($b) {
		if ($b) {
			$this->CurrentUserLevel = ($this->CurrentUserLevel | EW_ALLOW_VIEW);
		} else {
			$this->CurrentUserLevel = ($this->CurrentUserLevel & (~ EW_ALLOW_VIEW));
		}
	}

	// Can list
	function CanList() {
		return (($this->CurrentUserLevel & EW_ALLOW_LIST) == EW_ALLOW_LIST);
	}

	function setCanList($b) {
		if ($b) {
			$this->CurrentUserLevel = ($this->CurrentUserLevel | EW_ALLOW_LIST);
		} else {
			$this->CurrentUserLevel = ($this->CurrentUserLevel & (~ EW_ALLOW_LIST));
		}
	}

	// Can report
	function CanReport() {
		return (($this->CurrentUserLevel & EW_ALLOW_REPORT) == EW_ALLOW_REPORT);
	}

	function setCanReport($b) {
		if ($b) {
			$this->CurrentUserLevel = ($this->CurrentUserLevel | EW_ALLOW_REPORT);
		} else {
			$this->CurrentUserLevel = ($this->CurrentUserLevel & (~ EW_ALLOW_REPORT));
		}
	}

	// Can search
	function CanSearch() {
		return (($this->CurrentUserLevel & EW_ALLOW_SEARCH) == EW_ALLOW_SEARCH);
	}

	function setCanSearch($b) {
		if ($b) {
			$this->CurrentUserLevel = ($this->CurrentUserLevel | EW_ALLOW_SEARCH);
		} else {
			$this->CurrentUserLevel = ($this->CurrentUserLevel & (~ EW_ALLOW_SEARCH));
		}
	}

	// Can admin
	function CanAdmin() {
		return (($this->CurrentUserLevel & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN);
	}

	function setCanAdmin($b) {
		if ($b) {
			$this->CurrentUserLevel = ($this->CurrentUserLevel | EW_ALLOW_ADMIN);
		} else {
			$this->CurrentUserLevel = ($this->CurrentUserLevel & (~ EW_ALLOW_ADMIN));
		}
	}

	// Last URL
	function LastUrl() {
		if (is_array(@$_COOKIE[EW_PROJECT_NAME]))
			return @$_COOKIE[EW_PROJECT_NAME]['LastUrl'];
		return "";
	}

	// Save last URL
	function SaveLastUrl() {
		$s = ew_ServerVar("SCRIPT_NAME");
		$q = ew_ServerVar("QUERY_STRING");
		if ($q <> "") $s .= "?" . $q;
		if ($this->LastUrl() == $s) $s = "";
		@setcookie(EW_PROJECT_NAME . '[LastUrl]', $s);
	}

	// Auto login
	function AutoLogin() {
		$autologin = FALSE;
		if (!$autologin && @$_COOKIE[EW_PROJECT_NAME]['AutoLogin'] == "autologin") {
			$usr = ew_Decrypt(@$_COOKIE[EW_PROJECT_NAME]['Username']);
			$pwd = ew_Decrypt(@$_COOKIE[EW_PROJECT_NAME]['Password']);
			$autologin = $this->ValidateUser($usr, $pwd, TRUE, FALSE);
		}
		if (!$autologin && EW_ALLOW_LOGIN_BY_URL && isset($_GET["username"])) {
			$usr = ew_RemoveXSS(ew_StripSlashes($_GET["username"]));
			$pwd = ew_RemoveXSS(ew_StripSlashes(@$_GET["password"]));
			$enc = !empty($_GET["encrypted"]);
			$autologin = $this->ValidateUser($usr, $pwd, TRUE, $enc);
		}
		if (!$autologin && EW_ALLOW_LOGIN_BY_SESSION && isset($_SESSION[EW_PROJECT_NAME . "_Username"])) {
			$usr = $_SESSION[EW_PROJECT_NAME . "_Username"];
			$pwd = @$_SESSION[EW_PROJECT_NAME . "_Password"];
			$enc = !empty($_SESSION[EW_PROJECT_NAME . "_Encrypted"]);
			$autologin = $this->ValidateUser($usr, $pwd, TRUE, $enc);
		}
		if ($autologin)
			ew_WriteAuditTrail("log", ew_StdCurrentDateTime(), ew_ScriptName(), $usr, $GLOBALS["Language"]->Phrase("AuditTrailAutoLogin"), ew_CurrentUserIP(), "", "", "", "");
		return $autologin;
	}

	// Login user
	function LoginUser($userName = NULL, $userID = NULL, $parentUserID = NULL, $userLevel = NULL) {
		$_SESSION[EW_SESSION_STATUS] = "login";
		if (!is_null($userName))
			$this->setCurrentUserName($userName);
		if (!is_null($userID))
			$this->setSessionUserID($userID);
		if (!is_null($parentUserID))
			$this->setSessionParentUserID($parentUserID);
		if (!is_null($userLevel)) {
			$this->setSessionUserLevelID(intval($userLevel));
			SetUpUserLevel();
		}
	}

	// Validate user
	function ValidateUser(&$usr, &$pwd, $autologin, $encrypted = FALSE) {
		global $Language;
		global $UserTable, $UserTableConn;
		$validateUser = FALSE;
		$customValidateUser = FALSE;

		// Call User Custom Validate event
		if (EW_USE_CUSTOM_LOGIN) {
			$customValidateUser = $this->User_CustomValidate($usr, $pwd);
			if ($customValidateUser) {

				//$_SESSION[EW_SESSION_STATUS] = "login"; // To be setup below
				$this->setCurrentUserName($usr); // Load user name
			}
		}

		// Check other users
		if (!$validateUser) {
			$sFilter = str_replace("%u", ew_AdjustSql($usr, EW_USER_TABLE_DBID), EW_USER_NAME_FILTER);

			// Set up filter (SQL WHERE clause) and get return SQL
			// SQL constructor in <UserTable> class, <UserTable>info.php

			$sSql = $UserTable->GetSQL($sFilter, "");
			if ($rs = $UserTableConn->Execute($sSql)) {
				if (!$rs->EOF) {
					$validateUser = $customValidateUser || ew_ComparePassword($rs->fields('password'), $pwd, $encrypted);
					if ($validateUser) {
						$_SESSION[EW_SESSION_STATUS] = "login";
						$_SESSION[EW_SESSION_SYS_ADMIN] = 0; // Non System Administrator
						$this->setCurrentUserName($rs->fields('username')); // Load user name
						$this->setSessionUserID($rs->fields('user_id')); // Load User ID
						if (is_null($rs->fields('userlevel'))) {
							$this->setSessionUserLevelID(0);
						} else {
							$this->setSessionUserLevelID(intval($rs->fields('userlevel'))); // Load User Level
						}
						$this->SetUpUserLevel();

						// Call User Validated event
						$row = $rs->fields;
						$validateUser = $this->User_Validated($row) !== FALSE; // For backward compatibility
					}
				} else { // User not found in user table
					if ($customValidateUser) { // Grant default permissions
						$this->setSessionUserID($usr); // User name as User ID
						$this->setSessionUserLevelID(-2); // Anonymous User Level
						$this->SetUpUserLevel();
						$row = NULL;
						$customValidateUser = $this->User_Validated($row) !== FALSE;
					}
				}
				$rs->Close();
			}
		}
		if ($customValidateUser)
			return $customValidateUser;
		if (!$validateUser && !IsPasswordExpired())
			$_SESSION[EW_SESSION_STATUS] = ""; // Clear login status
		return $validateUser;
	}

	// Load user level from config file
	function LoadUserLevelFromConfigFile(&$arUserLevel, &$arUserLevelPriv, &$arTable, $userpriv = FALSE) {
		global $EW_RELATED_PROJECT_ID;

		// User Level definitions
		array_splice($arUserLevel, 0);
		array_splice($arUserLevelPriv, 0);
		array_splice($arTable, 0);

		// Load user level from config files
		$doc = new cXMLDocument();
		$folder = ew_AppRoot() . EW_CONFIG_FILE_FOLDER;

		// Load user level settings from main config file
		$ProjectID = CurrentProjectID();
		$file = $folder . EW_PATH_DELIMITER . $ProjectID . ".xml";
		if (file_exists($file) && $doc->Load($file) && (($projnode = $doc->SelectSingleNode("//configuration/project")) != NULL)) {
			$EW_RELATED_PROJECT_ID = $doc->GetAttribute($projnode, "relatedid");
			$userlevel = $doc->GetAttribute($projnode, "userlevel");
			$usergroup = explode(";", $userlevel);
			foreach ($usergroup as $group) {
				@list($id, $name, $priv) = explode(",", $group, 3);

				// Remove quotes
				if (strlen($name) >= 2 && substr($name,0,1) == "\"" && substr($name,-1) == "\"")
					$name = substr($name,1,strlen($name)-2);
				$arUserLevel[] = array($id, $name);
			}

			// Load from main config file
			$this->LoadUserLevelFromXml($folder, $doc, $arUserLevelPriv, $arTable, $userpriv);

			// Load from related config file
			if ($EW_RELATED_PROJECT_ID <> "")
				$this->LoadUserLevelFromXml($folder, $EW_RELATED_PROJECT_ID . ".xml", $arUserLevelPriv, $arTable, $userpriv);
		}

		// Warn user if user level not setup
		if (count($arUserLevel) == 0) {
			die("Unable to load user level from config file: " . $file);
		}

		// Load user priv settings from all config files
		if ($dir_handle = @opendir($folder)) {
			while (FALSE !== ($file = readdir($dir_handle))) {
				if ($file == "." || $file == ".." || !is_file($folder . EW_PATH_DELIMITER . $file))
					continue;
				$pathinfo = pathinfo($file);
				if (isset($pathinfo["extension"]) && strtolower($pathinfo["extension"]) == "xml") {
					if ($file <> $ProjectID . ".xml" && $file <> $EW_RELATED_PROJECT_ID . ".xml")
						$this->LoadUserLevelFromXml($folder, $file, $arUserLevelPriv, $arTable, $userpriv);
				}
			}
		}
	}

	// Load user level from xml
	function LoadUserLevelFromXml($folder, $file, &$arUserLevelPriv, &$arTable, $userpriv) {
		global $EW_RELATED_PROJECT_ID, $EW_RELATED_LANGUAGE_FOLDER;
		if (is_string($file)) {
			$file = $folder . EW_PATH_DELIMITER . $file;
			$doc = new cXMLDocument();
			$doc->Load($file);
		} else {
			$doc = $file;
		}
		if ($doc instanceof cXMLDocument) {

			// Load project id
			$projid = "";
			$projfile = "";
			if (($projnode = $doc->SelectSingleNode("//configuration/project")) != NULL) {
				$projid = $doc->GetAttribute($projnode, "id");
				$projfile = $doc->GetAttribute($projnode, "file");
				if ($projid == $EW_RELATED_PROJECT_ID)
					$EW_RELATED_LANGUAGE_FOLDER = $doc->GetAttribute($projnode, "languagefolder") . EW_PATH_DELIMITER;
			}

			// Load user priv
			$tablelist = $doc->SelectNodes("//configuration/project/table");
			foreach ($tablelist as $table) {
				$tablevar = $doc->GetAttribute($table, "id");
				$tablename = $doc->GetAttribute($table, "name");
				$tablecaption = $doc->GetAttribute($table, "caption");
				$userlevel = $doc->GetAttribute($table, "userlevel");
				$priv = $doc->GetAttribute($table, "priv");
				if (!$userpriv || ($userpriv && $priv == "1")) {
					$usergroup = explode(";", $userlevel);
					foreach ($usergroup as $group) {
						@list($id, $name, $priv) = explode(",", $group, 3);
						$arUserLevelPriv[] = array($projid . $tablename, $id, $priv);
					}
					$arTable[] = array($tablename, $tablevar, $tablecaption, $priv, $projid, $projfile);
				}
			}
		}
	}

	// Static User Level security
	function SetUpUserLevel() {

		// Load user level from config file
		$arTable = array();
		$this->LoadUserLevelFromConfigFile($this->UserLevel, $this->UserLevelPriv, $arTable);

		// User Level loaded event
		$this->UserLevel_Loaded();

		// Save the User Level to Session variable
		$this->SaveUserLevel();
	}

	// Get all User Level settings from database
	function SetUpUserLevelEx() {
		return FALSE;
	}

	// Add user permission
	function AddUserPermission($UserLevelName, $TableName, $UserPermission) {

		// Get User Level ID from user name
		$UserLevelID = "";
		if (is_array($this->UserLevel)) {
			foreach ($this->UserLevel as $row) {
				list($levelid, $name) = $row;
				if (ew_SameText($UserLevelName, $name)) {
					$UserLevelID = $levelid;
					break;
				}
			}
		}
		if (is_array($this->UserLevelPriv) && $UserLevelID <> "") {
			$cnt = count($this->UserLevelPriv);
			for ($i = 0; $i < $cnt; $i++) {
				list($table, $levelid, $priv) = $this->UserLevelPriv[$i];
				if (ew_SameText($table, EW_PROJECT_ID . $TableName) && ew_SameStr($levelid, $UserLevelID)) {
					$this->UserLevelPriv[$i][2] = $priv | $UserPermission; // Add permission
					break;
				}
			}
		}
	}

	// Delete user permission
	function DeleteUserPermission($UserLevelName, $TableName, $UserPermission) {

		// Get User Level ID from user name
		$UserLevelID = "";
		if (is_array($this->UserLevel)) {
			foreach ($this->UserLevel as $row) {
				list($levelid, $name) = $row;
				if (ew_SameText($UserLevelName, $name)) {
					$UserLevelID = $levelid;
					break;
				}
			}
		}
		if (is_array($this->UserLevelPriv) && $UserLevelID <> "") {
			$cnt = count($this->UserLevelPriv);
			for ($i = 0; $i < $cnt; $i++) {
				list($table, $levelid, $priv) = $this->UserLevelPriv[$i];
				if (ew_SameText($table, EW_PROJECT_ID . $TableName) && ew_SameStr($levelid, $UserLevelID)) {
					$this->UserLevelPriv[$i][2] = $priv & (127 - $UserPermission); // Remove permission
					break;
				}
			}
		}
	}

	// Load current User Level
	function LoadCurrentUserLevel($Table) {

		// Load again if user level list changed
		if (@$_SESSION[EW_SESSION_USER_LEVEL_LIST_LOADED] <> "" && @$_SESSION[EW_SESSION_USER_LEVEL_LIST_LOADED] <> @$_SESSION[EW_SESSION_USER_LEVEL_LIST]) {
			$_SESSION[EW_SESSION_AR_USER_LEVEL_PRIV] = "";
		}
		$this->LoadUserLevel();
		$this->setSessionUserLevel($this->CurrentUserLevelPriv($Table));
	}

	// Get current user privilege
	function CurrentUserLevelPriv($TableName) {
		if ($this->IsLoggedIn()) {
			$Priv = 0;
			foreach ($this->UserLevelID as $UserLevelID)
				$Priv |= $this->GetUserLevelPrivEx($TableName, $UserLevelID);
			return $Priv;
		} else { // Anonymous
			return $this->GetUserLevelPrivEx($TableName, -2);
		}
	}

	// Get User Level ID by User Level name
	function GetUserLevelID($UserLevelName) {
		global $Language;
		if (ew_SameStr($UserLevelName, "Anonymous") || ew_SameStr($UserLevelName, $Language->Phrase("UserAnonymous"))) {
			return -2;
		} elseif (ew_SameStr($UserLevelName, "Administrator") || ew_SameStr($UserLevelName, $Language->Phrase("UserAdministrator"))) {
			return -1;
		} elseif (ew_SameStr($UserLevelName, "Default") || ew_SameStr($UserLevelName, $Language->Phrase("UserDefault"))) {
			return 0;
		} elseif ($UserLevelName <> "") {
			if (is_array($this->UserLevel)) {
				foreach ($this->UserLevel as $row) {
					list($levelid, $name) = $row;
					if (ew_SameStr($name, $UserLevelName) || ew_SameStr($Language->Phrase($name), $UserLevelName))
						return $levelid;
				}
			}
		}
		return -2;
	}

	// Add User Level by name
	function AddUserLevel($UserLevelName) {
		if (strval($UserLevelName) == "") return;
		$UserLevelID = $this->GetUserLevelID($UserLevelName);
		$this->AddUserLevelID($UserLevelID);
	}

	// Add User Level by ID
	function AddUserLevelID($UserLevelID) {
		if (!is_numeric($UserLevelID)) return;
		if ($UserLevelID < -1) return;
		if (!in_array($UserLevelID, $this->UserLevelID)) {
			$this->UserLevelID[] = $UserLevelID;
			$_SESSION[EW_SESSION_USER_LEVEL_LIST] = $this->UserLevelList(); // Update session variable
		}
	}

	// Delete User Level by name
	function DeleteUserLevel($UserLevelName) {
		if (strval($UserLevelName) == "") return;
		$UserLevelID = $this->GetUserLevelID($UserLevelName);
		$this->DeleteUserLevelID($UserLevelID);
	}

	// Delete User Level by ID
	function DeleteUserLevelID($UserLevelID) {
		if (!is_numeric($UserLevelID)) return;
		if ($UserLevelID < -1) return;
		$cnt = count($this->UserLevelID);
		for ($i = 0; $i < $cnt; $i++) {
			if ($this->UserLevelID[$i] == $UserLevelID) {
				unset($this->UserLevelID[$i]);
				$_SESSION[EW_SESSION_USER_LEVEL_LIST] = $this->UserLevelList(); // Update session variable
				break;
			}
		}
	}

	// User Level list
	function UserLevelList() {
		return implode(", ", $this->UserLevelID);
	}

	// User Level name list
	function UserLevelNameList() {
		$list = "";
		foreach ($this->UserLevelID as $UserLevelID) {
			if ($list <> "") $list .= ", ";
			$list .= ew_QuotedValue($this->GetUserLevelName($UserLevelID), EW_DATATYPE_STRING, EW_USER_LEVEL_DBID);
		}
		return $list;
	}

	// Get user privilege based on table name and User Level
	function GetUserLevelPrivEx($TableName, $UserLevelID) {
		if (strval($UserLevelID) == "-1") { // System Administrator
			if (defined("EW_USER_LEVEL_COMPAT")) {
				return 31; // Use old User Level values
			} else {
				return 127; // Use new User Level values (separate View/Search)
			}
		} elseif ($UserLevelID >= 0 || $UserLevelID == -2) {
			if (is_array($this->UserLevelPriv)) {
				foreach ($this->UserLevelPriv as $row) {
					list($table, $levelid, $priv) = $row;
					if (strtolower($table) == strtolower($TableName) && strval($levelid) == strval($UserLevelID)) {
						if (is_null($priv) || !is_numeric($priv)) return 0;
						return intval($priv);
					}
				}
			}
		}
		return 0;
	}

	// Get current User Level name
	function CurrentUserLevelName() {
		return $this->GetUserLevelName($this->CurrentUserLevelID());
	}

	// Get User Level name based on User Level
	function GetUserLevelName($UserLevelID, $Lang = TRUE) {
		global $Language;
		if (strval($UserLevelID) == "-2") {
			return ($Lang) ? $Language->Phrase("UserAnonymous") : "Anonymous";
		} elseif (strval($UserLevelID) == "-1") {
			return ($Lang) ? $Language->Phrase("UserAdministrator") : "Administrator";
		} elseif (strval($UserLevelID) == "0") {
			return ($Lang) ? $Language->Phrase("UserDefault") : "Default";
		} elseif ($UserLevelID > 0) {
			if (is_array($this->UserLevel)) {
				foreach ($this->UserLevel as $row) {
					list($levelid, $name) = $row;
					if (strval($levelid) == strval($UserLevelID)) {
						$UserLevelName = "";
						if ($Lang)
							$UserLevelName = $Language->Phrase($name);	
						return ($UserLevelName <> "") ? $UserLevelName : $name;
					}
				}
			}
		}
		return "";
	}

	// Display all the User Level settings (for debug only)
	function ShowUserLevelInfo() {
		echo "<pre>";
		print_r($this->UserLevel);
		print_r($this->UserLevelPriv);
		echo "</pre>";
		echo "<p>Current User Level ID = " . $this->CurrentUserLevelID() . "</p>";
		echo "<p>Current User Level ID List = " . $this->UserLevelList() . "</p>";
	}

	// Check privilege for List page (for menu items)
	function AllowList($TableName) {
		return ($this->CurrentUserLevelPriv($TableName) & EW_ALLOW_LIST);
	}

	// Check privilege for View page (for Allow-View / Detail-View)
	function AllowView($TableName) {
		return ($this->CurrentUserLevelPriv($TableName) & EW_ALLOW_VIEW);
	}

	// Check privilege for Add page (for Allow-Add / Detail-Add)
	function AllowAdd($TableName) {
		return ($this->CurrentUserLevelPriv($TableName) & EW_ALLOW_ADD);
	}

	// Check privilege for Edit page (for Detail-Edit)
	function AllowEdit($TableName) {
		return ($this->CurrentUserLevelPriv($TableName) & EW_ALLOW_EDIT);
	}

	// Check if user password expired
	function IsPasswordExpired() {
		return (@$_SESSION[EW_SESSION_STATUS] == "passwordexpired");
	}

	// Set session password expired
	function SetSessionPasswordExpired() {
		$_SESSION[EW_SESSION_STATUS] = "passwordexpired";
	}

	// Set login status
	function SetLoginStatus($status = "") {
		$_SESSION[EW_SESSION_STATUS] = $status;
	}

	// Check if user password reset
	function IsPasswordReset() {
		return (@$_SESSION[EW_SESSION_STATUS] == "passwordreset");
	}

	// Check if user is logging in (after changing password)
	function IsLoggingIn() {
		return (@$_SESSION[EW_SESSION_STATUS] == "loggingin");
	}

	// Check if user is logged in
	function IsLoggedIn() {
		return (@$_SESSION[EW_SESSION_STATUS] == "login");
	}

	// Check if user is system administrator
	function IsSysAdmin() {
		return (@$_SESSION[EW_SESSION_SYS_ADMIN] == 1);
	}

	// Check if user is administrator
	function IsAdmin() {
		$IsAdmin = $this->IsSysAdmin();
		if (!$IsAdmin)
			$IsAdmin = $this->CurrentUserLevelID == -1 || in_array(-1, $this->UserLevelID);
		if (!$IsAdmin)
    		$IsAdmin = $this->CurrentUserID == -1 || in_array(-1, $this->UserID);
		return $IsAdmin;
	}

	// Save User Level to Session
	function SaveUserLevel() {

		//$_SESSION[EW_SESSION_PROJECT_ID] = CurrentProjectID(); // Save project id
		$_SESSION[EW_SESSION_AR_USER_LEVEL] = $this->UserLevel;
		$_SESSION[EW_SESSION_AR_USER_LEVEL_PRIV] = $this->UserLevelPriv;
	}

	// Load User Level from Session
	function LoadUserLevel() {
		$ProjectID = CurrentProjectID();

		//if (!is_array(@$_SESSION[EW_SESSION_AR_USER_LEVEL]) || !is_array(@$_SESSION[EW_SESSION_AR_USER_LEVEL_PRIV]) || $ProjectID <> @$_SESSION[EW_SESSION_PROJECT_ID]) { // Reload if different project
		if (!is_array(@$_SESSION[EW_SESSION_AR_USER_LEVEL]) || !is_array(@$_SESSION[EW_SESSION_AR_USER_LEVEL_PRIV])) {
			$this->SetupUserLevel();
			$this->SaveUserLevel();
		} else {
			$this->UserLevel = $_SESSION[EW_SESSION_AR_USER_LEVEL];
			$this->UserLevelPriv = $_SESSION[EW_SESSION_AR_USER_LEVEL_PRIV];
		}
	}

	// Get current user info
	function CurrentUserInfo($fldname) {
		global $UserTableConn;
		$info = NULL;
		$info = $this->GetUserInfo($fldname, $this->CurrentUserID);
		return $info;
	}

	// Get user info
	function GetUserInfo($FieldName, $UserID) {
		global $UserTable, $UserTableConn;
		if (strval($UserID) <> "") {

			// Get SQL from GetSQL method in <UserTable> class, <UserTable>info.php
			$sFilter = str_replace("%u", ew_AdjustSql($UserID, EW_USER_TABLE_DBID), EW_USER_ID_FILTER);
			$sSql = $UserTable->GetSQL($sFilter, '');
			if (($RsUser = $UserTableConn->Execute($sSql)) && !$RsUser->EOF) {
				$info = $RsUser->fields($FieldName);
				$RsUser->Close();
				return $info;
			}
		}
		return NULL;
  }

	// Get User ID by user name
	function GetUserIDByUserName($UserName) {
		global $UserTable, $UserTableConn;
		if (strval($UserName) <> "") {
			$sFilter = str_replace("%u", ew_AdjustSql($UserName, EW_USER_TABLE_DBID), EW_USER_NAME_FILTER);
			$sSql = $UserTable->GetSQL($sFilter, '');
			if (($RsUser = $UserTableConn->Execute($sSql)) && !$RsUser->EOF) {
				$UserID = $RsUser->fields('user_id');
				$RsUser->Close();
				return $UserID;
			}
		}
		return "";
	}

	// Load User ID
	function LoadUserID() {
		global $UserTable, $UserTableConn;
		$this->UserID = array();
		if (strval($this->CurrentUserID) == "") {

			// Add codes to handle empty user id here
		} elseif ($this->CurrentUserID <> "-1") {

			// Get first level
			$this->AddUserID($this->CurrentUserID);
			$sFilter = $UserTable->UserIDFilter($this->CurrentUserID);
			$sSql = $UserTable->GetSQL($sFilter, '');
			if ($RsUser = $UserTableConn->Execute($sSql)) {
				while (!$RsUser->EOF) {
					$this->AddUserID($RsUser->fields('user_id'));
					$RsUser->MoveNext();
				}
				$RsUser->Close();
			}
		}
	}

	// Add user name
	function AddUserName($UserName) {
		$this->AddUserID($this->GetUserIDByUserName($UserName));
	}

	// Add User ID
	function AddUserID($userid) {
		if (strval($userid) == "") return;
		if (!is_numeric($userid)) return;
		if (!in_array(trim(strval($userid)), $this->UserID))
			$this->UserID[] = trim(strval($userid));
	}

	// Delete user name
	function DeleteUserName($UserName) {
		$this->DeleteUserID($this->GetUserIDByUserName($UserName));
	}

	// Delete User ID
	function DeleteUserID($userid) {
		if (strval($userid) == "") return;
		if (!is_numeric($userid)) return;
		$cnt = count($this->UserID);
		for ($i = 0; $i < $cnt; $i++) {
			if ($this->UserID[$i] == trim(strval($userid))) {
				unset($this->UserID[$i]);
				break;
			}
		}
	}

	// User ID list
	function UserIDList() {
		$ar = $this->UserID;
		$len = count($ar);
		for ($i = 0; $i < $len; $i++)
			$ar[$i] =  ew_QuotedValue($ar[$i], EW_DATATYPE_NUMBER, EW_USER_TABLE_DBID);
		return implode(", ", $ar);
	}

	// List of allowed User IDs for this user
	function IsValidUserID($userid) {
		return in_array(trim(strval($userid)), $this->UserID);
	}

	// UserID Loading event
	function UserID_Loading() {

		//echo "UserID Loading: " . $this->CurrentUserID() . "<br>";
	}

	// UserID Loaded event
	function UserID_Loaded() {

		//echo "UserID Loaded: " . $this->UserIDList() . "<br>";
	}

	// User Level Loaded event
	function UserLevel_Loaded() {

		//$this->AddUserPermission(<UserLevelName>, <TableName>, <UserPermission>);
		//$this->DeleteUserPermission(<UserLevelName>, <TableName>, <UserPermission>);

	}

	// Table Permission Loading event
	function TablePermission_Loading() {

		//echo "Table Permission Loading: " . $this->CurrentUserLevelID() . "<br>";
	}

	// Table Permission Loaded event
	function TablePermission_Loaded() {

		//echo "Table Permission Loaded: " . $this->CurrentUserLevel . "<br>";
	}

	// User Custom Validate event
	function User_CustomValidate(&$usr, &$pwd) {

		// Enter your custom code to validate user, return TRUE if valid.
		return FALSE;
	}

	// User Validated event
	function User_Validated(&$rs) {

		// Example:
		//$_SESSION['UserEmail'] = $rs['Email'];

	}

	// User PasswordExpired event
	function User_PasswordExpired(&$rs) {

		//echo "User_PasswordExpired";
	}
}
?>
<?php
/**
 * Common functions
 */

// Connection/Query error handler
function ew_ErrorFn($DbType, $ErrorType, $ErrorNo, $ErrorMsg, $Param1, $Param2, $Object) {
	if ($ErrorType == 'CONNECT') {
		if ($DbType == "ado_access" || $DbType == "ado_mssql") {
			$msg = "Failed to connect to database. Error: " . $ErrorMsg;
		} else {
			$msg = "Failed to connect to $Param2 at $Param1. Error: " . $ErrorMsg;
		}
	} elseif ($ErrorType == 'EXECUTE') {
		if (EW_DEBUG_ENABLED) {
			$msg = "Failed to execute SQL: $Param1. Error: " . $ErrorMsg;
		} else {
			$msg = "Failed to execute SQL. Error: " . $ErrorMsg;
		}
	}
	ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $msg);
}

// Write HTTP header
function ew_Header($cache, $charset = EW_CHARSET) {
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Always modified
	$export = @$_GET["export"];
	if ($cache || ew_IsHttps() && $export <> "" && $export <> "print") { // Allow cache
		header("Cache-Control: private, must-revalidate");
		header("Pragma: public");
	} else { // No cache
		header("Cache-Control: private, no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}
	header("X-UA-Compatible: IE=edge");
	if ($charset <> "")
		header("Content-Type: text/html; charset=" . $charset); // Charset
}

// Get content file extension
function ew_ContentExt($data) {
	$ct = ew_ContentType(substr($data, 0, 11));
	switch ($ct) {
	case "image/gif": return ".gif"; // Return gif
	case "image/jpeg": return ".jpg"; // Return jpg
	case "image/png": return ".png"; // Return png
	case "image/bmp": return ".bmp"; // Return bmp
	case "application/pdf": return ".pdf"; // Return pdf
	default: return ""; // Unknown extension
	}
}

// Get content type
function ew_ContentType($data, $fn = "") {
	global $EW_MIME_TYPES;

	// http://en.wikipedia.org/wiki/List_of_file_signatures
	if (substr($data, 0, 6) == "\x47\x49\x46\x38\x37\x61" || substr($data, 0, 6) == "\x47\x49\x46\x38\x39\x61") { // Check if gif
		return "image/gif";

	//} elseif (substr($data, 0, 4) == "\xFF\xD8\xFF\xE0" && substr($data, 6, 5) == "\x4A\x46\x49\x46\x00") { // Check if jpg
	} elseif (substr($data, 0, 4) == "\xFF\xD8\xFF\xE0") { // Check if jpg
		return "image/jpeg";
	} elseif (substr($data, 0, 8) == "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A") { // Check if png
		return "image/png";
	} elseif (substr($data, 0, 2) == "\x42\x4D") { // Check if bmp
		return "image/bmp";
	} elseif (substr($data, 0, 4) == "\x25\x50\x44\x46") { // Check if pdf
		return "application/pdf";
	} elseif ($fn <> "") { // Use file extension to get mime type
		$extension = strtolower(substr(strrchr($fn, "."), 1));
		$ct = @$EW_MIME_TYPES[$extension];
		if ($ct == "") {
			if (file_exists($fn) && function_exists("finfo_file")) {
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$ct = finfo_file($finfo, $fn);
				finfo_close($finfo);
			} elseif (function_exists("mime_content_type")) {
				$ct = mime_content_type($fn);
			}
		}
		return $ct;
	} else {
		return "images";
	}
}

// Get connection object
function &Conn($dbid = 0) {
	$db = &Db($dbid);
	if ($db && is_null($db["conn"]))
		ew_ConnectDb($db);
	if ($db)
		$conn = &$db["conn"];
	else
		$conn = FALSE;
	return $conn;
}

// Get database object
function &Db($dbid = 0) {
	global $EW_CONN;
	if (ew_EmptyStr($dbid))
		$dbid = 0;
	if (array_key_exists($dbid, $EW_CONN))
		$db = &$EW_CONN[$dbid];
	else
		$db = FALSE;
	return $db;
}

// Get connection type
function ew_GetConnectionType($dbid = 0) {
	$db = Db($dbid);
	if ($db) {
		return $db["type"];
	} elseif (ew_SameText($dbid, "MYSQL")) {
		return "MYSQL";
	} elseif (ew_SameText($dbid, "POSTGRESQL")) {
		return "POSTGRESQL";
	} elseif (ew_SameText($dbid, "ORACLE")) {
		return "ORACLE";
	} elseif (ew_SameText($dbid, "ACCESS")) {
		return "ACCESS";
	} elseif (ew_SameText($dbid, "MSSQL")) {
		return "MSSQL";
	}
	return FALSE;
}

// Connect to database
function &ew_Connect($dbid = 0) {
	return Conn($dbid);
}

// Connect to database
function ew_ConnectDb(&$info) {
	global $EW_DATE_FORMAT;
	$GLOBALS["ADODB_FETCH_MODE"] = ADODB_FETCH_BOTH;
	$GLOBALS["ADODB_COUNTRECS"] = FALSE;

	// Database connecting event
	Database_Connecting($info);
	$dbid = @$info["id"];
	$dbtype = @$info["type"];
	if (($dbtype == "MSSQL" || $dbtype == "ACCESS") && !class_exists("COM"))
		die("<strong>PHP COM extension required for database type '" . $dbtype . "' is not installed on this server.</strong> Note that Windows server is required for database type '" . $dbtype . "' and as of PHP 5.3.15/5.4.5, the COM extension requires php_com_dotnet.dll to be enabled in php.ini. See <a href='http://php.net/manual/en/com.installation.php'>http://php.net/manual/en/com.installation.php</a> for details.");
	if ($dbtype == "MYSQL") {
		if (EW_USE_ADODB) {
			if (EW_USE_MYSQLI)
				$conn = ADONewConnection('mysqli');
			else
				$conn = ADONewConnection('mysqlt');
		} else {
			$conn = new mysqlt_driver_ADOConnection();
		}
	} elseif ($dbtype == "POSTGRESQL") {
		$conn = ADONewConnection('postgres7');
	} elseif ($dbtype == "MSSQL") {
		$conn = ADONewConnection('ado_mssql');
	} elseif ($dbtype == "ACCESS") {
		$conn = ADONewConnection('ado_access');
	} elseif ($dbtype == "ORACLE") {
		$conn = ADONewConnection('oci805');
		$conn->NLS_DATE_FORMAT = 'RRRR-MM-DD HH24:MI:SS';
	}
	$conn->info = $info;
	$conn->debug = EW_DEBUG_ENABLED;
	$conn->debug_echo = FALSE;
	if ($dbtype == "MYSQL" || $dbtype == "POSTGRESQL" || $dbtype == "ORACLE")
		$conn->port = intval(@$info["port"]);
	if ($dbtype == "ORACLE")
		$conn->charSet = @$info["charset"];
	$conn->raiseErrorFn = (EW_DEBUG_ENABLED) ? $GLOBALS["EW_ERROR_FN"] : "";
	if ($dbtype == "MYSQL" || $dbtype == "POSTGRESQL" || $dbtype == "ORACLE") {
		if ($dbtype == "MYSQL")
			$conn->Connect(@$info["host"], @$info["user"], @$info["pass"], @$info["db"], @$info["new"]);
		else
			$conn->Connect(@$info["host"], @$info["user"], @$info["pass"], @$info["db"]);
		if ($dbtype == "MYSQL" && EW_MYSQL_CHARSET <> "")
			$conn->Execute("SET NAMES '" . EW_MYSQL_CHARSET . "'");
		if ($dbtype == "ORACLE") {

			// Set schema
			$conn->Execute("ALTER SESSION SET CURRENT_SCHEMA = ". ew_QuotedName(@$info["schema"], $dbid));
			$conn->Execute("ALTER SESSION SET NLS_TIMESTAMP_FORMAT = 'yyyy-mm-dd hh24:mi:ss'");
			$conn->Execute("ALTER SESSION SET NLS_TIMESTAMP_TZ_FORMAT = 'yyyy-mm-dd hh24:mi:ss'");
		}
		if ($dbtype == "POSTGRESQL") {

			// Set schema
			if (@$info["schema"] <> "public")
				$conn->Execute("SET search_path TO " . ew_QuotedName($info["schema"], $dbid));
		}
	} elseif ($dbtype == "ACCESS" || $dbtype == "MSSQL") {
		if (EW_CODEPAGE > 0)
			$conn->charPage = EW_CODEPAGE;
		if ($dbtype == "ACCESS") {
			$relpath = @$info["relpath"];
			$dbname = @$info["dbname"];
			$provider = @$info["provider"];
			$password = @$info["password"];
			if ($relpath == "")
				$datasource = realpath($GLOBALS["EW_RELATIVE_PATH"] . $dbname);
			elseif (substr($relpath, 0, 1) == ".") // Relative path starting with "." or ".." (relative to app root)
				$datasource = ew_ServerMapPath($relpath . $dbname);
			elseif (substr($relpath, 0, 2) == "\\\\" || strpos($relpath, ":") !== FALSE) // Physical path
				$datasource = $relpath . $dbname;
			else // Relative to app root
				$datasource = ew_AppRoot() . str_replace("/", "\\", $relpath) . $dbname;
			if ($password <> "")
				$connstr = $provider . ";Data Source=" . $datasource . ";Jet OLEDB:Database Password=" . $password . ";";
			elseif (strtoupper(substr($dbname, -6)) == ".ACCDB") // AccDb
				$connstr = $provider . ";Data Source=" . $datasource . ";Persist Security Info=False;";
			else
				$connstr = $provider . ";Data Source=" . $datasource . ";";
		} else {
			$connstr = @$info["connectionstring"];
		}
		$conn->Connect($connstr, FALSE, FALSE);

		// Set date format
		if ($dbtype == "MSSQL" && $EW_DATE_FORMAT <> "")
			$conn->Execute("SET DATEFORMAT ymd");
	}

	//$conn->raiseErrorFn = '';
	// Database connected event

	Database_Connected($conn);
	$info["conn"] = &$conn;
}

// Close database connections
function ew_CloseConn() {
	global $conn, $EW_CONN;
	foreach ($EW_CONN as $dbid => &$db) {
		if ($db["conn"]) $db["conn"]->Close();
		$db["conn"] = NULL;
	}
	$conn = NULL;
}

// Database Connecting event
function Database_Connecting(&$info) {

	// Example:
	//var_dump($info);
	//if ($info["id"] == "DB" && ew_CurrentUserIP() == "127.0.0.1") { // Testing on local PC
	//	$info["host"] = "locahost";
	//	$info["user"] = "root";
	//	$info["pass"] = "";
	//}

	if (ew_CurrentUserIP () == "127.0.0.1"  || ew_CurrentUserIP () == ":: 1"  || ew_CurrentHost () == "localhost" ) { // testing on local PC
		$info["host"] = "localhost";
		$info["user"] = "root"; // sesuaikan dengan username database di komputer localhost
		$info["pass"] = "admin"; // sesuaikan dengan password database di komputer localhost
		$info["db"] = "db_siap"; // sesuaikan dengan nama database di komputer localhost
	} elseif (ew_CurrentHost () == "siap.nma-indonesia.com") { // setting koneksi database untuk komputer server
		$info["host"] = "mysql.idhostinger.com";  // sesuaikan dengan ip address atau hostname komputer server
		$info["user"] = "u945388674_siap"; // sesuaikan dengan username database di komputer server
		$info["pass"] = "M457r1P 81"; // sesuaikan deengan password database di komputer server
		$info["db"] = "u945388674_siap"; // sesuaikan dengan nama database di komputer server
	}
}

// Database Connected event
function Database_Connected(&$conn) {

	// Example:
	// if ($conn->info["id"] == "DB")
	//   $conn->Execute("Your SQL");

}

// Check if allow add/delete row
function ew_AllowAddDeleteRow() {
	return TRUE;
}

// Check if HTTP POST
function ew_IsHttpPost() {
	$ct = ew_ServerVar("CONTENT_TYPE");
	if (empty($ct)) $ct = ew_ServerVar("HTTP_CONTENT_TYPE");
	return strpos($ct, "application/x-www-form-urlencoded") !== FALSE;
}

// Cast date/time field for LIKE
function ew_CastDateFieldForLike($fld, $namedformat, $dbid = 0) {
	global $EW_DATE_SEPARATOR, $EW_TIME_SEPARATOR, $EW_DATE_FORMAT, $EW_DATE_FORMAT_ID;
	$dbtype = ew_GetConnectionType($dbid);
	$isDateTime = FALSE; // Date/Time
	if ($namedformat == 0 || $namedformat == 1 || $namedformat == 2 || $namedformat == 8) {
		$isDateTime = ($namedformat == 1 || $namedformat == 8);
		$namedformat = $EW_DATE_FORMAT_ID;
	}
	$shortYear = ($namedformat >= 12 && $namedformat <= 17);
	$isDateTime = $isDateTime || in_array($namedformat, array(9, 10, 11, 15, 16, 17));
	$dateFormat = "";
	switch ($namedformat) {
		case 3:
			if ($dbtype == "MYSQL") {
				$dateFormat = "%h" . $EW_TIME_SEPARATOR . "%i" . $EW_TIME_SEPARATOR . "%s %p";
			} else if ($dbtype == "ACCESS") {
				$dateFormat = "hh" . $EW_TIME_SEPARATOR . "nn" . $EW_TIME_SEPARATOR . "ss AM/PM";
			} else if ($dbtype == "MSSQL") {
				$dateFormat = "REPLACE(LTRIM(RIGHT(CONVERT(VARCHAR(19), %s, 0), 7)), ':', '" . $EW_TIME_SEPARATOR . "')"; // Use hh:miAM (or PM) only or SQL too lengthy
			} else if ($dbtype == "ORACLE") {
				$dateFormat = "HH" . $EW_TIME_SEPARATOR . "MI" . $EW_TIME_SEPARATOR . "SS AM";
			}
			break;
		case 4:
			if ($dbtype == "MYSQL") {
				$dateFormat = "%H" . $EW_TIME_SEPARATOR . "%i" . $EW_TIME_SEPARATOR . "%s";
			} else if ($dbtype == "ACCESS") {
				$dateFormat = "hh" . $EW_TIME_SEPARATOR . "nn" . $EW_TIME_SEPARATOR . "ss";
			} else if ($dbtype == "MSSQL") {
				$dateFormat = "REPLACE(CONVERT(VARCHAR(8), %s, 108), ':', '" . $EW_TIME_SEPARATOR . "')";
			} else if ($dbtype == "ORACLE") {
				$dateFormat = "HH24" . $EW_TIME_SEPARATOR . "MI" . $EW_TIME_SEPARATOR . "SS";
			}
			break;
		case 5: case 9: case 12: case 15:
			if ($dbtype == "MYSQL") {
				$dateFormat = ($shortYear ? "%y" : "%Y") . $EW_DATE_SEPARATOR . "%m" . $EW_DATE_SEPARATOR . "%d";
				if ($isDateTime) $dateFormat .= " %H" . $EW_TIME_SEPARATOR . "%i" . $EW_TIME_SEPARATOR . "%s";
			} else if ($dbtype == "ACCESS") {
				$dateFormat = ($shortYear ? "yy" : "yyyy") . $EW_DATE_SEPARATOR . "mm" . $EW_DATE_SEPARATOR . "dd";
				if ($isDateTime) $dateFormat .= " hh" . $EW_TIME_SEPARATOR . "nn" . $EW_TIME_SEPARATOR . "ss";
			} else if ($dbtype == "MSSQL") {
				$dateFormat = "REPLACE(" . ($shortYear ? "CONVERT(VARCHAR(8), %s, 2)" : "CONVERT(VARCHAR(10), %s, 102)") . ", '.', '" . $EW_DATE_SEPARATOR . "')";
				if ($isDateTime) $dateFormat = "(" . $dateFormat . " + ' ' + REPLACE(CONVERT(VARCHAR(8), %s, 108), ':', '" . $EW_TIME_SEPARATOR . "'))";
			} else if ($dbtype == "ORACLE") {
				$dateFormat = ($shortYear ? "YY" : "YYYY") . $EW_DATE_SEPARATOR . "MM" . $EW_DATE_SEPARATOR . "DD";
				if ($isDateTime) $dateFormat .= " HH24" . $EW_TIME_SEPARATOR . "MI" . $EW_TIME_SEPARATOR . "SS";
			}
			break;
		case 6: case 10: case 13: case 16:
			if ($dbtype == "MYSQL") {
				$dateFormat = "%m" . $EW_DATE_SEPARATOR . "%d" . $EW_DATE_SEPARATOR . ($shortYear ? "%y" : "%Y");
				if ($isDateTime) $dateFormat .= " %H" . $EW_TIME_SEPARATOR . "%i" . $EW_TIME_SEPARATOR . "%s";
			} else if ($dbtype == "ACCESS") {
				$dateFormat = "mm" . $EW_DATE_SEPARATOR . "dd" . $EW_DATE_SEPARATOR . ($shortYear ? "yy" : "yyyy");
				if ($isDateTime) $dateFormat .= " hh" . $EW_TIME_SEPARATOR . "nn" . $EW_TIME_SEPARATOR . "ss";
			} else if ($dbtype == "MSSQL") {
				$dateFormat = "REPLACE(" . ($shortYear ? "CONVERT(VARCHAR(8), %s, 1)" : "CONVERT(VARCHAR(10), %s, 101)") . ", '/', '" . $EW_DATE_SEPARATOR . "')";
				if ($isDateTime) $dateFormat = "(" . $dateFormat . " + ' ' + REPLACE(CONVERT(VARCHAR(8), %s, 108), ':', '" . $EW_TIME_SEPARATOR . "'))";
			} else if ($dbtype == "ORACLE") {
				$dateFormat = "MM" . $EW_DATE_SEPARATOR . "DD" . $EW_DATE_SEPARATOR . ($shortYear ? "YY" : "YYYY");
				if ($isDateTime) $dateFormat .= " HH24" . $EW_TIME_SEPARATOR . "MI" . $EW_TIME_SEPARATOR . "SS";
			}
			break;
		case 7: case 11: case 14: case 17:
			if ($dbtype == "MYSQL") {
				$dateFormat = "%d" . $EW_DATE_SEPARATOR . "%m" . $EW_DATE_SEPARATOR . ($shortYear ? "%y" : "%Y");
				if ($isDateTime) $dateFormat .= " %H" . $EW_TIME_SEPARATOR . "%i" . $EW_TIME_SEPARATOR . "%s";
			} else if ($dbtype == "ACCESS") {
				$dateFormat = "dd" . $EW_DATE_SEPARATOR . "mm" . $EW_DATE_SEPARATOR . ($shortYear ? "yy" : "yyyy");
				if ($isDateTime) $dateFormat .= " hh" . $EW_TIME_SEPARATOR . "nn" . $EW_TIME_SEPARATOR . "ss";
			} else if ($dbtype == "MSSQL") {
				$dateFormat = "REPLACE(" . ($shortYear ? "CONVERT(VARCHAR(8), %s, 3)" : "CONVERT(VARCHAR(10), %s, 103)") . ", '/', '" . $EW_DATE_SEPARATOR . "')";
				if ($isDateTime) $dateFormat = "(" . $dateFormat . " + ' ' + REPLACE(CONVERT(VARCHAR(8), %s, 108), ':', '" . $EW_TIME_SEPARATOR . "'))";
			} else if ($dbtype == "ORACLE") {
				$dateFormat = "DD" . $EW_DATE_SEPARATOR . "MM" . $EW_DATE_SEPARATOR . ($shortYear ? "YY" : "YYYY");
				if ($isDateTime) $dateFormat .= " HH24" . $EW_TIME_SEPARATOR . "MI" . $EW_TIME_SEPARATOR . "SS";
			}
			break;
	}
	if ($dateFormat) {
		if ($dbtype == "MYSQL") {
			return "DATE_FORMAT(" . $fld . ", '" . $dateFormat . "')";
		} else if ($dbtype == "ACCESS") {
			return "FORMAT(" . $fld . ", '" . $dateFormat . "')";
		} else if ($dbtype == "MSSQL") {
			return str_replace("%s", $fld, $dateFormat);
		} else if ($dbtype == "ORACLE") {
			return "TO_CHAR(" . $fld . ", '" . $dateFormat . "')";
		}
	}
	return $fld;
}

// Append like operator
function ew_Like($pat, $dbid = 0) {
	$dbtype = ew_GetConnectionType($dbid);
	if ($dbtype == "POSTGRESQL") {
		return ((EW_USE_ILIKE_FOR_POSTGRESQL) ? " ILIKE " : " LIKE ") . $pat;
	} elseif ($dbtype == "MYSQL") {
		if (EW_LIKE_COLLATION_FOR_MYSQL <> "") {
			return " LIKE " . $pat . " COLLATE " . EW_LIKE_COLLATION_FOR_MYSQL;
		} else {
			return " LIKE " . $pat;
		}
	} elseif ($dbtype == "MSSQL") {
		if (EW_LIKE_COLLATION_FOR_MSSQL <> "") {
			return  " COLLATE " . EW_LIKE_COLLATION_FOR_MSSQL . " LIKE " . $pat;
		} else {
			return " LIKE " . $pat;
		}
	} else {
		return " LIKE " . $pat;
	}
}

// Return multi-value search SQL
function ew_GetMultiSearchSql(&$Fld, $FldOpr, $FldVal, $dbid) {
	if ($FldOpr == "IS NULL" || $FldOpr == "IS NOT NULL") {
		return $Fld->FldExpression . " " . $FldOpr;
	} else {
		if ($FldOpr == "LIKE")
			$sWrk = "";
		else
			$sWrk = $Fld->FldExpression . ew_SearchString($FldOpr, $FldVal, EW_DATATYPE_STRING, $dbid);
		$arVal = explode(",", $FldVal);
		$dbtype = ew_GetConnectionType($dbid);
		foreach ($arVal as $sVal) {
			$sVal = trim($sVal);
			if ($sVal == EW_NULL_VALUE) {
				$sSql = $Fld->FldExpression . " IS NULL";
			} elseif ($sVal == EW_NOT_NULL_VALUE) {
				$sSql = $Fld->FldExpression . " IS NOT NULL";
			} else {
				if ($FldOpr == "LIKE") {
					if ($dbtype == "MYSQL") {
						$sSql = "FIND_IN_SET('" . ew_AdjustSql($sVal, $dbid) . "', " . $Fld->FldExpression . ")";
					} else {
						if (count($arVal) == 1 || EW_SEARCH_MULTI_VALUE_OPTION == 3) {
							$sSql = $Fld->FldExpression . " = '" . ew_AdjustSql($sVal, $dbid) . "' OR " . ew_GetMultiSearchSqlPart($Fld, $sVal, $dbid);
						} else {
							$sSql = ew_GetMultiSearchSqlPart($Fld, $sVal, $dbid);
						}
					}
				} else {
					$sSql = $Fld->FldExpression . ew_SearchString($FldOpr, $sVal, EW_DATATYPE_STRING, $dbid);
				}
			}
			if ($sWrk <> "") {
				if (EW_SEARCH_MULTI_VALUE_OPTION == 2) {
					$sWrk .= " AND ";
				} elseif (EW_SEARCH_MULTI_VALUE_OPTION == 3) {
					$sWrk .= " OR ";
				}
			}
			$sWrk .= "($sSql)";
		}
		return $sWrk;
	}
}

// Get multi search SQL part
function ew_GetMultiSearchSqlPart(&$Fld, $FldVal, $dbid) {
	return $Fld->FldExpression . ew_Like("'" . ew_AdjustSql($FldVal, $dbid) . ",%'", $dbid) . " OR " .
		$Fld->FldExpression . ew_Like("'%," . ew_AdjustSql($FldVal, $dbid) . ",%'", $dbid) . " OR " .
		$Fld->FldExpression . ew_Like("'%," . ew_AdjustSql($FldVal, $dbid) . "'", $dbid);
}

// Check if float format
function ew_IsFloatFormat($FldType) {
	return ($FldType == 4 || $FldType == 5 || $FldType == 131 || $FldType == 6);
}

// Check if is numeric
function ew_IsNumeric($Value) {
	$Value = ew_StrToFloat($Value);
	return is_numeric($Value);
}

// Get search SQL
function ew_GetSearchSql(&$Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2, $dbid) {
	$sSql = "";
	$virtual = ($Fld->FldIsVirtual && $Fld->FldVirtualSearch);
	$sFldExpression = ($virtual) ? $Fld->FldVirtualExpression : $Fld->FldExpression;
	$FldDataType = $Fld->FldDataType;
	if (ew_IsFloatFormat($Fld->FldType)) {
		$FldVal = ew_StrToFloat($FldVal);
		$FldVal2 = ew_StrToFloat($FldVal2);
	}
	if ($virtual)
		$FldDataType = EW_DATATYPE_STRING;
	if ($FldDataType == EW_DATATYPE_NUMBER) { // Fix wrong operator
		if ($FldOpr == "LIKE" || $FldOpr == "STARTS WITH" || $FldOpr == "ENDS WITH") {
			$FldOpr = "=";
		} elseif ($FldOpr == "NOT LIKE") {
			$FldOpr = "<>";
		}
		if ($FldOpr2 == "LIKE" || $FldOpr2 == "STARTS WITH" || $FldOpr2 == "ENDS WITH") {
			$FldOpr2 = "=";
		} elseif ($FldOpr2 == "NOT LIKE") {
			$FldOpr2 = "<>";
		}
	}
	if ($FldOpr == "BETWEEN") {
		$IsValidValue = ($FldDataType <> EW_DATATYPE_NUMBER) ||
			($FldDataType == EW_DATATYPE_NUMBER && is_numeric($FldVal) && is_numeric($FldVal2));
		if ($FldVal <> "" && $FldVal2 <> "" && $IsValidValue)
			$sSql = $sFldExpression . " BETWEEN " . ew_QuotedValue($FldVal, $FldDataType, $dbid) .
				" AND " . ew_QuotedValue($FldVal2, $FldDataType, $dbid);
	} else {

		// Handle first value
		if ($FldVal == EW_NULL_VALUE || $FldOpr == "IS NULL") {
			$sSql = $Fld->FldExpression . " IS NULL";
		} elseif ($FldVal == EW_NOT_NULL_VALUE || $FldOpr == "IS NOT NULL") {
			$sSql = $Fld->FldExpression . " IS NOT NULL";
		} else {
			$IsValidValue = ($FldDataType <> EW_DATATYPE_NUMBER) ||
				($FldDataType == EW_DATATYPE_NUMBER && is_numeric($FldVal));
			if ($FldVal <> "" && $IsValidValue && ew_IsValidOpr($FldOpr, $FldDataType)) {
				$sSql = $sFldExpression . ew_SearchString($FldOpr, $FldVal, $FldDataType, $dbid);
				if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN && $FldVal == $Fld->FalseValue && $FldOpr == "=")
					$sSql = "(" . $sSql . " OR " . $sFldExpression . " IS NULL)";
			}
		}

		// Handle second value
		$sSql2 = "";
		if ($FldVal2 == EW_NULL_VALUE || $FldOpr2 == "IS NULL") {
			$sSql2 = $Fld->FldExpression . " IS NULL";
		} elseif ($FldVal2 == EW_NOT_NULL_VALUE || $FldOpr2 == "IS NOT NULL") {
			$sSql2 = $Fld->FldExpression . " IS NOT NULL";
		} else {
			$IsValidValue = ($FldDataType <> EW_DATATYPE_NUMBER) ||
				($FldDataType == EW_DATATYPE_NUMBER && is_numeric($FldVal2));
			if ($FldVal2 <> "" && $IsValidValue && ew_IsValidOpr($FldOpr2, $FldDataType)) {
				$sSql2 = $sFldExpression . ew_SearchString($FldOpr2, $FldVal2, $FldDataType, $dbid);
				if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN && $FldVal2 == $Fld->FalseValue && $FldOpr2 == "=")
					$sSql2 = "(" . $sSql2 . " OR " . $sFldExpression . " IS NULL)";
			}
		}

		// Combine SQL
		if ($sSql2 <> "") {
			if ($sSql <> "")
				$sSql = "(" . $sSql . " " . (($FldCond == "OR") ? "OR" : "AND") . " " . $sSql2 . ")";
			else
				$sSql = $sSql2;
		}
	}
	return $sSql;
}

// Return search string
function ew_SearchString($FldOpr, $FldVal, $FldType, $dbid) {
	if (strval($FldVal) == EW_NULL_VALUE || $FldOpr == "IS NULL") {
		return " IS NULL";
	} elseif (strval($FldVal) == EW_NOT_NULL_VALUE || $FldOpr == "IS NOT NULL") {
		return " IS NOT NULL";
	} elseif ($FldOpr == "LIKE") {
		return ew_Like(ew_QuotedValue("%$FldVal%", $FldType, $dbid), $dbid);
	} elseif ($FldOpr == "NOT LIKE") {
		return " NOT " . ew_Like(ew_QuotedValue("%$FldVal%", $FldType, $dbid), $dbid);
	} elseif ($FldOpr == "STARTS WITH") {
		return ew_Like(ew_QuotedValue("$FldVal%", $FldType, $dbid), $dbid);
	} elseif ($FldOpr == "ENDS WITH") {
		return ew_Like(ew_QuotedValue("%$FldVal", $FldType, $dbid), $dbid);
	} else {
		if ($FldType == EW_DATATYPE_NUMBER && !is_numeric($FldVal)) // Invalid field value
 			return " = -1 AND 1 = 0"; // Always false
		else
			return " " . $FldOpr . " " . ew_QuotedValue($FldVal, $FldType, $dbid);
	}
}

// Check if valid operator
function ew_IsValidOpr($Opr, $FldType) {
	$Valid = ($Opr == "=" || $Opr == "<" || $Opr == "<=" ||
		$Opr == ">" || $Opr == ">=" || $Opr == "<>");
	if ($FldType == EW_DATATYPE_STRING || $FldType == EW_DATATYPE_MEMO || $FldType == EW_DATATYPE_XML)
		$Valid = ($Valid || $Opr == "LIKE" || $Opr == "NOT LIKE" ||	$Opr == "STARTS WITH" || $Opr == "ENDS WITH");
	return $Valid;
}

// Quote table/field name based on dbid
function ew_QuotedName($Name, $DbId = 0) {
	global $EW_CONN;
	$db = @$EW_CONN[$DbId];
	if ($db) {
		$qs = $db["qs"];
		$qe = $db["qe"];
		$Name = str_replace($qe, $qe . $qe, $Name);
		return $qs . $Name . $qe;
	} else { // Use default quotes
		$Name = str_replace(EW_DB_QUOTE_END, EW_DB_QUOTE_END . EW_DB_QUOTE_END, $Name);
		return EW_DB_QUOTE_START . $Name . EW_DB_QUOTE_END;
	}
}

// Quote field value based on dbid
function ew_QuotedValue($Value, $FldType, $DbId = 0) {
	if (is_null($Value))
		return "NULL";
	$dbtype = ew_GetConnectionType($DbId);
	switch ($FldType) {
		case EW_DATATYPE_STRING:
		case EW_DATATYPE_MEMO:
			if (EW_REMOVE_XSS)
				$Value = ew_RemoveXSS($Value);
			if ($dbtype == "MSSQL")
				return "N'" . ew_AdjustSql($Value, $DbId) . "'";
			else
				return "'" . ew_AdjustSql($Value, $DbId) . "'";
		case EW_DATATYPE_TIME:
			if (EW_REMOVE_XSS)
				$Value = ew_RemoveXSS($Value);
			return "'" . ew_AdjustSql($Value, $DbId) . "'";
		case EW_DATATYPE_XML:
			return "'" . ew_AdjustSql($Value, $DbId) . "'";
		case EW_DATATYPE_BLOB:
			if ($dbtype == "MYSQL") {
				return "'" . addslashes($Value) . "'";
			} elseif ($dbtype == "POSTGRESQL") {
				return "'" . Conn($DbId)->BlobEncode($Value) . "'";
			} else {
				return "0x" . bin2hex($Value);
			}
		case EW_DATATYPE_DATE:
			if ($dbtype == "ACCESS")
				return "#" . ew_AdjustSql($Value, $DbId) . "#";
			else
				return "'" . ew_AdjustSql($Value, $DbId) . "'";
		case EW_DATATYPE_GUID:
			if ($dbtype == "ACCESS") {
				if (strlen($Value) == 38) {
					return "{guid " . $Value . "}";
				} elseif (strlen($Value) == 36) {
					return "{guid {" . $Value . "}}";
				}
			} else {
				return "'" . $Value . "'";
			}
		case EW_DATATYPE_BOOLEAN:
			if ($dbtype == "MYSQL" || $dbtype == "POSTGRESQL")
				return "'" . $Value . "'"; // 'Y'|'N' or 'y'|'n' or '1'|'0' or 't'|'f'
			else
				return $Value;
		case EW_DATATYPE_NUMBER:
			if (ew_IsNumeric($Value))
				return $Value;
			else
				return "NULL"; // Treat as null
		default:
			return $Value;
	}
}

// Convert different data type value
function ew_Conv($v, $t) {
	switch ($t) {
	case 2:
	case 3:
	case 16:
	case 17:
	case 18:
	case 19: // If adSmallInt/adInteger/adTinyInt/adUnsignedTinyInt/adUnsignedSmallInt
		return (is_null($v)) ? NULL : intval($v);
	case 4:
	Case 5:
	case 6:
	case 131:
	case 139: // If adSingle/adDouble/adCurrency/adNumeric/adVarNumeric
		return (is_null($v)) ? NULL : (float)$v;
	default:
		return (is_null($v)) ? NULL : $v;
	}
}

// Convert string to float
function ew_StrToFloat($v) {
	global $EW_THOUSANDS_SEP, $EW_DECIMAL_POINT;
	$v = str_replace(" ", "", $v);
	$v = str_replace(array($EW_THOUSANDS_SEP, $EW_DECIMAL_POINT), array("", "."), $v);
	return $v;
}

// Convert string to int
function ew_StrToInt($v) {
	global $EW_DECIMAL_POINT;
	$v = ew_StrToFloat($v);
	$ar = explode($EW_DECIMAL_POINT, $v);
	return $ar[0];
}

// Concat string
function ew_Concat($str1, $str2, $sep) {
	$str1 = trim($str1);
	$str2 = trim($str2);
	if ($str1 <> "" && $sep <> "" && substr($str1, -1 * strlen($sep)) <> $sep)
		$str1 .= $sep;
	return $str1 . $str2;
}

// Contains a substring (case-sensitive)
function ew_ContainsStr($haystack, $needle, $offset = 0) {
	return strpos($haystack, $needle, $offset) !== FALSE;
}

// Contains a substring (case-insensitive)
function ew_ContainsText($haystack, $needle, $offset = 0) {
	return stripos($haystack, $needle, $offset) !== FALSE;
}

// Starts with a substring (case-sensitive)
function ew_StartsStr($needle, $haystack) {
	return strpos($haystack, $needle) === 0;
}

// Starts with a substring (case-insensitive)
function ew_StartsText($needle, $haystack) {
	return stripos($haystack, $needle) === 0;
}

// Ends with a substring (case-sensitive)
function ew_EndsStr($needle, $haystack) {
	return strrpos($haystack, $needle) === strlen($haystack) - strlen($needle);
}

// Ends with a substring (case-insensitive)
function ew_EndsText($needle, $haystack) {
	return strripos($haystack, $needle) === strlen($haystack) - strlen($needle);
}

// Same trimmed strings (case-sensitive)
function ew_SameStr($str1, $str2) {
	return strcmp(trim($str1), trim($str2)) === 0;
}

// Same trimmed strings (case-insensitive)
function ew_SameText($str1, $str2) {
	return strcasecmp(trim($str1), trim($str2)) === 0;
}

// Write message to debug file
function ew_Trace($msg) {
	$filename = "debug.txt";
	if (!$handle = fopen($filename, 'a')) exit;
	if (is_writable($filename)) fwrite($handle, $msg . "\n");
	fclose($handle);
}

// Compare values with special handling for null values
function ew_CompareValue($v1, $v2) {
	if (is_null($v1) && is_null($v2)) {
		return TRUE;
	} elseif (is_null($v1) || is_null($v2)) {
		return FALSE;

//	} elseif (is_float($v1) || is_float($v2)) {
//		return (float)$v1 == (float)$v2;

	} else {
		return ($v1 == $v2);
	}
}

// Check if boolean value is TRUE
function ew_ConvertToBool($value) {
	return ($value === TRUE || strval($value) == "1" ||
		strtolower(strval($value)) == "y" || strtolower(strval($value)) == "t");
}

// Strip slashes
function ew_StripSlashes($value) {
	if (!get_magic_quotes_gpc()) return $value;
	if (is_array($value)) { 
		return array_map('ew_StripSlashes', $value);
	} else {
		return stripslashes($value);
	}
}

// Add message
function ew_AddMessage(&$msg, $msgtoadd, $sep = "<br>") {
	if (strval($msgtoadd) <> "") {
		if (strval($msg) <> "")
			$msg .= $sep;
		$msg .= $msgtoadd;
	}
}

// Add filter
function ew_AddFilter(&$filter, $newfilter) {
	if (trim($newfilter) == "") return;
	if (trim($filter) <> "") {
		$filter = "(" . $filter . ") AND (" . $newfilter . ")";
	} else {
		$filter = $newfilter;
	}
}

// Adjust SQL based on dbid
function ew_AdjustSql($val, $dbid = 0) {
	$dbtype = ew_GetConnectionType($dbid);
	if ($dbtype == "MYSQL") {
		$val = addslashes(trim($val));
	} else {
		$val = str_replace("'", "''", trim($val)); // Adjust for single quote
	}
	return $val;
}

// Build SELECT SQL based on different sql part
function ew_BuildSelectSql($sSelect, $sWhere, $sGroupBy, $sHaving, $sOrderBy, $sFilter, $sSort) {
	$sDbWhere = $sWhere;
	ew_AddFilter($sDbWhere, $sFilter);
	$sDbOrderBy = $sOrderBy;
	if ($sSort <> "") $sDbOrderBy = $sSort;
	$sSql = $sSelect;
	if ($sDbWhere <> "") $sSql .= " WHERE " . $sDbWhere;
	if ($sGroupBy <> "") $sSql .= " GROUP BY " . $sGroupBy;
	if ($sHaving <> "") $sSql .= " HAVING " . $sHaving;
	if ($sDbOrderBy <> "") $sSql .= " ORDER BY " . $sDbOrderBy;
	return $sSql;
}

// Executes the query, and returns the row(s) as JSON, first row only by default
function ew_ExecuteJson($SQL, $FirstOnly = TRUE, $c = NULL) {
	if (is_null($c) && is_object($FirstOnly) && method_exists($FirstOnly, "Execute")) // ew_ExecuteJson($SQL, $c)
		$c = $FirstOnly;
	$rs = ew_LoadRecordset($SQL, $c);
	if ($rs && !$rs->EOF && $rs->FieldCount() > 0) {
		$res = ($FirstOnly) ? $rs->fields : $rs->GetRows();
		$rs->Close();
		return json_encode($res);
	}
	return "false";
}

// Executes the query, and returns the row(s) as JSON array (no keys)
function ew_ExecuteJsonArray($SQL, $c = NULL) {
	$rs = ew_LoadRecordset($SQL, $c);
	if ($rs && !$rs->EOF && $rs->FieldCount() > 0) {
		$res = $rs->GetRows();
		$rs->Close();
		return ew_ArrayToJson($res);
	}
	return "false";
}

// Write audit trail
function ew_WriteAuditTrail($pfx, $dt, $script, $usr, $action, $table, $field, $keyvalue, $oldvalue, $newvalue) {
	if ($table === EW_AUDIT_TRAIL_TABLE_NAME)
		return;
	$usrwrk = $usr;
	if ($usrwrk == "") $usrwrk = "-1"; // Assume Administrator if no user
	if (EW_AUDIT_TRAIL_TO_DATABASE)
		$rsnew = array(EW_AUDIT_TRAIL_FIELD_NAME_DATETIME => $dt, EW_AUDIT_TRAIL_FIELD_NAME_SCRIPT => $script, EW_AUDIT_TRAIL_FIELD_NAME_USER => $usrwrk, EW_AUDIT_TRAIL_FIELD_NAME_ACTION => $action,
			EW_AUDIT_TRAIL_FIELD_NAME_TABLE => $table, EW_AUDIT_TRAIL_FIELD_NAME_FIELD => $field, EW_AUDIT_TRAIL_FIELD_NAME_KEYVALUE => $keyvalue, EW_AUDIT_TRAIL_FIELD_NAME_OLDVALUE => $oldvalue, EW_AUDIT_TRAIL_FIELD_NAME_NEWVALUE => $newvalue);
	else
		$rsnew = array("datetime" => $dt, "script" => $script, "user" => $usrwrk, "action" => $action,
			"table" => $table, "field" => $field, "keyvalue" => $keyvalue, "oldvalue" => $oldvalue, "newvalue" => $newvalue);

	// Call AuditTrail Inserting event
	$bWriteAuditTrail = AuditTrail_Inserting($rsnew);
	if ($bWriteAuditTrail) {
		if (EW_AUDIT_TRAIL_TO_DATABASE) {
			$tblcls = "c" . EW_AUDIT_TRAIL_TABLE_VAR;
			$tbl = new $tblcls;
			if ($tbl->Row_Inserting(NULL, $rsnew)) {
				if ($tbl->Insert($rsnew))
					$tbl->Row_Inserted(NULL, $rsnew);
			}
		} else {
			$sTab = "\t";
			$sHeader = "date/time" . $sTab . "script" . $sTab .	"user" . $sTab .
				"action" . $sTab . "table" . $sTab . "field" . $sTab .
				"key value" . $sTab . "old value" . $sTab . "new value";
			$sMsg = $rsnew["datetime"] . $sTab . $rsnew["script"] . $sTab . $rsnew["user"] . $sTab . 
					$rsnew["action"] . $sTab . $rsnew["table"] . $sTab . $rsnew["field"] . $sTab .
					$rsnew["keyvalue"] . $sTab . $rsnew["oldvalue"] . $sTab . $rsnew["newvalue"];
			$sFolder = EW_AUDIT_TRAIL_PATH;
			$sFn = $pfx . "_" . date("Ymd") . ".txt";
			$filename = ew_UploadPathEx(TRUE, $sFolder) . $sFn;
			if (file_exists($filename)) {
				$fileHandler = fopen($filename, "a+b");
			} else {
				$fileHandler = fopen($filename, "a+b");
				fwrite($fileHandler,$sHeader."\r\n");
			}
			fwrite($fileHandler, $sMsg."\r\n");
			fclose($fileHandler);
		}
	}
}

// AuditTrail Inserting event
function AuditTrail_Inserting(&$rsnew) {

	//var_dump($rsnew);
	return TRUE;
}

// Unformat date time based on format type
function ew_UnFormatDateTime($dt, $namedformat) {
	global $EW_DATE_SEPARATOR, $EW_TIME_SEPARATOR, $EW_DATE_FORMAT, $EW_DATE_FORMAT_ID;
	if (preg_match('/^([0-9]{4})-([0][1-9]|[1][0-2])-([0][1-9]|[1|2][0-9]|[3][0|1])( (0[0-9]|1[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9]))?$/', $dt))
		return $dt;
	$dt = trim($dt);
	while (strpos($dt, "  ") !== FALSE)
		$dt = str_replace("  ", " ", $dt);
	$arDateTime = explode(" ", $dt);
	if (count($arDateTime) == 0)
		return $dt;
	if ($namedformat == 0 || $namedformat == 1 || $namedformat == 2 || $namedformat == 8)
		$namedformat = $EW_DATE_FORMAT_ID;
	$arDatePt = explode($EW_DATE_SEPARATOR, $arDateTime[0]);
	if (count($arDatePt) == 3) {
		switch ($namedformat) {
			case 5:
			case 9: //yyyymmdd
				if (ew_CheckDate($arDateTime[0])) {
					list($year, $month, $day) = $arDatePt;
					break;
				} else {
					return $dt;
				}
			case 6:
			case 10: //mmddyyyy
				if (ew_CheckUSDate($arDateTime[0])) {
					list($month, $day, $year) = $arDatePt;
					break;
				} else {
					return $dt;
				}
			case 7:
			case 11: //ddmmyyyy
				if (ew_CheckEuroDate($arDateTime[0])) {
					list($day, $month, $year) = $arDatePt;
					break;
				} else {
					return $dt;
				}
			case 12:
			case 15: //yymmdd
				if (ew_CheckShortDate($arDateTime[0])) {
					list($year, $month, $day) = $arDatePt;
					$year = ew_UnformatYear($year);
					break;
				} else {
					return $dt;
				}
			case 13:
			case 16: //mmddyy
				if (ew_CheckShortUSDate($arDateTime[0])) {
					list($month, $day, $year) = $arDatePt;
					$year = ew_UnformatYear($year);
					break;
				} else {
					return $dt;
				}
			case 14:
			case 17: //ddmmyy
				if (ew_CheckShortEuroDate($arDateTime[0])) {
					list($day, $month, $year) = $arDatePt;
					$year = ew_UnformatYear($year);
					break;
				} else {
					return $dt;
				}
			default:
				return $dt;
		}
		return $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-" .
			str_pad($day, 2, "0", STR_PAD_LEFT) .
			((count($arDateTime) > 1) ? " " . str_replace($EW_TIME_SEPARATOR, ":", $arDateTime[1]) : "");
	} else {
		if ($namedformat == 3 || $namedformat == 4) {
			$dt = str_replace($EW_TIME_SEPARATOR, ":", $dt);
		}
		return $dt;
	}
}

// Format a timestamp, datetime, date or time field
// $namedformat:
// 0 - Default date format
// 1 - Long Date (with time)
// 2 - Short Date (without time)
// 3 - Long Time (hh:mm:ss AM/PM)
// 4 - Short Time (hh:mm:ss)
// 5 - Short Date (yyyy/mm/dd)
// 6 - Short Date (mm/dd/yyyy)
// 7 - Short Date (dd/mm/yyyy)
// 8 - Short Date (Default) + Short Time (if not 00:00:00)
// 9 - Short Date (yyyy/mm/dd) + Short Time (hh:mm:ss)
// 10 - Short Date (mm/dd/yyyy) + Short Time (hh:mm:ss)
// 11 - Short Date (dd/mm/yyyy) + Short Time (hh:mm:ss)
// 12 - Short Date - 2 digit year (yy/mm/dd)
// 13 - Short Date - 2 digit year (mm/dd/yy)
// 14 - Short Date - 2 digit year (dd/mm/yy)
// 15 - Short Date (yy/mm/dd) + Short Time (hh:mm:ss)
// 16 - Short Date (mm/dd/yyyy) + Short Time (hh:mm:ss)
// 17 - Short Date (dd/mm/yyyy) + Short Time (hh:mm:ss)
function ew_FormatDateTime($ts, $namedformat) {
	global $Language, $EW_DATE_SEPARATOR, $EW_TIME_SEPARATOR, $EW_DATE_FORMAT, $EW_DATE_FORMAT_ID;
	if ($namedformat == 0)
		$namedformat = $EW_DATE_FORMAT_ID;
	if (is_numeric($ts)) // Timestamp
	{
		switch (strlen($ts)) {
			case 14:
				$patt = '/(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/';
				break;
			case 12:
				$patt = '/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/';
				break;
			case 10:
				$patt = '/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/';
				break;
			case 8:
				$patt = '/(\d{4})(\d{2})(\d{2})/';
				break;
			case 6:
				$patt = '/(\d{2})(\d{2})(\d{2})/';
				break;
			case 4:
				$patt = '/(\d{2})(\d{2})/';
				break;
			case 2:
				$patt = '/(\d{2})/';
				break;
			default:
				return $ts;
		}
		if ((isset($patt))&&(preg_match($patt, $ts, $matches)))
		{
			$year = $matches[1];
			$month = @$matches[2];
			$day = @$matches[3];
			$hour = @$matches[4];
			$min = @$matches[5];
			$sec = @$matches[6];
		}
		if (($namedformat==0)&&(strlen($ts)<10)) $namedformat = 2;
	}
	elseif (is_string($ts))
	{
		if (preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', $ts, $matches)) // Datetime
		{
			$year = $matches[1];
			$month = $matches[2];
			$day = $matches[3];
			$hour = $matches[4];
			$min = $matches[5];
			$sec = $matches[6];
		}
		elseif (preg_match('/(\d{4})-(\d{2})-(\d{2})/', $ts, $matches)) // Date
		{
			$year = $matches[1];
			$month = $matches[2];
			$day = $matches[3];
			if ($namedformat==0) $namedformat = 2;
		}
		elseif (preg_match('/(^|\s)(\d{2}):(\d{2}):(\d{2})/', $ts, $matches)) // Time
		{
			$hour = $matches[2];
			$min = $matches[3];
			$sec = $matches[4];
			if (($namedformat==0)||($namedformat==1)) $namedformat = 3;
			if ($namedformat==2) $namedformat = 4;
		}
		else
		{
			return $ts;
		}
	}
	else
	{
		return $ts;
	}
	if (!isset($year)) $year = 0; // Dummy value for times
	if (!isset($month)) $month = 1;
	if (!isset($day)) $day = 1;
	if (!isset($hour)) $hour = 0;
	if (!isset($min)) $min = 0;
	if (!isset($sec)) $sec = 0;
	$uts = @mktime($hour, $min, $sec, $month, $day, $year);
	if ($uts < 0 || $uts == FALSE || // Failed to convert
		(intval($year) == 0 && intval($month) == 0 && intval($day) == 0)) {
		$year = substr_replace("0000", $year, -1 * strlen($year));
		$month = substr_replace("00", $month, -1 * strlen($month));
		$day = substr_replace("00", $day, -1 * strlen($day));
		$hour = substr_replace("00", $hour, -1 * strlen($hour));
		$min = substr_replace("00", $min, -1 * strlen($min));
		$sec = substr_replace("00", $sec, -1 * strlen($sec));
		if (ew_ContainsStr($EW_DATE_FORMAT, "yyyy"))
			$DefDateFormat = str_replace("yyyy", $year, $EW_DATE_FORMAT);
		elseif (ew_ContainsStr($EW_DATE_FORMAT, "yy"))
			$DefDateFormat = str_replace("yy", substr(strval($year), -2), $EW_DATE_FORMAT);
		$DefDateFormat = str_replace("mm", $month, $DefDateFormat);
		$DefDateFormat = str_replace("dd", $day, $DefDateFormat);
		switch ($namedformat) {

			//case 0: // Default
			case 1:
				return $DefDateFormat . " " . $hour . $EW_TIME_SEPARATOR . $min . $EW_TIME_SEPARATOR . $sec;
				break;

			//case 2: // Default
			case 3:
				if (intval($hour) == 0) {
					if ($min == 0 && $sec == 0)
						return "12 " . $Language->Phrase("Midnight");
					else
						return "12" . $EW_TIME_SEPARATOR . $min . $EW_TIME_SEPARATOR . $sec . " " . $Language->Phrase("AM");
				} elseif (intval($hour) > 0 && intval($hour) < 12) {
					return $hour . $EW_TIME_SEPARATOR . $min . $EW_TIME_SEPARATOR . $sec . " " . $Language->Phrase("AM");
				} elseif (intval($hour) == 12) {
					if ($min == 0 && $sec == 0)
						return "12 " . $Language->Phrase("Noon");
					else
						return $hour . $EW_TIME_SEPARATOR . $min . $EW_TIME_SEPARATOR . $sec . " " . $Language->Phrase("PM");
				} elseif (intval($hour) > 12 && intval($hour) <= 23) {
					return (intval($hour)-12) . $EW_TIME_SEPARATOR . $min . $EW_TIME_SEPARATOR . $sec . " " . $Language->Phrase("PM");
				} else {
					return $hour . $EW_TIME_SEPARATOR . $min . $EW_TIME_SEPARATOR . $sec;
				}
				break;
			case 4:
				return $hour . $EW_TIME_SEPARATOR . $min . $EW_TIME_SEPARATOR . $sec;
				break;
			case 5:
				return $year . $EW_DATE_SEPARATOR . $month . $EW_DATE_SEPARATOR . $day;
				break;
			case 6:
				return $month . $EW_DATE_SEPARATOR . $day . $EW_DATE_SEPARATOR . $year;
				break;
			case 7:
				return $day . $EW_DATE_SEPARATOR . $month . $EW_DATE_SEPARATOR . $year;
				break;
			case 8:
				return $DefDateFormat . (($hour == 0 && $min == 0 && $sec == 0) ? "" : " " . $hour . $EW_TIME_SEPARATOR . $min . $EW_TIME_SEPARATOR . $sec);
				break;
			case 9:
				return $year . $EW_DATE_SEPARATOR . $month . $EW_DATE_SEPARATOR . $day . " " . $hour . $EW_TIME_SEPARATOR . $min . $EW_TIME_SEPARATOR . $sec;
				break;
			case 10:
				return $month . $EW_DATE_SEPARATOR . $day . $EW_DATE_SEPARATOR . $year . " " . $hour . $EW_TIME_SEPARATOR . $min . $EW_TIME_SEPARATOR . $sec;
				break;
			case 11:
				return $day . $EW_DATE_SEPARATOR . $month . $EW_DATE_SEPARATOR . $year . " " . $hour . $EW_TIME_SEPARATOR . $min . $EW_TIME_SEPARATOR . $sec;
				break;
			case 12:
				return substr($year,-2) . $EW_DATE_SEPARATOR . $month . $EW_DATE_SEPARATOR . $day;
				break;
			case 13:
				return $month . $EW_DATE_SEPARATOR . $day . $EW_DATE_SEPARATOR . substr($year,-2);
				break;
			case 14:
				return $day . $EW_DATE_SEPARATOR . $month . $EW_DATE_SEPARATOR . substr($year,-2);
				break;
			default:
				return $DefDateFormat;
				break;
		}
	} else {
		if (ew_ContainsStr($EW_DATE_FORMAT, "yyyy"))
			$DefDateFormat = str_replace("yyyy", $year, $EW_DATE_FORMAT);
		elseif (ew_ContainsStr($EW_DATE_FORMAT, "yy"))
			$DefDateFormat = str_replace("yy", substr(strval($year), -2), $EW_DATE_FORMAT);
		$DefDateFormat = str_replace("mm", $month, $DefDateFormat);
		$DefDateFormat = str_replace("dd", $day, $DefDateFormat);
		switch ($namedformat) {

			// case 0: // Default
			case 1:
				return strftime($DefDateFormat . " %H" . $EW_TIME_SEPARATOR . "%M" . $EW_TIME_SEPARATOR . "%S", $uts);
				break;

			// case 2: // Default
			case 3:
				if (intval($hour) == 0) {
					if ($min == 0 && $sec == 0)
						return "12 " . $Language->Phrase("Midnight");
					else
						return strftime("%I" . $EW_TIME_SEPARATOR . "%M" . $EW_TIME_SEPARATOR . "%S", $uts) . " " . $Language->Phrase("AM");
				} elseif (intval($hour) > 0 && intval($hour) < 12) {
					return strftime("%I" . $EW_TIME_SEPARATOR . "%M" . $EW_TIME_SEPARATOR . "%S", $uts) . " " . $Language->Phrase("AM");
				} elseif (intval($hour) == 12) {
					if ($min == 0 && $sec == 0)
						return "12 " . $Language->Phrase("Noon");
					else
						return strftime("%I" . $EW_TIME_SEPARATOR . "%M" . $EW_TIME_SEPARATOR . "%S", $uts) . " " . $Language->Phrase("PM");
				} elseif (intval($hour) > 12 && intval($hour) <= 23) {
					return strftime("%I" . $EW_TIME_SEPARATOR . "%M" . $EW_TIME_SEPARATOR . "%S", $uts) . " " . $Language->Phrase("PM");
				} else {
					return strftime("%I" . $EW_TIME_SEPARATOR . "%M" . $EW_TIME_SEPARATOR . "%S %p", $uts);
				}
				break;
			case 4:
				return strftime("%H" . $EW_TIME_SEPARATOR . "%M" . $EW_TIME_SEPARATOR . "%S", $uts);
				break;
			case 5:
				return strftime("%Y" . $EW_DATE_SEPARATOR . "%m" . $EW_DATE_SEPARATOR . "%d", $uts);
				break;
			case 6:
				return strftime("%m" . $EW_DATE_SEPARATOR . "%d" . $EW_DATE_SEPARATOR . "%Y", $uts);
				break;
			case 7:
				return strftime("%d" . $EW_DATE_SEPARATOR . "%m" . $EW_DATE_SEPARATOR . "%Y", $uts);
				break;
			case 8:
				return strftime($DefDateFormat . (($hour == 0 && $min == 0 && $sec == 0) ? "" : " %H" . $EW_TIME_SEPARATOR . "%M" . $EW_TIME_SEPARATOR . "%S"), $uts);
				break;
			case 9:
				return strftime("%Y" . $EW_DATE_SEPARATOR . "%m" . $EW_DATE_SEPARATOR . "%d %H" . $EW_TIME_SEPARATOR . "%M" . $EW_TIME_SEPARATOR . "%S", $uts);
				break;
			case 10:
				return strftime("%m" . $EW_DATE_SEPARATOR . "%d" . $EW_DATE_SEPARATOR . "%Y %H" . $EW_TIME_SEPARATOR . "%M" . $EW_TIME_SEPARATOR . "%S", $uts);
				break;
			case 11:
				return strftime("%d" . $EW_DATE_SEPARATOR . "%m" . $EW_DATE_SEPARATOR . "%Y %H" . $EW_TIME_SEPARATOR . "%M" . $EW_TIME_SEPARATOR . "%S", $uts);
				break;
			case 12:
				return strftime("%y" . $EW_DATE_SEPARATOR . "%m" . $EW_DATE_SEPARATOR . "%d", $uts);
				break;
			case 13:
				return strftime("%m" . $EW_DATE_SEPARATOR . "%d" . $EW_DATE_SEPARATOR . "%y", $uts);
				break;
			case 14:
				return strftime("%d" . $EW_DATE_SEPARATOR . "%m" . $EW_DATE_SEPARATOR . "%y", $uts);
				break;
			case 15:
				return strftime("%y" . $EW_DATE_SEPARATOR . "%m" . $EW_DATE_SEPARATOR . "%d %H" . $EW_TIME_SEPARATOR . "%M" . $EW_TIME_SEPARATOR . "%S", $uts);
				break;
			case 16:
				return strftime("%m" . $EW_DATE_SEPARATOR . "%d" . $EW_DATE_SEPARATOR . "%y %H" . $EW_TIME_SEPARATOR . "%M" . $EW_TIME_SEPARATOR . "%S", $uts);
				break;
			case 17:
				return strftime("%d" . $EW_DATE_SEPARATOR . "%m" . $EW_DATE_SEPARATOR . "%y %H" . $EW_TIME_SEPARATOR . "%M" . $EW_TIME_SEPARATOR . "%S", $uts);
				break;
			default:
				return strftime($DefDateFormat, $uts);
				break;
		}
	}
}

// Format currency
// Arguments: Expression ,NumDigitsAfterDecimal [,IncludeLeadingDigit [,UseParensForNegativeNumbers [,GroupDigits]]]
// NumDigitsAfterDecimal is the numeric value indicating how many places to the right of the decimal are displayed
// -1 Use Default
// -2 Retain all values after decimal place
// The IncludeLeadingDigit, UseParensForNegativeNumbers, and GroupDigits arguments have the following settings:
// -1 True
// 0 False
// -2 Use Default
function ew_FormatCurrency($amount, $numDigitsAfterDecimal, $includeLeadingDigit = -2, $useParensForNegativeNumbers = -2, $groupDigits = -2) {
	extract($GLOBALS["EW_LOCALE"]);

	// Check $numDigitsAfterDecimal
	if ($numDigitsAfterDecimal == -2) { // Use all values after decimal point
		$stramt = strval($amount);
		if (strrpos($stramt, '.') >= 0)
			$frac_digits = strlen($stramt) - strrpos($stramt, '.') - 1;
		else
			$frac_digits = 0;
	} elseif ($numDigitsAfterDecimal > -1) {
		$frac_digits = $numDigitsAfterDecimal;
	}

	// Check $useParensForNegativeNumbers
	if ($useParensForNegativeNumbers == -1) {
		$n_sign_posn = 0;
		if ($p_sign_posn == 0) {
			$p_sign_posn = 3;
		}
	} elseif ($useParensForNegativeNumbers == 0) {
		if ($n_sign_posn == 0)
			$n_sign_posn = 3;
	}

	// Check $groupDigits
	if ($groupDigits == -1) {
	} elseif ($groupDigits == 0) {
		$mon_thousands_sep = "";
	}

	// Start by formatting the unsigned number
	$number = number_format(abs($amount),
							$frac_digits,
							$mon_decimal_point,
							$mon_thousands_sep);

	// Check $includeLeadingDigit
	if ($includeLeadingDigit == 0) {
		if (substr($number, 0, 2) == "0.")
			$number = substr($number, 1, strlen($number)-1);
	}
	if ($amount < 0) {
		$sign = $negative_sign;

		// "extracts" the boolean value as an integer
		$n_cs_precedes  = intval($n_cs_precedes  == true);
		$n_sep_by_space = intval($n_sep_by_space == true);
		$key = $n_cs_precedes . $n_sep_by_space . $n_sign_posn;
	} else {
		$sign = $positive_sign;
		$p_cs_precedes  = intval($p_cs_precedes  == true);
		$p_sep_by_space = intval($p_sep_by_space == true);
		$key = $p_cs_precedes . $p_sep_by_space . $p_sign_posn;
	}
	$formats = array(

	  // Currency symbol is after amount
	  // No space between amount and sign

	  '000' => '(%s' . $currency_symbol . ')',
	  '001' => $sign . '%s ' . $currency_symbol,
	  '002' => '%s' . $currency_symbol . $sign,
	  '003' => '%s' . $sign . $currency_symbol,
	  '004' => '%s' . $sign . $currency_symbol,

	  // One space between amount and sign
	  '010' => '(%s ' . $currency_symbol . ')',
	  '011' => $sign . '%s ' . $currency_symbol,
	  '012' => '%s ' . $currency_symbol . $sign,
	  '013' => '%s ' . $sign . $currency_symbol,
	  '014' => '%s ' . $sign . $currency_symbol,

	  // Currency symbol is before amount
	  // No space between amount and sign

	  '100' => '(' . $currency_symbol . '%s)',
	  '101' => $sign . $currency_symbol . '%s',
	  '102' => $currency_symbol . '%s' . $sign,
	  '103' => $sign . $currency_symbol . '%s',
	  '104' => $currency_symbol . $sign . '%s',

	  // One space between amount and sign
	  '110' => '(' . $currency_symbol . ' %s)',
	  '111' => $sign . $currency_symbol . ' %s',
	  '112' => $currency_symbol . ' %s' . $sign,
	  '113' => $sign . $currency_symbol . ' %s',
	  '114' => $currency_symbol . ' ' . $sign . '%s');

	// Lookup the key in the above array
	return sprintf($formats[$key], $number);
}

// Format number
// Arguments: Expression ,NumDigitsAfterDecimal [,IncludeLeadingDigit [,UseParensForNegativeNumbers [,GroupDigits]]]
// NumDigitsAfterDecimal is the numeric value indicating how many places to the right of the decimal are displayed
// -1 Use Default
// -2 Retain all values after decimal place
// The IncludeLeadingDigit, UseParensForNegativeNumbers, and GroupDigits arguments have the following settings:
// -1 True
// 0 False
// -2 Use Default
function ew_FormatNumber($amount, $numDigitsAfterDecimal, $includeLeadingDigit = -2, $useParensForNegativeNumbers = -2, $groupDigits = -2) {
	extract($GLOBALS["EW_LOCALE"]);

	// Check $numDigitsAfterDecimal
	if ($numDigitsAfterDecimal == -2) { // Use all values after decimal point
		$stramt = strval($amount);
		if (strrpos($stramt, '.') === FALSE)
			$frac_digits = 0;
		else
			$frac_digits = strlen($stramt) - strrpos($stramt, '.') - 1;
	} elseif ($numDigitsAfterDecimal > -1) {
		$frac_digits = $numDigitsAfterDecimal;
	}

	// Check $useParensForNegativeNumbers
	if ($useParensForNegativeNumbers == -1) {
		$n_sign_posn = 0;
		if ($p_sign_posn == 0) {
			$p_sign_posn = 3;
		}
	} elseif ($useParensForNegativeNumbers == 0) {
		if ($n_sign_posn == 0)
			$n_sign_posn = 3;
	}

	// Check $groupDigits
	if ($groupDigits == -1) {
	} elseif ($groupDigits == 0) {
		$thousands_sep = "";
	}

	// Start by formatting the unsigned number
	$number = number_format(abs($amount),
						  $frac_digits,
						  $decimal_point,
						  $thousands_sep);

	// Check $includeLeadingDigit
	if ($includeLeadingDigit == 0) {
		if (substr($number, 0, 2) == "0.")
			$number = substr($number, 1, strlen($number)-1);
	}
	if ($amount < 0) {
		$sign = $negative_sign;
		$key = $n_sign_posn;
	} else {
		$sign = $positive_sign;
		$key = $p_sign_posn;
	}
	$formats = array(
		'0' => '(%s)',
		'1' => $sign . '%s',
		'2' => $sign . '%s',
		'3' => $sign . '%s',
		'4' => $sign . '%s');

	// Lookup the key in the above array
	return sprintf($formats[$key], $number);
}

// Format percent
// Arguments: Expression ,NumDigitsAfterDecimal [,IncludeLeadingDigit [,UseParensForNegativeNumbers [,GroupDigits]]]
// NumDigitsAfterDecimal is the numeric value indicating how many places to the right of the decimal are displayed
// -1 Use Default
// The IncludeLeadingDigit, UseParensForNegativeNumbers, and GroupDigits arguments have the following settings:
// -1 True
// 0 False
// -2 Use Default
function ew_FormatPercent($amount, $numDigitsAfterDecimal, $includeLeadingDigit = -2, $useParensForNegativeNumbers = -2, $groupDigits = -2) {
	extract($GLOBALS["EW_LOCALE"]);

	// Check $numDigitsAfterDecimal
	if ($numDigitsAfterDecimal > -1)
		$frac_digits = $numDigitsAfterDecimal;

	// Check $useParensForNegativeNumbers
	if ($useParensForNegativeNumbers == -1) {
		$n_sign_posn = 0;
		if ($p_sign_posn == 0) {
			$p_sign_posn = 3;
		}
	} elseif ($useParensForNegativeNumbers == 0) {
		if ($n_sign_posn == 0)
			$n_sign_posn = 3;
	}

	// Check $groupDigits
	if ($groupDigits == -1) {
	} elseif ($groupDigits == 0) {
		$thousands_sep = "";
	}

	// Start by formatting the unsigned number
	$number = number_format(abs($amount)*100,
							$frac_digits,
							$decimal_point,
							$thousands_sep);

	// Check $includeLeadingDigit
	if ($includeLeadingDigit == 0) {
		if (substr($number, 0, 2) == "0.")
			$number = substr($number, 1, strlen($number)-1);
	}
	if ($amount < 0) {
		$sign = $negative_sign;
		$key = $n_sign_posn;
	} else {
		$sign = $positive_sign;
		$key = $p_sign_posn;
	}
	$formats = array(
		'0' => '(%s%%)',
		'1' => $sign . '%s%%',
		'2' => $sign . '%s%%',
		'3' => $sign . '%s%%',
		'4' => $sign . '%s%%');

	// Lookup the key in the above array
	return sprintf($formats[$key], $number);
}

// Format sequence number
function ew_FormatSeqNo($seq) {
	global $Language;
	return str_replace("%s", $seq, $Language->Phrase("SequenceNumber"));
}

// Encode value for single-quoted JavaScript string
function ew_JsEncode($val) {
	$val = strval($val);
	if (EW_IS_DOUBLE_BYTE)
		$val = ew_ConvertToUtf8($val);
	$val = str_replace("\\", "\\\\", $val);
	$val = str_replace("'", "\\'", $val);
	$val = str_replace("\r\n", "<br>", $val);
	$val = str_replace("\r", "<br>", $val);
	$val = str_replace("\n", "<br>", $val);
	if (EW_IS_DOUBLE_BYTE)
		$val = ew_ConvertFromUtf8($val);
	return $val;
}

// Display field value separator
// idx (int) display field index (1|2|3)
// fld (object) field object
function ew_ValueSeparator($idx, &$fld) {
	$sep = ($fld) ? $fld->DisplayValueSeparator : ", ";
	return (is_array($sep)) ? @$sep[$idx - 1] : $sep;
}

// Delimited values separator (for select-multiple or checkbox)
// idx (int) zero based value index
function ew_ViewOptionSeparator($idx = -1) {
	return ", ";
}

// Get temp upload path
function ew_UploadTempPath($fldvar = "", $tblvar = "") {
	$path = (EW_UPLOAD_TEMP_PATH) ? ew_IncludeTrailingDelimiter(EW_UPLOAD_TEMP_PATH, TRUE) : ew_UploadPathEx(TRUE, EW_UPLOAD_DEST_PATH);
	$path .= EW_UPLOAD_TEMP_FOLDER_PREFIX . session_id();
	if ($tblvar <> "")
		$path .= EW_PATH_DELIMITER . $tblvar;
	if ($fldvar <> "")
		$path .= EW_PATH_DELIMITER . $fldvar;
	return $path;
}

// Render upload field to temp path
function ew_RenderUploadField(&$fld, $idx = -1) {
	global $Language;
	$fldvar = ($idx < 0) ? $fld->FldVar : substr($fld->FldVar, 0, 1) . $idx . substr($fld->FldVar, 1);
	$folder = ew_UploadTempPath($fldvar, $fld->TblVar);
	ew_CleanUploadTempPaths(); // Clean all old temp folders
	ew_CleanPath($folder); // Clean the upload folder
	if (!file_exists($folder)) {
		if (!ew_CreateFolder($folder))
			die("Cannot create folder: " . $folder);
	}
	$thumbnailfolder = ew_PathCombine($folder, EW_UPLOAD_THUMBNAIL_FOLDER, TRUE);
	if (!file_exists($thumbnailfolder)) {
		if (!ew_CreateFolder($thumbnailfolder))
			die("Cannot create folder: " . $thumbnailfolder);
	}
	if ($fld->FldDataType == EW_DATATYPE_BLOB) { // Blob field
		if (!ew_Empty($fld->Upload->DbValue)) {

			// Create upload file
			$filename = ($fld->Upload->FileName <> "") ? $fld->Upload->FileName : substr($fld->FldVar, 2);
			$f = ew_IncludeTrailingDelimiter($folder, TRUE) . $filename;
			ew_CreateUploadFile($f, $fld->Upload->DbValue);

			// Create thumbnail file
			$f = ew_IncludeTrailingDelimiter($thumbnailfolder, TRUE) . $filename;
			$data = $fld->Upload->DbValue;
			$width = EW_UPLOAD_THUMBNAIL_WIDTH;
			$height = EW_UPLOAD_THUMBNAIL_HEIGHT;
			ew_ResizeBinary($data, $width, $height);
			ew_CreateUploadFile($f, $data);
			$fld->Upload->FileName = basename($f); // Update file name
		}
	} else { // Upload to folder
		$fld->Upload->FileName = $fld->Upload->DbValue; // Update file name
		if (!ew_Empty($fld->Upload->FileName)) {

			// Create upload file
			$pathinfo = pathinfo($fld->Upload->FileName);
			$filename = $pathinfo['basename'];
			$filepath = (@$pathinfo['dirname'] <> "") ? $fld->UploadPath . '/' . $pathinfo['dirname'] : $fld->UploadPath;
			if ($fld->UploadMultiple)
				$files = explode(EW_MULTIPLE_UPLOAD_SEPARATOR, $filename);
			else
				$files = array($filename);
			$cnt = count($files);
			for ($i = 0; $i < $cnt; $i++) {
				$filename = $files[$i];
				if ($filename <> "") {
					$srcfile = ew_UploadPathEx(TRUE, $filepath) . $filename;
					$f = ew_IncludeTrailingDelimiter($folder, TRUE) . $filename;
					if (!is_dir($srcfile) && file_exists($srcfile)) {
						$data = file_get_contents($srcfile);
						ew_CreateUploadFile($f, $data);
					} else {
						ew_CreateImageFromText($Language->Phrase("FileNotFound"), $f);
						$data = file_get_contents($f);
					}

					// Create thumbnail file
					$f = ew_IncludeTrailingDelimiter($thumbnailfolder, TRUE) . $filename;
					$width = EW_UPLOAD_THUMBNAIL_WIDTH;
					$height = EW_UPLOAD_THUMBNAIL_HEIGHT;
					ew_ResizeBinary($data, $width, $height);
					ew_CreateUploadFile($f, $data);
				}
			}
		}
	}
}

// Write uploaded file
function ew_CreateUploadFile(&$f, $data) {
	$handle = fopen($f, 'w+');
	fwrite($handle, $data);
	fclose($handle);
	$pathinfo = pathinfo($f);
	if (!isset($pathinfo['extension']) || $pathinfo['extension'] == '') {
		$info = @getimagesize($f);
		switch (@$info[2]) {
			case 1:
				rename($f, $f .= '.gif'); break;
			case 2:
				rename($f, $f .= '.jpg'); break;
			case 3:
				rename($f, $f .= '.png'); break;
		}
	}
}

// Create image from text
function ew_CreateImageFromText($txt, $file, $width = EW_UPLOAD_THUMBNAIL_WIDTH, $height = 0, $font = EW_TMP_IMAGE_FONT) {
	$pt = round(EW_FONT_SIZE/1.33); // 1pt = 1.33px
	$h = ($height > 0) ? $height : round(EW_FONT_SIZE / 14 * 20);
	$im = @imagecreate($width, $h);
	$color = @imagecolorallocate($im, 255, 255, 255);
	$color = @imagecolorallocate($im, 0, $h, 0);
	if (strrpos($font, '.') === FALSE)
		$font .= '.ttf';
	$font = $GLOBALS["EW_FONT_PATH"] . EW_PATH_DELIMITER . $font; // Always use full path
	@imagettftext($im, $pt, 0, 0, round(($h - EW_FONT_SIZE)/2 + EW_FONT_SIZE), $color, $font, ew_ConvertToUtf8($txt));
	@imagepng($im, $file);
	@imagedestroy($im);
}

// Clean temp upload folders
function ew_CleanUploadTempPaths($sessionid = "") {
	$folder = (EW_UPLOAD_TEMP_PATH) ? ew_IncludeTrailingDelimiter(EW_UPLOAD_TEMP_PATH, TRUE) : ew_UploadPathEx(TRUE, EW_UPLOAD_DEST_PATH);
	if (@is_dir($folder)) {

		// Load temp folders
		foreach (glob($folder . EW_UPLOAD_TEMP_FOLDER_PREFIX . "*", GLOB_ONLYDIR) as $tempfolder) {
			$subfolder = basename($tempfolder);
			if (EW_UPLOAD_TEMP_FOLDER_PREFIX . $sessionid == $subfolder) { // Clean session folder
				ew_CleanPath($tempfolder, TRUE);
			} else {
				if (EW_UPLOAD_TEMP_FOLDER_PREFIX . session_id() <> $subfolder) {
					if (ew_IsEmptyPath($tempfolder)) { // Empty folder
						ew_CleanPath($tempfolder, TRUE);
					} else { // Old folder
						$lastmdtime = filemtime($tempfolder);
						if ((time() - $lastmdtime) / 60 > EW_UPLOAD_TEMP_FOLDER_TIME_LIMIT || count(@scandir($tempfolder)) == 2)
							ew_CleanPath($tempfolder, TRUE);
					}
				}
			}
		}
	}
}

// Clean temp upload folder
function ew_CleanUploadTempPath($fld, $idx = -1) {
	$fldvar = ($idx < 0) ? $fld->FldVar : substr($fld->FldVar, 0, 1) . $idx . substr($fld->FldVar, 1);
	$folder = ew_UploadTempPath($fldvar, $fld->TblVar);
	ew_CleanPath($folder, TRUE); // Clean the upload folder

	// Remove table temp folder if empty
	$folder = ew_UploadTempPath("", $fld->TblVar);
	$files = @scandir($folder);
	if (count($files) <= 2)
		ew_CleanPath($folder, TRUE);

	// Remove complete temp folder if empty
	$folder = ew_UploadTempPath();
	$files = @scandir($folder);
	if (count($files) <= 2)
		ew_CleanPath($folder, TRUE);
}

// Clean folder
function ew_CleanPath($folder, $delete = FALSE) {
	$folder = ew_IncludeTrailingDelimiter($folder, TRUE);
	try {
		if (@is_dir($folder)) {

			// Delete files in the folder
			if ($ar = glob($folder . '*.*')) {
				foreach ($ar as $v) {
					@unlink($v);
				}
			}

			// Clear sub folders
			if ($dir_handle = @opendir($folder)) {
				while (FALSE !== ($subfolder = readdir($dir_handle))) {
					$tempfolder = ew_PathCombine($folder, $subfolder, TRUE);
					if ($subfolder == "." || $subfolder == ".." || !@is_dir($tempfolder))
						continue;
					ew_CleanPath($tempfolder, $delete);
				}
			}
			if ($delete) {
				@closedir($dir_handle);
				@rmdir($folder);
			}
		}
	} catch (Exception $e) {
		if (EW_DEBUG_ENABLED)
			throw $e;
	}
}

// Check if empty folder
function ew_IsEmptyPath($folder) {
	$IsEmptyPath = TRUE;

	// Check folder
	$folder = ew_IncludeTrailingDelimiter($folder, TRUE);
	if (is_dir($folder)) {
		if (count(@scandir($folder)) > 2)
			return FALSE;
		if ($dir_handle = @opendir($folder)) {
			while (FALSE !== ($subfolder = readdir($dir_handle))) {
				$tempfolder = ew_PathCombine($folder, $subfolder, TRUE);
				if ($subfolder == "." || $subfolder == "..")
					continue;
				if (is_dir($tempfolder))
					$IsEmptyPath = ew_IsEmptyPath($tempfolder);
				if (!$IsEmptyPath)
					return FALSE; // No need to check further
			}
		}
	} else {
		$IsEmptyPath = FALSE;
	}
	return $IsEmptyPath;
}

// Move uploaded file
function ew_MoveUploadFile($srcfile, $destfile) {
	$res = move_uploaded_file($srcfile, $destfile);
	if ($res) chmod($destfile, EW_UPLOADED_FILE_MODE);
	return $res;
}

// Truncate Memo Field based on specified length, string truncated to nearest space or CrLf
function ew_TruncateMemo($memostr, $ln, $removehtml) {
	$str = ($removehtml) ? ew_RemoveHtml($memostr) : $memostr;
	if (strlen($str) > 0 && strlen($str) > $ln) {
		$k = 0;
		while ($k >= 0 && $k < strlen($str)) {
			$i = strpos($str, " ", $k);
			$j = strpos($str, chr(10), $k);
			if ($i === FALSE && $j === FALSE) { // Not able to truncate
				return $str;
			} else {

				// Get nearest space or CrLf
				if ($i > 0 && $j > 0) {
					if ($i < $j) {
						$k = $i;
					} else {
						$k = $j;
					}
				} elseif ($i > 0) {
					$k = $i;
				} elseif ($j > 0) {
					$k = $j;
				}

				// Get truncated text
				if ($k >= $ln) {
					return substr($str, 0, $k) . "...";
				} else {
					$k++;
				}
			}
		}
	} else {
		return $str;
	}
}

// Remove HTML tags from text
function ew_RemoveHtml($str) {
	return preg_replace('/<[^>]*>/', '', strval($str));
}

// Extract JavaScript from HTML and return converted script
function ew_ExtractScript(&$html, $class = "") {
	if (!preg_match_all('/<script([^>]*)>([\s\S]*?)<\/script\s*>/i', $html, $matches, PREG_SET_ORDER))
		return "";
	$scripts = "";
	foreach ($matches as $match) {
		if (preg_match('/(\s+type\s*=\s*[\'"]*(text|application)\/(java|ecma)script[\'"]*)|^((?!\s+type\s*=).)*$/i', $match[1])) { // JavaScript
			$html = str_replace($match[0], "", $html); // Remove the script from HTML
			$scripts .= ew_HtmlElement("script", array("type" => "text/html", "class" => $class), $match[2]); // Convert script type and add CSS class, if specified
		}
	}
	return $scripts; // Return converted scripts
}

// Include PHPMailer class
include_once($EW_RELATIVE_PATH . "phpmailer5223/PHPMailerAutoload.php");

// Function to send email
function ew_SendEmail($sFrEmail, $sToEmail, $sCcEmail, $sBccEmail, $sSubject, $sMail, $sFormat, $sCharset, $sSmtpSecure = "", $arAttachments = array(), $arImages = array(), $arProperties = NULL) {
	global $Language, $gsEmailErrDesc;
	$res = FALSE;
	$mail = new PHPMailer();
	$mail->IsSMTP(); 
	$mail->Host = EW_SMTP_SERVER;
	$mail->SMTPAuth = (EW_SMTP_SERVER_USERNAME <> "" && EW_SMTP_SERVER_PASSWORD <> "");
	$mail->Username = EW_SMTP_SERVER_USERNAME;
	$mail->Password = EW_SMTP_SERVER_PASSWORD;
	$mail->Port = EW_SMTP_SERVER_PORT;
	if ($sSmtpSecure <> "") {
		$mail->SMTPSecure = $sSmtpSecure;
		$mail->SMTPOptions = array("ssl" => array("verify_peer" => FALSE, "verify_peer_name" => FALSE, "allow_self_signed" => TRUE));
	}
	if (preg_match('/^(.+)<([\w.%+-]+@[\w.-]+\.[A-Z]{2,6})>$/i', trim($sFrEmail), $m)) {
		$mail->From = $m[2];
		$mail->FromName = trim($m[1]);
	} else {
		$mail->From = $sFrEmail;
		$mail->FromName = $sFrEmail;
	}
	$mail->Subject = $sSubject;
	if (ew_SameText($sFormat, "html")) {
		$mail->IsHTML(TRUE);
		$mail->Body = $sMail;
	} else {
		$mail->IsHTML(FALSE);
		$mail->Body = @Html2Text\Html2Text::convert($sMail);
    }
	if ($sCharset <> "" && strtolower($sCharset) <> "iso-8859-1")
		$mail->CharSet = $sCharset;
	$sToEmail = str_replace(";", ",", $sToEmail);
	$arrTo = explode(",", $sToEmail);
	foreach ($arrTo as $sTo) {
		$mail->AddAddress(trim($sTo));
	}
	if ($sCcEmail <> "") {
		$sCcEmail = str_replace(";", ",", $sCcEmail);
		$arrCc = explode(",", $sCcEmail);
		foreach ($arrCc as $sCc) {
			$mail->AddCC(trim($sCc));
		}
	}
	if ($sBccEmail <> "") {
		$sBccEmail = str_replace(";", ",", $sBccEmail);
		$arrBcc = explode(",", $sBccEmail);
		foreach ($arrBcc as $sBcc) {
			$mail->AddBCC(trim($sBcc));
		}
	}
	if (is_array($arAttachments)) {
		foreach ($arAttachments as $attachment) {
			$filename = @$attachment["filename"];
			$content = @$attachment["content"];
			if ($content <> "" && $filename <> "") {
				$mail->AddStringAttachment($content, $filename);
			} else if ($filename <> "") {
				$mail->AddAttachment($filename);
			}
		}
	}
	if (is_array($arImages)) {
		foreach ($arImages as $tmpimage) {
			$file = ew_UploadPathEx(TRUE, EW_UPLOAD_DEST_PATH) . $tmpimage;
			$cid = ew_TmpImageLnk($tmpimage, "cid");
			$mail->AddEmbeddedImage($file, $cid, $tmpimage);
		}
	}
	if (is_array($arProperties)) {
		foreach ($arProperties as $key => $value)
			$mail->set($key, $value);
	}
	$res = $mail->Send();
	$gsEmailErrDesc = $mail->ErrorInfo;

	// Uncomment to debug
//		var_dump($mail); exit();

	return $res;
}

// Clean email content
function ew_CleanEmailContent($Content) {
	$Content = str_replace("class=\"panel panel-default ewGrid\"", "", $Content);
	$Content = str_replace("class=\"table-responsive ewGridMiddlePanel\"", "", $Content);
	$Content = str_replace("table ewTable", "ewExportTable", $Content);
	return $Content;
}

// Field data type
function ew_FieldDataType($fldtype) {
	switch ($fldtype) {
		case 20:
		case 3:
		case 2:
		case 16:
		case 4:
		case 5:
		case 131:
		case 139:
		case 6:
		case 17:
		case 18:
		case 19:
		case 21: // Numeric
			return EW_DATATYPE_NUMBER;
		case 7:
		case 133:
		case 135: // Date
		case 146: // DateTiemOffset
			return EW_DATATYPE_DATE;
		case 134: // Time
		case 145: // Time
			return EW_DATATYPE_TIME;
		case 201:
		case 203: // Memo
			return EW_DATATYPE_MEMO;
		case 129:
		case 130:
		case 200:
		case 202: // String
			return EW_DATATYPE_STRING;
		case 11: // Boolean
			return EW_DATATYPE_BOOLEAN;
		case 72: // GUID
			return EW_DATATYPE_GUID;
		case 128:
		case 204:
		case 205: // Binary
			return EW_DATATYPE_BLOB;
		case 141: // XML
			return EW_DATATYPE_XML;
		default:
			return EW_DATATYPE_OTHER;
	}
}

// Application root
function ew_AppRoot() {
	global $EW_ROOT_RELATIVE_PATH;

	// Use root relative path
	$Path = realpath($EW_ROOT_RELATIVE_PATH ?: ".");
	$Path = preg_replace('/(?<!^)\\\\\\\\/', EW_PATH_DELIMITER, $Path); // Replace '\\' (not at the start of path) by path delimiter 

	// Use custom path, uncomment the following line and enter your path, e.g.:
	// $Path = 'C:\MyPath\MyWebRoot'; // Windows
	//$Path = 'enter your path here';

	if (empty($Path))
		die("Path of website root unknown.");
	return ew_IncludeTrailingDelimiter($Path, TRUE);
}

// Get path relative to application root
function ew_ServerMapPath($Path) {
	return ew_RemoveTrailingDelimiter(ew_PathCombine(ew_AppRoot(), $Path, TRUE), TRUE);
}

// Write the paths for config/debug only
function ew_WritePaths() {
	global $EW_ROOT_RELATIVE_PATH, $EW_RELATIVE_PATH;
	echo "EW_RELATIVE_PATH = " . $EW_RELATIVE_PATH . "<br>";
	echo "EW_ROOT_RELATIVE_PATH = " . $EW_ROOT_RELATIVE_PATH . "<br>";
	echo "EW_UPLOAD_DEST_PATH = " . EW_UPLOAD_DEST_PATH . "<br>";
	echo "ew_AppRoot() = " . ew_AppRoot() . "<br>";
	echo "realpath('.') = " . realpath(".") . "<br>";
	echo "DOCUMENT_ROOT = " . ew_ServerVar("DOCUMENT_ROOT") . "<br>";
	echo "__FILE__ = " . __FILE__ . "<br>";
}

// Write info for config/debug only
function ew_Info() {
	global $Security;
	ew_WritePaths();
	echo "CurrentUserName() = " . CurrentUserName() . "<br>";
	echo "CurrentUserID() = " . CurrentUserID() . "<br>";
	echo "CurrentParentUserID() = " . CurrentParentUserID() . "<br>";
	echo "IsLoggedIn() = " . (IsLoggedIn() ? "TRUE" : "FALSE") . "<br>";
	echo "IsAdmin() = " . (IsAdmin() ? "TRUE" : "FALSE") . "<br>";
	echo "IsSysAdmin() = " . (IsSysAdmin() ? "TRUE" : "FALSE") . "<br>";
	if (isset($Security))
		$Security->ShowUserLevelInfo();
}

// Upload path
// If PhyPath is TRUE(1), return physical path on the server
// If PhyPath is FALSE(0), return relative URL
function ew_UploadPathEx($PhyPath, $DestPath) {
	global $EW_ROOT_RELATIVE_PATH;
	if ($PhyPath) {
		$Path = ew_PathCombine(ew_AppRoot(), str_replace("/", EW_PATH_DELIMITER, $DestPath), TRUE);
	} else {
		$Path = ew_PathCombine($EW_ROOT_RELATIVE_PATH, $DestPath, FALSE);
	}
	return ew_IncludeTrailingDelimiter($Path, $PhyPath);
}

// Global upload path
// If PhyPath is TRUE(1), return physical path on the server
// If PhyPath is FALSE(0), return relative URL
function ew_UploadPath($PhyPath) {
	return ew_UploadPathEx($PhyPath, EW_UPLOAD_DEST_PATH);
}

// Upload file name
function ew_UploadFileNameEx($folder, $sFileName) {

	// By default, ew_UniqueFileName() is used to get an unique file name
	// You can change the logic here

	$sOutFileName = ew_UniqueFilename($folder, $sFileName);

	// Return computed output file name
	return $sOutFileName;
}

// Generate an unique file name (filename(n).ext)
function ew_UniqueFilename($folder, $orifn, $indexed = FALSE) {
	if ($orifn == "")
		$orifn = date("YmdHis") . ".bin";

	//$info = pathinfo(preg_replace('/\s/', '_', $orifn));
	//$newfn = strtolower($info["basename"]);

	$info = pathinfo($orifn);
	$newfn = $info["basename"];
	$destpath = $folder . $newfn;
	$i = 1;
	if ($indexed && preg_match('/\(\d+\)$/', $newfn, $matches)) // Match '(n)' at the end of the file name
		$i = intval($matches[1]);
	if (!file_exists($folder) && !ew_CreateFolder($folder))
		die("Folder does not exist: " . $folder);
	while (file_exists(ew_Convert(EW_ENCODING, EW_FILE_SYSTEM_ENCODING, $destpath))) {

		//$file_name = preg_replace('/\(\d+\)$/', '', strtolower($info["filename"])); // Remove "(n)" at the end of the file name
		//$newfn = $file_name . "(" . $i++ . ")." . strtolower($info["extension"]);

		$file_name = preg_replace('/\(\d+\)$/', '', $info["filename"]); // Remove "(n)" at the end of the file name
		$newfn = $file_name . "(" . $i++ . ")." . $info["extension"];
		$destpath = $folder . $newfn;
	}
	return $newfn;
}

// Get refer URL
function ew_ReferURL() {
	return ew_ServerVar("HTTP_REFERER");
}

// Get refer page name
function ew_ReferPage() {
	return ew_GetPageName(ew_ReferURL());
}

// Get script physical folder
function ew_ScriptFolder() {
	$folder = "";
	$path = ew_ServerVar("SCRIPT_FILENAME");
	$p = strrpos($path, EW_PATH_DELIMITER);
	if ($p !== FALSE)
		$folder = substr($path, 0, $p);
	return ($folder <> "") ? $folder : realpath(".");
}

// Get a temp folder for temp file
function ew_TmpFolder() {
	$tmpfolder = NULL;
	$folders = array();
	if (EW_IS_WINDOWS) {
		$folders[] = ew_ServerVar("TEMP");
		$folders[] = ew_ServerVar("TMP");
	} else {
		if (EW_UPLOAD_TMP_PATH <> "") $folders[] = ew_AppRoot() . str_replace("/", EW_PATH_DELIMITER, EW_UPLOAD_TMP_PATH);
		$folders[] = '/tmp';
	}
	if (ini_get('upload_tmp_dir')) {
		$folders[] = ini_get('upload_tmp_dir');
	}
	foreach ($folders as $folder) {
		if (!$tmpfolder && is_dir($folder)) {
			$tmpfolder = $folder;
		}
	}

	//if ($tmpfolder) $tmpfolder = ew_IncludeTrailingDelimiter($tmpfolder, TRUE);
	return $tmpfolder;
}

// Create folder
function ew_CreateFolder($dir, $mode = 0777) {
	return (is_dir($dir) || @mkdir($dir, $mode, TRUE));
}

// Save file
function ew_SaveFile($folder, $fn, $filedata) {
	$fn = ew_Convert(EW_ENCODING, EW_FILE_SYSTEM_ENCODING, $fn);
	$res = FALSE;
	if (ew_CreateFolder($folder)) {
		if ($handle = fopen($folder . $fn, 'w')) { // P6
			$res = fwrite($handle, $filedata);
			fclose($handle);
		}
		if ($res)
			chmod($folder . $fn, EW_UPLOADED_FILE_MODE);
	}
	return $res;
}

// Copy file
function ew_CopyFile($folder, $fn, $file) {
	$fn = ew_Convert(EW_ENCODING, EW_FILE_SYSTEM_ENCODING, $fn);
	if (file_exists($file)) {
		if (ew_CreateFolder($folder)) {
			$newfile = ew_UploadPathEx(TRUE, $folder) . $fn;
			return copy($file, $newfile);
		}
	}
	return FALSE;
}

// Generate random number
function ew_Random() {
	return mt_rand();
}

// Remove CR and LF
function ew_RemoveCrLf($s) {
	if (strlen($s) > 0) {
		$s = str_replace("\n", " ", $s);
		$s = str_replace("\r", " ", $s);
		$s = str_replace("\l", " ", $s);
	}
	return $s;
}

// Calculate field hash
function ew_GetFldHash($value) {
	return md5(ew_GetFldValueAsString($value));
}

// Get field value as string
function ew_GetFldValueAsString($value) {
	if (is_null($value)) {
		return "";
	} else {
		if (strlen($value) > 65535) { // BLOB/TEXT
			if (EW_BLOB_FIELD_BYTE_COUNT > 0) {
				return substr($value, 0, EW_BLOB_FIELD_BYTE_COUNT);
			} else {
				return $value;
			}
		} else {
			return strval($value);
		}
	}
}

// Convert byte array to binary string
function ew_BytesToStr($bytes) {
	$str = "";
	foreach ($bytes as $byte)
		$str .= chr($byte);
	return $str;
}

// Convert binary string to byte array
function ew_StrToBytes($str) {
	$cnt = strlen($str);
	$bytes = array();
	for ($i = 0; $i < $cnt; $i++)
		$bytes[] = ord($str[$i]);
	return $bytes;
}

// Create temp image file from binary data
function ew_TmpImage(&$filedata) {
	global $gTmpImages;
	$export = "";
	if (@$_GET["export"] <> "")
		$export = $_GET["export"];
	elseif (@$_POST["export"] <> "")
		$export = $_POST["export"];
	elseif (@$_POST["exporttype"] <> "")
		$export = $_POST["exporttype"];

//  $f = tempnam(ew_TmpFolder(), "tmp");
	$folder = ew_AppRoot() . EW_UPLOAD_DEST_PATH;
	$f = tempnam($folder, "tmp");
	$handle = fopen($f, 'w+');
	fwrite($handle, $filedata);
	fclose($handle);
	$info = @getimagesize($f);
	switch ($info[2]) {
		case 1:
			rename($f, $f .= '.gif'); break;
		case 2:
			rename($f, $f .= '.jpg'); break;
		case 3:
			rename($f, $f .= '.png'); break;
		case 6:
			rename($f, $f .= '.bmp'); break;
		default:
			return "";
	}
	$tmpimage = basename($f);
	$gTmpImages[] = $tmpimage;

	//return EW_UPLOAD_DEST_PATH . $tmpimage;
	return ew_TmpImageLnk($tmpimage, $export);
}

// Delete temp images
function ew_DeleteTmpImages() {
	global $gTmpImages;
	foreach ($gTmpImages as $tmpimage)
		@unlink(ew_AppRoot() . EW_UPLOAD_DEST_PATH . $tmpimage);
}

// Get temp image link
function ew_TmpImageLnk($file, $lnktype = "") {
	global $EW_ROOT_RELATIVE_PATH;
	if ($file == "") return "";
	if ($lnktype == "email" || $lnktype == "cid") {
		$ar = explode('.', $file);
		$lnk = implode(".", array_slice($ar, 0, count($ar)-1));
		if ($lnktype == "email") $lnk = "cid:" . $lnk;
		return $lnk;
	} else {
		if ($lnktype == "excel" && defined('EW_USE_PHPEXCEL') || $lnktype == "word" && defined('EW_USE_PHPWORD')) {
			return EW_UPLOAD_DEST_PATH . $file;
		} else {
			$fn = EW_UPLOAD_DEST_PATH . $file;
			if ($EW_ROOT_RELATIVE_PATH <> ".") $fn = $EW_ROOT_RELATIVE_PATH . "/" . $fn;
			return $fn;
		}
	}
}

// Get Hash Url
function ew_GetHashUrl($url, $hash) {
	$wrkurl = $url;
	$wrkurl .= "#" . $hash;
	return $wrkurl;
}

// Add querystring to url
function ew_AddQueryStringToUrl($url, $qry) {
	return $url . (strpos($url, "?") !== FALSE ? "&" : "?") . $qry;
}
?>
<?php
/**
 * Form class
 */

class cFormObj {
	var $Index;
	var $FormName = "";

	// Constructor
	function __construct() {
		$this->Index = -1;
	}

	// Get form element name based on index
	function GetIndexedName($name) {
		if ($this->Index < 0) {
			return $name;
		} else {
			return substr($name, 0, 1) . $this->Index . substr($name, 1);
		}
	}

	// Has value for form element
	function HasValue($name) {
		$wrkname = $this->GetIndexedName($name);
		if (preg_match('/^(fn_)?(x|o)\d*_/', $name) && $this->FormName <> "") {
			if (isset($_POST[$this->FormName . '$' . $wrkname]))
				return TRUE;
		}
		return isset($_POST[$wrkname]);
	}	

	// Get value for form element
	function GetValue($name) {
		$wrkname = $this->GetIndexedName($name);
		$value = @$_POST[$wrkname];
		if (preg_match('/^(fn_)?(x|o)\d*_/', $name) && $this->FormName <> "") {
			$wrkname = $this->FormName . '$' . $wrkname;
			if (isset($_POST[$wrkname]))
				$value = $_POST[$wrkname];
		}
		return $value;
	}

	// Get upload file size
	function GetUploadFileSize($name) {
		$wrkname = $this->GetIndexedName($name);
		return @$_FILES[$wrkname]['size'];
	}

	// Get upload file name
	function GetUploadFileName($name) {
		$wrkname = $this->GetIndexedName($name);
		return @$_FILES[$wrkname]['name'];
	}

	// Get file content type
	function GetUploadFileContentType($name) {
		$wrkname = $this->GetIndexedName($name);
		return @$_FILES[$wrkname]['type'];
	}

	// Get file error
	function GetUploadFileError($name) {
		$wrkname = $this->GetIndexedName($name);
		return @$_FILES[$wrkname]['error'];
	}

	// Get file temp name
	function GetUploadFileTmpName($name) {
		$wrkname = $this->GetIndexedName($name);
		return @$_FILES[$wrkname]['tmp_name'];
	}

	// Check if is upload file
	function IsUploadedFile($name) {
		$wrkname = $this->GetIndexedName($name);
		return is_uploaded_file(@$_FILES[$wrkname]["tmp_name"]);
	}

	// Get upload file data
	function GetUploadFileData($name) {
		if ($this->IsUploadedFile($name)) {
			$wrkname = $this->GetIndexedName($name);
			return file_get_contents($_FILES[$wrkname]["tmp_name"]);
		} else {
			return NULL;
		}
	}

	// Get upload image size
	function GetUploadImageSize($name) {
		$wrkname = $this->GetIndexedName($name);
		$file = @$_FILES[$wrkname]['tmp_name'];
		return (file_exists($file)) ? @getimagesize($file) : array(NULL, NULL);
	}
}
?>
<?php
/**
 * Functions for image resize
 */

// Resize binary to thumbnail
function ew_ResizeBinary(&$filedata, &$width, &$height, $quality = EW_THUMBNAIL_DEFAULT_QUALITY, $plugins = array()) {
	global $EW_THUMBNAIL_CLASS, $EW_RESIZE_OPTIONS;
	if ($width <= 0 && $height <= 0)
		return FALSE;
	$f = tempnam(ew_TmpFolder(), "tmp");
	$handle = @fopen($f, 'wb');
	if ($handle) {
		fwrite($handle, $filedata);
		fclose($handle);
	}
	$format = "";
	if (file_exists($f) && filesize($f) > 0) { // temp file created
		$info = @getimagesize($f);
		@unlink($f);
		if (!$info || !in_array($info[2], array(1, 2, 3))) { // not gif/jpg/png
			return FALSE;
		} elseif ($info[2] == 1) {
			$format = "GIF";
		} elseif ($info[2] == 2) {
			$format = "JPG";
		} elseif ($info[2] == 3) {
			$format = "PNG";
		}
	} else { // temp file not created
		if (substr($filedata, 0, 6) == "\x47\x49\x46\x38\x37\x61" || substr($filedata, 0, 6) == "\x47\x49\x46\x38\x39\x61") {
			$format = "GIF";
		} elseif (substr($filedata, 0, 4) == "\xFF\xD8\xFF\xE0" && substr($filedata, 6, 5) == "\x4A\x46\x49\x46\x00") {
			$format = "JPG";
		} elseif (substr($filedata, 0, 8) == "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A") {
			$format = "PNG";
		} else {
			return FALSE;
		}
	}
	$thumb = new $EW_THUMBNAIL_CLASS($filedata, $EW_RESIZE_OPTIONS + array("isDataStream" => TRUE, "format" => $format), $plugins);
	return $thumb->resizeEx($filedata, $width, $height);
}

// Resize file to thumbnail file
function ew_ResizeFile($fn, $tn, &$width, &$height, $plugins = array()) {
	global $EW_THUMBNAIL_CLASS, $EW_RESIZE_OPTIONS;
	$info = @getimagesize($fn);
	if (!$info || !in_array($info[2], array(1, 2, 3)) ||
		($width <= 0 && $height <= 0)) {
		if ($fn <> $tn) copy($fn, $tn);
		return;
	}
	$thumb = new $EW_THUMBNAIL_CLASS($fn, $EW_RESIZE_OPTIONS, $plugins);
	$fdata = NULL;
	if (!$thumb->resizeEx($fdata, $width, $height, $tn))
		if ($fn <> $tn) copy($fn, $tn);
}

// Resize file to binary
function ew_ResizeFileToBinary($fn, &$width, &$height, $plugins = array()) {
	global $EW_THUMBNAIL_CLASS, $EW_RESIZE_OPTIONS;
	$info = @getimagesize($fn);
	if (!$info)
		return NULL;
	if (!in_array($info[2], array(1, 2, 3)) ||
		($width <= 0 && $height <= 0)) {
		$fdata = file_get_contents($fn);
	} else {
		$thumb = new $EW_THUMBNAIL_CLASS($fn, $EW_RESIZE_OPTIONS, $plugins);
		$fdata = NULL;
		if (!$thumb->resizeEx($fdata, $width, $height))
			$fdata = file_get_contents($fn);
	}
	return $fdata;
}
/**
 * Class Thumbnail (extends GD)
 * Constructor: public function __construct($file, $options = array(), array $plugins = array())
 * @param string $file (file name or file data)
 * @param array $options: 'jpegQuality'(int), resizeUp'(bool), 'keepAspectRatio'(bool), 'isDataStream'(bool), 'format'(string)
 * @param array $plugins: anonymous function with an argument $phpthumb(cThumbnail)
 */

class cThumbnail extends GD {

	// Extended resize method
	function resizeEx(&$fdata, &$width, &$height, $fn = "") {
		try {
			$this->executePlugins()->resize($width, $height); // Execute plugins and resize
			$dimensions = $this->getCurrentDimensions();
			$width = $dimensions["width"];
			$height = $dimensions["height"];
			if ($fn <> "")
				$this->save($fn);
			else
				$fdata = $this->getImageAsString();
			return TRUE;
		} catch (Exception $e) {
			if (EW_DEBUG_ENABLED)
				throw $e;
			return FALSE;
		}
	}
}
?>
<?php
/**
 * Functions for search
 */

// Highlight value based on basic search / advanced search keywords
function ew_Highlight($name, $src, $bkw, $bkwtype, $akw, $akw2="") {
	$outstr = "";
	if (strlen($src) > 0 && (strlen($bkw) > 0 || strlen($akw) > 0 || strlen($akw2) > 0)) {
		$xx = 0;
		$yy = strpos($src, "<", $xx);
		if ($yy === FALSE) $yy = strlen($src);
		while ($yy >= 0) {
			if ($yy > $xx) {
				$wrksrc = substr($src, $xx, $yy - $xx);
				$kwstr = trim($bkw);
				if (strlen($bkw) > 0 && strlen($bkwtype) == 0) { // Check for exact phase
					$kwlist = array($kwstr); // Use single array element
				} else {
					$kwlist = explode(" ", $kwstr);
				}
				if (strlen($akw) > 0)
					$kwlist[] = $akw;
				if (strlen($akw2) > 0)
					$kwlist[] = $akw2;
				$x = 0;
				ew_GetKeyword($wrksrc, $kwlist, $x, $y, $kw);
				while ($y >= 0) {
					$outstr .= substr($wrksrc, $x, $y-$x) .
						"<span class=\"" . $name . " ewHighlightSearch\">" .
						substr($wrksrc, $y, strlen($kw)) . "</span>";
					$x = $y + strlen($kw);
					ew_GetKeyword($wrksrc, $kwlist, $x, $y, $kw);
				}
				$outstr .= substr($wrksrc, $x);
				$xx += strlen($wrksrc);
			}
			if ($xx < strlen($src)) {
				$yy = strpos($src, ">", $xx);
				if ($yy !== FALSE) {
					$outstr .= substr($src, $xx, $yy - $xx + 1);
					$xx = $yy + 1;
					$yy = strpos($src, "<", $xx);
					if ($yy === FALSE) $yy = strlen($src);
				} else {
					$outstr .= substr($src, $xx);
					$yy = -1;
				}
			} else {
				$yy = -1;
			}
		}	
	} else {
		$outstr = $src;
	}
	return $outstr;
}

// Get keyword
function ew_GetKeyword(&$src, &$kwlist, &$x, &$y, &$kw) {
	$thisy = -1;
	$thiskw = "";
	foreach ($kwlist as $wrkkw) {
		$wrkkw = trim($wrkkw);
		if ($wrkkw <> "") {
			if (EW_HIGHLIGHT_COMPARE) { // Case-insensitive
				$wrky = stripos($src, $wrkkw, $x);
			} else {
				$wrky = strpos($src, $wrkkw, $x);
			}
			if ($wrky !== FALSE) {
				if ($thisy == -1) {
					$thisy = $wrky;
					$thiskw = $wrkkw;
				} elseif ($wrky < $thisy) {
					$thisy = $wrky;
					$thiskw = $wrkkw;
				}
			}
		}
	}
	$y = $thisy;
	$kw = $thiskw;
}
?>
<?php
/**
 * Functions for Auto-Update fields
 */

// Get user IP
function ew_CurrentUserIP() {
	return ew_ServerVar("REMOTE_ADDR");
}

// Get current host name, e.g. "www.mycompany.com"
function ew_CurrentHost() {
	return ew_ServerVar("HTTP_HOST");
}

// Get current Windows user (for Windows Authentication)
function ew_CurrentWindowsUser() {
	return ew_ServerVar("AUTH_USER"); // REMOTE_USER or LOGON_USER or AUTH_USER
}

// Get current date in default date format
// $namedformat = -1|5|6|7 (see comment for ew_FormatDateTime)
function ew_CurrentDate($namedformat = -1) {
	if (in_array($namedformat, array(5, 6, 7, 9, 10, 11, 12, 13, 14, 15, 16, 17))) {
		if ($namedformat == 5 || $namedformat == 9 || $namedformat == 12 || $namedformat == 15) {
			$DT = ew_FormatDateTime(date('Y-m-d'), 5);
		} elseif ($namedformat == 6 || $namedformat == 10 || $namedformat == 13 || $namedformat == 16) {
			$DT = ew_FormatDateTime(date('Y-m-d'), 6);
		} else {
			$DT = ew_FormatDateTime(date('Y-m-d'), 7);
		}
		return $DT;
	} else {
		return date('Y-m-d');
	}
}

// Get current time in hh:mm:ss format
function ew_CurrentTime() {
	return date("H:i:s");
}

// Get current date in default date format with time in hh:mm:ss format
// $namedformat = -1, 5-7, 9-11 (see comment for ew_FormatDateTime)
function ew_CurrentDateTime($namedformat = -1) {
	if (in_array($namedformat, array(5, 6, 7, 9, 10, 11, 12, 13, 14, 15, 16, 17))) {
		if ($namedformat == 5 || $namedformat == 9 || $namedformat == 12 || $namedformat == 15) {
			$DT = ew_FormatDateTime(date('Y-m-d H:i:s'), 9);
		} elseif ($namedformat == 6 || $namedformat == 10 || $namedformat == 13 || $namedformat == 16) {
			$DT = ew_FormatDateTime(date('Y-m-d H:i:s'), 10);
		} else {
			$DT = ew_FormatDateTime(date('Y-m-d H:i:s'), 11);
		}
		return $DT;
	} else {
		return date('Y-m-d H:i:s');
	}
}

// Get current date in standard format (yyyy/mm/dd)
function ew_StdCurrentDate() {
	return date('Y/m/d');
}

// Get date in standard format (yyyy/mm/dd)
function ew_StdDate($ts) {
	return date('Y/m/d', $ts);
}

// Get current date and time in standard format (yyyy/mm/dd hh:mm:ss)
function ew_StdCurrentDateTime() {
	return date('Y/m/d H:i:s');
}

// Get date/time in standard format (yyyy/mm/dd hh:mm:ss)
function ew_StdDateTime($ts) {
	return date('Y/m/d H:i:s', $ts);
}

// Encrypt password
function ew_EncryptPassword($input, $salt = '') {
	return (strval($salt) <> "") ? md5($input . $salt) . ":" . $salt : md5($input);
}

// Compare password
// Note: If salted, password must be stored in '<hashedstring>:<salt>' or in phpass format
function ew_ComparePassword($pwd, $input, $encrypted = FALSE) {
	if ($encrypted)
		return $pwd == $input;
	if (preg_match('/^\$[HP]\$/', $pwd)) { // phpass
		include "passwordhash.php";
		$ar = json_decode(EW_PHPASS_ITERATION_COUNT_LOG2);
		if (is_array($ar)) {
			foreach ($ar as $i) {
				$hasher = new PasswordHash($i, TRUE);
				if ($hasher->CheckPassword($input, $pwd))
					return TRUE;
			}
			return FALSE;
		}
	} elseif (strpos($pwd, ':') !== FALSE) { // <hashedstring>:<salt>
		@list($crypt, $salt) = explode(":", $pwd, 2);
		return ($pwd == ew_EncryptPassword($input, $salt));
	} else {
		if (EW_CASE_SENSITIVE_PASSWORD) {
			if (EW_ENCRYPTED_PASSWORD) {
				return ($pwd == ew_EncryptPassword($input));
			} else {
				return ($pwd == $input);
			}
		} else {
			if (EW_ENCRYPTED_PASSWORD) {
				return ($pwd == ew_EncryptPassword(strtolower($input)));
			} else {
				return (strtolower($pwd) == strtolower($input));
			}
		}
	}
}

// Get security object
function &Security() {
	return $GLOBALS["Security"];
}

// Get profile object
function &Profile() {
	return $GLOBALS["UserProfile"];
}

// Get language object
function &Language() {
	return $GLOBALS["Language"];
}

// Get breadcrumb object
function &Breadcrumb() {
	return $GLOBALS["Breadcrumb"];
}
/**
 * Functions for backward compatibilty
 */

// Get current user name
function CurrentUserName() {
	global $Security;
	return (isset($Security)) ? $Security->CurrentUserName() : strval(@$_SESSION[EW_SESSION_USER_NAME]);
}

// Get current user ID
function CurrentUserID() {
	global $Security;
	return (isset($Security)) ? $Security->CurrentUserID() : strval(@$_SESSION[EW_SESSION_USER_ID]);
}

// Get current parent user ID
function CurrentParentUserID() {
	global $Security;
	return (isset($Security)) ? $Security->CurrentParentUserID() : strval(@$_SESSION[EW_SESSION_PARENT_USER_ID]);
}

// Get current user level
function CurrentUserLevel() {
	global $Security;
	return (isset($Security)) ? $Security->CurrentUserLevelID() : @$_SESSION[EW_SESSION_USER_LEVEL_ID];
}

// Get current user level list
function CurrentUserLevelList() {
	global $Security;
	return (isset($Security)) ? $Security->UserLevelList() : strval(@$_SESSION[EW_SESSION_USER_LEVEL_LIST]);
}

// Get Current user info
function CurrentUserInfo($fldname) {
	global $Security, $UserTableConn;
	if (isset($Security)) {
		return $Security->CurrentUserInfo($fldname);
	} elseif (defined("EW_USER_TABLE") && !IsSysAdmin()) {
		$user = CurrentUserName();
		if (strval($user) <> "")
			return ew_ExecuteScalar("SELECT " . ew_QuotedName($fldname, EW_USER_TABLE_DBID) . " FROM " . EW_USER_TABLE . " WHERE " .
				str_replace("%u", ew_AdjustSql($user, EW_USER_TABLE_DBID), EW_USER_NAME_FILTER), $UserTableConn);
	}
	return NULL;
}

// Get current page ID
function CurrentPageID() {
	if (isset($GLOBALS["Page"])) {
		return $GLOBALS["Page"]->PageID;
	} elseif (defined("EW_PAGE_ID")) {
		return EW_PAGE_ID;
	}
	return "";
}

// Allow list
function AllowList($TableName) {
	global $Security;
	return $Security->AllowList($TableName);
}

// Allow add
function AllowAdd($TableName) {
	global $Security;
	return $Security->AllowAdd($TableName);
}

// Is password expired
function IsPasswordExpired() {
	global $Security;
	return (isset($Security)) ? $Security->IsPasswordExpired() : (@$_SESSION[EW_SESSION_STATUS] == "passwordexpired");
}

// Set session password expired
function SetSessionPasswordExpired() {
	global $Security;
	if (isset($Security))
		$Security->SetSessionPasswordExpired();
	else
		$_SESSION[EW_SESSION_STATUS] = "passwordexpired";
}

// Is password reset
function IsPasswordReset() {
	global $Security;
	return (isset($Security)) ? $Security->IsPasswordReset() : (@$_SESSION[EW_SESSION_STATUS] == "passwordreset");
}

// Is logging in
function IsLoggingIn() {
	global $Security;
	return (isset($Security)) ? $Security->IsLoggingIn() : (@$_SESSION[EW_SESSION_STATUS] == "loggingin");
}

// Is logged in
function IsLoggedIn() {
	global $Security;
	return (isset($Security)) ? $Security->IsLoggedIn() : (@$_SESSION[EW_SESSION_STATUS] == "login");
}

// Is admin
function IsAdmin() {
	global $Security;
	return (isset($Security)) ? $Security->IsAdmin() : (@$_SESSION[EW_SESSION_SYS_ADMIN] == 1);
}

// Is system admin
function IsSysAdmin() {
	global $Security;
	return (isset($Security)) ? $Security->IsSysAdmin() : (@$_SESSION[EW_SESSION_SYS_ADMIN] == 1);
}

// Is Windows authenticated
function IsAuthenticated() {
	return ew_CurrentWindowsUser() <> "";
}
/**
 * Class for TEA encryption/decryption
 */

class cTEA {

	function long2str($v, $w) {
		$len = count($v);
		$s = array();
		for ($i = 0; $i < $len; $i++)
		{
			$s[$i] = pack("V", $v[$i]);
		}
		if ($w) {
			return substr(join('', $s), 0, $v[$len - 1]);
		}	else {
			return join('', $s);
		}
	}

	function str2long($s, $w) {
		$v = unpack("V*", $s. str_repeat("\0", (4 - strlen($s) % 4) & 3));
		$v = array_values($v);
		if ($w) {
			$v[count($v)] = strlen($s);
		}
		return $v;
	}

	// Encrypt
	public function Encrypt($str, $key = EW_RANDOM_KEY) {
		if ($str == "") {
			return "";
		}
		$v = $this->str2long($str, true);
		$k = $this->str2long($key, false);
		$cntk = count($k);
		if ($cntk < 4) {
			for ($i = $cntk; $i < 4; $i++) {
				$k[$i] = 0;
			}
		}
		$n = count($v) - 1;
		$z = $v[$n];
		$y = $v[0];
		$delta = 0x9E3779B9;
		$q = floor(6 + 52 / ($n + 1));
		$sum = 0;
		while (0 < $q--) {
			$sum = $this->int32($sum + $delta);
			$e = $sum >> 2 & 3;
			for ($p = 0; $p < $n; $p++) {
				$y = $v[$p + 1];
				$mx = $this->int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ $this->int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
				$z = $v[$p] = $this->int32($v[$p] + $mx);
			}
			$y = $v[0];
			$mx = $this->int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ $this->int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
			$z = $v[$n] = $this->int32($v[$n] + $mx);
		}
		return $this->UrlEncode($this->long2str($v, false));
	}

	// Decrypt
	public function Decrypt($str, $key = EW_RANDOM_KEY) {
		$str = $this->UrlDecode($str);
		if ($str == "") {
			return "";
		}
		$v = $this->str2long($str, false);
		$k = $this->str2long($key, false);
		$cntk = count($k);
		if ($cntk < 4) {
			for ($i = $cntk; $i < 4; $i++) {
				$k[$i] = 0;
			}
		}
		$n = count($v) - 1;
		$z = $v[$n];
		$y = $v[0];
		$delta = 0x9E3779B9;
		$q = floor(6 + 52 / ($n + 1));
		$sum = $this->int32($q * $delta);
		while ($sum != 0) {
			$e = $sum >> 2 & 3;
			for ($p = $n; $p > 0; $p--) {
				$z = $v[$p - 1];
				$mx = $this->int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ $this->int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
				$y = $v[$p] = $this->int32($v[$p] - $mx);
			}
			$z = $v[$n];
			$mx = $this->int32((($z >> 5 & 0x07ffffff) ^ $y << 2) + (($y >> 3 & 0x1fffffff) ^ $z << 4)) ^ $this->int32(($sum ^ $y) + ($k[$p & 3 ^ $e] ^ $z));
			$y = $v[0] = $this->int32($v[0] - $mx);
			$sum = $this->int32($sum - $delta);
		}
		return $this->long2str($v, true);
	}

	function int32($n) {
		while ($n >= 2147483648) $n -= 4294967296;
		while ($n <= -2147483649) $n += 4294967296;
		return (int)$n;
	}

	function UrlEncode($string) {
		$data = base64_encode($string);
		return str_replace(array('+','/','='), array('-','_','.'), $data);
	}

	function UrlDecode($string) {
		$data = str_replace(array('-','_','.'), array('+','/','='), $string);
		return base64_decode($data);
	}
}

// Encrypt
function ew_Encrypt($str, $key = EW_RANDOM_KEY) {
	$tea = new cTEA;
	return $tea->Encrypt($str, $key);
}

// Decrypt
function ew_Decrypt($str, $key = EW_RANDOM_KEY) {
	$tea = new cTEA;
	return $tea->Decrypt($str, $key);
}

// Remove XSS
function ew_RemoveXSS($val) {

	// Remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed 
	// This prevents some character re-spacing such as <java\0script> 
	// Note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs 

	$val = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val); 

	// Straight replacements, the user should never need these since they're normal characters 
	// This prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29> 

	$search = 'abcdefghijklmnopqrstuvwxyz'; 
	$search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
	$search .= '1234567890!@#$%^&*()'; 
	$search .= '~`";:?+/={}[]-_|\'\\'; 
	for ($i = 0; $i < strlen($search); $i++) { 

	   // ;? matches the ;, which is optional 
	   // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars 
	   // &#x0040 @ search for the hex values 

	   $val = preg_replace('/(&#[x|X]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // With a ; 

	   // &#00064 @ 0{0,7} matches '0' zero to seven times 
	   $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // With a ; 
	} 

	// Now the only remaining whitespace attacks are \t, \n, and \r 
	$ra = $GLOBALS["EW_XSS_ARRAY"]; // Note: Customize $EW_XSS_ARRAY in ewcfg*.php
	$found = true; // Keep replacing as long as the previous round replaced something 
	while ($found == true) { 
	   $val_before = $val; 
	   for ($i = 0; $i < sizeof($ra); $i++) { 
	      $pattern = '/'; 
	      for ($j = 0; $j < strlen($ra[$i]); $j++) { 
	         if ($j > 0) { 
	            $pattern .= '('; 
	            $pattern .= '(&#[x|X]0{0,8}([9][a][b]);?)?'; 
	            $pattern .= '|(&#0{0,8}([9][10][13]);?)?'; 
	            $pattern .= ')?'; 
	         } 
	         $pattern .= $ra[$i][$j]; 
	      } 
	      $pattern .= '/i'; 
	      $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // Add in <> to nerf the tag 
	      $val = preg_replace($pattern, $replacement, $val); // Filter out the hex tags 
	      if ($val_before == $val) { 

	         // No replacements were made, so exit the loop 
	         $found = false; 
	      } 
	   } 
	} 
	return $val; 
}

// Check token
function ew_CheckToken($token, $timeout = 0) {
	if ($timeout <= 0)
		$timeout = ew_SessionTimeoutTime();
	return (time() - intval(ew_Decrypt($token))) < $timeout;
}

// Create token
function ew_CreateToken() {
	return ew_Encrypt(time());
}

// HTTP request by cURL
// Note: cURL must be enabled in PHP
function ew_ClientUrl($url, $postdata = "", $method = "GET") {
	if (!function_exists("curl_init"))
		die("cURL not installed.");
	$ch = curl_init();
	$method = strtoupper($method);
	if ($method == "POST") {
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	} elseif ($method == "GET") {
		curl_setopt($ch, CURLOPT_URL, $url . "?" . $postdata);
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$res = curl_exec($ch);
	curl_close($ch);
	return $res;
}

// Set client variable
function ew_SetClientVar($name, $value) {
	global $EW_CLIENT_VAR;
	if (strval($name) <> "")
		$EW_CLIENT_VAR[strval($name)] = $value;
}

// Calculate date difference
function ew_DateDiff($dateTimeBegin, $dateTimeEnd, $interval = "d") {
	$dateTimeBegin = strtotime($dateTimeBegin);
	if ($dateTimeBegin === -1 || $dateTimeBegin === FALSE)
		return FALSE;
	$dateTimeEnd = strtotime($dateTimeEnd);
	if ($dateTimeEnd === -1 || $dateTimeEnd === FALSE)
		return FALSE;
	$dif = $dateTimeEnd - $dateTimeBegin;
	$arBegin = getdate($dateTimeBegin);
	$dateBegin = mktime(0, 0, 0, $arBegin["mon"], $arBegin["mday"], $arBegin["year"]);
	$arEnd = getdate($dateTimeEnd);
	$dateEnd = mktime(0, 0, 0, $arEnd["mon"], $arEnd["mday"], $arEnd["year"]);
	$difDate = $dateEnd - $dateBegin;
	switch ($interval) {
		case "s": // Seconds
			return $dif;
		case "n": // Minutes
			return ($dif > 0) ? floor($dif/60) : ceil($dif/60);
		case "h": // Hours
			return ($dif > 0) ? floor($dif/3600) : ceil($dif/3600);
		case "d": // Days
			return ($difDate > 0) ? floor($difDate/86400) : ceil($difDate/86400);
		case "w": // Weeks
			return ($difDate > 0) ? floor($difDate/604800) : ceil($difDate/604800);
		case "ww": // Calendar weeks
			$difWeek = (($dateEnd - $arEnd["wday"]*86400) - ($dateBegin - $arBegin["wday"]*86400))/604800;
			return ($difWeek > 0) ? floor($difWeek) : ceil($difWeek);
		case "m": // Months
			return (($arEnd["year"]*12 + $arEnd["mon"]) -	($arBegin["year"]*12 + $arBegin["mon"]));
		case "yyyy": // Years
			return ($arEnd["year"] - $arBegin["year"]);
	}
}

// Write global debug message
function ew_DebugMsg() {
	global $gsDebugMsg;
	$msg = preg_replace('/^<br>\n/', "", $gsDebugMsg);
	$gsDebugMsg = "";
	return ($msg <> "") ? "<div class=\"alert alert-info ewAlert\">" . $msg . "</div>" : "";
}

// Write global debug message
function ew_SetDebugMsg($v, $newline = TRUE) {
	global $gsDebugMsg;
	$gsDebugMsg .= $v;
}

// Permission denied message
function ew_DeniedMsg() {
	global $Language;
	return str_replace("%s", ew_CurrentUrl(), $Language->Phrase("NoPermission"));
}

// Init array
function &ew_InitArray($len, $value) {
	if ($len > 0)
		$ar = array_fill(0, $len, $value);
	else
		$ar = array();
	return $ar;
}

// Init 2D array
function &ew_Init2DArray($len1, $len2, $value) {
	return ew_InitArray($len1, ew_InitArray($len2, $value));
}

// Remove elements from array by an array of keys and return the removed elements as array
function ew_Splice(&$ar, $keys) {
	$arkeys = array_fill_keys($keys, 0);
	$res = array_intersect_key($ar, $arkeys);
	$ar = array_diff_key($ar, $arkeys);
	return $res;
}

// Extract elements from array by an array of keys
function ew_Slice(&$ar, $keys) {
	$arkeys = array_fill_keys($keys, 0);
	return array_intersect_key($ar, $arkeys);
}
/**
 * Validation functions
 */

// Check date format
// Format: std/stdshort/us/usshort/euro/euroshort
function ew_CheckDateEx($value, $format, $sep) {
	if (strval($value) == "") return TRUE;
	while (strpos($value, "  ") !== FALSE)
		$value = str_replace("  ", " ", $value);
	$value = trim($value);
	$arDT = explode(" ", $value);
	if (count($arDT) > 0) {
		if (preg_match('/^([0-9]{4})-([0][1-9]|[1][0-2])-([0][1-9]|[1|2][0-9]|[3][0|1])$/', $arDT[0], $matches)) { // Accept yyyy-mm-dd
			$sYear = $matches[1];
			$sMonth = $matches[2];
			$sDay = $matches[3];
		} else {
			$wrksep = "\\$sep";
			switch ($format) {
				case "std":
					$pattern = '/^([0-9]{4})' . $wrksep . '([0]?[1-9]|[1][0-2])' . $wrksep . '([0]?[1-9]|[1|2][0-9]|[3][0|1])$/';
					break;
				case "stdshort":
					$pattern = '/^([0-9]{2})' . $wrksep . '([0]?[1-9]|[1][0-2])' . $wrksep . '([0]?[1-9]|[1|2][0-9]|[3][0|1])$/';
					break;
				case "us":
					$pattern = '/^([0]?[1-9]|[1][0-2])' . $wrksep . '([0]?[1-9]|[1|2][0-9]|[3][0|1])' . $wrksep . '([0-9]{4})$/';
					break;
				case "usshort":
					$pattern = '/^([0]?[1-9]|[1][0-2])' . $wrksep . '([0]?[1-9]|[1|2][0-9]|[3][0|1])' . $wrksep . '([0-9]{2})$/';
					break;
				case "euro":
					$pattern = '/^([0]?[1-9]|[1|2][0-9]|[3][0|1])' . $wrksep . '([0]?[1-9]|[1][0-2])' . $wrksep . '([0-9]{4})$/';
					break;
				case "euroshort":
					$pattern = '/^([0]?[1-9]|[1|2][0-9]|[3][0|1])' . $wrksep . '([0]?[1-9]|[1][0-2])' . $wrksep . '([0-9]{2})$/';
					break;
			}
			if (!preg_match($pattern, $arDT[0])) return FALSE;
			$arD = explode($sep, $arDT[0]); // Change $EW_DATE_SEPARATOR to $sep
			switch ($format) {
				case "std":
				case "stdshort":
					$sYear = ew_UnformatYear($arD[0]);
					$sMonth = $arD[1];
					$sDay = $arD[2];
					break;
				case "us":
				case "usshort":
					$sYear = ew_UnformatYear($arD[2]);
					$sMonth = $arD[0];
					$sDay = $arD[1];
					break;
				case "euro":
				case "euroshort":
					$sYear = ew_UnformatYear($arD[2]);
					$sMonth = $arD[1];
					$sDay = $arD[0];
					break;
			}
		}
		if (!ew_CheckDay($sYear, $sMonth, $sDay)) return FALSE;
	}
	if (count($arDT) > 1 && !ew_CheckTime($arDT[1])) return FALSE;
	return TRUE;
}

// Unformat 2 digit year to 4 digit year
function ew_UnformatYear($yr) {
	if (strlen($yr) == 2) {
		if ($yr > EW_UNFORMAT_YEAR)
			return "19" . $yr;
		else
			return "20" . $yr;
	} else {
		return $yr;
	}
}

// Check Date format (yyyy/mm/dd)
function ew_CheckDate($value) {
	global $EW_DATE_SEPARATOR;
	return ew_CheckDateEx($value, "std", $EW_DATE_SEPARATOR);
}

// Check Date format (yy/mm/dd)
function ew_CheckShortDate($value) {
	global $EW_DATE_SEPARATOR;
	return ew_CheckDateEx($value, "stdshort", $EW_DATE_SEPARATOR);
}

// Check US Date format (mm/dd/yyyy)
function ew_CheckUSDate($value) {
	global $EW_DATE_SEPARATOR;
	return ew_CheckDateEx($value, "us", $EW_DATE_SEPARATOR);
}

// Check US Date format (mm/dd/yy)
function ew_CheckShortUSDate($value) {
	global $EW_DATE_SEPARATOR;
	return ew_CheckDateEx($value, "usshort", $EW_DATE_SEPARATOR);
}

// Check Euro Date format (dd/mm/yyyy)
function ew_CheckEuroDate($value) {
	global $EW_DATE_SEPARATOR;
	return ew_CheckDateEx($value, "euro", $EW_DATE_SEPARATOR);
}

// Check Euro Date format (dd/mm/yy)
function ew_CheckShortEuroDate($value) {
	global $EW_DATE_SEPARATOR;
	return ew_CheckDateEx($value, "euroshort", $EW_DATE_SEPARATOR);
}

// Check default date format
function ew_CheckDateDef($value) {
	global $EW_DATE_FORMAT;
	if (preg_match('/^yyyy/', $EW_DATE_FORMAT))
		return ew_CheckDate($value);
	else if (preg_match('/^yy/', $EW_DATE_FORMAT))
		return ew_CheckShortDate($value);
	else if (preg_match('/^m/', $EW_DATE_FORMAT) && preg_match('/yyyy$/', $EW_DATE_FORMAT))
		return ew_CheckUSDate($value);
	else if (preg_match('/^m/', $EW_DATE_FORMAT) && preg_match('/yy$/', $EW_DATE_FORMAT))
		return ew_CheckShortUSDate($value);
	else if (preg_match('/^d/', $EW_DATE_FORMAT) && preg_match('/yyyy$/', $EW_DATE_FORMAT))
		return ew_CheckEuroDate($value);
	else if (preg_match('/^d/', $EW_DATE_FORMAT) && preg_match('/yy$/', $EW_DATE_FORMAT))
		return ew_CheckShortEuroDate($value);
	return false;
}

// Check day
function ew_CheckDay($checkYear, $checkMonth, $checkDay) {
	$maxDay = 31;
	if ($checkMonth == 4 || $checkMonth == 6 ||	$checkMonth == 9 || $checkMonth == 11) {
		$maxDay = 30;
	} elseif ($checkMonth == 2)	{
		if ($checkYear % 4 > 0) {
			$maxDay = 28;
		} elseif ($checkYear % 100 == 0 && $checkYear % 400 > 0) {
			$maxDay = 28;
		} else {
			$maxDay = 29;
		}
	}
	return ew_CheckRange($checkDay, 1, $maxDay);
}

// Check integer
function ew_CheckInteger($value) {
	global $EW_DECIMAL_POINT;
	if (strval($value) == "") return TRUE;
	if (strpos($value, $EW_DECIMAL_POINT) !== FALSE)
		return FALSE;
	return ew_CheckNumber($value);
}

// Check number
function ew_CheckNumber($value) {
	global $EW_THOUSANDS_SEP, $EW_DECIMAL_POINT;
	if (strval($value) == "") return TRUE;
	$pat = '/^[+-]?(\d{1,3}(' . (($EW_THOUSANDS_SEP) ? '\\' . $EW_THOUSANDS_SEP . '?' : '') . '\d{3})*(\\' .
		$EW_DECIMAL_POINT . '\d+)?|\\' . $EW_DECIMAL_POINT . '\d+)$/';
	return preg_match($pat, $value);
}

// Check range
function ew_CheckRange($value, $min, $max) {
	if (strval($value) == "") return TRUE;
	if (is_int($min) || is_float($min) || is_int($max) || is_float($max)) { // Number
		if (ew_CheckNumber($value))
			$value = floatval(ew_StrToFloat($value));
	}
	if ((!is_null($min) && $value < $min) || (!is_null($max) && $value > $max))
		return FALSE;
	return TRUE;
}

// Check time
function ew_CheckTime($value) {
	global $EW_TIME_SEPARATOR;
	if (strval($value) == "") return TRUE;
	return preg_match('/^(0[0-9]|1[0-9]|2[0-3])' . preg_quote($EW_TIME_SEPARATOR) . '[0-5][0-9](' . preg_quote($EW_TIME_SEPARATOR) . '[0-5][0-9])?$/', $value);
}

// Check US phone number
function ew_CheckPhone($value) {
	if (strval($value) == "") return TRUE;
	return preg_match('/^\(\d{3}\) ?\d{3}( |-)?\d{4}|^\d{3}( |-)?\d{3}( |-)?\d{4}$/', $value);
}

// Check US zip code
function ew_CheckZip($value) {
	if (strval($value) == "") return TRUE;
	return preg_match('/^\d{5}$|^\d{5}-\d{4}$/', $value);
}

// Check credit card
function ew_CheckCreditCard($value, $type="") {
	if (strval($value) == "") return TRUE;
	$creditcard = array("visa" => "/^4\d{3}[ -]?\d{4}[ -]?\d{4}[ -]?\d{4}$/",
		"mastercard" => "/^5[1-5]\d{2}[ -]?\d{4}[ -]?\d{4}[ -]?\d{4}$/",
		"discover" => "/^6011[ -]?\d{4}[ -]?\d{4}[ -]?\d{4}$/",
		"amex" => "/^3[4,7]\d{13}$/",
		"diners" => "/^3[0,6,8]\d{12}$/",
		"bankcard" => "/^5610[ -]?\d{4}[ -]?\d{4}[ -]?\d{4}$/",
		"jcb" => "/^[3088|3096|3112|3158|3337|3528]\d{12}$/",
		"enroute" => "/^[2014|2149]\d{11}$/",
		"switch" => "/^[4903|4911|4936|5641|6333|6759|6334|6767]\d{12}$/");
	if (empty($type))	{
		$match = FALSE;
		foreach ($creditcard as $type => $pattern) {
			if (@preg_match($pattern, $value) == 1) {
				$match = TRUE;
				break;
			}
		}
		return ($match) ? ew_CheckSum($value) : FALSE;
	}	else {
		if (!preg_match($creditcard[strtolower(trim($type))], $value)) return FALSE;
		return ew_CheckSum($value);
	}
}

// Check sum
function ew_CheckSum($value) {
	$value = str_replace(array('-',' '), array('',''), $value);
	$checksum = 0;
	for ($i=(2-(strlen($value) % 2)); $i<=strlen($value); $i+=2)
		$checksum += (int)($value[$i-1]);
  for ($i=(strlen($value)%2)+1; $i <strlen($value); $i+=2) {
	  $digit = (int)($value[$i-1]) * 2;
		$checksum += ($digit < 10) ? $digit : ($digit-9);
  }
	return ($checksum % 10 == 0);
}

// Check US social security number
function ew_CheckSSC($value) {
	if (strval($value) == "") return TRUE;
	return preg_match('/^(?!000)([0-6]\d{2}|7([0-6]\d|7[012]))([ -]?)(?!00)\d\d\3(?!0000)\d{4}$/', $value);
}

// Check emails
function ew_CheckEmailList($value, $email_cnt) {
	if (strval($value) == "") return TRUE;
	$emailList = str_replace(",", ";", $value);
	$arEmails = explode(";", $emailList);
	$cnt = count($arEmails);
	if ($cnt > $email_cnt && $email_cnt > 0)
		return FALSE;
	foreach ($arEmails as $email) {
		if (!ew_CheckEmail($email))
			return FALSE;
	}
	return TRUE;
}

// Check email
function ew_CheckEmail($value) {
	if (strval($value) == "") return TRUE;
	return preg_match('/^[\w.%+-]+@[\w.-]+\.[A-Z]{2,18}$/i', trim($value));
}

// Check GUID
function ew_CheckGUID($value) {
	if (strval($value) == "") return TRUE;
	$p1 = '/^\{\w{8}-\w{4}-\w{4}-\w{4}-\w{12}\}$/';
	$p2 = '/^\w{8}-\w{4}-\w{4}-\w{4}-\w{12}$/';
	return preg_match($p1, $value) || preg_match($p2, $value);
}

// Check file extension
function ew_CheckFileType($value, $exts = EW_UPLOAD_ALLOWED_FILE_EXT) {
	if (strval($value) == "") return TRUE;
	$extension = substr(strtolower(strrchr($value, ".")), 1);
	$allowExt = explode(",", strtolower($exts));
	return (in_array($extension, $allowExt) || trim($exts) == "");
}

// Check empty string
function ew_EmptyStr($value) {
	$str = strval($value);
	$str = str_replace("&nbsp;", "", $str);
	return (trim($str) == "");
}

// Check empty file
function ew_Empty($value) {
	return is_null($value) || strlen($value) == 0;
}

// Check by preg
function ew_CheckByRegEx($value, $pattern) {
	if (strval($value) == "") return TRUE;
	return preg_match($pattern, $value);
}

// Include shared code
include_once "ewshared13.php";
?>
