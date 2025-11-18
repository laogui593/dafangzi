<?php

// +----------------------------------------------------------------------
// | ThinkAdmin
// +-------------s---------------------------------------------------------
// | 版权所有 2014~2019 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://demo.thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 代码仓库3：https://gitee.com/zoujingli/ThinkAdmin
// | github 代码仓库3：https://github.com/zoujingli/ThinkAdmin
// +----------------------------------------------------------------------

namespace app\index\controller\api;
use library\Controller;
use think\Db;

/**
 * 应用入口
 * Class Index
 * @package app\index\controller
 */
class Index extends Controller
{


    /**
     * @description：首页
     * @date: 2020/5/13 0013
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function index()
    {
        $uid = $this->app->session->get('uid');
        if(!$uid) {
            if (true) { // true:开启线路;false:关闭线路
                return $this->fetch();
            }
            // $this->redirect('/index/login');
        }
        $this->redirect('/index/index/home');
    }


    /**
     * @description：未登录新闻页
     * @date: 2020/5/14 0014
     */
    public function news(){
        $this->fetch('new_index');
    }

    /**
     * Describe: 新闻页面
     * DateTime: 2020/5/14 1:16
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function my_news(){
        $this->login = 0;
        if(!isLogin()) $this->login = 1;
        $this->conf = Db::name('LcReward')->get(1);
        $this->fetch('my_news');
    }

    /**
     * Describe: 关于我们
     * DateTime: 2020/5/14 0:31
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function aboutUs() {
        $id = input('id',2);
        $uid = input('uid','');
        if(!$uid) $this->error('用户ID不能为空！');
        $where['uid'] = $uid;
        $where['mid'] = $id;
        $ret = Db::name('LcMsgIs')->where($where)->find();
        if (!$ret) Db::name('LcMsgIs')->insertGetId(['uid' => $uid, 'mid' => $id]);
        $msg = Db::name('LcMsg')->find($id);
        if($msg){
            $this->success('数据查询成功',$msg);
        }else{
            $this->error('暂无数据');
        }
    }
    /**
     * Describe: 隐私政策
     * DateTime: 2020/5/14 0:31
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function privacyPolicy() {
        $id = input('id',1);
        $uid = input('uid','');
        if(!$uid) $this->error('用户ID不能为空！');
        $where['uid'] = $uid;
        $where['mid'] = $id;
        $ret = Db::name('LcMsgIs')->where($where)->find();
        if (!$ret) Db::name('LcMsgIs')->insertGetId(['uid' => $uid, 'mid' => $id]);
        $msg = Db::name('LcMsg')->find($id);
        if($msg){
            $this->success('数据查询成功',$msg);
        }else{
            $this->error('暂无数据');
        }
    }
    /**
     * Describe: 帮助中心
     * DateTime: 2020/5/14 0:31
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function helpCenter() {
        $id = input('id',3);
        $uid = input('uid','');
        if(!$uid) $this->error('用户ID不能为空！');
        $where['uid'] = $uid;
        $where['mid'] = $id;
        $ret = Db::name('LcMsgIs')->where($where)->find();
        if (!$ret) Db::name('LcMsgIs')->insertGetId(['uid' => $uid, 'mid' => $id]);
        $msg = Db::name('LcMsg')->find($id);
        if($msg){
            $this->success('数据查询成功',$msg);
        }else{
            $this->error('暂无数据');
        }
    }
    /**
     * Describe: 客服链接
     * DateTime: 2020/5/14 0:31
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function service() {
        $msg = getInfo('service');
        if($msg){
            $this->success('查询成功',$msg);
        }else{
            $this->error('暂无数据');
        }
    }


    /**
     * Describe: 新闻奖励
     * DateTime: 2020/5/14 1:27
     * @throws \think\Exception
     * @throws \think\Exception\DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\PDOException
     */
    public function news_reward(){
        if(isLogin()){
            $uid = $this->app->session->get('uid');
            $reward = Db::name('LcReward')->get(1);
            $start_time=strtotime(date("Y-m-d",time()));
            $end_time=$start_time+60*60*24;
            $reward['newsmoney'] = round($this->randFloat($reward['newsmoney'],$reward['newsmoneytwo']),2);
            $todaynum = Db::name('LcSeeLog')->where('uid=\'' . $uid . '\' and dateline > \'' . $start_time . '\' and dateline < \'' . $end_time . '\'')->count();
            if($todaynum < $reward['getnum']){
                addFinance($uid, $reward['newsmoney'],1, '浏览新闻，系统赠送' . $reward['newsmoney'] . '元');
                setNumber('LcUser', 'money', $reward['newsmoney'], 1, "id = $uid");
                setNumber('LcUser', 'income', $reward['newsmoney'], 1, "id = $uid");
                $add = array('uid' => $uid, 'dateline' => time(), 'money' => $reward['newsmoney']);
                Db::name('LcSeeLog')->insert($add);
                $morenum = $reward['getnum'] - $todaynum - 1;
                $this->success('奖励领取成功',['more'=>$morenum,'times'=>$reward['seetime'] * 60]);
            }else{
                $this->error('今日领取次数用尽');
            }
        }
    }

    private function randFloat($min=0, $max=1){
        return $min + mt_rand()/mt_getrandmax() * ($max-$min);
    }

