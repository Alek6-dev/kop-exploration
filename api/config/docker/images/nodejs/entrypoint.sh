#!/bin/sh
set -e

mkdir -p $HOME/.ssh

if [ -f /var/tmp/id ]; then
    echo " >> Copying host ssh key from /var/tmp/id to $HOME/.ssh/id_rsa"
    cp /var/tmp/id $HOME/.ssh/id_rsa
    chmod 0600 $HOME/.ssh/id_rsa
fi

if [ -f /var/tmp/sshconf ]; then
    echo " >> Copying host ssh config from /var/tmp/sshconf to $HOME/.ssh/config"
    cp /var/tmp/sshconf $HOME/.ssh/config
    chmod 0600 $HOME/.ssh/config
fi

if [ -f /var/tmp/ssh_hosts ]; then
    echo " >> Copying host ssh known_hosts from /var/tmp/ssh_hosts to $HOME/.ssh/known_hosts"
    cp /var/tmp/ssh_hosts $HOME/.ssh/known_hosts
    chmod 0600 $HOME/.ssh/known_hosts
fi

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
    set -- php "$@"
fi

FORCED_USER_ID=${LOCAL_USER_ID:-9001}
FORCED_GROUP_ID=${LOCAL_GROUP_ID:-9001}

echo "Starting with UID: $FORCED_USER_ID"
echo "Starting with GID: $FORCED_GROUP_ID"

if [ $(getent group $FORCED_GROUP_ID) ]; then
  echo "group exists"
else
  echo "group does not exist"
  groupadd -g $FORCED_GROUP_ID kop
fi

useradd --shell /bin/bash --no-create-home --home $HOME -u $FORCED_USER_ID -g $FORCED_GROUP_ID -o -c "" kop

chown -R $FORCED_USER_ID:$FORCED_GROUP_ID $HOME

exec gosu kop "$@"
