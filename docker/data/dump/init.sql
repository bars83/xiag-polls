# noinspection SqlNoDataSourceInspectionForFile

create table poll
(
    uid      varchar(255) not null primary key,
    question varchar(255) not null
) collate = utf8_unicode_ci;

create table answer
(
    id       int primary key auto_increment,
    poll_uid varchar(255) not null,
    value    varchar(255) not null
) collate = utf8_unicode_ci;

create table result
(
    answer_id int          not null,
    user_name varchar(255) not null
) collate = utf8_unicode_ci;