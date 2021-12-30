#! /usr/bin/env python3

#---------------------------------------------------------------------------------------------------
# Program to read in a named Intel Hex file and send it to an MK14
# as key presses via an array of optocouplers connected to the
# MK14's external keypad connector.

# V1.1: Bug Fixed: Intel hex lines with checksum '00' would not load. -SH
#
# V1.2: Bug Fixed: where an execution address was found at FFFF/FFFE in file,
# uploader  was (unintentionally) also typing the execution address into
# 0FFE/0FFF, disrupting the state of the hardware flag outputs. -SH

# V1.3: updated to fit in with the MK14keys webpages
#
# David Allday Dec 2021
# adapted from the one supplied by SiriusHardware
# https://www.vintage-radio.net/forum/showpost.php?p=1301405&postcount=90
#
# import the lowlevel routines from send14_string.py
# and added 2nd parameter to say what OS is on the MK14
# so
# send14_file.py filename.hex 1
#   uses the 'old' OS with the "---- --" reset prompt
# send14_file.py filename.hex 2
#   uses the 'new' OS with the "0000 00" reset prompt
# set the MK14_OS below to the default if no parameter is used.
# 
#---------------------------------------------------------------------------------------------------

import sys
import os.path
from send14_string import *     # the base code to send keycodes to the MK14 from send14_string.py

# If your MK14 has the 'Old' OS with the "---- --" reset prompt
# edit the line below to read 'MK14_OS = 1'
# If your MK14 has the 'new' OS with the "0000 00" reset prompt
# edit the line below to read 'MK14_OS = 2'
# This is the default setting - it can be reset by suppplying a second paramters see above

MK14_OS = 2


#---------------------------------------------------------------------------------------------------
# Function: Read Intel Hex file, send to MK14 as keypresses
#---------------------------------------------------------------------------------------------------

