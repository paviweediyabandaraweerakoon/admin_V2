<div class="form-group">
    @if (isset($editable) && $editable)
        <input type="hidden" name="id" class="id">
    @endif
    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Level</label>
        </div>
        <div class="col-md-9">
            <select name="level" id="level" class="form-control level" placeholder="Select a menu" required>
                <option value="" selected disabled> Select a level</option>
                @foreach($levels as $value => $label)
                    <option value="{{ $value }}">
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mb-1">
        <div class="col-md-3 text-right mt-2">
            <label>Name</label>
        </div>
        <div class="col-md-9">
            <input type="text" name='name' class="form-control name" required>
        </div>
    </div>

        <div class="row">
            <div class="col-md-3 text-right mt-2">
                <label>Permissions</label>
            </div>
            <div class="col-md-9 ml-2 mt-5">
                @foreach(\App\Models\Menu::whereIsRoot()->get() as $root)
                    <div class="super ml-5">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="checkbox" class="mr-1 superParentCheckBox">
                                <label class="font-weight-bold text-right">{{$root->title}}</label>
                                @if(!empty($root->url))
                                    <div class="col-md-12 ml-3 main-parent">
                                        @foreach ($root->permissions()->get() as $permission)
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                            class="mr-1 parentCheckBox"><label class="font-weight-bold">{{ $permission->name }}</label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            @foreach($root->children()->get() as $child)
                                <div class="col-md-5 ml-3 main-parent">
                                    <input type="checkbox" class="mr-1 parentCheckBox"><label
                                        class="font-weight-bold">{{$child->title}}</label>
                                    <div class="col-md-12">
                                        <ul>
                                            @foreach($child->permissions()->get() as $permission)
                                                <li class="text-nowrap">
                                                    <input type="checkbox" name="permissions[]" value="{{$permission->name}}" class="mr-1 childCheckBox">{{$permission->name}}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
</div>
