const mysql = require('mysql2');
var con = mysql.createPool({
    host: 'flashdbinstance.cdjfcsodhvgi.us-east-1.rds.amazonaws.com',
    database: 'flash_dev',
    user: 'admin',
    password: 'fsAWuvb6tKcC0Ajophye'
});

con.getConnection(function(error) {
    con.on('error', function(err) {
        console.log("[mysql error]", err);
    });
    //console.log("mysql work successfully.")
});

exports.fetchData = function (table, conditions, parameters, orderBy, limit, offset, callback) {
    var res = "";
    var cond = " ";
    if(typeof conditions  !== 'undefined' && conditions !== '')
    {
        cond += "WHERE "+conditions;
    }
    var param = "*";
    if(typeof parameters  !== 'undefined' && parameters !== '')
    {
        param = parameters;
    }
    var order = " ";
    if(typeof orderBy  !== 'undefined' && orderBy !== '')
    {
        order += "ORDER BY "+orderBy;
    }
    var lim=" ";
    if(typeof limit  !== 'undefined' && limit !== '')
    {
        lim += "LIMIT "+limit;
    }
    var offs =" ";
    if(typeof offset  !== 'undefined' && offset !== '')
    {
        offs += "OFFSET "+offset;
    }
    var sql = "SELECT " + param + " FROM " + table + cond + order + lim + offs;
    var queryData = con.query(sql, function (err, result, fields) {
        console.log(queryData.sql);
        con.on('error', function(err) {
            console.log("[mysql error]",err);
            return callback(err);
        });
        return callback(null, result);
    });
};

exports.insertData = function (table, values, callback) {
    var sql = "INSERT INTO "+table+" SET ?";
    var queryData = con.query(sql, values, function (err, result) {
        console.log(queryData.sql);
        con.on('error', function(err) {
            console.log("[mysql error]",err);
            return callback(err);
            throw err;
        });
        return callback(null, result);
    });
};

exports.updateData = function (table, conditions, values,callback) {
    var cond=" ";
    if(typeof conditions  !== 'undefined')
    {
        cond += "WHERE " + conditions;
    }
    var sql = "UPDATE " + table + " SET ? " + cond;
    var queryData = con.query(sql, values, function (err, result) {
        console.log(queryData.sql);
        con.on('error', function(err) {
            console.log("[mysql error]",err);
            return callback(err);
        });
        return callback(null, result);
    });
};

exports.deleteData = function(table, conditions) {
    var cond=" ";
    if(typeof conditions  !== 'undefined')
    {
        cond += "WHERE "+conditions;
    }
    var sql = "DELETE FROM "+table+cond;
    var queryData = con.query(sql, function (err, result) {
        console.log(queryData.sql);
        con.on('error', function(err) {
            console.log("[mysql error]",err);
            throw err;
        });
    });
};