    /**
     * @description：首页
     * @date: 2020/5/14 0014
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function home(){
//        $this->ater = Db::name('LcArticle')->where(['type'=>17,'show'=>1])->find();
//        $this->banner = Db::name('LcSlide')->where(['show'=>1])->order("sort asc,id desc")->select();
        $allproducts= Db::name('LcProduct')->where(['isdelete'=>0,'iskq'=>1])->order("sort asc,id desc")->select();
        //判断是否开市
        $weekday=date("w");
        $newallproducts=array();
        if($weekday==0) $weekday=7;
        foreach($allproducts as $x=>$p){
            if(strpos($p['code'],"btc")!==false || strpos($p['code'],"usdt")!==false){
            $p['isclosetime']=0;
            $ttimes=$p['opentime_'.$weekday];
            if(empty($ttimes)){
                $p['isclosetime']=1;
                continue;
            };
            //var_dump($this->info['opentime_'.$weekday],$weekday);die;
            if(!empty($ttimes)){
                $optime=0;
                $ttimesarr=explode("|",$ttimes);
                foreach($ttimesarr as $t){
                    $t=explode('~',$t);
                    if(time()>strtotime(date('Y-m-d '.$t[0])) and time()<strtotime(date('Y-m-d '.$t[1]))) $optime=$optime+1;
                }
                if($optime==0)  $p['isclosetime']=1;
            }
            $newallproducts[$x]=$p;}
        }
        $data['products'] = $newallproducts;
//        $data['ater'] =  Db::name('LcArticle')->where(['type'=>17,'show'=>1])->find();
        $data['banner'] = Db::name('LcSlide')->where(['show'=>1])->order("sort asc,id desc")->select();
        $this->success('首页数据查询成功',$data);
    }
    /**
     * @description：首页底部交易对信息
     * @date: 2020/5/14 0014
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getProducts(){
//        $this->ater = Db::name('LcArticle')->where(['type'=>17,'show'=>1])->find();
//        $this->banner = Db::name('LcSlide')->where(['show'=>1])->order("sort asc,id desc")->select();
        $allproducts= Db::name('LcProduct')->where(['isdelete'=>0,'iskq'=>1])->order("sort asc,id desc")->select();
        // var_dump($allproducts);die();
        //判断是否开市
        $weekday=date("w");
        $newallproducts=array();
        if($weekday==0) $weekday=7;
        foreach($allproducts as $x=>$p){
            if(strpos($p['code'],"btc")!==false || strpos($p['code'],"usdt")!==false){
                $p['isclosetime']=0;
                $ttimes=$p['opentime_'.$weekday];
                if(empty($ttimes)){
                    $p['isclosetime']=1;
                    continue;
                };
                //var_dump($this->info['opentime_'.$weekday],$weekday);die;
                if(!empty($ttimes)){
                    $optime=0;
                    $ttimesarr=explode("|",$ttimes);
                    foreach($ttimesarr as $t){
                        $t=explode('~',$t);
                        if(time()>strtotime(date('Y-m-d '.$t[0])) and time()<strtotime(date('Y-m-d '.$t[1]))) $optime=$optime+1;
                    }
                    if($optime==0)  $p['isclosetime']=1;
                }
                $newallproducts[$x]=$p;}
        }
        $data = $newallproducts;
//        $data['ater'] =  Db::name('LcArticle')->where(['type'=>17,'show'=>1])->find();
//        $data['banner'] = Db::name('LcSlide')->where(['show'=>1])->order("sort asc,id desc")->select();
        $this->success('首页底部交易对信息查询成功',$data);
    }
    /**
     * @description：首页轮播图信息
     * @date: 2020/5/14 0014
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getBanner(){
        $data = Db::name('LcSlide')->where(['show'=>1])->order("sort asc,id desc")->select();
        $this->success('首页轮播图信息查询成功',$data);
    }
    /**
     * @description：项目列表
     * @date: 2020/5/14 0014
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function lists(){
        $uid = $this->app->session->get('uid');
        if(!$uid) $this->redirect('/index/login');
        $this->ljsy = Db::name('LcMallInvestList')->where(['status'=>1,'uid'=>$uid])->sum("money1");
        $now = date('Y-m-d H:i:s');
        $this->yxkj = Db::name('LcMallInvest')->where("time2 >= '$now' and uid = $uid")->count();
        $this->dqkj = Db::name('LcMallInvest')->where("time2 <= '$now' and uid = $uid")->count();
        $this->invest = Db::name('LcMallInvest')->where('uid', $uid)->where("time2 >= '$now'")->order("id desc")->select();
        $this->fetch();
    }

    public function normalfutures(){
        $uid = $this->app->session->get('uid');
        if(!$uid) $this->redirect('/index/login');
        $this->ljsy = Db::name('LcInvestList')->where(['status'=>1,'uid'=>$uid])->sum("money1");
        $now = date('Y-m-d H:i:s');
        $this->yxkj = Db::name('LcInvest')->where("time2 >= '$now'")->count();
        $this->dqkj = Db::name('LcInvest')->where("time2 <= '$now'")->count();
        $this->invest = Db::name('LcInvest')->where('uid', $uid)->where("time2 >= '$now'")->order("id desc")->select();
        $this->fetch();
    }

    public function expirefutures()
    {
        $uid = $this->app->session->get('uid');
        if(!$uid) $this->redirect('/index/login');
        $this->ljsy = Db::name('LcInvestList')->where(['status'=>1,'uid'=>$uid])->sum("money1");
        $now = date('Y-m-d H:i:s');
        $this->yxkj = Db::name('LcInvest')->where("time2 >= '$now'")->count();
        $this->dqkj = Db::name('LcInvest')->where("time2 <= '$now'")->count();
        $this->invest = Db::name('LcInvest')->where('uid', $uid)->where("time2 <= '$now'")->order("id desc")->select();
        $this->fetch();
    }

    /**
     * @description：项目列表
     * @date: 2020/5/14 0014
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function ex_lists(){
        $uid = $this->app->session->get('uid');
        if(!$uid) $this->redirect('/index/login');
        $this->ljsy = Db::name('LcMallInvestList')->where(['status'=>1,'uid'=>$uid])->sum("money1");
        $now = date('Y-m-d H:i:s');
        $this->yxkj = Db::name('LcMallInvest')->where("time2 >= '$now'")->count();
        $this->dqkj = Db::name('LcMallInvest')->where("time2 <= '$now'")->count();
        $this->invest = Db::name('LcMallInvest')->where('uid', $uid)->where("time2 <= '$now'")->order("id desc")->select();
        $this->fetch();
    }

    /**
     * @description：项目详情
     * @date: 2020/5/14 0014
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function item(){
        $uid = $this->app->session->get('uid');
        if(!$uid) $this->redirect('/index/login');
        $id = $this->app->request->param('id');
        if(!$id) msg('无效参数',2,'/index');
        $this->data = Db::name('LcItem')->where(['id'=>$id])->find();
        if(!$this->data) msg('无效项目',2,'/index');
        if (date('Y-m-d H:i:s') < $this->data['time'])  msg('项目暂未开始！',2,'/index');
        $this->fetch();
    }

    /**
     * @description：矿机详情
     * @date: 2020/5/14 0014
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function mall_detail(){
        $uid = $this->app->session->get('uid');
        if(!$uid) $this->redirect('/index/login');
        $id = $this->app->request->param('id');
        if(!$id) msg('无效参数',2,'/index');
        $this->data = Db::name('LcMall')->where(['id'=>$id])->find();
        if(!$this->data) msg('无效矿机',2,'/index');
        $this->fetch();
    }

    /**
     * @description：投资
     * @date: 2020/5/14 0014
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function investment(){
        $arr = array();
        $uid = input('uid','');
        if(!$uid) $this->error('用户ID不能为空！');
        $id = input('id','');
        if(!$id) $this->error('产品ID不能为空');
        $data = Db::name('LcItem')->where(['id'=>$id])->find();
        if(!$data) $this->error('产品信息异常');
        if (date('Y-m-d H:i:s') < $data['time'])  $this->error('项目暂未开始！');
        if (getProjectPercent($data['id']) == 100) $this->error('项目已满，请选择其他项目！');
        $user = Db::name('LcUser')->find($uid);
        if($user['auth'] != 1) $this->error('请实名认证后再投资！');
        //抵用券
        $voucher_info = Db::name('LcVoucher')->where("uid = $uid AND status = 2")->order('money desc')->select();
        if(empty($voucher_info)){
            $arr['vinfo'] = array();
        }else{
            $arr['vinfo'] = $voucher_info;
        }
        $count = Db::name('LcVoucher')->where('status = 1 and uid = '.$uid.' and xid = '.$id)->count();
        if($data['usevoucher'] <= $count){
            $arr['voushow'] = 0;
            $arr['usevounum'] = 0;
        }else{
            $arr['voushow'] = 1;
            $arr['usevounum'] = $data['usevoucher']-$count;
        }
        $arr['item'] = $data;
        $arr['user'] = $user;
        //检查等级
        $level = Db::name('LcItemClass')->alias('c')->field("c.id,m.value")->join("lc_user_member m","c.member_id = m.id")->where("c.id = {$data['class']}")->find();
        if($user['value'] < $level['value']) $this->error('您的等级不够！');
        if($this->app->request->isPost()){
            $param = $this->app->request->param();
//            $voucher = $param['voucher'];
            $voucher = 0;
            $money = $param['money'];
            if($voucher){
                $arrvid = explode(',',$voucher);
                $vouallmoney = 0;
                foreach ($arrvid as $k => $v) {
                    $voucherinfos = Db::name('LcVoucher')->where("vid = '$v'")->find();
                    if(empty($voucherinfos)){
                        msg('抵用券不存在', 2, '/index');
                    }
                    if($voucherinfos['status'] != 2){
                        msg('抵用券已使用', 2, '/index');
                    }
                    $vouallmoney = $vouallmoney + $voucherinfos['money'];
                }
                $count = $count + count($arrvid);
                if($count > $data['usevoucher']){
                    msg('最多使用'.$data['usevoucher'].'张投资抵用券', 2, '/index');
                }
                $money = $param['money'] - $vouallmoney;
                if($money < 0) $money = 0;
            }
            $my_count = Db::name('LcInvest')->where(['uid' =>$uid,'pid' =>$id])->count();
            if($data['num'] <= $my_count) msg('该项目每人限投' . $data['num'] . '次！', 2, '/index');
            if($user['password2'] != md5($param['pwd'])) msg('请输入正确的交易密码！', 2, '/index');
            if($user['money'] < $money) msg('余额不足，请充值后再进行投资！', 2,'/index');
            if ($data['max'] < $money) msg('投资金额大于项目最大投资额度！', 2,'/index');
            if (getProjectSurplus($data['id']) < $money) msg('投资金额大于项目剩余投资额度！', 2,'/index');
            if ($data['min'] > $money) msg('投资金额小于项目最小投资额度！', 2,'/index');
            addFinance($uid, $money, 2, '投资项目：' . $data['title'] . '，使用余额' . $money . '元');
            setNumber('LcUser', 'money', $money, 2, "id = $uid");
            setInvestReward_old($uid, $money);
            if($voucher){
                foreach ($arrvid as $k => $v) {
                    Db::name('LcVoucher')->where("vid = '{$v}'")->update(array('status' => 1,'xid'=>$id,'title'=>$data['title']));
                }
            }
            if(getInvestList($id, $money, $uid)) {
                if (0 < $data['red']) {
                    $multiple = floor($money / $data['min']) * $data['red'] ?: 0;

                    if (0 < $multiple) {
                        addFinance($uid, $multiple, 1, '投资送红包');
                        setNumber('LcUser', 'money', $multiple, 1, "id = $uid");
                    }
                }
                msg('投资成功！', 2, '/index/user/index');
            }
            msg('投资失败！', 2, '/index/user/index');
        }
        $this->success($arr);
    }


    /**
     * Describe:文章列表
     * DateTime: 2020/5/14 21:13
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function about_list(){
        $id = $this->app->request->param('id');
        if (empty($id)) msg('参数缺失！', 2, '/index');
        $this->count = Db::name('lcArticle')->where(['type'=>$id,'show'=>1])->count();
        if($this->count == 1){
            $this->article_id = Db::name('lcArticle')->where(['type'=>$id,'show'=>1])->value('id');
            $this->redirect('/index/index/about_details?id='.$this->article_id);
        }else{
            $this->type_name = Db::name('lcArticleType')->where(['id'=>$id])->value('name');
            $this->about = Db::name('lcArticle')->where(['type'=>$id,'show'=>1])->order("id desc")->select();
            $this->fetch();
        }
    }

    /**
     * Describe:文章详情
     * DateTime: 2020/5/14 21:25
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function about_details(){
        $id = $this->app->request->param('id');
        if (empty($id)) msg('参数缺失！', 2, '/index');
        $this->article = Db::name('lcArticle')->where(['id'=>$id,'show'=>1])->find();
        $this->fetch();
    }



    /**
     * Describe:开始抽奖
     * DateTime: 2020/5/14 23:06
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function prize_start(){
        $res = $this->get_gift();
        $item = $res['id'] + 1;
        if (empty($item)) $this->error("参数缺失，请刷新后重试！");
        if(!isLogin()) $this->error("参数缺失，请刷新后重试！",'',2);
        $uid = $this->app->session->get('uid');
        $this->user = Db::name('LcUser')->find($uid);
        if ($this->user['prize'] <= 0) $this->error("抽奖次数不足，请投资后再进行抽奖！");
        $prize = Db::name("LcPrize")->find(1);
        $name = $prize['name' . $item] ?: '谢谢参与';
        $type = $prize['type' . $item] ?: '无';
        $reason = $prize['reason' . $item] ?: '继续投资，还有机会哟！';
        $money = $prize['money' . $item] ?: 0;
        if ($prize['endtime'] < date('Y-m-d H:i:s')) $this->error("活动已结束");
        $add_prize = array('uid' => $uid, 'item' => $item, 'name' => $name, 'type' => $type, 'money' => $money, 'time' => date('Y-m-d H:i:s'));
        Db::name("LcPrizeList")->insert($add_prize);
        if($prize['type'.$item] == 1){
            addFinance($uid, $money,1, '抽奖获得' . $money . '元现金红包');
            setNumber('LcUser', 'money', $money, 1, "id = $uid");
        }
        setNumber('LcUser', 'prize', 1, 2, "id = $uid");
        $this->success($reason,['item'=>$item]);
    }

    /**
     * Describe:抽奖记录
     * DateTime: 2020/5/14 23:14
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function prize_list(){
        $uid = $this->app->session->get('uid');
        if(!$uid) $this->redirect('/index/login');
        $this->prize = Db::name("LcPrizeList")->where("uid = $uid AND type <> 0")->order("id desc")->select();
        $this->fetch();
    }

    /**
     * Describe:积分商城
     * DateTime: 2020/5/14 23:48
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function shop(){
        $uid = $this->app->session->get('uid');
        if(!$uid) $this->redirect('/index/login');
        $this->shop = Db::name("LcShop")->where("num > 0")->order("sort asc,id desc")->select();
        $this->fetch();
    }

    /**
     * Describe:商品详情
     * DateTime: 2020/5/15 0:06
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function shop_details(){
        $uid = $this->app->session->get('uid');
        if(!$uid) $this->redirect('/index/login');
        $id = $this->app->request->param('id');
        if(!$id) msg('参数缺失！', 2, 'index/user/index');
        $this->goods = Db::name("LcShop")->where(['id'=>$id])->find();
        $integral = Db::name('LcUser')->where(['id'=>$uid])->value('integral');
        $this->count = $integral?:0;
        $this->fetch();
    }

    /**
     * Describe:积分兑换
     * DateTime: 2020/5/15 0:15
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function shop_exchange(){
        $uid = $this->app->session->get('uid');
        if(!$uid) $this->error("请先登录",'',2);
        $gid = $this->app->request->param('gid');
        if(!$gid) msg('参数缺失！', 2, 'index/user/index');
        $this->goods = Db::name("LcShop")->where(['id'=>$gid])->find();
        if(!$this->goods) msg('暂无该商品！', 2, 'index/user/index');
        $this->user = Db::name('LcUser')->find($uid);
        if ($this->user['integral'] < $this->goods['integral']) $this->error("积分不足，请投资后再进行兑换！");
        if ($this->goods['num'] <= 0) $this->error("商品数量不足，请兑换其他商品！");
        $add_order = array('uid' => $uid, 'gid' => $gid, 'goods' => $this->goods['title'], 'img' => $this->goods['img'], 'integral' => $this->goods['integral'], 'type' => $this->goods['type'], 'money' => $this->goods['money'], 'time' => date('Y-m-d H:i:s'));
        Db::name("LcShopOrder")->insert($add_order);
        setNumber('LcUser', 'integral', $this->goods['integral'], 2, "id = $uid");
        setNumber('LcShop', 'num', 1, 2, "id = $gid");
        if($this->goods['type'] == '1'){
            addFinance($uid, $this->goods['money'],1, '积分兑换获得' . $this->goods['money'] . '元现金红包');
            setNumber('LcUser', 'money', $this->goods['money'], 1, "id = $uid");
            $this->success($this->goods['money']."元现金下发到您的余额！");
        }
        $this->success("兑换成功，请联系客服邮寄！");
    }

    /**
     * Describe:兑换记录
     * DateTime: 2020/5/15 0:22
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function shop_order(){
        $uid = $this->app->session->get('uid');
        if(!$uid) $this->redirect('/index/login');
        $this->shop_order = Db::name("LcShopOrder")->where(['uid'=>$uid])->order("id desc")->select();
        $this->fetch();
    }

    /**
     * Describe:抽奖算法
     * DateTime: 2020/5/14 22:46
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function get_gift()
    {
        $data = Db::name("LcPrize")->find(1);
        $surplus = 100 - $data['odds1'] - $data['odds2'] - $data['odds3'] - $data['odds4'] - $data['odds5'];
        if (0 < $surplus) {
            $data['odds6'] = $surplus;
        }else {
            $data['odds6'] = 0;
        }
        //奖品数组
        $prize_arr = array(
            '0' => array('id' => 1, 'prize' => $data['name1'], 'v' => $data['odds1']),
            '1' => array('id' => 2, 'prize' => $data['name2'], 'v' => $data['odds2']),
            '2' => array('id' => 3, 'prize' => $data['name3'], 'v' => $data['odds3']),
            '3' => array('id' => 4, 'prize' => $data['name4'], 'v' => $data['odds4']),
            '4' => array('id' => 5, 'prize' => $data['name5'], 'v' => $data['odds5']),
            '5' => array('id' => 6, 'prize' => '谢谢参与', 'v' => $data['odds6']),
        );
        foreach ($prize_arr as $key => $val) {
            $arr[$val['id']] = $val['v'];
        }
        $rid = $this->get_rand($arr);
        $res['yes'] = $prize_arr[$rid - 1]['prize'];
        $res['id'] = $rid - 1;
        unset($prize_arr[$rid - 1]);
        shuffle($prize_arr);
        for ($i = 0; $i < count($prize_arr); $i++) {
            $pr[] = $prize_arr[$i]['prize'];
        }
        $res['no'] = $pr;
        if ($res['yes'] != '谢谢参与') {
            $result['status'] = 1;
            $result['name'] = $res['yes'];
            $result['id'] = $res['id'];
        } else {
            $result['status'] = -1;
            $result['msg'] = $res['yes'];
            $result['id'] = $res['id'];
        }
        return $result;
    }

    /**
     * Describe:随机
     * DateTime: 2020/5/14 22:49
     * @param $proArr
     * @return int|string
     */
    private function get_rand($proArr)
    {
        $result = '';
        $proSum = array_sum($proArr);
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }

