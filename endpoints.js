endpointsList = [
	{ name : "Select one endpoint" , 					url : "Select one endpoint",								"payload" : {},			"description" : ""},
	{ name : "cockpit" , 								url : "cockpit", 											"payload" : {},			"description" : "Show mileage" 																		,
		"response" : [{"name" : "totalMileage"}, {"name":"fuelAutonomy"}, {"name" : "fuelQuantity"}]},


	{ name : "location" , 								url : "location",											"payload" : {},			"description" : "Show car position"																 ,
		"response" : [{"name" : "gpsLatitude"}, {"name" : "gpsLongitude", }]},


	{ name : "battery-status" , 						url : "battery-status"	,									"payload" : {},			"description" : "Show battery status  and charging status"				 ,
		"response" : [
			{"name" : "chargeStatus"},
			{"name" : "plugStatus"},
			{"name" : "rangeHvacOn"},
			{"name" : "batteryLevel"},
			{"name" : "batteryTemperature"},
			{"name" : "batteryAutonomy"},
			{"name" : "batteryCapacity"},
			{"name" : "batteryAvailableEnergy"},
			{"name" : "plugStatus"},
			{"name" : "chargingStatus"},
			{"name" : "chargingRemainingTime"},
			{"name" : "chargingInstantaneousPower"}, ]},


	{ name : "-------- HVAC ---------" ,			url : "",														"payload" : {},			"description" : ""},
	{ name : "hvac-status" , 							url : "hvac-status",										"payload" : {},			"description" : "Show if On/off, target temperature, no schedule",
		"response" : [{"name" : "hvacStatus"}, {"name" : "internalTemperature"}, ]},


	{ name : "hvac-settings" , 							url : "hvac-settings",										"payload" : {},			"description" : "Show HVAC schedule grouped by id",
		"response" : [{"name" : "mode"}, {"name" : "globalTargetTemperature"},  {"name" : "schedules"}, ]},


	{ name : "hvac-schedule" , 							url : "hvac-schedule",										"payload" : {},			"description" : "Show HVAC schedule groupd by day",
		"response" : [{"name" : "calendar"}]},


	{ name : "hvac-history" , 							url : "hvac-history?start=20210201&end=20210301&type=day",	"payload":  {},			"description" : "-",
		"response" : [{"name" : ""}, {"name" : ""},]},


	{ name : "hvac-sessions" , 							url : "hvac-sessions?start=20210201&end=20210301",			"payload" : {},			"description" : "-",
		"response" : [{"name" : ""}, {"name" : ""},]},


	{ name : "----- Charging -------" ,					url : "",													"payload" : {},			"description" : ""},
	{ name : "Show current charging mode" , 			url : "charge-mode"	,										"payload" : {},			"description" : "Show charge mode",
		"response" : [{"name" : "chargeMode"}]},


	{ name : "Show charging settings" ,					url : "charging-settings",									"payload" : {},			"description" : "Show charge schedule by id",
		"response" : [{"name" : "mode"}, {"name" : "schedules"},]},


	{ name : "Show charge schedule" , 					url : "charge-schedule"	,									"payload" : {},			"description" : "Show charge schedule by day",
		"response" : [{"name" : "calendar"}]},


	{ name : "Show charge history" , 					url : "charge-history?start=20210101&end=20210201&type=day","payload" : {},			"description" : "Show number of charges and count of kWh",
		"response" : [{"name" : ""}]},


	{ name : "charges" , 								url : "charges?start=20210101&end=20210201&type=day",		"payload" : {},			"description" : ""},
	{ name : "----- Actions ------" , 					url : "",													"payload" : {},			"description" : ""},
	{ name : "Refresh battery status" ,					url : "actions/refresh-battery-status",						"payload" : {'data':{'type':'RefreshBatteryStatus'}}, 											"description" : "Refresh battery data retrieved nby  battery-status endpoint"},
	{ name : "Refresh HVAC status" , 					url : "actions/refresh-hvac-status"	,						"payload" : {'data':{'type':'RefreshHvacStatus'}}, 												"description" : "Refresh HVAC  data retrieved nby  hvac-status endpoin"},
	{ name : "Start charging" , 						url : "actions/charging-start",								"payload" : {'data':{'type':'ChargingStart','attributes':{'action':'start'}}},					"description" : "Start charging immediately"},
	{ name : "Stop charging" , 							url : "actions/charging-start",								"payload" : {'data':{'type':'ChargingStart','attributes':{'action':'stop'}}},					"description" : "Stop charging immediately"},
	{ name : "Set charge mode to ALWAYS" , 				url : "actions/charge-mode"	,								"payload" : {'data':{'type':'ChargeMode','attributes':{'action':'always_charging'}}},			"description" : "Set charge mode"},
	{ name : "Set charge mode to SCHEDULED" , 			url : "actions/charge-mode"	,								"payload" : {'data':{'type':'ChargeMode','attributes':{'action':'schedule_mode'}}},				"description" : "Set charge mode"},
	{ name : "Start HVAC immediately" , 				url : "actions/hvac-start"	,								"payload" : {'data':{'type':'HvacStart','attributes':{'action':'start','targetTemperature':'21'}}},	"description" : "You must specify proper payload!"},
	{ name : "Stop HVAC immediately" , 					url : "actions/hvac-start"	,								"payload" : {'data':{'type':'HvacStart','attributes':{'action':'stop'}}},						"description" : "You must specify proper payload!"},
	{ name : "----- Experimental ----------" ,			url : "",													"payload" : {},			"description" : "-"},
	{ name : "lock-status" , 							url : "lock-status"	,										"payload" : {},			"description" : "-"},
	{ name : "notification-settings" ,					url : "notification-settings",								"payload" : {},			"description" : "-"},
	{ name : "actions/horn-lights" , 					url : "actions/horn-lights",								"payload" : {},			"description" : "-"},
	{ name : "actions/engine-start" , 					url : "actions/engine-start",								"payload" : {'data':{'type':'EngineStart','attributes':{'action':'start'}}} , 					"description" : "-"},
	{ name : "actions/send-navigation" ,				url : "actions/send-navigation"	,							"payload" : {},			"description" : "-"},
];
