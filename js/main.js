window.onload = function() {

	var pID = 0;
	var canvas1 = document.getElementById('Chart1');
	var canvas2 = document.getElementById('Chart2');
	var xVal = 0;
	var yVal1 = 0;
	var yVal2 = 0;
	var updateInterval = 1000;
	var dataLength = 10;
	var data1 = {
		labels : [],
		datasets : [ {
			label : "Temprature",
			fill : false,
			lineTension : 0.1,
			backgroundColor : "rgba(75,192,192,0.4)",
			borderColor : "rgba(75,192,192,1)",
			borderCapStyle : 'butt',
			borderDash : [],
			borderDashOffset : 0.0,
			borderJoinStyle : 'miter',
			pointBorderColor : "rgba(75,192,192,1)",
			pointBackgroundColor : "#fff",
			pointBorderWidth : 1,
			pointHoverRadius : 5,
			pointHoverBackgroundColor : "rgba(75,192,192,1)",
			pointHoverBorderColor : "rgba(220,220,220,1)",
			pointHoverBorderWidth : 2,
			pointRadius : 5,
			pointHitRadius : 10,
			data : [],
		} ]
	};
	var data2 = {
		labels : [],
		datasets : [ {
			label : "Dielectric",
			fill : false,
			lineTension : 0.1,
			backgroundColor : "rgba(75,192,192,0.4)",
			borderColor : "rgba(75,192,192,1)",
			borderCapStyle : 'butt',
			borderDash : [],
			borderDashOffset : 0.0,
			borderJoinStyle : 'miter',
			pointBorderColor : "rgba(75,192,192,1)",
			pointBackgroundColor : "#fff",
			pointBorderWidth : 1,
			pointHoverRadius : 5,
			pointHoverBackgroundColor : "rgba(75,192,192,1)",
			pointHoverBorderColor : "rgba(220,220,220,1)",
			pointHoverBorderWidth : 2,
			pointRadius : 5,
			pointHitRadius : 10,
			data : [],
		} ]
	};
	var option1 = {
		showLines : true,
		legend : {
			display : true,
			labels : {
				fontSize : 20
			}
		}
	};
	var option2 = {
		showLines : true,
		legend : {
			display : true,
			labels : {
				fontSize : 20
			}
		}
	};
	var Chart1 = Chart.Line(canvas1, {
		data : data1,
		options : option1
	});
	var Chart2 = Chart.Line(canvas2, {
		data : data2,
		options : option2
	});
	$("#example-table").tabulator({
		height : "100px",
		width : "10%",
		layout : "fitColumns",
		placeholder : "No Data Available",
		columns : [ {
			title : "Node ID",
			field : "ni",
			headerSort : false,
			align : "center"
		}, ],
	});

	$("#example-table").tabulator("addData", [ {
		id : 1,
		ni : "0"
	} ], true);

	var updateChart = function() {

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
					//pID = data[0].iddata;
					yVal1 = data[0].temp;
					yVal2 = data[0].die;
					xVal = data[0].timeStamp;
					xVal = xVal.split(" ");
					xVal = xVal[1]
					if (Chart1.data.labels.length < dataLength) {
						Chart1.data.datasets[0].data.push(yVal1);
						Chart1.data.labels.push(xVal);
					} else {
						Chart1.data.datasets[0].data.push(yVal1);
						Chart1.data.labels.push(xVal);
						Chart1.data.labels.shift();
						Chart1.data.datasets[0].data.shift();
					}
					if (Chart2.data.labels.length < dataLength) {
						Chart2.data.datasets[0].data.push(yVal2);
						Chart2.data.labels.push(xVal);
					} else {
						Chart2.data.datasets[0].data.push(yVal2);
						Chart2.data.labels.push(xVal);
						Chart2.data.labels.shift();
						Chart2.data.datasets[0].data.shift();
					}
					Chart1.update();
					Chart2.update();
					document.getElementById("node").innerHTML = "Node ID: " + data[0].sourceID;
				}
			}
		}
	};
	updateChart(dataLength);
	setInterval(function() {
		updateChart()
	}, updateInterval);

}