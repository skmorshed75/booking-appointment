module.exports = {
    module: {
        rules: [{
            test: /\.(js|jsx)$/,
            exclude: /node_modules/,
            use: {
                loader: "babel-loader"
            }
        },
        {
			test: /\.(woff|woff2|eot|ttf|otf|png|jpg|gif|svg)(\?v=\d+\.\d+\.\d+)?$/,
			use: [
				{
					// With file loader which copies file
					// and brings in URL to the file
					loader: 'file-loader',
					options: {
						name: '[name].[ext]',
						outputPath: 'assets/',
					},
				},
			],
		},
		{
	        test: /\.css$/i,
	        use: ['style-loader', 'css-loader'],
	     }]
    }
};