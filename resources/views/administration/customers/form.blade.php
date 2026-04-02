@if($editable)
    <input type="hidden" name="id" class="id">
@endif

<div class="form-group">
    <label>Company Name</label>
    <input type="text" name="company_name" class="form-control company_name" required>
</div>

<div class="form-group">
    <label>Phone</label>
    <input type="text" name="phone" class="form-control phone">
</div>

<div class="form-group">
    <label>Country</label>
    <input type="text" name="country" class="form-control country">
</div>

<div class="form-group">
    <label>Status</label>
    <select name="status" class="form-control status">
        <option value="1">Active</option>
        <option value="0">Inactive</option>
    </select>
</div>