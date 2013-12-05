<?php

/**
 * english language file
 */

$this->langVars = array(
// GENERAL
'me'    => 'Me',
'close' => 'Close',

'time.dateformat'   => 'M jS Y',
'time.now'          => 'now',
'time.oneminuteago' => 'one minute ago',
'time.xminutesago'  => '%x minutes ago',
'time.onehourago'   => 'one hour ago',
'time.xhoursago'    => '%x hours ago',
'time.yesterday'    => 'yesterday',
'time.yesterdayat'  => 'yesterday at ',

'form.submit'                       => 'Submit',
'form.reset'                        => 'Reset',
'form.invalid'                      => 'Please correct the errors noted below!',
'form.invalid.missing'              => 'This field is obligatory.',
'form.invalid.username'             => 'Your username must be at least '
                                     . USERNAME_MIN_LENGTH
                                     . ' characters long.',
'form.invalid.username.used'        => 'This username is already taken.',
'form.invalid.username.nonexistant' => 'Username doesn\'t exist.',
'form.invalid.email'                => 'This is not a valid e-mail address.',
'form.invalid.email.used'           => 'This e-mail address is already in use',
'form.invalid.email.confirm'        => 'Please reconfirm your e-mail address!',
'form.invalid.password'             => 'Wrong password!',
'form.invalid.password.confirm'     => 'Please reconfirm your password!',
'form.invalid.password.insecure'    => 'Your password must be at least '
                                     . PASSWORD_MIN_LENGTH
                                     . ' characters long.',

// GLOBAL
'site.index'         => 'Home',
'site.legalnotice'   => 'Legal notice',
'site.contact'       => 'Contact information',
'site.menu.settings' => 'Settings',
'site.loggedinas'    => 'Logged in as',

// SITES
'legal.name'            => 'Name',
'legal.address'         => 'Address',
'legal.nameAndAddress'  => 'Name and address',
'legal.email'           => 'E-mail address',
'legal.phone'           => 'Phone',
'legal.fax'             => 'Fax',
'legal.representatives' => 'Representative',
'legal.taxID'           => 'Tax ID',

'user.name'              => 'Username',
'user.gameCount'         => 'Games',
'user.password'          => 'Password',
'user.password.confirm'  => 'Repeat password',
'user.email'             => 'E-mail address',
'user.email.confirm'     => 'Repeat E-mail address',
'user.login'             => 'Login',
'user.logout'            => 'Logout',
'user.register'          => 'Register',
'user.profile'           => 'Profile',
'user.settings'          => 'Settings',
'user.settings.language' => 'Language',
'user.list'              => 'User list',

'game.opponent'        => 'Opponent',
'game.whiteplayer'     => 'White player',
'game.blackplayer'     => 'Black player',
'game.list'            => 'Games',
'game.list.running'    => 'Running games',
'game.list.finished'   => 'Finished games',
'game.list.gotogame'   => 'view',
'game.new'             => 'New Game',
'game.new.against'     => 'Play against %opponent',
'game.settings'        => 'Game settings',
'game.lastupdate'      => 'last update',
'game.status'          => 'Status',
'game.status.nextturn' => "It's %u's turn",
'game.status.won'      => '%u won',
'game.status.draw'     => 'draw',
'game.menu.resign'     => 'Resign',
'game.menu.offerdraw'  => 'Offer Draw',

'chat.send' => 'Send',

'chess.invalidmove.blocked'     => "There is another chess piece blocking the path.",
'chess.invalidmove.nopiece'     => "There is no chesspiece on that field.",
'chess.invalidmove.notyourturn' => "It's not your turn!",
'chess.invalidmove.owncolor'    => "There is one of your pieces in the way.",
'chess.invalidmove.bishop'      => "A bishop can't move like that.",
'chess.invalidmove.king'        => "A king can't move like that.",
'chess.invalidmove.knight'      => "A knight can't move like that.",
'chess.invalidmove.pawn'        => "A pawn can't move like that.",
'chess.invalidmove.queen'       => "A queen can't move like that.",
'chess.invalidmove.rook'        => "A rook can't move like that.",

'chess.moved' => '<strong>%user</strong> moved <span class="chesspiece">%piece</span> from <strong>%from</strong> to <strong>%to</strong>',

'exception.404.msg' => 'This page is no longer available.<br /><a href="index.php">Return to home</a>',
'exception.403.msg' => 'You don\'t have access to this page.<br /><a href="index.php">Return to home</a>',

);
