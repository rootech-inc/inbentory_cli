docker run -d -e DB_HOST=172.21.144.1 -e DB_USER=anton -e DB_PASSWORD=258963 -e DB_NAME=posdb -e MECH_NO=3 -e MAC_ADDRESS=08:76:5D:43:21:BA --name=pos3  -p 3000:80 uyin/shopflow
