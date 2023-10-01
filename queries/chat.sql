-- creat user ifo table

CREATE TABLE `user_info` (
    `id` int(11) NOT null,
    `username` varchar(22) DEFAULT null,
    `email` varchar(225) DEFAULT null,
    `password` varchar(64) DEFAULT null,
    `bio` varchar(800) DEFAULT NULL,
    `picture` text DEFAULT NULL,
    `cover` text DEFAULT NULL,
    `last_active_update` timestamp NULL DEFAULT NULL,
    `joined` timestamp NOT NULL DEFAULT current_timestamp()
);

-- index for table
ALTER TABLE `user_info` 
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `username` (`username`);

-- Modify index auto increaase
ALTER TABLE `user_info`
    MODIFY `id` int(11) NOT null AUTO_INCREMENT, AUTO_INCREMENT=1;


-- create a user_session for manage remember me password

CREATE TABLE `user_session` (
    `id` int(11) NOT NULL,
    `user_name` varchar(11) DEFAULT NULL,
    `hash` varchar(64) DEFAULT NULL
);

ALTER TABLE `user_session`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `user_session`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;