    public function MarketDatas(){
        $period = array(
            1=>'1min',
            60=>'1hour',
            1440=>'1day',
        );
        $data = $this->request->param();
        $assets = json_decode(vpost("http://api.zb.center/data/v1/kline?market=btc_qc&type={$period[$data['period']]}&size={$data['coin_nums']}",""),true);
        $btc = json_decode(vpost("http://api.zb.center/data/v1/ticker?market=btc_qc",""),true);
        $result = array(
            'lastprice' => $btc['ticker']['sell'],
            'chg' => $btc['ticker']['riseRate'],
        );
        foreach($assets['data'] as $k => $v){
            $result['time'][$k] = $v[0]/1000;
            $result['date'][$k] = date('Y-m-d H:i:s',$v[0]/1000);
            $result['data'][$k] = array($v[1],$v[2],$v[3],$v[4]);
        }
        $this->success("OK",$result);
    }

    public function GetRealTimeDatas(){
        $ticker = json_decode(vpost("http://api.zb.center/data/v1/allTicker",''),true);
        $data = array(
            0=>array('coin_ad' => round($ticker['btcqc']['riseRate']/100,4),'coin_name'=>'BTC','coin_price'=>$ticker['btcqc']['sell']),
            1=>array('coin_ad' => round($ticker['ethqc']['riseRate']/100,4),'coin_name'=>'ETH','coin_price'=>$ticker['ethqc']['sell']),
            2=>array('coin_ad' => round($ticker['ltcqc']['riseRate']/100,4),'coin_name'=>'LTC','coin_price'=>$ticker['ltcqc']['sell']),
        );
        $this->success("OK",$data);
    }

