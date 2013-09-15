<?php

/**
 * english language file
 */

$this->langVars = array(
// GENERAL
'me'					=> 'Ich',

'time.dateformat'		=> 'j. M Y',
'time.now'				=> 'gerade eben',
'time.oneminuteago'		=> 'vor einer Minute',
'time.xminutesago'		=> 'vor %x Minuten',
'time.onehourago'		=> 'vor einer Stunde',
'time.xhoursago'		=> 'vor %x Stunden',
'time.yesterday'		=> 'gestern',
'time.yesterdayat'		=> 'gestern um ',

'form.submit' 			=> 'Abschicken',
'form.reset'			=> 'Zurücksetzen',
'form.invalid'			=> 'Bitte korriegieren Sie die markierten Felder!',
'form.invalid.missing'			=> 'Diese Feld muss ausgefüllt werden.',
'form.invalid.userName'			=> 'Ihr Benutzername muss mindestens '
								 . USERNAME_MIN_LENGTH
								 . ' Zeichen lang sein.',
'form.invalid.userName.used'	=> 'Dieser Benutzername ist bereits vergeben.',
'form.invalid.userName.nonexistant'	=> 'Der Benutzername existiert nicht.',
'form.invalid.email'			=> 'Keine E-Mail Adresse',
'form.invalid.email.used'		=> 'Diese E-Mail Adresse ist bereits in Benutzung.',
'form.invalid.emailConfirm'		=> 'Ihre E-Mail Adresse stimmt nicht überein.',
'form.invalid.password'			=> 'Falsches Passwort',
'form.invalid.password.insecure'=> 'Ihr Passwort muss mindestens '
								 . PASSWORD_MIN_LENGTH
								 . ' Zeichen lang sein.',
'form.invalid.passwordConfirm'	=> 'Ihr Passwort stimmt nicht überein.',

// GLOBAL
'site.index' 			=> 'Startseite',
'site.legalnotice'		=> 'Impressum',
'site.contact'			=> 'Kontaktinformationen',
'site.menu.settings'	=> 'Einstellungen',

'site.loggedinas' 		=> 'Angemeldet als',

// SITES
'legal.name'			=> 'Name',
'legal.address'			=> 'Addresse',
'legal.nameAndAddress' 	=> 'Name und Anschrift',
'legal.email' 			=> 'E-Mail Addresse',
'legal.phone'			=> 'Telefon',
'legal.fax'				=> 'Fax',
'legal.representatives'	=> 'Vertretungsberechtigte',
//'legal.taxID'			=> 'Steuernummer',

'user.name' 			=> 'Benutzername',
'user.gameCount'		=> 'Spiele',
'user.password' 		=> 'Passwort',
'user.password.confirm'	=> 'Passwort wiederholen',
'user.email'			=> 'E-Mail Adresse',
'user.email.confirm'	=> 'E-Mail Adresse wiederholen',
'user.login' 			=> 'Anmelden',
'user.logout' 			=> 'Abmelden',
'user.register' 		=> 'Registrieren',
'user.profile'			=> 'Profil',
'user.settings'			=> 'Einstellungen',
'user.settings.language'=> 'Sprache',
'user.list' 			=> 'Benutzerliste',

'game.opponent'			=> 'Gegner',
'game.whiteplayer' 		=> 'Weißer Spieler',
'game.blackplayer' 		=> 'Schwarzer Spieler',
'game.status' 			=> 'Status',
'game.list' 			=> 'Spiele',
'game.list.running' 	=> 'Laufende Spiele',
'game.list.finished'	=> 'Beendete Spiele',
'game.list.gotogame' 	=> 'ansehen',
'game.new'				=> 'Neues Spiel',
'game.new.against'		=> 'Gegen %opponent spielen',
'game.settings'			=> 'Spieleinstellungen',
'game.lastupdate' 		=> 'Letzte Aktivität',
'game.status.nextturn' 	=> '%u ist dran',
'game.status.won' 		=> '%u hat Gewonnen',
'game.status.draw' 		=> 'Unentschieden',
'game.menu.resign'		=> 'Aufgeben',
'game.menu.offerdraw'	=> 'Patt anbieten',

'chat.send'				=> 'Abschicken',

'chess.term.king'		=> 'König',
'chess.term.queen'		=> 'Dame',
'chess.term.rook'		=> 'Turm',
'chess.term.bishop'		=> 'Läufer',
'chess.term.knight'		=> 'Springer',
'chess.term.pawn'		=> 'Bauer',
'chess.term.castling'	=> 'Rouchade',
'chess.term.enpassant'	=> 'en passant',

'chess.rule.nocastling'	=> 'Die Rouchade ist nicht mehr möglich nachdem der König bewegt wurde',

'chess.moved'			=> '<strong>%user</strong> zieht %piece von <strong>%from</strong> nach <strong>%to</strong>',

'exception.404.msg' 	=> 'Diese Seite ist nicht mehr verfügbar.<br /><a href="index.php">Zurück zur Startseite</a>',
'exception.403.msg' 	=> 'Sie haben keinen Zugriff auf diese Seite.<br /><a href="index.php">Zurück zur Startseite</a>',

);
