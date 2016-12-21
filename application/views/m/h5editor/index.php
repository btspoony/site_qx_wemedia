<?php load_view('elements/header'); ?>
<link href="<?= jscss_path('default/css/app.css'); ?>" rel="stylesheet">
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
      <div class="col-xs-12 btn-group">
        <button type="button" class="btn btn-primary" @click="addElement('div')"><span class="glyphicon glyphicon-stop"></span></button>
        <button type="button" class="btn btn-primary" @click="addElement('text')"><span class="glyphicon glyphicon-font"></span></button>
        <button type="button" class="btn btn-primary" @click="addElement('image')"><span class="glyphicon glyphicon-picture"></span></button>
      </div>
    </div>
    <!--内容框体-->
    <div class="row editor-view">
      <ul class="view-toolbar">
        <li><span class="label label-info">页数</span></li>
        <li v-for="(page, index) in pages">
          <button type="button" class="btn btn-sm btn-default"
            :class="{ active: (currentPage==index) }"
            @click="currentPage = index">{{index+1}}</button>
        </li>
        <li>
          <button type="button" class="btn btn-xs btn-success"
            @click="addPage"><span class="glyphicon glyphicon-plus"></span></button>
        </li>
      </ul>
      <div class="panel panel-default view-content">
        <button type="button" class="btn btn-danger pull-right" style="margin-top: 3px; margin-right: 3px;">
          <span class="glyphicon glyphicon-trash"></span>
        </button>
        <div class="panel-heading">
          <span>第{{currentPage+1}}页</span>
        </div>
        <div class="panel-body">
          <div class="container-fluid">
            <h4 v-if="isEmpty">无内容元素</h4>
            <element-component
              v-for="(element, index) in currentPageElements"
              :define="element"
              :index="index"
              :current="currentElement"
              @current="currentElement = arguments[0];">
            </element-component>
          </div>
        </div>
      </div>
    </div>
  </div>
</script>

<!-- Editing 元素模块 -->
<script type="text/x-template" id="element-component">
  <div class="row view-element-define"
      :class="{ active: (index===current) }"
      @click="setCurrent">
      Basic panel example {{index}}
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