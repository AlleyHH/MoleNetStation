window.onload = function() {

	flatpickr(".flatpickr", {
		enableTime : true,
		altInput : true,
		dateFormat : "Y-m-d H:i",
		mode : "range",
		time_24hr : true
	});

	$("#example-table").tabulator({
		height : "600px",
		width : "800px",
		layout : "fitColumns",
		placeholder : "No Data Available",
		columns : [ {
			title : "Time Stamp",
			field : "ts",
			headerSort : false,
			align : "center"
		}, {
			title : "Source ID",
			field : "soid",
			headerSort : false,
			align : "center"
		}, {
			title : "Sink ID",
			field : "siid",
			headerSort : false,
			align : "center"
		}, {
			title : "Packet Type",
			field : "pt",
			headerSort : false,
			align : "center"
		}, {
			title : "Packet Length",
			field : "pl",
			headerSort : false,
			align : "center"
		}, {
			title : "Temperature",
			field : "temp",
			headerSort : false,
			align : "center"
		}, {
			title : "Dielectric",
			field : "die",
			headerSort : false,
			align : "center"
		}, {
			title : "RTT",
			field : "rtt",
			headerSort : false,
			align : "center"
		}, {
			title : "RSSI",
			field : "rssi",
			headerSort : false,
			align : "center"
		} ],
	});
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
					soid : data[a].sourceID,
					siid : data[a].sinkID,
					pt : data[a].packetType,
					pl : data[a].packetLength,
					temp : data[a].temp,
					die : data[a].die,
					rtt : data[a].rtt,
					rssi : data[a].rssi
				} ], true);
			}
		}
	}
	ajax.send(sendData);
}