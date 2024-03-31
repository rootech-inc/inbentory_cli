docker run -d -e DB_HOST=172.21.144.1 -e DB_USER=anton -e DB_PASSWORD=258963 -e DB_NAME=posdb -e MECH_NO=2 -e MAC_ADDRESS=00:1A:2B:3C:4D:5E -e DEBUG=false --name=pos2  -p 2000:80 uyin/shopflow
