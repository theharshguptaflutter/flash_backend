// const mysqlDB = require('./repositery/db_conn.js');

var express = require('express');
var http = require('http');
var app = express();
var server = http.createServer(app);

// const mysqlDB = require('./db_conn.js');
// const mysqlDB = require('./config/db.config');

const mysqlDB = require('./repositery/db_conn.js');
const axios = require('axios');

var io = require('socket.io').listen(server, { path: '/chat/socket.io' });
var token = [];

let numUsers = 0;
let onLine = {};
var userData_1 = {};
var fare_range = {};
var userData = {};
var customerData = {};
var rejectData = {};
var otpData = {};
var otpVerifyData = {};
var acceptData = {};
var rideCompleteData = {};

const TBL_USERS = 'users';
const TBL_DRIVER_CAR_AVAILABLE = 'driver_car_availables';
const TBL_DRIVER_DETAILS = 'driver_details';
const TBL_CAR_MODELS = 'car_models';
const TBL_PASSENGER_RIDE_DETAIL = 'passenger_ride_details';
const TBL_USER_TOKEN = 'user_tokens';
const TBL_NOTIFICATION = 'notifications';



/**
 * Shear the current location from app
 * @param {*} req 
 * @param {*} res 
 * @param {*} next 
 * @returns 
 */

exports.getLocation = function(data) {

    socket.on('driverLocationSentFromDriverApp', function (data) {
    
        data = typeof data == 'object' ? data : JSON.parse(data);
        if (data.user_type == 'driver') {
            let lat = data.lat;
            let lng = data.lng;
            let driver_id = data.driver_id;
            let status = data.status;
            let car_type = data.car_type;
            const ADD_USER = function (lat, lng, driver_id, status, car_type) {
                var R = 3958.8;
                var rlat1 = lat * (Math.PI / 180);
                mysqlDB.fetchData(TBL_USERS, "user_type='P'", 'id,cur_lat,cur_long,avg_rating', '', '', '', function (userErr, user) {
                    if (userErr) console.log(userErr);
                    else if (user) {
                        console.log('test user', user);
                        user.forEach(element => {
                            let rlat2 = element.cur_lat * (Math.PI / 180);
                            let difflat = rlat2 - rlat1;
                            var difflon = (element.cur_long - lng) * (Math.PI / 180);
                            let d = 2 * R * Math.asin(Math.sqrt(Math.sin(difflat / 2) * Math.sin(difflat / 2) + Math.cos(rlat1) * Math.cos(rlat2) * Math.sin(difflon / 2) * Math.sin(difflon / 2)));
                            console.log('distance', d);
                            if (d < 300) {
                                userData_1['lat'] = lat;
                                userData_1['lng'] = lng;
                                userData_1['driver_id'] = driver_id;
                                userData_1['status'] = status;
                                userData_1['car_type'] = car_type;
                                userData_1['avg_rating'] = element.avg_rating;
                                console.log('User Data', userData_1);
    
                                socket.join("roomTest-" + element.id);
                                io.sockets.in("roomTest-" + element.id).emit('driverLocationSentToCustomerApp', userData_1);
                            }
                        });
                    }
                });
            };
            ADD_USER(lat, lng, driver_id, status, car_type);
        } else {
            let customer_id = data.customer_id;
            let customer_lat = data.customer_lat;
            let customer_lng = data.customer_lng;
    
            mysqlDB.updateCusLat(TBL_USERS, "id=" + customer_id, customer_lat, function (tRideErr, onTRide) {
                if (tRideErr) console.log(tRideErr);
                else if (onTRide) {
                    console.log(onTRide);
                }
            });
            mysqlDB.updateCusLng(TBL_USERS, "id=" + customer_id, customer_lng, function (tRideErr, onTRide) {
                if (tRideErr) console.log(tRideErr);
                else if (onTRide) {
                    console.log(onTRide);
                }
            });
            console.log('Customer data', customer_id, customer_lat, customer_lng);
            socket.join("roomTest-" + customer_id);
        }
    });
}