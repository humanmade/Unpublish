const path = require( 'path' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );

module.exports = function( env, args ) {
	const { mode = 'production' } = args;

	return {
		mode,
		entry: {
			'block-editor': './assets/src/block-editor.js',
			'classic-editor': './assets/src/classic-editor.js',
		},
		output: {
			filename: '[name].js',
			libraryTarget: 'this',
			path: path.resolve( __dirname, 'assets', 'dist' ),
		},
		externals: {
			'@wordpress/api-fetch': { this: [ 'wp', 'apiFetch' ] },
			'@wordpress/compose': { this: [ 'wp', 'compose' ] },
			'@wordpress/components': { this: [ 'wp', 'components' ] },
			'@wordpress/data': { this: [ 'wp', 'data' ] },
			'@wordpress/dom-ready': { this: [ 'wp', 'domReady' ] },
			'@wordpress/edit-post': { this: [ 'wp', 'editPost' ] },
			'@wordpress/editor': { this: [ 'wp', 'editor' ] },
			'@wordpress/element': { this: [ 'wp', 'element' ] },
			'@wordpress/i18n': { this: [ 'wp', 'i18n' ] },
			'@wordpress/plugins': { this: [ 'wp', 'plugins' ] },
			'@wordpress/url': { this: [ 'wp', 'url' ] },
			'jQuery': { this: [ 'jquery' ] },
		},
		module: {
			rules: [
				{
					test: /\.js$/,
					exclude: /node_modules/,
					use: {
						loader: 'babel-loader',
						options: {
							presets: [ '@wordpress/default' ],
						},
					},
				},
				{
					test: /\.s[ac]ss$/,
					exclude: /node_modules/,
					use: [
						MiniCssExtractPlugin.loader,
						{
							loader: 'css-loader',
							options: {
								importLoaders: 1,
								sourceMap: true,
							},
						},
						{
							loader: 'sass-loader',
							options: {
								sourceMap: true,
							},
						},
					],
				},
			],
		},
		plugins: [
			new MiniCssExtractPlugin( {
				filename: '[name].css',
				chunkFilename: '[id].css',
			} ),
		],
	};
};
