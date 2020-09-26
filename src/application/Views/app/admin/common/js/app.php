    <script nonce="<?= getCspNonce() ?>">
    (() => {
        "use strict";

        function init() {
            const configs = {
                context:     document.querySelector("#j-ar"),
                filterBy:    <?= json_encode($filter['filter']['by'] ?? []) ?>,
                filterVal:   <?= json_encode($filter['filter']['val'] ?? []) ?>,
                searchBy:    <?= json_encode($filter['search']['by'] ?? []) ?>,
                searchVal:   <?= json_encode($filter['search']['val'] ?? []) ?>,
                currentPage: <?= $filter['current_page'] ?>,
                listCount:   <?= $filter['count'] ?>,
                orderBy:     "<?= $filter['order']['order'] ?? '' ?>",
                sortBy:      <?= (int)(($filter['order']['sort'] ?? '') === 'DESC') ?>,
                url:         "<?= $pageUrl; ?>"
            };

            (new GApp()).init(configs);
        }

        (window._jq = window._jq || []).push(init);
    })();
    </script>

    <script type="text/x-async-js" data-src="js/form.js" data-type="module" class="j-ajs"></script>
    <script type="text/x-async-js" data-src="js/app.js" data-type="module" class="j-ajs"></script>
