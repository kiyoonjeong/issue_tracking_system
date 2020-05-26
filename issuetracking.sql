

CREATE DATABASE issuetrackingsystem;

USE issuetrackingsystem;


DROP TABLE IF EXISTS user;

CREATE TABLE user (
  userid int NOT NULL AUTO_INCREMENT,
  email char(30) NOT NULL,
  username char(30) NOT NULL,
  dpname char(30) NOT NULL,
  upassword char(30) NOT NULL,
  PRIMARY KEY (userid)
);

insert  into user(email,username,dpname,upassword) values 
("kj629@nyu.edu", "kiyoon", "kiyoon", "a1"),
("hy12@nyu.edu", "hyun", "hy", "a2"),
("dt2@nyu.edu", "downtown", "dt", "a3"),
("jy4@nyu.edu", "jersey", "jy", "a4"),
("gb5@nyu.edu", "gab", "gb", "a5"),
("ab3@nyu.edu", "abs", "ab","a6"),
("xf7@nyu.edu", "xf", "xf", "a7"),
("ky4@nyu.edu", "ky", "kiwi", "a8");

/*Table structure for table 'customer' */

DROP TABLE IF EXISTS project;

CREATE TABLE project (
  pid int NOT NULL AUTO_INCREMENT,
  pname char(30) NOT NULL,
  pdescription char(30) NOT NULL,
  PRIMARY KEY (pid)
);

/*Data for the table 'customer' */

insert  into project(pname,pdescription) values 
('A','test1'),
('B','test2');

/*Table structure for table 'ingredient' */

DROP TABLE IF EXISTS issue;

CREATE TABLE issue (
  iid int NOT NULL AUTO_INCREMENT,
  pid int NOT NULL,
  userid int NOT NULL,
  ititle char(30) NOT NULL,
  idescription char(30) NOT NULL,
  PRIMARY KEY (iid),
  CONSTRAINT issue_ibfk_1 FOREIGN KEY (pid) REFERENCES project (pid),
  CONSTRAINT issue_ibfk_2 FOREIGN KEY (userid) REFERENCES user (userid)
);

/*Data for the table 'ingredient' */

insert  into issue(pid,userid,ititle,idescription) values 
(1,1,'i1',"d"),
(1,2,'i2',"d"),
(1,3,'i3',"d"),
(2,4,'i4',"d"),
(2,5,'i5',"d");

/*Table structure for table 'orders' */

DROP TABLE IF EXISTS track;

CREATE TABLE track (
  iid int NOT NULL,
  timedate datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  stat char(30) NOT NULL,
  PRIMARY KEY (iid, timedate, stat),
  CONSTRAINT track_ibfk_1 FOREIGN KEY (iid) REFERENCES issue (iid) ON DELETE CASCADE ON UPDATE CASCADE
);

/*Data for the table orders' */

insert  into track(iid, timedate, stat) values 
(1, '2020-04-23 15:55:17', "OPEN"),
(2,'2020-04-23 15:55:18',"OPEN"),
(3,'2020-04-23 15:55:19',"OPEN"),
(4,'2020-04-23 15:55:20',"OPEN"),
(5,'2020-04-23 15:55:21',"OPEN"),
(1,'2020-04-23 15:55:22',"IN PROGRESS"),
(1,'2020-04-23 15:55:23',"UNDER REVIEW"),
(1,'2020-04-23 15:55:24',"FINAL APPROVAL"),
(2,'2020-04-23 15:55:25',"IN PROGRESS"),
(2,'2020-04-23 15:55:26',"UNDER REVIEW"),
(3,'2020-04-23 15:55:27',"IN PROGRESS"),
(4,'2020-04-23 15:55:28',"WAITING FOR SUPPORT"),
(5,'2020-04-23 15:55:29',"WAITING FOR CUSTOMER"),
(5,'2020-04-23 15:55:30',"ESCALATED")
;

/*Table structure for table 'contain' */

DROP TABLE IF EXISTS workflow;

CREATE TABLE workflow
 (
  pid int NOT NULL,
  currstat char(30) NOT NULL DEFAULT "OPENED",
  nextstat char(30) NOT NULL DEFAULT "CLOSED",
  PRIMARY KEY (pid,currstat, nextstat),
  CONSTRAINT workflow_ibfk_1 FOREIGN KEY (pid) REFERENCES project (pid) ON DELETE CASCADE ON UPDATE CASCADE
);

/*Data for the table contain */

insert  into workflow(pid,currstat,nextstat) values 
(1,"OPEN","IN PROGRESS"),
(1,"IN PROGRESS", "UNDER REVIEW"),
(1,"UNDER REVIEW", "FINAL APPROVAL"),
(1,"FINAL APPROVAL", "DONE"),
(2,"OPEN", "WAITING FOR SUPPORT"),
(2,"OPEN", "WAITING FOR CUSTOMER"),
(2,"OPEN", "CANCELED"),
(2,"OPEN", "RESOLVED"),
(2,"WAITING FOR SUPPORT", "WAITING FOR CUSTOMER"),
(2,"WAITING FOR SUPPORT", "ESCALATED"),
(2,"WAITING FOR SUPPORT", "IN PROGRESS"),
(2,"WAITING FOR SUPPORT", "PENDING"),
(2,"WAITING FOR SUPPORT", "CANCELED"),
(2,"WAITING FOR SUPPORT", "RESOLVED"),
(2,"WAITING FOR CUSTOMER", "WAITING FOR SUPPORT"),
(2,"WAITING FOR CUSTOMER", "ESCALATED"),
(2,"WAITING FOR CUSTOMER", "IN PROGRESS"),
(2,"WAITING FOR CUSTOMER", "PENDING"),
(2,"WAITING FOR CUSTOMER", "CANCELED"),
(2,"WAITING FOR CUSTOMER", "RESOLVED"),
(2,"ESCALATED", "IN PROGRESS"),
(2,"ESCALATED", "PENDING"),
(2,"PENDING", "CANCELLED"),
(2,"PENDING", "RESOLVED"),
(2,"IN PROGRESS", "CANCELLED"),
(2,"IN PROGRESS", "RESOLVED"),
(2,"CANCELLED","CLOSED"),
(2,"RESOLVED","CLOSED")
;

DROP TABLE IF EXISTS leads;

CREATE TABLE leads
 (
  userid int NOT NULL,
  pid int NOT NULL,
  PRIMARY KEY (userid,pid),
  CONSTRAINT leads_ibfk_1 FOREIGN KEY (pid) REFERENCES project (pid),
  CONSTRAINT leads_ibfk_2 FOREIGN KEY (userid) REFERENCES user (userid)
);

insert  into leads(userid,pid) values 
(1,1),
(2,1),
(3,2)
;

CREATE TABLE authority
 (
  userid int NOT NULL,
  iid int NOT NULL,
  PRIMARY KEY (userid,iid),
  CONSTRAINT authority_ibfk_1 FOREIGN KEY (iid) REFERENCES issue (iid),
  CONSTRAINT authority_ibfk_2 FOREIGN KEY (userid) REFERENCES user (userid)
);
insert  into authority(userid,iid) values 
(1,1),
(1,2),
(1,3),
(2,1),
(2,2),
(2,3),
(3,4),
(3,5),
(4,1),
(5,4)
;

DROP TABLE IF EXISTS activity;

CREATE TABLE activity
 (
  userid int NOT NULL,
  timedate datetime DEFAULT CURRENT_TIMESTAMP,
  actions char(30),
  PRIMARY KEY (userid,timedate, actions),
  CONSTRAINT activity_ibfk_1 FOREIGN KEY (userid) REFERENCES user (userid)
);