var webpack = require('webpack');
var config = require('../webpack.config.js');

config.watch = false;

config.entry = {
	'ajax-load-more-layouts.min': './src/main.scss'
};

config.plugins.push(
	new webpack.DefinePlugin({
		'process.env': {
			NODE_ENV: '"production"'
		}
	})
);

module.exports = config;
