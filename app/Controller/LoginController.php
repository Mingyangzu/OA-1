<?php

App::uses('AppNotLoginController', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		AppNotLogin.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
//此class不用登录就可以用  例如 登录页面
class LoginController extends AppNotLoginController {

    public $name = 'Login';
    public $uses = array('User');
    //所传参数数组
    public $login_data = array('user', 'password');

    public function signin() {       
        $this->layout = 'blank';
        $this->set('title', '登陆');
        $returnMsg = array('result'=>'fail','message'=>'','locate'=>'');  
        //只接受 post
        $login_arr = $this->request->data;
        if ($this->request->isPost() && !empty($login_arr)) {
            $user = $login_arr['user'];
            $password = $login_arr['password'];
            if (empty($user) || empty($password)) {
               // $this->set('error', '用户名/密码为空');
                $returnMsg['message'] = '用户名/密码为空';
            } else {
                //$user_login = $this->User->check_user_pwd($user, md5($password));
                $user_login = $this->User->check_user_pwd($user, $password);

                if (is_array($user_login)) {
                    //记录 session 并跳走
                    $this->User->save_session_oa($user_login);
                    //$this->redirect(array('controller' => 'pages', 'action' => 'display'));
                    $returnMsg['result'] = 'success';
                    $returnMsg['locate'] = '/homes/index';
                } else {
                    //有错误
                   // $this->set('error', '用户名/密码错误');
                    $returnMsg['message'] = '用户名/密码错误';
                }
            }
            exit(json_encode($returnMsg));
        }
        $this->render();
    }

}
