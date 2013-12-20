$(function() {
    
    // global modules
    core.init();
    overlay.init();
    
    // optional modules
    if (typeof chat !== 'undefined')  chat.init();
    if (typeof chess !== 'undefined') chess.init();
    
});
