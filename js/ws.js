window.onload = function() {
	var g1, g2, g3, g4, g5;
	var updateInterval = 1000;
	var pID = 0;

	var g1 = new JustGage({
		id : "g1",
		min : -10,
		max : 50,
		relativeGaugeSize : true,
		symbol : ' °C',
		pointer : true,
		label : "Temperature",
		gaugeWidthScale : 0.6,
		customSectors : [ {
			color : '#ff0000',
			lo : 50,
			hi : 100
		}, {
			color : '#00ff00',
			lo : 0,
			hi : 50
		} ],
		counter : true
	});

	var g2 = new JustGage({
		id : "g2",
		min : 0,
		max : 60,
		relativeGaugeSize : true,
		symbol : ' %',
		pointer : true,
		label : "Humidity",
		gaugeWidthScale : 0.6,
		customSectors : [ {
			color : '#ff0000',
			lo : 50,
			hi : 100
		}, {
			color : '#00ff00',
			lo : 0,
			hi : 50
		} ],
		counter : true
	});

	var g3 = new JustGage({
		id : "g3",
		// value : getRandomInt(0, 100),
		min : 0,
		max : 20,
		relativeGaugeSize : true,
		symbol : ' km/h',
		label : "Wind Speed",
		pointer : true,
		gaugeWidthScale : 0.6,
		customSectors : [ {
			color : '#ff0000',
			lo : 50,
			hi : 100
		}, {
			color : '#00ff00',
			lo : 0,
			hi : 50
		} ],
		counter : true
	});

	var g4 = new JustGage({
		id : "g4",
		min : 0,
		max : 30,
		relativeGaugeSize : true,
		symbol : ' km/h',
		label : "Wind Gust",
		pointer : true,
		gaugeWidthScale : 0.6,
		customSectors : [ {
			color : '#ff0000',
			lo : 50,
			hi : 100
		}, {
			color : '#00ff00',
			lo : 0,
			hi : 50
		} ],
		counter : true
	});

	var g5 = new JustGage({
		id : "g5",
		min : 0,
		max : 50,
		relativeGaugeSize : true,
		symbol : ' %',
		label : "Rainfall",
		pointer : true,
		gaugeWidthScale : 0.6,
		customSectors : [ {
			color : '#ff0000',
			lo : 50,
			hi : 100
		}, {
			color : '#00ff00',
			lo : 0,
			hi : 50
		} ],
		counter : true
	});

	flatpickr(".flatpickr", {
		enableTime : true,
		altInput : true,
		dateFormat : "Y-m-d H:i",
		mode : "range",
		time_24hr : true
	});

	$("#example-table").tabulator({
		height : "400px",
		width : "500px",
		layout : "fitColumns",
		placeholder : "No Data Available",
		columns : [ {
			title : "Time Stamp",
			field : "ts",
			headerSort : false,
			align : "center"
		}, {
			title : "Temperature",
			field : "temp",
			headerSort : false,
			align : "center"
		}, {
			title : "Humidity",
			field : "hum",
			headerSort : false,
			align : "center"
		}, {
			title : "Wind Speed",
			field : "ws",
			headerSort : false,
			align : "center"
		}, {
			title : "Wind Gust",
			field : "wg",
			headerSort : false,
			align : "center"
		}, {
			title : "Rainfall",
			field : "rain",
			headerSort : false,
			align : "center"
		} ],
	});

	var updateGauges = function() {
		var ajax = new XMLHttpRequest();
		var method = "GET";
		var url = "http://localhost/data.php";
		var asynchronous = true;

		ajax.open(method, url, asynchronous);
		ajax.send();
		ajax.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var data = JSON.parse(this.responseText);
				console.log(data);
				if (pID < data[0].iddata) {
					pID = data[0].iddata;
					g1.refresh(parseInt(data[0].ws_temp));
					g2.refresh(parseInt(data[0].ws_hum));
					g3.refresh(parseInt(data[0].ws_ws));
					g4.refresh(parseInt(data[0].ws_wg));
					g5.refresh(parseInt(data[0].ws_rain));
				}
			}
		}
	}

	setInterval(function() {
		updateGauges()
	}, updateInterval);
}

function myFunction() {
	var input = document.getElementById("waqt").value;
	var res = input.split(" to ");
	var sendData = "sendData=" + res[0] + "," + res[1];
	var ajax = new XMLHttpRequest();
	var method = "POST";
	var url = "http://localhost/ws.php";
	var asynchronous = true;
	ajax.open(method, url, asynchronous);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200) {
			var data = JSON.parse(this.responseText);
			console.log(data);
			var len = data.length;
			for (var a = 0; a < len; a++) {
				$("#example-table").tabulator("addData", [ {
					id : a,
					ts : data[a].timeStamp,
					temp : data[a].ws_temp,
					hum : data[a].ws_hum,
					ws : data[a].ws_ws,
					wg : data[a].ws_wg,
					rain : data[a].ws_rain
				} ], true);
			}
		}
	}
	ajax.send(sendData);
}
