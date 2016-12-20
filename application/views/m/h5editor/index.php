<?php load_view('elements/header'); ?>
<style>
#preview {
  position: absolute;

  height: 640px;
  width: 360px;

  margin: 10px;
  border: 3px dotted #ff6666;
}
#app {
  margin-left: 370px;
  padding: 10px;
}
#app >div{
  border-left: 1px solid #ddd;
  border-right: 1px solid #ddd;
}
#app .row{
  margin-top: 10px;
}

</style>
<body>

<div id="preview"></div>
<main id="app">
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" :class="{ active: isEditing }">
      <a href="javascript:void(0);" @click="currentView='editing';">页面设计</a>
    </li>
    <li role="presentation" :class="{ active: isPreview }">
      <a href="javascript:void(0);" @click="currentView='preview';">效果预览</a>
    </li>
  </ul>
  <component v-bind:is="currentView">
    <!-- 组件在 vm.currentview 变化时改变！ -->
  </component>
</main>

 <!-- editing 的模版 -->
<script type="text/x-template" id="tab-editing">
  <div class="container-fluid">
    <!--工具栏-->
    <div class="row">
      <div class="col-xs-10">
        <div class="btn-group">
          <button type="button" class="btn btn-primary" @click="addPanel"><span class="glyphicon glyphicon-stop"></span></button>
          <button type="button" class="btn btn-primary" @click="addText"><span class="glyphicon glyphicon-font"></span></button>
          <button type="button" class="btn btn-primary" @click="addImage"><span class="glyphicon glyphicon-picture"></span></button>
        </div>
      </div>
      <div class="col-xs-2 text-right">
        <button type="button" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span></button>
      </div>
    </div>
    <!--内容框体-->
    
  </div>
</script>

<!-- preview 的模版 -->
<script type="text/x-template" id="tab-preview">
  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-6">
        
      </div>
    </div>
  </div>
</script>

<script src="<?= jscss_path('js/vue/vue'.(ENVIRONMENT == 'production'?'.min':'').'.js?v=2.1.6'); ?>"></script>
<script src="<?= jscss_path('default/js/app.js'); ?>"></script>

<?php load_view('elements/footer'); ?>