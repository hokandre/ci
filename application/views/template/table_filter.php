<div class="card" style="margin-top: 30px;">
    <div class="card-header">
        <h4> <i class="fas fa-users"></i> Pencapaian Unit Anggota</h4>
    </div>
    <div class="card-body">
        <div class="table-filter">
            <div class="table-topbar">
                <div class="table-topbar-filter">
                    <h4>show : </h4>
                    <select name="numberRow" id="numberRow">
                        <option value="10">10</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div> 
                <div class="table-topbar-filter">
                    <h4>search :</h4> <input id="tableSearch" type="text" value="" placeholder="search..."/>
                </div>
            </div>
            <?=$table;?>
        </div>
    </div>
</div>
<script>
window.onload = function(){
    let tableId = $(".table-filter").find("table").attr("id");
    tablePagination('#' + tableId);
};
</script>
