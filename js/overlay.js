var overlay = {
    
    overlay       : null,
    container     : null,
    content       : null,
    closeButton   : null,
    abortCallback : function() {},
    
    init : function() {
        overlay.overlay     = $('#overlay');
        overlay.container   = $('#overlay .overlayContainer');
        overlay.content     = $('#overlay .overlayContent');
        overlay.closeButton = $('#overlay a.close');
        
        // close via button
        overlay.closeButton.click(overlay.abort);
        // close via esc
        $(document).keypress(function(e) {
            if (e.keyCode == 27) overlay.abort();
        });
        // close via click next to overlay window
        overlay.overlay.click(overlay.abort);
        overlay.container.click(function(e) {e.stopPropagation()});
        
    },
    
    showTpl : function(tplName, data, loadCallback, abortCallback) {
        core.post(
            'TemplateEngine',
            'fetch',
            'tpl=' + tplName + (data === undefined ? '' : '&' + data),
            function(e) {
                overlay.show(e.tpl);
                if (typeof loadCallback != 'undefined') loadCallback.call();
        });
        if (typeof abortCallback != 'undefined') overlay.abortCallback = abortCallback;
    },
    
    show : function(content) {
        overlay.content.html(content);
        overlay.overlay.show();
        overlay.container.css('max-height',
                $('#overlay .overlayContent section').height()
                + 100
            );
    },
    
    abort : function() {
        overlay.hide();
        overlay.abortCallback.call();
        overlay.abortCallback = function() {};
    },
    
    hide : function() {
        overlay.overlay.hide();
    }
}
