const filters = {
	filterImg: function(value) {
		const baseURL = uni.$u.http.config.baseURL;
		return baseURL + value;
	},
	// 银行卡替换
	hiddenIdCard(str, startLen = 3, endLen = 4) {
		//str：要进行隐藏的变量  startLen: 前面需要保留几位    endLen: 后面需要保留几位
		let len = str.length - startLen - endLen;
		let start = '';
		for (let i = 0; i < len; i++) {
			start += '*';
		}
		// 最后的返回值由三部分组成
		return str.substring(0, startLen) + start + str.substring(str.length - endLen);
	},
}

export default filters;
