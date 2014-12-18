<?php session_start();
?>
<!--
Author: 	Anthony Hess
File: 		new_wx.php
Purpose: 	This is the main page for testing the weather bolding features. It calls
			a php program "display2.php" which then retreives the weather reports for 
			the airtport identifiers listed in that file. That retreives these reports by 
			calling a python script csvSearch.py which searches csv files on the system which contain the 
			current weather reports that NOAA publishes. These reports come out every 5 minues and 
			can be updated on the computer system by calling "load_CSV_met.py" or load_CSV_taf.py.


-->
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1"> 
        <link rel="stylesheet" type="text/css" href="jquery.mobile-1.3.2.min.css" />
        <script type="text/javascript" src="jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="jquery.mobile-1.3.2.min.js"></script>
<title>Personal Weather Data</title>


<style>
	#all{
		font-size: 9px;
		margin-left: 3px;
		margin-top: 3px;
		margin-right: 3px;
		display:inline-block;

	}
	.ui-input-text{
		width: 100px !important;

	}
	label{
		color:white;
	}
	#test{
		display:inline-block;
	}
	#airportID{
		background-color:blue;
		margin-left:15px;
	}
	#loadwx{
		background-color:blue;
		
	}
	b{
		color: red;
	}
	button{	
		width:150px;
	}
	tab{
		margin-left: 15px;
	}

</style>

<script src="jquery-1.11.0.min.js"></script>
<script type="text/javascript">

//global variables
var BOLD = " <b>";
var UNBOLD = "</b>";
var NOBOLD = " <nob>";
var UNNOBOLD = "</nob>";
var cigLimit = 20;  //in hundres of feet  eg. 20 == 2000ft
var windLimit = 20; //speed in kts
var visLimit = 2.75; // distance in SM
var tempLimit = 10;
var dewPointLimit = 0;

//holds user settings
var W_set = {
//SKY CONDITIONS
'check_FEW' : 1, 'check_SCT' : 1, 'check_BKN' : 1, 'check_OVC' : 1, 'check_VV' : 1,
'check_Vceil' : 1,
//WINDS SET
'check_winds' : 1,'check_pkwinds' : 1,
//VISIBILITY SET
'check_visibility' : 1,'check_run_rvr' : 1,
//TEMP/DEWPOINT
'check_temp' : 1,'check_dewpoint' : 1,
//ALTEMETER
'check_alt' : 0,'check_PRES' : 1,
//METAR TIME
'check_met_time' : 0,'check_taf_time' : 0,'check_taf_vtime' : 0,
//CONDITIONS
'check_ltg' : 1,'check_cond' : 1

}

// while the window loads, metars and tafs will be edited then loaded
window.onload = function(){
	var all;
//loads all 
   $.ajax({
      url:'display2.php',
       type: "GET",
      complete: function (data) {
          all = data.responseText;
          all = format_wx(all);
          all = boldline(all);
          document.getElementById("all").innerHTML = all;	
      },
      error: function () {
          $('#all').html('Bummer: there was an error!');
      }
  });
}


// formats the tafs by sections within each taf by FM.. PROB.. TEMPO...
function format_wx(string_in){

		var index;
		var re =/((TEMPO)|(FM[0-9]{6})|(PROB[0-9]{2})|(BECMG))\b/g;
		var found = string_in.match(re);
		if(found){	
			for(index = 0; index < found.length; ++index){
		      string_in = string_in.replace(/(?:\s)((TEMPO)|(FM[0-9]{6})|(PROB[0-9]{2})|(BECMG))\b/,  " <br><tab>" + found[index] + "</tab>");
		  		}
	       }
       return string_in; 
   }


