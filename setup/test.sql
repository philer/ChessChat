INSERT INTO `user` (`userName`, `email`, `password`, `language`) VALUES
	('phil', 'phil@mill.er', 'password', ''),
	('larissa', 'larissa@hamm.er', 'password', 'de'),
	('bare', 'bare@example.com', 'password', 'none'),
	('someguywithareallylongname', 'someguywithareallylongname@reallylongdomain.topleveldomain', 'password', 'en');

INSERT INTO `game` (`gameHash`, `whitePlayerId`, `blackPlayerId`, `board`, `status`) VALUES
	('testt1', 1, 2, 'Ra1Nb1Bc1Qd1Kd1Bc1Nb1Ra1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7ra8nb8bc8qd8kd8bc8nb8ra8', 0),
	('testt2', 2, 1, 'Ra1Nb1Bc1Qd1Kd1Bc1Nb1Ra1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7ra8nb8bc8qd8kd8bc8nb8ra8', 1),
	('testt3', 1, 3, 'Ra1Nb1Bc1Qd1Kd1Bc1Nb1Ra1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7ra8nb8bc8qd8kd8bc8nb8ra8', 4),
	('testt4', 3, 2, 'Ra1Nb1Bc1Qd1Kd1Bc1Nb1Ra1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7ra8nb8bc8qd8kd8bc8nb8ra8', 9),
	('testt5', 4, 1, 'Ra1Nb1Bc1Qd1Kd1Bc1Nb1Ra1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7ra8nb8bc8qd8kd8bc8nb8ra8', 15);

INSERT INTO `chatMessage` (`gameId`, `authorId`, `messageText`, `time`, `isBotMsg`) VALUES
	(1, 1, 'testmessage', '12345678', 0),
	(1, 2, 'testmessage', '12345678', 0),
	(1, 2, 'testmessage', '12345678', 1),
	(2, 1, 'testmessage', '12345678', 0),
	(2, 2, 'testmessage', '12345678', 0),
	(4, 3, 'testmessage', '12345678', 0),
	(4, 2, 'testmessage', '12345678', 0),
	(1, 1, 'testmessage', '12345678', 0);
