<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Form K-Means
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?= fs_show_alert() ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Form K-Means</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= form_open('admin/cluster/simpan/',array('method'=>'post','class'=>'form-horizontal')) ?>
                <div class="box-body">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Bulan <b style="color:red">*</b></label>
                            <div class="col-sm-3">
                                <?= form_dropdown('bulan',$data['bulan'],date('m'),array('class'=>'form-control','required'=>'true')) ?>
                            </div>
                            <div class="col-sm-3">
                                <?= form_dropdown('tahun',$data['tahun'],date('Y'),array('class'=>'form-control','required'=>'true')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">C1</label>
                            <div class="col-sm-6">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Temp <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="c1temp" name="c1temp" type="text" class="form-control" placeholder="Temp"
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Pressure <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="c1pres" name="c1pres" type="text" class="form-control" placeholder="Pressure"
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Vol Last Day <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="c1vol" name="c1vol" type="text" class="form-control" placeholder="Vol Last Day"
                                    required>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-sm-6 control-label">C2</label>
                            <div class="col-sm-6">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Temp <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="c2temp" name="c2temp" type="text" class="form-control" placeholder="Temp"
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Pressure <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="c2pres" name="c2pres" type="text" class="form-control" placeholder="Pressure"
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Vol Last Day <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="c2vol" name="c2vol" type="text" class="form-control" placeholder="Vol Last Day"
                                    required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-6 control-label">C3</label>
                            <div class="col-sm-6">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Temp <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="c3temp" name="c3temp" type="text" class="form-control" placeholder="Temp"
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Pressure <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="c3pres" name="c3pres" type="text" class="form-control" placeholder="Pressure"
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Vol Last Day <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="c3vol" name="c3vol" type="text" class="form-control" placeholder="Vol Last Day"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <?= anchor('admin/cluster/','Batal', array('class'=>'btn btn-default')) ?> &nbsp;
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                <!-- /.box-footer -->
                <?= form_close() ?>
            </div>
        </div>
    </div>
</section>