    public function mall(){
        $uid = $this->app->session->get('uid');
        if(!$uid) $this->redirect('/index/login');
        $this->mall = Db::name('LcMall')->where("stock > 0")->order("sort asc,id desc")->select();
        $this->fetch();
    }

    public function futureslist(){
        $now = date('Y-m-d H:i:s');
        $this->item = Db::name('LcItem')->where("time <= '$now' AND round(percent) < 100")->order("sort asc,id desc")->select();
        $this->fetch();
    }

    /**
     * Describe:定时结算任务
     * DateTime: 2020/5/14 22:22
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function item_crontab(){
        $now = time();
        $invest_list = Db::name("LcInvestList")->where("UNIX_TIMESTAMP(time1) <= $now AND status = '0'")->select();
        if(empty($invest_list)) exit('暂无返息计划');
        foreach($invest_list as $k=>$v){
            $data = array('time2' => date('Y-m-d H:i:s'), 'pay2' => $v['pay1'], 'status' => 1);
            if (Db::name("LcInvestList")->where(['id'=>$v['id']])->update($data)) {
                if($v['pay1']>0){
                    addFinance($v['uid'],$v['pay1'],1,$v['title'].' 第'.$v['num'].'期收益'.$v['pay1'].'元');
                    setNumber('LcUser', 'money', $v['pay1'], 1, "id = {$v['uid']}");
                    setNumber('LcUser', 'income', $v['money1'], 1, "id = {$v['uid']}");
                }
            }
        }
    }
    
    /**
     * Describe:交易订单自动结算任务
     * DateTime: 2025/11/18
     * 访问地址: /index/api.index/order_crontab
     */
    public function order_crontab(){
        $now = time();
        // 查询已到期但未结算的订单(ostaus=0表示持仓中)
        $order_list = Db::name("LcOrder")->where("selltime <= $now AND ostaus = '0'")->select();
        
        if(empty($order_list)) {
            // 没有待结算订单,直接返回不输出
            return;
        }
        
        $success_count = 0;
        foreach($order_list as $order){
            try {
                // 获取产品当前价格
                $product = Db::name('LcProduct')->find($order['pid']);
                if(!$product) continue;
                
                $sellprice = $product['Price']; // 平仓价格
                $buyprice = $order['buyprice']; // 买入价格
                $fee = $order['fee']; // 投资金额
                
                // 判断盈亏
                $is_win = false;
                if($order['ostyle'] == 0){ // 买涨
                    $is_win = ($sellprice > $buyprice);
                } elseif($order['ostyle'] == 1){ // 买跌
                    $is_win = ($sellprice < $buyprice);
                }
                
                // 计算盈亏金额
                if($is_win){
                    // 盈利
                    $profit_rate = floatval($order['endloss']) / 100; // 盈利比例
                    $profit = $fee * $profit_rate; // 盈利金额
                    $ploss = $profit; // 盈利
                    $commission = $order['commission'] + $fee + $profit; // 返还本金+盈利
                } else {
                    // 亏损
                    $loss_rate = floatval($order['lossrate']) / 100; // 亏损比例
                    $loss = $fee * $loss_rate; // 亏损金额
                    $ploss = -$loss; // 负数表示亏损
                    $commission = $order['commission'] + $fee - $loss; // 返还本金-亏损
                }
                
                // 更新订单状态
                $update_data = [
                    'ostaus' => '1', // 已平仓
                    'sellprice' => $sellprice,
                    'ploss' => $ploss,
                    'commission' => $commission,
                    'remarks' => '系统自动结算'
                ];
                
                Db::name('LcOrder')->where(['id' => $order['id']])->update($update_data);
                
                // 更新用户余额
                $return_money = $fee + $ploss; // 返还金额=本金+盈亏
                if($return_money > 0){
                    setNumber('LcUser', 'money', $return_money, 1, "id = {$order['uid']}");
                    $reason = $ploss > 0 ? "订单[{$order['orderno']}]盈利结算" : "订单[{$order['orderno']}]亏损结算";
                    addFinance($order['uid'], $return_money, 1, $reason);
                }
                
                $success_count++;
            } catch (\Exception $e) {
                continue;
            }
        }
        
        // 结算完成,输出日志
        echo date('Y-m-d H:i:s') . " 结算完成: 总计{$order_list->count()}单, 成功{$success_count}单\n";
    }
    public function mall_crontab(){
        $now = time();
        $mall_invest_list = Db::name("LcMallInvestList")->where("UNIX_TIMESTAMP(time1) <= $now AND status = '0'")->select();
        if(empty($mall_invest_list)) exit('暂无返息计划');
        foreach($mall_invest_list as $k=>$v){
            $data = array('time2' => date('Y-m-d H:i:s'), 'pay2' => $v['pay1'], 'status' => 1);
            if (Db::name("LcMallInvestList")->where(['id'=>$v['id']])->update($data)) {
                if($v['pay1']>0){
                    addFinance($v['uid'],$v['pay1'],1,$v['title'].' 第'.$v['num'].'期收益'.$v['pay1'].'BTC');
                    if($v['tran_type']>1){
                        $btc_price = json_decode(vpost("http://api.zb.center/data/v1/ticker?market=btc_qc",''),true)['ticker']['sell'];
                        $money = round($btc_price*$data['buynum'],2);
                        addFinance($v['uid'],$money,1,"BTC兑换交易{$money}元");
                        setNumber('LcUser', 'money', $money, 1, "id = {$v['uid']}");
                    }else{
                        setNumber('LcUser', 'btc', $v['money1'], 1, "id = {$v['uid']}");
                    }
                }
                if($v['money2']>0){
                    addFinance($v['uid'],$v['money2'],1,$v['title'].'，保证金退还'.$v['pay1'].'元');
                    setNumber('LcUser', 'money', $v['money2'], 1, "id = {$v['uid']}");
                }
            }
        }
    }


