

<div class="container">
    <?php echo form_open('bidang/add'); ?>
    <div class="form-row">
        <div class="col-lg-9">
            <div class="form-group">
                <input value="<?php echo set_value('nama_bidang'); ?>" type="text" class="<?php echo form_error('nama_bidang') == '' ? "form-control" : "form-control is-invalid";?> " name="nama_bidang" placeholder="masukan nama bidang ..."/>
                <div class="invalid-feedback"><?php echo form_error('nama_bidang');?></div>
            </div>
        </div>
        <div class="col-lg-3">
            <div><input type="submit" class="btn btn-primary" value="Masukan" /></div>
        </div>
    </div>
</div>
