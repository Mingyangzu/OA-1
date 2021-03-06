<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title> 我的申请项目列表 </title>
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

        <div class="main-container-inner">
            <a class="menu-toggler" id="menu-toggler" href="#">
                <span class="menu-text"></span>
            </a>

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
                            <a href="#"> 行政办公 </a>
                        </li>
                        <li class="active"> 我的申请 </li>
                        <li class="active"> 申请 </li>
                    </ul><!-- .breadcrumb -->

                </div>

                <div class="page-content">						
                    <div class="row">
                        <div class="col-xs-12">
                            <!-- PAGE CONTENT BEGINS -->

                            <div class="row">
                                <div class="col-xs-12 right_list">

                                    <div class="table-header">
                                        申请费用信息
                                        <select style="float: right; margin-right: 4%; height: 38px;font-size: 13px;" onchange="change_table(this.value);">
                                            <option value="0">请选择...</option>
                                            <?php foreach(Configure::read('select_apply') as $k=>$v){?>
                                            <option value="<?php echo $k;?>" <?php echo $k==$table ? 'selected':'';?>><?php echo $v;?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                    <script type="text/javascript">
                                        function change_table(table) {
                                            var host = window.location.host;
                                            var url = 'http://'+host+'/office/apply/1/' + table;
                                            window.location.href = url;
                                        }
                                    </script>
                                    <div class="table-responsive">
                                        <table  class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>申请名</th>
                                                    <th>申请时间</th>
                                                    <th>类型</th>
                                                    <th>申请人</th>
                                                    <th>附件</th>
                                                    <th>审核进度</th>
                                                    <th>下一审核人</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php  foreach($lists as $sk => $sv){  
                                                $apply = 'apply';//第二个参数用
						?>
                                                <tr>
                                                    <td><?php echo $sv['ApplyMain']['id'];  ?></td>
                                                    <?php  if($sv['ApplyMain']['table_name']){  ?>
                                                        <td>  <a data-toggle="modal" data-remote='true'   data-target="#modal_wait" href="#" style="text-decoration:none;" onclick="$('#modal-body').load('/office/<?php echo $sv['ApplyMain']['table_name'].'_print/'.$sv['ApplyMain']['id'];?>');"  ><?php echo $sv['ApplyMain']['name'];  ?> </a> </td>
                                                    <?php  }else{   ?>
                                                        <td><?php echo $sv['ApplyMain']['name'];  ?></td>
                                                    <?php }?>
                                                    <td><?php echo $sv['ApplyMain']['ctime'];  ?></td>
                                                    <td><?php echo $sv['ApplyMain']['name']; ?></td>
                                                    <td><?php echo $all_user_arr[$sv['ApplyMain']['user_id']];  ?></td>
                                                    <td>
                                                        <?php 
                                                          if(!empty($sv['ApplyMain']['attachment'])){
                                                            $filearr = array();
                                                            $filearr['url'] = $sv['ApplyMain']['attachment'];
                                                            $filearr['name'] = $sv['ApplyMain']['name'];
                                                            $filearr['uname'] = $all_user_arr[$sv['ApplyMain']['user_id']];
                                                            $filearr['type'] =  $sv['ApplyMain']['table_name'] == 'apply_caigou' ? 'caigou/' : '';
                                                            $filebase = base64_encode( json_encode( $filearr ) );
                                                           ?>
                                                        <a  data-toggle="modal" data-target="#fileModal" onclick="$('#modal-content').load('/office/file_print/<?php echo $filebase;?>');" > 附件 </a>
                                                        <?php   } ?>
                                                        </td>
                                                    <td><?php 
                                                        $new_appprove_code_arr = Configure::read('new_appprove_code_arr');
                                                        $resetchar = '#';
                                                        switch($sv['ApplyMain']['table_name']){ 
                                                            case 'apply_baoxiaohuizong':  
                                                            $resetchar = '/RequestNote/huizongbaoxiao/'; break;
                                                            case 'apply_jiekuandan':
                                                            $resetchar = '/RequestNote/gss_loan/'; break;
                                                            case 'apply_lingkuandan':
                                                            $resetchar = '/RequestNote/gss_draw_money/'; break;
                                                            case 'apply_chuchai_bxd':
                                                            $resetchar = '/RequestNote/gss_evection_expense/'; break;
                                                            case 'apply_leave':
                                                            $resetchar = '/RequestNote/gss_leave/'; break;
                                                            case 'apply_chuchai':
                                                            $resetchar = '/RequestNote/gss_evection/'; break;
                                                            case 'apply_paidleave':
                                                            $resetchar = '/RequestNote/gss_furlough/'; break;
                                                            case 'apply_caigou':
                                                            $resetchar = '/RequestNote/gss_purchase/'; break;
                                                            case 'apply_seal':
                                                            $resetchar = '/RequestNote/gss_seal/'; break;
                                                            case 'apply_received':
                                                            $resetchar = '/RequestNote/gss_received/'; break;
                                                            case 'apply_dispatch':
                                                            $resetchar = '/RequestNote/gss_send/'; break;
                                                            case 'apply_borrow':
                                                            $resetchar = '/RequestNote/gss_borrow/'; break;
                                                            case 'apply_news':
                                                            $resetchar = '/RequestNote/gss_news/'; break;
                                                            case 'apply_request_report':
                                                            $resetchar = '/RequestNote/gss_request_report/'; break;
                                                            
                                                        } 
                                                        if(($sv['ApplyMain']['code'] % 2) == 0){
                                                        echo $new_appprove_code_arr[$sv['ApplyMain']['code']];
                                                        }else{
                                                            
                                                        ?>
                                                          <a data-toggle="modal" data-remote='true'   data-target="#modal_wait" href="#" style="text-decoration:none;" onclick="$('#modal-body').load('<?php echo $resetchar.$sv['ApplyMain']['id'];?>');" ><?php echo $new_appprove_code_arr[$sv['ApplyMain']['code']];  ?> </a>
                                                    <?php   } ?>
                                                          <?php if (($sv['ApplyMain']['code'] == 0 || ($sv['ApplyMain']['code'] != 10000 && !(new OfficeController())->has_approval_information($sv['ApplyMain']['id']))) && in_array($sv['ApplyMain']['table_name'], array('apply_baoxiaohuizong', 'apply_jiekuandan', 'apply_lingkuandan', 'apply_chuchai_bxd', 'apply_request_report'))) {?>
                                                          <a data-toggle="modal" data-remote='true'   data-target="#modal_wait" href="#" style="text-decoration:none;" onclick="$('#modal-body').load('<?php echo $resetchar.$sv['ApplyMain']['id'];?>');" ><?php echo '撤销';//$new_appprove_code_arr[$sv['ApplyMain']['code']];  ?> </a>
                                                          <?php }?>
                                                          <?php if ($sv['ApplyMain']['code'] == 0 || ($sv['ApplyMain']['code'] != 10000 && !(new OfficeController())->has_approval_information($sv['ApplyMain']['id']))) {?>
                                                            <a  onclick="ajax_del_main('<?php echo $sv['ApplyMain']['id'];?>');" ><?php echo '删除';//$new_appprove_code_arr[$sv['ApplyMain']['code']];  ?> </a>
                                                          <?php }?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                        if($sv['ApplyMain']['code'] != 10000 &&($sv['ApplyMain']['code'] % 2) == 0){
                                                        echo $sv['u']['name'];
                                                        }  ?>
                                                    </td>
                                                   
                                                </tr>
                                                <?php   } ?>
                                            </tbody>


                                        </table>
                                    </div>
                                    
                                    <div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content" id='modal-content'> </div>
                                        </div>
                                    </div>

                                    <script type="text/javascript">
                                        $(function(){
                                            $('#modal').on('hidden.bs.modal', function(){
                                                //关闭模态框时，清除数据，防止下次加雷有，缓存
                                                $(this).removeData("bs.modal");
                                            })
                                        });
                                        function declares_close() {
                                            $('#modal_wait').click();
                                        }
                                        //审批
                                        function approve(type) {
                                            var remarks = $('#remarks').val();//备注
                                            if (remarks == '') {
                                                $('#remarks').focus();
                                                return;
                                            }
                                            var text = '拒绝';
                                            if (type == 2) {
                                                text = '同意';
                                            } else {
                                                type = 1;
                                            }
                                            if (!confirm('您确认 ' +text+ ' 该项目？')) {
                                                //取消
                                                return;
                                            }
                                            var data = {p_id: $('#p_id').val(), remarks:remarks, type:type};
                                            $.ajax({
                                                url: '/Office/ajax_approve',
                                                type: 'post',
                                                data: data,
                                                dataType: 'json',
                                                success: function (res) {
                                                    if (res.code == -1) {
                                                        //登录过期
                                                        window.location.href = '/homes/index';
                                                        return;
                                                    }
                                                    if (res.code == -2) {
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
                                                        $('.close').click();
                                                        window.location.reload();
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
                                    
                                    <div class="modal-footer no-margin-top">
                                        <?php echo $this->Page->show($limit, $total, $curpage, 1, "/office/apply/",5 , $table); ?>                                        
                                    </div>
                                </div>
                                   
                               <div class="modal fade" id="modal_wait" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
         <div class="modal-body" id="modal-body"> （-_-)抱歉，申请单加载不出来  </div>
    </div><!-- /.modal -->
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
                        window.jQuery || document.write("<script src='/js/jquery-2.0.3.min.js'>" + "<" + "/script>");
</script>
<!-- <![endif]-->

<!--[if IE]>
<script type="text/javascript">
window.jQuery || document.write("<script src='/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

<script type="text/javascript">
    if ("ontouchend" in document)
        document.write("<script src='/assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
</script>
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
    $('#modal_wait').on('shown.bs.modal', function () {
        
        // 执行一些动作...
        if (typeof chexiao == 'function' ) {
            //汇总报销撤销 等财务4个单子
            chexiao();
        }
        
      })
    jQuery(function ($) {
        var oTable1 = $('#sample-table-2').dataTable({
            "aoColumns": [
                {"bSortable": false},
                null, null, null, null, null,
                {"bSortable": false}
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


    function ajax_del_main(main_id) {
        if (!confirm('您确定要删除此单子?')) {
            return ;
        }
        var data = {main_id: main_id};
        $.ajax({
            url: '/office/ajax_del_main',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.code == -1) {
                    //登录过期
                    window.location.href = '/homes/index';
                    return;
                }
                if (res.code == -2) {
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
                    window.location.reload();
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
    function ajax_recovery(did) {
        if (!did) {
            alert('恢复失败');
            return;
        }

        var data = {did: did};
        $.ajax({
            url: '/user/ajax_recovery',
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.code == -1) {
                    //登录过期
                    window.location.href = '/homes/index';
                    return;
                }
                if (res.code == -2) {
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
                    location.href = '/user/index';
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
    $('#modal').on('hidden.bs.modal', function () {
        //关闭模态框时，清除数据，防止下次加雷有，缓存
        $(this).removeData("bs.modal");
    });
      function wait_close() {
        $('#wait_close').click();
    }

</script>

 <script type="text/javascript">
  show_left_select('office', 'apply', 'shengqing');                              
</script>
</body>
</html>