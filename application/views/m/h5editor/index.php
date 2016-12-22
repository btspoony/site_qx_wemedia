<?php load_view('elements/header'); ?>
<link href="<?= jscss_path('default/css/app.css'); ?>" rel="stylesheet">
<body>

<main class="main">
  <div id="preview"></div>
  <div id="app">
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
  </div>
</main>

 <!-- editing 的模版 -->
<script type="text/x-template" id="tab-editing">
  <div class="container-fluid">
    <!--工具栏-->
    <div class="row editor-toolbar">
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
            @click="addPage" disabled="disabled">
            <span class="glyphicon glyphicon-plus"></span></button>
        </li>
      </ul>
      <div class="panel panel-default view-content">
        <button type="button" class="btn btn-danger pull-right" style="margin-top: 3px; margin-right: 3px;"
          disabled="disabled">
          <span class="glyphicon glyphicon-trash"></span>
        </button>
        <div class="panel-heading">
          <span>第 {{currentPage+1}} 页</span>
        </div>
        <div class="panel-body">
          <div class="container-fluid">
            <h4 v-if="isEmpty">无内容元素</h4>
            <element-editor-comp
              v-for="(element, index) in currentPageElements"
              :key="element.id"

              :define="element"
              :index="index"
              :current="currentElement"

              @current="currentElement=arguments[0];"
              @remove-element="removeElement(arguments[0]);">
            </element-editor-comp>
          </div>
        </div>
      </div>
    </div>
  </div>
</script>

<!-- Editing 元素模块 -->
<script type="text/x-template" id="element-editor-comp">
  <div class="row view-element-define"
      :class="{ active: (index===current) }"
      @click="$emit('current', index );">
      <div class="col-xs-3">
        <span class="text-primary">编号: </span><span class="badge">{{ index+1 }}</span>
      </div> 
      <div class="col-xs-7">
        <span class="text-primary">类型: </span><span class="text-muted">{{ define.typename }}</span>
      </div> 
      <div class="col-xs-2 text-right">
        <button type="button" class="btn btn-xs btn-danger"
          @click="$emit('remove-element', index );">
          <span class="glyphicon glyphicon-minus"></span>
        </button>
      </div>
      <div class="col-xs-12">
        <div class="input-group input-group-sm" style="width: 34%;">
          <span class="input-group-addon">宽</span>
          <input type="text" class="form-control" v-model="define.w">
          <span class="input-group-addon" @click="define.toggleW()">{{define.w_unit}}</span>
        </div>
        <div class="input-group input-group-sm" style="width: 34%;">
          <span class="input-group-addon">高</span>
          <input type="text" class="form-control" v-model="define.h">
          <span class="input-group-addon" @click="define.toggleH()">{{define.h_unit}}</span>
        </div>
        <div v-if="define.type=='div'" class="input-group input-group-sm" style="width: 25%;">
          <span class="input-group-addon">圆角</span>
          <input type="text" class="form-control" v-model="define.radius">
        </div>
      </div>
      <template v-if="define.type=='div'||define.type=='text'">
        <div class="col-xs-9">
          <div class="input-group input-group-sm" style="width: 30%;">
            <span class="input-group-addon">R</span>
            <input type="text" class="form-control" v-model="define.r">
          </div>
          <div class="input-group input-group-sm" style="width: 30%;">
            <span class="input-group-addon">G</span>
            <input type="text" class="form-control" v-model="define.g">
          </div>
          <div class="input-group input-group-sm" style="width: 30%;">
            <span class="input-group-addon">B</span>
            <input type="text" class="form-control" v-model="define.b">
          </div>
        </div>
        <div class="col-xs-3" v-show="define.type=='div'">
          <div class="input-group input-group-sm">
            <span class="input-group-addon">A</span>
            <input type="text" class="form-control" v-model="define.a">
          </div>
        </div>
      </template>
      <template v-if="define.type=='text'">
        <div class="col-xs-12">
          <div class="input-group input-group-sm" style="width: 70%;">
            <span class="input-group-addon">文本</span>
            <input type="text" class="form-control" v-model="define.data.text">
          </div>
          <div style="width: 13%;">
            <select class="form-control" v-model="define.data.type">
              <option value="p">正文</option>
              <option value="h1">标题</option>
              <option value="h2">标题2</option>
              <option value="h3">标题3</option>
              <option value="h4">标题4</option>
            </select>
          </div>
          <div style="width: 13%;">
            <select class="form-control" v-model="define.data.align">
              <option value="text-left">靠左</option>
              <option value="text-center">居中</option>
              <option value="text-right">靠右</option>
            </select>
          </div>
        </div>
      </template>
      <template v-if="define.type=='image'">
        <div class="col-xs-12">
          <div class="input-group input-group-sm" style="width: 70%;">
            <span class="input-group-addon">地址</span>
            <input type="text" class="form-control" v-model="define.url">
          </div>
        </div>
      </template>
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