import re
import csv
import sys

#Author:	Anthony Hess
#File:		csvSearch.py
# This file searches the corresponding .csv file for the taf or metar 
# related to the airport ID. 
# ARGV [1]  accepts airport identifier   eg. KSEA
# ARGV[2] accepts "tafs" or "metars" 

def main(argv):
	#add arg2 for taf/metar
	if len(sys.argv) != 3:  # the program name and the two arguments
  		# stop the program and print an error message
  		sys.exit("Must provide airport ident and report type (taf or metars)\n")

  		# arguments used for airport ID and whether its a TAF or Metar
	airport = sys.argv[1]
	met_or_taf = sys.argv[2]

	if(met_or_taf != "metars" and met_or_taf != "tafs"):
		return "unknown report type, must be \"metars\" or \"tafs\"\n"

	airport = airport.upper()
	a = re.compile("(^[A-Z0-9]{4}$)")
	if a.match(airport):
		with open(met_or_taf + '.cache.csv', 'rt') as f:
		     	reader = csv.reader(f, delimiter=',') 
		     	for row in reader:
		     		if airport in row[0]: 
		     			return row[0]

		return "no airport found in file\n"
	else:
		return "Ident does not match code requirements\n"

if __name__ == "__main__":
   ret = main(sys.argv[1:])
   print ret
   
