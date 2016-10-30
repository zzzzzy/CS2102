INSERT INTO USERS (user_name, email, phone, password, points, address, date_joined, admin) VALUES (
'admin','admin@gmail.com',11111111,'00000000',100,'PGPR','2016-01-01',TRUE);
INSERT INTO USERS (user_name, email, phone, password, points, address, date_joined, admin) VALUES (
'user','user@gmail.com',22222222,'00000000',100,'PGPR','2016-02-01',FALSE);

INSERT INTO PRODUCTS (title, description, cate, owner_id, is_available,pic) VALUES (
'CS2102 book','textbook for CS2102', 'Stationery', 2, true, 'images/h6.jpg');
INSERT INTO PRODUCTS (title, description, cate, owner_id, is_available,pic) VALUES (
'chair','spare chair','Furniture',2, true,'images/h16.jpg');

INSERT INTO AUCTIONS (START_TIME_AVAIL,END_TIME_AVAIL,PICK_UP,MIN_PRICE,PRODUCT_ID,STATUS)
VALUES ('2016-09-24','2016-10-24','PGPR',10,1,TRUE);

INSERT INTO AUCTIONS (START_TIME_AVAIL,END_TIME_AVAIL,PICK_UP,MIN_PRICE,PRODUCT_ID,STATUS)
VALUES ('2016-09-24','2016-10-24','PGPR',10,2,TRUE);
