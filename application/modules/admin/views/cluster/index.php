<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?= fs_title() ?>
        <small>Data K-Means</small>
    </h1>
</section>
<!-- Default box -->

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <?= fs_show_alert() ?>

    <div class="box">
        <div class="box-header with-border">
        <h3 class="box-title">Data K-Means</h3>
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
                        <th rowspan="2">Action</th>
                        <th rowspan="2">Bulan</th>
                        <th colspan="3">Temp</th>
                        <th colspan="3">Pressure</th>
                        <th colspan="3">Vol Last Day</th>
                    </tr>
                    <tr>
                        <th>C1</th>
                        <th>C2</th>
                        <th>C3</th>
                        <th>C1</th>
                        <th>C2</th>
                        <th>C3</th>
                        <th>C1</th>
                        <th>C2</th>
                        <th>C3</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="box-footer">
            <?= anchor('admin/cluster/add','Tambah',array('class'=>'btn btn-primary')) ?>
        </div>
    </div>
    <!-- /.box -->

</section>