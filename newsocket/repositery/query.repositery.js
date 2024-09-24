var { con } = require('../config/db.config');

exports.fetchData = function (table, conditions, parameters, orderBy, limit, offset, callback) {
    var res = "";
    var cond = " ";
    if (typeof conditions !== 'undefined' && conditions !== '') {
        cond += "WHERE " + conditions;
    }
    var param = "*";
    if (typeof parameters !== 'undefined' && parameters !== '') {
        param = parameters;
    }
    var order = " ";
    if (typeof orderBy !== 'undefined' && orderBy !== '') {
        order += "ORDER BY " + orderBy;
    }
    var lim = " ";
    if (typeof limit !== 'undefined' && limit !== '') {
        lim += "LIMIT " + limit;
    }
    var offs = " ";
    if (typeof offset !== 'undefined' && offset !== '') {
        offs += "OFFSET " + offset;
    }

    var sql = "SELECT " + param + " FROM " + table + cond + order + lim + offs;
    var queryData = con.query(sql, function (err, result, fields) {
        // console.log(queryData.sql);
        con.on('error', function (err) {
            console.log("[mysql error]", err);
            return callback(err);
        });
        return callback(null, result);
    });
};

exports.insertData = function (table, values, callback) {
    var sql = "INSERT INTO " + table + " SET ?";
    var queryData = con.query(sql, values, function (err, result) {
        // console.log(queryData.sql);
        con.on('error', function (err) {
            console.log("[mysql error]", err);
            return callback(err);
            throw err;
        });
        return callback(null, result);
    });
};

exports.insertPassangerRideData = function (table, customerId, fromAddress, toAddress, fromLat, fromLng, toLat, toLng, seat, carType, distance, fare, estimatedFare, paidBy, callback) {
    var sql = "INSERT INTO " + table + " (id,passenger_id,driver_id,from_address,to_address,from_latitude,from_longitude,to_latitude,to_longitude,seat_no,schedule_date,schedule_time,car_type,distance,estimated_distance,total_distance,fare,estimated_fare,total_fare,coupon_id,discount,ride_rating,paid_by,paid_status,cancel_ride_by,cancel_reason,refund_status,trip_status,start_trip_date,end_trip_date,created_at,updated_at,ride_time,otp) VALUES (''," + customerId + ",'','" + fromAddress + "','" + toAddress + "'," + fromLat + "," + fromLng + "," + toLat + "," + toLng + "," + seat + ",'',''," + carType + "," + distance + ",'',''," + fare + "," + estimatedFare + ",'','','',''," + paidBy + ",''," + 0 + ",'','','','','','','','','')";
    // console.log('sql', sql);
    var queryData = con.query(sql, function (err, result) {
        console.log('query', queryData.sql);
        console.log('result', result, err);
        con.on('error', function (err) {
            console.log("[mysql error]", err);
            return callback(err);
            throw err;
        });
        return callback(null, result);
    });
};

exports.insertNotification = function (table, ride_id, sender_id, receiver_id, notification_type, title, callback) {
    var currentdate = new Date();
    var datetime = currentdate.getFullYear() + "-" + (currentdate.getMonth() + 1) + "-" + currentdate.getDate() + " "
        + currentdate.getHours() + ":"
        + currentdate.getMinutes() + ":"
        + currentdate.getSeconds();
    var sql = "INSERT INTO " + table + " (ride_id,sender_id,receiver_id,is_read,notification_type,title,created_at) VALUES (" + ride_id + "," + sender_id + "," + receiver_id + ",0," + notification_type + ",'" + title + "','" + datetime + "')";
    // console.log('sql', sql);
    var queryData = con.query(sql, function (err, result) {
        console.log('query', queryData.sql);
        console.log('result', result, err);
        con.on('error', function (err) {
            console.log("[mysql error]", err);
            return callback(err);
            throw err;
        });
        return callback(null, result);
    });
};

exports.insertRideHistory = function (table, ride_id, driver_id, step_number, step_name, status, callback) {
    // var currentdate = new Date();
    // var datetime = currentdate.getFullYear() + "-" + (currentdate.getMonth() + 1) + "-" + currentdate.getDate() + " "
    //     + currentdate.getHours() + ":"
    //     + currentdate.getMinutes() + ":"
    //     + currentdate.getSeconds();

    var sql = "INSERT INTO "+table+" (driver_id,ride_id,step_number,step_name,status) VALUES ("+driver_id+","+ride_id +", "+step_number+", 'accept by driver', 'pending')";
    console.log('Ride History SQL - ', sql);
    
    var queryData = con.query(sql, function (err, result) {
        console.log('query', queryData.sql);
        console.log('result', result, err);
        con.on('error', function (err) {
            console.log("[mysql error]", err);
            return callback(err);
            throw err;
        });

        return callback(null, result);
    });
};

