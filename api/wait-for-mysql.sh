. ./.env

echo "Waiting for mysql"

until mysqladmin ping -h $MYSQL_SERVICE -uroot -proot --silent
do
  printf "."
  sleep 1
done

echo -e "\nmysql ready"
