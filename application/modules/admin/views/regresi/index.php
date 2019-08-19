<!-- Content Header (Page header) -->

<section class="content-header">
    <h1>
        <?= fs_title() ?>
        <small>Data Regresi</small>
    </h1>
</section>
<!-- Default box -->

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <?= fs_show_alert() ?>
    <div class="row">
        <?= form_open('admin/regresi',array('method'=>'get')) ?>
        <div class="col-md-3">
            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="bulan" class="form-control" value="<?= $data['bulan'] ?>">

            </div>
        </div>
        <div class="col-md-2"><br>
            <button class="btn btn-primary" type="submit">Tampilkan</button>
        </div>
        <?= form_close() ?>
    </div>
    <div class="box">
        <div class="box-header with-border">
        <h3 class="box-title">Data Regresi</h3>
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
                        <th rowspan="2">No</th>
                        <th rowspan="2">Nama Wilayah</th>
                        <th colspan="2">Pendapatan/bln</th>
                        <?php for($i=0;$i< $data['total_minggu'];$i++){ ?>
                            <th colspan="2">Minggu ke-<?= ($i+1) ?></th>
                        <?php } ?>
                    </tr>
                    <tr>
                        <th>Real</th>
                        <th>Prediksi</th>
                        <?php for($i=0;$i< $data['total_minggu'];$i++){ ?>
                            <th>Real</th>
                            <th>Prediksi</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $a=0; foreach ($data as $item) {
                        if (empty($item['id_wilayah'])) continue;
                        $a++;
                     ?>
                        <tr>
                            <td><?= $a ?></td>
                            <td><?= $item['nama_wilayah'] ?></td>
                            <td><?= $item['barrel'].' MSCF ($'.round($item['barrel']*$item['dollar'],2).')' ?></td>
                            <td><?= $item['prediksi-bulan'].' MSCF ($'.round($item['prediksi-bulan']*$item['dollar'],2).')' ?></td>
                            <?php for($i=0;$i< $data['total_minggu'];$i++){ ?>
                                <td><?= $item['pendapatan-minggu'][$i] ?></td>
                                <td><?= @$item['prediksi'][$i] ?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box -->

</section>