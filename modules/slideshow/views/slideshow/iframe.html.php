<?php defined("SYSPATH") or die("No direct script access.") ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <style>
      * {
        margin: 0;
        padding: 0;
      }
      html, body {
        height: 100%;
        width: 100%;
        overflow: hidden;
      }
      video, object {
        position: absolute;
        top: 50%;
        left: 50%;
      }
    </style>
    <script>
      var console = (window.console = window.console || {});
      if (!console['log']) {
        console['log'] = function(){};
      }
      /**
	   * Media template v.1.4 @author suprsidr Copyright (c) 2013 Wayne Patterson <suprsidr [at) gmail (dot] com> License: http://www.opensource.org/licenses/mit-license.php
	   */
	  function getMediaTemplate(a){switch(a){case"video/mpeg":case"video/avi":case"video/x-msvideo":case"video/x-ms-wmv":var classId="CLSID:05589FA1-C356-11CE-BF01-00AA0055595A";case"video/x-ms-asf":case"video/x-ms-asx":var classId=classId!="undefined"?classId:"CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95";return'<object classid="'+classId+'" width="%(width)s" height="%(height)s" id="%(id)s"><param name="ShowDisplay" value="0"/><param name="ShowControls" value="1"/><param name="AutoStart" value="1"/><param name="AutoRewind" value="-1"/><param name="Volume" value="0"/><param name="FileName" value="%(file_url_public)s"/><embed src="%(file_url_public)s" width="%(width)s" height="%(height)s" type="%(mime_type)s" controller="true" autoplay="true" loop="false"/></object>';break;case"video/x-flv":case"video/quicktime":case"video/ogg":case"video/mp4":case"video/webm":return'<video id="multimedia-%(id)s" style="width:100%%;height:auto;" title="%(title)s" autoplay="autoplay" controls="controls" poster="%(thumb_url_public)s"><source src="%(file_url_public)s" type="%(mime_type)s" onerror="this.parentNode.parentNode.replaceChild(document.querySelector(\'#multimedia-%(id)s_api\'), this.parentNode);"></source><object style="background: #000000 url(%(thumb_url_public)s) no-repeat 0 0;width:%(width)spx;height:%(height)spx;" width="%(width)s" height="%(height)s" type="application/x-shockwave-flash" data="%(code)sflowplayer.swf" name="multimedia-%(id)s_api" id="multimedia-%(id)s_api"><param name="movie" value="%(code)sflowplayer.swf" /><param value="true" name="allowfullscreen"><param value="always" name="allowscriptaccess"><param value="high" name="quality"><param value="#000000" name="bgcolor"><param value="transparent" name="wmode"><param value="pseudostreaming" name="provider"><param value="config={&quot;plugins&quot;:{&quot;pseudostreaming&quot;:{&quot;url&quot;:&quot;%(code)sflowplayer.pseudostreaming-byterange.swf&quot;},&quot;controls&quot;:{&quot;autoHide&quot;:&quot;always&quot;,&quot;hideDelay&quot;:2000,&quot;height&quot;:24}},&quot;clip&quot;:{&quot;scaling&quot;:&quot;fit&quot;,&quot;url&quot;:&quot;%(file_url_public)s&quot;},&quot;playerId&quot;:&quot;multimedia-%(id)s_api&quot;,&quot;playlist&quot;:[{&quot;scaling&quot;:&quot;fit&quot;,&quot;url&quot;:&quot;%(file_url_public)s&quot;}]}" name="flashvars"></object></video>';break;default:return'<a href="%(file_url_public)s">Download %(title)s in original format</a>';break}};

	  /**
	   * sprintf() for JavaScript 0.7-beta1 http://www.diveintojavascript.com/projects/javascript-sprintf Copyright (c) Alexandru Marasteanu <alexaholic [at) gmail (dot] com> All rights reserved.
	   */
	  var sprintf=(function(){function a(d){return Object.prototype.toString.call(d).slice(8,-1).toLowerCase()}function b(e,f){for(var d=[];f>0;d[--f]=e){}return d.join("")}var c=function(){if(!c.cache.hasOwnProperty(arguments[0])){c.cache[arguments[0]]=c.parse(arguments[0])}return c.format.call(null,c.cache[arguments[0]],arguments)};c.format=function(m,l){var q=1,o=m.length,g="",r,d=[],h,f,j,e,n,p;for(h=0;h<o;h++){g=a(m[h]);if(g==="string"){d.push(m[h])}else{if(g==="array"){j=m[h];if(j[2]){r=l[q];for(f=0;f<j[2].length;f++){if(!r.hasOwnProperty(j[2][f])){throw (sprintf('[sprintf] property "%s" does not exist',j[2][f]))}r=r[j[2][f]]}}else{if(j[1]){r=l[j[1]]}else{r=l[q++]}}if(/[^s]/.test(j[8])&&(a(r)!="number")){throw (sprintf("[sprintf] expecting number but found %s",a(r)))}switch(j[8]){case"b":r=r.toString(2);break;case"c":r=String.fromCharCode(r);break;case"d":r=parseInt(r,10);break;case"e":r=j[7]?r.toExponential(j[7]):r.toExponential();break;case"f":r=j[7]?parseFloat(r).toFixed(j[7]):parseFloat(r);break;case"o":r=r.toString(8);break;case"s":r=((r=String(r))&&j[7]?r.substring(0,j[7]):r);break;case"u":r=Math.abs(r);break;case"x":r=r.toString(16);break;case"X":r=r.toString(16).toUpperCase();break}r=(/[def]/.test(j[8])&&j[3]&&r>=0?"+"+r:r);n=j[4]?j[4]=="0"?"0":j[4].charAt(1):" ";p=j[6]-String(r).length;e=j[6]?b(n,p):"";d.push(j[5]?r+e:e+r)}}}return d.join("")};c.cache={};c.parse=function(d){var g=d,h=[],j=[],i=0;while(g){if((h=/^[^\x25]+/.exec(g))!==null){j.push(h[0])}else{if((h=/^\x25{2}/.exec(g))!==null){j.push("%")}else{if((h=/^\x25(?:([1-9]\d*)\$|\(([^\)]+)\))?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?([b-fosuxX])/.exec(g))!==null){if(h[2]){i|=1;var k=[],f=h[2],e=[];if((e=/^([a-z_][a-z_\d]*)/i.exec(f))!==null){k.push(e[1]);while((f=f.substring(e[0].length))!==""){if((e=/^\.([a-z_][a-z_\d]*)/i.exec(f))!==null){k.push(e[1])}else{if((e=/^\[(\d+)\]/.exec(f))!==null){k.push(e[1])}else{throw ("[sprintf] huh?")}}}}else{throw ("[sprintf] huh?")}h[2]=k}else{i|=2}if(i===3){throw ("[sprintf] mixing positional and named placeholders is not (yet) supported")}j.push(h)}else{throw ("[sprintf] huh?")}}}g=g.substring(h[0].length)}return j};return c})();var vsprintf=function(b,a){console.log(a);console.log(b);a.unshift(b);return sprintf.apply(null,a)};

      var entity = <?= $item ?>;
      entity.code = '<?= $code ?>'; // reference to flowplayer directory

      window.onload = function() {
      	document.querySelector('body').innerHTML = sprintf(getMediaTemplate(entity.mime_type), entity);
        try {
          parent.jQuery("#galleria").data("galleria").pause()
        } catch(e) {
          console.log(e)
        }
        setMargins();
      };
      function supports_video() {
      	return !!document.createElement('video').canPlayType;
	  }
      window.onresize = setMargins;
      if (!window.getComputedStyle) {
        window.getComputedStyle = function(el, pseudo) {
          this.el = el;
          this.getPropertyValue = function(prop) {
            var re = /(\-([a-z]){1})/g;
            if (prop == "float") {
              prop = "styleFloat"
            }
            if (re.test(prop)) {
              prop = prop.replace(re, function() {
                return arguments[2].toUpperCase()
              })
            }
            return el.currentStyle[prop] ? el.currentStyle[prop] : null
          };
          return this
        }
      }
      function setMargins() {
        if(supports_video()){
        	var el = document.querySelector("video");
        	el.style.marginTop = parseInt(window.getComputedStyle(el, null).getPropertyValue("height").replace("px", "")) / 2 * -1 + "px";
	        el.style.marginLeft = parseInt(window.getComputedStyle(el, null).getPropertyValue("width").replace("px", "")) / 2 * -1 + "px";
	        el.onended = function(e) {
	          try {
	            parent.jQuery("#galleria").data("galleria").next().play()
	          } catch(e) {
	            console.log(e)
	          }
	        }
        }else{
        	var el = document.querySelector("object");
        	var w = entity.width,
        	h = entity.height,
        	asp = h/w;
        	w = window.innerWidth || document.documentElement.clientWidth;
        	h = w * asp;
        	el.style.width = w;
        	el.style.height = h;
        	el.style.marginTop = h / 2 * -1 + "px";
        	el.style.marginLeft = w / 2 * -1 + "px";
         }
      };
    </script>
  </head>
  <body></body>
</html>