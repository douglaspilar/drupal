/*
* countdata - jQuery plugin to provide a limiter for characters and provide feedback
*
* Tested with jQuery 1.3.2
*
* Copyright (c) 2006 Richard Ranke richardranke@gmail.com
*
* Dual licensed under the MIT and GPL licenses:
*   http://www.opensource.org/licenses/mit-license.php
*   http://www.gnu.org/licenses/gpl.html
*
*/
/**
 * @name countdata
 *
 * @example <input type="text"/>
 * @before $("input").countdata({limit:200});
 * @after <span><input type="text"/><div class="countdatamsg"></div></span>
 * @desc limits input by 200
 *
 * @dependancy jquery.metadata.js
 * @example class="countme {limit:200}"
 * @before $("input").countdata();
 * @after <span><input type="text"/><div class="countdatamsg"></div></span>
 * @desc limits input by 200
 *
 */
(function($) {
    $.fn.countdata = function(options){
        // setup debug
        //debug(this);
        // build main options before element iteration
        var opts = $.extend({}, $.fn.countdata.defaults, options);
        // remove message container -- catch duplication
        if($('.countdatamsg')) $('.countdatamsg').remove();
        return this.each(function() {
            $this = $(this);
            var o = ($this.metadata()) ? $.extend({}, opts, $this.metadata()) : opts;
            initTextArea($this, o);
        });
    };
    // init
    function initTextArea($obj, $o){
        attachFeedbackContainer($obj, $o.initMsg, $o.jqueryUI);
        setContainerInitialValue($obj, $o);
        updateContainerValue($obj, $o);
    };
    // create feedback container
    function attachFeedbackContainer($obj, initMsg, jqueryUI){
        if(jqueryUI){
            $obj.css({border:'1px solid white'});
            $obj.wrap('<div class="ui-state-default ui-corner-all" style="float:left"></div>');
            $('<div class="countdatamsg ui-state-default ui-corner-bottom" style="text-align:right">'+initMsg+'</div>').insertAfter($obj);
        }else{
            $obj.css({border:'1px solid #7f9db9'});
            $obj.wrap('<div style="float:left"></div>');
            $('<div class="countdatamsg" style="text-align:right">'+initMsg+'</div>').insertAfter($obj);
        }
    };
    // check textarea for current content and update container
    function setContainerInitialValue($obj, $o){
        var newVal = $obj.val().length + checkForNewLines($obj);
        if($obj.val().length < $o.limit) {
            $obj.next('div').html('There are ' + (newValue($o,newVal)) + ' characters left.');
        }
    };
    // check key stroke and update
    function updateContainerValue($obj, $o){
        checkBlurTextArea($obj, $o);
        checkKeyUpTextArea($obj, $o);
        checkKeyDownTextArea($obj, $o);
        checkPasteEvent($obj, $o);
    };
    function checkBlurTextArea($obj, $o){
        $obj.blur(function(){
            if($o.stripHTMLTags) {
                stripTags($(this));
            }
            setContainerInitialValue($obj, $o);
        });
    };
    function checkKeyDownTextArea($obj, $o){
        $obj.keydown(function(event){
            //$('.countdatamsg').fadeTo("fast", 1.0);
            var keyCode = event.keyCode;
            var allowedChars = [8, 37, 38, 39, 40, 46];    //Backspace, delete and arrow keys
            for(var x=0; x<allowedChars.length; x++){ if(allowedChars[x] == keyCode) {return true;}};
            return ($obj.val().length + checkForNewLines($obj)) < $o.limit;//$obj.val().length < $o.limit;
        });
    };
    function checkForNewLines($obj){
        var checkNewLine =  ($obj.val().split('\n').length);
        checkNewLine = (checkNewLine - 1);
        return checkNewLine;
    }
    function checkKeyUpTextArea($obj, $o){
        $obj.keyup(function(){
            //$('.countdatamsg').fadeTo("fast", 0.33);
            var newVal = $obj.val().length + checkForNewLines($obj);
            if ($obj.val().length > $o.limit) {
                $obj.next('div').html();
                $obj.next('div').html($o.prefixMsg + $o.limit + $o.suffixMsg);
                $obj.val($obj.val().substr(0, $o.limit));
            } else {
                $obj.next('div').html();
                $obj.next('div').html('There are ' + newValue($o,newVal) + ' characters left.');
                return true;
            }
        });
    };
    function newValue($o,newVal){
        if($o.limit < newVal) {
            return $o.limit - $o.limit;
        }else{
            return $o.limit - newVal;
        }
    }
    function checkPasteEvent($obj, $o){
        // disable contextual menu
        //$obj[0].oncontextmenu = function(){return false;};
        $obj.bind('paste', function(){
            // Thanks Brian Crescimanno for the setTimeout
            setTimeout(function(){
                if($o.stripHTMLTags) {
                    stripTags($(this));
                }
                $obj.next('div').html('There are ' + ($o.limit - ($obj.val().length + checkForNewLines($obj))) + ' characters left.');
            } , 300);
        });
    };
    // strip html tags
    function stripTags($obj) {
        var regexp = /<("[^"]*"|'[^']*'|[^'">])*>/gi;
        $obj.each(function() {
            $(this).val($(this).val().replace(regexp, ""));
        });
        return $obj.val();
    };

    // future ideas
    function addEffect($obj){
        $obj.animate({
        width: "70%",
        opacity: 0.4,
        marginLeft: "0.6in",
        fontSize: "3em",
        borderWidth: "10px"
      }, 1500 );
    }
    // debug
    function debug($obj) {
        if (window.console && window.console.log)
            window.console.log($obj.width());
    };
    // setup defaults
    $.fn.countdata.defaults = {
        limit: '100',
        initMsg: 'Limited Characters',
        prefixMsg: 'You cannot write more than ',
        suffixMsg: ' characters!',
        countNLandReturn: true,
        stripHTMLTags:false,
        jqueryUI:false
    };
})(jQuery);