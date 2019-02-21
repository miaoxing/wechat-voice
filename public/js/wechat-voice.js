define([
  'plugins/app/libs/artTemplate/template.min',
  'comps/jPlayer/dist/jplayer/jquery.jplayer.min',
  'css!comps/jPlayer/dist/skin/blue.monday/css/jplayer.blue.monday.min'
], function (template) {
  template.helper('$', $);

  var Voices = function () {
    // do nothing.
  };

  $.extend(Voices.prototype, {
    /**
     * 容器
     */
    $container: null,

    /**
     * 微信接口
     */
    wx: null,

    /**
     * 上传的url
     */
    uploadUrl: '',

    /**
     * 已有的录音
     */
    voices: [],

    /**
     * 最大上传数量
     */
    max: 1,

    init: function (options) {
      $.extend(this, options);

      var that = this;
      that.wx.load(function () {
        var isRecord = false;
        that.$container.on('click', '.js-start-voice-record', function () {
          if (!isRecord) {
            that.wx.startRecord();
            $('.js-start-voice-record .start-record-icon').css('color', '#1EB8D0');
            isRecord = true;

          } else {
            that.wx.stopRecord({
              success: function (res) {
                that.syncUpload(that, res.localId);
              }
            });

            $('.js-start-voice-record .start-record-icon').css('color', '#D9D9D9');
            isRecord = false;
          }
        });

        // 录音时间超过一分钟没有停止的时候会执行 complete 回调
        that.wx.onVoiceRecordEnd({
          complete: function (res) {
            $('.js-start-voice-record .start-record-icon').css('color', '#D9D9D9');
            that.syncUpload(that, res.localId);
          }
        });

        that.$container.on('click', '.js-delete-voice', function (e) {
          var item = $(this).parent().parent();
          $.confirm('确定删除该录音吗？', function (result) {
            if (result) {
              item.remove();
            }
          });
          e.stopPropagation();
        });
      });

      // 渲染已有的图片
      $.each(that.voices, function (i, voice) {
        that.htmlAppend(that, i, voice);
      });
    },

    /**
     * 根据配置选择组件
     */
    htmlAppend: function (self, i, url) {
      var htmlTpl = template.compile($('#voice-cell-tpl').html());
      self.$container.find('.js-upload-cells').prepend(htmlTpl({
        url: url,
        index: i
      }));

      var stream = {
        mp3: url
      };
      var ready = false;

      $('#jquery_jplayer_' + i).jPlayer({
        cssSelectorAncestor: '#jp_container_' + i,
        swfPath: 'comps/jPlayer/dist/jplayer',
        supplied: 'mp3',
        preload: 'none',
        wmode: 'window',
        volume: 1,
        useStateClassSkin: true,
        autoBlur: false,
        keyEnabled: true,
        ready: function () {
          ready = true;
          $(this).jPlayer('setMedia', stream);
        },
        pause: function () {
          $(this).jPlayer('clearMedia');
        },
        error: function (event) {
          if (ready && event.jPlayer.error.type === $.jPlayer.error.URL_NOT_SET) {
            $(this).jPlayer('setMedia', stream).jPlayer('play');
          }
        }
      });
    },

    /**
     * 同步上传
     */
    syncUpload: function (self, localId) {
      // 上传之前检查是否超出数量
      if (self.$container.find('.js-voice-cell').length >= self.max) {
        $.alert('上传的录音不能超过' + self.max + '条');
        return;
      }

      self.wx.uploadVoice({
        localId: localId,
        isShowProgressTips: 1,
        success: function (res) {
          self.uploadServerId(self, res.serverId);
        },
        error: function () {
          $.alert('上传失败！');
        }
      });
    },

    /**
     * 上传语音到服务器
     */
    uploadServerId: function (self, serverId) {
      $.ajax({
        url: self.uploadUrl,
        type: 'post',
        dataType: 'json',
        data: {
          serverId: serverId
        },
        success: function (ret) {
          if (ret.code === 1) {
            self.htmlAppend(self, 0, ret.url);
          }
          $.msg(ret);
        },
        error: function () {
          $.alert('上传失败，请重试');
        }
      });
    }
  });

  return new Voices();
});
