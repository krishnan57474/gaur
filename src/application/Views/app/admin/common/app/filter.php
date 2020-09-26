    <div data-jitem="ufilters" class="form-row d-none">
        <div class="col-sm-6 col-md-3 mb-2">
            <div class="mb-1">
                <select class="form-control" data-jitem="filterby">
                    <option value="">Filter by</option>
                    <?php foreach ($filterConfig->filterFields as $k => $v): ?>
                    <option value="<?= $k ?>"><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-1">
                <select class="form-control" data-jitem="filterval">
                    <option value="">Choose</option>
                    <?php foreach ($filterConfig->filterValues as $fk => $fv): ?>
                    <?php foreach ($fv as $k => $v): ?>
                    <option class="d-none" data-item="<?= $fk ?>" value="<?= $k ?>"><?= $v ?></option>
                    <?php endforeach; ?>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-sm-6 col-md-3 mb-2">
            <div class="mb-1">
                <select class="form-control" data-jitem="searchby">
                    <option value="">Search by</option>
                    <?php foreach ($filterConfig->searchFields as $k => $v): ?>
                    <option value="<?= $k ?>"><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-1">
                <input class="form-control" data-jitem="searchval" type="text" placeholder="keyword">
            </div>
        </div>
        <div class="col-sm-6 col-md-3 mb-2">
            <div class="mb-1">
                <select class="form-control" data-jitem="orderby">
                    <option value="">Order by</option>
                    <?php foreach ($filterConfig->orderFields as $k => $v): ?>
                    <option value="<?= $k ?>"><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-1">
                <select class="form-control" data-jitem="sortby">
                    <option value="">Sort by</option>
                    <option value="0">ASC</option>
                    <option value="1">DESC</option>
                </select>
            </div>
        </div>
        <div class="col-sm-6 col-md-3 mb-2">
            <div class="row no-gutters">
                <div class="col col-sm-12 mb-1 mr-1">
                    <button data-action="search" type="button" class="btn btn-block btn-success">
                        <span class="fas fa-search"></span>
                        Search
                    </button>
                </div>
                <div class="col col-sm-12 mb-1">
                    <button data-action="reset" type="button" class="btn btn-block btn-danger">
                        <span class="fas fa-times"></span>
                        Clear
                    </button>
                </div>
            </div>
        </div>
    </div>
