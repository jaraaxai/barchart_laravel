@extends('layout')

@section('content')
<hr/>
@if($errors->any())

<div class="error_list">{{$errors->first()}}</div>
<hr/>
@endif
<form action="/search" method="POST">
  {{ csrf_field() }}
  <input class="addsymbol" type="text" placeholder="Enter a symbol" name="keyword">
  <input class="add-btn" type="submit" value="Add symbol">
</form>
<table id="myTable" class="tablesorter">
  <thead>
    <tr>
      <th>Symbol</th>
      <th>Symbol Name</th>
      <th>Last Price</th>
      <th>Change</th>
      <th>%Change</th>
      <th>Volume</th>
      <th>Time</th>
      <th>#</th>
    </tr>
  </thead>
  <tbody id="tableBody">
  @if(count($quotes) > 0)
    @foreach ($quotes as $quote)
    <tr>
      <td>{{ $quote->symbol }}</td>
      <td>{{ $quote->name }}</td>
      <td class="bar">{{ $quote->last_price }}</td>
      <td class="bar">
        @if($quote->prichange == 0)
          unch
        @else
          {{ $quote->prichange }}
        @endif
      </td>
      <td class="bar">
        @if($quote->pctchange == 0)
          unch
        @else
          {{ $quote->pctchange }}%
        @endif
      </td>
      <td class="bar">{{ $quote->volume }}</td>
      <td class="bar">{{ date('m/d/Y', strtotime($quote->tradetime)) }}</td>
      <td class="gol">
        <a href="#" onclick="remove(this, {{ $quote->id }});">
        <img src="/images/close.png">  
        </a>
      </td>
    </tr>
    @endforeach
  @else
    <tr>
      <td colspan="8">
        <div id="messages">
          <div class="error">There are no symbols in your watchslist, please add one</div>
        </div>
      </td>
    </tr>
  @endif
  </tbody>
</table>

<hr/>

<script type="text/javascript">
  $(document).ready(function() 
    { 
        $("#myTable").tablesorter({ headers: { 7: {sorter: false} } });
    } 
  ); 

  function remove(_this, _id){
    $.ajax({
        data: {  
           "_token": "{{ csrf_token() }}"
        },
        type: 'DELETE',
        url: '/delete/' + _id,
        success: function(data)
        {
          $( _this ).parent().parent().remove();
          var x = document.getElementById("myTable").rows.length;
          if( x == 1 ){
            $('#tableBody').append('<tr><td colspan="8"><div id="messages"><div class="error">There are no symbols in your watchslist, please add one</div></div></td></tr>');
          }
        }
      });
    
  }


</script>

@endsection