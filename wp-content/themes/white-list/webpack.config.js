const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = (env) => {
    const isDevelopment = env.NODE_ENV === 'development';
    const isProduction = env.NODE_ENV === 'production';

    return {
        mode: isProduction ? 'production' : 'development',
        entry: {
            frontend: [
                path.resolve(__dirname, 'src/js/frontend.js'),
                path.resolve(__dirname, 'src/sass/frontend.scss'),
            ],
        },
        output: {
            path: path.resolve(__dirname, 'build'),
            filename: 'js/[name].js',
            clean: false, // Don't clean output directory to preserve other files
        },
        devtool: isDevelopment ? 'source-map' : false,
        module: {
            rules: [
                {
                    test: /\.tsx?$/,
                    exclude: /node_modules/,
                    use: [
                        {
                            loader: 'ts-loader',
                            options: {
                                configFile: path.resolve(__dirname, 'tsconfig.json'),
                            },
                        },
                    ],
                },
                {
                    test: /\.css$/i,
                    use: [
                        MiniCssExtractPlugin.loader,
                        'css-loader',
                        'postcss-loader'
                    ]
                },
                {
                    test: /\.s[ac]ss$/i,
                    exclude: /node_modules/,
                    use: [
                        MiniCssExtractPlugin.loader,
                        'css-loader',
                        'postcss-loader',
                        {
                            loader: 'sass-loader',
                            options: {
                                sassOptions: {
                                    outputStyle: 'expanded',
                                },
                            },
                        },
                    ],
                },
            ],
        },
        plugins: [
            new MiniCssExtractPlugin({
                filename: 'css/[name].css',
            }),
        ],
        resolve: {
            extensions: ['.ts', '.tsx', '.js', '.jsx', '.json'],
            modules: [path.resolve(__dirname, 'src'), 'node_modules'],
        },
    };
};
