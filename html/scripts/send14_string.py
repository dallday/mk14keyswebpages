#! /usr/bin/env python3

#---------------------------------------------------------------------------------------------------
# Program to send a set of keys to an MK14
# as key presses via an array of optocouplers connected to the
# MK14's external keypad connector.
#
# David Allday Dec 2021
# adapted from the one supplied by SiriusHardware
# https://www.vintage-radio.net/forum/showpost.php?p=1301405&postcount=90
# changed so it can be loaded as a module and the functions used as required
#     matched to version 1.3 of the send14_file.py and loaded into that script.
#
#---------------------------------------------------------------------------------------------------

# import the routines needed
import sys
import time
import RPi.GPIO as GPIO

# The following delay values are the smallest which work consistently on a Pi Zero W, therefore
# giving the fastest reliable upload speed. If you run this on a faster Pi you may need to
# increase these delays.

# Minimum length of a keypress (seconds).
KeyPressLength=0.005
# Time for post-release debounce delay (seconds).
#KeyReleaseLength=0.005
KeyReleaseLength=0.015
# Additional settle time after entry mode change (seconds).
#ModeChangeSettleTime=0.006
ModeChangeSettleTime=0.016
# reset hold time to hold the reset on
ResetHoldTime=0.10
# reset Settle Time to wait after reset
ResetSettleTime=0.16

#------------------------------------------------------------------------
# Function: Initialise all GPIO ports used by this program
#------------------------------------------------------------------------

def SetupGPIOs():
    GPIO.setmode(GPIO.BCM)
    GPIO.setwarnings(False)
    Pins=[27,18,17,4,22,23,24,9,25,8,5,7,12]
    for x in range (0,13):
        GPIO.setup(Pins[x],GPIO.OUT)
        GPIO.output(Pins[x],GPIO.HIGH)

#-------------------------------------------------------------------------
# Function: cleanup the GPIO system
#-------------------------------------------------------------------------

def CloseGPIOs():
    # Tidy up the GPIO ports
    GPIO.cleanup()

#------------------------------------------------------------------------
# Function: Momentarily connect the specified key row / key column
# together by activating their associated GPIO port pins
#------------------------------------------------------------------------

def Push(RowPin,ColumnPin):
    GPIO.output(RowPin,GPIO.LOW)
    GPIO.output(ColumnPin,GPIO.LOW)
    time.sleep(KeyPressLength)
    GPIO.output(ColumnPin,GPIO.HIGH)
    GPIO.output(RowPin,GPIO.HIGH)
    time.sleep(KeyReleaseLength)

#---------------------------------------------------------------------------------------------------
# Function: Convert characters 0-9,A-F,a-f and the command keys g-m-z-t to
# physical keypresses. The numbers are the BCM GPIO pin pairs
# which have to be asserted to press each key.
# used z instead of a for abort as it can except lower and upper case characters
#---------------------------------------------------------------------------------------------------

def Press_MK14_Key(keyp):

    # print for debugging
    # print ("Key:", keyp)
    # switch to uppercase to all a to f and A to F
    key = keyp.upper()

    if key=="T":          # Term
        Push(27,7)
    elif key=="M":        # Mem
        Push(27,9)
    elif key=="0":
        Push(4,22)
    elif key=="1":
        Push(4,23)
    elif key=="2":
        Push(4,24)
    elif key=="3":
        Push(4,9)
    elif key=="4":
        Push(4,25)
    elif key=="5":
        Push(4,8)
    elif key=="6":
        Push(4,5)
    elif key=="7":
        Push(4,7)
    elif key=="8":
        Push(17,22)
    elif key=="9":
        Push(17,23)
    elif key=="A":
        Push(18,22)
    elif key=="B":
        Push(18,23)
    elif key=="C":
        Push(18,24)
    elif key=="D":
        Push(18,9)
    elif key=="E":
        Push(18,5)
    elif key=="F":
        Push(18,7)
    elif key=="G":          # Go
        Push(27,24)
    elif key=="Z":          # Abort - used z instead of a
        Push(27,25)
    elif key=="R":          # System Reset
        GPIO.output(12,GPIO.LOW)
        time.sleep(ResetHoldTime)    # Time to hold MK14 in Reset 0.01
        GPIO.output(12,GPIO.HIGH)
        time.sleep(ResetSettleTime)    # Allow MK14 time to come back up after Reset 0.06
    else:
        # print for debugging
        if debugmode==1:
            print ("unknown key ", keyp)

#-----------------------------------------------------------------------------

#---------------------------------------------------------------------------------------------------
# Main Body Of Program if called as main program
#---------------------------------------------------------------------------------------------------

# need error trap at high level ????

# if this is the main routine called then send the characters 
# 
if __name__ == '__main__':

    try:
        debugmode=0  # set to 1 if a second parameter is supplied to the call
        # prints for debugging
        if len((sys.argv))>2:
            debugmode=1
        if debugmode==1:
            print ("send14_string called")
            print ("a second line to test ")
            print ("length of sys.argv",len((sys.argv)) );
        # Pull the string from the argument supplied.
        if len ((sys.argv))>1:
            datatosend=(sys.argv[1])
            if debugmode==1:
                print ("Data to send ", datatosend)
            # Initialise GPIOs
            SetupGPIOs()
            for char in datatosend:
                # send each characters
                # it will ignore any incorrect characters 
                if debugmode==1:
                    print(char)
                Press_MK14_Key (char)
            # Tidy up the GPIO ports
            GPIO.cleanup()
            print (datatosend)
        # The user did not supply any data
        else:
            # i'm aware this does not work so need a better debug mode
            if debugmode==1:
                print("Please supply data to send")
    except Exception as  err:
        print('ERROR - ' + str(err))
        
# end of code 

