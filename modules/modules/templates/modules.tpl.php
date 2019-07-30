<fieldset class="node">
    <legend class="bold mg5">Manage Modules</legend>
    <h2>Installed Modules</h2>
    <!-- modules-list -->
    <h2>Upload new module</h2>
    <form method="post" action="./?q=module/install" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
        <input type="file" id="module" name="module" />
        <input type="submit" value="Upload module" />
    </form>
</fieldset>
