#!/bin/bash
NUMBER=1

while [ $NUMBER -lt 121 ]; do #This will do our counting
	while [ $NUMBER -lt 10 ]; do
		wget http://netrunnerdb.com/web/bundles/netrunnerdbcards/images/cards/en/0400$NUMBER.png
		let "NUMBER += 1" #increment
	done
	while [ $NUMBER -lt 44 ]; do
		wget http://netrunnerdb.com/web/bundles/netrunnerdbcards/images/cards/en/040$NUMBER.png
		let "NUMBER += 1" #increment
	done
#	while [ $NUMBER -lt 121 ]; do
#		wget http://netrunnerdb.com/web/bundles/netrunnerdbcards/images/cards/en/02$NUMBER.png
#		let "NUMBER += 1" #increment
#	done
done
#EOF
