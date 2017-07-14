@extends('layout')

@section('content')

<div class="form-style-10">
<p>{{ $title }}</p>
@if ($errors->any())
    <div class="error_list">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(isset($quote))
    {{ Form::model($quote, ['route' => ['quotes.update', $quote->id], 'method' => 'PATCH']) }}
@else
    {{ Form::open(['route' => 'quotes.store']) }}
@endif

	{{ Form::label('symbol', 'Symbol') }}
	@if(isset($quote))
	    {{ Form::text('symbol', Input::old('symbol')) }}
	@else
	    {{ Form::text('symbol', $keyword) }}
	@endif
    
    {{ Form::label('name', 'Symbol Name') }}
    {{ Form::text('name', Input::old('name')) }}

    {{ Form::label('last_price', 'Last Price') }}
    {{ Form::text('last_price', Input::old('last_price')) }}

    {{ Form::label('prichange', 'Change') }}
    {{ Form::text('prichange', Input::old('prichange')) }}

    {{ Form::label('pctchange', '% Change') }}
    {{ Form::text('pctchange', Input::old('pctchange')) }}

    {{ Form::label('volume', 'Volume') }}
    {{ Form::text('volume', Input::old('volume')) }}
    
    {{ Form::submit('Save', ['name' => 'submit']) }}
    <a href="/">return</a>
{{ Form::close() }}
</div>
@endsection