//send in metar or taf or both and the bolding will happen then be returned
function boldline(string_in){

	// highlights if cloud layer is <= cigLimit
	if(W_set.check_FEW){
			var index;
			var re =/(FEW([0-9]{3})(CB)?(TCU)?(ACC)?\b(?!<))/;
			while(found = string_in.match(re)){
					if(found[2] <= cigLimit){ 
			      		string_in = string_in.replace(/(?:\s)(FEW([0-9]{3})(CB)?(TCU)?(ACC)?\b)(?!<)/, BOLD + found[1] + UNBOLD );
			  			}
			  		else{
			  			string_in = string_in.replace(/(?:\s)(FEW([0-9]{3})(CB)?(TCU)?(ACC)?\b)(?!<)/, NOBOLD + found[1] + UNNOBOLD );
			  		}	
		       }
		   }

	// highlights if cloud layer is <= cigLimit
	if(W_set.check_SCT){
			var index;
			var re =/(SCT([0-9]{3})(CB)?(TCU)?(ACC)?\b(?!<))/;
			while(found = string_in.match(re)){
					if(found[2] <= cigLimit){ 
			      		string_in = string_in.replace(/(?:\s)(SCT([0-9]{3})(CB)?(TCU)?(ACC)?\b)(?!<)/, BOLD + found[1] + UNBOLD );
			  			}
			  		else{
			  			string_in = string_in.replace(/(?:\s)(SCT([0-9]{3})(CB)?(TCU)?(ACC)?\b)(?!<)/, NOBOLD + found[1] + UNNOBOLD );
			  		}	
		       }
		   }

	// highlights if cloud layer is <= cigLimit
	if(W_set.check_BKN){
			var index;
			var re =/(BKN([0-9]{3})(CB)?(TCU)?(ACC)?\b(?!<))/;
			while(found = string_in.match(re)){
					if(found[2] <= cigLimit){
			      		string_in = string_in.replace(/(BKN([0-9]{3})(CB)?(TCU)?(ACC)?\b)(?!<)/, BOLD + found[1] + UNBOLD );
			  			}
			  		else{
			  			string_in = string_in.replace(/(BKN([0-9]{3})(CB)?(TCU)?(ACC)?\b)(?!<)/, NOBOLD + found[1] + UNNOBOLD );
			  		}	
		       }
		}

	// highlights if cloud layer is <= cigLimit
	if(W_set.check_OVC){
			var index;
			var re =/(OVC([0-9]{3})(CB)?(TCU)?(ACC)?\b(?!<))/;
			while(found = string_in.match(re)){
					if(found[2] <= cigLimit){ //temp += found[3] + ", ";
			      		string_in = string_in.replace(/(OVC([0-9]{3})(CB)?(TCU)?(ACC)?\b)(?!<)/, BOLD + found[1] + UNBOLD );
			  			}
			  		else{
			  			string_in = string_in.replace(/(OVC([0-9]{3})(CB)?(TCU)?(ACC)?\b)(?!<)/, NOBOLD + found[1] + UNNOBOLD );
			  		}	
		       }
		   }

	//highlights a vertical visibility report if cloud layer is <= cigLimit
	if(W_set.check_VV){
		var index;
		var re =/(VV([0-9]{3})\b(?!<))/;
		while(found = string_in.match(re)){
				if(found[2] <= cigLimit){ 
		      		string_in = string_in.replace(/(VV([0-9]{3})\b)(?!<)/, BOLD + found[1] + UNBOLD );
		  			}
		  		else{
		  			string_in = string_in.replace(/(VV([0-9]{3})\b)(?!<)/, NOBOLD + found[1] + UNNOBOLD );
		  		}	
	       }
	   }

	//highlights a variable ceiling
	if(W_set.check_Vceil){
		var index;
		var re =/(CIG\s([0-9]{3})V([0-9]{3})\b(?!<))/;
		while(found = string_in.match(re)){
				if(found[2] <= cigLimit){ 
		      		string_in = string_in.replace(/(CIG\s([0-9]{3})V([0-9]{3})\b(?!<))/, BOLD + found[1] + UNBOLD );
		  			}
		  		else{
		  			string_in = string_in.replace(/(CIG\s([0-9]{3})V([0-9]{3})\b(?!<))/, NOBOLD + found[1] + UNNOBOLD );
		  		}	
		    }
		 }

	//highlights winds if >= windLimid ***TODO: adjust for detecting gusts and wind/gust spread
	if(W_set.check_winds){
			var index;
			var re =/(([0-9]{3}|VRB)([0-9]{2,3})(G([0-9]{2,3}))?KT)(?!<)/;
			while(found = string_in.match(re)){
				if(found[3] >= windLimit){ 
		      		string_in = string_in.replace(/(([0-9]{3}|VRB)([0-9]{2,3})(G([0-9]{2,3}))?KT)(?!<)/, BOLD + found[1] + UNBOLD );
		  			}
		  		else{
		  			string_in = string_in.replace(/(([0-9]{3}|VRB)([0-9]{2,3})(G([0-9]{2,3}))?KT)(?!<)/, NOBOLD + found[1] + UNNOBOLD );
		  		}	
		    }
		 }

	if(W_set.check_pkwinds){
			var index;
			var re =/(PK\sWND\s([0-9]{3})([0-9]{2,3})\/([0-9]{4}))\b/g;
			var found = string_in.match(re);
			if(found){	
				for(index = 0; index < found.length; ++index){
			      string_in = string_in.replace(/(?:\s)(PK\sWND\s([0-9]{3})([0-9]{2,3})\/([0-9]{4}))/, BOLD + found[index] + UNBOLD);
				    }
		       }
		   }

	//highlights any visibility report <= visLimit
	if(W_set.check_visibility){
		var temp;
			//check for just full whole SM number 
			var re =/\s(P?([0-9]{1,2})SM)(?!<)/;
			var found = string_in.match(re);
			while(found = string_in.match(re)){
				if(found[2] <= visLimit){ 
		      		string_in = string_in.replace(/\s(P?([0-9]{1,2})SM)(?!<)/, BOLD + found[1] + UNBOLD );
		  			}
		  		else{
		  			string_in = string_in.replace(/\s(P?([0-9]{1,2})SM)(?!<)/, NOBOLD + found[1] + UNNOBOLD );
		  		}	
		    }

		    //check for a visibility with a whole and fraction.. (2 1/2SM)
		    re =/(((\b)[0-9]\s)?([0-9])\/([0-9]{0,2})SM)(?!<)/;
			found = string_in.match(re);
			while(found = string_in.match(re)){

				//if there is a whole value with fraction
				if(found[2] != null && found[2] <= visLimit){ 
					temp = found[4]/found[5];
					temp = temp + parseInt(found[2], 10);

					//after adding the fraction to the whole number
					if(temp <= visLimit){
						string_in = string_in.replace(/(((\b)[0-9]\s)?([0-9])\/([0-9]{0,2})SM)(?!<)/, BOLD + found[1] + UNBOLD );
					}

		      		else{
		  			string_in = string_in.replace(/(((\b)[0-9]\s)?([0-9])\/([0-9]{0,2})SM)(?!<)/, NOBOLD + found[1] + UNNOBOLD );
		  			}
		  		}


		  		//if it is just a fraction ... (1/2SM)
		  		else if(found[2] == null){
		  			temp = found[4]/found[5];

					//after adding the fraction to the whole number
					if(temp <= visLimit){
							string_in = string_in.replace(/(((\b)[0-9]\s)?([0-9])\/([0-9]{0,2})SM)(?!<)/, BOLD + found[1] + UNBOLD );
						}

		      		else{
			  			string_in = string_in.replace(/(((\b)[0-9]\s)?([0-9])\/([0-9]{0,2})SM)(?!<)/, NOBOLD + found[1] + UNNOBOLD );
			  			} 
		  			}

		  		else{
		  			string_in = string_in.replace(/(((\b)[0-9]\s)?([0-9])\/([0-9]{0,2})SM)(?!<)/, NOBOLD + found[1] + UNNOBOLD );
		  		}	
		    }
		}

	//bolds a RVR report
	if(W_set.check_run_rvr){
			var index;
			var re =/(R[0-9]{2}\w?\/(M|P)?([0-9]{4})V?P?([0-9]{4})?FT)\b/g;
			var found = string_in.match(re);
			if(found){	
				for(index = 0; index < found.length; ++index){
			      string_in = string_in.replace(/(?:\s)(R[0-9]{2}\w?\/(M|P)?([0-9]{4})V?P?([0-9]{4})?FT)/, BOLD + found[index] + UNBOLD);
				    }
		       }
		   }

	//bolds a temp <= tempLimit
	if(W_set.check_temp){
			var re =/\s(([M])?([0-9]{2}))(?=\/)/;
			while(found = string_in.match(re)){
				// in the case the temp is negative
				if(found[2] != null){
					found[3] = 0 - parseInt(found[3], 10);
				}
					if(found[3] <= tempLimit){ 
			      		string_in = string_in.replace(/\s(([M])?([0-9]{2}))(?=\/)/, BOLD + found[1] + UNBOLD );
			  			}
			  		else{
			  			string_in = string_in.replace(/\s(([M])?([0-9]{2}))(?=\/)/, NOBOLD + found[1] + UNNOBOLD );
			  		}	
		       }
		       
		   }

	if(W_set.check_dewpoint){
			var re =/(\/([M])?([0-9]{2}))(?=\s)/;
						while(found = string_in.match(re)){
				// in the case the temp is negative
				if(found[2] != null){
					found[3] = 0 - parseInt(found[3], 10);
				}
					if(found[3] <= dewPointLimit){ 
			      		string_in = string_in.replace(/(\/([M])?([0-9]{2}))(?=\s)/, "<b>" + found[1] + UNBOLD );
			  			}
			  		else{
			  			string_in = string_in.replace(/(\/([M])?([0-9]{2}))(?=\s)/, "<nob>" + found[1] + UNNOBOLD );
			  		}	
		       }
		       
		   }

	if(W_set.check_alt){
			var index;
			var re =/(A([0-9]{4}))/g;
			var found = string_in.match(re);
			if(found){	
				for(index = 0; index < found.length; ++index){
			      string_in = string_in.replace(/(?:\s)(A([0-9]{4}))/, BOLD + found[index] + UNBOLD); //bold in place due to spacing with temp
				    }
		       }
		   }

	if(W_set.check_PRES){
		string_in = string_in.replace(/(PRESFR)/g, BOLD + "PRESFR" + UNBOLD);
		string_in = string_in.replace(/(PRESRR)/g, BOLD + "PRESRR" + UNBOLD);
	}

	if(W_set.check_ltg){
			var index;
			var re =/((CONS\s)?(FRQ\s)?(OCNL\s)?(LTG)(\w+)?)/g;
			var found = string_in.match(re);
			if(found){	
				for(index = 0; index < found.length; ++index){
			      string_in = string_in.replace(/(?:\s)((CONS\s)?(FRQ\s)?(OCNL\s)?(LTG)(\w+)?)/, BOLD + found[index] + UNBOLD); //bold in place due to spacing with temp
				    }
		       }
		   }

	if(W_set.check_met_time){
			var index;
			var temp;
			var re =/(([A-Z0-9]{4})\s(([0-9]{2})([0-9]{4})Z)(?!\s[0-9]{4}\/))/g;
			var found = string_in.match(re);
			if(found){	
				for(index = 0; index < found.length; ++index){
			      string_in = string_in.replace(/(([A-Z0-9]{4})\s(([0-9]{2})([0-9]{4})Z)(?!\s[0-9]{4}\/)\s)/, BOLD + found[index] + UNBOLD +" ");
			  		}
		       }
		   }

	if(W_set.check_taf_time){
			var index;
			var re =/(TAF\s(AMD\s)?([A-Z0-9]{4})\s(([0-9]{2})([0-9]{4})Z))/g;
			var found = string_in.match(re);
			if(found){	
				for(index = 0; index < found.length; ++index){
			      string_in = string_in.replace(/(TAF\s(AMD\s)?([A-Z0-9]{4})\s(([0-9]{2})([0-9]{4})Z))(?!<)/, BOLD + found[index] + UNBOLD);
			  		}
		       }
		   }

	if(W_set.check_taf_vtime){
			var index;
			var re =/((\b[0-9]{2})([0-9]{2})\/([0-9]{2})([0-9]{2}\b))/g;
			var found = string_in.match(re);
			if(found){	
				for(index = 0; index < found.length; ++index){
			      string_in = string_in.replace(/((\s[0-9]{2})([0-9]{2})\/([0-9]{2})([0-9]{2}\s))/, BOLD + found[index] + UNBOLD + " ");
			  		}
		       }
		   }

	//break this down
	if(W_set.check_cond){
			var index;
			var re = /(\s([\+\-]?)(VC)?(MI|RA|SN|BC|PR|TS|BL|SH|DR|FZ)?((DZ)|(RA)|(SN)|(SQ)|(IC)|(PE)|(BR)|(FG)|(FU)|(HZ)|(VA)|(DU)|(SA)|(DS)|(FC)|(MI)|(BC)|(DR)|(BL)|(TS)|(FZ)|(VC)|(SS)|(SG)|(SH)|(GR)|(GS)))(?=\s)/g;
			var found = string_in.match(re);
			if(found){	
				for(index = 0; index < found.length; ++index){
			      string_in = string_in.replace(/(\s([\+\-]?)(VC)?(MI|SN|RA|BC|PR|TS|BL|SH|DR|FZ)?((DZ)|(RA)|(SN)|(SQ)|(IC)|(PE)|(BR)|(FG)|(FU)|(HZ)|(VA)|(DU)|(SA)|(DS)|(FC)|(MI)|(BC)|(DR)|(BL)|(TS)|(FZ)|(VC)|(SS)|(SG)|(SH)|(GR)|(GS)))(?:\s)/, BOLD + found[index] + UNBOLD + " ");
			  		}
		       }
		   }

	// "BOLDED" string to return
		     return string_in;  
	}

// used to update the csv files on the system when the update button is pushed
function update(){
   $.ajax({
      url:'manage.php',
       type: "GET",
      complete: function (data) {
          document.getElementById("test").innerHTML = data.responseText;	
      },
      error: function () {
          $('#test').html('Bummer: there was an error!');
      }
  });
}

function Airport(){
	var output = "The new metars will go here"
	document.getElementById("all").innerHTML = output;
}


</script>	

</head>

<body>
	
	<div id="test">Status:</div>
	<button onclick="update()" data-inline="true" data-mini="true" class="ui-btn-right">Update CSVs</button><br>

<div id = "loadwx">
	<form id = "airportID" type = "text">
		<label for="basic">Airport ID:</label>
		<input type="text" name="name" id="basic" data-mini="true" />
		<button onclick="Airport()" data-inline="true" data-mini="true">Load Weather Report</button><br>
	</form>
</div>

<div id="all">Page Loading</div><br>


</body>
</html>