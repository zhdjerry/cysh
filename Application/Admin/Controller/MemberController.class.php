<?php

namespace Admin\Controller;

/**
 * 后台会员管理控制器
 *
 */
class MemberController extends AdminController {

    /**
     * 会员列表
     */
    public function index(){

	    $username       =   I('username');
	    $map['status']  =   array('egt',0);
	    if(is_numeric($username)){
		    $map['id|username']=   array(intval($username),array('like','%'.$username.'%'),'_multi'=>true);
	    }else{
		    $map['username']    =   array('like', '%'.(string)$username.'%');
	    }

	    $list = $this->lists('ucenter_member', $map);
//	    dump($member);
	    int_to_string($list);
	    $this->assign('_list', $list);
	    $this->meta_title = '会员列表';
	    $this->display();
    }

	/**
	 * 会员状态修改
	 */
	public function changeStatus($method=null){
		$id = array_unique((array)I('id',0));
//		if( in_array(C('USER_ADMINISTRATOR'), $id)){
//			$this->error("不允许对超级管理员执行该操作!");
//		}
		$id = is_array($id) ? implode(',',$id) : $id;
		if ( empty($id) ) {
			$this->error('请选择要操作的数据!');
		}
		$map['id'] =   array('in',$id);
		switch ( strtolower($method) ){
			case 'forbiduser':
				$this->forbid('ucenter_member', $map );
				break;
			case 'resumeuser':
				$this->resume('ucenter_member', $map );
				break;
			case 'deleteuser':
				$this->delete('ucenter_member', $map );
				break;
			default:
				$this->error('参数非法');
		}
	}

}
