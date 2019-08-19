<?php

fs_add_assets_header('<link rel="stylesheet" href="'.fs_theme_path().'bower_components/select2/dist/css/select2.min.css">');
fs_add_assets_header('<link rel="stylesheet" href="'.fs_theme_path().'bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">');

fs_add_assets_header('<link rel="stylesheet" href="'.fs_theme_path().'bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">');

fs_add_assets_footer('<script src="'.fs_theme_path().'bower_components/datatables.net/js/jquery.dataTables.min.js"></script>');
fs_add_assets_footer('<script src="'.fs_theme_path().'bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>');
fs_add_assets_footer('<script src="'.fs_theme_path().'bower_components/select2/dist/js/select2.full.min.js"></script>');

$script_footer = $this->load->view('wilayah/script_footer','',TRUE);
fs_add_assets_footer($script_footer);