<div class="form-group">
    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Date Range</label>
        </div>
        <div class="col-md-9">
            <input type="text" name='dateRange' class="form-control date_input validate_group" id="dateRange">
        </div>
    </div>

    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Performed On</label>
        </div>
        <div class="col-md-9">
            <select name="performed_on" id="performed_on" class="form-control validate_group" placeholder="Select a perform on">
                <option value="" selected> ALL </option>
                @foreach($performed_on as $value => $label)
                    <option value="{{ $value }}" {{ old('performed_on') == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Caused By</label>
        </div>
        <div class="col-md-9">
            <select name="caused_by" id="caused_by" class="form-control validate_group" placeholder="Select a caused by">
                <option value="" selected> ALL </option>
                @foreach($causers as $value => $label)
                    <option value="{{ $value }}" {{ old('caused_by') == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Activity</label>
        </div>
        <div class="col-md-9">
           <select name="activity" id="activity" class="form-control validate_group">
                <option value="all"      {{ old('activity') == 'all'      ? 'selected' : '' }}>All</option>
                <option value="created"  {{ old('activity') == 'created'  ? 'selected' : '' }}>Created</option>
                <option value="updated"  {{ old('activity') == 'updated'  ? 'selected' : '' }}>Updated</option>
                <option value="deleted"  {{ old('activity') == 'deleted'  ? 'selected' : '' }}>Deleted</option>
                <option value="restored" {{ old('activity') == 'restored' ? 'selected' : '' }}>Restored</option>
            </select>
        </div>
    </div>
</div>
