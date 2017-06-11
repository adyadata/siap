<?php

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
// nama_bank
// nama_rek
// no_rek

?>
<?php if ($pegawai->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $pegawai->TableCaption() ?></h4> -->
<table id="tbl_pegawaimaster" class="table table-bordered table-striped ewViewTable">
<?php echo $pegawai->TableCustomInnerHtml ?>
	<tbody>
<?php if ($pegawai->pegawai_id->Visible) { // pegawai_id ?>
		<tr id="r_pegawai_id">
			<td><?php echo $pegawai->pegawai_id->FldCaption() ?></td>
			<td<?php echo $pegawai->pegawai_id->CellAttributes() ?>>
<span id="el_pegawai_pegawai_id">
<span<?php echo $pegawai->pegawai_id->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->pegawai_pin->Visible) { // pegawai_pin ?>
		<tr id="r_pegawai_pin">
			<td><?php echo $pegawai->pegawai_pin->FldCaption() ?></td>
			<td<?php echo $pegawai->pegawai_pin->CellAttributes() ?>>
<span id="el_pegawai_pegawai_pin">
<span<?php echo $pegawai->pegawai_pin->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_pin->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->pegawai_nip->Visible) { // pegawai_nip ?>
		<tr id="r_pegawai_nip">
			<td><?php echo $pegawai->pegawai_nip->FldCaption() ?></td>
			<td<?php echo $pegawai->pegawai_nip->CellAttributes() ?>>
<span id="el_pegawai_pegawai_nip">
<span<?php echo $pegawai->pegawai_nip->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_nip->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->pegawai_nama->Visible) { // pegawai_nama ?>
		<tr id="r_pegawai_nama">
			<td><?php echo $pegawai->pegawai_nama->FldCaption() ?></td>
			<td<?php echo $pegawai->pegawai_nama->CellAttributes() ?>>
<span id="el_pegawai_pegawai_nama">
<span<?php echo $pegawai->pegawai_nama->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_nama->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->pegawai_pwd->Visible) { // pegawai_pwd ?>
		<tr id="r_pegawai_pwd">
			<td><?php echo $pegawai->pegawai_pwd->FldCaption() ?></td>
			<td<?php echo $pegawai->pegawai_pwd->CellAttributes() ?>>
<span id="el_pegawai_pegawai_pwd">
<span<?php echo $pegawai->pegawai_pwd->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_pwd->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->pegawai_rfid->Visible) { // pegawai_rfid ?>
		<tr id="r_pegawai_rfid">
			<td><?php echo $pegawai->pegawai_rfid->FldCaption() ?></td>
			<td<?php echo $pegawai->pegawai_rfid->CellAttributes() ?>>
<span id="el_pegawai_pegawai_rfid">
<span<?php echo $pegawai->pegawai_rfid->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_rfid->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->pegawai_privilege->Visible) { // pegawai_privilege ?>
		<tr id="r_pegawai_privilege">
			<td><?php echo $pegawai->pegawai_privilege->FldCaption() ?></td>
			<td<?php echo $pegawai->pegawai_privilege->CellAttributes() ?>>
<span id="el_pegawai_pegawai_privilege">
<span<?php echo $pegawai->pegawai_privilege->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_privilege->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->pegawai_telp->Visible) { // pegawai_telp ?>
		<tr id="r_pegawai_telp">
			<td><?php echo $pegawai->pegawai_telp->FldCaption() ?></td>
			<td<?php echo $pegawai->pegawai_telp->CellAttributes() ?>>
<span id="el_pegawai_pegawai_telp">
<span<?php echo $pegawai->pegawai_telp->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_telp->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->pegawai_status->Visible) { // pegawai_status ?>
		<tr id="r_pegawai_status">
			<td><?php echo $pegawai->pegawai_status->FldCaption() ?></td>
			<td<?php echo $pegawai->pegawai_status->CellAttributes() ?>>
<span id="el_pegawai_pegawai_status">
<span<?php echo $pegawai->pegawai_status->ViewAttributes() ?>>
<?php echo $pegawai->pegawai_status->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->tempat_lahir->Visible) { // tempat_lahir ?>
		<tr id="r_tempat_lahir">
			<td><?php echo $pegawai->tempat_lahir->FldCaption() ?></td>
			<td<?php echo $pegawai->tempat_lahir->CellAttributes() ?>>
<span id="el_pegawai_tempat_lahir">
<span<?php echo $pegawai->tempat_lahir->ViewAttributes() ?>>
<?php echo $pegawai->tempat_lahir->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->tgl_lahir->Visible) { // tgl_lahir ?>
		<tr id="r_tgl_lahir">
			<td><?php echo $pegawai->tgl_lahir->FldCaption() ?></td>
			<td<?php echo $pegawai->tgl_lahir->CellAttributes() ?>>
<span id="el_pegawai_tgl_lahir">
<span<?php echo $pegawai->tgl_lahir->ViewAttributes() ?>>
<?php echo $pegawai->tgl_lahir->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->pembagian1_id->Visible) { // pembagian1_id ?>
		<tr id="r_pembagian1_id">
			<td><?php echo $pegawai->pembagian1_id->FldCaption() ?></td>
			<td<?php echo $pegawai->pembagian1_id->CellAttributes() ?>>
<span id="el_pegawai_pembagian1_id">
<span<?php echo $pegawai->pembagian1_id->ViewAttributes() ?>>
<?php echo $pegawai->pembagian1_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->pembagian2_id->Visible) { // pembagian2_id ?>
		<tr id="r_pembagian2_id">
			<td><?php echo $pegawai->pembagian2_id->FldCaption() ?></td>
			<td<?php echo $pegawai->pembagian2_id->CellAttributes() ?>>
<span id="el_pegawai_pembagian2_id">
<span<?php echo $pegawai->pembagian2_id->ViewAttributes() ?>>
<?php echo $pegawai->pembagian2_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->pembagian3_id->Visible) { // pembagian3_id ?>
		<tr id="r_pembagian3_id">
			<td><?php echo $pegawai->pembagian3_id->FldCaption() ?></td>
			<td<?php echo $pegawai->pembagian3_id->CellAttributes() ?>>
<span id="el_pegawai_pembagian3_id">
<span<?php echo $pegawai->pembagian3_id->ViewAttributes() ?>>
<?php echo $pegawai->pembagian3_id->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->tgl_mulai_kerja->Visible) { // tgl_mulai_kerja ?>
		<tr id="r_tgl_mulai_kerja">
			<td><?php echo $pegawai->tgl_mulai_kerja->FldCaption() ?></td>
			<td<?php echo $pegawai->tgl_mulai_kerja->CellAttributes() ?>>
<span id="el_pegawai_tgl_mulai_kerja">
<span<?php echo $pegawai->tgl_mulai_kerja->ViewAttributes() ?>>
<?php echo $pegawai->tgl_mulai_kerja->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->tgl_resign->Visible) { // tgl_resign ?>
		<tr id="r_tgl_resign">
			<td><?php echo $pegawai->tgl_resign->FldCaption() ?></td>
			<td<?php echo $pegawai->tgl_resign->CellAttributes() ?>>
<span id="el_pegawai_tgl_resign">
<span<?php echo $pegawai->tgl_resign->ViewAttributes() ?>>
<?php echo $pegawai->tgl_resign->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->gender->Visible) { // gender ?>
		<tr id="r_gender">
			<td><?php echo $pegawai->gender->FldCaption() ?></td>
			<td<?php echo $pegawai->gender->CellAttributes() ?>>
<span id="el_pegawai_gender">
<span<?php echo $pegawai->gender->ViewAttributes() ?>>
<?php echo $pegawai->gender->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->tgl_masuk_pertama->Visible) { // tgl_masuk_pertama ?>
		<tr id="r_tgl_masuk_pertama">
			<td><?php echo $pegawai->tgl_masuk_pertama->FldCaption() ?></td>
			<td<?php echo $pegawai->tgl_masuk_pertama->CellAttributes() ?>>
<span id="el_pegawai_tgl_masuk_pertama">
<span<?php echo $pegawai->tgl_masuk_pertama->ViewAttributes() ?>>
<?php echo $pegawai->tgl_masuk_pertama->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->photo_path->Visible) { // photo_path ?>
		<tr id="r_photo_path">
			<td><?php echo $pegawai->photo_path->FldCaption() ?></td>
			<td<?php echo $pegawai->photo_path->CellAttributes() ?>>
<span id="el_pegawai_photo_path">
<span<?php echo $pegawai->photo_path->ViewAttributes() ?>>
<?php echo $pegawai->photo_path->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->nama_bank->Visible) { // nama_bank ?>
		<tr id="r_nama_bank">
			<td><?php echo $pegawai->nama_bank->FldCaption() ?></td>
			<td<?php echo $pegawai->nama_bank->CellAttributes() ?>>
<span id="el_pegawai_nama_bank">
<span<?php echo $pegawai->nama_bank->ViewAttributes() ?>>
<?php echo $pegawai->nama_bank->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->nama_rek->Visible) { // nama_rek ?>
		<tr id="r_nama_rek">
			<td><?php echo $pegawai->nama_rek->FldCaption() ?></td>
			<td<?php echo $pegawai->nama_rek->CellAttributes() ?>>
<span id="el_pegawai_nama_rek">
<span<?php echo $pegawai->nama_rek->ViewAttributes() ?>>
<?php echo $pegawai->nama_rek->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($pegawai->no_rek->Visible) { // no_rek ?>
		<tr id="r_no_rek">
			<td><?php echo $pegawai->no_rek->FldCaption() ?></td>
			<td<?php echo $pegawai->no_rek->CellAttributes() ?>>
<span id="el_pegawai_no_rek">
<span<?php echo $pegawai->no_rek->ViewAttributes() ?>>
<?php echo $pegawai->no_rek->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
