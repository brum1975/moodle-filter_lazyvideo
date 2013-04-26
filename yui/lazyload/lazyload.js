YUI.add('moodle-filter_lazyvideo-lazyload', function(Y) {


    var AUTOLINKER = function() {
        AUTOLINKER.superclass.constructor.apply(this, arguments);
    };
    Y.extend(AUTOLINKER, Y.Base, {
        overlay : null,
        initializer : function(config) {
            Y.delegate('click', function(e){

                e.preventDefault();

                //display a progress indicator
                var title = '';
                var content = Y.Node.create('<iframe width="'+this.one('img').get('offsetWidth')+'" height="'+this.one('img').get('offsetHeight')+'" src="http://www.youtube.com/embed/'+this.getAttribute('data-code')+'?feature=player_detailpage&autoplay=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
                this.replace(content);

            }, Y.one(document.body), 'a.lazyvideo');
        }
    });

    M.filter_lazyvideo = M.filter_lazyvideo || {};
    M.filter_lazyvideo.init_filter_lazyload = function(config) {
        return new AUTOLINKER(config);
    }

}, '@VERSION@', {requires:['base','node','io-base','json-parse','event-delegate','overlay','moodle-enrol-notification']});
