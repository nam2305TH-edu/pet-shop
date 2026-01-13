<?php
// Không kiểm tra role, hiển thị tất cả button cho mọi người
$baseUrl = APP_URL . '/ProductType';
$productCounts = $data["productCounts"] ?? [];
$searchTerm = $data['searchTerm'] ?? '';
$productList = $data['productList'] ?? [];
$chunks = array_chunk($productList, 5);
?>
<div class="container mt-5">
    <h2 class="mb-4">📦 Quản lý danh mục loại sản phẩm</h2>
    <?php if (!empty($_SESSION['pt_error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['pt_error']) ?></div>
        <?php unset($_SESSION['pt_error']); endif; ?>
    <?php if (!empty($_SESSION['pt_success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['pt_success']) ?></div>
        <?php unset($_SESSION['pt_success']); endif; ?>
    <form class="row mb-3 g-2" action="<?= $baseUrl ?>" method="get">
        <div class="col-md-3">
            <input
                type="text"
                class="form-control"
                name="search_maLoai"
                placeholder="Tìm theo mã loại SP"
                value="<?= htmlspecialchars($searchTerm) ?>"
            />
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-primary w-100" type="submit">🔍 Tìm kiếm</button>
        </div>
        <div class="col-md-2">
            <a class="btn btn-outline-secondary w-100" href="<?= $baseUrl ?>">🧹 Xóa lọc</a>
        </div>
    </form>

    <!-- Form luôn hiển thị để có thể thêm mới kể cả khi danh sách trống -->
    <table class="table table-bordered table-hover">
        <tr>
            <td colspan="6">
                <?php
                // Nếu tồn tại biến $data["editItem"] thì đang ở chế độ sửa
                $isEdit = isset($data["editItem"]);
                $edit = $isEdit ? $data["editItem"] : null;
                ?>
                <form
                    action="<?= $isEdit ? ($baseUrl . "/update/" . urlencode($edit["maLoaiSP"])) : ($baseUrl . "/create") ?>"
                    method="post"
                    class="bg-light p-3 rounded shadow-sm">
                    <div class="row align-items-end gx-3 gy-2">
                        <!-- Mã loại sản phẩm -->
                        <div class="col-md-2">
                            <label for="txt_maloaisp" class="form-label">Mã loại SP</label>
                            <input type="text" name="txt_maloaisp" id="txt_maloaisp" class="form-control"
                                required value="<?= $isEdit ? htmlspecialchars($edit["maLoaiSP"]) : '' ?>" />
                        </div>

                        <!-- Tên loại sản phẩm -->
                        <div class="col-md-2">
                            <label for="txt_tenloaisp" class="form-label">Tên loại SP</label>
                            <input type="text"
                                name="txt_tenloaisp"
                                id="txt_tenloaisp"
                                class="form-control"
                                value="<?= $isEdit ? htmlspecialchars($edit["tenLoaiSP"]) : '' ?>" />
                        </div>

                        <!-- Mô tả loại sản phẩm -->
                        <div class="col-md-2">
                            <label for="txt_motaloaisp" class="form-label">Mô tả</label>
                            <input type="text"
                                name="txt_motaloaisp"
                                id="txt_motaloaisp"
                                class="form-control"
                                value="<?= $isEdit ? htmlspecialchars($edit["moTaLoaiSP"]) : '' ?>" />
                        </div>

                        <!-- Email (hiển thị khi edit, ẩn khi tạo mới) -->
                        <?php if ($isEdit): ?>
                          
                        <?php endif; ?>

                        <!-- Nút hành động -->
                        <div class="col-md-<?= $isEdit ? '2' : '3' ?>">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-<?= $isEdit ? 'warning' : 'primary' ?>">
                                    💾 <?= $isEdit ? "Cập nhật" : "Thêm mới" ?>
                                </button>
                                <!-- Nút Huỷ -->
                                <?php if ($isEdit): ?>
                                    <a href="<?= $baseUrl ?>" class="btn btn-secondary">
                                        🔁 Huỷ
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </form>
            </td>
        </tr>
    </table>

    <?php if (!empty($chunks)): ?>
        <ul class="nav nav-tabs mb-3" id="productTypeTabs" role="tablist">
            <?php foreach ($chunks as $idx => $_): ?>
                <?php $tabId = 'tab-' . $idx; ?>
                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link <?= $idx === 0 ? 'active' : '' ?>"
                        id="<?= $tabId ?>-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#<?= $tabId ?>"
                        type="button"
                        role="tab"
                        aria-controls="<?= $tabId ?>"
                        aria-selected="<?= $idx === 0 ? 'true' : 'false' ?>">
                        Danh sách <?= $idx + 1 ?>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="tab-content" id="productTypeTabsContent">
            <?php $globalIndex = 0; ?>
            <?php foreach ($chunks as $idx => $chunk): ?>
                <?php $tabId = 'tab-' . $idx; ?>
                <div
                    class="tab-pane fade <?= $idx === 0 ? 'show active' : '' ?>"
                    id="<?= $tabId ?>"
                    role="tabpanel"
                    aria-labelledby="<?= $tabId ?>-tab"
                >
                    <table class="table table-bordered table-hover">
                        <tr>
                            <th>STT</th>
                            <th>Mã loại</th>
                            <th>Tên loại SP</th>
                            <th>Mô tả</th>
                            <th>Hành động</th>
                        </tr>
                        <?php foreach ($chunk as $v): $globalIndex++; ?>
                            <tr>
                                <td><?= $globalIndex ?></td>
                                <td><?= htmlspecialchars($v["maLoaiSP"]) ?></td>
                                <td><?= htmlspecialchars($v["tenLoaiSP"]) ?> </td>
                                <td><?= htmlspecialchars($v["moTaLoaiSP"]) ?></td>
                                <td>
                                    <a href="<?= $baseUrl ?>/edit/<?= urlencode($v["maLoaiSP"]) ?>" class="btn btn-warning btn-sm">✏️ Sửa</a>
                                    <a href="<?= $baseUrl ?>/deleteProductType/<?= urlencode($v["maLoaiSP"]) ?>"
                                       class="btn btn-danger btn-sm btn-delete-product-type"
                                       data-product-count="<?= (int)($productCounts[$v["maLoaiSP"]] ?? 0) ?>">🗑️ Xoá</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-light border text-center text-muted">
            <?= $searchTerm === ''
                ? 'Chưa có danh mục loại sản phẩm nào. Hãy thêm mới ở form phía trên.'
                : 'Không tìm thấy kết quả phù hợp với từ khóa tìm kiếm.';
            ?>
        </div>
    <?php endif; ?>
</div>
<script>
    document.querySelectorAll('.btn-delete-product-type').forEach(function(btn) {
        btn.addEventListener('click', function(event) {
            var count = parseInt(btn.dataset.productCount || '0', 10);
            var message = count > 0
                ? 'Loại sản phẩm này có ' + count + ' sản phẩm. Bạn có chắc muốn xoá?'
                : 'Bạn có chắc muốn xoá loại sản phẩm này?';
            if (!confirm(message)) {
                event.preventDefault();
            }
        });
    });
</script>
