#!/bin/bash
ls
echo "What is your name"
read PERSON
echo "Hello $PERSON"
NAME="HI PERSON"
echo $NAME
NAME="Zara Ali"
readonly NAME
echo $NAME

echo "File Name: $0"
echo "Total Number of Parameters : $#"

#./script.sh it is a scripting language
for TOKEN in $*
do
   echo $TOKEN
done

#declare -a NAMEARR[4]
NAMEARR[0]="Zara"
NAMEARR[1]="Qadir"
NAMEARR[2]="Mahnaz"
NAMEARR[3]="Ayan"
NAMEARR[4]="Daisy"
echo "first index- ${NAMEARR[0]}"
echo "all index- ${NAMEARR[@]}"
echo "all 1 index- ${NAMEARR[*]}"

for i in ${NAMEARR[@]}
do
        echo $i
done

val=`expr 2 + 2`
echo "Total value : $val"

a=10
b=20
if [ $a -eq $b ]
then
   echo "$a -eq $b : a is equal to b"
else
   echo "$a -eq $b: a is not equal to b"
fi

if [ $a -ne $b ]
then
   echo "$a -ne $b: a is not equal to b"
else
   echo "$a -ne $b : a is equal to b"
fi

DATE=`date`
echo "Date is $DATE"

USERS=`who | wc -l`
echo "Logged in user are $USERS"

UP=`date ; uptime`
echo "Uptime is $UP"