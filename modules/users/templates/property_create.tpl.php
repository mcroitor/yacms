<fieldset class="node form-group">
    <legend class="bold mg5 label">Create custom site property</legend>
    <form method="POST" action="./?q=property/create">
        <table>
            <tr><td>variable name</td><td><input type="text" name="variable_name" /></td></tr>
            <tr><td>variable value</td><td><input type="text" name="variable_value" /></td></tr>
            <tr><td>variable type</td><td><select name="variable_type">
                        <option>number</option>
                        <option selected="selected">text</option>
                        <option>enumerate</option>
                    </select></td></tr>
        </table>
        <input type="submit" value="Create" />
    </form>
</fieldset>