exports.insertRideData = function (table, ride_id, driver_id, step_number, step_name, status, callback) {
    // var currentdate = new Date();
    // var datetime = currentdate.getFullYear() + "-" + (currentdate.getMonth() + 1) + "-" + currentdate.getDate() + " "
    //     + currentdate.getHours() + ":"
    //     + currentdate.getMinutes() + ":"
    //     + currentdate.getSeconds();

    var sql = "INSERT INTO "+table+" (driver_id,ride_id,step_number,step_name,status) VALUES ("+ driver_id +","+ ride_id +", "+ step_number +", '"+ step_name +"', '"+ status +"')";
    console.log('Ride History SQL - ', sql);
    
    var queryData = con.query(sql, function (err, result) {
        console.log('query', queryData.sql);
        console.log('result', result, err);
        con.on('error', function (err) {
            console.log("[mysql error]", err);
            return callback(err);
            throw err;
        });

        return callback(null, result);
    });
};


exports.updateData = function (table, conditions, values, callback) {
    var cond = " ";
    if (typeof conditions !== 'undefined') {
        cond += "WHERE " + conditions;
    }
    var sql = "UPDATE " + table + " SET ?" + cond;
    var queryData = con.query(sql, values, function (err, result) {
        // console.log(queryData.sql);
        con.on('error', function (err) {
            console.log("[mysql error]", err);
            return callback(err);
        });
        return callback(null, result);
    });
};

exports.updateCancel = function (table, conditions, values, callback) {
    var cond = " ";
    if (typeof conditions !== 'undefined') {
        cond += "WHERE " + conditions;
    }
    var sql = "UPDATE " + table + " SET cancel_ride_by=" + values + cond;
    var queryData = con.query(sql, values, function (err, result) {
        // console.log(queryData.sql);
        con.on('error', function (err) {
            console.log("[mysql error]", err);
            return callback(err);
        });
        return callback(null, result);
    });
};

exports.updateCusLat = function (table, conditions, values, callback) {
    var cond = " ";
    if (typeof conditions !== 'undefined') {
        cond += "WHERE " + conditions;
    }
    var sql = "UPDATE " + table + " SET cur_lat=" + values + cond;
    var queryData = con.query(sql, values, function (err, result) {
        // console.log(queryData.sql);
        con.on('error', function (err) {
            console.log("[mysql error]", err);
            return callback(err);
        });
        return callback(null, result);
    });
};

exports.updateCusLng = function (table, conditions, values, callback) {
    var cond = " ";
    if (typeof conditions !== 'undefined') {
        cond += "WHERE " + conditions;
    }
    var sql = "UPDATE " + table + " SET cur_long=" + values + cond;
    var queryData = con.query(sql, values, function (err, result) {
        // console.log(queryData.sql);
        con.on('error', function (err) {
            console.log("[mysql error]", err);
            return callback(err);
        });
        return callback(null, result);
    });
};

exports.updateRideDriver = function (table, conditions, values, callback) {
    var cond = " ";
    if (typeof conditions !== 'undefined') {
        cond += "WHERE " + conditions;
    }
    var sql = "UPDATE " + table + " SET driver_id =" + values + cond;
    var queryData = con.query(sql, values, function (err, result) {
        // console.log(queryData.sql);
        con.on('error', function (err) {
            console.log("[mysql error]", err);
            return callback(err);
        });
        return callback(null, result);
    });
};

exports.verifyOTP = function (table, conditions, values, callback) {
    var cond = " ";
    if (typeof conditions !== 'undefined') {
        cond += "WHERE " + conditions;
    }
    var sql = "UPDATE " + table + " SET ride_time ='" + values + "'" + cond;
    var queryData = con.query(sql, values, function (err, result) {
        // console.log(queryData.sql);
        con.on('error', function (err) {
            console.log("[mysql error]", err);
            return callback(err);
        });
        return callback(null, result);
    });
};

exports.updateRideOTP = function (table, conditions, values, callback) {
    var cond = " ";
    if (typeof conditions !== 'undefined') {
        cond += "WHERE " + conditions;
    }
    var sql = "UPDATE " + table + " SET otp =" + values + cond;
    var queryData = con.query(sql, values, function (err, result) {
        // console.log(queryData.sql);
        con.on('error', function (err) {
            console.log("[mysql error]", err);
            return callback(err);
        });
        return callback(null, result);
    });
};

exports.deleteData = function (table, conditions) {
    var cond = " ";
    if (typeof conditions !== 'undefined') {
        cond += "WHERE " + conditions;
    }
    var sql = "DELETE FROM " + table + cond;
    var queryData = con.query(sql, function (err, result) {
        // console.log(queryData.sql);
        con.on('error', function (err) {
            console.log("[mysql error]", err);
            throw err;
        });
    });
};