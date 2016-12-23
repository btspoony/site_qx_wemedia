<?php load_view('elements/header'); ?>
<style>
#app {
  position: absolute;
  height: 100%;
  width: 100%;
  overflow: hidden;
}
#app div{ position: absolute; }
</style>
<body>
<div id="app"></div>

<script src="<?= jscss_path('js/vue/vue.min.js?v=2.1.6'); ?>"></script>
<script src="<?= jscss_path('default/js/h5runner.js?v=0.1.0'); ?>"></script>
<script type="text/javascript">
    var vm = new Vue({
        el: '#app',
        data: {
            pageData: <?=$page_data?>
        },
        render: function (createElement) {
            var dataDefine = { attrs: { id: 'app' } };

            var h5app = createElement(VueH5App,{
                ref: 'h5app',
                props: {
                    page: this.pageData,
                    editor_mode: false,
                    production: true,
                }
            });
            // Render
            return createElement('div', dataDefine, [ h5app ] );
        }
    });
</script>
<?php load_view('elements/footer'); ?>