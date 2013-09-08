<?php defined("SYSPATH") or die("No direct script access.") ?>
/**
 * @author suprsidr
 */
( function(window, $) {
    window.g3_Galleria = {};
    var items =   <?= $items ?>, images = [], w = h = 0, full = false, current;
    window.g3_slideshow = {
      show : function(start) {
        var area = 0, padding = {
          'azur' : 40,
          'classic' : 50,
          'twelve' : 29,
          'miniml' : 30,
          'fullscreen' : 0,
          'folio' : 0
        };
        images = [];
        $(items).each(function(i) {
          var tmpw = parseInt(this.resize_width || this.width);
          var tmph = parseInt(this.resize_height || this.height);
          if (Math.floor(tmpw * tmph) > area) {
            area = Math.floor(tmpw * tmph);
            w = tmpw;
            h = tmph + padding['<?= $ss_theme ?>'];
          }
          if (parseInt(this.id) == start) {
            start = i;
          }
          switch(this.type) {
            case"photo":
              var el = this;
              images.push({
                resize_width : this.resize_width,
                resize_height : this.resize_height,
                width : this.width,
                height : this.height,
                image : this.resize_url_public,
                thumb : this.thumb_url_public,
                big : this.file_url_public,
                title : this.title,
                description : this.description,
                link : this.web_url,
                maxWidth : function() {
                  return window.innerWidth > el.resize_width ? el.resize_width : window.innerWidth;
                },
                maxHeight : function() {
                  return window.innerWidth > el.resize_width ? el.resize_width * (el.height / el.width) + padding['<?= $ss_theme ?>'] : window.innerWidth * (el.height / el.width) + padding['<?= $ss_theme ?>'];
                }
              });
              break;
            case"movie":
              var el = this;
              images.push({
                width : this.width,
                height : this.height,
                thumb : this.thumb_url_public,
                iframe : this.iframe_url_public,
                title : this.title,
                description : this.description,
                link : this.web_url,
                maxWidth : function() {
                  return window.innerWidth > el.width ? el.width : window.innerWidth;
                },
                maxHeight : function() {
                  return window.innerWidth > el.width ? el.width * (el.height / el.width) + padding['<?= $ss_theme ?>'] : window.innerWidth * (el.height / el.width) + padding['<?= $ss_theme ?>'];
                }
              });
              break
          }
          if (i == items.length - 1) {
            $("html").append('<div id="modal" style="background-color: rgba(0,0,0,.6);width:100%;min-width:100%;height:100%;min-height:100%;position:absolute;margin:auto;top:0;right:0;bottom:0;left:0;z-index:999;" /><div id="galleria" style="height: 100%; max-height:' + h + 'px;width:100%;max-width:' + w + 'px;position:absolute;margin:auto;top:0;right:0;bottom:0;left:0;z-index:1000;"/>');
            g3_slideshow.play(start);
          }
        })
      },
      play : function(start) {
        $('#galleria').galleria({
          data_source : images,
          _showPopout : false,
          autoplay : true,
          easing : 'swing',
          show : start
        });
        window.g3_Galleria = $("#galleria").data("galleria");
        window.g3_Galleria.bind("loadstart", function(e) {
          current = e.galleriaData;
          g3_slideshow.reform(current, window.g3_Galleria);
        }).bind("fullscreen_enter", function(e) {
          full = true;
        }).bind("fullscreen_exit", function(e) {
          full = false;
          g3_slideshow.reform(current, window.g3_Galleria);
        });
        if ('<?= $ss_theme ?>' == 'classic') {
          $('.galleria-container').append('<div class="galleria-info-link galleria-fullscreen" style="display:block;z-index:999;top: 15px;right: 15px;background: #000000 url(<?= $fs_png ?>) no-repeat 3.5px 3.5px;width:20px;height:20px;"></div>');
        }
        $(document).on('click', '.galleria-fullscreen', function(e) {
          full = !full;
          if (full) {
            $('#galleria').css({
              'max-width' : '100%',
              'max-height' : '100%'
            });
          }
          if ('<?= $ss_theme ?>' == 'classic') {
            window.g3_Galleria.toggleFullscreen();
          }
        })
        $(document).on("click", "#modal", function(e) {
          $("#galleria, #modal").remove()
        })
      },
      reform : function(el, G) {
        if (!full) {
          var w = el.maxWidth(), h = el.maxHeight();
          G.resize({
            width : w,
            height : h
          })
          $('#galleria').css({
            'max-width' : w + 'px',
            'max-height' : h + 'px'
          });
        }
      }
    }
  }(window, window.jQuery));
