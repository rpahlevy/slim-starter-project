<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<?php if (strpos(\Riri\Model\Utils::getURL(), "localhost") !== false): ?>
    <script src="/js/vue.js"></script>
<?php else: ?>
    <script src="/js/vue.min.js"></script>
<?php endif; ?>

<script src="/js/vue-lazyload.js"></script>
<script>
    // don't forget set :key on v-for
    Vue.use(VueLazyload, {
        preLoad: 1.25,
        throttleWait: 100,
        loading: '/img/spinner.svg',
        dispatchEvent: true,
    });
</script>

<!-- <script src="/js/sweetalert.min.js"></script> -->
<script src="/js/toastr.min.js"></script>
<script>
    toastr.options.closeButton = true;
    // toastr.options.progressBar = true;
    toastr.options.positionClass = 'toast-top-right';
</script>

<!-- <script src="/js/tools.js"></script> -->
