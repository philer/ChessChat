-- test passwords are all 'password'
INSERT INTO cc_user (userName, email, password, cookieHash, language) VALUES
	('phil', 'phil@mill.er', '$2y$08$100010001000100010001.LNsjk4vI3U65IdmjIEugZ2MnFxHt1De', 'wellthatsnotareallhash', ''),
	('larissa', 'larissa@hamm.er', '$2y$08$100010001000100010001.LNsjk4vI3U65IdmjIEugZ2MnFxHt1De', 'wellthatsnotareallhash', 'de'),
	('bare', 'bare@example.com', '$2y$08$100010001000100010001.LNsjk4vI3U65IdmjIEugZ2MnFxHt1De', 'wellthatsnotareallhash', '--'),
	('someguywithareallylongname', 'someguywithareallylongname@reallylongdomain.topleveldomain', '$2y$08$100010001000100010001.LNsjk4vI3U65IdmjIEugZ2MnFxHt1De', 'wellthatsnotareallhash', 'en'),
	('Full Name Guy', 'bare@example.com', '$2y$08$100010001000100010001.LNsjk4vI3U65IdmjIEugZ2MnFxHt1De', 'wellthatsnotareallhash', '--'),
	('stUp!d.n@m3$', 'bare@example.com', '$2y$08$100010001000100010001.LNsjk4vI3U65IdmjIEugZ2MnFxHt1De', 'wellthatsnotareallhash', '--'),
	('--.-.-.-__––…·–…,.…', 'bare@example.com', '$2y$08$100010001000100010001.LNsjk4vI3U65IdmjIEugZ2MnFxHt1De', 'wellthatsnotareallhash', '--'),
	('MoreInvalidCharacters?+*,.', 'bare@example.com', '$2y$08$100010001000100010001.LNsjk4vI3U65IdmjIEugZ2MnFxHt1De', 'wellthatsnotareallhash', '--');

INSERT INTO cc_game (gameHash, whitePlayerId, blackPlayerId, board, status) VALUES
	('testt1', 1, 2, 'RA1Nb1Bc1Qd1KE1Bf1Ng1RH1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7rA8nb8bc8qd8kE8bf8ng8rH8', 0),
	('testt2', 2, 1, 'RA1Nb1Bc1Qd1KE1Bf1Ng1RH1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7rA8nb8bc8qd8kE8bf8ng8rH8', 1),
	('testt3', 1, 3, 'RA1Nb1Bc1Qd1KE1Bf1Ng1RH1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7rA8nb8bc8qd8kE8bf8ng8rH8', 3),
	('test30', 6, 8, 'RA1Nb1Bc1Qd1KE1Bf1Ng1RH1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7rA8nb8bc8qd8kE8bf8ng8rH8', 4),
	('testt4', 3, 2, 'RA1Nb1Bc1Qd1KE1Bf1Ng1RH1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7rA8nb8bc8qd8kE8bf8ng8rH8', 9),
	('testt5', 2, 1, 'RA1Nb1Bc1Qd1KE1Bf1Ng1RH1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7rA8nb8bc8qd8kE8bf8ng8rH8', 3),
	('testt6', 2, 3, 'RA1Nb1Bc1Qd1KE1Bf1Ng1RH1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7rA8nb8bc8qd8kE8bf8ng8rH8', 4),
	('testt7', 3, 2, 'RA1Nb1Bc1Qd1KE1Bf1Ng1RH1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7rA8nb8bc8qd8kE8bf8ng8rH8', 2),
	('testt8', 4, 3, 'RA1Nb1Bc1Qd1KE1Bf1Ng1RH1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7rA8nb8bc8qd8kE8bf8ng8rH8', 7),
	('testt9', 1, 3, 'RA1Nb1Bc1Qd1KE1Bf1Ng1RH1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7rA8nb8bc8qd8kE8bf8ng8rH8', 8),
	('test10', 3, 1, 'RA1Nb1Bc1Qd1KE1Bf1Ng1RH1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7rA8nb8bc8qd8kE8bf8ng8rH8', 9),
	('test11', 4, 2, 'RA1Nb1Bc1Qd1KE1Bf1Ng1RH1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7rA8nb8bc8qd8kE8bf8ng8rH8', 15);

INSERT INTO cc_chatMessage (gameId, authorId, messageText, time, isBotMsg) VALUES
	(1, 1, 'testmessage', '12345678', 0),
	(1, 2, 'testmessage', '12345678', 0),
	(1, 2, 'testmessage', '12345678', 1),
	(2, 1, 'testmessage', '12345678', 0),
	(2, 2, 'testmessage', '12345678', 0),
	(4, 3, 'testmessage', '12345678', 0),
	(4, 2, 'testmessage', '12345678', 0),
	(1, 1, 'testmessage', '12345678', 0);
