<?php
App::uses('AppController', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 */
class AppController extends Controller {

    public $uses = array('User', 'Department', 'ResearchProject', 'ProjectMember', 'ResearchSource', 'ApplyMain', 'ApplyJiekuandan', 'ApplyLingkuandan', 'ApplyBaoxiaohuizong', 'ApplyChuchaiBxd');
    public $userInfo = array();
    public $appdata = array();
    public $code = 'code'; //返回的状态
    public $msg = 'msg'; //返回信息
    public $res = 'res'; //返回数据
    public $succ_code = '10000'; //审批成功的状态
    //定义不用部门的职务id,如所长，账务科长
    public $not_department_arr = array(
        6, //所长
        11, //账务科长
    );

    public function beforeFilter() {
        parent::beforeFilter();

        if (!$this->User->get_session_oa()) {
            //ajax
            if ($this->request->is('ajax')) {
                echo json_encode(array(
                    $this->code => -1,
                    $this->msg => 'Please login in'
                ));
                exit;
            }
            //普通请求
            $this->redirect(array('controller' => 'login', 'action' => 'signin'));
        }

        $this->userInfo = json_decode(base64_decode($this->User->get_session_oa()));
        $this->set('userInfo', $this->userInfo);

        # 部门列表
        $this->appdata['deplist'] = $this->Department->deplist();
        $this->set('deplist', $this->appdata['deplist']);

        #当前用户所属项目
        $projectId = $this->ProjectMember->find('list', array('conditions' => array('user_id' => $this->userInfo->id), 'fields' => array('project_id')));
        $projectId = empty($projectId) ? array(-1) : array_values($projectId);   //当前用户所属项目
        $this->appdata['projectId'] = $projectId;

        // 所长、财务副所长、财务科长、科研科室主任、科研副所长 显示所有项目
        $pro_conditions = ($this->is_who() != false) ? array('code' => 4) : array('code' => 4, 'id' => $projectId);

        $applyList = $this->ResearchProject->getApplyList($pro_conditions);
        $this->appdata['applyList'] = $applyList;
        $this->set('applyList', $applyList);
        
        //右上角，左边和中间的显示
        TOP_LEFT_MIDDLE:{
            // 左边是我的申请—申请 有几条未审批通过的,意思是说 除去拒绝的和审批通过的
            $user_id = $this->userInfo->id;
            $top_left_arr = $this->ApplyMain->query("select count(*) count from t_apply_main ApplyMain where user_id='{$user_id}' and code !='{$this->succ_code}' and code%2=0 ");
            $top_left_number = $top_left_arr[0][0]['count'];
            
            //中间当前用户待我审批有几条显示几条
            $can_approval = $this->userInfo->can_approval;
            if ($can_approval == 2) {
                //有审批权限
                $sql = "select count(*) count from t_apply_main ApplyMain where ( ";
                $wheresql = ' next_apprly_uid = ' . $user_id;
                $sql .= $wheresql;
                $sql .= " ) and code%2=0  and code !='$this->succ_code'";

                $top_middle_arr = $this->ApplyMain->query($sql);
                $top_middle_number = $top_middle_arr[0][0]['count'];
            } else {
                $top_middle_number = 0;
            }
            
        }
        $this->set('top_left_number', $top_left_number);
        $this->set('top_middle_number', $top_middle_number);
    }

    /**
     * 退出登录
     */
    public function logout() {
        $this->User->del_session_oa();
        $this->redirect(array('controller' => 'login', 'action' => 'signin'));
    }

    /**
     * 当前用户身份权限
     */
    public function is_who() {
        //判断当前用户是 科研办公室 主任3 4、财务科 科长5 11
        switch ($this->userInfo->position_id) {
            case 4: // 科研办公室 主任
                $dempar = $this->Department->find('first', array('conditions' => array('user_id' => $this->userInfo->id, 'id' => 3), 'fields' => array('id')));
                return empty($dempar['Department']['id']) ? false : 'keyanzhuren';
                break;
            case 5: // 科研 副所长
                $dempar = $this->Department->find('first', array('conditions' => array('sld' => $this->userInfo->id, 'id' => 3), 'fields' => array('id')));
                return empty($dempar['Department']['id']) ? false : 'keyanfusuozhang';
                break;
            case 14: // 财务科 科长
                return 'caiwukezhang';
                break;
            case 13: // 财务科 副所长
                return 'caiwufusuozhang';
                break;
            case 6: //  所长
                return 'suozhang';
                break;
            default:
                return false;
        }
    }

    /**
     * 创建表时 根据表名获取审批流，信息
     * @param type $table_name 表名 $type 费用类型 $department_id 部门id
     * @return array();
     */
    public function get_create_approval_process_by_table_name($table_name, $type, $department_id) {
        //code = 0;正常，1异常 msg正常或者错误信息，res返回数据
        $ret_arr = array(
            $this->code => 0,
            $this->msg => '',
            $this->res => array()
        );
        //获取审批流id
        $p_id = Configure::read('approval_process');
        $p_id = $p_id[$table_name];
        $approval_process_arr = $this->ResearchProject->query("select * from t_approval_process approval_process where id='$p_id' limit 1");
        //如果未找到则返回空
        if (!$approval_process_arr) {
            $ret_arr[$this->code] = 1;
            $ret_arr[$this->msg] = '审批流有问题，请联系管理员';
            return $ret_arr;
        }

        $approve_ids = $approval_process_arr[0]['approval_process']['approve_ids'];
        if (!$approve_ids) {
            $ret_arr[$this->code] = 1;
            $ret_arr[$this->msg] = '审批流未设置审批人，请联系管理员';
            return $ret_arr;
        }
        $approve_ids_arr = explode(',', $approve_ids);
        //取出创建人的用户id和角色id和是否有审批权限
        $user_id = $this->userInfo->id;
        $position_id = $this->userInfo->position_id;
        $can_approval = $this->userInfo->can_approval; //是否有审批权限 1,无审批权限，2是有
        $user_department_id = $this->userInfo->department_id; //用户的部门id
        //创建的时间，自己的部门id,就是单子的部门id 所以说，创建时先不用做处理
        //下一位审批人的职务id
        $next_approve_id = $approve_ids_arr[0];
        $approve_code = 0; //第一次
        foreach ($approve_ids_arr as $k => $v) {
            if ($position_id == $v && $can_approval == 2) {
                //先判断有没有下一个审批人，如果没有则说明审批成功,也就说他自己创建了一个不需要审批的单子
                if (!isset($approve_ids_arr[$k + 1])) {
                    $next_approve_id = $user_id; //审批
                    $approve_code = $this->succ_code;
                    break;
                } else {
                    $next_approve_id = $approve_ids_arr[$k + 1];
                    $approve_code = $v * 2;
                }
            } else {
                //如果不是则
                break;
            }
        }
        $ret_arr[$this->res]['next_approver_id'] = $next_approve_id;
        $ret_arr[$this->res]['approve_code'] = $approve_code;
        $ret_arr[$this->res]['approval_process_id'] = $p_id;

        return $ret_arr;
    }

    /**
     * 审批时 根据表名获取审批流，信息
     * @param type $table_name 表名  $type 费用类型  $is_approve 1同意 2拒绝 $department_id单子的部门id
     * @return array();
     */
    public function get_apporve_approval_process_by_table_name($table_name, $type, $is_approve, $department_id) {
        //code = 0;正常，1异常 msg正常或者错误信息，res返回数据
        $ret_arr = array(
            $this->code => 0,
            $this->msg => '',
            $this->res => array()
        );
        //获取审批流id
        $p_id = Configure::read('approval_process');
        $p_id = $p_id[$table_name];
        $approval_process_arr = $this->ResearchProject->query("select * from t_approval_process approval_process where id='$p_id' limit 1");
        //如果未找到则返回空
        if (!$approval_process_arr) {
            $ret_arr[$this->code] = 1;
            $ret_arr[$this->msg] = '审批流有问题，请联系管理员';
            return $ret_arr;
        }

        $approve_ids = $approval_process_arr[0]['approval_process']['approve_ids'];
        if (!$approve_ids) {
            $ret_arr[$this->code] = 1;
            $ret_arr[$this->msg] = '审批流未设置审批人，请联系管理员';
            return $ret_arr;
        }
        //取出创建人的用户id和角色id和是否有审批权限
        $user_id = $this->userInfo->id;
        $position_id = $this->userInfo->position_id;
        $can_approval = $this->userInfo->can_approval; //是否有审批权限 1,无审批权限，2是有
        $user_department_id = $this->userInfo->department_id; //用户部门id
        //判断审批人的部门与单子的部门是否一样，不一样返回错误，但是除去像所长，账务科长的特殊职务
        if (!in_array($position_id, $this->not_department_arr)) {
            //不是特殊职务
            if ($department_id != $user_department_id) {
                //用户的职务与单子的职务不一样，不能审批
                $ret_arr[$this->code] = 1;
                $ret_arr[$this->msg] = '您不能审批其它部门的审批单';
                return $ret_arr;
            }
        }

        $approve_ids_arr = explode(',', $approve_ids);
        if (($index = array_search($position_id, $approve_ids_arr)) === false) {
            //如果不存在，那么说明审批流发生变化
            $ret_arr[$this->code] = 1;
            $ret_arr[$this->msg] = '审批流发生变动，请联系管理员';
            return $ret_arr;
        }

        if ($can_approval != 2) {
            //如果不存在，那么说明审批流发生变化
            $ret_arr[$this->code] = 1;
            $ret_arr[$this->msg] = '您没有审批权限，请联系管理员';
            return $ret_arr;
        }
        if ($is_approve == 1) {
            //审核同意
            if (isset($approve_ids_arr[$index + 1])) {
                //说明还有人要审批
                $next_approve_id = $approve_ids_arr[$index + 1];
                $approve_code = $position_id * 2;
            } else {
                //已经完事了
                $next_approve_id = $user_id; //审批
                $approve_code = $this->succ_code;
            }
        } else {
            //拒绝
            $next_approve_id = $approve_ids_arr[$index];
            $approve_code = $position_id * 2 - 1;
        }

        $ret_arr[$this->res]['next_approver_id'] = $next_approve_id;
        $ret_arr[$this->res]['approve_code'] = $approve_code;
        $ret_arr[$this->res]['approval_process_id'] = $p_id;
        $ret_arr[$this->res]['status'] = $is_approve == 1 ? $is_approve : 2;

        return $ret_arr;
    }

    /**
     * 验证审批单申请是否超过 来源资金剩余金额
     * @param $apply 申请单详情  $source_id 资金来源id
     * @return array();
     */
    public function residual_cost($apply, $source_id) {
        if ($apply['ApplyMain']['type'] == 1) {
            $project_id = $apply['ApplyMain']['project_id'];
            $source = $this->ResearchSource->findById($source_id);
            $source_amount = $source['ResearchSource']['amount']; // 资金来源总额
            $sqlstr = "select sum(m.total) sum_total  from t_apply_main m "
                    . "left join t_apply_baoxiaohuizong h on m.project_id = h.project_id and h.source_id = $source_id and m.attr_id = h.id "
                    . " left join t_apply_chuchai_bxd c on m.project_id = c.project_id and c.source_id = $source_id and m.attr_id = c.id  "
                    . "left join t_apply_jiekuandan j on m.project_id = j.project_id and j.source_id = $source_id and m.attr_id = j.id  "
                    . "left join t_apply_lingkuandan l on m.project_id = l.project_id and j.source_id = $source_id and m.attr_id = l.id  "
                    . " where m.project_id = $project_id and type = 1 and m.code = 10000  and is_calculation = 1 ";
            $amount_sum = $this->ResearchSource->query($sqlstr);
            $total_cost = $amount_sum[0][0]['sum_total'];
            $residual = $source_amount - $total_cost; // 剩余金额
        }

        $feedback = array('code' => 0, 'total' => '', 'msg' => '');
        if ($residual < 0) {
            $feedback['code'] = 1;
            $feedback['total'] = $residual;
            $feedback['msg'] = '该来源资金已超出金额 ' . -$residual . ' 元!';
        } else if ($residual > 0 && $residual < $apply['ApplyMain']['total']) {
            $feedback['code'] = 1;
            $feedback['total'] = $residual;
            $feedback['msg'] = '该来源资金剩余金额 ' . $residual . ' 元，不足申请金额！';
        } else if ($residual > $apply['ApplyMain']['total']) {
            $feedback['total'] = $residual;
            $feedback['msg'] = '该来源资金剩余金额 ' . $residual . ' 元！';
        }
        return $feedback;
    }

    /**
     * 验证审批单申请是否超过 项目总金额
     * @param $apply 申请单详情  $project_sum_count 项目总金额
     * @return array();
     */
    public function residual_project_cost($apply, $project_sum_count) {
        if ($apply['ApplyMain']['type'] == 1) {
            $project_id = $apply['ApplyMain']['project_id'];
            $sumTotal = $this->ResearchSource->query("select sum(total) sum_total from t_apply_main where project_id = $project_id  and type = 1 and code = 10000 and is_calculation = 1 ");
            $residual = $project_sum_count - $sumTotal[0][0]['sum_total']; // 剩余金额
        }

        $feedback = array('code' => 0, 'total' => '', 'msg' => '');
        if ($residual < 0) {
            $feedback['code'] = 1;
            $feedback['total'] = $residual;
            $feedback['msg'] = '该项目已超出总金额 ' . -$residual . ' 元';
        } else if ($residual > 0 && $residual < $apply['ApplyMain']['total']) {
            $feedback['code'] = 1;
            $feedback['total'] = $residual;
            $feedback['msg'] = '该项目剩余总金额 ' . $residual . ' 元，不足申请金额！';
        } else if ($residual > $apply['ApplyMain']['total']) {
            $feedback['total'] = $residual;
            $feedback['msg'] = '该项目剩余总金额 ' . $residual . ' 元';
        }
        return $feedback;
    }

    /**
     * 验证审批单申请 单科目费用 是否超过 项目对应单科目总金额
     * @param $project_id 申请单所选项目  $subject 科目金额
     * @return array();
     */
    public function check_subject_cost($project_id, $subject) {
        $feedback = array('code' => 0, 'total' => '', 'msg' => '');
        //1、项目所包含科目费用
        $project_costArr = $this->ResearchSource->query("select data_fee,collection,facility,material,assay,elding,publish,property_right,office,vehicle,travel,meeting,international,cooperation,labour,consult,indirect_manage,indirect_performance,indirect_other,other,other2,other3  from t_research_cost cost where project_id = $project_id ;");
        if ($project_costArr) {
            $project_costArr = $project_costArr[0]['cost']; // 项目科目费用
            //2、申请单所选科目费用
            //$subject = json_decode($subject,true);
            //3、取所选项目下已申报的科目的总费用
            $costArr = $this->ApplyMain->find('list', array('conditions' => array('project_id' => $project_id, 'code' => 10000, 'is_calculation' => 1, 'total != ' => 0), 'fields' => array('id', 'subject')));
            $subjectArr = array();
            foreach ($costArr as $v) {
                $kemu = json_decode($v, true);
                foreach ($kemu as $k => $v) {
                    $subjectArr[$k] += $v;
                }
            }
            //4、比较单科目是否超额
            foreach ($subject as $k => $v) {
                if(!$project_costArr[$k]){
                   $keyanlist = Configure::read('keyanlist');
                   $kemu_name = '';
                   $feedback['code'] = 1;
                   $feedback['total'] = $v; 
                   $feedback['msg'] = $kemu_name . ' 已超出该科目总额 ' . $feedback['total'] . ' 元';
                   break;
                }else{
                    // 单科目剩余金额
                    $overplus = $project_costArr[$k] - $subjectArr[$k];
                    if ($v > $overplus) {
                        $keyanlist = Configure::read('keyanlist');
                        $kemu_name = '';
                        foreach ($keyanlist as $lk => $lv) {
                            foreach ($lv as $lkey => $lval) {
                                if ($lkey == $k) {
                                    $kemu_name = $lval;
                                    break 2;
                                }
                            }
                        }
                        $feedback['code'] = 1;  
                        $feedback['total'] = $v - $overplus;
                        $feedback['msg'] = $kemu_name . ' 已超出该科目总额 ' . $feedback['total'] . ' 元';
                        break ;
                    }
                }
            }
            
        }else{
            $feedback['code'] = 1;  
            $feedback['total'] = 0;
            $feedback['msg'] = '未找到该项目所包含科目费用！'; 
        }
        return $feedback;
    }



   /**
     * 部门
     * 验证审批单申请是否超过 来源资金剩余金额
     * @param $apply 申请单详情  $source_id 资金来源id
     * @return array();
     */
    public function residual_department($apply, $source_id) {  
        if ($apply['ApplyMain']['type'] == 2) {
            $department_id = $apply['ApplyMain']['department_id'];
            $source = $this->ResearchSource->findById($source_id);
            $source_amount = $source['ResearchSource']['amount']; // 资金来源总额
            $sqlstr = "select sum(m.total) sum_total  from t_apply_main m "
                    . "left join t_apply_baoxiaohuizong h on m.department_id = h.department_id and h.source_id = $source_id and m.attr_id = h.id "
                    . " left join t_apply_chuchai_bxd c on m.department_id = c.department_id and c.source_id = $source_id and m.attr_id = c.id  "
                    . "left join t_apply_jiekuandan j on m.department_id = j.department_id and j.source_id = $source_id and m.attr_id = j.id  "
                    . "left join t_apply_lingkuandan l on m.department_id = l.department_id and j.source_id = $source_id and m.attr_id = l.id  "
                    . " where m.department_id = $department_id and type = 2 and m.code = 10000  and is_calculation = 1 ";
            $amount_sum = $this->ResearchSource->query($sqlstr);
            $total_cost = $amount_sum[0][0]['sum_total'];
            $residual = $source_amount - $total_cost; // 剩余金额
        }

        $feedback = array('code' => 0, 'total' => '', 'msg' => '');
        if ($residual < 0) {
            $feedback['code'] = 1;
            $feedback['total'] = $residual;
            $feedback['msg'] = '部门该来源资金已超出金额 ' . -$residual . ' 元!';
        } else if ($residual > 0 && $residual < $apply['ApplyMain']['total']) {
            $feedback['code'] = 1;
            $feedback['total'] = $residual;
            $feedback['msg'] = '部门该来源资金剩余金额 ' . $residual . ' 元，不足申请金额！';
        } else if ($residual > $apply['ApplyMain']['total']) {
            $feedback['total'] = $residual;
            $feedback['msg'] = '部门该来源资金剩余金额 ' . $residual . ' 元！';
        }
        return $feedback;
    }



     /**
     * 部门
     * 验证审批单申请是否超过 部门资金来源总金额
     * @param $apply 申请单详情  $project_sum_count 项目总金额
     * @return array();
     */
    public function residual_department_cost($apply) { 
        if ($apply['ApplyMain']['type'] == 2) {
            $department_id = $apply['ApplyMain']['department_id'];
            //已申请金额
            $sumTotal = $this->ResearchSource->query("select sum(total) sum_total from t_apply_main where department_id = $department_id  and type = 2 and code = 10000 and is_calculation = 1 ");

            // 部门总资金来源
            $sumSourceAmount = $this->ResearchSource->query("select sum(amount) sum_amount from t_research_source where department_id = $department_id  ");
            // 剩余金额
            $residual = $sumSourceAmount[0][0]['sum_amount'] - $sumTotal[0][0]['sum_total']; 
        }

        $feedback = array('code' => 0, 'total' => '', 'msg' => '');
        if ($residual < 0) {
            $feedback['code'] = 1;
            $feedback['total'] = $residual;
            $feedback['msg'] = '该部门已超出总金额 ' . -$residual . ' 元';
        } else if ($residual > 0 && $residual < $apply['ApplyMain']['total']) {
            $feedback['code'] = 1;
            $feedback['total'] = $residual;
            $feedback['msg'] = '该部门剩余总金额 ' . $residual . ' 元，不足申请金额！';
        } else if ($residual > $apply['ApplyMain']['total']) {
            $feedback['total'] = $residual;
            $feedback['msg'] = '该部门剩余总金额 ' . $residual . ' 元';
        }
        return $feedback;
    }


    /**
     * 获取申请单 和 附属表详情
     * @param $main_id 申请单id  $type 申请单表名
     * @return array();
     */
    public function applyInfos($main_id, $type) {
        $mainInfo = $this->ApplyMain->findById($main_id);
        $mainInfo['ApplyMain']['subject'] = json_decode($mainInfo['ApplyMain']['subject'], true);
        $attrInfo = $this->$type->findById($mainInfo['ApplyMain']['attr_id']);

        $this->set('mainInfo', $mainInfo['ApplyMain']);
        $this->set('attrInfo', $attrInfo[$type]);

        $checkapply = array('mainid' => $mainInfo['ApplyMain']['id'], 'attrid' => $mainInfo['ApplyMain']['attr_id'], 'attrtable' => $type);
        $this->Cookie->write('checkapply', $checkapply, true, '1 day');
        return array('ApplyMain' => $mainInfo['ApplyMain'], $type => $attrInfo[$type]);
    }

}
