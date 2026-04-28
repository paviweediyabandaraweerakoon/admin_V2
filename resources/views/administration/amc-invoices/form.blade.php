@if($editable)
    <input type="hidden" name="id" class="id">
@endif

<div class="form-group">
    {{-- Project Selection --}}
    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Project Name</label>
        </div>
        <div class="col-md-9">
            <input type="text" class="form-control project_name_display" 
                   value="{{ $invoice->project?->project_name ?? '' }}" readonly>
            <input type="hidden" name="project_id" class="project_id" 
                   value="{{ $invoice->project_id ?? '' }}">
        </div>
    </div>

    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Description</label>
        </div>
        <div class="col-md-9">
            <textarea name="description" class="form-control description" rows="2">{{ $invoice->description ?? '' }}</textarea>
        </div>
    </div>

    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Invoice Date</label>
        </div>
        <div class="col-md-9">
            <input type="date" name="invoice_date" class="form-control invoice_date"
                   value="{{ isset($invoice) && $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : now()->format('Y-m-d') }}">
        </div>
    </div>

    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Due Date</label>
        </div>
        <div class="col-md-9">
            <input type="date" name="due_date" class="form-control due_date"
                   value="{{ isset($invoice) && $invoice->due_date ? $invoice->due_date->format('Y-m-d') : now()->addDays(14)->format('Y-m-d') }}">
        </div>
    </div>
</div>