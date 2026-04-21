@if($editable)
<input type="hidden" name="id" class="id">
@endif

<div class="form-group">
    {{-- Select Customer Row --}}
    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Select Customer <span class="text-danger">*</span></label>
        </div>
        <div class="col-md-9">
            <select name="customer_id" class="form-control select2 customer_id" required>
                <option value="">-- Select Customer --</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->company_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Project Name Row --}}
    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Project Name <span class="text-danger">*</span></label>
        </div>
        <div class="col-md-9">
            <input type="text" name="project_name" class="form-control project_name" required maxlength="128">
        </div>
    </div>

    {{-- Initial Value Row --}}
    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Initial Value (LKR)</label>
        </div>
        <div class="col-md-9">
            <input type="number" step="0.01" name="initial_value" class="form-control initial_value" value="0.00">
        </div>
    </div>

    {{-- Status Row --}}
    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Status</label>
        </div>
        <div class="col-md-9">
            <select name="status" class="form-control status">
                @php $currentStatus = old('status', $project->status ?? ''); @endphp
                <option value="active" {{ $currentStatus == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $currentStatus == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="completed" {{ $currentStatus == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="on-hold" {{ $currentStatus == 'on-hold' ? 'selected' : '' }}>On Hold</option>
            </select>
        </div>
    </div>

    {{-- AMC Percentage Row --}}
    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>AMC Percentage (%)</label>
        </div>
        <div class="col-md-9">
            <input type="number" step="0.01" name="amc_percentage" class="form-control amc_percentage">
        </div>
    </div>

    {{-- AMC Duration Row --}}
    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>AMC Duration (Months)</label>
        </div>
        <div class="col-md-9">
            <input type="number" name="amc_durations_month" class="form-control amc_durations_month">
        </div>
    </div>

    {{-- Launch Date Row --}}
    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Launch Date</label>
        </div>
        <div class="col-md-9">
            <input type="date" name="launch_date" class="form-control launch_date">
        </div>
    </div>

    {{-- Description Row --}}
    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Description</label>
        </div>
        <div class="col-md-9">
            <textarea name="description" class="form-control description" rows="2">{{ old('description', $project->description ?? '') }}</textarea>
        </div>
    </div>
</div>