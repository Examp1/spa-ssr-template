<div style="display: flex;justify-content: space-between;">
    <h4>{{ __('Permissions') }}</h4>
    <div>
        <span class="btn btn-success text-white selected-all-btn">{{ __('Select all') }}</span>
        <span class="btn btn-danger text-white unselected-all-btn">{{ __('Unselect all') }}</span>
    </div>
</div>

<div class="tree-checkbox">
    <ul>
        @foreach ($groups as $groupId => $group)
            <li data-group_id="{{ $groupId }}" class="open">
                <span class="fa fa-angle-right arrow-element"></span>
                <input type="checkbox" id="group_id_{{ $groupId }}" class="checkbox-section">
                <label for="group_id_{{ $groupId }}">{{ $group }}</label>

                <ul>
                    @foreach ($permissions as $permission)
                        @if ($permission->group_id === $groupId)
                            <li data-permission="{{ $permission->name }}">
                                <input type="checkbox" value="{{ $permission->id }}" class="checkbox-element"
                                    id="{{ $groupId }}__{{ $permission->id }}" name="permissions[]"
                                    @if (in_array($permission->id, $permissionsSelectedIds)) checked @endif>
                                <label
                                    for="{{ $groupId }}__{{ $permission->id }}">{{ $permission->name }}</label>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</div>

@push('styles')
    <style>
        .tree-checkbox ul {
            list-style: none;
        }

        .tree-checkbox ul li input[type="checkbox"] {
            cursor: pointer;
        }

        .tree-checkbox label {
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            cursor: pointer;
        }

        .tree-checkbox ul li .arrow-element {
            color: #999999;
            display: inline-flex;
            width: 20px;
            height: 20px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .3s;
        }

        .tree-checkbox ul li.open>.arrow-element {
            transform: rotate(90deg);
            transition: all .3s;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function selected(flag) {
            $(".tree-checkbox").find("input[type='checkbox']").prop('checked', flag);
        }

        function checkAll() {
            $(".tree-checkbox ul li").each(function() {
                let e1 = $(this).find('.checkbox-section');
                let group1 = [];
                e1.siblings('ul').children('li').each(function() {
                    e1.siblings('ul').children('li').each(function() {
                        let e2 = $(this).find('.checkbox-element');
                        group1.push(e2);
                    });

                    if (group1.length) {
                        let counterTrue1 = 0;
                        for (let i = 0; i < group1.length; i++) {
                            if (group1[i].prop('checked')) {
                                counterTrue1++;
                            }
                        }

                        if (group1.length === counterTrue1) {
                            e1.prop('checked', true);
                        } else {
                            e1.prop('checked', false);
                        }
                    }
                });
            });
        }

        $(document).ready(function() {
            checkAll();

            $(".checkbox-section").on('change', function() {
                if ($(this).prop('checked')) {
                    $(this).siblings('ul').find('.checkbox-element').prop('checked', true);
                } else {
                    $(this).siblings('ul').find('.checkbox-element').prop('checked', false);
                }
            });

            $('.tree-checkbox input[type="checkbox"]').on('change', function() {
                checkAll();
            });

            $(".selected-all-btn").on('click', function() {
                selected(true);
            });

            $(".unselected-all-btn").on('click', function() {
                selected(false);
            });

            $(".arrow-element").on("click", function() {
                if ($(this).parent('li').hasClass('open')) {
                    $(this).parent('li').removeClass('open');
                    $(this).parent('li').children('ul').slideUp();
                } else {
                    $(this).parent('li').addClass('open');
                    $(this).parent('li').children('ul').slideDown();
                }
            });
        });
    </script>
@endpush
