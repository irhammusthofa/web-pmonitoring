<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Form Tracking
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?= fs_show_alert() ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Form Tracking</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= form_open('admin/tracking/detail/simpan/'.base64_encode($data['tracking_detail']->id_tracking).'/'.base64_encode($data['tracking_detail']->td_id),array('method'=>'post','class'=>'form-horizontal')) ?>
                <div class="box-body">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Tanggal <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="tanggal" name="tanggal" type="datetime-local" class="form-control" value="<?= date('Y-m-d\TH:i:s',strtotime($data['tracking_detail']->td_tgl)) ?>" 
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Vol dikirim <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="vol" name="vol" type="text" class="form-control" placeholder="Vol" value="<?= $data['tracking_detail']->td_vol ?>" 
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Status <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="status" name="status" type="text" class="form-control" placeholder="Status" value="<?= $data['tracking_detail']->td_status ?>" 
                                    required>
                            </div>
                        </div>
                       
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <?= anchor('admin/tracking/detail/'.base64_encode($data['tracking_detail']->id_tracking),'Batal', array('class'=>'btn btn-default')) ?> &nbsp;
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                <!-- /.box-footer -->
                <?= form_close() ?>
            </div>
        </div>
    </div>
</section>