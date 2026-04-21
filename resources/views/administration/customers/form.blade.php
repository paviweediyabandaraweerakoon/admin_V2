@if($editable)
<input type="hidden" name="id" class="id">
@endif

<div class="form-group">
    
    {{-- Company Name Row --}}
    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Company Name <span class="text-danger">*</span></label>
        </div>
        <div class="col-md-9">
            <input type="text" name="company_name" class="form-control company_name" 
                   value="{{ old('company_name', $customer->company_name ?? '') }}" required>
        </div>
    </div>

    {{-- Phone Row --}}
    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Phone</label>
        </div>
        <div class="col-md-9">
            <input type="text" name="phone" class="form-control phone" 
                   value="{{ old('phone', $customer->phone ?? '') }}">
        </div>
    </div>

    {{-- Country Row --}}
    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Country</label>
        </div>
        <div class="col-md-9">
            <input type="text" name="country" class="form-control country" 
                   value="{{ old('country', $customer->country ?? '') }}">
        </div>
    </div>

    {{-- Status Row --}}
    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Status</label>
        </div>
        <div class="col-md-9">
            <select name="status" class="form-control status">
                <option value="1" {{ old('status', $customer->status ?? '') == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status', $customer->status ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
    </div>

</div>