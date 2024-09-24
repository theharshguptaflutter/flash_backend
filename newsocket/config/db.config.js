const mysql = require('mysql2');
//Server
// const con = mysql.createPool({
//     host: 'flashdbinstance.cdjfcsodhvgi.us-east-1.rds.amazonaws.com',
//     database: 'flash_dev',
//     user: 'admin',
//     password: 'fsAWuvb6tKcC0Ajophye'
// });

//Local Server
const con = mysql.createPool({
    host: 'localhost',
    database: 'flash',
    user: 'root',
    password: ''
});

con.getConnection(function(error) {
    con.on('error', function(err) {
        console.log("[mysql error]", err);
    });
    //console.log("mysql work successfully.")
});

module.exports = { 
    con 
};