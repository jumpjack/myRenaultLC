// Sample payload:
//{"data":{"type":"HvacStart","attributes":{"action":"start","targetTemperature":"21"}}}
samplePayloads = [
{
	"pldName" :"Sample" ,
	"associatedAction" : "ActionName",
	"testedOk" : "n/a",
	"pldContents" :
		{
			"data":{
			"type":"ACTION_NAME",
				"attributes":{
					"action":"ACTION",
					"PARAMETER 1":"VALUE 1",
					"PARAMETER 2":"VALUE 2"
				}
			}
		}
},

{
	"pldName" :"HvacStartNow" ,
	"associatedAction" : "hvac-start", // command accepted, response received, HVAC not started
	"testedOk" : "command accepted, HVAC not started",
	"pldContents" :
		{
			"data":{
			"type":"HvacStart",
				"attributes":{
					"action":"start",
					"targetTemperature":"21"
				}
			}
		}
},

{
	"pldName" :"HvacStartAtTime" ,
	"associatedAction" : "hvac-start",
	"testedOk" : "command accepted, HVAC not started", // command accepted, response received, HVAC not started
	"pldContents" :
		{
			"data":{
			"type":"HvacStart",
				"attributes":{
					"action":"start",
					"targetTemperature":"21",
					"startDateTime": "2021-10-05T03:00:00Z"
				}
			}
		}
},

{
	"pldName" :"HvacStop1" ,
	"associatedAction" : "hvac-start",
	"testedOk" : "ok",
	"pldContents" :
		{
			"data":{
			"type":"HvacStart",
				"attributes":{
					"action":"stop"
				}
			}
		}
},

{
	"pldName" :"HvacStop2" ,
	"associatedAction" : "hvac-start",
	"testedOk" : "not stopping after starting!",
	"pldContents" :
		{
			"data":{
			"type":"HvacStart",
				"attributes":{
					"action":"cancel"
				}
			}
		}
},

{
	"pldName" :"Set hvac schedule" ,
	"associatedAction" : "hvac-schedule",
	"testedOk" : "yes",
	"pldContents" :
		{
			"data":{
			"type":"HvacSchedule",
			    "attributes": {
			      "schedules": [
			        {
			          "id": 1,
			          "activated": false
			        },
			        {
			          "id": 2,
			          "activated": true,
			          "monday": { "readyAtTime": "T20:45Z" },
			          "sunday": { "readyAtTime": "T20:45Z" }
			        },
			        {
			          "id": 3,
			          "activated": false
			        },
			        {
			          "id": 4,
			          "activated": false
			        },
			        {
			          "id": 5,
			          "activated": false
			        }
			      ]
			    }			}
		}
},


{
	"pldName" :"Refresh HVAC status" ,
	"associatedAction" : "refresh-hvac-status",
	"testedOk" : "yes",
	"pldContents" :
		{
			"data": {
					"type": "RefreshHvacStatus"
				}
		}
},


{
	"pldName" :"Refresh battery status" ,
	"associatedAction" : "refresh-battery-status",
	"testedOk" : "yes",
	"pldContents" :
		{
			"data": {
					"type": "RefreshBatteryStatus"
				}
		}
},


{
	"pldName" :"Refresh GPS status" ,
	"associatedAction" : "refresh-location",
	"testedOk" : "yes",
	"pldContents" :
		{
			"data": {
					"type": "RefreshLocation"
				}
		}
},


{
	"pldName" :"Charge mode -> always" ,
	"associatedAction" : "charge-mode",
	"testedOk" : "yes",
	"pldContents" :
		{
			"data":{
			"type":"ChargeMode",
				"attributes":{
					'action':'always_charging'
				}
			}
		}
},

{
	"pldName" :"Start charging" ,
	"associatedAction" : "Charging-start",
	"testedOk" : "yes",
	"pldContents" :
		{
			"data":{
			"type":"charging-start",
				"attributes":{
					'action':'start'
				}
			}
		}
},


{
	"pldName" :"Stop charging" ,
	"associatedAction" : "Charging-start",
	"testedOk" : "yes",
	"pldContents" :
		{
			"data":{
			"type":"charging-start",
				"attributes":{
					'action':'stop'
				}
			}
		}
},


{
	"pldName" :"Charge mode -> scheduled" ,
	"associatedAction" : "charge-mode",
	"testedOk" : "yes",
	"pldContents" :
		{
			"data":{
			"type":"ChargeMode",
				"attributes":{
					'action':'schedule_mode'
				}
			}
		}
},




{
	"pldName" :"Set Charge Schedule" ,
	"associatedAction" : "charge-schedule",
	"testedOk" : "yes",
	"pldContents" :
		{
			"data":	{
				'type': 'ChargeSchedule',
				'attributes': {
					'schedules': [
					{
						'id':1,'activated':true,
						'monday':{'startTime':"T00:00Z",'duration':15},
						'tuesday':{'startTime':"T00:00Z",'duration':15},
						'wednesday':{'startTime':"T00:00Z",'duration':15},
						'thursday':{'startTime':"T00:00Z",'duration':15},
						'friday':{'startTime':"T00:00Z",'duration':15},
						'saturday':{'startTime':"T00:00Z",'duration':15},
						'sunday':{'startTime':"T00:00Z",'duration':15}
					}
					]
				}
			}
		}
},



{
	"pldName" :"Horn on" ,
	"associatedAction" : "horn-lights",
	"testedOk" : "n/a",
	"pldContents" :
		{
			"data" : {
				"type": "HornLights",
					"attributes": {
						"action": "start",
						"duration": 2,
						"target": "horn",
					}
				}
			}
			//action: "stop", "start", "double_start"
			//target: "horn_lights", "lights", "horn"
},



{
	"pldName" :"Horn on 2" ,
	"associatedAction" : "horn-lights",
	"testedOk" : "n/a",
	"pldContents" :
		{
			"data" : {
				"type": "HornLights",
					"attributes": {
						"action": "double_start",
						"duration": 2,
						"target": "horn",
					}
				}
			}
			//action: "stop", "start", "double_start"
			//target: "horn_lights", "lights", "horn"
},


{
	"pldName" :"Horn off" ,
	"associatedAction" : "horn-lights",
	"testedOk" : "n/a",
	"pldContents" :
		{
			"data" : {
				"type": "HornLights",
					"attributes": {
						"action": "stop",
						"target": "horn",
					}
				}
			}
			//action: "stop", "start", "double_start"
			//target: "horn_lights", "lights", "horn"
},


{
	"pldName" :"Lights on 1" ,
	"associatedAction" : "horn-lights",
	"testedOk" : "n/a",
	"pldContents" :
		{
			"data" : {
				"type": "HornLights",
					"attributes": {
						"action": "start",
						"target": "lights",
					}
				}
			}
			//action: "stop", "start", "double_start"
			//target: "horn_lights", "lights", "horn"
},


{
	"pldName" :"Lights on 2" ,
	"associatedAction" : "horn-lights",
	"testedOk" : "n/a",
	"pldContents" :
		{
			"data" : {
				"type": "HornLights",
					"attributes": {
						"action": "double_start",
						"target": "lights",
					}
				}
			}
			//action: "stop", "start", "double_start"
			//target: "horn_lights", "lights", "horn"
},



{
	"pldName" :"Lights off" ,
	"associatedAction" : "horn-lights",
	"testedOk" : "n/a",
	"pldContents" :
		{
			"data" : {
				"type": "HornLights",
					"attributes": {
						"action": "stop",
						"target": "lights",
					}
				}
			}
			//action: "stop", "start", "double_start"
			//target: "horn_lights", "lights", "horn"
},

//////////


{
	"pldName" :"Horn+Lights on 1" ,
	"associatedAction" : "horn-lights",
	"testedOk" : "n/a",
	"pldContents" :
		{
			"data" : {
				"type": "HornLights",
					"attributes": {
						"action": "start",
						"target": "horn_lights",
					}
				}
			}
			//action: "stop", "start", "double_start"
			//target: "horn_lights", "lights", "horn"
},


{
	"pldName" :"Horn+Lights on 2" ,
	"associatedAction" : "horn-lights",
	"testedOk" : "n/a",
	"pldContents" :
		{
			"data" : {
				"type": "HornLights",
					"attributes": {
						"action": "double_start",
						"target": "horn_lights",
					}
				}
			}
			//action: "stop", "start", "double_start"
			//target: "horn_lights", "lights", "horn"
},



{
	"pldName" :"Horn+Lights off" ,
	"associatedAction" : "horn-lights",
	"testedOk" : "n/a",
	"pldContents" :
		{
			"data" : {
				"type": "HornLights",
					"attributes": {
						"action": "stop",
						"target": "horn_lights",
					}
				}
			}
			//action: "stop", "start", "double_start"
			//target: "horn_lights", "lights", "horn"
},



{
// https://github.com/Tobiaswk/dartnissanconnect/blob/41a5eee9f7dbd04b0b2558ffaa73f0fd77c40fc7/lib/src/nissanconnect_vehicle.dart
	"pldName" :"Lock/unlock doors" ,
	"associatedAction" : "lock-unlock",
	"testedOk" : "not authorized",
	"pldContents" :
			{
			  "data": {
			      "type": "LockUnlock",
			      "attributes": {
							"target": "driver_s_door",
							"action" : "lock"
			      }
			  }
}
},


{
// https://github.com/Tobiaswk/dartnissanconnect/blob/41a5eee9f7dbd04b0b2558ffaa73f0fd77c40fc7/lib/src/nissanconnect_vehicle.dart
	"pldName" :"SRP initiates (???)" ,
	"associatedAction" : "srp-initiates",
	"testedOk" : "n/a",
	"pldContents" :
		{
          'data': {
            'type': 'SrpInitiates',
            'attributes': {
              's': "00000000000000000000", // s = salt, 20 char
              'i': "what?", 							// i = user Id, 0 to 24 chars; value unknown
																					// v = verifier, 512 chars
              'v': "0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef",
            }
		}
	}
},

{
// https://github.com/Tobiaswk/dartnissanconnect/blob/41a5eee9f7dbd04b0b2558ffaa73f0fd77c40fc7/lib/src/nissanconnect_vehicle.dart
	"pldName" :"SRP sets (???)" ,
	"associatedAction" : "srp-sets",
	"testedOk" : "n/a",
	"pldContents" :
		{
          'data': {
            'type': 'SrpSets',
            'attributes': {
              'i': "what?",
              'a': '',
            }
          }
	}
},

{
	"pldName" :"Engine start" ,
	"associatedAction" : "engine-start",
	"testedOk" : "403 - Forbidden",
	"pldContents" :
		{
			"data":{
			"type":"EngineStart",
				"attributes":{
					"action":"start",
				}
			}
		}
},

{
	"pldName" :"Engine stop" ,
	"associatedAction" : "engine-start",
	"testedOk" : "403 - Forbidden",
	"pldContents" :
		{
			"data":{
			"type":"EngineStart",
				"attributes":{
					"action":"stop",
				}
			}
		}
},


///////////



{
	"pldName" :"**** Reset hvac schedule ****(warning!)" ,
	"associatedAction" : "hvac-schedule",
	"testedOk" : "yes",
	"pldContents" :
		{
			"data":{
			"type":"HvacSchedule",
				'attributes': {
					'schedules': [{}]
				}
			}
		}
},



{
	"pldName" :"**** Reset charge schedule **** (warning!)" ,
	"associatedAction" : "charge-schedule",
	"testedOk" : "yes",
	"pldContents" :
		{
			"data":{
				'type': 'ChargeSchedule',
				'attributes': {
					'schedules': [{}]
				}
			}
		}
},



];