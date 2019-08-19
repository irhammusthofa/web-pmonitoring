<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?= fs_title() ?>
        <small>Data Perusahaan</small>
    </h1>
</section>
<!-- Default box -->

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <?= fs_show_alert() ?>

    <div class="form-group">
        <label >Wilayah</label>
        <?= form_dropdown('wilayah',$data['wilayah'],'',array('id' =>'wilayah', 'class'=>'form-control','onchange'=>'loadtable()')) ?>

    </div>
    <div class="box">
        <div class="box-header with-border">
        <h3 class="box-title">Data Perusahaan</h3>
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
                        <th>Nama Perusahaan</th>
                        <th>Wilayah</th>
                        <th>Kategori</th>
                        <th>Alamat</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="box-footer">
            <?= anchor('admin/perusahaan/add','Tambah',array('class'=>'btn btn-primary')) ?>
        </div>
    </div>
    <!-- /.box -->

</section>