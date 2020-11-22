<div class="comment-box">
    <hr size="3">
    <h4> <i class="far fa-comment-dots"></i> Catatan :</h4> 
    <?php 
        $comment_array_by_kpi_id = [];
        foreach($formulir->comment as $com ){
            if(isset($comment_array_by_kpi_id[$com->kpi_id])){
                array_push($comment_array_by_kpi_id[$com->kpi_id], $com);
            }else{
                $comment_array_by_kpi_id[$com->kpi_id] = [$com];
            }
        }
    ?>
    <?php $i=0; foreach($comment_array_by_kpi_id as $kpi_id => $row_comment): $i++; ?>
        <div class="comment-row">
            <div class="comment-title">
                <div class="comment-poin">[ <a href="#<?=$kpi_id;?>">Poin - <?=$i;?></a> ] </div>
                <div class="comment-poin-name">:<b> <?= $comment_array_by_kpi_id[$kpi_id][0]->nama_kpi;?> </b></div>
            </div>
            <?php $j=0; foreach($row_comment as $comment):$j++;?>
                <div class="comment-list">
                    <div class="comment-list-left">
                        <p><?= $comment->nama_user;?></p>
                        <p><?= $comment->created_at;?></p>
                    </div>
                    <div class="comment-list-right">
                        <p><?= $comment->isi;?></p>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    <?php endforeach;?>
</div>