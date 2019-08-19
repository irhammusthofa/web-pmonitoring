<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Form Konfigurasi
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?= fs_show_alert() ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Form Konfigurasi</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= form_open('admin/konfigurasi/simpan/'.base64_encode($data['setting']->s_key),array('method'=>'post','class'=>'form-horizontal')) ?>
                <div class="box-body">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Key <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="key" name="key" type="text" class="form-control" placeholder="Key" value="<?= $data['setting']->s_key ?>" 
                                    disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Value <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="value" name="value" type="text" class="form-control" placeholder="Value" value="<?= $data['setting']->s_value ?>" 
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <?= anchor('admin/konfigurasi/','Batal', array('class'=>'btn btn-default')) ?> &nbsp;
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                <!-- /.box-footer -->
                <?= form_close() ?>
            </div>
        </div>
    </div>
</section>