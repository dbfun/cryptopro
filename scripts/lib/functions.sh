function assert
{
	SUBSTR=`echo "$1" | grep -q "$2"`
	if [ $? != 0 ]; then
    error "$3; Expected: $2"
		exit 1
	fi
}

function info
{
	echo -e $Blue"$1"$Color_Off
}

function ok
{
	echo -e $Green"$1"$Color_Off
}

function warning
{
	echo -e $Yellow"$1"$Color_Off
}

function error
{
	echo -e $Red"$1"$Color_Off
}
