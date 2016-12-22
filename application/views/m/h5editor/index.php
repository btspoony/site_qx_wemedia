<?php load_view('elements/header'); ?>
<link href="<?= jscss_path('default/css/h5editor.css'); ?>" rel="stylesheet">
<body>

<main class="main">
  <div id="preview"></div>
  <div id="app" @drop.prevent @dragover.prevent>
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
      <div class="col-sm-12 btn-group">
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
              v-for="(element, index) in currentPageData.elements"
              :key="element.id"

              :define="element"
              :index="index"
              :current="currentElement"

              @current="currentElement=arguments[0];"
              @move-element="moveElement(arguments[0], arguments[1]);"
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
      @click="$emit('current', index );"
      @dragstart.capture="onStartDrag"
      @drop.prevent="onDrop" @dragover.prevent
      draggable=true >
      <div class="col-sm-1">
        <span class="badge">{{ index+1 }}</span>
      </div> 
      <div class="col-sm-3">
        <span class="text-primary">类型: </span><span class="text-muted">{{ define.typename }}</span>
      </div> 
      <div class="col-sm-6">
        <label><input type="checkbox" v-model="define.animed"> 动画</label>
        <label v-show="define.animed"><input type="checkbox" v-model="define.anim_infinite"> 循环</label>
        <label><input type="checkbox" v-model="define.evt_enabled"> 事件</label>
      </div> 
      <div class="col-sm-2 text-right">
        <button type="button" class="btn btn-xs btn-danger"
          @click="$emit('remove-element', index );">
          <span class="glyphicon glyphicon-minus"></span>
        </button>
      </div>
      <div class="col-sm-12">
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
        <div class="col-sm-12">
          <div class="input-group input-group-sm" style="width: 24%;">
            <span class="input-group-addon">R</span>
            <input type="text" class="form-control" v-model="define.r">
          </div>
          <div class="input-group input-group-sm" style="width: 24%;">
            <span class="input-group-addon">G</span>
            <input type="text" class="form-control" v-model="define.g">
          </div>
          <div class="input-group input-group-sm" style="width: 24%;">
            <span class="input-group-addon">B</span>
            <input type="text" class="form-control" v-model="define.b">
          </div>
          <div class="input-group input-group-sm" style="width: 24%;">
            <span class="input-group-addon">A</span>
            <input type="text" class="form-control" v-model="define.a">
          </div>
        </div>
      </template>
      <template v-if="define.type=='text'">
        <div class="col-sm-12">
          <div class="input-group input-group-sm" style="width: 70%;">
            <span class="input-group-addon" @click="define.toggleSrc()">{{define.src_name}}</span>
            <input v-if="define.src_name=='文本'" type="text" class="form-control" v-model="define.data.text">
            <select v-else class="form-control input-sm" v-model="define.data.text">
              <option v-for="n in 10" :value="'var'+(n-1)">页面变量{{ n-1 }}</option>
            </select>
          </div>
          <div style="width: 13%;">
            <select class="form-control input-sm" v-model="define.data.type">
              <option value="p">正文</option>
              <option value="h1">标题</option>
              <option value="h2">标题2</option>
              <option value="h3">标题3</option>
              <option value="h4">标题4</option>
            </select>
          </div>
          <div style="width: 13%;">
            <select class="form-control input-sm" v-model="define.data.align">
              <option value="text-left">靠左</option>
              <option value="text-center">居中</option>
              <option value="text-right">靠右</option>
            </select>
          </div>
        </div>
      </template>
      <!-- start 图片模版 -->
      <template v-if="define.type=='image'">
        <div class="col-sm-12">
          <div class="input-group input-group-sm" style="width: 70%;">
            <span class="input-group-addon">地址</span>
            <input type="text" class="form-control" v-model="define.url">
          </div>
        </div>
      </template>
      <!-- end 图片模版 -->
      <!-- start 动画模版 -->
      <template v-if="define.animed">
        <label for="animatePick" class="col-sm-2 control-label text-center" style="line-height:30px;">类型</label>
        <div class="col-sm-4">
          <select class="form-control input-sm" id="animatePick" v-model="define.anim_name">
            <optgroup label="增强关注">
              <option value="bounce">bounce</option>
              <option value="flash">flash</option>
              <option value="pulse">pulse</option>
              <option value="rubberBand">rubberBand</option>
              <option value="shake">shake</option>
              <option value="swing">swing</option>
              <option value="tada">tada</option>
              <option value="wobble">wobble</option>
            </optgroup>

            <optgroup label="缓动进场">
              <option value="bounceIn">bounceIn</option>
              <option value="bounceInDown">bounceInDown</option>
              <option value="bounceInLeft">bounceInLeft</option>
              <option value="bounceInRight">bounceInRight</option>
              <option value="bounceInUp">bounceInUp</option>
            </optgroup>

            <optgroup label="缓动出场">
              <option value="bounceOut">bounceOut</option>
              <option value="bounceOutDown">bounceOutDown</option>
              <option value="bounceOutLeft">bounceOutLeft</option>
              <option value="bounceOutRight">bounceOutRight</option>
              <option value="bounceOutUp">bounceOutUp</option>
            </optgroup>

            <optgroup label="渐变进场">
              <option value="fadeIn">fadeIn</option>
              <option value="fadeInDown">fadeInDown</option>
              <option value="fadeInDownBig">fadeInDownBig</option>
              <option value="fadeInLeft">fadeInLeft</option>
              <option value="fadeInLeftBig">fadeInLeftBig</option>
              <option value="fadeInRight">fadeInRight</option>
              <option value="fadeInRightBig">fadeInRightBig</option>
              <option value="fadeInUp">fadeInUp</option>
              <option value="fadeInUpBig">fadeInUpBig</option>
            </optgroup>

            <optgroup label="渐变出场">
              <option value="fadeOut">fadeOut</option>
              <option value="fadeOutDown">fadeOutDown</option>
              <option value="fadeOutDownBig">fadeOutDownBig</option>
              <option value="fadeOutLeft">fadeOutLeft</option>
              <option value="fadeOutLeftBig">fadeOutLeftBig</option>
              <option value="fadeOutRight">fadeOutRight</option>
              <option value="fadeOutRightBig">fadeOutRightBig</option>
              <option value="fadeOutUp">fadeOutUp</option>
              <option value="fadeOutUpBig">fadeOutUpBig</option>
            </optgroup>

            <optgroup label="翻转动画">
              <option value="flip">flip</option>
              <option value="flipInX">flipInX</option>
              <option value="flipInY">flipInY</option>
              <option value="flipOutX">flipOutX</option>
              <option value="flipOutY">flipOutY</option>
            </optgroup>

            <optgroup label="光速移动">
              <option value="lightSpeedIn">lightSpeedIn</option>
              <option value="lightSpeedOut">lightSpeedOut</option>
            </optgroup>

            <optgroup label="旋转进场">
              <option value="rotateIn">rotateIn</option>
              <option value="rotateInDownLeft">rotateInDownLeft</option>
              <option value="rotateInDownRight">rotateInDownRight</option>
              <option value="rotateInUpLeft">rotateInUpLeft</option>
              <option value="rotateInUpRight">rotateInUpRight</option>
            </optgroup>

            <optgroup label="旋转出场">
              <option value="rotateOut">rotateOut</option>
              <option value="rotateOutDownLeft">rotateOutDownLeft</option>
              <option value="rotateOutDownRight">rotateOutDownRight</option>
              <option value="rotateOutUpLeft">rotateOutUpLeft</option>
              <option value="rotateOutUpRight">rotateOutUpRight</option>
            </optgroup>

            <optgroup label="切入进场">
              <option value="slideInUp">slideInUp</option>
              <option value="slideInDown">slideInDown</option>
              <option value="slideInLeft">slideInLeft</option>
              <option value="slideInRight">slideInRight</option>

            </optgroup>
            <optgroup label="切出出场">
              <option value="slideOutUp">slideOutUp</option>
              <option value="slideOutDown">slideOutDown</option>
              <option value="slideOutLeft">slideOutLeft</option>
              <option value="slideOutRight">slideOutRight</option>
              
            </optgroup>
            
            <optgroup label="缩放进场">
              <option value="zoomIn">zoomIn</option>
              <option value="zoomInDown">zoomInDown</option>
              <option value="zoomInLeft">zoomInLeft</option>
              <option value="zoomInRight">zoomInRight</option>
              <option value="zoomInUp">zoomInUp</option>
            </optgroup>
            
            <optgroup label="缩放出场">
              <option value="zoomOut">zoomOut</option>
              <option value="zoomOutDown">zoomOutDown</option>
              <option value="zoomOutLeft">zoomOutLeft</option>
              <option value="zoomOutRight">zoomOutRight</option>
              <option value="zoomOutUp">zoomOutUp</option>
            </optgroup>

            <optgroup label="特殊动画">
              <option value="hinge">hinge</option>
              <option value="rollIn">rollIn</option>
              <option value="rollOut">rollOut</option>
            </optgroup>
          </select>
        </div>
        <label for="animateDelay" class="col-sm-2 control-label text-center" style="line-height:30px;">延迟</label>
        <div class="col-sm-4">
          <div class="input-group input-group-sm">
            <input type="text" id="animateDelay" class="form-control" v-model="define.anim_delay">
            <span class="input-group-addon">秒</span>
          </div>
        </div>
      </template>
      <!-- end 动画模版 -->
      <!-- start 点击模版 -->
      <template v-if="define.evt_enabled">
        <div class="col-sm-12" style="line-height:30px;">
          <div>
            <label class="control-label text-right">点击</label>
          </div>
          <div>
            <select class="form-control input-sm" v-model="define.evt_req_url">
              <option value="">无</option>
              <option value="api/cdk/getcdkcode">领码</option>
            </select>
          </div>
          <div>
            <label class="control-label text-right">存入变量</label>
          </div>
          <div>
            <select class="form-control input-sm" v-model="define.evt_save_var">
              <option v-for="n in 10" :value="'var'+(n-1)">页面变量{{ n-1 }}</option>
            </select>
          </div>
          <div>
            <label><input type="checkbox" v-model="define.evt_be_hidden"> 并隐藏</label>
          </div>
        </div>

      </template>
      <!-- end 点击模版 -->
  </div>
</script>

<!-- preview 的模版 -->
<script type="text/x-template" id="tab-preview">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h4>输出内容</h4>
      </div>
      <div class="col-sm-6 text-right">
        <button type="button" class="btn btn-success" @click="play">
        <span class="glyphicon glyphicon-play"></span> 播放</button>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <preview-code></preview-code>
      </div>
    </div>
  </div>
</script>

<script src="<?= jscss_path('js/vue/vue'.(ENVIRONMENT == 'production'?'.min':'').'.js?v=2.1.6'); ?>"></script>
<script src="<?= jscss_path('default/js/h5runner.js'); ?>"></script>
<script src="<?= jscss_path('default/js/h5editor.js'); ?>"></script>

<?php load_view('elements/footer'); ?>