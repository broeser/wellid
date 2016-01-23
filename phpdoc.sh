#!/bin/sh

_script=$0
DIR="$(dirname $_script)"

phpdoc run -vvv --ansi --progressbar --directory $DIR/src,$DIR/examples --target $DIR/doc --title wellid --log $DIR/phpdoc.log
echo $DIR
