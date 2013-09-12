<?php

/**
 * english language file
 */

$this->langVars = array(
// GENERAL
'dateformat'			=> 'M jS Y',
'yesterday'				=> 'Yesterday',
'me'					=> 'Me',

'form.submit' 			=> 'Submit',
'form.reset'			=> 'Reset',
'form.invalid'			=> 'Please correct the errors noted below!',
'form.invalid.missing'			=> 'This field is obligatory.',
'form.invalid.userName'			=> 'Your username must be at least '
								 . USERNAME_MIN_LENGTH
								 . ' characters long.',
'form.invalid.userName.used'	=> 'This username is already taken.',
'form.invalid.userName.nonexistant'	=> 'Username doesn\'t exist.',
'form.invalid.email'			=> 'This is not a valid e-mail address.',
'form.invalid.email.used'		=> 'This e-mail address is already in use',
'form.invalid.emailConfirm'		=> 'Please reconfirm your e-mail address!',
'form.invalid.password'			=> 'Wrong password!',
'form.invalid.password.insecure'=> 'Your password must be at least '
								 . PASSWORD_MIN_LENGTH
								 . ' characters long.',
'form.invalid.passwordConfirm'	=> 'Please reconfirm your password!',

// GLOBAL
'site.index' 			=> 'Home',
'site.legalnotice'		=> 'Legal notice',
'site.contact'			=> 'Contact information',
'site.menu.settings'	=> 'Settings',

'site.loggedinas' 		=> 'Logged in as',

// SITES
'legal.name'			=> 'Name',
'legal.address'			=> 'Address',
'legal.nameAndAddress' 	=> 'Name and address',
'legal.email' 			=> 'E-mail address',
'legal.phone'			=> 'Phone',
'legal.fax'				=> 'Fax',
'legal.representatives'	=> 'Representative',
//'legal.taxID'			=> 'Tax ID',

'user.name' 			=> 'Username',
'user.gameCount'		=> 'Games',
'user.password' 		=> 'Password',
'user.password.confirm'	=> 'Repeat password',
'user.email'			=> 'E-mail address',
'user.email.confirm'	=> 'Repeat E-mail address',
'user.login' 			=> 'Login',
'user.logout' 			=> 'Logout',
'user.register' 		=> 'Register',
'user.profile'			=> 'Profile',
'user.settings'			=> 'Settings',
'user.settings.language'=> 'Language',
'user.list' 			=> 'User list',

'game.opponent'			=> 'Opponent',
'game.whiteplayer' 		=> 'White player',
'game.blackplayer' 		=> 'Black player',
'game.status' 			=> 'Status',
'game.list' 			=> 'Games',
'game.list.running' 	=> 'Running games',
'game.list.finished'	=> 'Finished games',
'game.list.gotogame' 	=> 'view',
'game.new'				=> 'New Game',
'game.new.against'		=> 'Play against %opponent',
'game.settings'			=> 'Game settings',
'game.lastupdate' 		=> 'last update',
'game.status.nextturn' 	=> "It's %u's turn",
'game.status.won' 		=> '%u won',
'game.status.draw' 		=> 'draw',
'game.menu.resign'		=> 'Resign',
'game.menu.offerdraw'	=> 'Offer Draw',

'chat.send'				=> 'Send',

'chess.term.king'		=> 'king',
'chess.term.queen'		=> 'queen',
'chess.term.rook'		=> 'rook',
'chess.term.bishop'		=> 'bishop',
'chess.term.knight'		=> 'knight',
'chess.term.pawn'		=> 'pawn',
'chess.term.castling'	=> 'castling',
'chess.term.enpassant'	=> 'en passant',

'chess.rule.nocastling'	=> 'Castling is no longer allowed after the king has been moved at least once.',

'chess.moved'			=> '<strong>%user</strong> moved %piece from <strong>%from</strong> to <strong>%to</strong>',

'exception.404.msg' 	=> 'This page is no longer available.<br /><a href="index.php">Return to home</a>',
'exception.403.msg' 	=> 'You don\'t have access to this page.<br /><a href="index.php">Return to home</a>',

);
