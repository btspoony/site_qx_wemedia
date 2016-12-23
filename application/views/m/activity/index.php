<?php load_view('elements/header'); ?>
<style>
#app {
  position: absolute;
  height: 100%;
  width: 100%;
}
#app div{ position: absolute; }
</style>
<body>
<div id="app"></div>

<script src="<?= jscss_path('js/vue/vue.min.js?v=2.1.6'); ?>"></script>
<script src="<?= jscss_path('default/js/h5runner.js'); ?>"></script>
<script type="text/javascript">
    var vm = new Vue({
        el: '#app',
        data: {
            pageData: <?=$page_data?>
        },
        render: function (createElement) {
            let dataDefine = { attrs: { id: 'app' } };

            let h5app = createElement('h5app',{
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