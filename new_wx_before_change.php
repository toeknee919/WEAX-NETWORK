<?php session_start();
?>

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
	}
	b{
		color: red;
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
var NOBOLD = "<nob>";
var UNNOBOLD = "</nob>";

//assoc array holds user settings
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
'check_alt' : 1,'check_PRES' : 1,
//METAR TIME
'check_met_time' : 1,'check_taf_time' : 1,'check_taf_vtime' : 1,
//CONDITIONS
'check_ltg' : 1,'check_cond' : 1

}

// when the window loads, metars and tafs will be edited
window.onload = function(){
	var all;
	var boldtaf;
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


//boldtaf = document.getElementById("taf").innerHTML;
//boldtaf = boldline(boldtaf);
//document.getElementById("taf").innerHTML = boldtaf;
}


function format_wx(string_in){

		var index;
		var re =/((TEMPO)|(FM[0-9]{6})|(PROB[0-9]{2}))\b/g;
		var found = string_in.match(re);
		if(found){	
			for(index = 0; index < found.length; ++index){
		      string_in = string_in.replace(/(?:\s)((TEMPO)|(FM[0-9]{6})|(PROB[0-9]{2}))\b/,  "<br><tab>" + found[index] + "</tab>");
		  		}
	       }
       return string_in; 
   }








//send in metar or taf or both and the bolding will happen then be returned
function boldline(string_in){


if(W_set.check_FEW){
		var index;
		var re =/(FEW([0-9]{3})(CB)?(TCU)?(ACC)?)\b/g;
		var found = string_in.match(re);
		if(found){	
			for(index = 0; index < found.length; ++index){
		      string_in = string_in.replace(/(?:\s)(FEW([0-9]{3})(CB)?(TCU)?(ACC)?)/, BOLD + found[index] + UNBOLD);
		  		}
	       }
	   }

if(W_set.check_SCT){
		var index;
		var re =/(SCT([0-9]{3})(CB)?(TCU)?(ACC)?)\b/g;
		var found = string_in.match(re);
		if(found){		
			for(index = 0; index < found.length; ++index){
		      string_in = string_in.replace(/(?:\s)(SCT([0-9]{3})(CB)?(TCU)?(ACC)?)/, BOLD + found[index] + UNBOLD);
			    }
	       }
	   }


if(W_set.check_met_time){
		var index;
		var temp = "";
		var found;
		var re =/((BKN([0-9]{3})(CB)?(TCU)?(ACC)?)(?!<))/;
		while(found = string_in.match(re)){
				if(found[3] <= 80){ temp += found[3] + ", ";
		      		string_in = string_in.replace(/(?:\s)(BKN([0-9]{3})(CB)?(TCU)?(ACC)?)/, BOLD + found[1] + UNBOLD +" ");
		  			}
		  		else{
		  			string_in = string_in.replace(/(?:\s)(BKN([0-9]{3})(CB)?(TCU)?(ACC)?)/, BOLD + found[1] + UNBOLD +" ");
		  		}	
	       }
	       document.getElementById("test").innerHTML = temp;
	}





// if(W_set.check_BKN){
// 		var index;
// 		var re =/(BKN([0-9]{3})(CB)?(TCU)?(ACC)?)\b/g;
// 		var found = string_in.match(re);
// 		if(found){	
// 			for(index = 0; index < found.length; ++index){
// 		      string_in = string_in.replace(/(?:\s)(BKN([0-9]{3})(CB)?(TCU)?(ACC)?)/, BOLD + found[index] + UNBOLD);
// 		  		}
// 	       }
// 	   }

if(W_set.check_OVC){
		var index;
		var re =/(OVC([0-9]{3})(CB)?(TCU)?(ACC)?)\b/g;
		var found = string_in.match(re);
		if(found){	
			for(index = 0; index < found.length; ++index){
		      string_in = string_in.replace(/(?:\s)(OVC([0-9]{3})(CB)?(TCU)?(ACC)?)/, BOLD + found[index] + UNBOLD);
			    }
	       }
	   }

if(W_set.check_VV){
		var index;
		var re =/(VV([0-9]{3}))\b/g;
		var found = string_in.match(re);
		if(found){	
			for(index = 0; index < found.length; ++index){
		      string_in = string_in.replace(/(?:\s)(VV([0-9]{3}))/, BOLD + found[index] + UNBOLD);
			    }
	       }
	   }

if(W_set.check_Vceil){
		var index;
		var re =/(CIG\s([0-9]{3})V([0-9]{3}))\b/g;
		var found = string_in.match(re);
		if(found){	
			for(index = 0; index < found.length; ++index){
		      string_in = string_in.replace(/(?:\s)(CIG\s([0-9]{3})V([0-9]{3}))/, BOLD + found[index] + UNBOLD);
			    }
	       }
	   }

if(W_set.check_winds){
		var index;
		var re =/(([0-9]{3}|VRB)([0-9]{2,3})(G([0-9]{2,3}))?KT)\b/g;
		var found = string_in.match(re);
		if(found){	
			for(index = 0; index < found.length; ++index){
		      string_in = string_in.replace(/(?:\s)(([0-9]{3}|VRB)([0-9]{2,3})(G([0-9]{2,3}))?KT)/, BOLD + found[index] + UNBOLD);
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

if(W_set.check_visibility){
		var index;
		var re =/(P?([0-9])SM|(((\b)[0-9]\s)?[0-9]\S[0-9]{0,2})SM)/g;
		var found = string_in.match(re);
		if(found){	
			for(index = 0; index < found.length; ++index){
		      string_in = string_in.replace(/(P?([0-9])SM|(((\b)[0-9]\s)?[0-9]\S[0-9]{0,2})SM)(?!<)/, BOLD + found[index] + UNBOLD);
			    }
	       }
	   }

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

if(W_set.check_temp){
		var index;
		var re =/(?:\s)([M]?[0-9]{2})(?=\/)/g;
		var found = string_in.match(re);
		if(found){	
			for(index = 0; index < found.length; ++index){
				 string_in = string_in.replace(/(?:\s)([M]?[0-9]{2})(?=\/)/, BOLD + found[index] + UNBOLD);
			    }
	       }
	       
	   }

if(W_set.check_dewpoint){
		var index;
		var re =/(?:\/)([M]?[0-9]{2})(?=\s)/g;
		var found = string_in.match(re);
		if(found){	
			for(index = 0; index < found.length; ++index){
		      string_in = string_in.replace(/(?:\/)([M]?[0-9]{2})(?=\s)/, "<b>" + found[index] + UNBOLD); //bold in place due to spacing with temp
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



// string to return to the main function
	     return string_in;  
	}


</script>	

</head>

<body>
<div id="test">Other stuff here</div><br>
<div id="all">Page Loading</div><br>


</body>
</html>