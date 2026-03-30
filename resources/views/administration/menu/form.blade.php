<div class="form-group">
    <div class="row">
        <div class="col-md-3 text-right mt-2">
            <label>Is Parent</label>
        </div>
        <div class="col-md-9 d-flex align-items-center">
            <input type="checkbox" class="isParent" value="1" name="is_parent" id="is_parent" onchange="change()"/>
        </div>
    </div>
    <div class="row mb-2 parentRow">
        <div class="col-md-3 text-right mt-2">
            <label>Parent</label>
        </div>
        <div class="col-md-9">
            <select name="parent_id" id="parent_id" class="form-control parent_id" placeholder="Select a parent menu" required>
                <option value="" selected disabled> Select a parent menu</option>
                @foreach($roots as $value => $label)
                    <option value="{{ $value }}">
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-3 text-right mt-2">
            <label>Title</label>
        </div>
        <div class="col-md-9">
            <input type="text" name='title' class="form-control title" required>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-3 text-right mt-2">
            <label>URL</label>
        </div>
        <div class="col-md-9">
            <input type="text" name='url' class="form-control url_route">
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 text-right mt-2">
            <label>Order</label>
        </div>
        <div class="col-md-9">
            <input type="number" name='menu_order' class="form-control menu_order" required>
        </div>
    </div>
    <div class="row mb-2 mt-2 routeRow">
        <div class="col-md-3 text-right mt-2">
            <label>Icon </label>
            <span class="text-nowrap">(Ex: fa fa-times)</span>
        </div>
        <div class="col-md-9">
            <input type="text" name='icon' class="form-control icon" >
        </div>
    </div>
    <div class="row mb-2 chckobox permissionRow">
        <div class="col-md-3 text-right mt-2">
            <label>Permissions</label>
        </div>
        <div class="col-md-9 mt-2">
            <div class="">
                <label>
                    <input type="checkbox" class="index" name="permissions[]" value="index">
                    index
                </label>
            </div>
            <div class="">
                <label>
                    <input type="checkbox" class="create" name="permissions[]" value="create">
                    create
                </label>
            </div>
            <div class="">
                <label>
                    <input type="checkbox" class="edit" name="permissions[]" value="edit">
                    edit
                </label>
            </div>
            <div class="">
                <label>
                    <input type="checkbox" class="delete" name="permissions[]" value="delete">
                    delete
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 text-right mt-2">
            <label>Permissions (comma separated)</label>
        </div>
        <div class="col-md-9 mt-2">
            <input type="text" name="permission_tags" class="tgs permission_tags" data-role="tagsinput">
        </div>
    </div>
</div>
