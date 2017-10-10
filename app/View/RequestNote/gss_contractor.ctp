
<?php //echo $this->element('head_frame'); ?>
<!--<script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>-->

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:710px;'>

    <style>
        .table tr, .table td{border:1px solid #000;}
    </style>

    <div  style='padding:0;'>
        <div class="tab-content no-border ">
            <div id="faq-tab-1" class="tab-pane fade in active">
                <form class="form-horizontal" enctype="multipart/form-data" action="" method="post" role="form">
                    <table class="table  table-condensed" style="table-layout: fixed;text-align: center;border-color:#000;" >
                        <input type="hidden" name='declarename' class='declarename' value='田间作业包工申请表' /> 
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:24px;font-weight: 600;border-color:#000;">  山西省农科院果树所田间作业包工申请表 </td>
                            </tr>
                            
                             <tr>
                                 <td colspan="5" style="text-align: right;line-height: 25px;"> 编号 </td>
                                <td colspan='2'>  <input type="text" class="number" name="number" value=""  style='height:25px;width:180px;'> </td>
                            </tr>
                            <tr>
                                <td colspan='2' style="height: 45px; line-height: 45px;"> 部门 </td>
                                <td colspan='5' style="height: 45px; line-height: 45px;">
                                    <select style="height:25px;width:440px;" name="dep_pro" class="dep_pro" onchange="">
                                        <?php foreach($team_arr as $v){?>
                                        <option value="<?php echo $v['team']['id'];?>"><?php echo $v['team']['name'];?></option>
                                        <?php }?>
                                    </select>
                                </td>
                            </tr>
                            
                            <tr>
                                <td colspan='2' style="height: 45px; line-height: 45px;"> 包工人员 </td>
                                <td colspan='5' style="height: 45px; line-height: 45px;">
                                    <textarea class="personnel" name="personnel" style="min-width: 440px; max-width: 440px; min-height: 50px; max-height: 50px;"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='2' style="height: 45px; line-height: 45px;"> 包工时间及地点 </td>
                                <td colspan='5' style="height: 45px; line-height: 45px;"> 
                                    <input class="time_address" name="time_address" style="width: 440px;" type="text" value="" /> 
                                </td>
                            </tr>
                            <tr>
                                <td colspan='2' style="height: 45px; line-height: 45px;"> 包工内容及工作量 </td>
                                <td colspan='5' style="height: 45px; line-height: 45px;">
                                    <textarea class="content" name="content" style="min-width: 440px; max-width: 440px; min-height: 50px; max-height: 50px;"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='2' style="height: 45px; line-height: 45px;"> 部门负责人意见 </td>
                                <td colspan='5' style="height: 45px; line-height: 45px;">  </td>
                            </tr>
                            <tr>
                                <td colspan='2' style="height: 45px; line-height: 45px;"> 科研办公室意见 </td>
                                <td colspan='5' style="height: 45px; line-height: 45px;">  </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="modal-footer" style='background-color: #fff;'>
                <button style="margin-left:-50px;" type="button" class="btn btn-primary" onclick="window.parent.declares_close();" data-dismiss="modal"> <i class="icon-undo bigger-110"></i> 关闭</button>

                <button type="button" class="btn btn-primary" onclick="approve();"> <i class="icon-ok bigger-110"></i> 保存</button>
                <button type="button" class="btn btn-primary" onclick="printDIV();"><i class="glyphicon glyphicon-print bigger-110"></i> 打印</button>
            </div>
<script type="text/javascript">
    var class_name = 'not_right_tmp_8888';//定义一个没有的class
function printDIV(){
    $('.modal-footer').css('display', 'none');
    $('#dropzone').css('display', 'none');
    //隐藏下拉框
    $('.' + class_name).css('display', 'none');
    {
        /**
         * navbar-default
            id sidebar 
            breadcrumbs
            ace-settings-container
            id btn-scroll-up
            right_content
         */
        $('.navbar-default').css('display', 'none');
        $('#sidebar').css('display', 'none');
        $('.breadcrumbs').css('display', 'none');
        $('.ace-settings-container').css('display', 'none');
        $('#btn-scroll-up').css('display', 'none');
        $('.right_content').css('display', 'none');
    }
    window.print();//打印刚才新建的网页
    {
        /**
         * navbar-default
            id sidebar 
            breadcrumbs
            ace-settings-container
            id btn-scroll-up
            right_content
         */
        $('.navbar-default').css('display', '');
        $('#sidebar').css('display', '');
        $('.breadcrumbs').css('display', '');
        $('.ace-settings-container').css('display', '');
        $('#btn-scroll-up').css('display', '');
        $('.right_content').css('display', '');
    }
    $('.modal-footer').css('display', '');
    $('#dropzone').css('display', '');
    $('.' + class_name).css('display', '');
    return false;
}
</script>

        </div>
    </div><!-- /.row -->
</div>

<script type="text/javascript">
    function isPositiveInteger(s){//是否为正整数
        var re = /^[0-9]+$/ ;
        return re.test(s)
    }
    $('.nums,.price').blur(function(){
        var nums = $('.nums').val();
        var price = $('.price').val();
        var total = 0;
        if (!isPositiveInteger(nums)) {
            nums = 0;
        }
        if (!Number(price)) {
            price = 0;
        }
        total = parseFloat(nums) * parseFloat(price);
        $('.total').val(parseFloat(total));
    });
    function approve() {
       
        var declarename = $('.declarename').val();
        var number = $('.number').val();
        var dep_pro = $('.dep_pro option:selected').val();
        var personnel = $('.personnel').val();
        var time_address = $('.time_address').val();
        var content = $('.content').val();
        if (number == '') {
            $('.number').focus();
            return;
        }
        if (!dep_pro) {
            $('.dep_pro').focus();
            return;
        }
        if (personnel == '') {
            $('.personnel').focus();
            return;
        }
        if (time_address == '') {
            $('.time_address').focus();
            return;
        }
        if (content == '') {
            $('.content').focus();
            return;
        }
        var data = {};
        data.number = number;
        data.dep_pro = dep_pro;
        data.personnel = personnel;
        data.time_address = time_address;
        data.content = content;
        data.declarename = declarename;
        
        $.ajax({
            url: '/RequestNote/gss_contractor',
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
                    window.parent.declares_close();
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
    //添加错误信息
    function show_error(obj, msg) {
        obj.parent().find('.middle').addClass('text-danger').text(msg);
    }
    //去掉错误信息
    function hide_error(obj) {
        obj.parent().find('.middle').removeClass('text-danger').text('');
    }
    //为input框加事件
    $('input.col-xs-10').keyup(function () {
        if ($(this).val() != '') {
            hide_error($(this));
        }
    });
</script>

<?php echo $this->element('foot_frame'); ?>

