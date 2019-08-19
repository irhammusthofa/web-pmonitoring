<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?= fs_title() ?>
        <small>K-Means</small>
    </h1>
</section>
<!-- Default box -->

<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <?= fs_show_alert() ?>
    <?php for($iterasi=0;$iterasi<count($data['iterasi']);$iterasi++){ ?>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Iterasi <?= $data['iterasi'][$iterasi]['no'] ?></h3>
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
                        <th></th>
                        <th>Temp</th>
                        <th>Pressure</th>
                        <th>Vol Last Day</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i=0; $i < 3 ; $i++) {
                        $ftemp = 'cl_c'.($i+1).'temp';
                        $fpres = 'cl_c'.($i+1).'pres';
                        $fvol = 'cl_c'.($i+1).'vol';
                    ?>
                        <tr>
                            <td>C<?= $i+1 ?></td>
                            <td><?= round($data['iterasi'][$iterasi][$ftemp],2) ?></td>
                            <td><?= round($data['iterasi'][$iterasi][$fpres],2) ?></td>
                            <td><?= round($data['iterasi'][$iterasi][$fvol],2) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table><br>
            <table id="dtable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Perusahaan</th>
                        <th>Temp</th>
                        <th>Pressure</th>
                        <th>Vol Last Day</th>
                        <th>DC1</th>
                        <th>DC2</th>
                        <th>DC3</th>
                        <th>C1</th>
                        <th>C2</th>
                        <th>C3</th>
                        <?php if($iterasi == count($data['iterasi']) - 1) { ?>
                        <th>Status</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0;foreach ($data['iterasi'][$iterasi]['data'] as $item) { $i++; ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= $item->s_perusahaan ?></td>
                            <td><?= round($item->temp,3) ?></td>
                            <td><?= round($item->pressure,3) ?></td>
                            <td><?= round($item->vol_last_day,3) ?></td>
                            <td><?= round($item->dc1,3) ?></td>
                            <td><?= round($item->dc2,3) ?></td>
                            <td><?= round($item->dc3,3) ?></td>
                            <td><?= ($item->c1=='X') ? label_skin(['type'=>'danger','text'=>'X']) : '' ?></td>
                            <td><?= ($item->c2=='X') ? label_skin(['type'=>'danger','text'=>'X']) : '' ?></td>
                            <td><?= ($item->c3=='X') ? label_skin(['type'=>'danger','text'=>'X']) : '' ?></td>

                            <?php if($iterasi == count($data['iterasi']) - 1) {
                                if($item->c1=='X'){
                                    echo '<td>Under Nomination</td>';
                                }else if($item->c2=='X'){
                                    echo '<td>Normal</td>';
                                }else{
                                    echo '<td>Over Nomination</td>';
                                }
                            } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php } ?>
    <!-- /.box -->

</section>