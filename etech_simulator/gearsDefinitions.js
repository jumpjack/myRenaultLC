all =   [
			[
				[
					[2,2,2],[1,2,2],  [3,2,2]
				],
				[
					[2,1,2], [1,1,2],  [3,1,2]
				],
				[
					[2,3,2], [1,3,2],  [3,3,2]
				],
			],
			[
				[
					 [2,2,1],  [1,2,1], [3,2,1]
				],
				[
					 [2,1,1],  [1,1,1], [3,1,1]
				],
				[
					 [2,3,1],  [1,3,1],  [3,3,1]
				],
			],
			 [
				[
					 [2,2,3,],  [1,2,3] ,  [3,2,3]
				],
				[
					 [2,1,3,],   [1,1,3] , [3,1,3]
				],
				[
					 [2,3,3,],   [1,3,3] , [3,3,3]
				],
			]
		];
		


meshedGears =   [
			[
				[
					{
						E:		"<b>E</b> -> 9:11 -> R -> 13:15 -> T -> 17:18, 20:10 -> <b>E - LOOP!</b>", 
						T:		"<b>T</b> -> 17:18, 20:10 -> E -> 9:11 -> R -> 13:15  -> <b>T - LOOP!</b>", 
						Combo: "ERR"
					}, // [2,2,2]  
						{
						E:		"E -> 9:11 -> R.", 														
						T:		"T -> 15:13 -> R.",
						Combo : "Parallel"
					},   // 122
					{
						E:		"E -> 9:11 -> R.", 					
						T:		"<b>T</b> -> 17:18, 19:16, 16:14 -> R -> 13:15 -> <b>T - LOOP!</b>",			 
						Combo: "ERR"
					} // [3,2,2]
				],
				[
					{
						E:		"E -> 9:11 -> R.", 														
						T:		"T -> 17:18, 20:10 -> E... (coupled)", 												
						Combo: 	"T -> 17:18, 20:10 -> E -> 9:11 -> R. (Serial)"
					}, // [2,1,2] 
					{
						E:		"E -> 9:11 -> R.", 														
						T:		"Disconnected",
						Combo : "Pure EV"
					},  					//  [1,1,2]
					{
						E:		"E -> 9:11 -> R.", 														
						T:		"T -> 17:18, 19:16, 16:14 -> R.",
						Combo : "Parallel"
					} 		//  [3,1,2]
				],
				[
					{
						E:		"<b>E</b> -> 9:11 -> R -> 14:16 -> T -> 17:18, 20:10 -> <b>E - LOOP!</b>", 
						T:		"<b>T</b> -> 17:18, 20:10 -> E -> 9:11 -> R ->  14:16 -> <b>T - LOOP</b>. ", 
						Combo: "ERR"
					},  //[2,3,2]
					{
						E:		"E -> 9:11 -> R.", 														
						T:		"T -> 16:14 -> R.",
						Combo : "Parallel"
					},   // [1,3,2]
					{
						E:		"E -> 9:11 -> R.",														
						T:		"<b>T</b> -> 17:18, 19:16 -> <b>T - LOOP!</b>", 							
						Combo: "ERR"
					} //  [3,3,2]
				],
			],
			[
				[
					 {
						 E:		"Disconnected", 														
						 T:		"T -> 17:18 , 20:10 -> E. ,  T -> 15:13 -> R.   (unused)",
						Combo : "T to E, T to Wheels"
					 },  // [2,2,1]
					 {
						 E:		"Disconnected",															
						 T:		"T -> 15:13 -> R.",
						Combo : "Pure ICE"
					 },		// [1,2,1]
					 {
						 E:		"Disconnected", 														
						 T:		"<b>T</b> -> 17:18, 19:16, 16:14 -> R -> 13:15 -> <b>T - LOOP!</b>", 			
						 Combo: "ERR"
					 }, // [3,2,1]
				],
				[
					 {
						 E:		"Disconnected", 														
						 T:		"T -> 17:18, 20:10 -> E.", 												
						 Combo: "Charge by Main Motor + HSG (75kW)?"
					 },   //   [2,1,1]
					 {
						 E:		"Disconnected", 														
						 T:		"Disconnected",
						Combo : "Neutral EV, Neutral ICE"
					 },  	//   [1,1,1]
					 {
						 E:		"Disconnected", 														
						 T:		"T -> 17:18, 19:16, 16:14 -> R.",
						Combo : "Pure ICE"
					 }	//	 [3,1,1]
				],
				[
					 {
						 E:		"Disconnected", 														
						 T:		"T -> 17:18 , 20:10 -> E. , T -> 16:14 -> R.  (unused)",
						Combo : "T to E, T to Wheels"
					 },  //  [2,3,1]
					 {
						 E:		"Disconnected", 														
						 T:		"T -> 16:14 -> R.",
						Combo : "Pure ICE"
					 },  //  [1,3,1]
					 {
						 E:		"Disconnected", 														
						 T:		"<b>T</b> -> 17:18, 19:16 -> <b>T - LOOP!</b>", 							
						 Combo: "ERR"
					 },	//  [3,3,1]
				],
			],
			 [
				[
					 {
						 E:		"E -> 10:12 -> R.", 													
						 T: 	"<b>T</b> -> 17:18, 20:10 -> E-> 10:12 -> R -> 13:15 -> <b>T - LOOP</b>", 
						 Combo : "ERR"
					 } , // [2,2,3,]  
					 {
					 	E:		"E -> 10:12 -> R.", 												
					 	T:		"T -> 15:13 -> R.",
						Combo:	"Parallel"
						} ,  
					 {
					 	E:		"E -> 10:12 -> R.", 												
					 	T:		"<b>T</b> -> 17:18, 19:16, 16:14 -> R -> 13:15 -> <b>T - LOOP!</b>", 			
						Combo: "ERR"
					}  // [3,2,3]
				],
				[
					 {
						 E:		"E -> 10:12 -> R.", 													
						 T:		"T -> 17:18, 20:10 -> E... (coupled)", 												
						 Combo: "T -> 17:18, 20:10 -> E - 10:12 -> R. (Serial)"
					 },   
					 {
						 E:		"E -> 10:12 -> R.", 													
						 T:		"Disconnected",
						Combo : "Pure EV"
					 } , 
					 {
						 E:		"E -> 10:12 -> R.", 													
						 T:		"T -> 17:18, 19:16, 16:14 -> R.",
						Combo : "Parallel"
					 }
				],
				[
					 {
						 E:		"E -> 10:12 -> R.", 
						 T: 	"<b>T</b> -> 17:18, 20:10 -> E-> 10:12 -> R -> 14:16 -> <b>T - LOOP</b>", 
						 Combo: "ERR"
					 },   // [2,3,3,]  
					 {
						 E:		"E -> 10:12 -> R.", 
						 T: 	"T -> 16:14 -> R.",
						Combo : "Parallel"
					 },
					 {
						 E:		"E -> 10:12 -> R.", 
						 T: "<b>T</b> -> 17:18, 19:16 -> <b>T - LOOP!</b>", 
						 Combo: "ERR"
					 } // [3,3,3]
				],
			]
		];
			


