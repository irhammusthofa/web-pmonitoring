<?php

function fs_add_assets_header($assets){
	$CI =& get_instance();
	$tmp = $CI->config->item('fs_assets_header');
	$assets = $CI->config->set_item('fs_assets_header',$tmp.$assets);
}
function fs_add_assets_footer($assets){
	$CI =& get_instance();
	$tmp = $CI->config->item('fs_assets_footer');
	$assets = $CI->config->set_item('fs_assets_footer',$tmp.$assets);
}
function fs_assets_head(){
	$CI =& get_instance();
	$assets = $CI->config->item('fs_assets_header');
	return $assets;
}
function fs_assets_footer(){
	$CI =& get_instance();
	$assets = $CI->config->item('fs_assets_footer');
	return $assets;
}
function fs_title(){
	$CI =& get_instance();
	$title = $CI->config->item('fs_title');
	return $title;
}

function fs_theme_path($filename = ""){
	$CI =& get_instance();
	$config = $CI->config->item('fs_theme_path');
	$config = $config."/";
	if (!($filename == "" || empty($filename || $filename === NULL))) {
		$config = $config."/".$filename;
	}
	return $config;
}
function fs_hash($val){
	return sha1(sha1($val)."#12@5%");
}
function fs_copyright(){
	return '<strong>Copyright &copy; 2019 <a href="#">SEIRA</a>.</strong> All rights
    reserved.';
}
function fs_version(){
	return '1.0.0';
}
function fs_alert_status_user($status){
	switch ($status) {
		case '0':
			return "Akun anda sedang dalam status non-aktif.";
			break;
		case '1':
			return "Akun anda sedang dalam status aktif.";
			break;
		case '2':
			return "Akun anda sedang dalam status blokir.";
			break;
		
		default:
			return "Akun anda sedang dalam status non-aktif.";
			break;
	}
}
function fs_create_alert($config){

	$CI =& get_instance();

	$alert = '<div class="box-body">
	  <div class="alert alert-'.$config["type"].' alert-dismissible">
	    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	    '.$config["message"].'
	  </div>
	</div>';
	$CI->session->set_flashdata('notification',$alert);
}
function fs_show_alert(){
	$CI =& get_instance();
	echo $CI->session->flashdata('notification');
}

function convert_status_paket($status,$skin=false){
	switch ($status) {
		case '0':
			$config['type']	= 'danger';
			$config['text'] = 'Tidak Aktif';
			break;
		case '1':
			$config['type']	= 'success';
			$config['text'] = 'Aktif';
			break;
		case '2':
			$config['type']	= 'danger';
			$config['text'] = 'Sudah Habis';
			break;
		default:
			$config['type']	= 'danger';
			$config['text'] = 'Tidak Aktif';
			break;
	}
	if ($skin){
		return label_skin($config);
	}else{
		return $config['text'];
	}
}
function convert_status_account($status,$skin=false){
	switch ($status) {
		case '0':
			$config['type']	= 'danger';
			$config['text'] = 'Tidak Aktif';
			break;
		case '1':
			$config['type']	= 'success';
			$config['text'] = 'Aktif';
			break;
		case '2':
			$config['type']	= 'warning';
			$config['text'] = 'Blokir';
			break;
		case '3':
			$config['type']	= 'warning';
			$config['text'] = 'Tutup akun';
			break;
		default:
			$config['type']	= 'danger';
			$config['text'] = 'Tidak Aktif';
			break;
	}
	if ($skin){
		return label_skin($config);
	}else{
		return $config['text'];
	}
}

function convert_status_activate($status,$skin=false){
	switch ($status) {
		case '0':
			$config['type']	= 'warning';
			$config['text'] = 'Belum diaktivasi';
			break;
		case '1':
			$config['type']	= 'success';
			$config['text'] = 'Aktif';
			break;
		case '2':
			$config['type']	= 'danger';
			$config['text'] = 'Blokir';
			break;
		case '3':
			$config['type']	= 'danger';
			$config['text'] = 'Tolak Aktivasi';
			break;
		default:
			$config['type']	= 'warning';
			$config['text'] = 'Belum aktivasi';
			break;
	}
	if ($skin){
		return label_skin($config);
	}else{
		return $config['text'];
	}
}
function label_skin($config){
	return '<label class=" label label-'.$config['type'].'">'.$config['text'].'</label>';
}
function convert_jabatan_relawan($id){
	switch ($id) {
		case '0':
			return 'Pemilih';
			break;
		case '1':
			return 'Kordinator TPS';
			break;
		case '2':
			return 'Kordinator Desa';
			break;
		default:
		case '0':
			return 'Tidak terdaftar';
			break;
	}
}
function convert_role($id){
	switch ($id) {
		case '1':
			return 'Operator';
			break;
		case '2':
			return 'Admin';
			break;
		case '3':
			return 'Superadmin';
			break;
		default:
		case '1':
			return 'Operator';
			break;
	}
}
function status_validasi($id,$skin=false){
	switch ($id) {
		case '0':
			$tmp = 'Belum divalidasi';
			if ($skin){
				$tmp = label_skin(['type'=>'warning','text'=>$tmp]);
			}
			return $tmp;
			break;
		case '1':
			$tmp = 'Valid';
			if ($skin){
				$tmp = label_skin(['type'=>'success','text'=>$tmp]);
			}
			return $tmp;
			break;
		case '2':
			$tmp = 'Tidak Valid';
			if ($skin){
				$tmp = label_skin(['type'=>'danger','text'=>$tmp]);
			}
			return $tmp;
			break;
		default:
		case '0':
			$tmp = 'Belum divalidasi';
			if ($skin){
				$tmp = label_skin(['type'=>'warning','text'=>$tmp]);
			}
			return $tmp;
			break;
	}
}

