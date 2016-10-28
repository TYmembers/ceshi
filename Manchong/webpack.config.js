var webpack =  require('webpack');

module.exports = {
    entry:[
        './app/app.js'
    ],
    output:{
        path:'./build',
        filename:'bundle.js'
    },
    plugin:[
        new webpack.NoErrorsPlugin()
    ]
}