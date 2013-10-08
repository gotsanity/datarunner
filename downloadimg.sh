#!/bin/bash
NUMBER=1

while [ $NUMBER -lt 114 ]; do #This will do our counting
	while [ $NUMBER -lt 10 ]; do
		wget http://netrunnerdb.com/web/bundles/netrunnerdbcards/images/cards/en/0100$NUMBER.png
		let "NUMBER += 1" #increment
	done
	while [ $NUMBER -lt 100 ]; do
		wget http://netrunnerdb.com/web/bundles/netrunnerdbcards/images/cards/en/010$NUMBER.png
		let "NUMBER += 1" #increment
	done
	while [ $NUMBER -lt 114 ]; do
		wget http://netrunnerdb.com/web/bundles/netrunnerdbcards/images/cards/en/01$NUMBER.png
		let "NUMBER += 1" #increment
	done
done
#EOF
