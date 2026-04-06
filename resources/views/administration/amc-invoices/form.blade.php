@if($editable)
    <input type="hidden" name="id" class="id">
@endif

<div class="row">
    <div class="col-md-12 form-group">
        <label>Select Project <span class="text-danger">*</span></label>
        <select name="project_id" class="form-control select2 project_id" required>
            <option value="">-- Select Project --</option>
            @foreach($projects as $project)
                <option value="{{ $project->id }}">{{ $project->project_name }} ({{ $project->customer?->company_name ?? 'N/A' }})</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-12 form-group">
        <label>Description</label>
        <textarea name="description" class="form-control description" rows="2"></textarea>
    </div>

    <div class="col-md-6 form-group">
        <label>Invoice Date</label>
        <input type="date" name="invoice_date" class="form-control invoice_date" value="{{ now()->format('Y-m-d') }}">
    </div>

    <div class="col-md-6 form-group">
        <label>Due Date</label>
        <input type="date" name="due_date" class="form-control due_date" value="{{ now()->addDays(14)->format('Y-m-d') }}">
    </div>
</div>