    /**
     * Describe:最新公告
     * DateTime: 2020/5/14 21:02
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function notice(){
        $data = Db::name('lcArticle')->where(['type'=>9,'show'=>1])->order("id desc")->select();
        $this->success('公告查询成功', $data);
    }

    public function goods(){
        $uid = input('uid','');
        if (!$uid) $this->error('用户ID不能为空');
        $id = input('id','');
        if(!$id) $this->error('产品ID不能为空');
        $this->info = Db::name('LcProduct')->find($id);
        if(!$this->info) $this->error('产品不存在');
        if($this->info['iskq']==0) $this->error('该产品没有开启,无法访问');
        $this->user = Db::name('LcUser')->find($uid);
        if(!$this->user) $this->error('用户不存在');
        $weekday=date("w");
        if($weekday==0) $weekday=7;
        $ttimes=$this->info['opentime_'.$weekday];

        if(empty($ttimes)) $this->error('非交易时间，请选择其它商品！');
        //var_dump($this->info['opentime_'.$weekday],$weekday);die;
        if(!empty($ttimes)){
            $optime=0;
            $ttimesarr=explode("|",$ttimes);
            foreach($ttimesarr as $t){
                $t=explode('~',$t);
                if(time()>strtotime(date('Y-m-d '.$t[0])) and time()<strtotime(date('Y-m-d '.$t[1]))) $optime=$optime+1;
            }
            if($optime==0)  $this->error('非交易时间，请选择其它商品！');
        }
        //var_dump($this->info);die;
        if($this->info['protime_1']>0){
            $this->info['order_price'][] = ['time'=>$this->info['protime_1']*60,'prices'=>explode('|',trim($this->info['order_amount_1'])),'shouyi'=>$this->info['proscale_1'],'kuisun'=>$this->info['lossrate_1']];
        }
        unset($this->info['protime_1']);
        unset($this->info['order_amount_1']);
        if($this->info['protime_2']>0){
            $this->info['order_price'][] = ['time'=>$this->info['protime_2']*60,'prices'=>explode('|',trim($this->info['order_amount_2'])),'shouyi'=>$this->info['proscale_2'],'kuisun'=>$this->info['lossrate_2']];
        }
        unset($this->info['protime_2']);
        unset($this->info['order_amount_2']);
        if($this->info['protime_3']>0){
            $this->info['order_price'][] = ['time'=>$this->info['protime_3']*60,'prices'=>explode('|',trim($this->info['order_amount_3'])),'shouyi'=>$this->info['proscale_3'],'kuisun'=>$this->info['lossrate_3']];
        }
        unset($this->info['protime_3']);
        unset($this->info['order_amount_3']);
        if($this->info['protime_4']>0){
            $this->info['order_price'][] = ['time'=>$this->info['protime_4']*60,'prices'=>explode('|',trim($this->info['order_amount_4'])),'shouyi'=>$this->info['proscale_4'],'kuisun'=>$this->info['lossrate_4']];
        }
        unset($this->info['protime_4']);
        unset($this->info['order_amount_4']);
        // 添加系统配置信息(手续费、最小最大金额等)
        $config = [
            'order_charge' => getinfo('order_charge') ?: 0, // 手续费率
            'order_min' => getinfo('order_min') ?: 0, // 最小下单金额
            'order_max' => getinfo('order_max') ?: 0, // 最大下单金额
            'order_max_amount' => getinfo('order_max_amount') ?: 0, // 最大持仓总额
        ];
        $this->success('查询成功！',['info'=>$this->info,'user'=>$this->user,'config'=>$config]);
    }

    public function goodsinfo(){
        $pid = $this->app->request->param('pid');
        $goods = Db::name('LcProduct')->find($pid);
        $res = base64_encode(json_encode($goods));
        return $res;
    }
    public function getchart(){
        $data['kaipan'] = '开盘';
        $data['zuidi'] = '最低';
        $data['zuigao'] = '最高';
        $data['Kxian'] = 'k线';
        $data['zoushi'] = '走势';
        $data['DIFF'] = 'DIFF:';
        $data['DEA'] = 'DEA:';
        $data['MACD'] = 'MACD:';
        $data['chicang'] = '持仓';
        $data['maizhang'] = '买涨';
        $data['maidie'] = '买跌';
        $data['xiushi'] = '休市中';
        $data['tousijine'] = '投资金额';
        $data['chicangmingxi'] ='持仓明细';
        $res = base64_encode(json_encode($data));
        return $res;
    }
    public function getprodata(){
        $pid = input('pid',1);
        $data = Db::name('LcProduct')->field('Price,Open,Close,High,Low,UpdateTime')->find($pid);

        if(!$data){
            exit;
        }

        $topdata = array(
            'topdata'=>$data['UpdateTime'],
            'now'=>$data['Price'],
            'open'=>$data['Open'],
            'lowest'=>$data['Low'],
            'highest'=>$data['High'],
            'close'=>$data['Close']
        );
        $this->success('查询信息成功',$topdata);
    }
    public function ajaxpro(){
        $id = $this->app->request->param('pid');
        $data = Db::name('LcProduct')->field('Price,Open,Close,High,Low,UpdateTime')->find($id);
        $data['UpdateTime'] = date('H:i:s',$data['UpdateTime']);
        $this->success('查询信息成功！',$data);
    }
    public function ajaxdata(){
        // $list = Db::name('LcProduct')->where('isdelete',0)->select();

        // if(!isset($list)) return false;

        // $nowtime = time();
        // $_rand = rand(1,900)/100000;
        // $thisdatas = array();

        // foreach ($list as $k => $v) {
        //     $v['procode'] = $v['code'];
        //     //验证休市
        //     $isopen = 0;
        //     if ($v['isopen']) {
        //         $isopen = ChickIsOpen($v['id']);
        //     }
        //     if(!$isopen){
        //         continue;
        //     }

        //     //腾讯证券
        //     if($v['procode'] == "btc" || $v['procode'] == "ltc"|| $v['procode'] == "eth"){

        //         $minute = date('i',$nowtime);
        //         if($minute >= 0 && $minute < 15){ $minute = 0;}
        //         elseif($minute >= 15 && $minute < 30){ $minute = 15;}
        //         elseif($minute >= 30 && $minute < 45){ $minute = 30;}
        //         elseif($minute >= 45 && $minute < 60){ $minute = 45;}
        //         $new_date = strtotime(date('Y-m-d H',$nowtime).':'.$minute.':00');

        //         if($v['procode'] == 'btc'){
        //             $url = 'http://api.zb.live/data/v1/ticker?market=btc_usdt';
        //         }elseif($v['procode'] == 'ltc'){
        //             $url = 'http://api.zb.live/data/v1/ticker?market=ltc_usdt';
        //         }elseif($v['procode'] == 'eth'){
        //             $url = 'http://api.zb.live/data/v1/ticker?market=eth_usdt';
        //         }
        //         $getdata = $this->curlfun($url);
        //         $res = json_decode($getdata,1);
        //         $data_arr=$res['ticker'];

        //         if(!is_array($data_arr)) continue;
        //         $thisdata['Price'] = $this->fengkong($data_arr['sell'],$v);;
        //         $thisdata['Open'] = $data_arr['buy'];
        //         $thisdata['Close'] = $data_arr['last'];
        //         $thisdata['High'] = $data_arr['high'];
        //         $thisdata['Low'] = $data_arr['low'];
        //         $thisdata['Diff'] = 0;
        //         $thisdata['DiffRate'] = 0;
        //     }else{
        //         $url = "http://hq.sinajs.cn/rn=".$nowtime."list=".$v['code'];

        //         $getdata = $this->curlfun($url);
        //         $data_arr = explode(',',$getdata);

        //         if(!is_array($data_arr) || count($data_arr) != 18) continue;

        //         $thisdata['Price'] = $data_arr[1];
        //         $thisdata['Open'] = $data_arr[5];
        //         $thisdata['Close'] = $data_arr[3];
        //         $thisdata['High'] = $data_arr[6];
        //         $thisdata['Low'] = $data_arr[7];
        //         $thisdata['Diff'] = $data_arr[12];
        //         $thisdata['DiffRate'] = $data_arr[4]/10000;
        //     }
        //     $thisdata['UpdateTime'] = $nowtime;
        //     $ids = Db::name('LcProduct')->where('id',$v['id'])->update($thisdata);
        // }

        $product = Db::name('LcProduct')->field("id,title as Name,Price,isdelete,High,code")->where(array('isdelete' => 0))->select();


        foreach( $product as $k=>$val) {
            $rd = rand(-3,3);
            //修改前端显示位数！！！
            $product[$k]['Price'] =round($val['Price'] +$rd*0.01*$val['Price'],3);
            if($product[$k]['code']=='eth_usdt')   $product[$k]['Price'] =rand(189900,190199)/100;
            // $lastprice= session('price'.$val['id']);
            $lastprice = cache('price'.$val['id'])?cache('price'.$val['id']):0;
            $product[$k]['is_rise']=($lastprice>=$val['Price'])?1:2;
            $product[$k]['is_deal'] = ChickIsOpen($val['id']);
            // session('price'.$val['id'],$product[$k]['Price']);
            cache('price'.$val['id'],$product[$k]['Price']);
        }
        $this->success('查询信息成功',$product);
    }


    public function getkdata(){
        $pid = (int)input('pid',0);
        if(!$pid) $this->error('产品id不能为空');
        $num = (int)input('num',50);
        if($num < 1) $this->error('时间数量不合法');
        $interval = input('interval','1');
        $pro = Db::name('LcProduct')->where(['id'=>$pid])->find();
        if(!$pro) $this->error('产品信息异常');

        $basePrice = (float)$pro['Price'];
        $stepSeconds = ($interval==='d') ? 86400 : (intval($interval) * 60 ?: 60);
        $now = time();
        $items = [];
        $prevClose = $basePrice;

        for($i=$num-1; $i>=0; $i--){
            $ts = $now - $i * $stepSeconds;
            $open = $prevClose;
            $pct = rand(20,60)/10000; // 0.2%~0.6%
            $dir = rand(0,1) ? 1 : -1;
            $close = round($open * (1 + $dir * $pct),6);
            $highRaw = max($open,$close);
            $lowRaw  = min($open,$close);
            $high = round($highRaw * (1 + rand(0,10)/10000),6);
            $low  = round($lowRaw  * (1 - rand(0,10)/10000),6);
            $items[] = [ $ts, round($open,6), $close, $low, $high ];
            $prevClose = $close;
        }

        $closes = array_column($items,2);
        $highs  = array_column($items,4);
        $lows   = array_column($items,3);
        $state = end($closes) >= $basePrice ? 'up' : 'down';

        $topdata = [
            'topdata' => $now,
            'now'     => $pro['Price'],
            'open'    => $items[0][1],
            'lowest'  => min($lows),
            'highest' => max($highs),
            'close'   => end($closes),
            'state'   => $state,
            'mock'    => 1,
        ];

        $this->success('查询成功！',[ 'topdata'=>$topdata, 'items'=>$items ]);
    }
}
