INSERT INTO USERS (user_name, email, phone, password, points, address, date_joined) VALUES (
'admin','admin@gmail.com',11111111,'00000000',100,'PGPR','2016-01-01');
INSERT INTO USERS (user_name, email, phone, password, points, address, date_joined) VALUES (
'user','user@gmail.com',22222222,'00000000',100,'PGPR','2016-02-01');

INSERT INTO PRODUCTS (title, description, owner_id, is_available,pic) VALUES (
'database management book','textbook for CS2102', 2, true, 'images/h6.jpg');
INSERT INTO PRODUCTS (title, description, owner_id, is_available) VALUES (
'chair','spare chair', 2, true,'images/h16.jpg');

INSERT INTO AUCTIONS (START_TIME_AVAIL,END_TIME_AVAIL,PICK_UP,MIN_PRICE,PRODUCT_ID,STATUS)
VALUES ('2016-09-24','2016-10-24','PGPR',10,1,TRUE);

INSERT INTO AUCTIONS (START_TIME_AVAIL,END_TIME_AVAIL,PICK_UP,MIN_PRICE,PRODUCT_ID,STATUS)
VALUES ('2016-09-24','2016-10-24','PGPR',10,2,TRUE);