def SendFileToMK14(FileName):

    # Try to reset the MK14 before sending the file
    print ("Resetting...")
    Press_MK14_Key("r")

    # Assume by default that this file does not contain an execution
    # address at FFFE unless we find otherwise.
    ExecuteFlag=0

    # Initially no errors have occurred. This flag will be set to 1
    # If an error occurs during the load process
    ErrorFlag=0

    LineNumber=0
    # Open the file
    with open(FileName) as fileobj:

        # While there are lines to read from the file, read a line

        AddressAsHex="" # Hex string copy of most recent address incremented to. Empty on first pass.

        print ("Sending... ")

        for lineraw in fileobj:
           # allow reports to say what line failed
           LineNumber += 1

           # convert line to upper case
           line = lineraw.upper()

           # Check for Intel Hex Line start character
           # If not, declare file invalid and exit

           if (line [0]!=":"):
                ErrorFlag=1
                print ("Line ", LineNumber, " does not start with :")
                print ("Invalid Hex File")
                break
           else:
                # Read this line's 'record type',
                # If it is not a normal record (00), don't do anything with the line

                RecordTypeString=line[7:9]
                if (RecordTypeString=="00"):

                   # ..A normal record, so proceed to scan the line
                   # Zero the calculated line checksum

                   ChkSum=0

                   # By default, send whatever is read from the line to the
                   # MK14. This can be overridden with OutputMode = 0 if we only
                   # want to read and checksum the line, and not send it.

                   OutputMode=1

                   #Convert the record type to raw and add it to the calculated checksum

                   RecordTypeRaw=int(RecordTypeString, 16)
                   ChkSum=ChkSum+RecordTypeRaw

                   # Get the number of data bytes this line holds,
                   # convert to raw value and add to checksum.

                   DataBytesCountString=line[1:3]
                   DataBytesCount=int(DataBytesCountString,16)
                   ChkSum=ChkSum+DataBytesCount

                   # Get the four address digits, convert to raw 4 digit
                   # address, used as address upcounter.

                   AddressDigitsString=line[3:7]
                   AddressDigitsRaw=int(AddressDigitsString,16)

                   # Get the hi byte (only) of the address,
                   # add its byte value to the checksum.

                   AddressDigitsHiString=AddressDigitsString[0:2]
                   AddressDigitsHiRaw=int(AddressDigitsHiString,16)
                   ChkSum=ChkSum+AddressDigitsHiRaw

                   # Get the lo byte (only) of the address,
                   # add its byte value to the checksum.

                   AddressDigitsLoString=AddressDigitsString[2:4]
                   AddressDigitsLoRaw=int(AddressDigitsLoString,16)
                   ChkSum=ChkSum+AddressDigitsLoRaw

                   # If the start address of this line does not follow on
                   # consecutively from the last address in the previous
                   # line, first check if it is = 0xFFFE. If so, harvest the
                   # execution address but do not output the rest of the line.
                   # If not, go to address entry mode, enter the new address,
                   # then return to data entry mode and carry on sending data
                   # to the new address onwards.

                   if (AddressDigitsString!=AddressAsHex):

                           # Further check: if the start address of this line is
                           # FFFE = a 'run address' line...
                       if (AddressDigitsString=="FFFE") & (DataBytesCount > 1):
                           ExecutionAddressHiString=line[9:11]
                           ExecutionAddressLoString=line[11:13]
                           ExecuteFlag=1 # Execution address was found
                           OutputMode=0  # Validate line but do not send it
                       else:
                           # change to address entry mode. Key depends on
                           # whether the OS is old or new version.

                           if MK14_OS==1:
                               Press_MK14_Key('m')  # Old OS: Press Mem
                           else:
                               Press_MK14_Key('z')  # New OS: Press Abort

                           # Allow settle time after mode change
                           time.sleep(ModeChangeSettleTime)

                           Press_MK14_Key(AddressDigitsHiString[0])
                           Press_MK14_Key(AddressDigitsHiString[1])
                           Press_MK14_Key(AddressDigitsLoString[0])
                           Press_MK14_Key(AddressDigitsLoString[1])

                           #Change to data entry mode
                           Press_MK14_Key('t')  # Press Term

                           # Allow settle time after mode change
                           time.sleep(ModeChangeSettleTime)

                   # Scan and output the data bytes as MSD and LSD and
                   # add the byte value of the databyte to the checksum

                   for x in range (9,(9+DataBytesCount*2),2):
                        DataByteString=line[x:x+2]
                        DataByteRaw=int(DataByteString,16)
                        ChkSum=ChkSum+DataByteRaw

                        if (OutputMode==1):
                            #Output the high and low digits of the databyte
                            Press_MK14_Key(DataByteString[0])
                            Press_MK14_Key(DataByteString[1])

                            # On New OS only a single 'MEM' press is required
                            # to enter data / advance address. On Old OS,
                            # Term-Mem-Term sequence is required for each
                            # data byte entry / address advance.

                            if MK14_OS ==1:
                                Press_MK14_Key('t')  # Press Term

                            Press_MK14_Key('m')  # Press Mem

                            if MK14_OS ==1:
                                Press_MK14_Key('t') # Press Term

                            # Advance the raw address keeper
                            AddressDigitsRaw=AddressDigitsRaw+1
                            # AddressAsHex=ASCII HEX version of current address
                            AddressAsHex="%0.4X" % AddressDigitsRaw

                   # Get the line's checksum from the end of the line
                   LineChecksumString=line[(9+(DataBytesCount*2)):(9+(DataBytesCount*2)+2)]

                   # Calculated checksum has to be converted to its 8-bit twos-
                   # complement before comparison with the file checksum

                   # Invert it
                   ChkSum=~ChkSum

                   # Add one to it
                   ChkSum=ChkSum+1

                   # Strip it back down to one byte
                   ChkSum=ChkSum & 255

                   # Convert it to hex string
                   ChkSumHexString = "%0.2X" % ChkSum

                   # Check whether it is valid, if not, abort
                   if LineChecksumString!=ChkSumHexString:
                       print ("Invalid Checksum in file line ", LineNumber, ",  found ", LineChecksumString, " expected " , ChkSumHexString )
                       ErrorFlag=1
                       break

                   # AddressAsHex = ASCII hex version of current address
                   AddressAsHex="%0.4X" % AddressDigitsRaw

        # When all lines have been processed, exit data entry mode.
        # press abort
        Press_MK14_Key('z')
        time.sleep(ModeChangeSettleTime)
	# If an execution address was found in the file at special address FFFE and
        # there were no errors during the load process, execute from that address.
        # Key sequence depends on OS version.

        if (ExecuteFlag==1) & (ErrorFlag==0):
            print("Executing from address", ExecutionAddressHiString + ExecutionAddressLoString)
            #print("OS ",MK14_OS)
            if MK14_OS ==1:
                #print ("OS 1")
                Press_MK14_Key('g')
            Press_MK14_Key (ExecutionAddressHiString[0])
            Press_MK14_Key (ExecutionAddressHiString[1])
            Press_MK14_Key (ExecutionAddressLoString[0])
            Press_MK14_Key (ExecutionAddressLoString[1])
            if MK14_OS ==1:
                #print ("OS 1")
                Press_MK14_Key ('t')
            else:
                #print ("OS 2")
                Press_MK14_Key ('g')


        if ErrorFlag==0:
            print ("Done.")
        else:
            print ("Aborted.")

#---------------------------------------------------------------------------------------------------
# Main Body Of Program
#---------------------------------------------------------------------------------------------------


# Pull the filename from the argument supplied.
# Filename of hex file must include extension and
# is case-sensitive.


# If the user has supplied a filename, the second element
# of sys.argv contains the filename. If there is no second
# element, the user did not supply a filename.

if len ((sys.argv))>1:
    FileName=(sys.argv[1])

    # If the file exists, send the contents to the MK14
    if os.path.exists(FileName):
        # check if OS provided and set it
        if len ((sys.argv))>2:
            if sys.argv[2] == "1":
                MK14_OS=1
            elif sys.argv[2] == "2":
                MK14_OS=2
            else:
                print("parameter 2 should be 1 or 2")

        print ("Sending File ", FileName )
        # Initialise GPIOs
        SetupGPIOs()
        print ("OS version ", MK14_OS)
        SendFileToMK14(FileName)
        # Tidy up the GPIO ports
        CloseGPIOs()
    # If not, abort.
    else:
        print ("File not found...")

# The user did not supply a filename.
else:
    print("Please supply a .hex file name...")

# end of file

