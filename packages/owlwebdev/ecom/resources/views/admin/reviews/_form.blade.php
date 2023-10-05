@if ($action === 'create')
    <form class="form-horizontal" method="POST" action="{{ route('reviews.store') }}">
@elseif($action === 'edit')
    <form action="{{ route('reviews.update', $model->id) }}" class="form-horizontal" method="post">
        @method('PUT')
@endif
    @csrf

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-3 text-right" for="product_id">{{ __('Product') }}</label>
                        <div class="col-md-9" id="product_id">
                            <select
                                class="select2 custom-select{{ $errors->has('product_id') ? ' is-invalid' : '' }}"
                                style="width: 100%;"
                                name="product_id"
                                id="product_id">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        @if (old('product_id', $model->product_id) == $product->id) selected @endif>{{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 text-right" for="user_id">{{ __('User') }}</label>
                        <div class="col-md-9" id="user_id">
                            <select
                                class="select2 custom-select{{ $errors->has('user_id') ? ' is-invalid' : '' }}"
                                style="width: 100%;"
                                name="user_id"
                                id="user_id">
                                <option value="">---</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        @if (old('user_id', $model->user_id) == $user->id) selected @endif>{{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 text-right" for="page_rating">{{ __('Rating') }}</label>
                        <div class="col-md-9">
                            <input type="number" name="rating" value="{{ old('rating', $model->rating ?? 5) }}" min="1" max="5"
                                id="page_rating"
                                class="form-control{{ $errors->has('rating') ? ' is-invalid' : '' }}">

                            @if ($errors->has('rating'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('rating') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 text-right" for="page_author">{{ __('Name') }}</label>
                        <div class="col-md-9">
                            <input type="text" name="author" value="{{ old('author', $model->author ?? '') }}"
                                id="page_author" class="form-control{{ $errors->has('author') ? ' is-invalid' : '' }}">

                            @if ($errors->has('author'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('author') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 text-right" for="page_email">{{ __('email') }}</label>
                        <div class="col-md-9">
                            <input type="text" name="email" value="{{ old('email', $model->email ?? '') }}"
                                id="page_email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}">

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 text-right" for="page_text">{{ __('Text') }}</label>
                        <div class="col-md-9">
                            <textarea name="text" id="page_text"
                                class="{{ $errors->has('text') ? ' is-invalid' : '' }}" cols="50" rows="10">{{ old('text', $model->text ?? '') }}</textarea>

                            @if ($errors->has('text'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('text') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 text-right" for="page_created_at">{{ __('Publication date') }}</label>
                        <div class="col-md-9">
                            <input type="text" name="created_at" value="{{ old('created_at', $model->created_at ?? '') }}"
                                id="page_created_at" class="form-control{{ $errors->has('created_at') ? ' is-invalid' : '' }} date" required>

                            @if ($errors->has('created_at'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('created_at') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                            <label class="col-md-3 text-right" for="page_status">{{ __('Status') }}</label>
                            <div class="col-md-9">
                                <div class="material-switch pull-left">
                                    <input name="status" value="0" type="hidden" />
                                    <input id="someSwitchOptionSuccess" name="status" value="1" type="checkbox"
                                        {{ old('status', $model->status) ? ' checked' : '' }} />
                                    <label for="someSwitchOptionSuccess" class="label-success"></label>
                                </div>

                                @if ($errors->has('status'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                    <input type="submit" value="{{ __('Save') }}" class="btn btn-success text-white float-right">

                </div>
            </div>
        </div>
    </div>
</form>
