<fieldset class="node form-group">
    <legend class="bold mg5 label">Create custom site property</legend>
    <form method="POST" action="./?q=property/create">
        <input type="text" name="variable_name" />
        <input type="text" name="variable_value" />
        <select name="variable_type">
            <option>number</option>
            <option selected="selected">text</option>
            <option>enumerate</option>
        </select>
        <input type="submit" value="Create" />
    </form>
</fieldset>