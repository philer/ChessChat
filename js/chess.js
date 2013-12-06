var chess = {
    
    chesspieces : null,
    squares     : null,
    whitePrison : null,
    blackPrison : null,
    statusField : null,
    selected    : null,
    
    lastMoveId  : 0,
    
    utf8 : {
        'Q' : '&#x2655;',
        'q' : '&#x265B;',
        'P' : '&#x2659;',
        'p' : '&#x265F;',
        'N' : '&#x2658;',
        'n' : '&#x265E;',
        'R' : '&#x2656;',
        'r' : '&#x265C;',
        'K' : '&#x2654;',
        'k' : '&#x265A;',
        'B' : '&#x2657;',
        'b' : '&#x265D;'
    },
    
    init : function() {
        
        chess.lastMoveId = gameData.lastMoveId;
        
        chess.squares     = $('td.square');
        chess.whitePrison = $('#whitePrison');
        chess.blackPrison = $('#blackPrison');
        chess.statusField = $('#status');
        
        chess.setBoardSize();
        
        if (gameData.ownColor) {
            chess.chesspieces = $('table#chessboardTable span.chesspiece.' + gameData.ownColor);
            chess.chesspieces.click(chess.select);
            chess.squares.click(chess.clickMove);
            
            chess.chesspieces.draggable({
                containment   : 'table#chessboardTable',
                stack         : 'table#chessboardTable span.chesspiece',
                snap          : 'td.square > div',
                snapMode      : 'inner',
                snapTolerance : '10',
                revert        : 'invalid',
                start         : function(event, ui) {
                                    if (chess.selected !== null) {
                                        chess.selected.removeClass('selected');
                                    }
                                    chess.selected = $(this).addClass('selected noClick');
                                }
            });
            
            chess.squares.droppable({
                accept : chess.chesspieces,
                drop   : chess.dropMove
            });
        
            $('#resign').click(function(){
                overlay.show('Resign', 'resign is not implemented');
                return chat.sendMessage('/resign');
            });
            $('#offerDraw').click(function(){
                overlay.show('Draw', 'draw is not implemented');
                return chat.sendMessage('/offerDraw');
            });
        }
        
        $(window).resize(chess.setBoardSize);
        setInterval(chess.getUpdate, userData.updateInterval);
    },
    
    getUpdate : function() {
        core.post(
            'ChatController,GameController',
            'getUpdate',
            'gameId=' + gameData.id
                + '&lastMsgId=' + chat.lastMsgId
                + '&lastMoveId=' + chess.lastMoveId,
            [chat.handleReply, chess.handleReply]
            );
    },
    
    /**
     * Marks a chesspiece as 'selected' via mouse click
     */
    select : function() {
        if (chess.selected !== null) {
            chess.selected.removeClass('selected');
            if (chess.selected.getField() === $(this).getField()) {
                chess.selected = null;
                return;
            }
        }
        chess.selected = $(this).addClass('selected');
    },
    
    /**
     * Moves a selected chesspiece to the appropriate spot via mouse click
     */
    clickMove : function() {
        if (chess.selected !== null
            && chess.selected.getField() !== $(this).getField() ) {
            
            chess.move(chess.selected, $(this));
            
            chess.selected.removeClass('selected');
            chess.selected = null;
        }
    },
    
    /**
     * Handles move via drag & drop
     */
    dropMove : function(event, ui) {
        // ui.draggable.draggable( 'option', 'revert', false );
        // var moveStr = ui.draggable.getField() + '-' + $(this).getField();
        if (ui.draggable.getField() !== $(this).getField()) {
            chess.move(ui.draggable, $(this));

        } else {
            chess.resetMove(ui.draggable.getField());
        }
        ui.draggable.removeClass('selected');
    },
    
    /**
     * Returns a chesspiece to its original position, e.g.
     * when a move was invalid. Only works on moves that have not
     * been sent/executed yet.
     * @param  string  from  coordinates
     */
    resetMove : function(from) {
        $('#chesspiece-' + from).css({top: '0px', left: '0px'});
    },
    
    /**
     * Does a local move and sends request to server
     * @param  node  from
     * @param  node  to
     */
    move : function(from, to) {
        var toSquare = to.getField();
        
        if (toSquare[1] == 8 && from.getChessPiece() == 'P') {
            var promotionWhite = 1;
        } else if (toSquare[1] == 1 && from.getChessPiece() == 'p') {
            var promotionWhite = 0;
        } else {
            // regular move
            core.post(
                'GameController',
                'move',
                'gameId=' + gameData.id + '&move=' + from.getField() + '-' + toSquare,
                [chat.handleReply, chess.handleReply]
            );
            return;
        }
        // request promotion input
        overlay.showTpl(
            '_promotion',
            'white=' + promotionWhite,
            function() {
                $('.promotion-option').click(function() {
                    chess.promotionMove(from, to, $(this).getChessPiece());
                });
            },
            function() {
                chess.resetMove(from.getField());
            }
        );
    },
    
    /**
     * Handles moves that require a promotion.
     * Use this as a callback.
     */
    promotionMove : function(from, to, promotion) {
        core.post(
            'GameController',
            'move',
            'gameId=' + gameData.id + '&move=' + from.getField() + '-' + to.getField() + promotion,
            [chat.handleReply, chess.handleReply]
        );
        overlay.hide();
    },
    
    /**
     * Reacts to an ajax response
     * @param  {json} reply
     */
    handleReply : function(reply) {
        if (reply.move) {
            if (reply.move.valid) {
                chess.executeMove(reply.move);
                chess.lastMoveId = reply.move.id;
            } else {
                chess.resetMove(reply.move.from);
            }
        }
        if (reply.moves) {
            for (var i=0 ; i<reply.moves.length ; i++) {
                chess.executeMove(reply.moves[i]);
                chess.lastMoveId = reply.moves[i].id;
            }
        }
        if (reply.status) {
            chess.statusField.html(reply.status);
        }
    },
    
    /**
     * Actually moves chesspiece to new html parent.
     * @param  {string} move "E3-"E4"
     */
    executeMove : function(move) {
        var chesspiece = $('#chesspiece-' + move.from);
        var newsquare  = $('#square-' + move.to + ' > div');
        
        if (move.capture) {
            var target = $('#square-' + move.capture + ' > div');
            var prisoner = target.contents();
            if (typeof prisoner.html() !== 'undefined') {
                if (prisoner.hasClass('white')) {
                    chess.whitePrison.append('<li class="chesspiece">' + prisoner.html() + '</li>');
                } else {
                    chess.blackPrison.append('<li class="chesspiece">' + prisoner.html() + '</li>');
                }
            }
            target.empty();
        }
        
        chesspiece.appendTo(newsquare)
                  .css({top: '0px', left: '0px'})
                  .attr('id', 'chesspiece-' + move.to);
        
        if (move.promotion) {
            chesspiece.data('chesspiece', move.promotion);
            chesspiece.html(chess.utf8[move.promotion]);
        }
    },
    
    /**
     * Layout helper
     */
    setBoardSize : function() {
        var gh = $('#game').height() - $("#game header").height() - $("#game footer").height();
        var gw = $("#game").innerWidth();
        var a = Math.min(gh,gw-100);
        
        $("#chessboardTable").height(.9*a)
                             .width(.9*a);
        
        $("#chessboard").css("margin-top", (gh-0.9*a )/2)
                        .css("font-size",0.07*a+"px");
    }
};

/// chess lib ////
jQuery.fn.getField = function() {
    var id = $(this[0]).attr('id');
    // id looks like 'chesspiece-A4'
    return id.substr( id.indexOf('-')+1 );
};

jQuery.fn.getChessPiece = function() {
    return $(this[0]).data('chesspiece');
};
