<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title> 职务管理 </title>
        <meta name="keywords" content="OA" />
        <meta name="description" content="OA" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- basic styles -->
        <link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="/assets/css/font-awesome.min.css" />
        <!--[if IE 7]>
          <link rel="stylesheet" href="/assets/css/font-awesome-ie7.min.css" />
        <![endif]-->
        <!-- page specific plugin styles -->
        <!-- fonts -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300" />
        <!-- ace styles -->
        <link rel="stylesheet" href="/assets/css/ace.min.css" />
        <link rel="stylesheet" href="/assets/css/ace-rtl.min.css" />
        <link rel="stylesheet" href="/assets/css/ace-skins.min.css" />
        <!--[if lte IE 8]>
          <link rel="stylesheet" href="/assets/css/ace-ie.min.css" />
        <![endif]-->
        <!-- inline styles related to this page -->
        <!-- ace settings handler -->
        <script src="/assets/js/ace-extra.min.js"></script>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="/assets/js/html5shiv.js"></script>
        <script src="/assets/js/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        <?php echo $this->element('top'); ?>

        <?php echo $this->element('left'); ?>

        <div class="main-content">
            <div class="breadcrumbs" id="breadcrumbs">
                <script type="text/javascript">
                            try {
                            ace.settings.check('breadcrumbs', 'fixed')
                            } catch (e) {
                    }
                </script>

                <ul class="breadcrumb">
                    <li>
                        <i class="icon-home home-icon"></i>
                        <a href="#">Home</a>
                    </li>

                    <li>
                        <a href="#"> 系统设置 </a>
                    </li>
                    <li class="active"> 职务管理 </li>
                </ul><!-- .breadcrumb -->

            </div>

            <div class="page-content">						
                <div class="row">
                    <div class="col-xs-12">
                        <!-- PAGE CONTENT BEGINS -->

                        <div class="row">
                            <div class="col-xs-12">

                                <div class="table-header">
                                    职务列表信息
                                </div>

                                <div class="table-responsive">
                                    <table id="sample-table-2" class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>职务名</th>
                                                <th>简介</th>
                                                <th class="hidden-480"><i class="icon-time bigger-110 hidden-480"></i>创建时间</th>
                                                <th class="hidden-480">删除</th>
                                                <th class="hidden-480"> 操作 </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php  foreach($posiArr as $v){  ?>
                                            <tr>
                                                <td>
                                                    <a href="#"> <?php  echo $v['pos']['id']; ?> </a>
                                                </td>
                                                <td><?php  echo $v['pos']['name']; ?></td>
                                                <td class="hidden-480"><?php  echo $v['pos']['description']; ?></td>
                                                <td><?php  echo date('Y-m-d H:i',$v['pos']['ctime']); ?></td>
                                                <td><?php  echo $v['pos']['del'] == 0 ? '':' <span class="label label-sm label-warning">删除</span>'; ?></td>
                                                <td>
                                                    <div class="visible-md visible-lg hidden-sm hidden-xs action-buttons">
                                                        <a data-toggle="modal" data-remote='true'   data-target="#modal_wait" href="#" style="text-decoration:none;" onclick="$('#modal-body').load('/position/add/<?php echo $v['pos']['id']; ?>');"  >
                                                            <i class="icon-pencil bigger-130"></i>
                                                        </a>
                                                        <?php  if($v['pos']['del'] == 0) { ?>

                                                        <a class="red" onclick="ajax_del( <?php echo $v['pos']['id']; ?> , 'del');">
                                                            <i class="icon-trash bigger-130"></i>
                                                        </a>
                                                        <?php  }else{ ?>
                                                        <a class="red" onclick="ajax_del( <?php echo $v['pos']['id']; ?> , 'rest');">
                                                            <i class="icon-reply icon-only" alt='取消删除'></i>
                                                        </a>
                                                        <?php  } ?>
                                                    </div>

                                                </td>
                                            </tr>

                                            <?php } ?>

                                    </table>
                                </div>

                                <div class="modal-footer no-margin-top">
                                    <button class="btn btn-sm btn-info pull-left" data-toggle="modal" data-remote='true'   data-target="#modal_wait" href="#" onclick="$('#modal-body').load('/position/add');"  >
                                        <i class="icon-plus"></i>
                                        添加职务
                                    </button>

 
   <div class="modal fade" id="modal_wait" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
         <div class="modal-body" id="modal-body"> （-_-)抱歉，页面加载不出来  </div>
    </div><!-- /.modal -->
