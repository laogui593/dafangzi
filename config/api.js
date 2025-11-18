const http = uni.$u.http
export const url = http.config.baseURL
// post请求，注册
export const reg = (params, config = {}) => http.post('/index/api.login/reg', params, config)

// post请求，登录
export const login = (params, config = {}) => http.post('/index/api.login/login', params, config)

// 获取轮播图
export const getMenu = (data) => http.get('/index/api.index/getBanner', data)

// 获取公告
export const getNotice = (data) => http.get('/index/api.index/notice', data)

// 获取首页底部交易对
export const getProducts = (data) => http.get('/index/api.index/getProducts', data)

// 更新首页产品波动
export const updateProducts = (data) => http.get('/index/api.index/ajaxdata', data)

// 客服链接
export const service = (data) => http.get('/index/api.index/service', data)

// get请求，关于我们，注意：get请求的配置等，都在第二个参数中
export const aboutUs = (data) => http.get('/index/api.index/aboutUs', data)

// get请求，隐私政策，注意：get请求的配置等，都在第二个参数中
export const privacyPolicy = (data) => http.get('/index/api.index/privacyPolicy', data)

// K线顶部波动信息
export const getprodata = (data) => http.get('/index/api.index/getprodata', data)

// K线图数据
export const getkdata = (data) => http.get('/index/api.index/getkdata', data)

// K线最新数据
export const getKupdate = (data) => http.get('/index/api.index/ajaxpro', data)

// 委托页数据 委托弹窗数据
export const getGoods = (data) => http.get('/index/api.index/goods', data)

// 交易接口
export const addorder = (data) => http.post('/index/api.user/addorder', data)

// 账户页数据 
export const account = (params, config = {}) => http.post('/index/api.user/account', params, config)

// 交易记录 
export const ajaxorderList = (params, config = {}) => http.post('/index/api.user/ajaxorder_list', params, config)

// 充值记录 
export const rechargeRecord = (params, config = {}) => http.post('/index/api.user/recharge_record', params, config)

// 提现记录 
export const cashRecord = (params, config = {}) => http.post('/index/api.user/cash_record', params, config)

// 资金流水（账单）
export const fund = (params, config = {}) => http.post('/index/api.user/fund', params, config)

export const weimai = (data) => http.get('/index/index/weimai', data)

// 充值信息查询接口
export const recharge = (data) => http.get('/index/api.user/recharge', data)

// 充值申请接口
export const recharges = (params, config = {}) => http.post('/index/api.user/recharge', params, config)

// 提现信息查询
export const cash = (data) => http.get('/index/api.user/cash', data)

// 提现申请接口
export const cashs = (params, config = {}) => {
	console.log('🔵 cashs API 被调用, params:', params)
	console.log('🔵 调用 http.post, URL:', '/index/api.user/cash')
	console.log('🔵 http 对象:', http)
	console.log('🔵 http.post 方法:', typeof http.post)
	
	try {
		const promise = http.post('/index/api.user/cash', params, config)
		console.log('🔵 http.post 返回:', promise)
		
		// 手动添加日志
		promise.then(res => {
			console.log('🟢 cashs Promise resolved:', res)
			return res
		}).catch(err => {
			console.log('🔴 cashs Promise rejected:', err)
			throw err
		})
		
		return promise
	} catch(error) {
		console.log('🔴 cashs 调用出错:', error)
		throw error
	}
}

// 用户信息查询接口
export const index = (params, config = {}) => http.post('/index/api.user/index', params, config)

// 实名信息查询接口
export const verified = (data) => http.get('/index/api.user/verified', data)

// 实名认证
export const verifieds = (params, config = {}) => http.post('/index/api.user/verified', params, config)

// 登录密码修改
export const pwdLogin = (params, config = {}) => http.post('/index/api.user/pwd_login', params, config)

// 交易密码修改
export const pwdPay = (params, config = {}) => http.post('/index/api.user/pwd_pay', params, config)

// 银行卡列表
export const bankCard = (params, config = {}) => http.post('/index/api.user/bank_card', params, config)

// 添加银行卡
export const addCard = (params, config = {}) => http.post('/index/api.user/add_card', params, config)