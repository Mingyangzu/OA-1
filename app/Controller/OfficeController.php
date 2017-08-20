<?php

App::uses('AppController', 'Controller');
/* 行政办公 */

class OfficeController extends AppController {

    public $name = 'Office';

    public $uses = array('ResearchProject', 'User', 'ResearchCost', 'ResearchSource','ProjectMember', 'ApplyMain', 'ApplyBaoxiaohuizong', 'ApprovalInformation','DepartmentCost');

    public $layout = 'blank';
    public $components = array('Cookie', 'Approval');
    private $ret_arr = array('code' => 1, 'msg' => '', 'class' => '');
    const Table_fix = 't_';//表前缀

    public function index() {
        
    }

    /**
     * 起草申请
     */
    public function draf() {
//var_dump($this->Approval->apply_create(1, $this->userInfo, 8));
        $this->render();
    }

    /**
     * 我的申请 项目
     */
    public function apply_project_list($pages = 1) {
        if ((int) $pages < 1) {
            $pages = 1;
        }
        $limit = 10;
        $total = 0;
        $curpage = 0;
        $all_page = 0;
        $lists = array();
        $total = $this->ResearchProject->query('select count(*) as count from t_research_project where del=0 and  user_id=' . $this->userInfo->id);
        $total = $total[0][0]['count'];
        $userArr = array();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }
            $lists = $this->ResearchProject->getAll(array('del' => 0, 'user_id' => $this->userInfo->id), $limit, $pages);
        }

        $this->set('lists', $lists);
        $this->set('limit', $limit);       //limit      每页显示的条数
        $this->set('total', $total);      //total      总条数       
        $this->set('curpage', $pages);      //curpage    当前页
        $this->set('all_page', $all_page);
        $this->render();
    }

    /**
     * 我的申请 申请
     */
    public function apply($pages = 1) {
        $user_id = $this->userInfo->id;        
//        $type_arr = Configure::read('type_number');

        if ((int) $pages < 1) {
            $pages = 1;
        }
        $limit = 10;
        $total = 0;
        $curpage = 0;
        $all_page = 0;
        $lists = array();
        //有审批权限
        $total =  $this->ApplyMain->query("select count(*) count from t_apply_main ApplyMain where user_id='{$user_id}' ");
        $total = $total[0][0]['count'];
        $all_user_arr = $this->User->get_all_user_id_name();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }
            $lists = $this->ApplyMain->query("select * from t_apply_main ApplyMain where user_id='{$user_id}' order by id desc limit " . ($pages-1) * $limit . ", $limit");
        }
        $this->set('all_user_arr', $all_user_arr);
        $this->set('lists', $lists);
        $this->set('limit', $limit);       //limit      每页显示的条数
        $this->set('total', $total);      //total      总条数       
        $this->set('curpage', $pages);      //curpage    当前页
        $this->set('all_page', $all_page);
        
        $this->render();
    }

    /**
     * 待我审批 项目
     */
    public function wait_approval($pages = 1) {
        if ((int) $pages < 1) {
            $pages = 1;
        }
        $limit = 10;
        $total = 0;
        $curpage = 0;
        $all_page = 0;
        $lists = array();

        // 行政审批 还是财务审批
        $isWho = $this->is_who();
        if ($isWho == 'keyanzhuren') {
            $wherestr = ' and code = 0 ';
            $conditions = array('code' => 0, 'del' => 0);
        } else if ($isWho == 'caiwukezhang') {
            $wherestr = ' and code = 2 ';
            $conditions = array('code ' => 2, 'del' => 0);
        }else{
            $wherestr = ' and code = -1 ';
            $conditions = array( 'code' => '-1', 'del' => 0);
        }

        $total = $this->ResearchProject->query('select count(*) as count from t_research_project where del=0 ' . $wherestr); 
        $total = $total[0][0]['count'];
        $userArr = array();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }
            $lists = $this->ResearchProject->getAll($conditions, $limit, $pages);
        }

        $this->set('lists', $lists);
        $this->set('limit', $limit);       //limit      每页显示的条数
        $this->set('total', $total);      //total      总条数       
        $this->set('curpage', $pages);      //curpage    当前页
        $this->set('all_page', $all_page);
        $this->render();
    }

    /**
     * 待我审批 申请
     */
    public function wait_approval_apply($pages = 1) {
        //取出当前用户职务id以及审批权限
        $position_id = $this->userInfo->position_id;
        $can_approval = $this->userInfo->can_approval;
        $type_arr = Configure::read('type_number');
        $user_department_id = $this->userInfo->department_id;
       
        if ((int) $pages < 1) {
            $pages = 1;
        }
        $limit = 10;
        $total = 0;
        $curpage = 0;
        $all_page = 0;
        $lists = array();
        //在这里得加上部门的验证，如果是特殊职务，如所长，账务科长不验证部门
        $department_str = "department_id ='$user_department_id' ";//默认找不到部门
        $bumensql = ($position_id != 4) ? '': ' and type = 2 ';   // 部门负责人只能审核 行政申请
        if ($can_approval == 2) {
            // 财务副所长、账务科主任 验证所有申请（审批流中包含这两个角色）
            /*
            if (in_array($position_id, $this->not_department_arr)) {
                //所长，账务科长不验证部门
                $department_str = ' 1 ';
            }
            */
            //有审批权限
            $sql = "select count(*) count from t_apply_main ApplyMain where ( " ;
            // 项目负责人审核
            $sql .= "(next_approver_id=11 and project_user_id='{$this->userInfo->id}') "; 
            // 项目组负责人审核
            $sql .= "or (next_approver_id=12 and project_team_user_id='{$this->userInfo->id}') "; 
            // 部门负责人审核
            $sql .= "or (next_approver_id=15 and department_fzr='{$this->userInfo->id}') ";
            // 科研项目由科研主任、科研副所长审核
            if($this->userInfo->department_id == 3){ 
                $sql .= "or (type=1 and next_approver_id='{$this->userInfo->position_id}') ";
            } 
            // 财务科副所长、财务科办公室主任 审核
            if($this->userInfo->position_id == 13 || $this->userInfo->position_id == 14){ 
                $sql .= "or (next_approver_id='{$this->userInfo->position_id}') ";
            }else{
            // 部门申请筛选条件 
                $sql .= " or ({$department_str} and next_approver_id='$position_id' $bumensql ) " ; 
            } 
            $sql .=  ") and code%2=0 and code !='$this->succ_code'";
//echo $sql;
            $total =  $this->ApplyMain->query($sql);  
            $total = $total[0][0]['count'];
        } else {
            //没有审批权限
            $total = 0;
        }
        $all_user_arr = $this->User->get_all_user_id_name();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }
          //  $sql ="select * from t_apply_main ApplyMain where ((next_approver_id=11 and project_user_id='{$this->userInfo->id}') or (next_approver_id=12 and project_team_user_id='{$this->userInfo->id}') or ({$department_str} and next_approver_id='$position_id'))"
          //          . " and {$type_str} and code%2=0 and code !='$this->succ_code'order by id desc limit " . ($pages-1) * $limit . ", $limit";
            $sql = "select * from t_apply_main ApplyMain where ( " ;
            // 项目负责人审核
            $sql .= "(next_approver_id=11 and project_user_id='{$this->userInfo->id}') "; 
            // 项目组负责人审核
            $sql .= "or (next_approver_id=12 and project_team_user_id='{$this->userInfo->id}') "; 
            // 部门负责人审核
            $sql .= "or (next_approver_id=15 and department_fzr='{$this->userInfo->id}') "; 
            // 科研项目由科研主任、科研副所长审核
            if($this->userInfo->department_id == 3){ 
                $sql .= "or (type=1 and next_approver_id='{$this->userInfo->position_id}') ";
            }
            // 财务科副所长、财务科办公室主任 审核
            if($this->userInfo->position_id == 13 || $this->userInfo->position_id == 14){ 
                $sql .= "or (next_approver_id='{$this->userInfo->position_id}') ";
            }else{
            // 部门申请筛选条件 
                $sql .= " or ({$department_str} and next_approver_id='$position_id' $bumensql) " ; 
            }  
            $sql .=  ") and code%2=0 and code !='$this->succ_code' order by id desc limit " . ($pages-1) * $limit . ", $limit";

            $lists = $this->ApplyMain->query($sql);
        }
        $this->set('all_user_arr', $all_user_arr);
        $this->set('lists', $lists);
        $this->set('limit', $limit);       //limit      每页显示的条数
        $this->set('total', $total);      //total      总条数       
        $this->set('curpage', $pages);      //curpage    当前页
        $this->set('all_page', $all_page);
        $this->render();
    }

    /**
     * 经我审批 项目
     */
    public function my_approval($pages = 1) {
        if ((int) $pages < 1) {
            $pages = 1;
        }
        $limit = 10;
        $total = 0;
        $curpage = 0;
        $all_page = 0;
        $lists = array();
        
         // 行政审批 还是财务审批
        $isWho = $this->is_who();
        if ($isWho == 'keyanzhuren') {
            $wherestr = 'project_approver_id';
        } else if ($isWho == 'caiwukezhang') {
            $wherestr = 'financial_approver_id';
        }else{
            $wherestr = 'del';
        }
 
        $total = $this->ResearchProject->query('select count(*) as count from t_research_project where del=0 and '.$wherestr. '=' . $this->userInfo->id);
        $total = $total[0][0]['count'];
        $userArr = array();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }
            $lists = $this->ResearchProject->getAll(array('del' => 0, $wherestr => $this->userInfo->id), $limit, $pages);
        }

        $this->set('lists', $lists);
        $this->set('limit', $limit);       //limit      每页显示的条数
        $this->set('total', $total);      //total      总条数       
        $this->set('curpage', $pages);      //curpage    当前页
        $this->set('all_page', $all_page);
        $this->render();
    }

    /**
     * 经我审批 申请
     */
    public function my_approval_apply($pages = 1) {
        //取出当前用户职务id以及审批权限
        $user_id = $this->userInfo->id;
        $position_id = $this->userInfo->position_id;
        $can_approval = $this->userInfo->can_approval;
        $type_arr = Configure::read('type_number');
        $user_department_id = $this->userInfo->department_id;
       
        if ((int) $pages < 1) {
            $pages = 1;
        }
        $limit = 10;
        $total = 0;
        $curpage = 0;
        $all_page = 0;
        $lists = array();
        
        $total =  $this->ApplyMain->query($sql ="select count(*) count from t_apply_main ApplyMain left join t_approval_information ApprovalInformation on ApplyMain.id=ApprovalInformation.main_id where ApprovalInformation.approve_id='$user_id' "); 
        $total = $total[0][0]['count'];
        $all_user_arr = $this->User->get_all_user_id_name();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }
            $lists = $this->ApplyMain->query("select * from t_apply_main ApplyMain left join t_approval_information ApprovalInformation on ApplyMain.id=ApprovalInformation.main_id where ApprovalInformation.approve_id='$user_id'");
        }
        $this->set('all_user_arr', $all_user_arr);
        $this->set('lists', $lists);
        $this->set('limit', $limit);       //limit      每页显示的条数
        $this->set('total', $total);      //total      总条数       
        $this->set('curpage', $pages);      //curpage    当前页
        $this->set('all_page', $all_page);
        $this->render();
    }

    /**
     * 系统消息
     */
    public function system_message() {
        $this->render();
    }

    /**
     * 审批
     */
    public function ajax_approve() {
        //code 1是审核通过，2是拒绝
        if ($this->request->is('ajax')) {
            $pid = $this->request->data('p_id');
            $remarks = $this->request->data('remarks');
            $type = $this->request->data('type');
            $approve_id = $this->userInfo->id;
            
            $isWho = $this->is_who();  // 当前用户身份
            switch ($isWho){
                case 'keyanzhuren' :  $codestatus = 0 ; break;
                case 'caiwukezhang' : $codestatus = 2 ;  break;
                default :
                     $codestatus = '-1' ; 
            }
            $pinfos = $this->ResearchProject->query('select p.id,u.id,u.user,u.name,u.tel from t_research_project p left join t_user u on p.user_id = u.id  where p.id=' . $pid . ' and p.code='.$codestatus.' and p.del=0');
            if (!$pinfos) {
                //有可能是不存在，也有可能是已经审批
                $this->ret_arr['code'] = 1;
                $this->ret_arr['msg'] = '参数有误，请重新再试';
                echo json_encode($this->ret_arr);
                exit;
            }
            $approveStatus = 0;

            //判断当前用户是 科研办公室 主任3 4、财务科 科长5 11
            if ($isWho == 'keyanzhuren') {
                // 科研办公室 主任
                $save_arr = array(
                    'project_approver_remarks' => $remarks,
                    'project_approver_id' => $approve_id,
                    'code' => $type == 2 ? 2 : 1,
                );
                $approveStatus = $this->ResearchProject->edit($pid, $save_arr);
            } else if ($isWho == 'caiwukezhang') {
                // 财务科 科长
                $save_arr = array(
                    'financial_remarks' => $remarks,
                    'financial_approver_id' => $approve_id,
                    'code' => $type == 2 ? 4 : 3,
                );
                $approveStatus = $this->ResearchProject->edit($pid, $save_arr);

                // 财务科审批通过后  添加申请人为 项目负责人
                if ($approveStatus) {
                    $pMember = array(
                        'user_id' => $pinfos[0]['u']['id'],
                        'project_id' => $pid,
                        'user_name' => $pinfos[0]['u']['user'],
                        'name' => $pinfos[0]['u']['name'],
                        'tel' => $pinfos[0]['u']['tel'],
                        'type' => 1,
                        'ctime' => date('Y-m-d'),
                    );
                    $this->ProjectMember->add($pMember);
                }
            } else {
                $this->ret_arr['code'] = 2;
                $this->ret_arr['msg'] = '无审批权限';
                exit(json_encode($this->ret_arr));
            }

            if ($approveStatus) {
                //成功 
                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = '审批成功';
                echo json_encode($this->ret_arr);
                exit;
            } else {
                //失败
                $this->ret_arr['code'] = 2;
                $this->ret_arr['msg'] = '审批失败';
                echo json_encode($this->ret_arr);
            }
        } else {
            $this->ret_arr['code'] = 1;
            $this->ret_arr['msg'] = '参数有误';
            echo json_encode($this->ret_arr);
        }
        exit;
    }

    /**
     * 审核详情
     */
    public function apply_project($pid) {
        if (empty($pid)) {
            // header("Location:/homes/index");die;
        }
        $this->set('costList', Configure::read('keyanlist'));
        $this->set('pid', $pid);

        $pinfos = $this->ResearchProject->findById($pid);
        $pinfos = @$pinfos['ResearchProject'];
        $source = $this->ResearchSource->getAll($pid);

        $members = $this->ProjectMember->getList($pid);

        $cost = $this->ResearchCost->findByProjectId($pid);
        $cost = @$cost['ResearchCost'];

        $this->set('cost', $cost);

        $this->set('pinfos', $pinfos);
        $this->set('members', $members);
        $this->set('source', $source);

        $this->render();
    }

    /**
     * 报销单待审批
     */
    public function reimbursement($pages = 1) {
        $type = 1;
        //取出当前用户的职务
        $code = $this->get_reimbursement_code();

        if ((int) $pages < 1) {
            $pages = 1;
        }
        $limit = 10;
        $total = 0;
        $curpage = 0;
        $all_page = 0;
        $lists = array();
        $total = $this->ApplyMain->query("select count(*) count from t_apply_main ApplyMain where `type`='$type' and code='$code'");
        $total = $total[0][0]['count'];
        $userArr = array();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }
            $lists = $this->ApplyMain->query("select * from t_apply_main ApplyMain where `type`='$type' and code='$code' limit " . ($pages - 1) . "," . $limit);
        }

        $this->set('lists', $lists);
        $this->set('limit', $limit);       //limit      每页显示的条数
        $this->set('total', $total);      //total      总条数       
        $this->set('curpage', $pages);      //curpage    当前页
        $this->set('all_page', $all_page);
        $this->render();
    }

    /*     * *
     * 根据当前用户的角色 返回报销单所要审批的code 当$filed为true时，返回要更改的字段
     */

    private function get_reimbursement_code($filed = false) {
        //根据当前帐号，返回不同的code
        $position_id = $this->userInfo->position_id;

        $code = -1; //默认取不到数据
        $return_filed = 0; //默认错误
        switch ($position_id) {
            case 11;
                //财务科长
                $code = 12;
                $return_filed = 'financial_officer_id';
                break;
            case 7:
                //财务
                $code = 10;
                $return_filed = 'charge_finance_id';
                break;
            case 6:
                //所长
                $code = 8;
                $return_filed = 'director_id';
                break;
            case 5:
                //分管副所长
                $code = 6;
                $return_filed = 'charge_id';
                break;
            case 4:
                //科室主任
                $code = 4;
                $return_filed = 'head_department_id';
                break;
            case 2:
                //项目负责人
                $code = 0;
                $return_filed = 'project_leader_id';
                break;
            default :
                break;
        }
        if (!$filed) {
            return $code;
        }
        return $return_filed;
    }

    /**
     * 报销单审核详情
     */
    public function apply_project_reimbursement($main_id = 0,$code = '') {
        $this->set('seecode',$code);
        if (empty($main_id)) {
             header("Location:/homes/index");die;
        }
        //根据main_id取出数据
        $main_arr = $this->ApplyMain->findById($main_id);
        $main_arr['ApplyMain']['subject'] = json_decode($main_arr['ApplyMain']['subject'], true);
        $attr_id = @$main_arr['ApplyMain']['attr_id'];
        $attr_arr = $this->ApplyBaoxiaohuizong->findById($attr_id);
        $create_arr = $this->User->findById($main_arr['ApplyMain']['user_id']);
        $this->set('main_arr', @$main_arr['ApplyMain']);
        $this->set('createName', @$create_arr['User']['name']);
        $this->set('attr_arr', @$attr_arr['ApplyBaoxiaohuizong']); 


        // 审核记录
		$applylist = $this->ApprovalInformation->find('all',array('conditions'=>array('main_id'=>$main_arr['ApplyMain']['id']),'fields'=>array('position_id','name','remarks','ctime')));
		
		// 获取部门负责人
		if($main_arr['ApplyMain']['type'] == 2){
			$bmfzr = $this->User->query('select u.name,u.position_id from t_department d left join t_user u on d.user_id = u.id where d.id = '.$main_arr['ApplyMain']['department_id'].' limit 1');
		}

		$applyArr = array();
		foreach($applylist as $k => $v){
			if($v['ApprovalInformation']['position_id'] == $bmfzr[0]['u']['position_id']){
				$applyArr['ksfzr'] =  $v['ApprovalInformation'];
			}else{
				$applyArr[$v['ApprovalInformation']['position_id']] =  $v['ApprovalInformation'];
			}
		}

		$this->set('applyArr', @$applyArr); 



        $kemuStr =  '';
        if($main_arr['ApplyMain']['department_id'] > 0 && $main_arr['ApplyMain']['project_id'] <= 0){ // 部门
            $kemuArr = $this->Department->findById($main_arr['ApplyMain']['department_id']);
            $kemuStr = $kemuArr['Department']['name'];
        }else if($main_arr['ApplyMain']['project_id'] > 0 && $main_arr['ApplyMain']['project_id'] > 0){ // 项目
            $kemuArr = $this->ResearchProject->findById($main_arr['ApplyMain']['project_id']);
            $kemuStr = $kemuArr['ResearchProject']['name'];
            $kemuSourceArr = $this->ResearchSource->findByProjectId($main_arr['ApplyMain']['project_id']);
            $kemuStr .= ' 【'. $kemuSourceArr['ResearchSource']['source_channel'] .' ('.$kemuSourceArr['ResearchSource']['source_channel'] .') '.$kemuSourceArr['ResearchSource']['year'] .'】';            
        }
        $this->set('kemuStr', $kemuStr);  

      //  var_dump($main_arr,$attr_arr,$kemuStr);
        $this->render();
    }

    /**
     * 报销单审批
     */
    public function ajax_approve_reimbursement() {
        //code 1是审核通过，2是拒绝
        if ($this->request->is('ajax')) {
            $main_id = $this->request->data('main_id');
            $remarks = $this->request->data('remarks');
            $status = $this->request->data('type');
            $approve_id = $this->userInfo->id;

            
//            if (!($main_arr = $this->ApplyMain->findById($main_id))) {
//                //有可能是不存在，也有可能是已经审批
//                $this->ret_arr['code'] = 1;
//                $this->ret_arr['msg'] = '参数有误，请重新再试';
//                echo json_encode($this->ret_arr);
//                exit;
//            }

            //查看单子的 next_approve_id 是否和当前用户的职务Id一样，且有审批权限
//            $next_approve_id = $main_arr['ApplyMain']['next_approver_id'];
//            $position_id = $this->userInfo->position_id;
//            $can_approval = $this->userInfo->can_approval;
            //单子下个审批职务id与当前用户职务id不一样，不能审批
//            if ($next_approve_id != $position_id) {
//                $this->ret_arr['code'] = 1;
//                $this->ret_arr['msg'] = '您暂时不能审批该单子，请刷新页面再试';
//                echo json_encode($this->ret_arr);
//                exit;
//            }
            //没有审批权限
//            if ($can_approval != 2) {
//                $this->ret_arr['code'] = 1;
//                $this->ret_arr['msg'] = '您没有审批权限';
//                echo json_encode($this->ret_arr);
//                exit;
//            }
            $ret_arr = $this->Approval->apply($main_id, $this->userInfo, $status);
 // var_dump($ret_arr);   
            if ($ret_arr == false) {
                //说明审批出错
                $this->ret_arr['code'] = 1;
                $this->ret_arr['msg'] = '审批失败';
                echo json_encode($this->ret_arr);
                exit;
            }
//            $ret_arr = $this->get_apporve_approval_process_by_table_name($main_arr['ApplyMain']['table_name'], $main_arr['ApplyMain']['type'], $status, $main_arr['ApplyMain']['department_id']);
            
//            if ($ret_arr[$this->code] == 1) {
//                $this->ret_arr['code'] = 1;
//                $this->ret_arr['msg'] = $ret_arr[$this->msg];
//                echo json_encode($this->ret_arr);
//                exit;
//            }
            //保存主表的数据
            $save_main = array(
                'code' => $ret_arr['code'],
                'next_approver_id' => $ret_arr['next_id']
            );
            //保存审批的数据
            $save_approve = array(
                'main_id' => $main_id,
                'approve_id' => $approve_id,
                'remarks' => !$remarks ? '' : $remarks,
                'name' => $this->userInfo->name,
                'ctime' => date('Y-m-d H:i:s', time()),
                'status' => $status
            );
            //开启事务
            $this->ApplyMain->begin();
            if ($this->ApplyMain->edit($main_id, $save_main)) {
//                if ($this->ApprovalInformation->add($save_approve)) {
                    $this->ApplyMain->commit();
                    //如果审批通过，且跳过下个则在表里记录一下
                    if (isset($ret_arr['code_id'])) {
                        foreach ($ret_arr['code_id'] as $k=>$v) {
                            if ($v == $this->userInfo->id) {
                                $save_approve_log[$k] = array(
                                    'main_id' => $main_id,
                                    'approve_id' => $this->userInfo->id,
                                    'remarks' => !$remarks ? '' : $remarks,
                                    'position_id' => $this->userInfo->position_id,
                                    'name' => $this->userInfo->name,
                                    'ctime' => date('Y-m-d H:i:s', time()),
                                    'status' => 1
                                );
                            } else {
                                //根据id取出当前用户的信息
                                $userinfo = $this->User->findById($v);
                                $save_approve_log[$k] = array(
                                    'main_id' => $main_id,
                                    'approve_id' => $v,
                                    'remarks' => !$remarks ? '' : $remarks,
                                    'position_id' => $userinfo['User']['position_id'],
                                    'name' => $userinfo['User']['name'],
                                    'ctime' => date('Y-m-d H:i:s', time()),
                                    'status' => 1
                                );
                            }  
                        }
                           $this->ApprovalInformation->saveAll($save_approve_log);
                    }
                    //成功
                    $this->ret_arr['code'] = 0;
                    $this->ret_arr['msg'] = '审批成功';
                    echo json_encode($this->ret_arr);
                    exit;
//                }
            }
            $this->ApplyMain->rollback();
            //失败
            $this->ret_arr['code'] = 2;
            $this->ret_arr['msg'] = '审批失败';
            echo json_encode($this->ret_arr);
            exit;
            
        } else {
            $this->ret_arr['code'] = 1;
            $this->ret_arr['msg'] = '参数有误';
            echo json_encode($this->ret_arr);
            exit;
        }
    }

    /**
     * 果树所借款单 打印
     * @param type $main_id 主表id
     * @param type $flag 
     */
    public function gss_loan_print($main_id, $flag='') {
        //根据main_id取数据
        $main_arr = $this->ApplyMain->findById($main_id);
        $table_name = self::Table_fix . $main_arr['ApplyMain']['table_name'];
        $attr_id = $main_arr['ApplyMain']['attr_id'];
        //取附表
        $attr_arr = $this->ApplyMain->query("select *from " . $table_name. " where id=$attr_id");
        //取用户信息
        $user_arr = $this->User->findById($main_arr['ApplyMain']['user_id']);
        //取项目信息
        $projecct_id = $attr_arr[0][$table_name]['project_id'];
        if ($projecct_id) {
            $project_arr = $this->ResearchProject->findById($projecct_id);
            $this->set('project_arr', $project_arr);
            $source_id = $attr_arr[0][$table_name]['source_id'];
            $source_arr = $this->ResearchSource->findById($source_id);
            $this->set('source_arr', $source_arr);
        }
        $this->set('table_name', $table_name);
        $this->set('main_arr', $main_arr);
        $this->set('attr_arr', $attr_arr);
        $this->set('user_arr', $user_arr);
        $this->set('flag', $flag);
        $this->render();
    }
    
     /**
     * 果树所领款单 打印
     * @param type $main_id 主表id
     * @param type $flag 
     */
    public function gss_draw_money_print($main_id, $flag='') {
        //根据main_id取数据
        $main_arr = $this->ApplyMain->findById($main_id);
        $table_name = self::Table_fix . $main_arr['ApplyMain']['table_name'];
        $attr_id = $main_arr['ApplyMain']['attr_id'];
        //取附表
        $attr_arr = $this->ApplyMain->query("select *from " . $table_name. " where id=$attr_id");
        //取用户信息
        $user_arr = $this->User->findById($main_arr['ApplyMain']['user_id']);
        //取项目信息
        $projecct_id = $attr_arr[0][$table_name]['project_id'];
        if ($projecct_id) {
            $project_arr = $this->ResearchProject->findById($projecct_id);
            $this->set('project_arr', $project_arr);
            $source_id = $attr_arr[0][$table_name]['source_id'];
            $source_arr = $this->ResearchSource->findById($source_id);
            $this->set('source_arr', $source_arr);
        }
        $this->set('table_name', $table_name);
        $this->set('main_arr', $main_arr);
        $this->set('attr_arr', $attr_arr);
        $this->set('user_arr', $user_arr);
        $this->set('flag', $flag);
        $this->render();
    }
    
     /**
     * 果树所差旅费报销单 打印
     * @param type $main_id 主表id
     * @param type $flag 
     */
    public function gss_evection_expense_print($main_id, $flag='') {
        //根据main_id取数据
        $main_arr = $this->ApplyMain->findById($main_id);
        $table_name = self::Table_fix . $main_arr['ApplyMain']['table_name'];
        $attr_id = $main_arr['ApplyMain']['attr_id'];
        //取附表
        $attr_arr = $this->ApplyMain->query("select *from " . $table_name. " where id=$attr_id");
        //取用户信息
        $user_arr = $this->User->findById($main_arr['ApplyMain']['user_id']);
        //取项目信息
        $projecct_id = $attr_arr[0][$table_name]['project_id'];
        if ($projecct_id) {
            $project_arr = $this->ResearchProject->findById($projecct_id);
            $this->set('project_arr', $project_arr);
            $source_id = $attr_arr[0][$table_name]['source_id'];
            $source_arr = $this->ResearchSource->findById($source_id);
            $this->set('source_arr', $source_arr);
        }
        $this->set('table_name', $table_name);
        $this->set('main_arr', $main_arr);
        $this->set('attr_arr', $attr_arr);
        $this->set('user_arr', $user_arr);
        $this->set('flag', $flag);
        $this->render();
    }
}
