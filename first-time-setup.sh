#!/bin/sh

SCRIPT_ROOT=PWD
COMPOSER_ROOT=./www
FONT="\033[1;4;36m"
TITLEFONT="\033[1;32m"
RESET="\033[0m"

my_msg () {
    MSG="$1"
    echo "[${TITLEFONT}SmartHome Setup${RESET}]:" \
    "${FONT}${MSG}${RESET}\n\n"
}

my_msg "Installing Composer in ${COMPOSER_ROOT}"

# Get Composer
cd $COMPOSER_ROOT
curl -sS https://getcomposer.org/installer | php

# Use composer to install EVERYTHING
php composer.phar install --dev

# Go back to root directory
cd -

my_msg 'Installing git submodules'

git submodule init
git submodule update

my_msg 'Done'

