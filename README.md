**JavaScript, jQuery, and JSON**

This is folder contain course's auto-grades solution offred by Coursera
Topics:
***************************************************************************************************************
    1- Go to pdo.php inside week 1, week 3 and week 4 and change the port, dbname, username and password with your
       database infos.
***************************************************************************************************************
    2- You must go to phpmyadmin and create database, you can use this command:
        CREATE DATABASE DATABASENAME; 
***************************************************************************************************************
    3- Create a Users, Profile , Institution, Education and Postion tables    
    Users table: 
        CREATE TABLE users (
            user_id INTEGER NOT NULL AUTO_INCREMENT,
            name VARCHAR(128),
            email VARCHAR(128),
            password VARCHAR(128),
            PRIMARY KEY(user_id)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;
        ALTER TABLE users ADD INDEX(email);
        ALTER TABLE users ADD INDEX(password);
    Profile table:
        CREATE TABLE Profile (
            profile_id INTEGER NOT NULL AUTO_INCREMENT,
            user_id INTEGER NOT NULL,
            first_name TEXT,
            last_name TEXT,
            email TEXT,
            headline TEXT,
            summary TEXT,            
            PRIMARY KEY(profile_id),
            CONSTRAINT profile_ibfk_2
            FOREIGN KEY (user_id)
            REFERENCES users (user_id)
            ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    Position table:
        CREATE TABLE Position (
            position_id INTEGER NOT NULL AUTO_INCREMENT,
            profile_id INTEGER,
            rank INTEGER,
            year INTEGER,
            description TEXT,
            PRIMARY KEY(position_id),
            CONSTRAINT position_ibfk_1
            FOREIGN KEY (profile_id)
            REFERENCES Profile (profile_id)
            ON DELETE CASCADE ON UPDATE CASCADE
            )ENGINE=InnoDB DEFAULT CHARSET=utf8;
    Institution table: 
        CREATE TABLE Institution (
            institution_id INTEGER NOT NULL AUTO_INCREMENT,
            name VARCHAR(255),
            PRIMARY KEY(institution_id),
            UNIQUE(name)
            )ENGINE=InnoDB DEFAULT CHARSET=utf8;
    Education table: 
        CREATE TABLE Education (
            profile_id INTEGER,
            institution_id INTEGER,
            rank INTEGER,
            year INTEGER,
            CONSTRAINT education_ibfk_1
                  FOREIGN KEY (profile_id)
                  REFERENCES Profile (profile_id)
                  ON DELETE CASCADE ON UPDATE CASCADE,

            CONSTRAINT education_ibfk_2
                  FOREIGN KEY (institution_id)
                  REFERENCES Institution (institution_id)
                  ON DELETE CASCADE ON UPDATE CASCADE,

            PRIMARY KEY(profile_id, institution_id)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8;
***************************************************************************************************************
    4- Insert User information and Institution informations: 
        INSERT INTO users (name,email,password) VALUES ('UMSI','umsi@umich.edu','1a52e17fa899cf40fb04cfc42e6352f1');
        INSERT INTO Institution (name) VALUES ('University of Michigan');
        INSERT INTO Institution (name) VALUES ('University of Virginia');
        INSERT INTO Institution (name) VALUES ('University of Oxford');
        INSERT INTO Institution (name) VALUES ('University of Cambridge');
        INSERT INTO Institution (name) VALUES ('Stanford University');
        INSERT INTO Institution (name) VALUES ('Duke University');
        INSERT INTO Institution (name) VALUES ('Michigan State University');
        INSERT INTO Institution (name) VALUES ('Mississippi State University');
        INSERT INTO Institution (name) VALUES ('Montana State University');
***************************************************************************************************************
    5- Login email: umsi@umich.edu
            password: php123


