<?php //echo $this->element('head_frame'); ?>
<script type="text/javascript" src="/assets/js/bootstrap-datetimepicker.min.js"></script>

<div class="container" style='background-color:#fff;border-radius:4px;padding:0px;overflow-y:hidden;width:750px;'>

    <style>
        .table tr, .table td{border:1px solid #000;}
    </style>

    <div  style='padding:0;'>
        <div class="tab-content no-border ">
            <div id="faq-tab-1" class="tab-pane fade in active">
                <form class="form-horizontal" role="form">
                    <table class="table  table-condensed" style="table-layout: fixed;text-align: center;border-color:#000;" >
                        <input type="hidden" name='declarename' class='declarename' value='果树所职工带薪年休假审批单' /> 
                        <tbody>
                            <tr>
                                <td colspan="7" style="font-size:14px;font-weight: 600;border-color:#000;">  果树所职工带薪年休假审批单 </td>
                            </tr>
                            <tr>
                                <td >姓名</td>
                                <td colspan='2'>  <input  type="text" class="applyname" name="applyname"  style='height:25px;width:190px;' readonly="readonly" value="<?php echo $userInfo->name;?>"> </td>
                                <td >所在单位</td>
                                <td colspan='3'>  <input  type="text" class="depname" name="depname"  style='height:25px;width:280px;'> </td>
                            </tr>
                            
                             <tr>
                                <td>参加工作时间</td>
                                <td colspan='2'>  <input readonly="readonly" type="text" class="start_work" name="start_work"  style='height:25px;width:180px;'>  
                                    <script type="text/javascript">
                                        $(".start_work").datetimepicker({
                                            format: 'yyyy-mm-dd',
                                            minView: "month", //选择日期后，不会再跳转去选择时分秒 
                                        });
                                    </script> </td>
                                <td>工作年限</td>
                                <td colspan='3'> <input type="text" class="years" name="years" style='width:200px;height:25px;'/>  </td>
                             </tr>
                             <tr>
                                <td>按规定 <br/> 享受年假天数</td>
                                <td colspan='2' >  <input  type="text" class="vacation_days" name="vacation_days"  style='height:25px;width:180px;margin-top:5px;'> 
                                <td>本年度 <br/> 已休年假天数</td>
                                <td colspan='3'  align="center" valign="middle"> <input type="text" class="yx_vacation_days" name="yx_vacation_days" style='width:200px;height:25px;margin-top:5px;'/>  </td>
                             </tr>                           
                            <tr>
                                <td >休假时间及天数</td>
                                <td colspan='4'>
                                    <input readonly="readonly" type="text" class=" form_datetime1 start_time" name="start_time"  style='height:25px;width:180px;'>  
                                    <script type="text/javascript">
                                        $(".form_datetime1").datetimepicker({
                                            format: 'yyyy-mm-dd',
                                            minView: "month", //选择日期后，不会再跳转去选择时分秒 
                                        });
                                    </script>
                                    至
                                    <input readonly="readonly" type="text" class=" form_datetime2 end_time" name="end_time"  style='height:25px;width:180px;'>  
                                    <script type="text/javascript">
                                        $(".form_datetime2").datetimepicker({
                                            format: 'yyyy-mm-dd',
                                            minView: "month", //选择日期后，不会再跳转去选择时分秒 
                                        });
                                    </script>
                                </td>
                                <td style="width:90px;">合计</td>
                                <td >  <input type="text" name='sum_days' class='sum_days' value='0'  style="width:50px;" readonly="readonly"  />  天 </td>
                            </tr>
                           
                            <tr>
                                <td>个人申请</td>
                                <td colspan='6'> <input type="text" name='personal_apply' class="personal_apply" style='width:600px;height:25px;'/> </td>
                            </tr>
                            <tr>
                                <td style='height:50px;'>所在单位<br/>负责人意见</td>
                                <td  colspan='6' > <textarea  name="leading_opinion" class="leading_opinion" cols='80' rows='2' ></textarea>   </td>
                            </tr>
                            <tr >
                                <td style='height:50px;line-height: 50px;'> 分管所领导意见</td>
                                <td colspan='6'> <textarea  name="leadership_opinion" class="leadership_opinion"  cols='80' rows='2' ></textarea>   </td>
                            </tr>
                            <tr >
                                <td style='height:50px;'> 主管<br/>人事领导意见 </td>
                                <td colspan='6' > <textarea  name="personnel_opinion" class="personnel_opinion"  cols='80' rows='2' ></textarea>   </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </form>
            </div>

            <div class="modal-footer" style='background-color: #fff;'>
                <button style="margin-left:-50px;" type="button" class="btn btn-primary" onclick="window.parent.declares_close();" data-dismiss="modal"> <i class="icon-undo bigger-110"></i> 关闭</button>

                <button type="button" class="btn btn-primary" onclick="approve();"> <i class="icon-ok bigger-110"></i> 保存</button>
                <button type="button" class="btn btn-primary" onclick=""><i class="glyphicon glyphicon-print bigger-110"></i> 打印</button>
            </div>


        </div>
    </div><!-- /.row -->
</div>

<script type="text/javascript">
  
    function approve() {
        var applyname = $('.applyname').val();
        var depname = $('.depname').val();
        var start_work = $('.start_work').val();
        var years = $('.years').val();
        var vacation_days = $('.vacation_days').val();
        var yx_vacation_days = $('.yx_vacation_days').val();
        var start_time = $('.start_time').val();
        var end_time = $('.end_time').val();
        var sum_days = $('.sum_days').val();
        var personal_apply = $('.personal_apply').val();
        var leading_opinion = $('.leading_opinion').val();
        var leadership_opinion = $('.leadership_opinion').val();
        var personnel_opinion = $('.personnel_opinion').val();
        var declarename = $('.declarename').val();
        
        if (applyname == '') {
            $('.applyname').focus();
            return;
        }
        if (depname == '') {
            $('.depname').focus();
            return;
        }
        if (start_work == '') {
            $('.start_work').focus();
            return;
        }
        if (years == '') {
            $('.years').focus();
            return;
        }
        if (vacation_days == '') {
            $('.vacation_days').focus();
            return;
        }
        if (yx_vacation_days == '') {
            $('.yx_vacation_days').focus();
            return;
        }
        if (start_time == '') {
            $('.start_time').focus();
            return;
        }
        if (end_time == '') {
            $('.end_time').focus();
            return;
        }
        if (sum_days == '') {
            $('.sum_days').focus();
            return;
        }
        var data = {};
        data.applyname = applyname;
        data.depname = depname;
        data.start_work = start_work;
        data.years = years;
        data.vacation_days = vacation_days;
        data.yx_vacation_days = yx_vacation_days;
        data.start_time = start_time;
        data.end_time = end_time;
        data.sum_days = sum_days;
        data.personal_apply = personal_apply;
        data.leading_opinion = leading_opinion;
        data.leadership_opinion = leadership_opinion;
        data.personnel_opinion = personnel_opinion;
        data.declarename = declarename;
        $.ajax({
            url: '/RequestNote/gss_furlough',
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