function conv_status_risalah($text,$skin=false){
	$tmp='';
	switch ($text) {
		case '0':
			$tmp = 'Belum selesai';
			$type = 'default';
			break;
		case '1':
			$tmp = 'Sudah selesai';
			$type = 'success';
			break;
		
		default:
			$tmp = 'Belum selesai';
			$type = 'default';
			break;
	}
	if ($skin){
		return label_skin(['type'=>$type,'text'=>$tmp]);
	}else{
		return $tmp;
	}
}
function convertTglID($date,$skin=false){
	$time = strtotime($date);
	$day  = date('d',$time);
	$month  = date('m',$time);
	$year  = date('Y',$time);
	$h  = date('h',$time);
	$m  = date('i',$time);
	$s  = date('s',$time);

	$text = $day.'-'.$month.'-'.$year.' '.$h.':'.$m.':'.$s;
	if ($skin){
		return label_skin(['type'=>'default','text'=>$text]);
	}else{
		return $text;
	}
}
function convertDateID($date,$skin=false){
	$time = strtotime($date);
	$day  = date('d',$time);
	$month  = date('m',$time);
	$year  = date('Y',$time);

	$text = $day.' '.convert_bulan($month).' '.$year;
	if ($skin){
		return label_skin(['type'=>'default','text'=>$text]);
	}else{
		return $text;
	}
}
function tipe_akun($id){
	switch($id){
		case '0': 
			return 'DPR RI';
			break;
		case '1': 
			return 'DPRD PROVINSI';
			break;
		case '2': 
			return 'DPRD KABUPATEN KOTA';
			break;
		default:
			return '-';
			break;
	}
}
function role_akun($id){
	switch($id){
		case '1': 
			return 'Operator';
			break;
		case '2': 
			return 'Admin';
			break;
		case '3': 
			return 'Superadmin';
			break;
		default:
			return '-';
			break;
	}
}
function convert_bulan($bln){
	switch ($bln) {
		case '01':
			return "Januari";
			break;
		case '02':
			return "Februari";
			break;
		case '03':
			return "Maret";
			break;
		case '04':
			return "April";
			break;
		case '05':
			return "Mei";
			break;
		case '06':
			return "Juni";
			break;
		case '07':
			return "Juli";
			break;
		case '08':
			return "Agustus";
			break;
		case '09':
			return "September";
			break;
		case '10':
			return "Oktober";
			break;
		case '11':
			return "November";
			break;
		case '12':
			return "Desember";
			break;
		
		default:
			return "";
			break;
	}
}
function generateCard($date,$name='',$id){
	$numberOfImages = 2;
	$x = 719;
	$y = 509;
	$background = imagecreatetruecolor($x, $y);

	// if ($vip){
	// 	$firstUrl = base_url('assets/img/vip.jpg');
	// }else{
	// 	$firstUrl = base_url('assets/img/reguler.jpg');
	// }
		$firstUrl = base_url('assets/img/sertifikat.jpg');



	$outputImage = $background;

	$first = imagecreatefromjpeg($firstUrl);



	$im = imagecreatetruecolor(400, 30);
	$im_name = imagecreatetruecolor(800, 30);
	// Create some colors
	$white = imagecolorallocate($im, 255, 255, 255);
	$grey = imagecolorallocate($im, 128, 128, 128);
	$black = imagecolorallocate($im, 0, 0, 0);
	imagefilledrectangle($im, 0, 0, 399, 29, $white);

	// The text to draw
	$text = $date;
	// Replace path by your own font path
	$font = 'assets/font/Garogier.ttf';

	// Add the text
	imagettftext($im, 16, 0, 10, 20, $black, $font, $text);

	$white = imagecolorallocate($im_name, 255, 255, 255);
	$grey = imagecolorallocate($im_name, 128, 128, 128);
	$black = imagecolorallocate($im_name, 0, 0, 0);
	imagefilledrectangle($im_name, 0, 0, 399, 29, $white);
	imagettftext($im_name, 16, 0, 10, 20, $black, $font, $name);
	//imagepng($im);

	imagecopymerge($outputImage,$first,0,0,0,0, $x, $y,100);


	imagecopymerge($outputImage,$im,100,360,0,0, 240, 30,100);
	imagecopymerge($outputImage,$im_name,240,250,0,0, 300, 30,100);

	//imagecopymerge(dst_im, src_im, dst_x, dst_y, src_x, src_y, src_w, src_h, pct)
	imagejpeg($outputImage, 'assets/img/'.$id.'.jpg');

	imagedestroy($outputImage);
}