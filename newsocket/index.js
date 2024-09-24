var express = require('express');
var http = require('http');
var app = express();
var server = http.createServer(app);

// const mysqlDB = require('./db_conn.js');
// const mysqlDB = require('./config/db.config');

const axios = require('axios');
const mysqlDB = require('./repositery/query.repositery.js');

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
const TBL_RIDE_HOSTORY = 'ride_histories';

app.get('/', function (req, res) {
	res.statusCode = 200;
	res.setHeader('Content-Type', 'text/plain');
	res.end('Hello World!\n');
});


io.on('connection', function (socket) {
	console.log('A user connected');

	let addedUser = false, disconnected = false, addingUser = false, lat, lng, locality, totalReqDrivers = [], currentRequestDrivers = [],
		userTaxiDetails, myRoom = null, searchDriver = false, PASSANGER_TIMEOUT, DRIVER_TIMEOUT, driverReqData = {}, taxiReqId = 0, taxiType = [],
		first = true, onTaxiRide, pendingTaxiRide = [], pushArrivedDriver = true;

	socket.on('driverLocationSentFromDriverApp', function (data) {
		data = typeof data == 'object' ? data : JSON.parse(data);
		if (data.user_type == 'driver') {
			let lat = data.lat;
			let lng = data.lng;
			let driver_id = data.driver_id;
			let status = data.status;
			let car_type = data.car_type;
			// console.log('Driver Data', lat, lng, driver_id, status, car_type);

			const ADD_USER = function (lat, lng, driver_id, status, car_type) {
				var R = 3958.8;
				var rlat1 = lat * (Math.PI / 180);
				mysqlDB.fetchData(TBL_USERS, "user_type='P'", 'id,cur_lat,cur_long,avg_rating', '', '', '', function (userErr, user) {
					if (userErr) console.log(userErr);
					else if (user) {
						// console.log('User', user);
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
								// console.log('User Data', userData_1);

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
					// console.log(onTRide);
				}
			});
			mysqlDB.updateCusLng(TBL_USERS, "id=" + customer_id, customer_lng, function (tRideErr, onTRide) {
				if (tRideErr) console.log(tRideErr);
				else if (onTRide) {
					// console.log(onTRide);
				}
			});
			// console.log('Customer data', customer_id, customer_lat, customer_lng);
			socket.join("roomTest-" + customer_id);
		}
	});

	socket.on('requestCabFromCustomer', function (data) {
		console.log('Emit Ride Data requestCabFromCustomer', data, typeof data);
		data = typeof data == 'object' ? data : JSON.parse(data);
		if (data.user_type == 'customer') {
			let paidBy = data.paidBy;
			let customerId = data.customerId;
			let customerName = data.customerName;
			let customerMobile = data.customerMobile;
			let fromAddress = data.fromAddress;
			let toAddress = data.toAddress;
			let fromLat = data.fromLat;
			let fromLng = data.fromLng;
			let toLat = data.toLat;
			let toLng = data.toLng;
			let seat = data.seat;
			let scheduleDateTime = data.scheduleDateTime;
			let carType = data.carType;
			let distance = data.distance;
			let fare = data.fare;
			let customerImage = data.customerImage;
			let estimatedFare = data.estimatedFare;
			let driver_list = data.driverArray;

			const ADD_USER = function (driver_list, paidBy, customerId, customerName, customerMobile, fromAddress, toAddress, fromLat, fromLng, toLat, toLng, seat, scheduleDateTime, carType, distance, fare, estimatedFare) {
				userData['paidBy'] = paidBy;
				userData['customerId'] = customerId;
				userData['customerName'] = customerName;
				userData['customerMobile'] = customerMobile;
				userData['fromAddress'] = fromAddress;
				userData['toAddress'] = toAddress;
				userData['fromLat'] = fromLat;
				userData['fromLng'] = fromLng;
				userData['toLat'] = toLat;
				userData['toLng'] = toLng;
				userData['seat'] = seat;
				userData['scheduleDateTime'] = scheduleDateTime;
				userData['carType'] = carType;
				userData['distance'] = distance;
				userData['fare'] = fare;
				userData['estimatedFare'] = estimatedFare;
				userData['avg_rating'] = 4;
				userData['profile_image'] = customerImage;

				mysqlDB.insertPassangerRideData(TBL_PASSENGER_RIDE_DETAIL, customerId, fromAddress, toAddress, fromLat, fromLng, toLat, toLng, seat, carType, distance, fare, estimatedFare, paidBy, function (tRideErr, user) {
					if (tRideErr) console.log(tRideErr);
					else {
						console.log('user insert--Ride Id', user);
						userData['ride_id'] = user.insertId;
						customerData['ride_id'] = user.insertId;
						console.log('customerData------------------------------------------------------------>', customerData, user.insertId);
						console.log('driver data', userData);
						socket.join("roomForRideDetails-" + customerId);
						io.sockets.in("roomForRideDetails-" + customerId).emit('requestCabFromCustomer', customerData);
						driver_list.forEach(drivere => {
							if (carType == drivere.car_type) {
								axios.get('https://flashappza.com/api/v1/driverFirebase?id=' + drivere.driver_id + '&message=Hi!You have new Ride request.Please accept and earn')
									.then(response => {
										console.log(response.data.url);
										console.log(response.data.explanation);
									})
									.catch(error => {
										console.log(error);
									});
								socket.join("roomForRideDetails-" + drivere.driver_id);
								io.sockets.in("roomForRideDetails-" + drivere.driver_id).emit('requestCabFromCustomer', userData);
								mysqlDB.insertNotification(TBL_NOTIFICATION, user.insertId, 0, drivere.driver_id, 1, 'Hi!You have new Ride request.Please accept and earn', function (tNotiErr, notification) {
									if (tNotiErr) console.log(tNotiErr);
									else if (notification) {
										console.log('notification', notification);
									}
								});
							}
						});
					}
				});
				//For Room
			};
			ADD_USER(driver_list, paidBy, customerId, customerName, customerMobile, fromAddress, toAddress, fromLat, fromLng, toLat, toLng, seat, scheduleDateTime, carType, distance, fare, estimatedFare);
		} else {
			let driver_id = data.driver_id;
			console.log('driver_id driver_id', driver_id);
			socket.join("roomForRideDetails-" + driver_id);
		}
	});

	socket.on('requestCabFromCustomerTest', function (data) {
		console.log('EMITTED DATA FROM TEST', data);
		let customerId = 89;
		let fromAddress = 'Howrah';
		let toAddress = 'Kolkata';
		let fromLat = '22.45678';
		let fromLng = '88.087679';
		let toLat = '22.5726';
		let toLng = '88.3638';
		let seat = 3;
		let carType = 1;
		let distance = 5.70;
		let fare = 82.00;
		let estimatedFare = 82.00;

		mysqlDB.insertPassangerRideData(TBL_PASSENGER_RIDE_DETAIL, customerId, fromAddress, toAddress, fromLat, fromLng, toLat, toLng, seat, carType, distance, fare, estimatedFare, function (tRideErr, user) {
			if (tRideErr) console.log(tRideErr);
			else if (user) {
				userData['ride_id'] = user.insertId;
				console.log('user insert', user);
			}
		});

		//For Room
		socket.join("roomForRideDetailsTest");
		io.sockets.in("roomForRideDetailsTest").emit('requestCabFromCustomerTest', userData);
	});

	socket.on('rejectedByDriver', function (data) {
		console.log('Emit Data Rejected By Driver---------------------->>>>>>>>', data);
		data = typeof data == 'object' ? data : JSON.parse(data);
		let customer_id = data.customer_id;
		let driver_id = data.driverId;
		let ride_id = data.ride_id;
		if (data.user_type == 'driver') {
			mysqlDB.fetchData(TBL_PASSENGER_RIDE_DETAIL, "id=" + ride_id, 'driver_id,cancel_ride_by', '', '', '', function (userErr, ride) {
				if (userErr) console.log(userErr);
				else if (ride) {
					let fetchedDriver = ride[0].driver_id;
					if (fetchedDriver <= 0) {
						rejectData['customer_id'] = customer_id;
						rejectData['driver_id'] = driver_id;
						rejectData['ride_id'] = ride_id;
						rejectData['status'] = false;
						console.log('rejectData_id------------------------>>>>', customer_id, rejectData, "rejectedRoom-" + customer_id + ride_id);
						io.sockets.in("rejectedRoom-" + customer_id + ride_id).emit('rejectedByDriver', rejectData);
					}
				}
			});
		}
		if (data.user_type == 'customer') {
			console.log('customer_id', customer_id);
			let ride_id = data.ride_id;
			socket.join("rejectedRoom-" + customer_id + ride_id);
		}
	});

	socket.on('PriceRange', function (data) {
		console.log('Emit Data PriceRange', data);
		data = typeof data == 'object' ? data : JSON.parse(data);
		let customer_id = data.customer_id;
		let ride_id = data.ride_id;
		let distance_Of_travel = data.distance_Of_travel;
		let time_of_travel = data.time_of_travel;
		let car_type = data.car_type;

		let base = 0;
		let perKm = 0;
		let perMin = 0;
		let minimumFare = 0;
		if (car_type == 1) {
			base = 10.00;
			perKm = 7.80;
			perMin = 1.21;
			minimumFare = 35.00;
		}
		if (car_type == 2) {
			base = 20.00;
			perKm = 7.50;
			perMin = 1.21;
			minimumFare = 45.00;
		}
		if (car_type == 3) {
			base = 30.00;
			perKm = 10.00;
			perMin = 1.62;
			minimumFare = 65.00;
		}
		let priceBasedOnDistance = distance_Of_travel * perKm;
		let priceBasedOnTime = time_of_travel * perMin;
		// let priceTotal = Number(base).toFixed(2) + Number(priceBasedOnDistance).toFixed(2) + Number(priceBasedOnTime).toFixed(2);
		let priceTotal = base + priceBasedOnDistance + priceBasedOnTime;
		let fare = 0;
		if (priceTotal < minimumFare) {
			fare = Number(minimumFare).toFixed(2);
		} else {
			fare = priceTotal;
		}
		if ((fare - 200) < 35) {
			minPrice = parseInt(minimumFare);
		} else {
			minPrice = parseInt(fare - 200);
		}
		fare_range['min_price'] = minPrice;
		fare_range['max_price'] = parseInt(fare);
		socket.join("PriceRangeRoom-" + customer_id + ride_id);
		io.sockets.in("PriceRangeRoom-" + customer_id + ride_id).emit('PriceRange', fare_range);
	});

	socket.on('PriceSend', function (data) {
		console.log('Emit Data PriceRange', data);
		data = typeof data == 'object' ? data : JSON.parse(data);
		let user_id = data.user_id;
		let ride_id = data.ride_id;
		let price = data.price;
		socket.join("PriceRoom-" + user_id + ride_id);
		io.sockets.in("PriceRoom-" + user_id + ride_id).emit('PriceSend', price);
	});

	socket.on('CancelRequestFromCustomerEnd', function (data) {
		console.log('Emit Data Rejected By Driver', data);
		data = typeof data == 'object' ? data : JSON.parse(data);
		let driver_id = data.driver_id;
		if (data.user_type == 'customer') {
			let ride_id = data.ride_id;
			let driver_id = data.driver_id;
			let customer_id = data.customer_id;
			let is_canceled = data.is_canceled;

			rejectData['is_canceled'] = is_canceled;
			rejectData['driver_id'] = driver_id;
			rejectData['ride_id'] = ride_id;
			rejectData['status'] = false;
			console.log('Cancel from Customer / Driver End', driver_id, rejectData);
			//socket.join("CancelRequestFromCustomerEndRoom-"+driver_id+ride_id);
			mysqlDB.updateCancel(TBL_PASSENGER_RIDE_DETAIL, "id=" + ride_id, customer_id, function (tRideErr, onTRide) {
				if (tRideErr) console.log(tRideErr);
				else if (onTRide) {
					console.log('onTRide*******************', onTRide);
					io.sockets.in("CancelRequestFromCustomerEndRoom-" + driver_id + ride_id).emit('CancelRequestFromCustomerEnd', rejectData);
					axios.get('https://flashappza.com/api/v1/driverFirebase?id=' + driver_id + '&message=Oops Customer cancel the request')
						.then(response => {
							console.log(response.data.url);
							console.log(response.data.explanation);
						})
						.catch(error => {
							console.log(error);
						});
					mysqlDB.insertNotification(TBL_NOTIFICATION, ride_id, 0, driver_id, 2, 'Oops Customer cancel the request', function (tNotiErr, notification) {
						if (tNotiErr) console.log(tNotiErr);
						else if (notification) {
							console.log('notification', notification);
						}
					});
				}
			});
		}
		if (data.user_type == 'driver') {
			let ride_id = data.ride_id;
			console.log('Cancel from Customer / Customer End', driver_id);
			socket.join("CancelRequestFromCustomerEndRoom-" + driver_id + ride_id);
		}
	});

	socket.on('CancelRequestBeforeApprove', function (data) {
		console.log('Emit Data CancelRequestBeforeApprove', data);
		data = typeof data == 'object' ? data : JSON.parse(data);
		let ride_id = data.ride_id;
		let customer_id = data.customer_id;
		mysqlDB.updateCancel(TBL_PASSENGER_RIDE_DETAIL, "id=" + ride_id, customer_id, function (tRideErr, onTRide) {
			if (tRideErr) console.log(tRideErr);
			else if (onTRide) {
				console.log(onTRide);
			}
		});
	});


	socket.on('reachedToCustomerLocation', function (data) {
		console.log('Emit Data Reach to Customer', data);
		data = typeof data == 'object' ? data : JSON.parse(data);
		let customer_id = data.customer_id;
		if (data.user_type == 'driver') {
			let driver_id = data.driver_id;
			let ride_id = data.ride_id;
			let arrivingStatus = data.arrivingStatus;

			rejectData['customer_id'] = customer_id;
			rejectData['driver_id'] = driver_id;
			rejectData['ride_id'] = ride_id;
			rejectData['arrivingStatus'] = arrivingStatus;
			console.log('rejectData_id', customer_id, rejectData);
			socket.join("reachedRoom-" + customer_id + ride_id);
			io.sockets.in("reachedRoom-" + customer_id + ride_id).emit('reachedToCustomerLocation', rejectData);
			axios.get('https://flashappza.com/api/v1/firebase?id=' + customer_id + '&message=Driver arrived in your location.')
				.then(response => {
					console.log(response.data.url);
					console.log(response.data.explanation);
				})
				.catch(error => {
					console.log(error);
				});



			mysqlDB.insertNotification(TBL_NOTIFICATION, ride_id, 0, customer_id, 3, 'Driver arrived in your location.', function (tNotiErr, notification) {
				if (tNotiErr) console.log(tNotiErr);
				else if (notification) {
					console.log('notification', notification);
				}
			});

			console.log("==========================| Start |=============================");
			mysqlDB.insertRideData(TBL_RIDE_HOSTORY, ride_id, driver_id, 2, 'reach pickup location', 'success', function (tRideHistErr, result) {
				if (tRideHistErr) console.log('Error ',tRideHistErr);
				else if (result) {
					console.log('ride hostory', result);
				}
			});

			mysqlDB.insertRideData(TBL_RIDE_HOSTORY, ride_id, driver_id, 3, 'verify otp', 'pending', function (tRideHistErr, result) {
				if (tRideHistErr) console.log('Error ',tRideHistErr);
				else if (result) {
					console.log('ride hostory', result);
				}
			});
			console.log("===========================| End |=========================");
		}
		// @todo - need to check tomorrow.
		if (data.user_type == 'customer') {
			let ride_id = data.ride_id;
			console.log('customer_id', customer_id);
			socket.join("reachedRoom-" + customer_id + ride_id);
		}
	});

	socket.on('acceptedByDriver', function (data) {
		console.log('Emit Data Customer', data);
		data = typeof data == 'object' ? data : JSON.parse(data);
		let customer_id = data.customer_id;
		if (data.user_type == 'driver') {

			let driver_id = data.driverId;
			let driver_name = data.driverName;
			let arrivingStatus = data.arrivingStatus;
			let profileImage = data.profileImage;
			let mobile_number = data.mobileNumber;
			let covidAcceptance = data.covidAcceptance;
			let carType = data.carType;
			let lat = data.lat;
			let lng = data.lng;
			let ride_id = data.ride_id;
			let accessToken = data.token;

			mysqlDB.fetchData(TBL_PASSENGER_RIDE_DETAIL, "id=" + ride_id, 'driver_id,cancel_ride_by', '', '', '', function (userErr, ride) {
				if (userErr) console.log(userErr);
				else if (ride) {
					// console.log('ride----------------->', ride, ride[0].driver_id, ride[0].cancel_ride_by);
					let fetchedDriver = ride[0].driver_id;
					if (ride[0].cancel_ride_by > 0) {
						acceptData['customer_id'] = customer_id;
						acceptData['ride_id'] = ride_id;
						acceptData['driver_id'] = 0;
						acceptData['otp'] = 0;
						acceptData['status'] = false;
						acceptData['message'] = "Ride is already canceled";
						console.log()
						socket.join("acceptedRoom-" + driver_id + ride_id);
						io.sockets.in("acceptedRoom-" + driver_id + ride_id).emit('acceptedByDriver', acceptData);

						axios.get('https://flashappza.com/api/v1/driverFirebase?id=' + driver_id + '&message=Sorry!Ride is canceled.')
							.then(response => {
								console.log(response.data.url);
								console.log(response.data.explanation);
							})
							.catch(error => {
								console.log(error);
							});

						mysqlDB.insertNotification(TBL_NOTIFICATION, ride_id, 0, driver_id, 4, 'Sorry!Ride is canceled.', function (tNotiErr, notification) {
							if (tNotiErr) console.log(tNotiErr);
							else if (notification) {
								console.log('notification', notification);
							}
						});
					}

					if (fetchedDriver > 0) {
						acceptData['customer_id'] = customer_id;
						acceptData['ride_id'] = ride_id;
						acceptData['driver_id'] = 0;
						acceptData['otp'] = 0;
						acceptData['status'] = false;
						acceptData['message'] = "Someone already accept the ride";
						socket.join("acceptedRoom-" + driver_id + ride_id);
						io.sockets.in("acceptedRoom-" + driver_id + ride_id).emit('acceptedByDriver', acceptData);
						axios.get('https://flashappza.com/api/v1/driverFirebase?id=' + driver_id + '&message=Too late!Someone already accept the ride.')
							.then(response => {
								console.log(response.data.url);
								console.log(response.data.explanation);
							})
							.catch(error => {
								console.log(error);
							});
						mysqlDB.insertNotification(TBL_NOTIFICATION, ride_id, 0, driver_id, 5, 'Too late!Someone already accept the ride.', function (tNotiErr, notification) {
							if (tNotiErr) console.log(tNotiErr);
							else if (notification) {
								console.log('notification', notification);
							}
						});
					} else if ((fetchedDriver == 0) && ride[0].cancel_ride_by == 0) {
						var avg_rating;
						mysqlDB.fetchData(TBL_USERS, "id=" + driver_id, 'avg_rating', '', '', '', async function (userFetchErr, fetchDriver) {
							if (userFetchErr) console.log(userErr);
							if (fetchDriver) {
								avg_rating = fetchDriver[0].avg_rating;
								mysqlDB.fetchData(TBL_DRIVER_DETAILS, "user_id=" + driver_id, 'license_number,model', '', '', '', async function (userFetchError, driverDetails) {
									if (userFetchError) console.log(userFetchError);
									if (driverDetails) {
										registration_number = driverDetails[0].license_number;
										modelID = driverDetails[0].model;
										mysqlDB.fetchData(TBL_CAR_MODELS, "id=" + modelID, 'name', '', '', '', async function (modelFetchError, modelDetail) {
											if (modelFetchError) console.log(modelFetchError);
											if (modelDetail) {
												model = modelDetail[0].name;
												mysqlDB.updateRideDriver(TBL_PASSENGER_RIDE_DETAIL, "id=" + ride_id, driver_id, function (tRideErr, onTRide) {
													if (tRideErr) console.log(tRideErr);
													else if (onTRide) {
														console.log(onTRide);
													}
												});
												acceptData['customer_id'] = customer_id;
												acceptData['driver_id'] = driver_id;
												acceptData['driver_name'] = driver_name;
												acceptData['arrivingStatus'] = arrivingStatus;
												acceptData['profileImage'] = profileImage;
												acceptData['mobile_number'] = mobile_number;
												acceptData['covidAcceptance'] = covidAcceptance;
												acceptData['carType'] = carType;
												acceptData['lat'] = lat;
												acceptData['lng'] = lng;
												acceptData['avg_rating'] = avg_rating;
												acceptData['registration_number'] = registration_number;
												acceptData['model'] = model;
												acceptData['ride_id'] = ride_id;
												acceptData['status'] = true;
												var otp = Math.floor(Math.random() * (9999 - 1000 + 1)) + 1000;
												acceptData['otp'] = otp;
												mysqlDB.updateRideOTP(TBL_PASSENGER_RIDE_DETAIL, "id=" + ride_id, otp, function (tRideErr, onTRide) {
													if (tRideErr) console.log(tRideErr);
													else if (onTRide) {
														console.log(onTRide);
													}
												});

												acceptData['message'] = "Ride Accepted";
												console.log('rejectData_id', customer_id, acceptData);
												socket.join("acceptedRoom-" + customer_id + ride_id);
												io.sockets.in("acceptedRoom-" + customer_id + ride_id).emit('acceptedByDriver', acceptData);
												axios.get('https://flashappza.com/api/v1/firebase?id=' + customer_id + '&message=Driver accept your request.')
													.then(response => {
														console.log(response.data.url);
														console.log(response.data.explanation);
													})
													.catch(error => {
														console.log(error);
													});

												mysqlDB.insertNotification(TBL_NOTIFICATION, ride_id, 0, customer_id, 6, 'Driver accept your request.', function (tNotiErr, notification) {
													if (tNotiErr) console.log(tNotiErr);
													else if (notification) {
														console.log('notification', notification);
													}
												});

												console.log("==========================| Start |=============================");
												mysqlDB.insertRideData(TBL_RIDE_HOSTORY, ride_id, driver_id, 1, 'accept by driver', 'success', function (tRideHistErr, result) {
													if (tRideHistErr) console.log('Error ',tRideHistErr);
													else if (result) {
														console.log('ride hostory', result);
													}
												});

												mysqlDB.insertRideData(TBL_RIDE_HOSTORY, ride_id, driver_id, 2, 'reach pickup location', 'pending', function (tRideHistErr, result) {
													if (tRideHistErr) console.log('Error ',tRideHistErr);
													else if (result) {
														console.log('ride hostory', result);
													}
												});
												console.log("===========================| End |=========================");
											}
										});
									}
								});
							}
						});
					}
				}
			});
		}
		if (data.user_type == 'customer') {
			let ride_id = data.ride_id;
			console.log('customer_id', customer_id);
			socket.join("acceptedRoom-" + customer_id + ride_id);
		}
	});

	socket.on('otpVerification', function (data) {
		console.log('Emit Data OTP-------*************>>>>>>', data);
		data = typeof data == 'object' ? data : JSON.parse(data);
		let customer_id = data.customer_id;
		let user_type = data.user_type;
		if (user_type == 'driver') {
			console.log('Emit Data from Driver Otp', data);
			let ride_id = data.ride_id;
			let otp = data.otp;
			let ride_time = data.ride_time;
			let driver_id = data.driver_id;

			mysqlDB.verifyOTP(TBL_PASSENGER_RIDE_DETAIL, "id=" + ride_id + " AND otp=" + otp, ride_time, function (tRideErr, onTRide) {
				if (tRideErr) {
					console.log('tRideErr', tRideErr);
				}
				else if (onTRide) {
					console.log('onTRide', onTRide);
					if (onTRide.affectedRows == 1) {
						otpData['status'] = 200;
						// console.log('Driver Join OTP', driver_id, otpData);
						axios.get('https://flashappza.com/api/v1/driverFirebase?id=' + driver_id + '&message=OTP Verified')
							.then(response => {
								console.log(`Forebase response ${response}`);
								// console.log(response.data.url);
								// console.log(response.data.explanation);
							})
							.catch(error => {
								console.log(error);
							});
						mysqlDB.insertNotification(TBL_NOTIFICATION, ride_id, 0, customer_id, 7, 'OTP Verified', function (tNotiErr, notification) {
							if (tNotiErr) console.log(tNotiErr);
							else if (notification) {
								console.log('notification', notification);
							}
						});


						socket.join("otpVerificationRoom-" + driver_id + ride_id);
						io.sockets.in("otpVerificationRoom-" + driver_id + ride_id).emit('otpVerification', otpData);

						otpVerifyData['status'] = 1;
						console.log(`Customer Join OTP driver id - ${driver_id} OTP data - ${otpData}`);
						io.sockets.in("otpVerificationCustomerRoom-" + customer_id + "ride" + ride_id).emit('otpVerification', otpVerifyData);
						axios.get('https://flashappza.com/api/v1/firebase?id=' + customer_id + '&message=OTP verified by customer.Your ride has been started.')
							.then(response => {
								console.log(response.data.url);
								console.log(response.data.explanation);
							})
							.catch(error => {
								console.log(error);
							});
						mysqlDB.insertNotification(TBL_NOTIFICATION, ride_id, 0, customer_id, 8, 'OTP verified by customer.Your ride has been started.', function (tNotiErr, notification) {
							if (tNotiErr) console.log(tNotiErr);
							else if (notification) {
								console.log('notification', notification);
							}
						});

						// mysqlDB.insertRideHistory(TBL_RIDE_HOSTORY, ride_id, driver_id, 3, 'otp_verify', 'success', function (tNotiErr, notification) {
						// 	if (tNotiErr) console.log(tNotiErr);
						// 	else if (notification) {
						// 		console.log('ride_hostory', notification);
						// 	}
						// });

						// mysqlDB.insertRideHistory(TBL_RIDE_HOSTORY, ride_id, driver_id, 4, 'ride_complete', 'pending', function (tNotiErr, notification) {
						// 	if (tNotiErr) console.log(tNotiErr);
						// 	else if (notification) {
						// 		console.log('ride_hostory', notification);
						// 	}
						// });

						console.log("==========================| Start |=============================");
						mysqlDB.insertRideData(TBL_RIDE_HOSTORY, ride_id, driver_id, 3, 'verify otp', 'success', function (tRideHistErr, result) {
							if (tRideHistErr) console.log('Error ',tRideHistErr);
							else if (result) {
								console.log('ride hostory', result);
							}
						});

						mysqlDB.insertRideData(TBL_RIDE_HOSTORY, ride_id, driver_id, 4, 'complete ride', 'pending', function (tRideHistErr, result) {
							if (tRideHistErr) console.log('Error ',tRideHistErr);
							else if (result) {
								console.log('ride hostory', result);
							}
						});
						console.log("===========================| End |=========================");
					} else {
						console.log('OTP not valid');
						otpData['status'] = 400;
						axios.get('https://flashappza.com/api/v1/driverFirebase?id=' + driver_id + '&message=OTP Mismatched')
							.then(response => {
								console.log(response.data.url);
								console.log(response.data.explanation);
							})
							.catch(error => {
								console.log(error);
							});
						mysqlDB.insertNotification(TBL_NOTIFICATION, ride_id, 0, driver_id, 9, 'OTP Mismatched', function (tNotiErr, notification) {
							if (tNotiErr) console.log(tNotiErr);
							else if (notification) {
								console.log('notification', notification);
							}
						});


						socket.join("otpVerificationRoom-" + driver_id + ride_id);
						io.sockets.in("otpVerificationRoom-" + driver_id + ride_id).emit('otpVerification', otpData);

						otpVerifyData['status'] = 2;
						io.sockets.in("otpVerificationCustomerRoom-" + customer_id + "ride" + ride_id).emit('otpVerification', otpVerifyData);
						socket.leave("otpVerificationCustomerRoom-" + customer_id + "ride" + ride_id);
					}
				}
			});
		}
		if (user_type == 'customer') {
			let ride_id = data.ride_id;
			console.log('Emit Data from Customer Otp', data);
			socket.join("otpVerificationCustomerRoom-" + customer_id + "ride" + ride_id);
		}
	});

	socket.on('rideComplete', function (data) {
		console.log('Emit Data Ride Complete-------------->', data);
		data = typeof data == 'object' ? data : JSON.parse(data);
		let customer_id = data.customer_id;
		let user_type = data.user_type;
		if (user_type == 'driver') {
			let driver_id = data.driver_id;
			let ride_id = data.ride_id;
			mysqlDB.fetchData(TBL_PASSENGER_RIDE_DETAIL, "id=" + ride_id, 'driver_id,cancel_ride_by,end_trip_date,ride_time', '', '', '', function (userErr, ride) {
				if (userErr) console.log(userErr);
				else if (ride) {
					rideCompleteData['start_time'] = ride[0].ride_time;
					rideCompleteData['end_time'] = ride[0].end_trip_date;
					rideCompleteData['arrivingStatus'] = 3;
					console.log('rideCompleteData------------>', ride, rideCompleteData);
					socket.join("rideCompleteRoom-" + customer_id + ride_id);
					io.sockets.in("rideCompleteRoom-" + customer_id + ride_id).emit('rideComplete', rideCompleteData);
					axios.get('https://flashappza.com/api/v1/driverFirebase?id=' + driver_id + '&message=Ride Complete.')
						.then(response => {
							console.log(response.data.url);
							console.log(response.data.explanation);
						})
						.catch(error => {
							console.log(error);
						});
					mysqlDB.insertNotification(TBL_NOTIFICATION, ride_id, 0, driver_id, 10, 'Ride Complete.', function (tNotiErr, notification) {
						if (tNotiErr) console.log(tNotiErr);
						else if (notification) {
							console.log('notification', notification);
						}
					});


					axios.get('https://flashappza.com/api/v1/firebase?id=' + customer_id + '&message=Ride Complete.')
						.then(response => {
							console.log(response.data.url);
							console.log(response.data.explanation);
						})
						.catch(error => {
							console.log(error);
						});
					mysqlDB.insertNotification(TBL_NOTIFICATION, ride_id, 0, customer_id, 10, 'Ride Complete.', function (tNotiErr, notification) {
						if (tNotiErr) console.log(tNotiErr);
						else if (notification) {
							console.log('notification', notification);
						}
					});

					console.log("==========================| Start |=============================");
					mysqlDB.insertRideData(TBL_RIDE_HOSTORY, ride_id, driver_id, 4, 'complete ride', 'success', function (tRideHistErr, result) {
						if (tRideHistErr) console.log('Error ',tRideHistErr);
						else if (result) {
							console.log('ride hostory', result);
						}
					});

					mysqlDB.insertRideData(TBL_RIDE_HOSTORY, ride_id, driver_id, 5, 'customer paid', 'pending', function (tRideHistErr, result) {
						if (tRideHistErr) console.log('Error ',tRideHistErr);
						else if (result) {
							console.log('ride hostory', result);
						}
					});
					console.log("===========================| End |=========================");

					// mysqlDB.insertRideHistory(TBL_RIDE_HOSTORY, ride_id, driver_id, 4, 'ride_complete', 'success', function (tNotiErr, notification) {
					// 	if (tNotiErr) console.log(tNotiErr);
					// 	else if (notification) {
					// 		console.log('ride_hostory', notification);
					// 	}
					// });
					// socket.leave("rideCompleteRoom-" + customer_id + ride_id);
				}
			});
		}
		if (user_type == 'customer') {
			let ride_id = data.ride_id;
			socket.join("rideCompleteRoom-" + customer_id + ride_id);
		}
	});

	//socket.on('customerLocationSentFromCustomerApp', function (data) {
	//   console.log('Emit Data Customer',data);
	//   data = typeof data=='object'?data:JSON.parse(data);
	//   let lat = data.lat;
	//   let lng = data.lng;
	//   console.log('Emit Data Customer Lat Lng',lat,lng);
	//});

	socket.on("disconnect", (reason) => {
		// ...
		console.log('Disconnect from server');
	});

});

server.listen(4002, function () {
	console.log('listening on localhost:4002');
});




