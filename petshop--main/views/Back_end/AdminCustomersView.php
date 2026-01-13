<?php
// View: Quản lý khách hàng
?>
<div class="container-fluid py-4">
  <h2 class="mb-4">👥 Quản lý khách hàng</h2>

  <div class="card">
    <div class="card-body table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Điện thoại</th>
            <th>Địa chỉ</th>
            <th class="text-end">Số đơn mua</th>
            <th class="text-end">Tổng tiền</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($data['customers'])): ?>
            <?php $i=0; foreach ($data['customers'] as $c): $i++; ?>
              <tr>
                <td><?= $i ?></td>
                <td><?= htmlspecialchars($c['fullname'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($c['email'] ?? '') ?></td>
                <td><?= htmlspecialchars($c['phone'] ?? '') ?></td>
                <td><?= htmlspecialchars($c['address'] ?? '') ?></td>
                <td class="text-end">
                  <?= number_format((int)($c['order_count'] ?? 0)) ?>
                </td>
                <td class="text-end">
                  <?= number_format((float)($c['revenue'] ?? 0), 0, ',', '.') ?>₫
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center text-muted">Chưa có dữ liệu khách hàng.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
