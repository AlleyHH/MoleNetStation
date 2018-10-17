window.onload = function() 
{
	var updateInterval = 4000;
	var pID = 0;

	$("#example-table").tabulator({
	    height:"700px",
	    width: "500px",
	    layout:"fitColumns",
	    placeholder:"No Data Available",
	    columns:
	    [
	    	{title:"Time Stamp", field:"ts", headerSort:false, align:"center"},
	        {title:"Source ID", field:"soid", headerSort:false, align:"center"},
	        {title:"Sink ID", field:"siid", headerSort:false, align:"center"},
	        {title:"Packet Type", field:"pt", headerSort:false, align:"center"},
	        {title:"Packet Length", field:"pl", headerSort:false, align:"center"},
	        {title:"Temperature", field:"temp", headerSort:false, align:"center"},
	        {title:"Dielectric", field:"die", headerSort:false, align:"center"},
	        {title:"RTT", field:"rtt", headerSort:false, align:"center"},
	        {title:"RSSI", field:"rssi", headerSort:false, align:"center"}
	    ],
	});
	
	var updateTable = function() 
	{
		var ajax = new XMLHttpRequest();
		var method = "GET";
		var url = "http://localhost/data.php";
		var asynchronous = true;

		ajax.open(method, url, asynchronous);
		ajax.send();
		ajax.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				var data = JSON.parse(this.responseText);
				console.log(data);
				if(pID < data[0].iddata)
				{
					pID = data[0].iddata;
					$("#example-table").tabulator("addData", [ {
						id : 1,
						ts : data[0].timeStamp,
						soid : data[0].sourceID,
						siid : data[0].sinkID,
						pt : data[0].packetType,
						pl : data[0].packetLength,
						temp : data[0].temp,
						die : data[0].die,
						rtt : data[0].rtt,
						rssi : data[0].rssi
					} ], true);
				}
			}
		}
	}	
	setInterval(function() {updateTable()}, updateInterval);
}


