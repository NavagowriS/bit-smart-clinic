const path = require( "path" );

module.exports = {
    resolve: {
        extensions: [".js", ".css", ".vue", ".jpg"],
        alias: {
            "@": path.resolve( __dirname, "./vue" ),
        }
    }
};
