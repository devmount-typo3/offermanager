-- add own pid to cal category
CREATE TABLE tx_cal_category (
    own_pid int(11) NOT NULL default '0'
);

CREATE TABLE tx_cal_organizer (
    facebook varchar(250) NOT NULL default '',
    twitter varchar(250) NOT NULL default ''
);

CREATE TABLE tx_cal_event (
    dates_description text
);