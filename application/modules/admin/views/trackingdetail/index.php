<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?= fs_title() ?>
        <small>Data Tracking Detail</small>
    </h1>
</section>
<!-- Default box -->

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <?= fs_show_alert() ?>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Tanggal Awal </label>
                <input type="text" class="form-control" value="<?= $data['tracking']->tr_tgl ?>" disabled>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Nama Perusahaan </label>
                <input type="text" class="form-control" value="<?= $data['tracking']->s_perusahaan ?>" disabled>
            </div>
        </div>
    </div>
    <input type="text" name="id_tracking" id="id_tracking" value="<?= $data['id_tracking'] ?>" hidden>
    <div class="box">
        <div class="box-header with-border">
        <h3 class="box-title">Data Tracking</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
                    <i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body table-responsive">
            <table id="dtable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Tanggal</th>
                        <th>Vol dikirim</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="box-footer">
            <?= anchor('admin/trackingdetail/add/'.base64_encode($data['id_tracking']),'Tambah',array('class'=>'btn btn-primary')) ?>
        </div>
    </div>
    <!-- /.box -->

</section>