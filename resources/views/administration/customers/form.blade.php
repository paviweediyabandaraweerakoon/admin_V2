@if($editable)
    <input type="hidden" name="id" class="id" value="{{ $customer->id ?? '' }}">
@endif

<div class="form-group">
    <label>Company Name <span class="text-danger">*</span></label>
    <input type="text" name="company_name" class="form-control company_name" 
           value="{{ $customer->company_name ?? old('company_name') }}" required>
</div>

<div class="form-group">
    <label>Phone</label>
    <input type="text" name="phone" class="form-control phone" 
           value="{{ $customer->phone ?? old('phone') }}">
</div>

<div class="form-group">
    <label>Country</label>
    <input type="text" name="country" class="form-control country" 
           value="{{ $customer->country ?? old('country') }}">
</div>

<div class="form-group">
    <label>Status</label>
    <select name="status" class="form-control status">
        <option value="1" {{ old('status', $customer->status ?? '') == 1 ? 'selected' : '' }}>Active</option>
        <option value="0" {{ old('status', $customer->status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
    </select>
</div>