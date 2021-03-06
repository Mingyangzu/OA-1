<?php

/* *
 *  项目 出入库
 */

App::uses('ApplyMain', 'AppModel');

class ApplyMain extends AppModel {

    public $name = 'ApplyMain';
    public $useTable = 'apply_main';
    public $components = array('Session');

    /**
     * 添加数据
     * @param type $data
     * @return type
     */
    public function add($data) {
        $this->setDataSource('write');
        $this->create();
        $this->save($data);
        return $this->id;
    }

    /**
     * 修改数据
     * @param type $id
     * @param type $data
     * @return type
     */
    public function edit($id, $data) {
        $this->setDataSource('write');
        $this->id = $id;
        return $this->save($data);
    }

    /**
     * 添加数据
     * @param type $data
     * @return type
     */
    public function del($sid) {
        $this->setDataSource('write');
        return $this->deleteAll(array('id' => $sid));
    }

    # 获取项目全部 出入库

    public function getList($pid) {
        $fields = array();
        return $this->find('all', array('conditions' => array('project_id' => $pid, 'code' => 10000), 'fields' => $fields));
    }
    
    # 获取项目全部 费用信息

    public function getSubject($pid) {
        $fields = array('id','subject');
        return $this->find('list', array('conditions' => array('project_id' => $pid,'code'=>10000), 'fields' => $fields));
    }

    
        # 获取项目全部 费用信息 
    public function getSubjectTwo($pid) {
        $fields = array('id','subject','table_name','attr_id');
        return $this->find('all', array('conditions' => array('project_id' => $pid,'code'=>10000,'is_calculation' => 1), 'fields' => $fields));
    }

    # 获取资金来源 剩余金额 
    # $data : array  资金来源id，金额
    public function getSurplus($data) {
        $fields = array('id','total','source_id');
        $surplusArr = $this->find('list', array('conditions' => array('source_id' => $data['sourceId'],'code'=>10000,'is_calculation' => 1), 'fields' => $fields));
        $surplus = $data['amount'];
        if($surplusArr){
            foreach ($surplusArr as $key => $value) {
                foreach ($value as $k => $v) {
                    $surplus[$key] = sprintf("%0.2f",$surplus[$key] - $v);
                }
            }
        } 
        return $surplus;
    }

 
}
