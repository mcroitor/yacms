<fieldset class="node form-group">
    <legend class="bold mg5 label">Create custom site property</legend>
    <form method="POST" action="./?q=property/create">
        <table>
            <tr><td>variable name</td><td><input type="text" name="variable_name" class="form-check-input" /></td></tr>
            <tr><td>variable value</td><td><input type="text" name="variable_value" class="form-check-input" /></td></tr>
            <tr><td>variable value</td><td><select name="variable_value" class="form-check-input">
                        <option>number</option>
                        <option selected="selected">text</option>
                        <option>enumerate</option>
                    </select></td></tr>
        </table>
        <input type="submit" value="Create" class="form-button-submit" />
    </form>
</fieldset>