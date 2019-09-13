<?php

$status = [
    'oi oi-x text-danger',
    'oi oi-check text-success'
];

foreach ($items as $item):
?>
<div class="g-tr" data-id="<?= $item['id'] ?>">
    <div class="g-td"><?= $item['id'] ?></div>
    <div class="g-td text-left"><?= hentities($item['username']) ?></div>
    <div class="g-td text-left"><?= hentities($item['email']) ?></div>
    <div class="g-td">
        <?php if ($item['last_visited']): ?>
        <?= date('d-m-Y h:i a', strtotime($item['last_visited'])) ?>
        <?php else: ?>
        -
        <?php endif; ?>
    </div>
    <div class="g-td">
        <button type="button" data-item="status" class="btn btn-link p-0 <?= $status[$item['status']] ?>"></button>
    </div>
    <div class="g-td">
        <a href="admin/users/edit/<?= $item['id'] ?>">
            <span class="oi oi-pencil"></span>
        </a>
    </div>
    <div class="g-td">
        <a href="admin/users/view/<?= $item['id'] ?>">
            <span class="oi oi-link-intact"></span>
        </a>
    </div>
</div>
<?php endforeach; ?>
