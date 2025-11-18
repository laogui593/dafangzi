<?php
namespace app\index\controller;

use think\Db;
use think\Controller;

class Kefu extends Controller
{
    // 发送消息
    public function sendMessage()
    {
        $uid = input('uid');
        $message = input('message');
        
        if (!$uid || !$message) {
            return json(['code' => 0, 'msg' => '参数错误']);
        }
        
        $data = [
            'uid' => $uid,
            'message' => $message,
            'type' => 'user', // user=用户发送, service=客服回复
            'is_read' => 0,
            'create_time' => date('Y-m-d H:i:s')
        ];
        
        $result = Db::name('LcKefuMsg')->insert($data);
        
        if ($result) {
            return json(['code' => 1, 'msg' => '发送成功']);
        } else {
            return json(['code' => 0, 'msg' => '发送失败']);
        }
    }
    
    // 获取聊天记录
    public function getMessages()
    {
        $uid = input('uid');
        
        if (!$uid) {
            return json(['code' => 0, 'msg' => '参数错误']);
        }
        
        $messages = Db::name('LcKefuMsg')
            ->where('uid', $uid)
            ->order('id asc')
            ->select();
        
        return json(['code' => 1, 'data' => $messages]);
    }
    
    // 客服回复消息
    public function replyMessage()
    {
        $uid = input('uid');
        $message = input('message');
        
        if (!$uid || !$message) {
            return json(['code' => 0, 'msg' => '参数错误']);
        }
        
        $data = [
            'uid' => $uid,
            'message' => $message,
            'type' => 'service',
            'is_read' => 0,
            'create_time' => date('Y-m-d H:i:s')
        ];
        
        $result = Db::name('LcKefuMsg')->insert($data);
        
        if ($result) {
            return json(['code' => 1, 'msg' => '回复成功']);
        } else {
            return json(['code' => 0, 'msg' => '回复失败']);
        }
    }
    
    // 获取所有有消息的用户列表
    public function getUserList()
    {
        $users = Db::name('LcKefuMsg')
            ->alias('m')
            ->join('lc_user u', 'm.uid = u.id')
            ->field('m.uid, u.phone, u.name, MAX(m.create_time) as last_time, COUNT(CASE WHEN m.is_read=0 AND m.type="user" THEN 1 END) as unread')
            ->group('m.uid')
            ->order('last_time desc')
            ->select();
        
        return json(['code' => 1, 'data' => $users]);
    }
}
