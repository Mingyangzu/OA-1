<?php

/**
 * 科研项目
 */
App::uses('Fixedassets', 'AppModel');

class Fixedassets extends AppModel {

    public $name = 'Fixedassets';
    public $useTable = 'fixed_assets';
    public $is_del = 1; //删除
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

    # 获取全部项目

    public function getList() {
        $userArr = $fields = array();
        $fields = array('id', 'name');
        return $this->find('list', array('conditions' => array('del' => 0), 'fields' => $fields));
    }

    # 获取全部项目

    public function getAll($conditions = array(), $limit = 0, $page = 0) {
        $screen = array();
        $conditions['del'] = 0;
        $screen['conditions'] = $conditions;
        $limit && $screen['limit'] = $limit;
        $page && $screen['page'] = $page;
        return $this->find('all', $screen);
    }

}
