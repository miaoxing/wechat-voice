<?= $block->css() ?>
<!-- htmllint tag-bans="false" -->
<style>
  .admin-voice-audio {
    margin: 5px 10px 5px 0;
  }
</style>
<!-- htmllint tag-bans="$previous" -->
<?= $block->end() ?>

<!-- htmllint preset="none" -->
<!-- htmllint tag-name-match="false" -->
<div class="form-group">
  <label class="col-sm-2 control-label">语音</label>

  <div class="col-sm-10">
    <div class="media user-media">
      <% if(typeof config.voices != 'undefined') { %>
        <% for (var i in config.voices) { %>
        <audio class="media-audio admin-voice-audio" controls="controls">
          <source src="<%= config.voices[i]; %>" />
        </audio>
        <% } %>
      <% } else { %>
        <% for (var i in voices) { %>
        <audio class="media-audio admin-voice-audio" controls="controls">
          <source src="<%= voices[i]; %>" />
        </audio>
        <% } %>
      <% } %>
    </div>
  </div>
</div>