</div>

                                    <?php echo $this->Page->show($limit, $total, $curpage, $all_page, "/position/index/",5 ); ?>
                                </div>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- PAGE CONTENT ENDS -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.page-content -->
</div><!-- /.main-content -->


<?php echo $this->element('acebox'); ?>

</div><!-- /.main-container-inner -->



<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
    <i class="icon-double-angle-up icon-only bigger-110"></i>
</a>
</div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->
<script src="/js/jquery-2.0.3.min.js"></script>

<!-- <![endif]-->

<!--[if IE]>
<script src="/js/jquery-1.10.2.min.js"></script>
<![endif]-->

<!--[if !IE]> -->

<script type="text/javascript">
                                                                       window.jQuery || document.write("<script src='/js/jquery-2.0.3.min.js'>" + "<" + "/script>");</script>

<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
window.jQuery || document.write("<script src='/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

<script type="text/javascript">
            if ("ontouchend" in document)
            document.write("<script src='/assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");</script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/typeahead-bs2.min.js"></script>

<!-- page specific plugin scripts -->

<script src="/assets/js/jquery.dataTables.min.js"></script>
<script src="/assets/js/jquery.dataTables.bootstrap.js"></script>

<!-- ace scripts -->

<script src="/assets/js/ace-elements.min.js"></script>
<script src="/assets/js/ace.min.js"></script>

<!-- inline scripts related to this page -->

<script type="text/javascript">
            jQuery(function ($) {
            var oTable1 = $('#sample-table-2').dataTable({
            "aoColumns": [
                    /* {"bSortable": false},
                     null, null, null, null, null,
                     {"bSortable": false}*/
            ]});
                    $('table th input:checkbox').on('click', function () {
            var that = this;
                    $(this).closest('table').find('tr > td:first-child input:checkbox')
                    .each(function () {
                    this.checked = that.checked;
                            $(this).closest('tr').toggleClass('selected');
                    });
            });
                    $('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
                    function tooltip_placement(context, source) {
                    var $source = $(source);
                            var $parent = $source.closest('table')
                            var off1 = $parent.offset();
                            var w1 = $parent.width();
                            var off2 = $source.offset();
                            var w2 = $source.width();
                            if (parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2))
                            return 'right';
                            return 'left';
                    }
            })
</script>


<script>
            function ajax_del(did, status) {
            if (!did) {
            alert('删除失败');
                    return;
            }

            var data = {did: did, status:status};
                    $.ajax({
                    url: '/position/ajax_del',
                            type: 'post',
                            data: data,
                            dataType: 'json',
                            success: function (res) {
                            if (res.code == - 1) {
                            //登录过期
                            window.location.href = '/homes/index';
                                    return;
                            }
                            if (res.code == - 2) {
                            //权限不足
                            alert('权限不足');
                                    return;
                            }
                            if (res.code == 1) {
                            //说明有错误
                            alert(res.msg);
                                    return;
                            }
                            if (res.code == 0) {
                            //说明添加或修改成功
                            location.href = location.pathname;
                                    return;
                            }
                            if (res.code == 2) {
                            //失败
                            alert(res.msg);
                                    return;
                            }
                            }
                    });
            }
</script>


</body>
</html>
<script type="text/javascript">
    $('#modal').on('hidden.bs.modal', function(){
    //关闭模态框时，清除数据，防止下次加雷有，缓存
    $(this).removeData("bs.modal");
    });
            show_left_select('system_set', 'set_position');
</script>