<ul class="breadcrumb">
    <li>
        <form action="<?=$breadcrumb['url']?>" method="post">
            <input type="hidden" value="<?=$breadcrumb['tahun_crumb'];?>" name="tahun"/>
            <input type="hidden" value="<?=$breadcrumb['institusi_crumb'];?>" name="institusi"/>
            <input type="hidden" value="<?=$breadcrumb['ganjil_crumb']?>" name="ganjil"/>
            <input type="hidden" value="<?=$breadcrumb['genap_crumb']?>" name="genap"/>
            <input type="submit" value="<?=$breadcrumb['name']?>"/>
        </form>
    </li>
</ul>