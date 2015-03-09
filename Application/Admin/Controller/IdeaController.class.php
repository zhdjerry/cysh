<?php

namespace Admin\Controller;

/**
 * 后台创意管理控制器
 *
 */
class IdeaController extends AdminController {

	/**
	 * 创意列表
	 */
	public function index() {

		$ideaname = I('ideaname');
		$map['status'] = array('egt', 0);
		if (is_numeric($ideaname)) {
			$map['id|ideaname'] = array(intval($ideaname), array('like', '%' . $ideaname . '%'), '_multi' => TRUE);
		} else {
			$map['ideaname'] = array('like', '%' . (string)$ideaname . '%');
		}

		$list = $this->lists('idea', $map);

		if(!empty($list)){
			$uids = [];
			foreach($list as $v){
				$uids[$v['uid']] = $v['uid'];
				$uids[$v['check_uid']] = $v['check_uid'];
			}
			$map_user['uid'] = array('in', $uids);
			$list_user = $this->lists('member', $map_user);
			//dump($list_user);die;
			$nick_names = [];
			foreach($list_user as $v){
				$nick_names[$v['uid']] = $v['nickname'];
			}
			$map_str['uid'] = $nick_names;
			$map_str['check_uid'] = $nick_names;
		}

		$map_str['status']=array(1=>'正常',-1=>'删除',0=>'禁用',2=>'未审核',3=>'草稿');
		int_to_string($list, $map_str);
		$this->assign('_list', $list);
		$this->meta_title = '创意列表';
		$this->display();
	}

	public function detail(){
		$id = I('id', 0);
		if (empty($id)) {
			$this->error('请选择要查看的数据!');
		}
		$map['id'] = $id;
		$list = $this->lists('idea', $map);
		$row = array_shift($list);
		$this->assign('row', $row);
		$this->meta_title = '创意展示';
		$this->display('detail');
	}

	/**
	 * 创意状态修改
	 */
	public function changeStatus($method = NULL) {
		$id = array_unique((array)I('id', 0));
		$id = is_array($id) ? implode(',', $id) : $id;
		if (empty($id)) {
			$this->error('请选择要操作的数据!');
		}
		$model = 'idea';
		$map['id'] = array('in', $id);
		$data['check_time'] = time();
		$data['check_uid'] = session('user_auth.uid');
		$msg = array('success' => '操作成功！', 'error' => '操作失败！');
		switch (strtolower($method)) {
			case 'forbid':
				$data['status'] = 0;
				$this->editRow( $model , $data, $map, $msg);
				break;
			case 'resume':
				$data['status'] = 1;
				$this->editRow( $model , $data, $map, $msg);
				break;
			case 'delete':
				$this->delete('idea', $map, $msg);
				break;
			default:
				$this->error('参数非法');
		}
	}

}
