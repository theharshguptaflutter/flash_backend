var express = require('express');
var http = require('http');
var app = express();
var server = http.createServer(app);
const mysqlDB = require('./db_conn.js');

var io = require('socket.io').listen(server, { path: '/chat/socket.io' });

let numUsers = 0;
let onLine = {};
let userData = {};

const TBL_USERS = 'users';
const TBL_DRIVER_CAR_AVAILABLE = 'driver_car_availables';


app.get('/', function(req, res) {
   res.send('<h1>Hello test world</h1>');
   // res.statusCode = 200;
   // res.setHeader('Content-Type', 'text/plain');
   // res.end('Hello World!\n');
});

server.listen(4001, function() {
   console.log('listening on localhost:4001');
  
});


io.on('connection', function(socket) {   
   console.log('Socket connection Successfull'); return;

   let addedUser = false,disconnected = false, addingUser = false, lat, lng, locality, totalReqDrivers = [], currentRequestDrivers = [],
	userTaxiDetails, myRoom = null, searchDriver = false, PASSANGER_TIMEOUT, DRIVER_TIMEOUT, driverReqData = {}, taxiReqId = 0, taxiType = [],
	 first = true, onTaxiRide, pendingTaxiRide=[], pushArrivedDriver = true;


   let driverStatus = function (data) {
		if (addedUser && !disconnected) {
			let driverDetails = {
				id: userData[socket.username].id,
				full_name: userData[socket.username].full_name,
				country_code: userData[socket.username].country_code,
				mobile: userData[socket.username].mobile,
				profile_picture: userData[socket.username].profile_picture
			}
			let condition = "driver_id=" + data.driverId;


			mysqlDB.fetchData(TBL_DRIVER_CAR_AVAILABLE, condition , "", "", "", "", function (resErrs, resData) {
				if (resErrs) {
					console.log("resErrs",resErrs);
				}else{
					if (resData[0].for_hire == "P") {

					}
					else if(resData[0].for_hire != "P" && data.task == "adduser"){
						mysqlDB.updateData(TBL_DRIVER_CAR_AVAILABLE, "driver_id=" + data.driverId, {                          
							is_available: "Y",
							is_requested: "N",
							for_hire: "Y",
							updated_at: new Date()
						});
					}
					// else if(resData[0].forHire != "P" && data.task == "disconnected"){
					else if(resData[0].for_hire != "P" && data.task == "disconnected"){ //need to change 
                  console.log(resData[0].for_hire);
                  mysqlDB.updateData(TBL_DRIVER_CAR_AVAILABLE, "driver_id=" + data.driverId, {                          
                     is_available: "N",
                     is_requested: "N",
                     for_hire: "N",
                     updated_at: new Date()
                  });
               }
               else if(resData[0].for_hire != "P" && data.task == "location"){
                  mysqlDB.updateData(TBL_DRIVER_CAR_AVAILABLE, "driver_id=" + data.driverId, {                          
                     is_available: "Y",
                     is_requested: "N",
                     for_hire: "Y",
                     updated_at: new Date()
                  });
               }
            }
         });
		}
	};

   // current location
   socket.on('location', async function (data) {
      data = typeof data=='object'?data:JSON.parse(data);
     if (disconnected){ console.log("LOCATION SHARING disconnected"); return}; 
     if (!addedUser || onLine[socket.username] == void 0){ 
        addedUser = false;
        ADD_USER(data.userId, data.usertype); 
        return;
     };
     if (typeof data.locality == 'undefined' || data.locality == '' || data.locality == null || typeof data.lat == 'undefined' || data.lat == '' || data.lat == null || typeof data.lng == 'undefined' || data.lng == '' || data.lng == null) { console.log("LOCATION SHARING invalid data", data); return};
     //LOCATION_SHARING(data);
   });

   const ADD_USER = function (userId, userType='P') {
      if (addedUser) return;
      if (!addingUser) {
        addingUser = true;
        let usrid = parseInt(userId);
        let usrtype = userType.toString();
        socket.username = usrid;
        socket.usertype = usrtype;
        userData[usrid] = {};
        onLine[usrid] = socket.id;
        userData[usrid].onRide = false;
        ++numUsers;
        mysqlDB.fetchData(TBL_USERS, 'id=' + parseInt(socket.username), 'id,full_name,country_code,mobile,profile_picture', '', '', '', function (userErr, user) {
           if (userErr) console.log(userErr);
           else if(user.length > 0) {
              userData[usrid].id = user[0].id;
              userData[usrid].full_name = user[0].full_name;
              userData[usrid].country_code = user[0].country_code;
              userData[usrid].mobile = user[0].mobile;
              userData[usrid].profile_picture = user[0].profile_picture;
              userData[usrid].searchDriver = false;
              userData[usrid].driverAccepted = false;
              addedUser = true;
              addingUser = false;
              console.log("***************************************************************************************************");
              console.log("Connected user", userData[usrid].id, " and socket id ", onLine[usrid], " usrtype ", usrtype, " Name =>", userData[usrid].full_name + " " + userData[usrid].mobile );
           }
        });

        if (usrtype === 'D') {
           mysqlDB.fetchData(TBL_DRIVER_CAR_AVAILABLE, "driver_id=" + usrid, "", "", "", "", function (uTaxiErr, uTaxiData) {
              if (uTaxiErr) console.log(uTaxiErr);
               else if (uTaxiData.length > 0) {
                 //userData[usrid].taxiType = [];
                  // uTaxiData.forEach(function (elem) {
                  //    userData[usrid].taxiType.push(parseInt(elem.taxiTypeId));
                  //    taxiType.push(parseInt(elem.taxiTypeId));
                  // });
                  //let query = "SELECT uc.id, uc.userId, uc.numberPlate, uc.make, uc.model, uc.colour, uc.year, ucc.colour, ucc.hexCode FROM " + USER_CAR_TABLE + " AS uc LEFT JOIN " + USER_CAR_COLOUR_TABLE + " AS ucc ON uc.colour = ucc.id WHERE uc.userId=" + usrid;
                  // mysqlDB.querying(query, function (uCarErr, uCarData) {
                  //    if (uCarErr)  console.log(uCarErr);
                  //    userTaxiDetails = uCarData;


                     // let query = "SELECT tr.*, ut.userId FROM " + TAXI_RIDE_TABLE + " AS tr LEFT JOIN " + USER_TAXI_TABLE + " AS ut ON tr.userTaxiId = ut.id WHERE ut.userId=" + usrid + " AND tr.cancel='N' AND tr.status IN ('S', 'A', 'R')";
                     // mysqlDB.querying(query, function (tRideErr, onTRide) {
                     //    if (tRideErr) console.log(tRideErr);
                     //    else {
                     //       if (onTRide.length > 0) {
                     //          userData[usrid].onRide = true;
                     //          onTaxiRide = onTRide[0];
                     //          myRoom = parseInt(onTaxiRide.id);
                     //          socket.join(myRoom);
                     //          socket.room = myRoom;
                     //          mysqlDB.updating(USER_TAXI_TABLE, "userId=" + usrid, { available: "Y", requested: "N", forHire: "N", updated_at: new Date() });
                     //       } else {
                     //          mysqlDB.updating(USER_TAXI_TABLE, "userId=" + usrid, { available: "Y", requested: "N", forHire: "Y", updated_at: new Date() });
                     //          /** Need to verify driver Status function by anindita*/
                     //          data = {
                     //             'driverId': usrid,
                     //             'task': 'adduser',
                     //          }
                     //          driverStatus(data);
                     //       }
                     //    }
                     // });




                  //});
               }
           });
        }

      //   if (usrtype === 'P') {
      //       mysqlDB.fetching(TAXI_RIDE_TABLE, "passengerId=" + usrid + " AND status IN ('S', 'A')", "", "", "", "", function (tRideErr, onTRide) {
      //             if (tRideErr) console.log(tRideErr);
      //             else if (onTRide.length > 0) {
      //                userData[usrid].onRide = true;
      //                onTaxiRide = onTRide[0];
      //                myRoom = parseInt(onTaxiRide.id);
      //                socket.join(myRoom);
      //                socket.room = myRoom;
      //             }
      //       });
      //   }
     }
     return;
   };























































});


