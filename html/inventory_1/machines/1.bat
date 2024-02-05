docker run -d -e DB_HOST=172.21.144.1 -e DB_USER=anton -e DB_PASSWORD=258963 -e DB_NAME=posdb -e MECH_NO=1 -e MAC_ADDRESS=C4-9D-ED-93-4B-BC --name=pos1  --network=host uyin/shopflow