gearsNames =   [
					[
						[
							"Forbidden (222)", "ICE2 + EvA (122)", "Forbidden (322)"
						],
						[
							"ICE1 + EvA (212)", "EvA (112)", "ICE3 + EvA (312)"
						],
						[
							"Forbidden (232)", "ICE4 + EvA (132)", "Forbidden (332)"
						],
					],
					
					[
						[
							 "UNUSED (221) ", "ICE2 (121)", "Forbidden (321)"
						],
						[
							 "Charge (211)", "ICE neutral (111)", "ICE3 (311)"
						],
						[
							"UNUSED (231)", "ICE4 (131)", "Forbidden (331)"
						],
					],
					
					
					 [
						[
							 "Forbidden (223)", "ICE2 + EvB (123)", "Forbidden (323)"
						],
						[
							 "ICE5 + EvB (213)", "EvB (113)", "ICE3 + EvB (313)",
						],
						[
							 "Forbidden (233)", "ICE4 + EvB (133)", "Forbidden (333)"
						],
					],
				];
		



				
forbidden = [
	[2,2,2], [3,2,2],	[2,3,2], [3,3,2], [3,2,1], [3,3,1] , [2,2,3,], [2,3,3,], [3,2,3], [3,3,3]
]						

/*
wheels = [ // pixelAbs = Distance from topmost edge of image; axis distance = 200
	{pixelAbs: -1}, 
	{pixelAbs: -1}, 
	{pixelAbs: -1}, 
	{pixelAbs: -1}, 
	{pixelAbs: -1}, 
	{pixelAbs: -1}, 
	{pixelAbs: -1}, 
	{pixelAbs: -1}, 
	{pixelAbs: -1}, 
	{pixelAbs: 146}, // 9 
	{pixelAbs: 113},// 10
	{pixelAbs:  96},// 11
	{pixelAbs: 130},// 12
	{pixelAbs: 124},// 13
	{pixelAbs: 136},// 14
	{pixelAbs: 117},// 15
	{pixelAbs: 106},// 16
	{pixelAbs: 112},// 17
	{pixelAbs: 111},// 18 ???? TBW
	{pixelAbs: 111},// 19
	{pixelAbs: 103} // 20
]

wheelsAxle = 200;
*/

wheels = [ // pixelAbs = Distance from topmost edge of image; axis distance = 1159
	{pixelAbs: -1}, 
	{pixelAbs: -1}, 
	{pixelAbs: -1}, 
	{pixelAbs: -1}, 
	{pixelAbs: -1}, 
	{pixelAbs: -1}, 
	{pixelAbs: -1}, 
	{pixelAbs: -1}, 
	{pixelAbs: -1}, 
	{pixelAbs: 1252}, // 9 
	{pixelAbs: 1311},// 10
	{pixelAbs: 1351},// 11
	{pixelAbs: 1318},// 12
	{pixelAbs: 1331},// 13
	{pixelAbs: 1275},// 14
	{pixelAbs: 1300},// 15
	{pixelAbs: 1321},// 16
	{pixelAbs: 1315},// 17
	{pixelAbs: 1331},// 18 
	{pixelAbs: 1321},// 19
	{pixelAbs: 1331} // 20 - org: 1331
];
wheelsAxle = 1159;

fluxFlags = { EV_bat: false, ICE_bat: false, ICE_wheels : false}

