//vue.config.js
const TransformPages = require('uni-read-pages')
const {webpack} = new TransformPages()
module.exports = {
	configureWebpack: {
		plugins: [
			new webpack.DefinePlugin({
				ROUTES: webpack.DefinePlugin.runtimeValue(() => {
					const tfPages = new TransformPages({
						includes: ['path', 'name', 'aliasPath']
					});
					return JSON.stringify(tfPages.routes)
				}, true )
			})
		]
	},
	transpileDependencies: ['uview-ui'],
	chainWebpack: config => {
		config.plugin('define').tap(args => {
			args[0]['process.env'].VUE_APP_I18N_LOCALE = JSON.stringify('zh-CN')
			args[0]['process.env'].VUE_APP_I18N_FALLBACK_LOCALE = JSON.stringify('zh-CN')
			return args
		})
	}
}