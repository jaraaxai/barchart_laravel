@extends('layout')

@section('content')

<form action="quotes/search" method="POST">
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
      <td>{{ $quotes->symbol }}</td>
      <td>{{ $quotes->name }}</td>
      <td class="bar">{{ $quotes->last_price }}</td>
      <td class="bar">
        @if($quotes->prichange == 0)
          unch
        @else
          {{ $quotes->prichange }}
        @ endif
      </td>
      <td class="bar">
        @if($quotes->pctchange == 0)
          unch
        @else
          {{ $quotes->pctchange }}%
        @endif
      </td>
      <td class="bar">{{ $quotes->volume }}</td>
      <td class="bar">{{ date('m/d/Y', strtotime($quotes->tradetime)) }}</td>
      <td class="gol">
        <a href="#" onclick="remove(this, {{ $quotes->id }});">
        <img src="/images/icons/close.png">  
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


<script type="text/javascript">
  $(document).ready(function() 
    { 
        $("#myTable").tablesorter({ headers: { 7: {sorter: false} } });
    } 
  ); 

  function remove(_this, _id){
    $.ajax({
        type: 'GET',
        url: '/barchart.php/quotes/delete/id/' + _id,
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