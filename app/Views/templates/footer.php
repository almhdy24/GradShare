<footer class="bg-light text-center text-lg-start mt-2">
  <div class="text-center p-3">
    <span>Created using the following technologies:</span>
    <br>
    <span>
      <a href="https://github.com/almhdy24/simy-framework" target="_blank">
        <i class="fab fa-php"></i> Simy Framework
      </a> | 
      <a href="https://github.com/almhdy24/JsonShelter" target="_blank">
        <i class="fab fa-php"></i> JsonShelter
      </a> | 
      <a href="https://github.com/almhdy24/async-file-uploader" target="_blank">
        <i class="fab fa-php"></i> AsyncFileUploader
      </a>
    </span>
    <br>
    <span>Developed by <a href="https://github.com/almhdy24" target="_blank">Elmahdi Abdallh</a></span>
    <br>
    <small>&copy; <?= date(
      "Y"
    ) ?> GradShare. All Rights Reserved.</small> <!-- Current year placeholder for copyright -->
  </div>
</footer>
<script src="<?= $this->env("base_url") ?>assets/js/bootstrap.min.js"></script>
</body>
</html>