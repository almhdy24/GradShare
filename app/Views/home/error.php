<div class="mt-4">
  <?php if (!empty($message)): ?>
    <div class="alert alert-info" role="alert">
      <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
    </div>
  <?php endif; ?>
</div>