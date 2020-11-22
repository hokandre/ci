<div class="modal" id="<?= $id; ?>" style="<?= isset($style) ? $style : '';?>" >
    <div class="<?= $size; ?>">
        <div class="modal-header">
            <h4> <i class="<?= $icon; ?>"></i> <?= $title; ?></h4>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body text center">
            <?php if(isset($element)) echo $element; ?>
        </div>

        <div class="modal-footer">
            <button class="<?= isset($button_type) ? $button_type : "btn-info"; ?>" id="<?= isset($button_id) ? $button_id :"btn-save";?>">
                <?= isset($button_desc) ? $button_desc : "SAVE";?>
            </button>
        </div>
    </div>
</div>