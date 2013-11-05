-- clear leftover tables
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS cc_user CASCADE;
DROP TABLE IF EXISTS cc_game;
DROP TABLE IF EXISTS cc_move;
DROP TABLE IF EXISTS cc_chatMessage;
SET FOREIGN_KEY_CHECKS = 1;

-- create all new tables
CREATE TABLE cc_user (
	userId     INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	userName   VARCHAR(100) NOT NULL,
	email      VARCHAR(255) NOT NULL,
	password   VARCHAR(100) NOT NULL,
	cookieHash VARCHAR(100) NOT NULL DEFAULT '',
	language   VARCHAR(2) NOT NULL DEFAULT '',
	PRIMARY KEY (userId),
	UNIQUE KEY (userName)
);

CREATE TABLE cc_game (
	gameId        INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	gameHash      VARCHAR(22) NOT NULL,
	whitePlayerId INT(10) UNSIGNED NOT NULL,
	blackPlayerId INT(10) UNSIGNED NOT NULL,
	board         CHAR(96) NOT NULL DEFAULT '', -- fixed length
	-- status BIT(4) NOT NULL DEFAULT 0,   -- status is 0 trough 15
	status        TINYINT(2) NOT NULL DEFAULT 0,
	lastUpdate    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (gameId),
	UNIQUE KEY (gameHash),
	KEY (whitePlayerId),
	KEY (blackPlayerId),
	KEY (whitePlayerId, blackPlayerId)
);

CREATE TABLE cc_move (
	moveId     INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	gameId     INT(10) UNSIGNED NOT NULL,
	playerId   INT(10) UNSIGNED NOT NULL,
	chessPiece VARCHAR NOT NULL,
	fromSquare CHAR(2) NOT NULL,
	toSquare   CHAR(2) NOT NULL,
	time       INT(10) NOT NULL DEFAULT 0,
	PRIMARY KEY (moveId),
	KEY (gameId)
);

CREATE TABLE cc_chatMessage (
	messageId   INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	gameId 	    INT(10)    UNSIGNED NOT NULL,
	authorId    INT(10)  UNSIGNED NOT NULL,
	messageText TEXT NOT NULL,
	time        INT(10) NOT NULL DEFAULT 0,
	isBotMsg    BIT(1) NOT NULL DEFAULT b'0',
	PRIMARY KEY (messageId),
	KEY (gameId),
	KEY (gameId, time)
);

-- add foreign keys
ALTER TABLE cc_game
	ADD FOREIGN KEY (whitePlayerId) REFERENCES cc_user (userId),
	ADD FOREIGN KEY (blackPlayerId) REFERENCES cc_user (userId);
ALTER TABLE cc_move
	ADD FOREIGN KEY (gameId)   REFERENCES cc_game (gameId) ON DELETE CASCADE,
	ADD	FOREIGN KEY (playerId) REFERENCES cc_user (userId);
ALTER TABLE cc_chatMessage
	ADD FOREIGN KEY (gameId)   REFERENCES cc_game (gameId) ON DELETE CASCADE,
	ADD	FOREIGN KEY (authorId) REFERENCES cc_user (userId);
