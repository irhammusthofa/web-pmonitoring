<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Form Offtaker
    </h1>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?= fs_show_alert() ?>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Form Offtaker</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <?= form_open('admin/offtaker/simpan/'.base64_encode($data['shipper']->no),array('method'=>'post','class'=>'form-horizontal')) ?>
                <div class="box-body">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Perusahaan <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <?= form_dropdown('perusahaan',$data['perusahaan'],$data['shipper']->id_shipper,array('class'=>'form-control','required'=>'true')) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Tanggal <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="tanggal" name="tanggal" type="date" class="form-control" placeholder="Tanggal" value="<?= $data['shipper']->tgl ?>" 
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Normal <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="normal" name="normal" type="text" class="form-control" placeholder="Normal" value="<?= $data['shipper']->normal ?>" 
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Dp <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="dp" name="dp" type="text" class="form-control" placeholder="DP" value="<?= $data['shipper']->dp ?>" 
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Temp <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="temp" name="temp" type="text" class="form-control" placeholder="Temp" value="<?= $data['shipper']->temp ?>" 
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Pressure <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="pressure" name="pressure" type="text" class="form-control" placeholder="Pressure" value="<?= $data['shipper']->pressure ?>" 
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Vol Last Hour <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="vol_last_hour" name="vol_last_hour" type="text" class="form-control" placeholder="Vol Last Hour " value="<?= $data['shipper']->vol_last_hour ?>" 
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Vol Last Day <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="vol_last_day" name="vol_last_day" type="text" class="form-control" placeholder="Vol Last Day " value="<?= $data['shipper']->vol_last_day ?>" 
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Flow Rate <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="flow_rate" name="flow_rate" type="text" class="form-control" placeholder="Flow Rate" value="<?= $data['shipper']->flow_rate ?>" 
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Comment <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="comment" name="comment" type="text" class="form-control" placeholder="Comment" value="<?= $data['shipper']->comment ?>" 
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-6 control-label">Diff <b style="color:red">*</b></label>
                            <div class="col-sm-6">
                                <input id="diff" name="diff" type="text" class="form-control" placeholder="Diff" value="<?= $data['shipper']->diff ?>" 
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="pull-right">
                        <?= anchor('admin/offtaker/','Batal', array('class'=>'btn btn-default')) ?> &nbsp;
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
                <!-- /.box-footer -->
                <?= form_close() ?>
            </div>
        </div>
    </div>
</section>