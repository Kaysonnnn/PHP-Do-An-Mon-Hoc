<?php require_once __DIR__ . '/../../partials/admin-header.php'; ?>

<div class="container mt-5">
  <h1>Quản lý đánh giá</h1>
  <table class="table table-bordered">
    <thead>
    <tr>
      <th>ID</th>
      <th>Sản phẩm</th>
      <th>Khách hàng</th>
      <th>Bình luận</th>
      <th>Đánh giá</th>
      <th>Trạng thái</th>
      <th>Hành động</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($comments as $comment): ?>
      <tr>
        <td><?php echo $comment['id']; ?></td>
        <td><?php echo $comment['product_name']; ?></td>
        <td><?php echo $comment['customer_name']; ?></td>
        <td><?php echo $comment['comment']; ?></td>
        <td><?php echo $comment['rating']; ?> / 5 ⭐</td>
        <td><?php echo $comment['status']; ?></td>
        <td>
          <?php if ($comment['status'] === 'pending'): ?>
            <a href="/doan/admin/comments/approve?id=<?php echo $comment['id']; ?>" class="btn btn-success">Duyệt</a>
            <a href="/doan/admin/comments/reject?id=<?php echo $comment['id']; ?>" class="btn btn-warning">Từ chối</a>
          <?php endif; ?>
          <a href="/doan/admin/comments/delete?id=<?php echo $comment['id']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa bình luận đánh giá này không?')">Xóa</a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/../../partials/admin-footer.php'; ?>
