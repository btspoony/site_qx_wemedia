<?php if(!empty($page['config'])) :?>
<div class="row">

    <div class="col-sm-6">
        <div class="dataTables_info" id="DataTables_Table_0_info" role="alert" aria-live="polite" aria-relevant="all">
            <?php echo $page['config']['total_rows']; ?>&nbsp;条记录，当前显示第&nbsp;<?php echo $page['config']['this_page']; ?>&nbsp;页
            <!--显示 1 到 10 项，共 57 项-->
        </div>
    </div>
    <div class="col-sm-6">
        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
            <?= $page['page_link']; ?>
            <!--
             <ul class="pagination">
                 <li class="paginate_button previous disabled" aria-controls="DataTables_Table_0" tabindex="0" id="DataTables_Table_0_previous">
                     <a href="#">上一页</a>
                 </li>
                 <li class="paginate_button active" aria-controls="DataTables_Table_0" tabindex="0">
                     <a href="#">1</a>
                 </li>
                 <li class="paginate_button " aria-controls="DataTables_Table_0" tabindex="0">
                     <a href="#">2</a>
                 </li>
                 <li class="paginate_button next" aria-controls="DataTables_Table_0" tabindex="0" id="DataTables_Table_0_next">
                     <a href="#">下一页</a>
                 </li>
             </ul>-->
        </div>
    </div>
</div>
<?php endif;?>