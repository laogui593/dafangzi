export default {
	/**
	 * opt  object | string
	 * to_url object | string
	 * 例:
	 * this.Tips('/pages/test/test'); 跳转不提示
	 * this.Tips({title:'提示'},'/pages/test/test'); 提示并跳转
	 * this.Tips({title:'提示'},{tab:1,url:'/pages/index/index'}); 提示并跳转值table上
	 * tab=1 一定时间后跳转至 table上
	 * tab=2 一定时间后跳转至非 table上
	 * tab=3 一定时间后返回上页面
	 * tab=4 关闭所有页面，打开到应用内的某个页面
	 * tab=5 关闭当前页面，跳转到应用内的某个页面
	 */
	Tips: function(opt, to_url) {
		if (typeof opt == 'string') {
			to_url = opt;
			opt = {};
		}
		let title = opt.title || '',
			icon = opt.icon || 'none',
			endtime = opt.endtime || 2000,
			success = opt.success;
		if (title) uni.showToast({
			title: title,
			icon: icon,
			duration: endtime,
			success
		})
		if (to_url != undefined) {
			if (typeof to_url == 'object') {
				let tab = to_url.tab || 1,
					url = to_url.url || '';
				switch (tab) {
					case 1:
						//一定时间后跳转至 table
						setTimeout(function() {
							uni.switchTab({
								url: url
							})
						}, endtime);
						break;
					case 2:
						//跳转至非table页面
						setTimeout(function() {
							uni.navigateTo({
								url: url,
							})
						}, endtime);
						break;
					case 3:
						//返回上页面
						setTimeout(function() {
							// #ifndef H5
							uni.navigateBack({
								delta: parseInt(url),
							})
							// #endif
							// #ifdef H5
							history.back();
							// #endif
						}, endtime);
						break;
					case 4:
						//关闭所有页面，打开到应用内的某个页面
						setTimeout(function() {
							uni.reLaunch({
								url: url,
							})
						}, endtime);
						break;
					case 5:
						//关闭当前页面，跳转到应用内的某个页面
						setTimeout(function() {
							uni.redirectTo({
								url: url,
							})
						}, endtime);
						break;
				}

			} else if (typeof to_url == 'function') {
				setTimeout(function() {
					to_url && to_url();
				}, endtime);
			} else {
				//没有提示时跳转不延迟
				setTimeout(function() {
					uni.navigateTo({
						url: to_url,
					})
				}, title ? endtime : 0);
			}
		}
	},
	/**
	 * 移除数组中的某个数组并组成新的数组返回
	 * @param array array 需要移除的数组
	 * @param int index 需要移除的数组的键值
	 * @param string | int 值
	 * @return array
	 * 
	 */
	ArrayRemove: function(array, index, value) {
		const valueArray = [];
		if (array instanceof Array) {
			for (let i = 0; i < array.length; i++) {
				if (typeof index == 'number' && array[index] != i) {
					valueArray.push(array[i]);
				} else if (typeof index == 'string' && array[i][index] != value) {
					valueArray.push(array[i]);
				}
			}
		}
		return valueArray;
	},
	/*
	 * 单图上传
	 * @param object opt
	 * @param callable successCallback 成功执行方法 data 
	 * @param callable errorCallback 失败执行方法 
	 */
	uploadImageOne: function(opt, successCallback, errorCallback) {
		let that = this;
		if (typeof opt === 'string') {
			let url = opt;
			opt = {};
			opt.url = url;
		}
		let count = opt.count || 1,
			sizeType = opt.sizeType || ['compressed'],
			sourceType = opt.sourceType || ['album', 'camera'],
			is_load = opt.is_load || true,
			uploadUrl = opt.url || '',
			inputName = opt.name || '',
			fileType = opt.fileType || 'image';
		uni.chooseImage({
			count: count, //最多可以选择的图片总数  
			sizeType: sizeType, // 可以指定是原图还是压缩图，默认二者都有  
			sourceType: sourceType, // 可以指定来源是相册还是相机，默认二者都有  
			success: function(res) {
				//启动上传等待中...  
				uni.showLoading({
					title: `图片上传中`,
				});
				uni.uploadFile({
					url: uploadUrl,
					filePath: res.tempFilePaths[0],
					fileType: fileType,
					name: inputName,
					formData: {},
					header: {},
					success: function(res) {
						uni.hideLoading();
						if (res.statusCode == 403) {
							that.Tips({
								title: res.data
							});
						} else {
							let data = res.data ? JSON.parse(res.data) : {};
							console.log('data: ', data)
							if (data.uploaded) {
								successCallback && successCallback(data)
							} else {
								errorCallback && errorCallback(data);
								that.Tips({
									title: '图片上传成功'
								});
							}
						}
					},
					fail: function(res) {
						uni.hideLoading();
						that.Tips({
							title: `上传图片失败`
						});
					}
				})
			}
		})
	},
	/*
	 * 单图上传压缩版
	 * @param object opt
	 * @param callable successCallback 成功执行方法 data 
	 * @param callable errorCallback 失败执行方法 
	 */
	uploadImageChange: function(opt, successCallback, errorCallback, sizeCallback) {
		let that = this;
		if (typeof opt === 'string') {
			let url = opt;
			opt = {};
			opt.url = url;
		}
		let count = opt.count || 1,
			sizeType = opt.sizeType || ['compressed'],
			sourceType = opt.sourceType || ['album', 'camera'],
			is_load = opt.is_load || true,
			uploadUrl = opt.url || '',
			inputName = opt.name || 'pics',
			fileType = opt.fileType || 'image';
		uni.chooseImage({
			count: count, //最多可以选择的图片总数  
			sizeType: sizeType, // 可以指定是原图还是压缩图，默认二者都有  
			sourceType: sourceType, // 可以指定来源是相册还是相机，默认二者都有  
			success: function(res) {
				//启动上传等待中...  
				let imgSrc
				uni.getImageInfo({
					src: res.tempFilePaths[0],
					success(ress) {
						uni.showLoading({
							title: `图片上传中`,
						});
						if (res.tempFiles[0].size <= 2097152) {
							uploadImg(ress.path)
							return
						}
						// uploadImg(canvasPath.tempFilePath)
						let canvasWidth, canvasHeight, xs, maxWidth = 750
						xs = ress.width / ress.height // 宽高比例
						if (ress.width > maxWidth) {
							canvasWidth = maxWidth // 这里是最大限制宽度
							canvasHeight = maxWidth / xs
						} else {
							canvasWidth = ress.width
							canvasHeight = ress.height
						}
						sizeCallback && sizeCallback({
							w: canvasWidth,
							h: canvasHeight
						})
						let canvas = uni.createCanvasContext('canvas');
						canvas.width = canvasWidth
						canvas.height = canvasHeight
						canvas.clearRect(0, 0, canvasWidth, canvasHeight);
						canvas.drawImage(ress.path, 0, 0, canvasWidth, canvasHeight)
						canvas.save();
						// 这里的画布drawImage是一种异步属性  可能存在未绘制全就执行了draw的问题  so添加延迟
						setTimeout(e => {
							canvas.draw(true, () => {
								uni.canvasToTempFilePath({
									canvasId: 'canvas',
									fileType: 'JPEG',
									destWidth: canvasWidth,
									destHeight: canvasHeight,
									quality: 0.7,
									success: function(canvasPath) {
										uploadImg(canvasPath
											.tempFilePath)
									}
								})
							});
						}, 200)


					}
				})
			}
		})

		function uploadImg(filePath) {
			uni.uploadFile({
				url: HTTP_REQUEST_URL + '/api/' + uploadUrl,
				filePath,
				fileType: fileType,
				name: inputName,
				formData: {
					'filename': inputName
				},
				header: {
					// #ifdef MP
					"Content-Type": "multipart/form-data",
					// #endif
					[TOKENNAME]: 'Bearer ' + store.state.app.token
				},
				success: function(res) {
					uni.hideLoading();
					if (res.statusCode == 403) {
						that.Tips({
							title: res.data
						});
					} else {
						let data = res.data ? JSON.parse(res.data) : {};
						if (data.status == 200) {
							successCallback && successCallback(data)
						} else {
							errorCallback && errorCallback(data);
							that.Tips({
								title: data.msg
							});
						}
					}
				},
				fail: function(res) {
					uni.hideLoading();
					that.Tips({
						title: `上传图片失败`
					});
				}
			})
		}
	},

	/*
	 * 合并数组
	 */
	SplitArray(list, sp) {
		if (typeof list != 'object') return [];
		if (sp === undefined) sp = [];
		for (var i = 0; i < list.length; i++) {
			sp.push(list[i]);
		}
		return sp;
	},
	trim(backUrlCRshlcICwGdGY) {
		return String.prototype.trim.call(backUrlCRshlcICwGdGY);
	},
	/**
	 * 获取当前时间戳
	 */
	time() {
		return Math.round(new Date() / 1000);
	},
	/**
	 * 字符串转时间戳
	 * @param {Object} expiresTime
	 */
	strTotime(expiresTime) {
		let expires_time = expiresTime.substring(0, 19);
		expires_time = expires_time.replace(/-/g, '/');
		return Math.round(new Date(expires_time).getTime() / 1000);
	},
	/**
	 * 求百分比
	 * num当前数
	 * total总数
	 */
	 GetPercent(num, total) {
	    num = parseFloat(num);
	    total = parseFloat(total);
	    if (isNaN(num) || isNaN(total)) {
	        return "-";
	    }
	    // return total <= 0 ? "0%" : (Math.round(num / total * 10000) / 100.00)+"%";
	    return total <= 0 ? "" : (Math.round(num / total * 10000) / 100.00);
	},
	$h: {
		//除法函数，用来得到精确的除法结果
		//说明：javascript的除法结果会有误差，在两个浮点数相除的时候会比较明显。这个函数返回较为精确的除法结果。
		//调用：$h.Div(arg1,arg2)
		//返回值：arg1除以arg2的精确结果
		Div: function(arg1, arg2) {
			arg1 = parseFloat(arg1);
			arg2 = parseFloat(arg2);
			var t1 = 0,
				t2 = 0,
				r1, r2;
			try {
				t1 = arg1.toString().split(".")[1].length;
			} catch (e) {}
			try {
				t2 = arg2.toString().split(".")[1].length;
			} catch (e) {}
			r1 = Number(arg1.toString().replace(".", ""));
			r2 = Number(arg2.toString().replace(".", ""));
			return this.Mul(r1 / r2, Math.pow(10, t2 - t1));
		},
		//加法函数，用来得到精确的加法结果
		//说明：javascript的加法结果会有误差，在两个浮点数相加的时候会比较明显。这个函数返回较为精确的加法结果。
		//调用：$h.Add(arg1,arg2)
		//返回值：arg1加上arg2的精确结果
		Add: function(arg1, arg2) {
			arg2 = parseFloat(arg2);
			var r1, r2, m;
			try {
				r1 = arg1.toString().split(".")[1].length
			} catch (e) {
				r1 = 0
			}
			try {
				r2 = arg2.toString().split(".")[1].length
			} catch (e) {
				r2 = 0
			}
			m = Math.pow(100, Math.max(r1, r2));
			return (this.Mul(arg1, m) + this.Mul(arg2, m)) / m;
		},
		//减法函数，用来得到精确的减法结果
		//说明：javascript的加法结果会有误差，在两个浮点数相加的时候会比较明显。这个函数返回较为精确的减法结果。
		//调用：$h.Sub(arg1,arg2)
		//返回值：arg1减去arg2的精确结果
		Sub: function(arg1, arg2) {
			arg1 = parseFloat(arg1);
			arg2 = parseFloat(arg2);
			var r1, r2, m, n;
			try {
				r1 = arg1.toString().split(".")[1].length
			} catch (e) {
				r1 = 0
			}
			try {
				r2 = arg2.toString().split(".")[1].length
			} catch (e) {
				r2 = 0
			}
			m = Math.pow(10, Math.max(r1, r2));
			//动态控制精度长度
			n = (r1 >= r2) ? r1 : r2;
			return ((this.Mul(arg1, m) - this.Mul(arg2, m)) / m).toFixed(n);
		},
		//乘法函数，用来得到精确的乘法结果
		//说明：javascript的乘法结果会有误差，在两个浮点数相乘的时候会比较明显。这个函数返回较为精确的乘法结果。
		//调用：$h.Mul(arg1,arg2)
		//返回值：arg1乘以arg2的精确结果
		Mul: function(arg1, arg2) {
			arg1 = parseFloat(arg1);
			arg2 = parseFloat(arg2);
			var m = 0,
				s1 = arg1.toString(),
				s2 = arg2.toString();
			try {
				m += s1.split(".")[1].length
			} catch (e) {}
			try {
				m += s2.split(".")[1].length
			} catch (e) {}
			return Number(s1.replace(".", "")) * Number(s2.replace(".", "")) / Math.pow(10, m);
		},
	},
	// 获取地理位置;
	$L: {
		async getLocation() {
			// #ifdef APP-PLUS
			let status = await this.checkPermission();
			if (status !== 1) {
				return;
			}
			// #endif
			// #ifdef MP-WEIXIN || MP-TOUTIAO || MP-QQ
			let settingStatus = await this.getSetting();
			if (settingStatus === 2) {
				this.openSetting();
				return;
			}
			// #endif

			this.doGetLocation();
		},
		doGetLocation() {
			uni.getLocation({
				success: (res) => {
					uni.removeStorageSync('CACHE_LONGITUDE');
					uni.removeStorageSync('CACHE_LATITUDE');
					uni.setStorageSync('CACHE_LONGITUDE', res.longitude);
					uni.setStorageSync('CACHE_LATITUDE', res.latitude);
				},
				fail: (err) => {
					// #ifdef MP-BAIDU
					if (err.errCode === 202 || err.errCode === 10003) { // 202模拟器 10003真机 user deny
						this.openSetting();
					}
					// #endif
					// #ifndef MP-BAIDU
					if (err.errMsg.indexOf("auth deny") >= 0) {
						uni.showToast({
							title: `访问位置被拒绝`
						})
					} else {
						uni.showToast({
							title: err.errMsg
						})
					}
					// #endif
				}
			})
		},
		getSetting: function() {
			return new Promise((resolve, reject) => {
				uni.getSetting({
					success: (res) => {
						if (res.authSetting['scope.userLocation'] === undefined) {
							resolve(0);
							return;
						}
						if (res.authSetting['scope.userLocation']) {
							resolve(1);
						} else {
							resolve(2);
						}
					}
				});
			});
		},
		openSetting: function() {
			uni.openSetting({
				success: (res) => {
					if (res.authSetting && res.authSetting['scope.userLocation']) {
						this.doGetLocation();
					}
				},
				fail: (err) => {}
			})
		},
		async checkPermission() {
			let status = permision.isIOS ? await permision.requestIOS('location') :
				await permision.requestAndroid('android.permission.ACCESS_FINE_LOCATION');

			if (status === null || status === 1) {
				status = 1;
			} else if (status === 2) {
				uni.showModal({
					content: `系统定位已关闭`,
					confirmText: `确定`,
					showCancel: false,
					success: function(res) {}
				})
			} else if (status.code) {
				uni.showModal({
					content: status.message
				})
			} else {
				uni.showModal({
					content: `需要定位权限`,
					confirmText: `确定`,
					success: function(res) {
						if (res.confirm) {
							permision.gotoAppSetting();
						}
					}
				})
			}
			return status;
		},
	}

}
