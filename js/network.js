window.onload = function() {

	var totalNodes = 0;
	var activeNodes = 0;
	var averageRTT = 0;
	var averageRSSI = 0;
	var active1 = [];
	var active2 = [];

	var ajax = new XMLHttpRequest();
	var method = "GET";
	var url1 = "http://localhost/nodes.php";
	var url2 = "http://localhost/network.php";
	var asynchronous = true;
	ajax.open(method, url1, asynchronous);
	ajax.send();
	ajax.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var nodes = JSON.parse(this.responseText);
			// console.log(nodes.length);
			totalNodes = nodes.length;
			document.getElementById("na").innerHTML = "Network Analysis";
			document.getElementById("tn").innerHTML = "Total Nodes: "
					+ totalNodes;
			var n = new vis.DataSet();
			for (var a = 0; a < nodes.length; a++) {
				if (nodes[a].sinkNode == 1) {
					n.add({
						id : nodes[a].NodeID,
						label : "Base Station",
						color : {
							border : '#333333',
							background : '#737373',
							highlight : {
								border : '#2B7CE9',
								background : '#D2E5FF'
							},
							hover : {
								border : '#2B7CE9',
								background : '#D2E5FF'
							}
						},
						shape : 'elipse'
					});
				} else {
					n.add({
						id : nodes[a].NodeID,
						label : "Node " + nodes[a].NodeID,
						color : {
							border : '#333333',
							background : '#b3b3b3',
							highlight : {
								border : '#2B7CE9',
								background : '#D2E5FF'
							},
							hover : {
								border : '#2B7CE9',
								background : '#D2E5FF'
							}
						},
						shape : 'circle'
					});
				}
			}

			ajax.open(method, url2, asynchronous);
			ajax.send();
			ajax.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var edges = JSON.parse(this.responseText);
					// console.log(edges);
					var e = new vis.DataSet();
					var route = [];
					var routeNew = [];
					for (var b = 0; b < edges.length; b++) {
						var path = edges[b].sourceID + "-" + edges[b].nextHopID;
						route[b] = path;
						averageRTT = averageRTT + parseInt(edges[b].rtt);
						averageRSSI = averageRSSI + parseInt(edges[b].rssi);
					}
					averageRTT = averageRTT / edges.length;
					averageRSSI = averageRSSI / edges.length;
					averageRTT = Number((averageRTT).toFixed(2));
					averageRSSI = Number((averageRSSI).toFixed(2));
					document.getElementById("artt").innerHTML = "Average RTT: "
							+ averageRTT;
					document.getElementById("arssi").innerHTML = "Average RSSI: "
							+ averageRSSI;
					routeNew = uniqueArray(route);
					console.log(routeNew);
					for (var d = 0; d < routeNew.length; d++) {
						var line = routeNew[d].split("-");
						if (line[0] !== line[1]) {
							e.add({
								from : line[0],
								to : line[1],
								arrows : 'to'
							});
							active1[d] = line[0];
							active2[d] = line[1];
						}
					}
					// console.log(moles.length);
					for (var f = 0; f < nodes.length; f++) {
						if (active1.contains(nodes[f].NodeID)
								|| active2.contains(nodes[f].NodeID)) {
							activeNodes = activeNodes + 1;
						}
					}
					document.getElementById("aNodes").innerHTML = "Active Nodes: "
							+ activeNodes;
					var container = document.getElementById('mynetwork');
					var data = {
						nodes : n,
						edges : e
					};
					var options = {
						height : '100%',
						width : '100%',
						layout : {
							hierarchical : {
								enabled : true,
								levelSeparation : 250,
								nodeSpacing : 200,
								treeSpacing : 500,
								direction : "LR",
								sortMethod : "directed"
							}
						},
						interaction : {
							dragNodes : false
						},
						physics : {
							enabled : false
						},
						nodes : {
							borderWidth : 1,
							borderWidthSelected : 2,
							chosen : true,
							font : {
								color : '#343434',
								size : 25
							}
						},
						edges : {
							color : {
								color : '#848484',
								highlight : '#848484',
								hover : '#848484',
								inherit : 'from',
								opacity : 1.0
							}
						}
					};
					var network = new vis.Network(container, data, options);
				}
			}
		}
	}
}

var uniqueArray = function(arrArg) {
	return arrArg.filter(function(elem, pos, arr) {
		return arr.indexOf(elem) == pos;
	});
};

Array.prototype.contains = function(elem) {
	for ( var i in this) {
		if (this[i] == elem)
			return true;
	}
	return false;
}