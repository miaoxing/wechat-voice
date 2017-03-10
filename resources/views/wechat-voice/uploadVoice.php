<?= $block('css') ?>
<link rel="stylesheet" href="<?= $asset('plugins/wechat-voice/css/wechat-voice.css') ?>">
<?= $block->end() ?>

<script type="text/html" id="voice-cell-tpl">
  <div class="js-voice-cell">
    <div id="jquery_jplayer_<%= index %>" class="jp-jplayer"></div>
    <div id="jp_container_<%= index %>" class="jp-audio-stream voice-record">
      <div class="jp-type-single">
        <div class="jp-gui jp-interface">
          <div class="jp-controls" style="width:40px;">
            <button class="jp-play" role="button" tabindex="0" type="button">play</button>
          </div>
        </div>
      </div>
      <i class="v-f-icon delete-voice js-delete-voice">&#xe602;</i>
      <input type="hidden" name="voices[]" value="<%= url %>">
    </div>
  </div>
</script>

<script type="text/html" id="wx-upload-voice-tpl">
  <div class="wx-upload-voice js-wx-upload-voice">
    <div class="wx-upload-voice-header">
      <span><%== title ? title : '语音上传' %></span>
    </div>

    <div class="wx-upload-voice-body js-upload-cells">
      <span class="start-voice-record js-start-voice-record">
        <i class="v-f-icon start-record-icon">&#xe601;</i>
      </span>
    </div>
  </div>

  <div class="wx-upload-voice-help">
    提示：按一下开始录音，再按一下结束
  </div>
</script>

