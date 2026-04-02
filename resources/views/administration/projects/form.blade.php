@if($editable)
    <input type="hidden" name="id" class="id">
@endif

<div class="row">
    <div class="col-md-12 form-group">
        <label>Select Customer <span class="text-danger">*</span></label>
        <select name="customer_id" class="form-control select2 customer_id" required>
            <option value="">-- Select Customer --</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->company_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-12 form-group">
        <label>Project Name <span class="text-danger">*</span></label>
        <input type="text" name="project_name" class="form-control project_name" required maxlength="128">
    </div>

    <div class="col-md-6 form-group">
        <label>Initial Value (LKR)</label>
        <input type="number" step="0.01" name="initial_value" class="form-control initial_value" value="0.00">
    </div>

    <div class="col-md-6 form-group">
        <label>Status</label>
        <select name="status" class="form-control status">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="completed">Completed</option>
            <option value="on-hold">On Hold</option>
        </select>
    </div>

    <div class="col-md-6 form-group">
        <label>AMC Percentage (%)</label>
        <input type="number" step="0.01" name="amc_percentage" class="form-control amc_percentage">
    </div>

    <div class="col-md-6 form-group">
        <label>AMC Duration (Months)</label>
        <input type="number" name="amc_durations_month" class="form-control amc_durations_month">
    </div>

    <div class="col-md-6 form-group">
        <label>Launch Date</label>
        <input type="date" name="launch_date" class="form-control launch_date">
    </div>


    <div class="col-md-12 form-group">
        <label>Description</label>
        <textarea name="description" class="form-control description" rows="2"></textarea>
    </div>
</div>