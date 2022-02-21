#!/bin/bash
# creating a menu with the following options
echo "SELECT Your Options"
echo "0. All"
echo "1. Git Extension"
echo "2. VS Code"
echo "3. Skype"
echo "4. Chrome"
echo "5. Firfox"
echo "6. Slack"
echo "7. Thunderbird"
echo "8. Mysql workbench"
echo "9. Exit"
echo "Choose Your Options between [0-9]:"
while :
do
read choice
case $choice in
  0) mono /home/tosif/Downloads/GitExtensions-2.51.05-Mono/GitExtensions/GitExtensions.exe &
code &
skype &
google-chrome &
firefox &
slack &
thunderbird &
mysql-workbench;;
  # Pattern 1
  1)  echo "You have selected the option 1"
     mono /home/tosif/Downloads/GitExtensions-2.51.05-Mono/GitExtensions/GitExtensions.exe & ;;
  # Pattern 2
  2)  echo "You have selected the option 2"
     code & ;;
  # Pattern 3
  3)  echo "You have selected the option 3"
      skype &  ;;
  # Pattern 4
  4)   echo "You have selected the option 4"
     google-chrome & ;;
  5)  echo "You have selected the option 5"
    firefox & ;;
  6)  echo "You have selected the option 6"
        slack & ;; 
  7)  echo "You have selected the option 7"
       thunderbird & ;;
    8)  echo "You have selected the option 8"
    mysql-workbench ;;
   9) echo "Quiting"  
   exit;;
  # Default Pattern
  *) echo "invalid option";;
  
esac
  echo -n "Enter your menu choice [1-4]: "
done