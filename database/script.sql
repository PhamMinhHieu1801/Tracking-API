-- Active: 1690980795336@@127.0.0.1@3306@bookstore
-- tạo database
create database if not exists BookStore;
use BookStore;
-- tạo bảng

create table if not exists users (
	id INT PRIMARY KEY AUTO_INCREMENT,
	password VARCHAR(50) NOT NULL,
	name VARCHAR(50) NOT NULL,
	email VARCHAR(50) NOT NULL UNIQUE
);
insert into users (id, password, name, email) values (1, 'Andra', 'Brim', 'abrim0@opera.com');
insert into users (id, password, name, email) values (2, 'Blondie', 'Iannuzzi', 'biannuzzi1@foxnews.com');
insert into users (id, password, name, email) values (3, 'Hewie', 'Mellers', 'hmellers2@com.com');
insert into users (id, password, name, email) values (4, 'Shadow', 'Dubery', 'sdubery3@weather.com');
insert into users (id, password, name, email) values (5, 'Rodrick', 'Thomason', 'rthomason4@ucsd.edu');


create table reset_password (
	id INT PRIMARY KEY AUTO_INCREMENT,
	token VARCHAR(50) NOT NULL,
	createdAt DATETIME NOT NULL DEFAULT NOW(),
	email VARCHAR(50) NOT NULL,
	avaiable BIT DEFAULT 1
);

create table if not exists categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    image VARCHAR(50) NOT NULL
);

insert into categories (id, name, image) values (1, 'Điện thoại', 'https://asianwiki.com/images/d/de/Chi_Pu-p001.jpg');
insert into categories (id, name, image) values (2, 'Laptop', 'https://asianwiki.com/images/d/de/Chi_Pu-p001.jpg');
insert into categories (id, name, image) values (3, 'Phụ kiện', 'https://asianwiki.com/images/d/de/Chi_Pu-p001.jpg');

create table if not exists products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    price INT NOT NULL,
    image VARCHAR(5000) NOT NULL,
    description VARCHAR(50) NOT NULL,
    quantity INT NOT NULL,
    categoryId INT NOT NULL,
    FOREIGN KEY (categoryId) REFERENCES categories(id)
);

create table if not exists notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    price1 INT NOT NULL,
    price2 INT NOT NULL,
    day DATE NOT NULL
);

insert into notifications (id, name, price1, price2, day) 
values(1,'Dế Mèn Phưu Lưu Kí' , 1000 , 3000 , '24-11-20003');

insert into notifications (id, name, price1, price2, day) 
values(2,'Nhà Giả Kim' , 1000 , 3000 , '24-11-20003');

insert into notifications (id, name, price1, price2, day) 
values(3,'Doraemon' , 1000 , 3000 , '24-11-20003');
insert into notifications (id, name, price1, price2, day) 
values(4,'Conan' , 2000 , 6500 , '24-11-20003');
insert into notifications (id, name, price1, price2, day) 
values(5,'Đọc Vị Tâm Lý' , 1000 , 3600 , '24-11-20003');

insert into products (id, name, price, image, description, quantity, categoryId) 
values (1, 'Điện thoại 1', 1000, 'https://asianwiki.com/images/d/de/Chi_Pu-p001.jpg', 'Điện thoại 1', 10, 1);
insert into products (id, name, price, image, description, quantity, categoryId)
values (2, 'Điện thoại 2', 2000, 'https://asianwiki.com/images/d/de/Chi_Pu-p001.jpg', 'Điện thoại 2', 20, 2);
insert into products (id, name, price, image, description, quantity, categoryId)
values (3, 'Điện thoại 3', 3000, 'https://asianwiki.com/images/d/de/Chi_Pu-p001.jpg', 'Điện thoại 3', 30, 3);



create table if not exists sanpham (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    content VARCHAR(500) NOT NULL,
    author VARCHAR(50) NOT NULL  
);

insert into sanpham (id, name, content, author) 
values(1,'Tôi Thấy Hoa Vàng Trên Cỏ Xanh' ,'khi tôi','Nguyễn Nhật Anh');