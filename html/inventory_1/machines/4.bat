docker run -d -e DB_HOST=172.21.144.1 -e DB_USER=anton -e DB_PASSWORD=258963 -e DB_NAME=posdb -e MECH_NO=4 -e MAC_ADDRESS=2F:9C:E8:7B:A1:4D --name=pos4  -p 4000:80 uyin/shopflow
